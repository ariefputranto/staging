<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller

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
	| MODULE: 			Reports
	| -----------------------------------------------------
	| This is Reports module controller file.
	| -----------------------------------------------------
	*/

	function __construct()
	{
		parent::__construct();
	}

	
	/****** Default Function ******/
	public function index()
	{
		redirect('/');
	}


	/****** OVERALL VEHICLES ******/
	function overallVehicles()
	{

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}


		$records = $this->base_model->run_query(
			'SELECT v.*,vc.category, 
			(SELECT count(IF(b.booking_status="Confirmed",1,NULL)) 
			FROM ' . DBPREFIX . 'bookings b 
			WHERE b.vehicle_selected=v.id) AS confirmed_bookings,
			(SELECT count(IF(b.booking_status="Cancelled",1,NULL)) 
			FROM '. DBPREFIX . 'bookings b 
			WHERE b.vehicle_selected=v.id) AS cancelled_bookings,
			(SELECT count(IF(b.booking_status="Pending",1,NULL)) 
			FROM ' . DBPREFIX . 'bookings b 
			WHERE b.vehicle_selected=v.id) AS pending_bookings,
			(SELECT count(*) FROM ' . DBPREFIX . 'bookings b 
			WHERE b.vehicle_selected=v.id) AS total_bookings 
			FROM ' . DBPREFIX . 'vehicle v, 
			' . DBPREFIX . 'vehicle_categories vc 
			WHERE vc.id=v.category_id ORDER BY v.id DESC');


		$overallVehicleCnt = $this->base_model->run_query(
			'SELECT SUM(v.total_vehicles) as cnt FROM ' . 
			DBPREFIX . 'vehicle v, ' . DBPREFIX . 'vehicle_categories vc 
			WHERE vc.id=v.category_id'
			);

		$overallVehicles = "";
		if(count($overallVehicleCnt) >0)
			$overallVehicles = $overallVehicleCnt[0]->cnt;


		$this->data['overallVehicles'] 	= $overallVehicles;
		$this->data['records']			= $records;
		$this->data['css_type'] 		= array("datatable");
		$this->data['active_menu'] 		= "reports";
		$this->data['heading'] 			= (isset($this->phrases["reports"])) ? $this->phrases["reports"] : "Reports";
		$this->data['sub_heading'] 		= (isset($this->phrases["overall vehicles"])) ? $this->phrases["overall vehicles"] : "Overall Vehicles";
		$this->data['title']	 		= (isset($this->phrases["overall vehicle reports"])) ? $this->phrases["overall vehicle reports"] : "Overall Vehicle Reports";
		$this->data['content'] 			= "admin/reports/overall_vehicles";
		$this->_render_page('templates/admin_template', $this->data);
	}


	/****** User Payments ******/
	function payments()
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect('auth/login');
		}


		$records = $this->base_model->run_query(
			'SELECT b.*,v.name as vehicle_name,v.model 
			FROM ' . DBPREFIX . 'bookings b, ' . DBPREFIX . 'vehicle v 
			WHERE v.id=b.vehicle_selected AND 
			((b.payment_type="paypal" OR b.payment_type="payu") 
			AND b.payment_received=1 AND  b.booking_status!="Cancelled") OR 
			(b.payment_type="cash" AND b.booking_status="Confirmed") 
			GROUP BY b.id ORDER BY b.bookdate DESC');


		$this->data['records']			= $records;
		$this->data['css_type'] 		= array("datatable");
		$this->data['active_menu'] 		= "reports";
		$this->data['heading'] 			= (isset($this->phrases["reports"])) ? $this->phrases["reports"] : "Reports";
		$this->data['sub_heading'] 		= (isset($this->phrases["payments"])) ? $this->phrases["payments"] : "Payments";
		$this->data['title']	 		= (isset($this->phrases["payment reports"])) ? $this->phrases["payment reports"] : "Payment Reports";
		$this->data['content'] 			= "admin/reports/payment_reports";
		$this->_render_page('templates/admin_template', $this->data);
	}

	
}



/* End of file Reports.php */
/* Location: ./application/controllers/Reports.php */
