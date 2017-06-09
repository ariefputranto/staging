<div><?php echo $message;?></div>
<?php
$record = array();
if(count($this->session->userdata('journey_booking_details')) > 0) {
	$record = $this->session->userdata('journey_booking_details');
}
$journey_type = 'onward';
if(isset($record['is_return']) && $record['is_return'] == 'yes')
	$journey_type = 'return';
$drop_point = (isset($record[$journey_type]['drop_point'])) ? $record[$journey_type]['drop_point'] : 0;
$pick_point = isset($record[$journey_type]['pick_point']) ? $record[$journey_type]['pick_point'] : 0;
//neatPrint($available_seats);
?>
<?php if(count($record) > 0) { ?>
<div class="container">
<div class="row">
<div class="col-lg-12">
<div class="inner-hed">
<?php if($journey_type == 'onward') { ?>
<h3><?php echo $record['pick_point_view']?> <img src="<?php echo URL_SEAT_IMAGES;?>arrow.png"> <?php echo $record['drop_point_view']?></h3>
<?php } else {
	?>
<h3><?php echo $record['drop_point_view']?> <img src="<?php echo URL_SEAT_IMAGES;?>arrow.png"> <?php echo $record['pick_point_view']?></h3>	
	<?php
}?>
<p>( <?php echo getPhrase('Services')?>: <?php echo $total_vehicles?> , <?php echo getPhrase('Seats')?>: <?php echo $total_seats;?> <?php echo getPhrase('available')?>, <?php echo getPhrase('Journey Date')?>: <?php echo date('d/m/Y', strtotime($record['pick_date']))?> <?php if(isset($record['return_date']) && $record['return_date'] != '') { echo getPhrase('Date of Return'); echo date('d/m/Y', strtotime($record['return_date']));}?> ) </p>
</div>
</div>
</div>
</div>
<?php //$this->load->view($site_theme.'/site/common/filters', array('site_theme' => $site_theme, 'shuttle_types' => $shuttle_types));?>
<?php } 
if(!isset($record['pick_date'])) 
	$record['pick_date'] = date('m/d/Y');
?>
<div class="container">
<div class="row">
<div class="col-lg-12">

<div class="search-pera">
<ul>
<li> <a href="#"> <?php echo getPhrase('Shuttle Name')?> </a> </li>
<li> <a href="#"> <?php echo getPhrase('Stop')?> </a> </li>
<li> <a href="#"><?php echo getPhrase('Depart')?> </a> <a href="#"> <?php echo getPhrase('Arrive')?> </a>  <a href="#"><?php echo getPhrase('Duration')?> </a> </li>
<li><a href="#"><?php echo getPhrase('Seats')?></a></li>
<li><a href="#"><?php echo getPhrase('Ratings')?></a></li>
</ul>
</div>

