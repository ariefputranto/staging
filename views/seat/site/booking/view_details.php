<section class="apps line">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="inner_con tb">
         
         
       
	 
          <div class="car_list">
		  <div class="rate"> Total: 
			<?php 
				$cost_of_journey = '';
				if(isset($record['cost_of_journey']) && isset($record['cost_for_meet_greet']))
					$cost_of_journey = (($record['cost_of_journey'])+($record['cost_for_meet_greet']));
				echo $site_settings->currency_symbol.$cost_of_journey;
			?> </div>
			 
          <table width="100%" border="1" bordercolor="#6b6b6b" class="datatable">
		  <tbody>

			<tr>
			  <td><strong><?php if(isset($this->phrases["journey type"])) echo $this->phrases["journey type"]; else echo "Journey Type";?></strong></td>
			  <td>
				  <?php if(isset($record['journey_type'])) echo $record['journey_type']; ?>
			  </td>
			  <td></td>
			  <td></td>
			</tr>

			<tr>
			  <td><strong><?php if(isset($this->phrases["pick-up location"])) echo $this->phrases["pick-up location"]; else echo "Pick-up Location";?></strong></td>
			  <td>
				  <?php if(isset($record['pick_point_name'])) echo $record['pick_point_name']; ?>
			  </td>
			  <td><strong><?php if(isset($this->phrases["drop-off location"])) echo $this->phrases["drop-off location"]; else echo "Drop-off Location";?></strong></td>
			  <td>
				  <?php if(isset($record['drop_point_name'])) echo $record['drop_point_name']; ?>
			  </td>
			</tr>

			<tr>
			  <td><strong><?php if(isset($this->phrases["pick-up date"])) echo $this->phrases["pick-up date"]; else echo "Pick-up Date";?></strong></td>
			  <td>
				  <?php if(isset($record['pick_date'])) echo $record['pick_date']; ?>
			  </td>
			  <td><strong><?php if(isset($this->phrases["pick-up time"])) echo $this->phrases["pick-up time"]; else echo "Pick-up Time";?></strong></td>
			  <td>
				  <?php if(isset($record['pick_time'])) echo $record['pick_time']; ?>
			  </td>
			</tr>

			<?php if(isset($record['journey_type']) && $record['journey_type'] == "Round-Trip") { ?>
				<tr>
				  <td><strong><?php if(isset($this->phrases["return pick-up date"])) echo $this->phrases["return pick-up date"]; else echo "Return Pick-up Date";?></strong></td>
				  <td>
					  <?php if(isset($record['return_pick_date'])) echo $record['return_pick_date']; ?>
				  </td>
				  <td><strong><?php if(isset($this->phrases["return pick-up time"])) echo $this->phrases["return pick-up time"]; else echo "Return Pick-up Time";?></strong></td>
				  <td>
					  <?php if(isset($record['return_pick_time'])) echo $record['return_pick_time']; ?>
				  </td>
				</tr>
			<?php } ?>

			<tr>
			  <td><strong><?php if(isset($this->phrases["distance"])) echo $this->phrases["distance"]; else echo "Distance";?></strong></td>
			  <td>
				  <?php if(isset($record['ip_dist_txt'])) echo $record['ip_dist_txt']; ?>
			  </td>
			  <td><strong><?php if(isset($this->phrases["journey time"])) echo $this->phrases["journey time"]; else echo "Journey Time";?></strong></td>
			  <td>
				  <?php if(isset($record['ip_time_txt'])) echo $record['ip_time_txt']; ?>
			  </td>
			</tr>

			<tr>
			  <td><strong><?php if(isset($this->phrases["vehicle selected"])) echo $this->phrases["vehicle selected"]; else echo "Vehicle Selected";?></strong></td>
			  <td>
				  <?php if(isset($record['car_name'])) echo $record['car_name']; ?>
			  </td>
			  <td><strong><?php if(isset($this->phrases["cost of journey"])) echo $this->phrases["cost of journey"]; else echo "Cost of Journey";?></strong></td>
			  <td>
				  <?php if(isset($record['cost_of_journey'])) echo $site_settings->currency_symbol.$record['cost_of_journey']; ?>
			  </td>
			</tr>

			<tr>
			  <td><strong><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?></strong></td>
			  <td>
				  <?php if(isset($record['registered_name'])) echo $record['registered_name']; ?>
			  </td>
			  <td><strong><?php if(isset($this->phrases["email"])) echo $this->phrases["email"]; else echo "Email";?></strong></td>
			  <td>
				  <?php if(isset($record['email'])) echo $record['email']; ?>
			  </td>
			</tr>

			<tr>
			  <td><strong><?php if(isset($this->phrases["phone"])) echo $this->phrases["phone"]; else echo "Phone";?></strong></td>
			  <td>
				  <?php if(isset($record['phone'])) echo $record['phone']; ?>
			  </td>
			  <td><strong><?php if(isset($this->phrases["additional info"])) 
			 echo $this->phrases["additional info"]; else echo "Additional Info.";?></strong></td>
			  <td>
				  <?php if(isset($record['additional_info']) && $record['additional_info'] != "") echo $record['additional_info']; else echo "NA"; ?>
			  </td>
			</tr>

			<tr>
			  <td><strong><?php if(isset($this->phrases["complete pick-up address"])) echo $this->phrases["complete pick-up address"]; else echo "Complete Pick-up Address";?></strong></td>
			  <td>
				  <?php if(isset($record['complete_pickup_address'])) echo $record['complete_pickup_address']; ?>
			  </td>
			  <td><strong><?php if(isset($this->phrases["drop-off address"])) 
			 echo $this->phrases["drop-off address"]; else echo "Drop-off Address";?></strong></td>
			  <td>
				  <?php if(isset($record['complete_destination_address'])) echo $record['complete_destination_address']; ?>
			  </td>
			</tr>
			
			<?php if(isset($record['arrivingfrom_airport']) && $record['arrivingfrom_airport'] == 'Yes') {?>
			<tr>
			  <?php if(isset($record['flight_num']) && $record['flight_num'] != "") { ?>
			  <td><strong><?php if(isset($this->phrases["flight number"])) 
			 echo $this->phrases["flight number"]; else echo "Flight Number";?></strong></td>
			  <td>
				  <?php if(isset($record['flight_num'])) echo $record['flight_num']; ?>
			  </td>
			  <?php } if(isset($record['terminal_num']) && $record['terminal_num'] != "") { ?>
			  <td><strong><?php if(isset($this->phrases["terminal number"])) 
			 echo $this->phrases["terminal number"]; else echo "Terminal Number";?></strong></td>
			  <td>
				  <?php if(isset($record['terminal_num'])) echo $record['terminal_num']; ?>
			  </td>
			  <?php } ?>
			</tr>

			<tr>
			  <?php if(isset($record['arriving_from']) && $record['arriving_from'] != "") { ?>
			  <td><strong><?php if(isset($this->phrases["arriving from"])) echo $this->phrases["arriving from"]; else echo "Arriving From";?></strong></td>
			  <td>
				  <?php if(isset($record['arriving_from'])) echo $record['arriving_from']; ?>
			  </td>
			  <?php } if(isset($record['flight_num']) && $record['flight_num'] != "" && isset($record['meet_greet']) && $record['meet_greet'] != "") { ?>
			  <td><strong><?php if(isset($this->phrases["meet & greet"])) echo $this->phrases["meet & greet"]; else echo "Meet & Greet";?></strong></td>
			  <td>
				  <?php if(isset($record['meet_greet'])) echo $record['meet_greet']; ?>
			  </td>
			  <?php } ?>
			</tr>

			<tr>
			<?php if(isset($record['meet_greet']) && $record['meet_greet'] == "Yes") { ?>
			  <td><strong><?php if(isset($this->phrases["cost for meet & greet"])) echo $this->phrases["cost for meet & greet"]; else echo "Cost For Meet & Greet";?></strong></td>
			  <td>
				  <?php if(isset($record['cost_for_meet_greet'])) echo $site_settings->currency_symbol.$record['cost_for_meet_greet']; ?>
			  </td>
			  <?php } ?>
			  <td><strong><?php if(isset($this->phrases["total"])) echo $this->phrases["total"]; else echo "Total";?></strong></td>
			  <td>
				  <?php echo $site_settings->currency_symbol.$cost_of_journey; ?>
			  </td>
			</tr>
			<?php } ?>

		  </tbody>
		</table>

          <aside class="pull-left"> 
          <a href="<?php echo base_url();?>booking/passengerDetails">
           <div class="btn btn-danger next_btn pre_btn">
			    <i class="fa fa-arrow-circle-o-left"></i> <?php if(isset($this->phrases["previous step"])) echo $this->phrases["previous step"]; else echo "Previous Step";?> 
			   </div> </a></aside>
           <aside class="pull-right"> 
           <a href="<?php echo base_url();?>booking/payment">
           <div class="btn btn-danger next_btn">
			    <i class="fa fa-arrow-circle-o-right"></i> <?php if(isset($this->phrases["book now"])) echo $this->phrases["book now"]; else echo "Book Now";?> 
			   </div> </a></aside>
          <div class="clearfix"></div>
         </div>
        </div>
      </div>
    </div>
  </div>
</section>
