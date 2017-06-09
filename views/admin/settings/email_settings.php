  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
		  <?php echo $this->session->flashdata('message');?>
				<?php 

				$email_settings = $this->config->item('emailSettings');

                  $attributes = array('name' => 'email_settings_form', 'id' => 'email_settings_form');
                  echo form_open('settings/emailSettings',$attributes);?> 
 
			<div class="col-md-6">
				<label> <?php if(isset($this->phrases["email_type"])) echo $this->phrases["email_type"]; else echo "Email Type";?></label>
				<?php
				$mailtype = 'webmail';
				if(isset($_POST['submit'])) 
					$mailtype = $this->input->post('mailtype');
				else if (isset($email_settings->mail_config))
					$mailtype = $email_settings->mail_config;
				$options = array(
					'webmail' => 'Webmail (SMTP)', 
					'mandrill' => 'Mandril',
					'default' => 'Default (CI)',
					'defaultphp' => 'Default (PHP)',
				);
				echo form_dropdown('mail_config', $options, $mailtype, 'id="mail_config" onchange="javascript:changediv()"');
				?>				
			</div>			
	 
      <div class="col-md-6">
         <div class="module" id="smtp_div">
            <div class="module-head">
               <h3><?php if(isset($title)) echo $title;?></h3>
            </div>
            <div class="module-body">

               <div class="form-group">                    
                  <label><?php if(isset($this->phrases["host"])) echo $this->phrases["host"]; else echo "Host";?></label>    
                  <input type="text" name="smtp_host" placeholder="<?php if(isset($this->phrases["host name"])) echo $this->phrases["host name"]; else echo "Host Name";?>"  value="<?php if(isset($email_settings->smtp_host))
                     echo $email_settings->smtp_host;?>" />
                  <?php echo form_error('smtp_host');?>
               </div>
               <div class="form-group">                    
                  <label><?php if(isset($this->phrases["email"])) echo $this->phrases["email"]; else echo "Email";?></label>    
                  <input type="text" name="smtp_user" placeholder="<?php if(isset($this->phrases["email"])) echo $this->phrases["email"]; else echo "Email";?>" value="<?php if(isset($email_settings->smtp_user))		
                     echo $email_settings->smtp_user;?>" /> 
                  <?php echo form_error('smtp_user');?>
               </div>
               <div class="form-group">                    
                  <label><?php if(isset($this->phrases["password"])) echo $this->phrases["password"]; else echo "Password";?></label>    
                  <input type="password" name="smtp_password" placeholder="<?php if(isset($this->phrases["password"])) echo $this->phrases["password"]; else echo "Password";?>" value="<?php if(isset($email_settings->smtp_password))		
                     echo $email_settings->smtp_password;?>" />    
               </div>
               <div class="form-group">                    
                  <label><?php if(isset($this->phrases["port"])) echo $this->phrases["port"]; else echo "Port";?></label>
                  <input type="text" name="smtp_port" placeholder="<?php if(isset($this->phrases["smtp port number"])) echo $this->phrases["smtp port number"]; else echo "SMTP Port Number";?>" value="<?php if(isset($email_settings->smtp_port))
                     echo $email_settings->smtp_port;?>" />  
                  <?php echo form_error('smtp_port');?>
               </div>
         
            </div>
         </div>

		<div class="module" id="mandrill_div">
            <div class="module-head">
               <h3><?php if(isset($this->phrases["mandrill key"])) echo $this->phrases["mandrill key"]; else echo "Mandrill Key";?></h3>
            </div>
            <div class="module-body">
               <div class="form-group">                    
                  <label><?php if(isset($this->phrases["api key"])) echo $this->phrases["api key"]; else echo "API Key";?></label>      
                  <input type="text" name="api_key" placeholder="<?php if(isset($this->phrases["mandrill api key"])) echo $this->phrases["mandrill api key"]; else echo "Mandrill API Key";?>"  value="<?php if(isset($email_settings->api_key))		
                     echo $email_settings->api_key;?>" />
                  <?php echo form_error('api_key');?>
               </div>                         
            </div>
         </div>
		 
		 <div class="module" id="defaultphp_div">
   
            <div class="module-body">
               <div class="form-group">                    
                  <label><?php if(isset($this->phrases["from email"])) echo $this->phrases["from email"]; else echo "From Email";?></label>      
                  <input type="text" name="from_email" placeholder="<?php if(isset($this->phrases["from email"])) echo $this->phrases["from email"]; else echo "From Email";?>"  value="<?php if(isset($email_settings->from_email))		
                     echo $email_settings->from_email;?>" />
                  <?php echo form_error('from_email');?>
               </div>                         
            </div>
         </div>

		<div class="module">
			<input type="hidden" value="<?php  if(isset($email_settings->id)) echo $email_settings->id;?>"  name="update_record_id" />     
			<input type="submit" class="btn btn-success" value="<?php if(isset($this->phrases["update"])) echo $this->phrases["update"]; else echo "Update";?>" name="submit" />
		</div>		 
      </div>
     <?php echo form_close();?>  
		  </div>
      </div>
    </div>
  </div>
</section>


<script type= "text/javascript" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.validate.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/additional-methods.js" ></script>
<script> 
   (function($, W, D) {
     var JQUERY4U = {};
     JQUERY4U.UTIL = {
         setupFormValidation: function() {
             //Additional Methods			
             $.validator.addMethod("numbersonly", function(a, b) {
                 return this.optional(b) || /^[0-9 ]+$/i.test(a)
             }, "Please enter valid Port number.");
             //form validation rules
             $("#email_settings_form").validate({
                 rules: {
                     smtp_host: {
                         required: true
                     },
                     smtp_user: {
                         required: true,
                         email: true
                     },
                     smtp_password: {
                         required: true
                         
                     },
                     smtp_port: {
                         required: true,
                         numbersonly: true,
                         rangelength: [2, 4]
                     }
                 },
                 messages: {
					 
                     smtp_host: {
                         required: "<?php if(isset($this->phrases["please enter host"])) echo $this->phrases["please enter host"]; else echo "Please enter Host";?>."
                     },
                     smtp_user: {
                         required: "<?php if(isset($this->phrases["pelase enter email"])) echo $this->phrases["pelase enter email"]; else echo "Pelase enter Email";?>."
                     },
                     smtp_password: {
                         required: "<?php if(isset($this->phrases["please enter password"])) echo $this->phrases["please enter password"]; else echo "Please enter Password";?>."
                     },
                     smtp_port: {
                         required: "<?php if(isset($this->phrases["please enter port number"])) echo $this->phrases["please enter port number"]; else echo "Please enter Port number";?>."
                     }
                 },
                 submitHandler: function(form) {
                     $('#email_settings_form').submit();
                 }
             });
         }
     }
     //when the dom has loaded setup form validation rules
     $(D).ready(function($) {
         JQUERY4U.UTIL.setupFormValidation();
     });
 })(jQuery, window, document);
 
 //$(document).ready(function(){
	 function changediv()
	 {
		 var type = $('#mail_config').val();
		 //alert(type);
		 if(type == 'webmail')
		 {
			 $('#mandrill_div').hide();
			 $('#smtp_div').show();
			 $('#defaultphp_div').hide();
		 }
		 else if(type == 'mandrill')
		 {
			 $('#mandrill_div').show();
			 $('#smtp_div').hide();
			 $('#defaultphp_div').hide();
		 }
		 else if(type == 'default' || type == 'defaultphp')
		 {
			 $('#mandrill_div').hide();
			 $('#smtp_div').hide();
			 $('#defaultphp_div').show();
		 }
	 }
	 changediv();
 //});
</script>
