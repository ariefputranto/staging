<?php
$seats = array_values($details[$journey_type]['seats']);
//$token = $details[$journey_type]['token'];
//neatPrint($details);
?>

<div><?php echo $message;?></div>
<?php
$adults = $child = 0;
$selection_details = $details[$journey_type]['selection_details'];
$attributes = "id='payment_form' name='payment_form' class=''";
echo form_open('bookingseat/passenger_details', $attributes);
?>
<div class="container">
  <div class="row">
    <div class="col-lg-9">
      <div class="booked-users-list">
	  
	  <table width="200" border="1" class="ps-de">
          <tbody>
            <tr class="ud">
			<td><?php echo getPhrase('Name');?> <span class="red">*</span></td>
			
			<td><?php echo getPhrase('Gender');?> <span class="red">*</span></td>
			<td><?php echo getPhrase('Type');?></td>
			<td><?php echo getPhrase('Age');?> <span class="red">*</span></td>
			<td><?php echo getPhrase('Seat');?></td>
			</tr>
			<?php			
			//neatPrint($selection_details);
			$required_seats = $details[$journey_type]['adult']+$details[$journey_type]['child'];
			$required_adult = $details[$journey_type]['adult'];
			$required_child = $details[$journey_type]['child'];
			if((count($selection_details) > 0) && $required_seats > 0)
			{
				$i = $k = $adults = $child = 0;
				for($p = 0; $p < $required_seats; $p++)
				{
					$i+=2;
					$k++;
					$gender = set_value('passenger_gender[]');
					$seat_type = $seat_type_display = '';
					if($adults < $required_adult)
					{
						$adults++;
						$seat_type = 'adult';
						$seat_type_display = getPhrase('Adult');
					}
					else
					{
						$seat_type = 'child';
						$seat_type_display = getPhrase('Child');
					}
					?>
					<tr>
					  <td>
						<input type="text" class="user-f" name="passenger_name[]" value="<?php echo set_value('passenger_name[]');?>"></td>              
					  <td><span for="passenger_<?php echo ($i+1)?>">
						<input id="passenger_<?php echo ($i+1)?>" type="radio" name="passenger_gender[<?php echo $k;?>]" value="Male"<?php if($gender == 'Male') echo ' checked';?>>
						<label for="passenger_<?php echo ($i+1)?>" class="side-label"><?php echo getPhrase('M');?></label>
						</span> <span for="passenger_<?php echo ($i+2)?>">
						<input id="passenger_<?php echo ($i+2)?>" type="radio" name="passenger_gender[<?php echo $k;?>]" value="Female"<?php if($gender == 'Female') echo ' checked';?>>
						<label for="passenger_<?php echo ($i+2)?>" class="side-label"> <?php echo getPhrase('F');?></label>
						</span></td>
						
					<td>
						<?php
						echo $seat_type_display;
						?>
						<input type="hidden" class="passenger_type" name="passenger_type[<?php echo $k;?>]" value="<?php echo $seat_type;?>">
					</td>
					<td>
						<input type="text" class="age" name="passenger_age[<?php echo $k;?>]"  value="<?php echo set_value('passenger_age[]');?>">
					</td>
					<td>
					<?php
					$passenger_seat = $passenger_seat_no = $passenger_price_set = $passenger_shuttle_no = '';
					$shuttles = array();					
					foreach($selection_details as $details_inner)
					{
						if(!in_array($details_inner->shuttle_no, $shuttles))
						{
							$shuttles[] = $details_inner->shuttle_no;
						}
					}
					if(!empty($shuttles))
					{
						for($s = 0; $s < count($shuttles); $s++)
						{
							$shuttles_inner = array();
							foreach($selection_details as $details_inner)
							{
								if($details_inner->shuttle_no == $shuttles[$s] && !in_array($details_inner->shuttle_no, $shuttles_inner))
								{
									$shuttles_inner[] = $details_inner->shuttle_no;
									$passenger_seat .= ','.$details_inner->seat;
									$passenger_seat_no .= ','.$details_inner->seat_display;
									$passenger_price_set .= ','.$details_inner->price_set;
									$passenger_shuttle_no .= ','.$details_inner->shuttle_no;
								}
							}
						}
					}
					if($passenger_seat != '')
						echo substr($passenger_seat_no, 1);
					?>
						<input type="hidden" name="passenger_seat[<?php echo $k;?>]" value="">
						<input type="hidden" name="passenger_seat_no[<?php echo $k;?>]" value="">
						<input type="hidden" name="passenger_price_set[<?php echo $k;?>]" value="">
						<input type="hidden" name="passenger_shuttle_no[<?php echo $k;?>]" value="">
					</td>
						<td></td>
					</tr>
					<?php
				}
			
			/*
			foreach($selection_details as $key => $rec) 
			{
				if($i == 0)
				{
					$i++;
					continue;
				}			
			?>
			<tr>
              <td>
                <input type="text" class="user-f" name="passenger_name[]" value="<?php echo set_value('passenger_name[]');?>"></td>              
              <td><span for="passenger_<?php echo ($i+1)?>">
                <input id="passenger_<?php echo ($i+1)?>" type="radio" name="passenger_gender[<?php echo $k;?>]" value="Male">
                <label for="passenger_<?php echo ($i+1)?>" class="side-label"><?php echo getPhrase('M');?></label>
                </span> <span for="passenger_<?php echo ($i+2)?>">
                <input id="passenger_<?php echo ($i+2)?>" type="radio" name="passenger_gender[<?php echo $k;?>]" value="Female">
                <label for="passenger_<?php echo ($i+2)?>" class="side-label"> <?php echo getPhrase('F');?></label>
                </span></td>
				
			<td><?php
				$seat_type = 'adult';
				if($rec->seat_type == 'a') 
				{ 
				echo getPhrase('Adult'); 
				$seat_type = 'adult';
				}
				elseif($rec->seat_type == 'c')
				{
				echo getPhrase('Child');
				$seat_type = 'child';				
				}
			?>
                <input type="hidden" class="passenger_type" name="passenger_type[]" value="<?php echo $seat_type;?>"></td>
            <td>
                <input type="text" class="age" name="passenger_age[]"  value="<?php echo set_value('passenger_age[]');?>"></td>
				<td>
				<?php 
				$shuttles_inner = array();
				$seat = $seat_no = $shuttle = '';
				$c = 0;
				foreach($selection_details as $key_in => $rec_in) {
				if($c == 0) { $c++; continue;}
				if(!in_array($rec_in->shuttle_no, $shuttles_inner)) {
					$shuttles_inner[] = $rec_in->shuttle_no;
					$seat .= $rec_in->seat.', ';
					$seat_no .= $rec_in->seat_display.', ';
					$shuttle .= $rec_in->seat.'_'.$rec_in->shuttle_no.'_'.$rec_in->seat_display.', ';
				echo $rec->seat_display;?> (<?php echo $rec->shuttle_no;?>)<br>
				<?php }} ?>
				<input type="hidden" name="passenger_seat[]" value="<?php echo $rec->seat;?>">
				<input type="hidden" name="passenger_seat_no[]" value="<?php echo $rec->seat_display;?>">
				<input type="hidden" name="passenger_price_set[]" value="<?php echo $rec->price_set;?>">
				<input type="hidden" name="passenger_shuttle_no[]" value="<?php echo $shuttle;?>">
				</td>
				<td></td>
            </tr>
			<?php 
			$i+=2;
			$k++;
			}
*/			
			}
