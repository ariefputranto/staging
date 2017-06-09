<div class="col-lg-10 col-lg-offset-1">
<div class="contactCloseBox" style="cursor:pointer;" onclick="closeblock('<?php echo $index;?>');"><i class="flaticon-close-1"></i></div>
<div class="bus-book-view">
<div class="seats-list">

<ul>
<li> <img src="<?php echo URL_SEAT_IMAGES;?>seats-single.png"> <?php echo getPhrase('Available Seat')?></li>
<li><img src="<?php echo URL_SEAT_IMAGES;?>seats-single1.png"> <?php echo getPhrase('Selected Seat')?></li>
<!--<li><img src="<?php echo URL_SEAT_IMAGES;?>women.png"> <?php echo getPhrase('Female')?></li>-->
<li><img src="<?php echo URL_SEAT_IMAGES;?>booked.png"> <?php echo getPhrase('Booked')?></li>
<li><img src="<?php echo URL_SEAT_IMAGES;?>boy.png"> <?php echo getPhrase('Child')?></li>
</ul>
<p class="aler"><?php echo getPhrase('You can open and view multipleseat layouts simultaneously')?>.</p>
</div>
<div class="seats-list">
<h5><?php echo $available_price_set_seats;?> <?php echo getPhrase('seat(s) available')?></h5>
<p><?php echo getPhrase('Click on seat to select/deselect seat')?></p>

<p id="message_<?php echo $div_id;?>"></p>

<div class="seat-selct">
<div class="seat-first-row">
<?php
$thisrecord = $record[$journey_type];
$locked_seats_array = array();
if(!empty($locked_seats))
{
	foreach($locked_seats as $key => $ls)
	{
		foreach($ls as $in_key => $in_val)
		{
			$locked_seats_array[] = $in_val->seat;
		}
	}
}
$k = array();
$fare_details = (isset($v->fare_details) && $v->fare_details != '') ? json_decode($v->fare_details) : array();
$fare_details = (array)$fare_details;
//neatPrint($locked_seats);
if(isset($thisrecord['selection_details']) && count($thisrecord['selection_details']) > 0)
{
	foreach($thisrecord['selection_details'] as $ve => $selected){
		$parts = explode('_', $ve);
		if($shuttle_no_received == $selected->shuttle_no)
		{
			$k[] = $selected->seat;
		}
	}
}

$seat_no = 1;
if($v->has_driver_seat == 'Yes') { 
$seatno = 'driverseat';
if(in_array($seatno, array_keys($booked_seats[$v->tlc_id])) || (in_array($seatno, $locked_seats_array)))
{
	?>
	<a href="javascript:void(0);" title="Adult" id="anchor_<?php echo $tlc_id;?>_driverseat" class="booked"><span class="driver_seat"><?php echo $seat_no++;?></span></a>
	<?php
} else {
	$seat_display = 'Coseat';
	$class = 'available';
	if(in_array($seatno,$k))
		$class = 'selected';
?>
<a href="javascript:void(0);" title="Adult" onclick="setSelected(this,<?php echo $vehicle_id;?>,'driverseat',<?php echo $v->travel_location_id?>,'<?php echo $v->start_date_new;?>','',<?php echo $price_set?>,'<?php echo $v->shuttle_no?>',<?php echo $v->tlc_id;?>,<?php echo $available_price_set_seats;?>,'adult','<?php echo $token;?>', <?php echo $div_id;?>, '<?php echo $seat_display;?>')" id="anchor_<?php echo $tlc_id;?>_driverseat" class="<?php echo $class;?>">
<span class="driver_seat"><span class="seatno"><?php echo $seat_no++;?></span></span></a>
<?php } } ?>
<?php
$rows = $v->seat_rows;
$colums = $v->seat_columns;
$aisle = explode(',',$v->seats_empty);
$child_seats = array();
if($v->child_seats != '')
	$child_seats = explode(',', $v->child_seats);
