  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
		  <?php echo $this->session->flashdata('message');?>
				<?php 
				  $attributes = array('name' => 'formm', 'id' => 'formm');
				  echo form_open('admin/offers/'.$param,$attributes);?> 

				<?php if(count($records) > 0) $record = $records[0]; 
					?>
			<div class="col-md-6">
				<div class="form-group">
					<label><?php if(!empty($this->phrases["title"])) echo $this->phrases["title"]; else echo "Title";?></label>
					 <input type="text" name="title" id="title" value="<?php echo set_value('title', (isset($record->title)) ? $record->title : '');?>"/>
					<?php echo form_error('title'); ?>
				</div>

				<div class="form-group">
					<label><?php if(!empty($this->phrases["description"])) echo $this->phrases["description"]; else echo "Description";?></label>
					 <textarea name="description" id="description"><?php echo set_value('description', (isset($record->description)) ? $record->description : '');?></textarea>
					<?php echo form_error('description'); ?>
				</div>

				<div class="form-group">
				 <label><?php if(!empty($this->phrases["offer type"])) echo $this->phrases["offer type"]; else echo "Offer Type";?></label>			   
					<?php

						$selected = set_value('offer_type', (isset($record->offer_type)) ? $record->offer_type : '');

						$first_opt = (!empty($this->phrases["percentage"])) ? $this->phrases["percentage"] : "Percentage";
						$sec_opt   = (!empty($this->phrases["amount"])) ? $this->phrases["amount"] : "Amount";

						$opts = array(
									'percentage' => $first_opt,
									'amount'	 => $sec_opt
									);

						echo form_dropdown('offer_type', $opts, $selected, 'id="offer_type" ');
					?>
			   </div>

			   <div class="form-group">
					<label><?php if(!empty($this->phrases["offer type value"])) echo $this->phrases["offer type value"]; else echo "Offer Type Value";?></label>
					 <input type="text" name="offer_type_val" id="offer_type_val" value="<?php echo set_value('offer_type_val', (isset($record->offer_type_val)) ? $record->offer_type_val : '');?>"/>
					<?php echo form_error('offer_type_val'); ?>
				</div>

				<div class="form-group">
					<label><?php if(!empty($this->phrases["code"])) echo $this->phrases["code"]; else echo "Code";?></label>
					 <input type="text" name="code" id="code" value="<?php echo set_value('code', (isset($record->code)) ? $record->code : '');?>"/>
					<?php echo form_error('code'); ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label><?php if(!empty($this->phrases["min journey cost"])) echo $this->phrases["min journey cost"]; else echo "Min. Journey Cost";?></label>
					 <input type="text" name="min_journey_cost" id="min_journey_cost" value="<?php echo set_value('min_journey_cost', (isset($record->min_journey_cost)) ? $record->min_journey_cost : '');?>"/>
					<?php echo form_error('min_journey_cost'); ?>
				</div>

				<div class="form-group">
				 <label><?php if(!empty($this->phrases["usage type"])) echo $this->phrases["usage type"]; else echo "Usage Type";?></label>			   
					<?php

						$selected = set_value('usage_type', (isset($record->usage_type)) ? $record->usage_type : '');

						$first_opt = (!empty($this->phrases["one time"])) ? $this->phrases["one time"] : "One Time";
						$sec_opt   = (!empty($this->phrases["multiple"])) ? $this->phrases["multiple"] : "Multiple";

						$opts = array(
									'one_time' => $first_opt,
									'multiple' => $sec_opt
									);

						echo form_dropdown('usage_type', $opts, $selected, 'id="usage_type" onclick="toggleUsageTypeValDiv();"');
					?>
			   </div>

			   <div class="form-group" id="div_usage_type_val" style="display: none;">
					<label><?php if(!empty($this->phrases["usage type value"])) echo $this->phrases["usage type value"]; else echo "Usage Type Value";?></label>
					 <input type="text" name="usage_type_val" id="usage_type_val" value="<?php echo set_value('usage_type_val', (isset($record->usage_type_val)) ? $record->usage_type_val : '');?>" />
					<?php echo form_error('usage_type_val'); ?>
				</div>

				<div class="form-group">
					<label><?php if(!empty($this->phrases["expiry date"])) echo $this->phrases["expiry date"]; else echo "Expiry Date";?></label>
					 <input type="date" class="calendar" name="expiry_date" id="expiry_date" value="<?php echo set_value('expiry_date', (isset($record->expiry_date)) ? $record->expiry_date : '');?>"/>
					<?php echo form_error('expiry_date'); ?>
				</div>
				
				<div class="form-group">
				 <label><?php if(!empty($this->phrases["Applied on"])) echo $this->phrases["Applied on"]; else echo "Applied on";?></label>			   
					<?php

						$selected = set_value('discount_appliedon', (isset($record->discount_appliedon)) ? $record->discount_appliedon : '');

						$first_opt = (!empty($this->phrases["Basic Fare"])) ? $this->phrases["Basic Fare"] : "Basic Fare";
						$sec_opt   = (!empty($this->phrases["Total Value"])) ? $this->phrases["Total Value"] : "Total Value";

						$opts = array(
									'basic_fare' => $first_opt,
									'total_value' => $sec_opt
									);

						echo form_dropdown('discount_appliedon', $opts, $selected, 'id="discount_appliedon"');
					?>
			   </div>

				<div class="form-group">
				 <label><?php if(!empty($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></label>			   
					<?php

						$selected = set_value('status', (isset($record->status)) ? $record->status : '');

						$first_opt = (!empty($this->phrases["active"])) ? $this->phrases["active"] : "Active";
						$sec_opt   = (!empty($this->phrases["inactive"])) ? $this->phrases["inactive"] : "Inactive";

						$opts = array(
									'Active'   => $first_opt,
									'Inactive' => $sec_opt
									);

						echo form_dropdown('status', $opts, $selected, 'id="status" ');
					?>
			   </div>

				 <input type="hidden" name="update_rec_id" value="<?php if(isset($record->offer_id)) echo $record->offer_id;?>" />

				<div class="form-group">	  
					<button type="submit" class="btn btn-success"><?php if(isset($record->offer_id)) echo (!empty($this->phrases["update"])) ? $this->phrases["update"] : "Update"; else echo (!empty($this->phrases["add"])) ? $this->phrases["add"] : "Add";?></button>
					<a onclick="window.location.href = '<?php echo base_url().'admin/offers/list';?>';" class="btn btn-info" title="<?php if(!empty($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?>"> <?php if(!empty($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>
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
                title: {
                          required: true      
                      },
                description: {
                          required: true      
                      },
                offer_type_val: {
                          required: true,
                          digits: true
                      },
                code: {
                          required: true
                      },
                min_journey_cost: {
                          required: true,
                          number: true
                      },
                usage_type_val: {
                          required: true,
                          digits: true
                      },
                expiry_date: {
                          required: true
                      }
                  },

				messages: {
					title: {
							  required: "<?php if(!empty($this->phrases["please enter title"])) echo $this->phrases["please enter title"]; else echo "Please enter Title";?>."
						  },
					description: {
	                          required: "<?php if(!empty($this->phrases["please enter description"])) echo $this->phrases["please enter description"]; else echo "Please enter Description";?>."
	                      },
	                offer_type_val: {
	                          required: "<?php if(!empty($this->phrases["please enter offer type value"])) echo $this->phrases["please enter offer type value"]; else echo "Please enter Offer Type Value";?>."
	                      },
	                code: {
	                          required: "<?php if(!empty($this->phrases["please enter offer code"])) echo $this->phrases["please enter offer code"]; else echo "Please enter Offer Code";?>."
	                      },
	                min_journey_cost: {
	                          required: "<?php if(!empty($this->phrases["please enter min journey cost"])) echo $this->phrases["please enter min journey cost"]; else echo "Please enter Min. Journey Cost";?>."
	                      },
	                usage_type_val: {
	                          required: "<?php if(!empty($this->phrases["please enter usage type value"])) echo $this->phrases["please enter usage type value"]; else echo "Please enter Usage Type Value";?>."
	                      },
	                expiry_date: {
	                          required: "<?php if(!empty($this->phrases["please select expiry date"])) echo $this->phrases["please select expiry date"]; else echo "Please select Expiry Date";?>."
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
   

   function toggleUsageTypeValDiv()
   {
   		if($('#usage_type option:selected').val() == "multiple") {
   			$('#div_usage_type_val').slideDown();
   		} else {
   			$('#div_usage_type_val').slideUp();
   			$('#usage_type_val').val(1);
   		}
   }

   </script>
<script>
	$('document').ready(function() {
		toggleUsageTypeValDiv();
	});
</script>