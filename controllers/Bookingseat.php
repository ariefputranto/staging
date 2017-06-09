<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bookingseat extends MY_Controller
{
	/*
	| -----------------------------------------------------
	| PRODUCT NAME: 	DIGI Point to Point Transfers
	| -----------------------------------------------------
	| AUTHOR:			DIGITAL VIDHYA TEAM
	| -----------------------------------------------------
	| EMAIL:			digitalvidhya4u@gmail.com
	| -----------------------------------------------------
	| COPYRIGHTS:		RESERVED BY DIGITAL VIDHYA
	| -----------------------------------------------------
	| WEBSITE:			http://digitalvidhya.com
	|                   http://codecanyon.net/user/digitalvidhya
	| -----------------------------------------------------
	|
	| MODULE: 			Booking
	| -----------------------------------------------------
	| This is Booking module controller file.
	| -----------------------------------------------------
	*/


	public function __construct()
	{
		parent::__construct();

		// To use site_url and redirect on this controller.
		$this->load->helper('url');
		$this->form_validation->set_error_delimiters(
		$this->config->item('error_start_delimiter', 'ion_auth'), 
		$this->config->item('error_end_delimiter', 'ion_auth')
		);
		//echo $this->data['site_theme'];die();
		if($this->data['site_theme'] == 'vehicle')
		{
			redirect('booking/index');
		}
		/*
		if($this->ion_auth->is_admin())
		{
			redirect('admin/index');
		}
		*/
	}


	/****** Journey, Date & Time ******/
	function index()
	{
		$this->data['vehicles'] = array();
		$this->data['total_vehicles'] = 0;
		$pick_date = date('m/d/Y');
		$pick_point = $drop_point = $travel_location_id = 0;
		$this->data['message'] = $this->session->flashdata('message');
		if(count($this->session->userdata('journey_booking_details')) > 0) {
			$record = $this->session->userdata('journey_booking_details');
			$pick_date = $record['pick_date'];
			$pick_point = $record['pick_point'];
			//neatPrint($record);
			$drop_point = $record['drop_point'];
			$travel_location_id = $record['travel_location_id'];	
			//neatPrint($record);
			if(isset($record['is_return']) && $record['is_return'] == 'yes')
			{
				$pick_date = $record['return']['pick_date'];
				$travel_location_id = $record['return']['travel_location_id'];
				$results = $this->base_model->get_vehicles_seats(array('pick_point' => $drop_point, 'drop_point' => $pick_point, 'travel_location_id' => $travel_location_id, 'pick_date' => $pick_date));
			}
			else
			{
				$results = $this->base_model->get_vehicles_seats(array('pick_point' => $pick_point, 'drop_point' => $drop_point, 'travel_location_id' => $travel_location_id, 'pick_date' => $pick_date));
			}
			//echo $this->db->last_query();die();
			$this->data['total_vehicles'] = $this->base_model->num_rows;
			$this->data['vehicles'] = $results;
		}
		$this->data['travel_location_id'] = $travel_location_id;
		/* Check For Form Submission */
		if($this->input->post()) {

			$journey_booking_details = $this->session->userdata('journey_booking_details');
			
			
			$this->form_validation->set_rules('selected_seats_total', getPhrase('Please select atleast one seat'),'trim|required');
			$journey_type = $this->input->post('journey_type');
			$selected_seats_total = $this->input->post('selected_seats_total');
			$selected_seats_total_session = (isset($journey_booking_details[$journey_type]['selected_seats_total']))? $journey_booking_details[$journey_type]['selected_seats_total'] : 0;
			$vehicle_id = (isset($journey_booking_details[$journey_type]['vehicle_id']))? $journey_booking_details[$journey_type]['vehicle_id'] : 0;
			
			if($selected_seats_total != $selected_seats_total_session || $selected_seats_total == 0 || $selected_seats_total_session == 0 || $selected_seats_total == '')
			{
				$this->prepare_flashmessage(getPhrase('Something went wrong. Please try again'), 1);
				redirect('bookingseat/index');
			}
			
			
			/* Form Validations */
			//$this->form_validation->set_rules('boarding_point['.$vehicle_id.']', getPhrase('Please select boarding point'), 'trim|required|callback_checkvalidviapoint');
						
			$this->form_validation->set_error_delimiters(
			'<div class="error">', '</div>'	);
			
			if($this->form_validation->run() == TRUE) {
				//neatPrint($journey_booking_details);
				if(isset($journey_booking_details['onward']) && $journey_booking_details['is_return'] == 'no')
				{
					//Onward
					$selection_details = $journey_booking_details['onward']['selection_details'];

					if(!empty($selection_details))
					{
						foreach($selection_details as $key => $val)
						{
							$locked = array(
								'session_id' => $journey_booking_details['session_id'],
								'pick_date' => date('Y-m-d', strtotime($val->pick_date)),
								'pick_time' => $val->start_time,
								'destination_time' => $val->destination_time,
								'pick_point_view' => $val->pick_point_name,				
								'pick_point' => $val->from_loc_id,
								'drop_point_view' => $val->drop_point_name,
								'drop_point' => $val->to_loc_id,
								'travel_location_id' => $val->travel_location_id,
								'tlc_id' => $val->tlc_id,
								'vehicle_id' => $val->id,
								'shuttle_no' => $val->shuttle_no,
								'seat' => $val->seat,
								'seat_type' => $val->seat_type,
								'seat_cost' => $val->seat_cost,
								'date_created' => date('Y-m-d H:i'),
							);
							$this->base_model->insert_operation($locked, 'bookings_locked');
						}
					}
				}
				
				if($journey_type == 'onward' && isset($journey_booking_details['return'])) //User choose 'Two-way' journey and just finished onward journey, then retrun to choose return journey
				{
					$journey_booking_details['is_return'] = 'yes';
					$this->session->set_userdata('journey_booking_details', $journey_booking_details);
					
					$this->prepare_flashmessage(getPhrase('Please choose return ticket details'), 0);
					redirect('bookingseat/index');
				}
				
				if(isset($journey_booking_details['return']))
				{
					//Return
					$selection_details = $journey_booking_details['return']['selection_details'];
					if(!empty($selection_details))
					{
						foreach($selection_details as $key => $val)
						{
							$locked = array(
								'session_id' => $journey_booking_details['session_id'],
								'pick_date' => date('Y-m-d', strtotime($journey_booking_details['return']['pick_date'])),
								'pick_time' => $val->start_time,
								'destination_time' => $val->destination_time,
								'pick_point_view' => $val->pick_point_name,				
								'pick_point' => $val->from_loc_id,
								'drop_point_view' => $val->drop_point_name,
								'drop_point' => $val->to_loc_id,
								'travel_location_id' => $val->travel_location_id,
								'tlc_id' => $val->tlc_id,
								'vehicle_id' => $val->id,
								'shuttle_no' => $val->shuttle_no,
								'seat' => $val->seat,
								'seat_type' => $val->seat_type,
								'seat_cost' => $val->seat_cost,
								'date_created' => date('Y-m-d H:i'),
							);
							$this->base_model->insert_operation($locked, 'bookings_locked');
						}
					}
					
				}
				
				redirect('bookingseat/passenger_details');
			} else {
				$this->data['message'] = $this->prepare_message(validation_errors(),1);
			}

		}
		//neatPrint($this->data['vehicles']);
		$this->data['total_seats'] = $v_count =0;
		$this->data['available_seats'] = array();
		$this->data['via_points'] = array();
		$this->data['dropping_points'] = array();
		$vehicle_ids = $shuttle_types = array();
		$journey_booking_details = $this->session->userdata('journey_booking_details');
		if(count($this->data['vehicles']) > 0)
		{
			//neatPrint($this->data['vehicles']);
			$total_seats = $total_seats_locked = 0;
			foreach($this->data['vehicles'] as $v)
			{
				$vehicle_ids[] = $v->id;
				$tlc_ids[] = $v->tlc_id;
				$v_count++;
				$tlcid_date = $v->tlc_id.'_'.$v->start_date_new;
				
				if( $v->special_fare == 'yes' )
				{
					$specials = $this->db->query('SELECT * FROM '.$this->db->dbprefix('travel_location_costs_special').' WHERE tlc_id = '.$v->tlc_id.' AND "'.date('Y-m-d', strtotime($v->start_date_new)).'" BETWEEN special_start AND special_end AND status="active" ORDER BY updated DESC LIMIT 1')->result();
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
				
				$tlc_total_seats = $tlc_total_seats_locked = 0;
				if(isset($fare_details['variation']))
				{
					foreach($fare_details['variation'] as $pv => $vv)
					{
						$tlc_total_seats += get_seat_priceset_count($pv, $fare_details);
					}
				}
				$total_seats += $tlc_total_seats;
				
				
				$is_return = (isset($journey_booking_details['is_return'])) ? 'yes' : 'no';
				$journey_type = 'onward';
				if($is_return == 'yes')
				{
					$journey_type = 'return';
				}
				$is_waiting_list = isset($journey_booking_details[$journey_type]['is_waiting_list']) ? $journey_booking_details[$journey_type]['is_waiting_list'] : 'No';
				$p_date = isset($v->start_date_new) ? $v->start_date_new : $pick_date;
				
				
				$booked_seats_shuttle = $this->base_model->booked_seats_count($p_date, array($v->tlc_id), $is_waiting_list, $v->shuttle_no);
				
				$this->data['locked_seats'][$tlcid_date] = $this->base_model->locked_seats($v->tlc_id, $p_date, $v->shuttle_no);
				
				$tlc_total_seats_locked = count($this->data['locked_seats'][$tlcid_date]);
				
				$total_seats_locked += count($this->data['locked_seats'][$tlcid_date]);
				
				$tlc_available_seats = $tlc_total_seats - ($booked_seats_shuttle[0]->reserved + $tlc_total_seats_locked);
				$this->data['available_seats'][$tlcid_date] = ($tlc_available_seats > 0) ? $tlc_available_seats : 0;
				if($v->shuttle_no == 'EXP333')
				{
					//echo $p_date.'##'.$tlc_total_seats.'@@'.$tlc_total_seats_locked;
					//print_r($v);					
					//neatPrint($this->data['available_seats']);
				}
				
				//$this->data['booked_seats_pricesets'][$v->tlc_id] = $this->base_model->booked_seats_pricesets_count($p_date, $v->tlc_id, $is_waiting_list, $v->shuttle_no);

				$this->data['booked_seats_pricesets'][$v->start_date_new.'_'.$v->shuttle_no.'_'.$v->tlc_id] = $this->base_model->booked_seats_pricesets_count($p_date, $v->tlc_id, $is_waiting_list, $v->shuttle_no);
				//echo $this->db->last_query();
				
				if($this->data['available_seats'][$tlcid_date] == 0)
				{
					$this->data['booked_seats'][$tlcid_date] = $this->base_model->get_booking_info($v->tlc_id, $p_date, 'Yes');
				}
				else
				{
				$this->data['booked_seats'][$tlcid_date] = $this->base_model->get_booking_info($v->tlc_id, $p_date);
				}
			}
			
			
			$booked_seats = $this->base_model->booked_seats_count($p_date, $tlc_ids, $is_waiting_list);
			//echo $total_seats.'@@'.$booked_seats[0]->reserved;die();
			//echo $this->db->last_query();die();
			$this->data['total_seats'] = $total_seats- ($booked_seats[0]->reserved + $total_seats_locked);
		}
		
		$this->data['shuttle_types'] = $this->base_model->available_shuttle_types($shuttle_types);				
		$this->data['css_type']		= array(
												'datepicker', 
												'timepicker', 
												'gmap'
											   );
		$this->data['title'] 		= (isset($this->phrases["shuttle booking"])) ? 
										$this->phrases["shuttle booking"] : "Shuttle Booking";
		$this->data['content'] 		= 'site/booking/booking';
		$this->_render_page('templates/site_template', $this->data);
	}
	
	/*
	* This function will validate the journey date
	* @param date - pick-up date
	* @param date - return date
	* @return bool
	*/
	function checkvaliddate()
	{
		$pick_date = $this->input->post('pick_date');
		$return_date = $this->input->post('return_date');
		$pick_point = $this->input->post('pick_point');
		$drop_point = $this->input->post('drop_point');
		
		$currentdate = date('m/d/Y');
		//echo $pick_date.'#'.$currentdate;die();
		if(strtotime($pick_date) < strtotime($currentdate))
		{
			$this->form_validation->set_message('checkvaliddate', getPhrase('Date should be future date'));
			return FALSE;
		}
		else
		{
			if($return_date != '')
			{
				if(strtotime($return_date) < strtotime($pick_date))
				{
					$this->form_validation->set_message('checkvaliddate', getPhrase('Return date should be greater than Pick-up date'));
					return FALSE;
				}
				$travel_location_id = $this->base_model->get_traval_location($drop_point, $pick_point);
				if($travel_location_id == 0)
				{
					$this->form_validation->set_message('checkvaliddate', getPhrase('There are no services for return journey'));
					return FALSE;
				}
			}
			return TRUE;
		}
	}
	
	function checkmaxseats()
	{
		$max_seats_to_book = $this->config->item('site_settings')->max_seats_to_book;
		$seats_required = $this->input->post('adult') + $this->input->post('child');
		
		$currentdate = date('m/d/Y');
		if( $seats_required > $max_seats_to_book)
		{
			$this->form_validation->set_message('checkmaxseats', getPhrase('You can select only '.$max_seats_to_book.'. Please contact administrator'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	/*
	* This function will select From and To location for the journey along with Onword and (OR) return journey
	* @param int - pick-up location
	* @param int - drop-off locaiton
	* @param date - pick-up date
	* @param date - return date
	*/
	function selectdate()
	{
		if($this->input->post()) {
			/* Form Validations */
			$this->form_validation->set_rules('pick_point', getPhrase('Pick-up Location'),'trim|required');
			$this->form_validation->set_rules('drop_point', getPhrase('Drop-off Location'),'trim|required');
			
			if($_POST['journey_type'] == 'Round-Trip')
			{
			$this->form_validation->set_rules('pick_date', getPhrase('Journey Date'),'trim|required');
			$this->form_validation->set_rules('return_date', getPhrase('Return Date'),'trim|required|callback_checkvaliddate');			
			}
			else
			{
			$this->form_validation->set_rules('pick_date', getPhrase('Journey Date'),'trim|required|callback_checkvaliddate');
			}
			$this->form_validation->set_rules('adult', getPhrase('Adult'),'trim|required|numeric|callback_checkmaxseats');
			$this->form_validation->set_rules('child', getPhrase('Child'),'trim|required|numeric');
			$adult = $this->input->post('adult');
			$child = $this->input->post('child');
			$infant = $this->input->post('infant');
			$this->form_validation->set_rules('infant', getPhrase('Infant'),'trim|required|numeric|less_than_equal_to['.($adult+$child).']');
			
			$this->form_validation->set_error_delimiters(
			'<div class="error">', '</div>'
			);

			if($this->form_validation->run() == true) {
				
				$this->session->unset_userdata('minutes');
				$this->session->unset_userdata('seconds');
				$this->session->unset_userdata('journey_booking_details');
				$this->session->unset_userdata('final_details');
				
				$journey_booking_details = array();
				$return_date = $this->input->post('return_date');
				$post_data = $this->input->post();
				
				$pick_point_view = $this->base_model->get_location_name($post_data['pick_point']);
				$drop_point_view = $this->base_model->get_location_name($post_data['drop_point']);
				//echo $this->db->last_query();
				$travel_location_id = $this->base_model->get_traval_location($post_data['pick_point'], $post_data['drop_point']);
				
				$post_data['pick_point_view'] = $pick_point_view;
				$post_data['drop_point_view'] = $drop_point_view;
				$post_data['travel_location_id'] = $travel_location_id;
				
				$journey_booking_details = $this->session->userdata('journey_booking_details');
								
				$session_id = (isset($journey_booking_details['session_id'])) ? $journey_booking_details['session_id'] : '';
				if($session_id != '')
				$this->base_model->delete_record('bookings_locked', array('session_id' => $session_id));
			
				foreach($this->input->post() as $key=>$val)
				{
					if(in_array($key, array('pick_point_view', 'pick_point', 'drop_point_view', 'drop_point', 'pick_date', 'return_date', 'travel_location_id', 'journey_type', 'adult', 'child', 'infant')))
					{
						if($key == 'travel_location_id')
						{
							$journey_booking_details['onward'][$key] = $this->base_model->get_traval_location($post_data['pick_point'], $post_data['drop_point']);
						}
						else
						{
							$journey_booking_details['onward'][$key] = $val;
						}
						//$journey_booking_details[$key] = $val;
						if($return_date != '')
						{
							if($key == 'pick_point_view')
							$journey_booking_details['return'][$key] = $post_data['drop_point_view'];
							if($key == 'pick_point')
							$journey_booking_details['return'][$key] = $post_data['drop_point'];
							if($key == 'drop_point_view')
							$journey_booking_details['return'][$key] = $post_data['pick_point_view'];
							if($key == 'drop_point')
							$journey_booking_details['return'][$key] = $post_data['pick_point'];
							if($key == 'pick_date')
							$journey_booking_details['return'][$key] = $post_data['return_date'];
							if($key == 'travel_location_id')
							$journey_booking_details['return'][$key] = $this->base_model->get_traval_location($post_data['drop_point'], $post_data['pick_point']);
						
							if($key == 'journey_type')
								$journey_booking_details['return'][$key] = $post_data['journey_type'];
							if($key == 'adult')
							{
								if(is_numeric($post_data['adult']))
								$journey_booking_details['return'][$key] = $post_data['adult'];
								else
								$journey_booking_details['return'][$key] = 0;
							}
								
							if($key == 'child')
							{
								if(is_numeric($post_data['child']))
									$journey_booking_details['return'][$key] = $post_data['child'];
								else
									$journey_booking_details['return'][$key] = 0;
							}
								
							if($key == 'infant')
							{
								if(is_numeric($post_data['infant']))
									$journey_booking_details['return'][$key] = $post_data['infant'];
								else
									$journey_booking_details['return'][$key] = 0;
							}
								
						}
					}
					
				}
				$journey_booking_details['pick_point_view'] = $post_data['pick_point_view'];
				$journey_booking_details['drop_point_view'] = $post_data['drop_point_view'];
				$journey_booking_details['pick_point'] = $post_data['pick_point'];
				$journey_booking_details['drop_point'] = $post_data['drop_point'];
				$journey_booking_details['pick_date'] = $post_data['pick_date'];
				if(isset($_POST['return_date']))
				$journey_booking_details['return_date'] = $post_data['return_date'];
				$journey_booking_details['travel_location_id'] = $post_data['travel_location_id'];
				
				$journey_booking_details['journey_type'] = $post_data['journey_type'];
				$journey_booking_details['adult'] = $post_data['adult'];
				$journey_booking_details['child'] = $post_data['child'];
				$journey_booking_details['infant'] = $post_data['infant'];
								
				$journey_booking_details['onward']['pick_point_name'] = $post_data['pick_point_view'];
				$journey_booking_details['onward']['drop_point_name'] = $post_data['drop_point_view'];
				
				$journey_booking_details['onward']['selected_adult'] = 0;
				$journey_booking_details['onward']['selected_child'] = 0;
				$journey_booking_details['onward']['selected_infant'] = 0;
				
				$journey_booking_details['is_return'] = 'no';
				if($return_date != '')
				{
				$journey_booking_details['return']['pick_point_name'] = $post_data['drop_point_view'];
				$journey_booking_details['return']['drop_point_name'] = $post_data['pick_point_view'];
				$journey_booking_details['return']['selected_adult'] = 0;
				$journey_booking_details['return']['selected_child'] = 0;
				$journey_booking_details['return']['selected_infant'] = 0;
				}
				$journey_booking_details['session_id'] = session_id();					
				$this->session->set_userdata('journey_booking_details', $journey_booking_details);
				//neatPrint($journey_booking_details);
				redirect('bookingseat/index');
			}
			else
			{
				$this->session->unset_userdata('journey_booking_details');
				$this->prepare_flashmessage(validation_errors(), 1);
				redirect('bookingseat/index');
			}

		}
	}
	
	function checkfor_valid()
	{
		$session_deails = $this->session->userdata('journey_booking_details');
		if(empty($session_deails))
		{
			$this->prepare_flashmessage((isset($this->phrases["please select a Pick-up and Drop-off Location for your journey"])) ? $this->phrases["please select a Pick-up and Drop-off Location for your journey"] : "Please select a Pick-up and Drop-off Location for your Journey".".", 1);
				redirect('bookingseat/index');
		}
	}
	function checkfor_valid_final()
	{
		$final_details = $this->session->userdata('final_details');
		if(empty($final_details))
		{
			$this->prepare_flashmessage((isset($this->phrases["please select a Pick-up and Drop-off Location for your journey"])) ? $this->phrases["please select a Pick-up and Drop-off Location for your journey"] : "Please select a Pick-up and Drop-off Location for your Journey".".", 1);
				redirect('bookingseat/index');
		}
	}
			
	/****** Get Cities According to Country ******/
	function getEndLocations()
	{
		$end_loc_options = "";

		$start_id = $this->input->post('start_id');

		if($start_id > 0) {

			$end_locations = $this->base_model->
							run_query("SELECT tl.travel_location_id, l.location, l.id as lid
										FROM digi_travel_locations tl, digi_locations l 
										WHERE tl.from_loc_id=".$start_id." AND l.id=tl.to_loc_id 
										AND tl.status='Active' AND l.status='Active' 
										ORDER BY l.location DESC");

			if(count($end_locations) > 0) {

				$first_opt = (isset($this->phrases["select drop-off location"])) ? $this->phrases["select drop-off location"] : "Select Drop-off Location";

				$end_loc_options = '<option value="">'.$first_opt.'</option>';

				foreach($end_locations as $rec) {
					$selected = "";
					if($this->session->userdata('journey_booking_details')['drop_point'] == $rec->lid)
						$selected = "selected";
					$end_loc_options = $end_loc_options . 
										'<option value="'.$rec->lid.'" '.$selected.'>'.$rec->location.
										'</option>';
				}

			} else {

				$no_opt = (isset($this->phrases["no drop-off locations available"])) ? $this->phrases["no drop-off locations available"] : "No Drop-off Locations Available";
				$end_loc_options = "<option value=''>".$no_opt.".</option>";
			}
		}
		echo $end_loc_options;
	}
	
	//New Code Start
	function getLocations()
	{
		$location_opts = "";
		$str = $this->input->post('str');
		$location_type = $this->input->post('type');
		$locations = $this->base_model->getLocaitons($str, $location_type);
		if(count($locations) > 0)
		{
			$location_opts = '<ul>';
			foreach($locations as $loc)
			{
				$location_opts .= '<li onclick="assign(\''.$loc->location.'\','.$loc->id.')">'.$loc->location.'</li>';
			}
			$location_opts .= '</ul>';
		}
		echo $location_opts;
	}
	
	function getEndLocationsseat()
	{
		$end_loc_options = "";

		$start_id = $this->input->post('start_id');
		$str = $this->input->post('str');

		if($start_id > 0) {

			if($str != '')
			{
			$end_locations = $this->base_model->
							run_query("SELECT l.id, tl.travel_location_id, l.location 
										FROM digi_travel_locations tl, digi_locations l 
										WHERE tl.from_loc_id=".$start_id." AND l.location LIKE '%".$str."%' AND l.id=tl.to_loc_id 
										AND tl.status='Active' AND l.status='Active' 
										ORDER BY l.is_airport='1' DESC");
			}
			else
			{
			$end_locations = $this->base_model->
							run_query("SELECT l.id,tl.travel_location_id, l.location 
										FROM digi_travel_locations tl, digi_locations l 
										WHERE tl.from_loc_id=".$start_id." AND l.id=tl.to_loc_id 
										AND tl.status='Active' AND l.status='Active' 
										ORDER BY l.is_airport='1' DESC");
			}
			$end_loc_options = '<ul>';
			if(count($end_locations) > 0) {				
				foreach($end_locations as $rec) {
					$end_loc_options = $end_loc_options . 
										'<li onclick="assign2(\''.$rec->location.'\', '.$rec->id.','.$rec->travel_location_id.')">'.$rec->location.
										'</li>';
				}

			} else {

				$no_opt = (isset($this->phrases["no drop-off locations available"])) ? $this->phrases["no drop-off locations available"] : "No Drop-off Locations Available";
				$end_loc_options = "<li value=''>".$no_opt.".</li>";
			}
			$end_loc_options .= '</ul>';
		}
		echo $end_loc_options;
	}
	
	/*
	* This function is check for whether the user selects valid via point or not.
	* 
	* @param string
	* @param string
	* @return bool
	*/
	function checkvalidviapoint()
	{
		$journey_booking_details = $this->session->userdata('journey_booking_details');
		$journey_type = $this->input->post('journey_type');
		//neatPrint($journey_booking_details);
		$pick_point = (isset($journey_booking_details[$journey_type]['pick_point'])) ? $journey_booking_details[$journey_type]['pick_point'] : 0;
		
		$drop_point = (isset($journey_booking_details[$journey_type]['drop_point'])) ? $journey_booking_details[$journey_type]['drop_point'] : 0;
		
		$vehicle_id = (isset($journey_booking_details[$journey_type]['vehicle_id'])) ? $journey_booking_details[$journey_type]['vehicle_id'] : 0;
		
		$travel_location_id = (isset($journey_booking_details[$journey_type]['travel_location_id'])) ? $journey_booking_details[$journey_type]['travel_location_id'] : 0;
		
		$pick_date = (isset($journey_booking_details[$journey_type]['pick_date'])) ? $journey_booking_details[$journey_type]['pick_date'] : 0;
		
		$selected_seats = isset($journey_booking_details[$journey_type]['selected_seats']) ? $journey_booking_details[$journey_type]['selected_seats'] : '';
		
		$is_waiting_list = isset($journey_booking_details[$journey_type]['is_waiting_list']) ? $journey_booking_details[$journey_type]['is_waiting_list'] : 'No';
		
		$via_point = $this->input->post('boarding_point['.$vehicle_id.']');
		$via_drop_point = $this->input->post('boarding_point_drop['.$vehicle_id.']');
		
		$check = $this->db->query("SELECT * FROM digi_locations WHERE id = $via_point AND (parent_id = $pick_point OR id = $pick_point)")->result();
				
		$check_drop = $this->db->query("SELECT * FROM digi_locations WHERE id = $via_drop_point AND (parent_id = $drop_point OR id = $drop_point)")->result();
		
		if(count($check) > 0 && count($check_drop) > 0)
		{
			$seats = isset($journey_booking_details[$journey_type]['seats']) ? $journey_booking_details[$journey_type]['seats'] : array();
			//print_r($seats);die();
			$available = TRUE;
			if(count($seats) == 0)
			{
				$available = FALSE;
			}
			else
			{
				foreach($seats as $key => $val)
				{
					$availability = $this->base_model->check_seat_availability($travel_location_id, $vehicle_id, $pick_date, $val, $is_waiting_list);
					//echo $this->db->last_query();die();
					if(count($availability) > 0)
						$available = FALSE;
				}
			}
			
			//var_dump($available);die();
			if($available == FALSE)
			{
				$this->form_validation->set_message('checkvalidviapoint', getPhrase('Some thing went wrong. Seats not available'));
				return FALSE;
			}
			else
			{
				return TRUE;	
			}
		}
		else
		{
			$this->form_validation->set_message('checkvalidviapoint', getPhrase('Some thing went wrong. Not valid dropping points'));
			return FALSE;
		}
	}
	
	
	/**
	 * This function get the fare details of the selected vehicle
	 *
	 * @param	int $vehicle_id
	 * @param	string $seatno
	 * @param	int $travel_location_id
	 * @param	date $pick_date
	 * @param	date $selected_state
	 * @return	string
	 */
	 function getFaredetails()
	 {
		 $tlc_id = $this->input->post('tlc_id');
		 $vehicle_id = $this->input->post('vehicle_id');
		 $shuttle_no = $this->input->post('shuttle_no');
		 $seat = $this->input->post('seatno');
		 $travel_location_id = $this->input->post('travel_location_id');
		 $pick_date = $this->input->post('pick_date');
		 $selected_state = $this->input->post('selected_state');
		 $is_return = $this->input->post('is_return');
		 $wl = $this->input->post('wl');
		 $price_set = $this->input->post('price_set');
		 $available_price_set_seats = $this->input->post('available_price_set_seats');
		 $seat_type = $this->input->post('seat_type');
		 $token = $this->input->post('token');
		 $div_id = $this->input->post('div_id');
		 $seat_display = $this->input->post('seat_display');
		 
		 
		$journey_booking_details = array();
		if(count($this->session->userdata('journey_booking_details')) > 0) 
		{
			$journey_booking_details = $this->session->userdata('journey_booking_details');
		}
		
		$journey_type = 'onward';
		if($is_return == 'yes')
		{
			$journey_type = 'return';
		}
		$old_token = (isset($journey_booking_details[$journey_type]['token'])) ? $journey_booking_details[$journey_type]['token'] : '';
		$old_tlc_id = (isset($journey_booking_details[$journey_type]['tlc_id'])) ? $journey_booking_details[$journey_type]['tlc_id'] : '';
		
		if(count($journey_booking_details) > 0)
		{
			if(isset($journey_booking_details[$journey_type]['token']) && $journey_booking_details[$journey_type]['token'] != $token)
			{
				if(isset($journey_booking_details[$journey_type]['selection_details']))
					unset($journey_booking_details[$journey_type]['selection_details']);
				if(isset($journey_booking_details[$journey_type]['seats']))
					unset($journey_booking_details[$journey_type]['seats']);
				if(isset($journey_booking_details[$journey_type]['vehicle_details']))
					unset($journey_booking_details[$journey_type]['vehicle_details']);
				if(isset($journey_booking_details[$journey_type]['selected_seats']))
					unset($journey_booking_details[$journey_type]['selected_seats']);
				if(isset($journey_booking_details[$journey_type]['selected_seats_total']))
					unset($journey_booking_details[$journey_type]['selected_seats_total']);
				if(isset($journey_booking_details[$journey_type]['adult']))
					$journey_booking_details[$journey_type]['adult'] = 0;
				if(isset($journey_booking_details[$journey_type]['child']))
					$journey_booking_details[$journey_type]['child'] = 0;
				if(isset($journey_booking_details[$journey_type]['infant']))
					$journey_booking_details[$journey_type]['infant'] = 0;
				
				if(isset($journey_booking_details[$journey_type]['basic_fare']))
					unset($journey_booking_details[$journey_type]['basic_fare']);		
				if(isset($journey_booking_details[$journey_type]['service_charge']))
					unset($journey_booking_details[$journey_type]['service_charge']);
				if(isset($journey_booking_details[$journey_type]['total_fare']))
					unset($journey_booking_details[$journey_type]['total_fare']);
				
				if(isset($journey_booking_details['selected_seats_total']))
					unset($journey_booking_details['selected_seats_total']);
				if(isset($journey_booking_details['service_charge']))
					unset($journey_booking_details['service_charge']);
				if(isset($journey_booking_details['total_fare']))
					unset($journey_booking_details['total_fare']);
			}
		}
		
		$selection_details = (isset($journey_booking_details[$journey_type]['selection_details'])) ? $journey_booking_details[$journey_type]['selection_details'] : array();
		//neatPrint($selection_details);
		$selected_adults = $selected_child = $selected_infant = 0;
		$price_set_seats_selected = $selected_shuttle_seats = array();
		if(!empty($selection_details))
		{
			foreach($selection_details as $key => $selection_detail)
			{
				if($selection_detail->shuttle_no == $shuttle_no)
				{
					if($selection_detail->seat_type == 'a' || $selection_detail->seat_type == 'adult') //if Selected adult seats
						$selected_adults++;
					if($selection_detail->seat_type == 'c')
						$selected_child++;
					if($selection_detail->seat_type == 'i')
						$selected_infant++;
					if(!search_price_set($selection_detail->price_set, $price_set_seats_selected))
					{
						$price_set_seats_selected[$selection_detail->price_set] = 1;
					}
					else
					{
						if(!empty($price_set_seats_selected))
						{
							foreach($price_set_seats_selected as $key => $val)
							{
								if($key == $selection_detail->price_set)
								{
									$cc = $val + 1;
									$price_set_seats_selected[$key] = $cc;
								}
							}
						}
					}
				}
			}			
		}
		//if(!empty($selected_shuttle_seats))
		//neatPrint($selected_shuttle_seats);
		//$selected_seats_total = $selected_adults + $selected_child + $selected_infant;
		$selected_seats_total = $selected_adults + $selected_child;
		$max_seats_to_book = $this->config->item('site_settings')->max_seats_to_book;

		$required_seats = $journey_booking_details[$journey_type]['adult'] + $journey_booking_details[$journey_type]['child'];
		$required_seats_adult = $journey_booking_details[$journey_type]['adult'];
		$required_seats_child = $journey_booking_details[$journey_type]['child'];
		$required_seats_infant = $journey_booking_details[$journey_type]['infant'];
		
		if(isset($journey_booking_details['return_date']) && $journey_booking_details['return_date'] != '')
			$required_seats += $required_seats;

		//echo $required_seats.'##';
		$can_process = TRUE;
		$price_set_seats = array();
		if(isset($journey_booking_details[$journey_type]['price_set_seats']))
		$price_set_seats = $journey_booking_details[$journey_type]['price_set_seats'];
	
		$price_set_seats_selected_check = (isset($journey_booking_details[$journey_type]['price_set_seats_selected'])) ? $journey_booking_details[$journey_type]['price_set_seats_selected'] : array();
		
		$can_process = TRUE;
		$failed = 0;
		if($selected_seats_total == $required_seats)
		{
			$can_process = FALSE;
			$failed = '$selected_seats_total == $required_seats';
		}
		elseif($selected_seats_total == $max_seats_to_book)
		{
			$can_process = FALSE;
			$failed = '$selected_seats_total == $max_seats_to_book';
		}
		/*elseif(($required_seats_adult != 0) && ($selected_adults == $required_seats_adult) && $seat_type == 'a') //Already selected required adult seats
		{
			$failed = '($required_seats_adult != 0) && ($selected_adults == $required_seats_adult)';
			$can_process = FALSE;
		}
		elseif(($required_seats_child != 0) && ($selected_child == $required_seats_child) && $seat_type == 'c') //Already selected required child seats
		{
			$failed = '($required_seats_child != 0) && ($selected_child == $required_seats_child)';
			$can_process = FALSE;
		}*/
		elseif(isset($price_set_seats_selected[$price_set]) && $price_set_seats_selected[$price_set] >= $available_price_set_seats )
		{
			$failed = '(isset($price_set_seats_selected[$price_set]) && $price_set_seats_selected[$price_set] >= $available_price_set_seats )';
			$can_process = FALSE;
		}
		if($selected_state == 'selected') {
			$can_process = TRUE;
		}
		//neatPrint($journey_booking_details);
		if( $can_process )
		{
		 $fare_details = $this->base_model->getFaredetails($tlc_id, $selected_state);
		 
		 $result = array();
		 if(count($fare_details))
		 {
			$result['status'] = 1;
			$result['vehicle_id'] = $vehicle_id;
			$result['seat'] = $seat;
			$result['selected_state'] = $selected_state;
			$result['tlc_id'] = $tlc_id;
			$result['token'] = $token;
			$result['div_id'] = $div_id;
			$result['seat_display'] = $seat_display;
			
			$journey_type = 'onward';
			if($is_return == 'yes')
			{
				$journey_type = 'return';
				$journey_booking_details['is_return'] = 'yes';
			}
			
			$journey_booking_details[$journey_type]['vehicle_id'] = $vehicle_id;
			$car_name = $this->base_model->fetch_single_column_value('vehicle', 'name', array('id' => $vehicle_id));
			$journey_booking_details[$journey_type]['car_name'] = $car_name;
			$journey_booking_details[$journey_type]['shuttle_no'] = $shuttle_no;
			$journey_booking_details[$journey_type]['tlc_id'] = $tlc_id;
			$journey_booking_details[$journey_type]['token'] = $token;
			$journey_booking_details[$journey_type]['pick_time'] = $fare_details[0]->start_time;
			$journey_booking_details[$journey_type]['destination_time'] = $fare_details[0]->destination_time;
			$journey_booking_details[$journey_type]['elapsed_days'] = $fare_details[0]->elapsed_days;
			
			
			$journey_booking_details[$journey_type]['pick_point_view'] = $this->get_data('pick_point_view', $journey_booking_details,$journey_type);
			
			$journey_booking_details[$journey_type]['pick_point'] = $this->get_data('pick_point', $journey_booking_details,$journey_type);
			$pick_point = $journey_booking_details[$journey_type]['pick_point'];
			
			$journey_booking_details[$journey_type]['drop_point_view'] = $this->get_data('drop_point_view', $journey_booking_details,$journey_type);
			
			$journey_booking_details[$journey_type]['drop_point'] = $this->get_data('drop_point', $journey_booking_details,$journey_type);
			$drop_point = $journey_booking_details[$journey_type]['drop_point'];
			
			$journey_booking_details[$journey_type]['travel_location_id'] = $this->get_data('travel_location_id', $journey_booking_details,$journey_type);
			
			$journey_booking_details[$journey_type]['pick_date'] = $this->get_data('pick_date', $journey_booking_details,$journey_type);
			
			$journey_booking_details[$journey_type]['pick_point_name'] = $this->get_data('pick_point_name', $journey_booking_details,$journey_type);
			
			$journey_booking_details[$journey_type]['drop_point_name'] = $this->get_data('drop_point_name', $journey_booking_details,$journey_type);			
			//$price_set = (isset($journey_booking_details[$journey_type]['price_set'])) ? $journey_booking_details[$journey_type]['price_set'] : $price_set;
			
			$journey_booking_details[$journey_type]['price_set'] = $price_set;
			if($selected_state == 'available')
			{				 
				$fare_details[0]->price_set = $price_set;
				$fare_details[0]->shuttle_no = $shuttle_no;
				$fare_details[0]->pick_date = $pick_date;
				$fare_details[0]->seat = $seat;
				$fare_details[0]->seat_type = $seat_type;
				if($fare_details[0]->to_loc_id != $drop_point)
				{
					$fare_details[0]->has_connection = 'yes';
					$journey_booking_details[$journey_type]['travel_location_id1'] = $travel_location_id;
				}
				else
				{
					$journey_booking_details[$journey_type]['travel_location_id2'] = $travel_location_id;
				}
				
				if( $fare_details[0]->special_fare == 'yes' )
				{
					$specials = $this->db->query('SELECT * FROM '.$this->db->dbprefix('travel_location_costs_special').' WHERE tlc_id = '.$fare_details[0]->tlc_id.' AND "'.date('Y-m-d', strtotime($fare_details[0]->pick_date)).'" BETWEEN special_start AND special_end AND status="active" ORDER BY updated DESC LIMIT 1')->result();
					if(empty($specials))
					{
						$fare_details2 = (isset($fare_details[0]) && $fare_details[0]->fare_details != '') ? json_decode($fare_details[0]->fare_details) : array();
					}
					else
					{
						$fare_details2 = (isset($specials[0]->fare_details_special) && $specials[0]->fare_details_special != '') ? json_decode($specials[0]->fare_details_special) : array();	
					}
				} 
				else 
				{
						$fare_details2 = (isset($fare_details[0]) && $fare_details[0]->fare_details != '') ? json_decode($fare_details[0]->fare_details) : array();
				}
				$fare_details2 = (array)$fare_details2;
								
				$selected_adult = $journey_booking_details[$journey_type]['selected_adult'];
				$selected_child = $journey_booking_details[$journey_type]['selected_child'];
				$selected_infant = $journey_booking_details[$journey_type]['selected_infant'];

				$required_adult = $journey_booking_details[$journey_type]['adult'];
				$required_child = $journey_booking_details[$journey_type]['child'];
				$required_infant = $journey_booking_details[$journey_type]['infant'];
			
				if($selected_adult < $required_adult)
				{
					$seat_type = 'a';
					$journey_booking_details[$journey_type]['selected_adult']++;
				}
				elseif($selected_child < $required_child)
				{
					$seat_type = 'c';
					$journey_booking_details[$journey_type]['selected_child']++;
				}
				elseif($selected_infant < $required_infant)
				{
					$seat_type = 'i';
					$journey_booking_details[$journey_type]['selected_infant']++;
				}			
				$cost = getPrice($price_set, $fare_details2, $seat_type);
				$fare_details[0]->seat_cost = $cost;
				
				$cost_adult = getPrice($price_set, $fare_details2, 'a');
				$fare_details[0]->seat_cost_adult = $cost_adult;
				$cost_child = getPrice($price_set, $fare_details2, 'c');
				$fare_details[0]->seat_cost_child = $cost_child;
				$cost_infant = getPrice($price_set, $fare_details2, 'i');
				$fare_details[0]->seat_cost_infant = $cost_infant;
				$fare_details[0]->seat_display = $seat_display;
				
				//Check if there is any vehicle replacement
				$query = "SELECT v.*,vc.category FROM digi_travel_location_costs_drivers tlcd INNER JOIN digi_vehicle v ON v.id = tlcd.vehicle_id INNER JOIN digi_vehicle_categories vc ON v.category_id = vc.id WHERE tlc_id = ".$fare_details[0]->tlc_id." AND ('".date('Y-m-d', strtotime($fare_details[0]->pick_date))."' BETWEEN special_start AND special_end) ORDER BY special_start DESC LIMIT 1";
				$vehicle_change = $this->db->query($query)->result();
				if(!empty($vehicle_change))
				{
					$veh = $vehicle_change[0];
					$fare_details[0]->old_vehicle_id = $fare_details[0]->id;
					$fare_details[0]->id = $veh->id;
					$fare_details[0]->category_id = $veh->category_id;
					$fare_details[0]->model = $veh->model;
					$fare_details[0]->name = $veh->name;
					$fare_details[0]->number_plate = $veh->number_plate;
					$fare_details[0]->description = $veh->description;
					$fare_details[0]->passenger_capacity = $veh->passenger_capacity;
					$fare_details[0]->large_luggage_capacity = $veh->large_luggage_capacity;
					$fare_details[0]->small_luggage_capacity = $veh->small_luggage_capacity;
					$fare_details[0]->fuel_type = $veh->fuel_type;
					$fare_details[0]->total_vehicles = $veh->total_vehicles;
					
					$fare_details[0]->seat_rows = $veh->seat_rows;
					$fare_details[0]->seat_columns = $veh->seat_columns;
					$fare_details[0]->seats_empty = $veh->seats_empty;
					$fare_details[0]->child_seats = $veh->child_seats;
					$fare_details[0]->seats_child = $veh->seats_child;
					$fare_details[0]->availability = $veh->availability;
					$fare_details[0]->image = $veh->image;
					$fare_details[0]->base_fare = $veh->base_fare;
					$fare_details[0]->cost_per_km = $veh->cost_per_km;
					$fare_details[0]->cost_per_minute = $veh->cost_per_minute;
					$fare_details[0]->status = $veh->status;
					$fare_details[0]->has_driver_seat = $veh->has_driver_seat;
					
					$fare_details[0]->category = $veh->category;
				}
				
				$journey_booking_details[$journey_type]['selection_details'][$shuttle_no.'_'.$token.'_'.$seat] = $fare_details[0];
				
								
				$journey_booking_details[$journey_type]['seats'][$shuttle_no.'_'.$token.'_'.$seat.'_'.str_replace('/','#',$pick_date).'_'.$vehicle_id.'_'.$tlc_id.'_'.$travel_location_id] = $seat;
				
				$price_set_seats = array();
				if(isset($journey_booking_details[$journey_type]['price_set_seats']))
					$price_set_seats = $journey_booking_details[$journey_type]['price_set_seats'];
				$price_set_seats[$price_set] = $available_price_set_seats;
				$journey_booking_details[$journey_type]['price_set_seats'] = $price_set_seats;
				
				$price_set_seats_selected = array();
				if(isset($journey_booking_details[$journey_type]['price_set_seats_selected']))
					$price_set_seats_selected = $journey_booking_details[$journey_type]['price_set_seats_selected'];
				if(count($price_set_seats_selected) > 0)
				{
					foreach($price_set_seats_selected as $psss => $c)
					{
						
						if($psss == $price_set)
						{
							$cc = $c + 1;
							$price_set_seats_selected[$psss] = $cc;
						}
						else
						$price_set_seats_selected[$psss] = $c;
					}
				}
				else
				{
					$price_set_seats_selected[$price_set] = 1;
				}
				//neatPrint($price_set_seats_selected);
				$journey_booking_details[$journey_type]['price_set_seats_selected'] = $price_set_seats_selected;
			}
			else
			{
				if(isset($journey_booking_details[$journey_type]['selection_details']) && isset($journey_booking_details[$journey_type]['selection_details'][$shuttle_no.'_'.$token.'_'.$seat]))
				{
					unset($journey_booking_details[$journey_type]['selection_details'][$shuttle_no.'_'.$token.'_'.$seat]);
				}
				if(isset($journey_booking_details[$journey_type]['seats']) && isset($journey_booking_details[$journey_type]['seats'][$shuttle_no.'_'.$token.'_'.$seat]))
				{
					unset($journey_booking_details[$journey_type]['seats'][$shuttle_no.'_'.$token.'_'.$seat]);
				}				
				if(isset($journey_booking_details[$journey_type]['price_set_seats_selected']))
				{
					$price_set_seats_selected = $journey_booking_details[$journey_type]['price_set_seats_selected'];
					if(count($price_set_seats_selected) > 0)
					{
						foreach($price_set_seats_selected as $psss => $c)
						{
							if($psss == $price_set)
							$price_set_seats_selected[$psss] = $c--;
							else
							$price_set_seats_selected[$psss] = $c;
						}
					}
				}
			}
			$this->session->set_userdata('journey_booking_details', $journey_booking_details);
			$result['selected_seats'] = '';
			$result['selected_seats_no'] = '';			
			$result['selected_seats_total'] = 0;
			$result['selected_shuttles_total'] = 0;
			$result['selected_seats_adult'] = 0;
			$result['selected_seats_child'] = 0;
			
			$result['basic_fare'] = 0;
			$result['service_charge'] = 0;
			$result['total_fare'] = 0;
			$result['has_connection'] = 'no';
			
			if($fare_details[0]->to_loc_id != $drop_point)
			{
				$result['has_connection'] = 'yes';
			}
			if($fare_details[0]->from_loc_id != $pick_point) //In the connected vehicle, If user chooses second vehicle only, then we need to validate
			{
				$result['has_connection'] = 'yes';
			}
			$journey_booking_details = $this->session->userdata('journey_booking_details');
			$vehicle = $this->base_model->get_vehicle_details($vehicle_id);
			if($tlc_id == 59)
			{
			//neatPrint($journey_booking_details);
			}
			//neatPrint($journey_booking_details);
			$journey_booking_details[$journey_type]['vehicle_details'] = array();
			if(count($vehicle) > 0)
				$journey_booking_details[$journey_type]['vehicle_details'] = $vehicle[0];
			
			$amount = 0;
			$shuttles = $shuttles_tlcids = $selected_shuttle_seats = array();
					
			if(isset($journey_booking_details[$journey_type]['selection_details']) && count($journey_booking_details[$journey_type]['selection_details']) > 0)
			{
				foreach($journey_booking_details[$journey_type]['selection_details'] as $v => $selected)
				{
					$parts = explode('_', $v);
					if($parts[1] == $token)
					{
						$cost = $selected->seat_cost;						
						$cost_infant = $selected->seat_cost_infant;						
						if($cost == '') 
							$cost = 0;
						
						$result['selected_seats'] .= $parts[2].', ';
						$result['selected_seats_no'] .= $selected->seat_display.', ';
						if(!in_array($selected->shuttle_no, $shuttles))
						{
							$shuttles[] = $selected->shuttle_no;
							$result['selected_shuttles_total']++;
						}
						if(!in_array($selected->tlc_id, $shuttles_tlcids))
						{
							$shuttles_tlcids[] = $selected->tlc_id;
						}
												
						$result['selected_seats_total']++;
						if($result['selected_seats_total'] <= $required_seats_infant) //We are adding infant price
						$cost = $cost + $cost_infant;
						if($selected->seat_type == 'a' || $selected->seat_type == 'adult')
						$result['selected_seats_adult']++;
						if($selected->seat_type == 'c')
						$result['selected_seats_child']++;
						if(isset($selected->service_tax_type) && $selected->service_tax_type == 'percent')
						{
							$service_tax = (isset($selected->service_tax)) ? $selected->service_tax : 0;
							$tax = ($cost * $service_tax)/100; //Calculating Service tax on base price
							$result['service_charge'] += $tax;
							$result['basic_fare'] += $cost;
							
							$amount = $cost + $tax;
							$result['total_fare'] += $amount;						
						}
						else
						{
							$tax = (isset($selected->service_tax)) ? $selected->service_tax : 0;
							$result['service_charge'] += $tax;
							$result['basic_fare'] += $cost;
							
							$amount = $cost + $tax;
							$result['total_fare'] += $amount;
						}	
					}
				}
				
				$journey_booking_details[$journey_type]['shuttles_count'] = count($shuttles_tlcids);
				//To count selected seats in each shuttle
				for($s = 0; $s < count($shuttles_tlcids); $s++)
				{
					$selected_adults_temp = $selected_child_temp = $selected_infant_temp = $basic_fare_temp = $service_tax_temp = $total_fare_temp = 0;
					$price_set_seats_selected_temp = array();
					foreach($journey_booking_details[$journey_type]['selection_details'] as $v => $selection_detail)
					{
						if($selection_detail->tlc_id == $shuttles_tlcids[$s])
						{
							/* Price calculation for each shuttle start*/
							$cost = $selection_detail->seat_cost;
							$cost_infant = $selection_detail->seat_cost_infant;
							
							$selected_seats_total = $selected_adults_temp + $selected_child_temp;
							if($selected_seats_total < $required_seats_infant) //Adding child cost
							$cost = $cost + $cost_infant;
						
							$basic_fare_temp += $cost;
							
							if(isset($selection_detail->service_tax_type) && $selection_detail->service_tax_type == 'percent')
							{
								$service_tax = (isset($selection_detail->service_tax)) ? $selection_detail->service_tax : 0;
								$tax = ($cost * $service_tax)/100;
								$service_tax_temp += $tax;								
								$amount = $cost + $tax;
								$total_fare_temp += $amount;					
							}
							else
							{
								$tax = (isset($selection_detail->service_tax)) ? $selection_detail->service_tax : 0;
								$service_tax_temp += $tax;								
								$amount = $cost + $tax;
								$total_fare_temp += $amount;
							}
							/* Price calculation for each shuttle end*/
						
							if($selection_detail->seat_type == 'a' || $selection_detail->seat_type == 'adult') //if Selected adult seats
								$selected_adults_temp++;
							if($selection_detail->seat_type == 'c')
								$selected_child_temp++;
							if($selection_detail->seat_type == 'i')
								$selected_infant_temp++;
							if(!search_price_set($selection_detail->price_set, $price_set_seats_selected_temp))
							{
								$price_set_seats_selected_temp[$selection_detail->price_set] = 1;
							}
							else
							{
								if(!empty($price_set_seats_selected_temp))
								{
									foreach($price_set_seats_selected_temp as $key => $val)
									{
										if($key == $selection_detail->price_set)
										{
											$cc = $val + 1;
											$price_set_seats_selected_temp[$key] = $cc;
										}
									}
								}
							}
						}
					}
					$selected_shuttle_seats[$shuttles_tlcids[$s]] = 
						array('total_seats' => $selected_adults_temp+$selected_child_temp,
						'total_seats_adult' => $selected_adults_temp,
						'total_seats_child' => $selected_child_temp,
						'total_seats_infant' => $selected_infant_temp,
						'basic_fare' => $basic_fare_temp,
						'service_tax' => $service_tax_temp,
						'total_fare' => $total_fare_temp,
						);
				}
			} //Fare Calculation Done
			
			$result['selected_shuttle_seats'] = $selected_shuttle_seats;
			$journey_booking_details[$journey_type]['price_details'] = $selected_shuttle_seats;
			$journey_booking_details[$journey_type]['basic_fare'] = $result['basic_fare'];
			$journey_booking_details[$journey_type]['service_charge'] = $result['service_charge'];
			$journey_booking_details[$journey_type]['total_fare'] = $result['total_fare'];
			
			//neatPrint($journey_booking_details);
			//$result['basic_fare'] = number_format($result['basic_fare'], 2);
			//$result['service_charge'] = number_format($result['service_charge'], 2);
			//$result['total_fare'] = number_format($result['total_fare'], 2);
			
			$result['basic_fare'] = number_format($result['basic_fare'], 2);
			$result['service_charge'] = number_format($result['service_charge'],2);
			$result['total_fare'] = number_format($result['total_fare'], 2);
			
			$result['pick_point'] = $pick_point;
			$result['drop_point'] = $drop_point;
			$result['from_loc_id'] = $fare_details[0]->from_loc_id;
			$result['to_loc_id'] = $fare_details[0]->to_loc_id;
			
			//Save the details in session so that we may use later
			$journey_booking_details[$journey_type]['is_waiting_list'] = ($wl == '') ? 'No' : 'Yes';
			$journey_booking_details[$journey_type]['selected_seats'] = $result['selected_seats'];
			$journey_booking_details[$journey_type]['selected_seats_no'] = $result['selected_seats_no'];
			$journey_booking_details[$journey_type]['selected_seats_total'] = $result['selected_seats_total'];
			$journey_booking_details[$journey_type]['selected_shuttles_total'] = $result['selected_shuttles_total'];			
			
			$selected_seats_total_onward = (isset($journey_booking_details['onward']['selected_seats_total'])) ? $journey_booking_details['onward']['selected_seats_total'] : 0;
			$selected_seats_total_return = (isset($journey_booking_details['return']['selected_seats_total'])) ? $journey_booking_details['return']['selected_seats_total'] : 0;
			
			$journey_booking_details['selected_seats_total'] = $selected_seats_total_onward + $selected_seats_total_return;
			
			$journey_booking_details['service_charge'] = $result['service_charge'];
			$journey_booking_details['total_fare'] = $result['total_fare'];
			
			$journey_booking_details[$journey_type]['travel_location_id'] = $travel_location_id;			
			$this->session->set_userdata('journey_booking_details', $journey_booking_details);
		 }
		 else
		 {
			$result['status'] = 0;
			$result['message'] = getPhrase('Seats aleady booked or No Details found');
		 }
		}
		else
		{
			$result['status'] = 0;
			$result['token'] = $token;
			$result['div_id'] = $div_id;
			if($selected_seats_total == $required_seats ) {
			$result['message'] = getPhrase('You can book only '.$required_seats.' seats. Please book some more seats in next transaction');
			}
			elseif($selected_seats_total == $max_seats_to_book)
			{
			$result['message'] = getPhrase('You can book only '.$max_seats_to_book.' seats. Please book some more seats in next transaction');	
			}
			elseif(($required_seats_adult != 0) && ($selected_adults == $required_seats_adult) && $seat_type) //Already selected required adult seats
			{
				$result['message'] = getPhrase('You already select '.$required_seats_adult.' adult seat(s).');
			}
			elseif(($required_seats_child != 0) && ($selected_child == $required_seats_child)) //Already selected required child seats
			{
				$result['message'] = getPhrase('You already select '.$required_seats_child.' child seat(s).');
			}
			elseif(isset($price_set_seats_selected[$price_set]) && $price_set_seats_selected[$price_set] >= $available_price_set_seats )
			{
				$result['message'] = getPhrase('There are only '.$available_price_set_seats.' seat in this price set. Please book some more seats in next transaction');	
			}
			elseif($selected_seats_total == $available_price_set_seats)
			{
			$result['message'] = getPhrase('There are only '.$available_price_set_seats.' seat in this price set. Please book some more seats in next transaction');	
			}
			else {
			$result['message'] = getPhrase('You can book only '.$max_seats_to_book.' seats. Please book some more seats in next transaction');
			}
		}
		 echo json_encode($result);
	 }
	 
	 /**
	 * This function takes key of session and return the value
	 *
	 * @param	miexed 
	 * @return	void
	 */
	 function get_data($key, $details = array(), $journey_type = 'onward')
	 {
		if(count($details) == 0)
		$journey_booking_details = $this->session->userdata('journey_booking_details');
		else
		$journey_booking_details = $details;
		return (isset($journey_booking_details[$journey_type][$key])) ? $journey_booking_details[$journey_type][$key] : 0;
	 }
	 
	 /**
	 * This function takes all passenger details. This funciton specially used for seat booking system
	 *
	 * @param	miexed 
	 * @return	void
	 */
	 
	 function passenger_details()
	 {

		 $this->checkfor_valid();

		 $this->data['message'] = $this->session->flashdata('message');
		/* Check For Form Submission */
		if($this->input->post()) 
		{ 
			/* Form Validations */
			$this->form_validation->set_rules('primary_passenger_name', getPhrase('Name'),'trim|required');
			
			$this->form_validation->set_rules('primary_passenger_email', getPhrase('Email'),'trim|required|valid_email');

			$this->form_validation->set_rules('primary_passenger_cpa', getPhrase('complete pickup and drop off address'),'trim|required');

			//$this->form_validation->set_rules('primary_passenger_cda', getPhrase('Complete drop-off address'),'trim|required');

			$this->form_validation->set_rules(
			'primary_passenger_phone_code', getPhrase('Phone Code'), 
			'trim|required');
			$this->form_validation->set_rules(
			'primary_passenger_phone', getPhrase('Phone'), 
			'trim|required');		

			$this->form_validation->set_rules('primary_passenger_gender', getPhrase('Gender'), 'trim|required');
			$this->form_validation->set_rules('primary_passenger_age', getPhrase('Age'), 'trim|required');
			$this->form_validation->set_rules('payment_type', getPhrase('Payment method'),'trim|required');
			$journey_booking_details = $this->session->userdata('journey_booking_details');
			//print_r($this->input->post());
			//neatPrint($journey_booking_details);
			if(isset($_POST['passenger_name']))
			{
			$this->form_validation->set_rules(
			'passenger_name[]', getPhrase('Name'), 
			'trim|required');			
			$this->form_validation->set_rules(
			'passenger_age[]', getPhrase('Age'), 
			'trim|required');
			$this->form_validation->set_rules(
			'passenger_gender[]', getPhrase('Gender'), 
			'trim|required');
			}
			
			if(isset($_POST['infant_passenger_name']))
			{
			$this->form_validation->set_rules('infant_passenger_name[]', getPhrase('Name'),'trim|required');			
			$this->form_validation->set_rules('infant_passenger_age[]', getPhrase('Age'),'trim|required');
			$this->form_validation->set_rules('infant_passenger_gender[]', getPhrase('Gender'),'trim|required');
			}
			
			if($this->form_validation->run() == true) {
			$gateway_details = $this->base_model->get_payment_gateways($this->input->post('payment_type'));
			if(count($gateway_details) > 0)
			{
				$journey_booking_details = $this->session->userdata('journey_booking_details');
				$journey_type = 'onward';
				$has_return_journey = 'no';
				if(isset($journey_booking_details['is_return']) && $journey_booking_details['is_return'] == 'yes')
				{
					$journey_type = 'return';
					$has_return_journey = 'yes';
				}
				//neatPrint($journey_booking_details);			
				$booking_ref = date('ymdHis');
				$cost_of_journey = 0;
				$onward_amount = (isset($journey_booking_details['onward']['total_fare'])) ? $journey_booking_details['onward']['total_fare'] : 0;
				
				$onward_insurance = (isset($journey_booking_details['onward']['insurance_amount'])) ? $journey_booking_details['onward']['insurance_amount'] : 0;
				
				$return_amount = (isset($journey_booking_details['return']['total_fare'])) ? $journey_booking_details['return']['total_fare'] : 0;
				
				$return_insurance = (isset($journey_booking_details['return']['insurance_amount'])) ? $journey_booking_details['return']['insurance_amount'] : 0;
				
				$discount_amount = (isset($journey_booking_details['disount_amount'])) ? $journey_booking_details['disount_amount'] : 0;
				
				//Prices taken from price details array
				$onward_basic_fare = $onward_service_charge = 0;
				if(isset($journey_booking_details['onward']['price_details']) && count($journey_booking_details['onward']['price_details']) > 0)
				{
					foreach($journey_booking_details['onward']['price_details'] as $key => $val)
					{
						$onward_basic_fare += $val['basic_fare'];
						$onward_service_charge += $val['service_tax'];
					}
				}
				$return_basic_fare = $return_service_charge = 0;
				if(isset($journey_booking_details['return']['price_details']) && count($journey_booking_details['return']['price_details']) > 0)
				{
					foreach($journey_booking_details['return']['price_details'] as $key => $val)
					{
						$return_basic_fare += $val['basic_fare'];
						$return_service_charge += $val['service_tax'];
					}
				}				
				
				$cost_of_journey = ($onward_basic_fare + $onward_service_charge + $onward_insurance) + ($return_basic_fare + $return_service_charge + $return_insurance) - $discount_amount;
				$journey_booking_details['payment_type'] 	= $gateway_details[0]->gateway_title;
				$journey_booking_details['payment_gateway_id'] 	= $this->input->post('payment_type');
				$journey_booking_details['cost_of_journey'] = $cost_of_journey;
				
				$journey_booking_details['onward']['cost_of_journey'] = abs(($onward_basic_fare + $onward_service_charge + $onward_insurance) - $discount_amount);
				
				$journey_booking_details['onward']['disount_amount'] = $discount_amount;
				
				if($has_return_journey == 'yes')
				{
					$journey_booking_details['return']['cost_of_journey'] = abs($return_basic_fare + $return_service_charge + $return_insurance); //Discount we are deducting from onward journey to avoid loss of confusion
					$journey_booking_details['return']['disount_amount'] = 0;
				}				
				$journey_booking_details['booking_ref'] 	= $booking_ref;
				$journey_booking_details['onward']['payer_name'] 	= $this->input->post('primary_passenger_name');
				$journey_booking_details['onward']['phone_code'] 	= $this->input->post('primary_passenger_phone_code');
				$journey_booking_details['onward']['phone'] 	= $this->input->post('primary_passenger_phone');
				$journey_booking_details['onward']['email'] 	= $this->input->post('primary_passenger_email');
				$journey_booking_details['onward']['complete_pickup_address'] 	= $this->input->post('primary_passenger_cpa');
				
				if($has_return_journey == 'yes')
				{
					$journey_booking_details['return']['payer_name'] 	= $this->input->post('primary_passenger_name');
					$journey_booking_details['return']['phone_code'] 	= $this->input->post('primary_passenger_phone_code');
					$journey_booking_details['return']['phone'] 	= $this->input->post('primary_passenger_phone');
					$journey_booking_details['return']['email'] 	= $this->input->post('primary_passenger_email');
					$journey_booking_details['return']['complete_pickup_address'] 	= $this->input->post('primary_passenger_cpa');
				}
				//$journey_booking_details[$journey_type]['complete_destination_address'] 	= $this->input->post('primary_passenger_cda');
				
				$passenger_details_entered = array();
				$total_shuttles = array(); //This is to distribute the discount.
				
				$onward_passenger = (isset($journey_booking_details['onward'])) ? $journey_booking_details['onward']['selection_details'] : array();
				if(!empty($onward_passenger))
				{
					$inner_shuttles = array();
					foreach($onward_passenger as $selection)
					{
						if(!in_array($selection->shuttle_no, $inner_shuttles))
						{
							$inner_shuttles[] = $selection->shuttle_no;
							
							$p = $pp = 0;
							foreach($onward_passenger as $inner_selection)
							{
								if(!in_array($selection->shuttle_no, $total_shuttles))
								$total_shuttles[] = $selection->shuttle_no;
							
								if($selection->shuttle_no == $inner_selection->shuttle_no)
								{
									if($p == 0)
									{
										$inner_selection->passenger_name = $this->input->post('primary_passenger_name');
										$inner_selection->passenger_phone = $this->input->post('primary_passenger_phone');
										$inner_selection->passenger_phone_code = $this->input->post('primary_passenger_phone_code');
										$inner_selection->passenger_gender = $this->input->post('primary_passenger_gender');
										$inner_selection->passenger_complete_pickup_address = $this->input->post('primary_passenger_cpa');
										$inner_selection->passenger_is_primary = 'Yes';	
										$inner_selection->passenger_email = $this->input->post('primary_passenger_email');							
									}
									else
									{
										if(isset($_POST['passenger_name']) && count($_POST['passenger_name']) > 0)
										{
											$passenger_name = $_POST['passenger_name'];				
											$passenger_age = $_POST['passenger_age'];
											$passenger_gender = $_POST['passenger_gender'];								
											$inner_selection->passenger_name = $passenger_name[$pp];
											$inner_selection->passenger_gender = $passenger_gender[$pp];
											$inner_selection->passenger_age = $passenger_age[$pp];
											$inner_selection->passenger_complete_pickup_address = $this->input->post('primary_passenger_cpa');
											$inner_selection->passenger_is_primary = 'No';	
											$pp++;
										}							
									}
									$p++;
								}								
							}							
						}												
					}
					$selection_details = $journey_booking_details['onward']['selection_details'];
					
					$selected_adult = $selected_child = $selected_infant = 0;
					
					$adults = isset($journey_booking_details['adult']) ? $journey_booking_details['adult'] : 0;
					$child = isset($journey_booking_details['child']) ? $journey_booking_details['child'] : 0;
					$infant = isset($journey_booking_details['infant']) ? $journey_booking_details['infant'] : 0;

					foreach($selection_details as $selection)
					{
						$seat_type = 'adult';
						if($selected_adult < $adults)
						{
						$seat_type = 'a';
						$selected_adult++;
						}					
						elseif($selected_child < $child)
						{
						$seat_type = 'c';
						$selected_child++;
						}
						
						if($selection->passenger_is_primary == 'Yes')
						{
							$journey_booking_details['onward']['passenger_details'][] = array('name' => $selection->passenger_name,
								'phone' => $selection->passenger_phone,
								'phone_code' => $selection->passenger_phone_code,
								'seat' => $selection->seat,
								'seat_no' => $selection->seat_display,
								'gender' => $selection->passenger_gender,
								//'age' => $selection->passenger_age,
								'email' => $selection->passenger_email,
								'complete_pickup_address' => $selection->passenger_complete_pickup_address,
								'is_primary' => 'Yes',
								//'passenger_type' => $selection->seat_type,
								'passenger_type' => $seat_type,
								'price_set' => $selection->price_set,
								'shuttle_no' => $selection->shuttle_no,
							);
						}
						else
						{
							$journey_booking_details['onward']['passenger_details'][] = array('name' => $selection->passenger_name,
								//'phone' => $selection->passenger_phone,
								//'phone_code' => $selection->passenger_phone_code,
								'seat' => $selection->seat,
								'seat_no' => $selection->seat_display,
								'gender' => $selection->passenger_gender,
								//'age' => $selection->passenger_age,
								//'email' => $selection->passenger_email,
								//'complete_pickup_address' => $selection->passenger_complete_pickup_address,
								'is_primary' => 'No',
								'passenger_type' => $seat_type,
								'price_set' => $selection->price_set,
								'shuttle_no' => $selection->shuttle_no,
							);
						}
					}
				}
				
				$return_passenger = (isset($journey_booking_details['return']) && isset($journey_booking_details['return']['selection_details'])) ? $journey_booking_details['return']['selection_details'] : array();
				if(!empty($return_passenger))
				{
					$inner_shuttles = array();
					foreach($return_passenger as $selection)
					{
						if(!in_array($selection->shuttle_no, $inner_shuttles))
						{
							$inner_shuttles[] = $selection->shuttle_no;					
							$p = $pp = 0;
							if(!in_array($selection->shuttle_no, $total_shuttles))
								$total_shuttles[] = $selection->shuttle_no;
							foreach($return_passenger as $inner_selection)
							{
								if($selection->shuttle_no == $inner_selection->shuttle_no)
								{
									if($p == 0)
									{
										$inner_selection->passenger_name = $this->input->post('primary_passenger_name');
										$inner_selection->passenger_phone = $this->input->post('primary_passenger_phone');
										$inner_selection->passenger_phone_code = $this->input->post('primary_passenger_phone_code');
										$inner_selection->passenger_gender = $this->input->post('primary_passenger_gender');
										$inner_selection->passenger_complete_pickup_address = $this->input->post('primary_passenger_cpa');
										$inner_selection->passenger_is_primary = 'Yes';	
										$inner_selection->passenger_email = $this->input->post('primary_passenger_email');							
									}
									else
									{
										if(isset($_POST['passenger_name']) && count($_POST['passenger_name']) > 0)
										{
											$passenger_name = $_POST['passenger_name'];				
											$passenger_age = $_POST['passenger_age'];
											$passenger_gender = $_POST['passenger_gender'];								
											$inner_selection->passenger_name = $passenger_name[$pp];
											$inner_selection->passenger_gender = $passenger_gender[$pp];
											$inner_selection->passenger_age = $passenger_age[$pp];
											$inner_selection->passenger_complete_pickup_address = $this->input->post('primary_passenger_cpa');
											$inner_selection->passenger_is_primary = 'No';	
											$pp++;
										}							
									}
									$p++;
								}								
							}							
						}												
					}
					
					$selection_details = $journey_booking_details['return']['selection_details'];
					$selected_adult = $selected_child = $selected_infant = 0;
					foreach($selection_details as $selection)
					{
						$seat_type = 'adult';
						if($selected_adult < $adults)
						{
						$seat_type = 'a';
						$selected_adult++;
						}					
						elseif($selected_child < $child)
						{
						$seat_type = 'c';
						$selected_child++;
						}
						if($selection->passenger_is_primary == 'Yes')
						{							
							$journey_booking_details['return']['passenger_details'][] = array('name' => $selection->passenger_name,
								'phone' => $selection->passenger_phone,
								'phone_code' => $selection->passenger_phone_code,
								'seat' => $selection->seat,
								'seat_no' => $selection->seat_display,
								'gender' => $selection->passenger_gender,
								//'age' => $selection->passenger_age,
								'email' => $selection->passenger_email,
								'complete_pickup_address' => $selection->passenger_complete_pickup_address,
								'is_primary' => 'Yes',
								'passenger_type' => $seat_type,
								'price_set' => $selection->price_set,
								'shuttle_no' => $selection->shuttle_no,
							);
						}
						else
						{
							$journey_booking_details['return']['passenger_details'][] = array('name' => $selection->passenger_name,
								//'phone' => $selection->passenger_phone,
								//'phone_code' => $selection->passenger_phone_code,
								'seat' => $selection->seat,
								'seat_no' => $selection->seat_display,
								'gender' => $selection->passenger_gender,
								//'age' => $selection->passenger_age,
								//'email' => $selection->passenger_email,
								//'complete_pickup_address' => $selection->passenger_complete_pickup_address,
								'is_primary' => 'No',
								'passenger_type' => $seat_type,
								'price_set' => $selection->price_set,
								'shuttle_no' => $selection->shuttle_no,
							);
						}
					}
				}
				
				//Infants
				$infants = isset($journey_booking_details['onward']['infant']) ? $journey_booking_details['onward']['infant'] : 0;
				if($infants > 0)
				{
					$passenger_name = $_POST['infant_passenger_name'];				
					$passenger_age = $_POST['infant_passenger_age'];
					$passenger_gender = $_POST['infant_passenger_gender'];
					
					$infant = array();
					for($i = 0; $i < $infants; $i++)
					{
						$infant[] = array('infant_name' => $passenger_name[$i],
						'infant_gender' => $passenger_gender[$i],
						'infant_passenger_type' => 'infant',
						'infant_age' => $passenger_age[$i],
						);
					}
					if(!empty($infant))
					{
						$journey_booking_details['onward']['infant_passenger_details'] = $infant;
						if(isset($journey_booking_details['return']))
						{
							$journey_booking_details['return']['infant_passenger_details'] = $infant;
						}
					}
				}
				
								
				$journey_booking_details['registered_name'] = $this->input->post('primary_passenger_name');
				$journey_booking_details['email'] = $this->input->post('primary_passenger_email');
				$journey_booking_details['complete_pickup_address'] = $this->input->post('primary_passenger_cpa');
				
				$journey_booking_details['phone'] = $this->input->post('primary_passenger_phone');
				$journey_booking_details['phone_code'] = $this->input->post('primary_passenger_phone_code');
				$journey_booking_details['total_shuttles'] = count($total_shuttles);
				//neatPrint($journey_booking_details);
				$this->session->set_userdata('final_details', $journey_booking_details);
				
				if($gateway_details[0]->gateway_title == "Paypal")
				{
					$config['business'] = '';
					$config['cpp_header_image'] = base_url()."seat/assets/system_design/images/". strtolower($gateway_details[0]->gateway_title).'.png';
					$config['return'] 			= base_url().'bookingseat/paypal_success';
					$config['cancel_return'] 	= base_url().'bookingseat/payment_cancel';
					$config['notify_url'] 		= '';//'process_payment.php'; //IPN Post
					$config['production'] 	= true;
					$config['currency_code'] 		= 'USD';
					foreach($gateway_details as $index => $value) {
						if($value->field_key == 'paypal_email') {
							$config['business'] = $value->gateway_field_value;
						}
						if($value->field_key == 'account_type') {
							$config['production'] = false;
						}
						if($value->field_key == 'currency') {
							$config['currency_code'] = $value->gateway_field_value;
						}
					}


					$bk_txt = (isset($this->phrases["booking reference"])) ? 
								$this->phrases["booking reference"] : "Booking Reference";

					$this->load->library('paypal', $config);
					$this->paypal->__initialize($config);
					$this->paypal->add($bk_txt." ".$booking_ref, $cost_of_journey);
					$this->paypal->pay(); /*Process the payment*/
				}
				elseif($gateway_details[0]->gateway_title == 'PayU')
				{
					$payuparams = array();
					foreach($gateway_details as $index => $value) {
						$MERCHANT_KEY = $SALT = '';
						$PAYU_BASE_URL = 'https://test.payu.in';
						if($value->field_key == 'merchant_key') {
							$payuparams['key'] = $value->gateway_field_value;
						}
						if($value->field_key == 'salt') {
							$payuparams['salt'] = $value->gateway_field_value;
						}
						if($value->field_key == 'mode') {
							if($value->gateway_field_value == 'live') {
								$payuparams['action'] = 'https://secure.payu.in/_payment';
							} else {
								$payuparams['action'] = 'https://test.payu.in/_payment';
							}
						}
					}
					$payuparams['surl'] = base_url() . 'booking/payu_success';
					$payuparams['furl'] = base_url() . 'booking/payu_success';
					$payuparams['service_provider'] = 'payu_paisa';
					$payuparams['productinfo'] = 'Journey From ' . $journey_booking_details['pick_point_name'] . ' To ' . $journey_booking_details['drop_point_name'];					
					$payuparams['amount'] = $cost_of_journey;
					$payuparams['firstname'] = $journey_booking_details['registered_name'];
					$payuparams['email'] = $journey_booking_details['email'];
					$payuparams['phone'] = $journey_booking_details['phone'];
					$this->load->helper('payu');	
					//neatPrint($journey_booking_details);
					echo call_payu( $payuparams );
					die();
				}
				elseif($gateway_details[0]->gateway_title == 'Finpay')
				{
					//neatPrint($journey_booking_details);
					
					$finpayparams = array();
					foreach($gateway_details as $index => $value) {
						if($value->field_key == 'merchant_id') {
							$finpayparams['merchant_id'] = $value->gateway_field_value;
						}
						if($value->field_key == 'password') {
							$finpayparams['merchant_password'] = $value->gateway_field_value;
						}
						if($value->field_key == 'account_type') {
							if($value->gateway_field_value == 'live') {
			$finpayparams['action'] = 'https://finpay.finnet-indonesia.com/transaction/TransactionMain.do';
							} else {
			$finpayparams['action'] = 'https://finpaydev.finnet-indonesia.com/transaction/TransactionMain.do';
							}
						}
					}
					
					$finpayparams['item_desc'] = 'Journey From ' . $journey_booking_details['pick_point_view'] . ' To ' . $journey_booking_details['drop_point_view'];
					$cost_of_journey = str_replace('.', '%', $cost_of_journey);
					$finpayparams['amount'] = $cost_of_journey;
					//$finpayparams['amount'] = 25%5;
					$finpayparams['trax_type'] = 'Payment';
					$finpayparams['invoice'] = rand(100000,999999);
					$finpayparams['trax_date'] = date('YmdHis');
					$finpayparams['currency_code'] = 'IDR';
					$finpayparams['return_url'] = base_url() . 'bookingseat/finpay_success';
										
					$finpayparams['cust_first_name'] = $journey_booking_details['registered_name'];
					$finpayparams['cust_country_code'] = $journey_booking_details['phone_code'];
					$finpayparams['cust_contact_no'] = $journey_booking_details['phone'];
					$finpayparams['cust_email'] = $journey_booking_details['email'];
					if($this->ion_auth->logged_in())
						$finpayparams['cust_id'] = $this->ion_auth->get_user_id();
					else
						$finpayparams['cust_id'] = 'NON-MEMBER';
					
					$this->load->helper('finpay');
					//neatPrint($finpayparams);					
					echo call_finpay( $finpayparams );
					die();
				}
				elseif($gateway_details[0]->gateway_title == 'Finpayapi')
				{
					$journey_details = $this->session->userdata('final_details');
					$finpayparams = array();
					$finpayparams['invoice'] = rand(100000,999999);
					$cost_of_journey = str_replace('.', '%', $cost_of_journey);
					$finpayparams['amount'] = $cost_of_journey;
					$finpayparams['add_info1'] = $journey_details['registered_name'];
					$finpayparams['return_url'] = base_url() . 'bookingseat/update_finpayapi_transaction';
					$this->load->helper('finpayapi');
					$client = get_finpayapi_service();					
					$response = $client->call_server( $finpayparams );
					
					$respon_params = explode('&', $response);
					$trax_type = $payment_code = $merchant_id = '';
					foreach($respon_params as $param)
					{
						$parts = explode('=', $param);
						if($parts[0] == 'trax_type')
							$trax_type = $parts[1];
						if($parts[0] == 'payment_code')
							$payment_code = $parts[1];
						if($parts[0] == 'merchant_id')
							$merchant_id = $parts[1];
					}					
							
					$journey_details['booking_status'] = 'Pending';
					$journey_details['payment_received'] = "0";
					$journey_details['transaction_id'] = $finpayparams['invoice'];
					$journey_details['payer_email'] = $journey_details['email'];
					$journey_details['payment_code'] 	= $payment_code;
					$journey_details['transaction_type'] 	= $trax_type;				
					foreach($gateway_details as $index => $value) {
						if($value->field_key == 'timeout') {
							$journey_details['timeout'] = ($value->gateway_field_value != '') ? $value->gateway_field_value : 30;
						}						
					}					
					$journey_details['payer_id'] = $merchant_id;
					$journey_details['payer_name'] = $journey_details['registered_name'];					
					$this->session->set_userdata('final_details', $journey_details);
					redirect('bookingseat/success_finpayapi');
				}
				elseif($gateway_details[0]->gateway_title == 'Cash')
				{
					redirect('bookingseat/success');
				}
			}
			else
			{
				$this->prepare_flashmessage(getPhrase('Please select Payment method'), 1);
				$this->data['message'] = $this->prepare_message(getPhrase('Please select payment method'), 1);
			}
			}
			else
			{
				$this->data['message'] = $this->prepare_message(validation_errors(), 1);
			}
		}
		 
		$journey_booking_details = $this->session->userdata('journey_booking_details');	
		
		//Onward Journey Seat Checking
		$pick_point = $this->get_data('pick_point', $journey_booking_details, 'onward');
		$vehicle_id = $this->get_data('vehicle_id', $journey_booking_details, 'onward');
		$travel_location_id = $this->get_data('travel_location_id', $journey_booking_details, 'onward');
		$tlc_id = $this->get_data('tlc_id', $journey_booking_details, 'onward');
		//neatPrint($journey_booking_details);
		$shuttle_no = $this->get_data('shuttle_no', $journey_booking_details, 'onward');
		$pick_date = $this->get_data('pick_date', $journey_booking_details, 'onward');
		$this->data['pick_date'] = $pick_date;		
		$seats = isset($journey_booking_details['onward']['seats']) ? $journey_booking_details['onward']['seats'] : array();
				
		$is_waiting_list = isset($journey_booking_details['onward']['is_waiting_list']) ? $journey_booking_details['onward']['is_waiting_list'] : 'No';
		
		$available = TRUE;
		if(count($seats) == 0)
		{
			$available = FALSE;
		}
		else
		{
			foreach($seats as $key => $val)
			{
				$parts = explode('_', $key); //This is to find the correct vehicle and travel locations when they choose connected vehicles. Even if date change after reaching connected vehicle locaiton.
				/*
				0 - Shuttle No
				1 - token
				2 - Seat No
				3 - pick date
				4 - vehicle id
				5 - tlc id
				6 - travel_location_id
				*/
				$travel_location_id_change = $parts[6];
				$vehicle_id_change = $parts[4];
				$pick_date_change = str_replace('#', '/', $parts[3]);
				$tlc_id_change = $parts[5];
				$shuttle_no_change = $parts[0];
				
				$availability = $this->base_model->check_seat_availability($travel_location_id_change, $vehicle_id_change, $pick_date_change, $val, $is_waiting_list, $tlc_id_change, $shuttle_no_change);
				//echo $this->db->last_query();
				//die();
				if(count($availability) > 0)
					$available = FALSE;
			}
		}
		
		if($available == FALSE)
		{
			$this->prepare_flashmessage(getPhrase('Something went wrong. Seats not available for onward journey.'),1);
			redirect('bookingseat/index'); 
		}
		$viapoints = $this->base_model->getViarecords(array('condition' => array('travel_location_id' => $travel_location_id), 'order_by' => 'record_order ASC')); //Onward Journey Via Points
		$this->data['viapoints']['onward'] = $viapoints;
		
		$journey_type = 'onward';
		if(isset($journey_booking_details['is_return']) && $journey_booking_details['is_return'] == 'yes')
			$journey_type = 'return';
		
		
		//Return Journey Seat Checking
		if($journey_type == 'return')
		{
		$pick_point = $this->get_data('pick_point', $journey_booking_details, 'return');
		$vehicle_id = $this->get_data('vehicle_id', $journey_booking_details, 'return');		
		$travel_location_id = $this->get_data('travel_location_id', $journey_booking_details, 'return');
		$tlc_id = $this->get_data('tlc_id', $journey_booking_details, 'return');
		$shuttle_no = $this->get_data('shuttle_no', $journey_booking_details, 'onward');
		
		$pick_date = $this->get_data('pick_date', $journey_booking_details, 'return');		
		$this->data['pick_date'] = $pick_date;		
		$seats = isset($journey_booking_details['return']['seats']) ? $journey_booking_details['return']['seats'] : array();
				
		$is_waiting_list = isset($journey_booking_details['return']['is_waiting_list']) ? $journey_booking_details['return']['is_waiting_list'] : 'No';

		if(count($seats) == 0)
		{
			$available = FALSE;
		}
		else
		{
			foreach($seats as $key => $val)
			{
				$availability = $this->base_model->check_seat_availability($travel_location_id, $vehicle_id, $pick_date, $val, $is_waiting_list, $tlc_id, $shuttle_no);
				//echo $this->db->last_query();
				if(count($availability) > 0)
					$available = FALSE;
			}
		}

		if($available == FALSE)
		{
			$this->prepare_flashmessage(getPhrase('Something went wrong. Seats not available for return journey.'),1);
			redirect('bookingseat/index'); 
		}
		$viapoints = $this->base_model->getViarecords(array('condition' => array('travel_location_id' => $travel_location_id), 'order_by' => 'record_order ASC')); //Return Journey Via Points
		$this->data['viapoints']['return'] = $viapoints;
		}
		
		$this->data['journey_type'] = $journey_type;
		
		$this->data['details'] = $journey_booking_details;
		$this->data['title'] 	= getPhrase("Passenger Details");
		
		$this->data['gateways']	= $this->base_model->fetch_records_from('gateways', array('type' => 'payment', 'gateway_status' => 'active'), '', 'gateway_title', 'DESC');
		
		
		$this->data['content'] 	= 'site/booking/passenger_details';
		$this->_render_page('templates/site_template', $this->data);
	 }
	 
	 function select_return_journey()
	 {
		 $this->checkfor_valid();
		 $this->data['message'] = $this->session->flashdata('message');
		 $this->data['content'] 	= 'site/booking/select_return_journey';
		$this->_render_page('templates/site_template', $this->data);
	 }
	 
	 /*
	* This function will collect the all the information after payment success
	* @param string $_POST from paypal
	* @return void
	*/
	function success_finpayapi()
	{
		$this->checkfor_valid_final();
		//$booking_info = $this->session->userdata('final_details');
		
		$booking_info 	 = array();
		
		$journey_details = $this->session->userdata('final_details');
		//neatPrint($journey_details);
		if( 
			(isset($journey_details['payment_type']) && 
				$journey_details['payment_type'] == "Finpayapi")
		  ) {
				/* Transaction Details */
				if(isset($journey_details['onward']) && isset($journey_details['onward']['selection_details']) && count($journey_details['onward']['selection_details']) > 0)
				{
					$selection_details = $journey_details['onward']['selection_details'];
					$shuttle_nos = array();
					
					foreach($selection_details as $key => $val)
					{
						$parts = explode('_', $key);
						if(!in_array($parts[0], $shuttle_nos))
						{
							$shuttle_nos[] = $parts[0];
							if(isset($journey_details['onward']['price_set']))
							{
							$journey_details['onward']['price_set'] = $val->price_set;
							}

							$journey_details['onward']['pick_date'] = date('D, M d, Y', strtotime($val->pick_date));
							$journey_details['onward']['pick_time'] = $val->start_time;
							$journey_details['onward']['destination_time'] = $val->destination_time;
							$this->insert_transaction('onward', $journey_details, $parts[0], $val->from_loc_id, $val->to_loc_id, $val->pick_point_name, $val->drop_point_name, $val->tlc_id);

						}
					}
				}
				
				if(isset($journey_details['return']) && isset($journey_details['return']['selection_details']) && count($journey_details['return']['selection_details']) > 0)
				{
					$selection_details = $journey_details['return']['selection_details'];
					$shuttle_nos = array();
					foreach($selection_details as $key => $val)
					{
						$parts = explode('_', $key);
						if(!in_array($parts[0], $shuttle_nos))
						{
						$shuttle_nos[] = $parts[0];
						if(isset($journey_details['return']['price_set']))
						{
						$journey_details['return']['price_set'] = $val->price_set;
						}						

						$journey_details['return']['pick_date'] = date('D, M d, Y', strtotime($val->pick_date));
						$journey_details['return']['pick_time'] = $val->start_time;
						$journey_details['return']['destination_time'] = $val->destination_time;
						$this->insert_transaction('return', $journey_details, $parts[0], $val->from_loc_id, $val->to_loc_id, $val->pick_point_name, $val->drop_point_name, $val->tlc_id);

						}
					}
				}
				$journey_details = $this->session->userdata('final_details');
				//neatPrint($journey_details);
				if(count($journey_details) > 0)
				{
					$this->update_return_reference_id( $journey_details );
					$this->update_reference_id($journey_details, 'onward');
					$this->update_reference_id($journey_details, 'return');
				}
			}
			
			/* Remove Session of Booking Data.*/
			$journey_booking_details = $this->session->userdata('journey_booking_details');
			$session_id = (isset($journey_booking_details['session_id'])) ? $journey_booking_details['session_id'] : '';
			if($session_id != '')
			$this->base_model->delete_record('bookings_locked', array('session_id' => $session_id));				
			$this->session->unset_userdata('minutes');
			$this->session->unset_userdata('seconds');
			
			$this->session->unset_userdata('journey_booking_details');
			$this->session->unset_userdata('final_details');
			
			$this->data['payment_code'] = $journey_details['payment_code'];
			$this->data['title'] = getPhrase('Booking Success');
			$this->data['content'] 		= 'site/booking/success_finpayapi';
			$this->_render_page('templates/site_template', $this->data);
	}
	 
	/*
	* This function will collect the all the information after payment success
	* @param string $_POST from paypal
	* @return void
	*/
	function success()
	{
		$this->checkfor_valid_final();
		//$booking_info = $this->session->userdata('final_details');
		
		$booking_info 	 = array();
		
		$journey_details = $this->session->userdata('final_details');
		//neatPrint($journey_details);
		if( 
			(isset($journey_details['payment_type']) && 
				$journey_details['payment_type'] == "Cash")
		  ) {
				/* Transaction Details */
				if(isset($journey_details['onward']) && isset($journey_details['onward']['selection_details']) && count($journey_details['onward']['selection_details']) > 0)
				{
					$selection_details = $journey_details['onward']['selection_details'];
					$shuttle_nos = array();
					//neatPrint($selection_details);
					foreach($selection_details as $key => $val)
					{
						$parts = explode('_', $key);
						if(!in_array($parts[0], $shuttle_nos))
						{
							$shuttle_nos[] = $parts[0];
							if(isset($journey_details['onward']['price_set']))
							{
							$journey_details['onward']['price_set'] = $val->price_set;
							}

							$journey_details['onward']['pick_date'] = date('D, M d, Y', strtotime($val->pick_date));
							$journey_details['onward']['pick_time'] = $val->start_time;
							$journey_details['onward']['destination_time'] = $val->destination_time;
							$this->insert_transaction('onward', $journey_details, $parts[0], $val->from_loc_id, $val->to_loc_id, $val->pick_point_name, $val->drop_point_name, $val->tlc_id);

						}
					}
				}
				
				if(isset($journey_details['return']) && isset($journey_details['return']['selection_details']) && count($journey_details['return']['selection_details']) > 0)
				{
					$selection_details = $journey_details['return']['selection_details'];
					$shuttle_nos = array();
					foreach($selection_details as $key => $val)
					{
						$parts = explode('_', $key);
						if(!in_array($parts[0], $shuttle_nos))
						{
						$shuttle_nos[] = $parts[0];
						if(isset($journey_details['return']['price_set']))
						{
						$journey_details['return']['price_set'] = $val->price_set;
						}						

						$journey_details['return']['pick_date'] = date('D, M d, Y', strtotime($val->pick_date));
						$journey_details['return']['pick_time'] = $val->start_time;
						$journey_details['return']['destination_time'] = $val->destination_time;
						$this->insert_transaction('return', $journey_details, $parts[0], $val->from_loc_id, $val->to_loc_id, $val->pick_point_name, $val->drop_point_name, $val->tlc_id);

						}
					}
				}
				$journey_details = $this->session->userdata('final_details');
				if(count($journey_details) > 0)
				{
					if(isset($journey_details['onward_booking_id']) && isset($journey_details['return_booking_id']))
					{
						$cost = $journey_details[$journey_type]['cost_of_journey'];
						$newcost = $cost / 2;
						$this->base_model->update_operation(array('reference_booking_id' => $journey_details['onward_booking_id']), 'bookings', array('id' => $journey_details['return_booking_id']));
						
						$this->base_model->update_operation(array('reference_booking_id' => $journey_details['return_booking_id']), 'bookings', array('id' => $journey_details['onward_booking_id']));
					}
				}
			}
			
			/* Remove Session of Booking Data.*/
			$journey_booking_details = $this->session->userdata('journey_booking_details');
			$session_id = (isset($journey_booking_details['session_id'])) ? $journey_booking_details['session_id'] : '';
			if($session_id != '')
			$this->base_model->delete_record('bookings_locked', array('session_id' => $session_id));				
			$this->session->unset_userdata('minutes');
			$this->session->unset_userdata('seconds');
			
			$this->session->unset_userdata('journey_booking_details');
			$this->session->unset_userdata('final_details');
			
			$this->data['title'] = getPhrase('Booking Success');
			$this->data['content'] 		= 'site/booking/success';
			$this->_render_page('templates/site_template', $this->data);
	}
	
	function insert_transaction($journey_type, $journey_details, $shuttle_no, $from_loc_id, $to_loc_id, $pick_point_name, $drop_point_name, $tlc_id)
	{
		$booking_info['payment_received'] = "1";
		//echo $tlc_id;
		//neatPrint($journey_details);
		$booking_info['payer_name'] = $journey_details[$journey_type]['payer_name'];			
		$booking_info['user_id']	= ($this->ion_auth->logged_in()) ? $this->ion_auth->get_user_id() : '0';
		$booking_info['discount_amount']	= (isset($journey_details[$journey_type]['discount_amount'])) ? $journey_details[$journey_type]['discount_amount'] : 0;

		$booking_info['pick_point']	= $pick_point_name;
		$booking_info['drop_point']	= $drop_point_name;
		$booking_info['pick_point_id']	= $from_loc_id;
		$booking_info['drop_point_id']	= $to_loc_id;	
		$booking_info['travel_location_cost_id']	= $tlc_id;
		
		$drop_point = $journey_details[$journey_type]['drop_point'];
		$amount=$journey_details[$journey_type]['cost_of_journey'];
		if($journey_type == 'onward')
		{
			if($to_loc_id != $drop_point)
			{
			$booking_info['booking_ref']	= $journey_details['booking_ref'].'C';
			}
			else
			{
			$booking_info['booking_ref']	= $journey_details['booking_ref'];	
			}
		}		
		else
		{
			if($to_loc_id != $drop_point)
			{
				$booking_info['booking_ref']	= $journey_details['booking_ref'].'RC';
			}
			else
			{
				$booking_info['booking_ref']	= $journey_details['booking_ref'].'R';	
			}
		}
		
		$booking_info['pick_date']	= date('Y-m-d', strtotime($journey_details[$journey_type]['pick_date']));
		$booking_info['pick_time']	= $journey_details[$journey_type]['pick_time'];
		$booking_info['destination_time']	= $journey_details[$journey_type]['destination_time'];
		$booking_info['token']	= $journey_details[$journey_type]['token'];
		
		if(isset($journey_details[$journey_type]['return_date']) && $journey_details[$journey_type]['return_date'] != '')
		{
		$booking_info['return_pick_date']	= date('Y-m-d', strtotime($journey_details[$journey_type]['return_date']));
		}
		
		
		$booking_info['insurance_taken']  = (isset($journey_details[$journey_type]['insurance_taken'])) ? $journey_details[$journey_type]['insurance_taken'] : 'no';
		$booking_info['insurance_amount_total']  = (isset($journey_details[$journey_type]['insurance_amount'])) ? $journey_details[$journey_type]['insurance_amount'] : 0;
		
		//Let us take individual shuttle prices
		$price_details = $journey_details[$journey_type]['price_details'];
		$basic_fare = $service_charge = $insurance = $discount = 0;
		if(!empty($price_details))
		{
			foreach($price_details as $key => $val)
			{
				if($key == $tlc_id)
				{
					if(isset($val['basic_fare']))
					$basic_fare += $val['basic_fare'];
					if(isset($val['service_tax']))
					$service_charge += $val['service_tax'];
					if(isset($val['insurance_amount']))
					$insurance += $val['insurance_amount'];
					$discount = (isset($val['discount'])) ? $val['discount'] : 0;
				}
			}
		}		
		$booking_info['basic_fare'] = $basic_fare;
		$booking_info['service_charge'] = $service_charge;
		$booking_info['insurance_amount'] = $insurance;
		$booking_info['discount_amount'] = $discount;
		
		//Let us update individual shuttle seats
		$seat = $journey_details[$journey_type]['selected_seats'];
		$seat_no = $journey_details[$journey_type]['selected_seats_no'];
		$selection_details = $journey_details[$journey_type]['selection_details'];
		if(!empty($selection_details))
		{
			$seat = $seat_no = '';
			foreach($selection_details as $key => $selection)
			{
				if($selection->tlc_id == $tlc_id)
				{
					$seat .= $selection->seat.', ';
					$seat_no .= $selection->seat_display.', ';
				}
			}
		}
		$booking_info['seat']  = $seat;
		$booking_info['seat_no']  = $seat_no;
		
		$booking_info['date_of_booking']  = date('Y-m-d');
		$booking_info['bookdate']		  = time();
		
		if($journey_details['payment_type'] == 'Paypal')
		{
			$booking_info['transaction_id'] 	= $journey_details['transaction_id'];
			$booking_info['payer_id'] 			= $journey_details['payer_id'];
			$booking_info['payer_email'] 		= $journey_details['payer_email'];
			$booking_info['payer_name'] 		= $journey_details['payer_name'];
			$booking_info['booking_status']	= 'Confirmed';
		}
		if($journey_details['payment_type'] == 'Payu')
		{
			$booking_info['transaction_id'] 	= $journey_details['transaction_id'];
			$booking_info['other_details'] 		= $journey_details['other_details'];
			$booking_info['booking_status']	= 'Confirmed';
		}
		if($journey_details['payment_type'] == 'Finpay')
		{
			$booking_info['transaction_id'] 	= $journey_details['transaction_id'];
			$booking_info['payer_id'] 			= $journey_details['payer_id'];
			$booking_info['payer_email'] 		= $journey_details['payer_email'];
			$booking_info['payer_name'] 		= $journey_details['payer_name'];
			$booking_info['booking_status']	= 'Confirmed';
		}
		if($journey_details['payment_type'] == 'Finpayapi')
		{
			$booking_info['transaction_id'] 	= $journey_details['transaction_id'];
			$booking_info['payer_id'] 			= isset($journey_details['payer_id']) ? $journey_details['payer_id'] : 0;
			$booking_info['payer_email'] 		= $journey_details['payer_email'];
			$booking_info['payer_name'] 		= isset($journey_details['payer_name']) ? $journey_details['payer_name'] : '';
			$booking_info['timeout'] 		= $journey_details['timeout'];
			$booking_info['transaction_type'] 	= $journey_details['transaction_type'];
			$booking_info['payment_code'] 	= $journey_details['payment_code'];
			$booking_info['booking_status']	= 'Pending';
		}
		if($journey_details['payment_type'] == 'Cash')
		{
			$booking_info['booking_status']	= 'Confirmed';
		}
		$booking_info['payment_type'] = strtolower($journey_details['payment_type']);
				
		$booking_info['vehicle_selected'] = $journey_details[$journey_type]['vehicle_id'];
		$booking_info['car_name'] = $journey_details[$journey_type]['car_name'];
		$travel_location_id = $journey_details[$journey_type]['travel_location_id'];
		if($to_loc_id != $drop_point)
		{
			if(isset($journey_details[$journey_type]['travel_location_id1']))
				$travel_location_id = $journey_details[$journey_type]['travel_location_id1'];
		}
		else
		{
			if(isset($journey_details[$journey_type]['travel_location_id2']))
				$travel_location_id = $journey_details[$journey_type]['travel_location_id2'];
		}
		
			
		$booking_info['travel_location_id'] = $travel_location_id; //We need to change this
		
		//$booking_info['car_name'] = $journey_details['car_name'];
		$booking_info['registered_name'] = $journey_details[$journey_type]['payer_name'];
		$booking_info['phone_code'] = $journey_details[$journey_type]['phone_code'];
		$booking_info['phone'] = $journey_details[$journey_type]['phone'];
		$booking_info['email'] = $journey_details[$journey_type]['email'];

		$booking_info['complete_pickup_address'] = $journey_details[$journey_type]['complete_pickup_address'];
		//$booking_info['complete_destination_address'] = $journey_details[$journey_type]['complete_destination_address'];

		$booking_info['cost_of_journey'] = ($amount < 0) ? 0 : $amount;
		//$booking_info['basic_fare'] = $journey_details[$journey_type]['basic_fare'];
		//$booking_info['service_charge'] = $journey_details[$journey_type]['service_charge'];
		$booking_info['shuttles'] = $journey_details['total_shuttles'];
				
		$booking_info['shuttle_no'] = $shuttle_no;		
		//$booking_info['seat'] = $journey_details[$journey_type]['selected_seats'];
		//$booking_info['seat_no'] = $journey_details[$journey_type]['selected_seats_no'];
		$booking_info['is_waiting_list'] = ($journey_details[$journey_type]['is_waiting_list']) ? $journey_details[$journey_type]['is_waiting_list'] : 'No';
		
		//$booking_info['seat_reserve'] = $journey_details[$journey_type]['selected_seats_total'];
		$booking_info['seat_reserve'] = $journey_details['adult']+$journey_details['child'];
		$booking_info['journey_type'] = $journey_type;
		//neatPrint($booking_info);
		$booking_id = $this->base_model->insert_operation_id($booking_info, 'bookings');

		if($booking_id > 0) {
			
			if(count($this->session->userdata('final_details')) > 0) {
				$record = $this->session->userdata('final_details');
				if($journey_type == 'onward')
				$record['onward_booking_id'] = $booking_id;
				else
				$record['return_booking_id'] = $booking_id;
			
				if($to_loc_id != $drop_point)
				{
					$record[$journey_type]['onward_connection_booking_id'] = $booking_id;
				}
				else
				{
					$record[$journey_type]['return_connection_booking_id'] = $booking_id;
				}
				$record = $this->session->set_userdata('final_details', $record);
			}
			
			if(isset($journey_details[$journey_type]['passenger_details']) && count($journey_details[$journey_type]['passenger_details']) > 0)
			{
				foreach($journey_details[$journey_type]['passenger_details'] as $key => $val)
				{					
					if($val['shuttle_no'] == $shuttle_no)
					{
						$passenger = array(
							'name' => (isset($val['name'])) ? $val['name'] : '',
							'complete_pickup_address' => (isset($val['complete_pickup_address'])) ? $val['complete_pickup_address'] : '',
							//'complete_destination_address' => (isset($val['complete_destination_address'])) ? $val['complete_destination_address'] : '',
							'phone' => (isset($val['phone'])) ? $val['phone'] : '',
							'phone_code' => (isset($val['phone_code'])) ? $val['phone_code'] : '',
							'seat' => (isset($val['seat'])) ? $val['seat'] : '',
							'seat_no' => (isset($val['seat_no'])) ? $val['seat_no'] : '',
							'gender' => (isset($val['gender'])) ? $val['gender'] : '',
							'age' => (isset($val['age'])) ? $val['age'] : '',
							'passenger_type' => (isset($val['passenger_type'])) ? $val['passenger_type'] : '',
							'email' => (isset($val['email'])) ? $val['email'] : '',
							'is_primary' => (isset($val['is_primary'])) ? $val['is_primary'] : 'No',
							'booking_id' => $booking_id,
							'price_set' => (isset($val['price_set'])) ? $val['price_set'] : '',
							'shuttle_no' => (isset($val['shuttle_no'])) ? $val['shuttle_no'] : '',
							'is_waiting_list' => $booking_info['is_waiting_list'],
							'token' => $booking_info['token'],
						);					
						$this->base_model->insert_operation($passenger, 'bookings_passengers');
					}
				}
			}
			
			if(isset($journey_details[$journey_type]['infant_passenger_details']) && count($journey_details[$journey_type]['infant_passenger_details']) > 0)
			{
				foreach($journey_details[$journey_type]['infant_passenger_details'] as $key => $val)
				{					
					//if($val['shuttle_no'] == $shuttle_no)
					{
						$passenger = array(
							'infant_name' => (isset($val['infant_name'])) ? $val['infant_name'] : '',
							'infant_gender' => (isset($val['infant_gender'])) ? $val['infant_gender'] : '',
							'date_created' => date('Y-m-d H:i:s'),
							'is_waiting_list' => $booking_info['is_waiting_list'],
							'infant_passenger_type' => 'infant',
							'booking_id' => $booking_id,
							'infant_age' => (isset($val['infant_age'])) ? $val['infant_age'] : '',
						);					
						$this->base_model->insert_operation($passenger, 'bookings_passengers_infants');
					}
				}
			}
			
			if(isset($journey_details['discount_details']) && count($journey_details['discount_details']) && $journey_type == 'onward')
			{
				$discount = $journey_details['discount_details'];
				$this->base_model->insert_operation($discount, 'offer_users');
			}
			$bk_ref_txt = getPhrase('Your Booking Reference');
			$sms_hd_txt = getPhrase('SMS Sent Failed. Reason');
			/* Send Booking Success Email to Client */
			$cost_of_journey = $journey_details[$journey_type]['total_fare'] - $booking_info['discount_amount'];
			$seats =  $journey_details[$journey_type]['selected_seats_no'];
			$route = $journey_details[$journey_type]['pick_point_name'] . ' - ' . $journey_details[$journey_type]['drop_point_name'];
			$payment_type = $journey_details['payment_type'];
			$booking_status = isset($journey_details['booking_status']) ? $journey_details['booking_status'] : 'Pending';
			
			if($payment_type == 'Cash')
			{
				$booking_status = 'Confirmed';
			}
			
			$emailvars = array('booking_ref' => $booking_info['booking_ref'], 'cost_of_journey' => $amount, 'seats' => $seats, 'payment_type' => $payment_type, 'booking_status' => $booking_status);
			
			$template = $this->base_model->fetch_records_from('templates', array('template_key' => 'Booking Success', 'template_status' => 'Active'));
			if(!empty($template))
			{
				$message = $template[0]->template_content;
				$tlc_amount = ($booking_info['basic_fare'] + $booking_info['service_charge'] + $booking_info['insurance_amount']) - $booking_info['discount_amount'];
				$passengers_str = $this->get_passengers($booking_id);
				$payment_code = '';
				if($journey_details['payment_type'] == 'Finpayapi')
				{
				$payment_code = isset($journey_details['payment_code']) ? $journey_details['payment_code'] : '';
				}
				
				$variables = array(
					'__BOOKING_REF__' => $booking_info['booking_ref'],
					'__SHUTTLE_NO__' => $booking_info['shuttle_no'],
					'__COST_OF_JOURNEY__' => $this->config->item('site_settings')->currency_symbol.' '.number_format($tlc_amount, 2),
					'__SEATS__' => $booking_info['seat_no'],
					'__PASSENGERS__' => $passengers_str,
					'__PASSENGERS_NAME__' => $this->get_passengers($booking_id, TRUE),
					'__ADDRESS__' => $this->get_passengers($booking_id, FALSE, TRUE),
					//'__ROUTE__' => $route,
					'__PAYMENT_TYPE__' => $payment_type,
					'__BOOKING_STATUS__' => $booking_status,
					'__PAYMENT_CODE__' => $payment_code,
					
					'__PICKUP_LOCATION__' => $booking_info['pick_point'],
					'__DROPOFF_LOCATION__' => $booking_info['drop_point'],
					'__DEPARTURE_TIME__' => $booking_info['pick_time'],
					'__ARRIVAL_TIME__' => $booking_info['destination_time'],
					'__DEPARTURE_DATE__' => $booking_info['pick_date'],
					'__VEHICLE_NAME__' => $booking_info['car_name'],
					
					'__USER_NAME__' => '',
					'__PASSWORD__' => '',
					'__LINK_TITLE__' => '',
					'__ROUTE__' => '',
				);
				$message = replace_constants($variables, $message);
				
				/*
				//$message = $this->load->view('email/booking_success_email', $emailvars, true);
				$message = str_replace('__BOOKING_REF__', $booking_info['booking_ref'], $message);
				$message = str_replace('__SHUTTLE_NO__', $booking_info['shuttle_no'], $message);
				
				$message = str_replace('__COST_OF_JOURNEY__', $this->config->item('site_settings')->currency_symbol.' '.number_format($tlc_amount, 2), $message);
				$message = str_replace('__SEATS__', $booking_info['seat_no'], $message);
				
				
				if($passengers_str != '')
				{
					$message = str_replace('__PASSENGERS__', $passengers_str, $message);
				}
				$message = str_replace('__ROUTE__', $route, $message);
				$message = str_replace('__PAYMENT_TYPE__', $payment_type, $message);
				$message = str_replace('__BOOKING_STATUS__', $booking_status, $message);
				if($journey_details['payment_type'] == 'Finpayapi')
				{
				$payment_code = isset($journey_details['payment_code']) ? $journey_details['payment_code'] : '';
				$message = str_replace('__PAYMENT_CODE__', $payment_code, $message);
				}
				*/
				$message = $template[0]->template_header.$message.$template[0]->template_footer;
				$from = $this->config->item('emailSettings')->from_email;
				$to = $journey_details[$journey_type]['email'];
				
				if($template[0]->template_subject != '')
				{
					$sub = $template[0]->template_subject;
				}
				else
				{
					$sub = $bk_ref_txt." - ".$booking_info['booking_ref'];
				}
				
				sendEmail($from, $to, $sub, $message);
			}
			$payment_code = '';
			if($journey_details['payment_type'] == 'Finpayapi')
			{
			$payment_code = isset($journey_details['payment_code']) ? $journey_details['payment_code'] : '';
			}
			$this->send_sms($journey_details, $journey_type, $payment_code, 'Success', $booking_info);
		}
	}
		
	function smstest()
	{
		$to = '08113461972';//08113461972
		$username = 'gosms';
		$password = 'xbs93zku';
		$message = 'This is test message';
		$auth=MD5($username.$password.$to);
		$url="http://send.gosmsgateway.com:8080/web2sms/api/Send.aspx?username=".$username."&mobile=".$to."&message=".urlencode($message)."&password=".$password.'&auth='.$auth;
		
		$curlHandle=curl_init();
		curl_setopt($curlHandle, CURLOPT_URL, $url);
		curl_setopt($curlHandle, CURLOPT_HEADER, 0);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
		curl_setopt($curlHandle, CURLOPT_POST, 0);
		$output=curl_exec($curlHandle);
		if(!$output)
		$output =	'Curl error: ' . curl_error($curlHandle);
		curl_close($curlHandle);
		echo $output;
	}
	
	function sendemail()
	{
		if($this->input->is_ajax_request())
		{
			$response = array('status' => 0, 'message' => getPhrase('Email failed to send. Please try again'));
			$booking_ref = $_POST['booking_ref'];
			$ticket_details = $this->base_model->ticket_details(array('booking_ref' => $booking_ref), 0, 1);
			
			$template = $this->base_model->fetch_records_from('templates', array('template_key' => 'mticket', 'template_status' => 'Active'));
			
			if(count($ticket_details) > 0 && count($template) > 0)
			{
				$cost_of_journey = ($ticket_details[0]->basic_fare + $ticket_details[0]->service_charge + $ticket_details[0]->insurance_amount) - $ticket_details[0]->discount_amount;
				$emailvars = array('site_theme' => $this->data['site_theme'], 'booking_ref' => $booking_ref, 'cost_of_journey' => number_format($cost_of_journey, 2), 'seats' => $ticket_details[0]->seat_no, 'payment_type' => $ticket_details[0]->payment_type, 'booking_status' => $ticket_details[0]->booking_status);
				//$message = $this->load->view('email/booking_success_email', $emailvars, true);
				
				$message = $template[0]->template_content;
				$booking_id = $ticket_details[0]->id;
				$passengers_str = $this->get_passengers($booking_id);
				/*
				$message = str_replace('__BOOKING_REF__', $booking_ref, $message);
				$message = str_replace('__SHUTTLE_NO__', $ticket_details[0]->shuttle_no, $message);
				$message = str_replace('__COST_OF_JOURNEY__', $this->config->item('site_settings')->currency_symbol.' '.number_format($cost_of_journey, 2), $message);
				$message = str_replace('__SEATS__', $ticket_details[0]->seat_no, $message);				
				if($passengers_str != '')
				{
					$message = str_replace('__PASSENGERS__', $passengers_str, $message);
				}
				$route = $ticket_details[0]->pick_point.' to '.$ticket_details[0]->drop_point;
				$message = str_replace('__ROUTE__', $route, $message);
				$message = str_replace('__PAYMENT_TYPE__', $ticket_details[0]->payment_type, $message);
				$message = str_replace('__BOOKING_STATUS__', $ticket_details[0]->booking_status, $message);
				*/
				$booking_info = (array) $ticket_details[0];
				$variables = array(
					'__BOOKING_REF__' => $booking_info['booking_ref'],
					'__SHUTTLE_NO__' => $booking_info['shuttle_no'],
					'__COST_OF_JOURNEY__' => $this->config->item('site_settings')->currency_symbol.' '.number_format($cost_of_journey, 2),
					'__SEATS__' => $booking_info['seat_no'],
					'__PASSENGERS__' => $passengers_str,
					'__PASSENGERS_NAME__' => $this->get_passengers($booking_id, TRUE),
					'__ADDRESS__' => $this->get_passengers($booking_id, FALSE, TRUE),
					'__PAYMENT_TYPE__' => $booking_info['payment_type'],
					'__BOOKING_STATUS__' => $booking_info['booking_status'],
					'__PAYMENT_CODE__' => '',					
					'__PICKUP_LOCATION__' => $booking_info['pick_point'],
					'__DROPOFF_LOCATION__' => $booking_info['drop_point'],
					'__DEPARTURE_TIME__' => $booking_info['pick_time'],
					'__ARRIVAL_TIME__' => $booking_info['destination_time'],
					'__DEPARTURE_DATE__' => $booking_info['pick_date'],
					'__VEHICLE_NAME__' => $booking_info['car_name'],					
					'__USER_NAME__' => '',
					'__PASSWORD__' => '',
					'__LINK_TITLE__' => '',
					'__ROUTE__' => '',
				);
				$message = replace_constants($variables, $message);
				$message = $template[0]->template_header.$message.$template[0]->template_footer;
				
				$from = $this->config->item('emailSettings')->from_email;
				$to = $_POST['email_address'];				
				if($template[0]->template_subject != '')
				{
					$sub = $template[0]->template_subject;
				}
				else
				{
					$sub = getPhrase('Your Booking Reference')." - ".$booking_ref;
				}
				//echo $message;die();
				sendEmail($from, $to, $sub, $message);
				$response = array('status' => 0, 'message' => 'Email sent to given email address');
			}
			echo json_encode($response);
		}
	}
	
	
	
	/**
	* This function will get the locaiton details
	* @param int $locaiton_id
	* @return Object
	*/
	function get_location()
	{
		if($this->input->is_ajax_request())
		{
			$locaiton_id = $this->input->post('locaiton_id');
			$details = $this->base_model->fetch_records_from('locations', array('id' => $locaiton_id));
			$return = array('status' => 0,'message' => getPhrase('Sorry no data found'), 'result' => array());
			if(count($details) > 0)
			{
				$return = array('status' => 1,'message' => getPhrase('Data Found'), 'result' => $details[0]);
			}
			echo json_encode($return);
		}
	}
	
	/**
	* This function will find the existence of particular ticket based on Booking Reference
	* @param string $booking_ref
	*/
	function findticket()
	{
		$check = $this->base_model->fetch_records_from('bookings', array('booking_ref' => $this->input->post('booking_ref')));
		if(count($check) > 0)
		{
			$can_cancel_time = $check[0]->pick_date;
			if($check[0]->pick_time != '')
			{
				$parts = explode(':',$check[0]->pick_time);
				$can_cancel_time .= ' '.trim($parts[0]).':'.trim($parts[1]);
			}
			else
			{
				$can_cancel_time .= ' 12:01 AM';
			}
			$hours = $this->config->item('site_settings')->canncel_before_hours;
			$new_time = date('Y-m-d H:i', strtotime("-$hours hours", strtotime($can_cancel_time)));
			$today = date('Y-m-d H:i');
			
			if(strtotime($today) < strtotime($new_time))
			{
				if($check[0]->booking_status == 'Cancelled')
				{
					$this->form_validation->set_message('findticket', getPhrase('Ticket already cancelled'));
					return FALSE;
				}
				else
				{
				return TRUE;
				}
			}
			else
			{
				$this->form_validation->set_message('findticket', getPhrase('Ticket can not cancel at this time'));
				return FALSE;
			}
		}
		else
		{
			$this->form_validation->set_message('findticket', getPhrase('Ticket not found'));
			return FALSE;
		}
	}

	/**
	* This function will facilitate user to find and print the ticket
	* @param string $booking_ref
	* @return Object
	*/
	function search_ticket()
	{
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['ticket_details'] = array();
		if(isset($_POST['buttSearch']))
		{
			/* Form Validations */
			$this->form_validation->set_rules('booking_ref', getPhrase('Ticket Number'),'trim|required|callback_findticket');
			
			$this->form_validation->set_error_delimiters(
			'<div class="error">', '</div>'
			);
			if($this->form_validation->run() == TRUE)
			{
				$this->data['ticket_details'] = $this->base_model->ticket_details(array('booking_ref' => $this->input->post('booking_ref')), 0,1);
			}
			else
			{
				$this->data['message'] = $this->prepare_message(validation_errors(), 1);
			}
		}
		$this->data['title'] 		= getPhrase('Search Ticket');
		$this->data['content'] 		= 'site/booking/search_ticket';
		$this->_render_page('templates/site_template', $this->data);
	}
	
	/**
	* This function will get facilitate user to cancel ticket
	* @param string $booking_ref
	* @return Object
	*/
	function cancel_ticket($booking_ref = '')
	{
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['ticket_details'] = array();
		if(isset($_POST['buttSearch']))
		{
			/* Form Validations */
			$this->form_validation->set_rules('booking_ref', getPhrase('Ticket Number'),'trim|required|callback_findticket');
			
			$this->form_validation->set_error_delimiters(
			'<div class="error">', '</div>'
			);
			if($this->form_validation->run() == TRUE)
			{
				$booking_ref = $this->input->post('booking_ref');
				$this->data['ticket_details'] = $this->base_model->get_ticket_details($booking_ref);
				if($this->data['ticket_details']['status'] == 1)
				{
					$cancel_otp = random_string();					
					
					$this->db->query('UPDATE '.$this->db->dbprefix('bookings').' SET cancel_otp = "'.$cancel_otp.'", cancel_otp_valid_upto="'.(time()+(3*60)).'", cancel_otp_times = cancel_otp_times+1 WHERE booking_ref = "'.$booking_ref.'"');
					
					$to_number = $this->data['ticket_details']['details']->phone_code.$this->data['ticket_details']['details']->phone;
					$your_onetime = (isset($this->phrases['Your One-Time password (OTP) is'])) ? $this->phrases['Your One-Time password (OTP) is'] : 'Your One-Time password (OTP) is';
					$for = (isset($this->phrases['to cancel the ticket of'])) ? $this->phrases['to cancel the ticket of'] : 'to cancel the ticket of';
					$valid_for = (isset($this->phrases['This is valid for 3 Minutes only'])) ? $this->phrases['This is valid for 3 Minutes only'] : 'This is valid for 3 Minutes only';
					$message = date('d M H:i') . ' '.$your_onetime . ' ' . $cancel_otp .' '. $for . ' '. $booking_ref . '. '.$valid_for;
					send_sms($to_number, $message);
					$this->data['message'] = $this->prepare_message($this->data['ticket_details']['message'], 0);
				}
				else
				{
					$this->data['message'] = $this->prepare_message($this->data['ticket_details']['message'], 1);
				}
			}
			else
			{
				$this->data['message'] = $this->prepare_message(validation_errors(), 1);
			}
		}
		
		if(isset($_POST['buttCancel']))
		{
			$booking_ref = $this->input->post('booking_ref');
			$otp = $this->input->post('otp');
			$details = $this->base_model->get_ticket_details($booking_ref, $otp);
			
			if($details['status'] == 1) //Success
			{
				$this->base_model->update_operation(array('booking_status' => 'Cancelled', 'cancelled_on' => time()), 'bookings', array('booking_ref' => $booking_ref));
				$this->data['message'] = $this->prepare_flashmessage(getPhrase('Your Ticket has been cancelled.'), 0);
				redirect('bookingseat/search_ticket');
			}
			else
			{
				$this->data['message'] = $this->prepare_message($details['message'], 1);
			}
		}
		$this->data['booking_ref'] = $booking_ref;
		$this->data['title'] 		= getPhrase('Cancel Ticket');
		$this->data['content'] 		= 'site/booking/cancel_ticket';
		$this->_render_page('templates/site_template', $this->data);
	}
	
	
	/**
	* This function facilitate user to pay the amount with paypal account
	* @param string $booking_ref
	* @return Object
	*/
	function paypal_success()
	{
		$this->checkfor_valid_final();
		$journey_details = $this->session->userdata('final_details');
		//neatPrint($journey_details);
		if($this->input->post() || 
			(isset($journey_details['payment_type']) && 
				$journey_details['payment_type'] == "paypal")
		  ) {
				$journey_details['booking_status'] = 'Confirmed';
				$journey_details['payment_received'] = "1";
				$journey_details['transaction_id'] = $this->input->post("txn_id");
				$journey_details['payer_id'] 		= $this->input->post("payer_id");
				$journey_details['payer_email'] 	= $this->input->post("payer_email");
				$journey_details['payer_name'] 	= $this->input->post("first_name") . " " . $this->input->post("last_name");
				$this->session->set_userdata('final_details', $journey_details);
				$journey_details = $this->session->userdata('final_details');
				$selection_details = $journey_details['onward']['selection_details'];
				$shuttle_nos = array();
			  /* Transaction Details */
				if(isset($journey_details['onward']) && isset($journey_details['onward']['selection_details']) && count($selection_details) > 0)
				{
					foreach($selection_details as $key => $val)
					{
						$parts = explode('_', $key);
						if(!in_array($parts[0], $shuttle_nos))
						{
							$shuttle_nos[] = $parts[0];
							if(isset($journey_details['onward']['price_set']))
							{
							$journey_details['onward']['price_set'] = $val->price_set;
							}

							$journey_details['onward']['pick_date'] = date('D, M d, Y', strtotime($val->pick_date));
							$journey_details['onward']['pick_time'] = $val->start_time;
							$journey_details['onward']['destination_time'] = $val->destination_time;
							$this->insert_transaction('onward', $journey_details, $parts[0], $val->from_loc_id, $val->to_loc_id, $val->pick_point_name, $val->drop_point_name, $val->tlc_id);

						}
					}
				}

				
				$shuttle_nos = array();
				if(isset($journey_details['return']) && isset($journey_details['return']['selection_details']) && count($journey_details['return']['selection_details']) > 0)
				{
					$selection_details = $journey_details['return']['selection_details'];
					foreach($selection_details as $key => $val)
					{
						$parts = explode('_', $key);
						if(!in_array($parts[0], $shuttle_nos))
						{
							$shuttle_nos[] = $parts[0];
							if(isset($journey_details['return']['price_set']))
							{
							$journey_details['return']['price_set'] = $val->price_set;
							}

							$journey_details['return']['pick_date'] = date('D, M d, Y', strtotime($val->pick_date));
							$journey_details['return']['pick_time'] = $val->start_time;
							$journey_details['return']['destination_time'] = $val->destination_time;
							$this->insert_transaction('return', $journey_details, $parts[0], $val->from_loc_id, $val->to_loc_id, $val->pick_point_name, $val->drop_point_name, $val->tlc_id);
						}
					}
				}
				
				
				//If it is the Onward and Return Journey, updating the 
				$this->update_return_reference_id( $journey_details );
				$this->update_reference_id($journey_details, 'onward');
				$this->update_reference_id($journey_details, 'return');
				
				/* Remove Session of Booking Data.*/
				$journey_booking_details = $this->session->userdata('journey_booking_details');
				$session_id = (isset($journey_booking_details['session_id'])) ? $journey_booking_details['session_id'] : '';
				if($session_id != '')
				$this->base_model->delete_record('bookings_locked', array('session_id' => $session_id));				
				$this->session->unset_userdata('minutes');
				$this->session->unset_userdata('seconds');				
				
				$this->session->unset_userdata('journey_booking_details');
				$this->session->unset_userdata('final_details');				
				$this->data['title'] = getPhrase('Booking Success');
				$this->data['content'] 		= 'site/booking/success';
				$this->_render_page('templates/site_template', $this->data);			
		  }
		  else
		  {
			  $this->prepare_flashmessage((isset($this->phrases["sorry for the inconvenience, subscription process interrupted. please contact Admin"])) ? $this->phrases["sorry for the inconvenience, subscription process interrupted. please contact Admin"] : "Sorry for the inconvenience, 
			Subscription process interrupted. Please contact Admin".".",1);
			redirect('bookingseat/index');
		  }
	}
	
	function update_reference_id($journey_details, $journey_type )
	{
		if(isset($journey_details[$journey_type]['onward_connection_booking_id']) && isset($journey_details[$journey_type]['return_connection_booking_id']))
		{
			$cost = $journey_details[$journey_type]['cost_of_journey'];
			$newcost = $cost / 2;
			
			$this->base_model->update_operation(array('reference_booking_id' => $journey_details[$journey_type]['onward_connection_booking_id']), 'bookings', array('id' => $journey_details[$journey_type]['return_connection_booking_id']));
			
			$this->base_model->update_operation(array('reference_booking_id' => $journey_details[$journey_type]['return_connection_booking_id']), 'bookings', array('id' => $journey_details[$journey_type]['onward_connection_booking_id']));
		}
	}
	
	function update_return_reference_id( $journey_details )
	{
		if(isset($journey_details['onward_booking_id']) && isset($journey_details['return_booking_id']))
		{
			$this->base_model->update_operation(array('reference_booking_id' => $journey_details['onward_booking_id']), 'bookings', array('id' => $journey_details['return_booking_id']));
			
			$this->base_model->update_operation(array('reference_booking_id' => $journey_details['return_booking_id']), 'bookings', array('id' => $journey_details['onward_booking_id']));
		}
	}
	
	
	/**
	* This function facilitate user to pay the amount with paypal account
	* @param string $booking_ref
	* @return void
	*/
	function payu_success()
	{
		$this->checkfor_valid_final();
		if($this->input->post()) //After success PayU will send the result data as 'POST'
		{
			$journey_details = $this->session->userdata('final_details');
			$get_payment_gateway = $this->base_model->get_payment_gateways($journey_details['payment_gateway_id']);
			
			if(count($get_payment_gateway) > 0)
			{
				$status_message = '';

				$status=$_POST["status"];
				$firstname=$_POST["firstname"];
				$amount=$_POST["amount"];
				$txnid=$_POST["txnid"];
				$posted_hash=$_POST["hash"];
				$key=$_POST["key"];
				$productinfo=$_POST["productinfo"];
				$email=$_POST["email"];
				$salt = '';
				foreach($gateway_details as $index => $value) {
					if($value->field_key == 'salt') {
						$salt = $value->gateway_field_value;
					}				
				}
				If (isset($_POST["additionalCharges"])) {
				   $additionalCharges=$_POST["additionalCharges"];
					$retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
					
				}
				else {
					$retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
				 }
				 $hash = hash("sha512", $retHashSeq);
				 if ($hash != $posted_hash) {
				   $status_message = (isset($this->phrases["invalid transaction. please try again"])) ? $this->phrases["invalid transaction. please try again"] : "Invalid Transaction. Please try again";
				  } else {
					  if($status == 'success')
					  {
						$journey_details['transaction_id']	= $_POST["txnid"];
						$journey_details['other_details']	= implode(" ", $_POST);
						$this->session->set_userdata('final_details', $journey_details);
						
						$journey_details = $this->session->userdata('final_details');
						$booking_info 	 = array();
						/* Transaction Details */
						if(isset($journey_details['onward']) && isset($journey_details['onward']['selection_details']) && count($journey_details['onward']['selection_details']) > 0)
						{
							$$selection_details = $journey_details['onward']['selection_details'];
							$shuttle_nos = array();
							foreach($selection_details as $key => $val)
							{
								$parts = explode('_', $key);
								if(!in_array($parts[0], $shuttle_nos))
								{
									$shuttle_nos[] = $parts[0];
									if(isset($journey_details['onward']['price_set']))
									{
									$journey_details['onward']['price_set'] = $val->price_set;
									}

									$journey_details['onward']['pick_date'] = date('D, M d, Y', strtotime($val->pick_date));
									$journey_details['onward']['pick_time'] = $val->start_time;
									$journey_details['onward']['destination_time'] = $val->destination_time;
									$this->insert_transaction('onward', $journey_details, $parts[0], $val->from_loc_id, $val->to_loc_id, $val->pick_point_name, $val->drop_point_name, $val->tlc_id);
								}
							}
						}
						
						if(isset($journey_details['return']) && isset($journey_details['return']['selection_details']) && count($journey_details['return']['selection_details']) > 0)
						{
							$selection_details = $journey_details['return']['selection_details'];
							$shuttle_nos = array();
							foreach($selection_details as $key => $val)
							{
								$parts = explode('_', $key);
								if(!in_array($parts[0], $shuttle_nos))
								{
									$shuttle_nos[] = $parts[0];
									if(isset($journey_details['return']['price_set']))
									{
									$journey_details['return']['price_set'] = $val->price_set;
									}

									$journey_details['return']['pick_date'] = date('D, M d, Y', strtotime($val->pick_date));
									$journey_details['return']['pick_time'] = $val->start_time;
									$journey_details['return']['destination_time'] = $val->destination_time;
									$this->insert_transaction('return', $journey_details, $parts[0], $val->from_loc_id, $val->to_loc_id, $val->pick_point_name, $val->drop_point_name, $val->tlc_id);
								}
							}
						} 

						//If it is the Onward and Return Journey, updating the 
						$this->update_return_reference_id( $journey_details );
						$this->update_reference_id($journey_details, 'onward');
						$this->update_reference_id($journey_details, 'return');
						/* Remove Session of Booking Data.*/
						$journey_booking_details = $this->session->userdata('journey_booking_details');
						$session_id = (isset($journey_booking_details['session_id'])) ? $journey_booking_details['session_id'] : '';
						if($session_id != '')
						$this->base_model->delete_record('bookings_locked', array('session_id' => $session_id));				
						$this->session->unset_userdata('minutes');
						$this->session->unset_userdata('seconds');
				
						$this->session->unset_userdata('journey_booking_details');
						$this->session->unset_userdata('final_details');
						
						$sms_bd_txt1 = (isset($this->phrases["Thank You. Your order status is"])) ? $this->phrases["Thank You. Your order status is"] : "Thank You. Your order status is";

						$sms_bd_txt2 = (isset($this->phrases["your transaction ID for this transaction is"])) ? $this->phrases["your transaction ID for this transaction is"] : "Your Transaction ID for this transaction is";

						$sms_bd_txt3 = (isset($this->phrases["we have received a payment of"])) ? $this->phrases["we have received a payment of"] : "We have received a payment of";

						$sms_bd_txt4 = (isset($this->phrases["your order will soon be shipped"])) ? $this->phrases["your order will soon be shipped"] : "Your order will soon be shipped";

						$sms_bd_txt5 = (isset($this->phrases["payment Success. but data insertion problem. please contact administrator"])) ? 			$this->phrases["payment Success. but data insertion problem. please contact administrator"] : "Payment Success. But Data insertion problem. Please contact Administrator";

						$sms_bd_txt6 = (isset($this->phrases["payment failed"])) ? 		$this->phrases["payment failed"] : "Payment Failed";

						$status_message = "<h3> <i class='fa fa-check'></i> ".$sms_bd_txt1." ". $status .".</h3>";
						if($smsinfo != '') {
							$status_message .= "<h4>$smsinfo</h4>";
						}
						$status_message .= "<h4>".$sms_bd_txt2." <span>".$txnid."</span>.</h4>";
						$status_message .= "<h4>".$sms_bd_txt3."  ".$this->config->item('site_settings')->currency_symbol. "<span>".$amount . "</span>. ".$sms_bd_txt4.".</h4>";
					  }
					  else
					  {
						  $els_txt1 = (isset($this->phrases["please"])) ? $this->phrases["please"] : "Please";
						  $els_txt2 = (isset($this->phrases["try"])) ? $this->phrases["try"] : "try";
						  $els_txt3 = (isset($this->phrases["again"])) ? $this->phrases["again"] : "again";
						  
						  $sms_bd_txt6 = (isset($this->phrases["payment failed"])) ? 
								$this->phrases["payment failed"] : "Payment Failed";

						  $status_message = $sms_bd_txt6.". <br><br>".$els_txt1." <a href='".base_url()."bookingseat/index'>".$els_txt2."</a> ".$els_txt3.".";
					  }
				  }
				
			}
			else
			{
				$op_fail_txt = (isset($this->phrases["wrong operation (gateway not found)"])) ? $this->phrases["wrong operation (gateway not found)"] : "Wrong Operation (Gateway Not Found)";
				$status_message = $op_fail_txt;
			}
		}
		else
		{
			$wrng_op_txt = (isset($this->phrases["wrong operation (not post data)"])) ? $this->phrases["wrong operation (not post data)"] : "Wrong Operation (Not Post Data)";
			$status_message = $wrng_op_txt;
		}
		$this->data['details'] 	= $status_message;
		$this->data['title'] 	= (isset($this->phrases["payU payment status"])) ? $this->phrases["payU payment status"] : "PayU Payment Status";
		$this->data['content'] 	= "site/booking/payustatus";
		$this->_render_page('templates/site_template', $this->data);		
	}
	
	function checkvalidfile()
	{
		$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
		if(!in_array($ext, array('jpg', 'jpeg', 'png')))
		{
			$this->form_validation->set_message('checkvalidfile', getPhrase('Please upload valid file.'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function my_bookings()
	{
		if(!$this->ion_auth->logged_in())
		{
			$this->prepare_flashmessage(getPhrase('Please login to access this page'), 0);
			redirect('auth/login');
		}
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['tab'] = 'a';
		if(isset($_POST['buttProfile']))
		{
			$this->form_validation->set_rules('first_name', getPhrase('Please enter First Name'),'trim|required');
			$this->form_validation->set_rules('phone_code', getPhrase('Please enter Country Code'),'trim|required');
			$this->form_validation->set_rules('phone', getPhrase('Please enter Phone Number'),'trim|required');
			$this->form_validation->set_rules('dob', getPhrase('Please select date of birth'),'trim|required');
			if (!empty($_FILES['photo']['name']))
			{
			$this->form_validation->set_rules('photo', getPhrase('Please select date of birth'),'trim|callback_checkvalidfile');	
			}
			$this->data['tab'] = 'a';									
			if($this->form_validation->run() == TRUE)
			{
				$inputdata = array(
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'phone_code' => $this->input->post('phone_code'),
					'phone' => $this->input->post('phone'),
					'dob' => date('Y-m-d', strtotime($this->input->post('dob'))),
					'gender' => $this->input->post('gender'),
					'address' => $this->input->post('address'),
				);
				/* Save File*/
				$error = '';
				if (!empty($_FILES['photo']['name'])) {
					$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
					$file_name = 'profile_'.getUserId().'.'.$ext;
					$config['upload_path'] 		= './uploads/user_profile_pics/';
					$config['allowed_types'] 	= 'jpg|jpeg|png';
					$config['overwrite'] 		= TRUE;
					$config['file_name']        = $file_name;
					$this->load->library('upload', $config);
					if ($this->upload->do_upload('photo')) {
						$inputdata['photo']		= $file_name;
						$this->create_thumbnail($config['upload_path'] . $config['file_name'], $config['upload_path'] . 'thumbs/', 178, 115);
					}
					else
					{
						$error = $this->upload->display_errors();
					}
				}
				//neatPrint($error);
				$this->base_model->update_operation($inputdata, 'users', array('id' => getUserId()));
				$this->prepare_flashmessage(getPhrase('Profile has been updated successfully.'));
				redirect('bookingseat/my_bookings');
			}
		}
		
		if(isset($_POST['buttSearch']))
		{
			/* Form Validations */
			$this->form_validation->set_rules('booking_ref', getPhrase('Ticket Number'),'trim|required|callback_findticket');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			$this->data['tab'] = 'c';
			if($this->form_validation->run() == TRUE)
			{
				$booking_ref = $this->input->post('booking_ref');
				$this->data['ticket_details'] = $this->base_model->get_ticket_details($booking_ref);
				if($this->data['ticket_details']['status'] == 1)
				{
					$cancel_otp = random_string();					
					
					$this->db->query('UPDATE '.$this->db->dbprefix('bookings').' SET cancel_otp = "'.$cancel_otp.'", cancel_otp_valid_upto="'.(time()+(3*60)).'", cancel_otp_times = cancel_otp_times+1 WHERE booking_ref = "'.$booking_ref.'"');
					$to_number = $this->data['ticket_details']['details']->phone_code.$this->data['ticket_details']['details']->phone;
					$your_onetime = (isset($this->phrases['Your One-Time password (OTP) is'])) ? $this->phrases['Your One-Time password (OTP) is'] : 'Your One-Time password (OTP) is';
					$for = (isset($this->phrases['to cancel the ticket of'])) ? $this->phrases['to cancel the ticket of'] : 'to cancel the ticket of';
					$valid_for = (isset($this->phrases['This is valid for 3 Minutes only'])) ? $this->phrases['This is valid for 3 Minutes only'] : 'This is valid for 3 Minutes only';
					$message = date('d M H:i') . ' '.$your_onetime . ' ' . $cancel_otp .' '. $for . ' '. $booking_ref . '. '.$valid_for;
					send_sms($to_number, $message);
					$this->data['message'] = $this->prepare_message($this->data['ticket_details']['message'], 0);
				}
				else
				{
					$this->data['message'] = $this->prepare_message($this->data['ticket_details']['message'], 1);
				}
			}
			else
			{
				$this->data['message'] = $this->prepare_message(validation_errors(), 1);
			}
		}
		
		if(isset($_POST['buttCancel']))
		{
			$booking_ref = $this->input->post('booking_ref');
			$otp = $this->input->post('otp');
			$details = $this->base_model->get_ticket_details($booking_ref, $otp);
			
			if($details['status'] == 1) //Success
			{
				$this->base_model->update_operation(array('booking_status' => 'Cancelled', 'cancelled_on' => time()), 'bookings', array('booking_ref' => $booking_ref));
				$this->data['message'] = $this->prepare_flashmessage(getPhrase('Your Ticket has been cancelled.'), 0);
				redirect('bookingseat/my-bookings');
			}
			else
			{
				$this->data['message'] = $this->prepare_message($details['message'], 1);
			}
		}
		
		$this->data['mybookings'] = $this->base_model->fetch_records_from('bookings', array('user_id' => getUserId()), '*', 'date_of_booking', 'ASC', 20);
		
		$this->data['offers'] 	= $this->base_model->fetch_records_from('offers', array('status' => 'Active', 'expiry_date >=' => date('Y-m-d')));
		
		$this->data['title'] 	= (isset($this->phrases["User Details"])) ? $this->phrases["User Details"] : "User Details";
		$this->data['content'] 	= "site/booking/my_bookings";
		$this->_render_page('templates/site_template', $this->data);
	}
	
	/**
	* This function will fetch the details of the a particular vehicle
	* @param array $_POST
	*/
	function fetchDetails()
	{
		if($this->input->is_ajax_request())
		{
			$result = '';
			$vehicle_id = $this->input->post('vehicle_id');
			$tlc_id = $this->input->post('tlc_id');
			$token = $this->input->post('token');
			$index = $this->input->post('index');
			$has_connection = $this->input->post('has_connection');
			
			$journey_booking_details = $this->session->userdata('journey_booking_details');
			$travel_location_id = $journey_booking_details['travel_location_id'];
			
			$journey_type = 'onward';
			if(isset($journey_booking_details['is_return']) && $journey_booking_details['is_return'] == 'yes')
				$journey_type = 'return';
			
			if(isset($journey_booking_details[$journey_type]['token']) && $journey_booking_details[$journey_type]['token'] != $token)
			{
				if(isset($journey_booking_details[$journey_type]['selection_details']) && isset($journey_booking_details[$journey_type]['selection_details']))
				{
					unset($journey_booking_details[$journey_type]['selection_details']);
				}
				if(isset($journey_booking_details[$journey_type]['vehicle_details']) && isset($journey_booking_details[$journey_type]['vehicle_details']))
				{
					unset($journey_booking_details[$journey_type]['vehicle_details']);
				}
				
				if(isset($journey_booking_details[$journey_type]['seats']) && isset($journey_booking_details[$journey_type]['seats']))
				{
					unset($journey_booking_details[$journey_type]['seats']);
				}
				
				if(isset($journey_booking_details[$journey_type]['selected_seats']) && isset($journey_booking_details[$journey_type]['selected_seats']))
				{
					unset($journey_booking_details[$journey_type]['selected_seats']);
				}
				if(isset($journey_booking_details[$journey_type]['selected_seats_total']) && isset($journey_booking_details[$journey_type]['selected_seats_total']))
				{
					$journey_booking_details[$journey_type]['selected_seats_total'] = 0;
				}
				
				if(isset($journey_booking_details[$journey_type]['basic_fare']) && isset($journey_booking_details[$journey_type]['basic_fare']))
				{
					$journey_booking_details[$journey_type]['basic_fare'] = 0;
				}
				if(isset($journey_booking_details[$journey_type]['service_charge']) && isset($journey_booking_details[$journey_type]['service_charge']))
				{
					$journey_booking_details[$journey_type]['service_charge'] = 0;
				}
				if(isset($journey_booking_details[$journey_type]['total_fare']) && isset($journey_booking_details[$journey_type]['total_fare']))
				{
					$journey_booking_details[$journey_type]['total_fare'] = 0;
				}
				
				//Over All
				if(isset($journey_booking_details['selected_seats_total']) && isset($journey_booking_details['selected_seats_total']))
				{
					$journey_booking_details['selected_seats_total'] = 0;
				}
				if(isset($journey_booking_details['basic_fare']) && isset($journey_booking_details['basic_fare']))
				{
					$journey_booking_details['basic_fare'] = 0;
				}
				if(isset($journey_booking_details['service_charge']) && isset($journey_booking_details['service_charge']))
				{
					$journey_booking_details['service_charge'] = 0;
				}
				if(isset($journey_booking_details['total_fare']) && isset($journey_booking_details['total_fare']))
				{
					$journey_booking_details['total_fare'] = 0;
				}
				if(isset($journey_booking_details['total_fare']) && isset($journey_booking_details['total_fare']))
				{
					$journey_booking_details['total_fare'] = 0;
				}
				
				//Particular Journey Type
				if(isset($journey_booking_details[$journey_type]['selected_seats_total']) && isset($journey_booking_details[$journey_type]['selected_seats_total']))
				{
					$journey_booking_details[$journey_type]['selected_seats_total'] = 0;
				}
				if(isset($journey_booking_details[$journey_type]['basic_fare']) && isset($journey_booking_details[$journey_type]['basic_fare']))
				{
					$journey_booking_details[$journey_type]['basic_fare'] = 0;
				}
				if(isset($journey_booking_details[$journey_type]['service_charge']) && isset($journey_booking_details[$journey_type]['service_charge']))
				{
					$journey_booking_details[$journey_type]['service_charge'] = 0;
				}
				if(isset($journey_booking_details[$journey_type]['total_fare']) && isset($journey_booking_details[$journey_type]['total_fare']))
				{
					$journey_booking_details[$journey_type]['total_fare'] = 0;
				}
				if(isset($journey_booking_details[$journey_type]['total_fare']) && isset($journey_booking_details[$journey_type]['total_fare']))
				{
					$journey_booking_details[$journey_type]['total_fare'] = 0;
				}
				
				if(isset($journey_booking_details[$journey_type]['vehicle_id']) && isset($journey_booking_details[$journey_type]['vehicle_id']))
				{
					unset($journey_booking_details[$journey_type]['vehicle_id']);
				}
				if(isset($journey_booking_details[$journey_type]['shuttle_no']) && isset($journey_booking_details[$journey_type]['shuttle_no']))
				{
					unset($journey_booking_details[$journey_type]['shuttle_no']);
				}
				if(isset($journey_booking_details[$journey_type]['tlc_id']) && isset($journey_booking_details[$journey_type]['tlc_id']))
				{
					unset($journey_booking_details['tlc_id']);
				}
				if(isset($journey_booking_details[$journey_type]['token']) && isset($journey_booking_details[$journey_type]['token']))
				{
					unset($journey_booking_details[$journey_type]['token']);
				}
				if(isset($journey_booking_details[$journey_type]['price_set']) && isset($journey_booking_details[$journey_type]['price_set']))
				{
					unset($journey_booking_details[$journey_type]['price_set']);
				}
				if(isset($journey_booking_details[$journey_type]['price_set_seats']) && isset($journey_booking_details[$journey_type]['price_set_seats']))
				{
					unset($journey_booking_details[$journey_type]['price_set_seats']);
				}
				if(isset($journey_booking_details[$journey_type]['price_set_seats_selected']) && isset($journey_booking_details[$journey_type]['price_set_seats_selected']))
				{
					unset($journey_booking_details[$journey_type]['price_set_seats_selected']);
				}
				if(isset($journey_booking_details[$journey_type]['tlc_id']) && isset($journey_booking_details[$journey_type]['tlc_id']))
				{
					unset($journey_booking_details[$journey_type]['tlc_id']);
				}
				
				$this->session->set_userdata('journey_booking_details', $journey_booking_details);
			}
			//neatPrint($record);
			$rid = $this->input->post('rid');
			$price_set = $this->input->post('price_set');
			$pick_point = $this->input->post('pick_point');
			$drop_point = $this->input->post('drop_point');
			$pick_date = $this->input->post('pick_date');
			$adult = $this->input->post('adult');
			$child = $this->input->post('child');
			$infant = $this->input->post('infant');
			$shuttle_no = $this->input->post('shuttle_no');
			
			$available = $this->input->post('available');
			$total_number_of_seats = $this->input->post('total_number_of_seats');
			
			$this->data['token'] = $token;
			$this->data['index'] = $rid;
			$this->data['div_id'] = $index;
			$this->data['price_set'] = $price_set;
			$this->data['available_price_set_seats'] = $available;
			$this->data['tlc_id'] = $tlc_id;
			$this->data['travel_location_id'] = $travel_location_id;
			$this->data['shuttle_no_received'] = $shuttle_no;
			$this->data['total_number_of_seats'] = $total_number_of_seats;
			$this->data['vehicles'] = array();
			$this->data['total_seats'] = 0;
			$this->data['available_seats'] = array();
			$this->data['via_points'] = array();
			$this->data['dropping_points'] = array();
			$vehicle_ids = $shuttle_types = $tlc_ids = array();
			$this->data['vehicle_id'] = $vehicle_id;
			if(isset($journey_booking_details['is_return']) && $journey_booking_details['is_return'] == 'yes')
			{
			$results = $this->base_model->get_vehicles_seats(array('pick_point' => $pick_point, 'drop_point' => $drop_point, 'vehicle_id' => $vehicle_id, 'travel_location_id' => $travel_location_id, 'tlc_id' => $tlc_id, 'token' => $token, 'pick_date' => $pick_date));	
			}
			else
			{
			$results = $this->base_model->get_vehicles_seats(array('pick_point' => $pick_point, 'drop_point' => $drop_point, 'vehicle_id' => $vehicle_id, 'travel_location_id' => $travel_location_id, 'tlc_id' => $tlc_id, 'token' => $token, 'pick_date' => $pick_date));
			}
			$this->data['v'] = $results;
			//echo $this->db->last_query();
			//neatPrint($results);
			$from_loc_id = $to_loc_id = '';
			if(count($results) > 0)
			{
				$this->data['v'] = $results[0];
				$total_seats = 0;
				foreach($results as $v)
				{
					$vehicle_ids[] = $v->id;
					$tlc_ids[] = $v->tlc_id;
					$from_loc_id = $v->from_loc_id;
					$to_loc_id = $v->to_loc_id;
					
					$fare_details = (isset($v->fare_details) && $v->fare_details != '') ? json_decode($v->fare_details) : array();
					$fare_details = (array)$fare_details;
					$tlc_total_seats = 0;
					if(isset($fare_details['variation']))
					{
						foreach($fare_details['variation'] as $pv => $vv)
						{
							$tlc_total_seats += get_seat_priceset_count($pv, $fare_details);
						}
					}
					
					$total_seats += $tlc_total_seats;
					
					$is_return = 'no';
					if(isset($journey_booking_details['is_return']) && $journey_booking_details['is_return'] == 'yes')
						$is_return = 'yes';
					$journey_type = 'onward';
					if($is_return == 'yes')
					{
						$journey_type = 'return';
					}
					$is_waiting_list = isset($journey_booking_details[$journey_type]['is_waiting_list']) ? $journey_booking_details[$journey_type]['is_waiting_list'] : 'No';
					
					$booked_seats_shuttle = $this->base_model->booked_seats_count($pick_date, array($v->tlc_id), $is_waiting_list, $v->shuttle_no);
					//echo $this->db->last_query();die();
					$tlc_available_seats = $tlc_total_seats - $booked_seats_shuttle[0]->reserved;
					$this->data['available_seats'][$v->tlc_id] = ($tlc_available_seats > 0) ? $tlc_available_seats : 0;
										
					if($this->data['available_seats'][$v->tlc_id] == 0)
					{
						$this->data['booked_seats'][$v->tlc_id] = $this->base_model->get_booking_info($v->tlc_id, $pick_date, 'Yes');
					}
					else
					{
					$this->data['booked_seats'][$v->tlc_id] = $this->base_model->get_booking_info($v->tlc_id, $pick_date);
					}
					
					$this->data['locked_seats'][$v->tlc_id] = $this->base_model->locked_seats($v->tlc_id, $pick_date, $v->shuttle_no);
					//echo $this->db->last_query();
					//neatPrint($this->data['booked_seats']);
				}
				$booked_seats = $this->base_model->booked_seats_count($pick_date, $tlc_ids, $is_waiting_list, $v->shuttle_no);
				$this->data['total_seats'] = $total_seats-$booked_seats[0]->reserved;
			}
			else
			{
				$result = getPhrase('Something went wrong. Vehicle not found.');
			}
			//neatPrint($this->data['available_seats']);
			$this->data['seats_available'] = $this->data['available_seats'][$tlc_id];
			$this->data['message'] = $result;
			$record = array();
			if(count($this->session->userdata('journey_booking_details')) > 0) {
				$record = $this->session->userdata('journey_booking_details');
			}
			$this->data['record'] = $record;
			$journey_type = 'onward';
			if(isset($record['is_return']) && $record['is_return'] == 'yes')
				$journey_type = 'return';
			$this->data['journey_type'] = $journey_type;
			$this->data['has_connection'] = $has_connection;
			
			$data = $this->load->view('seat/site/booking/seat_layout', $this->data, TRUE);
			echo json_encode(array('status' => 'success', 'id' => $rid, 'data' => $data, 'has_connection' => $has_connection, 'div_id' => $index, 'pick_point' =>$pick_point, 'drop_point' => $drop_point, 'from_loc_id' => $from_loc_id, 'to_loc_id' => $to_loc_id));
		}
	}
	
	/**
	* This function will calculate the insurance
	* @param String $insurance
	*/
	function calculate_insurace_fee()
	{
		if($this->input->is_ajax_request())
		{
			$status = $this->input->post('status');
			$journey_type = $this->input->post('type');
			
			$result = array();
			$journey_booking_details = $this->session->userdata('journey_booking_details');
			
			$insurance_type = $this->config->item('site_settings')->insurance_type; //For now we are not using this. Its a fixed value
			$insurance_value = $this->config->item('site_settings')->insurance_value;
			$insurance_appliedto = $this->config->item('site_settings')->insurance_appliedto;
			
			$total_seats = $journey_booking_details['adult'] + $journey_booking_details['child'] + $journey_booking_details['infant']; //For onward and return journey same number of seats. Here we are including infants ie they have insurance
			//neatPrint($journey_booking_details);		
			$basic_fare = 0;
			$basic_fare_onward = (isset($journey_booking_details['onward']['basic_fare'])) ? $journey_booking_details['onward']['basic_fare'] : 0;
			$basic_fare_return = (isset($journey_booking_details['return']['basic_fare'])) ? $journey_booking_details['return']['basic_fare'] : 0;
			$basic_fare = $basic_fare_onward + $basic_fare_return;
			
			$total_fare_onward = (isset($journey_booking_details['onward']['total_fare'])) ? $journey_booking_details['onward']['total_fare'] : 0;
			$total_fare_return = (isset($journey_booking_details['return']['total_fare'])) ? $journey_booking_details['return']['total_fare'] : 0;			
			$total_fare = $total_fare_onward + $total_fare_return;
			
			$service_charge = 0;
			$service_charge_onward = (isset($journey_booking_details['onward']['service_charge'])) ? $journey_booking_details['onward']['service_charge'] : 0;
			$service_charge_return = (isset($journey_booking_details['return']['service_charge'])) ? $journey_booking_details['return']['service_charge'] : 0;
			$service_charge = $service_charge_onward + $service_charge_return;
			
			$insurance_old = $insurance = $insurance_onward = $insurance_return = $insurance_display = 0;
			if($journey_type == 'onward')
			{
				$insurance_return = (isset($journey_booking_details['return']['insurance_amount'])) ? $journey_booking_details['return']['insurance_amount'] : 0;
			}
			if($journey_type == 'return')
			{
				$insurance_onward = (isset($journey_booking_details['onward']['insurance_amount'])) ? $journey_booking_details['onward']['insurance_amount'] : 0;
			}			
			$insurance_old = $insurance_onward + $insurance_return;
			//echo $insurance_old.'##'.$insurance_onward.'##'.$insurance_return;die();
			$disount_amount = (isset($journey_booking_details['disount_amount'])) ? $journey_booking_details['disount_amount'] : 0;
			
			$fee = $insurance_value;			
			if($status == 'true')
			{
				if($insurance_appliedto == 'per_person') //For each person we need to add amount
				{
					$insurance = $insurance_value * $total_seats;
					$fee = $insurance;
					$insurance_value = $insurance;
					if($journey_type == 'onward')
					{
						$total_fare_new = ($basic_fare_onward + $insurance) - $disount_amount;	
					}
					else
					{
						$total_fare_new = ($basic_fare_return + $insurance) - $disount_amount;
					}
					
				}
				else
				{
					if($journey_type == 'onward')
					{
						$total_fare_new = ($total_fare_onward + $insurance) - $disount_amount; //We are adding insurance to tatal fare
					}
					else
					{
						$total_fare_new = ($total_fare_return + $insurance) - $disount_amount;
					}
				}
				$result['total_fare'] = $this->config->item('site_settings')->currency_symbol . ' ' . number_format(($total_fare_new + $fee), 2);
				
				$fee_display = $fee;
				
				$price_details = $journey_booking_details[$journey_type]['price_details'];
				if(!empty($price_details))
				{
					if($fee != 0)
					$half_fee = $fee/$journey_booking_details[$journey_type]['shuttles_count']; //We are distributing insurance amount to each shuttle.
					foreach($price_details as $key => $val)
					{
						$journey_booking_details[$journey_type]['price_details'][$key]['insurance_amount'] = $half_fee;
					}
				}
				
				$journey_booking_details[$journey_type]['insurance_amount'] = $fee;
				$journey_booking_details[$journey_type]['insurance_taken'] = 'yes';
				
				$insurance_display = $insurance + $fee_display;
				$result['insurance'] = $this->config->item('site_settings')->currency_symbol . ' ' . number_format(($insurance_old + $fee), 2);
				//neatPrint($result);
			}
			else
			{	
				$price_details = $journey_booking_details[$journey_type]['price_details'];
				if(!empty($price_details))
				{
					foreach($price_details as $key => $val)
					{
						$journey_booking_details[$journey_type]['price_details'][$key]['insurance_amount'] = 0;
					}
				}
				//$journey_booking_details[$journey_type]['price_details'] = $price_details;
				$journey_booking_details[$journey_type]['insurance_amount'] = 0;
				$journey_booking_details[$journey_type]['insurance_taken'] = 'no';
				$insurance_display = $insurance;
				$insurance = $fee = 0;
				$result['insurance'] = $this->config->item('site_settings')->currency_symbol . ' ' . number_format(($insurance_old), 2); //Here we are displaying insurance with
			}
			//neatPrint($result);
			$journey_booking_details['insurance_amount'] = $insurance_old + $fee;
			
			$this->session->set_userdata('journey_booking_details', $journey_booking_details);
			
			$details = $this->session->userdata('journey_booking_details');
			
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
			$insurance_onward = (isset($details['onward']['insurance_amount'])) ? $details['onward']['insurance_amount'] : 0;
			$insurance_return = (isset($details['return']['insurance_amount'])) ? $details['return']['insurance_amount'] : 0;
			$insurance = $insurance_onward + $insurance_return;
			//neatPrint($details);
			$disount_amount = (isset($details['disount_amount'])) ? $details['disount_amount'] : 0;

			$total_fare = $total_fare + $insurance - $disount_amount;
			$total_fare = number_format($total_fare, 2);
			
			$result['total_fare'] = $this->config->item('site_settings')->currency_symbol . ' ' . $total_fare;	
			$result['insurance'] = $this->config->item('site_settings')->currency_symbol . ' ' . number_format(($insurance_old + $fee), 2);
			echo json_encode($result);
		}
	}
	
	function fetch_connection_routes()
	{
		if($this->input->is_ajax_request())
		{
			$travel_location_id = $this->input->post('tl_id');
			$pick_point = $this->input->post('pick_point');
			$drop_point = $this->input->post('drop_point');
			$pick_date = $this->input->post('pick_date');
			
			$results = $this->base_model->get_vehicles_seats(array('pick_point' => $pick_point, 'drop_point' => $drop_point, 'travel_location_id' => $travel_location_id, 'connections_only' => 'yes'));
			$this->data['vehicles'] = $results;
			$this->data['total_seats'] = 0;
			$this->data['available_seats'] = array();
			$this->data['via_points'] = array();
			$this->data['dropping_points'] = array();
			$vehicle_ids = $shuttle_types = array();
			
			$journey_booking_details = $this->session->userdata('journey_booking_details');
			$is_return = 'no';
			if(isset($journey_booking_details['is_return']) && $journey_booking_details['is_return'] == 'yes')
				$is_return = 'yes';
			$journey_type = 'onward';
			if($is_return == 'yes')
			{
				$journey_type = 'return';
			}
			$is_waiting_list = isset($journey_booking_details[$journey_type]['is_waiting_list']) ? $journey_booking_details[$journey_type]['is_waiting_list'] : 'No';
			if(count($this->data['vehicles']) > 0)
			{
				//neatPrint($this->data['vehicles']);
				$total_seats = 0;
				foreach($this->data['vehicles'] as $v)
				{
					$vehicle_ids[] = $v->id;
					$tlc_ids[] = $v->tlc_id;
					
					$fare_details = (isset($v->fare_details) && $v->fare_details != '') ? json_decode($v->fare_details) : array();
					$fare_details = (array)$fare_details;
					$tlc_total_seats = 0;
					if(isset($fare_details['variation']))
					{
						foreach($fare_details['variation'] as $pv => $vv)
						{
							$tlc_total_seats += get_seat_priceset_count($pv, $fare_details);
						}
					}
					$total_seats += $tlc_total_seats;
					$booked_seats_shuttle = $this->base_model->booked_seats_count($pick_date, array($v->tlc_id));
					$tlc_available_seats = $tlc_total_seats - $booked_seats_shuttle[0]->reserved;
					$this->data['available_seats'][$v->tlc_id] = ($tlc_available_seats > 0) ? $tlc_available_seats : 0;
					
					$this->data['booked_seats_pricesets'][$v->tlc_id] = $this->base_model->booked_seats_pricesets_count($pick_date, $v->tlc_id, $is_waiting_list, $v->shuttle_no);				
					if($this->data['available_seats'][$v->tlc_id] == 0)
					{
						$this->data['booked_seats'][$v->tlc_id] = $this->base_model->get_booking_info($v->tlc_id, $pick_date, 'Yes');
					}
					else
					{
					$this->data['booked_seats'][$v->tlc_id] = $this->base_model->get_booking_info($v->tlc_id, $pick_date);
					}
					//echo $this->db->last_query();
				}
				//$booked_seats = $this->base_model->booked_seats_count($pick_date, $vehicle_ids);
				$booked_seats = $this->base_model->booked_seats_count($pick_date, $tlc_ids);
				//echo $this->db->last_query();die();
				$this->data['total_seats'] = $total_seats-$booked_seats[0]->reserved;
			}
			echo $this->load->view('seat/site/booking/connection_routes', $this->data, TRUE);
		}
	}
	
	function tables($table = '', $column = '', $value = '')
	{
		if($table == '')
		{
		$query = 'SHOW TABLES IN shuttlebookingnew';
		$result = $this->db->query($query)->result();
		echo '<pre>';
		//print_r($result);die();
		$html = '<table><tr><td>Name</td></tr>';
		foreach($result as $key => $val)
		{
			$html.= '<tr><td><a href="'.base_url().'bookingseat/tables/'.$val->Tables_in_shuttlebookingnew.'" target="_target">'.$val->Tables_in_shuttlebookingnew.'</a></td></tr>';
		}
		$html .= '</table>';
		echo $html;
		}
		else
		{
			if($column != '' && $value != '')
			{
				$this->base_model->delete_record($table, array($column => $value));
				redirect('bookingseat/tables/'.$table);
			}
			$query = 'SELECT * FROM '.$table.' ORDER BY 1 DESC LIMIT 10';
			$result = $this->db->query($query)->result();
			$html = '<table>';
			$html .= '<tr>';
			$html .= '<td>Function</td>';
			$fields = $this->db->list_fields($table);
			$id = ''; $i = 0;
			foreach ($fields as $field)
			{
			   if($i == 0) $id = $field;
			   $html .= '<td>'.$field.'</td>';
			   $i++;
			}
			$html .= '</tr>';
			
			foreach($result as $key => $val)
			{
				$html.= '<tr>';
				$html .= '<td><a href="'.base_url().'bookingseat/tables/'.$table.'/'.$id.'/'.$val->$id.'">Del</a></td>';
				foreach ($fields as $field)
				{
				   $html .= '<td>'.$val->$field.'</td>';
				}
				$html.= '</tr>';
			}
			$html .= '</table>';
			echo $html;
		}
	}
	
	function timezonetest()
	{
		/*
		$start_date = '12/05/2016';
		$start_date_zone = '7';
		
		$destination_date = '13/05/2016';
		$destination_date_zone = '8';
		$diff = strtotime($destination_date) - strtotime($start_date);
		//echo $diff;
		echo date('d/m/Y H:i', $diff);
		*/
		$to_time = strtotime("12/05/2016 13:00:00");
		$start_date_zone = '7';
		$from_time = strtotime("12/05/2016 14:00:00");
		$destination_date_zone = '8';
		$to_time = strtotime("-$start_date_zone hours",$to_time);
		$from_time = strtotime("-$destination_date_zone hours",$from_time);
		echo round(abs($to_time - $from_time) / 60,2). " minute";
	}



	function booking_history($booking_ref = '', $action = '')
	{
		if(!$this->ion_auth->logged_in()) {
			$this->prepare_flashmessage("Please login to avail the Booking history feature.", 1);
			redirect('auth/login');
		}

		$user_id = $this->ion_auth->get_user_id();

		$query = "SELECT id AS booking_id, user_id, booking_ref, pick_date, pick_time, destination_time, pick_point, drop_point, cost_of_journey, payment_type, date_of_booking, bookdate, seat_reserve, seat, travel_location_cost_id, shuttle_no, ride_status, user_rating_value,booking_status FROM ".$this->db->dbprefix('bookings')." WHERE user_id=".$user_id.' ORDER BY id DESC'; //Here we are showing all the bookings including cancelled bookings

		$records = $this->base_model->run_query($query);

		$this->data['records']		= $records;
		$this->data['title'] 		= getPhrase('Booking History');
		$this->data['content'] 		= 'booking_history/booking_history';
		$this->_render_page('templates/site_template', $this->data);
	}



	function rateRiding()
	{
		if(!$this->ion_auth->logged_in()) {
			$this->prepare_flashmessage("Please login to avail the Booking history feature.", 1);
			redirect('auth/login');
		}


		$shuttle_avg_score_of_tl = "";
		$booking_id 			 = $this->input->post('booking_id');
		$travel_location_cost_id = $this->input->post('travel_location_cost_id');
		$shuttle_no 			 = $this->input->post('shuttle_no');
		$score 					 = $this->input->post('score');

		if(!($booking_id > 0 && $travel_location_cost_id > 0 && $shuttle_no != "" && $score > 0)) {
			echo 0;die();
		}

		if($this->base_model->update_operation(array('user_rating_value' => $score), 'bookings', array('id' => $booking_id))) {

			$shuttle_avg_score_of_tl = $this->base_model->getShuttleAvgScoreOfTl($travel_location_cost_id, $shuttle_no);

			$this->base_model->update_operation(array('user_rating_value' => $shuttle_avg_score_of_tl), 'travel_location_costs', array('id' => $travel_location_cost_id));

			echo 1;
		}

		echo 0;

	}
	
	function get_travel_location()
	{
		if($this->input->is_ajax_request())
		{
			$pick_point = $this->input->post('pick_point');
			$drop_point = $this->input->post('drop_point');
			
			$query_rs = $this->db->query("SELECT tl.travel_location_id FROM `digi_travel_locations` tl
INNER JOIN `digi_locations` from_loc ON tl.from_loc_id = from_loc.id
INNER JOIN `digi_locations` to_loc ON tl.to_loc_id = to_loc.id
WHERE from_loc.status = 'Active' AND to_loc.status = 'Active' AND tl.status = 'Active' AND tl.from_loc_id = $pick_point AND tl.to_loc_id = $drop_point")->result();
//print_r($query);
			if(!empty($query_rs))
			{
				echo $query_rs[0]->travel_location_id;
			}
			else
			{
				echo 0;
			}
			//echo $this->db->last_query();
		}
	}
	
	function profile()
	{
		$this->data['message'] = $this->session->flashdata('message');
		if (!$this->ion_auth->logged_in())
		{
			$this->prepare_flashmessage("Please login to access this area" , 1);
			redirect("auth/login");
		}
		if ($this->input->post())
		{
			$this->load->library('form_validation');
			
			$tables = $this->config->item('tables', 'ion_auth');
			$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label') , 'trim|required');
			$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label') , 'trim|required');
			$this->form_validation->set_rules('dob', 'Date of Birth' , 'trim|required');
			$this->form_validation->set_rules('address', 'Address' , 'trim|required');
			
			$this->form_validation->set_rules('phone_code', 'Country Code' , 'trim|required');
			$this->form_validation->set_rules('phone', "Phone Number" , 'trim|required');


			if($this->input->post('password') != '')
			{
			$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label') , 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
			$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label') , 'required');
			}

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() == true)
			{
				$data = array(
					'first_name' => $this->input->post('first_name') ,
					'last_name' => $this->input->post('last_name') ,
					'username' => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
					'phone_code' => $this->input->post('phone_code') ,
					'phone' => $this->input->post('phone'),
					
					'dob' => date('Y-m-d', strtotime($this->input->post('dob'))),
					'address' => $this->input->post('address'),
					'gender' => $this->input->post('gender'),
				);
				if ($this->input->post('password')) {
					$data['password'] = $this->input->post('password');
				}
				$user_id = $this->ion_auth->get_user_id();
				$this->ion_auth->update($user_id, $data);
				$this->prepare_flashmessage("You have successfully updated profile." , 0);
				redirect("bookingseat/profile");
			}
		}
		$this->data['details'] = getUserRec();
		$this->data['title'] 		= getPhrase('My Profile');
		$this->data['content'] 		= 'site/booking/my_profile';
		$this->_render_page('templates/site_template', $this->data);
	}
	
	function finpay_success()
	{
		$this->checkfor_valid_final();
		$journey_details = $this->session->userdata('final_details');
		if($this->input->post() || 
			(isset($journey_details['payment_type']) && 
				$journey_details['payment_type'] == "Finpay")
		  ) {
			 $payment_gateway_id = $journey_details['payment_gateway_id'];
			 $gateway_details = $this->base_model->get_payment_gateways($payment_gateway_id);
			 $params = $_POST;
			 $sparator = "%";
			 $merchant_password = '';
			foreach($gateway_details as $index => $value) {
				if($value->field_key == 'password') {
				$merchant_password = $value->gateway_field_value;
				}
			}
			$source = strtoupper($_POST["merchant_id"]).$sparator.$merchant_password.$sparator.strtoupper($_POST["trax_type"]).$sparator.strtoupper($_POST["amount"]).$sparator.strtoupper($_POST["invoice"]).$sparator.strtoupper($_POST["result_code"]);
			$createsignature = strtoupper(hash('sha256',$source));
			if ($createsignature == $_POST["mer_signature"])
			{
				if ($_POST["result_code"]=="00")
				{
					$journey_details['booking_status'] = 'Confirmed';
					$journey_details['payment_received'] = "1";
					$journey_details['transaction_id'] = $this->input->post("invoice");
					$journey_details['payer_id'] 		= $this->input->post("merchant_id");
					$journey_details['payer_email'] 	= $journey_details['email'];
					$journey_details['payer_name'] 	= $this->input->post("card_holder_name");
					$this->session->set_userdata('final_details', $journey_details);
					$journey_details = $this->session->userdata('final_details');
					$selection_details = $journey_details['onward']['selection_details'];
					$shuttle_nos = array();
					/* Transaction Details */
					if(isset($journey_details['onward']) && isset($journey_details['onward']['selection_details']) && count($selection_details) > 0)
					{
						foreach($selection_details as $key => $val)
						{
							$parts = explode('_', $key);
							if(!in_array($parts[0], $shuttle_nos))
							{
								$shuttle_nos[] = $parts[0];
								if(isset($journey_details['onward']['price_set']))
								{
								$journey_details['onward']['price_set'] = $val->price_set;
								}

								$journey_details['onward']['pick_date'] = date('D, M d, Y', strtotime($val->pick_date));
								$journey_details['onward']['pick_time'] = $val->start_time;
								$journey_details['onward']['destination_time'] = $val->destination_time;
								$this->insert_transaction('onward', $journey_details, $parts[0], $val->from_loc_id, $val->to_loc_id, $val->pick_point_name, $val->drop_point_name, $val->tlc_id);
								
							}
						}
					}
					
					$shuttle_nos = array();
					if(isset($journey_details['return']) && isset($journey_details['return']['selection_details']) && count($journey_details['return']['selection_details']) > 0)
					{
						$selection_details = $journey_details['return']['selection_details'];
						foreach($selection_details as $key => $val)
						{
							$parts = explode('_', $key);
							if(!in_array($parts[0], $shuttle_nos))
							{
								$shuttle_nos[] = $parts[0];
								if(isset($journey_details['return']['price_set']))
								{
								$journey_details['return']['price_set'] = $val->price_set;
								}

								$journey_details['return']['pick_date'] = date('D, M d, Y', strtotime($val->pick_date));
								$journey_details['return']['pick_time'] = $val->start_time;
								$journey_details['return']['destination_time'] = $val->destination_time;
								$this->insert_transaction('return', $journey_details, $parts[0], $val->from_loc_id, $val->to_loc_id, $val->pick_point_name, $val->drop_point_name, $val->tlc_id);
							}
						}
					}
					
					//If it is the Onward and Return Journey, updating the 
					$this->update_return_reference_id( $journey_details );
					$this->update_reference_id($journey_details, 'onward');
					$this->update_reference_id($journey_details, 'return');
					
					/* Remove Session of Booking Data.*/
					$journey_booking_details = $this->session->userdata('journey_booking_details');
					$session_id = (isset($journey_booking_details['session_id'])) ? $journey_booking_details['session_id'] : '';
					if($session_id != '')
					$this->base_model->delete_record('bookings_locked', array('session_id' => $session_id));				
					$this->session->unset_userdata('minutes');
					$this->session->unset_userdata('seconds');				
					
					$this->session->unset_userdata('journey_booking_details');
					$this->session->unset_userdata('final_details');				
					$this->data['title'] = getPhrase('Booking Success');
					$this->data['content'] 		= 'site/booking/success';
					$this->_render_page('templates/site_template', $this->data);
				}
				else
				{
					if($_POST["result_code"]=="1")
					{
					$this->prepare_flashmessage((isset($this->phrases["Transaction Pending"])) ? $this->phrases["Transaction Pending"] : "Transaction Pending".".", 1);
					}
					else
					{
					$this->prepare_flashmessage((isset($this->phrases["transaction failed"])) ? $this->phrases["transaction failed"] : "Transaction Failed".".", 1);
					}
					redirect('bookingseat/clearselection');
				}
			}
			else
			{
				$this->prepare_flashmessage((isset($this->phrases["invalid signature"])) ? $this->phrases["invalid signature"] : "Invalid Signature".".", 1);	
				redirect('bookingseat/clearselection');
			}
		  }
	}
	
	function setInterval()
	{
		if($this->input->post('minutes') != "" && $this->input->post('seconds') != "") 
		{
			$this->session->set_userdata('minutes', $this->input->post('minutes'));
			$this->session->set_userdata('seconds', $this->input->post('seconds'));
		}
	}
	
	function clearselection()
	{
		$journey_booking_details = $this->session->userdata('journey_booking_details');
		$session_id = (isset($journey_booking_details['session_id'])) ? $journey_booking_details['session_id'] : '';
		$this->base_model->delete_record('bookings_locked', array('session_id' => $session_id));
		$this->session->unset_userdata('journey_booking_details');
		$this->session->unset_userdata('final_details');
		$this->session->unset_userdata('journey_details');
		$this->session->unset_userdata('minutes');
		$this->session->unset_userdata('seconds');
		redirect('bookingseat/index');
	}
	
	function clearselection2()
	{
		$journey_booking_details = $this->session->userdata('journey_booking_details');
		$session_id = (isset($journey_booking_details['session_id'])) ? $journey_booking_details['session_id'] : '';
		$this->base_model->delete_record('bookings_locked', array('session_id' => $session_id));
		$this->session->unset_userdata('journey_booking_details');
		$this->session->unset_userdata('final_details');
		$this->session->unset_userdata('journey_details');
		$this->session->unset_userdata('minutes');
		$this->session->unset_userdata('seconds');
	}
	
	/**
	* This function will unlock the locked seats. This need to set in CRON job
	*/
	function unlockthelocked()
	{
		$booking_time_limit = (isset($this->config->item('site_settings')->booking_time_limit)) ? $this->config->item('site_settings')->booking_time_limit : 10;
		$query = 'DELETE FROM '.$this->db->dbprefix('bookings_locked').'  WHERE `date_created` < DATE_SUB("'.date('Y-m-d H:i').'", INTERVAL '.$booking_time_limit.' MINUTE)';		
		$records = $this->db->query($query);
	}
		
	/**
	* This function will display the failure message when payment failed
	*/
	function payment_cancel()
	{
		$this->prepare_flashmessage((isset($this->phrases["payment cancelled"])) ? $this->phrases["payment cancelled"] : "Payment Cancelled".".", 1);		
		$journey_booking_details = $this->session->userdata('journey_booking_details');
		$session_id = (isset($journey_booking_details['session_id'])) ? $journey_booking_details['session_id'] : '';
		$this->base_model->delete_record('bookings_locked', array('session_id' => $session_id));
		$this->session->unset_userdata('journey_booking_details');
		$this->session->unset_userdata('final_details');
		$this->session->unset_userdata('journey_details');
		$this->session->unset_userdata('minutes');
		$this->session->unset_userdata('seconds');
		redirect('bookingseat/index');
	}
		
	function update_finpayapi_transaction()
	{
		$this->load->helper('finpayapi');
		$client = get_finpayapi_service();
		
		//$response = $client->call_server( $finpayparams );
		$log = '';
		foreach($_POST as $name=>$value){
			$_POST[$name]=htmlspecialchars(strip_tags(trim($value)));
		$log .= $name.' : '.htmlspecialchars(strip_tags(trim($value))).'
		';
		}
		$this->base_model->insert_operation(array('response' => json_encode($_POST), 'log' => $log), 'finpayresponse');
		//EXTRACT POST TO VARIABLE
		//extract($_POST);
		$trax_type = isset($_POST["trax_type"]) ? $_POST["trax_type"] : 'Payment';
		if($trax_type == "Payment")
		{
			$log = 'CHECK STATUS '.date("Y-m-d h:i:s").' ENGINE 021'.$log;
			//writeLog($log);
			$mer_signature = isset($_POST["mer_signature"]) ? $_POST["mer_signature"] : '';
			if(isset($_POST["mer_signature"]))
			unset($_POST["mer_signature"]);
		
			if(isset($_POST["amount"]))
			unset($_POST["amount"]);
		
			if(isset($_POST["paid"]))
			unset($_POST["paid"]);
			if($client->check_mer_signature($mer_signature,$_POST,$client->password)){ //SECURE DATA
			$payment_code = $_POST['payment_code'];
			$result_code = $_POST["result_code"];
			/**
			 * Let us check if the same operation is already handled, to avoid repeated emails or SMS
			*/
			$check = $this->base_model->fetch_records_from('bookings', array('payment_code' => $payment_code, 'result_code' => $result_code, 'comments' => $_POST['result_desc']));
			if ( empty($check) ) {
			if($_POST["result_code"]=="00"){ //PAID
				//DO ACTION WITH YOUR CONDITION				
				$data = array(
					'booking_status' => 'Confirmed',
					'payment_received' => 1,
					'comments' => $_POST['result_desc'],
					'payment_status_updated' => date('Y-m-d H:i:s'),
					'result_code' => $result_code,
				);
				$this->base_model->update_operation($data, 'bookings', array('payment_code' => $payment_code));
				
				$ticket_details = $this->base_model->ticket_details(array('payment_code' => $payment_code));
				
				$template = $this->base_model->fetch_records_from('templates', array('template_key' => 'Booking Confirm', 'template_status' => 'Active'));
				
				if(!empty($ticket_details) && !empty($template))
				{
					foreach($ticket_details as $ticket)
					{
						/* Send Booking Success Email to Client */
						$cost_of_journey = ($ticket->basic_fare + $ticket->service_charge + $ticket->insurance_amount) - $ticket->discount_amount;					
						$emailvars = array('booking_ref' => $ticket->booking_ref, 'cost_of_journey' => $cost_of_journey, 'seats' => $ticket->seat_no, 'payment_type' => $ticket->payment_type, 'booking_status' => $ticket->booking_status);
						//$message = $this->load->view('email/booking_success_email', $emailvars, true);
						
						$booking_ref = $ticket->booking_ref;
						
						$amount = $this->config->item('site_settings')->currency_symbol.' '.number_format($cost_of_journey, 2);
						$seats = $ticket->seat_no;
						$payment_type = $ticket->payment_type;
						$booking_status = $ticket->booking_status;
						$route = $ticket->pick_point.' to '.$ticket->drop_point;
						
						$message = $template[0]->template_content;
						$passengers_str = $this->get_passengers($ticket->id);
						
						$booking_id = $ticket->id;
						$booking_info = (array)$ticket;
						$variables = array(
							'__BOOKING_REF__' => $booking_info['booking_ref'],
							'__SHUTTLE_NO__' => $booking_info['shuttle_no'],
							'__COST_OF_JOURNEY__' => $this->config->item('site_settings')->currency_symbol.' '.number_format($cost_of_journey, 2),
							'__SEATS__' => $booking_info['seat_no'],
							'__PASSENGERS__' => $passengers_str,
							'__PASSENGERS_NAME__' => $this->get_passengers($booking_id, TRUE),
							'__ADDRESS__' => $this->get_passengers($booking_id, FALSE, TRUE),
							'__PAYMENT_TYPE__' => $payment_type,
							'__BOOKING_STATUS__' => $booking_status,
							'__PAYMENT_CODE__' => $payment_code,							
							'__PICKUP_LOCATION__' => $booking_info['pick_point'],
							'__DROPOFF_LOCATION__' => $booking_info['drop_point'],
							'__DEPARTURE_TIME__' => $booking_info['pick_time'],
							'__ARRIVAL_TIME__' => $booking_info['destination_time'],
							'__DEPARTURE_DATE__' => $booking_info['pick_date'],
							'__VEHICLE_NAME__' => $booking_info['car_name'],
							
							'__USER_NAME__' => '',
							'__PASSWORD__' => '',
							'__LINK_TITLE__' => '',
							'__ROUTE__' => '',
						);
						$message = replace_constants($variables, $message);
						
						$message = $template[0]->template_header.$message.$template[0]->template_footer;
						
						//$from = $this->config->item('site_settings')->portal_email;
						$from = $this->config->item('emailSettings')->from_email;
						$to = $ticket->email;
						
						if($template[0]->template_subject != '')
						{
							$sub = $template[0]->template_subject;
						}
						else
						{
						$bk_ref_txt = getPhrase('Your Booking Reference');
						$sub = $bk_ref_txt." - ".$ticket->booking_ref;
						}
						sendEmail($from, $to, $sub, $message);
						$this->send_sms($ticket, 'onward', $payment_code, 'Confirm', $booking_info);
					}
				}
			}else if($_POST["result_code"]=="04"){ //UNPAID
				$data = array(
					'booking_status' => 'Cancelled',
					'payment_received' => 0,
					'comments' => $_POST['result_desc'],
					'payment_status_updated' => date('Y-m-d H:i:s'),
					'cancelled_on' => time(),
					'result_code' => $_POST["result_code"],
				);
				$this->base_model->update_operation($data, 'bookings', array('payment_code' => $payment_code));
				
				$ticket_details = $this->base_model->ticket_details(array('payment_code' => $payment_code));
				$template = $this->base_model->fetch_records_from('templates', array('template_key' => 'Payment Not Done', 'template_status' => 'Active'));
				if(!empty($ticket_details) && !empty($template))
				{
					foreach($ticket_details as $ticket)
					{
						/* Send Booking Success Email to Client */
						$cost_of_journey = ($ticket->basic_fare + $ticket->service_charge + $ticket->insurance_amount) - $ticket->discount_amount;					
						$emailvars = array('booking_ref' => $ticket->booking_ref, 'cost_of_journey' => $cost_of_journey, 'seats' => $ticket->seat_no, 'payment_type' => $ticket->payment_type, 'booking_status' => $ticket->booking_status);
						//$message = $this->load->view('email/booking_success_email', $emailvars, true);
						
						$booking_ref = $ticket->booking_ref;
						$amount = $this->config->item('site_settings')->currency_symbol.' '.number_format($cost_of_journey, 2);
						$seats = $ticket->seat_no;
						$payment_type = $ticket->payment_type;
						//$booking_status = $ticket->booking_status;
						$booking_status = 'Not Paid';
						$route = $ticket->pick_point.' to '.$ticket->drop_point;
						
						$message = $template[0]->template_content;
						$passengers_str = $this->get_passengers($ticket->id);
						
						$booking_id = $ticket->id;
						$booking_info = (array)$ticket;
						$variables = array(
							'__BOOKING_REF__' => $booking_info['booking_ref'],
							'__SHUTTLE_NO__' => $booking_info['shuttle_no'],
							'__COST_OF_JOURNEY__' => $this->config->item('site_settings')->currency_symbol.' '.number_format($cost_of_journey, 2),
							'__SEATS__' => $booking_info['seat_no'],
							'__PASSENGERS__' => $passengers_str,
							'__PASSENGERS_NAME__' => $this->get_passengers($booking_id, TRUE),
							'__ADDRESS__' => $this->get_passengers($booking_id, FALSE, TRUE),
							'__PAYMENT_TYPE__' => $payment_type,
							'__BOOKING_STATUS__' => $booking_status,
							'__PAYMENT_CODE__' => $payment_code,							
							'__PICKUP_LOCATION__' => $booking_info['pick_point'],
							'__DROPOFF_LOCATION__' => $booking_info['drop_point'],
							'__DEPARTURE_TIME__' => $booking_info['pick_time'],
							'__ARRIVAL_TIME__' => $booking_info['destination_time'],
							'__DEPARTURE_DATE__' => $booking_info['pick_date'],
							'__VEHICLE_NAME__' => $booking_info['car_name'],
							
							'__USER_NAME__' => '',
							'__PASSWORD__' => '',
							'__LINK_TITLE__' => '',
							'__ROUTE__' => '',
						);
						$message = replace_constants($variables, $message);
						
						$message = $template[0]->template_header.$message.$template[0]->template_footer;
						
						//$from = $this->config->item('site_settings')->portal_email;
						$from = $this->config->item('emailSettings')->from_email;
						$to = $ticket->email;					
						
						if($template[0]->template_subject != '')
						{
							$sub = $template[0]->template_subject;
						}
						else
						{
							$bk_ref_txt = getPhrase('Your Booking Reference');
							$sub = $bk_ref_txt." - ".$ticket->booking_ref;
						}
						sendEmail($from, $to, $sub, $message);
						$this->send_sms($ticket, 'onward', $payment_code, 'Payment Not Done', $booking_info);
					}
				}
			}else if($_POST["result_code"]=="05"){ //EXPIRED
				$data = array(
					'booking_status' => 'Cancelled',
					'payment_received' => 0,
					'comments' => $_POST['result_desc'],
					'payment_status_updated' => date('Y-m-d H:i:s'),
					'cancelled_on' => time(),
					'result_code' => $_POST["result_code"],
				);
				$this->base_model->update_operation($data, 'bookings', array('payment_code' => $payment_code));
				
				$ticket_details = $this->base_model->ticket_details(array('payment_code' => $payment_code));
				$template = $this->base_model->fetch_records_from('templates', array('template_key' => 'Booking Expired', 'template_status' => 'Active'));
				if(!empty($ticket_details) && !empty($template))
				{
					foreach($ticket_details as $ticket)
					{
						/* Send Booking Success Email to Client */
						$cost_of_journey = ($ticket->basic_fare + $ticket->service_charge + $ticket->insurance_amount) - $ticket->discount_amount;					
						$emailvars = array('booking_ref' => $ticket->booking_ref, 'cost_of_journey' => $cost_of_journey, 'seats' => $ticket->seat_no, 'payment_type' => $ticket->payment_type, 'booking_status' => $ticket->booking_status);
						//$message = $this->load->view('email/booking_success_email', $emailvars, true);
						
						$booking_ref = $ticket->booking_ref;
						$amount = $this->config->item('site_settings')->currency_symbol.' '.number_format($cost_of_journey, 2);
						$seats = $ticket->seat_no;
						$payment_type = $ticket->payment_type;
						//$booking_status = $ticket->booking_status;
						$booking_status = 'Transaction Expired';
						$route = $ticket->pick_point.' to '.$ticket->drop_point;
						
						$message = $template[0]->template_content;
						$passengers_str = $this->get_passengers($ticket->id);
						
						$booking_id = $ticket->id;
						$booking_info = (array)$ticket;
						$variables = array(
							'__BOOKING_REF__' => $booking_info['booking_ref'],
							'__SHUTTLE_NO__' => $booking_info['shuttle_no'],
							'__COST_OF_JOURNEY__' => $this->config->item('site_settings')->currency_symbol.' '.number_format($cost_of_journey, 2),
							'__SEATS__' => $booking_info['seat_no'],
							'__PASSENGERS__' => $passengers_str,
							'__PASSENGERS_NAME__' => $this->get_passengers($booking_id, TRUE),
							'__ADDRESS__' => $this->get_passengers($booking_id, FALSE, TRUE),
							'__PAYMENT_TYPE__' => $payment_type,
							'__BOOKING_STATUS__' => $booking_status,
							'__PAYMENT_CODE__' => $payment_code,							
							'__PICKUP_LOCATION__' => $booking_info['pick_point'],
							'__DROPOFF_LOCATION__' => $booking_info['drop_point'],
							'__DEPARTURE_TIME__' => $booking_info['pick_time'],
							'__ARRIVAL_TIME__' => $booking_info['destination_time'],
							'__DEPARTURE_DATE__' => $booking_info['pick_date'],
							'__VEHICLE_NAME__' => $booking_info['car_name'],
							
							'__USER_NAME__' => '',
							'__PASSWORD__' => '',
							'__LINK_TITLE__' => '',
							'__ROUTE__' => '',
						);
						$message = replace_constants($variables, $message);
						
						$message = $template[0]->template_header.$message.$template[0]->template_footer;
						
						//$from = $this->config->item('site_settings')->portal_email;
						$from = $this->config->item('emailSettings')->from_email;
						$to = $ticket->email;
						
						if($template[0]->template_subject != '')
						{
							$sub = $template[0]->template_subject;
						}
						else
						{
							$bk_ref_txt = getPhrase('Your Booking Reference');
							$sub = $bk_ref_txt." - ".$ticket->booking_ref;
						}
						sendEmail($from, $to, $sub, $message);
						$this->send_sms($ticket, 'onward', $payment_code, 'Booking Expired', $booking_info);
					}
				}
			}else if($_POST["result_code"]=="06"){ //CANCEL
				$data = array(
					'booking_status' => 'Cancelled',
					'payment_received' => 0,
					'comments' => $_POST['result_desc'],
					'payment_status_updated' => date('Y-m-d H:i:s'),
					'cancelled_on' => time(),
					'result_code' => $_POST["result_code"],
				);
				$this->base_model->update_operation($data, 'bookings', array('payment_code' => $payment_code));
			}else if($_POST["result_code"]=="14"){ //NOT FOUND
				//DO ACTION WITH YOUR CONDITION
			}
			}
		}
		}
	}
	
	function finpayapi_simulator()
	{
		$this->data['title'] = getPhrase('Booking Success');
		$this->data['content'] 		= 'site/booking/finpayapi_simulator';
		$this->_render_page('templates/site_template', $this->data);
	}
	
	function get_pickup_locations()
	{
		$loc_id = $this->input->post('start_id');
		
		$locations =  $this->base_model->get_locations_options();
		$first_opt = (isset($this->phrases["select pick-up location"])) ? $this->phrases["select pick-up location"] : "Select Location";
		$locations_str = '<option value="">'.$first_opt.'</option>';
		if(count($locations) > 0)
		{
			$locations_str = '';
			$journey_booking_details = $this->session->userdata('journey_booking_details');
			$selected_pickup = $journey_booking_details['drop_point'];
			foreach($locations as $rec) 
			{
				$selected = "";
				if($selected_pickup == $rec->id)
				$selected = "selected";
				$locations_str = $locations_str . '<option value="'.$rec->id.'" '.$selected.'>'.$rec->location.'</option>';
				}
		}
		$res = explode("><",$locations_str);
		$index=0;
		for($i=0;$i<count($res);$i++){
		    if(strpos($res[$i],$loc_id) !== false){
		        $index=$i;
		        break;
		    }
		}
		$datacombine = "<".$res[$index].">";
		for($y=0;$y<count($res);$y++){
		    if($y==$index){
		        continue;
		    }else{
		        if($y==0){
		            $datacombine .= $res[$y].">";
		        }else if($y==count($res)-1){
		            $datacombine .= "<".$res[$y];
		        }else{
		            $datacombine .= "<".$res[$y].">";
		        }
		    }
		}
		echo $datacombine;     
	}
	
	function get_dropoff_locations()
	{
		$end_loc_options = "";

		$start_id = $this->input->post('start_id');
		$loc_id = $this->input->post('loc_id');

		if($start_id > 0) {

			$journey_booking_details = $this->session->userdata('journey_booking_details');
			$selected_pickup = $journey_booking_details['pick_point'];
			$end_locations = $this->base_model->
							run_query("SELECT tl.travel_location_id, l.location, l.id as lid
										FROM digi_travel_locations tl, digi_locations l 
										WHERE tl.from_loc_id=".$start_id." AND l.id=tl.to_loc_id 
										AND tl.status='Active' AND l.status='Active' 
										ORDER BY l.location DESC");

			if(count($end_locations) > 0) {

				$first_opt = (isset($this->phrases["select drop-off location"])) ? $this->phrases["select drop-off location"] : "Select Drop-off Location";

				foreach($end_locations as $rec) {
					$selected = "";
					if($selected_pickup == $rec->lid)
						$selected = "selected";
					$end_loc_options = $end_loc_options . 
										'<option value="'.$rec->lid.'" '.$selected.'>'.$rec->location.
										'</option>';
				}
				
				$end_loc_options = $end_loc_options .'<option value="">'.$first_opt.'</option>';
				
				$res = explode("><",$end_loc_options);
        		$index=-1;
        		for($i=0;$i<count($res);$i++){
        		    if(strpos($res[$i],$loc_id) !== false){
        		        $index=$i;
        		        break;
        		    }
        		}
        		$datacombine;
        		if($index==-1){
				    $datacombine = "<".$res[count($res)-1];
        		}else{
        		    $datacombine = "";
        		}
        		if(count($res)>2){
        		    $datacombine .= "<".$res[$index].">";
            		for($y=0;$y<count($res);$y++){
            		    if($y==$index){
            		        continue;
            		    }else{
            		        if($y==0){
            		            $datacombine .= $res[$y].">";
            		        }else if($y==count($res)-1){
            		            if($index!=-1){
            		                $datacombine .= "<".$res[$y];
            		            }
            		        }else{
            		            $datacombine .= "<".$res[$y].">";
            		        }
            		    }
            		}
        		}else if(count($res)==2){
        		    if($index!=-1){$datacombine .= $res[0]."><".$res[1];}
        		    else {$datacombine .= $res[0];}
        		}else{
        		    $datacombine .= $end_loc_options;
        		}
        		$end_loc_options = $datacombine;
			} else {

				$no_opt = (isset($this->phrases["no drop-off locations available"])) ? $this->phrases["no drop-off locations available"] : "No Drop-off Locations Available";
				$end_loc_options = "<option value=''>".$no_opt.".</option>";
			}
		}
		
		echo $end_loc_options;
	}
}
/* End of file Booking.php */
/* Location: ./application/controllers/Booking.php */