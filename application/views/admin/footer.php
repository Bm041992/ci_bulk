                    

</body>
</html>

<div id="add_county_modal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog" width="50px">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add</h5>
            </div>

            <form  id="add_county_form" method="POST">
                <div class="modal-body">
                      <div class="alert alert-danger" id="county_error_msg" style="display:none">
                        
                      </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>SMTP Username</label>
                                <small class="req text-danger">*</small>                               
                                        
                                <input type="text" id="county_name" class="form-control" name="county_name" autocomplete="off">
                                        
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer text-left">                    
                    <button name="add_county_submit" type="submit" id="add_county" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
           
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/tables/datatables/datatables.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/pages/datatables_basic.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/forms/selects/select2.min.js'); ?>"></script>
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
</script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/custom/smtp_config.js"></script>
