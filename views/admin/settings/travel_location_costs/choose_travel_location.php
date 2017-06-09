  <?php

		/* Prepare Travel Location Options */
		$travel_locations = $this->base_model->run_query("SELECT tl.travel_location_id, sl.location as start_location, el.location as end_location FROM ".DBPREFIX."travel_locations tl, ".DBPREFIX."locations sl, ".DBPREFIX."locations el WHERE sl.id=tl.from_loc_id AND el.id=tl.to_loc_id AND sl.status='Active' AND el.status='Active' AND tl.status='Active' ORDER BY tl.travel_location_id DESC");

		$first_opt = (isset($this->phrases["select travel location"])) ? $this->phrases["select travel location"] : "Select Travel Location";

		$travel_location_opts = array('' => $first_opt);
		foreach($travel_locations as $rec)
			$travel_location_opts[$rec->travel_location_id] = $rec->start_location." <code>To</code> ".$rec->end_location;


		/* Prepare Vehicle Options */
		$vehicles = $this->base_model->run_query("SELECT id, name FROM ".DBPREFIX."vehicle WHERE status='Active' ORDER BY id DESC");

		$vehicle_opts = array('' => 'Select Vehicle');
		foreach($vehicles as $rec)
			$vehicle_opts[$rec->id] = $rec->name;
 
  ?>
  
  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
			<div class="col-md-6">
			<?php echo $message;?>
				<?php 
				  $attributes = array('name' => 'formm', 'id' => 'formm');
				  //echo form_open('settings/travelLocationCosts/'.$param,$attributes);
				  echo form_open(base_url(uri_string()),$attributes);
				  ?> 



				<div class="form-group">
				 <label><?php if(isset($this->phrases["travel location"])) echo $this->phrases["travel location"]; else echo "Travel Location";?></label>			   
					<?php

						$selected = set_value('travel_location_id', (isset($record->travel_location_id)) ? $record->travel_location_id : '');

						echo form_dropdown('travel_location_id', $travel_location_opts, $selected, 'id="travel_location_id" class="chzn-select"').form_error('travel_location_id');
					?>	  
			    </div>

				<div class="form-group">
				 <label><?php if(isset($this->phrases["select vehicle"])) echo $this->phrases["select vehicle"]; else echo "Select Vehicle";?></label>			   
					<?php

						$selected = set_value('vehicle_id', (isset($record->vehicle_id)) ? $record->vehicle_id : '');
						$vehicle_details = '';
						if($selected > 0)
						{
							$vehicle_details = $this->base_model->fetch_records_from('vehicle', array('id' => $selected));
						}
						
						echo form_dropdown('vehicle_id', $vehicle_opts, $selected, 'id="vehicle_id" class="chzn-select"').form_error('vehicle_id');
					?>	  
			    </div>

			   
				<input type="hidden" name="update_rec_id" value="<?php if(isset($record->id)) echo $record->id;?>" />

				<div class="form-group">
					<button type="submit" class="btn btn-success"><?php echo getPhrase('Go')?></button>
					<a onclick="window.location.href = '<?php echo base_url().'settings/travelLocationCosts/list';?>';" class="btn btn-info" title="<?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?>"> <?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>
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
			$.validator.addMethod("proper_value", function(uid, element) {
					return (this.optional(element) || uid.match(/^((([0-9]*)[\.](([0-9]{1})|([0-9]{2})))|([0-9]*))$/));
				}, "<?php if(isset($this->phrases["please enter valid value"])) echo $this->phrases["please enter valid value"]; else echo "Please enter valid value";?>.");

			/* Form validation rules */
              $("#formm").validate({
                  rules: {
                travel_location_id: {
                          required: true
                      },
                vehicle_id: {
                          required: true
                      }
                  },

				messages: {
					travel_location_id: {
							  required: "<?php if(isset($this->phrases["please select travel location"])) echo $this->phrases["please select travel location"]; else echo "Please select Travel Location";?>."
						  },
					vehicle_id: {
							  required: "<?php if(isset($this->phrases["please select vehicle"])) echo $this->phrases["please select vehicle"]; else echo "Please select Vehicle";?>."
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