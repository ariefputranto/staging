<?php
$record = array();
if(count($this->session->userdata('journey_booking_details')) > 0) {
	$record = $this->session->userdata('journey_booking_details');
}
$journey_type = 'onward';
if(isset($record['is_return']) && $record['is_return'] == 'yes')
	$journey_type = 'return';
?>

<?php
 echo form_open('', 'name="bookingform" id="bookingform"');
 $index = $pv_count = 0;
 //echo '<pre>';print_r($vehicles);
 if(count($vehicles) > 0) {
	 foreach($vehicles as $v)
	 {
	 ?>
<li class="vehicle">
 <table>
  <tbody>
    <?php if(isset($v->has_connection) && $v->has_connection == 'yes' && isset($v->connection_start) && $v->connection_start == 'yes') {
		 ?>
		 <tr><td colspan="4" align="center">---------------</td></tr>
		 <?php
	 }?>
	<tr>
      <td>
      <i class="flaticon-transport-3"></i> 
     <h5><?php echo $v->name?><?php echo '- TLC:'.$v->tlc_id.'-V:'.$v->id.'-TL:'.$v->travel_location_id?></h5>
      <p class="shuttle_type"><?php echo $v->category?></p>
	  <p><small><?php echo $v->pick_point_name?> to <?php echo $v->drop_point_name?></small></p>
<span class="coun"><?php echo $v->shuttle_no?></span>
      <div class="badge"> <a href="#"> <i class="fa fa-list"></i> </a></div>
       <div class="badge"><a href="#"><i class="fa fa-image"></i> </a></div>
        <div class="badge"><a href="#" data-toggle="modal" data-target=".bs-example-modal-lg"><i class="fa fa-map"></i> </a></div> 
      
      </td>
      <td valign="top"> <i class="flaticon-clock"></i>
      <h5><?php echo $v->pick_point_name?> - <?php echo ($v->start_time != '') ? $v->start_time : 'Not Available';?></h5>
	  <h5><?php echo $v->drop_point_name?> - <?php echo ($v->destination_time != '') ? $v->destination_time : 'Not Available';?></h5>
      
	  <?php 
	  $seats_available = $available_seats[$v->tlc_id];
	  	++$index;
	  if($v->start_time != '') { 
	  $time = str_replace(' ','',$v->start_time);
	  $pick_date_time = $record['pick_date'].' '.$time;
	  
	  $time = str_replace(' ','',$v->destination_time);
	  $destination_time = date('m/d/Y',strtotime($record['pick_date'].' +'.$v->elapsed_days.' days')).' '.$time;
	  ?>
	  <p><?php echo getPhrase('Duration')?> &nbsp; &nbsp; &nbsp; <?php echo timespan(strtotime($pick_date_time),strtotime($destination_time));?></p>
	  <?php } ?>
       </td>
      <td valign="top"> 
      <i class="flaticon-transport-4"></i>   
	  <?php
	  //neatPrint($v);
	  $fare_details = (isset($v->fare_details) && $v->fare_details != '') ? json_decode($v->fare_details) : array();
	  $fare_details = (array)$fare_details;
	  //print_r($fare_details);
	  $price = $v->base_fare;
	  $total_number_of_seats = 0;
	  if(isset($fare_details['variation']))
	  {
		  foreach($fare_details['variation'] as $pv => $vv)
		  {
			  if(isset($fare_details['fare']))
			  {
				  $price = getPrice($pv, $fare_details);			  
			  }
			  if(get_seat_priceset($pv, $fare_details))
			  {
				  $number_of_seats = get_seat_priceset_count($pv, $fare_details, $record);				  
				  $number_of_seats_booked = get_seat_priceset_booked_count($pv, $booked_seats_pricesets[$v->tlc_id]);
				  $available = $number_of_seats-$number_of_seats_booked;
				  $price_set_title = get_price_set_title($pv, $fare_details['variation_titles']);
				if($price_set_title != '') 
					$price_set_title = ' ('.$price_set_title.')';
				  if($available > 0)
				  {
				  echo '<input id="how-other'.$pv_count.'" name="how" type="radio" onclick="displayblock(\'contactBoxMain_'.$index.'\', \''.$v->id.'\','.$pv.','.$v->tlc_id.', '.$available.', '.$available_seats[$v->tlc_id].');"> 	  <label for="how-other'.$pv_count++.'" class="side-label" title="'.$this->config->item('site_settings')->currency_symbol . ' '.$price.'">'.$available.$price_set_title.'</label>';
				  }
				  else
				  {
				  echo '<input id="how-other'.$pv_count.'" name="how" type="radio" disabled><label for="how-other'.$pv_count++.'" class="side-label" title="'.$this->config->item('site_settings')->currency_symbol . ' '.$price.'">'.$available.$price_set_title.'</label>'; 
				  }
				  $total_number_of_seats += $number_of_seats;
			  }
		  }
	  }
	  else
	  {
		 echo '<input id="how-other'.$pv_count.'" name="how" type="radio" disabled><label for="how-other'.$pv_count++.'" class="side-label" title="'.$this->config->item('site_settings')->currency_symbol . ' '.$price.'">0</label>'; 
	  }
	  ?>
      <h5 class="sa"><?php echo $available_seats[$v->tlc_id];?> <?php echo getPhrase('seats')?></h5>
      </td>
      <td valign="top">
      <div class="rate">
      <h5> 
       <i class="fa fa-star"></i> 
       <i class="fa fa-star"></i>
       <i class="fa fa-star"></i>
	   <i class="fa fa-star"></i>
 	   <i class="fa fa-star"></i>
       </h5>
        <p><?php echo getPhrase('Ratings')?> (5)</p>
        </div>
       </td>
    </tr>
	 <?php if(isset($v->has_connection) && $v->has_connection == 'yes' && isset($v->connection_end) && $v->connection_end == 'yes') {
		 ?>
		 <tr><td colspan="4" align="center">---------------</td></tr>
		 <?php
	 }?>
   
  </tbody>
  
</table>
<div id="contactBoxMain_<?php echo $index;?>"  class="premiumads">     
	Layout Here
</div>
</li>
 <?php }
 }?>