<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <span class="text-semibold">Birthday Templates </span>
            </h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>"><i class="icon-home2 position-left"></i>Dashboard</a>
            </li>
            <li class=""><a href="<?php echo base_url('birthday_templates'); ?>">Birthday Templates</a></li>
            <li class="active"><?php if($this->uri->segment(2) == 'edit') { echo 'Edit Template'; } else { echo 'Add Template'; } ?> </li>
        </ul>
    </div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
    <style>
        /* Set the dropdown height */
        .select2-results__options {
            max-height: 300px !important;
            overflow-y: auto !important;
        }
        /* Force show and align the clear (x) icon in Select2 v4.0.3 */
        .select2-container .select2-selection__clear {
            position: absolute;
            right: 25px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2em;
            color: #888;
            cursor: pointer;
            display: inline-block;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            max-height: 100px;       /* or whatever height you want */
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 10px;     /* optional for scrollbar spacing */
        }
    </style>
    
    <form id="add_bt_form" method="POST" >
        <div class="row">
            <!-- Panel -->
            <div class="panel panel-flat">
                <!-- Panel heading -->
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="panel-title">
                                <strong><?php if($this->uri->segment(2) == 'edit') { echo 'Edit Template'; } else { echo 'Add Template'; } ?></strong>
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="req text-danger">* </small>
                                        <label>Subject</label>
                                        <input type="text" class="form-control" placeholder="Subject" id="email_subject" name="email_subject" autocomplete="off" value="<?php if(!empty($bt_detail) && !empty($bt_detail->email_subject)) { echo $bt_detail->email_subject; } ?>">
                                    </div>
                                    <?php
                                    if(base64_decode($this->uri->segment(3)) != 1)
                                    {
                                        ?>
                                        <div class="col-md-6">
                                            <!-- <small class="req text-danger">* </small> -->
                                            <label>Smtp Configuration</label>
                                            <select class='form-control select2' id='smtp_config' name='smtp_config'>
                                                <option value=''>Select SMTP Configuration</option>
                                                <?php
                                                    if($smtp_configs)
                                                    {
                                                        foreach($smtp_configs as $config)
                                                        {
                                                            ?>
                                                            <option value='<?php echo $config->id;?>' <?php if(!empty($bt_detail) && $bt_detail->smtp_config_id==$config->id) { echo "selected"; } ?>><?php echo $config->smtp_username;?></option>
                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                            <div id='smtp_config_error'></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <small class="req text-danger">* </small>
                                <label>Message</label>
                                <textarea class="form-control" placeholder="Message" id="email_message" name="email_message" autocomplete="off" ><?php if(!empty($bt_detail) && !empty($bt_detail->email_content)) { echo $bt_detail->email_content; } ?></textarea>
                                <div id='email_message_error'></div>
                            </div>
                            <?php
                            if(base64_decode($this->uri->segment(3)) != 1)
                            {
                                ?>
                                <div class="form-group">
                                    <!-- <small class="req text-danger">* </small> -->
                                    <label>Contacts</label>
                                    <select class="form-control select2" id="email_to" name="email_to[]" multiple>
                                        <!-- <option value="">Select</option> -->
                                        <option value="select_all">Select All</option>
                                        <?php
                                            if($contacts)
                                            {
                                                foreach($contacts as $contact)
                                                {
                                                    ?>
                                                    <option value="<?php echo $contact->id;?>" <?php if(!empty($bt_contacts) && in_array($contact->id, $bt_contacts)) { echo "selected"; } ?>><?php echo $contact->name.' ('.$contact->email.')';?></option>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                    <div id='email_to_error'></div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!-- /Panel body -->	
            </div>
            <!-- /Panel -->
           
        </div>
        <div class="btn-bottom-toolbar text-center btn-toolbar-container-out">
            <input type="hidden" name="template_id" value="<?php if(!empty($bt_detail) && !empty($bt_detail->id)) { echo str_replace('=','',base64_encode($bt_detail->id)); } ?>" >
            <button type="submit" name='template_form_btn' class="btn btn-success" name="submit">Submit</button>
            <a class="btn btn-default" onclick="window.history.back();">Back</a>
        </div>
    </form>
</div>
<!-- /Content area -->