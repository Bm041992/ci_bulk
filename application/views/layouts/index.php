<!DOCTYPE html>
<html lang="en">
<head>
	<!-- <meta charset="utf-8"> -->
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php if(isset($title)) { echo $title; } else { ?>Limitless - Responsive Web Application Kit<?php } ?></title>
	<noscript>
		<link href="<?php echo base_url('assets/css/no_js.css');?>" rel="stylesheet" type="text/css" >
		<div class="md" id="modal-one" aria-hidden="true">
            <div class="md-dialog">
                <div class="md-header">
                    <h2>Enable Javascript</h2>
                </div>
                <div class="md-body">
                    <h3 class="text-center">Warning!</h3>
                    <p>Sorry, an error has occured, Please Enable javascript in your browser to access website.</p>
                </div>
                <div class="md-footer"> 
                	<!-- <a href="#modal-one" class="btn-md">Ok</a> -->

                </div>
            </div>
        </div> 
    </noscript>
	<!-- <link rel="icon" href="<?php //echo base_url('assets/images/bulk_email_favicon.ico');?>" type="image/x-icon">
	
	<link rel="shortcut icon" href="<?php //echo base_url('assets/images/bulk_email_favicon.ico');?>" type="image/x-icon">  -->
	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	
	<link href="<?php echo base_url('assets/css/icons/fontawesome/styles.min.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('assets/css/icons/icomoon/styles.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('assets/css/bootstrap.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('assets/css/core.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('assets/css/components.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('assets/css/colors.css'); ?>" rel="stylesheet" type="text/css">
	<!-- <link rel="shortcut icon" type="image/png" href="<?php //echo base_url('assets/images/favicon.png');?>"/> -->
	<!-- /global stylesheets -->

	<!-- Custom stylesheets -->

	<link href="<?php echo base_url('assets/css/loader.css'); ?>" rel="stylesheet" type="text/css">
	<!-- <link href="<?php //echo base_url('assets/css/admin_custom.css'); ?>" rel="stylesheet" type="text/css"> -->
	<?php
	 	if(in_array($this->uri->segment(1),['contacts','send_emails']) && in_array($this->uri->segment(2),['add','edit']))
		{
			?>
			<link href="<?php echo base_url('assets/css/bootstrap-datepicker.css'); ?>" rel="stylesheet" type="text/css">
	<?php } ?>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-switch.min.css');?>">
	<?php
		if($this->uri->segment(2)=="dashboard") { ?>
		<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-slider.min.css'); ?>">
	<?php }?>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/responsive.dataTables.min.css'); ?>">
	<?php
		if($this->uri->segment(1)=="send_emails") 
		{ 
			?>
			<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css" rel="stylesheet"> -->
			<link rel="stylesheet" href="<?php echo base_url('assets/css/summernote.css'); ?>">
	<?php }?>
	<!-- End Custom stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/loaders/pace.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/core/libraries/jquery.min.js'); ?>"></script>
	
	<script type="text/javascript" src="<?php echo base_url('assets/js/core/libraries/bootstrap.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/loaders/blockui.min.js'); ?>"></script>
	
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
	
	<!-- Responsive extension JS -->
	<script src="<?php echo base_url('assets/js/dataTables.responsive.min.js'); ?>"></script>

	<!-- Responsive extension CSS -->
	
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/forms/selects/select2.min.js'); ?>"></script>

	<script type="text/javascript" src="<?php echo base_url('assets/js/core/app.js'); ?>"></script>
	
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/notifications/sweet_alert.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/forms/validation/validate.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/forms/validation/additional_methods.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/forms/styling/uniform.min.js'); ?>"></script>

	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/notifications/jgrowl.min.js'); ?>"></script>

	<script type="text/javascript" src="<?php //echo base_url('assets/js/plugins/forms/styling/switchery.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/forms/styling/switch.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/pages/datatables_basic.js'); ?>"></script>
	<!-- /theme JS files -->

</head>

