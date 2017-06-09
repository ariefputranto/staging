<link href="<?php echo base_url().$site_theme;?>/assets/system_design/css/chosen.min.css" rel="stylesheet" media="screen">
<?php
/* Prepare Start Location Options */
$start_locations = $this->base_model->getLocations("start");

$first_opt = (isset($this->phrases["select pick-up location"])) ? 
$this->phrases["select pick-up location"] : "Select Pick-up Location";

$start_location_opts = array('' => $first_opt);
foreach($start_locations as $rec)
$start_location_opts[$rec->id] = $rec->location;

/** Date & Time **/
/* Default Pick-up/Return Pick-up Dates */
$today = date('d-m-Y');
$pick_date_default = $today;
$return_pick_date_default = date('d-m-Y', strtotime($today . "+1 days")); //Adding a day to current Date.
/* Default Pick-up/Return Pick-up Times */
$pick_time_default = date('h : i : A', time() + 60*40); //Adding 40 Minutes to current Time.
$return_pick_time_default = $pick_time_default;

$record = array();
if(count($this->session->userdata('journey_booking_details')) > 0) {
$record = $this->session->userdata('journey_booking_details');
//print_r($record);
}
?> 

<?php
echo form_open('bookingseat/selectdate', "id='booking_form' name='booking_form' class=''");
$pick_point_view = '';
$pick_point = '';
$drop_point_view = '';
$drop_point = '';
$travel_location_id = '';
$pick_date = date('m/d/Y');
$return_date = '';
$journey_type = 'One-Way';
$adult = 1;
$child = $infant = 0;
if(isset($_POST['searchbutt']))
{
	$pick_point_view = isset($_POST['pick_point_view']) ? $_POST['pick_point_view'] : '';
	$pick_point = $_POST['pick_point'];
	$drop_point_view = isset($_POST['drop_point_view']) ? $_POST['drop_point_view'] : '';
	$drop_point = $_POST['drop_point'];
	$pick_date = $_POST['pick_date'];
	$return_date = $_POST['return_date'];
	$travel_location_id = $_POST['travel_location_id'];
	$journey_type = $_POST['journey_type'];
	$adult = $_POST['adult'];
	$child = $_POST['child'];
	$infant = $_POST['infant'];
	
}
elseif(count($record) > 0)
{
	$pick_point_view = $record['pick_point_view'];
	$pick_point = $record['pick_point'];
	$drop_point_view = $record['drop_point_view'];
	$drop_point = $record['drop_point'];
	$pick_date = $record['pick_date'];
	if(isset($record['return_date']))
	$return_date = $record['return_date'];
	$travel_location_id = $record['travel_location_id'];
	if(isset($record['journey_type']))
	$journey_type =  $record['journey_type'];

	if(isset($record['adult']))
	$adult = $record['adult'];
	if(isset($record['child']))
	$child = $record['child'];
	if(isset($record['infant']))
	$infant = $record['infant'];
}
?>
<div class="col-lg-12">

<div class="btn-group ro" role="group" aria-label="...">
 <input id="journey_type_return" name="journey_type" type="radio" onclick="enabledate(this.value)" value="Round-Trip" <?php if($journey_type == 'Round-Trip') echo ' checked';?>>
 <label for="journey_type_return" class="side-label">Return</label> 
 <input id="journey_type_oneway" name="journey_type" type="radio" onclick="enabledate(this.value)" value="One-Way" <?php if($journey_type == 'One-Way') echo ' checked';?>>
 <label for="journey_type_oneway" class="side-label">One Way</label> 
 </div>
 
<div class="search">
<span class="return"><i class="flaticon-arrows" onclick="reverse()" style="cursor:pointer;"></i></span>
<span class="sea">
<!--
<input type="search" name="pick_point_view" id="pick_point_view" placeholder="<?php if(isset($this->phrases["pick-up location"])) echo $this->phrases["pick-up location"]; else echo "Pick-up Location";?>" onclick="getLocations(this.value,'start')" onkeyup="getLocations(this.value,'start')" value="<?php echo $pick_point_view?>" autocomplete="off">
<input type="hidden" name="pick_point" id="pick_point" value="<?php echo $pick_point?>">
<div id="pick_point_list" class="search-auto"></div>
-->
<?php
/* Prepare Start Location Options */
$start_locations = $this->base_model->getLocations("start");
$start_location_opts = array('' => 'From');
foreach($start_locations as $rec)
$start_location_opts[$rec->id] = $rec->location;