$tlc_id = $v->tlc_id;
$vehicle_id = $v->id;
$shuttle_no = $v->shuttle_no;
//print_r($locked_seats_array);
//neatPrint($seats_available);
$locked_seats_count = count($locked_seats_array);
$seats_count = 0;
//for($r = 1; $r <= $colums; $r++)
for($r = 1; $r <= $rows; $r++)
{
	echo '<ul>';
	
	//for($c=1;$c<=$rows;$c++)
	for($c=1;$c<=$colums;$c++)
	{
		if($r == 1 && $c == $colums -1)
		{
			$right = $colums * 53.6;
			echo '<div class="driver-seat" style="left:'.$right.'px"></div>';	
		}
		$seatno = 'R'.$r.'C'.$c;
		$wl = ($seats_available > 0) ? '' : 'WL_';
		if($wl == '' && $seats_available == $locked_seats_count) //Means seats available but all seats locked
		{
			$wl = 'WL_'; //We need see the new selection as waiting list only
		}
		$fare = $r;
		$seat_type = 'a';
		$seat_type_display = '';
		$seat_type_label = 'Adult';
		if(in_array($seatno, $child_seats))
		{
			$seat_type = 'c';
			$seat_type_label = 'Child';
			$seat_type_display = 'c';
		}
		$is_available_for_booking = TRUE;
		$all_available_seats_blocked = FALSE;
		if($seats_available > 0 && $seats_available == $locked_seats_count) //Let us handle all available seats are locked
		{
			$is_available_for_booking = FALSE;
			$all_available_seats_blocked = TRUE;
		}
			
		if ( $is_available_for_booking || $all_available_seats_blocked )
		{
			if((isset($booked_seats[$v->tlc_id]) && count($booked_seats[$v->tlc_id]) > 0 && in_array($seatno, array_keys($booked_seats[$v->tlc_id]))) || (in_array($seatno, $locked_seats_array))) //Booked seat
			{
				/*
				if($this->base_model->seat_type($seatno, $booked_seats[$v->tlc_id]) == 'Female')
				{
				$seat = '<a href="javascript:void(0);" title="'.$wl.$seatno.'" class="booked female" id="anchor_'.$tlc_id.'_'.$seatno.'"><li>FB</li></a>';
				}
				else
				{
				$seat = '<a href="javascript:void(0);" title="'.$wl.$seatno.'" class="booked" id="anchor_'.$tlc_id.'_'.$seatno.'"><li>B</li></a>';
				}
				*/
				$seat_display = $seat_no++;
				$seat = '<a href="javascript:void(0);" title="'.$wl.$seat_display.'"  id="anchor_'.$tlc_id.'_'.$seatno.'"><li class="booked"><span class="seatno">'.$seat_display.'</span></li></a>';
			}
			elseif(in_array($seatno,$k)){ //Available to book
				$class = '';
				$seat_display = $seat_no++;
				if($seat_type_display == 'c')
					$class = ' class="child"';
				$seat = '<a href="javascript:void(0);" title="'.$wl.$seat_display.'('.$seat_type_label.')" class="selected" onclick="setSelected(this,'.$vehicle_id.',\''.$seatno.'\','.$v->travel_location_id.',\''.$v->start_date_new.'\',\''.$wl.'\', '.$price_set.',\''.$v->shuttle_no.'\', '.$v->tlc_id.', '.$available_price_set_seats.',\''.$seat_type.'\',\''.$token.'\', '.$div_id.','.$seat_display.')" id="anchor_'.$tlc_id.'_'.$seatno.'"><li'.$class.' id="li_'.$tlc_id.'_'.$seatno.'"><span class="seatno">'.$seat_display.'</span></li></a>';				
			}elseif(!in_array($seatno,$aisle)){				
				$class = '';
				$seat_display = $seat_no++;
				if($seat_type_display == 'c')
					$class = ' class="child"';
				$seat = '<a href="javascript:void(0);" title="'.$wl.$seat_display.'('.$seat_type_label.')" onclick="setSelected(this,'.$vehicle_id.',\''.$seatno.'\','.$v->travel_location_id.',\''.$v->start_date_new.'\',\''.$wl.'\', '.$price_set.',\''.$v->shuttle_no.'\', '.$v->tlc_id.', '.$available_price_set_seats.',\''.$seat_type.'\',\''.$token.'\', '.$div_id.','.$seat_display.')" id="anchor_'.$tlc_id.'_'.$seatno.'" class="available"><li'.$class.' id="li_'.$tlc_id.'_'.$seatno.'"><span class="seatno">'.$seat_display.'</span></li></a>';				
			}else{
				$seat = '&nbsp;';
			}
		}
		else //Waiting List Booking
		{
			if(isset($booked_seats[$v->tlc_id]) && count($booked_seats[$v->tlc_id]) > 0 && in_array($seatno, array_keys($booked_seats[$v->tlc_id])))
			{
				/*
				if($this->base_model->seat_type($seatno, $booked_seats[$v->tlc_id]) == 'Female')
				{
				$seat = '<a href="javascript:void(0);" title="'.$wl.$seatno.'"  id="anchor_'.$tlc_id.'_'.$seatno.'"><li class="booked female">FB</li></a>';
				}
				else
				{
				$seat = '<a href="javascript:void(0);" title="'.$wl.$seatno.'"  id="anchor_'.$tlc_id.'_'.$seatno.'"><li class="booked">B</li></a>';
				}
				*/
				$seat_display = $seat_no++;
				$seat = '<a href="javascript:void(0);" title="'.$wl.$seat_display.'"  id="anchor_'.$tlc_id.'_'.$seatno.'"><li class="booked"><span class="seatno">'.$seat_display.'</span></li></a>';
			}
			elseif(in_array($seatno,$k)){
				$class = '';
				$seat_display = $seat_no++;
				if($seat_type_display == 'c')
					$class = ' class="child"';
				$seat = '<a href="javascript:void(0);" title="'.$wl.$seat_display.'('.$seat_type_label.')" class="selected" onclick="setSelected(this,'.$vehicle_id.',\''.$seatno.'\','.$v->travel_location_id.',\''.$v->start_date_new.'\',\''.$wl.'\', '.$price_set.',\''.$v->shuttle_no.'\', '.$v->tlc_id.', '.$available_price_set_seats.',\''.$seat_type.'\',\''.$token.'\', '.$div_id.','.$seat_display.')" id="anchor_'.$tlc_id.'_'.$seatno.'"><li'.$class.'><span class="seatno">'.$seat_display.'</span></li></a>';
			}elseif(!in_array($seatno,$aisle)){
				$class = '';
				$seat_display = $seat_no++;
				if($seat_type_display == 'c')
					$class = ' class="child"';
				$seat = '<a href="javascript:void(0);" title="'.$wl.$seat_display.'('.$seat_type_label.')" onclick="setSelected(this,'.$vehicle_id.',\''.$seatno.'\','.$v->travel_location_id.',\''.$v->start_date_new.'\',\''.$wl.'\', '.$price_set.', \''.$v->shuttle_no.'\', '.$v->tlc_id.', '.$available_price_set_seats.',\''.$seat_type.'\',\''.$token.'\', '.$div_id.','.$seat_display.')" id="anchor_'.$tlc_id.'_'.$seatno.'" class="available"><li'.$class.'><span class="seatno">'.$seat_display.'</span></li></a>';
			}else{
				$seat_no++;
				$seat = '&nbsp;';
			}
		}
		//if($seats_count < $total_number_of_seats)
		if(!in_array($seatno,$aisle))
		{
		echo $seat;
		$seats_count++;
		}
		else
		{
		//$seat_no++;
		echo '<a href="javascript:void(0);"><li class="empty_space" style="background:none !important;"></li></a>';
		}
		
	}
	
	echo '</ul>';
}

