  <div class="col-lg-10 col-md-10 col-sm-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
  <div class="inner-elements">
        <div class="col-md-12">
		<?php echo $this->session->flashdata('message');?>

		<?php

            if(count($shuttle_details) > 0) {

              $shuttle_details = $shuttle_details[0];

              if(!empty($shuttle_details->shuttle_no))
                echo '<p><strong>Shuttle Number: </strong>'.$shuttle_details->shuttle_no.'</p>';

              if(!empty($shuttle_details->name))
                echo '<p><strong>Name: </strong>'.$shuttle_details->name.'</p>';

              if(!empty($shuttle_details->pick_date))
                echo '<p><strong>Pick Date: </strong>'.$shuttle_details->pick_date.'</p>';

              if(!empty($shuttle_details->pick_point_name))
                echo '<p><strong>Pickup Point: </strong>'.$shuttle_details->pick_point_name.' ('.$shuttle_details->start_time.')</p>';

              if(!empty($shuttle_details->drop_point_name))
                echo '<p><strong>Drop off Point: </strong>'.$shuttle_details->drop_point_name.' ('.$shuttle_details->destination_time.')</p>';

            }

    ?>
    
<br/><br/>

		<table id="example" class="cell-border" cellspacing="0" width="100%">
                   <thead>
                     <tr>
                          <th>#</th>
                          <th><?php if(!empty($this->phrases["seat number"])) echo $this->phrases["seat number"]; else echo "Seat Number";?></th>
                          <th><?php if(!empty($this->phrases["passenger name"])) echo $this->phrases["passenger name"]; else echo "Passenger Name";?></th>
                          <th><?php if(!empty($this->phrases["gender"])) echo $this->phrases["gender"]; else echo "Gender";?></th>
                          <!--<th><?php if(!empty($this->phrases["age"])) echo $this->phrases["age"]; else echo "Age";?></th>-->
                          <th><?php if(!empty($this->phrases["email"])) echo $this->phrases["email"]; else echo "Email";?></th> 
                          <th><?php if(!empty($this->phrases["phone"])) echo $this->phrases["phone"]; else echo "Phone";?></th>
                          <th><?php if(!empty($this->phrases["complete pickup address"])) echo $this->phrases["complete pickup address"]; else echo "Complete Pickup Address";?></th>
                          <th><?php if(!empty($this->phrases["complete dropoff address"])) echo $this->phrases["complete dropoff address"]; else echo "Complete Dropoff Address";?></th>
                          <th><?php if(!empty($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
                          <th><?php if(!empty($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </thead>
                  <tfoot>
                    <tr>
                          <th>#</th>
                          <th><?php if(!empty($this->phrases["seat number"])) echo $this->phrases["seat number"]; else echo "Seat Number";?></th>
                          <th><?php if(!empty($this->phrases["passenger name"])) echo $this->phrases["passenger name"]; else echo "Passenger Name";?></th>
                          <th><?php if(!empty($this->phrases["gender"])) echo $this->phrases["gender"]; else echo "Gender";?></th>
                          <!--<th><?php if(!empty($this->phrases["age"])) echo $this->phrases["age"]; else echo "Age";?></th>-->
                          <th><?php if(!empty($this->phrases["email"])) echo $this->phrases["email"]; else echo "Email";?></th> 
                          <th><?php if(!empty($this->phrases["phone"])) echo $this->phrases["phone"]; else echo "Phone";?></th>
                          <th><?php if(!empty($this->phrases["complete pickup address"])) echo $this->phrases["complete pickup address"]; else echo "Complete Pickup Address";?></th>
                          <th><?php if(!empty($this->phrases["complete dropoff address"])) echo $this->phrases["complete dropoff address"]; else echo "Complete Dropoff Address";?></th>
                          <th><?php if(!empty($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
                          <th><?php if(!empty($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
                        </tr>
                  </tfoot>
                      <tbody>

						<?php

							if(count($records) > 0) {

							  $i=1;
							  foreach($records as $row) {

						   ?>

						<tr>
						  <td><?php echo $i++;?></td>
                          <td><?php echo $row->seat;?></td>
                          <td><?php echo $row->name;?></td>
                          <td><?php echo $row->gender;?></td>
                          <!--<td><?php echo $row->age;?></td>-->
                          <td><?php echo $row->email;?></td>
                          <td><?php echo $row->phone_code." ".$row->phone;?></td>
                           <td><?php if($row->complete_pickup_address != "") echo $row->complete_pickup_address; else echo "NA";?></td>
                           <td><?php if($row->complete_destination_address != "") echo $row->complete_destination_address; else echo "NA";?></td>
                          <td><?php echo humanize($row->ride_status);?></td>

                          <td>
              							<a data-toggle="modal" data-target="#changeStatusModal"  onclick="changeStatusModal(<?php echo $row->passenger_id;?>, '<?php echo $row->shuttle_no;?>', <?php echo $row->travel_location_cost_id;?>, '<?php echo $row->pick_date;?>', <?php echo $row->booking_id;?>)"  class="btn btn-success act-btn" title="<?php if(!empty($this->phrases["update status"])) echo $this->phrases["update status"]; else echo "Update Status";?>"><i class="fa fa-edit"></i> </a>

              						</td>
                        </tr>

						<?php } } ?>

                  </tbody>
                    </table>
        </div>


      </div>
      </div>
    </div>
  </div>
</section>

<style>
  
  #ride_status {
    color: #000 !important;
  }

</style>



<!-- modal fade -->
<div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
   <?php echo form_open('driver/updatePassengerStatus', 'id="passenger_status_form"'); ?>
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php if(isset($this->phrases["close"])) echo $this->phrases["close"]; else echo "Close";?></span></button>
            <h4 class="modal-title" id="myModalLabel"><?php if(isset($this->phrases["update passenger status"])) echo $this->phrases["update passenger status"]; else echo "Update Passenger Status";?></h4>
         </div>
         <div class="modal-body">
            <?php
                    $status_opts = array(
                                            'picked_up'   => 'Picked up', 
                                            'dropped_off' => 'Dropped off',
                                        );
                    echo form_dropdown('ride_status', $status_opts, '', 'id="ride_status" ');

            ?>
            <input type="hidden" name="passenger_id" id="passenger_id" />
            <input type="hidden" name="shuttle_no" id="shuttle_no" />
            <input type="hidden" name="travel_location_cost_id" id="travel_location_cost_id" />
            <input type="hidden" name="pick_date" id="pick_date" />
            <input type="hidden" name="booking_id" id="booking_id" />
         </div>
         <div class="modal-footer">
            <button type="submit" class="btn btn-default modal-btn" ><?php if(isset($this->phrases["update"])) echo $this->phrases["update"]; else echo "Update";?></button>
            <a type="button" class="btn btn-default modal-btn" data-dismiss="modal"><?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>
         </div>
      </div>
      <?php echo form_close();?>
   </div>
</div>



<script>
/****** Delete Message ******/
   function changeStatusModal(passenger_id, shuttle_no, travel_location_cost_id, pick_date, booking_id){

     document.getElementById("passenger_id").value = passenger_id;
     document.getElementById("shuttle_no").value   = shuttle_no;
     document.getElementById("travel_location_cost_id").value   = travel_location_cost_id;
     document.getElementById("pick_date").value    = pick_date;
     document.getElementById("booking_id").value   = booking_id;

   }

</script>