$selected = set_value('pick_point', (isset($record['pick_point'])) ? $record['pick_point'] : '');
echo form_dropdown('pick_point', $start_location_opts, $selected, 'id="pick_point" class="chzn-select" tabindex="1" onchange="get_end_locations(this.value);"');
?>
<i class="flaticon-location"></i> <?php echo form_error('pick_point'); ?></span>


<span class="sea"> 
<!-- 
<input type="search" name="drop_point_view" id="drop_point_view" placeholder="<?php if(isset($this->phrases["drop-off location"])) echo $this->phrases["drop-off location"]; else echo "Drop-off Location";?>" onclick="get_end_locations(this.value,'end')" onkeyup="get_end_locations(this.value,'end')" value="<?php echo $drop_point_view?>" autocomplete="off">
<input type="hidden" name="drop_point" id="drop_point" value="<?php echo $drop_point?>">
<input type="hidden" name="travel_location_id" id="travel_location_id" value="<?php echo $travel_location_id?>">
<div id="drop_point_list" class="search-auto"></div>
-->
<?php
$end_location_opts = array('' => 'To'); 
echo form_dropdown('drop_point', $end_location_opts, $drop_point, 'id="drop_point" class="chzn-select" tabindex="2"').form_error('drop_point');
?>
<!--<select name="drop_point" id="drop_point" class="chzn-select chzn-done" tabindex="-1" style="display: none;">-->
<!--<option value="" selected="selected">To</option>-->
<!--</select>-->
<!--<div class="chzn-container chzn-container-single" style="width: 229px;" title="" id="drop_point_chzn">-->
<!--    <a href="javascript:void(0)" class="chzn-single" tabindex="-1">-->
<!--        <span>To</span>-->
<!--        <div></div></a>-->
<!--    <div class="chzn-drop"><div class="chzn-search"><input autocomplete="off" tabindex="1" type="text"></div>-->
<!--    <ul class="chzn-results"><li class="active-result result-selected" style="" data-option-array-index="0">To</li></ul></div></div>-->
</span>

<input type="hidden" name="pick_point_view" id="pick_point_view" value="<?php echo $pick_point_view;?>">
<input type="hidden" name="drop_point_view" id="drop_point_view" value="<?php echo $drop_point_view;?>">
<input type="hidden" name="travel_location_id" id="travel_location_id" value="<?php echo $travel_location_id;?>">


<span class="da"> <input type="text" placeholder="<?php if(isset($this->phrases["pick-up date"])) echo $this->phrases["pick-up date"]; else echo "Pick-up Date";?>" name="pick_date" id="pick_date" class="calendar mycalendar" value="<?php echo $pick_date?>" tabindex="3"  readonly > <i class="flaticon-calendar"></i><?php echo form_error('pick_date'); ?></span>


<span class="da"> <input type="text"  placeholder="<?php if(isset($this->phrases["Date of Return (Optional)"])) echo $this->phrases["Date of Return (Optional)"]; else echo "Date of Return (Optional)";?>" id="return_date" name="return_date" tabindex="4" class="calendar" value="<?php echo $return_date;?>" <?php if($journey_type == 'One-Way') echo ' disabled';?> readonly > <i class="flaticon-calendar"></i><?php echo form_error('return_date'); ?></span>

<input type="text" name="adult" id="adult" class="adu pair" placeholder="00" value="<?php echo $adult;?>">
<input type="text" name="child" id="child" class="adu boy" placeholder="00" value="<?php echo $child;?>">    
<input type="text" name="infant" id="infant" class="adu kid" placeholder="00" value="<?php echo $infant;?>"> 

<input type="submit" class="search-btn search-custom" value="<?php if(isset($this->phrases["SEARCHING"])) echo $this->phrases["SEARCHING"]; else echo "SEARCHING";?>" name="searchbutt">
</div>
</div>
</form>

<script>
function enabledate(val)
{
	if(val == 'One-Way') {
		document.getElementById('return_date').disabled = true;
		document.getElementById('return_date').value = '';
	}
	else
		document.getElementById('return_date').disabled = false;
}

