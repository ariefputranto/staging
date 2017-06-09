<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


		$CI =& get_instance();
     	$CI->load->database();
		$CI->load->model('base_model');
		$CI->load->library('ion_auth');
		$CI->load->helper('inflector');

		/****** Get Site Settings ******/
		$results = $CI->db->get_where('site_settings')->result();
		$CI->config->set_item('site_settings', $results[0]);

		/****** Get Email Settings ******/
		$emailSettings = $CI->db->get('email_settings')->row();
		$CI->config->set_item('emailSettings', $emailSettings);	

		/****** Get SEO Settings ******/
		$seoSettings = $CI->db->get('seo_settings')->row();
		$CI->config->set_item('seoSettings', $seoSettings);	

		/****** Get PayU Settings ******/
		$payuSettings= $CI->db->get('payu_settings')->row();
		$CI->config->set_item('payu_settings', $payuSettings);

		/****** Get Count of Today's Bookings ******/
		$site_country 	= $CI->config->item('site_settings')->site_country;
		$site_time_zone = $CI->config->item('site_settings')->site_time_zone;

		setlocale(LC_MONETARY, "en_".strtoupper($site_country)); //'en_US'
		date_default_timezone_set($site_time_zone);
		$CI->db->where('date_of_booking', date('Y-m-d'));
		$count_of_todayz_bookings = $CI->db->count_all_results('bookings');
		$CI->config->set_item('count_of_todayz_bookings', $count_of_todayz_bookings);


		/****** Load Words of Selected Language ******/
		$lang_words = $CI->base_model->getLanguageWords($CI->config->item('site_settings')->language_id);

		if(count($lang_words) > 0) {

			foreach($lang_words as $word) {

				$config['words'][$word->phrase]	= humanize($word->text);

			}
		}
		
		//Dynamic Pages
		$config['dynamic_pages'] = $CI->base_model->fetch_records_from('pages', array('page_status' => 'Active'));
		
		//vehicle_typeshome
		$config['vehicle_typeshome'] = $CI->base_model->fetch_records_from('vehicle_categories', array('status' => 'Active'));
		
		//Locations
		$config['vehicle_typeshome'] = $CI->base_model->fetch_records_from('vehicle_categories', array('status' => 'Active'));



$config['use_mongodb'] = FALSE;



/*

| -------------------------------------------------------------------------

| MongoDB Collection.

| -------------------------------------------------------------------------

| Setup the mongodb docs using the following command:

| $ mongorestore sql/mongo

|

*/

$config['collections']['users']          = 'users';

$config['collections']['groups']         = 'groups';

$config['collections']['login_attempts'] = 'login_attempts';



/*

| -------------------------------------------------------------------------

| Tables.

| -------------------------------------------------------------------------

| Database table names.

*/

$config['tables']['users']           = 'users';

$config['tables']['groups']          = 'groups';

$config['tables']['users_groups']    = 'users_groups';

$config['tables']['login_attempts']  = 'login_attempts';

$config['tables']['buser_customers'] = 'buser_customers';

$config['tables']['roles']  		 = 'roles';



/*

 | Users table column and Group table column you want to join WITH.

 |

 | Joins from users.id

 | Joins from groups.id

 */

$config['join']['users']  = 'user_id';

$config['join']['groups'] = 'group_id';







/* End of file ion_auth.php */

/* Location: ./application/config/ion_auth.php */

