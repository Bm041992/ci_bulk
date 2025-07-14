$(document).ready(function(){
    
    var table=$('#smtp_config_list_table').DataTable({
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
            "url": baseUrl +"Smtp_config/getLists",
            "type": "POST"
        },
        //Set column definition initialisation properties
        "columnDefs": [
            { 
                "targets": [0],
                "orderable": false
            },
            { 
                "targets": [2],
                className: "text-center",
                "orderable": false,
                // 'render': function (data, type, full, meta){
                //     if(data == 1){
                //         return '<div class="checkbox checkbox-switch"><label><input type="checkbox" data-on-color="success" data-off-color="danger" data-on-text="Yes" data-off-text="No" class="switch" onchange="change_status(this);" id="'+ btoa(data) +'" checked="checked"></label></div>';
                //     }else{
                //         return '<div class="checkbox checkbox-switch"><label><input type="checkbox" data-on-color="success" data-off-color="danger" data-on-text="Yes" data-off-text="No" class="switch" onchange="change_status(this);" id="'+btoa(data)+'" ></label></div>';
                //     }
                // }
            },
            {
                "targets": [3],
                className: "text-center",
                "orderable": false
            }           
        ],
        "drawCallback": function(settings) {
            initSwitches();
            // $('.smtp-status-switch').bootstrapSwitch();

            // $('.smtp-status-switch').on('switchChange.bootstrapSwitch', function(event, state) {
            //     const $switch = $(this);
            //     const smtp_config_id = $(this).data('id');
            //     const status = state ? 1 : 0;
            //     const previousState = !state ;
                
            //     // Send AJAX request to update status
            //     $.ajax({
            //         url: baseUrl + "Smtp_config/update_status",
            //         type: "POST",
            //         data: { smtp_config_id: smtp_config_id, status: status },
            //         dataType:'JSON',
            //         beforeSend: function () {
            //             ajxLoader('show','body');    
            //         }, 
            //         success: function(response) {
            //             ajxLoader('hide','body');    
                   
            //             if(response.success) 
            //             {
            //                 swal({
            //                     title: "Success!",
            //                     text: response.msg,
            //                     type: "success"
            //                 });
            //             } 
            //             else 
            //             { 
            //                 swal({
            //                     title: "Oops!",
            //                     text: response.msg,
            //                     type: "error" 
            //                 });
                            
            //                 $switch.bootstrapSwitch('state', previousState, true); 
            //             }
            //         },
            //         error: function() {
            //             ajxLoader('hide','body'); 
            //             swal({
            //                 title: "Oops!",
            //                 text: 'Something went wrong.',
            //                 type: "error" 
            //             });
            //              $switch.bootstrapSwitch('state', previousState, true); 
            //         }
            //     });
            // });
        }
    });   

    table.on('responsive-display.dt', function () {
        initSwitches();
    });
    // table.on('responsive-display.dt', function (e, datatable, row, showHide, update) {
    //     $('.smtp-status-switch').bootstrapSwitch('destroy').bootstrapSwitch();
    // });
});
function initSwitches() {
    $('.smtp-status-switch').each(function () {
        if (!$(this).hasClass('bootstrap-switch')) {
            $(this).bootstrapSwitch();
        }
    });
    // $('.smtp-status-switch').bootstrapSwitch('destroy').bootstrapSwitch();

    $('.smtp-status-switch').on('switchChange.bootstrapSwitch', function (event, state) {
        const $switch = $(this);
        const smtp_config_id = $(this).data('id');
        const status = state ? 1 : 0;
        const previousState = !state ;
        
        // Send AJAX request to update status
        $.ajax({
            url: baseUrl + "Smtp_config/update_status",
            type: "POST",
            data: { smtp_config_id: smtp_config_id, status: status },
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

$('#assign_to_sub_admin_id').select2({
    placeholder: 'Select Sub Admin',
    width: '100%',
    allowClear: true
});

$("#smtp_config_form").validate({
    rules: {
        email: {
            required: true,
            customEmail:true
        },
        app_password: {
            required: true,
            noSpace:true,
            noHTMLtags:true,
            minlength: 16,
            maxlength:16
        }
    },
    submitHandler: function(form) {
       
        var ajaxSubmit=baseUrl+"smtp_config/save_smtp_settings"
        jQuery.ajax({
            url: ajaxSubmit,
            type: 'POST',
            data:jQuery("#smtp_config_form").serialize(),
            dataType:'JSON',
            beforeSend: function () {
                ajxLoader('show','body');   
            }, 
            success: function(response) {

                ajxLoader('hide','body');    
                    
                if(response.success) 
                {
                    jQuery("#smtp_config_form")[0].reset();
                    swal({
                        title: "Success!",
                        text: response.msg,
                        type: "success" 
                    },function () {
                        window.location.href = baseUrl + 'smtp_config';
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

jQuery(document).on('click','.delete_smtp_config',function(){

    let ajaxSubmit=baseUrl+"smtp_config/delete_smtp_config";
    let smtp_config_id=jQuery(this).attr('data-id');

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
                data:{smtp_config_id:smtp_config_id},
                dataType:'JSON',
                //async:false,
                beforeSend: function () {
                    ajxLoader('show','body');    
                }, 
                success: function(response) {

                    ajxLoader('hide','body');    
                   
                    if(response.success) 
                    {
                        $('#smtp_config_list_table').DataTable().ajax.reload();
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