<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <span class="text-semibold">Contacts</span>
            </h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>"><i class="icon-home2 position-left"></i>Dashboard</a>
            </li>
            <li class="active">Contacts</li>
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
           
            <a href="<?php echo base_url('contacts/add'); ?>" class="btn btn-primary">Add New<i class="icon-plus-circle2 position-right"></i></a> 
            
            <a href="<?php echo base_url('contacts/import_contacts'); ?>" class="btn btn-primary">Import Contacts<i class="icon-plus-circle2 position-right"></i></a> 
        
        </div>
        <!-- /Panel heading -->
        <style>
            /* table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before,
            table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control:before {
                content: '\002B'; 
                background-color: #007bff;
                color: #fff;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                display: inline-block;
                text-align: center;
                font-weight: bold;
                line-height: 20px;
                margin-right: 10px;
                font-size: 14px;
            }

            table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td.dtr-control:before,
            table.dataTable.dtr-inline.collapsed > tbody > tr.parent > th.dtr-control:before {
                content: '\2212'; 
                background-color: #dc3545;  
                color: #fff;
            } */
        </style>
        <!-- Listing table -->
        <div class="panel-body table-responsive">
            <table id="contacts_list_table" class="table table-bordered table-striped">
                <thead>
                    <tr>
						<th>Name</th>
                        <th>Birthday</th>
                        <th>Company</th>
                        <th>Job Title</th>
                        <th>Address</th>
                        <th>Created At</th>
                        <th>Action</th>
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
