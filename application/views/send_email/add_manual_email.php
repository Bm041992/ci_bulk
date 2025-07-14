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
                                <small class="req text-danger">* </small>
                                <label>Emails (comma separated values)</label>
                                <textarea rows='3' class="form-control" id="manual_emails" name="manual_emails"></textarea>
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
            <button type="submit" name='send_email_form_btn' class="btn btn-success" name="submit">Submit</button>
            <a class="btn btn-default" onclick="window.history.back();">Back</a>
        </div>
    </form>
</div>
<!-- /Content area -->