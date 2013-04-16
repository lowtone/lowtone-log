<?php
/*
 * Plugin Name: Logging
 * Plugin URI: http://wordpress.lowtone.nl
 * Description: Generic log interface and settings.
 * Version: 1.0
 * Author: Lowtone <info@lowtone.nl>
 * Author URI: http://lowtone.nl
 * License: http://wordpress.lowtone.nl/license
 * Requires: lowtone-lib
 */
/**
 * @author Paul van der Meijs <code@lowtone.nl>
 * @copyright Copyright (c) 2011-2012, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.0
 * @package wordpress\plugins\lowtone\log
 */

namespace lowtone\log {

	use lowtone\content\packages\Package,
		lowtone\io\logging\Log,
		lowtone\ui\forms\Form,
		lowtone\ui\forms\FieldSet,
		lowtone\ui\forms\Input;

	// Includes
	
	if (!include_once WP_PLUGIN_DIR . "/lowtone-content/lowtone-content.php") 
		return trigger_error("Lowtone Content plugin is required", E_USER_ERROR) && false;

	$__i = Package::init(array(
			Package::INIT_PACKAGES => array("lowtone"),
			Package::INIT_MERGED_PATH => __NAMESPACE__,
			Package::INIT_SUCCESS => function() {

				add_action("lowtone_log_write", __NAMESPACE__ . "\\write", 10, 2);

				add_action("lowtone_log_buffer", __NAMESPACE__ . "\\buffer", 10, 2);

			}
		));

	if (!$__i)
		return;

	// Functions
	
	function enabled() {
		return 0 == get_option("lowtone_log_enabled") ? false : true;
	}
	
	function path($file = NULL) {
		return LOG_DIR . DIRECTORY_SEPARATOR . (trim($file) ?: "default.log");
	}
	
	function log($file = NULL) {
		return Log::__instance(path($file));
	}

	function logDo($method, array $args = NULL, $file = NULL) {
		if (!(($log = log($file)) instanceof Log))
			return false;

		call_user_func_array(array($log, $method), (array) $args);

		return true;
	}

	function write($message, $file = NULL) {
		return logDo("write", array($message), $file);
	}

	function buffer($message, $file = NULL) {
		return logDo("buffer", array($message), $file);
	}

}