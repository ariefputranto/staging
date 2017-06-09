<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends MY_Controller
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
		
		if($this->data['site_theme'] == 'seat')
		{
			redirect('bookingseat/index');
		}
	}


	/****** Journey, Date & Time ******/
	function index()
	{
		/* Check For Form Submission */
		if($this->input->post()) {

			/* Form Validations */
			$this->form_validation->set_rules(
			'pick_point', 
			(isset($this->phrases["pick-up location"])) ? 
			$this->phrases["pick-up location"] : "Pick-up Location", 
			'required'
			);
			$this->form_validation->set_rules(
			'drop_point', 
			(isset($this->phrases["drop-off location"])) ? 
			$this->phrases["drop-off location"] : "Drop-off Location", 
			'required'
			);

			$this->form_validation->set_error_delimiters(
			'<div class="error">', '</div>'
			);

			if($this->form_validation->run() == true) {

				$journey_booking_details = array();

				if(count($this->session->userdata(
				'journey_booking_details')) > 0) {
					$journey_booking_details = $this->session->userdata(
					'journey_booking_details');
				}

				foreach($this->input->post() as $key=>$val)
					$journey_booking_details[$key] = $val;


				$this->session->set_userdata(
				'journey_booking_details', $journey_booking_details
				);

				redirect('booking/vehicleSelection');
			}

		}


		$this->data['css_type']		= array(
												'datepicker', 
												'timepicker', 
												'gmap'
											   );
		$this->data['title'] 		= (isset($this->phrases["cab booking"])) ? 
										$this->phrases["cab booking"] : "Cab Booking";
		$this->data['content'] 		= 'site/booking/booking';
		$this->_render_page('templates/site_template', $this->data);
	}
	
	function checkfor_valid()
	{
		$session_deails = $this->session->userdata('journey_booking_details');
		if(empty($session_deails))
		{
			$this->prepare_flashmessage((isset($this->phrases["please select a Pick-up and Drop-off Location for your journey"])) ? $this->phrases["please select a Pick-up and Drop-off Location for your journey"] : "Please select a Pick-up and Drop-off Location for your Journey".".", 1);
				redirect('booking/index');
		}
	}
	function checkfor_valid_final()
	{
		$final_details = $this->session->userdata('final_details');
		if(empty($final_details))
		{
			$this->prepare_flashmessage((isset($this->phrases["please select a Pick-up and Drop-off Location for your journey"])) ? $this->phrases["please select a Pick-up and Drop-off Location for your journey"] : "Please select a Pick-up and Drop-off Location for your Journey".".", 1);
				redirect('booking/index');
		}
	}
	/****** Vehicle Selection ******/
	function vehicleSelection()
	{
		$this->checkfor_valid();
		/* Check For Form Submission */
		if($this->input->post()) {
			if($this->input->post('vehicle_selected') > 0 && 
				$this->input->post('car_name') != "" && 
				$this->input->post('cost_of_journey') > 0
			   ) {


				$journey_booking_details = $this->session->userdata(
				'journey_booking_details');

				foreach($this->input->post() as $key=>$val)
					$journey_booking_details[$key] = $val;


				$this->session->set_userdata(
				'journey_booking_details', $journey_booking_details
				);

				redirect('booking/passengerDetails');

			} else {

				$this->prepare_flashmessage((isset($this->phrases["please select a vehicle for your journey"])) ? $this->phrases["please select a vehicle for your journey"] : "Please select a vehicle for your Journey".".", 1);
				redirect('booking/vehicleSelection');
			}

		}


		$query = "SELECT tlc.cost, v.*, vc.category 
				FROM digi_travel_location_costs tlc, 
				digi_vehicle v, digi_vehicle_categories vc  
				WHERE v.id=tlc.vehicle_id 
				AND vc.id=v.category_id AND tlc.status='Active' 
				AND v.status='Active' AND vc.status='Active' 
				AND v.total_vehicles > 
				(SELECT COUNT(*) FROM digi_bookings b 
				WHERE b.vehicle_selected=v.id AND 
				b.pick_date='".date('Y-m-d', strtotime($this->session->userdata('journey_booking_details')['pick_date']))."' AND b.pick_time='".$this->session->userdata('journey_booking_details')['pick_time']."') 
				AND tlc.travel_location_id=".
				$this->session->userdata('journey_booking_details')['drop_point']." 
				ORDER BY tlc.cost ";

		$vehicles = $this->base_model->run_query($query);
		//echo "<pre>"; print_r($vehicles);die();
		if(count($vehicles) == 0) {

			$this->prepare_flashmessage((isset($this->phrases["sorry, no vehicles available for this route for your requirement"])) ? $this->phrases["sorry, no vehicles available for this route for your requirement"] : "Sorry, No Vehicles available for this route for your requirement".".", 2);
			redirect('booking');

		}

		$this->data['vehicles']		= $vehicles;
		$this->data['css_type']		= array(
												'bxslider'
											   );
		$this->data['title'] 		= (isset($this->phrases["vehicle selection"])) ? 
										$this->phrases["vehicle selection"] : "Vehicle Selection";
		$this->data['content'] 		= 'site/booking/vehicle_selection';
		$this->_render_page('templates/site_template', $this->data);
	}
	
	
	/****** Passenger Details ******/
	function passengerDetails()
	{
		$this->checkfor_valid();
		/* Check For Form Submission */
		if($this->input->post()) {

			/* Form Validations */
			$this->form_validation->set_rules(
			'registered_name', 
			(isset($this->phrases["your name"])) ? $this->phrases["your name"] : "Your Name", 
			'required'
			);
			$this->form_validation->set_rules(
			'email', 
			(isset($this->phrases["your email"])) ? $this->phrases["your email"] : "Your Email", 
			'required|valid_email'
			);
			$this->form_validation->set_rules(
			'phone', 
			(isset($this->phrases["your phone number"])) ? $this->phrases["your phone number"] : "Your Phone Number", 
			'required'
			);
			$this->form_validation->set_rules(
			'complete_pickup_address', 
			(isset($this->phrases["your complete pick-up address"])) ? $this->phrases["your complete pick-up address"] : "Your Complete Pick-up Address",  
			'required'
			);
			$this->form_validation->set_rules(
			'complete_destination_address', 
			(isset($this->phrases["your drop-off address"])) ? $this->phrases["your drop-off address"] : "Your Drop-off Address", 
			'required'
			);

			$this->form_validation->set_error_delimiters(
			'<div class="error">', '</div>'
			);

			if($this->form_validation->run() == true) {

				$journey_booking_details = $this->session->userdata(
				'journey_booking_details');

				foreach($this->input->post() as $key=>$val)
					$journey_booking_details[$key] = $val;

				$this->session->set_userdata(
				'journey_booking_details', $journey_booking_details
				);

				redirect('booking/viewDetails');
			}

		}


		$this->data['title'] 	= (isset($this->phrases["passenger details"])) ? 
									$this->phrases["passenger details"] : "Passenger Details";
		$this->data['content'] 	= 'site/booking/passenger_details';
		$this->_render_page('templates/site_template', $this->data);
	}



	/****** View Details ******/
	function viewDetails()
	{
		$this->checkfor_valid();
		if(count($this->session->userdata('journey_booking_details')) > 0) {

			$this->data['record'] 	= $this->session->userdata('journey_booking_details');
			$this->data['title'] 	= (isset($this->phrases["view details"])) ? 
									$this->phrases["view details"] : "View Details";
			$this->data['content'] 	= 'site/booking/view_details';
			$this->_render_page('templates/site_template', $this->data);

		} else {

			redirect('booking');
		}
	}



	/****** Payment ******/
	function payment()
	{
		$this->checkfor_valid();
		
		/* Check For Form Submission */
		if($this->input->post()) {

			$query = 'SELECT * FROM '.$this->db->dbprefix('gateways').' g LEFT JOIN '.$this->db->dbprefix('gateways_fields').' gf ON g.gateway_id = gf.gateway_id LEFT JOIN '.$this->db->dbprefix('gateways_fields_values').' gfv ON gf.field_id = gfv.`gateway_field_id` WHERE g.gateway_id = '.$this->input->post('payment_type');
			$gateway_details = $this->base_model->fetch_records_from_query_object($query);
			if(count($gateway_details) > 0) {
				$journey_booking_details = $this->session->userdata('journey_booking_details');
				//print_r($journey_booking_details);die();
				$cost_of_journey = $journey_booking_details['cost_of_journey'] + $journey_booking_details['cost_for_meet_greet'];
				$booking_ref = date('ymdHis');
				$journey_booking_details['payment_type'] 	= $gateway_details[0]->gateway_title;
				$journey_booking_details['payment_gateway_id'] 	= $this->input->post('payment_type');
				$journey_booking_details['cost_of_journey'] = $cost_of_journey;
				$journey_booking_details['booking_ref'] 	= $booking_ref;
				$this->session->set_userdata('final_details', $journey_booking_details);
				
				if($gateway_details[0]->gateway_title == "Paypal") {
					$config['business'] = '';
					$config['cpp_header_image'] 	= base_url().$site_theme.'/'."assets/system_design/images/". strtolower($gateway_details[0]->gateway_title).'.png';
					$config['return'] 				= base_url().'booking/success';
					$config['cancel_return'] 		= base_url().'booking/payment_cancel';
					$config['notify_url'] 			= '';//'process_payment.php'; //IPN Post
					$config['production'] 	= true;
					$config['currency_code'] 		= 'USD';
					foreach($gateway_details as $index => $value) {
						if($value->field_key == 'paypal_email') {
							$config['business'] = $value->gateway_field_value;
						}
						if($value->field_key == 'sandbox') {
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
				} elseif($gateway_details[0]->gateway_title == "PayU") {
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
					//$cost_of_journey = $journey_booking_details['cost_of_journey'] + $journey_booking_details['cost_for_meet_greet'];
					$payuparams['surl'] = base_url() . 'booking/payustatus';
					$payuparams['furl'] = base_url() . 'booking/payustatus';
					$payuparams['service_provider'] = 'payu_paisa';
					$payuparams['productinfo'] = $journey_booking_details['journey_type'] . ' Journey From ' . $journey_booking_details['pick_point_name'] . ' To ' . $journey_booking_details['drop_point_name'];					
					$payuparams['amount'] = $cost_of_journey;
					$payuparams['firstname'] = $journey_booking_details['registered_name'];
					$payuparams['email'] = $journey_booking_details['email'];
					$payuparams['phone'] = $journey_booking_details['phone'];
					$this->load->helper('payu');					
					echo call_payu( $payuparams );
					die();
				} elseif($gateway_details[0]->gateway_title == "Cash") {

					redirect('booking/success');
				}

			} else {

				$this->prepare_flashmessage((isset($this->phrases["please select payment method"])) ? $this->phrases["please select payment method"] : "Please select Payment method".".", 1);
				redirect('booking/payment');
			}

		}

		$this->data['gateways']	= $this->base_model->fetch_records_from('gateways', array('type' => 'payment', 'gateway_status' => 'active'), '', 'gateway_title', 'DESC');
		$this->data['title'] 		= (isset($this->phrases["payment"])) ? $this->phrases["payment"] : "Payment";
		$this->data['content'] 		= 'site/booking/payment';
		$this->_render_page('templates/site_template', $this->data);
	}

	function payustatus()
	{
		$this->checkfor_valid_final();
		if($this->input->post())
		{
			$booking_info 	 = $this->session->userdata('final_details');
			$journey_details = $this->session->userdata('final_details');

			$query = 'SELECT * FROM '.$this->db->dbprefix('gateways').' g LEFT JOIN '.$this->db->dbprefix('gateways_fields').' gf ON g.gateway_id = gf.gateway_id LEFT JOIN '.$this->db->dbprefix('gateways_fields_values').' gfv ON gf.field_id = gfv.`gateway_field_id` WHERE g.gateway_title = "PayU"';
			$gateway_details = $this->base_model->fetch_records_from_query_object($query);
			if(count($gateway_details) > 0) {
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
					  $sms_bd_txt1 = '';
					  $sms_bd_txt2 = '';
					  $sms_bd_txt3 = '';
					  $sms_bd_txt4 = '';
					  $sms_bd_txt5 = '';
					  $sms_bd_txt6 = '';
					  if($status == 'success') {
						
						$booking_info['booking_status']	= "Confirmed";
						$booking_info['user_id'] = ($this->ion_auth->logged_in()) ?  $this->ion_auth->get_user_id() : '0';
						$booking_info['pick_point']	= $booking_info['pick_point_name'];
						$booking_info['drop_point']	= $booking_info['drop_point_name'];
						$booking_info['pick_date']	= date('Y-m-d', strtotime($booking_info['pick_date']));
						$booking_info['return_pick_date']	= date('Y-m-d', strtotime($booking_info['return_pick_date']));
						$booking_info['distance']	= $booking_info['ip_dist_txt'];
						$booking_info['transaction_id']	= $_POST["txnid"];
						$booking_info['other_details']	= implode(" ", $_POST);
						$booking_info['payment_received']	= 1;
						/* Remove the data that is not required for 
						 * inserting into the table from booking_info array */
						unset($booking_info['pick_point_name']);
						unset($booking_info['drop_point_name']);
						unset($booking_info['ip_dist_txt']);
						unset($booking_info['ip_time_txt']);
						unset($booking_info['meet_greet_chkbx']);
						if($booking_info['journey_type'] != "Round-Trip") {
							unset($booking_info['return_pick_date']);
							unset($booking_info['return_pick_time']);
						}
						$booking_info['date_of_booking']  = date('Y-m-d');
						$booking_info['bookdate']		  = time();
						if($this->base_model->insert_operation($booking_info, 'bookings')) {

							$bk_txt2 = (isset($this->phrases["your booking reference"])) ? 
								$this->phrases["your booking reference"] : "Your Booking Reference";

							$sms_hd_txt = (isset($this->phrases["SMS sent failed. Reason"])) ? 
								$this->phrases["SMS sent failed. Reason"] : "SMS Sent Failed. Reason";

							/* Send Booking Success Email to Client */
							$message = $this->load->view('email/booking_success_email', $journey_details, true);
							$from = $this->config->item('site_settings')->portal_email;
							$to = $journey_details['email'];
							$sub = $bk_txt2." - ".$journey_details['booking_ref'];
							sendEmail($from, $to, $sub, $message);

							/*SMS Sending Start*/
							$smsinfo = '';
							$smsquery = 'SELECT * FROM '.$this->db->dbprefix('gateways').' g LEFT JOIN '.$this->db->dbprefix('gateways_fields').' gf ON g.gateway_id = gf.gateway_id LEFT JOIN '.$this->db->dbprefix('gateways_fields_values').' gfv ON gf.field_id = gfv.`gateway_field_id` WHERE g.type = "sms" AND is_default=1';
							$smsgateway_details = $this->base_model->fetch_records_from_query_object($smsquery);
							if(count($smsgateway_details) > 0)
							{
								$sms_msg_txt1 = (isset($this->phrases["booking details your booking reference is"])) ? $this->phrases["booking details your booking reference is"] : "Booking Details
								Your Booking Reference is";

								$sms_msg_txt2 = (isset($this->phrases["journey cost"])) ? $this->phrases["journey cost"] : "Journey Cost";

								$smsmessage = $sms_msg_txt1.' '.$journey_details['booking_ref'].'
								'.$sms_msg_txt2.' '.$this->config->item('site_settings')->currency_symbol.$amount;
								$smsto = $booking_info['phone_code'] . $booking_info['phone'];
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
								if($smsgateway_details[0]->gateway_title == 'Twilio') 
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
								if($smsgateway_details[0]->gateway_title == 'Nexmo') 
								{
									$this->load->library('nexmo');
									$this->nexmo->set_format('json');
									$from = '1234567890';
									$smstext = array(
										'text' => $smsmessage,
									);
									$response = $this->nexmo->send_message($from, $smsto, $smstext);
									$response = $this->nexmo->send_message($from, $smstoadmin, $smstext);
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
									
									$sms_data = array(
										'src' => '919700376656', //The phone number to use as the caller id (with the country code). E.g. For USA 15671234567
										'dst' => $smstoadmin, // The number to which the message needs to be send (regular phone numbers must be prefixed with country code but without the ‘+’ sign) E.g., For USA 15677654321.
										'text' => $smsmessage, // The text to send
										'type' => 'sms', //The type of message. Should be 'sms' for a text message. Defaults to 'sms'
									);									
									$response = $this->plivo->send_sms($sms_data);
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
									$response = $solution_object->send_sms($smstoadmin, $smsmessage, current_url());				
									if(strpos($response,'Message GID') === false) {
										$smsinfo = $sms_hd_txt.' : ' . $response;
									}
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
							/*SMS Sending End*/

							/* Remove Session of Booking Data */				
							$this->session->unset_userdata('journey_booking_details');
							$this->session->unset_userdata('final_details');
							$this->session->unset_userdata('booking_info');
							$this->session->unset_userdata('journey_details');

							$sms_bd_txt1 = (isset($this->phrases["Thank You. Your order status is"])) ? 
								$this->phrases["Thank You. Your order status is"] : "Thank You. Your order status is";

							$sms_bd_txt2 = (isset($this->phrases["your transaction ID for this transaction is"])) ? 
								$this->phrases["your transaction ID for this transaction is"] : "Your Transaction ID for this transaction is";

							$sms_bd_txt3 = (isset($this->phrases["we have received a payment of"])) ? 
								$this->phrases["we have received a payment of"] : "We have received a payment of";

							$sms_bd_txt4 = (isset($this->phrases["your order will soon be shipped"])) ? 
								$this->phrases["your order will soon be shipped"] : "Your order will soon be shipped";

							$sms_bd_txt5 = (isset($this->phrases["payment Success. but data insertion problem. please contact administrator"])) ? 
								$this->phrases["payment Success. but data insertion problem. please contact administrator"] : "Payment Success. But Data insertion problem. Please contact Administrator";

							$sms_bd_txt6 = (isset($this->phrases["payment failed"])) ? 
								$this->phrases["payment failed"] : "Payment Failed";

							$status_message = "<h3> <i class='fa fa-check'></i> ".$sms_bd_txt1." ". $status .".</h3>";
							if($smsinfo != '') {
								$status_message .= "<h4>$smsinfo</h4>";
							}
							$status_message .= "<h4>".$sms_bd_txt2." <span>".$txnid."</span>.</h4>";
							$status_message .= "<h4>".$sms_bd_txt3."  ".$this->config->item('site_settings')->currency_symbol. "<span>".$amount . "</span>. ".$sms_bd_txt4.".</h4>";
						}
						else
						{
							$status_message = $sms_bd_txt5.".";
						}
					  }
					  else
					  {
						  $els_txt1 = (isset($this->phrases["please"])) ? $this->phrases["please"] : "Please";
						  $els_txt2 = (isset($this->phrases["try"])) ? $this->phrases["try"] : "try";
						  $els_txt3 = (isset($this->phrases["again"])) ? $this->phrases["again"] : "again";

						  $status_message = $sms_bd_txt6.". <br><br>".$els_txt1." <a href='".base_url()."booking/payment'>".$els_txt2."</a> ".$els_txt3.".";
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
	

	/****** Booking Success ******/
	function success()
	{
		$this->checkfor_valid_final();
		$booking_info 	 = $this->session->userdata('final_details');
		$journey_details = $this->session->userdata('final_details');

		//echo "<pre>"; print_r($journey_details); die();

		if($this->input->post() || 
			(isset($journey_details['payment_type']) && 
				$journey_details['payment_type'] == "Cash")
		  ) {

			/* Transaction Details */
			$booking_info['payment_received'] 	= "1";
			$booking_info['transaction_id'] 	= $this->input->post("txn_id");
			$booking_info['payer_id'] 			= $this->input->post("payer_id");
			$booking_info['payer_email'] 		= $this->input->post("payer_email");
			$booking_info['payer_name'] 		= $this->input->post("first_name") . 
													" " . $this->input->post("last_name");			
			if($journey_details['payment_type'] == "paypal")
				$booking_info['booking_status']	= "Confirmed";

			$booking_info['user_id']	= ($this->ion_auth->logged_in()) ?
										  $this->ion_auth->get_user_id() : '0';

			$booking_info['pick_point']	= $booking_info['pick_point_name'];
			$booking_info['drop_point']	= $booking_info['drop_point_name'];
			$booking_info['pick_date']	= date('Y-m-d', strtotime($booking_info['pick_date']));
			$booking_info['return_pick_date']	= date('Y-m-d', strtotime($booking_info['return_pick_date']));
			$booking_info['distance']	= $booking_info['ip_dist_txt'];
			$amount=$booking_info['cost_of_journey'];
			/* Remove the data that is not required for 
			 * inserting into the table from booking_info array */
			unset($booking_info['pick_point_name']);
			unset($booking_info['drop_point_name']);
			unset($booking_info['ip_dist_txt']);
			unset($booking_info['ip_time_txt']);
			unset($booking_info['meet_greet_chkbx']);

			if($booking_info['journey_type'] != "Round-Trip") {

				unset($booking_info['return_pick_date']);
				unset($booking_info['return_pick_time']);

			}

			$booking_info['date_of_booking']  = date('Y-m-d');
			$booking_info['bookdate']		  = time();

			//echo "<pre>"; print_r($booking_info); die();

			if($this->base_model->insert_operation($booking_info, 'bookings')) {

				$bk_ref_txt = (isset($this->phrases["your booking reference"])) ? 
								$this->phrases["your booking reference"] : "Your Booking Reference";

				$sms_hd_txt = (isset($this->phrases["SMS sent failed. Reason"])) ? 
								$this->phrases["SMS sent failed. Reason"] : "SMS Sent Failed. Reason";

				/* Send Booking Success Email to Client */
				$message = $this->load->view('email/booking_success_email', $journey_details, true);

				$from = $this->config->item('site_settings')->portal_email;

				$to = $journey_details['email'];

				$sub = $bk_ref_txt." - ".$journey_details['booking_ref'];

				sendEmail($from, $to, $sub, $message);

				/*SMS Sending Start*/
				$smsinfo = '';
				$smsquery = 'SELECT * FROM '.$this->db->dbprefix('gateways').' g LEFT JOIN '.$this->db->dbprefix('gateways_fields').' gf ON g.gateway_id = gf.gateway_id LEFT JOIN '.$this->db->dbprefix('gateways_fields_values').' gfv ON gf.field_id = gfv.`gateway_field_id` WHERE g.type = "sms" AND is_default=1';
				$smsgateway_details = $this->base_model->fetch_records_from_query_object($smsquery);
				if(count($smsgateway_details) > 0)
				{
					$sms_msg_txt1 = (isset($this->phrases["booking details your booking reference is"])) ? $this->phrases["booking details your booking reference is"] : "Booking Details
								Your Booking Reference is";

					$sms_msg_txt2 = (isset($this->phrases["journey cost"])) ? $this->phrases["journey cost"] : "Journey Cost";

					$smsmessage = $sms_msg_txt1.' '.$journey_details['booking_ref'].'
					'.$sms_msg_txt2.''.$this->config->item('site_settings')->currency_symbol.$amount;
					$smsto = $booking_info['phone_code'] . $booking_info['phone'];
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
					if($smsgateway_details[0]->gateway_title == 'Twilio') 
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
					if($smsgateway_details[0]->gateway_title == 'Nexmo') 
					{
						$this->load->library('nexmo');
						$this->nexmo->set_format('json');
						$from = '1234567890';
						$smstext = array(
							'text' => $smsmessage,
						);
						$response = $this->nexmo->send_message($from, $smsto, $smstext);
						$response = $this->nexmo->send_message($from, $smstoadmin, $smstext);
						$status = $response['messages'][0]['status'];
						if(isset($status) && $status != 0) {
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
					if($smsgateway_details[0]->gateway_title == 'Solutionsinfini') 
					{
						$this->load->helper('solutionsinfini');
						$solution_object = new sendsms();
						$response = $solution_object->send_sms($smsto, $smsmessage, current_url());
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
				}
				/*SMS Sending End*/


				/* Remove Session of Booking Data */
				$this->session->unset_userdata('journey_booking_details');
				$this->session->unset_userdata('final_details');
				$this->session->unset_userdata('booking_info');
				$this->session->unset_userdata('journey_details');

				$this->data['title'] 		= (isset($this->phrases["booking success"])) ? $this->phrases["booking success"] : "Booking Success";
				$this->data['content'] 		= 'site/booking/success';
				$this->_render_page('templates/site_template', $this->data);

			}

		} else {

			$this->prepare_flashmessage((isset($this->phrases["sorry for the inconvenience, subscription process interrupted. please contact Admin"])) ? $this->phrases["sorry for the inconvenience, subscription process interrupted. please contact Admin"] : "Sorry for the inconvenience, 
			Subscription process interrupted. Please contact Admin".".",1);
			redirect('booking');
		}

	}
	
	
	/* Payment Cancel		 */
	function payment_cancel()
	{
		//$this->checkfor_valid_final();
		$this->prepare_flashmessage((isset($this->phrases["payment cancelled"])) ? $this->phrases["payment cancelled"] : "Payment Cancelled".".", 1);
		/* Remove Session of Booking Data */				
		$this->session->unset_userdata('journey_booking_details');
		$this->session->unset_userdata('final_details');
		$this->session->unset_userdata('booking_info');
		$this->session->unset_userdata('journey_details');
		redirect ('booking', 'refresh');
	}



	/***** Cancellation Policy Details ******/
	function cancellationPolicy()
	{
		$cancellation_policy_rec	= $this->base_model->fetch_records_from(
												'cancellation_policy'
												);

		if(count($cancellation_policy_rec) == 0) {

			$this->prepare_flashmessage((isset($this->phrases["no details found. please contact admin for the deatils"])) ? $this->phrases["no details found. please contact admin for the deatils"] : "No Details Found. Please contact Admin for the Deatils".".", 2);
			redirect('client/myBookings');
		}

		$this->data['cancellation_policy_rec']		= $cancellation_policy_rec[0];
		$this->data['title'] 				= (isset($this->phrases["cancellation policy details"])) ? $this->phrases["cancellation policy details"] : "Cancellation Policy Details";
		$this->data['content'] 				= "site/booking/cancellation_policy";
		$this->_render_page('templates/site_template', $this->data);
	}
	
	
	/****** Get Cities According to Country ******/
	function getEndLocations()
	{
		$end_loc_options = "";

		$start_id = $this->input->post('start_id');

		if($start_id > 0) {

			$end_locations = $this->base_model->
							run_query("SELECT tl.travel_location_id, l.location 
										FROM digi_travel_locations tl, digi_locations l 
										WHERE tl.from_loc_id=".$start_id." AND l.id=tl.to_loc_id 
										AND tl.status='Active' AND l.status='Active' 
										ORDER BY l.is_airport='1' DESC");

			if(count($end_locations) > 0) {

				$first_opt = (isset($this->phrases["select drop-off location"])) ? $this->phrases["select drop-off location"] : "Select Drop-off Location";

				$end_loc_options = '<option value="">'.$first_opt.'</option>';

				foreach($end_locations as $rec) {
					$selected = "";
					if($this->session->userdata('journey_booking_details')['drop_point'] == $rec->travel_location_id)
						$selected = "selected";
					$end_loc_options = $end_loc_options . 
										'<option value="'.$rec->travel_location_id.'" '.$selected.'>'.$rec->location.
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
							run_query("SELECT tl.travel_location_id, l.location 
										FROM digi_travel_locations tl, digi_locations l 
										WHERE tl.from_loc_id=".$start_id." AND l.location LIKE '%".$str."%' AND l.id=tl.to_loc_id 
										AND tl.status='Active' AND l.status='Active' 
										ORDER BY l.is_airport='1' DESC");
			}
			else
			{
			$end_locations = $this->base_model->
							run_query("SELECT tl.travel_location_id, l.location 
										FROM digi_travel_locations tl, digi_locations l 
										WHERE tl.from_loc_id=".$start_id." AND l.id=tl.to_loc_id 
										AND tl.status='Active' AND l.status='Active' 
										ORDER BY l.is_airport='1' DESC");
			}
			$end_loc_options = '<ul>';
			if(count($end_locations) > 0) {				
				foreach($end_locations as $rec) {
					$end_loc_options = $end_loc_options . 
										'<li onclick="assign2(\''.$rec->location.'\', '.$rec->travel_location_id.')">'.$rec->location.
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
		
		$pick_point = (isset($journey_booking_details['pick_point'])) ? $journey_booking_details['pick_point'] : 0;
		
		$vehicle_id = (isset($journey_booking_details['vehicle_id'])) ? $journey_booking_details['vehicle_id'] : 0;
		
		$travel_location_id = (isset($journey_booking_details['travel_location_id'])) ? $journey_booking_details['travel_location_id'] : 0;
		
		$pick_date = (isset($journey_booking_details['pick_date'])) ? $journey_booking_details['pick_date'] : 0;
		
		$via_point = $this->input->post('boarding_point['.$vehicle_id.']');
		
		$check = $this->base_model->fetch_records_from('locations', array('parent_id' => $pick_point, 'id' => $via_point));
		if(count($check) > 0)
		{
			$availability = $this->base_model->getFaredetails($travel_location_id, $vehicle_id, $pick_date);
			if(count($availability) == 0)
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
			$this->form_validation->set_message('checkvalidviapoint', getPhrase('Some thing went wrong'));
			return FALSE;
		}
	}
	
	function bookingseat()
	{
		$this->data['vehicles'] = array();
		$this->data['total_vehicles'] = 0;
		$pick_date = date('m/d/Y');
		$drop_point = '';
		$this->data['message'] = $this->session->flashdata('message');
		if(count($this->session->userdata('journey_booking_details')) > 0) {
			$record = $this->session->userdata('journey_booking_details');
			$pick_date = $record['pick_date'];
			$drop_point = $record['drop_point'];
			$results = $this->base_model->get_vehicles_seats(array('pick_date' => $pick_date, 'drop_point' => $drop_point));
			//echo $this->db->last_query();
			$this->data['total_vehicles'] = $this->base_model->numrows;
			$this->data['vehicles'] = $results;
		}
		/* Check For Form Submission */
		if($this->input->post()) {

			$journey_booking_details = $this->session->userdata('journey_booking_details');
			
			$selected_seats_total = $this->input->post('selected_seats_total');
			$selected_seats_total_session = (isset($journey_booking_details['selected_seats_total']))? $journey_booking_details['selected_seats_total'] : 0;
			$vehicle_id = (isset($journey_booking_details['vehicle_id']))? $journey_booking_details['vehicle_id'] : 0;
			
			if($selected_seats_total != $selected_seats_total_session || $selected_seats_total == 0 || $selected_seats_total_session == 0 || $selected_seats_total == '')
			{
				$this->prepare_flashmessage(getPhrase('Something went wrong. Please try again'), 1);
				redirect('booking/bookingseat');
			}
			
			/* Form Validations */
			$this->form_validation->set_rules('boarding_point['.$vehicle_id.']', getPhrase('Please select boarding point'), 'trim|required|callback_checkvalidviapoint');
			
			
			
			$this->form_validation->set_error_delimiters(
			'<div class="error">', '</div>'	);

			if($this->form_validation->run() == TRUE) {				
				//Setting up Session
				$journey_booking_details = array();
				if(count($this->session->userdata(
				'journey_booking_details')) > 0) {
					$journey_booking_details = $this->session->userdata(
					'journey_booking_details');
				}
				$journey_booking_details['boarding_point'] = $this->input->post('boarding_point['.$vehicle_id.']');
				$this->session->set_userdata('journey_booking_details', $journey_booking_details);
				redirect('booking/passenger_details');
			} else {
				$this->data['message'] = $this->prepare_message(validation_errors(),1);
			}

		}
		
		$this->data['total_seats'] = 0;
		$this->data['available_seats'] = array();
		$this->data['via_points'] = array();
		$this->data['dropping_points'] = array();
		$vehicle_ids = array();
		if(count($this->data['vehicles']) > 0)
		{
			$total_seats = 0;
			foreach($this->data['vehicles'] as $v)
			{
				$vehicle_ids[] = $v->id;
				$total_seats += $v->total_seats;
				$booked_seats_shuttle = $this->base_model->booked_seats_count($pick_date, array($v->id));
				$this->data['available_seats'][$v->id] = $v->total_seats - $booked_seats_shuttle[0]->reserved;
				
				$this->data['via_points'][$v->id] = $this->base_model->getViarecords(array('condition' => array('location_visibility_type' => 'via', 'parent_id' => $v->from_loc_id)));
				
				$this->data['dropping_points'][$v->id] = $this->base_model->getViarecords(array('condition' => array('location_visibility_type' => 'via', 'parent_id' => $v->to_loc_id)));
				//echo $this->db->last_query();
			}
			$booked_seats = $this->base_model->booked_seats_count($pick_date, $vehicle_ids);
			$this->data['total_seats'] = $total_seats-$booked_seats[0]->reserved;
		}


		$this->data['css_type']		= array(
												'datepicker', 
												'timepicker', 
												'gmap'
											   );
		$this->data['title'] 		= (isset($this->phrases["cab booking"])) ? 
										$this->phrases["cab booking"] : "Cab Booking";
		$this->data['content'] 		= 'site/booking/booking';
		$this->_render_page('templates/site_template', $this->data);
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
		 $vehicle_id = $this->input->post('vehicle_id');
		 $seat = $this->input->post('seatno');
		 $travel_location_id = $this->input->post('travel_location_id');
		 $pick_date = $this->input->post('pick_date');
		 $selected_state = $this->input->post('selected_state');
		 
		 $fare_details = $this->base_model->getFaredetails($travel_location_id, $vehicle_id,$pick_date,$selected_state);
		 $result = array();
		 if(count($fare_details))
		 {
			 $result['status'] = 1;
			 $result['result'] = $fare_details[0];
			 $result['vehicle_id'] = $vehicle_id;
			 $result['seat'] = $seat;
			 $result['travel_location_id'] = $travel_location_id;
			 $result['pick_date'] = $pick_date;
			 $result['selected_state'] = $selected_state;
			 $journey_booking_details = array();
			if(count($this->session->userdata('journey_booking_details')) > 0) 
			{
				$journey_booking_details = $this->session->userdata('journey_booking_details');
			}
			if(isset($journey_booking_details['vehicle_id']) && $journey_booking_details['vehicle_id'] != $vehicle_id)
			{
				if(isset($journey_booking_details['selection_details']))
				{
					unset($journey_booking_details['selection_details']);
				}
				if(isset($journey_booking_details['seats']))
				{
					unset($journey_booking_details['seats']);
				}
			}
			$journey_booking_details['vehicle_id'] = $vehicle_id;
			
			 if($selected_state == 'available')
			 {				 
				$journey_booking_details['selection_details'][$vehicle_id.'_'.$seat] = $fare_details[0];
				$journey_booking_details['seats'][$vehicle_id.'_'.$seat] = $seat;
			}
			else
			{
				if(isset($journey_booking_details['selection_details']) && isset($journey_booking_details['selection_details'][$vehicle_id.'_'.$seat]))
				{
					unset($journey_booking_details['selection_details'][$vehicle_id.'_'.$seat]);
				}
				if(isset($journey_booking_details['seats']) && isset($journey_booking_details['seats'][$vehicle_id.'_'.$seat]))
				{
					unset($journey_booking_details['seats'][$vehicle_id.'_'.$seat]);
				}
			}
			$this->session->set_userdata('journey_booking_details', $journey_booking_details);
			
			$result['selected_seats'] = '';
			$result['selected_seats_total'] = 0;
			$result['basic_fare'] = 0;
			$result['service_charge'] = 0;
			$result['total_fare'] = 0;
			$journey_booking_details = $this->session->userdata('journey_booking_details');
			$vehicle = $this->base_model->get_vehicle_details($vehicle_id);
			$journey_booking_details['vehicle_details'] = array();
			if(count($vehicle) > 0)
				$journey_booking_details['vehicle_details'] = $vehicle[0];
			
			//print_r($journey_booking_details);
			if(isset($journey_booking_details['selection_details']) && count($journey_booking_details['selection_details']) > 0)
			{
				foreach($journey_booking_details['selection_details'] as $v => $selected)
				{
					$parts = explode('_', $v);
					if($parts[0] == $vehicle_id)
					{
						$result['selected_seats'] .= $parts[1].',';
						$result['selected_seats_total']++;
						if(isset($selected->service_tax_type) && $selected->service_tax_type == 'percent')
						{
							$service_tax = (isset($selected->service_tax)) ? $selected->service_tax : 0;
							$tax = ($selected->cost*$service_tax)/100;
							$result['service_charge'] += $tax;
							$result['basic_fare'] += $selected->cost;
							
							$amount = $selected->cost+$tax;
							$result['total_fare'] += $amount;						
						}
						else
						{
							$tax = (isset($selected->service_tax)) ? $selected->service_tax : 0;
							$result['service_charge'] += $tax;
							$result['basic_fare'] += $selected->cost;
							
							$amount = $selected->cost+$tax;
							$result['total_fare'] += $amount;
						}	
					}
				}
			}
			//Save the details in session so that we may use later
			$journey_booking_details['selected_seats'] = $result['selected_seats'];
			$journey_booking_details['selected_seats_total'] = $result['selected_seats_total'];
			$journey_booking_details['basic_fare'] = $result['basic_fare'];
			$journey_booking_details['service_charge'] = $result['service_charge'];
			$journey_booking_details['total_fare'] = $result['total_fare'];
			$journey_booking_details['travel_location_id'] = $travel_location_id;			
			$this->session->set_userdata('journey_booking_details', $journey_booking_details);
		 }
		 else
		 {
			$result['status'] = 0;
			$result['message'] = getPhrase('Seats aleady booked or No Details found');
		 }
		 echo json_encode($result);
	 }
	 
	 /**
	 * This function takes key of session and return the value
	 *
	 * @param	miexed 
	 * @return	void
	 */
	 function get_data($key)
	 {
		 $journey_booking_details = $this->session->userdata('journey_booking_details');
		 return (isset($journey_booking_details[$key])) ? $journey_booking_details[$key] : 0;
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
		 $journey_booking_details = $this->session->userdata('journey_booking_details');		
		
		$pick_point = $this->get_data('pick_point');		
		$vehicle_id = $this->get_data('vehicle_id');		
		$travel_location_id = $this->get_data('travel_location_id');	
		$pick_date = $this->get_data('pick_date');
		
		$availability = $this->base_model->getFaredetails($travel_location_id, $vehicle_id, $pick_date);
		if(count($availability) == 0)
		{
			$this->prepare_flashmessage(getPhrase('Something went wrong. Seats not available.'));
			redirect('booking/bookingseat'); 
		}
		
		$viapoints = $this->base_model->getViarecords(array('condition' => array('travel_location_id' => $travel_location_id), 'order_by' => 'record_order ASC'));
		$this->data['viapoints'] = $viapoints;
		$this->data['details'] = $journey_booking_details;
		$this->data['title'] 	= getPhrase("Passenger Details");
		$this->data['content'] 	= 'site/booking/passenger_details';
		$this->_render_page('templates/site_template', $this->data);
	 }
	 
}
/* End of file Booking.php */
/* Location: ./application/controllers/Booking.php */