?>
				
          </tbody>
        </table>
		<table width="100%">
			<tr><td>Phone <span class="red">*</span></td><td><input type="text" class="user-f c-cod" placeholder="code" name="primary_passenger_phone_code" value="<?php echo (isset(getUserRec()->phone_code)) ? getUserRec()->phone_code : '';?>" maxlength="3">
				
				<input type="text" class="user-f" placeholder="Please enter mobile number" name="primary_passenger_phone" value="<?php echo (isset(getUserRec()->phone)) ? getUserRec()->phone : '';?>" maxlength="10">
				</td><td>Email <span class="red">*</span></td><td><input type="text" class="user-f" placeholder="Please enter country code" name="primary_passenger_email" value="<?php echo (isset(getUserRec()->email)) ? getUserRec()->email : '';?>"></td></tr>
			
			<tr><td>Address <span class="red">*</span></td><td>
			<textarea class="user-area" name="primary_passenger_cpa" placeholder="enter your complete pickup and drop off address here"><?php echo set_value('primary_passenger_cda');?></textarea>
			</td><td></td><td></td></tr>
			</table>
	</div>
      
      <div class="payment">	  
      <div class="panel panel-default">
  <div class="panel-heading"><?php echo getPhrase('Payment Details');?></div>
  <div class="panel-body">
  
