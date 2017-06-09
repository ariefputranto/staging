<?php 
ob_start();
class MY_Controller extends CI_Controller
{
	protected $data;
	public $phrases = array();
	public $dynamicpages = array();
	public $vehicle_typeshome = array();
	public $isdemo;
	
	function __construct()
	{

		parent::__construct();

		/* Set Phrases in Array */
		$this->phrases = $this->config->item('words');
		$this->dynamicpages = $this->config->item('dynamic_pages');
		$this->vehicle_typeshome = $this->config->item('vehicle_typeshome');

		/**set the site settings from admin...**********/
		$this->data['site_settings'] = $this->config->item('site_settings');
		$this->data['site_theme'] = $this->config->item('site_settings')->site_theme;

		$site_country 	= $this->config->item('site_settings')->site_country;
		$site_time_zone = $this->config->item('site_settings')->site_time_zone;

		setlocale(LC_MONETARY, "en_".strtoupper($site_country)); //'en_US'
		date_default_timezone_set($site_time_zone);
		$this->data['country_code'] = $site_country;
		$this->isdemo = FALSE;
	}
	
	function create_thumbnail($sourceimage,$newpath, $width, $height)
	{
		
		$this->load->library('image_lib');
		$this->image_lib->clear();
		
		$config['image_library'] = 'gd2';
		$config['source_image'] = $sourceimage;
		$config['create_thumb'] = TRUE;
		$config['new_image'] = $newpath;
		$config['dynamic_output'] = FALSE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $width;
		$config['height'] = $height;
	    $config['thumb_marker'] = '';
	
		$this->image_lib->initialize($config); 
		return $this->image_lib->resize();
	}

	function is_valid($id=0)
	{
		if($id==0)
		return FALSE;

		$recs = $this->session->userdata('logindata');

		if(count($recs)>0)
		{
			if($recs->user_group_id==1)
			{
				// ADMIN
				//redirect('admin/index');
				return TRUE;

			}
			else if($recs->user_group_id==2)
			{
				//USER
				$this->prepare_flashmessage("You have no permission to view",3);
				redirect('users/dashboard');
			}
			
		}
		
		//NOT LOGGED IN
		 		$this->prepare_flashmessage("Session Expired..",1);
				redirect('users/login');
	}

	function prepare_flashmessage($msg,$type)
	{
		$returnmsg='';
		switch($type){
			case 0: $returnmsg = " <div class='col-md-12'>
										<div class='alert alert-success'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>Success!</strong> ". $msg."
										</div>
									</div>";
				break;
			case 1: $returnmsg = " <div class='col-md-12'>
										<div class='alert alert-danger'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>Error!</strong> ". $msg."
										</div>
									</div>";
				break;
			case 2: $returnmsg = " <div class='col-md-12'>
										<div class='alert alert-info'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>Info!</strong> ". $msg."
										</div>
									</div>";
				break;
			case 3: $returnmsg = " <div class='col-md-12'>
										<div class='alert alert-warning'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>Warning!</strong> ". $msg."
										</div>
									</div>";
				break;

                      case 4: $returnmsg = " <div class='col-md-12'>
										<div class='alert alert-danger'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong></strong> ". $msg."
										</div>
									</div>";
				break;





		}
		
		$this->session->set_flashdata("message",$returnmsg);
	}
	
