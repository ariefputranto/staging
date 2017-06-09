<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Base_Model extends CI_Model  

{
	var $numrows;
	function __construct()

	{

		parent::__construct();

	}

	

	

	//General database operations

	function run_query( $query )

	{

              

		return($this->db->query( $query )->result());  

	}
	
	
	function runQuery( $query = null )
	{

		if($query != "") {

			$this->db->query( $query);

		}

	}


	function getMaxId($TableName,$ColName)

	{

		$query = $this->db->query("select max(".$ColName.") as Id from ".$this->db->dbprefix($TableName))->result();

		return $query[0]->Id;

	}



	function insert_operation( $inputdata, $table, $email = '' )

	{

		//echo $this->db->dbprefix($table);

		if($this->db->insert($this->db->dbprefix($table),$inputdata))

		return 1;

		else 

		return 0;

	}

	
	function getInsertId()
	{
		return $this->db->insert_id();
	}


	function insert_operation_id( $inputdata, $table, $email = '' )

	{

		$result  = $this->db->insert($this->db->dbprefix($table),$inputdata);

		return $this->db->insert_id();

	}

	

	

	function update_operation( $inputdata, $table, $where )

	{
		

		$result  = $this->db->update($this->db->dbprefix($table),$inputdata, $where);

		return $result;

	}

	

	function fetch_records_from( $table, $condition = '',$select = '*', $order_by = '',$order_type='ASC',$limit='' )

	{
		$this->db->select($select, FALSE);

		$this->db->from( $this->db->dbprefix( $table ) );

		if( !empty( $condition ) )
			$this->db->where( $condition );

		if( !empty( $order_by ) )
			$this->db->order_by( $order_by,$order_type );				

		if(!empty( $limit) )
			$this->db->limit( $limit );

		$result = $this->db->get();

		return $result->result();

	}

	

	function fetch_single_column_value($table, $column, $where)
	{

		$this->db->select($column,FALSE);

		$this->db->from( $this->db->dbprefix( $table ) );

		

		if( !empty( $where ) )

			$this->db->where( $where );

		$result_rs = $this->db->get();

		$result = $result_rs->result();

		if( count( $result ) > 0 )

			$ret = $result[0]->$column;

		else

			$ret = '-';

		return $ret;

	}



	function delete_record($table, $where)

	{

		$result = $this->db->delete( $table, $where );

		return $result;

	}


		
	function fetch_records_from_new( $table, $condition = '',$select = '*', $order_by = '', $like = '', $offset = '', $perpage = '' )
	{
		$this->db->start_cache();
		$this->db->select($select, FALSE);
		$this->db->from( $this->db->dbprefix( $table ) );
		if( !empty( $condition ) )
		$this->db->where( $condition );
		if( !empty( $like ) )
		$this->db->like( $like );
		//$this->db->where( array( 'is_deleted' => 'no' ) );
		if( !empty( $order_by ) )
		$this->db->order_by( $order_by );
		$this->db->stop_cache();

    $this->numrows = $this->db->count_all_results();

      if( $perpage != '' )
        $this->db->limit($perpage, $offset);
      $result = $this->db->get();
    $this->db->flush_cache();
		return $result->result();
	}



	/****** GET SUM OF COLUMNS ******/
	function getSumOfColumns($column_name,$table_name)
	{
	
		$this->db->select_sum($column_name);
		$result_rs = $this->db->get($this->db->dbprefix( $table_name ));

		$result = $result_rs->row();

		return $result->$column_name;
	
	}



 function isUserExists($user_id = null)
 {
	if($user_id > 0) {

		$user_rec = $this->db->get_where('users', array('id' => $user_id))->row();
		if(count($user_rec) > 0)
			return true;
		else return false;

	}

	return false;
 }


 function getUsers($group = '', $status = '')
 {

	if(!empty($status) && $status == 0 || $status == 1)
		$status = ' AND u.active='.$status;

	return $this->db->query("select u.*, ug.group_id from ".DBPREFIX."users u, ".DBPREFIX."users_groups ug where u.id=ug.user_id ".$status." and ug.group_id IN (".$group.") ORDER BY u.id DESC")->result();

 }
 
 
 /* Get Location (Start|End) */
 function getLocations($location_visibility_type = 'both')
 {
	$locations = array();
	if(in_array($location_visibility_type, array('start', 'end', 'both'))) {
		$locations = $this->db->query("SELECT * FROM ".DBPREFIX."locations WHERE (location_visibility_type='both' OR location_visibility_type='".$location_visibility_type."') AND status='Active' ORDER BY location")->result();
	}
	return $locations;

 }
 
	/****** Get Words of Selected Language ******/
	public function getLanguageWords($language_id = 1)
	{	
		$lang_words = array();

		if($language_id != "" && $language_id > 0) {

			$query = "SELECT ml . * , l . * , p . text AS phrase  
						FROM ".$this->db->dbprefix('multi_lang')." ml, 
						".$this->db->dbprefix('languages')." l, 
						".$this->db->dbprefix('phrases')." p 
						WHERE l.id = ml.lang_id
						AND l.status = 'Active' 
						AND p.id = ml.phrase_id  
						AND ml.lang_id =".$language_id;

			$lang_words = $this->db->query($query)->result();			

		}

		if(count($lang_words) == 0) {

			$lang_words = $this->db->query("SELECT text AS phrase, text AS text, 'English' AS lang_name, 1 AS lang_id FROM ".$this->db->dbprefix('phrases')." ")->result();

		}

		return $lang_words;

	}
	
	function fetch_records_from_query_object($query, $offset = '', $perpage = '')
	{
		$resultset = $this->db->query( $query );
		$this->numrows = $resultset->num_rows();
		if( $perpage != '' )
			$query = $query . ' limit ' . $offset . ',' . $perpage;
		$resultsetlimit = $this->db->query( $query );
		return $resultsetlimit->result();
	}
	
	function getvehicles($category = '', $start_id = '', $result_no = 0)
	{
		$str = '';
		if(!empty($category))
			$str .= ' AND tl.from_loc_id = ' . $start_id;
		if(!empty($start_id))
			$str .= ' AND v.category_id = ' . $category;
		
		$query = "SELECT tlc.cost, v.*, vc.category 
				FROM digi_travel_location_costs tlc, 
				digi_vehicle v, digi_vehicle_categories vc, digi_travel_locations tl  
				WHERE v.id=tlc.vehicle_id 
				AND vc.id=v.category_id AND tlc.status='Active' 
				AND v.status='Active' AND vc.status='Active' 
				AND v.total_vehicles > 
				(SELECT COUNT(*) FROM digi_bookings b 
				WHERE b.vehicle_selected=v.id) $str
				GROUP BY v.id ORDER BY tlc.cost";
		$vehicles_rs = $this->db->query($query)->result();
		$vehicles_count = count($vehicles_rs);
		
		$query2 = "SELECT loc.location, tlc.cost, v.*, vc.category 
				FROM digi_travel_location_costs tlc, 
				digi_vehicle v, digi_vehicle_categories vc, digi_travel_locations tl, digi_locations loc  
				WHERE v.id=tlc.vehicle_id AND loc.id = tl.from_loc_id
				AND vc.id=v.category_id AND tlc.status='Active' 
				AND v.status='Active' AND vc.status='Active' 
				AND v.total_vehicles > 
				(SELECT COUNT(*) FROM digi_bookings b 
				WHERE b.vehicle_selected=v.id) $str
				GROUP BY v.id ORDER BY tlc.cost LIMIT $result_no,5";		
		
		$vehicles = $this->db->query($query2);
		//print_r($vehicles->result());
		return json_encode(array('totalcount' => $vehicles_count, 'records' => $vehicles->result()));
	}
	
	function getLocaitons($str, $type = 'start')
	{
		$query = '';
		if($type == 'start')
		{
			if($str != '')
			$query = "SELECT * FROM digi_locations WHERE location LIKE '%".$str."%' ORDER BY location ASC LIMIT 15";
			else
			$query = "SELECT * FROM digi_locations ORDER BY location ASC LIMIT 15";
		}
		return $this->db->query($query)->result();
	}
	
	//Getting vehicles
	function get_vehicles_seats($options = array())
	{
		//date_default_timezone_set('UTC');
		$transition_time = $this->config->item('site_settings')->transition_time;
		$transition_time_units = $this->config->item('site_settings')->transition_time_units;
		
		$display_after = $this->config->item('site_settings')->display_after;
		$display_after_units = $this->config->item('site_settings')->display_after_units;
		
		$pick_date = (isset($options['pick_date'])) ? $options['pick_date'] : '';
		$pick_point = (isset($options['pick_point'])) ? $options['pick_point'] : '0';
		$drop_point = (isset($options['drop_point'])) ? $options['drop_point'] : '0';
		$travel_location_id = (isset($options['travel_location_id'])) ? $options['travel_location_id'] : 0;
		$tlc_id = (isset($options['tlc_id'])) ? $options['tlc_id'] : 0;
		$vehicle_id = (isset($options['vehicle_id'])) ? $options['vehicle_id'] : 0;
		$vehicle_id_str = '';
		$token = (isset($options['token'])) ? $options['token'] : '';
		
		if($vehicle_id != 0) 
			$vehicle_id_str = ' AND v.id = '.$vehicle_id;
		
		$records = $tlc_ids = $temp_records = $temp_records_ids = array();
		
		//New Code
		$locaiton_details = $this->db->query('SELECT l.* FROM '.$this->db->dbprefix('travel_locations').' tl INNER JOIN '.$this->db->dbprefix('locations').' l ON l.id = tl.from_loc_id WHERE tl.`travel_location_id` = '.$travel_location_id)->result();
		$newdate = date('H:i');
		$newdate2 = date('m/d/Y');
		//echo $newdate.'##';
		$timezone = '';
		if(!empty($locaiton_details))
		{
			if($locaiton_details[0]->location_time_zone != '')
			{
				$timezone = $locaiton_details[0]->location_time_zone;
				$gmdate = gmdate('H:i');
				$newdate = date('H:i', strtotime("$timezone hour", strtotime($gmdate)));
				$newdate2 = date('m/d/Y', strtotime("$timezone hour", strtotime($gmdate)));
			}
		}
		
		$transition_time_new = $display_after;
		if($display_after_units == 'hours')
			$transition_time_new = $display_after * 60;
		if($timezone != '')
		{
			
			$gmdate = gmdate('H:i');
			$timezone_parts = explode(':', $timezone);
			$hours = (isset($timezone_parts[0])) ? $timezone_parts[0] : 0;
			$minutes = (isset($timezone_parts[1])) ? $timezone_parts[1] : 0;
			//echo $hours.'@@'.$minutes.'@@'.$gmdate;die();
			$newdate = date('H:i', strtotime("$hours hours", strtotime($gmdate)));
			$newdate = date('H:i', strtotime("$minutes minutes", strtotime($newdate)));
			
			$newdate2 = date('m/d/Y', strtotime("$hours hours", strtotime($gmdate)));
			$newdate2 = date('m/d/Y', strtotime("$minutes minutes", strtotime($newdate2)));
			//echo $timezone.'@@'.$gmdate.'@@'.$newdate.'##';die();
			//echo "$timezone hour";
			$newdate = date('H : i', strtotime("+$transition_time_new minute",strtotime($newdate)));
			//$newdate = date('H : i', strtotime("-$transition_time_new minute",strtotime($newdate)));
			
			$newdate2 = date('m/d/Y', strtotime("+$transition_time_new minute",strtotime($newdate2)));
			//$newdate2 = date('m/d/Y', strtotime("-$transition_time_new minute",strtotime($newdate2)));
		}
		
		$gmdate = gmdate('m/d/Y H:i');
		$today = date('m/d/Y', strtotime($gmdate));
		$today_time = date('H : i', strtotime($gmdate));
		$today2 = date('Y-m-d',strtotime($pick_date));
		if($timezone != '')
		{
			$timezone_parts = explode(':', $timezone);
			$hours = (isset($timezone_parts[0])) ? $timezone_parts[0] : 0;
			$minutes = (isset($timezone_parts[1])) ? $timezone_parts[1] : 0;
			$today = date('m/d/Y H:i', strtotime("$hours hours", strtotime($gmdate)));
			$today = date('m/d/Y H:i', strtotime("$minutes minutes", strtotime($today)));	
			$today_time = date('H : i', strtotime($today));
			$today = date('m/d/Y', strtotime($today));
			
			//$today = date('m/d/Y', strtotime("$timezone hours", strtotime($gmdate)));
			
			//$today2 = date('Y-m-d', strtotime("$timezone hour", strtotime($gmdate)));
		}
		//echo '##'.$timezone.'$$'.$today.'##'.$today_time;die();
		$time_str = '';
		//echo $timezone.'@@'.$today.'##'.$pick_date.'@@'.$gmdate.'%%'.$newdate.'$$'.$newdate2;die();
		if($today == $pick_date)
		{
			if($newdate2 != $pick_date)
				$newdate = '23:59';
			//$time_str = " AND tlc.start_time > '".$newdate."'";
		}
		$travel_loc = '';
		if($token != '')
		{
		if($tlc_id != '')
			$travel_loc = ' AND tlc.id = '.$tlc_id;
		}
		
		$query = "SELECT fromloc.location pick_point_name,toloc.location drop_point_name, tlc.id as tlc_id, tlc.cost, tlc.fare_details, tlc.start_time, fromloc.location_time_zone start_time_zone, tlc.destination_time, toloc.location_time_zone destination_time_zone, tlc.front_display_stop_at, tlc.elapsed_days,tlc.number_of_pricevariations, tlc.shuttle_no, tlc.season_start,tlc.season_end, tlc.season_type, tlc.number_of_transition_points_valid, tlc.user_rating_value,tlc.special_fare, tlc.special_start, tlc.special_end, tlc.fare_details_special, tlc.stop_over, v.*, vc.category,tl.* FROM digi_travel_location_costs tlc
		INNER JOIN digi_vehicle v ON v.id=tlc.vehicle_id
		INNER JOIN digi_vehicle_categories vc ON vc.id = v.category_id
		INNER JOIN digi_travel_locations tl ON tl.travel_location_id = tlc.travel_location_id
		INNER JOIN digi_locations fromloc ON fromloc.id = tl.from_loc_id
		INNER JOIN digi_locations toloc ON toloc.id = tl.to_loc_id
		WHERE tl.from_loc_id=$pick_point AND tl.to_loc_id=$drop_point $travel_loc AND v.status='Active' AND vc.status='Active' AND tlc.status='Active' $time_str  AND '".$today2."' BETWEEN season_start AND season_end $vehicle_id_str GROUP BY shuttle_no ORDER BY tlc.start_time ASC";
		//echo $query;die();
		$result = $this->db->query($query)->result();
		$this->num_rows = $this->db->affected_rows();
		if(!empty($result))
		{
			foreach($result as $r)
			{
				if(!in_array($r->tlc_id, $tlc_ids))
				{
					//Check if there is any vehicle replacement
					$query = "SELECT v.*,vc.category FROM digi_travel_location_costs_drivers tlcd INNER JOIN digi_vehicle v ON v.id = tlcd.vehicle_id INNER JOIN digi_vehicle_categories vc ON v.category_id = vc.id WHERE tlc_id = ".$r->tlc_id." AND ('".date('Y-m-d', strtotime($pick_date))."' BETWEEN special_start AND special_end) ORDER BY special_start DESC LIMIT 1";
					$check = $this->db->query($query)->result();
					if(!empty($check))
					{
						$veh = $check[0];
						$r->old_vehicle_id = $r->id;
						$r->id = $veh->id;
						$r->category_id = $veh->category_id;
						$r->model = $veh->model;
						$r->name = $veh->name;
						$r->number_plate = $veh->number_plate;
						$r->description = $veh->description;
						$r->passenger_capacity = $veh->passenger_capacity;
						$r->large_luggage_capacity = $veh->large_luggage_capacity;
						$r->small_luggage_capacity = $veh->small_luggage_capacity;
						$r->fuel_type = $veh->fuel_type;
						$r->total_vehicles = $veh->total_vehicles;
						
						$r->seat_rows = $veh->seat_rows;
						$r->seat_columns = $veh->seat_columns;
						$r->seats_empty = $veh->seats_empty;
						$r->child_seats = $veh->child_seats;
						$r->seats_child = $veh->seats_child;
						$r->availability = $veh->availability;
						$r->image = $veh->image;
						$r->base_fare = $veh->base_fare;
						$r->cost_per_km = $veh->cost_per_km;
						$r->cost_per_minute = $veh->cost_per_minute;
						$r->status = $veh->status;
						$r->has_driver_seat = $veh->has_driver_seat;
						
						$r->category = $veh->category;
					}				
					$r->start_date_new = $pick_date;
					
					//New code to stop vehicle display at front end
					if($r->front_display_stop_at == '' || $r->front_display_stop_at == NULL)
					{
						$start_time = str_replace(' ', '', $r->start_time);						
						$r->front_display_stop_at = date('H : i', strtotime("-30 minutes", strtotime($start_time)));
					}
					//echo $r->front_display_stop_at.'##'.$today_time.'##'.$r->start_time.'$$'.$today.'##'.$pick_date;die();					
					if($today == $pick_date)
					{
						if($today_time <= $r->front_display_stop_at)
						{
						$records[] = $r;	
						}
					}
					else
					{
					$records[] = $r;
					}
					//$records[] = $r;
					$tlc_ids[] = $r->tlc_id;
				}
			}
		}
		//neatPrint($records);
		if($travel_location_id != '')
			{
				$query2 = 'SELECT ptl.* FROM '.$this->db->dbprefix('possible_travel_locations').' ptl INNER JOIN '.$this->db->dbprefix('travel_locations').' tl ON ptl.`travel_location_id` = tl.`travel_location_id` WHERE tl.`travel_location_id` = '.$travel_location_id;
				//echo $query2;die();
				$transitions2 = $this->db->query($query2)->result();
				//neatPrint($transitions2);
				if(!empty($transitions2))
				{
					foreach($transitions2 as $r)
					{
						$pick_point_new = $r->from_location;
						$drop_point_new = $r->to_location;
						$query = "SELECT fromloc.location pick_point_name,toloc.location drop_point_name, tlc.id as tlc_id, tlc.cost, tlc.fare_details, tlc.start_time, fromloc.location_time_zone start_time_zone, tlc.destination_time,  tlc.front_display_stop_at, toloc.location_time_zone destination_time_zone, tlc.elapsed_days,tlc.number_of_pricevariations, tlc.shuttle_no, tlc.season_start,tlc.season_end, tlc.season_type, tlc.number_of_transition_points_valid, tlc.special_fare, tlc.special_start, tlc.special_end, tlc.fare_details_special, tlc.stop_over, v.*, vc.category,tl.* FROM digi_travel_location_costs tlc
	INNER JOIN digi_vehicle v ON v.id=tlc.vehicle_id
	INNER JOIN digi_vehicle_categories vc ON vc.id = v.category_id
	INNER JOIN digi_travel_locations tl ON tl.travel_location_id = tlc.travel_location_id
	INNER JOIN digi_locations fromloc ON fromloc.id = tl.from_loc_id
	INNER JOIN digi_locations toloc ON toloc.id = tl.to_loc_id
	WHERE tl.from_loc_id=$pick_point_new AND tl.to_loc_id=$drop_point_new AND v.status='Active' AND vc.status='Active' AND tlc.status='Active' $time_str AND '".$today2."' BETWEEN season_start AND season_end $vehicle_id_str GROUP BY shuttle_no ORDER BY tlc.start_time ASC";
//echo $query;die();	
						$result = $this->db->query($query)->result();
						
						if(!empty($result))
						{
							foreach($result as $r)
							{
								if(!in_array($r->tlc_id, $tlc_ids))
								{
									$temp_records[$r->travel_location_id][] = $r;
									//$records[] = $r;
									$temp_records_ids[$r->travel_location_id][] = $r->tlc_id;
									$tlc_ids[] = $r->tlc_id;
								}
							}
						}
					}
				}
				//neatPrint($temp_records_ids);
				$combinations = array();
				if(!empty($temp_records_ids))
				{
					foreach($temp_records_ids as $key_1 => $val_1)
					{
						foreach($temp_records_ids as $key_2 => $val_2)
						{
							if($key_1 != $key_2)
							$combinations[] = combinations(array($val_1,$val_2));
						}
					}
				}
//neatPrint($combinations);
				if(!empty($combinations))
				{
					foreach($combinations as $key => $val)
					{
						foreach($val as $key_1 => $val_2)
						{
							$temp = array();
							$record1 = find_record_new($val_2[0], $temp_records);
							$record1->has_connection = 'yes';
							$record1->connection_start = 'yes';
							$record1->start_date_new = $pick_date;
							
							//Check if there is any vehicle replacement
							$query = "SELECT v.*,vc.category FROM digi_travel_location_costs_drivers tlcd INNER JOIN digi_vehicle v ON v.id = tlcd.vehicle_id INNER JOIN digi_vehicle_categories vc ON v.category_id = vc.id WHERE tlc_id = ".$record1->tlc_id." AND ('".date('Y-m-d', strtotime($pick_date))."' BETWEEN special_start AND special_end) ORDER BY special_start DESC LIMIT 1";
							$check = $this->db->query($query)->result();
							if(!empty($check))
							{
								$veh = $check[0];
								$record1->old_vehicle_id = $record1->id;
								$record1->id = $veh->id;
								$record1->category_id = $veh->category_id;
								$record1->model = $veh->model;
								$record1->name = $veh->name;
								$record1->number_plate = $veh->number_plate;
								$record1->description = $veh->description;
								$record1->passenger_capacity = $veh->passenger_capacity;
								$record1->large_luggage_capacity = $veh->large_luggage_capacity;
								$record1->small_luggage_capacity = $veh->small_luggage_capacity;
								$record1->fuel_type = $veh->fuel_type;
								$record1->total_vehicles = $veh->total_vehicles;
								
								$record1->seat_rows = $veh->seat_rows;
								$record1->seat_columns = $veh->seat_columns;
								$record1->seats_empty = $veh->seats_empty;
								$record1->child_seats = $veh->child_seats;
								$record1->seats_child = $veh->seats_child;
								$record1->availability = $veh->availability;
								$record1->image = $veh->image;
								$record1->base_fare = $veh->base_fare;
								$record1->cost_per_km = $veh->cost_per_km;
								$record1->cost_per_minute = $veh->cost_per_minute;
								$record1->status = $veh->status;
								$record1->has_driver_seat = $veh->has_driver_seat;
								
								$record1->category = $veh->category;
							}
							
							//New code to stop display at front end
							if($record1->front_display_stop_at == '' || $record1->front_display_stop_at == NULL)
							{
								$start_time = str_replace(' ', '', $record1->start_time);						
								$record1->front_display_stop_at = date('H : i', strtotime("-30 minutes", strtotime($start_time)));
							}
							if($today == $pick_date)
							{
								if($today_time <= $record1->front_display_stop_at)
								{
								$temp[] = $record1;	
								}
							}
							else
							{
							$temp[] = $record1;
							}
							
							//$temp[] = $record1;
							//neatPrint($record1);
							$record2 = find_record_new($val_2[1], $temp_records);
							
							$record1_destination_time = str_replace(' ','',$record1->destination_time);
							$record2_start_time = str_replace(' ','',$record2->start_time);
							$diff = strtotime($record2_start_time) - strtotime($record1_destination_time);
							$diff_mins = round(abs($diff) / 60,2);
							
							$transition_time = $this->config->item('site_settings')->transition_time;
							if($transition_time_units == 'hours')
								$transition_time = $transition_time * 60;
							if($record1->tlc_id == 53 && $record2->tlc_id == 67)
							{
								//echo $diff_mins.'##'.$transition_time;die();
							}
							if(($record1->from_loc_id == $pick_point) && ($record1->to_loc_id == $record2->from_loc_id) && ($record2->to_loc_id == $drop_point) && ($record1->start_time < $record2->start_time) && ($diff_mins > $transition_time) && ($record1->shuttle_no != $record2->shuttle_no))
							{
								if($record1->elapsed_days > 0)
								{
									$elapsed_days = $record1->elapsed_days;
									$record2->start_date_new = date('m/d/Y', strtotime("+$elapsed_days day", strtotime($pick_date)));
								}
								else
								{
									$record2->start_date_new = $pick_date;
								}
								
								//Check if there is any vehicle replacement
								$query = "SELECT v.*,vc.category FROM digi_travel_location_costs_drivers tlcd INNER JOIN digi_vehicle v ON v.id = tlcd.vehicle_id INNER JOIN digi_vehicle_categories vc ON v.category_id = vc.id WHERE tlc_id = ".$record2->tlc_id." AND ('".date('Y-m-d', strtotime($record2->start_date_new))."' BETWEEN special_start AND special_end) ORDER BY special_start DESC LIMIT 1";
								$check = $this->db->query($query)->result();
								if(!empty($check))
								{
									$veh = $check[0];
									$record2->old_vehicle_id = $record2->id;
									$record2->id = $veh->id;
									$record2->category_id = $veh->category_id;
									$record2->model = $veh->model;
									$record2->name = $veh->name;
									$record2->number_plate = $veh->number_plate;
									$record2->description = $veh->description;
									$record2->passenger_capacity = $veh->passenger_capacity;
									$record2->large_luggage_capacity = $veh->large_luggage_capacity;
									$record2->small_luggage_capacity = $veh->small_luggage_capacity;
									$record2->fuel_type = $veh->fuel_type;
									$record2->total_vehicles = $veh->total_vehicles;
									
									$record2->seat_rows = $veh->seat_rows;
									$record2->seat_columns = $veh->seat_columns;
									$record2->seats_empty = $veh->seats_empty;
									$record2->child_seats = $veh->child_seats;
									$record2->seats_child = $veh->seats_child;
									$record2->availability = $veh->availability;
									$record2->image = $veh->image;
									$record2->base_fare = $veh->base_fare;
									$record2->cost_per_km = $veh->cost_per_km;
									$record2->cost_per_minute = $veh->cost_per_minute;
									$record2->status = $veh->status;
									$record2->has_driver_seat = $veh->has_driver_seat;
									
									$record2->category = $veh->category;
								}
								$record2->has_connection = 'yes';
								$record2->connection_end = 'yes';
								
								//New code to stop display at front end
								if($record2->front_display_stop_at == '' || $record2->front_display_stop_at == NULL)
								{
									$start_time = str_replace(' ', '', $record2->start_time);						
									$record2->front_display_stop_at = date('H : i', strtotime("-30 minutes", strtotime($start_time)));
								}
								if($today == $record2->start_date_new)
								{
									if($today_time <= $record2->front_display_stop_at)
									{
									$temp[] = $record2;	
									}
								}
								else
								{
								$temp[] = $record2;
								}
								
								//$temp[] = $record2;
							}
							
							if(count($temp) == 2) //If it is 2 then connection is finished
							{
								$this->num_rows = $this->num_rows+1;
								foreach($temp as $rec)
								{
									$tmp_rec = (array)$rec;
									$records[] = (Object)$tmp_rec;
								}
								
							}							
						}
					}
				}		
			}
		//neatPrint($records);
		return $records;
	}
	
	function booked_seats_count($pick_date, $tlc_ids, $is_waiting_list = 'No', $shuttle_no = '')
	{
		$shuttle_no_str = '';
		if($shuttle_no != '')
		{
			$shuttle_no_str = ' AND bp.shuttle_no = "'.$shuttle_no.'"';
		}
		$query = 'SELECT COUNT(*) AS reserved FROM digi_bookings b
INNER JOIN `digi_bookings_passengers` bp ON bp.booking_id = b.id
WHERE b.booking_status != "Cancelled" AND pick_date = "'.date('Y-m-d', strtotime($pick_date)).'" AND travel_location_cost_id IN('.implode(',', $tlc_ids).') AND bp.is_waiting_list = "'.$is_waiting_list.'"'.$shuttle_no_str;
		//$query = 'SELECT IFNULL(SUM(seat_reserve),0) as reserved FROM digi_bookings WHERE pick_date = "'.date('Y-m-d', strtotime($pick_date)).'" AND travel_location_cost_id IN('.implode(',', $tlc_ids).') AND is_waiting_list = "'.$is_waiting_list.'"'.$shuttle_no_str;
		return $this->db->query($query)->result();
	}
	
	
	function getViapoints($options = array())
	{
		$str = '1=1';
		$order_by = 'location ASC';
		if(count($options) > 0)
		{
			if(isset($options['condition']))
			{
				foreach($options['condition'] as $key => $val)
				{
					$str .= ' AND '.$key . '="' . $val . '"';
				}
			}
			if(isset($options['order_by']))
			{
				$order_by = $options['order_by'];
			}
		}
		$query = "select id, location from ".DBPREFIX."locations where $str order by $order_by";
		return $this->db->query($query)->result();
	}
	
	function getViarecord($options = array())
	{
		$str = '1=1';
		$order_by = 'record_order ASC';
		if(count($options) > 0)
		{
			if(isset($options['condition']))
			{
				foreach($options['condition'] as $key => $val)
				{
					$str .= ' AND '.$key . '="' . $val . '"';
				}
			}
			if(isset($options['order_by']))
			{
				$order_by = $options['order_by'];
			}
		}
		$query = "select travel_location_id,location_id,is_boarding_point,arrival_time,type from ".DBPREFIX."via_locations where $str order by $order_by";
		return $this->db->query($query)->result();
	}
	
	function getViarecords($options = array())
	{
		$str = '1=1';
		$order_by = '';
		if(count($options) > 0)
		{
			if(isset($options['condition']))
			{
				foreach($options['condition'] as $key => $val)
				{
					$str .= ' AND '.$key . '="' . $val . '"';
				}
			}
			if(isset($options['order_by']))
			{
				$order_by = ' ORDER BY '.$options['order_by'];
			}
		}
		$query = 'SELECT location,via.* FROM '.DBPREFIX.'locations loc INNER JOIN '.DBPREFIX.'via_locations via ON via.location_id = loc.id WHERE '.$str.' '.$order_by;
		return $this->db->query($query)->result();
	}
	
	function getFromLocaitons($status = '')
	{
		$condition = '';
		if($status != '') $condition .= 'status = \''.$status.'\''; 
		$query = "select id, location from ".DBPREFIX."locations where $condition order by location ASC";
		return $this->db->query($query)->result();
	}
	
	/**
	 * This function get the fare details of the selected vehicle
	 *
	 * @param	int
	 * @param	int
	 * @param 	string
	 * @param 	string
	 * @return	mixed
	 */
	function getFaredetails($tlc_id, $selected_state = 'available')
	{
		if($selected_state == 'selected')
		{
			$query = "SELECT tlc.start_time,tlc.start_time_zone,tlc.destination_time,tlc.destination_time_zone,tlc.elapsed_days,tlc.cost, tlc.fare_details, tlc.service_tax,tlc.service_tax_type,tlc.special_fare, tlc.special_start, tlc.special_end, tlc.fare_details_special, v.*, vc.category,tl.*,fromloc.location pick_point_name,toloc.location drop_point_name,tlc.id tlc_id  FROM digi_travel_location_costs tlc 
			INNER JOIN digi_vehicle v ON v.id = tlc.vehicle_id 
			INNER JOIN digi_vehicle_categories vc ON vc.id = v.category_id 
			INNER JOIN digi_travel_locations tl ON tl.travel_location_id = tlc.travel_location_id 
			INNER JOIN digi_locations fromloc ON fromloc.id = tl.from_loc_id
			INNER JOIN digi_locations toloc ON toloc.id = tl.to_loc_id
			WHERE tlc.status='Active' AND v.status='Active' AND vc.status='Active' AND tlc.id = ".$tlc_id." ORDER BY tlc.start_time LIMIT 1";
		}
		else
		{
			$query = "SELECT tlc.start_time,tlc.start_time_zone,tlc.destination_time,tlc.destination_time_zone,tlc.elapsed_days,tlc.cost, tlc.fare_details, tlc.service_tax,tlc.service_tax_type, tlc.special_fare, tlc.special_start, tlc.special_end, tlc.fare_details_special, v.*, vc.category,tl.*,fromloc.location pick_point_name,toloc.location drop_point_name,tlc.id tlc_id  FROM digi_travel_location_costs tlc 
			INNER JOIN digi_vehicle v ON v.id = tlc.vehicle_id
			INNER JOIN digi_vehicle_categories vc ON vc.id = v.category_id
			INNER JOIN digi_travel_locations tl ON tl.travel_location_id = tlc.travel_location_id 
			INNER JOIN digi_locations fromloc ON fromloc.id = tl.from_loc_id
			INNER JOIN digi_locations toloc ON toloc.id = tl.to_loc_id
			WHERE tlc.status='Active' AND v.status='Active' AND vc.status='Active' AND tlc.id = ".$tlc_id." ORDER BY tlc.start_time";
		}
				
		return $this->db->query($query)->result();
	}
	
	/*
	* This function will get the details of the given vehicle id
	
	* @param int $vehicle_id
	* @return Object
	*/
	function get_vehicle_details($vehicle_id)
	{
		$query = 'SELECT v.*,vc.category FROM `digi_vehicle` v INNER JOIN `digi_vehicle_categories` vc ON vc.id = v.category_id WHERE v.id = '.$vehicle_id;
		return $this->db->query($query)->result();
	}
	
	function get_payment_gateways($gateway_id)
	{
		$query = 'SELECT * FROM '.$this->db->dbprefix('gateways').' g LEFT JOIN '.$this->db->dbprefix('gateways_fields').' gf ON g.gateway_id = gf.gateway_id LEFT JOIN '.$this->db->dbprefix('gateways_fields_values').' gfv ON gf.field_id = gfv.`gateway_field_id` WHERE g.gateway_id = '.$gateway_id;
		return $this->db->query($query)->result();
	}
	
	function get_sms_gateway()
	{
		$query = 'SELECT * FROM '.$this->db->dbprefix('gateways').' g LEFT JOIN '.$this->db->dbprefix('gateways_fields').' gf ON g.gateway_id = gf.gateway_id LEFT JOIN '.$this->db->dbprefix('gateways_fields_values').' gfv ON gf.field_id = gfv.`gateway_field_id` WHERE g.type = "sms" AND is_default=1';
		return $this->db->query($query)->result();
	}
	
	/*
	* This funciton will check the whether the seat is available or not for a given vehicle and on the particular day
	
	* @param int $travel_location_id
	* @param int $vehicle_id
	* @param string $pick_date
	* @param array $seats
	* @param string $is_waiting_list
	*/
	function check_seat_availability($travel_location_id, $vehicle_id, $pick_date, $seat, $is_waiting_list = 'No', $tlc_id = '', $shuttle_no = '')
	{
		$tlc_str = '';
		if($tlc_id != '')
		{
			$tlc_str = ' AND tlc.id = '.$tlc_id;
		}
		$shuttle_no_str = '';
		if($shuttle_no != '')
		{
			$shuttle_no_str = ' AND bp.shuttle_no = "'.$shuttle_no.'"';
		}
		$query = "SELECT * FROM digi_bookings b
INNER JOIN digi_vehicle v ON v.id = b.vehicle_selected
INNER JOIN digi_vehicle_categories vc ON vc.id=v.category_id 
INNER JOIN digi_travel_locations tl ON tl.travel_location_id = b.travel_location_id 
INNER JOIN digi_travel_location_costs tlc ON tlc.travel_location_id = b.travel_location_id
INNER JOIN `digi_bookings_passengers` bp ON b.id = bp.booking_id WHERE bp.seat = '$seat' AND b.booking_status != 'Cancelled' AND b.pick_date='".date('Y-m-d', strtotime($pick_date))."' AND v.id=$vehicle_id $tlc_str $shuttle_no_str AND b.travel_location_id = $travel_location_id AND bp.is_waiting_list = '$is_waiting_list'";
		return $this->db->query($query)->result();
	}
	
	function get_booking_info($tlc_id, $pick_date, $is_waiting_list = 'No')
	{
		$query = "SELECT bp.* FROM digi_travel_location_costs tlc 
		INNER JOIN digi_vehicle v ON v.id=tlc.vehicle_id 
		INNER JOIN digi_vehicle_categories vc ON vc.id=v.category_id 
		INNER JOIN digi_travel_locations tl ON tl.travel_location_id = tlc.travel_location_id 
		INNER JOIN digi_bookings b ON b.vehicle_selected = v.id 
		INNER JOIN digi_bookings_passengers bp ON bp.booking_id = b.id 
		WHERE  b.booking_status != 'Cancelled' AND b.pick_date = '".date('Y-m-d', strtotime($pick_date))."' AND b.travel_location_cost_id = ".$tlc_id.' AND bp.is_waiting_list="'.$is_waiting_list.'"';
		//echo $query;die();
		$result = $this->db->query($query)->result();
		$output = array();
		if(count($result) > 0)
		{
			foreach($result as $key => $val)
			{
				$output[$val->seat] = $val->gender;
			}
		}
		return $output;
	}
	
	/**
	 * This function will return whenter the particular seat is Female booked OR Male Booked
	 * @param string $seat
	 * @param array $booked_seats
	 * @return string
	*/
	function seat_type($seat, $booked_seats)
	{
		$seat_type = 'Male';
		if(count($booked_seats) > 0)
		{
			foreach($booked_seats as $key => $val)
			{
				if($seat == $key)
					$seat_type = $val;
			}
		}
		return $seat_type;
	}
	
	/**
	 * This function will return the travel_location_id based on From adn To Locations
	 * @param int $from_loc_id
	 * @param int $to_loc_id
	 * @return int
	*/
	function get_traval_location($from_loc_id, $to_loc_id)
	{
		$query = "SELECT tl.travel_location_id, l.location 
										FROM digi_travel_locations tl, digi_locations l 
										WHERE tl.from_loc_id=".$from_loc_id." AND tl.to_loc_id = ".$to_loc_id." AND l.id=tl.to_loc_id 
										AND tl.status='Active' AND l.status='Active' 
										ORDER BY l.is_airport='1' DESC";
		$result = $this->db->query($query)->result();
		$travel_location_id = 0;
		if(count($result) > 0)
			$travel_location_id = $result[0]->travel_location_id;
		return $travel_location_id;
	}
	
	function validateCoupon($coupon_code, $total_fare, $user_id, $basic_fare, $insurance)
	{
		$output = array('status' => 0, 'message' => getPhrase('Not a valid coupon OR Coupon expired'));
		$query = "SELECT * FROM digi_offers WHERE code = '$coupon_code' AND date(expiry_date) >= '".date('Y-m-d')."'";		
		$result = $this->db->query($query)->result();
		//neatPrint($result);
		if(count($result) > 0)
		{
			$offer_id = $result[0]->offer_id;
			$min_journey_cost = $result[0]->min_journey_cost;
			$usage_type = $result[0]->usage_type;
			$usage_type_val = $result[0]->usage_type_val;
			if($result[0]->discount_appliedon == 'basic_fare')
			{
				$total_fare = $basic_fare;
			}
			
			if( $min_journey_cost != 0 && $total_fare < $min_journey_cost)
			{
				$msg = 'Applicable for minimum amount of '. $this->config->item('site_settings')->currency_symbol . $min_journey_cost;
				$output = array('status' => 0, 'message' => $msg);	
			}
			else
			{
				if($user_id == 0)
				{
					$usage_details = $this->db->query('SELECT * FROM digi_offer_users WHERE offer_id = '.$offer_id.' AND ip_address = "'.$this->input->ip_address().'"')->result();
				}
				else
				{
					$usage_details = $this->db->query('SELECT * FROM digi_offer_users WHERE offer_id = '.$offer_id.' AND user_id = '.$user_id)->result();
				}
				
				if(count($usage_details) > 0)
				{
					if($usage_type == 'one_time')
					{
						$output = array('status' => 0, 'message' => getPhrase('You have already used this coupon'));
					}
					else
					{
						if(count($usage_details) < $usage_type_val)
						{
							$opts = $this->calculate_discount($total_fare, $result, $user_id, $insurance);
							$output = array('status' => 1, 'result' => $opts);							
						}
						else
						{
							$output = array('status' => 0, 'message' => getPhrase('You have already used this coupon for <b>'.count($usage_details).'</b> times'));
						}
					}
				}
				else
				{
					$opts = $this->calculate_discount($total_fare, $result, $user_id, $insurance);
					$output = array('status' => 1, 'result' => $opts);
				}
			}
			
		}
		return json_encode($output);
	}
	
	function calculate_discount($total_fare, $coupon_details, $user_id, $insurance)
	{
		
		$discount_amount = $actual_discount = 0;
		if($coupon_details[0]->offer_type=='amount') {
			$discount_amount = $total_fare - $coupon_details[0]->offer_type_val;
			$actual_discount = $coupon_details[0]->offer_type_val;
		}
		else 
		{								
			$percentage = $coupon_details[0]->offer_type_val;
			$discount_amount = ($percentage / 100) * $total_fare;
			$actual_discount = ($percentage / 100) * $total_fare;;
			if($coupon_details[0]->maximum_amount != 0)
			{
				if($discount_amount > $coupon_details[0]->maximum_amount)
				{
					$discount_amount  = $coupon_details[0]->maximum_amount;
					$actual_discount = $coupon_details[0]->maximum_amount;
				}
			}
		
		}
		$opts = array(	'offer_id'	=> $coupon_details[0]->offer_id,
		'disount_amount'	=> $discount_amount,
		'disount_amount_format'	=> number_format($actual_discount, 2),
		'insurance' => $insurance,
		'actual_discount' => $actual_discount,
		'user_id' => $user_id,
		'ip_address' => $this->input->ip_address(),
		'total_fare' => $total_fare,
		'currency_symbol' => $this->config->item('site_settings')->currency_symbol,
		'message'			=> 'Waaw...! you got a discount.. :-)'
		);
		return $opts;
	}
	
	/**
	* This function will find the ticket details
	* @param mixed $condition
	* @return Object
	*/
	function ticket_details($condition = array(), $offset = 0, $limit = PER_PAGE)
	{
		$str = '';
		$query = 'SELECT b.*,v.name,v.number_plate, bp.shuttle_no passenger_shuttle_no, bp.seat_no passenger_seat_no FROM `digi_bookings` b 
		INNER JOIN `digi_bookings_passengers` bp ON b.id = bp.booking_id 
		INNER JOIN digi_vehicle v ON v.id = b.vehicle_selected
		WHERE 1 = 1';
		if(isset($condition['booking_ref']))
			$str .= ' AND b.booking_ref = "'.$condition['booking_ref'].'"';
		if(isset($condition['payment_code']))
		{
			$str .= ' AND b.payment_code = "'.$condition['payment_code'].'" GROUP BY b.id';
		}
		$query = $query . $str;
		$result = $this->db->query($query);
		$this->numrows = $result->num_rows();
		$query = $query . ' LIMIT ' . $offset . ',' . $limit;
		$result = $this->db->query($query); //Getting limitted records
		return $result->result();
	}
	
	/**
	* This function will check the ticket cancel validity
	* @param string $booking_ref
	* @return bool
	*/
	function get_ticket_details( $booking_ref, $otp = '' )
	{
		$return = array('status' => 1, 'message' => 'Can Cancel Ticket');
		$query = 'SELECT * FROM `digi_bookings` b 
		INNER JOIN `digi_bookings_passengers` bp ON b.id = bp.booking_id 
		INNER JOIN digi_vehicle v ON v.id = b.vehicle_selected
		WHERE b.booking_status != "Cancelled" AND b.booking_ref = "'.$booking_ref.'"';
		if($otp != '')
		{
			$query .= ' AND cancel_otp = "'.$otp.'" AND cancel_otp_valid_upto > "'.strtotime(date('Y-m-d h:i A')) . '"';
		}
		
		$result = $this->db->query($query)->result();
		if(count($result) > 0)
		{
			$can_cancel_time = $result[0]->pick_date;
			if($result[0]->pick_time != '')
			{
				$parts = str_replace(':','',$result[0]->pick_time);
				$can_cancel_time .= ' '.trim($parts[0]).':'.trim($parts[1]).' '.trim($parts[2]);
			}
			$hours = $this->config->item('site_settings')->canncel_before_hours;
			$new_time = date('Y-m-d h:i A', strtotime("-$hours hours", strtotime($can_cancel_time)));
			if(strtotime(date('Y-m-d h:i A')) > $new_time)
			{
				if($otp == '')
				{
					$times = $this->config->item('site_settings')->max_times_sms_cansend;
					if($result[0]->cancel_otp_times < $times)
					{
					$return = array('status' => 1, 'message' => getPhrase('Please enter OPT you received on registered mobile number'), 'details' => $result[0]);
					}
					else
					{
						$return = array('status' => 0, 'message' => getPhrase('You have already used '.$result[0]->cancel_otp_times.' times this feature. Please contact administrator.'));
					}
				}
				else
				{
					$return = array('status' => 1, 'message' => getPhrase('Your Ticket has been cancelled.'), 'details' => $result[0]);
				}
			}
			else
			{
				$return = array('status' => 0, 'message' => 'Ticket can not cancel at this time');
			}
		}
		else
		{
			if($otp != '')
			$return = array('status' => 0, 'message' => 'Invalid OTP OR OTP Expires');
			else
			$return = array('status' => 0, 'message' => 'Please enter valid ticket number');
		}
		return $return;
	}
	
	/**
	* This function will return the all the vehicle types which are available
	* @param array $shuttle_types
	* @return Object
	*/
	function available_shuttle_types($shuttle_types = array())
	{
		if(count($shuttle_types))
		{
		$query = 'SELECT * FROM '.$this->db->dbprefix('vehicle_categories').' WHERE id IN('.implode(',', $shuttle_types).')';
		return $this->db->query($query)->result();
		}
		else
		{
			return array();
		}
	}
	
	/**
	* This function will return the available seats in a route cost for each price set on a particular date
	* @param string $pick_date
	* @param int $tlc_id
	* @return mixed
	*/
	function booked_seats_pricesets_count($pick_date, $tlc_id, $is_waiting_list = 'No')
	{
		$seats_details = array();
		$tlc_details = $this->db->query('SELECT * FROM '.$this->db->dbprefix('travel_location_costs').' tlc WHERE id = '.$tlc_id)->result();
		if(count($tlc_details) > 0)
		{
			$number_of_pricevariations = $tlc_details[0]->number_of_pricevariations;
			
			$fare_details = (isset($tlc_details[0]->fare_details) && $tlc_details[0]->fare_details != '') ? json_decode($tlc_details[0]->fare_details) : array();
			$fare_details = (array)$fare_details;
			if(isset($fare_details['variation']))
			{
				foreach($fare_details['variation'] as $pv => $vv)
				{
					$query = 'SELECT * FROM '.$this->db->dbprefix('bookings').' b INNER JOIN '.$this->db->dbprefix('bookings_passengers').' bp ON b.id = booking_id WHERE b.booking_status != "Cancelled" AND b.pick_date = "'.date('Y-m-d', strtotime($pick_date)).'" AND b.travel_location_cost_id = '.$tlc_id.' AND b.is_waiting_list = "'.$is_waiting_list.'" AND bp.price_set = '.$pv;
					//echo $query;
					$result = $this->db->query($query)->result();
					$seats_details[$pv] = $this->db->affected_rows();
				}
			}			
		}
		return $seats_details;
	}



	function getDriverShuttles($driver_id = '', $shuttle_no = '', $travel_location_cost_id = '', $date = '')
	{
		if(!($driver_id > 0))
			return array();



		$shuttle_cond = "";
		$tlc_cond 	  = "";
		$date_cond    = "";


		if($shuttle_no != "") {

			$shuttle_cond = ' AND tlc.shuttle_no="'.$shuttle_no.'"';
		}

		if($travel_location_cost_id > 0) {

			$tlc_cond = ' AND tlc.id='.$travel_location_cost_id;
		}


		if($date != "") {

			$date_cond = ' AND b.pick_date="'.$date.'"';
		}

		$query = "SELECT v.*,vc.category FROM digi_travel_location_costs_drivers tlcd INNER JOIN digi_vehicle v ON v.id = tlcd.vehicle_id INNER JOIN digi_vehicle_categories vc ON v.category_id = vc.id WHERE ('".date('Y-m-d', strtotime($date))."' BETWEEN special_start AND special_end) AND driver_id =  $driver_id ORDER BY special_start DESC";
		//echo $query;die();
		$check = $this->db->query($query)->result();
		if(!empty($check))
		{
			$query = "SELECT fromloc.location pick_point_name,toloc.location drop_point_name, tlc.id as tlc_id, tlc.fare_details, tlc.start_time, tlc.destination_time, tlc.shuttle_no, v.*, vc.category,tl.*, b.pick_date FROM ".$this->db->dbprefix('travel_location_costs')." tlc
			INNER JOIN ".$this->db->dbprefix('travel_location_costs_drivers')." tlcd ON tlcd.tlc_id = tlc.id
			INNER JOIN ".$this->db->dbprefix('vehicle')." v ON v.id=tlcd.vehicle_id
			INNER JOIN ".$this->db->dbprefix('vehicle_categories')." vc ON vc.id = v.category_id
			INNER JOIN ".$this->db->dbprefix('travel_locations')." tl ON tl.travel_location_id = tlc.travel_location_id
			INNER JOIN ".$this->db->dbprefix('locations')." fromloc ON fromloc.id = tl.from_loc_id
			INNER JOIN ".$this->db->dbprefix('locations')." toloc ON toloc.id = tl.to_loc_id
			INNER JOIN ".$this->db->dbprefix('bookings')." b ON b.travel_location_cost_id = tlc.id
			WHERE b.booking_status != 'Cancelled' AND v.status='Active' AND vc.status='Active' 
			AND tlc.status='Active'  
			AND b.shuttle_no=tlc.shuttle_no 
			".$tlc_cond." 
			".$date_cond." 
			AND tlcd.driver_id=".$driver_id." ".$shuttle_cond." ORDER BY tlc.start_time ASC";
		}
		else
		{
		$query = "SELECT fromloc.location pick_point_name,toloc.location drop_point_name, tlc.id as tlc_id, tlc.fare_details, tlc.start_time, tlc.destination_time, tlc.shuttle_no, v.*, vc.category,tl.*, b.pick_date FROM ".$this->db->dbprefix('travel_location_costs')." tlc
			INNER JOIN ".$this->db->dbprefix('vehicle')." v ON v.id=tlc.vehicle_id
			INNER JOIN ".$this->db->dbprefix('vehicle_categories')." vc ON vc.id = v.category_id
			INNER JOIN ".$this->db->dbprefix('travel_locations')." tl ON tl.travel_location_id = tlc.travel_location_id
			INNER JOIN ".$this->db->dbprefix('locations')." fromloc ON fromloc.id = tl.from_loc_id
			INNER JOIN ".$this->db->dbprefix('locations')." toloc ON toloc.id = tl.to_loc_id
			INNER JOIN ".$this->db->dbprefix('bookings')." b ON b.travel_location_cost_id = tlc.id
			WHERE b.booking_status != 'Cancelled' AND v.status='Active' AND vc.status='Active' 
			AND tlc.status='Active'  
			AND b.shuttle_no=tlc.shuttle_no 
			".$tlc_cond." 
			".$date_cond." 
			AND tlc.driver_id=".$driver_id." ".$shuttle_cond." ORDER BY tlc.start_time ASC";
		}
		//echo $query;
		$driver_shuttles = $this->db->query($query)->result();
		return $driver_shuttles;
	}




	function getShuttlePassenger($driver_id = '', $shuttle_no = '', $travel_location_cost_id = '', $pick_date = '')
	{

		if(!($driver_id > 0 && $shuttle_no != "" && $travel_location_cost_id > 0))
			return array();

		if(!isDriverzShuttle($driver_id, $shuttle_no, $travel_location_cost_id))
			return array();

		$query = "SELECT b.pick_date, b.travel_location_cost_id, p.* FROM ".$this->db->dbprefix('bookings')." b 
			INNER JOIN ".$this->db->dbprefix('bookings_passengers')." p ON p.booking_id=b.id  
			WHERE b.booking_status = 'Confirmed' AND b.shuttle_no='".$shuttle_no."' AND b.travel_location_cost_id=".$travel_location_cost_id." AND b.pick_date='".$pick_date."'";

		$shuttle_passenger = $this->db->query($query)->result();

		return $shuttle_passenger;

	}


	function getShuttleAvgScoreOfTl($travel_location_cost_id = '', $shuttle_no = '')
	{

		if(!($travel_location_cost_id > 0 && $shuttle_no != ""))
			return 0;

		$query = "SELECT sum( user_rating_value ) / count( user_rating_value ) AS avg_score
					FROM ".$this->db->dbprefix('bookings')."
					WHERE `travel_location_cost_id` =".$travel_location_cost_id."
					AND b.booking_status != 'Cancelled' AND `shuttle_no` = '".$shuttle_no."'";

		return $this->db->query($query)->row()->avg_score;
	}
	
	function locked_seats($tlc_id, $p_date, $shuttle_no)
	{
		$rows = $this->db->query('SELECT * FROM `digi_bookings_locked` WHERE pick_date = "'.date('Y-m-d', strtotime($p_date)).'" AND shuttle_no = "'.$shuttle_no.'" AND tlc_id = '.$tlc_id)->result();
		return $rows;
	}
	
	function get_drivers($offset = 0)
	{
		$query = 'SELECT u.*,ug.group_id FROM '.$this->db->dbprefix('users').' u INNER JOIN '.$this->db->dbprefix('users_groups').' ug ON u.id = ug.user_id INNER JOIN '.$this->db->dbprefix('groups').' g ON g.id = ug.group_id WHERE ug.group_id = 6';
		$result = $this->db->query($query);
		$this->numrows = $this->db->affected_rows();
		$query = 'SELECT u.*,ug.group_id FROM '.$this->db->dbprefix('users').' u INNER JOIN '.$this->db->dbprefix('users_groups').' ug ON u.id = ug.user_id INNER JOIN '.$this->db->dbprefix('groups').' g ON g.id = ug.group_id WHERE ug.group_id = 6 LIMIT '.$offset.','.PER_PAGE;
		$result = $this->db->query($query)->result();
		return $result;
	}
	
	function get_driver_shuttles( $driver_id )
	{
		$query = 'SELECT tlc.*,u.first_name, u.last_name, v.name vehicle_name,v.model vehicle_model, v.id vehicle_id, v.number_plate FROM `digi_travel_location_costs` tlc 
		INNER JOIN digi_users u ON u.id = tlc.driver_id 
		INNER JOIN digi_vehicle v on v.id = tlc.vehicle_id
		WHERE tlc.driver_id = '.$driver_id;
		$result = $this->db->query($query);
		if($this->db->affected_rows() == 0)
		{
			//$query = 
		}
		return $this->db->query($query)->result();
	}
	
	function get_other_drivers()
	{
		$query = 'SELECT u.* FROM digi_users u INNER JOIN `digi_users_groups` ug ON u.id = ug.user_id INNER JOIN digi_groups g ON g.id = ug.group_id WHERE ug.group_id = 6';
		return $this->db->query($query)->result();
	}
	
	/**
	* This function will get all the schedules for a driver in particular time period
	* @param $driver_id int
	* @param $special_start date
	* @param $special_end date
	* return object
	*/
	function get_driver_shuttles_date($driver_id, $special_start, $special_end)
	{
		$query = 'SELECT tlcd.tlc_id, tlcd.id, tlcd.driver_id FROM `digi_travel_location_costs` tlc 
INNER JOIN `digi_travel_location_costs_drivers` tlcd ON tlcd.tlc_id = tlc.id
INNER JOIN digi_users u ON u.id = tlcd.driver_id AND
INNER JOIN `digi_users_groups` ON ug.user_id = u.id
INNER JOIN `digi_groups` g ON g.id = ug.group_id
INNER JOIN digi_vehicle v ON v.id = tlcd.driver_id
WHERE u.active = 1 AND tlcd.driver_id = '.$driver_id.' AND (("'.$special_start.'" BETWEEN tlcd.special_start AND tlcd.special_end) OR ("'.$special_end.'" BETWEEN tlcd.special_start AND tlcd.special_end))';
	}
	
	function get_travel_locations()
	{
		$query = "SELECT fl.location from_location, tol.location to_location,tl.travel_location_id FROM  `digi_travel_locations` tl
INNER JOIN digi_locations fl ON fl.id = tl.from_loc_id
INNER JOIN digi_locations tol ON tol.id = tl.to_loc_id
WHERE fl.status='Active' AND tol.status='Active' AND tl.status = 'Active'";
		$result = $this->db->query($query)->result();
		$this->num_rows = $this->db->affected_rows();
		return $result;
	}
	
	function get_location_costs()
	{
		$query = "SELECT * FROM `digi_travel_location_costs` tlc
INNER JOIN digi_travel_locations tl ON tl.travel_location_id = tlc.travel_location_id
INNER JOIN digi_locations fl ON fl.id = tl.from_loc_id
INNER JOIN digi_locations tol ON tol.id = tl.to_loc_id
WHERE fl.status='Active' AND tol.status='Active' AND tl.status = 'Active'"; //Here we are not joining vehicle coz that default vehicle may be replaced with other vehicle
	$result = $this->db->query($query)->result();
		$this->num_rows = $this->db->affected_rows();
		return $result;
	}
	
	function get_location_name( $id )
	{
		$str = '';
		$query = 'SELECT location FROM digi_locations WHERE id = '.$id;
		$result = $this->db->query($query)->result();
		if(!empty($result))
			$str = $result[0]->location;
		return $str;
	}
	
	function get_locations_options()
	{
		$query = "SELECT * FROM `digi_locations` WHERE STATUS = 'Active'";
		$result = $this->db->query($query)->result();
		/*
		$options = array('' => 'Select Pick-up Location');
		if(!empty($result))
		{
			foreach($result as $location)
			{
				$options[$location->id] = $location->location;
			}
		}
		*/
		return $result;
	}
	
	function get_vehicles()
	{
		$query = 'SELECT v.*,vc.category FROM `digi_vehicle` v
INNER JOIN `digi_vehicle_categories` vc ON v.category_id = vc.id';
		return $this->db->query($query)->result();
	}
	
	function get_vehicle_shuttles( $vehicle_id )
	{
		$query = 'SELECT tlc.*,u.first_name, u.last_name, v.name vehicle_name,v.model vehicle_model, v.id vehicle_id, vc.category, v.number_plate FROM `digi_travel_location_costs` tlc 
		INNER JOIN digi_users u ON u.id = tlc.driver_id 
		INNER JOIN digi_vehicle v on v.id = tlc.vehicle_id
		INNER JOIN `digi_vehicle_categories` vc ON vc.id = v.category_id
		WHERE tlc.vehicle_id = '.$vehicle_id;
		$result = $this->db->query($query);
		if($this->db->affected_rows() == 0)
		{
			//$query = 
		}
		return $this->db->query($query)->result();
	}
	
	function get_vehicle_shuttles_date($vehicle_id, $special_start, $special_end)
	{
		$query = 'SELECT tlcd.tlc_id, tlcd.id, tlcd.driver_id FROM `digi_travel_location_costs` tlc 
INNER JOIN `digi_travel_location_costs_drivers` tlcd ON tlcd.tlc_id = tlc.id
INNER JOIN digi_users u ON u.id = tlcd.driver_id AND
INNER JOIN `digi_users_groups` ON ug.user_id = u.id
INNER JOIN `digi_groups` g ON g.id = ug.group_id
INNER JOIN digi_vehicle v ON v.id = tlcd.driver_id
WHERE u.active = 1 AND tlcd.vehicle_id = '.$vehicle_id.' AND (("'.$special_start.'" BETWEEN tlcd.special_start AND tlcd.special_end) OR ("'.$special_end.'" BETWEEN tlcd.special_start AND tlcd.special_end))';
return $this->db->query($query)->result();
	}

function check_allotted_date($tlc_id, $special_start, $special_end)
{
	$query = 'SELECT * FROM digi_travel_location_costs_drivers WHERE ((special_start BETWEEN "'.$special_start.'" AND "'.$special_end.'") OR (special_end BETWEEN "'.$special_start.'" AND "'.$special_end.'") ) AND tlc_id = '.$tlc_id;
	return $this->db->query($query)->result();
}	
	
}
?>