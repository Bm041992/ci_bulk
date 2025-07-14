<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <span class="text-semibold">Send Emails </span>
            </h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>"><i class="icon-home2 position-left"></i>Dashboard</a>
            </li>
            <li class=""><a href="<?php echo base_url('send_emails'); ?>">Email Contents</a></li>
            <li class="active">Send Emails </li>
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
    <form id="send_email_form" method="POST" >
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
            <!-- Panel -->
            <div class="panel panel-flat">
                <!-- Panel heading -->
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="panel-title">
                                <strong>Send Email</strong>
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
                                        <label>Schedule Date</label> 
                                        <div class="input-group date schedule_datepicker" > 
                                            <input type="text" name="schedule_date" id="schedule_date" class="form-control schedule_date" placeholder="Select Date" autocomplete="off" value="">
                                            <div class="input-group-addon"> 
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>
                                        <div id='sdate_error'></div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="req text-danger">* </small>
                                        <label>Subject</label>
                                        <input type="text" class="form-control" placeholder="Subject" id="email_subject" name="email_subject" autocomplete="off" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <small class="req text-danger">* </small>
                                <label>Message</label>
                                <textarea class="form-control" placeholder="Message" id="email_message" name="email_message" autocomplete="off" value=""></textarea>
                                <div id='email_message_error'></div>
                            </div>
                            <div class="form-group">
                                <small class="req text-danger">* </small>
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
                                                <option value="<?php echo $contact->id;?>"><?php echo $contact->name.' ('.$contact->email.')';?></option>
                                                <?php
                                            }
                                        }
                                    ?>
                                </select>
                                
                            </div>
                            <div class="form-group">
                                <small class="req text-danger">* </small>
                                <label>Emails (comma separated values)</label>
                                <textarea rows='3' class="form-control" id="manual_emails" name="manual_emails"></textarea>
                            </div>
                            <div id='email_to_error'></div>
                            <?php
                                if($this->session->userdata('user_role') == 2)
                                {
                                    ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name='send_to_admin_contacts' value="1" id="send_to_admin_contacts" >
                                        <label class="form-check-label" for="send_to_admin_contacts">
                                            Send to Admin Location Contacts
                                        </label>
                                    </div>
                                    <?php
                                }
                            ?>
                            
                        </div>
                    </div>
                </div>
                <!-- /Panel body -->	
            </div>
            <!-- /Panel -->
            </div>
        </div>
        <div class="btn-bottom-toolbar text-center btn-toolbar-container-out">
            <button type="submit" name='send_email_form_btn' class="btn btn-success" name="submit">Submit</button>
            <a class="btn btn-default" onclick="window.history.back();">Back</a>
        </div>
    </form>
</div>
<!-- /Content area -->