<div class="container">
<div class="row">
<div class="col-lg-10 col-lg-offset-1">
<div class="login">
<ul>
<li>
<h2>User Login</h2>
<?php echo $this->session->flashdata('message'); ?>
<div class="form-feilds">

<?php
  echo form_open('auth/login', "id='login_form' name='login_form' class=''");
  ?>

<div class="form-group">
<label>User Name</label>
<?php echo form_input($identity); ?>
<span class="user"> <i class="flaticon-note"></i> </span>
<?php echo form_error('identity'); ?>
</div>

<div class="form-group">
<label>Password</label>
 <?php echo form_input($password); ?>
<span class="pass"><i class="flaticon-tool"></i></span>
 <?php echo form_error('password'); ?>
</div>

<div class="form-group text-right">
<a href="<?php echo base_url();?>auth/forgot_password">Forgot Password?</a>
</div>

<div class="form-group">
<button type="submit" class="btn btn-default site-buttos"> LOGIN</button>
</div>
</form>

<div class="form-group text-center">
Don't have Account? <a href="<?php echo base_url();?>auth/create_user"> Create it now !</a>
</div>
</div>

<span class="sign-up"> <a href="<?php echo base_url();?>auth/create_user"> SIGN UP <i class="flaticon-people-1"></i> </a></span>

<div class="clearfix"></div>
</li>
<li>
<h2>Login Benefits</h2>
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
                required: "<?php if(!empty($this->phrases["please enter your email"])) echo $this->phrases["please enter your email"]; else echo "Please enter your Email";?>."
              },
          password: {
                required: "<?php if(!empty($this->phrases["please enter your password"])) echo $this->phrases["please enter your password"]; else echo "Please enter your password";?>."
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