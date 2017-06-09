  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
			<div class="col-md-6">
			<?php echo $this->session->flashdata('message');?>
				<?php 
                  $attributes = array('name' => 'cancellation_policy_settings_form', 'id' => 'cancellation_policy_settings_form');
                  echo form_open_multipart('settings/cancellationPolicySettings',$attributes) ;?> 

               <div class="form-group">
                  <label><?php if(isset($this->phrases["refund amount(%), if cancelled before 3 hrs"])) echo $this->phrases["refund amount(%), if cancelled before 3 hrs"]; else echo "Refund Amount(%), If Cancelled before 3 Hrs";?></label>
                  <input type="text" name="three_hrs_before" value="<?php echo set_value('three_hrs_before', 
                     (isset($cancellation_policy_settings->three_hrs_before)) ? 
                     $cancellation_policy_settings->three_hrs_before : '');?>" >  

               </div>

               <div class="form-group">                    
                 <label><?php if(isset($this->phrases["refund amount(%), if cancelled before 5 hrs"])) echo $this->phrases["refund amount(%), if cancelled before 5 hrs"]; else echo "Refund Amount(%), If Cancelled before 5 Hrs";?></label>
                  <input type="text" name="five_hrs_before" value="<?php echo set_value('five_hrs_before', 
                     (isset($cancellation_policy_settings->five_hrs_before)) ? 
                     $cancellation_policy_settings->five_hrs_before : '');?>" >

               </div>

               <div class="form-group">                    
                 <label><?php if(isset($this->phrases["refund amount(%), if cancelled before >=8 hrs"])) echo $this->phrases["refund amount(%), if cancelled before >=8 hrs"]; else echo "Refund Amount(%), If Cancelled before >=8 Hrs";?></label>
                  <input type="text" name="eight_hrs_before" value="<?php echo set_value('eight_hrs_before', 
                     (isset($cancellation_policy_settings->eight_hrs_before)) ? 
                     $cancellation_policy_settings->eight_hrs_before : '');?>" >

               </div>

               <input type="hidden" value="<?php  if(isset($cancellation_policy_settings->id))
                  echo $cancellation_policy_settings->id;
                  ?>"  name="update_record_id" />


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
             $("#cancellation_policy_settings_form").validate({
                 rules: {
                     "three_hrs_before": {
                         required: true,
                         number: true
                     },
                     "five_hrs_before": {
                         required: true,
                         number: true
                     },
                     "eight_hrs_before": {
                         required: true,
                         number: true
                     }
                 },
                 messages: {

                     "three_hrs_before": {
                         required: "<?php if(isset($this->phrases["please enter refund amount, if booking cancelled before 3 hrs"])) echo $this->phrases["please enter refund amount, if booking cancelled before 3 hrs"]; else echo "Please enter refund amount, if booking cancelled before 3 hrs";?>."
                     },
                     "five_hrs_before": {
                         required: "<?php if(isset($this->phrases["please enter refund amount, if booking cancelled before 5 hrs"])) echo $this->phrases["please enter refund amount, if booking cancelled before 5 hrs"]; else echo "Please enter refund amount, if booking cancelled before 5 hrs";?>."
                     },
                     "eight_hrs_before": {
                         required: "<?php if(isset($this->phrases["please enter refund amount, if booking cancelled before >=8 hrs"])) echo $this->phrases["please enter refund amount, if booking cancelled before >=8 hrs"]; else echo "Please enter refund amount, if booking cancelled before >=8 hrs";?>."
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

