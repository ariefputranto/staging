<section class="apps line">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="inner_con">
            <a href="<?php echo base_url();?>"> <div class="roundOne innround"><img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/caricon.png"/></div></a>
            <?php echo $this->session->flashdata('message');?>
          <div class="car_list cl">

           <ul class="bxslider">

			   <?php if(isset($vehicles) && count($vehicles) >0) {

				   $i = 1;

				   foreach($vehicles as $row) {

					   $cost = $row->cost;
					   if($this->session->userdata('journey_booking_details')['journey_type'] == "Round-Trip")
							$cost *= 2;

				?>

			   <li>
					<div class="scrool-cab <?php if($i++ == 1) echo "active"; ?>">

					 
					  <div class="che-car">
						  <img src="<?php echo base_url();?>uploads/vehicle_images/<?php if($row->image != "" && file_exists('uploads/vehicle_images/'.$row->image)) echo $row->image; else echo "default-car.jpg"; ?>">
					  </div>
					  <h3><?php echo $row->name." - ".$row->model;?></h3>
					  <input type="text" class="members" value="<?php echo $row->passenger_capacity;?>" readonly />
					  <input type="text" class="luggage" value="<?php echo $row->large_luggage_capacity;?>" readonly />
					  <input type="text" class="bags" value="<?php echo $row->small_luggage_capacity;?>" readonly />
					  <input type="text" class="money" value="<?php echo $site_settings->currency_symbol.$cost;?>" readonly />

					   <label class="radio carSeleRa" id="car<?php echo $row->id;?>" onclick="select_vehicle(<?php echo $row->id;?>, '<?php echo $row->name." - ".$row->model;?>', <?php echo $cost;?>);" >
    <input id="radio3" type="radio" name="radios">
    <span class="outer"><span class="inner"></span></span><i class="fa fa-cab"></i> <?php if(isset($this->phrases["select vechile"])) echo $this->phrases["select vechile"]; else echo "Select Vechile";?></label>
	
					 

					</div>
			   </li>

			   <?php } } ?>

           </ul>

           <?php
				echo form_open('booking/vehicleSelection', "id='vehicle_selection_form' name='vehicle_selection_form' class=''");
				?>

				<input type="hidden" name="vehicle_selected" id="vehicle_selected"  />
				<input type="hidden" name="car_name" id="car_name"  />
				<input type="hidden" name="cost_of_journey" id="cost_of_journey"  />

           <aside class="pull-left"> <a href="<?php echo base_url();?>booking">
           <div class="btn btn-danger next_btn pre_btn">
			   <i class="fa fa-arrow-circle-o-left"></i> <?php if(isset($this->phrases["previous step"])) echo $this->phrases["previous step"]; else echo "Previous Step";?> 
			   </div> </a></aside>
          <aside class="pull-right"> <button type="submit" class="btn btn-danger next_btn">
			  <i class="fa fa-arrow-circle-o-right"></i> <?php if(isset($this->phrases["next step"])) echo $this->phrases["next step"]; else echo "Next Step";?> 
			  </button> </aside>
          </form>
          <div class="clearfix"></div>
         </div>
        </div>
      </div>
    </div>
  </div>
</section>


<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js">
</script>
<script>

	/* On Document Ready */
	$(document).ready(function() {
		<?php
			if(count($this->session->userdata('journey_booking_details')) > 0) {
				$record = $this->session->userdata('journey_booking_details');
				if(isset($record['vehicle_selected'])) {
		?>
			select_vehicle(<?php echo $record['vehicle_selected'];?>, 
						   "<?php echo $record['car_name'];?>", 
						   <?php echo $record['cost_of_journey'];?>
						   );
		<?php } } ?>

	});


	/* Get Selected Vehicle & Values Required */
	function select_vehicle(vehicle_id, vehicle_name, cost)
	{

		$('#vehicle_selected').val(vehicle_id);
		$('#car_name').val(vehicle_name);
		$('#cost_of_journey').val(cost);

		$('.sv').removeAttr('style');
		$('#car'+vehicle_id).attr('style', 'background:#449D44;border-color:#398439;');

	}



</script>
