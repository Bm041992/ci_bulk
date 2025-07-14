$(document).ready(function(){
    
    $('#contacts_list_table').DataTable({
        responsive: true,
        // Processing indicator
        "processing": true,
        // DataTables server-side processing mode
        "serverSide": true,
        // Initial no order.
        "order": [],
        "searching": true,
        responsive: {
            details: {
                type: 'inline' // or 'column' if using a control column
            }
        },
        // Load data from an Ajax source
        "ajax": {
            "url": baseUrl +"contacts/getLists",
            "type": "POST"
        },
        //Set column definition initialisation properties
        "columnDefs": [
            //{ 
            // "targets": [0],
            //     "orderable": false
            // },
            {
                "targets": [5],
                className: "text-center",
                "orderable": false
            }, 
            {
                "targets": [6],
                className: "text-center",
                "orderable": false
            }           
        ]
    });   
});

var selectedCountry = (jQuery('#edit_contact_country').length > 0) ? jQuery('#edit_contact_country').val() : '';
var selectedState = (jQuery('#edit_contact_state').length > 0) ? jQuery('#edit_contact_state').val() : '';
var selectedCity = (jQuery('#edit_contact_city').length > 0) ? jQuery('#edit_contact_city').val() : '';

function getCountries() {
    $.ajax({
        url : baseUrl+'dashboard/getCountries',
        method: 'get'
    }).done(function(getdata){
        var html = "<option value=''>Select Country</option>";

        $.each(getdata, function(key, val) {

            var selected = "";
            if(selectedCountry == val.country_id){
                selected = "selected";
            }
            
            html += "<option value='"+val.country_id+"' "+selected+" >"+val.name+"</option>";
        });
        $('#contact_country').select2('destroy');
        $('select[name="country"]').html(html);
        $('#contact_country').select2({
            placeholder: 'Select Country',
            width: '100%',
            allowClear: true
        });
    });
}

function getStates(countryId) {
    $.ajax({
        url : baseUrl+'dashboard/getStates/'+countryId,
        method: 'get'
    }).done(function(getdata){
        var html = '<option value="">Select State</option>';
        $.each(getdata, function(key, val) {

            var selected = "";
            if(selectedState == val.state_id){
                selected = "selected";
            }

            html += "<option value='"+val.state_id+"' "+selected+" >"+val.name+"</option>";
        });
        $('#contact_state').select2('destroy');
        $('select[name="state"]').html(html);
        $('#contact_state').select2({
            placeholder: 'Select State',
            width: '100%',
            allowClear: true
        });
    });
}

function getCities(stateId) {
    $.ajax({
        url : baseUrl+'dashboard/getCities/'+stateId,
        method: 'get'
    }).done(function(getdata){
        var html = '<option value="">Select City</option>';
        $.each(getdata, function(key, val) {

            var selected = "";
            if(selectedCity == val.city_id)
            {
                selected = "selected";
            }

            html += "<option value='"+val.city_id+"' "+selected+" >"+val.name+"</option>";
        });
        html += "<option value='other'>Other</option>";
        $('#contact_city').select2('destroy');
        $('select[name="city"]').html(html);
        $('#contact_city').select2({
            placeholder: 'Select City',
            width: '100%',
            allowClear: true
        });
    });
}

