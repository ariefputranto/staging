  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
			<div class="col-md-6">
			<?php echo $this->session->flashdata('message');?>
				<?php 
				  $attributes = array('name' => 'formm', 'id' => 'formm');
				  echo form_open('settings/price_variations/'.$param,$attributes);?> 

				<?php if(count($records) > 0) $record = $records[0]; 
					?>
				<div class="form-group">
					<label><?php if(isset($this->phrases["title"])) echo $this->phrases["title"]; else echo "Title";?>&nbsp;<font color="red">*</font></label>
					 <input type="text" name="variation_title" id="variation_title" value="<?php echo set_value('variation_title', (isset($record->variation_title)) ? $record->variation_title : '');?>"/>
					<?php echo form_error('variation_title'); ?>
				</div>

				<div class="form-group">
				 <label><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></label>
					<?php

						$selected = set_value('variation_status', (isset($record->variation_status)) ? $record->variation_status : '');

						$first_opt = (isset($this->phrases["active"])) ? $this->phrases["active"] : "Active";
						$sec_opt   = (isset($this->phrases["inactive"])) ? $this->phrases["inactive"] : "In-Active";

						$opts = array(
									'Active'   => $first_opt,
									'In-Active' => $sec_opt
									);

						echo form_dropdown('variation_status', $opts, $selected, 'id="variation_status" ');
					?>	  
			   </div>

				 <input type="hidden" name="update_rec_id" value="<?php if(isset($record->variation_id)) echo $record->variation_id;?>" />

				<div class="form-group">	  
					<button type="submit" class="btn btn-success"><?php if(isset($record->id)) echo (isset($this->phrases["update"])) ? $this->phrases["update"] : "Update"; else echo (isset($this->phrases["add"])) ? $this->phrases["add"] : "Add";?></button>
					<a onclick="window.location.href = '<?php echo base_url().'settings/price_variations/list';?>';" class="btn btn-info" title="<?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?>"> <?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>
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

			/* Form validation rules */
              $("#formm").validate({
                  rules: {
                features: {
                          required: true      
                      }
                  },

				messages: {
					features: {
							  required: "<?php if(isset($this->phrases["please enter some data"])) echo $this->phrases["please enter some data"]; else echo "Please enter some data";?>."
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
