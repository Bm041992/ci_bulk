<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <span class="text-semibold">Logs</span>
            </h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>"><i class="icon-home2 position-left"></i>Dashboard</a>
            </li>
            <li class="active">Logs</li>
        </ul>
    </div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
    <!-- Panel -->
    <div class="panel panel-flat">
        
        <!-- Panel heading -->
        <div class="panel-heading">
           
            <!-- <a href="<?php //echo base_url('smtp_config/add'); ?>" class="btn btn-primary">Add New<i class="icon-plus-circle2 position-right"></i></a>   -->
        
        </div>
        <!-- /Panel heading -->
        
        
        <!-- Listing table -->
        <div class="panel-body table-responsive">
            <table id="failed_email_error_log_list_table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Contact Name</th>
						<th>SMTP Username</th>
                        <th>Email Subject</th>
                        <th>Error Info</th>
                        <th>Created at</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>           
        </div>
        <!-- /Listing table -->
    </div>
    <!-- /Panel -->
</div>
<!-- /Content area -->
