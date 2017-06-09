<?php
$seats = array_values($details[$journey_type]['seats']);
//$token = $details[$journey_type]['token'];
//neatPrint($details[$journey_type]['selection_details']);

?>

<div><?php echo $message;?></div>
<?php
$adults = $child = 0;
$selection_details = $details[$journey_type]['selection_details'];
$adults = isset($details[$journey_type]['adult']) ? $details[$journey_type]['adult'] : 0;
$child = isset($details[$journey_type]['child']) ? $details[$journey_type]['child'] : 0;
$infant = isset($details[$journey_type]['infant']) ? $details[$journey_type]['infant'] : 0;
$selected_adult = $selected_child = $selected_infant = 0;
if($this->input->ip_address() == '183.82.114.32')
{
//neatPrint($details);
}
$attributes = "id='payment_form' name='payment_form' class=''";
echo form_open('bookingseat/passenger_details', $attributes);
?>
<div class="container">
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
      <div class="booked-users-list">
	  
	  <table width="200" border="1" class="ps-de">
          <tbody>
            <tr class="ud">
			<td><?php echo getPhrase('Name');?> <span class="red">*</span></td>
			
			<td><?php echo getPhrase('Gender');?> <span class="red">*</span></td>
			<td><?php echo getPhrase('Type');?></td>
			<td><?php echo getPhrase('Age');?> <span class="red">*</span></td>
			<!--<td><?php echo getPhrase('Seat');?></td>-->
			</tr>
			<?php			
			//neatPrint($selection_details);
			$required_seats = $details[$journey_type]['adult']+$details[$journey_type]['child'];
			$i = 0;
			$shuttles = array();
			foreach($selection_details as $key => $rec) 
			{
				if($i > 0) break;
				?>
			<tr>
				<td>
				<input type="text" class="user-f" name="primary_passenger_name" id="primary_passenger_name" value="<?php echo set_value('primary_passenger_name', (isset(getUserRec()->username)) ? getUserRec()->username : '');?>"></td>
				
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
				/*
				if($rec->seat_type == 'a' || $rec->seat_type == 'adult') 
				{ 
				echo getPhrase('Adult'); 
				$seat_type = 'adult';
				}
				elseif($rec->seat_type == 'c')
				{
				echo getPhrase('Child');
				$seat_type = 'child';				
				}
				*/
				if($selected_adult < $adults)
				{
					echo getPhrase('Adult');
					$seat_type = 'adult';
					$selected_adult++;
				}					
				elseif($selected_child < $child)
				{
					echo getPhrase('Child');
					$seat_type = 'child';
					$selected_child++;
				}
				?>
				<input type="hidden" name="primary_passenger_type" value="<?php echo $seat_type;?>">
				
				</td>
				<td>
				<?php $age = set_value('primary_passenger_age', (isset(getUserRec()->dob)) ? getUserRec()->dob : '');
				if($age != '' && is_date($age))
				$age = ageCalculator($age);
				?>
				<input type="text" class="age" name="primary_passenger_age" value="<?php echo $age;?>"></td>
				
				<!--<td>
					<?php 
					$shuttles[] = $rec->shuttle_no;
					$shuttles_inner = array();
					$seat = $seat_no = $shuttle = '';
					foreach($selection_details as $key_in => $rec_in) { ?>
						<?php
						if(!in_array($rec_in->shuttle_no, $shuttles_inner)) {
							$shuttles_inner[] = $rec_in->shuttle_no;
							$seat .= $rec_in->seat.', ';
							$seat_no .= $rec_in->seat_display.', ';
							$shuttle .= $rec_in->seat.'_'.$rec_in->shuttle_no.'_'.$rec_in->seat_display.', ';
						echo $rec_in->seat_display;?> (<?php echo $rec_in->shuttle_no;?>)<br>
						<?php } } ?>					
				</td>-->
				<input type="hidden" name="primary_passenger_seat" value="<?php echo $seat;?>">
				<input type="hidden" name="primary_passenger_seat_no" value="<?php echo $seat_no;?>">
				<input type="hidden" name="primary_passenger_shuttle_no" value="<?php echo $shuttle;?>">
				
			</tr>
			<?php 
			$i++;
			} ?>

			<?php if(count($selection_details) > 1) { ?>
			
			<?php
			$i = $k = 0;
			$count_check = 1;
			foreach($selection_details as $key => $rec) 
			{
				if($i == 0) //We are skipping first seat as it is allocated to primary passenger
				{
					$i++;
					continue;
				}
				if($count_check > $required_seats-1) break; //Take only required number of passenger details, even there are more seats (In case of conncted shuttles there will be more seats than required ie. 3 - required seats in two shuttles it will be 6 seats)
			
			?>
			<tr>
              <td>
                <input type="text" class="user-f" name="passenger_name[<?php echo $k;?>]" value="<?php echo set_value('passenger_name[]');?>"></td>              
              <td><span for="passenger<?php echo ($i+1)?>">
                <input id="passenger_<?php echo ($i+1)?>" type="radio" name="passenger_gender[<?php echo $k;?>]" value="Male" checked>
                <label for="passenger_<?php echo ($i+1)?>" class="side-label"><?php echo getPhrase('M');?></label>
                </span> <span for="passenger<?php echo ($i+2)?>">
                <input id="passenger_<?php echo ($i+2)?>" type="radio" name="passenger_gender[<?php echo $k;?>]" value="Female">
                <label for="passenger_<?php echo ($i+2)?>" class="side-label"> <?php echo getPhrase('F');?></label>
                </span></td>
				
			<td><?php
				$seat_type = 'adult';
				/*
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
				*/
				if($selected_adult < $adults)
				{
					echo getPhrase('Adult');
					$seat_type = 'adult';
					$selected_adult++;
				}					
				elseif($selected_child < $child)
				{
					echo getPhrase('Child');
					$seat_type = 'child';
					$selected_child++;
				}
					
			?>
                <input type="hidden" class="passenger_type" name="passenger_type[]" value="<?php echo $seat_type;?>"></td>
            <td>
                <input type="text" class="age" name="passenger_age[]"  value="<?php echo set_value('passenger_age[]');?>"></td>
				<!--<td>
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
					$shuttle .= $rec->seat.'_'.$rec_in->shuttle_no.'_'.$rec->seat_display.', ';
				echo $rec->seat_display;?> (<?php echo $rec_in->shuttle_no;?>)<br>
				<?php }} ?>				
				</td>-->
				<input type="hidden" name="passenger_seat[]" value="<?php echo $rec->seat;?>">
				<input type="hidden" name="passenger_seat_no[]" value="<?php echo $rec->seat_display;?>">
				<input type="hidden" name="passenger_price_set[]" value="<?php echo $rec->price_set;?>">
				<input type="hidden" name="passenger_shuttle_no[]" value="<?php echo $shuttle;?>">
				<td></td>
            </tr>
			<?php 
			$i+=2;
			$k++;
			$count_check++;
			} } ?>
			
			<?php if($infant > 0)
			{
				for($infa = 0; $infa < $infant; $infa++)
				{
				?>
				<tr>
				  <td>
					<input type="text" class="user-f" name="infant_passenger_name[]" value="<?php echo set_value('infant_passenger_name[]');?>"></td>              
				  <td><span for="passenger_<?php echo ($i+1)?>">
					<input id="passenger_<?php echo ($i+1)?>" type="radio" name="infant_passenger_gender[<?php echo $k;?>]" value="Male" checked>
					<label for="passenger_<?php echo ($i+1)?>" class="side-label"><?php echo getPhrase('M');?></label>
					</span> <span for="passenger_<?php echo ($i+2)?>">
					<input id="passenger_<?php echo ($i+2)?>" type="radio" name="infant_passenger_gender[<?php echo $k;?>]" value="Female">
					<label for="passenger_<?php echo ($i+2)?>" class="side-label"> <?php echo getPhrase('F');?></label>
					</span></td>
					
				<td>
				<?php					 
					echo getPhrase('Infant'); 
				?></td>
				<td>
					<input type="text" class="age" name="infant_passenger_age[]"  value="<?php echo set_value('infant_passenger_age[]');?>">
				</td>
		 
				</tr>
				<?php
				$i+=2;
				}
			}
			?>				
          </tbody>
        </table>
		<table width="100%" class="pad">
			<tr><td>Phone <span class="red">*</span></td><td>
			<input type="text" class="user-f c-cod" placeholder="code" name="primary_passenger_phone_code" value="<?php echo set_value('primary_passenger_phone_code', (isset(getUserRec()->phone_code)) ? getUserRec()->phone_code : '');?>" maxlength="3">
				
				<input type="text" class="user-f" placeholder="Please enter mobile number" name="primary_passenger_phone" value="<?php echo set_value('primary_passenger_phone', (isset(getUserRec()->phone)) ? getUserRec()->phone : '');?>" maxlength="10">
				<input type="hidden" name="primary_passenger_price_set" value="<?php echo$rec->price_set;?>"></td><td>Email <span class="red">*</span></td><td><input type="text" class="user-f" placeholder="Please enter country code" name="primary_passenger_email" value="<?php echo set_value('primary_passenger_email', (isset(getUserRec()->email)) ? getUserRec()->email : '');?>"></td></tr>
			
			<tr><td>Address <span class="red">*</span></td><td>
			<textarea class="user-area" name="primary_passenger_cpa" placeholder="enter your complete pickup and drop off address here"><?php echo set_value('primary_passenger_cda', (isset(getUserRec()->address)) ? getUserRec()->address : '');?></textarea>
			</td> </tr>
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
	<input id="payment_type_<?php echo $gateway->gateway_id;?>" name="payment_type" class="payment_type" type="radio" value="<?php echo $gateway->gateway_id;?>" onclick="hide_all()">
	<label for="payment_type_<?php echo $gateway->gateway_id;?>" class="side-label"><?php echo (isset($gateway->display_title) && $gateway->display_title!= '') ? $gateway->display_title: $gateway->gateway_title;?></label>
	</div>
	<?php
	}
} else {
	$onclick = $str = $str2 = '';
	if($gateway->gateway_title == 'Finpayapi')
	{
		$instructions = $this->base_model->fetch_records_from('banks', array('status' => 'Active'));
		if(!empty($instructions))
		{
			$onclick = ' onclick="show_instructions('.$gateway->gateway_id.')"';
			foreach($instructions as $b)
			{
				$str .= '<div class="banks">
	<input id="bank_'.$b->id.'" name="bank" type="radio" value="'.$b->id.'" onclick="show_instructions2('.$b->id.')">
	<label for="bank_'.$b->id.'" class="side-label"><img height="100" src="'. base_url().'uploads/banks/'.$b->image.'" ></label>
	</div>';
	
		$str2 .= '<span id="instructions2_'.$b->id.'" class="instructions2" style="display:none;">'.$b->comments.'</span>';
			}
			//$str = $instructions[0]->gateway_field_value;
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
	<input id="payment_type_<?php echo $gateway->gateway_id;?>" name="payment_type" class="payment_type" type="radio" value="<?php echo $gateway->gateway_id;?>"<?php echo $onclick;?>>
	<label for="payment_type_<?php echo $gateway->gateway_id;?>" class="side-label"><?php echo (isset($gateway->display_title) && $gateway->display_title!= '') ? $gateway->display_title: $gateway->gateway_title;?></label>
	</div>
	<span id="instructions_<?php echo $gateway->gateway_id;?>" class="instructions" style="display:none;"><?php echo $str;?></span>
	<?php echo $str2;?>
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

function show_instructions2(gateway_id)
{
	var elements = document.getElementsByClassName('instructions2');
	for (var i = 0; i < elements.length; i++){
        elements[i].style.display = 'none';
    }
	document.getElementById('instructions2_'+gateway_id).style.display = 'block';
}
function hide_instructions2()
{
	var elements = document.getElementsByClassName('instructions2');
	for (var i = 0; i < elements.length; i++){
        elements[i].style.display = 'none';
    }
}
function hide_all()
{
	hide_instructions();
	hide_instructions2();
}
</script>

<?php if(!isset($details['disount_amount'])) { ?>
<div id="coupon_view">
<?php $this->load->view('seat/site/booking/coupon_view');?>
</div>
<?php } ?>
	
<input type="submit" name="buttsubmit" value="<?php echo getPhrase('Process Anyway');?>" class="btn btn-default">

  </div>
 
</div>
      </div>
      
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
	
	<div class="timer">
	<h4>Please complete your booking between <span id="mins"></span> : <span id="seconds"> minutes.</span></h4>
	</div>
	
    <div class="panel panel-default onps">
  <div class="panel-heading"><?php echo getPhrase('Onward Journey');?></div>
  <div class="panel-body">
 <strong>
 <?php 
 if($details['onward']['pick_point_view'] != '')
 echo $details['onward']['pick_point_view'];
else if($details['onward']['pick_point_name'] != '')
	echo $details['onward']['pick_point_name'];
?></strong> <?php echo getPhrase('to');?> <strong>
<?php 
 if($details['onward']['drop_point_view'] != '')
 echo $details['onward']['drop_point_view'];
else if($details['onward']['drop_point_name'] != '')
	echo $details['onward']['drop_point_name'];
?>
</strong><br>
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
			echo $val_in->seat_display.',';
		}
		}
		echo ' ('.$val->shuttle_no.')';

		echo '<br>Pick Time : '.date('D, M d, Y', strtotime($val->pick_date)).' '.$val->start_time;

		echo '<br>'.$val->pick_point_name .' <b> TO </b> '.$val->drop_point_name;
		echo '<br></hr>';
		$cc++;
		if($cc != count($selection_details))
			echo '<br>';
		}
	}
}
?>

