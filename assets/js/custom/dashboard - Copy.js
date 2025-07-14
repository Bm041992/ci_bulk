$(document).ready(function(){
    
    $('#daily_contacts_bday_list_table').DataTable({
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
                "targets": [1],
                className: "text-center",
                "orderable": false
            }, 
            {
                "targets": [3],
                className: "text-center",
                "orderable": false
            }, 
            {
                "targets": [4],
                className: "text-center",
                "orderable": false
            }           
        ]
    });   
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

// jQuery(document).on('click','.send_whatsapp_bday_msg',function(){

//     let ajaxSubmit=baseUrl+"dashboard/send_whatsapp_msg";
//     let contact_id=jQuery(this).attr('data-id');
//     let contact_no=jQuery(this).attr('data-contact');

//     if(jQuery('#bday_msg').val() == '')
//     {
//         swal({
//             title: "Oops!",
//             text: 'Please Enter Message.',
//             type: "error" 
//         });
//     }
//     else if(contact_id == '' || contact_no == '')
//     {
//         swal({
//             title: "Oops!",
//             text: 'Something went wrong.',
//             type: "error" 
//         });
//     }
//     else
//     {
//         jQuery.ajax({
//             url: ajaxSubmit,
//             type: 'POST',
//             data:{contact_id:contact_id,contact_no:contact_no,message:jQuery('#bday_msg').val()},
//             dataType:'JSON',
//             async:false,
//             beforeSend: function () {
//                 ajxLoader('show','body');    
//             }, 
//             success: function(response) {

//                 ajxLoader('hide','body');    
                
//                 if(response.success) 
//                 {
//                     $('#daily_contacts_bday_list_table').DataTable().ajax.reload();
//                     swal({
//                         title: "Success!",
//                         text: response.msg,
//                         type: "success" 
//                     });
//                 } 
//                 else 
//                 { 
//                     swal({
//                         title: "Oops!",
//                         text: response.msg,
//                         //icon: "error",
//                         type: "error" 
//                     });
//                 }
//             },
//             error: function() {
//                 ajxLoader('hide','body');  
//                 swal({
//                     title: "Oops!",
//                     text: 'Something went wrong.',
//                     type: "error" 
//                 });
//             }
//         });
//     }  
       
// });