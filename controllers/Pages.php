<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends MY_Controller
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
	| MODULE: 			Pages
	| -----------------------------------------------------
	| This is Pages module controller file.
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
	function index( $id )
	{
		if(empty($id))
		{
			$this->prepare_flashmessage((isset($this->phrases["wrong operation"])) ? 
					$this->phrases["wrong operation"] : "Wrong Operation"."." , 1);
			redirect('pages/error');
		}
		$this->data['record'] = $this->base_model->fetch_records_from('pages', array('page_id' => $id, 'page_status' => 'Active'));
		if(count($this->data['record']) == 0)
		{
			$this->prepare_flashmessage((isset($this->phrases["wrong operation"])) ? 
					$this->phrases["wrong operation"] : "Wrong Operation"."." , 1);
			redirect('pages/error');
		}
		/*
		$this->data['page_deading'] = (isset($this->phrases["welcome to point to point transfers"])) ? 
										$this->phrases["welcome to point to point transfers"] : 
										"Welcome to Shuttle booking";
		*/
		$this->data['title'] = $this->data['record'][0]->page_title;
		$this->data['active_class'] = str_replace(' ', '_', strtolower($this->data['record'][0]->page_title));
		$this->data['content'] 	= 'site/pages/index';
		$this->_render_page('templates/site_template', $this->data);
	}
	
	function error()
	{
		$this->data['content'] 	= 'site/pages/error';
		$this->_render_page('templates/site_template', $this->data);
	}
	
	function getvehicles()
	{
		$category = $this->input->post('category');
		$start_id = $this->input->post('start_id');
		$result_no = $this->input->post('result_no');
		$vehicles = json_decode($this->base_model->getvehicles($category, $start_id, $result_no));
		//print_r($vehicles);
		$html = 'No Vehicles';
		$cas = $this->input->post('cas');
		
		if(count($vehicles->records) > 0)
		{
			$html = '';
			foreach($vehicles->records as $vehicle)
			{
				$image = base_url() . 'uploads/vehicle_images/';
				if($vehicle->image != "" && file_exists('uploads/vehicle_images/'.$vehicle->image))
					$image .= $vehicle->image;
				else
					$image .= 'default-car.jpg';
				$html .= '<li><img src="' . $image . '"> 
<h3>'.$this->config->item('site_settings')->currency_symbol . $vehicle->cost.'</h3> 
<h4>'.$vehicle->name." - ".$vehicle->model.'</h4> 
<span> <i class="fa fa-car"></i> '.$vehicle->passenger_capacity.' </span>
<span> <i class="fa fa-car"></i> '.$vehicle->large_luggage_capacity.' </span>
<span> <i class="fa fa-car"></i> '.$vehicle->small_luggage_capacity.' </span>
 </li>';
			}
		}
		echo json_encode(array('html' => $html, 'cas' => $cas, 'records' => count($vehicles->records), 'totalvehicles' => $vehicles->totalcount, 'result_no' => $result_no));
	}
	
}
/* End of file Welcome.php */
/* Location: ./application/controllers/Welcome.php */
