<section class="apps line">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="inner_con">
                   <a href="<?php echo base_url();?>"> <div class="roundOne innround"><img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/caricon.png"/></div></a>

           <?php echo $this->session->flashdata('message');?>

           <div class="panel-heading lgn-hed"> <?php if(isset($this->phrases["booking details"])) echo $this->phrases["booking details"]; else echo "Booking Details";?> </div>

          <div class="car_list">



<div class="bdet">
 <h5 class="booking-ref br"> <strong><?php if(isset($this->phrases["booking reference"])) echo $this->phrases["booking reference"]; else echo "Booking Reference";?> </strong><?php if(isset($booking_details->booking_ref)) echo $booking_details->booking_ref;?></h5> 
<div class="col-md-6">
<ul class="applicant-det frnt">
	<li><strong><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?> </strong><?php if(isset($booking_details->registered_name)) echo $booking_details->registered_name;?></li>
	<li><strong><?php if(isset($this->phrases["email"])) echo $this->phrases["email"]; else echo "Email";?> </strong><?php if(isset($booking_details->email)) echo $booking_details->email;?></li>
	<li><strong><?php if(isset($this->phrases["phone"])) echo $this->phrases["phone"]; else echo "Phone";?> </strong><?php if(isset($booking_details->phone)) echo $booking_details->phone;?></li>
	<?php if(isset($booking_details->additional_info) && $booking_details->additional_info != "") { ?>
	<li><strong><?php if(isset($this->phrases["additional info"])) echo $this->phrases["additional info"]; else echo "Additional Info";?> </strong><?php echo $booking_details->additional_info;?></li>
	<?php } ?>
	<li><strong><?php if(isset($this->phrases["complete pick-up address"])) echo $this->phrases["complete pick-up address"]; else echo "Complete Pick-up Address";?> </strong><?php if(isset($booking_details->complete_pickup_address)) echo $booking_details->complete_pickup_address;?></li>
	<li><strong><?php if(isset($this->phrases["complete destination address"])) echo $this->phrases["complete destination address"]; else echo "Complete Destination Address";?> </strong><?php if(isset($booking_details->complete_destination_address)) echo $booking_details->complete_destination_address;?></li>
	
	<?php if(isset($booking_details->flight_num) && $booking_details->flight_num != "") { ?>
	<li><strong><?php if(isset($this->phrases["flight number"])) echo $this->phrases["flight number"]; else echo "Flight Number";?> </strong><?php echo $booking_details->flight_num;?></li>
	<?php } ?>
	<?php if(isset($booking_details->terminal_num) && $booking_details->terminal_num != "") { ?>
	<li><strong><?php if(isset($this->phrases["terminal number"])) echo $this->phrases["terminal number"]; else echo "Terminal Number";?> </strong><?php echo $booking_details->terminal_num;?></li>
	<?php } ?>
	<?php if(isset($booking_details->arriving_from) && $booking_details->arriving_from != "") { ?>
	<li><strong><?php if(isset($this->phrases["arriving from"])) echo $this->phrases["arriving from"]; else echo "Arriving From";?> </strong><?php echo $booking_details->arriving_from;?></li>
	<?php } ?>
	<?php if(isset($booking_details->flight_num) && $booking_details->flight_num != "") { ?>
	<li><strong><?php if(isset($this->phrases["meet & greet"])) echo $this->phrases["meet & greet"]; else echo "Meet & Greet";?> </strong><?php if(isset($booking_details->meet_greet)) echo $booking_details->meet_greet;?></li>
		<?php if(isset($booking_details->meet_greet) && $booking_details->meet_greet == "Yes") { ?>
		<li><strong><?php if(isset($this->phrases["cost for meet & greet"])) echo $this->phrases["cost for meet & greet"]; else echo "Cost for Meet & Greet";?> </strong><?php if(isset($booking_details->cost_for_meet_greet)) echo $site_settings->currency_symbol.$booking_details->cost_for_meet_greet;?></li>
	<?php } } ?>
		<li><strong><?php if(isset($this->phrases["vehicle selected"])) echo $this->phrases["vehicle selected"]; else echo "Vehicle Selected";?> </strong><?php if(isset($booking_details->car_name)) echo $booking_details->car_name;?></li>

	
