<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <span class="text-semibold"><?php if($this->uri->segment(2) == 'edit') { echo 'Edit Contact'; } else { echo 'Add Contact'; } ?></span>
            </h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>"><i class="icon-home2 position-left"></i>Dashboard</a>
            </li>
            <li>
                <a href="<?php echo base_url('contacts'); ?>">Contacts</a>
            </li>
            <li class="active"><?php if($this->uri->segment(2) == 'edit') { echo 'Edit Contact'; } else { echo 'Add Contact'; } ?> </li>
        </ul>
    </div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
    <style>
        .add-more-contact-email-btn, .remove-more-email-icon-btn {
            background: #000;
            color: #FFF;
            border-radius: 50%;
            padding: 14px 16px;
            cursor: pointer;
            transition: all 0.4s;
        }
        .add-more-contact-email-btn:hover, .remove-more-email-icon-btn:hover{
            background: #4DB7FE;
        }
        .add-more-contact-phone-btn, .remove-more-phone-icon-btn {
            background: #000;
            color: #FFF;
            border-radius: 50%;
            padding: 14px 16px;
            cursor: pointer;
            transition: all 0.4s;
        }
        .add-more-contact-phone-btn:hover, .remove-more-phone-icon-btn:hover{
            background: #4DB7FE;
        }
    </style>
    <form id="add_contact_form" method="POST">
        <div class="row">
            <!-- Panel -->
            <div class="panel panel-flat">
                <!-- Panel heading -->
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="panel-title">
                                <strong>Contacts</strong>
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
                                <input type="text" class="form-control" placeholder="Name" id="contact_name" name="contact_name" autocomplete="off" value="<?php if(!empty($contact_dtl) && !empty($contact_dtl->name)) { echo $contact_dtl->name; }?>">
                            </div>
                           <div class="col-sm-6">
                                <small class="req text-danger">* </small>
                                <label>Birth Date</label>
                                <div class="input-group date contact_bdatepicker" > 
                                    <input type="text" name="birthday" id="contact_bdate" class="form-control contact_bdate" placeholder="Select Date" autocomplete="off" value="<?php if(!empty($contact_dtl) && !in_array($contact_dtl->birthday, array('1970-01-01','0000-00-00',null,''))) { echo date('d-m-Y',strtotime($contact_dtl->birthday)); }?>">
                                    <div class="input-group-addon"> 
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                                <div id='birthday_error'></div>
                                <!-- <input type="text" name="birthday" class="form-control" id='contact_bdate' placeholder="Birth Date" value="<?php //if(!empty($contact_dtl)) { echo date('Y-m-d',strtotime($contact_dtl->birthday)); }?>" autocomplete="off" />                                                 -->
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="row">
                            <div class="col-sm-6">
                                <small class="req text-danger">* </small>
                                <label>Company Name</label>
                                <input type="text" name="company_name" class="form-control" placeholder="Company Name" value="<?php if(!empty($contact_dtl)) { echo $contact_dtl->company; } ?>" autocomplete="off" />                                                
                            </div>
                            <div class="col-sm-6">
                                <small class="req text-danger">* </small>
                                <label>Job Title</label>
                                <input type="text" name="job_title" class="form-control" placeholder="Job Title" value="<?php if(!empty($contact_dtl)) { echo $contact_dtl->job_title; } ?>" autocomplete="off" />                                                
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="row">
                            <div class="col-sm-6">
                                <small class="req text-danger">* </small>
                                <label>Address </label>
                                <textarea rows="2" name="contact_address" class="form-control" placeholder="Address" autocomplete="off" style="resize: none;"><?php if(!empty($contact_dtl)) { echo $contact_dtl->address; } ?></textarea>                                                
                            </div>
                            <div class="col-sm-6">
                                <label>Notes </label>
                                <textarea rows="2" class="form-control" name="contact_notes" placeholder="Notes" autocomplete="off" style="resize: none;"><?php if(!empty($contact_dtl)) { echo $contact_dtl->note; } ?></textarea>                                                    
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="row">
                            <div class="col-sm-4">
                                <small class="req text-danger">* </small>
                                <label>Country</label>
                                <select name="country" class="form-control select2" id='contact_country'>
                                    <option value=''>Select Country</option>
                                    <?php
                                        // if(!empty($countries))
                                        // {
                                        //     foreach($countries as $country)
                                        //     {
                                                ?>
                                                <!-- <option value="<?php //echo $country->country_id;?>" <?php //if(!empty($contact_dtl) && $contact_dtl->country == $country->country_id) { echo 'selected'; } ?>><?php //echo $country->name;?></option> -->
                                                <?php
                                        //     }
                                        // }
                                    ?>
                                </select>
                                <div id='contact_country_error'></div>
                            </div>
                            <?php
                                //$states= (!empty($contact_dtl) && !empty($contact_dtl->country)) ? get_states($contact_dtl->country) : '';
                            ?>
                            <div class="col-sm-4">
                                <small class="req text-danger">* </small>
                                <label>State/Province</label>
                                <select name="state" class="form-control select2" id='contact_state'>
                                    <option value="">Select State</option>
                                    <?php
                                        // if(!empty($states))
                                        // {
                                        //     foreach($states as $state)
                                        //     {
                                                ?>
                                                <!-- <option value="<?php //echo $state->state_id;?>" <?php //if(!empty($contact_dtl) && $contact_dtl->state == $state->state_id) { echo 'selected'; } ?>><?php //echo $state->name;?></option> -->
                                                <?php
                                        //     }
                                        // }
                                    ?>
                                </select>
                                <div id='contact_state_error'></div>
                            </div>
                            <?php
                               //$cities= (!empty($contact_dtl) && !empty($contact_dtl->state)) ? get_cities($contact_dtl->state) : '';  
                            ?>
                            <div class="col-sm-4">
                                <small class="req text-danger">* </small>
                                <label>City </label>
                                <select name="city" class="form-control select2" id='contact_city'>
                                    <option value="">Select City</option>
                                    <?php
                                        // if(!empty($cities))
                                        // {
                                        //     foreach($cities as $city)
                                        //     {
                                        //         ?>
                                                 <!-- <option value="<?php //echo $city->city_id;?>" <?php //if(!empty($contact_dtl) && $city->city_id==$contact_dtl->city) { echo 'selected'; } ?>><?php //echo $city->name;?></option> -->
                                                <?php
                                        //     }
                                        // }
                                    ?>
                                    <option value="other">Other</option>
                                </select>
                                <div id='contact_city_error'></div>
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
                    <div class='form-group mt-20'>
                        <div class="row ">
                            <div class="col-md-6 add-more-email-container">
                                <div class="col-md-12 dashboard-title">
                                    <h3>Add Email</h3>
                                </div>
                                <?php
                                if($contact_id > 0 && !empty($contact_emails))
                                {
                                    foreach($contact_emails as $key=>$email_val)
                                    {
                                        ?>
                                        <div class="row mb-10">
                                            <div class="col-sm-4">
                                                <input class="form-control contact_email_type" type="text" name="contact_email_type[<?php echo $key;?>]" placeholder="Type (personal, work..)"  autocomplete="off" value='<?php echo $email_val->email_type;?>' />
                                            </div>
                                            <div class="col-sm-6">
                                                <input class="form-control contact_email" type="email" name="contact_email[<?php echo $key; ?>]" placeholder="Email" value="<?php echo $email_val->email; ?>" autocomplete="off" />
                                            </div>
                                            <?php
                                                if($key === 0)
                                                {
                                                    ?>
                                                    <div class="col-sm-2">
                                                        <i class="fa fa-plus pull-right add-more-contact-email-btn"></i>
                                                    </div>
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                    <div class="col-sm-2">
                                                        <i class="fa fa-times pull-right remove-more-email-icon-btn"></i>
                                                    </div>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                }
                                else
                                {
                                    ?>
                                    <div class="row mb-10">
                                        <div class="col-sm-4">
                                            <input class="form-control contact_email_type" type="text" name="contact_email_type[]" placeholder="Type (personal, work..)"  autocomplete="off" value='' />
                                        </div>
                                        <div class="col-sm-6">
                                            <input class="form-control contact_email" type="email" name="contact_email[]" placeholder="Email" value="" autocomplete="off" />
                                        </div>
                                        <div class="col-sm-2">
                                            <i class="fa fa-plus pull-right add-more-contact-email-btn"></i>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-md-6 add-more-phone-container">
                                <div class="col-md-12 dashboard-title">
                                    <h3>Add Phone</h3>
                                </div>
                                <?php
                                if($contact_id > 0 && !empty($contact_phones))
                                {
                                    foreach($contact_phones as $key=>$phone_val)
                                    {
                                        ?>
                                        <div class="row mb-10">
                                            <div class="col-sm-4">
                                                <input class="form-control contact_type" type="text" name="contact_type[<?php echo $key;?>]" placeholder="Type (mobile, work..)"  autocomplete="off" value='<?php echo $phone_val->phone_type;?>' />
                                            </div>
                                            <div class="col-sm-6">
                                                <input class="form-control contact_number" type="text" name="contact_number[<?php echo $key;?>]" placeholder="Phone" value="<?php echo $phone_val->phone_number;?>" />
                                            </div>
                                            <?php
                                                if($key === 0)
                                                {
                                                    ?>
                                                    <div class="col-sm-2">
                                                        <i class="fa fa-plus pull-right add-more-contact-phone-btn"></i>
                                                    </div>
                                                    <?php 
                                                }
                                                else
                                                {
                                                    ?>
                                                    <div class="col-sm-2">
                                                        <i class="fa fa-times pull-right remove-more-phone-icon-btn"></i>
                                                    </div>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                        <?php 
                                    }
                                }
                                else
                                {
                                    ?>
                                    <div class="row mb-10">
                                        <div class="col-sm-4">
                                            <input class="form-control contact_type" type="text" name="contact_type[]" placeholder="Type (mobile, work..)" value="" autocomplete="off" />
                                        </div>
                                        <div class="col-sm-6">
                                            <input class="form-control contact_number" type="text" name="contact_number[]" placeholder="Phone" value="" />
                                        </div>
                                        <div class="col-sm-2">
                                            <i class="fa fa-plus pull-right add-more-contact-phone-btn"></i>
                                        </div>
                                    </div>
                                    <?php
                                } ?>
                            </div>
                        </div>
                    </div>
                                
                </div>
                <!-- /Panel body -->	
            </div>
            <!-- /Panel -->
        </div>
        <div class="btn-bottom-toolbar text-center btn-toolbar-container-out">
            <input type="hidden" name="contact_id" value="<?php if(!empty($contact_dtl) && !empty($contact_dtl->id)) { echo str_replace('=','',base64_encode($contact_dtl->id)); } ?>" >
            <button type="submit" name='contact_submit_btn' class="btn btn-success" name="submit">Submit</button>
            <a class="btn btn-default" onclick="window.history.back();">Back</a>
        </div>
    </form>
    <input type="hidden" id="edit_contact_country" value="<?php if(!empty($contact_dtl) && !empty($contact_dtl->country)) { echo $contact_dtl->country; } ?>" >
    <input type="hidden" id="edit_contact_state" value="<?php if(!empty($contact_dtl) && !empty($contact_dtl->state)) { echo $contact_dtl->state; } ?>" >
    <input type="hidden" id="edit_contact_city" value="<?php if(!empty($contact_dtl) && !empty($contact_dtl->city)) { echo $contact_dtl->city; } ?>" >
</div>
<!-- /Content area -->