<?php
foreach($gateways as $key => $gateway) { 
if($gateway->gateway_title == 'Cash')
{
	if($this->ion_auth->is_executive() || $this->ion_auth->is_admin())
	{
	?>
	<div class="pay">
	<input id="payment_type_<?php echo $gateway->gateway_id;?>" name="payment_type" type="radio" value="<?php echo $gateway->gateway_id;?>">
	<label for="payment_type_<?php echo $gateway->gateway_id;?>" class="side-label"><?php echo (isset($gateway->display_title) && $gateway->display_title!= '') ? $gateway->display_title: $gateway->gateway_title;?></label>
	</div>
	<?php
	}
} else {
	$onclick = $str = '';
	if($gateway->gateway_title == 'Finpayapi')
	{
		$instructions = $this->base_model->fetch_records_from('gateways_fields_values', array('gateway_id' => $gateway->gateway_id, 'gateway_field_id' => 35));
		if(!empty($instructions))
		{
			$onclick = ' onclick="show_instructions('.$gateway->gateway_id.')"';
			$str = $instructions[0]->gateway_field_value;
		}
		else
		{
			$onclick = ' onclick="hide_instructions()"';
		}	
	}
	else
	{
		$onclick = ' onclick="hide_instructions()"';
	}
?>
	<div class="pay">
	<input id="payment_type_<?php echo $gateway->gateway_id;?>" name="payment_type" type="radio" value="<?php echo $gateway->gateway_id;?>"<?php echo $onclick;?>>
	<label for="payment_type_<?php echo $gateway->gateway_id;?>" class="side-label"><?php echo (isset($gateway->display_title) && $gateway->display_title!= '') ? $gateway->display_title: $gateway->gateway_title;?></label>
	</div>
	<span id="instructions_<?php echo $gateway->gateway_id;?>" class="instructions" style="display:none;"><?php echo $str;?></span>
<?php } } ?>
<script>
function show_instructions(gateway_id)
{
	var elements = document.getElementsByClassName('instructions');
	for (var i = 0; i < elements.length; i++){
        elements[i].style.display = 'none';
    }
	document.getElementById('instructions_'+gateway_id).style.display = 'block';
}
function hide_instructions()
{
	var elements = document.getElementsByClassName('instructions');
	for (var i = 0; i < elements.length; i++){
        elements[i].style.display = 'none';
    }
}
</script>

<?php if(isset($details['return']) && isset($details['is_return']) && $details['is_return'] == 'yes') { ?>
<div id="coupon_view">
<?php $this->load->view('seat/site/booking/coupon_view');?>
</div>
<?php } else if(!isset($details['return'])) {
	?>
<div id="coupon_view">
<?php $this->load->view('seat/site/booking/coupon_view');?>
</div>	
	<?php
}?>
<?php if(isset($details['return']) && (isset($details['is_return']) && $details['is_return'] != 'yes')) { ?>
	<input type="submit" name="buttsubmit" value="<?php echo getPhrase('Book Return');?>" class="btn btn-default">
	<?php
}?>
<input type="submit" name="buttsubmit" value="<?php echo getPhrase('Process Anyway');?>" class="btn btn-default">

  </div>
 
</div>
      </div>
      
    </div>
    <div class="col-lg-3">
	
	<div class="timer">
	<h4>Time Left : <span id="mins"></span> : <span id="seconds"></span></h4>
	</div>
	
    <div class="panel panel-default onps">
  <div class="panel-heading"><?php echo getPhrase('Onward Journey');?></div>
  <div class="panel-body">
 <strong><?php echo $details['onward']['pick_point_view'];?></strong> <?php echo getPhrase('to');?> <strong><?php echo $details['onward']['drop_point_view'];?></strong><br>
 <?php echo date('D, M d, Y', strtotime($details['onward']['pick_date']));?>
<br>

