<section class="apps line">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="inner_con ic lgn">
         <a href="<?php echo base_url();?>"> <div class="roundOne innround"><img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/caricon.png"/></div></a>

 
			<div class="panel-heading lgn-hed"> <?php if(isset($title)) echo $title;?> </div>
			 
			 <?php echo $this->session->flashdata('message'); ?>

			<?php $attributes = array('id'=>'reset_form','name'=>'reset_form');
				  echo form_open('auth/reset_password/' . $code, $attributes);?>

		<div class="col-lg-12">
			<label><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label>
			 <?php echo form_input($new_password);?>
			<?php echo form_error('new', '<div class="error">', '</div>'); ?>
			</div>

	<div class="col-lg-12">
				<label><?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?></label>
				<?php echo form_input($new_password_confirm);?>
				<?php echo form_error('new_confirm', '<div class="error">', '</div>'); ?>
			</div>
			<?php echo form_input($user_id);?>
			<div class="col-lg-12">
			<button class="btn btn-danger book pull-right" type="submit" ><?php if(isset($this->phrases["submit"])) echo $this->phrases["submit"]; else echo "Submit";?></button>
			</div>
			 </form>

		 

	  <div class="for">
	  <div class="col-md-12">
		  <a href="<?php echo base_url();?>auth/create_user"> <?php if(isset($this->phrases["create account"])) echo $this->phrases["create account"]; else echo "Create Account";?> </a> | 
		  <a href="<?php echo base_url();?>auth/login"> <?php if(isset($this->phrases["login"])) echo $this->phrases["login"]; else echo "Login";?> </a>
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
         			var pwd= $('#new').val();
         			return (this.optional(element) || repwd==pwd);
         		},"<?php if(isset($this->phrases["password and confirm password not matched"])) echo $this->phrases["password and confirm password not matched"]; else echo "Password and Confirm Password not matched";?>.");

			/* Reset Password form validation rules */
              $("#reset_form").validate({
                  rules: {
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
					"new": {
							  required: "<?php if(isset($this->phrases["please enter new password"])) echo $this->phrases["please enter new password"]; else echo "Please enter new Password";?>."
						  },
					new_confirm: {
							  required: "<?php if(isset($this->phrases["please confirm your new password"])) echo $this->phrases["please confirm your new password"]; else echo "Please confirm your new password";?>."
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