?>

</div>
</div>
<h5><?php echo $available_price_set_seats;//echo $available_seats[$v->tlc_id];?> <?php echo getPhrase('seat(s) available')?></h5>
</div>


<div class="seats-list">
<?php
if(isset($record[$journey_type]) && count($record[$journey_type]) > 0) {?>
<h5><?php echo ($journey_type == 'onward') ? getPhrase('Onward Journey') : getPhrase('Return Journey');?></h5>
<p><?php echo getPhrase('From');?> <strong><?php echo $v->pick_point_name;?></strong> <?php echo getPhrase('To');?> <strong><?php echo $v->drop_point_name;?></strong> <?php echo getPhrase('on');?> <strong>
<?php echo date('d M, Y',strtotime($v->start_date_new));//echo date('d M, Y', strtotime($record[$journey_type]['pick_date']))?></strong></p>
<ul>

<?php /*?>
<li><?php echo getPhrase('Seats');?> <span id="selected_seats_<?php echo $v->tlc_id;?>_<?php echo $token;?>"><?php if(isset($record[$journey_type]['token']) && $record[$journey_type]['token'] == $token) { echo (isset($record[$journey_type]['selected_seats'])) ? $record['onward']['selected_seats'] : '-'; } else { echo '-'; } ?></span></li>
<?php */?>

<li><?php echo getPhrase('Seats');?> <span id="selected_seats_<?php echo $v->tlc_id;?>_<?php echo $token;?>"><?php if(isset($record[$journey_type]['token']) && $record[$journey_type]['token'] == $token) { echo (isset($record[$journey_type]['selected_seats_no'])) ? $record['onward']['selected_seats_no'] : '-'; } else { echo '-'; } ?></span></li>

<li><?php echo getPhrase('Basic Fare');?> 	<span id="basic_fare_<?php echo $v->tlc_id;?>_<?php echo $token;?>"><?php echo $site_settings->currency_symbol . ' '; if((isset($record[$journey_type]['token']) && $record[$journey_type]['token'] == $token) && (isset($record[$journey_type]['token']) && $record[$journey_type]['token'] == $token)) { echo (isset($record[$journey_type]['basic_fare'])) ? number_format($record[$journey_type]['basic_fare'],2) : '0'; } else { echo '0'; } ?></span></li>

<li><?php echo getPhrase('Service Charge');?>  <span id="service_charge_<?php echo $v->tlc_id;?>_<?php echo $token;?>"><?php echo $site_settings->currency_symbol . ' '; if((isset($record[$journey_type]['token']) && $record[$journey_type]['token'] == $token) && (isset($record[$journey_type]['token']) && $record[$journey_type]['token'] == $token)) { echo (isset($record[$journey_type]['service_charge'])) ? number_format($record[$journey_type]['service_charge'],2) : '0'; } else { echo '0'; } ?> </span></li>

<li><?php echo getPhrase('Total Amount');?> <span id="total_fare_<?php echo $v->tlc_id;?>_<?php echo $token;?>"> <?php echo $site_settings->currency_symbol . ' '; if((isset($record[$journey_type]['token']) && $record[$journey_type]['token'] == $token) && (isset($record[$journey_type]['token']) && $record[$journey_type]['token'] == $token)) { echo (isset($record['onward']['total_fare'])) ? number_format($record[$journey_type]['total_fare'], 2) : '0'; } else { echo '0'; } ?> </span> </li>
</ul>
<div class="btn btn-default contune-payme" onclick="validateform(<?php echo $v->tlc_id;?>)"> <?php echo getPhrase('Continue to Payment')?> </div>
<?php } ?>

</div>

</div>
</div>