<?php
$seats = array_values($details[$journey_type]['seats']);
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
//print_r(getUserRec());
?>
<div class="container">
  <div class="row">
    <div class="col-lg-8">
      <div class="booked-users-list">
        <table width="200" border="1">
          <tbody>
            <tr>
				<td><?php echo getPhrase('Name');?>
				<input type="text" class="user-f" name="primary_passenger_name" id="primary_passenger_name" value="<?php echo (isset(getUserRec()->username)) ? getUserRec()->username : '';?>"></td>
				
				<td><?php echo getPhrase('Phone');?> </td>
				<td colspan="1"> 
				<input type="text" class="user-f phone" placeholder="Please enter country code" name="primary_passenger_phone_code" value="<?php echo (isset(getUserRec()->phone_code)) ? getUserRec()->phone_code : '';?>">
				
				<input type="text" class="user-f phone" placeholder="Please enter mobile number" name="primary_passenger_phone" value="<?php echo (isset(getUserRec()->phone)) ? getUserRec()->phone : '';?>">
				<!--<span class="numeric">+91</span>-->
				</td>
				
				<td><?php echo getPhrase('Email');?> </td>
				<td colspan="1"> 
				<input type="text" class="user-f phone" placeholder="Please enter country code" name="primary_passenger_email" value="<?php echo (isset(getUserRec()->email)) ? getUserRec()->email : '';?>">
				</td>
				
				<td><?php echo getPhrase('Gender');
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
				?></td>
              <td><span>
                <input id="how-other7" name="primary_passenger_gender" type="radio" name="passenger_gender[]" value="Male"<?php echo $male_checked?>>
                <label for="how-other7" class="side-label"><?php echo getPhrase('M');?></label>
                </span> <span>
                <input id="how-other8" name="primary_passenger_gender" type="radio" name="passenger_gender[]" value="Female"<?php echo $female_checked?>>
                <label for="how-other8" class="side-label"> <?php echo getPhrase('F');?></label>
                </span></td>
				
				
				<td><?php echo getPhrase('Type');?>
				<?php echo getPhrase('Adult'); $adults++;?>
				<input type="hidden" name="primary_passenger_type" value="adult">
				</td>
				
				<td><?php echo getPhrase('Seat');?>
					<?php echo $seats[0];?>
					<input type="hidden" name="primary_passenger_seat" value="<?php echo $seats[0];?>">
				</td>
				
			</tr>
			
			<?php
			//echo '<pre>';
			//print_r($details[$journey_type]);
			for($i = 0; $i < $details[$journey_type]['selected_seats_total']-1; $i++) {?>
			<tr>
              <td><?php echo getPhrase('Name');?>
                <input type="text" class="user-f" name="passenger_name[]"></td>
              <td><?php echo getPhrase('Gender');?></td>
              <td><span>
                <input id="passenger_<?php echo ($i+1)?>" type="radio" name="passenger_gender[]" value="Male">
                <label for="passenger_<?php echo ($i+1)?>" class="side-label"><?php echo getPhrase('M');?></label>
                </span> <span>
                <input id="passenger_<?php echo ($i+2)?>" type="radio" name="passenger_gender[]" value="Female">
                <label for="passenger_<?php echo ($i+2)?>" class="side-label"> <?php echo getPhrase('F');?></label>
                </span></td>
				
			<td><?php echo getPhrase('Type');
			$type = 'adult';
			if($details[$journey_type]['adult'] != $adults)
			{
				$adults++;
				echo getPhrase('Adult');
			}
			elseif($details[$journey_type]['child'] != $child)
			{
				$child++;
				echo getPhrase('Child');
				$type = 'chilt';
			}
			?>
                <input type="hidden" class="passenger_type" name="passenger_type[]" value="<?php echo $type;?>"></td>
            <td><?php echo getPhrase('Age');?>
                <input type="text" class="age" name="passenger_age[]"></td>
			<td><?php echo getPhrase('Seat');?>
					<?php echo $seats[$i+1];?>
					<input type="hidden" name="passenger_seat[]" value="<?php echo $seats[$i+1];?>">
				</td>
            </tr>
			<?php } ?>
			
			
          </tbody>
        </table>
      </div>
      
      <div class="payment">
      <div class="panel panel-default">
  <div class="panel-heading"><?php echo getPhrase('Payment Details');?></div>
  <div class="panel-body">
  
<?php foreach($gateways as $key => $gateway) { ?>
	<div class="pay">
	<input id="payment_type_<?php echo $gateway->gateway_id;?>" name="payment_type" type="radio" value="<?php echo $gateway->gateway_id;?>">
	<label for="payment_type_<?php echo $gateway->gateway_id;?>" class="side-label"><?php echo $gateway->gateway_title;?></label>
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
    <div class="col-lg-4"> 
    <div class="panel panel-default onps">
  <div class="panel-heading"><?php echo getPhrase('Onward Journey');?></div>
  <div class="panel-body">
 <strong><?php echo $details['onward']['pick_point_view'];?></strong> <?php echo getPhrase('to');?> <strong><?php echo $details['onward']['drop_point_view'];?></strong><br>
 <?php echo date('D, M d, Y', strtotime($details['onward']['pick_date']));?>
<br>

<?php echo getPhrase('Seat no(s)');?> : <?php echo (isset($details['onward']['selected_seats'])) ? $details['onward']['selected_seats'] : '-'?><br>
<?php echo (isset($details['onward']['vehicle_details'])) ? $details['onward']['vehicle_details']->name : '-'?><br>
<?php echo (isset($details['onward']['vehicle_details'])) ? $details['onward']['vehicle_details']->category : '-'?>

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

<?php echo getPhrase('Seat no(s)');?> : <?php echo (isset($details['return']['selected_seats'])) ? $details['return']['selected_seats'] : '-'?><br>
<?php echo (isset($details['return']['vehicle_details'])) ? $details['return']['vehicle_details']->name : '-'?><br>
<?php echo (isset($details['return']['vehicle_details'])) ? $details['return']['vehicle_details']->category : '-'?>
<?php 
$return_insurance_check = '';
if(isset($details['onward']['insurance']) && $details['onward']['insurance'] > 0) $return_insurance_check = ' checked'; ?>
<input type="checkbox" name="return_insurance" value="yes" onclick="calculate_insurace_fee(this)" <?php echo $return_insurance_check;?>><?php echo getPhrase('Insurance')?>

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