function getLocations(str, type)
{
	$.ajax({
	  type: "post",
	  url: "<?php echo base_url();?>bookingseat/getLocations",
	  async: false,
	  data: { 
				str:str, 
				<?php echo $this->security->get_csrf_token_name();?>:
				"<?php echo $this->security->get_csrf_hash();?>",
				type:type
			},
	  cache: false, 
	  success: function(data) {
		$('#pick_point_list').empty();
		$('#drop_point_list').empty();
		$('#pick_point_list').append(data);
	  },
	  error: function(){
		alert('Ajax Error');
	  }
	});
}

function assign(label, id)
{
	$('#pick_point_view').val(label);
	$('#pick_point').val(id);
	$('#pick_point_list').empty();
	$('#drop_point_list').empty();
}

function assign2(label, id,travel_location_id)
{
	$('#drop_point_view').val(label);
	$('#drop_point').val(id);
	$('#travel_location_id').val(travel_location_id)
	$('#pick_point_list').empty();
	$('#drop_point_list').empty();
}

function get_end_locations(str, type)
{
	var start_id = $('#pick_point').val();
	if(start_id == '')
	{
		$('#drop_point_list').html('<?php if(isset($this->phrases["select pick-up location first"])) echo $this->phrases["select pick-up location first"]; else echo "Select Pick-up Location First";?>');
		return false;
	}
	$.ajax({
		  type: "post",
		  url: "<?php echo base_url();?>bookingseat/getEndLocations",
		  async: false,
		  data: { 
					start_id:start_id, 
					<?php echo $this->security->get_csrf_token_name();?>:
					"<?php echo $this->security->get_csrf_hash();?>",
					str:str
				},
		  cache: false, 
		  success: function(data) {
			$('#drop_point').empty();
			$('#drop_point').append(data);
		  },
		  error: function(){
			alert('Ajax Error');
		  }
		});
		$('#drop_point').trigger("liszt:updated");
}

function get_pickup_locations( location_id , pick_id)
{
	$.ajax({
		  type: "post",
		  url: "<?php echo base_url();?>bookingseat/get_pickup_locations",
		  async: false,
		  data: { 
					start_id:location_id,
					<?php echo $this->security->get_csrf_token_name();?>:
					"<?php echo $this->security->get_csrf_hash();?>"
				},
		  cache: false, 
		  success: function(data) {
			$('#pick_point').empty();
			$('#pick_point').append(data);
		  },
		  error: function(){
			alert('Ajax Error');
		  }
		});
		$('#pick_point').trigger("liszt:updated");
		
		get_dropoff_locations( location_id , pick_id);
}

function get_dropoff_locations( pickup_id , pick_id)
{
	$.ajax({
		  type: "post",
		  url: "<?php echo base_url();?>bookingseat/get_dropoff_locations",
		  async: false,
		  data: { 
					start_id:pickup_id, 
					loc_id:pick_id,
					<?php echo $this->security->get_csrf_token_name();?>:
					"<?php echo $this->security->get_csrf_hash();?>"
				},
		  cache: false, 
		  success: function(data) {
			$('#drop_point').empty();
			$('#drop_point').append(data);
		  },
		  error: function(){
			alert('Ajax Error');
		  }
		});
		$('#drop_point').trigger("liszt:updated");
}

/*
function reverse()
{
	var pick_point_view = $('#pick_point_view').val();
	var pick_point = $('#pick_point').val();
	
	var drop_point_view = $('#drop_point_view').val();
	var drop_point = $('#drop_point').val();
	var travel_location_id = $('#travel_location_id').val()
	if(pick_point != '' && drop_point != '')
	{
		$('#pick_point_view').val(drop_point_view);
		$('#pick_point').val(drop_point);
		
		$('#drop_point_view').val(pick_point_view);
		$('#drop_point').val(pick_point);
		
		$.ajax({
			  type: "post",
			  url: "<?php echo base_url();?>bookingseat/get_travel_location",
			  async: false,
			  data: { 
						pick_point:$('#pick_point').val(),
						drop_point:$('#drop_point').val(),
						<?php echo $this->security->get_csrf_token_name();?>:
						"<?php echo $this->security->get_csrf_hash();?>"
					},
			  cache: false, 
			  success: function(data) {
				$('#travel_location_id').val(data)
			  },
			  error: function(){
				alert('Ajax Error');
			  }
			});
	}
}
*/
function reverse()
{
	var pick_point = $('#pick_point').val();
	var drop_point = $('#drop_point').val();
	if(pick_point != '' && drop_point != ''){
	    get_pickup_locations(drop_point,pick_point);
	}
}
</script>