	/*
	* This function will prepare the mesage to be displayed for user or  admin. This funciton specially used to prepare nice error  message when validations failed or any other information
	* @param string $msg
	* @param string $type
	* return string
	*/
	function prepare_message($msg,$type)
	{
		$returnmsg='';
		switch($type){
			case 0: $returnmsg = " <div class='col-md-12'>
										<div class='alert alert-success'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>Success!</strong> ". $msg."
										</div>
									</div>";
				break;
			case 1: $returnmsg = " <div class='col-md-12'>
										<div class='alert alert-danger'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>Error!</strong> ". $msg."
										</div>
									</div>";
				break;
			case 2: $returnmsg = " <div class='col-md-12'>
										<div class='alert alert-info'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>Info!</strong> ". $msg."
										</div>
									</div>";
				break;
			case 3: $returnmsg = " <div class='col-md-12'>
										<div class='alert alert-warning'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong>Warning!</strong> ". $msg."
										</div>
									</div>";
				break;

                      case 4: $returnmsg = " <div class='col-md-12'>
										<div class='alert alert-danger'>
											<a href='#' class='close' data-dismiss='alert'>&times;</a>
											<strong></strong> ". $msg."
										</div>
									</div>";
				break;





		}
		return $returnmsg;
	}

 
	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function _render_page($view, $data=null, $render=false)
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $render);

		if (!$render) return $view_html;
	}


	// set Pagination
	function set_pagination($url,$offset,$numrows,$perpage,$pagingfunction='')
	{
		$config['base_url'] = base_url().$url;  //Setting Pagination parameters
		$config['per_page'] = $perpage;
		$config['offset'] = $offset;
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['num_links'] = 4; // numlinks before and after current page
		$config['total_rows'] =  $numrows;
		
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		
		if(!empty($pagingfunction))
			$config['paging_function'] = $pagingfunction; 
		else	$config['paging_function'] = 'ajax_paging';
		$this->pagination->initialize($config);  
	}
	
	function sendSMS($to, $message, $toadmin = FALSE)
	{
		/*SMS Sending Start*/
		$smsinfo = '';
		$smsquery = 'SELECT * FROM '.$this->db->dbprefix('gateways').' g LEFT JOIN '.$this->db->dbprefix('gateways_fields').' gf ON g.gateway_id = gf.gateway_id LEFT JOIN '.$this->db->dbprefix('gateways_fields_values').' gfv ON gf.field_id = gfv.`gateway_field_id` WHERE g.type = "sms" AND is_default=1';
		$smsgateway_details = $this->base_model->fetch_records_from_query_object($smsquery);
		if(count($smsgateway_details) > 0)
		{
			$smsmessage = $message;			
			$smsto = $to;
			$smstoadmin = $this->config->item('site_settings')->phone_code . $this->config->item('site_settings')->phone;
			if($smsgateway_details[0]->gateway_title == 'Cliakatell') 
			{
				$this->load->library('clickatell');
				$response = $this->clickatell->send_message($smsto, $smsmessage);
				if($toadmin)
				$response = $this->clickatell->send_message($smstoadmin, $smsmessage);
				if($response === FALSE) {
					$smsinfo = $sms_hd_txt.' : ' . $this->clickatell->error_message;
				}
			}
			if($smsgateway_details[0]->gateway_title == 'Twilio') 
			{
				$this->load->helper('ctech-twilio');
				$client = get_twilio_service();
				$twilioquery = 'SELECT * FROM '.$this->db->dbprefix('gateways').' g INNER JOIN '.$this->db->dbprefix('gateways_fields').' gf ON g.`gateway_id`=gf.`gateway_id` LEFT JOIN '.$this->db->dbprefix('gateways_fields_values').' gfv ON gf.`field_id` = gfv.`gateway_field_id` WHERE g.`gateway_title`="Twilio" AND gf.field_key="number" ORDER BY gf.field_order ASC LIMIT 1';		
				$twiliogateway_details = $this->base_model->fetch_records_from_query_object( $twilioquery );
				$twilio_number = $twiliogateway_details[0]->gateway_field_value;
				try {				
					$response = $client->account->messages->sendMessage($twilio_number,$smsto,$smsmessage);
					if($toadmin)
					$response = $client->account->messages->sendMessage($twilio_number,$smstoadmin,$smsmessage);
				} catch (Exception $e ){										
					$smsinfo = $sms_hd_txt.' : ' . $e->getMessage();
				}
			}
			if($smsgateway_details[0]->gateway_title == 'Nexmo') 
			{
				$this->load->library('nexmo');
				$this->nexmo->set_format('json');
				$from = '1234567890';
				$smstext = array(
					'text' => $smsmessage,
				);
				$response = $this->nexmo->send_message($from, $smsto, $smstext);
				if($toadmin)
				$response = $this->nexmo->send_message($from, $smstoadmin, $smstext);
				$status = $response['messages'][0]['status'];
				if($status != 0) {
					$smsinfo = $sms_hd_txt.' : ' . $response['messages'][0]['error-text'];
				}
			}
			if($smsgateway_details[0]->gateway_title == 'Plivo') 
			{
				$this->load->library('plivo');
				$sms_data = array(
					'src' => '919700376656', //The phone number to use as the caller id (with the country code). E.g. For USA 15671234567
					'dst' => $smsto, // The number to which the message needs to be send (regular phone numbers must be prefixed with country code but without the ‘+’ sign) E.g., For USA 15677654321.
					'text' => $smsmessage, // The text to send
					'type' => 'sms', //The type of message. Should be 'sms' for a text message. Defaults to 'sms'
				);									
				$response = $this->plivo->send_sms($sms_data);
				if($toadmin)
				{
					$sms_data = array(
						'src' => '919700376656', //The phone number to use as the caller id (with the country code). E.g. For USA 15671234567
						'dst' => $smstoadmin, // The number to which the message needs to be send (regular phone numbers must be prefixed with country code but without the ‘+’ sign) E.g., For USA 15677654321.
						'text' => $smsmessage, // The text to send
						'type' => 'sms', //The type of message. Should be 'sms' for a text message. Defaults to 'sms'
					);									
					$response = $this->plivo->send_sms($sms_data);
				}
				if ($response[0] != '202')
				{
					$smsinfo = $sms_hd_txt.' : ' . $data["response"]["message"];
				}
			}
			if($smsgateway_details[0]->gateway_title == 'Solutionsinfini') 
			{
				$this->load->helper('solutionsinfini');
				$solution_object = new sendsms();
				$response = $solution_object->send_sms($smsto, $smsmessage, current_url());
				if($toadmin)
				$response = $solution_object->send_sms($smstoadmin, $smsmessage, current_url());				
				if(strpos($response,'Message GID') === false) {
					$smsinfo = $sms_hd_txt.' : ' . $response;
				}
			}
		$scheduledata = array(
		'gateway_id' => $smsgateway_details[0]->gateway_id,
		'gateway_title' => $smsgateway_details[0]->gateway_title,
		'to_number' => $smsto,
		'message' => $smsmessage,
		'resultmessage' => $smsinfo,
		'sent_datetime' => date('Y-m-d H:i:s'),
		);
		$this->base_model->insert_operation($scheduledata, 'smslog');
		return true;
	}
	else
	{
		return false;
	}
		/*SMS Sending End*/
	}
	
	function check_isdemo($redirect_url)
	{
		if($this->isdemo == TRUE)
		{
			$this->prepare_flashmessage('Access Denied', 2);
			redirect($redirect_url);
		}
	}
	
	function get_passengers($booking_id, $is_single = FALSE, $is_address = FALSE)
	{
		$passengers_str = '';
		if ( $is_single == TRUE || $is_address == TRUE ) {
			$passengers = $this->base_model->fetch_records_from('bookings_passengers', array('booking_id' => $booking_id, 'is_primary' => 'Yes'));
		} else {
			$passengers = $this->base_model->fetch_records_from('bookings_passengers', array('booking_id' => $booking_id));
		}
		if(!empty($passengers))
		{
			if ( $is_single == TRUE ) {
				foreach($passengers as $p) {
					$passengers_str = $p->name;
					break; //To take only one passenger
				}
			} elseif ( $is_address == TRUE ) {
				foreach($passengers as $p) {
					$passengers_str = $p->complete_pickup_address;
					break; //To take only one passenger address
				}
			}else {
				foreach($passengers as $p)
				{
					$passengers_str .= 'Name : '.$p->name.'<br>';
					/*$passengers_str .= 'Seat : '.$p->seat_no.'<br>';
					$passengers_str .= 'Type : '.$p->passenger_type.'<br>';
					$passengers_str .= 'Shuttle : '.$p->shuttle_no.'<br>';
					$passengers_str .= 'Address : '.$p->complete_pickup_address.'<br>';
					*/
				}
				
				$infants = $this->base_model->fetch_records_from('bookings_passengers_infants', array('booking_id' => $booking_id));
				foreach($infants as $p)
				{
					$passengers_str .= 'Name : '.$p->infant_name.'<br>';
					/*
					$passengers_str .= 'Age : '.$p->infant_age.'<br>';
					$passengers_str .= 'Type : Infant <br>';
					*/
				}
			}
		}
		return $passengers_str;
	}
	
	function send_sms($journey_details = '', $journey_type = '', $payment_code = '', $status = '', $booking_info = array())
	{
		$booking_id = '';
		if(isset($_POST['booking_ref']))
		{
			$journey_details = array();
			$ticket_details = $this->base_model->ticket_details(array('booking_ref' => $_POST['booking_ref']), 0, 1);
			
			if(count($ticket_details) > 0)
			{
				$journey_type = 'onward';
				$journey_details['booking_ref'] = $_POST['booking_ref'];
				$journey_details['onward']['discount_amount'] = 0;
				$journey_details['onward']['total_fare'] = $ticket_details[0]->cost_of_journey;				
				$journey_details['onward']['phone_code'] = $ticket_details[0]->phone_code;
				$journey_details['onward']['phone'] = $ticket_details[0]->phone;
				$booking_id = $ticket_details[0]->id;
			}
		}
		
		if(!empty($payment_code))
		{
			$journey_details = array();
			$ticket_details = $this->base_model->ticket_details(array('payment_code' => $payment_code), 0, 1);
			
			if(count($ticket_details) > 0)
			{
				$cost_of_journey = ($ticket_details[0]->basic_fare + $ticket_details[0]->service_charge + $ticket_details[0]->insurance_amount) - $ticket_details[0]->discount_amount;
				$journey_type = 'onward';
				$journey_details['booking_ref'] = $ticket_details[0]->booking_ref;
				$journey_details['seat_no'] = $ticket_details[0]->seat_no;
				$journey_details['onward']['discount_amount'] = 0;
				$journey_details['onward']['total_fare'] = $cost_of_journey;				
				$journey_details['onward']['phone_code'] = $ticket_details[0]->phone_code;
				$journey_details['onward']['phone'] = $ticket_details[0]->phone;
				$booking_id = $ticket_details[0]->id;
			}
		}
		/*SMS Sending Start*/
		$smsinfo = '';
		
		$discount_amount = (isset($journey_details[$journey_type]['discount_amount'])) ? $journey_details[$journey_type]['discount_amount'] : 0;
		$amount = $journey_details[$journey_type]['total_fare'] - $discount_amount;
		$smsgateway_details = $this->base_model->get_sms_gateway();
		
		if($status == 'Confirm')
		{
			$template = $this->base_model->fetch_records_from('templates_sms', array('template_key' => 'Booking Confirm', 'template_status' => 'Active'));
		}
		elseif($status == 'Payment Not Done')
		{
			$template = $this->base_model->fetch_records_from('templates_sms', array('template_key' => 'Payment Not Done', 'template_status' => 'Active'));
		}
		elseif($status == 'Booking Expired')
		{
			$template = $this->base_model->fetch_records_from('templates_sms', array('template_key' => 'Booking Expired', 'template_status' => 'Active'));
		}
		elseif($status == 'Booking Cancelled')
		{
			$template = $this->base_model->fetch_records_from('templates_sms', array('template_key' => 'Booking Cancelled', 'template_status' => 'Active'));
		}
		else
		{
			$template = $this->base_model->fetch_records_from('templates_sms', array('template_key' => 'Booking Success', 'template_status' => 'Active'));
		}
		
		if(count($smsgateway_details) > 0 && count($template) > 0)
		{
			$sms_hd_txt = getPhrase('SMS Sent Failed. Reason');
			$sms_msg_txt1 = getPhrase('Booking Details Your Onward Booking Reference is');
			if($journey_type == 'return')
				$sms_msg_txt1 = getPhrase('Booking Details Your Return Booking Reference is');
			$sms_msg_txt2 = getPhrase('Journey Cost');
			$booking_ref = $journey_details['booking_ref'];
			if($journey_type == 'return')
				$booking_ref = $journey_details['booking_ref'].'R';
			$seats = isset($journey_details['seat_no']) ? $journey_details['seat_no'] : '';
			
			$smsmessage = $template[0]->template_content;
			$passengers_str = $this->get_passengers($booking_id);
			
			
			if ( ! empty($booking_info) ) {
			$tlc_amount = ($booking_info['basic_fare'] + $booking_info['service_charge'] + $booking_info['insurance_amount']) - $booking_info['discount_amount'];
			$booking_status = isset( $booking_info['booking_status'] ) ? $booking_info['booking_status'] : '';
			if ( $status == 'Booking Expired' ) {
				$booking_status = 'Expired';
			}
			$variables = array(
				'__BOOKING_REF__' => $booking_ref,
				'__SHUTTLE_NO__' => isset($booking_info['shuttle_no']) ? $booking_info['shuttle_no'] : '',
				'__COST_OF_JOURNEY__' => $this->config->item('site_settings')->currency_symbol.' '.number_format($tlc_amount, 2),
				'__SEATS__' => isset($booking_info['seat_no']) ? $booking_info['seat_no'] : '',
				'__PASSENGERS__' => $passengers_str,
				'__PASSENGERS_NAME__' => $this->get_passengers($booking_id, TRUE),
				'__ADDRESS__' => $this->get_passengers($booking_id, FALSE, TRUE),
				'__PAYMENT_TYPE__' => isset($booking_info['payment_type']) ? $booking_info['payment_type'] : '',
				'__BOOKING_STATUS__' => $booking_status,
				'__PAYMENT_CODE__' => (isset($booking_info['payment_code'])) ? $booking_info['payment_code'] : '',				
				'__PICKUP_LOCATION__' => isset( $booking_info['pick_point'] ) ? $booking_info['pick_point'] : '',
				'__DROPOFF_LOCATION__' => isset($booking_info['drop_point']) ? $booking_info['drop_point'] : '',
				'__DEPARTURE_TIME__' => isset( $booking_info['pick_time'] ) ? $booking_info['pick_time'] : '',
				'__ARRIVAL_TIME__' => isset( $booking_info['destination_time'] ) ? $booking_info['destination_time'] : '',
				'__DEPARTURE_DATE__' => isset( $booking_info['pick_date'] ) ? $booking_info['pick_date'] : '',
				'__VEHICLE_NAME__' => isset( $booking_info['car_name'] ) ? $booking_info['car_name'] : '',				
				'__USER_NAME__' => '',
				'__PASSWORD__' => '',
				'__LINK_TITLE__' => '',
				'__ROUTE__' => '',
			);
			$smsmessage = replace_constants($variables, $smsmessage);
			
			} else {
				/*
				$smsmessage = str_replace('__BOOKING_REF__', $booking_ref, $smsmessage);
				if($booking_id != '')
				{
					
					if($passengers_str != '')
					{
						$smsmessage = str_replace('__PASSENGERS__', $passengers_str, $smsmessage);
					}
				}			
				$smsmessage = str_replace('__COST_OF_JOURNEY__', $this->config->item('site_settings')->currency_symbol . ' '.$amount, $smsmessage);
				$smsmessage = str_replace('__SEATS__', $seats, $smsmessage);
				*/
				$variables = array(
					'__BOOKING_REF__' => $booking_ref,
					'__SHUTTLE_NO__' => '',
					'__COST_OF_JOURNEY__' => $this->config->item('site_settings')->currency_symbol.' '.number_format($amount, 2),
					'__SEATS__' => $seats,
					'__PASSENGERS__' => $passengers_str,
					'__PASSENGERS_NAME__' => $this->get_passengers($booking_id, TRUE),
					'__ADDRESS__' => $this->get_passengers($booking_id, FALSE, TRUE),
					'__PAYMENT_TYPE__' => '',
					'__BOOKING_STATUS__' => '',
					'__PAYMENT_CODE__' => '',				
					'__PICKUP_LOCATION__' => '',
					'__DROPOFF_LOCATION__' => '',
					'__DEPARTURE_TIME__' => '',
					'__ARRIVAL_TIME__' => '',
					'__DEPARTURE_DATE__' => '',
					'__VEHICLE_NAME__' => '',				
					'__USER_NAME__' => '',
					'__PASSWORD__' => '',
					'__LINK_TITLE__' => '',
					'__ROUTE__' => '',
				);
				$smsmessage = replace_constants($variables, $smsmessage);
			}
						
			$smsto = $journey_details[$journey_type]['phone_code'] . $journey_details[$journey_type]['phone'];
			$phone_code = $journey_details[$journey_type]['phone_code'];
			$phone = $journey_details[$journey_type]['phone'];
						
			$smstoadmin = $this->config->item('site_settings')->phone_code . $this->config->item('site_settings')->phone;
			if($smsgateway_details[0]->gateway_title == 'Cliakatell') 
			{
				$this->load->library('clickatell');
				$response = $this->clickatell->send_message($smsto, $smsmessage);
				$response = $this->clickatell->send_message($smstoadmin, $smsmessage);
				if($response === FALSE) {
					$smsinfo = $sms_hd_txt.' : ' . $this->clickatell->error_message;
				}
			}
			elseif($smsgateway_details[0]->gateway_title == 'Twilio') 
			{
				$this->load->helper('ctech-twilio');
				$client = get_twilio_service();
				$twilioquery = 'SELECT * FROM '.$this->db->dbprefix('gateways').' g INNER JOIN '.$this->db->dbprefix('gateways_fields').' gf ON g.`gateway_id`=gf.`gateway_id` LEFT JOIN '.$this->db->dbprefix('gateways_fields_values').' gfv ON gf.`field_id` = gfv.`gateway_field_id` WHERE g.`gateway_title`="Twilio" AND gf.field_key="number" ORDER BY gf.field_order ASC LIMIT 1';		
				$twiliogateway_details = $this->base_model->fetch_records_from_query_object( $twilioquery );
				$twilio_number = $twiliogateway_details[0]->gateway_field_value;
				try {				
					$response = $client->account->messages->sendMessage($twilio_number,$smsto,$smsmessage);
					$response = $client->account->messages->sendMessage($twilio_number,$smstoadmin,$smsmessage);
				} catch (Exception $e ){										
					$smsinfo = $sms_hd_txt.' : ' . $e->getMessage();
				}
			}
			elseif($smsgateway_details[0]->gateway_title == 'Nexmo') 
			{
				$this->load->library('nexmo');
				$this->nexmo->set_format('json');
				$from = '1234567890';
				$smstext = array(
					'text' => $smsmessage,
				);
				$response = $this->nexmo->send_message($from, $smsto, $smstext); //SMS to User
				$response = $this->nexmo->send_message($from, $smstoadmin, $smstext); //SMS to Admin
				$status = $response['messages'][0]['status'];
				if(isset($status) && $status != 0) {
					$smsinfo = $sms_hd_txt.' : ' . $response['messages'][0]['error-text'];
				}
			}
			elseif($smsgateway_details[0]->gateway_title == 'Plivo') 
			{
				$this->load->library('plivo');
				$sms_data = array(
					'src' => '919700376656', //The phone number to use as the caller id (with the country code). E.g. For USA 15671234567
					'dst' => $smsto, // The number to which the message needs to be send (regular phone numbers must be prefixed with country code but without the ‘+’ sign) E.g., For USA 15677654321.
					'text' => $smsmessage, // The text to send
					'type' => 'sms', //The type of message. Should be 'sms' for a text message. Defaults to 'sms'
				);									
				$response = $this->plivo->send_sms($sms_data);
				
				$sms_data = array(
					'src' => '919700376656', //The phone number to use as the caller id (with the country code). E.g. For USA 15671234567
					'dst' => $smstoadmin, // The number to which the message needs to be send (regular phone numbers must be prefixed with country code but without the ‘+’ sign) E.g., For USA 15677654321.
					'text' => $smsmessage, // The text to send
					'type' => 'sms', //The type of message. Should be 'sms' for a text message. Defaults to 'sms'
				);									
				$response = $this->plivo->send_sms($sms_data);
				
				if ($response[0] != '202')
				{
					$response = json_decode($response[1], TRUE);				
					$smsinfo = $sms_hd_txt.' : ' . $response["error"];
				}
			}
			elseif($smsgateway_details[0]->gateway_title == 'Solutionsinfini') 
			{
				$this->load->helper('solutionsinfini');
				$solution_object = new sendsms();
				$response = $solution_object->send_sms($smsto, $smsmessage, current_url());
				$response = $solution_object->send_sms($smstoadmin, $smsmessage, current_url());				
				if(strpos($response,'Message GID') === false) {
					$smsinfo = $sms_hd_txt.' : ' . $response;
				}
			}
			elseif($smsgateway_details[0]->gateway_title == 'Gosms')
			{
				$this->load->library('gosms');
				$errors = array(
					'1702' => 'Invalid Username or Password ',
					'1703' => 'Internal Server Error',
					'1704' => 'Data not found',
					'1705' => 'Process Failed',
					'1706' => 'Invalid Message',
					'1707' => 'Invalid Number',
					'1708' => 'Insufficient Credit',
					'1709' => 'Group Empty',
					
					'1711' => 'Invalid Group Name',
					'1712' => 'Invalid Group ID',
					'1713' => 'Invalid msgid',
					
					'1721' => 'Invalid Phonebook Name',
					'1722' => 'Invalid Phonebook ID',
					
					'1731' => 'User Name already exist',
					'1732' => 'Sender ID not valid',
					'1733' => 'Internal Error – please contact administrator',
					'1734' => 'Invalid client user name',
					'1735' => 'Invalid Credit Value ',
				);
				$gosms = new gosms();
				$response = $gosms->send_sms($smsto, $smsmessage);				
				//$response = $gosms->get_balance();
				//var_dump($response);die();
				if(!in_array($response, array_keys($errors))) //Success
				$smsinfo = 'SMS Sent successfull : ' . $response;
				else
				{
					if(isset($errors[$response]))
					$smsinfo = $sms_hd_txt.' : Response code - ' . $errors[$response];
				else
					$smsinfo = $sms_hd_txt.' : Response code - ' . $response;
				}
					
			}
			
			$scheduledata = array(
			'gateway_id' => $smsgateway_details[0]->gateway_id,
			'gateway_title' => $smsgateway_details[0]->gateway_title,
			'to_number' => $smsto,
			'message' => $smsmessage,
			'resultmessage' => $smsinfo,
			'sent_datetime' => date('Y-m-d H:i:s'),
			);
			$this->base_model->insert_operation($scheduledata, 'smslog');
		}
		/*SMS Sending End*/
		if(isset($_POST['booking_ref']) )
		{
			if(count($journey_details) > 0 && count($ticket_details) > 0)
			{
				echo json_encode(array('status' => 1, 'result' => $ticket_details, 'message' => 'Details sent to mobile number : '.$ticket_details[0]->phone));
			}
			else
			{
				echo json_encode(array('status' => 0, 'message' => 'Failed to send SMS. Please contact admin'));
			}
		}
	}
}

?>
