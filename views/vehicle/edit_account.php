<section class="apps line">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="inner_con ic lgn">
         

		 <div class="panel">
			<div class="panel-heading lgn-hed"> <?php if(isset($title)) echo $title;?> </div>
			<div class="panel-body">
			 <?php echo $this->session->flashdata('message'); ?>

			<?php
				$attributes = array("name" => 'register_form',"id" => 'register_form');
				echo form_open('auth/edit_user', $attributes);
				?>
			<div class="form-group">
			   <?php echo form_input($first_name);?>
               <?php echo form_error('first_name'); ?>
			</div>
				
			<div class="form-group">
			 <?php echo form_input($last_name);?>
             <?php echo form_error('last_name'); ?>
			</div>

			<div class="form-group">
			  <?php if(!$this->ion_auth->is_admin()) $email['readonly'] = 'readonly'; echo form_input($email); ?>
             <?php echo form_error('email'); ?>
			</div>

			<div class="form-group">
			  <?php echo form_input($phone_code); ?>
              <?php echo form_error('phone_code'); ?>
			  <?php echo form_input($phone); ?>
              <?php echo form_error('phone'); ?>
			</div>
 
			<div class="form-group">
			  <?php echo form_input($password); ?>
              <?php echo form_error('password'); ?>
			</div>

			<div class="form-group">
			  <?php echo form_input($password_confirm); ?>
              <?php echo form_error('password_confirm'); ?>
			</div>

			<input type="hidden" name="id" value="<?php echo $user->id;?>">

			 <div class="form-group">
			<button class="btn btn-danger next_btn pre_btn lgn-btn" type="submit" ><?php if(isset($this->phrases["update"])) echo $this->phrases["update"]; else echo "Update";?></button>
			</div>
			 </form>

		</div>
			  
	  </div>

</div>
</div>
</div>
  </div>
</section>

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
					phone_code: {
							  required: true,
							  phoneNumber: true
						  },
					phone: {
							  required: true,
							  phoneNumber: true
						  },
					password: {
							  rangelength: [8, 30]
						},
					password_confirm: {
							  pwdmatch: true
						}
                  },

				messages: {
					first_name: {
							  required: "<?php if(isset($this->phrases["please enter your first name"])) echo $this->phrases["please enter your first name"]; else echo "Please enter your First Name";?>."
						  },
					phone_code: {
							  required: "<?php if(isset($this->phrases["please enter your country code"])) echo $this->phrases["please enter your country code"]; else echo "Please enter your Country Code";?>."
						  },
					phone: {
							  required: "<?php if(isset($this->phrases["please enter your phone"])) echo $this->phrases["please enter your phone"]; else echo "Please enter your Phone";?>."
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
