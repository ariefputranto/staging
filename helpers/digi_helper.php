<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/****** Send Email ******/
if ( ! function_exists('sendEmail'))
{
	function sendEmail($from = null, $to = null, $sub = null, $msg = null, $reply_to = null, $cc = null, $bcc = null, $attachment = null)
  	{
		
		if(!filter_var($from, FILTER_VALIDATE_EMAIL) ) {
			return false;
		}
		
		$CI = & get_instance();
		if($msg != "") {		
			
			$CI->load->library('email');			
			$CI->email->clear();	

			if($CI->config->item('emailSettings')->mail_config == "webmail"){
				$config = Array(
						'protocol' 	=> 'smtp',
						'smtp_host' => $CI->config->item('emailSettings')->smtp_host,
						'smtp_port' => $CI->config->item('emailSettings')->smtp_port,
						'smtp_user' => $CI->config->item('emailSettings')->smtp_user,
						'smtp_pass' => $CI->config->item('emailSettings')->smtp_password,
						'charset' 	=> 'utf-8',
						'mailtype' 	=> 'html',
						'newline' 	=> "\r\n",
						'wordwrap' 	=> TRUE
					);
				$CI->email->initialize($config);

				$CI->email->from($CI->config->item('emailSettings')->smtp_user, $CI->config->item('site_settings')->site_title);

				$CI->email->to($to);

				if($reply_to != "" && filter_var($reply_to, FILTER_VALIDATE_EMAIL))
					$CI->email->reply_to($reply_to);
				if($cc != "" && filter_var($cc, FILTER_VALIDATE_EMAIL))
					$CI->email->cc($cc);
				if($bcc != "" && filter_var($bcc, FILTER_VALIDATE_EMAIL))
					$CI->email->bcc($bcc);

				if($attachment != "")
					$CI->email->attach($attachment);

				$CI->email->subject($sub);
				$CI->email->message($msg);

					if( $CI->email->send() )
					return true;
			}
			elseif($CI->config->item('emailSettings')->mail_config == 'default')
			{		
				$config = array();
				$config['mailtype'] = 'html';
				$CI->email->initialize($config);				
				$CI->email->from($from, $CI->config->item('site_settings')->site_title);
				
				if($reply_to != "" && filter_var($reply_to, FILTER_VALIDATE_EMAIL))
					$CI->email->reply_to($reply_to);
				if($cc != "" && filter_var($cc, FILTER_VALIDATE_EMAIL))
					$CI->email->cc($cc);
				if($bcc != "" && filter_var($bcc, FILTER_VALIDATE_EMAIL))
					$CI->email->bcc($bcc);
				if($attachment != "")
					$CI->email->attach($attachment);				
				$CI->email->subject( $sub );
				$CI->email->message( $msg );
				if( $CI->email->send() )
					return true;
			}
			elseif($CI->config->item('emailSettings')->mail_config == 'defaultphp')
			{
					$from_name = $CI->config->item('site_settings')->site_title;
					//$from = $CI->config->item('emailSettings')->from_email;
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
					$headers .= "X-Priority: 1\r\n"; 
					$headers .= 'From: '.$from_name.'<'.$from.'>' . "\r\n";
					$headers .= 'Reply-To: ' . $from . "\r\n";
					$headers .= 'X-Mailer: PHP/' . phpversion();
					mail($to,$sub,$msg, $headers);
					return true;
			}
			/**end of  sendEmail through Web mail settings**/
			else {

				$CI->load->config('mandrill');

				$CI->load->library('mandrill');	

				$mandrill_ready = NULL;

				try {
					$CI->mandrill->init( $CI->config->item('mandrill_api_key') );
					$mandrill_ready = TRUE;
				} catch(Mandrill_Exception $e) {
					$mandrill_ready = FALSE;
				}

				if( $mandrill_ready ) {

					//Send us some email!
					$email = array(
						'html' => $msg, //Consider using a view file
						'text' => '',
						'subject' => $sub,
						'from_email' => $from,
						'from_name' => $CI->config->item('site_settings')->site_title,
						'to' => array(array('email' => $to )),
						);

						$result = $CI->mandrill->messages_send($email);
						print_r($result);die();
						if($result[0]['status']=='sent')
						return TRUE;
						else
						return FALSE;
				}
								
			}
		}
		return false;
    }
}


