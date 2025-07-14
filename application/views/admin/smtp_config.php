<?php
    $this->load->view('admin/header');
?>
	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main sidebar -->
			<?php
                $this->load->view('admin/sidebar');
            ?>
			<!-- /main sidebar -->


			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Page header -->
				<div class="page-header page-header-default">
					<div class="page-header-content">
						<div class="page-title">
							<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Home</span> - SMTP Configuration</h4>
						</div>
					</div>

					<div class="breadcrumb-line">
						<ul class="breadcrumb">
							<li><a href="index.html"><i class="icon-home2 position-left"></i> Home</a></li>
							<li class="active">SMTP Configuration</li>
						</ul>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content">

					<!-- Panel -->
					<div class="panel panel-flat">
					
						<!-- Panel heading -->
						<div class="panel-heading">
							<a data-toggle="modal" data-target="#add_county_modal" class="btn btn-primary addcounty">Add SMTP Config<i class="icon-plus-circle2 position-right "></i></a>
							
						</div>
						<!-- /Panel heading -->
						<div class="row">
							<div class="col-sm-12">
								<hr>
							</div>
						</div>
						
						<!-- Listing table -->
						<div class="panel-body table-responsive">
							<table id="smtp_config_list_table" class="table table-bordered table-striped" >
								<thead>
									<tr>
										<th>#</th>
										<th>SMTP Username</th>
										<!-- <th>Status</th> -->
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

                    <!-- Footer -->
					<div class="footer text-muted">
						&copy; 2015. <a href="#">Limitless Web App Kit</a>
					</div>
					<!-- /footer -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->
									
<?php
    $this->load->view('admin/footer');
?>