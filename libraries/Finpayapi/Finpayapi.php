<?php 
class Finpayapi
{
 	private $username;
 	public $password;
	private $mode;
	private $timeout;
	private $bill_host;
	// codeigniter instance
    private $_ci;

	function __construct()
	{
		$this->_ci = & get_instance();
		$query = 'SELECT * FROM '.$this->_ci->db->dbprefix('gateways').' g INNER JOIN '.$this->_ci->db->dbprefix('gateways_fields').' gf ON g.`gateway_id`=gf.`gateway_id` LEFT JOIN '.$this->_ci->db->dbprefix('gateways_fields_values').' gfv ON gf.`field_id` = gfv.`gateway_field_id` WHERE g.`gateway_title`="Finpayapi" ORDER BY gf.field_order ASC';		
		$gateway_details = $this->_ci->base_model->fetch_records_from_query_object( $query );
		
		$this->mode = 'sandbox';
		$this->bill_host = 'https://sandbox.finpay.co.id/servicescode/';
		if(count($gateway_details) > 0) {
			foreach($gateway_details as $selectedgateway) {
				switch($selectedgateway->field_key)
				{
					case 'user_name':
						$this->username = $selectedgateway->gateway_field_value;
					break;
					case 'password':
						$this->password = $selectedgateway->gateway_field_value;
					break;
					case 'mode':
						$this->mode = $selectedgateway->gateway_field_value;
					break;
					case 'timeout':
						$this->timeout = $selectedgateway->gateway_field_value;
					break;
				}						
			}
		}
		if($this->mode == 'live')
			$this->bill_host = 'https://billhosting.finnet-indonesia.com/prepaidsystem/195/';
	}
	
	function curl_post($url, $postdata, $timeout=0){
		$sentdata = '';
		foreach($postdata as $name=>$value){
			$sentdata .= $name.'='.$value.'&';
		}
		$sentdata = rtrim($sentdata,'&');
		$ssl_active = false;
		if(strtolower(substr($url,0,5))=="https"){
			$ssl_active = true;
		}
		$channel = curl_init($url);
		curl_setopt ($channel, CURLOPT_HEADER, false);
		curl_setopt ($channel, CURLINFO_HEADER_OUT, false);
		curl_setopt	($channel, CURLOPT_POST, 1);
		curl_setopt	($channel, CURLOPT_POSTFIELDS, $sentdata);
		curl_setopt	($channel, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt ($channel, CURLOPT_ENCODING, "");
		curl_setopt ($channel, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($channel, CURLOPT_AUTOREFERER, 1);
		curl_setopt ($channel, CURLOPT_URL, $url);
		if($ssl_active==true){
			//curl_setopt ($channel, CURLOPT_PORT , 443);
			curl_setopt ($channel, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt ($channel, CURLOPT_SSL_VERIFYHOST, 0);
		}
		if($timeout>0){
			curl_setopt ($channel, CURLOPT_CONNECTTIMEOUT, $timeout );
			curl_setopt ($channel, CURLOPT_TIMEOUT, $timeout );
		}
		curl_setopt ($channel, CURLOPT_MAXREDIRS, 10);
		curl_setopt ($channel, CURLOPT_VERBOSE, 1);
		$output = curl_exec($channel);
		curl_close 	($channel);
		return $output;
	}

	function mer_signature($array){
		$output = '';
		foreach($array as $key=>$val){
			if(!empty($val)){
				$output .= $val.'%';
			}
		}
		return strtoupper($output);
	}

	function check_mer_signature($mer_signature,$array,$password){
		$comparator = $this->mer_signature($array).$password;
		if(strtoupper($mer_signature)==strtoupper($this->hash256($comparator))){
			return true;
		}else{
			return false;
		}
	}

	function hash256($input){
		return hash("sha256",$input);
	}

	function writeLog($text,$prefix='195log'){
		$fileurl = ''.$prefix.'_'.date('Ymd').'.txt';
		if(file_exists($fileurl)){
			if (!$handle = fopen($fileurl, 'a+')) {
				echo 'Cannot open file ('.$fileurl.')';
				exit;
			}
		}else{
			if (!$handle = fopen($fileurl, 'w')) {
				echo 'Cannot create file ('.$fileurl.')';
				exit;
			}
			@chmod($fileurl,0777);
		}
		if (fwrite($handle, $text."
	") === FALSE) {
			echo 'Cannot write to file ('.$fileurl.')';
			exit;
		}
		fclose($handle);
	}
	
	function call_server($post_data = array())
	{
		/* CREATE SIGNATURE */
		$mer_password = $this->password; //IMPORTANT!
		$postdata = array(
			'merchant_id' => $this->username,  //IMPORTANT!
			'invoice' => $post_data["invoice"],  //IMPORTANT!
			'amount' => $post_data["amount"],  //IMPORTANT!
			'add_info1' => isset($post_data["add_info1"]) ? $post_data["add_info1"] : '',  //Customer Name //IMPORTANT!
			'add_info2' => isset($post_data["add_info2"]) ? $post_data["add_info2"] : '',
			'add_info3' => isset($post_data["add_info3"]) ? $post_data["add_info3"] : '',
			'add_info4' => isset($post_data["add_info4"]) ? $post_data["add_info4"] : '',
			'add_info5' => isset($post_data["add_info5"]) ? $post_data["add_info5"] : '',
			'timeout' => ($this->timeout == '') ? '30' :$this->timeout , //60 Menit (Expired Date)  //IMPORTANT!
			'return_url' => isset($post_data["return_url"]) ? $post_data["return_url"] : '' //IMPORTANT! CHANGE THIS WITH YOUR RETURN TARGET URL!!!
		);
		
		$mer_signature =  $this->mer_signature($postdata).$mer_password;  //IMPORTANT!
		
		/* DATA FOR SENT */
		$postdata = array(
			'mer_signature' => strtoupper($this->hash256($mer_signature)),  //IMPORTANT!
			'merchant_id' => $postdata['merchant_id'],  //IMPORTANT!
			'invoice' => $postdata['invoice'],  //IMPORTANT!
			'amount' => $postdata['amount'],  //IMPORTANT!
			'add_info1' => $postdata['add_info1'], //Customer Name //IMPORTANT!
			'add_info2' => $postdata['add_info2'],
			'add_info3' => $postdata['add_info3'],
			'add_info4' => $postdata['add_info4'],
			'add_info5' => $postdata['add_info5'],
			'timeout' => $postdata['timeout'], //IMPORTANT!
			'return_url' => $postdata['return_url'] //IMPORTANT!
		);
		/* END DATA FOR SENT */
		$respon = $this->curl_post($this->bill_host.'va-request-02111.php', $postdata);
		
		return $respon;
	}
}
?>
