<div class="container">
<div class="row">
<div class="col-lg-10 col-lg-offset-1">
<div class="login">
<ul>
<li>
<h2>Reset Password</h2>
<?php echo $this->session->flashdata('message'); ?>
<div class="form-feilds">

<?php $attributes = array('id'=>'reset_form','name'=>'reset_form');
          echo form_open('auth/reset_password/' . $code, $attributes);?>

<div class="form-group">
<label><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label>
<?php echo form_input($new_password);?>
<span class="user"> <i class="flaticon-tool"></i> </span>
<?php echo form_error('new', '<div class="error">', '</div>'); ?>
</div>

<div class="form-group">
<label><?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?></label>
<?php echo form_input($new_password_confirm);?>
<span class="pass"><i class="flaticon-tool"></i></span>
<?php echo form_error('new_confirm', '<div class="error">', '</div>'); ?>
</div>
<?php echo form_input($user_id);?>

<div class="form-group">
<button type="submit" class="btn btn-default site-buttos"> <?php if(!empty($this->phrases["submit"])) echo $this->phrases["submit"]; else echo "Submit";?></button>
</div>
</form>

<div class="form-group text-center">
<a href="<?php echo base_url();?>auth/login"> Login now !</a>
</div>
</div>

<span class="sign-up"> <a href="<?php echo base_url();?>auth/create_user"> SIGN UP <i class="flaticon-people-1"></i> </a></span>

<div class="clearfix"></div>
</li>
<li>
<h2>Suggestions</h2>
<div class="form-feilds">
 <ul>
 <li> <i class="flaticon-shield"></i> Better to set your password with mix of small & capital letters, numbers, and special characters.  </li> 
 <li> <i class="flaticon-shield"></i> And is good if password length is more than 8 characters and less than 16 characters.  </li>
 <li> <i class="flaticon-shield"></i> Always save your passwords in some text file and save it securely on drives. </li>
 <li><i class="flaticon-shield"></i> Try to maintain same passwords for all Site logins except for Banking sites </li>
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