<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>User Login</title>

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

					<!-- Simple login form -->
					<form id="auth_login_form" method='post'>
						<div class="panel panel-body login-form">
							<div class="text-center">
								<div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
								<h5 class="content-group">Login to your account <small class="display-block">Enter your credentials below</small></h5>
							</div>

                            <div class="alert alert-danger hidden mb-5 text-center" id="login_error">

                            </div>
                            <div class="alert alert-success hidden mb-5 text-center" id="login_success">

                            </div>
                            
							<div class="form-group has-feedback has-feedback-left">
								<input type="text" name='email' class="form-control" placeholder="Email" autocomplete="off">
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
                                <div class="text-danger" id="email_error"></div>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<input type="password" name='password' class="form-control" placeholder="Password">
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
                                <div class="text-danger" id="password_error"></div>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-block">Sign in <i class="icon-circle-right2 position-right"></i></button>
							</div>

							<div class="text-center">
								<a href="<?php echo base_url();?>auth/forgot_password">Forgot password?</a>
							</div>
						</div>
					</form>
					<!-- /simple login form -->

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
<script>
    var baseUrl="<?php echo base_url(); ?>";
        
    jQuery.validator.addMethod("noSpace", function(value, element) {
        if($.trim(value).length > 0)
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }, "No space please and do not leave it empty");

    jQuery.validator.addMethod("noHTML", function(value, element) {
        // return true - means the field passed validation
        // return false - means the field failed validation and it triggers the error
        return this.optional(element) || /^([a-zA-Z0-9 _&?=(){},.|*+-]+)$/.test(value);
    }, "Special Characters not allowed");

    jQuery.validator.addMethod("noHTMLtags", function(value, element){
        if(this.optional(element) || /<\/?[^>]+(>|$)/g.test(value))
        {
            return false;
        } 
        else 
        {
            return true;
        }
    }, "HTML tags are Not allowed");

    jQuery.validator.addMethod("customEmail", function(value, element, param) {
        return value.match(/^(?!\.)[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/);
    },'Enter Correct E-mail Address');

    jQuery.validator.addMethod("onlyNumber", function(value, element) {
        return this.optional(element) || /^([0-9+-- /]+)$/.test(value);
    }, 'Only numbers,space,+ and - are allowed');

    $.validator.addMethod("greater_than_start_time", function (value, element,param) {
        // console.log(value);
        // console.log($(param).val());
        var startTime = $(param).val(); // param is selector like '#start_time'
        //if (!value || !startTime) return true; // let 'required' handle empty case

        return startTime < value;
    }, "End time must be greater than start time.");

    $.validator.addMethod('filesize', function(value, element, param) {
        // param = size (en bytes) 
        // element = element to validate (<input>)
        // value = value of the element (file name)
        return this.optional(element) || (element.files[0].size <= param); 
    
    }, "File Size must be less than or equal to 2Mb");

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
   
    jQuery('#auth_login_form').validate({
        rules:{
            email: {
                required: true,
                customEmail: true
            },
            password: {
                required: true
            }
        },
        messages:{
            email: {
                required: 'Email field is required'
            },
            password: {
                required: 'Password field is required'
            }
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "email") 
            {
                error.appendTo("#email_error");
            } 
            else if(element.attr("name") == "password")
            {
                error.appendTo("#password_error");
            }
            else
            {
                error.insertAfter(element);   
            }
        },
        submitHandler:function(form){
        
            let ajaxSubmit='<?php echo base_url();?>auth/login';
            jQuery("#login_error").addClass('hidden');
            jQuery("#login_error").html("");  
            jQuery("#login_success").addClass('hidden');
            jQuery("#login_success").html("");  

            jQuery.ajax({
                url: ajaxSubmit,
                type: 'POST',
                data:$('#auth_login_form').serialize(),
                dataType:'JSON',
                beforeSend: function () {
                    ajxLoader('show','body');    
                }, 
                success: function(response) {

                    ajxLoader('hide','body');    
                    
                    if(response.success) 
                    {
                        jQuery("#auth_login_form")[0].reset();
                        
                        jQuery("#login_success").removeClass('hidden');
                        jQuery("#login_success").html(response.msg); 
                        setTimeout(function() {
                            window.location.href = '<?php echo base_url('dashboard');?>';
                        });
                    } 
                    else 
                    { 
                        jQuery("#login_error").removeClass('hidden');
                        jQuery("#login_error").html(response.msg);  
                    }
                },
                error: function() {
                    ajxLoader('hide','body');  
                    jQuery("#login_error").addClass('hidden');
                    jQuery("#login_error").html("Something went wrong. Please try again");  
                }
            });   
        }
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