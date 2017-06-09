<section class="apps line">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="inner_con tb">
               

           <?php echo $this->session->flashdata('message');?>

           <div class="panel-heading lgn-hed"> 
		   <?php if(isset($this->phrases["booking history"])) echo $this->phrases["booking history"]; else echo "Booking History";?> 
		   </div>
		   <a class="fnt15 flt-rght" href="<?php echo base_url();?>booking/cancellationPolicy" target="_blank" ><?php if(isset($this->phrases["cancellation policy"])) echo $this->phrases["cancellation policy"]; else echo "Cancellation Policy";?></a>

          <div class="car_list db">
			<table id="example" class="cell-border example datatable" cellspacing="0" width="100%">
			   <thead>
				  <tr>
					 <th>#</th>
					 <th><?php if(isset($this->phrases["booking reference"])) echo $this->phrases["booking reference"]; else echo "Booking Reference";?></th>
					 <th><?php if(isset($this->phrases["journey date & time"])) echo $this->phrases["journey date & time"]; else echo "Journey Date & Time";?></th>
					 <th><?php if(isset($this->phrases["source"])) echo $this->phrases["source"]; else echo "Source";?></th>
					 <th><?php if(isset($this->phrases["destination"])) echo $this->phrases["destination"]; else echo "Destination";?></th>
					 <th><?php if(isset($this->phrases["cost of journey"])) echo $this->phrases["cost of journey"]; else echo "Cost of Journey";?></th>
					 <th><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
					 <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
				  </tr>
			   </thead>
			   <tfoot>
				  <tr>
					 <th>#</th>
					 <th><?php if(isset($this->phrases["booking reference"])) echo $this->phrases["booking reference"]; else echo "Booking Reference";?></th>
					 <th><?php if(isset($this->phrases["journey date & time"])) echo $this->phrases["journey date & time"]; else echo "Journey Date & Time";?></th>
					 <th><?php if(isset($this->phrases["source"])) echo $this->phrases["source"]; else echo "Source";?></th>
					 <th><?php if(isset($this->phrases["destination"])) echo $this->phrases["destination"]; else echo "Destination";?></th>
					 <th><?php if(isset($this->phrases["cost of journey"])) echo $this->phrases["cost of journey"]; else echo "Cost of Journey";?></th>
					 <th><?php if(isset($this->phrases["status"])) echo $this->phrases["status"]; else echo "Status";?></th>
					 <th><?php if(isset($this->phrases["action"])) echo $this->phrases["action"]; else echo "Action";?></th>
				  </tr>
			   </tfoot>
			   <tbody>
				  <?php 
					 $i=1;
					 foreach($booking_history as $row):?>
				  <tr>
					 <td><?php echo $i++; ?></td>
					 <td><?php echo $row->booking_ref;?></td>
					 <td><?php echo date('D, d M Y', strtotime($row->pick_date))."<br/>".$row->pick_time;?></td>
					 <td><?php echo $row->pick_point;?></td>
					 <td><?php echo $row->drop_point;?></td>
					 <td><?php echo $this->config->item('site_settings')->currency_symbol.$row->cost_of_journey;?></td>
					 <td>
						<?php
							$booking_status = $row->booking_status;

							$booking_status_lang = (isset($this->phrases[$booking_status])) ? $this->phrases[$booking_status] : $booking_status;

						if($booking_status=="Pending") { ?>
						<span class="label label-warning"><?php echo $booking_status_lang;?></span>
						<?php } else if($booking_status=="Confirmed") { ?>
						<span class="label label-success"><?php echo $booking_status_lang;?></span>
						<?php } else if($booking_status=="Cancelled") { ?>
						<span class="label label-danger"><?php echo $booking_status_lang;?></span>
						<?php }?>
					 </td>
					 <td>
						<a class="btn btn-info act-btn" href="<?php echo base_url();?>client/viewBookingDetails/<?php echo $row->id;?>" title="<?php if(isset($this->phrases["view booking details"])) echo $this->phrases["view booking details"]; else echo "View Booking Details";?>"><i class="fa fa-eye open"></i></a>

						<?php if($row->booking_status=="Pending" && (strtotime($row->pick_date) >= strtotime(date('Y-m-d'))) ) {

								/* Get Journey Time */
								$journey_time_rec = explode(':', str_replace(' ', '', $row->pick_time));

								/* Convert to 24 Hr Format */
								$journey_time_24hr_format = date('H:i', strtotime($journey_time_rec[0].":".$journey_time_rec[1]." ".$journey_time_rec[2]));
								
								/* Journey Time in 24 hour format fill */ 
								$journey_time 	  = $row->pick_date." ".$journey_time_24hr_format;

								/* Subtract 3 Hrs from journey time to check with the current time for booking cancellation */
								$journey_time_before_3hrs 	= date('Y-m-d H:i',strtotime('-3 hour',strtotime($journey_time)));

								/* Get Current Time */
								$cur_time = date('Y-m-d H:i');

								/* Enable Cancel Bookign option, before 3 hours(or greater than) to the journey time */
								if(strtotime($journey_time_before_3hrs) >= strtotime($cur_time)) {

							?>
							<a data-toggle="modal" data-target="#cancelModal"  onclick="cancelMessage(<?php echo $row->id;?>)" class="btn btn-warning act-btn" title="<?php if(isset($this->phrases["cancel booking"])) echo $this->phrases["cancel booking"]; else echo "Cancel Booking";?>"><i class="fa fa-close"></i> </a>
						<?php } } ?>
					 </td>
				  </tr>
				  <?php endforeach;?>
			   </tbody>
			</table>
		 <div class="clearfix"></div>
         </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php if(isset($this->phrases["close"])) echo $this->phrases["close"]; else echo "Close";?></span></button>
            <h4 class="modal-title" id="myModalLabel"><?php if(isset($this->phrases["cancel booking"])) echo $this->phrases["cancel booking"]; else echo "Cancel Booking";?></h4>
         </div>
         <div class="modal-body">
            <?php if(isset($this->phrases["are you sure to cancel the booking"])) echo $this->phrases["are you sure to cancel the booking"]; else echo "Are you sure to cancel the Booking";?>?
         </div>
         <div class="modal-footer">
            <a type="button" class="btn btn-default modal-btn" id="cnl_no" href=""><?php if(isset($this->phrases["yes"])) echo $this->phrases["yes"]; else echo "Yes";?></a>
            <a type="button" class="btn btn-default modal-btn" data-dismiss="modal"><?php if(isset($this->phrases["no"])) echo $this->phrases["no"]; else echo "No";?></a>
         </div>
      </div>
   </div>
</div>


<script>
/****** Cancel Message ******/
   function cancelMessage(x){

	var str = "<?php echo base_url();?>client/myBookings/Cancelled/"+x;
	document.getElementById("cnl_no").setAttribute("href", str);

   }

   
</script>
