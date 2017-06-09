  <div class="col-lg-10 col-md-10 col-sm-12 padding-lr">
    <div class="body-content">
      <div class="admin-body">
  <div class="inner-elements">
	<?php if(count($bookings) > 0) {

		 foreach($bookings as $row) {
	?> 



<div class="col-md-12">

<div class="bdet">
<h1> <?php if(isset($this->phrases["booking reference"])) echo $this->phrases["booking reference"]; else echo "Booking Reference";?> <?php echo $row->booking_ref;?></h1>
<div class="col-md-6">

<ul class="list-group">
<li class="list-group-item"><strong><?php if(isset($this->phrases["pick point"])) echo $this->phrases["pick point"]; else echo "Pick Point";?> </strong><?php echo $row->pick_point;?></li>
<li class="list-group-item"><strong><?php if(isset($this->phrases["drop point"])) echo $this->phrases["drop point"]; else echo "Drop Point";?> </strong><?php echo $row->drop_point;?></li>
<li class="list-group-item"><strong><?php if(isset($this->phrases["pick date"])) echo $this->phrases["pick date"]; else echo "Pick Date";?> </strong><?php echo $row->pick_date;?></li>
<li class="list-group-item"><strong><?php if(isset($this->phrases["pick time"])) echo $this->phrases["pick time"]; else echo "Pick Time";?> </strong><?php echo $row->pick_time;?></li>

<?php if($site_theme == 'vehicle') { ?>
<li class="list-group-item"><strong><?php if(isset($this->phrases["distance"])) echo $this->phrases["distance"]; else echo "Distance";?> </strong><?php echo $row->distance;?></li>
<li class="list-group-item"><strong><?php if(isset($this->phrases["journey time"])) echo $this->phrases["journey time"]; else echo "Journey Time";?> </strong><?php echo $row->total_time;?></li>
<?php } ?>

<li class="list-group-item"><strong><?php if(isset($this->phrases["journey type"])) echo $this->phrases["journey type"]; else echo "Journey Type";?> </strong><?php echo $row->journey_type;?></li>

	<?php 
	//print_r($row);
	if($row->journey_type == "Round-Trip") { ?>
	<li class="list-group-item"><strong><?php if(isset($this->phrases["return pick date"])) echo $this->phrases["return pick date"]; else echo "Return Pick Date";?> </strong><?php echo $row->return_pick_date;?></li>
	<li class="list-group-item"><strong><?php if(isset($this->phrases["return pick time"])) echo $this->phrases["return pick time"]; else echo "Return Pick Time";?> </strong><?php echo $row->return_pick_time;?></li>
	<?php } ?>

<?php if($site_theme == 'vehicle') { ?>
	<li class="list-group-item"><strong><?php if(isset($this->phrases["vehicle selected"])) echo $this->phrases["vehicle selected"]; else echo "Vehicle Selected";?> </strong><?php echo $row->car_name;?></li>
<?php } ?>
</ul>
</div>
<?php 
if($site_theme == 'vehicle')
{
?>
<div class="col-md-6">
<ul class="list-group">

<li class="list-group-item"><strong><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?> </strong><?php echo $row->registered_name;?></li>
<li class="list-group-item"><strong><?php if(isset($this->phrases["email"])) echo $this->phrases["email"]; else echo "Email";?> </strong><?php echo $row->email;?></li>
<li class="list-group-item"><strong><?php if(isset($this->phrases["phone"])) echo $this->phrases["phone"]; else echo "Phone";?> </strong><?php echo $row->phone;?></li>

	<?php if($row->additional_info != "") { ?>
<li class="list-group-item"><strong><?php if(isset($this->phrases["additional info"])) echo $this->phrases["additional info"]; else echo "Additional Info";?> </strong><?php echo $row->additional_info;?></li>
	<?php } ?>
<li class="list-group-item"><strong><?php if(isset($this->phrases["complete pick-up address"])) echo $this->phrases["complete pick-up address"]; else echo "Complete Pick-up Address";?> </strong><?php echo $row->complete_pickup_address;?></li>
<li class="list-group-item"><strong><?php if(isset($this->phrases["complete destination address"])) echo $this->phrases["complete destination address"]; else echo "Complete Destination Address";?> </strong><?php echo $row->complete_destination_address;?></li>
	
	<?php if($row->flight_num != "") { ?>
<li class="list-group-item"><strong><?php if(isset($this->phrases["flight number"])) echo $this->phrases["flight number"]; else echo "Flight Number";?> </strong><?php echo $row->flight_num;?></li>
	<?php } ?>
	<?php if($row->terminal_num != "") { ?>
<li class="list-group-item"><strong><?php if(isset($this->phrases["terminal number"])) echo $this->phrases["terminal number"]; else echo "Terminal Number";?> </strong><?php echo $row->terminal_num;?></li>
	<?php } ?>
	<?php if($row->arriving_from != "") { ?>
<li class="list-group-item"><strong><?php if(isset($this->phrases["arriving from"])) echo $this->phrases["arriving from"]; else echo "Arriving From";?> </strong><?php echo $row->arriving_from;?></li>
	<?php } ?>
	<?php if($row->flight_num != "") { ?>
<li class="list-group-item"><strong><?php if(isset($this->phrases["meet & greet"])) echo $this->phrases["meet & greet"]; else echo "Meet & Greet";?> </strong><?php echo $row->meet_greet;?></li>
		<?php if($row->meet_greet == "Yes") { ?>
	<li class="list-group-item"><strong><?php if(isset($this->phrases["cost for meet & greet"])) echo $this->phrases["cost for meet & greet"]; else echo "Cost for Meet & Greet";?> </strong><?php echo $site_settings->currency_symbol.$row->cost_for_meet_greet;?></li>
	<?php } } ?>


</ul>
</div>
<?php } ?>
<div class="col-md-6">
<ul class="list-group">
<li class="list-group-item"><strong><?php if(isset($this->phrases["cost of journey (incl. all)"])) echo $this->phrases["cost of journey (incl. all)"]; else echo "Cost of Journey (Incl. all)";?> </strong><?php echo $site_settings->currency_symbol.$row->cost_of_journey;?></li>

<?php if($row->discount_amount != '0.00' && $row->discount_amount != '') { ?>
<li class="list-group-item"><strong><?php if(isset($this->phrases["discount"])) echo $this->phrases["discount"]; else echo "Discount";?> </strong><?php echo $site_settings->currency_symbol.$row->discount_amount;?></li>
<?php } ?>

<li class="list-group-item"><strong><?php if(isset($this->phrases["payment type"])) echo $this->phrases["payment type"]; else echo "Payment Type";?> </strong><?php echo ucwords($row->payment_type);?></li>
<li class="list-group-item"><strong><?php if(isset($this->phrases["date of booking"])) echo $this->phrases["date of booking"]; else echo "Date of Booking";?> </strong><?php echo explode(',', timespan($row->bookdate, time()))[0];?> <?php if(isset($this->phrases["ago"])) echo $this->phrases["ago"]; else echo "ago";?></li>
<li class="list-group-item"><strong><?php if(isset($this->phrases["booking status"])) echo $this->phrases["booking status"]; else echo "Booking Status";?> </strong><?php echo $row->booking_status;?></li>
	<?php if($row->booking_status == "Cancelled") { ?>
	<li class="list-group-item"><strong><?php if(isset($this->phrases["cancelled"])) echo $this->phrases["cancelled"]; else echo "Cancelled";?> </strong><?php echo explode(',', timespan($row->cancelled_on, time()))[0];?> <?php if(isset($this->phrases["ago"])) echo $this->phrases["ago"]; else echo "ago";?></li>
	<?php } ?>

</ul>
</div>

<?php if($site_theme == 'seat') { 
if(!empty($passengers))
{
	foreach($passengers as $passenger)
	{
?>
<!--Passengers-->
<div class="col-md-6">
Passenger Details:
<ul class="list-group">
<li class="list-group-item"><strong><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?> </strong><?php echo $passenger->name;?></li>
<li class="list-group-item"><strong><?php if(isset($this->phrases["gender"])) echo $this->phrases["gender"]; else echo "Gender";?> </strong><?php echo ucwords($passenger->gender);?></li>
<li class="list-group-item"><strong><?php if(isset($this->phrases["type"])) echo $this->phrases["type"]; else echo "Type";?> </strong><?php echo $passenger->passenger_type;?></li>
<li class="list-group-item"><strong><?php if(isset($this->phrases["shuttle no"])) echo $this->phrases["shuttle no"]; else echo "Shuttle No.";?> </strong><?php echo $passenger->shuttle_no;?></li>	
<li class="list-group-item"><strong><?php if(isset($this->phrases["is waiting list"])) echo $this->phrases["is waiting list"]; else echo "Is Waiting List";?> </strong><?php echo $passenger->is_waiting_list;?></li>

<li class="list-group-item"><strong><?php if(isset($this->phrases["ride status"])) echo $this->phrases["ride status"]; else echo "Ride Status";?> </strong><?php echo $passenger->ride_status;?></li>
	

</ul>
</div>
	<?php } }
	
	if(!empty($passengers_infants))
{
	foreach($passengers_infants as $passenger)
	{
?>
<!--Passengers-->
<div class="col-md-6">
Infant Passenger Details:
<ul class="list-group">
<li class="list-group-item"><strong><?php if(isset($this->phrases["name"])) echo $this->phrases["name"]; else echo "Name";?> </strong><?php echo $passenger->infant_name;?></li>
<li class="list-group-item"><strong><?php if(isset($this->phrases["gender"])) echo $this->phrases["gender"]; else echo "Gender";?> </strong><?php echo ucwords($passenger->infant_gender);?></li>
<li class="list-group-item"><strong><?php if(isset($this->phrases["age"])) echo $this->phrases["age"]; else echo "Age";?> </strong><?php echo ucwords($passenger->infant_age);?></li>
</ul>
</div>
	<?php } }
	
	
	} ?>

<div class="col-md-6">

<a onclick="window.location.href = '<?php echo base_url().'executive/viewBookings/todayz';?>';" class="btn btn-info" title="Back"><i class="fa fa-arrow-left"></i> <?php if(isset($this->phrases["back"])) echo $this->phrases["back"]; else echo "Back";?></a>

<?php if($row->booking_status == "Pending") { ?>

	<a data-toggle="modal" data-target="#confirmModal"  onclick="confirmMessage(<?php echo $row->id;?>)" class="btn btn-success" title="Confirm"><i class="fa fa-check"></i> <?php if(isset($this->phrases["confirm"])) echo $this->phrases["confirm"]; else echo "Confirm";?></a>

	

<?php } ?>

<a data-toggle="modal" data-target="#cancelModal"  onclick="cancelMessage(<?php echo $row->id;?>)" class="btn btn-warning" title="Cancel"><i class="fa fa-close"></i> <?php if(isset($this->phrases["cancel"])) echo $this->phrases["cancel"]; else echo "Cancel";?></a>

<!--
<a data-toggle="modal" data-target="#delRecModal"  onclick="deleteMessage(<?php echo $row->id;?>)" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i> <?php if(isset($this->phrases["delete"])) echo $this->phrases["delete"]; else echo "Delete";?></a>
 -->
 </div>
</div>

</div>
<?php } } else { ?>

	<h3><?php if(isset($this->phrases["no details available"])) echo $this->phrases["no details available"]; else echo "No Details Available";?>.</h3><br/>
	<a onclick="window.location.href = '<?php echo base_url().'executive/viewBookings/todayz';?>';" class="btn btn-success" title="Back"><i class="fa fa-arrow-left"></i> <?php if(isset($this->phrases["back"])) echo $this->phrases["back"]; else echo "Back";?></a>
	
<?php } ?>

      </div>      

      </div>
      </div>
