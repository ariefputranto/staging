<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Executive extends MY_Controller

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
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_executive()) {
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
		$this->data['title'] 					= "Executive Dashboard";
		$this->data['content']					= 'executive/dashboard';
		$this->_render_page('templates/executive_template', $this->data);

	}
	
	/****** VIEW BOOKINGS - START ******/
	function viewBookings($param1 = "all", $param2 = '', $param3 = '')
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_executive()) {
			redirect('auth/login');
		}


		$where['1']	= "1";
		$content = 'executive/bookings/bookings_list';

		/* Delete Booking Details */
		if($param1 == "delete" && $param2 > 0) {
			$this->check_isdemo(base_url() . 'executive/viewBookings');
			if($this->base_model->delete_record('bookings', array('id' => $param2))) {

				$this->prepare_flashmessage(
				(isset($this->phrases["booking has been deleted successfully"])) ? 
				$this->phrases["booking has been deleted successfully"] : 
				"Booking has been deleted successfully".".", 0);
				redirect('executive/viewBookings');

			}
		}


		/* Update Booking Status and Send an Email to User|Client  */
		if(($param1 == "Confirmed" || $param1 == "Cancelled") && $param2 > 0) {
			
			$updata['booking_status'] = $param1;

			$updata['cancelled_on'] = ($param1 == "Cancelled") ? time() : '';

			if($this->base_model->update_operation($updata, 'bookings', array('id' => $param2))) {

				$journey_details = array();
				$booking_details = $this->base_model->fetch_records_from('bookings', array('id' => $param2));
				
				if(count($booking_details) >0) {

					foreach($booking_details[0] as $key=>$val)
						$journey_details[$key] = $val;

					/* Send Confirmation|Cancellation Email */
					$message = $this->load->view('email/booking_status_email', $journey_details, true);

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
				}


				$msg_txt1 = (isset($this->phrases["booking has been"])) ? $this->phrases["booking has been"] : "Booking has been";

				$msg_txt2 = (isset($this->phrases[$param1])) ? $this->phrases[$param1] : $param1;

				$this->prepare_flashmessage($msg_txt1." ".$msg_txt2.".", 0);
				redirect('executive/viewBookings');

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
			$content = 'executive/bookings/booking_details';
			$passengers = $this->db->query('SELECT bp.* FROM '.$this->db->dbprefix('bookings').' b INNER JOIN '.$this->db->dbprefix('bookings_passengers').' bp ON b.id = bp.booking_id WHERE b.id = '.$param2)->result();
			
			$passengers_infants = $this->db->query('SELECT bp.* FROM '.$this->db->dbprefix('bookings').' b INNER JOIN '.$this->db->dbprefix('bookings_passengers_infants').' bp ON b.id = bp.booking_id WHERE b.id = '.$param2)->result();
		}

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
		$this->_render_page('templates/executive_template', $this->data);
	}
	/****** VIEW BOOKINGS - END ******/

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
	/****** Executive PROFILE ******/
	public function profile()
	{

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_executive()) {
			redirect('auth/login');
		}

		if ($this->input->post()) {
			$this->check_isdemo(base_url() . 'executive/profile');
			$this->form_validation->set_rules(
			'first_name', 
			(isset($this->phrases["first name"])) ? $this->phrases["first name"] : "First Name", 
			'trim|required'
			);
			/*$this->form_validation->set_rules(
			'email', 
			(isset($this->phrases["email"])) ? $this->phrases["email"] : "Email", 
			'valid_email|required'
			);*/
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

				$inputdata['first_name'] 	= $this->input->post('first_name');
				$inputdata['last_name'] 	= $this->input->post('last_name');
				$inputdata['display_name'] 	= $this->input->post('first_name');
				$inputdata['username'] 	= $inputdata['first_name'].' '.$inputdata['last_name'];
				//$inputdata['email'] 			= $this->input->post('email');
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
					redirect('executive/profile', 'refresh');
				}
				else {
					$this->prepare_flashmessage(
					(isset($this->phrases["unable to update profile"])) ? 
					$this->phrases["unable to update profile"] : 
					"Unable to update profile"."." , 1);
					redirect('executive/profile');
				}
			}
		}

		$admin_details 							= $this->base_model->fetch_records_from('users', array(
			'id' => $this->session->userdata('user_id')
		));
		if(count($admin_details) > 0) $admin_details = $admin_details[0];

		$this->data['admin_details'] 	= $admin_details;
		$this->data['active_menu'] 		= "admin_profile";
		$this->data['heading'] 			= (isset($this->phrases["admin profile"])) ? $this->phrases["admin profile"] : "Executive Profile";
		$this->data['title'] 			= (isset($this->phrases["admin profile"])) ? $this->phrases["admin profile"] : "Executive Profile";
		$this->data['content'] 			= 'executive/executive_profile';
		$this->_render_page('templates/executive_template', $this->data);
	}
	
	/****** Executive change password ******/
	public function change_password()
	{

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_executive()) {
			redirect('auth/login');
		}
		$this->data['message'] = $this->session->flashdata('message');
		if ($this->input->post()) {
			$this->check_isdemo(base_url() . 'executive/change_password');
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
					redirect('executive/change_password');
				}
				else
				{
					$this->prepare_flashmessage((isset($this->phrases["unable to change password"])) ? $this->phrases["unable to change password"] : "Unable to Change Password".".",1);
					redirect('executive/change_password');
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

		$this->data['admin_details'] 	= $admin_details;
		$this->data['active_menu'] 		= "change_password";
		$this->data['heading'] 			= (isset($this->phrases["admin profile"])) ? $this->phrases["admin profile"] : "Executive Change Password";
		$this->data['title'] 			= (isset($this->phrases["admin profile"])) ? $this->phrases["admin profile"] : "Executive Change Password";
		$this->data['content'] 			= 'executive/change_password';
		$this->_render_page('templates/executive_template', $this->data);
	}


	


	/****** UPDATE BOOKING READ STATUS - START ******/
	function updateReadStatus()
	{
		$this->check_isdemo(base_url() . 'executive/viewBookings');
		$booking_id = $this->input->post('id');

		if($booking_id > 0) {

			if($this->base_model->update_operation(array('read_status' => '1'), 'bookings', array('id' => $booking_id)))
				echo 1;
			else echo 0;

		} else echo 0;

	}
	/****** UPDATE BOOKING READ STATUS - END ******/
}
/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */
