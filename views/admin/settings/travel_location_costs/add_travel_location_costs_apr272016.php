<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/datepicker.css" rel="stylesheet">

<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/timepicki.css" rel="stylesheet">
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
  <?php 
  $record = array();
  if(isset($records) && count($records) > 0) $record = $records[0]; 
	//print_r($record);				?>
  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
			<div class="col-md-6">
			<?php echo $message;?>
				<div class="form-group">
				 <label><?php echo getPhrase('Price Variations');?></label>			   
					<?php
						$variations = array('' => getPhrase('Please choose price variations'));
						for($i = 1; $i <= 20; $i++)
							$variations[$i] = $i;
						echo form_dropdown('price_variations', $variations, $price_variations, 'id="price_variations" class="chzn-select" onchange="change_form(this.value)"').form_error('price_variations');
					?>
				<script type="text/javascript">
					function change_form(val)
					{
						var id = <?php echo ($this->uri->segment(4)) ? $this->uri->segment(4) : '';?>;
						if(id == '')
						{
						document.location = '<?php echo base_url();?>settings/add_travel_location_costs/'+val;
						}
						else
						{
						document.location = '<?php echo base_url();?>settings/add_travel_location_costs/'+val+'/'+id;	
						}
					}
				</script>					
			    </div>
				<?php if($price_variations != '' && $price_variations > 0) { ?>
				<?php 
				  $attributes = array('name' => 'formm', 'id' => 'formm');
				  //echo form_open('settings/travelLocationCosts/'.$param,$attributes);
				  echo form_open(base_url(uri_string()),$attributes);
				  ?> 

				
				<div class="form-group">
				 <label><?php if(isset($this->phrases["travel location"])) echo $this->phrases["travel location"]; else echo "Travel Location";?></label>			   
					<?php

						$selected = getSelected('buttSubmit', 'travel_location_id', isset($record) ? $record : '');

						echo form_dropdown('travel_location_id', $travel_location_opts, $selected, 'id="travel_location_id" class="chzn-select"').form_error('travel_location_id');
					?>	  
			    </div>

				<div class="form-group">
				 <label><?php if(isset($this->phrases["select vehicle"])) echo $this->phrases["select vehicle"]; else echo "Select Vehicle";?></label>	   
					<?php
						$selected = getSelected('buttSubmit', 'vehicle_id', isset($record) ? $record : '');
						$vehicle_details = '';
						if($selected > 0)
						{
							$vehicle_details = $this->base_model->fetch_records_from('vehicle', array('id' => $selected));
						}					
						
						echo form_dropdown('vehicle_id', $vehicle_opts, $selected, 'id="vehicle_id" class="chzn-select"').form_error('vehicle_id');
					?>	  
					
			    </div>
				<input type="hidden" name="price_variations" value="<?php echo $price_variations;?>">
				
				<div class="form-group">
					<label><?php echo getPhrase('Shuttle Number');?></label>
						<?php $selected = getSelected('buttSubmit', 'shuttle_no', isset($record) ? $record : '');?>
						<input name="shuttle_no" id="shuttle_no" type="text" value="<?php echo $selected;?>">
						<?php echo form_error('shuttle_no'); ?>
				</div>
				   
				<div class="form-group">
					<label><?php echo getPhrase('Season');?></label>
						<?php $selected = getSelected('buttSubmit', 'season', isset($record) ? $record : '');
						$anytime = getPhrase('All Time');
						$quarter = getPhrase('Quarter');
						$half = getPhrase('Half');
						$opts = array(
									'anytime'   => $anytime,
									'quarter1' => '1 '.$quarter,
									'quarter2' => '2 '.$quarter,
									'quarter3' => '3 '.$quarter,
									'quarter4' => '4 '.$quarter,						
									'half1' => '1 '.$half,
									'half2' => '2 '.$half,
									);

						echo form_dropdown('season', $opts, $selected, 'id="status" class="chzn-select"');
						?>
						<?php echo form_error('season'); ?>
				</div>
				
				<div class="form-group">
					<label><?php if(isset($this->phrases["start time"])) echo $this->phrases["start time"]; else echo "Start Time";?></label>
						<?php $selected = getSelected('buttSubmit', 'start_time', isset($record) ? $record : '');?>
						<input name="start_time" id="start_time" type="text" class="tme tp" value="<?php echo $selected;?>">
						<?php echo form_error('start_time'); ?>
				   </div>
				   
				   <div class="form-group">
					<label><?php if(isset($this->phrases["destination time"])) echo $this->phrases["destination time"]; else echo "Destination Time";?></label>
						<input name="destination_time" id="destination_time" type="text" class="tme tp" value="<?php echo getSelected('buttSubmit', 'destination_time', isset($record) ? $record : '');?>">
						<?php echo form_error('destination_time'); ?>
				   </div>
				   
				   <div class="form-group">
					<label><?php echo getPhrase('Elapse Days');?></label>
						<input name="elapsed_days" id="elapsed_days" type="text" value="<?php echo getSelected('buttSubmit', 'elapsed_days', isset($record) ? $record : 0);?>">
						<?php echo form_error('elapsed_days'); ?>
				   </div>
				
				<div class="form-group">
					<label><?php echo getPhrase('Fare Chart')?></label>
					 <div id="fare_chart">
					 <?php $number_of_variatons = $price_variations;
					 $html = '';
						$fares = [];
						$seats = [];
						if($number_of_variatons != '')
						{
							for($i = 1; $i <= $number_of_variatons; $i++)
							{
								$fares[$i] = 1;
								$seats[$i] = 1;
							}
						}
						if(isset($_POST['buttSubmit']))
						{
							$values = $_POST['fare'];
							$fares = $values['fare'];
							$seats = $values['seats'];
						}
						elseif(isset($record->fare_details))
						{
							$values = (array)json_decode($record->fare_details);
							$fares_arr = (array)$values['fare'];
							$seats_arr = (array)$values['seats'];
							foreach($fares_arr as $key => $val)
							{
								$fares[$key] = $val;
							}
							foreach($seats_arr as $key => $val)
							{
								$seats[$key] = $val;
							}
						}
						//print_r($seats);die();
						$html = '<table>';
						$html .= '<tr><td width="30%">Row</td><td>Fare</td><td>Seats</td></tr>';			
						for($i = 1; $i <= $number_of_variatons; $i++)
							{
							$html .= '<tr><td>F'.$i.'</td><td><input type="text" name="fare[fare]['.$i.']" id="fare[fare]['.$i.']" value="'.$fares[$i].'"></td><td><input type="text" name="fare[seats]['.$i.']" value="'.$seats[$i].'" id="">'.form_error('fare[seats]['.$i.']').'</td></tr>';
							}
							$html .= '</table>';
					echo $html;
					 ?>
					 </div>
				</div>				
				
				<div class="form-group">
				 <label><?php echo getPhrase('Service Tax')?></label>			   
					<input type="text" name="service_tax" id="service_tax" value="<?php echo set_value('service_tax', (isset($record->service_tax)) ? $record->service_tax : '');?>"/>
					<?php echo form_error('service_tax'); ?>  
			    </div>
				
				<div class="form-group">
				 <label><?php echo getPhrase('Service Tax Type')?></label>			   
					<?php

						$selected = set_value('service_tax_type', (isset($record->service_tax_type)) ? $record->service_tax_type : '');
						
						$first_opt = getPhrase('Value');
						$sec_opt   = getPhrase('Percent');

						$opts = array(
									'value'   => $first_opt,
									'percent' => $sec_opt
									);

						echo form_dropdown('service_tax_type', $opts, $selected, 'id="status" class="chzn-select"');
					?>	  
			   </div>

				<div class="form-group">
				 <label><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></label>			   
					<?php

						$selected = set_value('status', (isset($record->status)) ? $record->status : '');
						
						$first_opt = (isset($this->phrases["active"])) ? $this->phrases["active"] : "Active";
						$sec_opt   = (isset($this->phrases["inactive"])) ? $this->phrases["inactive"] : "Inactive";

						$opts = array(
									'Active'   => $first_opt,
									'Inactive' => $sec_opt
									);

						echo form_dropdown('status', $opts, $selected, 'id="status" class="chzn-select"');
					?>	  
			   </div>

			   <?php if(isset($record->id)) { ?>

					 <input type="hidden" value="<?php if(isset($record->vehicle_id)) echo $record->vehicle_id;?>" name="vehicle_old_id">
					 <input type="hidden" value="<?php if(isset($record->cost)) echo $record->cost;?>" name="old_cost">

			    <?php } else { ?>

					<div class="form-group">
					 <label><?php if(isset($this->phrases["add vice-versa"])) echo $this->phrases["add vice-versa"]; else echo "Add Vice-versa";?>?</label>			   
						<input type="radio" name="add_vice_versa" id="add_vice_versa_no" value="No" checked/> <?php if(isset($this->phrases["no"])) echo $this->phrases["no"]; else echo "No";?>
						<input type="radio" name="add_vice_versa" id="add_vice_versa_yes" value="Yes" /> <?php if(isset($this->phrases["yes"])) echo $this->phrases["yes"]; else echo "Yes";?>
					</div>

			   <?php } ?>

				 <input type="hidden" name="update_rec_id" value="<?php if(isset($record->id)) echo $record->id;?>" />

				<div class="form-group">
					<button type="submit" class="btn btn-success" name="buttSubmit"><?php if(isset($record->id)) echo (isset($this->phrases["update"])) ? $this->phrases["update"] : "Update"; else echo (isset($this->phrases["add"])) ? $this->phrases["add"] : "Add";?></button>
					<a onclick="window.location.href = '<?php echo base_url().'settings/travelLocationCosts/list';?>';" class="btn btn-info" title="<?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?>"> <?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>
				</div>
				</form>
				<?php } ?>
			</div>

		  </div>
      </div>
    </div>
  </div>
</section>

<script type= "text/javascript" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.validate.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/additional-methods.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/timepicki.js"></script>
<script>
	$('.tp').timepicki({
		increase_direction:'up', 
		min_hour_value: 1
	});
</script>
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
                      },
                cost: {
                          required: true,
                          proper_value: true
                      }
                  },

				messages: {
					travel_location_id: {
							  required: "<?php if(isset($this->phrases["please select travel location"])) echo $this->phrases["please select travel location"]; else echo "Please select Travel Location";?>."
						  },
					vehicle_id: {
							  required: "<?php if(isset($this->phrases["please select vehicle"])) echo $this->phrases["please select vehicle"]; else echo "Please select Vehicle";?>."
						  },
					cost: {
							  required: "<?php if(isset($this->phrases["please enter cost"])) echo $this->phrases["please enter cost"]; else echo "Please enter cost";?>."
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