if($('#add_contact_form').length > 0)
{
    jQuery('#add_contact_form').validate({
        rules:{
            contact_name: {
                required: true,
                noSpace:true,
                noHTML:true,
                maxlength: 50
            },
            birthday: {
                required: true
            },
            company_name: {
                required: true,
                noSpace:true,
                noHTML:true,
                maxlength: 50
            },
            job_title: {
                required: true,
                noSpace:true,
                noHTML:true,
                maxlength: 50
            },
            contact_address: {
                required: true,
                noSpace:true,
                noSpecialChars:true,
                maxlength: 1000
            },
            contact_notes: {
                maxlength: 10000
            },
            country: {
                required: true
            },
            state: {
                required: true
            },
            city: {
                required: true
            }
        },
        messages:{
            contact_name: {
                required: 'Name field is required'
            },
            birthday: {
                required: 'Birth Date field is required'
            },
            company_name: {
                required: 'Company name field is required'
            },
            job_title: {
                required: 'Job title field is required'
            },
            contact_address: {
                required: 'Address field is required'
            },
            country: {
                required: 'Country field is required'
            },
            state: {
                required: 'State field is required'
            },
            city: {
                required: 'City field is required'
            }
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "birthday") 
            {
                error.appendTo("#birthday_error");
            } 
            else if (element.attr("name") == "country") 
            {
                error.appendTo("#contact_country_error");
            } 
            else if (element.attr("name") == "state") 
            {
                error.appendTo("#contact_state_error");
            } 
            else if (element.attr("name") == "city") 
            {
                error.appendTo("#contact_city_error");
            } 
            else
            {
                error.insertAfter(element);
            }
        },
        submitHandler:function(form){
        
            let ajaxSubmit=baseUrl+"Contacts/save_contact";

            jQuery.ajax({
                url: ajaxSubmit,
                type: 'POST',
                data:$('#add_contact_form').serialize(),
                dataType:'JSON',
                beforeSend: function () {
                    ajxLoader('show','body');    
                }, 
                success: function(response) {

                    ajxLoader('hide','body');    
                    
                    if(response.success) 
                    {
                        jQuery("#add_contact_form")[0].reset();
                        
                        swal({
                            title: "Success!",
                            text: response.msg,
                            type: "success" 
                        },function () {
                            window.location.href = baseUrl+'contacts';
                        });
                    } 
                    else 
                    { 
                        swal({
                            title: "Oops!",
                            text:  response.msg,
                            type: "error" 
                        });
                    }
                },
                error: function() {
                    ajxLoader('hide','body');  
                    swal({
                        title: "Error!",
                        text:  'Oops! Something went wrong.',
                        type: "error" 
                    });
                }
            });   
        }
    });
}

if($('#contact_country').length > 0)
{
    $('#contact_country').select2({
        placeholder: 'Select Country',
        width: '100%',
        allowClear: true
    });

    getCountries();

    $('#contact_state').select2({
        placeholder: 'Select State',
        width: '100%',
        allowClear: true
    });

    $('#contact_city').select2({
        placeholder: 'Select City',
        width: '100%',
        allowClear: true
    });

    $('select[name="country"]').on('change', function(){
        jQuery(this).valid(); 
        getStates($(this).val());
    });

    $('select[name="state"]').on('change', function(){
        jQuery(this).valid(); 
        getCities($(this).val());
    });

    $('select[name="city"]').on('change', function(){
        jQuery(this).valid(); 
    });
}

if(selectedCountry != '')
{
    getStates(selectedCountry);
}

if(selectedState != '')
{
    getCities(selectedState);
}

if($('#contact_bdate').length > 0)
{
    jQuery('#contact_bdate').datepicker({
        format: 'dd-mm-yyyy',
        endDate: new Date(),
        todayHighlight: true,
        autoclose: true,             
    }).on('changeDate', function(e) {
        jQuery(this).valid(); 
    });
}

$('#contact_city').on('change', function() {
    if ( this.value == 'other') 
    {
        $("#manual-city-input").removeClass('hidden');
        $('#city_manual').rules("add", {
            required: true,
            noSpace: true,
            noHTML:true,
            minlength:2,
            maxlength: 25,
            messages: {
                required: "Manual city field is required"
            }
        });
    } 
    else 
    {
        $('#city_manual').rules("remove");
        $("#manual-city-input").addClass('hidden');
    }
});

jQuery.validator.addMethod("uniqueEmail", function(value, element) {
    let isDuplicate = false;
    let currentVal = value.trim().toLowerCase();
    let seen = [];

    $('.contact_email').each(function() {
        let val = $(this).val().trim().toLowerCase();
        if (val !== '') {
            if (seen.includes(val)) {
                if (val === currentVal && element === this) {
                    isDuplicate = true;
                }
            }
            seen.push(val);
        }
    });

    return !isDuplicate;
}, "Duplicate emails are not allowed");

