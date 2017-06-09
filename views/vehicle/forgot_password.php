<section class="apps line">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="inner_con ic lgn">
      <a href="<?php echo base_url();?>"> <div class="roundOne innround"><img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/caricon.png"/></div></a>

 
			<div class="panel-heading lgn-hed"> <?php if(isset($title)) echo $title;?> </div>
 
			 <?php echo $this->session->flashdata('message'); ?>

			<?php
				echo form_open('auth/forgot_password', "id='fp_form' name='fp_form' class=''");
				?>
			<div class="col-lg-12">
			<label><?php if(isset($this->phrases["email"])) echo $this->phrases["email"]; else echo "Email";?></label>
			  <?php echo form_input($email); ?>
			  <?php echo form_error('email', '<div class="error">', '</div>'); ?>
			</div>


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

			/* Forgot Password form validation rules */
              $("#fp_form").validate({
                  rules: {
					email: {
							  required: true,
							  email: true
						  }
                  },

				messages: {
					email: {
							  required: "<?php if(isset($this->phrases["please enter your email"])) echo $this->phrases["please enter your email"]; else echo "Please enter your Email";?>."
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
