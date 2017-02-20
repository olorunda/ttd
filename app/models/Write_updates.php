<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Write_updates extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function writeLatestUpdate($data)
	{
		$retVal = array(
			'cloud' 	=> 0,
			'prem'		=> 0
			);
		if($this->sma->checkInternet() == "connected")
		{
    		//update cloud and prem
			if($this->db->insert('write_updates', $data))
			{
    			//cloud version successfully updated.
				$ertVal['cloud'] = 1;
			}
			if($prem->insert('write_updates', $data))
			{
    			//prem version successfully updated.
				$retVal['prem'] = 1;
			}
		}
		else
		{
    		//update premonly.	
			if($prem->insert('write_updates', $data))
			{
    			//prem version successfully updated.
				$retVal['prem'] = 1;
			}
		}
		return $retVal;
	}

	public function onPremLatestUpdate($data)
	{
		if($this->db->insert('write_updates', $data))
		{
			//write last update time to database.
			return TRUE;
		}
		return FALSE;
	}

	public function cloudLatestUpdate($data)
	{
		if($this->sma->checkInternet() == "connected")
		{
			$cloud = $this->load->database('cloud', TRUE);
			if($cloud->insert('write_updates', $data))
			{
			//write cloud last update time to the database;
				return TRUE;
			}
		}
		return FALSE;
	}

	public function getUpdatesCount($table)
	{
		$table_row_count = $this->db->where('operation', $table)->count_all('write_updates');
		# $table_row_count = $this->db->count_all('write_updates');
		return $table_row_count;
	}

	public function getLastUpdate($date)
	{
		$retVal = array();

		$q = $this->db->get_where('write_updates', array('created_at' => $date));
	}

	public function getFailedUpdates($table)
	{
		$q = $this->db->get_where('write_updates', array('status' => 0, 'operation' => $table));
        if($q->num_rows() > 0)
        {
            foreach (($q->result()) as $row) 
            {
                $data[] = $row;
            }
            return $data;
        }
        return NULL;
	}

	public function updateOnPremLatestUpdate($table)
	{
		if($this->db->update('write_updates', $data))
		{
			//write last update time to database.
			return TRUE;
		}
		return FALSE;
	}
}