/****** Render Age based on DOB ******/
if ( ! function_exists('ageCalculator'))
{
	function ageCalculator($dob){

		if(!empty($dob)){

			$birthdate = new DateTime($dob);
			$today   = new DateTime('today');
			$age = $birthdate->diff($today)->y;
			return $age;

		} else {

			return 0;
		}
	}
}

if ( ! function_exists('is_date'))
{
	function is_date($date){

		if(!empty($date))
		{
			$parts = explode('-', $date);
			if(count($parts) == 3)
			return TRUE;
		else
			return FALSE;

		} else {

			return FALSE;
		}
	}
}


if ( ! function_exists('getDigiDays'))
{
	//$type = 0 --> $date1 is timestamp;
	//$type = 1 --> $date1 is date;
	
	function getDigiDays($date1='', $type=0)
  	{    
  		   
  		if($date1=='')
  			return $date1;
  		
		$your_date = $date1;
		
  		if($type==0)
  		$your_date = date('Y-m-d', $your_date);

  		$now = time(); // or your date as well
		$your_date = strtotime($your_date);
		$datediff = $now - $your_date;
		$days = floor($datediff/(60*60*24));
		return $days;
               
    }
}

//Get User INfo
if( ! function_exists('getUserRec')){
	function getUserRec($userId=''){
		
		$CI =& get_instance();
		$user = $CI->ion_auth->user()->row();
		if($userId!='' && is_numeric($userId)) {
			$user = $CI->ion_auth->user($userId)->row();
		}
		
		return $user;
	}
}


if ( ! function_exists('cleanString'))
{
	function cleanString($str) {

	$clean = preg_replace ('/[^\p{L}\p{N}]/u', '-', $str);

	return $clean;
}
}

if ( ! function_exists('getSelected'))
{
	function getSelected($submit_button, $field, $record) 
	{
		$val = '';
		if(isset($_POST[$submit_button]))
		{
			$val = isset($_POST[$field]) ? $_POST[$field] : '';
		}
		elseif(isset($record->$field))
		{
			$val = $record->$field;
		}
		return $val;
	}
}

if ( ! function_exists('getPhrase'))
{
	function getPhrase($key) 
	{
		$CI =& get_instance();
		$val = (isset($CI->phrases[$key])) ? $CI->phrases[$key] : $key;
		return $val;
	}
}

