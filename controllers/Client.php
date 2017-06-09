<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends MY_Controller

{
	/*
	| -----------------------------------------------------
	| PRODUCT NAME: 	Digi Point to Point Transfers
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
	| MODULE: 			Client
	| -----------------------------------------------------
	| This is Client module controller file.
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

	}


	public function index()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_client()) {
			redirect('auth/login');
		}

		redirect('welcome');

	}

	
	/***** Client's Booking History ******/
	function myBookings($param1 = '', $param2 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_client()) {
			redirect('auth/login');
		}


		/* Update Booking Status To Cancelled */
		if($param1 == "Cancelled" && $param2 > 0) {

			$updata['booking_status'] 	= $param1;
			$updata['cancelled_on']		= time();

			if($this->base_model->update_operation($updata, 'bookings', array('id' => $param2))) {

				$this->prepare_flashmessage((isset($this->phrases["your booking has been cancelled. admin will refund your money, if you made payment online"])) ? $this->phrases["your booking has been cancelled. admin will refund your money, if you made payment online"] : "Your Booking has been cancelled. Admin will refund your money, If you made payment online", 0);
				redirect('client/myBookings');

			}
		}

		$this->data['booking_history'] 		= $this->base_model->fetch_records_from(
												'bookings', 
												array('user_id' => $this->ion_auth->get_user_id())
												);

		$this->data['css_type'] 			= array('datatable');
		$this->data['title'] 				= (isset($this->phrases["booking history"])) ? $this->phrases["booking history"] : "Booking History";
		$this->data['content'] 				= "client/booking_history";
		$this->_render_page('templates/site_template', $this->data);
	}
	
	
	
	/***** View Booking Details ******/
	function viewBookingDetails($booking_id = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_client()) {
			redirect('auth/login');
		}

		$booking_details	= $this->base_model->fetch_records_from(
												'bookings', 
												array('id' => $booking_id)
												);

		if(count($booking_details) == 0) {

			$this->prepare_flashmessage((isset($this->phrases["no booking details found"])) ? $this->phrases["no booking details found"] : "No Booking Details Found", 2);
			redirect('client/myBookings');
		}

		$this->data['booking_details']		= $booking_details[0];
		$this->data['title'] 				= (isset($this->phrases["booking details"])) ? $this->phrases["booking details"] : "Booking Details";
		$this->data['content'] 				= "client/booking_details";
		$this->_render_page('templates/site_template', $this->data);
	}







}
/* End of file Client.php */
/* Location: ./application/controllers/Client.php */
