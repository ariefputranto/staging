<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request_quote extends MY_Controller
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
	| MODULE: 			Request_quote
	| -----------------------------------------------------
	| This is Request_quote module controller file.
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
	}


	/****** Quote Request Function ******/
	function index()
	{

		/* Check For Form Submission */
		if($this->input->post()) {
			$this->check_isdemo(base_url() . 'request-quote/index');
			/* Form Validations */
			$this->form_validation->set_rules(
			'name', 
			(isset($this->phrases["name"])) ? $this->phrases["name"] : "Name", 
			'required'
			);
			$this->form_validation->set_rules(
			'email', 
			(isset($this->phrases["email"])) ? $this->phrases["email"] : "Email", 
			'required|valid_email'
			);
			$this->form_validation->set_rules(
			'phone', 
			(isset($this->phrases["phone"])) ? $this->phrases["phone"] : "Phone", 
			'required'
			);
			$this->form_validation->set_rules(
			'pick_date', 
			(isset($this->phrases["pick-up date"])) ? 
			$this->phrases["pick-up date"] : "Pick-up Date", 
			'required'
			);
			$this->form_validation->set_rules(
			'pick_time', 
			(isset($this->phrases["pick-up time"])) ? 
			$this->phrases["pick-up time"] : "Pick-up Time", 
			'required'
			);
			$this->form_validation->set_rules(
			'pick_point', 
			(isset($this->phrases["pick-up address"])) ? 
			$this->phrases["pick-up address"] : "Pick-up Address", 
			'required'
			);
			$this->form_validation->set_rules(
			'drop_point', 
			(isset($this->phrases["drop-off address"])) ? 
			$this->phrases["drop-off address"] : "Drop-off Address", 
			'required'
			);

			$this->form_validation->set_error_delimiters(
			'<div class="error">', '</div>'
			);

			if($this->form_validation->run() == true) {

				$quote_req_det = array();

				foreach($this->input->post() as $key=>$val)
					$quote_req_det[$key] = $val;

				/* Save Quote request Details */
				$quote_req_det['pick_date'] 			= date('Y-m-d', 
				strtotime($this->input->post('pick_date')));
				$quote_req_det['date_of_request'] 		= date('Y-m-d');
				$quote_req_det['full_date_of_request'] 	= time();

				if($this->base_model->insert_operation($quote_req_det, 
				'quote_requests')) {

					/* Email Quote Request Details To Admin */
					$message = $this->load->view(
					'email/quote_request_details_email', $quote_req_det, true);

					$from 	 = $quote_req_det['email'];

					$to 	 = $this->config->item('site_settings')->portal_email;

					$sub 	 = (isset($this->phrases["quote request received"])) ? 
									$this->phrases["quote request received"] : "Quote Request Received";

					sendEmail($from, $to, $sub, $message);

					$this->prepare_flashmessage(
					(isset($this->phrases["your request for quote has been received successfully. we will get back to you soon."])) ? $this->phrases["your request for quote has been received successfully. we will get back to you soon."] : "Your request for quote has been received successfully. 
					We will get back to you soon.", 0);

				}

				redirect('request-quote');
			}

		}


		$this->data['css_type']		= array(
												'datepicker', 
												'timepicker'
											   );
		$this->data['title'] 		= (isset($this->phrases["request quote"])) ? $this->phrases["request quote"] : "Request Quote";
		$this->data['content'] 		= 'site/request_quote';
		$this->_render_page('templates/site_template', $this->data);
	}


	
	

		
	
}
/* End of file Request_quote.php */
/* Location: ./application/controllers/Request_quote.php */
