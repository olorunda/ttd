<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<title><?= $title ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/sync/css/bootstrap-paper.min.css">-->
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/sync/css/bootstrap-cosmos.min.css">

	<style>
		body {
			background-color: #ececec;
		}
		#sync-box {
			border: 1px solid #ccc;
			border-top: 20px solid #ececec;
			border-radius: 0px;
			height: 320px;
			-webkit-transition: background-color .5s, border-top 1s; /* For Safari 3.1 to 6.0 */
			transition: background-color .5s, border-top 1s;
		}
		#sync-box:hover {
			background-color: #ececec;
			border-top: 1px solid #fff;
		}
	</style>

</head>

<body>
	<div class="container">
		<div class="well well-large">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<h1>Activity Sync.</h1>
					<h3>Please wait while we update your activity logs...</h3>
					<h4 class="text-danger">Critical Operation Running in Background. Do not reload or close until operation is complete</h4>
				</div>
			</div>
		</div>
		<hr>
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">Synchronizing daily activity log </h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-1">
							
						</div>
						<div class="col-md-3" id="sync-box">
							<h4>Products</h4>
							<p class='text-warning'>Synchronizing All Products Logs.<p>Please wait...</p></p>
							<p id="prod-sync"></p>
							<p id="prod-sync-2"></p>
						</div>
						<div class="col-md-3" id="sync-box">
							<h4>Sales</h4>
						</div>
						<div class="col-md-3" id="sync-box">
							<h4>Purchases</h4>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="<?= base_url() ?>themes/default/assets/js/jquery-2.0.3.min.js"></script>
	<script src="<?= base_url() ?>themes/default/assets/js/bootstrap.min.js"></script>
	<script src="<?= base_url() ?>assets/sync/js/pace.min.js"></script>
	<script type="text/javascript">
		$(function() {
			var start = $.ajax({
				type: 'get',
				url: '<?= site_url('daily_synchronization/data_syncronization_start'); ?>',
				dataType: "json",
				data: {
					uid: 2
				},
				success: function (data) {
					console.log(data);
					//response(data);
					console.log(data.reason);
					if(data.reason == "database")
					{
						$("#prod-sync").html("<p class='text-danger'>Unable to complete synchronization.<p>Reason: Database server actively refused the connection.</p></p>");
						$("#prod-sync-2").html("<p><p class='text-success'>Done.</p><a href='daily_synchronization/view_report/"+data.id+"' class='btn btn-primary btn-block btn-lg'>Report</a></p>");
					}
					else if(data.reason == "good")
					{
						$("#prod-sync").html("<p class='text-success'>Synchronization Complete.<p>Your latest updates have been mapped to the cloud.</p></p>");
						$("#prod-sync-2").html("<p><p class='text-success'>Done.</p><a href='daily_synchronization/view_report/"+data.id+"' class='btn btn-primary btn-block btn-lg'>Report</a></p>");	
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					//alert("some error occured");
					console.log(XMLHttpRequest);
					if(typeof XMLHttpRequest.responseText == 'string')
					{
						$("#prod-sync").html("<p class='text-danger'>Unable to complete synchronization.<p>Reason: Bad or no internet connectivity.</p></p>");
						$("#prod-sync-2").html("<p><p class='text-success'>Done.</p><a class='btn btn-primary btn-block btn-lg'>Report</a></p>");
					}
					//if(Array.isArray())
				} 
			});
		});
	</script>
</body>
</html>