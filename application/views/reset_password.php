<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Reset Password</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/colors.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<!-- /theme JS files -->
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/notifications/sweet_alert.min.js'); ?>"></script>
</head>

<body class="login-container">

	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Content area -->
				<div class="content">
					
					<!-- Password recovery -->
					<form  id="reset_password_form" action="<?php echo base_url($this->uri->uri_string()); ?>" method="POST">
						<div class="panel panel-body login-form">
							<div class="text-center">
								<div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
								<h5 class="content-group">Reset_password</h5>

							</div>

							<div class="form-group has-feedback has-feedback-left">
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
								<input type="password" class="form-control" placeholder="New Password" name="password" id="password">
								<div class="text-danger" id="password_error"></div>
							</div>
							<div class="form-group has-feedback has-feedback-left">
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
								<input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" id="confirm_password">
								
								<div class="text-danger" id="confirm_password_error"></div>
							</div>

							<div class="form-group">
								<button type="submit" class="btn bg-blue btn-block" name="reset_password_submit">Reset password <i class="icon-arrow-right14 position-right"></i></button>
							</div>

							<div class='form-group text-center' style="margin-top:10px;">
								<a href="<?= base_url('auth') ?>" style="text-decoration:none;">Login</a> 
							</div>
						</div>
					</form>
					<!-- /password recovery -->
					
				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>
</html>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/validation/validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/validation/additional_methods.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
<script type="text/javascript">

$(function () {

	function ajxLoader(showstatus, elem) 
    {
        jQuery.LoadingOverlaySetup({
            background: "rgba(0, 0, 0, 0.5)",
            imageColor: "#FFF",
            imageAutoResize: false,
            size: 50,
            maxSize: 50,
            minSize: 20
        });
        if (elem === undefined)
        {
            jQuery.LoadingOverlay(showstatus);
        }
        else
        {
            jQuery(elem).LoadingOverlay(showstatus);
        }
    }
	
	$("#reset_password_form").validate({
		rules: {
			password: {
				required: true,
				minlength: 8
			},
			confirm_password: {
				required: true,
				equalTo: "#password"
			}
		},
		messages:{
			password: {
				required: "Password field is required",
				minlength: "Password length must be minimum 8 characters."
			},
			confirm_password: {
				required: "Confirm password field is required.",
				equalTo: "Confirm password does not match with password."
			},
		},
		errorPlacement: function(error, element) {
            if (element.attr("name") == "password") 
            {
                error.appendTo("#password_error");
            } 
			else if (element.attr("name") == "confirm_password") 
            {
                error.appendTo("#confirm_password_error");
            } 
            else
            {
                error.insertAfter(element);   
            }
        },
		submitHandler:function(form){
	    	
			ajxLoader('show','body');    
	        form.submit();  
	    }
	});  
});

let err_msg='<?php if(!empty($this->session->flashdata('error_msg'))) { echo $this->session->flashdata('error_msg'); } ?>';
let success_msg='<?php if(!empty($this->session->flashdata('success_msg'))) { echo $this->session->flashdata('success_msg'); } ?>';

    if(success_msg != '')
    {
        swal({
            title: "Success!",
            text: success_msg,
            type: "success" 
        });
    }
    else if(err_msg != '')
    {
        swal({
            title: "Oops!",
            text: err_msg,
            type: "error" 
        });
    }
</script>

<?php 
	if(!empty($this->session->flashdata('error_msg')))
	{ 
		$this->session->unset_userdata('error_msg');
	} 
	if(!empty($this->session->flashdata('success_msg')))
	{ 
		$this->session->unset_userdata('success_msg');
	} 
?>