<body>
	<!-- Main navbar -->
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href="<?php echo base_url();?>"><img src="<?php echo base_url();?>assets/images/logo_light.png" alt=""></a>

			<ul class="nav navbar-nav visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
				<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav">
				<li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
			</ul>

			<div class="navbar-right">
				<ul class="nav navbar-nav">
					

					<li class="dropdown dropdown-user">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<img src="<?= base_url('assets/images/placeholder.jpg'); ?>" alt="">
							<span class=""><?php echo $this->session->userdata('user_name');?></span>
							<i class="caret"></i>
						</a>

						<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="<?= base_url('profile/edit'); ?>"><i class="icon-user-plus"></i> My profile</a></li>
							<li><a href="<?= base_url('auth/logout'); ?>"><i class="icon-switch2"></i> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main sidebar -->
			<div class="sidebar sidebar-main">
				<div class="sidebar-content">
					
					<!-- User menu -->
					<div class="sidebar-user">
						<div class="category-content">
							<div class="media">
								<a href="#" class="media-left"><img src="<?php echo base_url();?>assets/images/placeholder.jpg" class="img-circle img-sm" alt=""></a>
								<div class="media-body">
									<span class="media-heading text-semibold "><?php echo $this->session->userdata('user_name');?></span>
									<div class="text-size-mini text-muted">
										<i class="icon-pin text-size-small"></i> &nbsp;Online
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /user menu -->

					<!-- Main navigation -->
					<div class="sidebar-category sidebar-category-visible">
						<div class="category-content no-padding">
							<ul class="navigation navigation-main navigation-accordion">

								<!-- Main -->
								<!-- <li class="navigation-header"><span></span> <i class="icon-menu" title="Main pages"></i></li> -->
								<li class="navigation-header"></li>
								<li <?php if(is_active_controller('dashboard')) { echo 'class="active"'; } ?> ><a href="<?php echo base_url('dashboard'); ?>"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
								<?php 
								if(isset($_SESSION['user_role']) && $_SESSION['user_role']==1) 
								{
									?>
									<li <?php if(is_active_controller('smtp_config')) { echo 'class="active"'; } ?> >
										<a href="<?php echo base_url('smtp_config'); ?>"><i class="fa fa-server"></i> <span>SMTP Configuration</span></a>
									</li>
								<?php } ?>
								<li <?php if(is_active_controller('contacts')) { echo 'class="active"'; } ?> >
									<a href="<?php echo base_url('contacts'); ?>"><i class="fa fa-phone"></i> <span>Contacts</span></a>
								</li>
								<li <?php if(is_active_controller('send_emails') && $this->uri->segment(2)=='') { echo 'class="active"'; } ?> >
									<a href="<?php echo base_url('send_emails'); ?>"><i class="fa fa-send"></i> <span>Send Emails</span></a>
								</li>
								<li <?php if(is_active_controller('birthday_templates')) { echo 'class="active"'; } ?> >
									<a href="<?php echo base_url('birthday_templates'); ?>"><i class="fa fa-birthday-cake"></i> <span>Birthday Templates</span></a>
								</li>
								<?php 
								if(isset($_SESSION['user_role']) && $_SESSION['user_role']==1) 
								{
									?>
									<!-- <li <?php //if(is_active_controller('send_emails') && $this->uri->segment(2)=='logs') { echo 'class="active"'; } ?> >
										<a href="<?php //echo base_url('send_emails/logs'); ?>"><i class="icon-home4"></i> <span>Logs</span></a>
									</li> -->
								
									<li <?php if(is_active_controller('Sub_admins')) { echo 'class="active"'; } ?> >
										<a href="<?php echo base_url('sub_admins'); ?>"><i class="fa fa-users"></i> <span>Sub Admins</span></a>
									</li>
								<?php } ?>
							</ul>
						</div>
					</div>
					<!-- /main navigation -->

				</div>
			</div>
			<!-- /main sidebar -->


			<!-- Main content -->
			<div class="content-wrapper">

					<?php //$this->load->view('folio/includes/alerts'); ?>
					
					<?php echo $content; ?>
			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

    <!-- Swal Alert text -->
    <input type="hidden" name="swal_title" id="swal_title" value="Are you sure you want to delete this record?">
    <input type="hidden" name="swal_text" id="swal_text" value="You will not be able to recover this record after deletion.">
    <input type="hidden" name="swal_cancelButtonText" id="swal_cancelButtonText" value="No, Cancel It">
    <input type="hidden" name="swal_confirmButtonText" id="swal_confirmButtonText" value="Yes, I am sure">
    <!-- End Swal Alert text -->

    <script>
		var baseUrl= "<?php echo base_url(); ?>";
	</script>

	<script type="text/javascript">
		// var url_segment='<?php //echo $this->uri->segment(2); ?>';
		
		$.validator.setDefaults({
		  ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
		        errorClass: 'validation-error-label',
		        successClass: 'validation-valid-label',
		        highlight: function(element, errorClass) {
		            $(element).removeClass(errorClass);
		        },
		        unhighlight: function(element, errorClass) {
		            $(element).removeClass(errorClass);
		        },

		        // Different components require proper error label placement
		        errorPlacement: function(error, element) {

		            // Styled checkboxes, radios, bootstrap switch
		            if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container') ) {
		                if(element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
		                    error.appendTo( element.parent().parent().parent().parent() );
		                }
		                 else {
		                    error.appendTo( element.parent().parent().parent().parent().parent() );
		                }
		            }

		            // Unstyled checkboxes, radios
		            else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
		                error.appendTo( element.parent().parent().parent() );
		            }

		            // Input with icons and Select2
		            else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
		                error.appendTo( element.parent() );
		            }

		            // Inline checkboxes, radios
		            else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
		                error.appendTo( element.parent().parent() );
		            }

		            // Input group, styled file input
		            else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
		                error.appendTo( element.parent().parent() );
		                
		            }
		            
		            else {
		                error.insertAfter(element);
		            }
		        },
		        validClass: "validation-valid-label",
		        success: function(label) {
		        	label.remove();
		            // label.addClass("validation-valid-label").text("")
		        },
		});
		$(function() {

			// Style checkboxes and radios
			$('.styled').uniform();
			
			//init radio button
			reInitRadio();

			// init swutch checkbox
			$(".switch").bootstrapSwitch();


		    // reset form on modal close 
		   	$('.modal').on('hidden.bs.modal', function(){
		   		//var target = $( event.target );
  				if ( $(this).is( "form" ) ) {
		   			$(this).find('form')[0].reset();
		   		}
			});

		   	// Default file input style
			$(".file-styled").uniform({
				fileButtonClass: 'action btn btn-default'
			});


			// Primary file input
			$(".file-styled-primary").uniform({
				fileButtonClass: 'action btn bg-blue'
			});


			$( document ).ajaxComplete(function() {
				$('.styled').uniform();

				// init swutch checkbox
				$(".switch").bootstrapSwitch();

				// var switches = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
				// switches.forEach(function(html) {
					
				//     var switchery = new Switchery(html);
				// });

			});


		});	

		function reInitRadio(){
			// Danger
		    $(".control-danger").uniform({
		        radioClass: 'choice',
		        wrapperClass: 'border-danger-600 text-danger-800'
		    });

		    // Success
		    $(".control-success").uniform({
		        radioClass: 'choice',
		        wrapperClass: 'border-success-600 text-success-800'
		    });

		    // Primary
		    $(".control-primary").uniform({
		        radioClass: 'choice',
		        wrapperClass: 'border-primary-600 text-primary-800'
		    });

		    // Info
		    $(".control-info").uniform({
		        radioClass: 'choice',
		        wrapperClass: 'border-info-600 text-info-800'
		    });
		}

		/* jQuery switch */
			var switches = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
			switches.forEach(function(html) {
				
			    var switchery = new Switchery(html);
			});

		/**
		 * Generates the notification on activity
		 *
		 * @param {str}  message    The message
		 * @param {str}  alertType  The alert type
		 */
		function jGrowlAlert(message, alertType) {

		    var header_msg = alertType == 'success' ? 'Success!' : 'Oh Snap!';
		    $.jGrowl(message, {
		        header: header_msg,
		        theme: 'bg-' + alertType
		    });
		}
		/**
		 * Selects/deselects all the checkboxes
		 *
		 * @param {obj}  obj  The checkbox object
		 */
		function select_all(obj) {

		    if (obj.checked) {
		        $(".checkbox").each(function() {
		            $(this).prop("checked", "checked");
		            $(this).parent().addClass("checked");
		        });
		    } else {
		        $('.checkbox').each(function() {
		            this.checked = false;
		            $(this).parent().removeClass("checked");
		        });
		    }
		}
	</script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/loadingoverlay.min.js"></script>
	<!-- Custom js -->
	<script>
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

		jQuery.validator.addMethod("noSpecialChars", function(value, element) {
			// return true - means the field passed validation
			// return false - means the field failed validation and it triggers the error
			// return this.optional(element) || /^([a-zA-Z0-9 _&?=(){},.|*+-]+)$/.test(value);
			 return this.optional(element) || /^([a-zA-Z0-9 _&?=(){},.|*+\/-]+)$/.test(value);
		}, "Special Characters not allowed");
		jQuery.validator.addMethod("noHTML", function(value, element) {
			// return this.optional(element) || /^[a-zA-Z0-9 _&?=(){},.|*+\-]*$/.test(value);
			//return this.optional(element) || /^[a-zA-Z0-9\s\-\.\_]+$/.test(value);
			return this.optional(element) || /^[a-zA-Z0-9\s\-_]+$/.test(value);
		}, "HTML tags and special characters are not allowed");

		// jQuery.validator.addMethod("noHTMLtags", function(value, element){
		// 	if(this.optional(element) || /<\/?[^>]+(>|$)/g.test(value))
		// 	{
		// 		return false;
		// 	} 
		// 	else 
		// 	{
		// 		return true;
		// 	}
		// }, "HTML tags are Not allowed");
		jQuery.validator.addMethod("noHTMLtags", function(value, element) {
			return this.optional(element) || !/[<>]/g.test(value);
		}, "HTML tags or angle brackets < > are not allowed");

		jQuery.validator.addMethod("customEmail", function(value, element, param) {
			return value.match(/^(?!\.)[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/);
		},'Enter Correct E-mail Address');

		// jQuery.validator.addMethod("onlyNumber", function(value, element) {
		// 	return this.optional(element) || /^([0-9+-- /]+)$/.test(value);
		// }, 'Only numbers,space,+ and - are allowed');
		jQuery.validator.addMethod("onlyNumber", function(value, element) {
			return this.optional(element) || /^([0-9+\-\s\(\)]+)$/.test(value);
		}, 'Only numbers, space, +, -, and ( ) are allowed');

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

	</script>
	<?php
	 	if(in_array($this->uri->segment(1),['contacts','send_emails']) && in_array($this->uri->segment(2),['add','edit']))
		{
			?>
			<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-datepicker.js'); ?>"></script>
		<?php }
		if($this->uri->segment(1) == '' || $this->uri->segment(1) == 'dashboard')
		{
			?>
			<script type="text/javascript" src="<?php echo base_url('assets/js/custom/dashboard.js'); ?>"></script>
			<?php
		}
		else if($this->uri->segment(1) == 'smtp_config')
		{
			?>
			<script type="text/javascript" src="<?php echo base_url('assets/js/custom/smtp_config.js'); ?>"></script>
			<?php
		}
		else if($this->uri->segment(1) == 'contacts')
		{
			?>
			<script type="text/javascript" src="<?php echo base_url('assets/js/custom/contacts.js'); ?>"></script>
			<?php
		}
		else if($this->uri->segment(1) == 'send_emails' || $this->uri->segment(1) == 'birthday_templates')
		{
			?>
			<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/editors/summernote/summernote.min.js'); ?>"></script>
			<?php
			if($this->uri->segment(1) == 'send_emails')
			{
				?>
				<script type="text/javascript" src="<?php echo base_url('assets/js/custom/send_emails.js'); ?>"></script>
			<?php }
			else if($this->uri->segment(1) == 'birthday_templates')
			{
				?>
				<script type="text/javascript" src="<?php echo base_url('assets/js/custom/birthday_templates.js'); ?>"></script>
			<?php
			}
		}
		else if($this->uri->segment(1) == 'profile')
		{
			?>
			<script type="text/javascript" src="<?php echo base_url('assets/js/custom/profile.js'); ?>"></script>
		<?php } 
		else if($this->uri->segment(1) == 'sub_admins')
		{
			?>
			<script type="text/javascript" src="<?php echo base_url('assets/js/custom/sub_admins.js'); ?>"></script>
		<?php } 
	?>

	<!--End Custom js-->
	<script>
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
</body>
</html>
