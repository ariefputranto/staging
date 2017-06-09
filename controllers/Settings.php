<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller

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
	| MODULE: 			Settings
	| -----------------------------------------------------
	| This is Settings module controller file.
	| -----------------------------------------------------
	*/


	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->helper('url');

		// Load MongoDB library instead of native db driver if required

		$this->config->item('use_mongodb', 'ion_auth') ? 
		$this->load->library('mongo_db') : $this->load->database();
		
		$this->form_validation->set_error_delimiters(
		$this->config->item('error_start_delimiter', 'ion_auth') , 
		$this->config->item('error_end_delimiter', 'ion_auth'));
		
		$this->lang->load('auth');
		$this->load->helper('language');
	}


	/****** VALIDATE URL ******/
	function _valid_url($url)
	{
		$pattern = "/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
		if (!preg_match($pattern, $url)) {
			$this->form_validation->set_message('_valid_url', $this->lang->line('valid_url_req'));
			return false;
		}
		return true;
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

			$this->form_validation->set_message('_file_check', (isset($this->phrases["please upload your site logo with the extension jpg|jpeg|png."])) ? $this->phrases["please upload your site logo with the extension jpg|jpeg|png."] : "Please upload your Site Logo with the extension jpg|jpeg|png.");
			return false;
		}

	}


	function index()
	{
		redirect('admin');
	}


	/****** FUNCTION FOR SITE SETTINGS ******/
	function siteSettings()
	{
		$this->data['message'] = $this->session->flashdata('message');

		 if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		 }

		if ($this->input->post('update_rec_id')) {
			$this->check_isdemo(base_url() . 'settings/siteSettings');
			// FORM VALIDATIONS
			$this->form_validation->set_rules(
			'site_title', 
			(isset($this->phrases["site title"])) ? 
			$this->phrases["site title"] : "Site Title", 
			'required');
			$this->form_validation->set_rules(
			'address', 
			(isset($this->phrases["address"])) ? 
			$this->phrases["address"] : "Address", 
			'required');
			$this->form_validation->set_rules(
			'city', 
			(isset($this->phrases["city"])) ? 
			$this->phrases["city"] : "City", 
			'required');
			$this->form_validation->set_rules(
			'state', 
			(isset($this->phrases["state"])) ? 
			$this->phrases["state"] : "State", 
			'required');
			$this->form_validation->set_rules(
			'country', 
			(isset($this->phrases["country"])) ? 
			$this->phrases["country"] : "Country", 
			'required');
			$this->form_validation->set_rules(
			'zip', 
			(isset($this->phrases["zip code"])) ? 
			$this->phrases["zip code"] : "Zip Code", 
			'required');
			$this->form_validation->set_rules(
			'phone_code', 
			(isset($this->phrases["phone code"])) ? 
			$this->phrases["phone code"] : "Phone Code", 
			'required');
			$this->form_validation->set_rules(
			'phone', 
			(isset($this->phrases["phone"])) ? 
			$this->phrases["phone"] : "Phone", 
			'required');
			if($this->data['site_theme'] == 'seat')
			{
				if($this->input->post('insurance_type') == 'percent' && $this->input->post('insurance_value') != '')
				{
				$this->form_validation->set_rules('insurance_value', getPhrase('Inserance Value'),'trim|less_than[100]');
				}
				$this->form_validation->set_rules('booking_time_limit', getPhrase('booking time limit'),'trim|required|numeric');
			}
			$this->form_validation->set_rules(
			'portal_email', 
			(isset($this->phrases["portal email"])) ? 
			$this->phrases["portal email"] : "Portal Email", 
			'valid_email|required');
			$this->form_validation->set_rules(
			'currency_symbol', 
			(isset($this->phrases["currency symbol"])) ? 
			$this->phrases["currency symbol"] : "Currency Symbol", 
			'required');
			if($this->data['site_theme'] == 'vehicle')
			{
			$this->form_validation->set_rules(
			'cost_for_meet_greet', 
			(isset($this->phrases["cost for meet & greet"])) ? 
			$this->phrases["cost for meet & greet"] : "Cost for Meet & Greet",  
			'required|numeric');
			}
			$this->form_validation->set_rules(
			'rights_reserved_content', 
			(isset($this->phrases["rights reserved content"])) ? 
			$this->phrases["rights reserved content"] : "Rights Reserved Content",  
			'required');

			if (!empty($_FILES['userfile']['name'])) {
				$this->form_validation->set_rules('userfile', "Site Logo" , 'trim|callback__file_check[' . $_FILES['userfile']['name'] . ']');
			}

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');


			if ($this->form_validation->run() 	== TRUE) {

				$inputdata['site_title'] 		= $this->input->post('site_title');
				$inputdata['address'] 			= $this->input->post('address');
				$inputdata['google_address'] 	= $this->input->post('google_address');
				
				$inputdata['lat'] 	= $this->input->post('lat');
				$inputdata['lng'] 	= $this->input->post('lng');
				
				$inputdata['city'] 				= $this->input->post('city');
				$inputdata['state'] 			= $this->input->post('state');
				$inputdata['country'] 			= $this->input->post('country');
				$inputdata['zip'] 				= $this->input->post('zip');
				$inputdata['phone_code'] 		= $this->input->post('phone_code');
				$inputdata['phone'] 			= $this->input->post('phone');
				$inputdata['land_line'] 		= $this->input->post('land_line');
				$inputdata['fax'] 				= $this->input->post('fax');
				$inputdata['portal_email'] 		= $this->input->post('portal_email');
				$inputdata['site_country'] 		= $this->input->post('site_country');
				$inputdata['site_time_zone'] 	= $this->input->post('site_time_zone');
				if($this->data['site_theme'] == 'vehicle')
				{
				$inputdata['distance_type'] 	= $this->input->post('distance_type');
				}
				$inputdata['currency_symbol'] 	= $this->input->post('currency_symbol');
				if($this->data['site_theme'] == 'vehicle')
				{
				$inputdata['cost_for_meet_greet'] = $this->input->post('cost_for_meet_greet');
				}
				$inputdata['rights_reserved_content'] = $this->input->post('rights_reserved_content');				
				$inputdata['call_center'] 	= $this->input->post('call_center');
				$inputdata['email_support'] 	= $this->input->post('email_support');
				$inputdata['faq_page'] 	= $this->input->post('faq_page');
				$inputdata['support_ticket'] 	= $this->input->post('support_ticket');
				$inputdata['live_chat'] 	= $this->input->post('live_chat');
				$inputdata['site_theme'] 		= $this->input->post('site_theme');
				
				if($this->data['site_theme'] == 'seat')
				{
				//For Seat Booking Fields				
				$inputdata['max_seats_to_book'] = $this->input->post('max_seats_to_book');
				$inputdata['canncel_before_hours'] = $this->input->post('canncel_before_hours');
				$inputdata['max_times_sms_cansend'] = $this->input->post('max_times_sms_cansend');
				
				$inputdata['insurance_value'] = $this->input->post('insurance_value');
				$inputdata['insurance_type'] = $this->input->post('insurance_type');
				$inputdata['insurance_appliedon'] = $this->input->post('insurance_appliedon');
				//$inputdata['insurance_appliedto'] = $this->input->post('insurance_appliedto');
				
				$inputdata['transition_time_units'] = $this->input->post('transition_time_units');
				$inputdata['transition_time'] = $this->input->post('transition_time');
				
				$inputdata['display_after_units'] = $this->input->post('display_after_units');
				$inputdata['display_after'] = $this->input->post('display_after');
				$inputdata['booking_time_limit'] = $this->input->post('booking_time_limit');
				$inputdata['about_company'] = $this->input->post('about_company');
				$inputdata['whyus'] = $this->input->post('whyus');
				$inputdata['homepage_title'] = $this->input->post('homepage_title');
				$inputdata['homepage_subtitle'] = $this->input->post('homepage_subtitle');
				}
				
				$inputdata['facebook'] 	= $this->input->post('facebook');
				$inputdata['twitter'] 	= $this->input->post('twitter');
				$inputdata['google_plus'] 	= $this->input->post('google_plus');
				$inputdata['pinterest'] 	= $this->input->post('pinterest');
				$inputdata['instagram'] 	= $this->input->post('instagram');
				

				$table_name = "site_settings";
				$where['id'] = $this->input->post('update_rec_id');

				/* Save File(Site Logo) */
					$err = '';
					if (!empty($_FILES['userfile']['name'])) {

						$file_name = $this->input->post('update_rec_id') . "_" . 
															str_replace(' ', '',$_FILES['userfile']['name']);

						$config['upload_path'] 		= './'.$inputdata['site_theme'].'/'.'assets/system_design/images/';
						$config['allowed_types'] 	= 'jpg|jpeg|png';
						$config['overwrite'] 		= true;
						$config['file_name']        = $file_name;

						$this->load->library('upload', $config);

						/* Unlink Old Logo of The Site */
						if ($this->config->item('site_settings')->site_logo != "" && file_exists($inputdata['site_theme'].'/'.'assets/system_design/images/'.$this->config->item('site_settings')->site_logo)) {
							unlink($inputdata['site_theme'].'/'.'assets/system_design/images/' . $this->config->item('site_settings')->site_logo);
						}

						if ($this->upload->do_upload('userfile')) {

							$inputdata['site_logo']		= $file_name;

						} else {
							$err .= $this->upload->display_errors();
						}
					}
					
					if (!empty($_FILES['homepage_banner']['name'])) {

						$file_name = rand() . "_" .str_replace(' ', '',$_FILES['homepage_banner']['name']);

						$config['upload_path'] 		= './'.$inputdata['site_theme'].'/'.'assets/system_design/images/';
						$config['allowed_types'] 	= 'jpg|jpeg|png';
						$config['overwrite'] 		= true;
						$config['file_name']        = $file_name;

						$this->load->library('upload', $config);

						/* Unlink Old Logo of The Site */
						if ($this->config->item('site_settings')->homepage_banner != "" && file_exists($inputdata['site_theme'].'/'.'assets/system_design/images/'.$this->config->item('site_settings')->homepage_banner)) {
							unlink($inputdata['site_theme'].'/'.'assets/system_design/images/' . $this->config->item('site_settings')->homepage_banner);
						}

						if ($this->upload->do_upload('homepage_banner')) {

							$inputdata['homepage_banner']		= $file_name;

						}else {
							$err .= $this->upload->display_errors();
						}
					}
					
					if (!empty($_FILES['search_banner']['name'])) {

						$file_name = rand() . "_" .str_replace(' ', '',$_FILES['search_banner']['name']);

						$config['upload_path'] 		= './'.$inputdata['site_theme'].'/'.'assets/system_design/images/';
						$config['allowed_types'] 	= 'jpg|jpeg|png';
						$config['overwrite'] 		= true;
						$config['file_name']        = $file_name;

						$this->load->library('upload', $config);

						/* Unlink Old Logo of The Site */
						if ($this->config->item('site_settings')->search_banner != "" && file_exists($inputdata['site_theme'].'/'.'assets/system_design/images/'.$this->config->item('site_settings')->search_banner)) {
							unlink($inputdata['site_theme'].'/'.'assets/system_design/images/' . $this->config->item('site_settings')->search_banner);
						}

						if ($this->upload->do_upload('search_banner')) {
							$inputdata['search_banner']		= $file_name;
						}else {
							$err .= $this->upload->display_errors();
						}
					}
					
					if (!empty($_FILES['support_ticket_banner']['name'])) {

						$file_name = rand() . "_" .str_replace(' ', '',$_FILES['support_ticket_banner']['name']);

						$config['upload_path'] 		= './'.$inputdata['site_theme'].'/'.'assets/system_design/images/';
						$config['allowed_types'] 	= 'jpg|jpeg|png';
						$config['overwrite'] 		= true;
						$config['file_name']        = $file_name;

						$this->load->library('upload', $config);

						/* Unlink Old Logo of The Site */
						if ($this->config->item('site_settings')->support_ticket_banner != "" && file_exists($inputdata['site_theme'].'/'.'assets/system_design/images/'.$this->config->item('site_settings')->support_ticket_banner)) {
							unlink($inputdata['site_theme'].'/'.'assets/system_design/images/' . $this->config->item('site_settings')->support_ticket_banner);
						}

						if ($this->upload->do_upload('support_ticket_banner')) {

							$inputdata['support_ticket_banner']		= $file_name;

						}else {
							$err .= $this->upload->display_errors();
						}
					}
					
				if ($this->base_model->update_operation(
				$inputdata, 
				$table_name, 
				$where)) {

					$this->prepare_flashmessage(
					(isset($this->phrases["site settings has been updated successfully."])) ? $this->phrases["site settings has been updated successfully."] : "Site Settings has been updated successfully.", 0);
					redirect('settings/siteSettings', 'refresh');
				}
				else {
					$this->prepare_flashmessage(
					(isset($this->phrases["unable to update site settings"])) ? $this->phrases["unable to update site settings."] : "Unable to update Site Settings." , 1);
					redirect('settings/siteSettings');
				}
			}
		}


		$countries 							= $this->base_model->fetch_records_from('country');
		$country_options 					= array();
		foreach($countries as $row) 
			$country_options[$row->country_code_alpha2] = $row->country_name;
		$this->data['country_options'] 		= $country_options;

		$time_zones = $this->base_model->fetch_records_from('calendar|timezones', '', 'TimeZone');
		$time_zone_options 					= array();
		foreach($time_zones as $row) 
			$time_zone_options[$row->TimeZone] = $row->TimeZone;
		$this->data['time_zone_options'] 	= $time_zone_options;
		$this->data['faq_pages'] 	= $this->base_model->fetch_records_from('pages', array('page_status' => 'Active'));

		$this->data['active_menu'] 			= "site_settings";
		$this->data['heading'] 				= (isset($this->phrases["site settings"])) ? $this->phrases["site settings"] : "Site Settings";
		$this->data['title'] 				= (isset($this->phrases["site settings"])) ? $this->phrases["site settings"] : "Site Settings";
		$this->data['content'] 				= "admin/settings/site_settings";
		$this->_render_page('templates/admin_template', $this->data);
	}
	/****** FUNCTION FOR SITE SETTINGS END ******/

	
	/****** FUNCTION FOR EMAIL SETTINGS ******/
	public function emailSettings()
	{
		$this->data['message'] 				= $this->session->flashdata('message');
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		 }

		if ($this->input->post('update_record_id')) {
			$this->check_isdemo(base_url() . 'settings/emailSettings');
			// FORM VALIDATIONS

			$this->form_validation->set_rules(
			'smtp_host', 
			(isset($this->phrases["host"])) ? $this->phrases["host"] : "Host", 
			'trim|required');
			$this->form_validation->set_rules(
			'smtp_user', 
			(isset($this->phrases["email"])) ? $this->phrases["email"] : "Email", 
			'trim|required');
			$this->form_validation->set_rules(
			'smtp_port', 
			(isset($this->phrases["port"])) ? $this->phrases["port"] : "Port", 
			'trim|required');
			$this->form_validation->set_rules(
			'smtp_password', 
			(isset($this->phrases["password"])) ? 
			$this->phrases["password"] : "Password", 
			'required');
			$this->form_validation->set_rules(
			'mail_config', 
			(isset($this->phrases["mail config"])) ? 
			$this->phrases["mail config"] : "Mail Config", 
			'required');

			if($this->input->post('mail_config') == "mandrill")
				$this->form_validation->set_rules('api_key', (isset($this->phrases["api key"])) ? $this->phrases["api key"] : "API Key", 'required');
			
			if($this->input->post('mail_config') == "default")
				$this->form_validation->set_rules('from_email', (isset($this->phrases["from_email"])) ? $this->phrases["from_email"] : "From Email", 'required');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() == TRUE) {

				$inputdata['smtp_host'] 	= $this->input->post('smtp_host');
				$inputdata['smtp_user'] 	= $this->input->post('smtp_user');
				$inputdata['smtp_password'] = $this->input->post('smtp_password');
				$inputdata['smtp_port'] 	= $this->input->post('smtp_port');
				$inputdata['api_key'] 		= $this->input->post('api_key');
				$inputdata['mail_config'] 	= $this->input->post('mail_config');
				$inputdata['from_email'] 		= $this->input->post('from_email');

				$table_name 				= "email_settings";
				$where['id'] 				= $this->input->post('update_record_id');

				if ($this->base_model->update_operation($inputdata, $table_name, $where)) {
					$this->prepare_flashmessage((isset($this->phrases["email settings has been updated successfully."])) ? $this->phrases["email settings has been updated successfully."] : "Email settings has been updated successfully." , 0);
					redirect('settings/emailSettings');
				}
				else {
					$this->prepare_flashmessage((isset($this->phrases["unable to update email settings"])) ? $this->phrases["unable to update email settings"] : "Unable to update Email settings", 1);
					redirect('settings/emailSettings');
				}
			}
		}

	
		$this->data['active_menu'] 				= "email_settings";
		$this->data['heading'] 					= (isset($this->phrases["email settings"])) ? $this->phrases["email settings"] : "Email Settings";
		$this->data['title'] 					= (isset($this->phrases["email settings"])) ? $this->phrases["email settings"] : "Email Settings";
		$this->data['content'] 					= "admin/settings/email_settings";
		$this->_render_page('templates/admin_template', $this->data);
	}

	/****** FUNCTION FOR EMAIL SETTINGS END ******/
	
	
	/****** FUNCTION FOR SEO SETTINGS ******/
	public function seoSettings()
	{
		$this->data['message'] 				= $this->session->flashdata('message');
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		 }

		if (isset($_POST['submit'])) {
			$this->check_isdemo(base_url() . 'settings/seoSettings');
			$inputdata['meta_keywords'] 	= $this->input->post('meta_keywords');
			$inputdata['meta_description'] 	= $this->input->post('meta_description');
			$inputdata['google_analytics'] 	= $this->input->post('google_analytics');

			$table_name 				= "seo_settings";
			$update_record_id = $this->input->post('update_record_id');
			if($this->input->post('update_record_id') != '')
			{
				$where['id'] 				= $this->input->post('update_record_id');
				$this->base_model->update_operation($inputdata, $table_name, $where);
				
			}
			else
			{
				$update_record_id = $this->base_model->insert_operation($inputdata, $table_name);
			}

			if ($update_record_id > 0) {
				$this->prepare_flashmessage((isset($this->phrases["SEO settings has been updated successfully"])) ? $this->phrases["SEO settings has been updated successfully"] : "SEO settings has been updated successfully." , 0);
				redirect('settings/seoSettings');
			}
			else {
				$this->prepare_flashmessage((isset($this->phrases["unable to update SEO settings"])) ? $this->phrases["unable to update SEO settings"] : "Unable to update SEO settings", 1);
				redirect('settings/seoSettings');
			}
		}


		$seo_settings = $this->base_model->fetch_records_from('seo_settings');

		$this->data['seo_settings']		= (count($seo_settings) > 0) ? $seo_settings[0] : array();
		$this->data['active_menu'] 		= "seo_settings";
		$this->data['heading'] 			= (isset($this->phrases["SEO settings"])) ? $this->phrases["SEO settings"] : "SEO Settings";
		$this->data['title'] 			= (isset($this->phrases["SEO settings"])) ? $this->phrases["SEO settings"] : "SEO Settings";
		$this->data['content'] 			= "admin/settings/seo_settings";
		$this->_render_page('templates/admin_template', $this->data);
	}

	/****** FUNCTION FOR SEO SETTINGS END ******/
	
	
	/****** FUNCTION FOR CANCELLATION POLICY SETTINGS ******/
	public function cancellationPolicySettings()
	{
		$this->data['message'] 				= $this->session->flashdata('message');
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth', 'refresh');
		 }

		if (isset($_POST['submit'])) {
			$this->check_isdemo(base_url() . 'settings/cancellationPolicySettings');
			// FORM VALIDATIONS

			$this->form_validation->set_rules(
			'three_hrs_before', 
			(isset($this->phrases["3 hours before"])) ? $this->phrases["3 hours before"] : "3 Hours before", 
			'trim|required|numeric');
			$this->form_validation->set_rules(
			'five_hrs_before', 
			(isset($this->phrases["5 hours before"])) ? $this->phrases["5 hours before"] : "5 Hours before", 
			'trim|required|numeric');
			$this->form_validation->set_rules(
			'eight_hrs_before', 
			(isset($this->phrases["8 hours before"])) ? $this->phrases["8 hours before"] : "8 Hours before", 
			'trim|required|numeric');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() == TRUE) {

				$inputdata['three_hrs_before'] 	= $this->input->post('three_hrs_before');
				$inputdata['five_hrs_before'] 	= $this->input->post('five_hrs_before');
				$inputdata['eight_hrs_before'] 	= $this->input->post('eight_hrs_before');

				$table_name 				= "cancellation_policy";
				$update_record_id = $this->input->post('update_record_id');
				if($this->input->post('update_record_id') != '')
				{
				$where['id'] 				= $this->input->post('update_record_id');
				$this->base_model->update_operation($inputdata, $table_name, $where);
				}
				else
				{
					$update_record_id = $this->base_model->insert_operation($inputdata, $table_name);
				}

				if ($update_record_id > 0) {
					$this->prepare_flashmessage((isset($this->phrases["cancellation policy settings has been updated successfully"])) ? $this->phrases["cancellation policy settings has been updated successfully"] : "Cancellation Policy settings has been updated successfully." , 0);
					redirect('settings/cancellationPolicySettings');
				}
				else {
					$this->prepare_flashmessage((isset($this->phrases["unable to update cancellation policy settings"])) ? $this->phrases["unable to update cancellation policy settings"] : "Unable to update Cancellation Policy settings", 1);
					redirect('settings/cancellationPolicySettings');
				}
			}
		}

		$cancellation_policy_settings = $this->base_model->fetch_records_from('cancellation_policy');

		$this->data['cancellation_policy_settings']	= (count($cancellation_policy_settings) > 0) ? $cancellation_policy_settings[0] : array();
		$this->data['active_menu'] 				= "cancellation_policy";
		$this->data['heading'] 					= (isset($this->phrases["cancellation policy settings"])) ? $this->phrases["cancellation policy settings"] : "Cancellation Policy Settings";
		$this->data['title'] 					= (isset($this->phrases["cancellation policy settings"])) ? $this->phrases["cancellation policy settings"] : "Cancellation Policy Settings";
		$this->data['content'] 					= "admin/settings/cancellation_policy_settings";
		$this->_render_page('templates/admin_template', $this->data);
	}

	/****** FUNCTION FOR CANCELLATION POLICY SETTINGS END ******/


	/****** VEHICLE CATEGORIES MODULE - START ******/
	function vehicleCategories($param1 = "list", $param2 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}


		$records    = array();
		$where['1']	= "1";
		$title		= (isset($this->phrases["vehicle categories"])) ? 
		$this->phrases["vehicle categories"] : "Vehicle Categories";
		$content = 'admin/settings/vehicle_categories/vehicle_categories_list';

		/* Delete Record */
		if($param1 == "delete" && $param2 > 0) {
$this->check_isdemo(base_url() . 'settings/vehicleCategories');
			if($this->base_model->delete_record('vehicle_categories', array('id' => $param2))) {

				/* Delete Vehicles under this Category */
				$this->base_model->delete_record('vehicle', array('category_id' => $param2));

				$this->prepare_flashmessage((isset($this->phrases["record deleted successfully"])) ? $this->phrases["record deleted successfully"] : "Record Deleted Successfully.", 0);
				redirect('settings/vehicleCategories');

			}
		}


		if($param1 == "add" || ($param1 == "edit" && $param2 > 0)) {

			$op_txt1  = (isset($this->phrases[$param1])) ? 
							$this->phrases[$param1] : ucwords($param1);
			$op_txt2  = (isset($this->phrases["vehicle category"])) ? 
							$this->phrases["vehicle category"] : "Vehicle Category";
			$title	  = $op_txt1." ".$op_txt2;
			$content  = 'admin/settings/vehicle_categories/add_vehicle_category';

			if($param1 == "edit") {

				$this->data['update_rec_id'] = $param2;
				$where['id']				 = $param2;
			}
		}


		/* Check for Form Submission */
		if($this->input->post()) {
$this->check_isdemo(base_url() . 'settings/vehicleCategories');
			$table_name = "vehicle_categories";
			// FORM VALIDATIONS
			if($this->input->post('update_rec_id') > 0)
			{
				$this->form_validation->set_rules(
			'category', 
			(isset($this->phrases["category"])) ? 
			$this->phrases["category"] : "Category" , 
			'trim|required');
			}
			else
			{
			$this->form_validation->set_rules(
			'category', 
			(isset($this->phrases["category"])) ? 
			$this->phrases["category"] : "Category" , 
			'trim|required|is_unique['.$this->db->dbprefix($table_name).'.category]');
			}

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');


			if ($this->form_validation->run() 	== TRUE) {

				$inputdata['category'] 		= $this->input->post('category');
				$inputdata['status'] 		= $this->input->post('status');

				

				if($this->input->post('update_rec_id') > 0) {

					/* Update Record */
					$where['id'] = $this->input->post('update_rec_id');
					if ($this->base_model->update_operation(
					$inputdata, 
					$table_name, 
					$where)) {

						$this->prepare_flashmessage(
						(isset($this->phrases["record updated successfully"])) ? 
						$this->phrases["record updated successfully"] : "Record updated successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(isset($this->phrases["unable to update the record"])) ? 
						$this->phrases["unable to update the record"] : "Unable to update the Record." , 1);
					}

				} else {

					/* Insert Record */
					if ($this->base_model->insert_operation(
					$inputdata, 
					$table_name)) {

						$this->prepare_flashmessage(
						(isset($this->phrases["record inserted successfully"])) ? 
						$this->phrases["record inserted successfully"] : "Record inserted successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(isset($this->phrases["unable to insert record"])) ? 
						$this->phrases["unable to insert record"] : "Unable to insert Record." , 1);
					}

				}

				redirect('settings/vehicleCategories');

			}

		}


		if(!in_array($param1, array('add'))) /* For Listing and Editing Record(s) */
			$records	= $this->base_model->fetch_records_from('vehicle_categories', $where, '', 'id', 'DESC');

		$this->data['records']					= $records;
		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= "vehicle_settings";
		$heading = (isset($this->phrases["vehicle categories"])) ? '<a href="'.base_url().'settings/vehicleCategories/list">'.$this->phrases["vehicle categories"].'</a>' : "Vehicle Categories";
		$this->data['heading'] 		= $heading;
		$this->data['sub_heading'] 				= (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
		$this->data['param'] 					= $param1;
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
	/****** VEHICLE CATEGORIES MODULE - END ******/
	
	
	/****** VEHICLE FEATURES MODULE - START ******/
	function vehicleFeatures($param1 = "list", $param2 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		$this->data['message'] = $this->session->flashdata('message');
		$records    = array();
		$where['1']	= "1";
		$title		= (isset($this->phrases["vehicle features"])) ? 
						$this->phrases["vehicle features"] : "Vehicle Features";
		$content = 'admin/settings/vehicle_features/vehicle_features_list';

		/* Delete Record */
		if($param1 == "delete" && $param2 > 0) {
			$this->check_isdemo(base_url() . 'settings/vehicleFeatures');
			if($this->base_model->delete_record('features', array('id' => $param2))) {

				$this->prepare_flashmessage((isset($this->phrases["record deleted successfully"])) ? $this->phrases["record deleted successfully"] : "Record Deleted Successfully.", 0);
				redirect('settings/vehicleFeatures');

			}
		}


		if($param1 == "add" || ($param1 == "edit" && $param2 > 0)) {

			$op_txt1  = (isset($this->phrases[$param1])) ? 
							$this->phrases[$param1] : ucwords($param1);
			$op_txt2  = (isset($this->phrases["vehicle feature"])) ? 
							$this->phrases["vehicle feature"] : "Vehicle Feature";
			$title	  = $op_txt1." ".$op_txt2;
			$content  = 'admin/settings/vehicle_features/add_vehicle_feature';

			if($param1 == "edit") {

				$this->data['update_rec_id'] = $param2;
				$where['id']				 = $param2;
			}
		}


		/* Check for Form Submission */
		if($this->input->post()) {
$this->check_isdemo(base_url() . 'settings/vehicleFeatures');
			$table_name = "features";
			// FORM VALIDATIONS
			if($this->input->post('update_rec_id') > 0) {
				$this->form_validation->set_rules('features',(isset($this->phrases["feature"])) ? $this->phrases["feature"] : "Feature" , 'trim|required');
			} else {			
			$this->form_validation->set_rules('features',(isset($this->phrases["feature"])) ? $this->phrases["feature"] : "Feature" ,		'trim|required|is_unique['.$this->db->dbprefix($table_name).'.features]');
			}

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');


			if ($this->form_validation->run() 	== TRUE) {

				$inputdata['features'] 		= $this->input->post('features');
				$inputdata['status'] 		= $this->input->post('status');

				

				if($this->input->post('update_rec_id') > 0) {

					/* Update Record */
					$where['id'] = $this->input->post('update_rec_id');
					if ($this->base_model->update_operation(
					$inputdata, 
					$table_name, 
					$where)) {

						$this->prepare_flashmessage(
						(isset($this->phrases["record updated successfully"])) ? 
						$this->phrases["record updated successfully"] : "Record updated successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(isset($this->phrases["unable to update the record"])) ? 
						$this->phrases["unable to update the record"] : "Unable to update the Record." , 1);
					}

				} else {

					/* Insert Record */
					if ($this->base_model->insert_operation(
					$inputdata, 
					$table_name)) {

						$this->prepare_flashmessage(
						(isset($this->phrases["record inserted successfully"])) ? 
						$this->phrases["record inserted successfully"] : "Record inserted successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(isset($this->phrases["unable to insert record"])) ? 
						$this->phrases["unable to insert record"] : "Unable to insert Record." , 1);
					}

				}

				redirect('settings/vehicleFeatures');

			}

		}


		if(!in_array($param1, array('add')))  /* For Listing and Editing Record(s) */
			$records	= $this->base_model->fetch_records_from('features', $where, '', 'id', 'DESC');

		$this->data['records']					= $records;
		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= "vehicle_settings";
		
		$heading = (isset($this->phrases["vehicle features"])) ? '<a href="'.base_url().'settings/vehicleFeatures">'.$this->phrases["vehicle features"].'</a>' : '<a href="'.base_url().'settings/vehicleFeatures">'."Vehicle Features".'</a>';
		$this->data['heading'] 					= $heading;
		$this->data['sub_heading'] 				= (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
		$this->data['param'] 					= $param1;
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
	/****** VEHICLE FEATURES MODULE - END ******/



	/******	CRUD OPERATIONS FOR VEHICLE SETTINGS - START	******/

	function vehicles($param1 = "list", $param2 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}
		$this->data['message'] = $this->session->flashdata('message');

		$records     		= array();
		$extra_conds 		= "";
		$vehicle_cat_opts	= array();
		$title		 		= (isset($this->phrases["vehicle settings"])) ? 
		$this->phrases["vehicle settings"] : "Vehicle Settings";
		$content 			= 'admin/settings/vehicles/vehicle_list';
		$this->data['vehicle_cat_opts']	= "";

		/* Delete Record */
		if($param1 == "delete" && $param2 > 0) {
$this->check_isdemo(base_url() . 'settings/vehicles');
			$vehicle_image_rec = $this->base_model->fetch_records_from('vehicle', array('id' => $param2));

			if($this->base_model->delete_record('vehicle', array('id' => $param2))) {

				/* Delete Vehicle Features associated with the vehicle */
				$this->base_model->delete_record('vehicle_features', array('vehicle_id' => $param2));

				/* Unlink Vehicle Image */
				if(count($vehicle_image_rec) > 0)
				if (isset($vehicle_image_rec[0]->image) && $vehicle_image_rec[0]->image != "" && file_exists('uploads/vehicle_images/' . $vehicle_image_rec[0]->image)) 
					unlink('uploads/vehicle_images/' . $vehicle_image_rec[0]->image);

				$this->prepare_flashmessage((isset($this->phrases["record deleted successfully"])) ? $this->phrases["record deleted successfully"] : "Record Deleted Successfully.", 0);
				redirect('settings/vehicles');

			}
		}


		if($param1 == "add" || ($param1 == "edit" && $param2 > 0)) {

			$op_txt1  = (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
			$op_txt2  = (isset($this->phrases["vehicle"])) ? $this->phrases["vehicle"] : "Vehicle";
			$title	 = $op_txt1." ".$op_txt2;
			$content = 'admin/settings/vehicles/add_vehicle';

			/****** Prepare Vehicle Category Options ******/
			$vehicle_cats = $this->base_model->fetch_records_from('vehicle_categories', array('status' => 'Active'), '', 'category', 'ASC');
			foreach($vehicle_cats as $c)
				$vehicle_cat_opts[$c->id]	= (isset($this->phrases[$c->category])) ? 
												$this->phrases[$c->category] : $c->category;

			$this->data['vehicle_cat_opts']	= $vehicle_cat_opts;


			/****** Get Records of Features ******/
			$features = $this->base_model->fetch_records_from('features', array('status' => 'Active'));
			$this->data['features'] = $features;

			$this->data['vehicle_features'] = array();

			if($param1 == "edit") {

				$this->data['update_rec_id'] = $param2;
				$extra_conds 				 = " AND v.id=".$param2;

				/****** Get Records of Selected Vehicle Features ******/
				$vehicle_features_recs = $this->base_model->fetch_records_from('vehicle_features', array('vehicle_id' => $param2), 'feature_id');
				$vehicle_features 	= array();
				foreach($vehicle_features_recs as $f) {
					array_push($vehicle_features, $f->feature_id);
				}

				$this->data['vehicle_features'] = $vehicle_features;

			}
		}


		/* Check for Form Submission */
		if($this->input->post()) {
$this->check_isdemo(base_url() . 'settings/vehicles');
			// FORM VALIDATIONS
			$this->form_validation->set_rules(
			'category_id', 
			(isset($this->phrases["category id"])) ? 
			$this->phrases["category id"] : "Category Id", 
			'required');
			$this->form_validation->set_rules(
			'name', 
			(isset($this->phrases["vehicle name"])) ? 
			$this->phrases["vehicle name"] : "Vehicle Name", 
			'required');
			$this->form_validation->set_rules(
			'model', 
			(isset($this->phrases["vehicle model"])) ? 
			$this->phrases["vehicle model"] : "Vehicle Model", 
			'required');
			$this->form_validation->set_rules(
			'number_plate', 
			(isset($this->phrases["vehicle number"])) ? 
			$this->phrases["vehicle number"] : "Vehicle Number", 
			'required');
			$this->form_validation->set_rules(
			'passenger_capacity', 
			(isset($this->phrases["passenger capacity"])) ? 
			$this->phrases["passenger capacity"] : "Passenger Capacity", 
			'required|numeric');
			$this->form_validation->set_rules(
			'large_luggage_capacity', 
			(isset($this->phrases["large luggage capacity"])) ? 
			$this->phrases["large luggage capacity"] : "Large Luggage Capacity", 
			'required|numeric');
			$this->form_validation->set_rules(
			'small_luggage_capacity', 
			(isset($this->phrases["small luggage capacity"])) ? 
			$this->phrases["small luggage capacity"] : "Small Luggage Capacity", 
			'required|numeric');
						
			$this->form_validation->set_rules('seat_rows', getPhrase('Rows'),'required|numeric');
			$this->form_validation->set_rules('seat_columns', getPhrase('Columns'),'required|numeric');
						
			if($this->data['site_theme'] == 'vehicle')
			{
			$this->form_validation->set_rules(
			'total_vehicles', 
			(isset($this->phrases["total vehicles"])) ? 
			$this->phrases["total vehicles"] : "Total Vehicles", 
			'required|numeric');
			$this->form_validation->set_rules(
			'base_fare', 
			(isset($this->phrases["base fare"])) ? 
			$this->phrases["base fare"] : "Base Fare", 
			'required|numeric');
			$this->form_validation->set_rules(
			'cost_per_km', 
			(isset($this->phrases["cost per kilometer / mile"])) ? 
			$this->phrases["cost per kilometer / mile"] : "Cost per Kilometer/Mile" , 
			'required|numeric');
			$this->form_validation->set_rules(
			'cost_per_minute', 
			(isset($this->phrases["cost per minute"])) ? 
			$this->phrases["cost per minute"] : "Cost per Minute", 
			'required|numeric');
			}

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() 	== TRUE) {
				$image_up_success = 0;
				$inputdata['category_id'] 				= $this->input->post('category_id');
				$inputdata['name'] 						= $this->input->post('name');
				$inputdata['model'] 					= $this->input->post('model');
				$inputdata['number_plate'] 				= $this->input->post('number_plate');
				$inputdata['description'] 				= $this->input->post('description');
				$inputdata['passenger_capacity'] 		= $this->input->post('passenger_capacity');
				$inputdata['large_luggage_capacity'] 	= $this->input->post('large_luggage_capacity');
				$inputdata['small_luggage_capacity'] 	= $this->input->post('small_luggage_capacity');
				$inputdata['fuel_type'] 	= $this->input->post('fuel_type');
				if($this->data['site_theme'] == 'vehicle')
				{
				$inputdata['total_vehicles'] = $this->input->post('total_vehicles');
				$inputdata['base_fare'] 				= $this->input->post('base_fare');
				$inputdata['cost_per_km'] 				= $this->input->post('cost_per_km');
				$inputdata['cost_per_minute'] 			= $this->input->post('cost_per_minute');
				}
				
				$inputdata['seat_rows'] = $this->input->post('seat_rows');
				$inputdata['seat_columns'] = $this->input->post('seat_columns');
				
				$seats_empty = $this->input->post('seats_empty');
				if(!empty($seats_empty))
				$inputdata['seats_empty'] = implode(',', $seats_empty);
			else
				$inputdata['seats_empty'] = '';
				
				$child_seats = $this->input->post('child_seats');
				if(!empty($child_seats))
				$inputdata['child_seats'] = implode(',', $child_seats);
			else
				$inputdata['child_seats'] = '';	
				$inputdata['has_driver_seat'] 		= $this->input->post('has_driver_seat');
				$inputdata['status'] 					= $this->input->post('status');

				$table_name = "vehicle";

				if($this->input->post('update_rec_id') > 0) {

					$id = $this->input->post('update_rec_id');
					/* Update Record */
					$where['id'] 			= $id;
					$where2['vehicle_id'] 	= $id;
					if ($this->base_model->update_operation(
					$inputdata, 
					$table_name, 
					$where)) {

						$image_up_success = 1;

						$this->base_model->delete_record('vehicle_features', $where2);
						/* Save Vehicle Features. */
						$sel_vehicle_features = $this->input->post('feature_id');
						foreach($sel_vehicle_features as $vf) {
							if ($vf != "" && is_numeric($vf)) {
								$vfData['feature_id'] 			= $vf;
								$vfData['vehicle_id'] 			= $id;
								$this->base_model->insert_operation($vfData, 'vehicle_features');
							}
						}

						/* Update Vehicle Image */
						if($this->input->post('is_image_set') == "yes") {

							/* Unlink Old Image */
							if ($this->input->post('current_img') != "" && file_exists('uploads/vehicle_images/' . $this->input->post('current_img'))) unlink('uploads/vehicle_images/' . $this->input->post('current_img'));

							$config['upload_path'] 			= './uploads/vehicle_images/';
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

						/* Save Vehicle Features. */
						$sel_vehicle_features = $this->input->post('feature_id');
						foreach($sel_vehicle_features as $vf) {
							if ($vf != "" && is_numeric($vf)) {
								$vfData['feature_id'] 			= $vf;
								$vfData['vehicle_id'] 			= $insertId;
								$this->base_model->insert_operation($vfData, 'vehicle_features');
							}
						}

						/* Save Vehicle Image */
						if($this->input->post('is_image_set') == "yes") {

							$config['upload_path'] 			= './uploads/vehicle_images/';
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

				redirect('settings/vehicles');

			}

		}


		if(!in_array($param1, array('add')))  /* For Listing and Editing Record(s) */
		{
			$records	= $this->base_model->run_query("SELECT v.*, vc.category FROM ".DBPREFIX."vehicle v, ".DBPREFIX."vehicle_categories vc WHERE v.category_id=vc.id AND vc.status='Active'".$extra_conds." ORDER by v.id DESC");
			if($param1 == 'edit' && empty($records))
			{
				$this->prepare_flashmessage(
						(isset($this->phrases["wrong operation"])) ? 
						$this->phrases["wrong operation"] : "Wrong operation" , 1);
				redirect('settings/vehicles/list');
			}
		}

		$this->data['records']			= $records;
		$this->data['css_type'] 		= array("datatable");
		$this->data['active_menu'] 		= "vehicle_settings";
		$heading = (isset($this->phrases["vehicle"])) ? $this->phrases["vehicle"] : "Vehicle";
		$this->data['heading'] 			= '<a href="'.base_url().'settings/vehicles/list">'.$heading.'</a>';
		$this->data['sub_heading'] 				= (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
		$this->data['param'] 					= $param1;
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}

	/******	CRUD OPERATIONS FOR VEHICLE SETTINGS - END	******/
	
	function checkimage()
	{
		$file = $_FILES['location_image']['name'];
		$ext = pathinfo($_FILES['location_image']['name'], PATHINFO_EXTENSION);
		if(!in_array($ext, array('gif','jpg','jpeg','png')))
		{
			$this->form_validation->set_message('checkimage', getPhrase('No a valid file'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	/****** LOCATIONS MODULE - START ******/
	function locations($param1 = "list", $param2 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}
$this->data['message'] = $this->session->flashdata('message');
		$records     	= array();
		$where['1']		= "1";
		$title		 	= (isset($this->phrases["locations"])) ? $this->phrases["locations"] : "Locations";
		$content 		= 'admin/settings/locations/locations_list';

		/* Delete Record */
		if($param1 == "delete" && $param2 > 0) {
$this->check_isdemo(base_url() . 'settings/locations');
			if($this->base_model->delete_record('locations', array('id' => $param2))) {

				/* Delete Travel Locations associated with the location */
				$this->db->query("DELETE FROM ".DBPREFIX."travel_locations WHERE from_loc_id=".$param2." OR to_loc_id=".$param2);

				/* Delete Travel Location Costs associated with the location.*/
				$this->db->query("DELETE FROM ".DBPREFIX."travel_location_costs WHERE travel_location_id IN (SELECT travel_location_id FROM ".DBPREFIX."travel_locations WHERE from_loc_id=".$param2." OR to_loc_id=".$param2.")");

				$this->prepare_flashmessage((isset($this->phrases["record deleted successfully"])) ? $this->phrases["record deleted successfully"] : "Record Deleted Successfully.", 0);
				redirect('settings/locations');

			}
		}


		if($param1 == "add" || ($param1 == "edit" && $param2 > 0)) {

			$op_txt1  = (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
			$op_txt2  = (isset($this->phrases["location"])) ? $this->phrases["location"] : "Location";
			$title		= $op_txt1." ".$op_txt2;
			$content = 'admin/settings/locations/add_location';

			if($param1 == "edit") {

				$this->data['update_rec_id'] = $param2;
				$where['id']				 = $param2;
			}
		}


		/* Check for Form Submission */
		if($this->input->post()) {
$this->check_isdemo(base_url() . 'settings/locations');
			// FORM VALIDATIONS
			$this->form_validation->set_rules(
			'location', 
			(isset($this->phrases["location"])) ? 
			$this->phrases["location"] : "Location" , 
			'required');
			if($this->input->post('location_visibility_type') == 'via')
			{
			$this->form_validation->set_rules('parent_id',(isset($this->phrases["location"])) ? $this->phrases["location"] : "Location" , 'trim|required|numeric');
			}
			if($_FILES['location_image']['name'] != '')
			{
				$this->form_validation->set_rules('location_image', getPhrase('Location Image'), 'trim|callback_checkimage');
			}

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');


			if ($this->form_validation->run() 	== TRUE) {

				$inputdata['location'] 					= $this->input->post('location');
				$inputdata['location_visibility_type'] 	= $this->input->post('location_visibility_type');
				$inputdata['parent_id'] = $this->input->post('parent_id');
				$inputdata['address'] = $this->input->post('address');
				$inputdata['is_airport'] 				= $this->input->post('is_airport');
				
				$parts = $this->input->post('location_time_zone');
				$parts = explode('_', $parts);
				$inputdata['location_time_zone'] = $parts[0];
				$inputdata['location_time_zone_id'] = $parts[1];
				$inputdata['status'] 					= $this->input->post('status');
//print_r($inputdata);print_r($_FILES);die();
				$table_name = "locations";
				$id = 0;
				if($this->input->post('update_rec_id') > 0) {
				$id = $this->input->post('update_rec_id');
					/* Update Record */
					$where['id'] = $this->input->post('update_rec_id');
					if ($this->base_model->update_operation(
					$inputdata, 
					$table_name, 
					$where)) {

					
						$this->prepare_flashmessage(
						(isset($this->phrases["record updated successfully"])) ? 
						$this->phrases["record updated successfully"] : "Record updated successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(isset($this->phrases["unable to update the record"])) ? 
						$this->phrases["unable to update the record"] : "Unable to update the Record." , 1);
					}

				} else {

					/* Insert Record */
					$id = $this->base_model->insert_operation_id(
					$inputdata, 
					$table_name);
					if ($id) {

						$this->prepare_flashmessage(
						(isset($this->phrases["record inserted successfully"])) ? 
						$this->phrases["record inserted successfully"] : "Record inserted successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(isset($this->phrases["unable to insert record"])) ? 
						$this->phrases["unable to insert record"] : "Unable to insert Record." , 1);
					}

				}
				if($id > 0)
				{
					if(count($_FILES) > 0)
					{
						$filedata = array();
						
						foreach ($_FILES as $key => $value)
						{
							
							if (!empty($value['name']) && $value['error'] != 4)
							{
								$config['overwrite'] 		= TRUE;
								$config['upload_path'] 			= './uploads/location_images/';									
								$config['allowed_types'] 	= 'gif|jpg|jpeg|png';
								$ext = pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION);
								$config['file_name'] 		= 'location_' . $id . '.' . $ext;
								
								$this->load->library('upload', $config);
								$this->upload->initialize($config);
								
								if (!$this->upload->do_upload($key))
								{
									$file_error .= $this->upload->display_errors();
									echo "<pre>";print_R($file_error);die();
								}
								else
								{								
									$this->create_thumbnail($config['upload_path'] . $config['file_name'], $config['upload_path'].'thumbs/', 178, 115);
									$filedata['location_image'] = $config['file_name'];
								}							 
							}
						}
						
						if(count($filedata) > 0)
						{
							$this->base_model->update_operation( $filedata, $table_name, array('id' => $id) );
						}
						//neatPrint($filedata);
					}
				}

				redirect('settings/locations');

			}

		}


		if(!in_array($param1, array('add')))  /* For Listing and Editing Record(s) */
		{
			$records	= $this->base_model->fetch_records_from('locations', $where, '', 'id', 'DESC');
			if($param1 == 'edit' && empty($records))
			{
				$this->prepare_flashmessage(
						(isset($this->phrases["wrong operation"])) ? 
						$this->phrases["wrong operation"] : "Wrong operation" , 1);
				redirect('settings/locations');
			}
		}

		$this->data['records']					= $records;
		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= "locations";
		$heading = (isset($this->phrases["location"])) ? '<a href="'.base_url().'settings/locations">'.$this->phrases["location"].'</a>' : '<a href="'.base_url().'settings/locations">'."Location".'</a>';
		$this->data['heading'] 		= $heading;
		$this->data['sub_heading'] 				= (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
		
		$from_locations = $this->base_model->getFromLocaitons('Active');  
		$from_loc_opts = array('' => 'Select Parent Location');
		foreach($from_locations as $rec)
			$from_loc_opts[$rec->id] = $rec->location;
		$this->data['from_loc_opts'] = $from_loc_opts;
		
		$this->data['param'] 					= $param1;
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
	/****** LOCATIONS MODULE - END ******/

	function checkdatevalid()
	{
		if($this->input->post('start_time'))
		{
			$parts = explode(':', $this->input->post('start_time'));
			$time = trim($parts[0]).':'.trim($parts[1]).' '.trim($parts[2]);	
		}
		else
		$time = date('h').':'.date('i').' '.date('a');
		$pick_date_time = date('m/d/Y ').$time;

		if($this->input->post('destination'))
		{
			$parts = explode(':', $this->input->post('destination'));
			$time = trim($parts[0]).':'.trim($parts[1]).' '.trim($parts[2]);
		}
		else
		$time = date('h').':'.date('i').' '.date('A');
		$destination_time = date('m/d/Y',strtotime($pick_date_time.' +'.$this->input->post('elapsed_days').' days')).' '.$time;
		
		if($destination_time > $pick_date_time )
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('checkdatevalid', getPhrase('Destination time should be greater than start time'));
			return FALSE;
		}
	}
	/****** TRAVEL LOCATIONS MODULE - START ******/
	function travelLocations($param1 = "list", $param2 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}
$this->data['message'] = $this->session->flashdata('message');

		$records     	= array();
		$where['1']		= "1";
		$title		 	= (isset($this->phrases["travel locations"])) ? $this->phrases["travel locations"] : "Travel Locations";
		$content 		= 'admin/settings/travel_locations/travel_locations_list';

		/* Delete Record */
		if($param1 == "delete" && $param2 > 0) {
$this->check_isdemo(base_url() . 'settings/travelLocations');
			if($this->base_model->delete_record('travel_locations', array('travel_location_id' => $param2))) {

				$this->base_model->delete_record('travel_location_costs', array('travel_location_id' => $param2));

				$this->prepare_flashmessage((isset($this->phrases["record deleted successfully"])) ? $this->phrases["record deleted successfully"] : "Record Deleted Successfully.", 0);
				redirect('settings/travelLocations');

			}
		}


		if($param1 == "add" || ($param1 == "edit" && $param2 > 0)) {

			$op_txt1  = (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
			$op_txt2  = (isset($this->phrases["travel location"])) ? $this->phrases["travel location"] : "Travel Location";
			$title	  = $op_txt1." ".$op_txt2;
			$content  = 'admin/settings/travel_locations/add_travel_location';

			if($param1 == "edit") {

				$this->data['update_rec_id'] 	= $param2;
				$where['travel_location_id']	= $param2;
				$records	= $this->base_model->fetch_records_from('travel_locations', $where);
			}
		}


		/* Check for Form Submission */
		if($this->input->post()) {
$this->check_isdemo(base_url() . 'settings/travelLocations');
			// FORM VALIDATIONS
			$this->form_validation->set_rules(
			'from_loc_id', 
			(isset($this->phrases["start location"])) ? 
			$this->phrases["start location"] : "Start Location", 
			'required');			
			$this->form_validation->set_rules('to_loc_id', getPhrase('end location'),'required');
			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if ($this->form_validation->run() 	== TRUE) {

				$inputdata['from_loc_id'] 	= $this->input->post('from_loc_id');
				$inputdata['to_loc_id'] 	= $this->input->post('to_loc_id');
				$inputdata['number_of_transition_points'] = (isset($_POST['transition_points'])) ? count($_POST['transition_points']) : 0;				
				$inputdata['status'] 		= $this->input->post('status');
				if($inputdata['from_loc_id'] == $inputdata['to_loc_id']) {

					$this->prepare_flashmessage(
							(isset($this->phrases["from and to locations should not be the same"])) ? $this->phrases["from and to locations should not be the same"] : "From and To Locations should not be the same." , 2);
					redirect('settings/travelLocations');
				}
				$table_name = "travel_locations";
				$travel_location_id = $this->input->post('update_rec_id');
				$transition_location_ids = array();
				if($this->input->post('update_rec_id') > 0) {
					/* Update Record */
					$where['travel_location_id'] = $this->input->post('update_rec_id');
					if ($this->base_model->update_operation(
					$inputdata, 
					$table_name, 
					$where)) {
						
						$this->db->query('DELETE FROM '.$this->db->dbprefix('travel_locations_transitions').' WHERE travel_location_id = '.$this->input->post('update_rec_id'));
						if(isset($_POST['transition_points']) && count($_POST['transition_points']) > 0)
						{
							$transition_points = array();
							foreach($_POST['transition_points'] as $key => $val)
							{
								$transition_points[] = array(
									'travel_location_id' => $where['travel_location_id'],
									'location_id' => $val,
								);
								$transition_location_ids[] = $val;
							}
							if(count($transition_points) > 0)
							{
								$this->db->insert_batch('travel_locations_transitions', $transition_points);
							}
						}
						$this->prepare_flashmessage(
						(isset($this->phrases["record updated successfully"])) ? 
						$this->phrases["record updated successfully"] : "Record updated successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(isset($this->phrases["unable to update the record"])) ? 
						$this->phrases["unable to update the record"] : "Unable to update the Record." , 1);
					}

				} else {

					$check_for_duplicate = $this->base_model->fetch_records_from(
										'travel_locations', 
										 array('from_loc_id' => $this->input->post('from_loc_id'), 
										       'to_loc_id'	 => $this->input->post('to_loc_id')
										      )
										);
					
					if(count($check_for_duplicate) > 0) {
						$this->prepare_flashmessage(
							(isset($this->phrases["the travel location already exists"])) ? $this->phrases["the travel location already exists"] : "The Travel Location Already Exists." , 2);
						redirect('settings/travelLocations');
					}

					/* Insert Record */
					$inert_id = $this->base_model->insert_operation_id(		$inputdata, $table_name);
					$travel_location_id = $insert_id;
					if ($inert_id > 0) {
						
						if(isset($_POST['transition_points']) && count($_POST['transition_points']) > 0)
						{
							$transition_points = array();
							foreach($_POST['transition_points'] as $key => $val)
							{
								$transition_points[] = array(
									'travel_location_id' => $inert_id,
									'location_id' => $val,
								);
								$transition_location_ids[] = $val;
							}	
							if(count($transition_points) > 0)
							{
								$this->db->insert_batch('travel_locations_transitions', $transition_points);
							}
						}

						$this->prepare_flashmessage(
						(isset($this->phrases["record inserted successfully"])) ? 
						$this->phrases["record inserted successfully"] : "Record inserted successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(isset($this->phrases["unable to insert record"])) ? 
						$this->phrases["unable to insert record"] : "Unable to insert Record." , 1);
					}

				}
				
				//Generate all possible routes
				$this->base_model->delete_record('possible_travel_locations', array('travel_location_id' => $travel_location_id));
				if(!empty($transition_location_ids))
				{
					$from_loc_id = $this->input->post('from_loc_id');
					$to_loc_id = $this->input->post('to_loc_id');
					
					$transitions[] = array('from_location' => $from_loc_id, 'to_location' => $to_loc_id, 'travel_location_id' => $travel_location_id);
					$from_possibilities = combinations(array(array($from_loc_id), $transition_location_ids));
					
					if(!empty($from_possibilities))
					{
						foreach($from_possibilities as $key => $route)
						{
							$transitions[] = array('from_location' => $route[0], 'to_location' => $route[1], 'travel_location_id' => $travel_location_id);
						}
					}
					
					$to_possibilities = combinations(array($transition_location_ids, array($to_loc_id)));
					
					if(!empty($to_possibilities))
					{
						foreach($to_possibilities as $key => $route)
						{
							$transitions[] = array('from_location' => $route[0], 'to_location' => $route[1], 'travel_location_id' => $travel_location_id);
						}
					}
					//$transitions = array();
					foreach($transition_location_ids as $loc1)
					{
						foreach($transition_location_ids as $loc2)
						{
							$new_array = $transition_location_ids;
							if(($key = array_search($loc1, $new_array)) !== false) {
								unset($new_array[$key]);
							}
							$combinations = combinations(array(array($loc1), $new_array));
							//$combinations = array_unique($combinations);
							
							foreach($combinations as $key => $route)
							{
								$arr = array('from_location' => $route[0], 'to_location' => $route[1], 'travel_location_id' => $travel_location_id);
								if(!isin_transitions($transitions, $arr))
								$transitions[] = $arr;
							}
						}
						
					}
					if(!empty($transitions))
					$this->db->insert_batch('possible_travel_locations', $transitions);
				//neatPrint($transitions);
				}

				redirect('settings/travelLocations');

			}
			else
			{
				$this->data['message'] = $this->prepare_message(validation_errors(), 0);
			}

		}


		if(!in_array($param1, array('add', 'edit'))) /* For Listing Records */
			$records	= $this->base_model->run_query("SELECT tl.travel_location_id, tl.status, sl.location as start_location, el.location as end_location FROM ".DBPREFIX."travel_locations tl, ".DBPREFIX."locations sl, ".DBPREFIX."locations el WHERE sl.id=tl.from_loc_id AND el.id=tl.to_loc_id AND sl.status='Active' AND el.status='Active' ORDER BY tl.travel_location_id DESC");

		$this->data['records']					= $records;
		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= "locations";
		$this->data['heading'] 					= (isset($this->phrases["travel location"])) ? $this->phrases["travel location"] : "Travel Location";
		$this->data['sub_heading'] 				= (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
		$this->data['param'] 					= $param1;
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
	/****** TRAVEL LOCATIONS MODULE - END ******/

	/**
	 * This function validate the tax entered by admin.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function checkvalidtax()
	{
		if($this->input->post('service_tax_type') == 'percent')
		{
			if($this->input->post('service_tax') > 100)
			{
				$this->form_validation->set_message('checkvalidtax', getPhrase('Tax Sould be less than 100 percent'));
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
		else
		{
			if($this->input->post('service_tax') > $this->input->post('cost'))
			{
				$this->form_validation->set_message('checkvalidtax', getPhrase('Tax Sould be less than the cost'));
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
	}

	/****** TRAVEL LOCATION COSTS MODULE - START ******/
	function travelLocationCosts($param1 = "list", $param2 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		$this->data['message'] 	= (validation_errors()) ? 
		validation_errors() : $this->session->flashdata('message');
		$records     	= array();
		$where['1']		= "1";
		$title		 	= (isset($this->phrases["travel location costs"])) ? 
							$this->phrases["travel location costs"] : "Travel Location Costs";
		$content 		= 'admin/settings/travel_location_costs/travel_location_costs_list';

		/* Delete Record */
		if($param1 == "delete" && $param2 > 0) {
$this->check_isdemo(base_url() . 'settings/travelLocationCosts');
			if($this->base_model->delete_record('travel_location_costs', array('id' => $param2))) {

				$this->prepare_flashmessage((isset($this->phrases["record deleted successfully"])) ? $this->phrases["record deleted successfully"] : "Record Deleted Successfully.", 0);
				redirect('settings/travelLocationCosts');

			}
		}


		if($param1 == "add" || ($param1 == "edit" && $param2 > 0)) {

			$op_txt1  = (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
			$op_txt2  = (isset($this->phrases["travel location cost"])) ? 
							$this->phrases["travel location cost"] : "Travel Location Cost";
			$title	  = $op_txt1." ".$op_txt2;
			$content  = 'admin/settings/travel_location_costs/add_travel_location_cost';

			if($param1 == "edit") {

				$this->data['update_rec_id'] 	= $param2;
				$where['id']					= $param2;
				$records	= $this->base_model->fetch_records_from('travel_location_costs', $where);
			}
		}


		/* Check for Form Submission */
		if($this->input->post()) {
$this->check_isdemo(base_url() . 'settings/travelLocationCosts');
			// FORM VALIDATIONS
			$this->form_validation->set_rules(
			'travel_location_id', 
			(isset($this->phrases["travel location"])) ? 
			$this->phrases["travel location"] : "Travel Location", 
			'required');
			$this->form_validation->set_rules(
			'vehicle_id', 
			(isset($this->phrases["vehicle"])) ? 
			$this->phrases["vehicle"] : "Vehicle", 
			'required');
			if($this->data['site_theme'] == 'vehicle')
			{
			$this->form_validation->set_rules('cost',(isset($this->phrases["cost"])) ? $this->phrases["cost"] : "Cost",'required|numeric');
			$this->form_validation->set_rules('service_tax',getPhrase('Service Tax'),'required|numeric|callback_checkvalidtax');
			}
			
			$this->form_validation->set_rules('service_tax_type',getPhrase('Service Tax Type'), 'trim|required');
//neatPrint($this->input->post());
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');


			if ($this->form_validation->run() 	== TRUE) {

				$inputdata['travel_location_id'] 	= $this->input->post('travel_location_id');
				$inputdata['vehicle_id'] 			= $this->input->post('vehicle_id');
				if($this->data['site_theme'] == 'vehicle')
				{
				$inputdata['cost'] 					= $this->input->post('cost');
				}
				
				if($this->data['site_theme'] == 'seat')
				{
				$inputdata['fare_details'] 	= json_encode($this->input->post('fare'));
				}
				
				$inputdata['service_tax'] 	= $this->input->post('service_tax');
				$inputdata['service_tax_type'] 	= $this->input->post('service_tax_type');
				
				$inputdata['status'] 				= $this->input->post('status');
//neatPrint($inputdata);
				$table_name = "travel_location_costs";

				if($this->input->post('update_rec_id') > 0) {

					/* Update Record */
					$where['id'] = $this->input->post('update_rec_id');
					if ($this->base_model->update_operation(
					$inputdata, 
					$table_name, 
					$where)) {

						/* Update Vice-versa */
						$travel_loc_rec = $this->base_model->fetch_records_from('travel_locations', array('travel_location_id' => $this->input->post('travel_location_id')));

						if(count($travel_loc_rec) > 0) {

							$travel_loc_cost_rec = $this->base_model->run_query("select id, travel_location_id from ".$this->db->dbprefix('travel_location_costs')." where travel_location_id=(select travel_location_id from ".$this->db->dbprefix('travel_locations')." where from_loc_id=".$travel_loc_rec[0]->to_loc_id." and to_loc_id=".$travel_loc_rec[0]->from_loc_id.") and vehicle_id=".$this->input->post('vehicle_old_id')." and cost=".$this->input->post('old_cost')."");

							if(count($travel_loc_cost_rec) >0) {

								$inputdata['travel_location_id']= $travel_loc_cost_rec[0]->travel_location_id;
								$where['id'] 					= $travel_loc_cost_rec[0]->id;
								$this->base_model->update_operation($inputdata, $table_name, $where);
							}

						}

						$this->prepare_flashmessage(
						(isset($this->phrases["record updated successfully"])) ? 
						$this->phrases["record updated successfully"] : "Record updated successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(isset($this->phrases["unable to update the record"])) ? 
						$this->phrases["unable to update the record"] : "Unable to update the Record." , 1);
					}

				} else {

					$check_for_duplicate = $this->base_model->fetch_records_from(
										'travel_location_costs', 
										 array('travel_location_id' => $this->input->post('travel_location_id'), 
										       'vehicle_id'	 => $this->input->post('vehicle_id')
										      )
										);
					if(count($check_for_duplicate) > 0) {
						$this->prepare_flashmessage(
							(isset($this->phrases["the travel location cost already exists"])) ? $this->phrases["the travel location cost already exists"] : "The Travel Location Cost Already Exists." , 2);
						redirect('settings/travelLocationCosts');
					}


					/* Insert Record */
					if ($this->base_model->insert_operation(
					$inputdata, 
					$table_name)) {

						if($this->input->post('add_vice_versa') == "Yes") {

							//Insert Vice-Versa
							$travel_loc_rec = $this->base_model->fetch_records_from('travel_locations', array('travel_location_id' => $this->input->post('travel_location_id')));

							if(count($travel_loc_rec) > 0) {

								$reversedata['from_loc_id']	= $travel_loc_rec[0]->to_loc_id;
								$reversedata['to_loc_id']	= $travel_loc_rec[0]->from_loc_id;
								$reversedata['distance']	= 0;
								$reversedata['status']		= 'Active';

								$is_reverse_exists = $this->base_model->fetch_records_from('travel_locations', array('status' => 'Active', 'from_loc_id' => $reversedata['from_loc_id'], 'to_loc_id' => $reversedata['to_loc_id']));

								if(count($is_reverse_exists) > 0)
									$insert_id = $is_reverse_exists[0]->travel_location_id;
								else
									$insert_id = $this->base_model->insert_operation_id($reversedata, 'travel_locations');


								if($insert_id > 0) {

									$inputdata['travel_location_id']= $insert_id;
									$this->base_model->insert_operation($inputdata, $table_name);

								}

							}

						}

						$this->prepare_flashmessage(
						(isset($this->phrases["record inserted successfully"])) ? 
						$this->phrases["record inserted successfully"] : "Record inserted successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(isset($this->phrases["unable to insert record"])) ? 
						$this->phrases["unable to insert record"] : "Unable to insert Record." , 1);
					}

				}
				redirect('settings/travelLocationCosts');
			}

		}


		if(!in_array($param1, array('add', 'edit'))) /* For Listing Records */
			$records	= $this->base_model->run_query("SELECT tlc.id, tlc.cost,tlc.start_time,tlc.destination_time,tlc.elapsed_days,tlc.number_of_pricevariations,tlc.fare_details,tlc.travel_location_id,tlc.vehicle_id, tlc.status, tlc.shuttle_no, tlc.season_start, tlc.season_end, tlc.season_type, tl.from_loc_id, tl.to_loc_id, v.name,v.model,tlc.stop_over, sl.location as start_location, el.location as end_location FROM ".DBPREFIX."travel_location_costs tlc, ".DBPREFIX."travel_locations tl, ".DBPREFIX."vehicle v, ".DBPREFIX."locations sl, ".DBPREFIX."locations el WHERE tl.travel_location_id=tlc.travel_location_id AND sl.id=tl.from_loc_id AND el.id=tl.to_loc_id AND sl.status='Active' AND el.status='Active' AND v.id=tlc.vehicle_id AND v.status='Active' AND tl.status='Active' ORDER BY tlc.id DESC");

		$this->data['records']					= $records;
		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= "locations";
		$this->data['heading'] 					= (isset($this->phrases["travel location costs"])) ? $this->phrases["travel location costs"] : "Travel Location Costs";
		$this->data['sub_heading'] 				= (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
		$this->data['param'] 					= $param1;
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
	/****** TRAVEL LOCATIONS MODULE - END ******/



	function languages($param = '', $param1 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		$this->data['message'] 					= (validation_errors()) ? 
		validation_errors() : $this->session->flashdata('message');

		if ($param 	== "delete") {
		$this->check_isdemo(base_url() . 'settings/languages');
			$table_name 						= "languages";
			$where['id'] 						= $param1;
			$cond 								= "id";
			$cond_val 							= $param1;
			if (is_numeric($param1)) {
				if ($this->base_model->delete_record($table_name, $where)) {
					$this->base_model->delete_record('multi_lang', array('lang_id' => $param1));
					$this->prepare_flashmessage(
					(isset($this->phrases["language deleted successfully"])) ? $this->phrases["language deleted successfully"] : "Language Deleted Successfully", 0);
					redirect('settings/languages', 'refresh');
				}
				else {
					$this->prepare_flashmessage(
					(isset($this->phrases["unable to delete language"])) ? $this->phrases["unable to delete language"] : "Unable To Delete Language", 1);
					redirect('settings/languages');
				}
			}
			else {
				$this->prepare_flashmessage((isset($this->phrases["invalid operation"])) ? $this->phrases["invalid operation"] : "Invalid Operation", 1);
				redirect('settings/languages', 'refresh');
			}
		}
		elseif ($param  == "") {

			$this->data['langs'] 		= $this->base_model->fetch_records_from('languages');
			$this->data['css_type'] 	= array("datatable");
			$this->data['active_menu'] 	= "languages";

			$this->data['heading'] 		= (isset($this->phrases["languages"])) ? $this->phrases["languages"] : "Languages";
			$this->data['title']	 	= (isset($this->phrases["languages"])) ? $this->phrases["languages"] : "Languages";
			$this->data['content'] 		= "admin/languages/list_lang";

		}

		$this->_render_page('templates/admin_template', $this->data);
	}


	/*** Function for Add Language ***/
	function add_edit_Lang($param = '', $param1 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		$this->data['message'] 					= (validation_errors()) ? 
		validation_errors() : $this->session->flashdata('message');

		if ($this->input->post()) {
			$this->check_isdemo(base_url() . 'settings/languages');
			$this->form_validation->set_rules(
			'language_name', 
			(isset($this->phrases["language name"])) ? 
			$this->phrases["language name"] : "Language Name", 
			'required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if ($this->form_validation->run() 	== TRUE) {
				$input_data['lang_name'] 		= strtolower(
				$this->input->post('language_name'));
				$input_data['status'] 			= 'Active';

				// check whether the language is already exist or not--
				$languages 						= $this->base_model->run_query(
				"SELECT * FROM " . $this->db->dbprefix('languages') . " 
				WHERE lang_name='" . $this->input->post('language_name') . "'");

				if (count($languages) > 0) {
					$this->prepare_flashmessage((isset($this->phrases["language already exists"])) ? $this->phrases["language already exists"] : "Language already exists", 2);
					redirect('settings/add_edit_Lang');
				}

				if ($this->base_model->insert_operation($input_data, 'languages')) 
				{
					$this->prepare_flashmessage((isset($this->phrases["language added successfully"])) ? $this->phrases["language added successfully"] : "Language Added Successfully", 0);
					redirect('settings/languages');
				}
				else {
					$this->prepare_flashmessage((isset($this->phrases["language not added"])) ? $this->phrases["language not added"] : "Language Not Added", 1);
					redirect('settings/languages');
				}
			}
		}
			

		$this->data['css_type'] 		= array("form","datatable");
		$this->data['active_menu'] 		= "languages";
		$this->data['heading'] 			= (isset($this->phrases["languages"])) ? $this->phrases["languages"] : "Languages";
		$this->data['sub_heading'] 		= (isset($this->phrases["add"])) ? $this->phrases["add"] : "Add";
		$this->data['title'] 			= (isset($this->phrases["add language"])) ? $this->phrases["add language"] : "Add Language";
		$this->data['content'] 			= "admin/languages/add_language";
		$this->_render_page('templates/admin_template', $this->data);
	}

	/*** Function for Add Phrase ***/
	function add_edit_Phrase($param = '', $param1 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		$this->data['message'] 					= (validation_errors()) ? 
		validation_errors() : $this->session->flashdata('message');
		
		if ($this->input->post()) {
			$this->check_isdemo(base_url() . 'settings/languages');
			$this->form_validation->set_rules(
			'phrase_name', 
			(isset($this->phrases["phrase"])) ? 
			$this->phrases["phrase"] : "Phrase", 
			'required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if ($this->form_validation->run() 	== TRUE) {
				$input_data['text'] 			= $this->input->post('phrase_name');
				$phrases 						= $this->base_model->run_query(
				'SELECT text FROM ' . $this->db->dbprefix('phrases') . ' 
				WHERE text = "' . $this->input->post('phrase_name') . '"');

				if (count($phrases) > 0) {
					$this->prepare_flashmessage((isset($this->phrases["phrase already exists"])) ? $this->phrases["phrase already exists"] : "Phrase already exists", 2);
					redirect('settings/add_edit_Phrase');
				}

				$insert_id = $this->base_model->insert_operation_id($input_data, 'phrases');
				if ($insert_id > 0) {
					$this->base_model->insert_operation(
					array('lang_id' 			=> 1,
						'phrase_id' 			=> $insert_id ,
						'text' 					=> $this->input->post('phrase_name')
					), 'multi_lang');
					$this->prepare_flashmessage((isset($this->phrases["phrase added successfully"])) ? $this->phrases["phrase added successfully"] : "Phrase Added Successfully", 0);
					redirect('settings/languages');
				}
				else {
					$this->prepare_flashmessage((isset($this->phrases["phrase not added"])) ? $this->phrases["phrase not added"] : "Phrase Not Added", 1);
					redirect('settings/languages');
				}
			}
		}

		$this->data['css_type'] 		= array("form","datatable");
		$this->data['active_menu'] 		= "languages";
		$this->data['heading'] 			= (isset($this->phrases["languages"])) ? $this->phrases["languages"] : "Languages";
		$this->data['sub_heading'] 		= (isset($this->phrases["add phrase"])) ? $this->phrases["add phrase"] : "Add Phrase";
		$this->data['title']			= (isset($this->phrases["add phrase"])) ? $this->phrases["add phrase"] : "Add Phrase";
		$this->data['content'] 			= "admin/languages/add_phrase";
		$this->_render_page('templates/admin_template', $this->data);
	}

	/*** Function for Edit Phrases ***/
	function editPhrases($param = '', $param1 = 1)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		$this->data['message'] 					= (validation_errors()) ? 
		validation_errors() : $this->session->flashdata('message');
		if ($this->input->post()) {
$this->check_isdemo(base_url() . 'settings/languages');
			// check whether existed phrases are present in table and delete them.
			$existed_phrases 					= $this->base_model->run_query(
			"SELECT * FROM " . $this->db->dbprefix('multi_lang') . " 
			WHERE lang_id='" . $param . "'");

			if (count($existed_phrases) > 0) {
				foreach($existed_phrases as $r) {
					$this->base_model->delete_record(
					$this->db->dbprefix('multi_lang') , 
					array('id' 					=> ($r->id)));
				}
			}

			// inserting new phrases

			$records 							= array();
			$data 								= $this->input->post();
			foreach( $data as $key 				=> $value ) {
				array_push($records, array(
					"lang_id" 					=> $param,
					"phrase_id" 				=> $key,
					"text" 						=> $value
				));
			}

			if ($this->db->insert_batch(
			$this->db->dbprefix('multi_lang') , $records)) {
				$this->prepare_flashmessage((isset($this->phrases["phrases updated successfully"])) ? $this->phrases["phrases updated successfully"] : "Phrases Updated Successfully", 0);
				redirect('settings/languages');
			}
			else {
				$this->prepare_flashmessage((isset($this->phrases["unable to update phrases"])) ? $this->phrases["unable to update phrases"] : "Unable To Update Phrases", 1);
				redirect('settings/languages');
			}
		}

		$language_id 							= $param1;
		$phrases 								= $this->base_model->run_query(
		"SELECT p.id,p.text, ml.text as existing_text FROM " . 
		$this->db->dbprefix('phrases') . " p LEFT OUTER JOIN " . 
		$this->db->dbprefix('multi_lang') . " ml ON ml.phrase_id=p.id 
		AND ml.lang_id=" . $language_id);

		if (count($phrases) 					== 0) {
			$phrases 							= $this->base_model->run_query(
			"SELECT p.*, p.text AS existing_text FROM " . 
			$this->db->dbprefix('phrases') . " p");
		}

		$lang_name 								= $this->db->get_where(
		'languages', array('id' 				=> $language_id))->row()->lang_name;
		$this->data['language_id'] 				= $language_id;
		$this->data['language_name'] 			= $lang_name;
		$this->data['phrases'] 					= $phrases;
		$this->data['css_type'] 				= array("form","datatable");
		$this->data['active_menu'] 				= "languages";
		$this->data['heading'] 					= (isset($this->phrases["languages"])) ? $this->phrases["languages"] : "Languages";
		$this->data['sub_heading'] 				= (isset($this->phrases["edit phrases"])) ? $this->phrases["edit phrases"] : "Edit Phrases";
		$this->data['title'] 					= (isset($this->phrases["edit phrases"])) ? $this->phrases["edit phrases"] : "Edit Phrases";
		$this->data['content'] 					= "admin/languages/phrase_list";
		$this->_render_page('templates/admin_template', $this->data);
	}

	
	/****** Change Language ******/
	function changeLanguage($language_id = null)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		if($language_id > 0) {

			if($this->base_model->update_operation(array('language_id' => $language_id), 'site_settings')) {
				$this->prepare_flashmessage((isset($this->phrases["language changed successfully"])) ? $this->phrases["language changed successfully"] : "Language Changed Successfully.", 0);
				redirect('admin');				
			}
		}
	}


	/****** Read Excel Format and Insert into DB ******/
	function readExcel($param = '')
	{

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}
		
		if(!isset($_FILES['userfile']))
		{
			$this->prepare_flashmessage((isset($this->phrases["wrong operation"])) ? $this->phrases["wrong operation"] : "Wrong operation", 1);
			redirect('settings/'.$param);
		}

		if ($_FILES['userfile']['name']) {
				$f_type							 = explode(".", $_FILES['userfile']['name']);
				$last_in 						 = (count($f_type) - 1);
				if (!in_array($f_type[$last_in], array('xls'))) {
					$this->prepare_flashmessage((isset($this->phrases["invalid file"])) ? $this->phrases["invalid file"] : "Invalid File", 1);
					redirect('settings/'.$param);
				}
			}

		if ($param == "locations" || $param == "travel_locations" || $param == "travel_location_costs") {
		$this->check_isdemo(base_url() .$this->data['site_theme'].'/'. 'settings/locations/list');
		include (FCPATH .$this->data['site_theme'].'/'. '/assets/excelassets/PHPExcel/IOFactory.php');

		$file 									= $_FILES['userfile']['tmp_name'];
		$inputFileName 							= $file;
		$objReader 								= new PHPExcel_Reader_Excel5();
		$objPHPExcel 							= $objReader->load($inputFileName);
		echo '<hr />';
		$sheetData 								= $objPHPExcel->getActiveSheet()->toArray(
		null, true, true, true
		);
		$i 										= 0;
		$j 										= 0;
		$data 									= array();
		$valid 									= 1;

		

		foreach($sheetData as $r) {
			if ($i++ != 0) {
				if ($valid == 1) {
					if ($param == 'locations') {
						$data[$j++] 					= array(
							'id'			   			=> $r['A'],
							'location' 					=> $r['B'],
							'location_visibility_type'	=> $r['C'],
							'is_airport' 				=> $r['D'],
							'status' 					=> $r['E'],
						);
					}
					elseif ($param == 'travel_locations') {
						$data[$j++] 			= array(
							'travel_location_id' 	=> $r['A'],
							'from_loc_id' 			=> $r['B'],
							'to_loc_id' 			=> $r['C'],
							'distance' 				=> $r['D'],
							'status' 				=> $r['E'],
						);
					}
					elseif ($param == 'travel_location_costs') {
						$data[$j++] 			= array(
							'id' 					=> $r['A'],
							'travel_location_id' 	=> $r['B'],
							'vehicle_id' 			=> $r['C'],
							'cost' 					=> $r['D'],
							'status' 				=> $r['E'],
						);
					}
				}
				else {
					break;
				}
			}
		}

		if ($valid == 1) {

			$this->db->insert_batch($this->db->dbprefix($param) , $data);
		}
		else {
			$msg = (isset($this->phrases["invalid data in excel"])) ? 
			$this->phrases["invalid data in excel"] : "Invalid data in Excel.";
			$this->prepare_flashmessage($msg, 1);
			redirect('settings/' . camelize($param), 'refresh');
		}

		if ($this->db->affected_rows() > 0) {
			$msg = (isset($this->phrases["records inserted successfully"])) ? 
			$this->phrases["records inserted successfully"] : "Records inserted successfully.";
			$this->prepare_flashmessage($msg, 0);
		}
		else {
			$msg = (isset($this->phrases["records not inserted"])) ? 
			$this->phrases["records not inserted"] : "Records not inserted.";
			$this->prepare_flashmessage($msg, 1);
		}

		redirect('settings/'.camelize($param), 'refresh');

		}
		else {

			$this->prepare_flashmessage((isset($this->phrases["incorrect operation"])) ? 
			$this->phrases["incorrect operation"] : "Incorrect Operation.",2);
			redirect('settings/'.camelize($param));
		}
	}



	/****** Add Phrases By Excel ******/
	function addPhrasesByExcel()
	{

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}
$this->data['message'] 					= (validation_errors()) ? 
		validation_errors() : $this->session->flashdata('message');

		if (!empty($_FILES['userfile']['name'])) {
$this->check_isdemo(base_url() . 'settings/languages');
			$f_type							 = explode(".", $_FILES['userfile']['name']);
			$last_in 						 = (count($f_type) - 1);

			if (!in_array($f_type[$last_in], array('xls'))) {

				$this->prepare_flashmessage((isset($this->phrases["please upload only .xls file"])) ? $this->phrases["please upload only .xls file"] : "Please upload only .xls file.", 1);
				redirect('settings/addPhrasesByExcel');
			}


			include (FCPATH .$this->data['site_theme'].'/'. '/assets/excelassets/PHPExcel/IOFactory.php');

			$file 									= $_FILES['userfile']['tmp_name'];
			$inputFileName 							= $file;
			$objReader 								= new PHPExcel_Reader_Excel5();
			$objPHPExcel 							= $objReader->load($inputFileName);
			echo '<hr />';
			$sheetData 								= $objPHPExcel->getActiveSheet()->toArray(
			null, true, true, true
			);
			$i 										= 0;
			$data 									= array();
			$valid 									= 1;


			foreach($sheetData as $r) {

				if ($i++ != 0) {

					if ($valid == 1) {

						$data	= array(
										'id'		=> $r['A'],
										'text' 		=> $r['B'],
									);

						$is_phrase_exist = $this->base_model->run_query(
						'SELECT text FROM ' . $this->db->dbprefix('phrases') . ' 
						WHERE text = "' . $data['text'] . '"');

						if(count($is_phrase_exist) == 0) {

							$phrase_id = $this->base_model->insert_operation_id($data, 'phrases');

							if ($phrase_id > 0) {

								$this->base_model->insert_operation(
								array('lang_id' 		=> 1,
									'phrase_id' 		=> $phrase_id ,
									'text' 				=> $data['text']
								), 'multi_lang');

							}
						}

					} else {

						break;
					}
				}
			}

			if ($valid != 1) {

				$msg = (isset($this->phrases["invalid data in excel"])) ? 
				$this->phrases["invalid data in excel"] : "Invalid data in Excel.";
				$this->prepare_flashmessage($msg, 1);
				redirect('settings/addPhrasesByExcel');
			}

			if ($this->db->affected_rows() > 0) {
				$msg = (isset($this->phrases["records inserted successfully"])) ? 
				$this->phrases["records inserted successfully"] : "Records inserted successfully.";
				$this->prepare_flashmessage($msg, 0);
			}
			else {
				$msg = (isset($this->phrases["records not inserted"])) ? 
				$this->phrases["records not inserted"] : "Records not inserted.";
				$this->prepare_flashmessage($msg, 1);
			}

			redirect('settings/languages');
		}


		$this->data['active_menu'] 				= "languages";
		$this->data['heading'] 					= (isset($this->phrases["languages"])) ? $this->phrases["languages"] : "Languages";
		$this->data['sub_heading'] 				= (isset($this->phrases["add phrases by excel"])) ? $this->phrases["add phrases by excel"] : "Add Phrases By Excel";
		$this->data['title'] 					= (isset($this->phrases["add phrases by excel"])) ? $this->phrases["add phrases by excel"] : "Add Phrases By Excel";
		$this->data['content'] 					= "admin/languages/add_phrases_by_excel";
		$this->_render_page('templates/admin_template', $this->data);
	}
	
	

	//Dynamic Pages
	function pages($param = '', $param1 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		$this->data['message'] 					= (validation_errors()) ? 
		validation_errors() : $this->session->flashdata('message');

		if ($param 	== "delete") {		
			$this->check_isdemo(base_url() . 'settings/pages');
			if ($this->base_model->delete_record('pages', array('page_id' => $param1))) {
				$this->prepare_flashmessage(
				(isset($this->phrases["page deleted successfully"])) ? $this->phrases["page deleted successfully"] : "Page Deleted Successfully", 0);
				redirect('settings/pages');
			}
			else {
				$this->prepare_flashmessage(
				(isset($this->phrases["unable to delete page"])) ? $this->phrases["unable to delete page"] : "Unable To Delete Page", 1);
				redirect('settings/pages');
			}
		}
		elseif ($param  == "") {
			$this->data['pages'] 		= $this->base_model->fetch_records_from('pages');
			$this->data['css_type'] 	= array("datatable");
			$this->data['active_menu'] 	= "pages";

			$this->data['heading'] 		= (isset($this->phrases["pages"])) ? $this->phrases["pages"] : "Pages";
			$this->data['title']	 	= (isset($this->phrases["pages"])) ? $this->phrases["pages"] : "Pages";
			$this->data['content'] 		= "admin/pages/list_pages";

		}

		$this->_render_page('templates/admin_template', $this->data);
	}
	
	function add_edit_page($param = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		$this->data['message'] 	= (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		
		if ($this->input->post()) {
			$this->check_isdemo(base_url() . 'settings/pages');
			$this->form_validation->set_rules('page_title', (isset($this->phrases["title"])) ? $this->phrases["title"] : "Title", 'required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if ($this->form_validation->run() 	== TRUE) {
				$input_data['page_title'] 	= $this->input->post('page_title');
				$input_data['page_content'] 	= $this->input->post('page_content');
				$input_data['page_status'] 	= $this->input->post('page_status');
				$input_data['page_link_display'] 	= $this->input->post('page_link_display');				
				$message = '';
				
				if ($this->input->post('update_rec_id') > 0) {
					$input_data['page_updated'] 	= date('Y-m-d H:i:s');
					$this->base_model->update_operation($input_data, 'pages', array('page_id' => $this->input->post('update_rec_id')));
					$message = (isset($this->phrases["page updated successfully"])) ? $this->phrases["page updated successfully"] : "Page Updated Successfully";
				}
				else {					
					$this->base_model->insert_operation($input_data, 'pages');
					$message = (isset($this->phrases["page added successfully"])) ? $this->phrases["page added successfully"] : "Page Added Successfully";					
				}
				$this->prepare_flashmessage($message, 0);
				redirect('settings/pages');
			}
		}
		
		if(empty($param))
		{
			$this->data['page_rec'] = array();
		}
		else
		{
			$this->data['page_rec'] = $this->base_model->fetch_records_from('pages', array('page_id' => $param));
		}

		$this->data['css_type'] 		= array("form","datatable");
		$this->data['active_menu'] 		= "pages";
		$this->data['heading'] 			= (isset($this->phrases["pages"])) ? $this->phrases["pages"] : "Pages";
		$this->data['sub_heading'] 		= (isset($this->phrases["add page"])) ? $this->phrases["add page"] : "Add Page";
		$this->data['title']			= (isset($this->phrases["add page"])) ? $this->phrases["add page"] : "Add Page";
		$this->data['content'] 			= "admin/pages/add_edit_page";
		$this->_render_page('templates/admin_template', $this->data);
	}
	
	function getViapoints()
	{
		if($this->input->is_ajax_request())
		{
			$start_id = $this->input->post('start_id');
			$tlid = $this->input->post('tlid');			
			$time = $this->input->post('time');
			$cas = $this->input->post('cas');			
			$record = $this->base_model->getViarecord(array('condition' => array('travel_location_id' => $tlid, 'type' => $cas)));
			
			$times = $ids = $is_boardings = array();
			foreach($record as $r)
			{
				$times[] = $r->arrival_time;
				$ids[] = $r->location_id;
			}
			
			$parts = ($time != '') ? explode(':', $time) : array();
			$times_str = '';
			
			for($i = 1; $i < 24; $i++)
			{
				for($m = 0; $m < 60; $m++)
				{
					$str = ($i < 12) ? str_pad($i,2,0,STR_PAD_LEFT).':'.str_pad($m,2,0,STR_PAD_LEFT).': AM' : str_pad(($i-12),2,0,STR_PAD_LEFT).':'.str_pad($m,2,0,STR_PAD_LEFT).': PM';
					if(in_array($str, $times))
					$times_str .= '<option value="'.$str.'" selected>'.$str.'</option>';
					else
					$times_str .= '<option value="'.$str.'">'.$str.'</option>';
				}
			}
			
			$result = $this->base_model->getViapoints(array('condition' => array('parent_id' => $start_id), 'order_by' => 'location ASC'));
			//echo $this->db->last_query();die();
			if(count($result) > 0)
			{
				echo '<tr><td>&nbsp</td><td>Location</td><td>Time</td><td>Order</td><td>Is Boarding</td></tr>';
				$index = 0;
				foreach($result as $row)
				{
					$check = (in_array($row->id, $ids)) ? ' checked' : '';
					echo '<tr><td><input type="checkbox" name="via_'.$cas.'['.$row->id.']" value="'.$row->id.'"'.$check.'></td><td>'.$row->location.'</td><td><select class="chzn-select" name="via_time_'.$cas.'['.$row->id.']"  id="via_time_'.$index.'">'.$times_str.'</select></td><td><input type="number" name="via_order_'.$cas.'['.$row->id.']" value="'.$index++.'"></td><td><select name="via_is_boarding_'.$cas.'['.$row->id.']"><option value="Yes">Yes</option><option value="No">No</option></select></td></tr>';
				}
			}
			else
			{
				echo '<tr><td>No Records Found</td></tr>';
			}
		}
	}
	
	function fetch_fares()
	{
		if($this->input->is_ajax_request())
		{
			$vehicle_details = $this->base_model->fetch_records_from('vehicle', array('id' => $this->input->post('vehicle_id')));
			$html = '';
			if(count($vehicle_details) > 0)
			{
				$seat_rows = $vehicle_details[0]->number_of_pricevariations;
				$html = '';
				$fares = [];
				$seats = [];
				if($seat_rows != '')
				{
					for($i = 1; $i <= $seat_rows; $i++)
					{
						$fares[$i] = 0;
						$seats[$i] = '';
					}
				}
				if($seat_rows != '')
				{
					$options = '';
					$rows = $vehicle_details[0]->seat_rows;
					$columns = $vehicle_details[0]->seat_columns;
					
					$html = '<table>';
					$html .= '<tr><td width="30%">Row</td><td>Fare</td><td>Seats</td></tr>';
					for($i = 1; $i <= $seat_rows; $i++)
					{
						$options = '<option value="">Please select seat</option>';
						for($r = 1; $r <= $rows; $r++)
						{
							for($c = 1; $c <= $columns; $c++)
							{
								$seat_name = 'R'.$r.'C'.$c;
								
								if(isset($seats[$i]) && $seats[$i] != '' && in_array($seat_name, implode(',', $seats[$i])))
								{
								$options .= '<option value="'.$seat_name.'" selected>'.$seat_name.'</option>';	
								}
								else
								{
								$options .= '<option value="'.$seat_name.'">'.$seat_name.'</option>';
								}
							}
						}
					$html .= '<tr><td>F'.$i.'</td><td><input type="text" name="fare[fare]['.$i.']" id="fare[fare]['.$i.']" value="'.$fares[$i].'"></td><td><select name="fare[seats]['.$i.'][]" id="fare[seats]['.$i.']" class="chzn-select" multiple="multiple">'.$options.'</select></td></tr>';
					}
					$html .='</table>					
					<script type="text/javascript">
						$(function() {
							$(".chzn-select").chosen();
						});
						$(".chzn-select").trigger("chosen:updated");
					</script>';
				}
			}
			echo $html;
		}
	}
	
	/**
	* This function will fecilitate to choose travel location and vehicle
	*
	*/
	function choose_travel_location()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		$this->data['message'] 	= (validation_errors()) ? 
		validation_errors() : $this->session->flashdata('message');
		$records     	= array();
		$where['1']		= "1";
		$title		 	= (isset($this->phrases["travel location costs"])) ? 
							$this->phrases["travel location costs"] : "Travel Location Costs";
		$content 		= 'admin/settings/travel_location_costs/choose_travel_location';

		/* Check for Form Submission */
		if($this->input->post()) {
		$this->check_isdemo(base_url() . 'settings/travelLocationCosts');
			// FORM VALIDATIONS
			$this->form_validation->set_rules('travel_location_id',getPhrase('travel location'),'trim|required');
			$this->form_validation->set_rules(
			'vehicle_id',getPhrase('vehicle'),'trim|required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			if ($this->form_validation->run() 	== TRUE) {
				$travel_location_id = $this->input->post('travel_location_id');
				$vehicle_id = $this->input->post('vehicle_id');
				$this->prepare_flashmessage(getPhrase('Please enter fare details', 0));
				redirect(base_url() . 'settings/add_travel_location_costs/'.$travel_location_id.'/'.$vehicle_id);
			}

		}

		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= "locations";
		$this->data['heading'] 					= (isset($this->phrases["travel location costs"])) ? $this->phrases["travel location costs"] : "Travel Location Costs";
		$this->data['sub_heading'] 				= getPhrase('Choose Travel Location');
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
	
	
	/**
	* This function will fecilitate to choose number of price variations going to create
	*
	*/
	function choose_price_variations()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		$this->data['message'] 	= (validation_errors()) ? 
		validation_errors() : $this->session->flashdata('message');
		$records     	= array();
		$where['1']		= "1";
		$title		 	= (isset($this->phrases["travel location costs"])) ? 
							$this->phrases["travel location costs"] : "Travel Location Costs";
		$content 		= 'admin/settings/travel_location_costs/choose_price_variations';

		/* Check for Form Submission */
		if($this->input->post()) {
		$this->check_isdemo(base_url() . 'settings/travelLocationCosts');
			// FORM VALIDATIONS
			$this->form_validation->set_rules('price_variations',getPhrase('Price Variations'),'trim|required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			if ($this->form_validation->run() 	== TRUE) {
				$price_variations = $this->input->post('price_variations');
				$this->prepare_flashmessage(getPhrase('Please enter fare details', 0));
				redirect(base_url() . 'settings/add_travel_location_costs');
			}

		}

		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= "locations";
		$this->data['heading'] 					= (isset($this->phrases["travel location costs"])) ? $this->phrases["travel location costs"] : "Travel Location Costs";
		$this->data['sub_heading'] 				= getPhrase('Choose Travel Location');
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}

	/**
	* This function will check the availability of the vehicle for a particular route
	* @param array $_POST
	* @return bool
	*/
	function checkavailability()
	{
		$travel_location_id = $this->input->post('travel_location_id');
		if($travel_location_id == '') $travel_location_id = 0;
		$vehicle_id = $this->input->post('vehicle_id');
		if($vehicle_id == '') $vehicle_id = 0;
		$start_time = $this->input->post('start_time');
		$destination = $this->input->post('destination_time');
		$elapsed_days = $this->input->post('elapsed_days');
		//neatPrint($_POST);
		if($elapsed_days == '') $elapsed_days = 0;
		if($start_time != '')
		{
			$parts = explode(':', $start_time);
			$time = trim($parts[0]).':'.trim($parts[1]).' '.trim($parts[2]);	
		}
		else
		$time = date('h').':'.date('i').' '.date('a');
		$pick_date_time = date('m/d/Y ').$time;
		
		if($destination != '')
		{
			$parts = explode(':', $destination);
			$time = trim($parts[0]).':'.trim($parts[1]).' '.trim($parts[2]);
		}
		else
		$time = date('h').':'.date('i').' '.date('A');
		$destination_time = date('m/d/Y',strtotime($pick_date_time.' +'.$elapsed_days.' days')).' '.$time;
		
		if($destination_time > $pick_date_time )
		{
			$pick_date_time_parts = explode(' ', $pick_date_time);
			$destination_time_parts = explode(' ', $destination_time);
			$check = $this->db->query('SELECT * FROM '.$this->db->dbprefix('travel_location_costs').' WHERE travel_location_id = '.$travel_location_id.' AND vehicle_id = '.$vehicle_id.' AND start_time = "'.$start_time.'" AND destination_time = "'.$destination.'"')->result();
			if(count($check) > 0)
			{
				$update_rec_id = $this->input->post('update_rec_id');
				if($update_rec_id == '')
				{
				$this->form_validation->set_message('checkavailability', getPhrase('This vehicle is not available in this time'));
				return FALSE;
				}
				else
				{
					return TRUE;
				}
			}
			else
			{
			return TRUE;
			}
		}
		else
		{
			$this->form_validation->set_message('checkavailability', getPhrase('Destination time should be greater than start time'));
			return FALSE;
		}
	}
	
	/**
	* This function will check the number of seats available for vehicle
	* @param array $_POST
	* @return bool
	*/
	function checkseats()
	{		
		$vehicle_id = $this->input->post('vehicle_id');
		$values = $_POST['fare'];
		$variation = isset($values['variation']) ? $values['variation'] : array();
		$details = $this->base_model->fetch_records_from('vehicle', array('id' => $vehicle_id));
		if(count($details) > 0)
		{
			$seats = $details[0]->passenger_capacity;
			$seats_selected = 0;
			foreach($variation as $key => $val)
			{
				$this->form_validation->set_rules('fare[seats]['.$key.']',getPhrase('Seats'),'trim|required|numeric');
				$seat = $values['seats'][$key];
				$seat_c = $values['seats_c'][$key];
				$seat_i = $values['seats_i'][$key];
				if($seat == '' || $seat_c == '' ||  $seat_i == '') 
				{
					$seat = $seat_c = $seat_i = 0;	
				}
				$seats_selected += ($seat+$seat_c+$seat_i);
			}
			if($seats_selected > $seats)
			{
				$this->form_validation->set_message('checkseats', getPhrase('You can only select '.$seats.' seats'));
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
		else
		{
			$this->form_validation->set_message('checkseats', getPhrase('Vehicle not available'));
			return FALSE;
		}
	}
	
	function checkfareseat()
	{
		$values = $_POST['fare'];
		//neatPrint($values);
		$fare = $values['fare'];
		$seats = $values['seats'];
		$variation = isset($values['variation']) ? $values['variation'] : array();
		$agent_commission = $values['agent_commission'];
		
		$fare_err = $seats_err = $agent_commission_err = FALSE;
		
		if(count($variation) > 0)
		{
			foreach($variation as $key => $val)
			{
				if($fare[$key] == '') $fare_err = TRUE;
				if($seats[$key] == '') $seats_err = TRUE;
				if($agent_commission[$key] == '') $agent_commission_err = TRUE;
				if($fare[$key] == 0 && $seats[$key] == 0 && $agent_commission[$key] == 0)
					$seats_err = TRUE;
			}
		}
		
		if($fare_err == TRUE || $seats_err == TRUE || $agent_commission_err == TRUE)
		{
			if($fare_err == TRUE)
			{
				$this->form_validation->set_message('checkfareseat', getPhrase('Fare is required field'));
				return FALSE;
			}
			elseif($seats_err == TRUE)
			{
				$this->form_validation->set_message('checkfareseat', getPhrase('Seat is required field'));
				return FALSE;
			}
			elseif($agent_commission_err == TRUE)
			{
				$this->form_validation->set_message('checkfareseat', getPhrase('Commission is required field'));
				return FALSE;
			}
		}
		else
		{
			return TRUE;
		}
	}
	
	function checkseats_special()
	{		
		$vehicle_id = $this->input->post('vehicle_id');
		$values = $_POST['fare_details_special'];
		$variation = isset($values['variation']) ? $values['variation'] : array();
		$details = $this->base_model->fetch_records_from('vehicle', array('id' => $vehicle_id));
		if(count($details) > 0)
		{
			$seats = $details[0]->passenger_capacity;
			$seats_selected = 0;
			foreach($variation as $key => $val)
			{
				$this->form_validation->set_rules('fare_details_special[seats]['.$key.']',getPhrase('Seats'),'trim|required|numeric');
				$seat = $values['seats'][$key];
				$seat_c = $values['seats_c'][$key];
				$seat_i = $values['seats_i'][$key];
				if($seat == '' || $seat_c == '' ||  $seat_i == '') 
				{
					$seat = $seat_c = $seat_i = 0;	
				}
				$seats_selected += ($seat+$seat_c+$seat_i);
			}
			if($seats_selected > $seats)
			{
				$this->form_validation->set_message('checkseats_special', getPhrase('You can only select '.$seats.' seats'));
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
		else
		{
			$this->form_validation->set_message('checkseats_special', getPhrase('Vehicle not available'));
			return FALSE;
		}
	}
	
	function checkfareseat_special()
	{
		$values = $_POST['fare_details_special'];
		//neatPrint($values);
		$fare = $values['fare'];
		$seats = $values['seats'];
		$variation = isset($values['variation']) ? $values['variation'] : array();
		$agent_commission = $values['agent_commission'];
		
		$fare_err = $seats_err = $agent_commission_err = FALSE;
		
		if(count($variation) > 0)
		{
			foreach($variation as $key => $val)
			{
				if($fare[$key] == '') $fare_err = TRUE;
				if($seats[$key] == '') $seats_err = TRUE;
				if($agent_commission[$key] == '') $agent_commission_err = TRUE;
				if($fare[$key] == 0 && $seats[$key] == 0 && $agent_commission[$key] == 0)
					$seats_err = TRUE;
			}
		}
		
		if($fare_err == TRUE || $seats_err == TRUE || $agent_commission_err == TRUE)
		{
			if($fare_err == TRUE)
			{
				$this->form_validation->set_message('fare_details_special', getPhrase('Fare is required field'));
				return FALSE;
			}
			elseif($seats_err == TRUE)
			{
				$this->form_validation->set_message('fare_details_special', getPhrase('Seat is required field'));
				return FALSE;
			}
			elseif($agent_commission_err == TRUE)
			{
				$this->form_validation->set_message('fare_details_special', getPhrase('Commission is required field'));
				return FALSE;
			}
		}
		else
		{
			return TRUE;
		}
	}
	
	function checkunique()
	{
		/*
		$shuttle_no = $this->input->post('shuttle_no');
		$check = $this->base_model->fetch_records_from('travel_location_costs', array('shuttle_no' => $shuttle_no));
		$update_rec_id = $this->input->post('update_rec_id');
		if($update_rec_id != '' && count($check) > 0)
		{
			if($update_rec_id != $check[0]->id)
			{
				$this->form_validation->set_message('checkunique', getPhrase('Shuttle number should be unique'));
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
		else
		{
			if($update_rec_id == '' && count($check) > 0)
			{
				$this->form_validation->set_message('checkunique', getPhrase('Shuttle number should be unique'));
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
		*/
		return TRUE;
	}
	
	/**
	* This function will facilitate to enter fare details of a particular travel location for a vehicle
	* @param int $price_variations
	* @return void
	*/
	/*
	function add_travel_location_costs($price_variations = '', $edit_id = '')
	{
		$this->data['message'] = $this->session->flashdata('message');
		if(isset($_POST['buttSubmit']))
		{
			$this->check_isdemo(base_url() . 'settings/travelLocationCosts');
			// FORM VALIDATIONS
			$this->form_validation->set_rules('travel_location_id',getPhrase('Travel Location'),'trim|required|callback_checkavailability');
			$this->form_validation->set_rules('vehicle_id',getPhrase('Vehicle'),'trim|required|callback_checkseats');
			
			$this->form_validation->set_rules('shuttle_no',getPhrase('Shuttle Number'),'trim|required');			
			$this->form_validation->set_rules('start_time',getPhrase('Start Time'),'trim|required');
			$this->form_validation->set_rules('destination_time',getPhrase('Destination Time'),'trim|required');
			$price_variations = $this->input->post('price_variations');
			for($i = 1; $i <= $price_variations; $i++)
			{
				$this->form_validation->set_rules('fare[fare]['.$i.']',getPhrase('Fare'),'trim|callback_checkfareseat');
				$this->form_validation->set_rules('fare[seats]['.$i.']',getPhrase('Seats'),'trim|callback_checkfareseat');
			}
			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			if ($this->form_validation->run() 	== TRUE) {
				$inputdata = array();
				$inputdata['travel_location_id'] = $this->input->post('travel_location_id');
				$inputdata['vehicle_id'] = $this->input->post('vehicle_id');
				
				$inputdata['start_time'] = $this->input->post('start_time');
				$inputdata['destination_time'] = $this->input->post('destination_time');
				$inputdata['elapsed_days'] = $this->input->post('elapsed_days');
				$inputdata['number_of_pricevariations'] = $this->input->post('price_variations');
				$inputdata['shuttle_no'] = $this->input->post('shuttle_no');
				$inputdata['season'] = $this->input->post('season');
				
				$inputdata['service_tax'] = $this->input->post('service_tax');
				$inputdata['service_tax_type'] = $this->input->post('service_tax_type');
				$inputdata['status'] = $this->input->post('status');
				$inputdata['fare_details'] = json_encode($this->input->post('fare'));
				$update_rec_id = $this->input->post('update_rec_id');
				if($update_rec_id != '')
				{
						//Update Operation
						$this->base_model->update_operation($inputdata, 'travel_location_costs', array('id' => $update_rec_id));
						$message = getPhrase('Record Updated Successfully');
				}
				else
				{
					//Insert OPERATIONS
					$this->base_model->insert_operation($inputdata, 'travel_location_costs');
					$message = getPhrase('Record Inserted Successfully');
				}
				redirect(base_url().'settings/travelLocationCosts/list');
			}
			else
			{
				$this->prepare_message(validation_errors(), 1);
			}			
		}
		$this->data['update_rec_id'] 	= $edit_id;
		$records	= $this->base_model->fetch_records_from('travel_location_costs', array('id' => $edit_id));
		
		$this->data['records'] = $records;
		$title = getPhrase('Add Travel Location Costs');
		if($edit_id != '') $title = getPhrase('Edit Travel Location Costs');
		$content = 'admin/settings/travel_location_costs/add_travel_location_costs';
		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= "locations";
		$this->data['heading'] 					= (isset($this->phrases["travel location costs"])) ? $this->phrases["travel location costs"] : "Travel Location Costs";
		$this->data['sub_heading'] 				= $title;
		$this->data['price_variations'] 		= $price_variations;
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
	*/
	/**
	* This function will fecilitate the user to add/edit/delete/list the price variations
	* @return void
	*/
	function price_variations($param1 = '', $param2 = '')
	{
		
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		$this->data['message'] = $this->session->flashdata('message');
		$records    = array();
		$where['1']	= "1";
		$title		= getPhrase('Price Variations');
		$content = 'admin/settings/price_variations/vehicle_variations_list';

		/* Delete Record */
		if($param1 == "delete" && $param2 > 0) {
			$this->check_isdemo(base_url() . 'settings/price_variations');
			if($this->base_model->delete_record('price_variations', array('variation_id' => $param2))) {

				$this->prepare_flashmessage((isset($this->phrases["record deleted successfully"])) ? $this->phrases["record deleted successfully"] : "Record Deleted Successfully.", 0);
				redirect('settings/price_variations');

			}
		}


		if($param1 == "add" || ($param1 == "edit" && $param2 > 0)) {

			$op_txt1  = (isset($this->phrases[$param1])) ? 
							$this->phrases[$param1] : ucwords($param1);
			$op_txt2  = getPhrase('Add Variation');
			$title	  = $op_txt1." ".$op_txt2;
			$content  = 'admin/settings/price_variations/add_price_variation';

			if($param1 == "edit") {

				$this->data['update_rec_id'] = $param2;
				$where['variation_id']				 = $param2;
			}
		}


		/* Check for Form Submission */
		if($this->input->post()) {
$this->check_isdemo(base_url() . 'settings/vehicleFeatures');
			
			$table_name = "price_variations";
			// FORM VALIDATIONS
			if($this->input->post('update_rec_id') > 0) {
				$this->form_validation->set_rules('variation_title', (isset($this->phrases["title"])) ? $this->phrases["title"] : "Title",'trim|required');
			} else {
			$this->form_validation->set_rules(
			'variation_title', 
			(isset($this->phrases["title"])) ? 
			$this->phrases["title"] : "Title" , 
			'trim|required|is_unique['.$this->db->dbprefix($table_name).'.variation_title]');
			}

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');


			if ($this->form_validation->run() 	== TRUE) {

				$inputdata['variation_title'] 		= $this->input->post('variation_title');
				$inputdata['variation_status'] 		= $this->input->post('variation_status');

				

				if($this->input->post('update_rec_id') > 0) {

					/* Update Record */
					$where['variation_id'] = $this->input->post('update_rec_id');
					if ($this->base_model->update_operation(
					$inputdata, 
					$table_name, 
					$where)) {

						$this->prepare_flashmessage(
						(isset($this->phrases["record updated successfully"])) ? 
						$this->phrases["record updated successfully"] : "Record updated successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(isset($this->phrases["unable to update the record"])) ? 
						$this->phrases["unable to update the record"] : "Unable to update the Record." , 1);
					}

				} else {

					/* Insert Record */
					if ($this->base_model->insert_operation(
					$inputdata, 
					$table_name)) {

						$this->prepare_flashmessage(
						(isset($this->phrases["record inserted successfully"])) ? 
						$this->phrases["record inserted successfully"] : "Record inserted successfully.", 0);

					} else {

						$this->prepare_flashmessage(
						(isset($this->phrases["unable to insert record"])) ? 
						$this->phrases["unable to insert record"] : "Unable to insert Record." , 1);
					}

				}

				redirect('settings/price_variations');

			}

		}


		if(!in_array($param1, array('add')))  /* For Listing and Editing Record(s) */
		{
			$records	= $this->base_model->fetch_records_from('price_variations', $where, '', 'variation_title', 'ASC');
			if($param1 == 'edit' && empty($records))
			{
				$this->prepare_flashmessage('Wrong operation' , 1);
				redirect('settings/price_variations/list');
			}
		}

		$this->data['records']					= $records;
		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= "vehicle_settings";
		$this->data['heading'] 					= '<a href="'.base_url().'settings/price_variations/list">'.getPhrase('Price Variations').'</a>';
		$this->data['sub_heading'] 				= (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
		$this->data['param'] 					= $param1;
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
	
	function add_travel_location_costs($edit_id = '', $special_id = '', $action = '')
	{
		$this->data['message'] = $this->session->flashdata('message');
		if(!empty($edit_id) && !empty($action) && !empty($special_id))
		{
			if($action == 'del')
			{
				$this->base_model->delete_record('digi_travel_location_costs_special', array('id' => $special_id));
				$this->prepare_flashmessage('Record deleted successfully', 0);
				redirect(base_url().'settings/add_travel_location_costs/'.$edit_id);
			}
		}
		if(isset($_POST['buttSubmit']))
		{
			$this->check_isdemo(base_url() . 'settings/travelLocationCosts');
			// FORM VALIDATIONS
			//$this->form_validation->set_rules('travel_location_id',getPhrase('Travel Location'),'trim|required|callback_checkavailability');
			$this->form_validation->set_rules('travel_location_id',getPhrase('Travel Location'),'trim|required');
			$this->form_validation->set_rules('stop_over',getPhrase('Stop Over'),'trim|required|numeric');
			$this->form_validation->set_rules('vehicle_id',getPhrase('Vehicle'),'trim|required|callback_checkseats');
			
			$this->form_validation->set_rules('season_start',getPhrase('Season Start Date'),'trim|required');
			$this->form_validation->set_rules('season_end',getPhrase('Season End Date'),'trim|required');
			
			$this->form_validation->set_rules('shuttle_no',getPhrase('Shuttle Number'),'trim|required');
			$this->form_validation->set_rules('driver_id',getPhrase('Driver'),'trim|required');
			$this->form_validation->set_rules('start_time',getPhrase('Departure Time'),'trim|required');
			$this->form_validation->set_rules('destination_time',getPhrase('Arrival Time'),'trim|required');
			//neatPrint($_POST);
			$price_variations = $this->input->post('fare');
			$variants = isset($price_variations['variation']) ? count($price_variations['variation']) : 0;
			if(isset($price_variations['variation']) && count($price_variations['variation']) > 0)
			{
				foreach($price_variations['variation'] as $key => $val)
				{
					$this->form_validation->set_rules('fare[fare]['.$key.']',getPhrase('Fare'),'trim|callback_checkfareseat');
					$this->form_validation->set_rules('fare[seats]['.$key.']',getPhrase('Seats'),'trim|callback_checkfareseat');
				}
			}
			
			/*
			if(isset($_POST['special_fare']) && $_POST['special_fare'] == 'yes')
			{
				$price_variations = $this->input->post('fare_details_special');
				$variants = isset($price_variations['variation']) ? count($price_variations['variation']) : 0;
				if(isset($price_variations['variation']) && count($price_variations['variation']) > 0)
				{
					foreach($price_variations['variation'] as $key => $val)
					{
						$this->form_validation->set_rules('fare_details_special[fare]['.$key.']',getPhrase('Fare'),'trim|callback_checkfareseat_special');
						$this->form_validation->set_rules('fare_details_special[seats]['.$key.']',getPhrase('Seats'),'trim|callback_checkfareseat_special');
					}
				}
			}
			*/
			//neatPrint($_POST);			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			if ($this->form_validation->run() 	== TRUE) {
				$inputdata = array();
				$inputdata['travel_location_id'] = $this->input->post('travel_location_id');
				$inputdata['stop_over'] = $this->input->post('stop_over');
				$inputdata['vehicle_id'] = $this->input->post('vehicle_id');
				
				$inputdata['start_time'] = $this->input->post('start_time');
				$parts = $this->input->post('start_time_zone');
				$parts = explode('_', $parts);
				$inputdata['start_time_zone'] = $parts[0];
				$inputdata['start_time_zone_id'] = $parts[1];
				$inputdata['destination_time'] = $this->input->post('destination_time');
				
				$parts = $this->input->post('destination_time_zone');
				$parts = explode('_', $parts);
				$inputdata['destination_time_zone'] = $parts[0];
				$inputdata['destination_time_zone_id'] = $parts[1];
				
				$inputdata['front_display_stop_at'] = $this->input->post('front_display_stop_at');
				
				$inputdata['elapsed_days'] = $this->input->post('elapsed_days');
				$inputdata['number_of_pricevariations'] = $variants;
				$inputdata['shuttle_no'] = $this->input->post('shuttle_no');
				$inputdata['driver_id']  = $this->input->post('driver_id');
				
				$inputdata['season_start'] = $this->input->post('season_start');
				$inputdata['season_end'] = $this->input->post('season_end');
				$inputdata['season_type'] = $this->input->post('season_type');
				
				$inputdata['agent_commisstion_type'] = $this->input->post('agent_commisstion_type');
				$inputdata['service_tax'] = $this->input->post('service_tax');
				$inputdata['service_tax_type'] = $this->input->post('service_tax_type');
				$inputdata['status'] = $this->input->post('status');
				$inputdata['fare_details'] = json_encode($this->input->post('fare'));
				
				$inputdata['special_fare'] = $this->input->post('special_fare');
				//$inputdata['special_start'] = $this->input->post('special_start');
				//$inputdata['special_end'] = $this->input->post('special_end');
				//$inputdata['fare_details_special'] = json_encode($this->input->post('fare_details_special'));
				
				$update_rec_id = $this->input->post('update_rec_id');
				if($update_rec_id != '')
				{
						//Update Operation
						$this->base_model->update_operation($inputdata, 'travel_location_costs', array('id' => $update_rec_id));
						$message = getPhrase('Record Updated Successfully');
				}
				else
				{
					//Insert OPERATIONS
					$update_rec_id = $this->base_model->insert_operation_id($inputdata, 'travel_location_costs');
					$message = getPhrase('Record Inserted Successfully');
				}
				
				$this->prepare_flashmessage($message, 0);
				redirect(base_url().'settings/travelLocationCosts/list');
			}	
		}

		$driver_opts[''] = "No Drivers Available.";
		$drivers = $this->base_model->getUsers('6', 1);
		if(!empty($drivers)) {
			$driver_opts[''] = "Select Driver";
			foreach ($drivers as $key => $value) {
				$driver_opts[$value->id] = ucfirst($value->username);
			}
		}

		$this->data['driver_opts'] = $driver_opts;


		$this->data['update_rec_id'] 	= $edit_id;
		$records	= $this->base_model->fetch_records_from('travel_location_costs', array('id' => $edit_id));		
		$this->data['records'] = $records;
		$this->data['specials'] = $this->base_model->fetch_records_from('travel_location_costs_special', array('tlc_id' => $edit_id), '*', 'special_start');
		$title = getPhrase('Add Travel Location Costs');
		if($edit_id != '') $title = getPhrase('Edit Travel Location Costs');
		$content = 'admin/settings/travel_location_costs/add_travel_location_costs';
		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= "locations";
		$this->data['heading'] 					= (isset($this->phrases["travel location costs"])) ? $this->phrases["travel location costs"] : "Travel Location Costs";
		$this->data['sub_heading'] 				= $title;
		$this->data['price_variations'] 		= $this->base_model->fetch_records_from('price_variations', array('variation_status' => 'Active'));
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
	
	function checkvalidate()
	{
		$special_start = $this->input->post('special_start');
		$special_end = $_POST['special_end'];
		$tlc_id = $this->input->post('tlc_id');
		$special_id = $this->input->post('special_id');
		$details = $this->db->query('select * from digi_travel_location_costs_special where ((special_start between "'.$special_start.'" AND "'.$special_end.'") OR (special_end between "'.$special_start.'" AND "'.$special_end.'")) AND tlc_id = '.$tlc_id)->result();
		if(empty($details))
		{
			return TRUE;
		}
		else
		{
			if($special_id != '')
			{
				if($details[0]->id == $special_id)
					return TRUE;
				else
				{
					$this->form_validation->set_message('checkvalidate', getPhrase('Record already exists with given dates'));
					return FALSE;
				}
					
			}
			$this->form_validation->set_message('checkvalidate', getPhrase('Record already exists with given dates'));
			return FALSE;
		}
	}
	
	function add_travel_location_costs_special($tlc_id, $special_id = '')
	{
		if(empty($tlc_id))
		{
			$this->prepare_flashmessage(getPhrase('Please select location cost'), 1);
			redirect(base_url().'settings/travelLocationCosts/list');
		}
		$this->data['message'] = $this->session->flashdata('message');
		if(isset($_POST['buttSubmit']))
		{
			$this->check_isdemo(base_url() . 'settings/travelLocationCosts');
			// FORM VALIDATIONS		
			$price_variations = $this->input->post('fare_details_special');
			$variants = isset($price_variations['variation']) ? count($price_variations['variation']) : 0;
			if(isset($price_variations['variation']) && count($price_variations['variation']) > 0)
			{
				foreach($price_variations['variation'] as $key => $val)
				{
					$this->form_validation->set_rules('fare_details_special[fare]['.$key.']',getPhrase('Fare'),'trim|callback_checkfareseat_special');
					$this->form_validation->set_rules('fare_details_special[seats]['.$key.']',getPhrase('Seats'),'trim|callback_checkfareseat_special');
				}
			}
			if($this->input->post('special_id') == '')
			{
				$this->form_validation->set_rules('special_start',getPhrase('Start'),'trim|required|callback_checkvalidate');
			}
			else
			{
				
			}
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			if ($this->form_validation->run() 	== TRUE) {
				$inputdata = array();
				$tlc_id = $this->input->post('tlc_id');
				$inputdata['tlc_id'] = $tlc_id;				
				$inputdata['special_start'] = $this->input->post('special_start');
				$inputdata['special_end'] = $this->input->post('special_end');
				$inputdata['fare_details_special'] = json_encode($this->input->post('fare_details_special'));
				$inputdata['status'] = $this->input->post('special_status');				
				//neatPrint($this->input->post());
				$special_id = $this->input->post('special_id');
				if($special_id != '')
				{
						$inputdata['updated'] = date('Y-m-d H:i:s');
						//Update Operation
						$this->base_model->update_operation($inputdata, 'travel_location_costs_special', array('id' => $special_id));
						$message = getPhrase('Record Updated Successfully');
				}
				else
				{
					//Insert OPERATIONS
					$update_rec_id = $this->base_model->insert_operation_id($inputdata, 'travel_location_costs_special');
					$message = getPhrase('Record Inserted Successfully');
				}
				
				$this->prepare_flashmessage($message, 0);
				redirect(base_url().'settings/add_travel_location_costs/'.$tlc_id);
			}	
		}


		$driver_opts[''] = "No Drivers Available.";
		$drivers = $this->base_model->getUsers('6', 1);
		if(!empty($drivers)) {
			$driver_opts[''] = "Select Driver";
			foreach ($drivers as $key => $value) {
				$driver_opts[$value->id] = ucfirst($value->username);
			}
		}

		$this->data['driver_opts'] = $driver_opts;


		$this->data['tlc_id'] 	= $tlc_id;
		$this->data['special_id'] 	= $special_id;		
		$records	= $this->base_model->fetch_records_from('travel_location_costs', array('id' => $tlc_id));
//print_r($records);		
		$this->data['records'] = $records;
		
		$this->data['special'] = $this->base_model->fetch_records_from('travel_location_costs_special', array('id' => $special_id));
		
		$title = getPhrase('Add Travel Location Costs');
		if($tlc_id != '') $title = getPhrase('Edit Travel Location Costs');
		$content = 'admin/settings/travel_location_costs/add_travel_location_costs_special';
		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= "locations";
		$this->data['heading'] 					= (isset($this->phrases["travel location costs"])) ? $this->phrases["travel location costs"] : "Travel Location Costs";
		$this->data['sub_heading'] 				= $title;
		$this->data['price_variations'] 		= $this->base_model->fetch_records_from('price_variations', array('variation_status' => 'Active'));
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
	
	function get_transition_points()
	{
		if($this->input->is_ajax_request())
		{
			$tl_id = $this->input->post('tl_id');
			$html = 'No Transition Points';
			if($tl_id != '')
			{
				$query = 'SELECT l.id,l.location FROM '.$this->db->dbprefix('locations').' l 
				INNER JOIN '.$this->db->dbprefix('travel_locations_transitions').' tlt ON tlt.location_id = l.id 
				INNER JOIN '.$this->db->dbprefix('travel_locations').' tl ON tl.travel_location_id = tlt.travel_location_id 
				WHERE tlt.travel_location_id = '.$tl_id;
				$locations = $this->db->query($query)->result();
				if(!empty($locations))
				{
				$html = '<table width="100%">
				<tr><td>Location</td><td>Time</td></tr>';
				foreach($locations as $location)
				{
					$html .= '<tr><td>'.$location->location.'</td><td><input type="text" name="transition_time['.$location->id.']" id="transition_time" value="" class="tme2"></td></tr>';
				}
				$html .= '</table>';
				}
			}
			echo $html;
		}
	}
	
	/**
	* This function will return the total number of seats in a vehicle
	* @param int $vehicle_id
	* @return int
	*/
	function get_total_seats()
	{
		if($this->input->is_ajax_request())
		{
			$vehicle_id = $this->input->post('vehicle_id');
			$query = 'SELECT * FROM '.$this->db->dbprefix('vehicle').' WHERE id = '.$vehicle_id;
			$total_seats = 0;
			$results = $this->db->query($query)->result();
			if(!empty($results))
			{
				$total_seats = $results[0]->passenger_capacity;
				$child_seats = 0;
				if($results[0]->child_seats != '')
				{
					$child_seats = count(explode(',', $results[0]->child_seats));
				}
			}
			echo $total_seats;
		}
	}
	
	function checkavailable()
	{
		$shuttles = $this->input->post('shuttles');
		$data = array(
						'special_start' => $this->input->post('special_start') ,
						'special_end' => $this->input->post('special_end'),
						'vehicle_id' => $this->input->post('new_vehicle'),
					);
		foreach($shuttles as $shuttle)
		{
			$parts = explode('_', $shuttle);
			$data['tlc_id'] = $parts[0];
			$data['vehicle_id'] = $parts[1];
			$check = $this->base_model->check_allotted_date($data['tlc_id'], $data['special_start'], $data['special_end']);
			//echo $this->db->last_query();die();
			if(empty($check))
				return TRUE;
			else
			{
				$this->form_validation->set_message('checkavailable', 'Vehicle is not available in these dates');
				return false;
			}	
		}
	}
	
	function change_vehicle()
	{
		if($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) 
		{

			$this->load->library('form_validation');
			if(isset($_POST['show']))
			{
				$this->form_validation->set_rules('present_vehicle', getPhrase('Present Vehicle') , 'trim|required');
				$this->form_validation->set_rules('new_vehicle', getPhrase('New Vehicle') , 'trim|required');
				$this->form_validation->set_rules('special_start', getPhrase('Special Start') , 'trim|required');
				$this->form_validation->set_rules('special_end', getPhrase('Special End') , 'trim|required');
				$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
				
				if ($this->form_validation->run() == true)
				{					
					$present_vehicle = $this->input->post('present_vehicle');
					$new_vehicle = $this->input->post('new_vehicle');
					
					$special_start = $this->input->post('special_start');
					$special_end = $this->input->post('special_end');
					
					if($present_vehicle == $new_vehicle)
					{
						$this->prepare_flashmessage("<p>Vehicles should be different</p>" , 1);
						redirect("settings/change_vehicle");
					}
					$this->data['details'] = $this->base_model->get_vehicle_shuttles( $present_vehicle );

					$new_vehicle_schedules = $this->base_model->get_driver_shuttles_date( $new_vehicle,$special_end, $special_end);
					$assigned_shuttles = array();
					if(!empty($new_vehicle_schedules))
					{
						foreach($new_vehicle_schedules as $shuttle)
						$assigned_shuttles[] = $shuttle->tlc_id;
					}
					$this->data['assigned_shuttles'] = $assigned_shuttles;
				}
			}
			if (isset($_POST['change'])) 
			{
				$this->check_isdemo(base_url() . 'settings/change_vehicle');
				// validate form input				
				$this->form_validation->set_rules('present_vehicle', getPhrase('Present Vehicle') , 'trim|required');
				$this->form_validation->set_rules('new_vehicle', getPhrase('New Vehicle') , 'trim|required');
				$this->form_validation->set_rules('special_start', getPhrase('Special Start') , 'trim|required');
				$this->form_validation->set_rules('special_end', getPhrase('Special End') , 'trim|required');
				if(count($_POST['shuttles']) == 0)
				{
				$this->form_validation->set_rules('shuttles[]', getPhrase('Shuttle'), 'trim|required');
				}
				else
				{
				$this->form_validation->set_rules('shuttles[]', getPhrase('Shuttle'), 'trim|callback_checkavailable');
				}
				$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
				
				if ($this->form_validation->run() == true) {
					$data = array(
						'special_start' => $this->input->post('special_start') ,
						'special_end' => $this->input->post('special_end') ,
						'vehicle_id' => $this->input->post('new_vehicle'),
						'created' => date('Y-m-d H:i:s'),
					);
					$shuttles = $this->input->post('shuttles');
					if(!empty($shuttles))
					{
						$newdata = array();
						foreach($shuttles as $shuttle)
						{
							$parts = explode('_', $shuttle);
							$data['tlc_id'] = $parts[0];
							$data['driver_id'] = $parts[1]; //Default Driver
							$check = $this->base_model->check_allotted_date($data['tlc_id'], $data['special_start'], $data['special_end']);
							//print_r($check);die();
							if(empty($check))
							{
								$newdata[] = $data;
							}
							else
							{
								$this->base_model->update_operation(array('vehicle_id' => $data['vehicle_id'], 'updated' => date('Y-m-d H:i:s')), 'travel_location_costs_drivers', array('id' => $check[0]->id));
							}
						}
						if(!empty($newdata))
						{
							$this->db->insert_batch('travel_location_costs_drivers', $newdata);
						}
					}												
					$txt 		= (isset($this->phrases["vehicle changed successfully"])) ? $this->phrases["vehicle changed successfully"] : "Vehicle Changed Successfully";
					$txt2 = "";
					$this->prepare_flashmessage("<p>".$txt."</p><p>".$txt2."</p>" , 0);	
					redirect("settings/vehicles_schedule");					
				}
				
				$present_driver = $this->input->post('present_driver');
				$this->data['details'] = $this->base_model->get_driver_shuttles( $present_driver );
				
				$new_driver = $this->input->post('new_driver');				
				$special_start = $this->input->post('special_start');
				$special_end = $this->input->post('special_end');
				$new_driver_schedules = $this->base_model->get_driver_shuttles_date( $new_driver,$special_end, $special_end);
				$assigned_shuttles = array();
				if(!empty($new_driver_schedules))
				{
					foreach($new_driver_schedules as $shuttle)
					$assigned_shuttles[] = $shuttle->tlc_id;
				}
				$this->data['assigned_shuttles'] = $assigned_shuttles;
			}

			
			$vehicles = $this->base_model->get_vehicles();
			$vehicle_opts = array('' => 'Please select vehicle');
			if(!empty($vehicles))
			{
				foreach($vehicles as $vehicle)
				{
					$vehicle_opts[$vehicle->id] = $vehicle->name.'-'.$vehicle->model.'('.$vehicle->number_plate.') ('.$vehicle->category.')';
				}
			}
			$this->data['vehicles'] = $vehicle_opts;
						
			$this->data['css'] = array(
				'form'
			);
			$content 		 = "admin/settings/vehicles/change_vehicle";
			$template		 = "templates/admin_template";
			$active_class	 = "vehicle_settings";
			$this->data['active_menu'] 	= $active_class;			
			$this->data['heading'] 		= (isset($this->phrases["users"])) ? $this->phrases["users"] : "Users";
			$this->data['sub_heading'] 	= (isset($this->phrases["create"])) ? $this->phrases["create"] : "Create";

			$this->data['title'] 			= (isset($this->phrases["create account"])) ? $this->phrases["create account"] : "Create Account";
			$this->data['content'] 			= $content;			
			$this->_render_page($template, $this->data);

		} else {
			redirect('auth/login');
		}
	}
	
	function vehicles_schedule($param1 = "list", $param2 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}
		$this->data['message'] = $this->session->flashdata('message');

		$records     		= array();
		$extra_conds 		= "";
		$vehicle_cat_opts	= array();
		$title		 		= (isset($this->phrases["vehicle settings"])) ? 
		$this->phrases["vehicle settings"] : "Vehicle Settings";
		$content 			= 'admin/settings/vehicles/vehicles_schedule';
		$this->data['vehicle_cat_opts']	= "";

		/* Delete Record */
		if($param1 == "delete" && $param2 > 0) {
		$this->check_isdemo(base_url() . 'settings/vehicles_schedule');
			
			if($this->base_model->delete_record('travel_location_costs_drivers', array('id' => $param2))) {				
				$this->prepare_flashmessage((isset($this->phrases["record deleted successfully"])) ? $this->phrases["record deleted successfully"] : "Record Deleted Successfully.", 0);
				redirect('settings/vehicles_schedule');

			}
		}
		
		if(!in_array($param1, array('add')))  /* For Listing and Editing Record(s) */
		{
			$records	= $this->base_model->run_query("SELECT tlcd.*, v.name,v.model,v.passenger_capacity,tlc.shuttle_no,fromloc.location startloc, toloc.location endloc,vc.category, d.username FROM `digi_travel_location_costs_drivers` tlcd
INNER JOIN ".$this->db->dbprefix('travel_location_costs')." tlc ON tlcd.tlc_id = tlc.id
INNER JOIN ".$this->db->dbprefix('travel_locations')." tl ON tlc.travel_location_id = tl.travel_location_id
INNER JOIN ".$this->db->dbprefix('locations')." fromloc ON fromloc.id = tl.from_loc_id
INNER JOIN ".$this->db->dbprefix('locations')." toloc ON toloc.id = tl.to_loc_id
INNER JOIN ".$this->db->dbprefix('users')." d ON d.id = tlcd.driver_id
INNER JOIN ".$this->db->dbprefix('vehicle')." v ON v.id = tlcd.vehicle_id
INNER JOIN ".$this->db->dbprefix('vehicle_categories')." vc ON vc.id = v.category_id");
			if($param1 == 'edit' && empty($records))
			{
				$this->prepare_flashmessage(
						(isset($this->phrases["wrong operation"])) ? 
						$this->phrases["wrong operation"] : "Wrong operation" , 1);
				redirect('settings/vehicles/list');
			}
		}


		$this->data['records']			= $records;
		$this->data['css_type'] 		= array("datatable");
		$this->data['active_menu'] 		= "vehicle_settings";
		$heading = (isset($this->phrases["vehicle"])) ? $this->phrases["vehicle"] : "Vehicle";
		$this->data['heading'] 			= '<a href="'.base_url().'settings/vehicles/list">'.$heading.'</a>';
		$this->data['sub_heading'] 				= (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
		$this->data['param'] 					= $param1;
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
}
/* End of file Settings.php */
/* Location: ./application/controllers/Settings.php */
