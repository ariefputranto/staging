<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Managevehicle extends MY_Controller

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
	| MODULE: 			Managedriver
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

	function index($param1 = 0, $param2 = '', $param3 = '')
	{
		
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		$this->data['message'] = $this->session->flashdata('message');
		
		/* Delete User */
		if($param1 == "delete" && $param2 > 0 &&  $param2 != 1) {
			$this->check_isdemo(base_url() . 'managedriver/index');
			if($this->base_model->delete_record('users', array('id' => $param2))) {

				$this->base_model->delete_record('users_groups', array('user_id' => $param2));

				$this->prepare_flashmessage((isset($this->phrases["user deleted successfully"])) ? $this->phrases["user deleted successfully"] : "User Deleted Successfully".".", 0);
				$str = '';
				if($param3 != '' && $param3 != 0)
				$str = '/'.$param3;
				redirect('managedriver/index'.$str);

			}
		}
		$records    = array();
		$where['1']	= "1";
		$title		= getPhrase('Drivers');
		$content = 'admin/managedriver/drivers_list';

		
		$records = $this->base_model->get_drivers( $param1 );
		$this->load->library('pagination');
		$config = array();
        $config["base_url"] = base_url() . "managedriver/index";
        $total_row = $this->base_model->numrows;
        $config["total_rows"] = $total_row;
        $config["per_page"] = PER_PAGE;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 5;
        $config['cur_tag_open'] = '&nbsp;<a class="current">';
        $config['cur_tag_close'] = '</a>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';        
        $this->pagination->initialize($config);
		
		$this->data['page'] = $param1;
		$this->data['page_links'] = $this->pagination->create_links();
		$this->data['records']					= $records;
		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= "driver";
		$this->data['heading'] 					= '<a href="'.base_url().'managedriver/index">'.getPhrase('Driver Management').'</a>';
		//$this->data['sub_heading'] 				= (isset($this->phrases[$param1])) ? $this->phrases[$param1] : ucwords($param1);
		$this->data['param'] 					= $param1;
		$this->data['title']	 				= $title;
		$this->data['content'] 					= $content;
		$this->_render_page('templates/admin_template', $this->data);
	}
	
	function create_account()
	{
		
		if($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

			$this->config->load('ion_auth', TRUE);
			$tables = $this->config->item('tables', 'ion_auth');

			if ($this->input->post()) {
				$this->check_isdemo(base_url() . 'managedriver/create_account');
				// validate form input

				$this->load->library('form_validation');

				$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label') , 'required');
				$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label') , 'required');
				$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label') , 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
				$this->form_validation->set_rules('phone_code', 'Country Code' , 'required');
				$this->form_validation->set_rules('phone', "Phone Number" , 'required');
				$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label') , 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label') , 'required');

				
				$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

				if ($this->form_validation->run() == true) {
					$username = $this->input->post('first_name') . ' ' . $this->input->post('last_name');
					$email = strtolower($this->input->post('email'));
					$password = $this->input->post('password');
					$additional_data = array(
						'first_name' => $this->input->post('first_name') ,
						'last_name' => $this->input->post('last_name') ,
						'phone_code' => $this->input->post('phone_code'),
						'phone' => $this->input->post('phone'),
					);
					$groups = array(6);					
					$result = $this->ion_auth->register($username, $password, $email, $additional_data, $groups);
					if ( $result > 0 ) 
					{							
					$txt 		= (isset($this->phrases["driver created successfully"])) ? $this->phrases["driver created successfully"] : "Driver Created Successfully";
					$txt2 = "";
					$this->prepare_flashmessage("<p>".$txt."</p><p>".$txt2."</p>" , 2);	
					redirect("managedriver/index");
					}
				}
			}

			$this->data['first_name'] = array(
				'name' => 'first_name',
				'placeholder' => (isset($this->phrases["first name"])) ? $this->phrases["first name"].' *' : "First Name".' *',
				'id' => 'first_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('first_name') ,
			);
			$this->data['last_name'] = array(
				'name' => 'last_name',
				'placeholder' => (isset($this->phrases["last name"])) ? $this->phrases["last name"] : "Last Name" ,
				'id' => 'last_name',
				'type' => 'text',
				'value' => $this->form_validation->set_value('last_name') ,
			);
			$this->data['email'] = array(
				'name' => 'email',
				'placeholder' => (isset($this->phrases["email"])) ? $this->phrases["email"].' *' : "Email".' *' ,
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email') ,
			);
			$this->data['phone'] = array(
				'name' => 'phone',
				'placeholder' => (isset($this->phrases["phone"])) ? $this->phrases["phone"].' *' : "Phone".' *',
				'id' => 'phone',
				'type' => 'text',
				'value' => $this->form_validation->set_value('phone') ,
			);
			$this->data['phone_code'] = array(
				'name' => 'phone_code',
				'placeholder' => (isset($this->phrases["phone code"])) ? $this->phrases["phone code"].' *' : "Phone Code".' *',
				'id' => 'phone_code',
				'type' => 'text',
				'maxlength' => 3,
				'value' => $this->form_validation->set_value('phone_code') ,
			);
			$this->data['password'] = array(
				'name' => 'password',
				'placeholder' => (isset($this->phrases["password"])) ? $this->phrases["password"].' *' : "Password".' *' ,
				'id' => 'password',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password') ,
				'maxlength' => $this->config->item('max_password_length', 'ion_auth'),
			);
			$this->data['password_confirm'] = array(
				'name' => 'password_confirm',
				'placeholder' => (isset($this->phrases["confirm password"])) ? $this->phrases["confirm password"].' *' : "Confirm Password".' *' ,
				'id' => 'password_confirm',
				'type' => 'password',
				'value' => $this->form_validation->set_value('password_confirm') ,
				'maxlength' => $this->config->item('max_password_length', 'ion_auth'),
			);
			$this->data['css'] = array(
				'form'
			);
			$content 		 = "admin/managedriver/create_account";
			$template		 = "templates/admin_template";
			$active_class	 = "driver";
			$this->data['active_menu'] 	= $active_class;			
			$this->data['heading'] 		= (isset($this->phrases["users"])) ? $this->phrases["users"] : "Users";
			$this->data['sub_heading'] 	= (isset($this->phrases["create"])) ? $this->phrases["create"] : "Create";

			$this->data['title'] 			= (isset($this->phrases["create account"])) ? $this->phrases["create account"] : "Create Account";
			$this->data['content'] 			= $content;			
			$this->_render_page($template, $this->data);

		} else {
			redirect('managedriver');
		}
	}
	
	function edit_driver($id = '')
	{

		if(empty($id))
		{
			$this->prepare_flashmessage("Please select user to edit" , 1);
			redirect("managedriver/index");
		}

		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
			$this->check_isdemo(base_url() . 'managedriver/index');
			$user = $this->ion_auth->user($id)->row();

			if(empty($user)) {

				$this->prepare_flashmessage("Invalid Request. No User Found.", 1);
				redirect('managedriver/index');
			}

			
			// validate form input

			$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label') , 'required');
			$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label') , 'trim');
			$this->form_validation->set_rules('phone_code', 'Country Code' , 'required');
			$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label') , 'required');
			$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label') , 'trim');			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if (isset($_POST) && !empty($_POST)) {

				$data = array(
					'first_name' => $this->input->post('first_name') ,
					'last_name' => $this->input->post('last_name') ,
					'username' => $this->input->post('first_name') . ' ' . $this->input->post('last_name') ,
					'company' => $this->input->post('company') ,
					'phone_code' => $this->input->post('phone_code') ,
					'phone' => $this->input->post('phone'),
				);

				// Only allow updating groups if user is admin
				$data['email'] = $this->input->post('email');
				// Update the groups user belongs to
				$groupData = array(6);
				if (isset($groupData) && !empty($groupData)) {
					$this->ion_auth->remove_from_group('', $id);
					foreach($groupData as $grp) {
						$this->ion_auth->add_to_group($grp, $id);
					}
				}
				// update the password if it was posted				
				if ($this->input->post('password')) {
					$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label') , 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
					$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label') , 'required');
					$data['password'] = $this->input->post('password');
				}

				if ($this->form_validation->run() === TRUE) {

					$this->ion_auth->update($user->id, $data);

					if( $this->ion_auth->user()->row()->id == $id) {

						$session_data = array(
								'username'       => $data['username'],

							);
						if ($this->ion_auth->is_admin())
							$session_data['email']	=	$data['email'];

						$this->session->set_userdata($session_data);
					}					
					$txt 		= (isset($this->phrases["driver updated successfully"])) ? $this->phrases["driver updated successfully"] : "Driver Updated Successfully";	
					$this->prepare_flashmessage("<p>".$txt."</p>" , 0);
					redirect("managedriver/index");
				}
			}

			// display the edit user form

			// set the flash data error message if there is one

			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			// pass the user to the view

			$this->data['user'] = $user;
			
			$this->data['first_name'] = array(
				'name' => 'first_name',
				'id' => 'first_name',
				'type' => 'text',
				'placeholder' => (isset($this->phrases["first name"])) ? $this->phrases["first name"] : "First Name" ,
				'value' => $this->form_validation->set_value('first_name', $user->first_name) ,
			);
			$this->data['last_name'] = array(
				'name' => 'last_name',
				'id' => 'last_name',
				'type' => 'text',
				'placeholder' => (isset($this->phrases["last name"])) ? $this->phrases["last name"] : "Last Name" ,
				'value' => $this->form_validation->set_value('last_name', $user->last_name) ,
			);
			$this->data['email'] = array(
				'name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'placeholder' => (isset($this->phrases["email"])) ? $this->phrases["email"] : "Email" ,
				'value' => $this->form_validation->set_value('company', $user->email) ,
			);
			$this->data['company'] = array(
				'name' => 'company',
				'id' => 'company',
				'type' => 'text',
				'value' => $this->form_validation->set_value('company', $user->company) ,
			);
			$this->data['phone'] = array(
				'name' => 'phone',
				'id' => 'phone',
				'type' => 'text',
				'placeholder' => (isset($this->phrases["phone"])) ? $this->phrases["phone"] : "Phone" ,
				'value' => $this->form_validation->set_value('phone', $user->phone) ,
			);
			$this->data['phone_code'] = array(
				'name' => 'phone_code',
				'class' => 'phone1',
				'placeholder' => (isset($this->phrases["phone code"])) ? $this->phrases["phone code"] : "Phone Code",
				'id' => 'phone_code',
				'type' => 'text',
				'maxlength' => 3,
				'value' => $this->form_validation->set_value('phone_code', $user->phone_code) ,
			);
			$this->data['deposited_amount'] = array(
				'name' => 'deposited_amount',
				'id' => 'deposited_amount',
				'type' => 'text',
				'placeholder' => (isset($this->phrases["deposited amount"])) ? $this->phrases["deposited amount"] : "Deposited Amount" ,
				'value' => $this->form_validation->set_value('deposited_amount', $user->deposited_amount) ,
			);
			$this->data['password'] = array(
				'name' => 'password',
				'id' => 'password',
				'placeholder' => (isset($this->phrases["password"])) ? $this->phrases["password"] : "Password" ,
				'type' => 'password',
				'maxlength' => $this->config->item('max_password_length', 'ion_auth'),
			);
			$this->data['password_confirm'] = array(
				'name' => 'password_confirm',
				'id' => 'password_confirm',
				'placeholder' => (isset($this->phrases["confirm password"])) ? $this->phrases["confirm password"] : "Confirm Password" ,
				'type' => 'password',
				'maxlength' => $this->config->item('max_password_length', 'ion_auth'),
			);
			$content 		 = "admin/managedriver/edit_driver";
			$template		 = "templates/admin_template";
			$active_class	 = "driver";
			
			$this->data['heading'] 		= (isset($this->phrases["users"])) ? $this->phrases["users"] : "Users";
			$this->data['sub_heading'] 	= (isset($this->phrases["edit"])) ? $this->phrases["edit"] : "Edit";
			$this->data['id'] = $id;

			$this->data['title'] 			= (isset($this->phrases["Edit Driver"])) ? $this->phrases["Edit Driver"] : "Edit Driver";
			$this->data['content'] 			= $content;
			$this->data['active_class'] 	= $active_class;
			$this->_render_page($template, $this->data);

			// $this->_render_page('auth/edit_user', $this->data);
		} else {
			redirect('managedriver', 'refresh');
		}
	}
	
	function checkavailable()
	{
		
		$query = 'SELECT * FROM digi_travel_location_costs_drivers WHERE 
tlc_id = AND (special_start BETWEEN special_start AND special_end OR special_end BETWEEN special_start AND special_end)
AND driver_id = AND vehicle_id = ';
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
	
	function change_driver()
	{
		if($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) 
		{

			$this->load->library('form_validation');
			if(isset($_POST['show']))
			{
				$this->form_validation->set_rules('present_driver', getPhrase('Present Driver') , 'trim|required');
				$this->form_validation->set_rules('new_driver', getPhrase('New Driver') , 'trim|required');
				$this->form_validation->set_rules('special_start', getPhrase('Special Start') , 'trim|required');
				$this->form_validation->set_rules('special_end', getPhrase('Special End') , 'trim|required');
				$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
				if ($this->form_validation->run() == true)
				{					
					$present_driver = $this->input->post('present_driver');
					$new_driver = $this->input->post('new_driver');
					
					$special_start = $this->input->post('special_start');
					$special_end = $this->input->post('special_end');
					
					if($present_driver == $new_driver)
					{
						$this->prepare_flashmessage("<p>Drivers should be different</p>" , 1);
						redirect("managedriver/change_driver");
					}
					$this->data['details'] = $this->base_model->get_driver_shuttles( $present_driver );

					$new_driver_schedules = $this->base_model->get_driver_shuttles_date( $new_driver,$special_end, $special_end);
					$assigned_shuttles = array();
					if(!empty($new_driver_schedules))
					{
						foreach($new_driver_schedules as $shuttle)
						$assigned_shuttles[] = $shuttle->tlc_id;
					}
					$this->data['assigned_shuttles'] = $assigned_shuttles;
				}
			}
			if (isset($_POST['change'])) {
				$this->check_isdemo(base_url() . 'managedriver/index');
				// validate form input				
				$this->form_validation->set_rules('present_driver', getPhrase('Present Driver') , 'trim|required');
				$this->form_validation->set_rules('new_driver', getPhrase('New Driver') , 'trim|required');
				$this->form_validation->set_rules('special_start', getPhrase('Special Start') , 'trim|required');
				$this->form_validation->set_rules('special_end', getPhrase('Special End') , 'trim|required');
				if(count($_POST['shuttles']) == 0)
				{
				$this->form_validation->set_rules('shuttles[]', getPhrase('Shuttle'), 'trim|required|callback_checkavailable');
				}
				else
				{
				//$this->form_validation->set_rules('shuttles[]', getPhrase('Shuttle'), 'trim|callback_checkavailable');
				}
				$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
				
				if ($this->form_validation->run() == true) {
					
					$data = array(
						'special_start' => $this->input->post('special_start') ,
						'special_end' => $this->input->post('special_end') ,
						'driver_id' => $this->input->post('new_driver'),
						'created' => date('Y-m-d H:i:s'),
					);
					$shuttles = $this->input->post('shuttles');
					if(!empty($shuttles))
					{
						//Delete old driver assigned shuttles
						$old_driver = $this->input->post('present_driver');
						$old_assignments = $this->base_model->get_driver_shuttles_date($old_driver, $data['special_start'], $data['special_end']);
						if(!empty($old_assignments))
						{
							foreach($old_assignments as $assigns)
							{
							$this->base_model->delete_record('travel_location_costs_drivers', array('id' => $assigns->id));
							}
						}
						$newdata = array();
						foreach($shuttles as $shuttle)
						{
							$parts = explode('_', $shuttle);
							$data['tlc_id'] = $parts[0];
							$data['vehicle_id'] = $parts[1];
							$check = $this->base_model->fetch_records_from('travel_location_costs_drivers', array('tlc_id' => $data['tlc_id'], 'special_start' => $data['special_start'], 'special_end' => $data['special_end'], 'driver_id' => $data['driver_id'], 'vehicle_id' => $data['vehicle_id']));
							if(empty($check))
							$newdata[] = $data;
						}
						if(!empty($newdata))
						{
							$this->db->insert_batch('travel_location_costs_drivers', $newdata);
						}
					}												
					$txt 		= (isset($this->phrases["driver changed successfully"])) ? $this->phrases["driver changed successfully"] : "Driver Changed Successfully";
					$txt2 = "";
					$this->prepare_flashmessage("<p>".$txt."</p><p>".$txt2."</p>" , 0);	
					redirect("managedriver/index");					
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

			
			$other_drivers = $this->base_model->get_other_drivers();
			$other_drivers_opts = array('' => 'Please select driver');
			if(!empty($other_drivers))
			{
				foreach($other_drivers as $driver)
				{
					$other_drivers_opts[$driver->id] = $driver->first_name.' '.$driver->last_name;
				}
			}
			$this->data['other_drivers'] = $other_drivers_opts;
						
			$this->data['css'] = array(
				'form'
			);
			$content 		 = "admin/managedriver/change_driver";
			$template		 = "templates/admin_template";
			$active_class	 = "driver";
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
}
/* End of file Settings.php */
/* Location: ./application/controllers/Settings.php */
