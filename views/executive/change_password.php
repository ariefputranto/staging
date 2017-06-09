  <div class="col-lg-10 col-md-10 col-sm-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
			<div class="col-md-6">
			<?php echo $message;?>
				<?php echo form_open_multipart('executive/change_password', 'id="admin_profile_form" class=""');?>
				<div class="form-group">
				  <label><?php if(isset($this->phrases["old password"])) echo $this->phrases["old password"]; else echo "Old Password";?>&nbsp;<font color="red">*</font></label>
				  <input type="password" name="old_password" id="old_password" value=""/>
				  <?php echo form_error('old_password');?>
				</div>

				<div class="form-group">
				  <label><?php if(isset($this->phrases["new password"])) echo $this->phrases["new password"]; else echo "new password";?>&nbsp;<font color="red">*</font></label>
				  <input type="password" name="new_password" id="new_password" value=""/>
				  <?php echo form_error('new_password');?>
				</div>

				<div class="form-group">
				  <label><?php if(isset($this->phrases["confirm new password"])) echo $this->phrases["confirm new password"]; else echo "confirm new password";?>&nbsp;<font color="red">*</font></label>
				  <input type="password" name="new_password_confirm" id="new_password_confirm" value=""/>
				  <?php echo form_error('new_password_confirm');?>
				</div>

				 <input type="hidden" name="update_rec_id" value="<?php if(isset($admin_details->id)) echo $admin_details->id;?>" />
				<div class="form-group">	  
					<input type="submit" name="submit" value="<?php if(isset($this->phrases["update"])) echo $this->phrases["update"]; else echo "Update";?>" class="btn btn-success">
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
			$.validator.addMethod("phoneNumber", function(uid, element) {
				 return (this.optional(element) || uid.match(/^([0-9 +-]*)$/));
			 }, "<?php if(isset($this->phrases["please enter valid number"])) echo $this->phrases["please enter valid number"]; else echo "Please enter valid number";?>.");

			/* Admin Profile Form Validations */
            $("#admin_profile_form").validate({
   			rules: {
				username: "required",
				email: {
						required: true,
						email: true
					},
				phone: {
						required: true,
						phoneNumber: true
					},
				userfile: {
						extension: "png|jpg|jpeg"
					}
			},
			messages: {
				username: "<?php if(isset($this->phrases["please enter your name"])) echo $this->phrases["please enter your name"]; else echo "Please enter your Name";?>.",				
				email : {
					required: "<?php if(isset($this->phrases["please enter your email id"])) echo $this->phrases["please enter your email id"]; else echo "Please enter your Email-id";?>."
				},
				phone: {
					required: "<?php if(isset($this->phrases["please enter your phone number"])) echo $this->phrases["please enter your phone number"]; else echo "Please enter your Phone number";?>."
				},
				userfile: {
					extension: "<?php if(isset($this->phrases["please upload your photo with the extension jpg jpeg png"])) echo $this->phrases["please upload your photo with the extension jpg jpeg png"]; else echo "Please upload your Photo with the extension jpg|jpeg|png";?>."
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
   
   
   /* Read File Input */
   function readURL(input) {
   
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {

            input.style.width = '100%';
			$('#admin_photo')
                    .attr('src', e.target.result);
			$('#admin_photo').fadeIn();
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
   
   </script>
