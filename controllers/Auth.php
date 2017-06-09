<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller

{
	/*
	| -----------------------------------------------------
	| PRODUCT NAME: 	Digi Point to Point Transfers
	| -----------------------------------------------------
	| AUTHER:			DIGITAL VIDHYA TEAM
	| -----------------------------------------------------
	| EMAIL:			digitalvidhya4u@gmail.com
	| -----------------------------------------------------
	| COPYRIGHTS:		RESERVED BY DIGITAL VIDHYA
	| -----------------------------------------------------
	| WEBSITE:			http://digitalvidhya.com
	|                   http://codecanyon.net/user/digitalvidhya
	| -----------------------------------------------------
	|
	| MODULE: 			Auth
	| -----------------------------------------------------
	| This is Auth module controller file.
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


	function index($param = '')
	{
		if (!$this->ion_auth->logged_in()) {

			redirect('auth/login');

		} elseif ($this->ion_auth->is_client()) {

			redirect('client', 'refresh');

		} elseif ($this->ion_auth->is_executive()) {

			redirect('executive', 'refresh');

		} elseif ($this->ion_auth->is_admin()) {

			redirect('admin', 'refresh');

		} elseif ($this->ion_auth->is_driver()) {

			redirect('driver', 'refresh');
		}
	}



	// log the user in

	function login()
	{
		if (!$this->ion_auth->logged_in())
		{
		$this->data['title'] 					= "";

		$this->data['message'] 					= "";

		if ($this->input->post()) {
			$this->form_validation->set_rules(
			'identity', 
			(isset($this->phrases["identity"])) ? $this->phrases["identity"] : "Identity", 
			'required|valid_email');
			$this->form_validation->set_rules(
			'password', 
			(isset($this->phrases["password"])) ? $this->phrases["password"] : "Password", 
			'required');
			$this->load->library(array('email','form_validation'));
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			if ($this->form_validation->run() == true) {

				// check to see if the user is logging in
				// check for "remember me"

				$remember 						= (bool)$this->input->post('remember');
				if ($this->ion_auth->login($this->input->post('identity') , 
				$this->input->post('password') , $remember)) {

					// if the login is successful

					$this->prepare_flashmessage((isset($this->phrases["login success"])) ? $this->phrases["login success"] : "Login Success".".", 0);
					redirect('auth');
				}
				else {

					// if the login was un-successful
					// redirect them back to the login page

					$this->prepare_flashmessage($this->ion_auth->errors() , 1);
					redirect('auth/login', 'refresh');
				}
			}
		}

		$this->data['identity'] 				= array(
			'name' 								=> 'identity',
			'id' 								=> 'identity',
			'type' 								=> 'text',
			'placeholder' 						=> (isset($this->phrases["email"])) ? $this->phrases["email"] : "Email" ,
			'value' 							=> $this->form_validation->set_value('identity') ,
		);
		$this->data['password'] 				= array(
			'name' 								=> 'password',
			'id' 								=> 'password',
			'type' 								=> 'password',
			'placeholder' 						=> (isset($this->phrases["password"])) ? $this->phrases["password"] : "Password" ,
		);

			$this->data['title'] 				= (isset($this->phrases["login"])) ? $this->phrases["login"] : "Login";
			$this->data['content'] 				= "login";
			$this->data['active_class'] 		= "login";
			$this->_render_page('templates/site_template', $this->data);
		}
		else
		{
			redirect('auth');
		}
	}

	//log the user out
	function logout()
	{

		$this->data['title'] = (isset($this->phrases["logout"])) ? $this->phrases["logout"] : "Logout";

		//log the user out
		$logout = $this->ion_auth->logout();

		//redirect them to the login page
		$this->prepare_flashmessage($this->ion_auth->messages(),0);
		redirect('auth/login', 'refresh');
	}

	// change password

	function change_password($param = '')
	{

		$this->form_validation->set_rules(
		'old', 
		$this->lang->line('change_password_validation_old_password_label') , 
		'required');
		$this->form_validation->set_rules(
		'new', 
		$this->lang->line('change_password_validation_new_password_label') , 
		'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . 
		$this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules(
		'new_confirm', 
		$this->lang->line('change_password_validation_new_password_confirm_label') , 
		'required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();
		if ($this->form_validation->run() 		== false) {
			$this->data['message'] 				= (validation_errors()) ? 
			validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length']	= $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] 		= array(
				'name' 							=> 'old',
				'id' 							=> 'old',
				'type' 							=> 'password',
			);
			$this->data['new_password'] 		= array(
				'name' 							=> 'new',
				'id' 							=> 'new',
				'type' 							=> 'password',
			);
			$this->data['new_password_confirm'] = array(
				'name' 							=> 'new_confirm',
				'id' 							=> 'new_confirm',
				'type' 							=> 'password',
			);
			$this->data['user_id'] 				= array(
				'name' 							=> 'user_id',
				'id' 							=> 'user_id',
				'type' 							=> 'hidden',
				'value' 						=> $user->id,
			);

			$this->data['active_menu']			= "change_password";
			$this->data['heading'] 				= (isset($this->phrases["change password"])) ? $this->phrases["change password"] : "Change Password";
			$this->data['title'] 				= (isset($this->phrases["change password"])) ? $this->phrases["change password"] : "Change Password";
			$this->data['content'] 				= "change_password";
			$template = 'templates/site_template';
			if($this->ion_auth->is_admin()) {
				$this->data['content'] 			= "admin/change_password";
				$template = 'templates/admin_template';
			}
			else if($this->ion_auth->is_driver()) {
				$this->data['content'] 			= "driver/change_password";
				$template = 'templates/driver_template';
			}
			else if($this->ion_auth->is_client()) {
				$this->data['content'] 			= "site/booking/change_password";
				$template = 'templates/site_template';
			}
			else if($this->ion_auth->is_executive())
			{
				$this->data['content'] 			= "executive/change_password";
				$template = 'templates/executive_template';
			}				
			$this->_render_page($template, $this->data);
		}
		else {
			$this->check_isdemo(base_url() . 'auth/change_password');
			$identity 	= $this->session->userdata('identity');
			$change 	= $this->ion_auth->change_password($identity, 
			$this->input->post('old') , $this->input->post('new'));
			if ($change) {

				//log the user out
				//$logout = $this->ion_auth->logout();

				//redirect them to the login page
				$this->prepare_flashmessage($this->ion_auth->messages(),0);
				redirect('auth/change_password');
			}
			else {
				$this->prepare_flashmessage((isset($this->phrases["unable to change password"])) ? $this->phrases["unable to change password"] : "Unable to Change Password".".",1);
				redirect('auth/change_password/'.$param);
			}
		}
	}

	// forgot password

	function forgot_password()
	{

		// setting validation rules by checking wheather identity is username or email

		if ($this->input->post()) {

			if ($this->config->item('identity', 'ion_auth') == 'username') {
				$this->form_validation->set_rules(
				'email', 
				$this->lang->line('forgot_password_username_identity_label') , 
				'required');
			}
			else {
				$this->form_validation->set_rules(
				'email', 
				$this->lang->line('forgot_password_validation_email_label') , 
				'required|valid_email');
			}

			if ($this->form_validation->run() == true) {

				// get identity from username or email
				
				if ($this->config->item('identity', 'ion_auth') == 'username') {
					$identity = $this->ion_auth->where('username', 
					strtolower($this->input->post('email')))->users()->row();
				}
				else {
					$identity = $this->ion_auth->where('email', 
					strtolower($this->input->post('email')))->users()->row();
				}

				if (empty($identity)) {
					if ($this->config->item('identity', 'ion_auth') == 'username') {
						$this->prepare_flashmessage(
						$this->ion_auth->set_message('forgot_password_username_not_found') , 1);
					}
					else {
						$this->prepare_flashmessage(
						$this->ion_auth->set_message('forgot_password_email_not_found') , 1);
					}

					$this->prepare_flashmessage($this->ion_auth->messages() , 3);
					redirect("auth/forgot_password", 'refresh');
				}

				// run the forgotten password method to email an activation code to the user

				$forgotten 		= $this->ion_auth->forgotten_password(
				$identity->{$this->config->item('identity', 'ion_auth') });
				
				if ($forgotten) {

					// if there were no errors
					// echo "true";

					$this->prepare_flashmessage($this->ion_auth->messages() , 2);
					redirect("auth/login"); //we should display a confirmation page here instead of the login page
				}
				else {

					$msg = $this->ion_auth->messages();
					if(empty($msg))
						$msg = 'Unable to Reset Password';
					$this->prepare_flashmessage( $msg, 3);
					redirect("auth/forgot_password", 'refresh');
				}
			}
		}

		// setup the input

		if ($this->config->item('identity', 'ion_auth') == 'username') {
			$this->data['identity_label'] 		= $this->lang->line('forgot_password_username_identity_label');
		}
		else {
			$this->data['identity_label'] 		= $this->lang->line('forgot_password_email_identity_label');
		}

		// set any errors and display the form

		$this->data['message'] 					= (validation_errors()) ? 
		validation_errors() : $this->session->flashdata('message');
		
		$this->data['title'] 					= $this->lang->line('forgot_password_heading');
		$this->data['email'] 					= array(
			'name' 								=> 'email',
			'id' 								=> 'email',
			'type' 								=> 'text',
			'class' 							=> 'user-name',
			'placeholder' 						=> (isset($this->phrases["email"])) ? $this->phrases["email"] : "Email" ,
			'value' 							=> $this->form_validation->set_value('email') ,
		);

		$this->data['content'] 					= 'forgot_password';
		$this->_render_page('templates/site_template', $this->data);
	}

	// reset password - final step for forgotten password

	public function reset_password($code = NULL)
	{
		if (!$code) {
			show_404();
		}
		$this->check_isdemo(base_url() . 'auth/change_password');
		// echo $code; die();

		$user = $this->ion_auth->forgotten_password_check($code);
		
		if ($user) 
		{
			// if the code is valid then display the password reset form
			$this->form_validation->set_rules(
			'new', 
			$this->lang->line('reset_password_validation_new_password_label') , 
			'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . 
			$this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			
			$this->form_validation->set_rules(
			'new_confirm', 
			$this->lang->line('reset_password_validation_new_password_confirm_label') , 
			'required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			if ($this->form_validation->run() 	== false) 
			{

				// display the form
				// set the flash data error message if there is one

				$this->data['message'] 				= $this->session->flashdata('message');
				$this->data['min_password_length']  = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] 	= array(
					'name' 						=> 'new',
					'id'			 			=> 'new',
					'type' 						=> 'password',
					'value' 					=> $this->form_validation->set_value('new') ,
				);
				$this->data['new_password_confirm'] = array(
					'name' 						=> 'new_confirm',
					'id' 						=> 'new_confirm',
					'type'		 				=> 'password',
					'value' 					=> $this->form_validation->set_value('new_confirm') ,
				);
				$this->data['user_id'] 			= array(
					'name' 						=> 'user_id',
					'id' 						=> 'user_id',
					'type' 						=> 'hidden',
					'value' 					=> $user->id,
				);
				$this->data['code'] 			= $code;
				$this->data['title'] 			= (isset($this->phrases["reset password"])) ? $this->phrases["reset password"] : "Reset Password";
				$this->data['content'] 			= 'reset_password';
				$this->_render_page('templates/site_template', $this->data);
			}
			else {

				// do we have a valid request?

				if ($user->id != $this->input->post('user_id')) {

					// something fishy might be up

					$this->ion_auth->clear_forgotten_password_code($code);
					show_error($this->lang->line('error_csrf'));
				}
				else {

					// finally change the password

					$identity 	= $user->{$this->config->item('identity', 'ion_auth') };
					$change 	= $this->ion_auth->reset_password($identity, $this->input->post('new'));
					if ($change) {

						//log the user out
						//$logout = $this->ion_auth->logout();

						// if the password was successfully changed
						$this->prepare_flashmessage($this->ion_auth->messages() , 0);
						redirect('auth/login');
					}
					else {
						$this->prepare_flashmessage($this->ion_auth->errors() , 1);
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else {

			// if the code is invalid then send them back to the forgot password page

			$this->prepare_flashmessage($this->ion_auth->errors() , 1);
			redirect("auth/forgot_password", 'refresh');
		}
	}

	// activate the user

	function activate($id, $code = false)
	{
		if ($code !== false) {
			$activation 						= $this->ion_auth->activate($id, $code);
		}
		else
		if ($this->ion_auth->is_admin()) {
			$activation 						= $this->ion_auth->activate($id);
		}

		if ($activation) {

			// redirect them to the auth page

			$this->prepare_flashmessage($this->ion_auth->messages() , 0);

			// $this->session->set_flashdata('message', $this->ion_auth->messages());

			redirect("auth/login", 'refresh');
		}
		else {

			// redirect them to the forgot password page

			$this->prepare_flashmessage($this->ion_auth->errors() , 1);
			redirect("auth/login", 'refresh');
		}
	}

	
	function deactivate($id = NULL)
	{
		$id 									= $this->config->item('use_mongodb', 'ion_auth') ? 
		(string)$id : (int)$id;
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules(
		'confirm', 
		$this->lang->line('deactivate_validation_confirm_label') , 
		'required');
		$this->form_validation->set_rules(
		'id', 
		$this->lang->line('deactivate_validation_user_id_label') , 
		'required|alpha_numeric');
		if ($this->form_validation->run() 		== FALSE) {

			// insert csrf check

			$this->data['csrf'] 				= $this->_get_csrf_nonce();
			$this->data['user'] 				= $this->ion_auth->user($id)->row();
			$this->data['title'] 				= 'Deactivate User';
			$this->data['content'] 				= 'auth/deactivate_user';
			$this->_render_page('templates/admin_template', $this->data);
		}
		else {

			// do we really want to deactivate?

			if ($this->input->post('confirm') 	== 'yes') {

				// do we have a valid request?

				if ($this->_valid_csrf_nonce()  === FALSE || $id != $this->input->post('id')) {
					show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?

				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
					$this->ion_auth->deactivate($id);
				}
			}

			// redirect them back to the auth page

			redirect('auth', 'refresh');
		}
	}


	//List Users
	function users($param1 = '', $param2 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}

		/* Delete User */
		if($param1 == "delete" && $param2 > 0 &&  $param2 != 1) {
			$this->check_isdemo(base_url() . 'auth/users');
			if($this->base_model->delete_record('users', array('id' => $param2))) {

				$this->base_model->delete_record('users_groups', array('user_id' => $param2));

				$this->prepare_flashmessage((isset($this->phrases["user deleted successfully"])) ? $this->phrases["user deleted successfully"] : "User Deleted Successfully".".", 0);
				redirect('auth/users');

			}
		}

		/* Change Status of USer*/
		if(($param1 == 0 || $param1 == 1) && ($param2 > 0 &&  $param2 != 1)) {

			if($this->base_model->update_operation(array('active' => $param1), 'users', array('id' => $param2))) {

				$this->prepare_flashmessage((isset($this->phrases["user status has been changed successfully"])) ? $this->phrases["user status has been changed successfully"] : "User status has been changed successfully".".", 0);
				redirect('auth/users');

			}
		}

		$records	= $this->base_model->getUsers('2,3,6'); //2-client group, 3-Executive group, 4-B2B User Group, 5-Supplier group, 6- driver group
//echo "<pre>"; print_r($records); die();
		$this->data['records']					= $records;
		$this->data['css_type'] 				= array("datatable");
		$this->data['active_menu'] 				= "users";
		$this->data['heading'] 					= (isset($this->phrases["users"])) ? $this->phrases["users"] : "Users";
		$this->data['param'] 					= (isset($this->phrases[$param1])) ? $this->phrases[$param1] : $param1;
		$this->data['title']	 				= (isset($this->phrases["all users"])) ? $this->phrases["all users"] : "All Users";
		$this->data['content'] 					= "admin/users/users_list";
		$this->_render_page('templates/admin_template', $this->data);
	}
	
	
	// create a new user

	function create_user()
	{
		//neatPrint($this->config->item('site_settings'));
		if(!$this->ion_auth->logged_in() || $this->ion_auth->is_admin()) {

			$this->config->load('ion_auth', TRUE);
			$tables = $this->config->item('tables', 'ion_auth');

			if ($this->input->post()) {
				$this->check_isdemo(base_url() . 'auth/create_user');
				// validate form input

				$this->load->library('form_validation');

				$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label') , 'required');
				$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label') , 'required');
				$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label') , 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
				$this->form_validation->set_rules('phone_code', 'Country Code' , 'required');
				$this->form_validation->set_rules('phone', "Phone Number" , 'required');
				$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label') , 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label') , 'required');

				if(isset($_POST['group']))
					$this->form_validation->set_rules('group', "User Type" , 'required');


				$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

				if ($this->form_validation->run() == true) {
					$username = $this->input->post('first_name') . ' ' . $this->input->post('last_name');
					//$username = $this->input->post('first_name');
					$email = strtolower($this->input->post('email'));
					$password = $this->input->post('password');
					$additional_data = array(
						'first_name' => $this->input->post('first_name') ,
						'last_name' => $this->input->post('last_name') ,
						'phone_code' => $this->input->post('phone_code'),
						'phone' => $this->input->post('phone'),
						'display_name' => $this->input->post('first_name'),
					);

					if(isset($_POST['deposited_amount']))
						$additional_data['deposited_amount'] = $this->input->post('deposited_amount');

					$groups = ($this->input->post('group')) ? array($this->input->post('group')) : array();
					
					$result = $this->ion_auth->register($username, $password, $email, $additional_data, $groups);

					if ( $result > 0 ) {
						// check to see if we are creating the user
						// redirect them back to the admin page
						if($this->ion_auth->is_admin()) {

							if($this->input->post('group') == "2") {
								$txt 		= (isset($this->phrases["client created successfully"])) ? $this->phrases["client created successfully"] : "Client Created Successfully";
							} elseif($this->input->post('group') == "3") {
								$txt 		= (isset($this->phrases["executive created successfully"])) ? $this->phrases["executive created successfully"] : "Executive Created Successfully";
							} elseif($this->input->post('group') == "4") {
								$txt 		= (isset($this->phrases["b2buser created successfully"])) ? $this->phrases["b2buser created successfully"] : "B2B User Created Successfully";
							} elseif($this->input->post('group') == "5") {
								$txt 		= (isset($this->phrases["supplier created successfully"])) ? $this->phrases["supplier created successfully"] : "Supplier Created Successfully";
							} elseif($this->input->post('group') == "6") {
								$txt 		= (isset($this->phrases["driver created successfully"])) ? $this->phrases["driver created successfully"] : "Driver Created Successfully";
							}

							$txt2 = "";
							if($this->input->post('group') == "2")
								$txt2 = (isset($this->phrases["activation email sent"])) ? $this->phrases["activation email sent"] : "Activation Email Sent";

							$this->prepare_flashmessage("<p>".$txt."</p><p>".$txt2."</p>" , 2);						
							redirect("auth/users");

						} else {

							$txt3 = (isset($this->phrases["thanks for registering with us"])) ? $this->phrases["thanks for registering with us"] : "Thanks For Registering with us. ";

							$txt4 = "";
							if($this->input->post('group') == "2")
								$txt4 = (isset($this->phrases["activation email sent"])) ? $this->phrases["activation email sent"] : "Activation Email Sent";

							$this->prepare_flashmessage("<p>".$txt3."</p><p>".$txt4."</p>" , 2);
							redirect("auth/login");
						}

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

			$content 	 	= "create_account";
			$template	 	= "templates/site_template";
			$active_class	= "create_account";
			$this->data['active_class'] 	= $active_class;
			$this->data['group_opts'] 	= array();


			if($this->ion_auth->is_admin()) {

				$group_opts[''] = "Select User type";
				$groups = $this->ion_auth->groups()->result();
				foreach($groups as $g) {
					if($g->id == 2 || $g->id == 6 || $g->id == 3)
						$group_opts[$g->id] = ucfirst($g->name);
				}
				$this->data['group_opts'] 	= $group_opts;

				$content 		 = "admin/users/create_account";
				$template		 = "templates/admin_template";
				$active_class	 = "users";
				$this->data['active_menu'] 	= $active_class;
				
				$this->data['heading'] 		= (isset($this->phrases["users"])) ? $this->phrases["users"] : "Users";
				$this->data['sub_heading'] 	= (isset($this->phrases["create"])) ? $this->phrases["create"] : "Create";

			}


			$this->data['title'] 			= (isset($this->phrases["create account"])) ? $this->phrases["create account"] : "Create Account";
			$this->data['content'] 			= $content;			
			$this->_render_page($template, $this->data);

		} else {
			redirect('auth');
		}
	}

	// edit a user

	function edit_user($id = '')
	{

		if(empty($id))
		{
			$this->prepare_flashmessage("Please select user to edit" , 1);
			redirect("auth/users");
		}
		$id = ($id > 0) ? $id : $this->input->post('id');

		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->user()->row()->id == $id)) {
			$this->check_isdemo(base_url() . 'auth/users');
			$user = $this->ion_auth->user($id)->row();

			if(empty($user)) {

				$this->prepare_flashmessage("Invalid Request. No User Found.", 1);
				redirect('auth/users');
			}

			$currentGroups = $this->ion_auth->get_users_groups($id)->result();

			// validate form input

			$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label') , 'required');
			$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label') , 'trim');
			$this->form_validation->set_rules('phone_code', 'Country Code' , 'required');
			$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label') , 'required');
			$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label') , 'trim');

			if($this->input->post('group'))
				$this->form_validation->set_rules('group', "User Type" , 'required');

			if($this->input->post('deposited_amount'))
					$this->form_validation->set_rules('deposited_amount', "Deposited Amount" , 'required|numeric');

			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

			if (isset($_POST) && !empty($_POST)) {

				$data = array(
					'first_name' => $this->input->post('first_name') ,
					'last_name' => $this->input->post('last_name') ,
					'username' => $this->input->post('first_name') . ' ' . $this->input->post('last_name'),
					'display_name' => $this->input->post('first_name'),
					'company' => $this->input->post('company') ,
					'phone_code' => $this->input->post('phone_code') ,
					'phone' => $this->input->post('phone') ,
				);

				if($this->input->post('deposited_amount'))
					$data['deposited_amount'] = $this->input->post('deposited_amount');

				// Only allow updating groups if user is admin

				if ($this->ion_auth->is_admin()) {

					$data['email'] = $this->input->post('email');

					// Update the groups user belongs to

					$groupData = array($this->input->post('group'));
					if (isset($groupData) && !empty($groupData)) {
						$this->ion_auth->remove_from_group('', $id);
						foreach($groupData as $grp) {
							$this->ion_auth->add_to_group($grp, $id);
						}
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
								'username'       => $data['first_name'],

							);
						if ($this->ion_auth->is_admin())
							$session_data['email']	=	$data['email'];

						$this->session->set_userdata($session_data);
					}

					// check to see if we are creating the user
					// redirect them back to the respective page


					if($this->ion_auth->is_admin()) {

						if($this->input->post('group') == "2") {
							$txt 		= (isset($this->phrases["client updated successfully"])) ? $this->phrases["client updated successfully"] : "Client Updated Successfully";
						} elseif($this->input->post('group') == "3") {
							$txt 		= (isset($this->phrases["executive updated successfully"])) ? $this->phrases["executive updated successfully"] : "Executive Updated Successfully";
						} elseif($this->input->post('group') == "4") {
							$txt 		= (isset($this->phrases["b2buser updated successfully"])) ? $this->phrases["b2buser updated successfully"] : "B2B User Updated Successfully";
						} elseif($this->input->post('group') == "5") {
							$txt 		= (isset($this->phrases["supplier updated successfully"])) ? $this->phrases["supplier updated successfully"] : "Supplier Updated Successfully";
						} elseif($this->input->post('group') == "6") {
							$txt 		= (isset($this->phrases["driver updated successfully"])) ? $this->phrases["driver updated successfully"] : "Driver Updated Successfully";
						}

						$this->prepare_flashmessage("<p>".$txt."</p>" , 0);
						redirect("auth/users");

					} else {

						$txt2 = (isset($this->phrases["account updated successfully"])) ? $this->phrases["account updated successfully"] : "Account Updated Successfully";

						$this->prepare_flashmessage("<p>".$txt2.".</p>" , 0);
						redirect("auth/edit_user/".$id);
					}	

				}
			}

			// display the edit user form

			// set the flash data error message if there is one

			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			// pass the user to the view

			$this->data['user'] = $user;
			$this->data['currentGroups'] = $currentGroups;
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


			$content 	 	= "edit_account";
			$template	 	= "templates/site_template";
			$active_class	= "edit_account";
			$this->data['group_opts'] 	= array();

			if($this->ion_auth->is_admin()) {

				$groups = $this->ion_auth->groups()->result();
				foreach($groups as $g) {
					if($g->id == 2 || $g->id == 3 || $g->id == 6)
						$group_opts[$g->id] = ucfirst($g->name);
				}

				$content 		 = "admin/users/edit_account";
				$template		 = "templates/admin_template";
				$active_class	 = "users";
				$this->data['group_opts'] 	= $group_opts;
				$this->data['heading'] 		= (isset($this->phrases["users"])) ? $this->phrases["users"] : "Users";
				$this->data['sub_heading'] 	= (isset($this->phrases["edit"])) ? $this->phrases["edit"] : "Edit";

			}


			$this->data['title'] 			= (isset($this->phrases["edit account"])) ? $this->phrases["edit account"] : "Edit Account";
			$this->data['content'] 			= $content;
			$this->data['active_class'] 	= $active_class;


			$this->_render_page($template, $this->data);

			// $this->_render_page('auth/edit_user', $this->data);
		} else {
			redirect('auth', 'refresh');
		}

	}

	

	
}