<?php 
$onward_insurance_check = '';
if(isset($details['onward']['insurance_taken']) && $details['onward']['insurance_taken'] == 'yes') $onward_insurance_check = ' checked'; ?>
<br><input type="checkbox" name="onward_insurance" value="yes" onclick="calculate_insurace_fee(this, 'onward')" <?php echo $onward_insurance_check;?>> <?php echo getPhrase('Insurance')?>
  </div>
</div>

<?php 
//neatPrint($details);
if(isset($details['is_return']) && $details['is_return'] == 'yes') { ?>
<div class="panel panel-default onps">
  <div class="panel-heading"><?php echo getPhrase('Return Journey');?></div>
  <div class="panel-body">
 <strong>
  <?php 
 if($details['return']['pick_point_view'] != '')
 echo $details['return']['pick_point_view'];
else if($details['return']['pick_point_name'] != '')
	echo $details['return']['pick_point_name'];
?>
 </strong> <?php echo getPhrase('to');?> <strong>
 <?php 
 if($details['return']['drop_point_view'] != '')
 echo $details['return']['drop_point_view'];
else if($details['return']['drop_point_name'] != '')
	echo $details['return']['drop_point_name'];
?>
 </strong><br>
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
			echo $val_in->seat_display.',';
		}
		}
		echo ' ('.$val->shuttle_no.')';

		echo '<br>Pick Time : '.date('D, M d, Y', strtotime($val->pick_date)).' '.$val->start_time;

		echo '<br>'.$val->pick_point_name .' <b> TO </b> '.$val->drop_point_name;
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
if(isset($details['return']['insurance_taken']) && $details['return']['insurance_taken'] == 'yes') $return_insurance_check = ' checked'; ?>
<br><input type="checkbox" name="return_insurance" value="yes" onclick="calculate_insurace_fee(this, 'return')" <?php echo $return_insurance_check;?>><?php echo getPhrase('Insurance')?>

  </div>
