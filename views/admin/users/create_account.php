  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
			<?php echo $this->session->flashdata('message');?>

			<?php
				$attributes = array("name" => 'register_form',"id" => 'register_form');
				echo form_open('auth/create_user', $attributes);
				?>
		  <div class="col-md-6">
			<div class="form-group">
			   <?php echo form_input($first_name);?>
               <?php echo form_error('first_name'); ?>
			</div>

			<div class="form-group">
			 <?php echo form_input($last_name);?>
             <?php echo form_error('last_name'); ?>
			</div>

			<div class="form-group">
			  <?php echo form_input($email); ?>
             <?php echo form_error('email'); ?>
			</div>

			<div class="form-group">
			  <?php echo form_input($phone_code); ?>
              <?php echo form_error('phone_code'); ?>
			</div>

			<div class="form-group">
			  <?php echo form_input($phone); ?>
              <?php echo form_error('phone'); ?>
			</div>
		 </div>
		 <div class="col-md-6">
			<div class="form-group">
			  <?php echo form_input($password); ?>
              <?php echo form_error('password'); ?>
			</div>

			<div class="form-group">
			  <?php echo form_input($password_confirm); ?>
              <?php echo form_error('password_confirm'); ?>
			</div>

			<div class="form-group">
			  <?php 
					echo form_dropdown('group', $group_opts, '', 'id="group" onchange="toggleAmtDiv(this.value);"').form_error('group'); 
				?>
			</div>

			<div class="form-group" id="amt_field_div" style="display: none;">
			  <?php echo form_input('deposited_amount', set_value('deposited_amount'), 'id="deposited_amount" placeholder="Deposited Amount"'); ?>
              <?php echo form_error('deposited_amount'); ?>
			</div>


			 <div class="form-group">
			<button class="btn btn-success" type="submit" ><?php if(isset($this->phrases["create"])) echo $this->phrases["create"]; else echo "Create";?></button>
			</div>
			 </form>

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
         		},"<?php if(isset($this->phrases["password and confirm password not matched"])) echo $this->phrases["password and confirm password not matched"]; else echo "Password and Confirm Password not matched";?>.");

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
						},
					group: {
							  required: true
						},
					deposited_amount: {
							  required: true,
							  number: true
					}
                  },

				messages: {
					first_name: {
							  required: "<?php if(!empty($this->phrases["please enter first name"])) echo $this->phrases["please enter first name"]; else echo "Please enter First Name";?>."
						  },
					email: {
							  required: "<?php if(!empty($this->phrases["please enter email id"])) echo $this->phrases["please enter email id"]; else echo "Please enter Email-id";?>."
						  },
					phone_code: {
							  required: "<?php if(!empty($this->phrases["please enter your country code"])) echo $this->phrases["please enter your country code"]; else echo "Please enter your Country Code";?>."
						  },
					phone: {
							  required: "<?php if(!empty($this->phrases["please enter phone number"])) echo $this->phrases["please enter phone number"]; else echo "Please enter Phone number";?>."
						  },
					password: {
							  required: "<?php if(!empty($this->phrases["please enter password"])) echo $this->phrases["please enter password"]; else echo "Please enter Password";?>."
						},
					password_confirm: {
							  required: "<?php if(!empty($this->phrases["please enter onfirm password"])) echo $this->phrases["please enter confirm password"]; else echo "Please enter confirm Password";?>."
						},
					group: {
							  required: "<?php if(!empty($this->phrases["please select user type"])) echo $this->phrases["please select user type"]; else echo "Please select User type";?>."
						},
					deposited_amount: {
							  required: "<?php if(!empty($this->phrases["please enter deposited amount"])) echo $this->phrases["please enter deposited amount"]; else echo "Please enter Deposited Amount";?>."
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
   

   function toggleAmtDiv(group_id)
   {
   		if(!group_id)
   			return;

   		if(group_id == 4 || group_id == 5) {
   			$('#amt_field_div').slideDown();
   		} else {
   			$('#amt_field_div').slideUp();
   		}
   }


   </script>
