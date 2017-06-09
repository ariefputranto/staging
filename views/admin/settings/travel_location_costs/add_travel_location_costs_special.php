<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/datepicker.css" rel="stylesheet">

<?php if($site_theme == 'seat') {
?>
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/wickedpicker.css" rel="stylesheet">
<?php	
} else { ?>
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/timepicki.css" rel="stylesheet">
<?php } ?>
  <?php

		/* Prepare Travel Location Options */
		$travel_locations = $this->base_model->run_query("SELECT tl.travel_location_id, sl.location as start_location, el.location as end_location FROM ".DBPREFIX."travel_locations tl, ".DBPREFIX."locations sl, ".DBPREFIX."locations el WHERE sl.id=tl.from_loc_id AND el.id=tl.to_loc_id AND sl.status='Active' AND el.status='Active' AND tl.status='Active' ORDER BY tl.travel_location_id DESC");

		$first_opt = (isset($this->phrases["select travel location"])) ? $this->phrases["select travel location"] : "Select Travel Location";

		$travel_location_opts = array('' => $first_opt);
		foreach($travel_locations as $rec)
			$travel_location_opts[$rec->travel_location_id] = $rec->start_location." <code>To</code> ".$rec->end_location;


		/* Prepare Vehicle Options */
		$vehicles = $this->base_model->run_query("SELECT id, name, model, number_plate FROM ".DBPREFIX."vehicle WHERE status='Active' ORDER BY id DESC");

		$vehicle_opts = array('' => 'Select Vehicle');
		foreach($vehicles as $rec)
			$vehicle_opts[$rec->id] = $rec->name . ' - '.$rec->model.' ('.$rec->number_plate.')';
			
		$timezones = $this->db->query('SELECT * FROM `'.$this->db->dbprefix('calendar|timezones').'` ORDER BY `UTC offset` ASC')->result(); 
  ?>
  <?php 
  $record = array();
  if(isset($records) && count($records) > 0) $record = $records[0]; 
  
  $special_record = array();
  if(isset($special) && count($special) > 0) $special_record = $special[0]; 
	?>
  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
		  
			<div class="col-lg-12">
			<?php echo $message;?>
								
				<?php 
				  $attributes = array('name' => 'formm', 'id' => 'formm');
				  echo form_open(base_url(uri_string()),$attributes);
				  ?>
				
				
			   <div class="form-group" id="special_start_div" >
					<label><?php echo getPhrase('Special Start Date');?></label>
						<?php
						$selected = date('Y-m-d');
						if(isset($_POST['buttSubmit']))
						{
							$selected = $_POST['special_start'];
						}
						elseif(isset($special_record) && count($special_record) > 0)
						{
							if($special_record->special_start != '' && $special_record->special_start != '0000-00-00 00:00:00')
								$selected = $special_record->special_start;
						}
						?>
						<input type="text" placeholder="<?php if(isset($this->phrases["Special Start Date"])) echo $this->phrases["Special Start Date"]; else echo "Special Start Date";?>" name="special_start" id="special_start" class="calendar" value="<?php echo $selected;?>">	
						<?php echo form_error('special_start'); ?>
				</div>
			
				<div class="form-group" id="special_end_div" >
					<label><?php echo getPhrase('Special End Date');?></label>
						<?php
						$selected = date('Y-m-d', strtotime("+30 days", strtotime(date('Y-m-d'))));
						if(isset($_POST['buttSubmit']))
						{
							$selected = $_POST['special_end'];
						}
						elseif(isset($special_record) && count($special_record) > 0)
						{
							if($special_record->special_end != '' && $special_record->special_end != '0000-00-00 00:00:00')
								$selected = $special_record->special_end;
						}
						?>
						<input type="text" placeholder="<?php if(isset($this->phrases["Special End Date"])) echo $this->phrases["Special End Date"]; else echo "Special End Date";?>" name="special_end" id="special_end" class="calendar" value="<?php echo $selected;?>">					
						<?php echo form_error('special_end'); ?>
				</div>
				
				<div class="form-group">
				 <label><?php echo getPhrase('status')?></label>			   
				<?php
				$selected = getSelected('buttSubmit', 'special_status', isset($special_record) ? $special_record : '');
				echo form_dropdown('special_status', array('active' => getPhrase('active'), 'inactive' => 'In-Active'), $selected, 'id="special_status" class="chzn-select"').form_error('special_status');
				?>
			    </div>
			   
			   <div class="form-group" id="fare_details_special_div">
					<label><?php echo getPhrase('Fare Chart Special')?>&nbsp;<span id="fare_chart_info"></span></label>
					 <div id="fare_chart">
					 <?php $number_of_variatons = count($price_variations);
					 $html = '';
						$fares = $seats = $agent_commission = $fares_c = $seats_c = $agent_commission_c = $fares_i = $seats_i = $agent_commission_i = [];
						
						$variation = [];
						$variation_titles = [];
						if($number_of_variatons != '')
						{
							foreach($price_variations as $price_variation)
							{
								$fares[$price_variation->variation_id] = 0;
								$seats[$price_variation->variation_id] = 0;
								$agent_commission[$price_variation->variation_id] = 0;
								//Child
								$fares_c[$price_variation->variation_id] = 0;
								$seats_c[$price_variation->variation_id] = 0;
								$agent_commission_c[$price_variation->variation_id] = 0;
								//Infant
								$fares_i[$price_variation->variation_id] = 0;
								$seats_i[$price_variation->variation_id] = 0;
								$agent_commission_i[$price_variation->variation_id] = 0;
								$variation[$price_variation->variation_id] = 0;
								$variation_titles[$price_variation->variation_id] = $price_variation->variation_title;
							}
						}
						if(isset($_POST['buttSubmit']))
						{
							$values = $_POST['fare_details_special'];
							
							$fares = $values['fare'];
							$seats = $values['seats'];
							$agent_commission = $values['agent_commission'];
							//Child
							$fares_c = $values['fare_c'];
							$seats_c = $values['seats_c'];
							$agent_commission_c = $values['agent_commission_c'];
							//Infant
							$fares_i = $values['fare_i'];
							$seats_i = $values['seats_i'];
							$agent_commission_i = $values['agent_commission_i'];
							
							$variation_arr = isset($values['variation']) ? $values['variation'] : array();
							if(count($variation_arr) > 0)
							{
								foreach($variation_arr as $key => $val)
								$variation[] = $key;
							}
							
							$variation_titles = isset($values['variation_titles']) ? $values['variation_titles'] : array();
						}
						elseif(isset($special_record->fare_details_special))
						{
							$values = (array)json_decode($special_record->fare_details_special);
							$fares_arr = isset($values['fare']) ? (array)$values['fare'] : array();
							$seats_arr = isset($values['seats']) ? (array)$values['seats'] : array();
							$agent_commission_arr = isset($values['agent_commission']) ? (array)$values['agent_commission'] : array();
							//Child
							$fares_arr_c = isset($values['fare_c']) ? (array)$values['fare_c'] : array();
							$seats_arr_c = isset($values['seats_c']) ? (array)$values['seats_c'] : array();
							$agent_commission_arr_c = isset($values['agent_commission_c']) ? (array)$values['agent_commission_c'] : array();
							//Infant
							$fares_arr_i = isset($values['fare_i']) ? (array)$values['fare_i'] : array();
							$seats_arr_i = isset($values['seats_i']) ? (array)$values['seats_i'] : array();
							$agent_commission_arr_i = isset($values['agent_commission_i']) ? (array)$values['agent_commission_i'] : array();
							
							$variation_arr = isset($values['variation']) ? (array)$values['variation'] : array();
							
							$variation_titles_arr = isset($values['variation_titles']) ? $values['variation_titles'] : array();
							foreach($fares_arr as $key => $val)
							{
								$fares[$key] = $val;
							}
							foreach($seats_arr as $key => $val)
							{
								$seats[$key] = $val;
							}
							foreach($agent_commission_arr as $key => $val)
							{
								$agent_commission[$key] = $val;
							}
							//Child
							foreach($fares_arr_c as $key => $val)
							{
								$fares_c[$key] = $val;
							}
							foreach($seats_arr_c as $key => $val)
							{
								$seats_c[$key] = $val;
							}
							foreach($agent_commission_arr_c as $key => $val)
							{
								$agent_commission_c[$key] = $val;
							}
							//Infant
							foreach($fares_arr_i as $key => $val)
							{
								$fares_i[$key] = $val;
							}
							foreach($seats_arr_i as $key => $val)
							{
								$seats_i[$key] = $val;
							}
							foreach($agent_commission_arr_i as $key => $val)
							{
								$agent_commission_i[$key] = $val;
							}
							
							foreach($variation_arr as $key => $val)
							{
								$variation[] = $key;
							}
							foreach($variation_titles_arr as $key => $val)
							{
								$variation_titles[$key] = $val;
							}
						}
						//print_r($seats);die();
						$html = '<table class="cost">';
						$html .= '<thead><tr><th width="30%">Row</th><th colspan="3">Basic Fare</th><th colspan="3">Seats</th><th colspan="3">Agent Commission</th></tr></thead>';
						
						$html .= '<tr class="sub-he"><td width="30%">&nbsp</td>
						<td>Adult</td><td>Child</td><td>Infant</td>  <td>Adult</td><td>Child</td><td>Infant</td>
						<td>Adult</td><td>Child</td><td>Infant</td>
						</tr>';
						
						foreach($price_variations as $price_variation)
						{
							$html .= '<tr>';
							if(in_array($price_variation->variation_id, $variation))
							{
								$html .= '
							<td><input type="checkbox" name="fare_details_special[variation]['.$price_variation->variation_id.']" checked>&nbsp;&nbsp;'.$price_variation->variation_title.'</td>';
							}
							else
							{
								$html .= '<td><input type="checkbox" name="fare_details_special[variation]['.$price_variation->variation_id.']">&nbsp;&nbsp;'.$price_variation->variation_title.'</td>';
							}
							
							//Adult Price
							$html .= '							
							<td><input class="fare" type="text" name="fare_details_special[fare]['.$price_variation->variation_id.']" id="fare_details_special[fare]['.$price_variation->variation_id.']" value="'.$fares[$price_variation->variation_id].'"></td>';
							//Child Price
							$html .= '							
							<td><input class="fare" type="text" name="fare_details_special[fare_c]['.$price_variation->variation_id.']" id="fare_details_special[fare_c]['.$price_variation->variation_id.']" value="'.$fares_c[$price_variation->variation_id].'"></td>';
							//Infant Price
							$html .= '							
							<td><input class="fare" type="text" name="fare_details_special[fare_i]['.$price_variation->variation_id.']" id="fare_details_special[fare_i]['.$price_variation->variation_id.']" value="'.$fares_i[$price_variation->variation_id].'"></td>';
							
							//Adult Seats
							$html .= '<td><input type="text" name="fare_details_special[seats]['.$price_variation->variation_id.']" value="'.$seats[$price_variation->variation_id].'"></td>';
							//Child Seats
							$html .= '<td><input type="text" name="fare_details_special[seats_c]['.$price_variation->variation_id.']" value="'.$seats_c[$price_variation->variation_id].'"></td>';
							//Infant Seats
							$html .= '<td><input type="text" name="fare_details_special[seats_i]['.$price_variation->variation_id.']" value="'.$seats_i[$price_variation->variation_id].'"></td>';
							
							
							$html .= '<input type="hidden" name="fare_details_special[variation_titles]['.$price_variation->variation_id.']" value="'.$variation_titles[$price_variation->variation_id].'">';
							
							//Adult Agent Commission
							$html .= '<td><input class="fare" type="text" name="fare_details_special[agent_commission]['.$price_variation->variation_id.']" value="'.$agent_commission[$price_variation->variation_id].'" id="">'.form_error('fare_details_special[seats]['.$price_variation->variation_id.']').'</td>';
							//Child Agent Commission
							$html .= '<td><input class="fare" type="text" name="fare_details_special[agent_commission_c]['.$price_variation->variation_id.']" value="'.$agent_commission_c[$price_variation->variation_id].'" id="">'.form_error('fare_details_special[seats]['.$price_variation->variation_id.']').'</td>';
							//Infant Agent Commission
							$html .= '<td><input class="fare" type="text" name="fare_details_special[agent_commission_i]['.$price_variation->variation_id.']" value="'.$agent_commission_i[$price_variation->variation_id].'" id="">'.form_error('fare_details_special[seats]['.$price_variation->variation_id.']').'</td>';
							
							$html .= '</tr>';							
						}
						$html .= '</table>';
					echo $html;
					 ?>
					 </div>
				</div>
				
				<input type="hidden" name="tlc_id" value="<?php if(isset($record->id)) echo $record->id;?>" />
				 <input type="hidden" name="special_id" value="<?php if(isset($special_id)) echo $special_id; else echo '';?>" />

				<div class="form-group">
					<button type="submit" class="btn btn-success" name="buttSubmit"><?php if(isset($record->id)) echo (isset($this->phrases["update"])) ? $this->phrases["update"] : "Update"; else echo (isset($this->phrases["add"])) ? $this->phrases["add"] : "Add";?></button>
					<a onclick="window.location.href = '<?php echo base_url().'settings/travelLocationCosts/list';?>';" class="btn btn-info" title="<?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?>"> <?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>
				</div>
				
				
				<div class="form-group">
				 <label>&nbsp;</label>
				 &nbsp;
			    </div>
				
				<div class="form-group">
				 <label><?php if(isset($this->phrases["travel location"])) echo $this->phrases["travel location"]; else echo "Travel Location";?></label>			   
				<?php
				$selected = getSelected('buttSubmit', 'travel_location_id', isset($record) ? $record : '');
				echo form_dropdown('travel_location_id', $travel_location_opts, $selected, 'id="travel_location_id" class="chzn-select" readonly').form_error('travel_location_id');
				?>
			    </div>
				
				<div class="form-group">
					<label><?php echo getPhrase('Stop Over');?></label>
						<input type="text" placeholder="<?php if(isset($this->phrases["Stop Over"])) echo $this->phrases["Stop Over"]; else echo "Stop Over";?>" name="stop_over" id="stop_over" value="<?php echo getSelected('buttSubmit', 'stop_over', isset($record) ? $record : '0');?>" readonly>						
						<?php echo form_error('stop_over'); ?>
				</div>

				<div class="form-group">
				 <label><?php if(isset($this->phrases["select vehicle"])) echo $this->phrases["select vehicle"]; else echo "Select Vehicle";?></label>	   
					<?php
						$selected_vehicle = getSelected('buttSubmit', 'vehicle_id', isset($record) ? $record : '');
						$vehicle_details = '';
						if($selected_vehicle > 0)
						{
							$vehicle_details = $this->base_model->fetch_records_from('vehicle', array('id' => $selected_vehicle));
						}					
						
						echo form_dropdown('vehicle_id', $vehicle_opts, $selected_vehicle, 'id="vehicle_id" class="chzn-select" onchange="get_total_seats(this.value)" readonly').form_error('vehicle_id');
					?>	  
					
			    </div>
				<script type="text/javascript">
					function get_total_seats(vehicle_id)
					{
						$.ajax({   	
							type:'POST',
							url:'<?php echo base_url();?>settings/get_total_seats',
							data:{
								vehicle_id:vehicle_id,
								<?php echo $this->security->get_csrf_token_name();?>:'<?php echo $this->security->get_csrf_hash();?>',								
							},
							cache:false,
							success: function(data) {				
									$('#fare_chart_info').empty();
									$('#fare_chart_info').append(' (Vehicle has '+data+' seats)');					
							}
						});
					}
					
				</script>
				<input type="hidden" name="price_variations" value="<?php echo count($price_variations);?>">
				
				<div class="form-group">
					<label><?php echo getPhrase('Shuttle Number');?></label>
						<?php $selected = getSelected('buttSubmit', 'shuttle_no', isset($record) ? $record : '');?>
						<input name="shuttle_no" id="shuttle_no" type="text" value="<?php echo $selected;?>" readonly>
						<?php echo form_error('shuttle_no'); ?>
				</div>
				   
				<div class="form-group">
					<label><?php echo getPhrase('Season Start Date');?></label>
						<input type="text" placeholder="<?php if(isset($this->phrases["Season Start Date"])) echo $this->phrases["Season Start Date"]; else echo "Season Start Date";?>" name="season_start" id="season_start" class="calendar" value="<?php echo getSelected('buttSubmit', 'season_start', isset($record) ? $record : '');?>" readonly>						
						<?php echo form_error('season_start'); ?>
				</div>
				<div class="form-group">
					<label><?php echo getPhrase('Season End Date');?></label>
						<input type="text" placeholder="<?php if(isset($this->phrases["Season End Date"])) echo $this->phrases["Season End Date"]; else echo "Season End Date";?>" name="season_end" id="season_end" class="calendar" value="<?php echo getSelected('buttSubmit', 'season_end', isset($record) ? $record : '');?>" readonly>						
						<?php echo form_error('season_end'); ?>
				</div>
				
				<input type="hidden" name="season_type" id="season_type" value="strict_to_date">
				
				
				
				<div class="form-group">
					<label><?php echo getPhrase('Departure Time');?></label>
						<?php $selected = getSelected('buttSubmit', 'start_time', isset($record) ? $record : '');?>
						<input name="start_time" id="start_time" type="text" class="tme tp" value="<?php echo $selected;?>" readonly>
						<?php echo form_error('start_time'); ?>
						<?php
						$selected = '';						
						if(isset($_POST['buttSubmit']))
						{
							$selected = $_POST['start_time_zone'];
						}
						elseif(isset($record->start_time_zone))
						{
							$selected = $record->start_time_zone.'_'.$record->start_time_zone_id;
						}
						?>
						
						<select name="start_time_zone" id="start_time_zone" readonly>
							<?php
							foreach($timezones as $timezone)
							{
								$timezone = (array)$timezone;
								?>
								<option value="<?php echo $timezone['UTC offset'];?>_<?php echo $timezone['zone_id'];?>" <?php if($selected == $timezone['UTC offset'].'_'.$timezone['zone_id']) echo 'selected';?>><?php echo $timezone['TimeZone'].' ('.$timezone['UTC offset'].')';?></option>
								<?php
							}
							?>
						</select>
				   </div>
				   
				   <div class="form-group">
					<label><?php echo getPhrase('Arrival Time');?></label>
						<input name="destination_time" id="destination_time" type="text" class="tme tp" value="<?php echo getSelected('buttSubmit', 'destination_time', isset($record) ? $record : '');?>" readonly>
						<?php echo form_error('destination_time'); ?>
						
						
						
						<?php
						$selected = '';						
						if(isset($_POST['buttSubmit']))
						{
							$selected = $_POST['destination_time_zone'];
						}
						elseif(isset($record->destination_time_zone))
						{
							$selected = $record->destination_time_zone.'_'.$record->destination_time_zone_id;
						}						
						?>
												
						<select name="destination_time_zone" id="destination_time_zone" readonly>
							<?php
							foreach($timezones as $timezone)
							{
								$timezone = (array)$timezone;
								?>
								<option value="<?php echo $timezone['UTC offset'];?>_<?php echo $timezone['zone_id'];?>" <?php if($selected == $timezone['UTC offset'].'_'.$timezone['zone_id']) echo 'selected';?>><?php echo $timezone['TimeZone'].' ('.$timezone['UTC offset'].')';?></option>
								<?php
							}
							?>
							
						</select>
				   </div>
				   
				   <div class="form-group">
					<label><?php echo getPhrase('Elapse Days');?></label>
						<input name="elapsed_days" id="elapsed_days" type="text" value="<?php echo getSelected('buttSubmit', 'elapsed_days', isset($record) ? $record : 0);?>" readonly>
						<?php echo form_error('elapsed_days'); ?>
				   </div>
				
				<div class="form-group">
					<label><?php echo getPhrase('Fare Chart')?>&nbsp;<span id="fare_chart_info"></span></label>
					 <div id="fare_chart">
					 <?php $number_of_variatons = count($price_variations);
					 $html = '';
						$fares = $seats = $agent_commission = $fares_c = $seats_c = $agent_commission_c = $fares_i = $seats_i = $agent_commission_i = [];
						
						$variation = [];
						$variation_titles = [];
						if($number_of_variatons != '')
						{
							foreach($price_variations as $price_variation)
							{
								$fares[$price_variation->variation_id] = 0;
								$seats[$price_variation->variation_id] = 0;
								$agent_commission[$price_variation->variation_id] = 0;
								//Child
								$fares_c[$price_variation->variation_id] = 0;
								$seats_c[$price_variation->variation_id] = 0;
								$agent_commission_c[$price_variation->variation_id] = 0;
								//Infant
								$fares_i[$price_variation->variation_id] = 0;
								$seats_i[$price_variation->variation_id] = 0;
								$agent_commission_i[$price_variation->variation_id] = 0;
								$variation[$price_variation->variation_id] = 0;
								$variation_titles[$price_variation->variation_id] = $price_variation->variation_title;
							}
						}
						if(isset($_POST['buttSubmit']))
						{
							$values = $_POST['fare'];
							
							$fares = $values['fare'];
							$seats = $values['seats'];
							$agent_commission = $values['agent_commission'];
							//Child
							$fares_c = $values['fare_c'];
							$seats_c = $values['seats_c'];
							$agent_commission_c = $values['agent_commission_c'];
							//Infant
							$fares_i = $values['fare_i'];
							$seats_i = $values['seats_i'];
							$agent_commission_i = $values['agent_commission_i'];
							
							$variation_arr = isset($values['variation']) ? $values['variation'] : array();
							if(count($variation_arr) > 0)
							{
								foreach($variation_arr as $key => $val)
								$variation[] = $key;
							}
							
							$variation_titles = isset($values['variation_titles']) ? $values['variation_titles'] : array();
						}
						elseif(isset($record->fare_details))
						{
							$values = (array)json_decode($record->fare_details);
							$fares_arr = isset($values['fare']) ? (array)$values['fare'] : array();
							$seats_arr = isset($values['seats']) ? (array)$values['seats'] : array();
							$agent_commission_arr = isset($values['agent_commission']) ? (array)$values['agent_commission'] : array();
							//Child
							$fares_arr_c = isset($values['fare_c']) ? (array)$values['fare_c'] : array();
							$seats_arr_c = isset($values['seats_c']) ? (array)$values['seats_c'] : array();
							$agent_commission_arr_c = isset($values['agent_commission_c']) ? (array)$values['agent_commission_c'] : array();
							//Infant
							$fares_arr_i = isset($values['fare_i']) ? (array)$values['fare_i'] : array();
							$seats_arr_i = isset($values['seats_i']) ? (array)$values['seats_i'] : array();
							$agent_commission_arr_i = isset($values['agent_commission_i']) ? (array)$values['agent_commission_i'] : array();
							
							$variation_arr = isset($values['variation']) ? (array)$values['variation'] : array();
							
							$variation_titles_arr = isset($values['variation_titles']) ? $values['variation_titles'] : array();
							foreach($fares_arr as $key => $val)
							{
								$fares[$key] = $val;
							}
							foreach($seats_arr as $key => $val)
							{
								$seats[$key] = $val;
							}
							foreach($agent_commission_arr as $key => $val)
							{
								$agent_commission[$key] = $val;
							}
							//Child
							foreach($fares_arr_c as $key => $val)
							{
								$fares_c[$key] = $val;
							}
							foreach($seats_arr_c as $key => $val)
							{
								$seats_c[$key] = $val;
							}
							foreach($agent_commission_arr_c as $key => $val)
							{
								$agent_commission_c[$key] = $val;
							}
							//Infant
							foreach($fares_arr_i as $key => $val)
							{
								$fares_i[$key] = $val;
							}
							foreach($seats_arr_i as $key => $val)
							{
								$seats_i[$key] = $val;
							}
							foreach($agent_commission_arr_i as $key => $val)
							{
								$agent_commission_i[$key] = $val;
							}
							
							foreach($variation_arr as $key => $val)
							{
								$variation[] = $key;
							}
							foreach($variation_titles_arr as $key => $val)
							{
								$variation_titles[$key] = $val;
							}
						}
						//print_r($seats);die();
						$html = '<table class="cost">';
						$html .= '<thead><tr><th width="30%">Row</th><th colspan="3">Basic Fare</th><th colspan="3">Seats</th><th colspan="3">Agent Commission</th></tr></thead>';
						
						$html .= '<tr class="sub-he"><td width="30%">&nbsp</td>
						<td>Adult</td><td>Child</td><td>Infant</td>  <td>Adult</td><td>Child</td><td>Infant</td>
						<td>Adult</td><td>Child</td><td>Infant</td>
						</tr>';
						
						foreach($price_variations as $price_variation)
						{
							$html .= '<tr>';
							if(in_array($price_variation->variation_id, $variation))
							{
								$html .= '
							<td><input type="checkbox" name="fare[variation]['.$price_variation->variation_id.']" checked readonly>&nbsp;&nbsp;'.$price_variation->variation_title.'</td>';
							}
							else
							{
								$html .= '<td><input type="checkbox" name="fare[variation]['.$price_variation->variation_id.']" readonly>&nbsp;&nbsp;'.$price_variation->variation_title.'</td>';
							}
							
							//Adult Price
							$html .= '							
							<td><input class="fare" type="text" name="fare[fare]['.$price_variation->variation_id.']" id="fare[fare]['.$price_variation->variation_id.']" value="'.$fares[$price_variation->variation_id].'" readonly></td>';
							//Child Price
							$html .= '							
							<td><input class="fare" type="text" name="fare[fare_c]['.$price_variation->variation_id.']" id="fare[fare_c]['.$price_variation->variation_id.']" value="'.$fares_c[$price_variation->variation_id].'" readonly></td>';
							//Infant Price
							$html .= '							
							<td><input class="fare" type="text" name="fare[fare_i]['.$price_variation->variation_id.']" id="fare[fare_i]['.$price_variation->variation_id.']" value="'.$fares_i[$price_variation->variation_id].'" readonly></td>';
							
							//Adult Seats
							$html .= '<td><input type="text" name="fare[seats]['.$price_variation->variation_id.']" value="'.$seats[$price_variation->variation_id].'" readonly></td>';
							//Child Seats
							$html .= '<td><input type="text" name="fare[seats_c]['.$price_variation->variation_id.']" value="'.$seats_c[$price_variation->variation_id].'" readonly></td>';
							//Infant Seats
							$html .= '<td><input type="text" name="fare[seats_i]['.$price_variation->variation_id.']" value="'.$seats_i[$price_variation->variation_id].'" readonly></td>';
							
							
							$html .= '<input type="hidden" name="fare[variation_titles]['.$price_variation->variation_id.']" value="'.$variation_titles[$price_variation->variation_id].'" readonly>';
							
							//Adult Agent Commission
							$html .= '<td><input class="fare" type="text" name="fare[agent_commission]['.$price_variation->variation_id.']" value="'.$agent_commission[$price_variation->variation_id].'" id="" readonly>'.form_error('fare[seats]['.$price_variation->variation_id.']').'</td>';
							//Child Agent Commission
							$html .= '<td><input class="fare" type="text" name="fare[agent_commission_c]['.$price_variation->variation_id.']" value="'.$agent_commission_c[$price_variation->variation_id].'" id="" readonly>'.form_error('fare[seats]['.$price_variation->variation_id.']').'</td>';
							//Infant Agent Commission
							$html .= '<td><input class="fare" type="text" name="fare[agent_commission_i]['.$price_variation->variation_id.']" value="'.$agent_commission_i[$price_variation->variation_id].'" id="" readonly>'.form_error('fare[seats]['.$price_variation->variation_id.']').'</td>';
							
							$html .= '</tr>';							
						}
						$html .= '</table>';
					echo $html;
					 ?>
					 </div>
				</div>				
				
				<div class="form-group">
				 <label><?php echo getPhrase('Special Fare?')?></label>			   
					<?php
						$selected = set_value('special_fare', (isset($record->special_fare)) ? $record->special_fare : '');
						
						$first_opt = getPhrase('No');
						$sec_opt   = getPhrase('Yes');

						$opts = array(
									'no'   => $first_opt,
									'yes' => $sec_opt
									);

						echo form_dropdown('special_fare', $opts, $selected, 'id="special_fare" class="chzn-select" onchange="showhide()" readonly');
					?>	  
			   </div>
			   			   
				<div class="form-group">
				 <label><?php echo getPhrase('Agent CommissionType')?></label>			   
					<?php

						$selected = set_value('agent_commisstion_type', (isset($record->agent_commisstion_type)) ? $record->agent_commisstion_type : '');
						
						$first_opt = getPhrase('Value');
						$sec_opt   = getPhrase('Percent');

						$opts = array(
									'value'   => $first_opt,
									'percent' => $sec_opt
									);

						echo form_dropdown('agent_commisstion_type', $opts, $selected, 'id="agent_commisstion_type" class="chzn-select" readonly');
					?>	  
			   </div>
			   
				<div class="form-group">
				 <label><?php echo getPhrase('Service Tax')?></label>			   
					<input type="text" name="service_tax" id="service_tax" value="<?php echo set_value('service_tax', (isset($record->service_tax)) ? $record->service_tax : '');?>" readonly />
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

						echo form_dropdown('service_tax_type', $opts, $selected, 'id="status" class="chzn-select" readonly');
					?>	  
			   </div>
			   
			   
				<div class="form-group">
				 <label><?php if(isset($this->phrases["assign driver"])) echo $this->phrases["assign driver"]; else echo "Assign Driver";?></label>			   
					<?php

						$selected = set_value('driver_id', (isset($record->driver_id)) ? $record->driver_id : '');
						
						echo form_dropdown('driver_id', $driver_opts, $selected, 'id="driver_id" class="chzn-select" readonly').form_error('driver_id');
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

						echo form_dropdown('status', $opts, $selected, 'id="status" class="chzn-select" readonly');
					?>	  
			   </div>

			   <?php if(isset($record->id)) { ?>

					 <input type="hidden" value="<?php if(isset($record->vehicle_id)) echo $record->vehicle_id;?>" name="vehicle_old_id">
					 <input type="hidden" value="<?php if(isset($record->cost)) echo $record->cost;?>" name="old_cost">

			    <?php } else { /*?>

					<div class="form-group">
					 <label><?php if(isset($this->phrases["add vice-versa"])) echo $this->phrases["add vice-versa"]; else echo "Add Vice-versa";?>?</label>			   
						<input type="radio" name="add_vice_versa" id="add_vice_versa_no" value="No" checked/> <?php if(isset($this->phrases["no"])) echo $this->phrases["no"]; else echo "No";?>
						<input type="radio" name="add_vice_versa" id="add_vice_versa_yes" value="Yes" /> <?php if(isset($this->phrases["yes"])) echo $this->phrases["yes"]; else echo "Yes";?>
					</div>

			   <?php */ } ?>

				 
				</form>
				
			</div>

		  </div>
      </div>
    </div>
  </div>
