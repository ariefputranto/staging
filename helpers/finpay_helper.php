<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('call_finpay'))
{
	function call_finpay($params)
	{
		if( !is_array( $params ) )
		{
			return 'Parameters should be in form of array';
		}
		$sparator = '%';
		$source = strtoupper($params['merchant_id']).$sparator.$params['merchant_password'].$sparator.strtoupper($params['trax_type']).$sparator.strtoupper($params['amount'].$sparator.strtoupper($params['invoice']));
		$params['mer_signature'] = strtoupper(hash('sha256',$source));
		
		//neatPrint($params);

		$required = array('item_desc', 'merchant_id', 'amount', 'trax_type', 'mer_signature', 'return_url', 'invoice', 'cust_id');
		$missed = '';
		$procede = TRUE;
		for( $i = 0; $i < count( $required ); $i++ )
		{
			if( !in_array( $required[$i], array_keys( $params ) ) )
			{
				$missed .= $required[$i] . ', ';
				$procede = FALSE;
			}
		}
		
		if( !$procede )
		{
			return 'Parameters missed <b>' . $missed . '</b>';
		}		
				
		$str = '<form name="finpayForm" method="post" action="'.$params['action'].'">';
		foreach( $params as $key => $val )
		{
			if(!in_array($key, array('action', 'merchant_password'))) {
				$str .= '<input type="hidden" name="'.$key.'" value="'.$val.'">';
			}
		}
		
		$str .= '</form>';		
		$str .= '
			<script>
		window.onload = function() { 
		document.finpayForm.submit();
		}
		</script>';		
		return $str;
	}
}


/* End of file download_helper.php */
/* Location: ./system/helpers/download_helper.php */