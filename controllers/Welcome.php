<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller
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
	| MODULE: 			Welcome
	| -----------------------------------------------------
	| This is welcome module controller file.
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

		$this->data['title'] 	= (isset($this->phrases["welcome to point to point transfers"])) ? 
										$this->phrases["welcome to point to point transfers"] : 
										"Welcome to Point to Point Transfers";
		$this->data['active_class'] = 'home';
		$this->data['content'] 		= 'site/index';
		$this->_render_page('templates/site_template', $this->data);
	}

	
	
	/****** FAQs ******/
	function faqs()
	{

		$this->data['faqs'] 		= $this->base_model->fetch_records_from('faqs', array('faq_status' => 'Active'));
		$this->data['title'] 	= (isset($this->phrases["faqs"])) ? 
										$this->phrases["faqs"] : 
										"FAQs";
		$this->data['content'] 	= 'site/pages/faqs';
		$this->data['active_class'] = 'faqs';
		$this->_render_page('templates/site_template', $this->data);
	}
	

	
	
}
/* End of file Welcome.php */
/* Location: ./application/controllers/Welcome.php */