<?php echo getPhrase('Seat no(s)');?> : <?php echo (isset($details['onward']['selected_seats_no'])) ? $details['onward']['selected_seats_no'] : '-'?><br><br>
<?php $selection_details = (isset($details['onward']['selection_details'])) ? $details['onward']['selection_details'] : array();
//neatPrint($details);
if(!empty($selection_details))
{
	$shuttle_nos = array();
	foreach($selection_details as $key => $val)
	{
		if(!in_array($val->shuttle_no, $shuttle_nos))
		{
			$shuttle_nos[] = $val->shuttle_no;
		}
	}
	$cc = 0;
	$shuttles = array();
	foreach($selection_details as $key => $val)
	{
		$parts = explode('_', $key);
		if(!in_array($val->shuttle_no, $shuttles))
		{
		$shuttles[] = $val->shuttle_no;
		echo $val->name . ' ('.$val->category.') - ';
		if(count($shuttle_nos) > 1)
		{
		foreach($selection_details as $key_in => $val_in)
		{
			$pp = explode('_', $key_in);
			if($pp[0] == $val->shuttle_no)
			echo $val_in->seat.',';
		}
		}
		echo ' ('.$val->shuttle_no.')';

		echo '<br>Pick Time : '.date('D, M d, Y', strtotime($val->pick_date)).' '.$val->start_time;

		echo '<br>'.$val->pick_point_name .' <b>TO</b> '.$val->drop_point_name;
		echo '<br>----------------------------------------';
		$cc++;
		if($cc != count($selection_details))
			echo '<br>';
		}
	}
}
?>
<?php /* echo (isset($details['onward']['vehicle_details'])) ? $details['onward']['vehicle_details']->name : '-'?><br>
<?php echo (isset($details['onward']['vehicle_details'])) ? $details['onward']['vehicle_details']->category : '-';*/?>

<?php 
$onward_insurance_check = '';
if(isset($details['onward']['insurance']) && $details['onward']['insurance'] > 0) $onward_insurance_check = ' checked'; ?>
<br><input type="checkbox" name="onward_insurance" value="yes" onclick="calculate_insurace_fee(this)" <?php echo $onward_insurance_check;?>> <?php echo getPhrase('Insurance')?>
  </div>
</div>

<?php 
//neatPrint($details);
if(isset($details['is_return']) && $details['is_return'] == 'yes') { ?>
<div class="panel panel-default onps">
  <div class="panel-heading"><?php echo getPhrase('Return Journey');?></div>
  <div class="panel-body">
 <strong><?php echo $details['return']['pick_point_view'];?></strong> <?php echo getPhrase('to');?> <strong><?php echo $details['return']['drop_point_view'];?></strong><br>
 <?php echo date('D, M d, Y', strtotime($details['return']['pick_date']));?>
<br>

<?php echo getPhrase('Seat no(s)');?> : <?php echo (isset($details['return']['selected_seats_no'])) ? $details['return']['selected_seats_no'] : '-'?><br><br>

<?php $selection_details = (isset($details['return']['selection_details'])) ? $details['return']['selection_details'] : array();
if(!empty($selection_details))
{
	$shuttle_nos = array();
	foreach($selection_details as $key => $val)
	{
		if(!in_array($val->shuttle_no, $shuttle_nos))
		{
			$shuttle_nos[] = $val->shuttle_no;
		}
	}
	$cc = 0;
	$shuttles = array();
	foreach($selection_details as $key => $val)
	{
		$parts = explode('_', $key);
		//if(!in_array($val->shuttle_no, $shuttles))
		{
		$shuttles[] = $val->shuttle_no;
		//echo $val->name . ' ('.$val->category.') - '.$val->seat . ' ('.$val->shuttle_no.')';
		echo $val->name . ' ('.$val->category.') - ';
		if(count($shuttle_nos) > 1)
		{
		foreach($selection_details as $key_in => $val_in)
		{
			$pp = explode('_', $key_in);
			if($pp[0] == $val->shuttle_no)
			echo $val_in->seat.',';
		}
		}
		echo ' ('.$val->shuttle_no.')';
		if($cc > 0) //If they are connection shuttle
		{
			if($val->elapsed_days == 0)
			{
			echo '<br>Pick Time : '.date('D, M d, Y', strtotime($details['return']['pick_date'])).' '.$val->start_time;
			}
			else
			{
				if($val->elapsed_days != '')
				{
					$elapsed_days = $val->elapsed_days;
					echo '<br>Pick Time : '.date('D, M d, Y', strtotime("+$elapsed_days day", strtotime($details['return']['pick_date']))).' '.$val->start_time;
				}
				else
				{
				echo '<br>Pick Time : '.date('D, M d, Y', strtotime($details['return']['pick_date'])).' '.$val->start_time;	
				}
			}
		}
		else
		{
			echo '<br>Pick Time : '.date('D, M d, Y', strtotime($details['return']['pick_date'])).' '.$val->start_time;
		}
		echo '<br>'.$val->pick_point_name .' <b>TO</b> '.$val->drop_point_name;
		echo '<br>----------------------------------------';
		$cc++;
		if($cc != count($selection_details))
			echo '<br>';
		}
	}
}
?>

<?php 
$return_insurance_check = '';
if(isset($details['onward']['insurance']) && $details['onward']['insurance'] > 0) $return_insurance_check = ' checked'; ?>
<br><input type="checkbox" name="return_insurance" value="yes" onclick="calculate_insurace_fee(this)" <?php echo $return_insurance_check;?>><?php echo getPhrase('Insurance')?>

  </div>
</div>
<?php } ?>

