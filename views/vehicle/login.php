<section class="apps line">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="inner_con ic lgn">
       <a href="<?php echo base_url();?>"> <div class="roundOne innround"><img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/caricon.png"/></div></a>

 
			<div class="panel-heading lgn-hed"> <?php if(isset($title)) echo $title;?> </div>
 
			 <?php echo $this->session->flashdata('message'); ?>

			<?php
				echo form_open('auth/login', "id='login_form' name='login_form' class=''");
				?>
			<div class="col-lg-12">
			<label><?php if(isset($this->phrases["email"])) echo $this->phrases["email"]; else echo "Email";?></label>
			  <?php echo form_input($identity); ?>
			  <?php echo form_error('identity'); ?>
			</div>

			  <div class="col-lg-12">
			<label> <?php if(isset($this->phrases["password"])) echo $this->phrases["password"]; else echo "Password";?> </label>
			 <?php echo form_input($password); ?>
			 <?php echo form_error('password'); ?>
			</div>

			<div class="col-lg-12">
			<button class="btn btn-danger book pull-right" type="submit" ><?php if(isset($this->phrases["login"])) echo $this->phrases["login"]; else echo "Login";?></button>
			</div>
			 </form>
 
 
			   
	  <div class="for"> 
	  <div class="col-lg-5 col-xs-12"> <a href="<?php echo base_url();?>auth/forgot_password"> <?php if(isset($this->phrases["forgot password"])) echo $this->phrases["forgot password"]; else echo "Forgot Password";?> ? </a></div>
	  <div class="col-lg-7 col-xs-12 text-right"> <a href="<?php echo base_url();?>auth/create_user">
	  <?php if(isset($this->phrases["do not have an account? sign up"])) echo $this->phrases["do not have an account? sign up"]; else echo "Do not have an account? Sign Up";?></a></div>
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

			/* Login form validation rules */
              $("#login_form").validate({
                  rules: {
					identity: {
							  required: true,
							  email: true
						  },
					password: {
							  required: true
						}
                  },

				messages: {
					identity: {
							  required: "<?php if(isset($this->phrases["please enter your email"])) echo $this->phrases["please enter your email"]; else echo "Please enter your Email";?>."
						  },
					password: {
							  required: "<?php if(isset($this->phrases["please enter your password"])) echo $this->phrases["please enter your password"]; else echo "Please enter your password";?>."
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
