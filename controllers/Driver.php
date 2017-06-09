<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver extends MY_Controller

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
	| MODULE: 			Driver
	| -----------------------------------------------------
	| This is Driver module controller file.
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
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_driver()) {
			redirect('auth/login');
		}


		$driver_id = $this->ion_auth->get_user_id();
		$today = date("Y-m-d");

		$today_rides = $this->base_model->getDriverShuttles($driver_id, '', '', $today);

		$this->data['records']			= $today_rides;

		$this->data['active_menu'] 		= "dashboard";
		$this->data['title'] 			= "Driver Dashboard";
		$this->data['content']			= 'driver/dashboard';
		$this->data['css_type'] 		= array('datatable');
		$this->_render_page('templates/driver_template', $this->data);

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


	/****** DRIVER PROFILE ******/
	public function profile()
	{

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_driver()) {
			redirect('auth/login');
		}

		if ($this->input->post()) {
			$this->check_isdemo(base_url() . 'driver/profile');
			$this->form_validation->set_rules(
			'username', 
			(isset($this->phrases["name"])) ? $this->phrases["name"] : "Name", 
			'required'
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

				$inputdata['username'] 			= $this->input->post('username');
				$inputdata['email'] 			= $this->input->post('email');
				$inputdata['phone'] 			= $this->input->post('phone');
				$table_name = "users";
				$where['id'] = $this->input->post('update_rec_id');

				/* Save File(Admin's Photo) */
					if (!empty($_FILES['userfile']['name'])) {

						$file_name = $this->input->post('update_rec_id') . "_" . 
															str_replace(' ', '',$_FILES['userfile']['name']);

						$config['upload_path'] 		= './uploads/driver_profile_pic/';
						$config['allowed_types'] 	= 'jpg|jpeg|png';
						$config['overwrite'] 		= true;
						$config['file_name']        = $file_name;

						$this->load->library('upload', $config);

						/* Unlink Old Photo */
						if ($this->session->userdata('photo') != "" && file_exists('uploads/driver_profile_pic/'.$this->session->userdata('photo'))) {
							unlink('uploads/driver_profile_pic/' . $this->session->userdata('photo'));
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

					if(isset($inputdata['username']))
						$this->session->set_userdata('username', $inputdata['username']);

					$this->prepare_flashmessage(
					(isset($this->phrases["profile has been updated successfully"])) ? 
					$this->phrases["profile has been updated successfully"] : 
					"Profile has been updated successfully".".", 0);
					redirect('driver/profile');
				}
				else {
					$this->prepare_flashmessage(
					(isset($this->phrases["unable to update profile"])) ? 
					$this->phrases["unable to update profile"] : 
					"Unable to update profile"."." , 1);
					redirect('driver/profile');
				}
			}
		}

		$admin_details 							= $this->base_model->fetch_records_from('users', array(
			'id' => $this->session->userdata('user_id')
		));
		if(count($admin_details) > 0) $admin_details = $admin_details[0];

		$this->data['admin_details'] 	= $admin_details;
		$this->data['active_menu'] 		= "driver_profile";
		$this->data['heading'] 			= (isset($this->phrases["driver profile"])) ? $this->phrases["driver profile"] : "Driver Profile";
		$this->data['title'] 			= (isset($this->phrases["driver profile"])) ? $this->phrases["driver profile"] : "Driver Profile";
		$this->data['content'] 			= 'driver/driver_profile';
		$this->_render_page('templates/driver_template', $this->data);
	}


	/***** ASSIGNED SHUTTLES *****/
	function assigned_shuttles($param = 'all')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_driver()) {
			redirect('auth/login');
		}


		$driver_id  = $this->ion_auth->get_user_id();
		$shuttle_no = "";
		$travel_location_cost_id = "";

		if($param == "all") {

			$date = "";

		} else if($param == "todayz") {

			$date = date("Y-m-d");
		}


		$records = $this->base_model->getDriverShuttles($driver_id, $shuttle_no, $travel_location_cost_id, $date);

		$this->data['records']			= $records;
		$this->data['param']			= $param;
		$this->data['active_menu'] 		= "assigned_shuttles";
		$this->data['heading'] 			= (isset($this->phrases["assigned shuttles"])) ? $this->phrases["assigned shuttles"] : "Assigned Shuttles";
		$this->data['heading'] 			.= " >> ".ucfirst(($param == 'todayz') ? "Tpday's" : $param);
		$this->data['title'] 			= (isset($this->phrases["assigned shuttles"])) ? $this->phrases["assigned shuttles"] : "Assigned Shuttles";
		$this->data['content'] 			= 'driver/assigned_shuttles';
		$this->data['css_type'] 		= array('datatable');
		$this->_render_page('templates/driver_template', $this->data);
	}


	/****** ASSIGNED SHUTTLES BY DATE ******/
	function assigned_shuttles_by_date()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_driver()) {
			redirect('auth/login');
		}

		$records = array();
		$driver_id  = $this->ion_auth->get_user_id();
		$shuttle_no = "";
		$travel_location_cost_id = "";
		$param 	 = ($this->input->post('date_key')) ? $this->input->post('date_key') : 'by_pick_date';

		if($param != "" && ((bool)strtotime($param))) {
			$date 	 = date('Y-m-d', strtotime($param));
			$records = $this->base_model->getDriverShuttles($driver_id, $shuttle_no, $travel_location_cost_id, $date);
		}


		$this->data['records']			= $records;
		$this->data['param']			= $param;
		$this->data['active_menu'] 		= "assigned_shuttles";
		$this->data['heading'] 			= (isset($this->phrases["assigned shuttles"])) ? $this->phrases["assigned shuttles"] : "Assigned Shuttles";
		$this->data['heading'] 			.= " >> ".humanize($param);
		$this->data['title'] 			= (isset($this->phrases["assigned shuttles"])) ? $this->phrases["assigned shuttles"] : "Assigned Shuttles";
		$this->data['content'] 			= 'driver/assigned_shuttles';
		$this->data['css_type'] 		= array('datatable');
		$this->_render_page('templates/driver_template', $this->data);


	}


	/***** VIEW PASSENGER *****/
	function view_passenger($shuttle_no = '', $travel_location_cost_id = '', $pick_date = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_driver()) {
			redirect('auth/login');
		}


		$driver_id = $this->ion_auth->get_user_id();

		if(!isDriverzShuttle($driver_id, $shuttle_no, $travel_location_cost_id)) {

			$this->prepare_flashmessage("You are not assigned to this Shuttle.", 1);
			redirect('driver/assigned_shuttles');
		}


		$shuttle_details = $this->base_model->getDriverShuttles($driver_id, $shuttle_no, $travel_location_cost_id, $pick_date);

		$records = $this->base_model->getShuttlePassenger($driver_id, $shuttle_no, $travel_location_cost_id, $pick_date);

		$this->data['records']			= $records;
		$this->data['shuttle_details']	= $shuttle_details;
		$this->data['active_menu'] 		= "assigned_shuttles";
		$this->data['heading'] 			= (isset($this->phrases["shuttle passenger"])) ? $this->phrases["shuttle passenger"] : "Shuttle Passenger";
		$this->data['title'] 			= (isset($this->phrases["shuttle passenger"])) ? $this->phrases["shuttle passenger"] : "Shuttle Passenger";
		$this->data['content'] 			= 'driver/shuttle_passenger';
		$this->data['css_type'] 		= array('datatable');
		$this->_render_page('templates/driver_template', $this->data);
	}


	function updatePassengerStatus()
	{
		$ride_status  = $this->input->post('ride_status');
		if($ride_status == "picked_up")
			$ride_status = "on_board";
		$passenger_id = $this->input->post('passenger_id');
		$shuttle_no   = $this->input->post('shuttle_no');
		$travel_location_cost_id   = $this->input->post('travel_location_cost_id');
		$pick_date    = $this->input->post('pick_date');
		$booking_id   = $this->input->post('booking_id');

		if(!($passenger_id > 0)) {

			redirect('driver/assigned_shuttles');
		}


		if($this->base_model->update_operation(array('ride_status' => $ride_status), 'bookings_passengers', array('passenger_id' => $passenger_id))) {

			if($ride_status == "dropped_off") { //If any passenger is dropped_off update the whole ticket as dropped_off

				$this->base_model->update_operation(array('ride_status' => $ride_status), 'bookings', array('id' => $booking_id));
			}

			$this->prepare_flashmessage("Passenger status updated successfully.", 0);
		}

		if($shuttle_no != "" && $travel_location_cost_id > 0 && $pick_date != "")
			redirect('driver/view_passenger/'.$shuttle_no.'/'.$travel_location_cost_id.'/'.$pick_date);
		else
			redirect('driver/assigned_shuttles');
	}


	


















}
/* End of file Driver.php */
/* Location: ./application/controllers/Driver.php */
