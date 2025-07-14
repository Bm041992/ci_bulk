<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <span class="text-semibold"><?php if($this->uri->segment(2) == 'edit') { echo 'Edit Sub Admin'; } else { echo 'Add Sub Admin'; } ?></span>
            </h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>"><i class="icon-home2 position-left"></i>Dashboard</a>
            </li>
            <li>
                <a href="<?php echo base_url('sub_admins'); ?>">Sub Admins</a>
            </li>
            <li class="active"><?php if($this->uri->segment(2) == 'edit') { echo 'Edit Sub Admin'; } else { echo 'Add Sub Admin'; } ?> </li>
        </ul>
    </div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
    <form id="add_sub_admin_form" method="POST">
        <div class="row">
            <!-- Panel -->
            <div class="panel panel-flat">
                <!-- Panel heading -->
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="panel-title">
                                <strong>Sub Admins</strong>
                            </h5>
                        </div>
                    </div>
                </div>
                <!-- /Panel heading -->
                <!-- Panel body -->
                <div class="panel-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="req text-danger">* </small>
                                <label>Name</label>
                                <input type="text" class="form-control" placeholder="Name" id="sub_admin_name" name="username" autocomplete="off" value="<?php if(!empty($sub_admin_dtl)) { echo $sub_admin_dtl->name; }?>">
                            </div>
                           <div class="col-sm-6">
                                <small class="req text-danger">* </small>
                                <label>Email</label>
                                <input type="text" name="email" id="sub_admin_email" class="form-control" placeholder="Email" autocomplete="off" value="<?php if(!empty($sub_admin_dtl)) { echo $sub_admin_dtl->email; }?>" <?php if(!empty($sub_admin_dtl)) { echo 'readonly'; } ?> >
                            </div>
                        </div>
                    </div>
                   
                    <div class='form-group'>
                        <div class="row">
                            <div class="col-sm-6">
                                <small class="req text-danger">* </small>
                                <label>Email Limit</label>
                                <input type="number" name="email_limit" class="form-control" placeholder="Email Limit" value="<?php if(!empty($sub_admin_dtl)) { echo $sub_admin_dtl->email_limit; } ?>" autocomplete="off" min='0' />                                                
                            </div>
                            <div class="col-sm-6">
                                <small class="req text-danger">* </small>
                                <label>Country</label>
                                <select name="country" class="form-control select2" id='sa_country'>
                                    <option value=''>Select Country</option>
                                   
                                </select>
                                <div id='sa_country_error'></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='form-group'>
                        <div class="row">
                            <div class="col-sm-6">
                                <small class="req text-danger">* </small>
                                <label>State/Province</label>
                                <select name="state" class="form-control select2" id='sa_state'>
                                    <option value="">Select State</option>
                                    
                                </select>
                                <div id='sa_state_error'></div>
                            </div>
                            
                            <div class="col-sm-6">
                                <small class="req text-danger">* </small>
                                <label>City </label>
                                <select name="city" class="form-control select2" id='sa_city'>
                                    <option value="">Select City</option>
                                    
                                    <option value="other">Other</option>
                                </select>
                                <div id='sa_city_error'></div>
                            </div>
                        </div>
                    </div>
                    <div class='form-group hidden' id='manual-city-input'>
                        <div class="row">
                            <div class="col-sm-12">
                                <small class="req text-danger">* </small>
                                <label>City (Add Manually)</label>
                                <input type="text" id="city_manual" name="city_manual" class='form-control' placeholder="City (Add Manually)" autocomplete="off" />                                                
                            </div>
                        </div>
                    </div> 
                    <?php 
                    if(!empty($sub_admin_dtl))
                    {
                        ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name='status' value="1" id="sa_status" <?php if(!empty($sub_admin_dtl) && $sub_admin_dtl->status==1) { echo 'checked'; } elseif(empty($sub_admin_dtl)) { echo 'checked'; } ?>>
                            <label class="form-check-label" for="sa_status">
                                Active
                            </label>
                        </div>  
                    <?php } ?>         
                </div>
                <!-- /Panel body -->	
            </div>
            <!-- /Panel -->
        </div>
        <div class="btn-bottom-toolbar text-center btn-toolbar-container-out">
            <input type="hidden" name="sub_admin_id" value="<?php if(!empty($sub_admin_dtl) && !empty($sub_admin_dtl->id)) { echo str_replace('=','',base64_encode($sub_admin_dtl->id)); } ?>" >
            <button type="submit" name='sub_admin_submit_btn' class="btn btn-success" name="submit">Submit</button>
            <a class="btn btn-default" onclick="window.history.back();">Back</a>
        </div>
    </form>
    <input type="hidden" id="edit_sub_admin_country" value="<?php if(!empty($sub_admin_dtl) && !empty($sub_admin_dtl->country_id)) { echo $sub_admin_dtl->country_id; } ?>" >
    <input type="hidden" id="edit_sub_admin_state" value="<?php if(!empty($sub_admin_dtl) && !empty($sub_admin_dtl->state_id)) { echo $sub_admin_dtl->state_id; } ?>" >
    <input type="hidden" id="edit_sub_admin_city" value="<?php if(!empty($sub_admin_dtl) && !empty($sub_admin_dtl->city_id)) { echo $sub_admin_dtl->city_id; } ?>" >
</div>
<!-- /Content area -->