// This needs to go AFTER the dynamic inputs are rendered
$('.contact_email_type').each(function() {
    $(this).rules("add", {
        required: true,
        noSpace: true,
        noHTML:true,
        //noHTMLtags: true,
        maxlength: 25,
        messages: {
            required: "Enter Email Type"
        }
    });
});

$('.contact_email').each(function() {
    $(this).rules("add", {
        required: true,
        customEmail: true,
        messages: {
            required: "Email field is required"
        }
    });
});

$('.contact_type').each(function() {
    $(this).rules("add", {
        required: true,
        noSpace: true,
        noHTML:true,
        //noHTMLtags: true,
        maxlength: 25,
        messages: {
            required: "Enter Phone Type"
        }
    });
});

$('.contact_number').each(function() {
    $(this).rules("add", {
        required: true,
        onlyNumber: true,
        messages: {
            required: "Enter Phone Number"
        }
    });
});

$('.add-more-contact-email-btn').on('click', function(){
    let html = '';
    html += '<div class="row mb-10">';
        html += '<div class="col-sm-4">';
            html += '<input class="form-control contact_email_type" type="text" name="contact_email_type[]" placeholder="Type (personal, work..)" value="" autocomplete="off" />';
        html += '</div>';
        html += '<div class="col-sm-6">';
            html += '<input class="form-control contact_email" type="email" name="contact_email[]" placeholder="Email" value="" autocomplete="off" />';
        html += '</div>';
    
        html += '<div class="col-sm-2">';
            html += '<i class="fa fa-times pull-right remove-more-email-icon-btn"></i>';
        html += '</div>';
    html += '</div>';

    $('.add-more-email-container').append(html);

    let c_cnt = 0 ;
    jQuery(".contact_email_type").each(function(i,v){
        $(this).attr("name","contact_email_type["+c_cnt+"]");
        $(this).attr("data-index",c_cnt);

        $("#add_contact_form").validate(); 
        $(this).rules("add", {
            required: true,
            noSpace:true,
            noHTML:true,
            //noHTMLtags:true,
            maxlength: 25,
            messages:{
                required:'Enter Email Type'
            }
        });
        c_cnt++;
    }); 

    let d_cnt = 0 ;
    jQuery(".contact_email").each(function(i,v){
        $(this).attr("name","contact_email["+d_cnt+"]");
        $(this).attr("data-index",d_cnt);

        $("#add_contact_form").validate(); 
        $(this).rules("add", {
                required:true,
                customEmail:true,
                uniqueEmail: true,
                messages:{
                    required:'Email field is required'
                }
        });
        d_cnt++;
    }); 
});

$(document).on('click', '.remove-more-email-icon-btn', function(){
    $(this).parent('div').parent('div.row').remove();
});

jQuery.validator.addMethod("uniquePhone", function(value, element) {
    let isDuplicate = false;
    let currentVal = value.trim().toLowerCase();
    let seen = [];

    $('.contact_number').each(function() {
        let val = $(this).val().trim().toLowerCase();
        if (val !== '') {
            if (seen.includes(val)) {
                if (val === currentVal && element === this) {
                    isDuplicate = true;
                }
            }
            seen.push(val);
        }
    });

    return !isDuplicate;
}, "Duplicate phone numbers are not allowed");