</div>
<?php } ?>

<div class="panel panel-default onps">
  <!-- Default panel contents -->
  <div class="panel-heading"><?php echo getPhrase('Payment Summary');?></div>
  <!-- List group -->
  <ul class="list-group">
  <?php 
  //neatPrint($details);
  $selected_seats_total = $total_fare = 0;
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
  
  
  /*
  $total_fare = 0;
  $total_fare_onward = (isset($details['onward']['total_fare'])) ? $details['onward']['total_fare'] : 0;
  $total_fare_return = (isset($details['return']['total_fare'])) ? $details['return']['total_fare'] : 0;
  $total_fare = $total_fare_onward + $total_fare_return;
  */
  
  $insurance = 0;
  $insurance_onward = (isset($details['onward']['insurance_amount'])) ? $details['onward']['insurance_amount'] : 0;
  $insurance_return = (isset($details['return']['insurance_amount'])) ? $details['return']['insurance_amount'] : 0;
  $insurance = $insurance_onward + $insurance_return;
  
  $disount_amount = (isset($details['disount_amount'])) ? $details['disount_amount'] : 0;
  
  $total_fare = ($basic_fare + $service_charge + $insurance) - $disount_amount;
  //$total_fare = $total_fare + $insurance - $disount_amount;
  
  
  $basic_fare = number_format($basic_fare, 2);
  $service_charge = number_format($service_charge, 2);
  $total_fare = number_format(($total_fare > 0) ? $total_fare : 0, 2); //If any case discount is more than total amount then total_fare reaches minus (Negative value).
  ?>
    <li class="list-group-item"><?php echo getPhrase('Seats');?> <span><?php echo $selected_seats_total;?></span></li>
    <li class="list-group-item"><?php echo getPhrase('Basic Fare');?> 	<span><?php echo $site_settings->currency_symbol; echo $basic_fare;?></span></li>
    <li class="list-group-item" id=""><?php echo getPhrase('Service Charge');?> <span><?php echo $site_settings->currency_symbol; echo $service_charge;?></span> </li>
	<?php if($insurance > 0) {
		?>
		<li class="list-group-item" id="insurance_li"><?php echo getPhrase('Insurance');?> <span><?php echo $site_settings->currency_symbol; echo number_format($insurance, 2);?></span> </li>
		<?php
	}?>
	<?php if($disount_amount > 0) {
		?>
		<li class="list-group-item"><?php echo getPhrase('Discount');?> <span><?php echo $site_settings->currency_symbol; echo number_format($disount_amount, 2);?></span> </li>
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
	
	function calculate_insurace_fee(obj, type)
	{
		$.ajax({
		  type: "post",
		  url: "<?php echo base_url();?>bookingseat/calculate_insurace_fee",
		  async: false,
		  data: {
					<?php echo $this->security->get_csrf_token_name();?>:
					"<?php echo $this->security->get_csrf_hash();?>",
					status:$(obj).is(':checked'),
					type:type
				},
		  cache: false, 
		  success: function(data) {
			var parsed_data = $.parseJSON(data);
			$('#insurance_li').html('<?php echo getPhrase('Insurance');?> <span>'+parsed_data['insurance']+'</span>');
			$('#total_fare').html(parsed_data['total_fare']);
		  },
		  error: function(){
			alert('Ajax Error');
		  }
		});
	}
	
	
</script>