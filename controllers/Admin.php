<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller

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
	| MODULE: 			Admin
	| -----------------------------------------------------
	| This is Admin module controller file.
	| -----------------------------------------------------
	*/


	function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->helper('url');

		// Load MongoDB library instead of native db driver if required

		$this->config->item('use_mongodb', 'ion_auth') ? 
		$this->load->library('mongo_db') : $this->load->database();
		$this->form_validation->set_error_delimiters(
		$this->config->item('error_start_delimiter', 'ion_auth') , 
		$this->config->item('error_end_delimiter', 'ion_auth')
		);

		$this->load->helper('language');
	}

	public function index()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		/* Date-wise Bookings  */
		$query = "SELECT date_of_booking, COUNT( * ) AS no_of_bookings 
					FROM ".DBPREFIX."bookings 
					GROUP BY date_of_booking";
		$date_wise_bookings = $this->base_model->run_query($query);
		$this->data['date_wise_bookings']		= $date_wise_bookings;
		$this->data['css_type'] 				= array('calendar');
		$this->data['active_menu'] 				= "dashboard";
		$this->data['title'] 					= "Admin Dashboard";
		$this->data['content']					= 'admin/dashboard';
		$this->_render_page('templates/admin_template', $this->data);

	}

	/****** VALIDATE FILE ******/
	function _file_check($file = '', $param2 = '')
	{
		$f_type = explode(".", $param2);
		$last_indx = (count($f_type) - 1);
		if (($f_type[$last_indx] == "jpg") || ($f_type[$last_indx] == "jpeg") || 
		($f_type[$last_indx] == "png")) {

			return true;

		} else {

			$msg = (isset($this->phrases["please upload your photo with the extension jpg|jpeg|png"])) ? $this->phrases["please upload your photo with the extension jpg|jpeg|png"] : "Please upload your Photo with the extension jpg|jpeg|png";

			$this->form_validation->set_message('_file_check', $msg);
			return false;
		}

	}


	/****** ADMIN PROFILE ******/
	public function profile()
	{

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		if ($this->input->post()) {
			$this->check_isdemo(base_url() . 'admin/profile');
			$this->form_validation->set_rules(
			'first_name', 
			(isset($this->phrases["first name"])) ? $this->phrases["first name"] : "First Name", 
			'trim|required'
			);
			$this->form_validation->set_rules(
			'email', 
			(isset($this->phrases["email"])) ? $this->phrases["email"] : "Email", 
			'valid_email|required'
			);
			$this->form_validation->set_rules(
			'phone', 
			(isset($this->phrases["phone"])) ? $this->phrases["phone"] : "Phone", 
			'required|numeric'
			);

			if (!empty($_FILES['userfile']['name'])) {
				$this->form_validation->set_rules('userfile', (isset($this->phrases["photo"])) ? $this->phrases["photo"] : "Photo", 'trim|callback__file_check[' . $_FILES['userfile']['name'] . ']');
			}

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() 	== TRUE) {

				$inputdata['first_name'] 		= $this->input->post('first_name');
				$inputdata['last_name'] 		= $this->input->post('last_name');
				$inputdata['username'] 	= $inputdata['first_name'].' '.$inputdata['last_name'];
				$inputdata['display_name'] 		= $this->input->post('first_name');
				$inputdata['email'] 			= $this->input->post('email');
				$inputdata['phone'] 			= $this->input->post('phone');
				$table_name = "users";
				$where['id'] = $this->input->post('update_rec_id');

				/* Save File(Admin's Photo) */
					if (!empty($_FILES['userfile']['name'])) {

						$file_name = $this->input->post('update_rec_id') . "_" . 
															str_replace(' ', '',$_FILES['userfile']['name']);

						$config['upload_path'] 		= './uploads/admin_profile_pic/';
						$config['allowed_types'] 	= 'jpg|jpeg|png';
						$config['overwrite'] 		= true;
						$config['file_name']        = $file_name;

						$this->load->library('upload', $config);

						/* Unlink Old Photo */
						if ($this->session->userdata('photo') != "" && file_exists('uploads/admin_profile_pic/'.$this->session->userdata('photo'))) {
							unlink('uploads/admin_profile_pic/' . $this->session->userdata('photo'));
						}

						if ($this->upload->do_upload()) {

							$inputdata['photo']		= $file_name;

						}
					}

				if ($this->base_model->update_operation(
				$inputdata, 
				$table_name, 
				$where)) {

					if(isset($inputdata['photo']))
						$this->session->set_userdata('photo', $inputdata['photo']);
					$this->session->set_userdata('username', $inputdata['display_name']);
					$this->prepare_flashmessage(
					(isset($this->phrases["profile has been updated successfully"])) ? 
					$this->phrases["profile has been updated successfully"] : 
					"Profile has been updated successfully".".", 0);
					redirect('admin/profile', 'refresh');
				}
				else {
					$this->prepare_flashmessage(
					(isset($this->phrases["unable to update profile"])) ? 
					$this->phrases["unable to update profile"] : 
					"Unable to update profile"."." , 1);
					redirect('admin/profile');
				}
			}
		}

		$admin_details 							= $this->base_model->fetch_records_from('users', array(
			'id' => $this->session->userdata('user_id')
		));
		if(count($admin_details) > 0) $admin_details = $admin_details[0];

		$this->data['admin_details'] 	= $admin_details;
		$this->data['active_menu'] 		= "admin_profile";
		$this->data['heading'] 			= (isset($this->phrases["admin profile"])) ? $this->phrases["admin profile"] : "Admin Profile";
		$this->data['title'] 			= (isset($this->phrases["admin profile"])) ? $this->phrases["admin profile"] : "Admin Profile";
		$this->data['content'] 			= 'admin/admin_profile';
		$this->_render_page('templates/admin_template', $this->data);
	}


	/****** VIEW BOOKINGS - START ******/
	function viewBookings($param1 = "all", $param2 = '', $param3 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}


		$where['1']	= "1";
		$content = 'admin/bookings/bookings_list';

		/* Delete Booking Details */
		if($param1 == "delete" && $param2 > 0) {
			$this->check_isdemo(base_url() . 'admin/viewBookings');
			if($this->base_model->delete_record('bookings', array('id' => $param2))) {
				$this->base_model->delete_record('bookings_passengers', array('booking_id' => $param2)); //It will delete all passengers related to the deleted booking
				$this->base_model->delete_record('bookings_passengers_infants', array('booking_id' => $param2)); //It will delete all infant passengers related to the deleted booking
				$this->prepare_flashmessage(
				(isset($this->phrases["booking has been deleted successfully"])) ? 
				$this->phrases["booking has been deleted successfully"] : 
				"Booking has been deleted successfully".".", 0);
				redirect('admin/viewBookings');

			}
		}


		/* Update Booking Status and Send an Email to User|Client  */
		if(($param1 == "Confirmed" || $param1 == "Cancelled") && $param2 > 0) {
			
			$updata['booking_status'] = $param1;
			$updata['cancelled_on'] = ($param1 == "Cancelled") ? time() : '';
			$updata['updated_by'] = $this->ion_auth->get_user_id();
			$updata['payment_status_updated'] = date('Y-m-d H:i:s');

			if($this->base_model->update_operation($updata, 'bookings', array('id' => $param2))) {

				$journey_details = array();
				$booking_details = $this->base_model->fetch_records_from('bookings', array('id' => $param2));
				
				if(count($booking_details) >0) {

					foreach($booking_details[0] as $key=>$val)
						$journey_details[$key] = $val;

					
					/* Send Confirmation|Cancellation Email */
					//$message = $this->load->view('email/booking_status_email', $journey_details, true);
					if($param1 == 'Confirmed')
					{
					$template = $this->base_model->fetch_records_from('templates', array('template_key' => 'Booking Confirm', 'template_status' => 'Active'));
					}
					else
					{
					$template = $this->base_model->fetch_records_from('templates', array('template_key' => 'Booking Cancelled', 'template_status' => 'Active'));
					}
					
					$booking_ref = $journey_details['booking_ref'];

					$cost_of_journey = ($journey_details['basic_fare'] + $journey_details['service_charge'] + $journey_details['insurance_amount']) - $journey_details['discount_amount'];
					$amount = $this->config->item('site_settings')->currency_symbol.' '.number_format($cost_of_journey, 2);
					$seats = $journey_details['seat_no'];
					$payment_type = $journey_details['payment_type'];
					$booking_status = $journey_details['booking_status'];
					$route = $journey_details['pick_point'].' to '.$journey_details['drop_point'];
					$shuttle_no = $journey_details['shuttle_no'];
					$payment_code = $journey_details['payment_code'];
					
					$message = $template[0]->template_content;
					$message = str_replace('__BOOKING_REF__', $booking_ref, $message);					
					$message = str_replace('__SHUTTLE_NO__', $shuttle_no, $message);
					$message = str_replace('__COST_OF_JOURNEY__', $amount, $message);
					$message = str_replace('__SEATS__', $seats, $message);
					$passengers_str = $this->get_passengers( $journey_details['id'] );
					//echo $passengers_str;
					//neatPrint($journey_details);
					if($passengers_str != '')
					{
						$message = str_replace('__PASSENGERS__', $passengers_str, $message);
					}
					$message = str_replace('__ROUTE__', $route, $message);
					$message = str_replace('__PAYMENT_TYPE__', $payment_type, $message);
					$message = str_replace('__BOOKING_STATUS__', $booking_status, $message);
					if(strtolower($payment_type) == 'finpayapi')
					{
					$message = str_replace('__PAYMENT_CODE__', $payment_code, $message);
					}					
					$message = $template[0]->template_header.$message.$template[0]->template_footer;
					
					$from = $this->config->item('emailSettings')->from_email;
					$to = $journey_details['email'];
					
					if($template[0]->template_subject != '')
					{
						$sub = $template[0]->template_subject;
					}
					else
					{
					$bk_ref_txt = getPhrase('Your Booking Reference');
					$sub = $bk_ref_txt." - ".$booking_ref;
					}
					sendEmail($from, $to, $sub, $message);
					
					if($param1 == 'Confirmed')
					{
					$this->send_sms($journey_details, 'onward', $payment_code, 'Confirm');
					}
					else
					{
					$this->send_sms($journey_details, 'onward', $payment_code, 'Booking Cancelled');
					}
					

					/*
					$from = $this->config->item('site_settings')->portal_email;
					$to = $journey_details['email'];
					$sub_txt1 = (isset($this->phrases["your booking"])) ? $this->phrases["your booking"] : "Your Booking";
					$sub_txt2 = (isset($this->phrases[$param1])) ? $this->phrases[$param1] : $param1;					
					$sub = $sub_txt1." ".$sub_txt2." - ".$journey_details['booking_ref'];
					sendEmail($from, $to, $sub, $message);					
					//SMS
					$smsto = $journey_details['phone_code'] . $journey_details['phone'];
					$smsmessage = $sub;
					$this->sendSMS($smsto, $smsmessage); //$to, $message, $toadmin = FALSE
					*/
				}


				$msg_txt1 = (isset($this->phrases["booking has been"])) ? $this->phrases["booking has been"] : "Booking has been";

				$msg_txt2 = (isset($this->phrases[$param1])) ? $this->phrases[$param1] : $param1;

				$this->prepare_flashmessage($msg_txt1." ".$msg_txt2.".", 0);
				redirect('admin/viewBookings');

			}
		}



		/* View Today Submitted Bookings */
		if($param1 == "todayz") {

			$where['date_of_booking']	= date('Y-m-d');
		}

		/* View Cancelled Bookings */
		if($param1 == "Cancelled") {

			$where['booking_status']	= "Cancelled";
			$this->data['param']		= "Cancelled";
		}

		/* View Date-wise Bookings */
		if($param1 == "date_wise" && $param2 > 0) {

			$where['date_of_booking']	= $param2;
		}

		$passengers = $passengers_infants = array();
		/* View Particular Booking Details */
		if($param1 == "details" && $param2 > 0) {

			$where['id']	= $param2;
			$content = 'admin/bookings/booking_details';
			$passengers = $this->db->query('SELECT bp.* FROM '.$this->db->dbprefix('bookings').' b INNER JOIN '.$this->db->dbprefix('bookings_passengers').' bp ON b.id = bp.booking_id WHERE b.id = '.$param2)->result();
			
			$passengers_infants = $this->db->query('SELECT bp.* FROM '.$this->db->dbprefix('bookings').' b INNER JOIN '.$this->db->dbprefix('bookings_passengers_infants').' bp ON b.id = bp.booking_id WHERE b.id = '.$param2)->result();
			//echo $this->db->last_query();
		}

		//$query = 'SELECT b.*, v.name vehicle_name, v.model vehicle_model FROM digi_bookings b INNER JOIN digi_vehicle v ON b.vehicle_selected = v.id ORDER BY id DESC';
		//$bookings	= $this->db->query( $query )->result();
		$bookings	= $this->base_model->fetch_records_from('bookings', $where, '', 'id', 'DESC');
		
		//$bookings = $this->db->query('select * from digi_bookings where id = '.$param2)->result();

		$this->data['bookings']			= $bookings;
		$this->data['passengers']		= $passengers;
		$this->data['passengers_infants']		= $passengers_infants;
		$this->data['css_type'] 		= array("datatable");
		$this->data['active_menu'] 		= "view_bookings";
		$this->data['heading'] 			= (isset($this->phrases["view bookings"])) ? $this->phrases["view bookings"] : "View Bookings";
		$this->data['sub_heading'] 		= ucwords((isset($this->phrases[$param1])) ? $this->phrases[$param1] : $param1);
		$this->data['title']	 		= (isset($this->phrases["view bookings"])) ? $this->phrases["view bookings"] : "View Bookings";
		$this->data['content'] 			= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
	/****** VIEW BOOKINGS - END ******/
	
	


	/****** UPDATE BOOKING READ STATUS - START ******/
	function updateReadStatus()
	{
		$this->check_isdemo(base_url() . 'admin/viewBookings');
		$booking_id = $this->input->post('id');

		if($booking_id > 0) {

			if($this->base_model->update_operation(array('read_status' => '1'), 'bookings', array('id' => $booking_id)))
				echo 1;
			else echo 0;

		} else echo 0;

	}
	/****** UPDATE BOOKING READ STATUS - END ******/





	/****** SITE BACKUP ******/
	function siteBackup()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}
		$this->data['records'] 		= array('users', 'locations', 'travel_locations', 'travel_location_costs', 'bookings');
		$this->data['css_type'] 	= array("datatable");
		$this->data['active_menu'] 	= "site_backup";
		$this->data['heading'] 		= (isset($this->phrases["site backup"])) ? $this->phrases["site backup"] : "Site Backup";
		$this->data['title']	 	= (isset($this->phrases["site backup"])) ? $this->phrases["site backup"] : "Site Backup";
		$this->data['content'] 		= "admin/site_backup";
		$this->_render_page('templates/admin_template', $this->data);
	}



	/****** EMPTY THE TABLE DATA ******/
	function emptyTheTableData($param = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		if($param != "" && in_array($param, array('locations', 'travel_locations', 'travel_location_costs', 'bookings'))) {
			$this->check_isdemo(base_url() . 'admin/viewBookings');
			$this->db->truncate(DBPREFIX.$param);
			$this->prepare_flashmessage((isset($this->phrases["table data emptied successfully"])) ? $this->phrases["table data emptied successfully"] : "Table data emptied successfully".".", 0);

		} else {

			$this->prepare_flashmessage((isset($this->phrases["no table found"])) ? $this->phrases["no table found"] : "No Table Found".".", 1);
		}

		redirect('admin/siteBackup');

	}



	//////////////SMS Module Start/////////////////
	function sms_settings()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		$gateways	= $this->base_model->fetch_records_from('gateways', array('type' => 'sms'), '', 'gateway_title', 'DESC');
		$this->data['gateways']			= $gateways;
		$this->data['css_type'] 		= array("datatable");
		$this->data['active_menu'] 		= "sms";
		$this->data['heading'] 			= (isset($this->phrases["view gateways"])) ? $this->phrases["view gateways"] : "View Gateways";
		$this->data['title']	 		= (isset($this->phrases["view gateways"])) ? $this->phrases["view gateways"] : "View Gateways";
		$this->data['content'] 			= 'admin/sms/gateways_list';
		$this->_render_page('templates/admin_template', $this->data);
	}

	function addfieldvalues($gid, $active_menu = 'sms')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}
		if(empty($gid)) {
			$this->prepare_flashmessage((isset($this->phrases["please select gateway"])) ? $this->phrases["please select gateway"] : "Please select gateway", 0);
			redirect('admin/sms_settings', 'refresh');
		}

		$query = 'SELECT * FROM '.$this->db->dbprefix('gateways').' g INNER JOIN '.$this->db->dbprefix('gateways_fields').' gf ON g.`gateway_id`=gf.`gateway_id` LEFT JOIN '.$this->db->dbprefix('gateways_fields_values').' gfv ON gf.`field_id` = gfv.`gateway_field_id` WHERE g.`gateway_id`=' . $gid.' ORDER BY gf.field_order ASC';
		$details = $this->base_model->fetch_records_from_query_object( $query );

		if( $this->input->post( 'submitword' ) ) {
			$this->check_isdemo(base_url() . 'admin/sms_settings');
			$this->load->library('form_validation');			
			$field_values = $this->input->post('field');
			foreach($field_values as $field_id => $val) {
				$check = $this->base_model->fetch_records_from('gateways_fields_values',array('gateway_id' => $this->input->post('gid'), 'gateway_field_id' => $field_id));
				if(count($check) > 0) {
					$inputdata = array(
						'gateway_id' => $this->input->post('gid'),
						'gateway_field_id' => $field_id,
						'gateway_field_value' => $val,
						'updated' => date('Y-m-d H:i:s'),
					);
					$where = array('gateway_id' => $this->input->post('gid'), 'gateway_field_id' => $field_id);
					$insertid = $this->base_model->update_operation( $inputdata, 'gateways_fields_values', $where );
				} else {
					$inputdata = array(
						'gateway_id' => $this->input->post('gid'),
						'gateway_field_id' => $field_id,
						'gateway_field_value' => $val,
						'created' => date('Y-m-d H:i:s'),
					);
					$where = array('gateway_id' => $this->input->post('gid'), 'gateway_field_id' => $field_id);
					$insertid = $this->base_model->insert_operation( $inputdata, 'gateways_fields_values' );
				}
			}
			$this->prepare_flashmessage((isset($this->phrases["record updated successfully"])) ? $this->phrases["record updated successfully"] : "Record updated successfully".".", 0);
			if($details[0]->type == 'payment') {
				redirect('admin/paymentsettings');
			} else {
				redirect('admin/sms_settings');
			}
		}

		$this->data['gid'] = $gid;
		$this->data['fields'] = $details;
		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= $active_menu;
		$this->data['heading'] 					= "Gateway Field Values";
		$this->data['title']	 				= "VGateway Field Values";
		$this->data['content'] 					= 'admin/sms/addfieldvalues';
		$this->_render_page('templates/admin_template', $this->data);	
	}
	
	function makedefaultgateway( $id ) {
		if( !empty($id) ) {			
			$details = $this->base_model->fetch_records_from('gateways', array('gateway_id' => $id));
			
			$inputdata = array(
				'is_default' => 0,
			);
			$this->base_model->update_operation( $inputdata, 'gateways', array('type' => $details[0]->type) );
			
			$inputdata = array(
				'is_default' => 1,
			);
			$this->base_model->update_operation( $inputdata, 'gateways', array('gateway_id' => $id) );				

			$msg = (isset($this->phrases['current gateway is'])) ? $this->phrases['current gateway is'] : "Current Gateway is";
			$this->prepare_flashmessage($msg." <b>".$details[0]->gateway_title."</b>", 0);
			if($details[0]->type == 'payment') {
				redirect('admin/paymentsettings');
			} else {
				redirect('admin/sms_settings');
			}
		}
	}
	
	function showstatistics( $id )
	{
		if(empty($id)) {
			$this->prepare_flashmessage((isset($this->phrases["please select gateway"])) ? $this->phrases["please select gateway"] : "Please select gateway", 0);
			redirect('admin/sms_settings');
		}
		$query = 'SELECT * FROM '.$this->db->dbprefix('gateways').' g INNER JOIN '.$this->db->dbprefix('gateways_fields').' gf ON g.gateway_id = gf.gateway_id INNER JOIN '.$this->db->dbprefix('gateways_fields_values').' gfv ON gf.field_id = gfv.`gateway_field_id` WHERE g.gateway_id = '.$id;
		$this->data['fields'] = $this->base_model->fetch_records_from_query_object($query);
		if(count($this->data['fields']) > 0) {
			$row = $this->data['fields'][0];
			$this->data['gateway'] = $row->gateway_title;
			if($row->gateway_title == 'Cliakatell') {
				$this->load->library('clickatell');
				$this->data['balance'] = $this->clickatell->get_balance();
			}
			if($row->gateway_title == 'Nexmo') {
				$this->load->library('nexmo');
				$bal = $this->nexmo->get_balance();
				$this->data['balance'] = $bal['value'];
			}
			if($row->gateway_title == 'Plivo') {
				$this->load->library('plivo');
				$account = $this->plivo->account();
				if($account[0] == '200') {
					$this->data['otherdetails'] = json_decode( $account[1] );
				} else {
					$this->data['otherdetails'] = '';
				}
				if($this->data['otherdetails'] != '')
				$this->data['balance'] = $this->data['otherdetails']->cash_credits;
				else
				$this->data['balance'] = '-';				
			}
			if($row->gateway_title == 'Twilio') {
				$this->load->helper('ctech-twilio');
				$client = get_twilio_pricing_service();
				$this->data['balance'] = '-';
			}
			if($row->gateway_title == 'Solutionsinfini') {
				$this->load->helper('solutionsinfini');
				$solution_object = new sendsms();	
				$this->data['balance'] = 'Not Available';
			}
		}
		$this->data['gid'] = $id;
		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= "sms";
		$this->data['heading'] 					= (isset($this->phrases["statistics"])) ? $this->phrases["statistics"] : "Statistics";
		$this->data['title']	 				= (isset($this->phrases["statistics"])) ? $this->phrases["statistics"] : "Statistics";
		$this->data['content'] 					= 'admin/sms/showstatistics';
		$this->_render_page('templates/admin_template', $this->data);
	}
	//////////SMS Module End/////////////
	
	///////Payment Gateways Start////////////////
	function paymentsettings()
	{		
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}
		
		$gateways	= $this->base_model->fetch_records_from('gateways', array('type' => 'payment'), '', 'gateway_title', 'DESC');
		$this->data['gateways']					= $gateways;
		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= "paymentgateway";
		$this->data['heading'] 					= (isset($this->phrases["view payment gateways"])) ? $this->phrases["view payment gateways"] : "View Payment Gateways";
		$this->data['title']	 				= (isset($this->phrases["view payment gateways"])) ? $this->phrases["view payment gateways"] : "View Payment Gateways";
		$this->data['content'] 					= 'admin/paymentgateways/gateways_list';
		$this->_render_page('templates/admin_template', $this->data);
	}
	
	function deactivategateway( $id ) {
		$this->check_isdemo(base_url() . 'admin/sms_settings');
		if( !empty($id) ) {			
			$details = $this->base_model->fetch_records_from('gateways', array('gateway_id' => $id));		
			$inputdata = array(
				'gateway_status' => 'inactive',
			);
			$this->base_model->update_operation( $inputdata, 'gateways', array('gateway_id' => $id) );				
			
			$this->prepare_flashmessage("<b>".$details[0]->gateway_title."</b> ".(isset($this->phrases['deactivated'])) ? $this->phrases['deactivated'] : "Deactivated", 0);
			if($details[0]->type == 'payment') {
				redirect('admin/paymentsettings');
			} else {
				redirect('admin/sms_settings');
			}
		}
	}

	function activategateway( $id ) {
		$this->check_isdemo(base_url() . 'admin/sms_settings');
		if( !empty($id) ) {			
			$details = $this->base_model->fetch_records_from('gateways', array('gateway_id' => $id));		
			$inputdata = array(
				'gateway_status' => 'active',
			);
			$this->base_model->update_operation( $inputdata, 'gateways', array('gateway_id' => $id) );				
			
			$this->prepare_flashmessage("<b>".$details[0]->gateway_title."</b> ".(isset($this->phrases['activated'])) ? $this->phrases['activated'] : "Activated", 0);
			if($details[0]->type == 'payment') {
				redirect('admin/paymentsettings');
			} else {
				redirect('admin/sms_settings');
			}
		}
	}
	
	///////Payment Gateways End////////////////
	

	/****** OFFERS MODULE - START ******/
	function offers($param1 = "list", $param2 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}


		$records    = array();
		$where['1']	= 1;
		$title		= (!empty($this->phrases["offers"])) ? 
		$this->phrases["offers"] : "Offers";
		$content = 'admin/offers/offers_list';

		/* Delete Record */
		if($param1 == "delete" && $param2 > 0) {
		$this->check_isdemo(base_url() . 'admin/offers');

			if($this->base_model->delete_record('offers', array('offer_id' => $param2))) {

				$this->base_model->delete_record('offer_users', array('offer_id' => $param2));

				$this->prepare_flashmessage((isset($this->phrases["record deleted successfully"])) ? $this->phrases["record deleted successfully"] : "Record Deleted Successfully.", 0);
				redirect('admin/offers');

			}
		}


		if($param1 == "create" || ($param1 == "edit" && $param2 > 0)) {

			$op_txt1  = (isset($this->phrases[$param1])) ? 
							$this->phrases[$param1] : ucwords($param1);
			$op_txt2  = (isset($this->phrases["offer"])) ? 
							$this->phrases["offer"] : "Offer";
			$title	  = $op_txt1." ".$op_txt2;
			$content  = 'admin/offers/addedit_offer';

			if($param1 == "edit") {

				$this->data['update_rec_id'] = $param2;
				$where['offer_id']			 = $param2;
			}
		}


		/* Check for Form Submission */
		if($this->input->post()) {
			$this->check_isdemo(base_url() . 'admin/offers');
			// FORM VALIDATIONS
			$this->form_validation->set_rules(
			'title', 
			(!empty($this->phrases["title"])) ? 
			$this->phrases["title"] : "Title" , 
			'required');

			$this->form_validation->set_rules(
			'description', 
			(!empty($this->phrases["description"])) ? 
			$this->phrases["description"] : "Description" , 
			'required');

			$this->form_validation->set_rules(
			'offer_type_val', 
			(!empty($this->phrases["offer type value"])) ? 
			$this->phrases["offer type value"] : "Offer Type Value" , 
			'required|integer');

			$this->form_validation->set_rules(
			'code', 
			(!empty($this->phrases["code"])) ? 
			$this->phrases["code"] : "Code" , 
			'required');

			$this->form_validation->set_rules(
			'min_journey_cost', 
			(!empty($this->phrases["min journey cost"])) ? 
			$this->phrases["min journey  cost"] : "Min Journey Cost" , 
			'required|numeric');

			if($this->input->post('usage_type_val')) {
				$this->form_validation->set_rules(
				'usage_type_val', 
				(!empty($this->phrases["usage type value"])) ? 
				$this->phrases["usage type value"] : "Usage Type Value" , 
				'required|integer');
			}
			$this->form_validation->set_rules(
			'discount_appliedon', 
			(!empty($this->phrases["Applied on"])) ? 
			$this->phrases["Applied on"] : "Applied on" , 
			'required');

			$this->form_validation->set_rules(
			'expiry_date', 
			(!empty($this->phrases["expiry date"])) ? 
			$this->phrases["expiry date"] : "Expiry Date" , 
			'required');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');


			if ($this->form_validation->run() 	== TRUE) {

				$inputdata['title'] 		 = $this->input->post('title');
				$inputdata['description'] 	 = $this->input->post('description');
				$inputdata['offer_type'] 	 = $this->input->post('offer_type');
				$inputdata['offer_type_val'] = $this->input->post('offer_type_val');
				$inputdata['code'] 			 = $this->input->post('code');
				$inputdata['min_journey_cost'] 	= $this->input->post('min_journey_cost');
				$inputdata['usage_type'] 	 = $this->input->post('usage_type');
				$inputdata['discount_appliedon'] 	 = $this->input->post('discount_appliedon');
				$inputdata['usage_type_val'] = $this->input->post('usage_type_val');
				$inputdata['expiry_date'] 	 = date('Y-m-d', strtotime($this->input->post('expiry_date')));
				$inputdata['status'] 		 = $this->input->post('status');

				$table_name = "offers";
//echo "<pre>";print_r($inputdata);die();
				if($this->input->post('update_rec_id') > 0) {

					/* Update Record */
					$inputdata['date_updated'] 	= date('Y-m-d H:i:s');
					$where['offer_id'] = $this->input->post('update_rec_id');
					if ($this->base_model->update_operation(
					$inputdata, 
					$table_name, 
					$where)) {

						$this->prepare_flashmessage(
						(!empty($this->phrases["record updated successfully"])) ? 
						$this->phrases["record updated successfully"] : "Record updated successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(!empty($this->phrases["unable to update the record"])) ? 
						$this->phrases["unable to update the record"] : "Unable to update the Record." , 1);
					}

				} else {

					/* Insert Record */
					$inputdata['date_created'] 	= date('Y-m-d H:i:s');
					if ($this->base_model->insert_operation(
					$inputdata, 
					$table_name)) {

						$this->prepare_flashmessage(
						(!empty($this->phrases["record inserted successfully"])) ? 
						$this->phrases["record inserted successfully"] : "Record inserted successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(!empty($this->phrases["unable to insert record"])) ? 
						$this->phrases["unable to insert record"] : "Unable to insert Record." , 1);
					}

				}

				redirect('admin/offers');

			}

		}


		if(!in_array($param1, array('create'))) /* For Listing and Editing Record(s) */
			$records	= $this->base_model->fetch_records_from('offers', $where, '', 'offer_id', 'DESC');

		$this->data['records']					= $records;
		$this->data['css_type'] 				= array("datatable", 'calendar');
		$this->data['active_menu'] 				= "offers";
		$this->data['heading'] 					= (!empty($this->phrases["offers"])) ? $this->phrases["offers"] : "Offers";
		$this->data['sub_heading'] 				= (!empty($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
		$this->data['param'] 					= $param1;
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
	/****** OFFERS MODULE - END ******/
	
	function duplicatecheck()
	{
		$check = $this->base_model->fetch_records_from('templates',array('template_key' => $this->input->post('template_key')));
		
		if (count($check) == 0 && $this->input->post('update_rec_id') == '') {
		  return true;
		} elseif((count($check) >= 1 || count($check) == 0)&& $this->input->post('update_rec_id') != '') {
			return true;
		}else {
		  $this->form_validation->set_message('duplicatecheck', getPhrase('duplicate key'));
		  return false;
		}
	}
	
	/****** Templates MODULE - START ******/
	function templates($param1 = "list", $param2 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}


		$records    = array();
		$where['1']	= 1;
		$title		= (!empty($this->phrases["templates"])) ? 
		$this->phrases["templates"] : "Templates";
		$content = 'admin/templates/templates_list';

		/* Delete Record */
		if($param1 == "delete" && $param2 > 0) {
		$this->check_isdemo(base_url() . 'admin/templates');

			if($this->base_model->delete_record('templates', array('template_id' => $param2))) {
				$this->prepare_flashmessage((isset($this->phrases["record deleted successfully"])) ? $this->phrases["record deleted successfully"] : "Record Deleted Successfully.", 0);
				redirect('admin/templates');

			}
		}


		if($param1 == "create" || ($param1 == "edit" && $param2 > 0)) {

			$op_txt1  = (isset($this->phrases[$param1])) ? 
							$this->phrases[$param1] : ucwords($param1);
			$op_txt2  = (isset($this->phrases["template"])) ? 
							$this->phrases["template"] : "Template";
			$title	  = $op_txt1." ".$op_txt2;
			$content  = 'admin/templates/addedit_template';

			if($param1 == "edit") {

				$this->data['update_rec_id'] = $param2;
				$where['template_id']			 = $param2;
			}
		}


		/* Check for Form Submission */
		if($this->input->post()) {
			$this->check_isdemo(base_url() . 'admin/templates');
			// FORM VALIDATIONS
			$this->form_validation->set_rules('template_key',(!empty($this->phrases["title"])) ? $this->phrases["title"] : "Title",'trim|required|callback_duplicatecheck');

			//$this->form_validation->set_rules('template_header',(!empty($this->phrases["header"])) ? $this->phrases["header"] : "Header",'trim|required');
			$this->form_validation->set_rules('template_content', (!empty($this->phrases["description"])) ? $this->phrases["description"] : "Description",'trim|required');
			//$this->form_validation->set_rules('template_footer',(!empty($this->phrases["footer"])) ? $this->phrases["footer"] : "Footer",'trim|required');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');


			if ($this->form_validation->run() 	== TRUE) {

				$inputdata['template_key'] 		= $this->input->post('template_key');
				$inputdata['template_subject'] 		= $this->input->post('template_subject');
				$inputdata['template_header'] 	= $this->input->post('template_header');
				$inputdata['template_content'] 	= $this->input->post('template_content');
				$inputdata['template_footer']   = $this->input->post('template_footer');
				$inputdata['template_status'] 		 = $this->input->post('template_status');

				$table_name = "templates";
//echo "<pre>";print_r($inputdata);die();
				if($this->input->post('update_rec_id') > 0) {

					/* Update Record */
					$inputdata['template_updated'] 	= date('Y-m-d H:i:s');
					$where['template_id'] = $this->input->post('update_rec_id');
					if ($this->base_model->update_operation(
					$inputdata, 
					$table_name, 
					$where)) {

						$this->prepare_flashmessage(
						(!empty($this->phrases["record updated successfully"])) ? 
						$this->phrases["record updated successfully"] : "Record updated successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(!empty($this->phrases["unable to update the record"])) ? 
						$this->phrases["unable to update the record"] : "Unable to update the Record." , 1);
					}

				} else {

					/* Insert Record */
					$inputdata['template_created'] 	= date('Y-m-d H:i:s');
					if ($this->base_model->insert_operation(
					$inputdata, 
					$table_name)) {

						$this->prepare_flashmessage(
						(!empty($this->phrases["record inserted successfully"])) ? 
						$this->phrases["record inserted successfully"] : "Record inserted successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(!empty($this->phrases["unable to insert record"])) ? 
						$this->phrases["unable to insert record"] : "Unable to insert Record." , 1);
					}

				}

				redirect('admin/templates');

			}

		}


		if(!in_array($param1, array('create'))) /* For Listing and Editing Record(s) */
			$records	= $this->base_model->fetch_records_from('templates', $where, '', 'template_id', 'DESC');

		$this->data['records']					= $records;
		$this->data['css_type'] 				= array("datatable", 'editor');
		$this->data['active_menu'] 				= "templates";
		$this->data['heading'] 					= (!empty($this->phrases["templates"])) ? $this->phrases["templates"] : "Templates";
		$this->data['sub_heading'] 				= (!empty($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
		$this->data['param'] 					= $param1;
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
	/****** Templates MODULE - END ******/
	
	/****** SMS Templates MODULE - START ******/
	
	function smsduplicatecheck()
	{
		$check = $this->base_model->fetch_records_from('templates_sms',array('template_key' => $this->input->post('template_key')));
		
		if (count($check) == 0 && $this->input->post('update_rec_id') == '') {
		  return true;
		} elseif((count($check) >= 1 || count($check) == 0)&& $this->input->post('update_rec_id') != '') {
			return true;
		}else {
		  $this->form_validation->set_message('smsduplicatecheck', getPhrase('duplicate key'));
		  return false;
		}
	}
	
	function smstemplates($param1 = "list", $param2 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}


		$records    = array();
		$where['1']	= 1;
		$title		= (!empty($this->phrases["templates"])) ? 
		$this->phrases["templates"] : "Templates";
		$content = 'admin/smstemplates/templates_list';

		/* Delete Record */
		if($param1 == "delete" && $param2 > 0) {
		$this->check_isdemo(base_url() . 'admin/smstemplates');

			if($this->base_model->delete_record('templates_sms', array('template_id' => $param2))) {
				$this->prepare_flashmessage((isset($this->phrases["record deleted successfully"])) ? $this->phrases["record deleted successfully"] : "Record Deleted Successfully.", 0);
				redirect('admin/smstemplates');

			}
		}


		if($param1 == "create" || ($param1 == "edit" && $param2 > 0)) {

			$op_txt1  = (isset($this->phrases[$param1])) ? 
							$this->phrases[$param1] : ucwords($param1);
			$op_txt2  = (isset($this->phrases["template"])) ? 
							$this->phrases["template"] : "Template";
			$title	  = $op_txt1." ".$op_txt2;
			$content  = 'admin/smstemplates/addedit_template';

			if($param1 == "edit") {

				$this->data['update_rec_id'] = $param2;
				$where['template_id']			 = $param2;
			}
		}


		/* Check for Form Submission */
		if($this->input->post()) {
			$this->check_isdemo(base_url() . 'admin/smstemplates');
			// FORM VALIDATIONS
			$this->form_validation->set_rules('template_key',(!empty($this->phrases["title"])) ? $this->phrases["title"] : "Title",'trim|required|callback_smsduplicatecheck');
			$this->form_validation->set_rules('template_content', (!empty($this->phrases["description"])) ? $this->phrases["description"] : "Description",'trim|required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() 	== TRUE) 
			{

				$inputdata['template_key'] 		= $this->input->post('template_key');
				$inputdata['template_content'] 	= $this->input->post('template_content');
				$inputdata['template_status'] 		 = $this->input->post('template_status');
				$table_name = "templates_sms";
//echo "<pre>";print_r($inputdata);die();
				if($this->input->post('update_rec_id') > 0) {

					/* Update Record */
					$inputdata['template_updated'] 	= date('Y-m-d H:i:s');
					$where['template_id'] = $this->input->post('update_rec_id');
					if ($this->base_model->update_operation(
					$inputdata, 
					$table_name, 
					$where)) {

						$this->prepare_flashmessage(
						(!empty($this->phrases["record updated successfully"])) ? 
						$this->phrases["record updated successfully"] : "Record updated successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(!empty($this->phrases["unable to update the record"])) ? 
						$this->phrases["unable to update the record"] : "Unable to update the Record." , 1);
					}

				} else {

					/* Insert Record */
					$inputdata['template_created'] 	= date('Y-m-d H:i:s');
					if ($this->base_model->insert_operation(
					$inputdata, 
					$table_name)) {

						$this->prepare_flashmessage(
						(!empty($this->phrases["record inserted successfully"])) ? 
						$this->phrases["record inserted successfully"] : "Record inserted successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(!empty($this->phrases["unable to insert record"])) ? 
						$this->phrases["unable to insert record"] : "Unable to insert Record." , 1);
					}

				}
				redirect('admin/smstemplates');
			}

		}


		if(!in_array($param1, array('create'))) /* For Listing and Editing Record(s) */
			$records	= $this->base_model->fetch_records_from('templates_sms', $where, '', 'template_id', 'DESC');

		$this->data['records']					= $records;
		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= "smstemplates";
		$this->data['heading'] 					= (!empty($this->phrases["templates"])) ? $this->phrases["templates"] : "Templates";
		$this->data['sub_heading'] 				= (!empty($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
		$this->data['param'] 					= $param1;
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
	/****** SMS Templates MODULE - END ******/
	
	/****** Admin change password ******/
	public function change_password()
	{

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}
		$this->data['message'] = $this->session->flashdata('message');
		if ($this->input->post()) {
			$this->check_isdemo(base_url() . 'admin/change_password');
			$this->form_validation->set_rules('old_password',getPhrase('old password'),'required');
			$this->form_validation->set_rules('new_password',getPhrase('new password') , 
			'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . 
			$this->config->item('max_password_length', 'ion_auth') . ']|matches[new_password_confirm]');
			$this->form_validation->set_rules(
			'new_password_confirm', getPhrase('confirm new password'),'required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() 	== TRUE)
			{
				$identity 	= $this->session->userdata('identity');
				$change 	= $this->ion_auth->change_password($identity, 
				$this->input->post('old_password') , $this->input->post('new_password'));
				if ($change) {
					//redirect them to the login page
					$this->prepare_flashmessage($this->ion_auth->messages(),0);
					redirect('admin/change_password');
				}
				else
				{
					$this->prepare_flashmessage((isset($this->phrases["unable to change password"])) ? $this->phrases["unable to change password"] : "Unable to Change Password".".",1);
					redirect('admin/change_password');
				}
			}
			else
			{
				$this->prepare_message(validation_errors(),1);
				//redirect('executive/change_password');
			}
		}

		$admin_details 							= $this->base_model->fetch_records_from('users', array(
			'id' => $this->session->userdata('user_id')
		));
		if(count($admin_details) > 0) $admin_details = $admin_details[0];

		$this->data['old_password'] = array(
				'name' => 'old_password',
				'placeholder' => (isset($this->phrases["Old password"])) ? $this->phrases["Old password"].' *' : "Old Password".' *' ,
				'id' => 'old_password',
				'type' => 'password',
				'value' => $this->form_validation->set_value('old_password') ,
				'maxlength' => $this->config->item('max_password_length', 'ion_auth'),
			);
		$this->data['new_password'] = array(
				'name' => 'new_password',
				'placeholder' => (isset($this->phrases["new password"])) ? $this->phrases["new password"].' *' : "New Password".' *' ,
				'id' => 'new_password',
				'type' => 'password',
				'value' => $this->form_validation->set_value('new_password') ,
				'maxlength' => $this->config->item('max_password_length', 'ion_auth'),
			);
		$this->data['new_password_confirm'] = array(
				'name' => 'new_password_confirm',
				'placeholder' => (isset($this->phrases["confirm password"])) ? $this->phrases["confirm password"].' *' : "Confirm Password".' *' ,
				'id' => 'new_password_confirm',
				'type' => 'password',
				'value' => $this->form_validation->set_value('new_password_confirm') ,
				'maxlength' => $this->config->item('max_password_length', 'ion_auth'),
			);
			
		$this->data['user_id'] = array(
			'type' => 'hidden',
			'name' => 'user_id',
			'id' => 'user_id',
			'value' => $this->ion_auth->get_user_id(),
		);
		$this->data['admin_details'] 	= $admin_details;
		$this->data['active_menu'] 		= "change_password";
		$this->data['heading'] 			= (isset($this->phrases["admin profile"])) ? $this->phrases["admin profile"] : "Admin Change Password";
		$this->data['title'] 			= (isset($this->phrases["admin profile"])) ? $this->phrases["admin profile"] : "Admin Change Password";
		$this->data['content'] 			= 'admin/change_password';
		$this->_render_page('templates/admin_template', $this->data);
	}
	
	function testimonials($param1 = "list", $param2 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}
		$this->data['message'] = $this->session->flashdata('message');

		$records     		= array();
		$extra_conds 		= "";
		$vehicle_cat_opts	= array();
		$title		 		= (isset($this->phrases["testimonials"])) ? 
		$this->phrases["testimonials"] : "Testimonials";
		$content 			= 'admin/testimonials/testimonial_list';
		$this->data['vehicle_cat_opts']	= "";

		/* Delete Record */
		if($param1 == "delete" && $param2 > 0) {
$this->check_isdemo(base_url() . 'admin/testimonials');
			$vehicle_image_rec = $this->base_model->fetch_records_from('testimonials', array('id' => $param2));

			if($this->base_model->delete_record('testimonials', array('id' => $param2))) {
				/* Unlink Vehicle Image */
				if(count($vehicle_image_rec) > 0)
				if (isset($vehicle_image_rec[0]->image) && $vehicle_image_rec[0]->image != "" && file_exists('uploads/testimonials/' . $vehicle_image_rec[0]->image)) 
					unlink('uploads/testimonials/' . $vehicle_image_rec[0]->image);

				$this->prepare_flashmessage((isset($this->phrases["record deleted successfully"])) ? $this->phrases["record deleted successfully"] : "Record Deleted Successfully.", 0);
				redirect('admin/testimonials');

			}
		}


		if($param1 == "add" || ($param1 == "edit" && $param2 > 0)) {

			$op_txt1  = (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
			$op_txt2  = (isset($this->phrases["testimonial"])) ? $this->phrases["testimonial"] : "Testimonial";
			$title	 = $op_txt1." ".$op_txt2;
			$content = 'admin/testimonials/add_testimonial';

			
			if($param1 == "edit") {

				$this->data['update_rec_id'] = $param2;
				$extra_conds 				 = " AND id=".$param2;

			}
		}


		/* Check for Form Submission */
		if($this->input->post()) {
			$this->check_isdemo(base_url() . 'admin/testimonials');
			// FORM VALIDATIONS			
			$this->form_validation->set_rules(
			'name', 
			(isset($this->phrases["name"])) ? 
			$this->phrases["name"] : "Name", 
			'trim|required');
			$this->form_validation->set_rules(
			'designation', 
			(isset($this->phrases["designation"])) ? 
			$this->phrases["designation"] : "Designation", 
			'trim|required');
			$this->form_validation->set_rules(
			'comments', 
			(isset($this->phrases["comments"])) ? 
			$this->phrases["comments"] : "Comments", 
			'trim|required');
			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() 	== TRUE) {
				$image_up_success = 0;				
				$inputdata['name'] 					= $this->input->post('name');
				$inputdata['designation'] 			= $this->input->post('designation');
				$inputdata['comments'] 				= $this->input->post('comments');
				$inputdata['status'] 					= $this->input->post('status');

				$table_name = "testimonials";
//neatPrint($this->input->post());
				if($this->input->post('update_rec_id') > 0) {

					$id = $this->input->post('update_rec_id');
					/* Update Record */
					$where['id'] 			= $id;
					$where2['id'] 	= $id;
					if ($this->base_model->update_operation(
					$inputdata, 
					$table_name, 
					$where)) {

						$image_up_success = 1;						
						/* Update Vehicle Image */
						if($this->input->post('is_image_set') == "yes") {

							/* Unlink Old Image */
							if ($this->input->post('current_img') != "" && file_exists('uploads/testimonials/' . $this->input->post('current_img'))) unlink('uploads/testimonials/' . $this->input->post('current_img'));

							$config['upload_path'] 			= './uploads/testimonials/';
							$config['allowed_types'] 		= 'jpeg|jpg|png';
							$config['max_size'] 			= '10240';
							$config['overwrite'] 			= true;
							$config['remove_spaces'] 		= true;
							$filename 						= $this->input->post('update_rec_id') . "_" .
															  $_FILES['userfile']['name'];
							$config['file_name'] 			= $filename;


							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if($this->upload->do_upload()) {

								if($s == 0) {

									$fileinput['image'] 		= $filename;

									$this->base_model->update_operation(
																$fileinput, 
																$table_name, 
																array('id' => $this->input->post('update_rec_id'))
																);
								}

								$image_up_success = 1;

							} else {

								$image_up_success = 0;
								$error = array(
												'error' => $this->upload->display_errors()
											);
								$inf_txt = (isset($this->phrases["record updated successfully. but"])) ? $this->phrases["record updated successfully. but"] : "Record updated successfully. But";
								$this->prepare_flashmessage($inf_txt." ".$error['error'], 2);
							}
						}

						if($image_up_success != 0) {

							$this->prepare_flashmessage(
							(isset($this->phrases["record updated successfully"])) ? 
							$this->phrases["record updated successfully"] : "Record updated successfully.", 0);

						}

					} else {

						$this->prepare_flashmessage(
						(isset($this->phrases["unable to update the record"])) ? 
						$this->phrases["unable to update the record"] : "Unable to update the Record." , 1);
					}

				} else {

					/* Insert Record */
					$insertId = $this->base_model->insert_operation_id(
														$inputdata, 
														$table_name);
					if ($insertId > 0) {

						$image_up_success = 1;
						
						/* Save Vehicle Image */
						if($this->input->post('is_image_set') == "yes") {

							$config['upload_path'] 			= './uploads/testimonials/';
							$config['allowed_types'] 		= 'jpeg|jpg|png';
							$config['max_size'] 			= '10240';
							$config['overwrite'] 			= true;
							$config['remove_spaces'] 		= true;
							$filename 						= $insertId . "_" .
															  $_FILES['userfile']['name'];
							$config['file_name'] 			= $filename;


							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if($this->upload->do_upload()) {

								if($s == 0) {

									$fileinput['image'] 		= $filename;

									$this->base_model->update_operation(
																$fileinput, 
																$table_name, 
																array('id' => $insertId)
																);
								}

								$image_up_success = 1;

							} else {

								$image_up_success = 0;
								$error = array(
												'error' => $this->upload->display_errors()
											);
								$inf_txt = (isset($this->phrases["record inserted successfully. but"])) ? $this->phrases["record inserted successfully. but"] : "Record inserted successfully. But";
								$this->prepare_flashmessage($inf_txt." ".$error['error'], 2);
							}

						}

						if($image_up_success != 0) {

							$this->prepare_flashmessage(
							(isset($this->phrases["record inserted successfully"])) ? 
							$this->phrases["record inserted successfully"] : "Record inserted successfully.", 0);

						}

					} else {

						$this->prepare_flashmessage(
						(isset($this->phrases["unable to insert record"])) ? 
						$this->phrases["unable to insert record"] : "Unable to insert Record." , 1);
					}

				}

				redirect('admin/testimonials');

			}

		}


		if(!in_array($param1, array('add')))  /* For Listing and Editing Record(s) */
		{
			$records	= $this->base_model->run_query("SELECT * FROM ".DBPREFIX."testimonials ORDER by id DESC");
			if($param1 == 'edit' && $param2 != '')
			{
				$records	= $this->base_model->run_query("SELECT * FROM ".DBPREFIX."testimonials WHERE id = $param2 ORDER by id DESC");
			}
			if($param1 == 'edit' && empty($records))
			{
				$this->prepare_flashmessage(
						(isset($this->phrases["wrong operation"])) ? 
						$this->phrases["wrong operation"] : "Wrong operation" , 1);
				redirect('admin/testimonials');
			}
		}

		$this->data['records']			= $records;
		$this->data['css_type'] 		= array("datatable");
		$this->data['active_menu'] 		= "testimonials";
		$heading = (isset($this->phrases["testimonial"])) ? $this->phrases["testimonial"] : "Testimonial";
		$this->data['heading'] 			= '<a href="'.base_url().'admin/testimonials">'.$heading.'</a>';
		$this->data['sub_heading'] 				= (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
		$this->data['param'] 					= $param1;
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
	
	function banks($param1 = "list", $param2 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}
		$this->data['message'] = $this->session->flashdata('message');

		$records     		= array();
		$extra_conds 		= "";
		$vehicle_cat_opts	= array();
		$title		 		= (isset($this->phrases["testimonials"])) ? 
		$this->phrases["testimonials"] : "Testimonials";
		$content 			= 'admin/banks/banks_list';
		$this->data['vehicle_cat_opts']	= "";

		/* Delete Record */
		if($param1 == "delete" && $param2 > 0) {
$this->check_isdemo(base_url() . 'admin/banks');
			$vehicle_image_rec = $this->base_model->fetch_records_from('banks', array('id' => $param2));

			if($this->base_model->delete_record('banks', array('id' => $param2))) {
				/* Unlink Vehicle Image */
				if(count($vehicle_image_rec) > 0)
				if (isset($vehicle_image_rec[0]->image) && $vehicle_image_rec[0]->image != "" && file_exists('uploads/banks/' . $vehicle_image_rec[0]->image)) 
					unlink('uploads/banks/' . $vehicle_image_rec[0]->image);

				$this->prepare_flashmessage((isset($this->phrases["record deleted successfully"])) ? $this->phrases["record deleted successfully"] : "Record Deleted Successfully.", 0);
				redirect('admin/banks');

			}
		}


		if($param1 == "add" || ($param1 == "edit" && $param2 > 0)) {

			$op_txt1  = (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
			$op_txt2  = (isset($this->phrases["testimonial"])) ? $this->phrases["testimonial"] : "Testimonial";
			$title	 = $op_txt1." ".$op_txt2;
			$content = 'admin/banks/add_bank';

			
			if($param1 == "edit") {

				$this->data['update_rec_id'] = $param2;
				$extra_conds 				 = " AND id=".$param2;

			}
		}


		/* Check for Form Submission */
		if($this->input->post()) {
			$this->check_isdemo(base_url() . 'admin/banks');
			// FORM VALIDATIONS			
			$this->form_validation->set_rules(
			'name', 
			(isset($this->phrases["name"])) ? 
			$this->phrases["name"] : "Name", 
			'trim|required');
			
			$this->form_validation->set_rules(
			'comments', 
			(isset($this->phrases["comments"])) ? 
			$this->phrases["comments"] : "Comments", 
			'trim|required');
			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() 	== TRUE) {
				$image_up_success = 0;				
				$inputdata['name'] 					= $this->input->post('name');
				$inputdata['comments'] 				= $this->input->post('comments');
				$inputdata['status'] 					= $this->input->post('status');

				$table_name = "banks";
//neatPrint($this->input->post());
				if($this->input->post('update_rec_id') > 0) {

					$id = $this->input->post('update_rec_id');
					/* Update Record */
					$where['id'] 			= $id;
					$where2['id'] 	= $id;
					if ($this->base_model->update_operation(
					$inputdata, 
					$table_name, 
					$where)) {

						$image_up_success = 1;						
						/* Update Vehicle Image */
						if($this->input->post('is_image_set') == "yes") {

							/* Unlink Old Image */
							if ($this->input->post('current_img') != "" && file_exists('uploads/banks/' . $this->input->post('current_img'))) unlink('uploads/banks/' . $this->input->post('current_img'));

							$config['upload_path'] 			= './uploads/banks/';
							$config['allowed_types'] 		= 'jpeg|jpg|png';
							$config['max_size'] 			= '10240';
							$config['overwrite'] 			= true;
							$config['remove_spaces'] 		= true;
							$filename 						= $this->input->post('update_rec_id') . "_" .
															  $_FILES['userfile']['name'];
							$config['file_name'] 			= $filename;


							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if($this->upload->do_upload()) {

								if($s == 0) {

									$fileinput['image'] 		= $filename;

									$this->base_model->update_operation(
																$fileinput, 
																$table_name, 
																array('id' => $this->input->post('update_rec_id'))
																);
								}

								$image_up_success = 1;

							} else {

								$image_up_success = 0;
								$error = array(
												'error' => $this->upload->display_errors()
											);
								$inf_txt = (isset($this->phrases["record updated successfully. but"])) ? $this->phrases["record updated successfully. but"] : "Record updated successfully. But";
								$this->prepare_flashmessage($inf_txt." ".$error['error'], 2);
							}
						}

						if($image_up_success != 0) {

							$this->prepare_flashmessage(
							(isset($this->phrases["record updated successfully"])) ? 
							$this->phrases["record updated successfully"] : "Record updated successfully.", 0);

						}

					} else {

						$this->prepare_flashmessage(
						(isset($this->phrases["unable to update the record"])) ? 
						$this->phrases["unable to update the record"] : "Unable to update the Record." , 1);
					}

				} else {

					/* Insert Record */
					$insertId = $this->base_model->insert_operation_id(
														$inputdata, 
														$table_name);
					if ($insertId > 0) {

						$image_up_success = 1;
						
						/* Save Vehicle Image */
						if($this->input->post('is_image_set') == "yes") {

							$config['upload_path'] 			= './uploads/banks/';
							$config['allowed_types'] 		= 'jpeg|jpg|png';
							$config['max_size'] 			= '10240';
							$config['overwrite'] 			= true;
							$config['remove_spaces'] 		= true;
							$filename 						= $insertId . "_" .
															  $_FILES['userfile']['name'];
							$config['file_name'] 			= $filename;


							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if($this->upload->do_upload()) {

								if($s == 0) {

									$fileinput['image'] 		= $filename;

									$this->base_model->update_operation(
																$fileinput, 
																$table_name, 
																array('id' => $insertId)
																);
								}

								$image_up_success = 1;

							} else {

								$image_up_success = 0;
								$error = array(
												'error' => $this->upload->display_errors()
											);
								$inf_txt = (isset($this->phrases["record inserted successfully. but"])) ? $this->phrases["record inserted successfully. but"] : "Record inserted successfully. But";
								$this->prepare_flashmessage($inf_txt." ".$error['error'], 2);
							}

						}

						if($image_up_success != 0) {

							$this->prepare_flashmessage(
							(isset($this->phrases["record inserted successfully"])) ? 
							$this->phrases["record inserted successfully"] : "Record inserted successfully.", 0);

						}

					} else {

						$this->prepare_flashmessage(
						(isset($this->phrases["unable to insert record"])) ? 
						$this->phrases["unable to insert record"] : "Unable to insert Record." , 1);
					}

				}

				redirect('admin/banks');

			}

		}


		if(!in_array($param1, array('add')))  /* For Listing and Editing Record(s) */
		{
			$records	= $this->base_model->run_query("SELECT * FROM ".DBPREFIX."banks ORDER by id DESC");
			if($param1 == 'edit' && $param2 != '')
			{
				$records	= $this->base_model->run_query("SELECT * FROM ".DBPREFIX."banks WHERE id = $param2 ORDER by id DESC");
			}
			if($param1 == 'edit' && empty($records))
			{
				$this->prepare_flashmessage(
						(isset($this->phrases["wrong operation"])) ? 
						$this->phrases["wrong operation"] : "Wrong operation" , 1);
				redirect('admin/banks');
			}
		}

		$this->data['records']			= $records;
		$this->data['css_type'] 		= array("datatable");
		$this->data['active_menu'] 		= "banks";
		$heading = (isset($this->phrases["testimonial"])) ? $this->phrases["testimonial"] : "Testimonial";
		$this->data['heading'] 			= '<a href="'.base_url().'admin/banks">'.$heading.'</a>';
		$this->data['sub_heading'] 				= (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
		$this->data['param'] 					= $param1;
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
}
/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */
