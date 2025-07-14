
$("#myprofileform").validate(
{
    errorElement: 'span',
    rules: 
    {
    	username: {
    		required: true,
        noSpace:true,
    		noHTML:true,
        maxlength:25
    	},
      email: {
          required: true,
          email: true,
          customEmail:true
      }     
    },
   	messages: 
   	{
    	username: {
        required:"Please Enter Username",
		  },
      email: {
         	required:"Please Enter Email",
          email:"Please Enter Valid Email"
      },	      
    }	    
}); 


jQuery.validator.addMethod("notEqual", function(value, element, param) {
 return this.optional(element) || value != $(param).val();
}, "Please Enter a different Password value");

$("#mypasswordform").validate({
	  rules: {
		    old_password: {
          required: true,
          remote: {
              url: baseUrl+"profile/check_user_oldpassword",
              type: "POST"
          },
        },
        new_password: {
          required: true,
          noSpace:true,
          minlength:8,
          maxlength:20,
          notEqual: "#old_password"
        }, 
        confirm_password: {
          required: true,
          equalTo: "#new_password"
        },  
	  },
    messages: {
      old_password: {
        required:"Please Enter Current Password",
        remote:'Incorrect password.'
      },
      new_password: {
        required:"Please Enter New Password",
        minlength: "Password length must be minimum 8 characters."
      },
      confirm_password: {
        required:"Please Enter Confirm Password",
        equalTo: "Confirm password does not match with password."
      },           
    }      
}); 