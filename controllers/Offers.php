<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offers extends MY_Controller
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
	| MODULE: 			Offers
	| -----------------------------------------------------
	| This is offers module controller file.
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

		$offers = $this->base_model->fetch_records_from('offers', array('status' => 'Active', 'expiry_date >=' => date('Y-m-d')));
		$this->data['offers'] 	= $offers;

		$this->data['title'] 	= (!empty($this->phrases["offers"])) ? 
										$this->phrases["offers"] : 
										"Offers";
		$this->data['active_class'] = 'offers';
		$this->data['content'] 		= 'site/offers';
		$this->_render_page('templates/site_template', $this->data);
	}
	
	/**
	* This function will validate the entered coupon
	* @param string $coupon_code
	* @return Object
	*/
	function validatecoupon()
	{
		if($this->input->is_ajax_request())
		{
			$result = array('status' => 0, 'message' => 'Some thing went wrong. Please try again.');
			$coupon_code = $this->input->post('code');
			$journey_booking_details = $this->session->userdata('journey_booking_details');
			//neatPrint($journey_booking_details);
			if(count($journey_booking_details) > 0)
			{
				$basic_fare = 0;
				$basic_fare_onward = (isset($journey_booking_details['onward']['basic_fare'])) ? $journey_booking_details['onward']['basic_fare'] : 0;
				$basic_fare_return = (isset($journey_booking_details['return']['basic_fare'])) ? $journey_booking_details['return']['basic_fare'] : 0;
				$basic_fare = $basic_fare_onward + $basic_fare_return;
				
				$service_charge = 0;
				$service_charge_onward = (isset($journey_booking_details['onward']['service_charge'])) ? $journey_booking_details['onward']['service_charge'] : 0;
				$service_charge_return = (isset($journey_booking_details['return']['service_charge'])) ? $journey_booking_details['return']['service_charge'] : 0;
				$service_charge = $service_charge_onward + $service_charge_return;
				
				$insurance = 0;
				$insurance_onward = (isset($journey_booking_details['onward']['insurance_amount'])) ? $journey_booking_details['onward']['insurance_amount'] : 0;
				$insurance_return = (isset($journey_booking_details['return']['insurance_amount'])) ? $journey_booking_details['return']['insurance_amount'] : 0;
				$insurance = $insurance_onward + $insurance_return;
				
				$disount_amount = (isset($journey_booking_details['disount_amount'])) ? $details['disount_amount'] : 0;
				
				$total_fare = $basic_fare + $service_charge + $insurance;
				//echo $basic_fare.'##'.$service_charge.'##'.$insurance;
				//neatPrint($journey_booking_details);
				//die();
				$user_id = ($this->ion_auth->logged_in()) ? $this->ion_auth->get_user_id() : 0;
				if($total_fare <= 0)
				{
					$result = array('status' => 0, 'message' => getPhrase('Total fare should be greater than zero to apply a coupon'));
				}
				$recs = $this->base_model->validateCoupon($coupon_code, $total_fare, $user_id, $basic_fare, $insurance);
				
				$response = json_decode($recs);
				$temp = $total_fare-$response->result->actual_discount;
				if($temp < 0)
					$temp = 0;
				$response->result->total_fare_display = number_format($temp, 2);
				//neatPrint( $response );
				
				if($response->status == 0)
				{
					$result = array('status' => 0, 'message' => $response->message);
				}
				else
				{
					$result = array('status' => 1, 'result' => $response->result);
					if(isset($journey_booking_details['onward']) && isset($journey_booking_details['return']))
					{
						$discount = $response->result->actual_discount/2;
						$journey_booking_details['onward']['discount_amount'] = $discount;
						$journey_booking_details['return']['discount_amount'] = $response->result->actual_discount - $discount; //This is to avoid any paisa or penny
					}
					else
					{
						if(isset($journey_booking_details['onward']))
							$journey_booking_details['onward']['discount_amount'] = $response->result->actual_discount;
						elseif(isset($journey_booking_details['return']))
							$journey_booking_details['return']['discount_amount'] = $response->result->actual_discount;
					}
					
					$price_details = $journey_booking_details['onward']['price_details'];
					if(!empty($price_details))
					{
						foreach($price_details as $key => $pd)
						{
							$journey_booking_details['onward']['price_details'][$key]['discount'] = $response->result->actual_discount;
							break; //We are adding discount to first vehicle, to avoid confusion
						}
					}
					
					$journey_booking_details['disount_amount'] = $response->result->actual_discount;
					$journey_booking_details['discount_details'] = 
					array(
					'offer_id' => $response->result->offer_id,
					'user_id' => $response->result->user_id,
					'ip_address' => $response->result->ip_address,
					'disount_amount' => $response->result->actual_discount,
					);
					//neatPrint($journey_booking_details);
					$this->session->set_userdata('journey_booking_details', $journey_booking_details);
				}
				
			}
			echo json_encode($result);
		}
	}
}
/* End of file Offers.php */
/* Location: ./application/controllers/Offers.php */
