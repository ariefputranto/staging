<section class="hil">
<div class="container">
<div class="row">
<div class="col-lg-10 col-lg-offset-1">
<div class="login other-form">
<ul>
<li>
 
 <div><?php echo $message;?></div>
<div class="form-feilds">
<?php
echo form_open('bookingseat/cancel_ticket', "id='print_ticket_form' name='print_ticket_form' class=''");
$value = $booking_ref;
if(isset($_POST['buttSearch']))
	$value = $this->input->post('booking_ref');
?>
<div class="form-group">
<label><?php echo getPhrase('Ticket Numer');?></label>
<input type="text" placeholder="<?php echo getPhrase('Enter Ticket Number');?>" name="booking_ref" id="booking_ref" value="<?php echo $value;?>"  required>
<span class="user"> <i class="flaticon-paper"></i> </span>
</div>

<div class="form-group">
<input type="submit" name="buttSearch" id="buttSearch" value="<?php echo getPhrase('Seacrh');?>" class="btn btn-default site-buttos">
</div>
</form>

<?php if(isset($ticket_details['status']) && $ticket_details['status'] == 1) { ?>

<div class="form-group clearfix">
	<?php echo '<h3>'.getPhrase('Ticket Details')."</h3>";
	echo '<strong>'.getPhrase('Pick-up Location') . ' :</strong> '.$ticket_details['details']->pick_point.'<br>';
	echo '<strong>'.getPhrase('Drop-off Location') . ' :</strong> '.$ticket_details['details']->drop_point.'<br>';
	echo '<strong>'.getPhrase('Journey Date') . ' :</strong> '.$ticket_details['details']->pick_date.'<br>';
	echo '<strong>'.getPhrase('Cost of journey') . ' :</strong> '.$ticket_details['details']->cost_of_journey.'<br>';
	echo '<strong>'.getPhrase('Seat') . ' :</strong> '.$ticket_details['details']->seat.'<br>';
	echo '<strong>'.getPhrase('Vehicle') . ' :</strong> '.$ticket_details['details']->name.' ('.$ticket_details['details']->number_plate.')';
	?>
	
</div>

<div class="form-group clearfix">
	<?php echo getPhrase('Please enter OTP you received on registered mobile number');?>
</div>
<?php
echo form_open('bookingseat/cancel_ticket', "id='print_ticket_form' name='print_ticket_form' class=''");
$value = $booking_ref;
if(isset($_POST['buttSearch']))
	$value = $this->input->post('booking_ref');
?>
<div class="form-group clearfix" id="email_address_div">
<input type="text" name="otp" id="opt" required>
</div>
<input type="hidden" name="booking_ref" value="<?php echo $value;?>">

<div class="form-group">
<input type="submit" name="buttCancel" id="buttCancel" value="<?php echo getPhrase('Go');?>" class="btn btn-default site-buttos">
</div>
</form>
<?php } ?>

</div>

 

<div class="clearfix"></div>
</li>
 
</ul>
</div>
</div>
</div>
</div>
</section>