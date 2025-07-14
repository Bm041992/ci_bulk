<!-- Page header -->
<div class="page-header page-header-default">
	<div class="breadcrumb-line">
		<ul class="breadcrumb">
			<li><a href="<?= base_url() ?>"><i class="icon-home2 position-left"></i>Dashboard</a></li>			
		</ul>
	</div>
</div>
<!-- /Page header -->

<!-- Content area -->
<div class="content">	
	<div class="row">
		<div class="panel panel-flat">
			<div class="panel-body">
				<!-- Quick stats boxes -->
				<div class="row">
					<div class="col-lg-3">
						<!-- Members online -->
						<div class="panel bg-teal-400">
							<div class="panel-body">
								<h3 class="no-margin"><?= $daily_success_count ?? 0 ?></h3>
								Email Stats (Today)
								<div class="text-muted text-size-small">Success</div>
							</div>
						</div>
						<!-- /members online -->
					</div>

					<div class="col-lg-3">
						<!-- Current server load -->
						<div class="panel bg-pink-400">
							<div class="panel-body">
								<h3 class="no-margin"><?= $daily_failed_count ?? 0 ?></h3>
								Email Stats (Today)
								<div class="text-muted text-size-small">Failed</div>
							</div>
						</div>
						<!-- /current server load -->
					</div>

					<div class="col-lg-3">
						<!-- Today's revenue -->
						<div class="panel bg-blue-400">
							<div class="panel-body">
								<h3 class="no-margin"><?= $monthly_success_count ?? 0 ?></h3>
								Email Stats (This Month)
								<div class="text-muted text-size-small">Success</div>
							</div>
						</div>
						<!-- /today's revenue -->
					</div>

					<div class="col-lg-3">
						<!-- Today's revenue -->
						<div class="panel bg-pink-400">
							<div class="panel-body">
								<h3 class="no-margin"><?= $monthly_failed_count ?? 0 ?></h3>
								Email Stats (This Month)
								<div class="text-muted text-size-small">Failed</div>
							</div>
						</div>
						<!-- /today's revenue -->
					</div>
				</div>
				<!-- /quick stats boxes -->

				<hr>
				<form id="bday_msg_form" method="POST">
					<div class="form-group">
						<small class="req text-danger">* </small>
						<label>Message:</label>
						<textarea rows="5" class="form-control" placeholder="Message" id="bday_msg" name="bday_msg" autocomplete="off" style="resize: none;"><?php if(!empty($whatsapp_bday_msg) && !empty($whatsapp_bday_msg->message)) { echo $whatsapp_bday_msg->message; }?></textarea>
					</div>
							
					<div class="btn-bottom-toolbar  btn-toolbar-container-out">
						<button type="submit" class="btn btn-success" name="dash_msg_submit">Save</button>
					</div>
				</form>
				<hr>

				<h5 class="text-semibold">🎉 Celebrating Today's Birthdays</h5>
				<!-- <h5 class="text-semibold">🎉 Today’s Special Birthday List</h5> -->
				<hr>
				<div class="table-responsive">
					<table id="daily_contacts_bday_list_table" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Admin Name</th>
								<th>Admin Email</th>
								<th>Contact Name</th>
								<th>Contact No</th>
								<th>Company</th>
								<th>Address</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>  
					
					<input type='hidden' id='current_user_role' value='<?php echo $this->session->userdata('user_role'); ?>'>         
				</div>
			</div>
		</div>
	</div>			
</div>
<!-- /page header -->	