if ( ! function_exists('neatPrint'))
{
	function neatPrint($key = '', $die = TRUE) 
	{
		$CI =& get_instance();
		echo '<pre>';
		if($key != '') 
			print_r($key);
		else
			print_r($CI->input->post());
		if($die) die();
	}
}
	/** 
	* This function will send the SMS to given number
	* @param string $to_number
	* @param string $message
	* @return bool	
	*/
	function send_sms($to_number = '', $message = '')
	{
		/*SMS Sending Start*/
		$CI =& get_instance();
		$smsinfo = '';
		$sent_status = TRUE;
		$sms_hd_txt = getPhrase('SMS Sent Failed. Reason');
		$smsgateway_details = $CI->base_model->get_sms_gateway();
		if(count($smsgateway_details) > 0)
		{
			$smsmessage = $message;
			$smsto = $to_number;
			$smstoadmin = $CI->config->item('site_settings')->phone_code . $CI->config->item('site_settings')->phone;
			if($smsgateway_details[0]->gateway_title == 'Cliakatell') 
			{
				$CI->load->library('clickatell');
				$response = $CI->clickatell->send_message($smsto, $smsmessage);
				$response = $CI->clickatell->send_message($smstoadmin, $smsmessage);
				if($response === FALSE) {
					$smsinfo = $sms_hd_txt.' : ' . $CI->clickatell->error_message;
					$sent_status = FALSE;
				}
			}
			if($smsgateway_details[0]->gateway_title == 'Twilio') 
			{
				$CI->load->helper('ctech-twilio');
				$client = get_twilio_service();
				$twilioquery = 'SELECT * FROM '.$CI->db->dbprefix('gateways').' g INNER JOIN '.$CI->db->dbprefix('gateways_fields').' gf ON g.`gateway_id`=gf.`gateway_id` LEFT JOIN '.$CI->db->dbprefix('gateways_fields_values').' gfv ON gf.`field_id` = gfv.`gateway_field_id` WHERE g.`gateway_title`="Twilio" AND gf.field_key="number" ORDER BY gf.field_order ASC LIMIT 1';		
				$twiliogateway_details = $CI->base_model->fetch_records_from_query_object( $twilioquery );
				$twilio_number = $twiliogateway_details[0]->gateway_field_value;
				try {				
					$response = $client->account->messages->sendMessage($twilio_number,$smsto,$smsmessage);
					$response = $client->account->messages->sendMessage($twilio_number,$smstoadmin,$smsmessage);
				} catch (Exception $e ){										
					$smsinfo = $sms_hd_txt.' : ' . $e->getMessage();
					$sent_status = FALSE;
				}
			}
			if($smsgateway_details[0]->gateway_title == 'Nexmo') 
			{
				$CI->load->library('nexmo');
				$CI->nexmo->set_format('json');
				$from = '1234567890';
				$smstext = array(
					'text' => $smsmessage,
				);
				$response = $CI->nexmo->send_message($from, $smsto, $smstext); //SMS to User
				$response = $CI->nexmo->send_message($from, $smstoadmin, $smstext); //SMS to Admin
				$status = $response['messages'][0]['status'];
				if(isset($status) && $status != 0) {
					$smsinfo = $sms_hd_txt.' : ' . $response['messages'][0]['error-text'];
					$sent_status = FALSE;
				}
			}
			if($smsgateway_details[0]->gateway_title == 'Plivo') 
			{
				$CI->load->library('plivo');
				$sms_data = array(
					'src' => '919700376656', //The phone number to use as the caller id (with the country code). E.g. For USA 15671234567
					'dst' => $smsto, // The number to which the message needs to be send (regular phone numbers must be prefixed with country code but without the ‘+’ sign) E.g., For USA 15677654321.
					'text' => $smsmessage, // The text to send
					'type' => 'sms', //The type of message. Should be 'sms' for a text message. Defaults to 'sms'
				);									
				$response = $CI->plivo->send_sms($sms_data);
				
				$sms_data = array(
					'src' => '919700376656', //The phone number to use as the caller id (with the country code). E.g. For USA 15671234567
					'dst' => $smstoadmin, // The number to which the message needs to be send (regular phone numbers must be prefixed with country code but without the ‘+’ sign) E.g., For USA 15677654321.
					'text' => $smsmessage, // The text to send
					'type' => 'sms', //The type of message. Should be 'sms' for a text message. Defaults to 'sms'
				);									
				$response = $CI->plivo->send_sms($sms_data);
				
				if ($response[0] != '202')
				{
					$response = json_decode($response[1], TRUE);				
					$smsinfo = $sms_hd_txt.' : ' . $response["error"];
					$sent_status = FALSE;
				}
			}
			if($smsgateway_details[0]->gateway_title == 'Solutionsinfini') 
			{
				$CI->load->helper('solutionsinfini');
				$solution_object = new sendsms();
				$response = $solution_object->send_sms($smsto, $smsmessage, current_url());
				$response = $solution_object->send_sms($smstoadmin, $smsmessage, current_url());				
				if(strpos($response,'Message GID') === false) {
					$smsinfo = $sms_hd_txt.' : ' . $response;
					$sent_status = FALSE;
				}
			}
			
			if($smsgateway_details[0]->gateway_title == 'Gosms') 
			{
				$this->load->library('gosms');
				$errors = array(
					'1702' => 'Invalid Username or Password ',
					'1703' => 'Internal Server Error',
					'1704' => 'Data not found',
					'1705' => 'Process Failed',
					'1706' => 'Invalid Message',
					'1707' => 'Invalid Number',
					'1708' => 'Insufficient Credit',
					'1709' => 'Group Empty',
					
					'1711' => 'Invalid Group Name',
					'1712' => 'Invalid Group ID',
					'1713' => 'Invalid msgid',
					
					'1721' => 'Invalid Phonebook Name',
					'1722' => 'Invalid Phonebook ID',
					
					'1731' => 'User Name already exist',
					'1732' => 'Sender ID not valid',
					'1733' => 'Internal Error – please contact administrator',
					'1734' => 'Invalid client user name',
					'1735' => 'Invalid Credit Value ',
				);
				$gosms = new gosms();
				$response = $gosms->send_sms($smsto, $smsmessage);
				if(!in_array($response, array_keys($errors))) //Success
					$smsinfo = 'SMS Sent successfull : ' . $response;
				else {
					if(isset($errors[$response]))
					$smsinfo = $sms_hd_txt.' : Response code - ' . $errors[$response];
					else
					$smsinfo = $sms_hd_txt.' : Response code - ' . $response;
				}
			}
			
			$scheduledata = array(
			'gateway_id' => $smsgateway_details[0]->gateway_id,
			'gateway_title' => $smsgateway_details[0]->gateway_title,
			'to_number' => $smsto,
			'message' => $smsmessage,
			'resultmessage' => $smsinfo,
			'sent_datetime' => date('Y-m-d H:i:s'),
			);
			$CI->base_model->insert_operation($scheduledata, 'smslog');
		}
		/*SMS Sending End*/
		return $sent_status;
	}
	
	/** 
	* This function will generate random string of given length
	* @param int $length
	* @return string	
	*/
	function random_string($length = 6)
	{
    $chars =  '0123456789';

    $str = '';
    $max = strlen($chars) - 1;

    for ($i=0; $i < $length; $i++)
      $str .= $chars[rand(0, $max)];

    return $str;
  }
  
  
  /** 
	* This function used to get logged in user id
	* @param int $user_id
	* @return int	
	*/
  if( ! function_exists('getUserId'))
{
	function getUserId($user_id='')
	{
		$CI =& get_instance();
		$user_type='';
		if(getUserRec() != NULL)
		{
			if($user_id=='') 
			{
				$user_id = getUserRec()->id;
			}
			$user_groups = $CI->ion_auth->get_users_groups($user_id)->result();
			if($user_id != '')
				return $user_id;
			else
				return 0;
		}
		else
		{
			return 0;
		}
	}
}