<div class="bus-lists arlist">
<ul>
 <?php
 echo form_open('', 'name="bookingform" id="bookingform"');
 $index = $pv_count = $v_count = 0;
 $connection_status = 'end';
 //$has_connection = 'no';
 $token = '';
 $vehicle_ids = array();
 if($this->input->ip_address() == '183.82.114.32')
 {
 //echo '<pre>';print_r($booked_seats_pricesets);
 } 
 if(count($vehicles) > 0) {
	 foreach($vehicles as $v)
	 {
		 $v_id = isset($v->old_vehicle_id) ? $v->old_vehicle_id : $v->id; //If there is any replaceent it will take old_vehicle_id		 
		 $tlcid_date = $v->tlc_id.'_'.$v->start_date_new;
		 $border = '';
		 if($index == '0') {
			 //$border = 'border-left:1px solid red; border-top:1px solid red; border-right:1px solid red; border-bottom:1px solid red;';
			 if($v->to_loc_id != $pick_point && $connection_status == 'end') {
			     echo '<div class="arselect row">';
			 //$border = 'border-left:1px solid red; border-right:1px solid red; border-top:1px solid red;';
			}else if($v->to_loc_id == $drop_point) {
			    echo '<div class="arselect row">';
			 //$border = 'border-left:1px solid red; border-bottom:1px solid red; border-right:1px solid red; border-top:1px solid red;';
			}
		 }
		 
		 $features = $this->db->query('SELECT f.* FROM '.$this->db->dbprefix('features').' f INNER JOIN '.$this->db->dbprefix('vehicle_features').' vf ON f.id = vf.feature_id WHERE vf.vehicle_id = '.$v_id.' AND f.status = "Active"')->result();
	 ?>
	 
 <li class="vehicle bor arhover"  id="row_<?php echo $index?>">
 <table>
  <tbody>
    <?php 
	if($v->to_loc_id != $pick_point && $connection_status == 'end') {
		//if($v->to_loc_id != $drop_point) {
		 ?>
		 <!--<tr><td colspan="4" align="center">---------------</td></tr>-->
		 <!--<tr><td colspan="4" align="center"> <div class="cir"></div> </td></tr>-->
		 <?php
		 $connection_status = 'start';
		 $token = rand();
	 }?>
	<tr>
      <td class="b">
      <i class="flaticon-transport-3"></i> 
     <h5><?php echo $v->name?> <?php echo $v->model?></h5>
      <p class="shuttle_type"> <?php echo $v->category?><span class="badge"><a href="#" data-toggle="modal" data-target=".bs-example-modal-lg-<?php echo $v_id;?>"><i class="fa fa-map"></i> </a></span></p>
	  <p><small class="way"><?php echo $v->pick_point_name?> to <?php echo $v->drop_point_name?></small></p>
		<span class="coun"><?php echo $v->shuttle_no?></span>
      <!--
	  <div class="badge"> <a href="#"> <i class="fa fa-list"></i> </a></div>
       <div class="badge"><a href="#"><i class="fa fa-image"></i> </a></div>-->
        <?php if(!empty($features)) { ?>
		
		
		<?php if(!in_array($v_id, $vehicle_ids)) { ?>
		 <!-- Modal -->
		<div class="modal fade bs-example-modal-lg-<?php echo $v_id;?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
		<div class="modal-dialog modal-lg">
		<div class="modal-content route">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"> <i class="flaticon-close-1"></i> </span></button>
		<div class="modal-body clearfix">
		<?php foreach($features as $feature) {
			?>
			<div class="statio">
		<h5><?php echo $feature->features?></h5>
		</div>
			<?php
		}?>
		 </div>
			  
			</div>
		  </div>
		</div>
		<?php }} 
		if(!in_array($v_id, $vehicle_ids))
			 $vehicle_ids[] = $v_id;
		?>
      
      </td>
	  
	  <td valign="top" ><?php echo (isset($v->stop_over)) ? $v->stop_over : 0;?></td>



      <!-- <td valign="top"> <i class="flaticon-clock"></i>
      <h5><?php echo $v->pick_point_name?> - <?php echo ($v->start_time != '') ? $v->start_time : 'Not Available';?></h5>
	  <h5><?php echo $v->drop_point_name?> - <?php echo ($v->destination_time != '') ? $v->destination_time : 'Not Available';?></h5>
      
	  <?php 
	  $seats_available = $available_seats[$tlcid_date];
	  	++$index;
	  if($v->start_time != '') { 
	  $time = str_replace(' ','',$v->start_time);
	  $pick_date_time = $record['pick_date'].' '.$time;
	  
	  $time = str_replace(' ','',$v->destination_time);
	  $destination_time = date('m/d/Y',strtotime($record['pick_date'].' +'.$v->elapsed_days.' days')).' '.$time;
	  ?>
	  <p class="dur"> <label><?php echo getPhrase('Duration')?> </label>  <?php 
	  //echo timespan(strtotime($pick_date_time),strtotime($destination_time));
	  echo timespan_new(strtotime($pick_date_time),strtotime($destination_time), $v->start_time_zone, $v->destination_time_zone);?></p>
	  <?php } ?>
       </td> -->

       <td valign="top"> <i class="flaticon-clock"></i>
	      <h5><?php echo ($v->start_time != '') ? $v->start_time : 'Not Available';?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo ($v->destination_time != '') ? $v->destination_time : 'Not Available';
		  //echo '<br>'.$v->start_date_new;
		  ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	      
		  <?php 
		  $seats_available = $available_seats[$tlcid_date];
		  	//++$index;
		  if($v->start_time != '') { 
		  $time = str_replace(' ','',$v->start_time);
		  $pick_date_time = $record['pick_date'].' '.$time;
		  
		  $time = str_replace(' ','',$v->destination_time);
		  $destination_time = date('m/d/Y',strtotime($record['pick_date'].' +'.$v->elapsed_days.' days')).' '.$time;
		  ?>

		  <?php 
		  //echo timespan(strtotime($pick_date_time),strtotime($destination_time));
		  echo timespan_new(strtotime($pick_date_time),strtotime($destination_time), $v->start_time_zone, $v->destination_time_zone);
		  //echo $pick_date_time.'##'.$destination_time.'##'.$v->start_time_zone.'##'.$v->destination_time_zone;
		  ?>

		  <?php } ?>
		  </h5>
       </td>



      <td valign="top"> 
      <i class="flaticon-transport-4"></i>   
	  <?php
	  if( $v->special_fare == 'yes' )
	  {
		$specials = $this->db->query('SELECT * FROM '.$this->db->dbprefix('travel_location_costs_special').' WHERE tlc_id = '.$v->tlc_id.' AND "'.date('Y-m-d', strtotime($v->start_date_new)).'" BETWEEN special_start AND special_end AND status="active" ORDER BY updated DESC LIMIT 1')->result();
		//echo $this->db->last_query();
		if(empty($specials))
		{
		$fare_details = (isset($v->fare_details) && $v->fare_details != '') ? json_decode($v->fare_details) : array();	
		}
		else
		{
			$fare_details = (isset($specials[0]->fare_details_special) && $specials[0]->fare_details_special != '') ? json_decode($specials[0]->fare_details_special) : array();	
		}
	} else {
		$fare_details = (isset($v->fare_details) && $v->fare_details != '') ? json_decode($v->fare_details) : array();
	}
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
				  //$number_of_seats_booked = get_seat_priceset_booked_count($pv, $booked_seats_pricesets[$v->tlc_id]);
				  $number_of_seats_booked = get_seat_priceset_booked_count($pv, $booked_seats_pricesets[$v->start_date_new.'_'.$v->shuttle_no.'_'.$v->tlc_id]);
				  
				  $available = abs($number_of_seats-$number_of_seats_booked);
				  if($v->tlc_id == 59)
				  {
					  //print_r($booked_seats_pricesets);
					  //echo $number_of_seats.'##'.$number_of_seats_booked.'##'.$available;
					  //die();
				  }
				  $price_set_title = get_price_set_title($pv, $fare_details['variation_titles']);
				if($price_set_title != '') 
					$price_set_title = ' ('.$price_set_title.')';
				  if($available > 0)
				  {
					  $has_connection = 'no';
					  if($v->to_loc_id != $pick_point)
						  $has_connection = 'yes';
				  
				  echo '<input id="how-other'.$pv_count.'" name="how" type="radio" onclick="displayblock(\'contactBoxMain_'.$index.'\', \''.$v_id.'\','.$pv.','.$v->tlc_id.', '.$available.', '.$available_seats[$tlcid_date].', '.$v->from_loc_id.', '.$v->to_loc_id.', \''.$token.'\', \''.$v->start_date_new.'\', \''.$v->shuttle_no.'\', '.$index.', \''.$has_connection.'\');"> 	  <label for="how-other'.$pv_count++.'" class="side-label" title="'.$this->config->item('site_settings')->currency_symbol . ' '.$price.'">'.$available.$price_set_title.'</label>';
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
      <h5 class="sa"><?php echo $available_seats[$tlcid_date];?> <?php echo getPhrase('seats left')?></h5>
      <!--<p>Window 408</p>-->
      </td>
      <td valign="top">
      <div class="rate">
      <h5> 
       <aside class="rating" <?php if(!empty($v->user_rating_value)) echo 'data-score='.$v->user_rating_value;?>></aside>
       </h5>
       <?php if(!empty($v->user_rating_value)) { ?>
        <p><?php echo getPhrase('Ratings')?> <?php echo "(".$v->user_rating_value."/5)"; ?></p>
        <?php } ?>
        </div>
       </td>
       
	  <?php /* ?><td valign="top"> 
      <div class="view-seats">
      <i class="flaticon-money"></i> 
      <h5><?php echo $site_settings->currency_symbol . ' ' . $v->cost;?></h5>
      <div class="btn btn-default"  onclick="displayblock('contactBoxMain_<?php echo ++$index;?>');"><?php echo getPhrase('View Seats')?></div>
      </div>
      </td><?php */ ?>
    </tr>
	 <?php
	 if($v->to_loc_id == $drop_point)
	 {
		 ?>
		 <!--<tr><td colspan="4" align="center"><div class="cir"></div> </td></tr>-->
		 <!--<tr><td colspan="4" align="center">---------------</td></tr>-->
		 <?php
		 $connection_status = 'end';
	 }?>
   
  </tbody>
  
</table>
<div id="contactBoxMain_<?php echo $index;?>"  class="premiumads">     
	<span class="lh"><img src="<?php echo base_url().$site_theme.'/';?>assets/system_design/images/bus-parking-sign.gif"></span>
</div>
</li>
 <?php 
 if($index == '0') {}
 else if($index == count($vehicles)){echo '</div>';}
 else{	 
     if($v->to_loc_id != $pick_point && $connection_status == 'end') {
    	 //$border = 'border-left:1px solid red; border-right:1px solid red;';
     }
     if($v->to_loc_id == $drop_point)
        echo '</div><div class="arselect row">';
	 //$border = 'border-left:1px solid red; border-bottom:1px solid red; border-right:1px solid red;';
 }
 $v_count++;
 }} else {
	 $not_found = getPhrase('No services available');
	 echo '<li>'.$not_found.'</li>';
 }
 $selected_seats_total = 0;
 if(isset($record[$journey_type]['selected_seats_total']))
	 $selected_seats_total = $record[$journey_type]['selected_seats_total'];
 
$required_seats_total = $required_seats_adult = $required_seats_child = 0;
if(isset($record[$journey_type]['adult']))
{
$required_seats_total +=  $record[$journey_type]['adult'];
$required_seats_adult = $record[$journey_type]['adult'];
}
if(isset($record[$journey_type]['child']))
{
$required_seats_total +=  $record[$journey_type]['child'];
$required_seats_child = $record[$journey_type]['child'];
}
 ?>
 <input type="hidden" name="selected_seats_total" id="selected_seats_total" value="<?php echo $selected_seats_total;?>">
 <input type="hidden" name="selected_shuttles_total" id="selected_shuttles_total" value="0">
 <input type="hidden" name="selected_seats_adult" id="selected_seats_adult" value="0">
 <input type="hidden" name="selected_seats_child" id="selected_seats_child" value="0">
 
 <input type="hidden" name="required_seats_total" id="required_seats_total" value="<?php echo $required_seats_total;?>"> 
 <input type="hidden" name="required_seats_adult" id="required_seats_adult" value="<?php echo $required_seats_adult;?>">
 <input type="hidden" name="required_seats_child" id="required_seats_child" value="<?php echo $required_seats_child;?>">
 
 <input type="hidden" name="has_connection" id="has_connection" value="no">
 <input type="hidden" name="journey_type" id="journey_type" value="<?php echo $journey_type;?>">
 
 <input type="hidden" name="is_selection_finished" id="is_selection_finished" value="no">
 <input type="hidden" name="is_selection_finished_message" id="is_selection_finished_message" value="You need to select seats for booking">
 </form>
 
 </ul>
</div>
</div>
</div>
</div>


<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.min.js"></script>
<link href="<?php echo base_url().$site_theme.'/';?>assets/system_design/css/jquery.raty.css" rel="stylesheet" media="screen">
<script src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/jquery.raty.js"></script>
<script>
   $('aside.rating').raty({
   
      path: '<?php echo base_url().$site_theme.'/';?>assets/system_design/raty_images',
      score: function() {
        return $(this).attr('data-score');
      },
      readOnly: true

   });

</script>


<script type="text/javascript" src="<?php echo base_url().$site_theme.'/';?>assets/system_design/js/tabcontent.js"></script>
<script>
function validateform(id)
{	
	var selected_seats_total = $('#selected_seats_total').val();
	var selected_seats_adult = $('#selected_seats_adult').val();
	var selected_seats_child = $('#selected_seats_child').val();
	
	var required_seats_total = $('#required_seats_total').val();
	if( $('#has_connection').val() == 'yes' ) {
		required_seats_total = parseInt(required_seats_total) + parseInt(required_seats_total);
	}
	var required_seats_adult = $('#required_seats_adult').val();
	var required_seats_child = $('#required_seats_child').val();
	
	if(selected_seats_total == 0)
	{
		alert('<?php echo getPhrase('Please select at least one seat');?>');
		$('#selected_seats_total').focus();
		return false;
	}
	else if(selected_seats_total < required_seats_total)
	{
		alert('Please select '+required_seats_total+' seats');
		$('#selected_seats_total').focus();
		return false;
	}
	/*else if(selected_seats_adult < required_seats_adult)
	{
		alert('Please select '+required_seats_adult+' adult seats');
		$('#selected_seats_total').focus();
		return false;
	}
	else if(selected_seats_child < required_seats_child)
	{
		alert('Please select '+required_seats_child+' child seats');
		$('#selected_seats_total').focus();
		return false;
	}*/
	else if($('#has_connection').val() == 'yes' && $('#selected_shuttles_total').val() < 2)
	{
		alert('<?php echo getPhrase('Please select connected vehicle seat');?>');
		$('#selected_seats_total').focus();
		return false;
	}
	else if($('#has_connection').val() == 'yes' && $('#is_selection_finished').val() == 'no')
	{
		alert('<?php echo getPhrase('Please select required seats in each shuttle');?>');
		$('#selected_seats_total').focus();
		return false;
	}
	$('#bookingform').submit();
}
function setSelected(obj, vehicle_id, seatno,travel_location_id,pick_date, wl, price_set,shuttle_no, tlc_id, available_price_set_seats, seat_type, token, div_id, seat_display)
{
	var selected_state = $(obj).attr('class');
	$.ajax({
	  type: "post",
	  url: "<?php echo base_url();?>bookingseat/getFaredetails",
	  async: false,
	  data: { 
				vehicle_id:vehicle_id, 
				<?php echo $this->security->get_csrf_token_name();?>:
				"<?php echo $this->security->get_csrf_hash();?>",
				seatno:seatno,
				price_set:price_set,
				travel_location_id:travel_location_id,
				pick_date:pick_date,
				shuttle_no:shuttle_no,
				tlc_id:tlc_id,
				available_price_set_seats:available_price_set_seats,
				<?php if(isset($record['is_return']) && $record['is_return'] == 'yes') {
					?>
				is_return:"yes",
					<?php
				}?>
				selected_state:selected_state,
				wl:wl,
				seat_type:seat_type,
				token:token,
				div_id:div_id,
				seat_display:seat_display
			},
	  cache: false, 
	  success: function(data) {
		var parsed_data = $.parseJSON(data);
		var tlc_id = parsed_data.tlc_id;
		var token_get = parsed_data.token;
		var div_id = parsed_data.div_id;
		var curr = '<?php echo $this->config->item('site_settings')->currency_symbol.' ';?>';
		if(parsed_data.status == 1)
		{
			$('#message_'+div_id).html('');
			$('#selected_seats_'+tlc_id+'_'+token_get).html(parsed_data.selected_seats_no);			
			$('#basic_fare_'+tlc_id+'_'+token_get).html(curr + parsed_data.basic_fare);	
			$('#service_charge_'+tlc_id+'_'+token_get).html(curr + parsed_data.service_charge);			
			$('#total_fare_'+tlc_id+'_'+token_get).html(curr + parsed_data.total_fare);
			if(parsed_data.selected_state == 'available')
			{
				$('#anchor_'+tlc_id+'_'+parsed_data.seat).attr('class', 'selected');
				if($('#li_'+tlc_id+'_'+parsed_data.seat).attr('class') == "child")
				$('#li_'+tlc_id+'_'+parsed_data.seat).removeAttr('class').attr('class', 'child1');
			}
			else
			{
				
				$('#anchor_'+parsed_data.tlc_id+'_'+parsed_data.seat).attr('class', 'available');
				if($('#li_'+parsed_data.tlc_id+'_'+parsed_data.seat).attr('class') == "child1")
					$('#li_'+parsed_data.tlc_id+'_'+parsed_data.seat).removeAttr('class').attr('class', 'child');
			}
			$('#selected_seats_total').val(parsed_data.selected_seats_total);
			$('#selected_seats_adult').val(parsed_data.selected_seats_adult);
			$('#selected_seats_child').val(parsed_data.selected_seats_child);
			
			$('#has_connection').val(parsed_data.has_connection);
			$('#selected_shuttles_total').val(parsed_data.selected_shuttles_total);
			//console.log(parsed_data);
			var required_seats_total = $('#required_seats_total').val();
			var is_selection_finished = true;
			$.each(parsed_data.selected_shuttle_seats, function(index, item){
				if(item.total_seats != required_seats_total)
					is_selection_finished = false;
			});
			if(is_selection_finished == true)
			{
				$('#is_selection_finished').val('yes');
			}
			else
			{
				$('#is_selection_finished').val('no');
				$('#is_selection_finished_message').val('Please select '+required_seats_total+' from each shuttle');
			}
		}
		else
		{
			$('#message_'+div_id).html('<font color="red">'+parsed_data.message+'</font>');
		}
	  },
	  error: function(){
		alert('Ajax Error');
	  }
	});
}


function displayblock(id, vehicle_id, rid, tlc_id, available, total_number_of_seats, from_loc_id, to_loc_id, token, pick_date, shuttle_no, index, has_connection)
{
var ele=document.getElementById(id);
var num = $('.premiumads').length;
for(var i=1;i<=num;i++){
var hld='contactBoxMain_'+i;
if(document.getElementById(hld)&&document.getElementById(hld)!=ele)
document.getElementById(hld).style.display='none';
}

if(ele.style.display=="block"){
//ele.style.display="none";
}
else{ele.style.display="block";}

<?php if(count($this->session->userdata('journey_booking_details')) > 0) { ?>
//Fetch Layout and available seats to book
$.ajax({
  type: "post",
  url: "<?php echo base_url();?>bookingseat/fetchDetails",
  async: false,
  data: { 
			vehicle_id:vehicle_id, 
			rid:id,
			price_set:rid,
			tlc_id:tlc_id,
			available:available,
			<?php echo $this->security->get_csrf_token_name();?>:
			"<?php echo $this->security->get_csrf_hash();?>",
			pick_point:from_loc_id,
			drop_point:to_loc_id,
			pick_date:pick_date,
			adult:<?php echo $record['adult'];?>,
			child:<?php echo $record['child'];?>,
			total_number_of_seats:total_number_of_seats,	
			<?php if(isset($record['is_return']) && $record['is_return'] == 'yes') {
				?>
			is_return:"yes",
				<?php
			}?>
			infant:<?php echo $record['infant'];?>,
			shuttle_no:shuttle_no,
			token:token,
			index:index,
			has_connection:has_connection
		},
  cache: false, 
  success: function(data) {
	var parsed_data = $.parseJSON(data);
	$('#'+parsed_data['id']).html(parsed_data['data']);
	if(parsed_data['has_connection'] == 'yes')
	{
		//if(parsed_data['to_loc_id'] != parsed_data['drop_point'])
		//$('#row_'+parsed_data['div_id']).hide();
	}
		
  },
  error: function(){
	alert('Ajax Error');
  }
});
<?php } ?>
}
function closeblock(id){document.getElementById(id).style.display='none';}

<?php if(isset($record['pick_point']) && $record['pick_point'] != '' && isset($record['drop_point']) && $record['drop_point'] && isset($record['pick_date']) && $record['pick_date'] != '') { ?>
//setInterval(function(){ fetch_connection_routes(<?php echo $travel_location_id;?>) }, 10000);
<?php } ?>
</script>