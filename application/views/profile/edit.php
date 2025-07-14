<!-- Page header -->
<div class="page-header page-header-default">
  <div class="page-header-content">
    <div class="page-title">
      <h4>
      	<span class="text-semibold">Edit Profile</span>
      </h4>
    </div>
  </div>
  <div class="breadcrumb-line">
    <ul class="breadcrumb">
			<li><a href="<?php echo base_url('dashboard'); ?>"><i class="icon-home2 position-left"></i>Dashboard</a></li>			
			<li class="active">Edit Profile</li>
		</ul>
  </div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
	<div class="row">
		<!-- Left column -->
		<div class="col-md-7">
			<form action="<?php echo base_url('profile/edit/') ?>" id="myprofileform" method="POST">
				<!-- Panel -->
				<div class="panel panel-flat">
					<!-- Panel heading -->
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-10">
								<h5 class="panel-title">Update Profile</h5>
							</div>
						</div>
					</div>
					<!-- /Panel heading -->
					<!-- Panel body -->
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									
									<label class="col-form-label label_text text-lg-right">Username:<small class="req text-danger">* </small></label>
									<input type="text" class="form-control" id="username" name="username" value="<?php echo $user->name; ?>">
								</div>
								
								<div class="form-group">
									<label class="col-form-label label_text text-lg-right">Email:</label>
									<input type="text" class="form-control" disabled readonly id="email" name="email" class="email" value="<?php echo $user->email; ?>">
								</div>	
								<?php
									if($this->session->userdata('user_role') == 2)
									{
										?>
										<div class="form-group">
											<label class="col-form-label label_text text-lg-right">Email Limit:</label>
											<input type="number" class="form-control" disabled readonly id="email_limit" name="email_limit" class="email" value="<?php echo $user->email_limit; ?>">
										</div>
										<div class="form-group">
											<label class="col-form-label label_text text-lg-right">Country:</label>
											<select name="country" class="form-control select2" id='country' disabled>
												<option value=''>Country</option>
												<?php
													if(!empty($countries))
													{
													    foreach($countries as $country)
													    {
															?>
															<option value="<?php echo $country->country_id;?>" <?php if(!empty($user) && $user->country_id == $country->country_id) { echo 'selected'; } ?>><?php echo $country->name;?></option>
															<?php
													    }
													}
												?>
											</select>
										</div>
										<?php
											$states= (!empty($user) && !empty($user->country_id)) ? get_states($user->country_id) : '';
										?>
										<div class="form-group">
											<label class="col-form-label label_text text-lg-right">State:</label>
											<select name="state" class="form-control select2" id='contact_state' disabled>
												<option value="">Select State</option>
												<?php
													if(!empty($states))
													{
													    foreach($states as $state)
													    {
															?>
															<option value="<?php echo $state->state_id;?>" <?php if(!empty($user) && $user->state_id == $state->state_id) { echo 'selected'; } ?>><?php echo $state->name;?></option> 
															<?php
													    }
													}
												?>
											</select>
										</div>
										<?php
											$cities= (!empty($user) && !empty($user->state_id)) ? get_cities($user->state_id) : '';  
										?>
										<div class="form-group">
											<label class="col-form-label label_text text-lg-right">City:</label>
											<select name="city" class="form-control select2" id='contact_city' disabled>
                                    			<option value="">Select City</option>
												<?php
													if(!empty($cities))
													{
														foreach($cities as $city)
														{
															?>
															<option value="<?php echo $city->city_id;?>" <?php if(!empty($user) && $city->city_id==$user->city_id) { echo 'selected'; } ?>><?php echo $city->name;?></option>
															<?php
														}
													}
												?>
											</select>
										</div>
										<?php
									}
								?>
								
								<div class="form-group" align="right">
									<button type="submit" class="btn btn-success " name="edit_profile_submit" id="save">Save</button>
								</div>
							</div>
						</div>	
					</div>
					<!-- /Panel body -->
				</div>
				<!-- /Panel -->
			</form>
		</div>
		<!-- /Left column -->
		<!-- Right column -->
		<div class="col-md-5">
			<form action="<?php echo base_url('profile/edit_password/') ?>" id="mypasswordform" method="POST">
				<!-- Panel -->
				<div class="panel panel-flat">
					<!-- Panel heading -->
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-10">
								<h5 class="panel-title">Change password</h5>
							</div>
						</div>
					</div>
					<!-- /Panel heading -->
					<!-- Panel body -->
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="col-form-label label_text text-lg-right">Current Password:</label>
									<input type="password" name="old_password" class="form-control" id="old_password" autocomplete="off">							
									<!-- <i class="fa fa-eye toggle-password" id="toggleOldPassword"></i> -->
								</div>
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="col-form-label label_text text-lg-right">New password:</label>
									<input type="password" class="form-control" id="new_password" name="new_password" autocomplete="off">
									<!-- <i class="fa fa-eye toggle-password" id="toggleNewPassword"></i>	 -->
								</div>
								<div class="form-group">
									<small class="req text-danger">* </small>
									<label class="col-form-label label_text text-lg-right">Confirm password:<small class="req text-danger">* </small></label>
									<input type="password" class="form-control" id="confirm_password" name="confirm_password" autocomplete="off">
									<!-- <i class="fa fa-eye toggle-password" id="toggleConfirmPassword"></i>						 -->
								</div>
								<div class="form-group" align="right">
									<button type="submit" class="btn btn-success" name="change_password_btn" id="change_password_btn">Submit</button>
								</div>
							</div>
						</div>
					</div>
					<!-- /Panel body -->
				</div>
				<!-- /Panel -->
			</form>
		</div>
		<!-- /Right column -->
	</div>
</div>
<!-- /Content area -->