/**
* This function will get the fare for the particular price selection
* @param int $find
* @param array $array
* @return decimal
*/
function getPrice($find, $array = array(), $seat_type = 'a')
{
	//neatPrint($array);
	$ret = 0;
	if($seat_type == 'a' || $seat_type == 'adult')
	{
		if(isset($array['fare']))
		{
			foreach($array['fare'] as $key => $val)
			{
				if($find == $key)
					$ret = $val;
			}
		}
	}
	elseif($seat_type == 'c')
	{
		if(isset($array['fare_c']))
		{
			foreach($array['fare_c'] as $key => $val)
			{
				if($find == $key)
					$ret = $val;
			}
		}
	}
	elseif($seat_type == 'i')
	{
		if(isset($array['fare_i']))
		{
			foreach($array['fare_i'] as $key => $val)
			{
				if($find == $key)
					$ret = $val;
			}
		}
	}
	return $ret;
}

/**
* This function will get the seat details for the particular price selection
* @param String $find
* @param array $array
* @return decimal
*/
function getSeat($price_set, $seatno, $array = array())
{
	$ret = FALSE;
	if(isset($array['seats']))
	{
		foreach($array['seats'] as $key => $val)
		{
			if($price_set == $key)
				$ret = TRUE;
		}
	}
	return $ret;
}

/**
* This function will check whether the particular priceset has seats or not
* @param String $find
* @param array $array
* @return decimal
*/
function get_seat_priceset($price_set, $array = array())
{
	$ret = FALSE;
	
	if(isset($array['seats']))
	{
		foreach($array['seats'] as $key => $val)
		{			
			if($val != '') 
					$ret = TRUE;			
		}
	}
	return $ret;
}

/**
* This function will check whether the particular priceset has seats or not
* @param String $find
* @param array $array
* @return decimal
*/
function get_seat_priceset_count($price_set, $array = array(), $session_data = array())
{
	$ret = $ret_c = $ret_i = 0;
	if(isset($array['seats']))
	{
		foreach($array['seats'] as $key => $val)
		{
			if($price_set == $key)
			$ret = $val;
		}
	}
	if(isset($array['seats_c']))
	{
		foreach($array['seats_c'] as $key => $val)
		{
			if($price_set == $key)
			$ret_c = $val;
		}
	}
	if(isset($array['seats_i']))
	{
		foreach($array['seats_i'] as $key => $val)
		{
			if($price_set == $key)
			$ret_i = $val;
		}
	}
	
	return ($ret+$ret_c+$ret_i);
}

/**
* This funciton will get the booked seats cound for a particular price set given
* @param int $price_set
* @param array 
* @return int
*/
function get_seat_priceset_booked_count($price_set, $array)
{
	$boooked_seats = 0;
	if(count($array) > 0)
	{
		foreach($array as $key => $val)
		{
			if($key == $price_set)
				$boooked_seats = $val;
		}
	}
	//echo $price_set;
	//neatPrint($array);
	return $boooked_seats;
}

