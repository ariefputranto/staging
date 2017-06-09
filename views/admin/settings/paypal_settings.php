    <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
			<div class="col-md-6">
			<?php echo $this->session->flashdata('message');?>
				<?php 
                  $attributes = array('name' => 'paypal_settings_form', 'id' => 'paypal_settings_form');
                  echo form_open_multipart('settings/paypalSettings',$attributes) ;?>            
               <div class="form-group">
                  <label><?php if(isset($this->phrases["paypal email"])) echo $this->phrases["paypal email"]; else echo "Paypal Email";?></label>    
                  <input type="text" name="paypalemail" 
                     value= "<?php if(isset($paypal_settings->paypal_email))		
                        echo $paypal_settings->paypal_email;echo set_value('paypalemail');?>" />  
                  <?php echo form_error('paypalemail');?>
               </div>
               <div class="form-group">           
                  <label class="control-label"><?php if(isset($this->phrases["currency"])) echo $this->phrases["currency"]; else echo "Currency";?></label>	
                  <?php 					 
                     $select = array();
                     if(isset($paypal_settings->currency)) {
                     		$select = array(								
                     						$paypal_settings->currency
                     						);
                     
                     }	
                      echo form_dropdown('currency',$currency_opts,$select,'class = "chzn-select"');					?>   
               </div>
               <div class="form-group">
                  <label class="control-label"><?php if(isset($this->phrases["account type"])) echo $this->phrases["account type"]; else echo "Account Type";?></label>	
                  <?php
                     $options = array(
                     "sandbox"=>"sandbox","live"=>"live"		
                     );

                     $select = array();
                     if(isset($paypal_settings->account_type)) {
                     	$select = array(								
                     					$paypal_settings->account_type	
                     					);

                     }	
                     echo form_dropdown('account_type',$options,$select,'class = "chzn-select"');?>                 
               </div>

               <input type="hidden" value="<?php  if(isset($paypal_settings->id))
                  echo $paypal_settings->id;
                  ?>"  name="update_record_id" />

			   <div class="form-group">
					<label> <?php if(isset($this->phrases["logo image"])) echo $this->phrases["logo image"]; else echo "Logo Image";?>Logo Image </label>
					<input name="userfile" type="file" id="image" title="<?php if(isset($this->phrases["logo image"])) echo $this->phrases["logo image"]; else echo "Logo Image";?>" onchange="readURL(this)" style="width:80px;">
								<br/>
								<?php 
									$src = "";
									$style="display:none;";

					if(isset($paypal_settings->logo_image) && $paypal_settings->logo_image != "") 							{
						$src = base_url()."uploads/paypal_settings_images/".$paypal_settings->logo_image;
										$style="";
									
						}
						?>
				<img id="logo_image" src="<?php echo $src;?>" height="120" style="<?php echo $style;?>" />     
					 </div>
               <input type="submit" value="<?php if(isset($this->phrases["update"])) echo $this->phrases["update"]; else echo "Update";?>" name="submit" class="btn btn-success" />
               <?php echo form_close();?>

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
   (function($, W, D) {
     var JQUERY4U = {};
     JQUERY4U.UTIL = {
         setupFormValidation: function() {
             //form validation rules
             $("#paypal_settings_form").validate({
                 rules: {
                     paypalemail: {
                         required: true,
                         email: true
                     }
                 },
                 messages: {
                     paypalemail: {
                         required: "<?php if(isset($this->phrases["please enter paypal email"])) echo $this->phrases["please enter paypal email"]; else echo "Please enter Paypal Email";?>."
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
 
 
 
 
 
 
 
	function readURL(input) {
	
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {

                input.style.width = '100%';
				$('#logo_image')
                    .attr('src', e.target.result);
				$('#logo_image').fadeIn();
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
