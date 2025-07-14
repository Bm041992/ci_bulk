$(document).ready(function(){
    
    var table=$('#daily_contacts_bday_list_table').DataTable({
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
            "url": baseUrl +"dashboard/getLists",
            "type": "POST"
        },
        //Set column definition initialisation properties
        "columnDefs": [
            {
                "targets": [3],
                className: "text-center",
                "orderable": false
            }, 
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
    
    if(jQuery('#current_user_role').val() == 2)
    {
        table.column(0).visible(false);
        table.column(1).visible(false);
    }
});

$("#bday_msg_form").validate({
	rules: {
        bday_msg: {
            required: true,
            noSpace: true,
            noHTMLtags:true,
            minlength: 10,
            maxlength: 5000
        }
    }, 
    messages: {
        bday_msg: {
            required:"Please Enter Message"
        }
    },
    submitHandler:function(form){
        
        let ajaxSubmit=baseUrl+"dashboard/save_message";

        jQuery.ajax({
            url: ajaxSubmit,
            type: 'POST',
            data:$('#bday_msg_form').serialize(),
            dataType:'JSON',
            beforeSend: function () {
                ajxLoader('show','body');    
            }, 
            success: function(response) {

                ajxLoader('hide','body');    
                
                if(response.success) 
                {
                    //jQuery("#bday_msg_form")[0].reset();
                    $('#daily_contacts_bday_list_table').DataTable().ajax.reload();
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