  <div class="col-lg-10 col-md-10 col-sm-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
			<div class="col-md-6">
			<?php echo $this->session->flashdata('message');?>
				<?php echo form_open_multipart('executive/profile', 'id="admin_profile_form" class=""');?>
				<div class="form-group">
				  <label><?php if(isset($this->phrases["first name"])) echo $this->phrases["first name"]; else echo "First Name";?>&nbsp;<font color="red">*</font></label>
				  <input type="text" name="first_name" id="first_name" value="<?php echo set_value('first_name', (isset($admin_details->first_name)) ? $admin_details->first_name : '');?>"/>
				  <?php echo form_error('first_name');?>
				</div>
				<div class="form-group">
				  <label><?php if(isset($this->phrases["last name"])) echo $this->phrases["last name"]; else echo "Last Name";?></label>
				  <input type="text" name="last_name" id="last_name" value="<?php echo set_value('last_name', (isset($admin_details->last_name)) ? $admin_details->last_name : '');?>"/>
				  <?php echo form_error('last_name');?>
				</div>

				<div class="form-group">
				  <label><?php if(isset($this->phrases["email"])) echo $this->phrases["email"]; else echo "Email";?>&nbsp;<font color="red">*</font></label>
				  <input type="text" name="email" id="email" value="<?php echo set_value('email', (isset($admin_details->email)) ? $admin_details->email : '');?>" disabled />
				  <?php echo form_error('email');?>
				</div>

				<div class="form-group">
				  <label><?php if(isset($this->phrases["phone"])) echo $this->phrases["phone"]; else echo "Phone";?>&nbsp;<font color="red">*</font></label>
				  <input type="text" name="phone" id="phone" value="<?php echo set_value('phone', (isset($admin_details->phone)) ? $admin_details->phone : '');?>"/>
				  <?php echo form_error('phone');?>
				</div>

				<div class="form-group">
                 <label><?php if(isset($this->phrases["photo"])) echo $this->phrases["photo"]; else echo "Photo";?></label>
                  <input name="userfile" type="file" id="image" title="<?php if(isset($this->phrases["admin photo"])) echo $this->phrases["admin photo"]; else echo "Admin Photo";?>"  onchange="readURL(this)" >
				  <?php echo form_error('userfile');?>
                  <br/>
                  <?php 
                     $src = "";
                     $style="display:none;";

                     if(isset($admin_details->photo) && file_exists('uploads/admin_profile_pic/'.$admin_details->photo)) {
						$src = base_url()."uploads/admin_profile_pic/".$admin_details->photo;
                     	$style="";
                     }
                     ?>
                  <img id="admin_photo" src="<?php echo $src;?>" height="120" style="<?php echo $style;?>" />
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
				/*email: {
						required: true,
						email: true
					},*/
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
