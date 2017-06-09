<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MY_Controller
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
	| MODULE: 			Contact
	| -----------------------------------------------------
	| This is contact module controller file.
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

	/****** DEFAULT FUNCTION ******/
	function index()
	{

		if($this->input->post()) {
			//echo "<pre>";print_r($this->input->post());die();
			
			$this->load->library('form_validation');

			$this->form_validation->set_rules('name', "Name" , 'required');
			$this->form_validation->set_rules('email', "Email", 'required|valid_email');
			$this->form_validation->set_rules('cr_tnc', " I agree with the Terms and Conditions" , 'required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() == true) {

				$success = 0;

				$contact_data['name']	= $this->input->post('name');
				$contact_data['email'] 	= $this->input->post('email');
				$contact_data['msg'] 	= $this->input->post('msg');

				$contact_data['terms_and_conditions'] 	= "not_accepted";
				$cr_tnc = $this->input->post('cr_tnc');
				if(!empty($cr_tnc))
					$contact_data['terms_and_conditions'] 	= "accepted";

				if($this->base_model->insert_operation($contact_data, 'contact_requests'))
					$success = 1;

				//Send Contact Request Email to Admin
				$from = $contact_data['email'];

				$to  = $this->config->item('site_settings')->portal_email;				

				$sub = "Contact Inquiry From ".$contact_data['name'];

				$msg = $this->load->view('email/contact_email', $contact_data, true);

				if(sendEmail($from, $to, $sub, $msg))
					$success = 1;

				if($success == 1) {
					$this->prepare_flashmessage("Contact Inquiry Sent Successfully.", 0);
				} else {
					$this->prepare_flashmessage("Contact Inquiry Not Sent.", 1);
				}
				redirect('contact','refresh');
			}

		}

		$this->data['title'] 	= "Contact Us";
		$this->data['active_class'] = 'contact_us';
		$this->data['content'] 		= 'site/contact';
		$this->_render_page('templates/site_template', $this->data);
	}


	
	
}
/* End of file Contact.php */
/* Location: ./application/controllers/Contact.php */
