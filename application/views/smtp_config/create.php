<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <span class="text-semibold"><?php if($this->uri->segment(2) == 'edit') { echo 'Edit SMTP Configuration'; } else { echo 'Add SMTP Configuration'; } ?></span>
            </h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>"><i class="icon-home2 position-left"></i>Dashboard</a>
            </li>
            <li>
                <a href="<?php echo base_url('smtp_config'); ?>">SMTP Configuration</a>
            </li>
            <li class="active"><?php if($this->uri->segment(2) == 'edit') { echo 'Edit SMTP Configuration'; } else { echo 'Add SMTP Configuration'; } ?> </li>
        </ul>
    </div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
    <form id="smtp_config_form" method="POST">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
            <!-- Panel -->
            <div class="panel panel-flat">
                <!-- Panel heading -->
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="panel-title">
                                <strong>SMTP Configuration</strong>
                            </h5>
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
                                <label>Email:</label>
                                <input type="text" class="form-control" placeholder="Email" id="email" name="email" autocomplete="off" value="<?php if(!empty($smtp_dtl) && !empty($smtp_dtl->smtp_username)) { echo $smtp_dtl->smtp_username; }?>">
                            </div>
                            <div class="form-group">
                                <small class="req text-danger">* </small>
                                <label>App Password:</label>
                                <input type="text" class="form-control" placeholder="App Password" id="app_password" name="app_password" autocomplete="off" value="<?php if(!empty($smtp_dtl) && !empty($smtp_dtl->app_password)) { echo base64_decode($smtp_dtl->app_password); }?>">
                            </div>
                            <div class="form-group">
                                <label>Assign to Sub Admin:</label>
                                <select name="sub_admin_id" class="form-control select2" id='assign_to_sub_admin_id'>
                                    <option value="">Select Sub Admin</option>
                                    <?php 
                                        foreach($sub_admins as $sub_admin) 
                                        {
                                            ?>
                                            <option value="<?php echo $sub_admin->id;?>" <?php if(!empty($smtp_dtl) && $sub_admin->id == $smtp_dtl->admin_id) { echo 'selected'; } ?>><?php echo $sub_admin->name.' ('.$sub_admin->email.')';?></option>
                                            <?php 
                                        }        
                                    ?>
                                </select>
                            </div>
                           
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name='status' value="1" id="smtp_config_status" <?php if(!empty($smtp_dtl) && $smtp_dtl->status==1) { echo 'checked'; } ?>>
                                <label class="form-check-label" for="smtp_config_status">
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Panel body -->	
            </div>
            <!-- /Panel -->
            </div>
        </div>
        <div class="btn-bottom-toolbar text-center btn-toolbar-container-out">
            <input type="hidden" name="smtp_config_id" value="<?php if(!empty($smtp_dtl) && !empty($smtp_dtl->id)) { echo str_replace('=','',base64_encode($smtp_dtl->id)); } ?>" >
            <button type="submit" class="btn btn-success" name="submit">Submit</button>
            <a class="btn btn-default" onclick="window.history.back();">Back</a>
        </div>
    </form>
</div>
<!-- /Content area -->