<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Forgot Password</title>

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
					<form id="recovery_form" action="<?php echo base_url('auth/forgot_password') ?>" method="POST">
						<div class="panel panel-body login-form">
							<div class="text-center">
								<div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
								<h5 class="content-group">Password recovery <small class="display-block">We'll send you instructions in email</small></h5>
							</div>

							<div class="form-group has-feedback">
								<input type="email" name='email' class="form-control" placeholder="Your email">
								<div class="form-control-feedback">
									<i class="icon-mail5 text-muted"></i>
								</div>
								<div class="text-danger" id='email_err'></div>
							</div>

							<button type="submit" class="btn bg-blue btn-block">Reset password <i class="icon-arrow-right14 position-right"></i></button>
							<div class='form-group text-center' style="margin-top:10px;">
								<a href="<?= base_url('auth') ?>" style="text-decoration:none;">← Back to login</a> 
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
	jQuery.validator.addMethod("customEmail", function(value, element, param) {
        return value.match(/^(?!\.)[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/);
    },'Enter Correct E-mail Address');

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
	
	$("#recovery_form").validate({
	    rules: {
	        email: {
                required: true,
                customEmail: true,
				remote: {
                    url: "<?php echo base_url('auth/is_email_exists'); ?>",
                    type: "POST"
                }
	        }
	    },
    	messages: {
	        email: {
				required:'Email field is required',
				remote:'Email not exist'
	        }
	    },
		errorPlacement: function(error, element) {
            if (element.attr("name") == "email") 
            {
                error.appendTo("#email_err");
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