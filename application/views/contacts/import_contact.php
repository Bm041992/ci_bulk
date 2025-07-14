<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <span class="text-semibold">Import Contacts</span>
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
            <li class="active">Import Contacts</li>
        </ul>
    </div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
    <form id="import-contacts-csv-form" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
            <!-- Panel -->
            <div class="panel panel-flat">
                <!-- Panel heading -->
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="panel-title">
                                <strong>Import Contacts</strong>
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
                                <label>Upload File <a href='<?php echo base_url().'assets/csv/contacts_sample.csv';?>' style='color:blue;'>Sample file</a></label>
                                <input type="file" name="contacts_file"  class="form-control">
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
            <button type="submit" name='import_contact_btn' class="btn btn-success" name="submit">Submit</button>
            <a class="btn btn-default" onclick="window.history.back();">Back</a>
        </div>
    </form>
</div>
<!-- /Content area -->