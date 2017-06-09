  <div class="col-lg-10 col-md-10 col-sm-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
			<div class="col-md-6">
			<?php echo $this->session->flashdata('message');?>
				<?php 
				  $attributes = array('name' => 'change_password_form', 'id' => 'change_password_form');
				  echo form_open('auth/change_password',$attributes);?> 

				<div class="form-group">
					<label><?php if(isset($this->phrases["old password"])) echo $this->phrases["old password"]; else echo "Old Password";?></label>
					<?php echo form_input($old_password); ?>
					<?php echo form_error('old_password'); ?>
				</div>

				<div class="form-group">  
				 <label><?php if(isset($this->phrases["new password"])) echo $this->phrases["new password"]; else echo "New Password";?></label>			   
				  <?php echo form_input($new_password); ?>
					<?php echo form_error('new_password'); ?>	  
			   </div>

			   <div class="form-group">   
				  <label><?php if(isset($this->phrases["confirm new password"])) echo $this->phrases["confirm new password"]; else echo "Confirm New Password";?></label>
				  <?php echo form_input($new_password_confirm); ?>
					<?php echo form_error('new_password_confirm'); ?>
			   </div>

			   <div class="form-group">                     
				  <?php echo form_input($user_id); ?>
					<?php echo form_error('user_id'); ?>				  
			   </div>
			
			 <div class="form-group">
			<button class="btn btn-success next_btn pre_btn lgn-btn" type="submit" ><?php if(isset($this->phrases["submit"])) echo $this->phrases["submit"]; else echo "Submit";?></button>
			<a onclick="window.location.href='<?php echo base_url();?>driver';" class="btn btn-info" title="Cancel"> <?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>
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

			/** Additional Methods **/
			$.validator.addMethod("pwdmatch", function(repwd, element) {
   			var pwd= $('#new').val();
   			return (this.optional(element) || repwd==pwd);
			},"<?php if(isset($this->phrases["password and confirm password not matched"])) echo $this->phrases["password and confirm password not matched"]; else echo "Password and Confirm Password not matched";?>.");

			/* Change Password form validation rules */
              $("#change_password_form").validate({
                  rules: {
                old: {
                          required: true      
                      },
				"new": {
						  required: true,
						  rangelength: [8, 30]
					},
   				new_confirm: {
                          required: true,
						  pwdmatch: true
                      }
                  },

				messages: {
					old: {
							  required: "<?php if(isset($this->phrases["please enter your old password"])) echo $this->phrases["please enter your old password"]; else echo "Please enter your old password";?>."
						  },
					"new": {
							  required: "<?php if(isset($this->phrases["please enter your new password"])) echo $this->phrases["please enter your new password"]; else echo "Please enter your new password";?>."
						  },
					new_confirm: {
							  required: "<?php if(isset($this->phrases["please enter confirm your new password"])) echo $this->phrases["please enter confirm your new password"]; else echo "Please enter confirm your new password";?>."
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
