<?php

	/* Prepare Start Location Options */
	$start_locations = $this->base_model->getLocations("start");

	$first_opt = (isset($this->phrases["select pick-up location"])) ? 
	$this->phrases["select pick-up location"] : "Select Pick-up Location";

	$start_location_opts = array('' => $first_opt);
	foreach($start_locations as $rec)
		$start_location_opts[$rec->id] = $rec->location;

	/** Date & Time **/
	/* Default Pick-up/Return Pick-up Dates */
	$today = date('d-m-Y');
	$pick_date_default = $today;
	$return_pick_date_default = date('d-m-Y', strtotime($today . "+1 days")); //Adding a day to current Date.
	/* Default Pick-up/Return Pick-up Times */
	$pick_time_default = date('h : i : A', time() + 60*40); //Adding 40 Minutes to current Time.
	$return_pick_time_default = $pick_time_default;


	if(count($this->session->userdata('journey_booking_details')) > 0) {
		$record = $this->session->userdata('journey_booking_details');
		//$this->session->unset_userdata('journey_booking_details');
	}

  ?>  
<section class="apps line">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="inner_con">
         <a href="<?php echo base_url();?>"> <div class="roundOne innround"><img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/caricon.png"/></div></a>
       

           <?php echo $this->session->flashdata('message');?>
 

          <?php
				echo form_open('booking', "id='booking_form' name='booking_form' class=''");
				?>

				  <input type="hidden" name="pick_point_name" id="pick_point_name" value="<?php if(isset($record['pick_point_name'])) echo $record['pick_point_name'];?>" />
				  <input type="hidden" name="drop_point_name" id="drop_point_name" value="<?php if(isset($record['drop_point_name'])) echo $record['drop_point_name'];?>" />
				  <input type="hidden" name="distance" id="distance" value="<?php if(isset($record['distance'])) echo $record['distance'];?>" />
				  <input type="hidden" name="ip_dist_txt" id="ip_dist_txt" value="<?php if(isset($record['ip_dist_txt'])) echo $record['ip_dist_txt'];?>" />
				  <input type="hidden" name="total_time" id="total_time" value="<?php if(isset($record['total_time'])) echo $record['total_time'];?>" />
				  <input type="hidden" name="ip_time_txt" id="ip_time_txt" value="<?php if(isset($record['ip_time_txt'])) echo $record['ip_time_txt'];?>" />

		   <div class="col-lg-6">
		 
					 <label class="radio">
					<input type="radio" name="journey_type" id="one_way" value="One-Way" <?php if(isset($record['journey_type']) && $record['journey_type'] == "One-Way") echo 'checked'; elseif(!isset($record)) echo 'checked';?> aria-label="..." onclick="set_journey_type(this.value);"> <?php if(isset($this->phrases["one way journey"])) echo $this->phrases["one way journey"]; else echo "One Way Journey";?>
					<span class="outer"><span class="inner"></span></span></label>
					 
				</div>
				  
					   <div class="col-lg-6">
					   	 <label class="radio">
		 
					<input type="radio" name="journey_type" id="two_way" value="Round-Trip" <?php if(isset($record['journey_type']) && $record['journey_type'] == "Round-Trip") echo 'checked';?> aria-label="..." onclick="set_journey_type(this.value);"> <?php if(isset($this->phrases["return journey"])) echo $this->phrases["return journey"]; else echo "Return Journey";?> <span class="outer"><span class="inner"></span></span></label> 
					</div>
				  
				     <div class="col-lg-6">
				 <label><?php if(isset($this->phrases["pick-up location"])) echo $this->phrases["pick-up location"]; else echo "Pick-up Location";?></label>
					<?php

						$selected = set_value('pick_point', (isset($record['pick_point'])) ? $record['pick_point'] : '');

						echo form_dropdown('pick_point', $start_location_opts, $selected, 'id="pick_point" class="chzn-select" onchange="get_end_locations(this.value);"').form_error('pick_point');
					?>
					</div>
					
					   <div class="col-lg-6">
				 
				 <label><?php if(isset($this->phrases["drop-off location"])) echo $this->phrases["drop-off location"]; else echo "Drop-off Location";?></label>
					<?php

						$first_opt = (isset($this->phrases["select pick-up location first"])) ? $this->phrases["select pick-up location first"] : "Select Pick-up Location First";

						$end_location_opts = array('' => $first_opt);

						$selected = set_value('drop_point', (isset($record['drop_point'])) ? $record['drop_point'] : '');

						echo form_dropdown('drop_point', $end_location_opts, $selected, 'id="drop_point" class="chzn-select" onchange="get_journey_details();"').form_error('drop_point');
					?>
				 </div>
				 
				    <div class="col-lg-6">
				 <label><?php if(isset($this->phrases["pick-up date"])) echo $this->phrases["pick-up date"]; else echo "Pick-up Date";?></label>
					<input name="pick_date" id="pick_date" type="text" class="calen span2 dp" readonly kl_virtual_keyboard_secure_input="on" value="<?php echo set_value('pick_date', (isset($record['pick_date'])) ? $record['pick_date'] : $pick_date_default);?>" >
					<?php echo form_error('pick_date'); ?>
					
					</div>
					
					   <div class="col-lg-6">
				 
				<label><?php if(isset($this->phrases["pick-up time"])) echo $this->phrases["pick-up time"]; else echo "Pick-up Time";?></label>
					<input name="pick_time" id="pick_time" type="text" class="tme tp" value="<?php echo set_value('pick_time', (isset($record['pick_time'])) ? $record['pick_time'] : $pick_time_default);?>">
					<?php echo form_error('pick_time'); ?>
			 
