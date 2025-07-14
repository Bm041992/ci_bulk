$(document).ready(function(){
    
    var table=$('#sub_admin_list_table').DataTable({
        responsive: true,
        // Processing indicator
        "processing": true,
        // DataTables server-side processing mode
        "serverSide": true,
        // Initial no order.
        "order": [],
        "searching": true,
        
        // Load data from an Ajax source
        "ajax": {
            "url": baseUrl +"sub_admins/getLists",
            "type": "POST"
        },
        //Set column definition initialisation properties
        "columnDefs": [
            { 
                "targets": [3],
                className: "text-center",
                "orderable": false,
            },
            {
                "targets": [7],
                className: "text-center",
                "orderable": false
            }           
        ],
        "drawCallback": function(settings) {
            initSwitches();
        }
    });   

    table.on('responsive-display.dt', function () {
        initSwitches();
    });
});
function initSwitches() 
{
    $('.sub-admin-status-switch').each(function () {
        if (!$(this).hasClass('bootstrap-switch')) {
            $(this).bootstrapSwitch();
        }
    });
    // $('.smtp-status-switch').bootstrapSwitch('destroy').bootstrapSwitch();

    $('.sub-admin-status-switch').on('switchChange.bootstrapSwitch', function (event, state) {
        const $switch = $(this);
        const sub_admin_id = $(this).data('id');
        const status = state ? 1 : 0;
        const previousState = !state ;
        
        // Send AJAX request to update status
        $.ajax({
            url: baseUrl + "sub_admins/update_status",
            type: "POST",
            data: { sub_admin_id: sub_admin_id, status: status },
            dataType:'JSON',
            beforeSend: function () {
                ajxLoader('show','body');    
            }, 
            success: function(response) {
                ajxLoader('hide','body');    
            
                if(response.success) 
                {
                    swal({
                        title: "Success!",
                        text: response.msg,
                        type: "success"
                    });
                } 
                else 
                { 
                    swal({
                        title: "Oops!",
                        text: response.msg,
                        type: "error" 
                    });
                    
                    $switch.bootstrapSwitch('state', previousState, true); 
                }
            },
            error: function() {
                ajxLoader('hide','body'); 
                swal({
                    title: "Oops!",
                    text: 'Something went wrong.',
                    type: "error" 
                });
                    $switch.bootstrapSwitch('state', previousState, true); 
            }
        });
    });
}

var selectedCountry = (jQuery('#edit_sub_admin_country').length > 0) ? jQuery('#edit_sub_admin_country').val() : '';
var selectedState = (jQuery('#edit_sub_admin_state').length > 0) ? jQuery('#edit_sub_admin_state').val() : '';
var selectedCity = (jQuery('#edit_sub_admin_city').length > 0) ? jQuery('#edit_sub_admin_city').val() : '';

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
        $('#sa_country').select2('destroy');
        $('select[name="country"]').html(html);
        $('#sa_country').select2({
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
        $('#sa_state').select2('destroy');
        $('select[name="state"]').html(html);
        $('#sa_state').select2({
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
        $('#sa_city').select2('destroy');
        $('select[name="city"]').html(html);
        $('#sa_city').select2({
            placeholder: 'Select City',
            width: '100%',
            allowClear: true
        });
    });
}

$("#add_sub_admin_form").validate({
    rules: {
        username:{
            required: true,
            noSpace:true,
            noHTML:true,
            minlength: 2,
            maxlength:25
        },
        email: {
            required: true,
            customEmail:true
        },
        password: {
            required: true,
            minlength: 8,
            maxlength:20
        },
        email_limit: {
            required: true,
            number:true,
            min: 0,
            max:100000
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
    submitHandler: function(form) {
       
        var ajaxSubmit=baseUrl+"sub_admins/save_sub_admin"
        jQuery.ajax({
            url: ajaxSubmit,
            type: 'POST',
            data:jQuery("#add_sub_admin_form").serialize(),
            dataType:'JSON',
            beforeSend: function () {
                ajxLoader('show','body');   
            }, 
            success: function(response) {

                ajxLoader('hide','body');    
                    
                if(response.success) 
                {
                    //jQuery("#add_sub_admin_form")[0].reset();
                    swal({
                        title: "Success!",
                        text: response.msg,
                        type: "success" 
                    },function () {
                        window.location.href = baseUrl + 'sub_admins';
                    });  
                } 
                else 
                {
                    swal({
                        title: "Oops!",
                        text: response.msg,
                        type: "error" 
                    });          
                }
            },
            error: function(response) {
                ajxLoader('hide','body');  
                swal({
                    title: "Oops!",
                    text: 'Something went wrong.',
                    type: "error" 
                }); 
            }
        });
    }
});

if($('#sa_country').length > 0)
{
    $('#sa_country').select2({
        placeholder: 'Select Country',
        width: '100%',
        allowClear: true
    });

    getCountries();

    $('#sa_state').select2({
        placeholder: 'Select State',
        width: '100%',
        allowClear: true
    });

    $('#sa_city').select2({
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

$('#sa_city').on('change', function() {
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

jQuery(document).on('click','.delete_sub_admin',function(){

    let ajaxSubmit=baseUrl+"sub_admins/delete_sub_admin";
    let sub_admin_id=jQuery(this).attr('data-id');

    swal({
        title: 'Are you sure?',
        text: 'This will delete the configuration.',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it'
    },function(result) {
    
        if (result) {
            jQuery.ajax({
                url: ajaxSubmit,
                type: 'POST',
                data:{sub_admin_id:sub_admin_id},
                dataType:'JSON',
                //async:false,
                beforeSend: function () {
                    ajxLoader('show','body');    
                }, 
                success: function(response) {

                    ajxLoader('hide','body');    
                   
                    if(response.success) 
                    {
                        $('#sub_admin_list_table').DataTable().ajax.reload();
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