</section>
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/bootstrap.min.css" rel="stylesheet">

<script type= "text/javascript" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.validate.js" ></script>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/additional-methods.js" ></script>
<?php 
if($site_theme == 'seat') 
{
?>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/wickedpicker.js"></script>
<?php } else { ?>
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/timepicki.js"></script>
<?php
} ?>


<!--<script type='text/javascript' src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/bootstrap-datepicker.js"></script>-->
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/jquery-ui.css" rel="stylesheet">
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery-ui.js"></script>
<script>
	<?php 
	if($site_theme == 'seat') 
	{
	?>
	$('.tme').wickedpicker({
		now: new Date().getHours()+':'+new Date().getMinutes(), 
		twentyFour: true, 
		title:'Timepicker', 
		showSeconds: false
	});
	$('.tme2').wickedpicker({
		now: $('#start_time').val(), 
		twentyFour: true, 
		title:'Timepicker', 
		showSeconds: false
	});
	
	<?php } else {
		?>	
	$('.tp').timepicki({
		increase_direction:'up', 
		min_hour_value: 1
	});
		<?php
	}?>
	
	/*
	var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
	$('#special_start').datepicker({
		dateFormat: 'yy-mm-dd',
		defaultDate: "+1d",
		minDate:date,
		autoclose: true,
		onClose: function( selectedDate ) {
			$( "#special_end" ).datepicker( "option", "minDate", selectedDate );
		}
	});
	$('#special_end').datepicker({
		dateFormat: 'yy-mm-dd',
		defaultDate: "+1d",
		minDate:date,
		autoclose: true,
		onClose: function( selectedDate ) {
			$( "#special_start" ).datepicker( "option", "maxDate", selectedDate );
		}
	});
	*/
	$(function() {
    //$( ".calendar" ).datepicker({dateFormat: "yy-mm-dd"});
	
	$( "#special_start" ).datepicker({
		defaultDate: "+1d",
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		onClose: function( selectedDate ) {
		$( "#special_end" ).datepicker( "option", "minDate", selectedDate );
      }
		});
		
	$( "#special_end" ).datepicker({
		defaultDate: "+1d",
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		onClose: function( selectedDate ) {
        $( "#special_start" ).datepicker( "option", "maxDate", selectedDate );
		}
		});
  });
   get_total_seats(<?php echo $selected_vehicle;?>);
</script>