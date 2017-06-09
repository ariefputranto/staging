<?php
$seats = array_values($details[$journey_type]['seats']);
//$token = $details[$journey_type]['token'];
//neatPrint($details);
?>
<!--
<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <div class="inner-hed">
        <h3><?php echo getPhrase('Payment');?> </h3>
        <p> <a href="<?php echo site_url();?>"> <?php echo getPhrase('Home');?> </a> &nbsp; <i class="fa fa-angle-right"></i> &nbsp; <?php echo getPhrase('Payment Options');?> </p>
      </div>
    </div>
  </div>
</div>
-->
<div><?php echo $message;?></div>
<?php
$adults = $child = 0;
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
			<!--<td><?php echo getPhrase('Phone');?></td>
			<td><?php echo getPhrase('Email');?></td>
			<td><?php echo getPhrase('Complete Pick-up Address');?></td>
			<td><?php echo getPhrase('Complete Drop-off Address');?></td>-->
			<td><?php echo getPhrase('Gender');?> <span class="red">*</span></td>
			<td><?php echo getPhrase('Type');?></td>
			<td><?php echo getPhrase('Age');?> <span class="red">*</span></td>
			<td><?php echo getPhrase('Seat');?></td>
			</tr>
			<?php 
			$i = 0;
			foreach($details[$journey_type]['selection_details'] as $key => $rec) 
			{
				if($i > 0) break;
				?>
			<tr>
				<td>
				<input type="text" class="user-f" name="primary_passenger_name" id="primary_passenger_name" value="<?php echo set_value('username', (isset(getUserRec()->username)) ? getUserRec()->username : '');?>"></td>
				
				
				<!--<td colspan="1"> 
				<input type="text" class="user-f c-cod" placeholder="Please enter country code" name="primary_passenger_phone_code" value="<?php echo (isset(getUserRec()->phone_code)) ? getUserRec()->phone_code : '';?>" maxlength="3">
				
				<input type="text" class="user-f" placeholder="Please enter mobile number" name="primary_passenger_phone" value="<?php echo (isset(getUserRec()->phone)) ? getUserRec()->phone : '';?>" maxlength="10">
				<input type="hidden" name="primary_passenger_price_set" value="<?php echo$rec->price_set;?>">
				</td>
				
				
				<td colspan="1"> 
				<input type="text" class="user-f" placeholder="Please enter country code" name="primary_passenger_email" value="<?php echo (isset(getUserRec()->email)) ? getUserRec()->email : '';?>">
				</td>

				<td colspan="1"> 
				<input type="text" class="user-f" name="primary_passenger_cpa" value="<?php echo (isset(getUserRec()->complete_pickup_address)) ? getUserRec()->complete_pickup_address : '';?>">
				</td>

				<td colspan="1"> 
				<input type="text" class="user-f" name="primary_passenger_cda" value="<?php echo (isset(getUserRec()->complete_destiantion_address)) ? getUserRec()->complete_destiantion_address : '';?>">
				</td>-->
				
				<?php
				$male_checked = ' checked';
				$female_checked = '';
				if((isset(getUserRec()->gender)))
				{
					if(getUserRec()->gender == 'Female')
					{
						$male_checked = '';
						$female_checked = ' checked';
					}

				}
				?>
              <td><span>
                <input id="how-other7" name="primary_passenger_gender" type="radio" name="passenger_gender[]" value="Male"<?php echo $male_checked?>>
                <label for="how-other7" class="side-label"><?php echo getPhrase('M');?></label>
                </span> <span>
                <input id="how-other8" name="primary_passenger_gender" type="radio" name="passenger_gender[]" value="Female"<?php echo $female_checked?>>
                <label for="how-other8" class="side-label"> <?php echo getPhrase('F');?></label>
                </span></td>
				
				
				<td>
				<?php 
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
				}?>
				<input type="hidden" name="primary_passenger_type" value="<?php echo $seat_type;?>">
				<input type="hidden" name="primary_passenger_shuttle_no" value="<?php echo $rec->shuttle_no;?>">
				</td>
				<td><input type="text" class="age" name="primary_passenger_age" value="<?php echo set_value('primary_passenger_age');?>"></td>
				
				<td><?php echo getPhrase('Seat');?>
					<?php echo $rec->seat;?> (<?php echo $rec->shuttle_no;?>)
					<input type="hidden" name="primary_passenger_seat" value="<?php echo $rec->seat;?>">
				</td>
				
			</tr>
			<?php 
			$i++;
			} ?>

			<?php if(count($details[$journey_type]['selection_details']) > 1) { ?>
			<?php /*?>
			<tr class="ud">
			<td><?php echo getPhrase('Name');?></td>
			<!--<td><?php echo getPhrase('Complete Pick-up Address');?></td>
			<td><?php echo getPhrase('Complete Drop-off Address');?></td>-->
			<td><?php echo getPhrase('Gender');?></td>
			<td><?php echo getPhrase('Type');?></td>
			<td><?php echo getPhrase('Age');?></td>
			<td><?php echo getPhrase('Seat');?></td>
			<td></td>
			</tr>
			<?php */?>
			<?php
			$i = $k = 0;
			foreach($details[$journey_type]['selection_details'] as $key => $rec) 
			{
				if($i == 0)
				{
					$i++;
					continue;
				}
			//for($i = 0; $i < $details[$journey_type]['selected_seats_total']-1; $i++) {
			?>
			<tr>
              <td>
                <input type="text" class="user-f" name="passenger_name[]" value="<?php echo set_value('passenger_name[]');?>"></td>

              <!--<td>
                <input type="text" class="user-f" name="passenger_cpa[]"></td>

              <td>
                <input type="text" class="user-f" name="passenger_cda[]"></td>-->
              
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
				<td><?php echo getPhrase('Seat');?>
				<?php echo $rec->seat;?> (<?php echo $rec->shuttle_no;?>)
				<input type="hidden" name="passenger_seat[]" value="<?php echo $rec->seat;?>">
				<input type="hidden" name="passenger_price_set[]" value="<?php echo $rec->price_set;?>">
				<input type="hidden" name="passenger_shuttle_no[]" value="<?php echo $rec->shuttle_no;?>">
				</td>
				<td></td>
            </tr>
			<?php 
			$i+=2;
			$k++;
			} } ?>
				
          </tbody>
        </table>
		<table width="100%">
			<tr><td>Phone <span class="red">*</span></td><td><input type="text" class="user-f c-cod" placeholder="code" name="primary_passenger_phone_code" value="<?php echo (isset(getUserRec()->phone_code)) ? getUserRec()->phone_code : '';?>" maxlength="3">
				
				<input type="text" class="user-f" placeholder="Please enter mobile number" name="primary_passenger_phone" value="<?php echo (isset(getUserRec()->phone)) ? getUserRec()->phone : '';?>" maxlength="10">
				<input type="hidden" name="primary_passenger_price_set" value="<?php echo$rec->price_set;?>"></td><td>Email <span class="red">*</span></td><td><input type="text" class="user-f" placeholder="Please enter country code" name="primary_passenger_email" value="<?php echo (isset(getUserRec()->email)) ? getUserRec()->email : '';?>"></td></tr>
			
			<tr><td>Address <span class="red">*</span></td><td>
			<textarea class="user-area" name="primary_passenger_cpa" placeholder="enter your complete pickup and drop off address here"><?php echo set_value('primary_passenger_cda');?></textarea>
			</td><td></td><td></td></tr>
			</table>

		<?php /*?>
	  <?php 
			$i = 0;
			foreach($details[$journey_type]['selection_details'] as $key => $rec) 
			{
				if($i > 0) break;
		?>
	  <table class="ps-de">
		<tbody>
			<tr>
			<td>Name</td>
			<td><input type="text" class="user-f" name="primary_passenger_name" value="<?php echo set_value('primary_passenger_name', (isset(getUserRec()->username)) ? getUserRec()->username : '');?>"></td>
			<td>Phone</td>
			<td><input type="text" maxlength="3" name="primary_passenger_phone_code" placeholder="Code" class="user-f c-cod" value="<?php echo set_value('primary_passenger_phone_code', (isset(getUserRec()->phone_code)) ? getUserRec()->phone_code : '');?>"> 
			<input type="text" class="user-f" name="primary_passenger_phone" value="<?php echo set_value('primary_passenger_phone', (isset(getUserRec()->phone)) ? getUserRec()->phone : '');?>" maxlength="10">
			<input type="hidden" name="primary_passenger_price_set" value="<?php echo $rec->price_set;?>">
			</td>
			</tr>
			<tr>
			<td>Email</td>
			<td><input type="text" class="user-f" name="primary_passenger_email" value="<?php echo set_value('primary_passenger_email', (isset(getUserRec()->email)) ? getUserRec()->email : '');?>"></td>
			<td>Gender </td>
			<td> 
			
			<?php
				$male_checked = 'TRUE';
				$female_checked = '';
				if((isset(getUserRec()->gender)))
				{
					if(getUserRec()->gender == 'Female')
					{
						$male_checked = '';
						$female_checked = 'TRUE';
					}

				}
			?>

			 <span>
			 <input type="radio" <?php echo set_radio('primary_passenger_gender', 'Female', $female_checked); ?> value="Female"  name="primary_passenger_gender" id="how-other8">
			 <label class="side-label" for="how-other8"> F</label>
			 </span>
			 <span>
			 <input type="radio" <?php echo set_radio('primary_passenger_gender', 'Male', $male_checked); ?> value="Male" name="primary_passenger_gender" id="how-other7">
			 <label class="side-label" for="how-other7">M</label>
			 </span>
			 
			 </td>
			</tr>
			<?php 
				$seat_type = 'adult';
				if($rec->seat_type == 'a') 
				{ 
				$seat_type = 'adult';
				}
				elseif($rec->seat_type == 'c')
				{
				$seat_type = 'child';				
				}?>
				<input type="hidden" name="primary_passenger_type" value="<?php echo $seat_type;?>">
				<input type="hidden" name="primary_passenger_shuttle_no" value="<?php echo $rec->shuttle_no;?>">
			<tr>
			<td>Type (Seat)</td>
			<td><strong><?php echo getPhrase(ucwords($seat_type));?> (<?php echo $rec->seat;?>)</strong></td>
			<input type="hidden" name="primary_passenger_seat" value="<?php echo $rec->seat;?>">
			<td>Age</td>
			<td><input type="text" class="user-f" name="primary_passenger_age" value="<?php echo set_value('primary_passenger_age');?>"></td>
			</tr>
			<tr>
			<td valign="top">Complete Drop-off Address</td>
			<td><textarea class="user-area" name="primary_passenger_cda"><?php echo set_value('primary_passenger_cda');?></textarea> </td>
			<td valign="top">Complete Pick-up Address</td>
			<td> <textarea class="user-area" name="primary_passenger_cpa"><?php echo set_value('primary_passenger_cpa');?></textarea></td>
			</tr>

		</tbody>
	 </table>
	 <?php 
		$i++;
		} 
	  ?>
<?php */?>

	<?php 
	/*
	if(count($details[$journey_type]['selection_details']) > 1) { 

			$i = $k = 0;
			foreach($details[$journey_type]['selection_details'] as $key => $rec) 
			{
				if($i == 0)
				{
					$i++;
					continue;
				}

		?>
	  <table class="ps-de">
		<tbody>
			<tr>
				<td>Name</td>
				<td><input type="text" class="user-f" name="passenger_name[]" value="<?php echo set_value('passenger_name[]'); ?>"></td>
				<td>Gender </td>
				<td> 
				 <span>
				 <input type="radio" <?php echo set_radio('passenger_gender[<?php echo $k;?>]', 'Female'); ?> value="Female" id="passenger_<?php echo ($i+1)?>"  name="passenger_gender[<?php echo $k;?>]" >
				 <label class="side-label" for="passenger_<?php echo ($i+1)?>"> F</label>
				 </span>
				 <span>
				 <input type="radio" value="Male" <?php echo set_radio('passenger_gender[<?php echo $k;?>]', 'Male'); ?> id="passenger_<?php echo ($i+2)?>" name="passenger_gender[<?php echo $k;?>]">
				 <label class="side-label" for="passenger_<?php echo ($i+2)?>">M</label>
				 </span>
				 
				 </td>
			</tr>

			<?php 
				$seat_type = 'adult';
				if($rec->seat_type == 'a') 
				{ 
				$seat_type = 'adult';
				}
				elseif($rec->seat_type == 'c')
				{
				$seat_type = 'child';				
				}?>
				<input type="hidden" class="passenger_type" name="passenger_type[]" value="<?php echo $seat_type;?>">
			<tr>
			<td>Type (Seat)</td>
			<td><strong><?php echo getPhrase(ucwords($seat_type));?> (<?php echo $rec->seat;?>)</strong></td>

			<td>Age</td>
			<td><input type="text" class="user-f" name="passenger_age[]" value="<?php echo set_value('passenger_age[]'); ?>"></td>
			</tr>
			<tr>
			<td valign="top">Complete Drop-off Address</td>
			<td><textarea class="user-area" name="passenger_cda[]"><?php echo set_value('passenger_cda[]'); ?></textarea> </td>
			<td valign="top">Complete Pick-up Address</td>
			<td> <textarea class="user-area" name="passenger_cpa[]"><?php echo set_value('passenger_cpa[]'); ?></textarea></td>

				<input type="hidden" name="passenger_seat[]" value="<?php echo $rec->seat;?>">
				<input type="hidden" name="passenger_price_set[]" value="<?php echo $rec->price_set;?>">
				<input type="hidden" name="passenger_shuttle_no[]" value="<?php echo $rec->shuttle_no;?>">
			</tr>

		</tbody>
	 </table>
	 <?php 
		$i+=2;
		$k++;
		} } */
	  ?>


	</div>
      
      <div class="payment">	  
      <div class="panel panel-default">
  <div class="panel-heading"><?php echo getPhrase('Payment Details');?></div>
  <div class="panel-body">
  
