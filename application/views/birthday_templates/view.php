<!-- Page header -->
<div class="page-header page-header-default">
    <div class="page-header-content">
        <div class="page-title">
            <h4>
                <span class="text-semibold">Email Contents</span>
            </h4>
        </div>
    </div>
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>"><i class="icon-home2 position-left"></i>Dashboard</a>
            </li>
            <li class=""><a href="<?php echo base_url('birthday_templates'); ?>">Birthday Templates</a></li>
            <li class="active">Email Contents</li>
        </ul>
    </div>
</div>
<!-- /Page header -->
<!-- Content area -->
<div class="content">
    <!-- Panel -->
    <div class="panel panel-flat">
        
        <!-- Listing table -->
        <div class="panel-body table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th style="width: 150px;">Subject</th>
                        <td><?= htmlspecialchars($email_content->email_subject) ?></td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td><?= $email_content->created_at ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="panel panel-primary">
                <div class="panel-heading">Email Content</div>
                <div class="panel-body">
                    <?= $email_content->email_content ?> <!-- Already stored as HTML -->
                </div>
            </div>
            <a href="<?= base_url('birthday_templates') ?>" style="text-decoration:none;">← Back to list</a>         
        </div>
        <!-- /Listing table -->
    </div>
    <!-- /Panel -->
</div>
<!-- /Content area -->