</ul>
</div>
<div class="col-md-6">
<ul class="applicant-det frnt">
	<li><strong><?php if(isset($this->phrases["pick point"])) echo $this->phrases["pick point"]; else echo "Pick Point";?> </strong><?php if(isset($booking_details->pick_point)) echo $booking_details->pick_point;?></li>
	<li><strong><?php if(isset($this->phrases["drop point"])) echo $this->phrases["drop point"]; else echo "Drop Point";?> </strong><?php if(isset($booking_details->drop_point)) echo $booking_details->drop_point;?></li>
	<li><strong><?php if(isset($this->phrases["pick date"])) echo $this->phrases["pick date"]; else echo "Pick Date";?> </strong><?php if(isset($booking_details->pick_date)) echo $booking_details->pick_date;?></li>
	<li><strong><?php if(isset($this->phrases["pick time"])) echo $this->phrases["pick time"]; else echo "Pick Time";?> </strong><?php if(isset($booking_details->pick_time)) echo $booking_details->pick_time;?></li>
	<li><strong><?php if(isset($this->phrases["distance"])) echo $this->phrases["distance"]; else echo "Distance";?> </strong><?php if(isset($booking_details->distance)) echo $booking_details->distance;?></li>
	<li><strong><?php if(isset($this->phrases["journey time"])) echo $this->phrases["journey time"]; else echo "Journey Time";?> </strong><?php if(isset($booking_details->total_time)) echo $booking_details->total_time;?></li>
	<li><strong><?php if(isset($this->phrases["journey type"])) echo $this->phrases["journey type"]; else echo "Journey Type";?> </strong><?php if(isset($booking_details->journey_type)) echo $booking_details->journey_type;?></li>

	<?php if(isset($booking_details->journey_type) && $booking_details->journey_type == "Round-Trip") { ?>
		<li><strong><?php if(isset($this->phrases["return pick date"])) echo $this->phrases["return pick date"]; else echo "Return Pick Date";?> </strong><?php if(isset($booking_details->return_pick_date)) echo $booking_details->return_pick_date;?></li>
		<li><strong><?php if(isset($this->phrases["return pick time"])) echo $this->phrases["return pick time"]; else echo "Return Pick Time";?> </strong><?php if(isset($booking_details->return_pick_time)) echo $booking_details->return_pick_time;?></li>
	<?php } ?>

</ul>
</div>
 
<div class="col-md-6">
<ul class="applicant-det frnt">
 
	<li><strong><?php if(isset($this->phrases["cost of journey (incl. all)"])) echo $this->phrases["cost of journey (incl. all)"]; else echo "Cost of Journey (Incl. all)";?> </strong><?php if(isset($booking_details->cost_of_journey)) echo $site_settings->currency_symbol.$booking_details->cost_of_journey;?></li>
	<li><strong><?php if(isset($this->phrases["payment type"])) echo $this->phrases["payment type"]; else echo "Payment Type";?> </strong><?php if(isset($booking_details->payment_type)) echo ucwords($booking_details->payment_type);?></li>
	<li><strong><?php if(isset($this->phrases["date of booking"])) echo $this->phrases["date of booking"]; else echo "Date of Booking";?> </strong><?php if(isset($booking_details->bookdate)) echo explode(',', timespan($booking_details->bookdate, time()))[0];?> <?php if(isset($this->phrases["ago"])) echo $this->phrases["ago"]; else echo "ago";?></li>
	<li><strong><?php if(isset($this->phrases["booking status"])) echo $this->phrases["booking status"]; else echo "Booking Status";?> </strong><?php if(isset($booking_details->booking_status)) echo $booking_details->booking_status;?></li>
	<?php if(isset($booking_details->booking_status) && $booking_details->booking_status == "Cancelled") { ?>
		<li><strong><?php if(isset($this->phrases["cancelled"])) echo $this->phrases["cancelled"]; else echo "Cancelled";?> </strong><?php if(isset($booking_details->cancelled_on)) echo explode(',', timespan($booking_details->cancelled_on, time()))[0];?> <?php if(isset($this->phrases["ago"])) echo $this->phrases["ago"]; else echo "ago";?></li>
	<?php } ?>

</ul>
</div>



 
<div class="col-md-12">

<a href="<?php echo base_url();?>client/myBookings" class="btn btn-info book" title="Back"><i class="fa fa-arrow-left"></i> <?php if(isset($this->phrases["back"])) echo $this->phrases["back"]; else echo "Back";?></a>

<?php if($booking_details->booking_status=="Pending" && (strtotime($booking_details->pick_date) >= strtotime(date('Y-m-d'))) ) {

		/* Get Journey Time */
		$journey_time_rec = explode(':', str_replace(' ', '', $booking_details->pick_time));

		/* Convert to 24 Hr Format */
		$journey_time_24hr_format = date('H:i', strtotime($journey_time_rec[0].":".$journey_time_rec[1]." ".$journey_time_rec[2]));
		
		/* Journey Time in 24 hour format fill */ 
		$journey_time 	  = $booking_details->pick_date." ".$journey_time_24hr_format;

		/* Subtract 3 Hrs from journey time to check with the current time for booking cancellation */
		$journey_time_before_3hrs 	= date('Y-m-d H:i',strtotime('-3 hour',strtotime($journey_time)));

		/* Get Current Time */
		$cur_time = date('Y-m-d H:i');

		/* Enable Cancel Bookign option, before 3 hours(or greater than) to the journey time */
		if(strtotime($journey_time_before_3hrs) >= strtotime($cur_time)) {

	?>
	<a data-toggle="modal" data-target="#cancelModal"  onclick="cancelMessage(<?php echo $booking_details->id;?>)" class="btn btn-warning book" title="Cancel Booking"><i class="fa fa-close"></i> <?php if(isset($this->phrases["cancel booking"])) echo $this->phrases["cancel booking"]; else echo "Cancel Booking";?></a>
<?php } } ?>

 <a class="can" href="<?php echo base_url();?>booking/cancellationPolicy" target="_blank" ><?php if(isset($this->phrases["cancellation policy"])) echo $this->phrases["cancellation policy"]; else echo "Cancellation Policy";?></a>

</div>
 

 </div>
 


 
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

