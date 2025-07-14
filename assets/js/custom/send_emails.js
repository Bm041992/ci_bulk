
if($('#email_message').length > 0)
{
    $('#email_message').summernote({
        height: 300
    });
}

if($('#email_to').length > 0)
{
    $("#email_to").select2({
        placeholder: "Select Contacts",
        // multiple: true,
        width: '100%',
        allowClear: true
    });

    $('#email_to').on('select2:select', function(e) {
        if (e.params.data.id === "select_all") {
            // Select all options except "Select All" itself
            var allValues = [];
            $('#email_to option').each(function() {
                if ($(this).val() !== 'select_all') {
                    allValues.push($(this).val());
                }
            });

            $('#email_to').val(allValues).trigger('change');
        }
    });

    $('#email_to').on('change', function(e) {
        var selected = $(this).val();
        if (selected && selected.includes('select_all')) {
            selected = selected.filter(val => val !== 'select_all');
            $(this).val(selected).trigger('change');
        }
    });
}

$.validator.addMethod("atLeastOneEmailRequired", function(value, element, params) {
    var contactsSelected = $('#email_to').val(); // array or null
    var manualEmails = $('#manual_emails').val().trim();

    return (contactsSelected && contactsSelected.length > 0) || manualEmails.length > 0;
}, "Please provide at least one contact or manual email.");

$("#send_email_form").validate({
    rules: {
        schedule_date: {
            required: true
        },
        email_subject: {
            required: true,
            noSpace:true,
            noHTMLtags:true,
            minlength: 5,
            maxlength:100
        },
        email_message: {
            required: true
        },
        // 'email_to[]': {
        //     required: true
        // }
        email_to: {
            atLeastOneEmailRequired: true
        },
        manual_emails: {
            noHTMLtags:true,
            atLeastOneEmailRequired: true
        }
    },
    errorPlacement: function(error, element) {
        if (element.attr("name") == "schedule_date") 
        {
            error.appendTo("#sdate_error");
        } 
        else if (element.attr("name") == "email_message") 
        {
            error.appendTo("#email_message_error");
        } 
        else if (element.attr("name") == "email_to[]" || element.attr("name") == "manual_emails") 
        {
            error.appendTo("#email_to_error");
        } 
        else
        {
            error.insertAfter(element);
        }
    },
    submitHandler: function(form) {
       
        var ajaxSubmit=baseUrl+"send_emails/send"
        jQuery.ajax({
            url: ajaxSubmit,
            type: 'POST',
            data:jQuery("#send_email_form").serialize(),
            dataType:'JSON',
            beforeSend: function () {
                ajxLoader('show','body');   
            }, 
            success: function(response) {

                ajxLoader('hide','body');    
                    
                if(response.success) 
                {
                    jQuery("#send_email_form")[0].reset();
                    swal({
                        title: "Success!",
                        text: response.msg,
                        type: "success" 
                    },function () {
                        window.location.href = baseUrl + 'send_emails';
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

$('#email_to').change(function() { 
    $('#email_to').valid();
    $('#manual_emails').valid(); 
});

$('#manual_emails').change(function() { 
    $('#email_to').valid();
    $('#manual_emails').valid(); 
});

if($('#schedule_date').length > 0)
{
    jQuery('#schedule_date').datepicker({
        format: 'dd-mm-yyyy',
        startDate: new Date(),
        todayHighlight: true,
        autoclose: true,             
    }).on('changeDate', function(e) {
        jQuery(this).valid(); 
    });
}

$(document).ready(function(){
    if($('#email_contents_list_table').length > 0)
    {
        $('#email_contents_list_table').DataTable({
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
                "url": baseUrl +"send_emails/get_email_contents",
                "type": "POST"
            },
            //Set column definition initialisation properties
            "columnDefs": [
                {
                    "targets": [0],
                    className: "text-center",
                    "orderable": false
                }, 
                {
                    "targets": [2],
                    "orderable": false
                }, 
                {
                    "targets": [3],
                    className: "text-center",
                    "orderable": false
                },
                {
                    "targets": [4],
                    width: "10%",
                    className: "text-center",
                    "orderable": false
                }           
            ]
        });  
    }

    if($('#failed_email_error_log_list_table').length > 0)
    {
        $('#failed_email_error_log_list_table').DataTable({
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
                "url": baseUrl +"send_emails/get_error_log_lists",
                "type": "POST"
            },
            //Set column definition initialisation properties
            "columnDefs": [
                {
                    "targets": [4],
                    // className: "text-center",
                    "orderable": false
                }, 
                {
                    "targets": [5],
                    //className: "text-center",
                    "orderable": false
                }           
            ]
        });   
    }
});

jQuery(document).on('click','.delete_email_content',function(){

    let ajaxSubmit=baseUrl+"send_emails/delete_email_content";
    let ec_id=jQuery(this).attr('data-id');

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
                data:{ec_id:ec_id},
                dataType:'JSON',
                async:false,
                beforeSend: function () {
                    ajxLoader('show','body');    
                }, 
                success: function(response) {

                    ajxLoader('hide','body');    
                    
                    if(response.success) 
                    {
                        $('#email_contents_list_table').DataTable().ajax.reload();
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