</div>
			   <div id="two_way_details_div" style="display:none;">

				    <div class="col-lg-6">
					 <label><?php if(isset($this->phrases["return pick-up date"])) echo $this->phrases["return pick-up date"]; else echo "Return Pick-up Date";?></label>
						<input name="return_pick_date" id="return_pick_date" type="text" class="calen span2 dp" readonly kl_virtual_keyboard_secure_input="on" value="<?php echo set_value('return_pick_date', (isset($record['return_pick_date'])) ? $record['return_pick_date'] : $return_pick_date_default);?>" >
						<?php echo form_error('return_pick_date'); ?>
				 </div>
				 
				    <div class="col-lg-6">
					<label><?php if(isset($this->phrases["return pick-up time"])) echo $this->phrases["return pick-up time"]; else echo "Return Pick-up Time";?></label>
						<input name="return_pick_time" id="return_pick_time" type="text" class="tme tp" value="<?php echo set_value('return_pick_time', (isset($record['return_pick_time'])) ? $record['return_pick_time'] : $return_pick_time_default);?>">
						<?php echo form_error('return_pick_time'); ?>
				   </div>

				</div>

     

       
	 
			  <div id="dist_time" style="<?php if(isset($record['ip_dist_txt'])) echo 'display:block;'; else echo 'display:none';?>">
				  <div id="map_canvas" style="height:150px;display:none;"> </div>
				  

				   
				    <div class="col-lg-6">  <p><strong><?php if(isset($this->phrases["distance"])) echo $this->phrases["distance"]; else echo "Distance";?></strong> <font id="dist_txt"><?php if(isset($record['ip_dist_txt'])) echo $record['ip_dist_txt'];?></font></p></div>
					<div class="col-lg-6"> 				   <p><strong><?php if(isset($this->phrases["journey time"])) echo $this->phrases["journey time"]; else echo "Journey Time";?></strong> <font id="time_txt"><?php if(isset($record['ip_time_txt'])) echo $record['ip_time_txt'];?></font></p> </div>
			  </div>
	 
		
		 <div class="col-lg-12">		 
 <button type="submit" class="btn btn-danger pull-right next_btn">
			 <i class="fa fa-arrow-circle-o-right"></i> <?php if(isset($this->phrases["next step"])) echo $this->phrases["next step"]; else echo "Next Step";?> </button> 
		</div>	  
			  
         </form>
		 <div class="clearfix"></div>
         </div>
 
      </div>
    </div>
  </div>
</section>


<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js"></script> 
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

              //for all select having class .chosen-select
              $.validator.setDefaults({ ignore: ":hidden:not(.chzn-select)" });

              $("#booking_form").validate({
                  rules: {
					pick_point: {
							  required: true
						  },
					drop_point: {
							  required: true
						  }
                  },

				messages: {
					pick_point: {
							  required: "<?php if(isset($this->phrases["please select pick-up location"])) echo $this->phrases["please select pick-up location"]; else echo "Please select Pick-up Location";?>."
						  },
					drop_point: {
							  required: "<?php if(isset($this->phrases["please select drop-off location"])) echo $this->phrases["please select drop-off location"]; else echo "Please select Drop-off Location";?>."
						  }
				},

				errorPlacement: function(error, element) {
					if($(element).attr('class') == "chzn-select chzn-done error")
						$('#'+$(element).attr('id')+'_chzn').after(error);
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



	/* On Document Ready */
   $(document).ready(function() {

		set_journey_type($('input[name="journey_type"]:checked').val());
		get_end_locations($('#pick_point').val());

	});



   /* Set Journey Type */
   function set_journey_type(journey_type)
   {

		if(journey_type == "One-Way") {

			$('#two_way_details_div').fadeOut();

		} else if(journey_type == "Round-Trip") {

			$('#two_way_details_div').fadeIn();

		}
   }


   /* Get End Locations according to Start Location */
	function get_end_locations(start_id)
	{
		if(start_id > 0) {

			$.ajax({
				  type: "post",
				  url: "<?php echo base_url();?>booking/getEndLocations",
				  async: false,
				  data: { 
							start_id:start_id, 
							<?php echo $this->security->get_csrf_token_name();?>:
							"<?php echo $this->security->get_csrf_hash();?>"
						},
				  cache: false, 
				  success: function(data) {

					$('#drop_point').empty();
					$('#drop_point').append(data);
				  },
				  error: function(){
					alert('Ajax Error');
				  }
				}); 

		} else {

			$('#drop_point').empty();
			$('#drop_point').append('<option value=""><?php if(isset($this->phrases["select pick-up location first"])) echo $this->phrases["select pick-up location first"]; else echo "Select Pick-up Location First";?>.</option>');
		}

		$('#drop_point').trigger("liszt:updated");
		<?php if(!isset($record)) { ?>
		$('#dist_time').fadeOut();
		<?php } ?>

	}



	/* Get Journey Details */
	function get_journey_details()
	{

		PickLocation = $('#pick_point option:selected').text();
		DropLocation = $('#drop_point option:selected').text();

		if(PickLocation != '' && DropLocation != '') {

			$('#pick_point_name').val(PickLocation);
			$('#drop_point_name').val(DropLocation);

			get_map(PickLocation, DropLocation);
		}

	}


</script>