</div>


<!-- modal fade -->
<div class="modal fade" id="delRecModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php if(isset($this->phrases["close"])) echo $this->phrases["close"]; else echo "Close";?></span></button>
            <h4 class="modal-title" id="myModalLabel"><?php if(isset($this->phrases["delete booking"])) echo $this->phrases["delete booking"]; else echo "Delete Booking";?></h4>
         </div>
         <div class="modal-body">
            <?php if(isset($this->phrases["are you sure to delete the booking"])) echo $this->phrases["are you sure to delete the booking"]; else echo "Are you sure to delete the Booking";?>?
         </div>
         <div class="modal-footer">
            <a type="button" class="btn btn-default modal-btn" id="delete_no" href=""><?php if(isset($this->phrases["yes"])) echo $this->phrases["yes"]; else echo "Yes";?></a>
            <a type="button" class="btn btn-default modal-btn" data-dismiss="modal"><?php if(isset($this->phrases["no"])) echo $this->phrases["no"]; else echo "No";?></a>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php if(isset($this->phrases["close"])) echo $this->phrases["close"]; else echo "Close";?></span></button>
            <h4 class="modal-title" id="myModalLabel"><?php if(isset($this->phrases["confirm booking"])) echo $this->phrases["confirm booking"]; else echo "Confirm Booking";?></h4>
         </div>
         <div class="modal-body">
            <?php if(isset($this->phrases["are you sure to confirm the booking"])) echo $this->phrases["are you sure to confirm the booking"]; else echo "Are you sure to confirm the Booking";?>?
         </div>
         <div class="modal-footer">
            <a type="button" class="btn btn-default modal-btn" id="cnf_no" href=""><?php if(isset($this->phrases["yes"])) echo $this->phrases["yes"]; else echo "Yes";?></a>
            <a type="button" class="btn btn-default modal-btn" data-dismiss="modal"><?php if(isset($this->phrases["no"])) echo $this->phrases["no"]; else echo "No";?></a>
         </div>
      </div>
   </div>
</div>

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
/****** Delete Message ******/
   function deleteMessage(x){

	var str = "<?php echo base_url();?>executive/viewBookings/delete/"+x;
	document.getElementById("delete_no").setAttribute("href", str);

   }

/****** Confirm Message ******/
   function confirmMessage(x){

	var str = "<?php echo base_url();?>executive/viewBookings/Confirmed/"+x;
	document.getElementById("cnf_no").setAttribute("href", str);

   }

/****** Cancel Message ******/
   function cancelMessage(x){

	var str = "<?php echo base_url();?>executive/viewBookings/Cancelled/"+x;
	document.getElementById("cnl_no").setAttribute("href", str);

   }   


</script>
