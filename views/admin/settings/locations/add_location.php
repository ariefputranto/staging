  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">
			<div class="col-md-6">
			<?php echo $this->session->flashdata('message');?>
				<?php 
				  $attributes = array('enctype' => 'multipart/form-data','name' => 'formm', 'id' => 'formm');
				  echo form_open('settings/locations/'.$param,$attributes);?> 

				<?php 
				if(count($records) > 0) $record = $records[0];
					$timezones = $this->db->query('SELECT * FROM `'.$this->db->dbprefix('calendar|timezones').'` ORDER BY `UTC offset` ASC')->result(); 				
					?>


				<div class="form-group">
					<label><?php if(isset($this->phrases["location / airport name"])) echo $this->phrases["location / airport name"]; else echo "Location / Airport Name";?></label>
					 <input type="text" name="location" id="location" value="<?php echo set_value('location', (isset($record->location)) ? $record->location : '');?>"/>
					<?php echo form_error('location'); ?>
				</div>
				
				<script>
			   
			   function fun_location_visibility_type()
			   {
				   if($('#location_visibility_type').val() == 'via')
				   {
					   $('#parent_id_div').show();
				   }
				   else
				   {
						$('#parent_id_div').hide();	
				   }
			   }
			   </script>
				<div class="form-group">
				 <label><?php if(isset($this->phrases["location visibility type"])) echo $this->phrases["location visibility type"]; else echo "Location Visibility Type";?></label>			   
					<?php

						$first_opt = (isset($this->phrases["start"])) ? $this->phrases["start"] : "Start";
						$sec_opt   = (isset($this->phrases["end"])) ? $this->phrases["end"] : "End";
						$third_opt = (isset($this->phrases["both"])) ? $this->phrases["both"] : "Both";
						$via_opt = (isset($this->phrases["via"])) ? $this->phrases["via"] : "Via";

						$location_visibility_type_opts = array(
													'start' => $first_opt, 
													'end' 	=> $sec_opt, 
													'both'	=> $third_opt,
													'via' => $via_opt
															 );

						$selected = set_value('location_visibility_type', (isset($record->location_visibility_type)) ? $record->location_visibility_type : 'both');

						echo form_dropdown('location_visibility_type', $location_visibility_type_opts, $selected, 'id="location_visibility_type" class="chzn-select" onchange="fun_location_visibility_type()"');
					?>	  
			   </div>
			   
			   <?php
			   $display = 'none';
			   if(isset($_POST['submitbutt']) && $_POST['location_visibility_type'] == 'via')
				   $display = 'block';
			   elseif(isset($record->parent_id) && $record->parent_id != 0)
				$display = 'block';
			   ?>
			   <div class="form-group" style="display:<?php echo $display;?>;" id="parent_id_div">
				 <label><?php if(isset($this->phrases["location"])) echo $this->phrases["location"]; else echo "Location";?>?</label>			   
					<?php
						$opts = $from_loc_opts;

						$selected = set_value('parent_id', (isset($record->parent_id)) ? $record->parent_id : '0');

						echo form_dropdown('parent_id', $opts, $selected, 'id="parent_id" class="chzn-select"');
					?>	  
			   </div>
			   
			   <div class="form-group">
				 <label><?php echo getPhrase('Time Zone');?>?</label>			   
					<?php
						/*$opts = array('+7' => 'Indonesia Western Time Zone(UTC+07:00)',
						'+8' => 'Indonesia Central Time Zone(UTC+08:00)',
						'+9' => 'Indonesia Eastern Time Zone(UTC+09:00)'
						);
						*/
						$opts = array();
						foreach($timezones as $timezone)
						{
							$timezone = (array)$timezone;
							$opts[$timezone['UTC offset'].'_'.$timezone['zone_id']] = $timezone['TimeZone'].' ('.$timezone['UTC offset'].')';
						}
						//$selected = set_value('location_time_zone', (isset($record->location_time_zone)) ? $record->location_time_zone : '0');
						$selected = '0';						
						if(isset($_POST['submitbutt']))
						{
							$selected = $_POST['location_time_zone'];
						}
						elseif(isset($record->location_time_zone))
						{
							$selected = $record->location_time_zone.'_'.$record->location_time_zone_id;
						}

						echo form_dropdown('location_time_zone', $opts, $selected, 'id="location_time_zone" class="chzn-select"');
					?>	  
			   </div>
			   
			   
			   
			   <div class="form-group">
					<label><?php echo getPhrase('Address');?></label>
					 <textarea name="address" id="address"><?php echo set_value('address', (isset($record->address)) ? $record->address : '');?></textarea>
					 <?php echo form_error('address'); ?>
				</div>
				<div class="form-group">
					<label><?php echo getPhrase('Image');?></label>
					 <input type="file" name="location_image" id="location_image" value="<?php echo set_value('location_image', (isset($record->location_image)) ? $record->location_image : '');?>"/>
					 <?php if(isset($record->location_image) && $record->location_image != '') { echo '<img src="'.base_url().'/uploads/location_images/thumbs/'.$record->location_image.'?dummy='.time().'">';}?>
					<?php echo form_error('location_image'); ?>
				</div>
			   

				<div class="form-group">
				 <label><?php if(isset($this->phrases["is airport"])) echo $this->phrases["is airport"]; else echo "Is Airport";?>?</label>			   
					<?php

						$first_opt = (isset($this->phrases["no"])) ? $this->phrases["no"] : "No";
						$sec_opt   = (isset($this->phrases["yes"])) ? $this->phrases["yes"] : "Yes";

						$opts = array(
										'0' => $first_opt, 
										'1'	=> $sec_opt
									 );

						$selected = set_value('is_airport', (isset($record->is_airport)) ? $record->is_airport : '0');

						echo form_dropdown('is_airport', $opts, $selected, 'id="is_airport" class="chzn-select"');
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

				 <input type="hidden" name="update_rec_id" value="<?php if(isset($record->id)) echo $record->id;?>" />

				<div class="form-group">
					<button type="submit" name="submitbutt" class="btn btn-success"><?php if(isset($record->id)) echo (isset($this->phrases["update"])) ? $this->phrases["update"] : "Update"; else echo (isset($this->phrases["add"])) ? $this->phrases["add"] : "Add";?></button>
					<a onclick="window.location.href = '<?php echo base_url().'settings/locations/list';?>';" class="btn btn-info" title="<?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?>"> <?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>
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
fun_location_visibility_type();
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
                location: {
                          required: true      
                      }
                  },

				messages: {
					location: {
							  required: "<?php if(isset($this->phrases["please enter location / airport name"])) echo $this->phrases["please enter location / airport name"]; else echo "Please enter Location / Airport Name";?>."
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