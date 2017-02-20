<?php ini_set('MAX_EXECUTION_TIME', -1);
defined('BASEPATH') OR exit('No direct script access allowed');

class Daily_sync extends MY_Controller
{
	public function __construct()
	{
		//parent::__construct();
		//$this->load->model('products_model');
		//die("Am in Daily_sync Controller");

		parent::__construct();

		/*if (!$this->loggedIn) {
			$this->session->set_userdata('requested_page', $this->uri->uri_string());
			$this->sma->md('login');
		}*/
		$this->load->model('db_model');
		$this->load->model('write_updates');
		$this->load->model('products_model');
	}

	
	function index($action = NULL)
	{
		$this->data['title'] = "Cloud Synchronization";
		$this->load->view($this->theme . 'daily_sync/index', $this->data);
	}

	function warehouseCl()
	{
		$warehouse = $this->products_model->getWarehouse();
		$update['warehouse_id'] = $this->get_wharehouse_from_cloud($warehouse->phone);
		echo json_encode( $update );
	}

	function synchronization()
	{
		$resp = array();
		$tables = array(
			0	=> 'sales',
			1	=> 'purchases',
			2	=> 'warehouses_products',
			3	=> 'purchase_items',
			4	=> 'quotes',
			5	=> 'quote_items',
			6	=> 'sale_items',
			7	=> 'stock_counts',
			8	=> 'suspended_bills',
			9	=> 'suspended_items',
			10	=> 'adjustments',
			11	=> 'adjustment_items',
			12	=> 'expenses',
			13	=> 'transfer_items',
			14	=> 'users',
			15	=> 'warehouses_products_variants'

			);

		for($i = 0; $i < count($tables); $i++)
		{
			$sync = $this->initiate_sync($tables[$i]);
			if($sync['status'] == "done")
			{
				$resp[$tables[$i]] = "complete";
			}
		}
		echo json_encode( $resp );
	}

	function warehouse()
	{
		$warehouse = $this->products_model->getWarehouse();

		echo json_encode( $warehouse->phone );
	}