<?php
foreach($gateways as $key => $gateway) { ?>
	<div class="pay">
	<input id="payment_type_<?php echo $gateway->gateway_id;?>" name="payment_type" type="radio" value="<?php echo $gateway->gateway_id;?>">
	<label for="payment_type_<?php echo $gateway->gateway_id;?>" class="side-label"><?php echo (isset($gateway->display_title) && $gateway->display_title!= '') ? $gateway->display_title: $gateway->gateway_title;?></label>
	</div>
<?php } ?>

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

<?php echo getPhrase('Seat no(s)');?> : <?php echo (isset($details['onward']['selected_seats'])) ? $details['onward']['selected_seats'] : '-'?><br><br>
<?php $selection_details = (isset($details['onward']['selection_details'])) ? $details['onward']['selection_details'] : array();
//neatPrint($selection_details);
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

<?php echo getPhrase('Seat no(s)');?> : <?php echo (isset($details['return']['selected_seats'])) ? $details['return']['selected_seats'] : '-'?><br><br>

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
  
  $service_charge = 0;
  $service_charge_onward = (isset($details['onward']['service_charge'])) ? $details['onward']['service_charge'] : 0;
  $service_charge_return = (isset($details['return']['service_charge'])) ? $details['return']['service_charge'] : 0;
  $service_charge = $service_charge_onward + $service_charge_return;
  
  $total_fare = 0;
  $total_fare_onward = (isset($details['onward']['total_fare'])) ? $details['onward']['total_fare'] : 0;
  $total_fare_return = (isset($details['return']['total_fare'])) ? $details['return']['total_fare'] : 0;
  $total_fare = $total_fare_onward + $total_fare_return;
  
  $insurance = 0;
  $insurance_onward = (isset($details['onward']['insurance'])) ? $details['onward']['insurance'] : 0;
  $insurance_return = (isset($details['return']['insurance'])) ? $details['return']['insurance'] : 0;
  $insurance = $insurance_onward + $insurance_return;
  $total_fare = $total_fare + $insurance;
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