<div class="panel panel-default onps">
  <!-- Default panel contents -->
  <div class="panel-heading"><?php echo getPhrase('Payment Summary');?></div>
  <!-- List group -->
  <ul class="list-group">
  <?php $selected_seats_total = 0;
  $selected_seats_total_onward = (isset($details['onward']['selected_seats_total'])) ? $details['onward']['selected_seats_total'] : 0;
  $selected_seats_total_return = (isset($details['return']['selected_seats_total'])) ? $details['return']['selected_seats_total'] : 0;
  $selected_seats_total = $selected_seats_total_onward + $selected_seats_total_return;
  
  $basic_fare = 0;
  $basic_fare_onward = (isset($details['onward']['basic_fare'])) ? $details['onward']['basic_fare'] : 0;
  $basic_fare_return = (isset($details['return']['basic_fare'])) ? $details['return']['basic_fare'] : 0;
  $basic_fare = $basic_fare_onward + $basic_fare_return;
  $basic_fare = number_format($basic_fare, 2);
  
  $service_charge = 0;
  $service_charge_onward = (isset($details['onward']['service_charge'])) ? $details['onward']['service_charge'] : 0;
  $service_charge_return = (isset($details['return']['service_charge'])) ? $details['return']['service_charge'] : 0;
  $service_charge = $service_charge_onward + $service_charge_return;
  $service_charge = number_format($service_charge, 2);
  
  $total_fare = 0;
  $total_fare_onward = (isset($details['onward']['total_fare'])) ? $details['onward']['total_fare'] : 0;
  $total_fare_return = (isset($details['return']['total_fare'])) ? $details['return']['total_fare'] : 0;
  $total_fare = $total_fare_onward + $total_fare_return;
  
  $insurance = 0;
  $insurance_onward = (isset($details['onward']['insurance'])) ? $details['onward']['insurance'] : 0;
  $insurance_return = (isset($details['return']['insurance'])) ? $details['return']['insurance'] : 0;
  $insurance = $insurance_onward + $insurance_return;
  $total_fare = $total_fare + $insurance;
  $total_fare = number_format($total_fare, 2);
  ?>
    <li class="list-group-item"><?php echo getPhrase('Seats');?> <span><?php echo $selected_seats_total;?></span></li>
    <li class="list-group-item"><?php echo getPhrase('Basic Fare');?> 	<span><?php echo $site_settings->currency_symbol; echo $basic_fare;?></span></li>
    <li class="list-group-item"><?php echo getPhrase('Service Charge');?> <span><?php echo $site_settings->currency_symbol; echo $service_charge;?></span> </li>
	<?php if($insurance > 0) {
		?>
		<li class="list-group-item"><?php echo getPhrase('Insurance');?> <span><?php echo $site_settings->currency_symbol; echo $insurance;?></span> </li>
		<?php
	}?>
    <li class="list-group-item"><strong><?php echo getPhrase('Total Amount');?></strong> <strong><span id="total_fare"><?php echo $site_settings->currency_symbol; echo $total_fare?></span></strong></li>
   </ul>
</div>
     </div>
  </div>
</div>

<input type="hidden" name="minutes" id="mins1">
<input type="hidden" name="seconds" id="seconds1">
</form>

<script>
	
	function calculate_insurace_fee(obj)
	{
		$.ajax({
		  type: "post",
		  url: "<?php echo base_url();?>bookingseat/calculate_insurace_fee",
		  async: false,
		  data: {
					<?php echo $this->security->get_csrf_token_name();?>:
					"<?php echo $this->security->get_csrf_hash();?>",
					status:$(obj).is(':checked')
				},
		  cache: false, 
		  success: function(data) {
			var parsed_data = $.parseJSON(data);
			$('#total_fare').html(parsed_data['total_fare']);
		  },
		  error: function(){
			alert('Ajax Error');
		  }
		});
	}
	
	
</script>