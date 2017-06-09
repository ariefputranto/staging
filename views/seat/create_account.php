<div class="container">
<div class="row">
<div class="col-lg-10 col-lg-offset-1">
<div class="login">
<ul>
<li>
<h2>New User? Sign Up</h2>
<?php echo $this->session->flashdata('message'); ?>
<div class="form-feilds">
<?php
	$attributes = array("name" => 'register_form',"id" => 'register_form');
	echo form_open('auth/create_user', $attributes);
	?>
<div class="form-group">
<label>First Name&nbsp;<font color="red">*</font></label>
 <?php echo form_input($first_name);?>
<span class="user"> <i class="flaticon-social"></i> </span>
<?php echo form_error('first_name'); ?>
</div>

<div class="form-group">
<label>Last Name</label>
 <?php echo form_input($last_name);?>
<span class="user"> <i class="flaticon-social"></i> </span>
<?php echo form_error('last_name'); ?>
</div>

<div class="form-group">
<label>Email Address&nbsp;<font color="red">*</font></label>
<?php echo form_input($email); ?>
<span class="user"> <i class="flaticon-note"></i> </span>
<?php echo form_error('email'); ?>
</div>

 

<div class="form-group pp">
<label>Phone Number&nbsp;<font color="red">*</font></label>
<?php echo form_input($phone_code); ?>
 <?php echo form_error('phone_code'); ?>
 <?php echo form_input($phone); ?>  
<span class="user"> <i class="flaticon-telephone"></i> </span>
<?php echo form_error('phone'); ?>
</div>

<div class="form-group">
<label>Password&nbsp;<font color="red">*</font></label>
 <?php echo form_input($password); ?>
<span class="pass"><i class="flaticon-tool"></i></span>
<?php echo form_error('password'); ?>
</div>

<div class="form-group">
<label>Confirm Password&nbsp;<font color="red">*</font></label>
<?php echo form_input($password_confirm); ?>
<span class="pass"><i class="flaticon-tool"></i></span>
<?php echo form_error('password_confirm'); ?>
</div>
 

<input type="hidden" name="group" id="group" value="2"> <!-- Client Group is 2 -->

<div class="form-group">
<button type="submit" class="btn btn-default site-buttos"> Register</button>
</div>
</form>

<div class="form-group text-center">
All Ready You have Account ! <a href="<?php echo base_url();?>auth/login"> Login Now !</a>
</div>
</div>

 

<div class="clearfix"></div>
</li>
<li>
<h2>Register Benefits</h2>
<div class="form-feilds">
 <ul>
 <li> <i class="flaticon-shield"></i> Lorem ipsum dolor sit amet, consectetur 
adipiscing consectetur </li>
 <li><i class="flaticon-shield"></i> Lorem ipsum dolor sit amet, consectetur 
adipiscing consectetur </li>

 <li><i class="flaticon-shield"></i> Lorem ipsum dolor sit amet, consectetur 
adipiscing consectetur </li>

 <li><i class="flaticon-shield"></i> Lorem ipsum dolor sit amet, consectetur 
adipiscing consectetur </li>

 <li><i class="flaticon-shield"></i> Lorem ipsum dolor sit amet, consectetur 
adipiscing consectetur </li>

 <li><i class="flaticon-shield"></i> Lorem ipsum dolor sit amet, consectetur 
adipiscing consectetur </li>

 </ul>
 
</div>
</li>
</ul>
</div>
</div>
</div>
</div>

<script type= "text/javascript" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.validate.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/additional-methods.js" ></script>
<script>
	(function($,W,D)
   {
      var JQUERY4U = {};

      JQUERY4U.UTIL =
      {
          setupFormValidation: function()
          {

			/* Additional Methods */
			$.validator.addMethod("pwdmatch", function(repwd, element) {
         			var pwd= $('#password').val();
         			return (this.optional(element) || repwd==pwd);
         		},"<?php if(isset($this->phrases["password and confirm password not matched"])) echo $this->phrases["password and confirm password not matched"]; else echo "Password and Confirm password not matched";?>.");

			 $.validator.addMethod("phoneNumber", function(uid, element) {
				 return (this.optional(element) || uid.match(/^([0-9 +-]*)$/));
			 }, "<?php if(isset($this->phrases["please enter valid number"])) echo $this->phrases["please enter valid number"]; else echo "Please enter valid number";?>.");

			/* Create Account form validation rules */
              $("#register_form").validate({
                  rules: {
					first_name: {
							  required: true
						  },
					email: {
							  required: true,
							  email: true
						  },
					phone_code: {
							  required: true,
							  phoneNumber: true
						  },
					phone: {
							  required: true,
							  phoneNumber: true
						  },
					password: {
							  required: true,
							  rangelength: [8, 30]
						},
					password_confirm: {
							  required: true,
							  pwdmatch: true
						}
                  },

				messages: {
					first_name: {
							  required: "<?php if(isset($this->phrases["please enter your first name"])) echo $this->phrases["please enter your first name"]; else echo "Please enter your First Name";?>."
						  },
					email: {
							  required: "<?php if(isset($this->phrases["please enter your email-id"])) echo $this->phrases["please enter your email-id"]; else echo "Please enter your Email-id";?>."
						  },
					phone_code: {
							  required: "<?php if(isset($this->phrases["please enter your country code"])) echo $this->phrases["please enter your country code"]; else echo "Please enter your Country Code";?>."
						  },
					phone: {
							  required: "<?php if(isset($this->phrases["please enter your phone"])) echo $this->phrases["please enter your phone"]; else echo "Please enter your Phone";?>."
						  },
					password: {
							  required: "<?php if(isset($this->phrases["please enter password"])) echo $this->phrases["please enter password"]; else echo "Please enter Password";?>"
						},
					password_confirm: {
							  required: "<?php if(isset($this->phrases["please confirm your password"])) echo $this->phrases["please confirm your password"]; else echo "Please confirm your Password";?>."
						}
				},

                  submitHandler: function(form) {
                      form.submit();
                  }
              });

          }
      }

      //when the dom has loaded setup form validation rules
      $(D).ready(function($) {
          JQUERY4U.UTIL.setupFormValidation();
      });
   
   })(jQuery, window, document);
   

  </script>