	function initiate_sync($table)
	{
		$resp = array();
		$server_resp;
		date_default_timezone_set("Africa/Lagos");
		$start_date = date('Y-m-d H:i:s', strtotime("-1 hour"));
		$end_date = date('Y-m-d H:i:s', strtotime("now"));
		$warehouse = $this->products_model->getWarehouse();


		$column; $done = array("status" => "NULL");
		if($table == 'warehouses_products' || $table == 'users' || $table == 'warehouse_products_variants')
		{
			$column = 'created_at';
		}
		else
		{
			$column = 'date';
		}

		$total_table_updates = $this->products_model->getUpdatesCount($table);

		if($total_table_updates <= 0)
		{
			# no entries in the database concerning this table.

			# $done[$table] =  "There are no entries for table  " . $table . " and attempt will be made to backup all content of " . $table;
			$table_rows = $this->products_model->getWarehouseTableContent($table, $warehouse->id);
			if($table_rows != NULL)
			{
				foreach($table_rows as $table_row)
				{
					# $done[$table . '' . $table_row->id] = ". Backing up: " . $table . '' . $table_row->id;
					$server_resp = $this->init_cloud(get_object_vars($table_row), $table);
					if($server_resp == 0)
					{
						$update['operation'] = $table;
						$update['operation_id'] = $table_row->id;
						$update['status'] = 0;
						$update['reason'] = 'database error';
						$update['created_at'] = date('Y-m-d H:i:s');
						$update['warehouse_id'] = $warehouse->id;
						$this->write_updates->onPremLatestUpdate($update);
						$done = array("status" => "failed");
					}
					elseif($server_resp == 1)
					{
						$update['operation'] = $table;
						$update['operation_id'] = $table_row->id;
						$update['status'] = 1;
						$update['reason'] = 'good';
						$update['created_at'] = date('Y-m-d H:i:s');
						$update['warehouse_id'] = $warehouse->id;
						$this->write_updates->onPremLatestUpdate($update);


						$update['warehouse_id'] = $this->get_wharehouse_from_cloud($warehouse->phone);
						$this->init_cloud($update, 'write_updates');
						$done = array("status" => "done");
					}
				}
			}
		}
		else
		{
			# data has already been written about this particular table to be updated.
			# get failed updates and new data between the last one hour and commit to cloud storage.

			# $done[$table] = "Starting...\n";
			$failed_updates = $this->write_updates->getFailedUpdates($table);
			if($failed_updates != NULL)
			{
				# $done[$table] .=  "There are previous updates for table " . $table . " and there were ". count($failed_updates) ."failed updates during the last synchronization session.";
				foreach($failed_updates as $failed_update)
				{
					$server_resp = $this->init_cloud_update(get_object_vars($failed_update), $table);
					if($server_resp == 0)
					{
						$update['operation'] = $table;
						$update['operation_id'] = $failed_update->id;
						$update['status'] = 0;
						$update['reason'] = 'database error';
						$update['created_at'] = date('Y-m-d H:i:s');
						$update['warehouse_id'] = $warehouse->id;
						$this->write_updates->updateOnPremLatestUpdate($update);
					}
					else if($server_resp == 1)
					{
						$update['operation'] = $table;
						$update['operation_id'] = $failed_update->id;
						$update['status'] = 1;
						$update['reason'] = 'good';
						$update['created_at'] = date('Y-m-d H:i:s');
						$update['warehouse_id'] = $warehouse->id;
						$this->write_updates->updateOnPremLatestUpdate($update);

						$update['warehouse_id'] = $this->get_wharehouse_from_cloud($warehouse->phone);
						$this->init_cloud_update($update, 'write_updates');
					}
				}
			}

			# commit new update changes
			$new_updates = $this->products_model->getWarehouseProductByDate($table, $column, $warehouse->id, $start_date, $end_date);

			if($new_updates != NULL)
			{
				# $done[$table] .=  " Morever, there are ". count($new_updates)." new updates for table " . $table ;
				foreach($new_updates as $new_update)
				{
					$server_resp = $this->init_cloud(get_object_vars($new_update), $table);

					if($server_resp == 0)
					{
						$update['operation'] = $table;
						$update['operation_id'] = $new_update->id;
						$update['status'] = 0;
						$update['reason'] = 'database error';
						$update['created_at'] = date('Y-m-d H:i:s');
						$update['warehouse_id'] = $warehouse->id;
						$this->write_updates->onPremLatestUpdate($update);
						$done = array("status" => "fail");
					}
					elseif($server_resp == 1)
					{
						$update['operation'] = $table;
						$update['operation_id'] = $new_update->id;
						$update['status'] = 1;
						$update['reason'] = 'good';
						$update['created_at'] = date('Y-m-d H:i:s');
						$update['warehouse_id'] = $warehouse->id;
						$this->write_updates->onPremLatestUpdate($update);

						$update['warehouse_id'] = $this->get_wharehouse_from_cloud($warehouse->phone);
						$this->init_cloud($update, 'write_updates');
						$done = array("status" => "done");
					}
				}
			}
		}
		

		# $done = array("status" => "done");

		# header('Content-Type: application/json');
		# echo json_encode( $done );

		return $done;
	}

	function init_cloud(array $data, $table)
	{
		$status = 0;
		$cloud = $this->load->database('cloud', TRUE);
		$cloud->insert($table, $data);

		# given that data has been written to database table successfully

		$status = 1;
		return $status;
	}

	function init_cloud_update(array $data, $table)
	{
		$status = 0;
		$cloud = $this->load->database('cloud', TRUE);
		$cloud->update($table, $data);
		$status = 1;

		return $status;
	}

	function get_wharehouse_from_cloud($phone)
	{
		$cloud = $this->load->database('cloud', TRUE);
		$q = $cloud->get_where('warehouses', array('phone' => $phone));
		if($q->num_rows() > 0)
		{
			$cloud_warehouse = $q->row();
			return $cloud_warehouse->id;
		}
		return NULL;
	}
}