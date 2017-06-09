<?php 
class gosms
{
 	private $username;
 	private $password;
	// codeigniter instance
    private $_ci;

	function __construct()
	{
		$this->_ci = & get_instance();
		$query = 'SELECT * FROM '.$this->_ci->db->dbprefix('gateways').' g INNER JOIN '.$this->_ci->db->dbprefix('gateways_fields').' gf ON g.`gateway_id`=gf.`gateway_id` LEFT JOIN '.$this->_ci->db->dbprefix('gateways_fields_values').' gfv ON gf.`field_id` = gfv.`gateway_field_id` WHERE g.`gateway_title`="Gosms" ORDER BY gf.field_order ASC';		
		$gateway_details = $this->_ci->base_model->fetch_records_from_query_object( $query );
		
		if(count($gateway_details) > 0) {
			foreach($gateway_details as $selectedgateway) {
				switch($selectedgateway->field_key)
				{
					case 'user_id':
						$this->username = $selectedgateway->gateway_field_value;
					break;
					case 'password':
						$this->password = $selectedgateway->gateway_field_value;
					break;
				}						
			}
		}
	}

	/**
	 * function to send sms
	 * 
	 */
	function send_sms($to,$message)
	{
		$auth=MD5($this->username.$this->password.$to);
		$url="http://send.gosmsgateway.com:8080/web2sms/api/Send.aspx?username=".$this->username."&mobile=".$to."&message=".urlencode($message)."&password=".$this->password.'&auth='.$auth;
		
		return $this->process_url($url);		
	}

	function get_balance()
	{
		$auth=MD5($this->username.$this->password);
		//$url = 'http://send.gosmsgateway.com:8080/web2sms/creditsleft.aspx?username='.$this->username.'&password='.$this->password;
		$url = 'http://send.gosmsgateway.com:8080/web2sms/creditsleft.aspx?username='.$this->username.'&auth='.$auth;
		return $this->process_url($url);
	}
	
	function process_url($url)
	{
		$curlHandle=curl_init();
		curl_setopt($curlHandle, CURLOPT_URL, $url);
		curl_setopt($curlHandle, CURLOPT_HEADER, 0);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
		curl_setopt($curlHandle, CURLOPT_POST, 0);
		$output=curl_exec($curlHandle);
		if(!$output)
		$output =	'Curl error: ' . curl_error($curlHandle);
		curl_close($curlHandle);
		return $output;
	}
}
?>
