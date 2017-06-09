<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faqs extends MY_Controller
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
	| MODULE: 			Faqs
	| -----------------------------------------------------
	| This is Faqs module controller file.
	| -----------------------------------------------------
	*/

	function __construct()
	{
		parent::__construct();
	}

	
	/****** Default Function ******/
	public function index($param = '', $param1 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		$this->data['message'] 					= (validation_errors()) ? 
		validation_errors() : $this->session->flashdata('message');

		if ($param 	== "delete") {		
			$this->check_isdemo(base_url() . 'faqs/index');
			if ($this->base_model->delete_record('faqs', array('faq_id' => $param1))) {
				$this->prepare_flashmessage(
				(isset($this->phrases["page deleted successfully"])) ? $this->phrases["page deleted successfully"] : "Record Deleted Successfully", 0);
				redirect('faqs/index');
			}
			else {
				$this->prepare_flashmessage(
				(isset($this->phrases["unable to delete faq"])) ? $this->phrases["unable to delete faq"] : "Unable To Delete Record", 1);
				redirect('faqs/index');
			}
		}
		elseif ($param  == "") {
			$this->data['pages'] 		= $this->base_model->fetch_records_from('faqs');
			$this->data['css_type'] 	= array("datatable");
			$this->data['active_menu'] 	= "faqs";

			$this->data['heading'] 		= (isset($this->phrases["faqs"])) ? $this->phrases["faqs"] : "FAQs";
			$this->data['title']	 	= (isset($this->phrases["faqs"])) ? $this->phrases["faqs"] : "FAQs";
			$this->data['content'] 		= "admin/faqs/list_faqs";
		}
		$this->_render_page('templates/admin_template', $this->data);
	}

	function addeditfaq($param = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		$this->data['message'] 	= (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		if ($this->input->post()) {
			$this->check_isdemo(base_url() . 'faqs/index');
			$this->form_validation->set_rules('faq_title', (isset($this->phrases["title"])) ? $this->phrases["title"] : "Title", 'required');
			$this->form_validation->set_rules('faq_content', (isset($this->phrases["content"])) ? $this->phrases["content"] : "Content", 'required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if ($this->form_validation->run() 	== TRUE) {
				$input_data['faq_title'] 	= $this->input->post('faq_title');
				$input_data['faq_content'] 	= $this->input->post('faq_content');
				$input_data['faq_status'] 	= $this->input->post('faq_status');		
				$message = '';
				
				if ($this->input->post('update_rec_id') > 0) {
					$input_data['faq_updated'] 	= date('Y-m-d H:i:s');
					$this->base_model->update_operation($input_data, 'faqs', array('faq_id' => $this->input->post('update_rec_id')));
					$message = (isset($this->phrases["page updated successfully"])) ? $this->phrases["faq updated successfully"] : "FAQ Updated Successfully";
				}
				else {					
					$this->base_model->insert_operation($input_data, 'faqs');
					$message = (isset($this->phrases["faq added successfully"])) ? $this->phrases["faq added successfully"] : "FAQ Added Successfully";					
				}
				$this->prepare_flashmessage($message, 0);
				redirect('faqs/index');
			}
		}
		
		if(empty($param))
		{
			$this->data['page_rec'] = array();
		}
		else
		{
			$this->data['page_rec'] = $this->base_model->fetch_records_from('faqs', array('faq_id' => $param));
		}

		$this->data['css_type'] 		= array("form","datatable");
		$this->data['active_menu'] 		= "faqs";
		$this->data['heading'] 			= (isset($this->phrases["faqs"])) ? $this->phrases["faqs"] : "FAQs";
		$this->data['sub_heading'] 		= (isset($this->phrases["add FAQ"])) ? $this->phrases["add FAQ"] : "Add FAQ";
		$this->data['title']			= (isset($this->phrases["add faq"])) ? $this->phrases["add faq"] : "Add FAQ";
		$this->data['content'] 			= "admin/faqs/addeditfaq";
		$this->_render_page('templates/admin_template', $this->data);
	}
	

	

	
}
/* End of file Reports.php */
/* Location: ./application/controllers/Reports.php */
