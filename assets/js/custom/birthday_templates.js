
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

if($('#smtp_config').length > 0)
{
    $("#smtp_config").select2({
        placeholder: "Select SMTP Configuration",
        // multiple: true,
        width: '100%',
        allowClear: true
    });
}

$("#add_bt_form").validate({
    rules: {
        email_subject: {
            required: true,
            noSpace:true,
            noHTMLtags:true,
            minlength: 5,
            maxlength:100
        },
        // smtp_config: {
        //     required: true
        // },
        email_message: {
            required: true
        },
        // 'email_to[]': {
        //     required: true
        // }
    },
    errorPlacement: function(error, element) {
        if (element.attr("name") == "email_message") 
        {
            error.appendTo("#email_message_error");
        } 
        // else if (element.attr("name") == "smtp_config") 
        // {
        //     error.appendTo("#smtp_config_error");
        // } 
        // else if (element.attr("name") == "email_to[]") 
        // {
        //     error.appendTo("#email_to_error");
        // } 
        else
        {
            error.insertAfter(element);
        }
    },
    submitHandler: function(form) {
       
        var ajaxSubmit=baseUrl+"birthday_templates/save_template"
        jQuery.ajax({
            url: ajaxSubmit,
            type: 'POST',
            data:jQuery("#add_bt_form").serialize(),
            dataType:'JSON',
            beforeSend: function () {
                ajxLoader('show','body');   
            }, 
            success: function(response) {

                ajxLoader('hide','body');    
                    
                if(response.success) 
                {
                    jQuery("#add_bt_form")[0].reset();
                    swal({
                        title: "Success!",
                        text: response.msg,
                        type: "success" 
                    },function () {
                        window.location.href = baseUrl + 'birthday_templates';
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

$(document).ready(function(){
    if($('#bt_list_table').length > 0)
    {
        $('#bt_list_table').DataTable({
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
                "url": baseUrl +"birthday_templates/get_list",
                "type": "POST"
            },
            //Set column definition initialisation properties
            "columnDefs": [
                {
                    "targets": [3],
                    width: "15%",
                    className: "text-center",
                    "orderable": false
                }           
            ]
        });  
    }
});

jQuery(document).on('click','.delete_bt',function(){

    let ajaxSubmit=baseUrl+"birthday_templates/delete_template";
    let template_id=jQuery(this).attr('data-id');

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
                data:{template_id:template_id},
                dataType:'JSON',
                async:false,
                beforeSend: function () {
                    ajxLoader('show','body');    
                }, 
                success: function(response) {

                    ajxLoader('hide','body');    
                    
                    if(response.success) 
                    {
                        $('#bt_list_table').DataTable().ajax.reload();
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