<?php

if (!class_exists('Finpayapi_Finpayapi')) {
	/**
	 * The main Twilio.php file contains an autoload method for its dependent
	 * classes, we only need to include the one file manually.
	 */
	include_once(APPPATH.'libraries/Finpayapi/Finpayapi.php');
}

/**
 * Return a twilio services object.
 *
 * Since we don't want to create multiple connection objects we
 * will return the same object during a single page load
 *
 * @return object Services_Twilio
 */
function get_finpayapi_service() {
	static $finpayapi_service;

	if (!($finpayapi_service instanceof Finpayapi_Finpayapi)) {
		$finpayapi_service = new Finpayapi();
	}

	return $finpayapi_service;
}
?>