function get_index_value($price_set, $array)
{
	$value = 0;
	if(count($array) > 0)
	{
		foreach($array as $key => $val)
		{
			if($key == $price_set)
				$value = $val;
		}
	}
	return $value;
}

function get_index_value2($index, $array)
{
	$value = 0;
	if(count($array) > 0)
	{
		$i = 0;
		foreach($array as $key => $val)
		{
			if($index == $i)
				$value = $key;
			$i++;
		}
	}
	return $value;
}

/**
* This function will search ids of an array in another ids
* @param array $source
* @param array $dest
* @retrun bool
*/
function is_found($source, $dest)
{
	$is_found = FALSE;
	if(!empty($source))
	{
		foreach($source as $key => $val)
		{
			if(in_array($val, $dest))
			{
				$is_found = TRUE;
				break;
			}
		}
	}
	return $is_found;
}

/**
* This function will return the variation title of the given price set
* @param int $price_set
* @param array $array
* @return bool
*/
function get_price_set_title($price_set, $array)
{
	$value = '';
	if(!empty($array) > 0)
	{
		foreach($array as $key => $val)
		{
			if($key == $price_set)
				$value = $val;
		}
	}
	return $value;
}

function combinations($arrays, $i = 0) {
    if (!isset($arrays[$i])) {
        return array();
    }
    if ($i == count($arrays) - 1) {
        return $arrays[$i];
    }
    // get combinations from subsequent arrays
    $tmp = combinations($arrays, $i + 1);

    $result = array();

    // concat each array from tmp with each element from $arrays[$i]
    foreach ($arrays[$i] as $v) {
        foreach ($tmp as $t) {
            $result[] = is_array($t) ? 
                array_merge(array($v), $t) :
                array($v, $t);
        }
    }

    return $result;
}

function isin_transitions($all, $search)
{
	$result = FALSE;
	if(!empty($all))
	{
		foreach($all as $row)
		{
			if(!empty($row))
			{
			if($row['from_location'] == $search['from_location'] && $row['to_location'] == $search['to_location'] && $row['travel_location_id'] == $search['travel_location_id'])
			return TRUE;	
			}
		}
	}
	return $result;
}

function find_record($start, $records)
{
	$res = array();
	foreach($records as $rec)
	{
		if($start == $rec->from_loc_id)
		{
			$res = $rec; break;
		}
	}
	return $res;
}

function find_record_new($tlc_id, $records)
{
	$res = array();
	foreach($records as $key => $val)
	{
		foreach($val as $key_1 => $val_1)
		{
			if($tlc_id == $val_1->tlc_id)
				$rec = $val_1;
		}
	}
	return $rec;
}

function timespan_new($from_time, $to_time, $from_time_zone, $to_time_zone)
{
	//echo $from_time;die('gggg');
	if($to_time_zone != '')
	$to_time = strtotime("$to_time_zone hours",$to_time);
	if($from_time_zone != '')
	$from_time = strtotime("$from_time_zone hours",$from_time);
	$diff = round(abs($to_time - $from_time) / 60,2);
	
	$str = '';
	if($diff >= 60)
	{
		$hours = (int)($diff / 60);
		$mins = (int)($diff % 60);
		$str = "$hours H";
		if($mins != '0')
			$str = $str . ' '.$mins .' M';
	}
	else
	{
		$str = $diff.' M';
	}
	echo $str;
}


if( ! function_exists('isDriverzShuttle'))
{
	function isDriverzShuttle($driver_id = '', $shuttle_no = '', $travel_location_cost_id)
	{
		if(!($driver_id > 0 && $shuttle_no != "" && $travel_location_cost_id > 0))
			return false;


		$CI = & get_instance();

		$driver_shuttles = $CI->db->query("SELECT shuttle_no FROM ".$CI->db->dbprefix('travel_location_costs')." WHERE status='Active' AND driver_id=".$driver_id." AND shuttle_no='".$shuttle_no."' AND id=".$travel_location_cost_id);

		if(count($driver_shuttles) > 0) {

			return true;
		}

		return false;
	}
}

function search_price_set($search_id, $set)
{
	$found = FALSE;
	if(!empty($set))
	{
		foreach($set as $key => $val)
		{
			if($key == $search_id)
				$found = TRUE;
		}
	}
	return  $found;
}

function replace_constants( $variables, $message ) {
	if ( is_array($variables) ) {
		foreach( $variables as $key => $val ) {
			$message = str_replace($key, $val, $message);
		}
	}
	return $message;
}
?>
