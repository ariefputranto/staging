  <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
		  <div class="inner-elements">

			<?php echo $this->session->flashdata('message');?>
				<?php 
				  $attributes = array('name' => 'formm', 'id' => 'formm');
				  echo form_open_multipart('settings/vehicles/'.$param,$attributes);?> 

				<?php if(count($records) > 0) $record = $records[0]; 
					?>
			<div class="col-md-6">
				<div class="form-group">
				 <label><?php if(isset($this->phrases["category"])) echo $this->phrases["category"]; else echo "Category";?></label>
					<?php

						$selected = set_value('category_id', (isset($record->category_id)) ? $record->category_id : '');

						echo form_dropdown('category_id', $vehicle_cat_opts, $selected, 'id="category_id" class="chzn-select"').form_error('category_id');
					?>	  
			   </div>

				<div class="form-group">
					<label><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?>&nbsp;<font color="red">*</font></label>
					 <input type="text" name="name" id="name" placeholder="<?php if(isset($this->phrases["vehicle name"])) echo $this->phrases["vehicle name"]; else echo "Vehicle Name";?>" value="<?php echo set_value('name', (isset($record->name)) ? $record->name : '');?>"/>
					<?php echo form_error('name'); ?>
				</div>

				<div class="form-group">
					<label><?php if(isset($this->phrases["model"])) echo $this->phrases["model"]; else echo "Model";?>&nbsp;<font color="red">*</font></label>
					 <input type="text" name="model" id="model" placeholder="<?php if(isset($this->phrases["vehicle model"])) echo $this->phrases["vehicle model"]; else echo "Vehicle Model";?>" value="<?php echo set_value('model', (isset($record->model)) ? $record->model : '');?>"/>
					<?php echo form_error('model'); ?>
				</div>

				<div class="form-group">
					<label><?php if(isset($this->phrases["vehicle number"])) echo $this->phrases["vehicle number"]; else echo "Vehicle Number";?>&nbsp;<font color="red">*</font></label>
					 <input type="text" name="number_plate" id="number_plate" placeholder="<?php if(isset($this->phrases["vehicle number on plate"])) echo $this->phrases["vehicle number on plate"]; else echo "Vehicle number on plate";?>" value="<?php echo set_value('number_plate', (isset($record->number_plate)) ? $record->number_plate : '');?>"/>
					<?php echo form_error('number_plate'); ?>
				</div>

				<div class="form-group">
				 <label><?php if(isset($this->phrases["fuel type"])) echo $this->phrases["fuel type"]; else echo "Fuel Type";?></label>			   
					<?php
						$first_opt = (isset($this->phrases["petrol"])) ? $this->phrases["petrol"] : "Petrol";
						$sec_opt   = (isset($this->phrases["diesel"])) ? $this->phrases["diesel"] : "Diesel";
						$third_opt = (isset($this->phrases["gas"])) ? $this->phrases["gas"] : "Gas";

						$fuel_type_opts = array(
												 'petrol' 	=> $first_opt, 
												 'diesel' 	=> $sec_opt, 
												 'gas'		=> $third_opt
												);

						$selected = set_value('fuel_type', (isset($record->fuel_type)) ? $record->fuel_type : '');

						echo form_dropdown('fuel_type', $fuel_type_opts, $selected, 'id="fuel_type" class="chzn-select"');
					?>	  
			   </div>

				<div class="form-group">
					<label><?php if(isset($this->phrases["passenger capacity"])) echo $this->phrases["passenger capacity"]; else echo "Passenger Capacity";?>&nbsp;<font color="red">*</font></label>
					 <input type="text" name="passenger_capacity" id="passenger_capacity" placeholder="<?php if(isset($this->phrases["number of passengers"])) echo $this->phrases["number of passengers"]; else echo "Number of Passengers";?>" value="<?php echo set_value('passenger_capacity', (isset($record->passenger_capacity)) ? $record->passenger_capacity : '');?>"/>
					<?php echo form_error('passenger_capacity'); ?>
				</div>

				<div class="form-group">
					<label><?php if(isset($this->phrases["large luggage capacity"])) echo $this->phrases["large luggage capacity"]; else echo "Large Luggage Capacity";?>&nbsp;<font color="red">*</font></label>
					 <input type="text" name="large_luggage_capacity" id="large_luggage_capacity" placeholder="<?php if(isset($this->phrases["in kg's"])) echo $this->phrases["in kg's"]; else echo "In kg's";?>" value="<?php echo set_value('large_luggage_capacity', (isset($record->large_luggage_capacity)) ? $record->large_luggage_capacity : '');?>"/>
					<?php echo form_error('large_luggage_capacity'); ?>
				</div>

				<div class="form-group">
					<label><?php if(isset($this->phrases["small luggage capacity"])) echo $this->phrases["small luggage capacity"]; else echo "Small Luggage Capacity";?>&nbsp;<font color="red">*</font></label>
					 <input type="text" name="small_luggage_capacity" id="small_luggage_capacity" placeholder="<?php if(isset($this->phrases["in kg's"])) echo $this->phrases["in kg's"]; else echo "In kg's";?>" value="<?php echo set_value('small_luggage_capacity', (isset($record->small_luggage_capacity)) ? $record->small_luggage_capacity : '');?>"/>
					<?php echo form_error('small_luggage_capacity'); ?>
				</div>

				<div class="form-group">
					<label><?php if(isset($this->phrases["description"])) echo $this->phrases["description"]; else echo "Description";?></label>
					 <textarea name="description" id="description"><?php echo set_value('description', (isset($record->description)) ? $record->description : '');?></textarea>
					<?php echo form_error('description'); ?>
				</div>

			</div>

			<div class="col-md-6">
				<div class="form-group">
					<label><?php 
					//print_r($record);
					if(isset($this->phrases["browse image ( upload vehicle image )"])) echo $this->phrases["browse image ( upload vehicle image )"]; else echo "Browse Image ( Upload Vehicle Image )";?>  </label>
					 <input type="file" name="userfile" id="image-input" onchange="previewImages(this);" style="width:80px;">
					  <?php echo form_error('userfile');?>
				</div>

				<input type="hidden" name="is_image_set" id="is_image_set" value="">
					<div class="preview-area">

						<?php if(isset($record->image) && $record->image != "" && file_exists('uploads/vehicle_images/'.$record->image)) { ?>
							<img height="100" src="<?php echo base_url();?>uploads/vehicle_images/<?php echo $record->image;?>" >
						<?php } ?>

					</div>

				<?php if($site_theme == 'vehicle') { ?>
				<div class="form-group">
					<label><?php if(isset($this->phrases["total vehicles"])) echo $this->phrases["total vehicles"]; else echo "Total Vehicles";?></label>
					 <input type="text" name="total_vehicles" id="total_vehicles" value="<?php echo set_value('total_vehicles', (isset($record->total_vehicles)) ? $record->total_vehicles : '');?>"/>
					<?php echo form_error('total_vehicles'); ?>
				</div>
				<?php } ?>
				
				<div class="form-group">
					<label><?php echo getPhrase('Rows');
					$seat_rows = set_value('seat_rows', (isset($record->seat_rows)) ? $record->seat_rows : '1');
					?></label>
					 <input type="text" name="seat_rows" id="seat_rows" value="<?php echo $seat_rows;?>"/>
					<?php echo form_error('seat_rows'); ?>
				</div>
				<div class="form-group">
					<label><?php echo getPhrase('Columns');
					$seat_columns = set_value('seat_columns', (isset($record->seat_columns)) ? $record->seat_columns : '1');
					?></label>
					 <input type="text" name="seat_columns" id="seat_columns" value="<?php echo $seat_columns;?>"/>
					<?php echo form_error('seat_columns'); ?>
				</div>
				
				<div class="form-group">
					<label><?php 
					echo getPhrase('Empty Space');
					$child_seats_opts = [];
					$seat_no = 1;
					if(isset($record->has_driver_seat) && $record->has_driver_seat == 'Yes')
					$seat_no = 2;
					//for($r = 1; $r <= $seat_columns; $r++)
					for($r = 1; $r <= $seat_rows; $r++)
					{
						//for($c = 1; $c <= $seat_rows; $c++)
						for($c = 1; $c <= $seat_columns; $c++)
						{
							$seat = 'R'.$r.'C'.$c;
							$child_seats_opts[$seat] = $seat_no;
							$seat_no++;
						}
					}
					?></label>
					 <?php
					$selected = set_value('seats_empty[]', (isset($record->seats_empty) && $record->seats_empty != '') ? explode(',', $record->seats_empty) : '');
					echo form_dropdown('seats_empty[]', $child_seats_opts, $selected, 'id="seats_empty" class="chzn-select" multiple').form_error('seats_empty[]');
					?>
				</div>
				
				
				<div class="form-group">
					<label><?php echo getPhrase('Child Seats');
					$child_seats_opts = [];
					$seat_no = 1;
					if(isset($record->has_driver_seat) && $record->has_driver_seat == 'Yes')
					$seat_no = 2;
					//for($r = 1; $r <= $seat_columns; $r++)
					for($r = 1; $r <= $seat_rows; $r++)
					{
						//for($c = 1; $c <= $seat_rows; $c++)
						for($c = 1; $c <= $seat_columns; $c++)
						{
							$seat = 'R'.$r.'C'.$c;
							$child_seats_opts[$seat] = $seat_no;
							$seat_no++;
						}
					}
					?></label>
					<?php
					$selected = set_value('child_seats[]', (isset($record->child_seats) && $record->child_seats != '') ? explode(',', $record->child_seats) : '');
					echo form_dropdown('child_seats[]', $child_seats_opts, $selected, 'id="child_seats" class="chzn-select" multiple').form_error('child_seats[]');
					?>
				</div>
				
				<div class="form-group">
					<label><?php echo getPhrase('Has Driver Seat');?></label>
					<?php $selected = set_value('has_driver_seat', (isset($record->has_driver_seat)) ? $record->has_driver_seat : '');?>
					<?php					
					echo form_dropdown('has_driver_seat', array('No' => 'No', 'Yes' => 'Yes'), $selected, 'id="has_driver_seat" class="chzn-select"').form_error('has_driver_seat');
					?>
				</div>
								

				<?php if($site_theme == 'vehicle') { ?>
				<div class="form-group">
					<label><?php if(isset($this->phrases["base fare"])) echo $this->phrases["base fare"]; else echo "Base Fare";?></label>
					 <input type="text" name="base_fare" id="base_fare" value="<?php echo set_value('base_fare', (isset($record->base_fare)) ? $record->base_fare : '');?>"/>
					<?php echo form_error('base_fare'); ?>
				</div>

				<div class="form-group">
					<?php 
							$distance_type = (isset($this->phrases["kilometer"])) ? $this->phrases["kilometer"] : "Kilometer"; 

							if(isset($site_settings->distance_type))
								$distance_type = (isset($this->phrases[$site_settings->distance_type])) ? $this->phrases[$site_settings->distance_type] : $site_settings->distance_type;
						?>
					<label><?php if(isset($this->phrases["cost per"])) echo $this->phrases["cost per"]; else echo "Cost per";?> <?php echo $distance_type; ?></label>
					 <input type="text" name="cost_per_km" id="cost_per_km" value="<?php echo set_value('cost_per_km', (isset($record->cost_per_km)) ? $record->cost_per_km : '');?>"/>
					<?php echo form_error('cost_per_km'); ?>
				</div>

				<div class="form-group">
					<label><?php if(isset($this->phrases["cost per minute"])) echo $this->phrases["cost per minute"]; else echo "Cost per Minute";?></label>
					 <input type="text" name="cost_per_minute" id="cost_per_minute" value="<?php echo set_value('cost_per_minute', (isset($record->cost_per_minute)) ? $record->cost_per_minute : '');?>"/>
					<?php echo form_error('cost_per_minute'); ?>
				</div>
				<?php } ?>

				<?php if(isset($features) && count($features)>0) { ?>
					<label><?php if(isset($this->phrases["vehicle features"])) echo $this->phrases["vehicle features"]; else echo "Vehicle Features";?></label>				
					<div class="form-group">
						<?php $x=1; foreach($features as $f) { ?>
						<input type="checkbox" name="feature_id[]" id="feature_id_<?php echo $x;?>" value="<?php echo $f->id;?>" <?php if(isset($vehicle_features) && in_array($f->id,$vehicle_features)) echo "checked";?> class="css-checkbox" />
						<label for="feature_id_<?php echo $x++;?>" class="css-label-ch"><?php echo (isset($this->phrases[$f->features])) ? $this->phrases[$f->features] : $f->features;?></label>
						 <?php } ?>
					</div>
					<?php } ?>

				<div class="form-group">
				 <label><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></label>			   
					<?php

						$selected = set_value('status', (isset($record->status)) ? $record->status : '');

						$first_opt = (isset($this->phrases["active"])) ? $this->phrases["active"] : "Active";
						$sec_opt   = (isset($this->phrases["inactive"])) ? $this->phrases["inactive"] : "Inactive";

						$opts = array(
									'Active' => $first_opt,
									'Inactive' => $sec_opt
									);

						echo form_dropdown('status', $opts, $selected, 'id="status" class="chzn-select" ');
					?>	  
			   </div>

				 <input name="current_img" id="current_img" type="hidden" value="<?php if(isset($record->image)) echo $record->image; ?>">	  
				 <input type="hidden" name="update_rec_id" value="<?php if(isset($record->id)) echo $record->id;?>" />

				<div class="form-group">	  
					<button type="submit" class="btn btn-success"><?php if(isset($record->id)) echo (isset($this->phrases["update"])) ? $this->phrases["update"] : "Update"; else echo (isset($this->phrases["add"])) ? $this->phrases["add"] : "Add";?></button>
					<a onclick="window.location.href = '<?php echo base_url().'settings/vehicles/list';?>';" class="btn btn-info" title="<?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?>"> <?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>
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
                category_id: {
                          required: true      
                      },
				name: {
                          required: true      
                      },
				model: {
                          required: true      
                      },
				number_plate: {
                          required: true      
                      },
				passenger_capacity: {
                          required: true, 
                          number: true
                      },
				large_luggage_capacity: {
                          required: true, 
                          number: true
                      },
				small_luggage_capacity: {
                          required: true, 
                          number: true
                      },
				<?php if($site_theme == 'vehicle') { ?>
				total_vehicles: {
                          required: true,
                          number: true
                      },
				base_fare: {
                          required: true,
                          proper_value: true
                      },
				cost_per_km: {
                          required: true,
                          proper_value: true
                      },
				cost_per_minute: {
                          required: true,
                          proper_value: true
                      },
				<?php } ?>
                userfile: {
						extension: "png|jpg|jpeg"
					}
                  },

				messages: {
					category_id: {
							  required: "<?php if(isset($this->phrases["please select vehicle category"])) echo $this->phrases["please select vehicle category"]; else echo "Please select Vehicle Category";?>."
						  },
					name: {
							  required: "<?php if(isset($this->phrases["please enter vehicle name"])) echo $this->phrases["please enter vehicle name"]; else echo "Please enter Vehicle Name";?>."
						  },
					model: {
							  required: "<?php if(isset($this->phrases["please enter vehicle model"])) echo $this->phrases["please enter vehicle model"]; else echo "Please enter Vehicle Model";?>." 
                      },
					number_plate: {
							  required: "<?php if(isset($this->phrases["please enter vehicle number plate"])) echo $this->phrases["please enter vehicle number plate"]; else echo "Please enter Vehicle number plate";?>."      
						  },
					passenger_capacity: {
							  required: "<?php if(isset($this->phrases["please enter passenger capacity"])) echo $this->phrases["please enter passenger capacity"]; else echo "Please enter Passenger capacity";?>." 
						  },
					large_luggage_capacity: {
							  required: "<?php if(isset($this->phrases["please enter large luggage capacity"])) echo $this->phrases["please enter large luggage capacity"]; else echo "Please enter large luggage capacity";?>."
						  },
					small_luggage_capacity: {
							  required: "<?php if(isset($this->phrases["please enter small luggage capacity"])) echo $this->phrases["please enter small luggage capacity"]; else echo "Please enter small luggage capacity";?>."
						  },
					<?php if($site_theme == 'vehicle') { ?>
					total_vehicles: {
							  required: "<?php if(isset($this->phrases["please enter total number of vehicles"])) echo $this->phrases["please enter total number of vehicles"]; else echo "Please enter total number of vehicles";?>."
						  },
					base_fare: {
                          required: "<?php if(isset($this->phrases["please enter base fare"])) echo $this->phrases["please enter base fare"]; else echo "Please enter Base Fare";?>."
                      },
					cost_per_km: {
                          required: "<?php if(isset($this->phrases["please enter cost per"])) echo $this->phrases["please enter cost per"]; else echo "Please enter cost per";?> <?php echo $distance_type; ?>."
                      },
					cost_per_minute: {
                          required: "<?php if(isset($this->phrases["please enter cost per minute"])) echo $this->phrases["please enter cost per minute"]; else echo "Please enter cost per minute";?>."
                      },
					<?php } ?>
					userfile: {
						extension: "<?php if(isset($this->phrases["please upload image with the extension jpg|jpeg|png"])) echo $this->phrases["please upload image with the extension jpg|jpeg|png"]; else echo "please upload image with the extension jpg|jpeg|png";?>."
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


   /* Preview Uploaded Images */
	function previewImages(input)
	{
		var fileList = input.files;

		var anyWindow = window.URL || window.webkitURL;
		input.style.width = '100%';
		$('.preview-area').html('');
			for(var i = 0; i < fileList.length; i++){
			 /* get a blob */
			  var objectUrl = anyWindow.createObjectURL(fileList[i]);
			   /* for the next line to work, you need something class="preview-area" in your html */
			  $('.preview-area').append('<img height="100" src="' + objectUrl + '" />');
			  /* get rid of the blob */
			  window.URL.revokeObjectURL(fileList[i]);
			  $('#is_image_set').val('yes');
			}
	}

   </script>