$('.add-more-contact-phone-btn').on('click', function(){
    let html = '';
    html += '<div class="row mb-10">';
        html += '<div class="col-sm-4">';
            html += '<input class="form-control contact_type" type="text" name="contact_type[]" placeholder="Type (mobile, work..)" value="" autocomplete="off" />';
        html += '</div>';
        html += '<div class="col-sm-6">';
            html += '<input class="form-control contact_number" type="text" name="contact_number[]" placeholder="Phone" value="" autocomplete="off" />';
        html += '</div>';
    
        html += '<div class="col-sm-2">';
            html += '<i class="fa fa-times pull-right remove-more-phone-icon-btn"></i>';
        html += '</div>';
    html += '</div>';

    $('.add-more-phone-container').append(html);

    let d_cnt = 0 ;
    jQuery(".contact_type").each(function(i,v){
        $(this).attr("name","contact_type["+d_cnt+"]");
        $(this).attr("data-index",d_cnt);

        $("#add_contact_form").validate(); 
        $(this).rules("add", {
            required: true,
            noSpace:true,
            noHTML:true,
            // noHTMLtags:true,
            maxlength: 25,
            messages:{
                required:'Enter Phone Type'
            }
        });
        d_cnt++;
    }); 
    let n_cnt = 0 ;
    jQuery(".contact_number").each(function(i,v){
        $(this).attr("name","contact_number["+n_cnt+"]");
        $(this).attr("data-index",n_cnt);

        $(this).rules("add", {
            required: true,
            onlyNumber:true,
            uniquePhone: true,
            messages:{
                required:'Enter Phone Number'
            }
        });
        n_cnt++;
    }); 
});

$(document).on('click', '.remove-more-phone-icon-btn', function(){
    $(this).parent('div').parent('div.row').remove();
});

jQuery(document).on('click','.delete_contact',function(){

    let ajaxSubmit=baseUrl+"contacts/delete_contact";
    let contact_id=jQuery(this).attr('data-id');

    swal({
        title: 'Are you sure?',
        text: 'This will delete the contact.',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it'
    },function(result) {
    
        if (result) {
            jQuery.ajax({
                url: ajaxSubmit,
                type: 'POST',
                data:{contact_id:contact_id},
                dataType:'JSON',
                async:false,
                beforeSend: function () {
                    ajxLoader('show','body');    
                }, 
                success: function(response) {

                    ajxLoader('hide','body');    
                    
                    if(response.success) 
                    {
                        $('#contacts_list_table').DataTable().ajax.reload();
                        setTimeout(function () {
                            swal({
                                title: "Success!",
                                text: response.msg,
                                type: "success" 
                            });
                            
                        }, 100); 
                    } 
                    else 
                    { 
                        setTimeout(function () {
                            swal({
                                title: "Oops!",
                                text: response.msg,
                                //icon: "error",
                                type: "error" 
                            });
                        }, 100); 
                    }
                },
                error: function() {
                    ajxLoader('hide','body');  
                    setTimeout(function () {
                            swal({
                                title: "Oops!",
                                text: 'Something went wrong.',
                                type: "error" 
                            });
                    }, 100); 
                }
            });  
        }
    });
});

$("#import-contacts-csv-form").validate({
    rules: {
        contacts_file: {
            required: true,
            extension:'csv',
            filesize: 2097152
        }
    },
    messages: {
        contacts_file: {
            required: 'Please upload a file',
            extension: "Only .csv files are allowed."
        }
    },
    submitHandler: function(form) {

       var ajaxSubmit=baseUrl+"contacts/import_contacts_csv";
        
        var form1 = $('#import-contacts-csv-form')[0];
        var formData = new FormData(form1);
        jQuery.ajax({
            url: ajaxSubmit,
            type: 'POST',
            data:formData,
            dataType:'JSON',
            contentType: false,
            processData: false,
            beforeSend: function () 
            {
                ajxLoader('show','body');
            }, 
            success: function(response) 
            {
                ajxLoader('hide','body');
                if(response.success) 
                {
                    jQuery("#import-contacts-csv-form")[0].reset();
                    
                    swal({
                        title: "Success!",
                        text: response.msg,
                        type: "success" 
                    }, function () {
                        window.location.href = baseUrl+'contacts';
                    });
                }
                else
                {
                    swal({
                        title: "Error!",
                        text:  response.msg,
                        type: "error" 
                    });
                } 
            },
            error: function()
            {
                ajxLoader('hide','body');  
                swal({
                    title: "Error!",
                    text:  'Oops! Something went wrong.',
                    type: "error" 
                });
            }
        }); 
    }
});