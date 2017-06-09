 <script type= "text/javascript" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js" ></script>
 <link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/datepicker.css" rel="stylesheet">

<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/timepicki.css" rel="stylesheet">

  <?php
  
		/* Prepare From Location Options */
		$from_locations = $this->base_model->run_query("select id, location from ".DBPREFIX."locations where status='Active' and (location_visibility_type ='start' or location_visibility_type ='both') order by location asc");
  
		$from_loc_opts = array('' => 'Select Strat Location');
		foreach($from_locations as $rec)
			$from_loc_opts[$rec->id] = $rec->location;


		/* Prepare To Location Options */
		$to_locations = $this->base_model->run_query("select id, location from ".DBPREFIX."locations where status='Active' and (location_visibility_type ='end' or location_visibility_type ='both') order by location asc");
  
		$to_loc_opts = array('' => 'Select End Location');
		foreach($to_locations as $rec)
			$to_loc_opts[$rec->id] = $rec->location;
			
		/* Prepare Via Points */
		$transition_points = $this->base_model->run_query("select id, location from ".DBPREFIX."locations where status='Active' AND (location_visibility_type ='start' or location_visibility_type ='both') order by location asc");
  
		$via_loc_opts = array();
		foreach($transition_points as $rec)
			$via_loc_opts[$rec->id] = $rec->location;
 
  ?>
  
  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  
		  <?php 
				  $attributes = array('name' => 'formm', 'id' => 'formm');
				  echo form_open('settings/travelLocations/'.$param,$attributes);?> 
		  <div class="inner-elements">
			<div class="col-md-6">
			<?php echo $message;?>
				

				<?php if(count($records) > 0) $record = $records[0]; 
					?>


				<div class="form-group">
				 <label><?php if(isset($this->phrases["start location"])) echo $this->phrases["start location"]; else echo "Start Location";?></label>			   
					<?php

						$selected = set_value('from_loc_id', (isset($record->from_loc_id)) ? $record->from_loc_id : '');
						
						$selected_from_loc_id = $selected;

						echo form_dropdown('from_loc_id', $from_loc_opts, $selected, 'id="from_loc_id" class="chzn-select"').form_error('from_loc_id');
					?>	  
			    </div>

				<div class="form-group">
				 <label><?php echo getPhrase('Transition Points (Select in order of their appearing)')?></label>		   
				<?php
				$selected = array();
				if(isset($record->travel_location_id))
				{
					$query = 'SELECT l.id,l.location FROM '.$this->db->dbprefix('locations').' l 
					INNER JOIN '.$this->db->dbprefix('travel_locations_transitions').' tlt ON tlt.location_id = l.id 
					INNER JOIN '.$this->db->dbprefix('travel_locations').' tl ON tl.travel_location_id = tlt.travel_location_id WHERE tlt.travel_location_id = '.$record->travel_location_id;
					
					$records = $this->db->query($query)->result();
					if(count($records) > 0)
					{
						foreach($records as $vrecord)
						{
							$selected[] = $vrecord->id;
						}
					}
				}
				//echo $selected;die();
				echo form_dropdown('transition_points[]', $via_loc_opts, $selected, 'id="transition_points" class="chzn-select" multiple').form_error('transition_points[]');
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
			</div>
			
			<div class="col-md-6">
				
				<div class="form-group">
				 <label><?php if(isset($this->phrases["end location"])) echo $this->phrases["end location"]; else echo "End Location";?></label>			   
					<?php
//print_r($record);
						$selected = set_value('to_loc_id', (isset($record->to_loc_id)) ? $record->to_loc_id : '');

						echo form_dropdown('to_loc_id', $to_loc_opts, $selected, 'id="to_loc_id" class="chzn-select"').form_error('to_loc_id');
					?>
			    </div>
				   
				   
				<div class="form-group">	  
					<input type="hidden" name="update_rec_id" value="<?php if(isset($record->travel_location_id)) echo $record->travel_location_id;?>" />
					<button type="submit" name="submitbutt" class="btn btn-success"><?php if(isset($record->travel_location_id)) echo (isset($this->phrases["update"])) ? $this->phrases["update"] : "Update"; else echo (isset($this->phrases["add"])) ? $this->phrases["add"] : "Add";?></button>
					<a onclick="window.location.href = '<?php echo base_url().'settings/travelLocations/list';?>';" class="btn btn-info" title="<?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?>"> <?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>
				</div>
			</div>
			
			</form>

		  </div>
      </div>
    </div>
  </div>
</section>


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
                from_loc_id: {
                          required: true      
                      },
                to_loc_id: {
                          required: true      
                      }
                  },

				messages: {
					from_loc_id: {
							  required: "<?php if(isset($this->phrases["please select start location"])) echo $this->phrases["please select start location"]; else echo "Please select Start Location";?>."
						  },
					to_loc_id: {
							  required: "<?php if(isset($this->phrases["please select end location"])) echo $this->phrases["please select end location"]; else echo "Please select End Location";?>."
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