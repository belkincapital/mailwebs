<?php
/*
Plugin Name: Mail Webs - Secure Authenticated SMTP Server.
Version: 1.0.1
Plugin URI: https://github.com/belkincapital/mailwebs
Description: Reconfigures the wp_mail() function to use SMTP instead of php mail() function.
Author: Jason Jersey
Author URI: http://twitter.com/degersey
GitHub Plugin URI: https://github.com/belkincapital/mailwebs
GitHub Branch: master
*/

/**
 * This plugin is based on the WP-Mail-SMTP plugin version 0.9.5 by Callum Macdonald.
 * 
 * @author Callum Macdonald
 * @copyright Callum Macdonald, 2007-11, All Rights Reserved
 * This code is released under the GPL licence version 3 or later, available here
 * http://www.gnu.org/licenses/gpl.txt
 */

// Array of options and their default values
global $mailwebs_options;
$mailwebs_options = array (
	'mail_from' => '',
	'mail_from_name' => '',
	'mailer' => 'smtp',
	'mail_set_return_path' => 'false',
	'smtp_host' => 'localhost',
	'smtp_port' => '25',
	'smtp_ssl' => '',
	'smtp_auth' => true,
	'smtp_user' => '',
	'smtp_pass' => ''
);

/**
 * Activation function. This function creates the required options and defaults.
 */
if (!function_exists('mail_we_bs_smtp_activate')) :
function mail_we_bs_smtp_activate() {
	
	global $mailwebs_options;
	
	// Create the required options...
	foreach ($mailwebs_options as $name => $val) {
		add_option($name,$val);
	}
	
}
endif;

if (!function_exists('mail_we_bs_smtp_whitelist_options')) :
function mail_we_bs_smtp_whitelist_options($whitelist_options) {
	
	global $mailwebs_options;
	
	// Add our options to the array
	$whitelist_options['email'] = array_keys($mailwebs_options);
	
	return $whitelist_options;
	
}
endif;

// To avoid any (very unlikely) clashes, check if the function alredy exists
if (!function_exists('phpmailer_init_smtp')) :
// This code is copied, from wp-includes/pluggable.php as at version 2.2.2
function phpmailer_init_smtp($phpmailer) {
	
	// If constants are defined, apply those options
	if (defined('MAILWEBS_ON') && MAILWEBS_ON) {
		
		$phpmailer->Mailer = MAILWEBS_MAILER;
		
		if (MAILWEBS_SET_RETURN_PATH)
			$phpmailer->Sender = $phpmailer->From;
		
		if (MAILWEBS_MAILER == 'smtp') {
			$phpmailer->SMTPSecure = MAILWEBS_SSL;
			$phpmailer->Host = MAILWEBS_SMTP_HOST;
			$phpmailer->Port = MAILWEBS_SMTP_PORT;
			if (MAILWEBS_SMTP_AUTH) {
				$phpmailer->SMTPAuth = true;
				$phpmailer->Username = MAILWEBS_SMTP_USER;
				$phpmailer->Password = MAILWEBS_SMTP_PASS;
			}
		}
		
		// If you're using contstants, set any custom options here
		$phpmailer = apply_filters('mail_we_bs_smtp_custom_options', $phpmailer);
		
	}
	else {
		
		// Check that mailer is not blank, and if mailer=smtp, host is not blank
		if ( ! get_option('mailer') || ( get_option('mailer') == 'smtp' && ! get_option('smtp_host') ) ) {
			return;
		}
		
		// Set the mailer type as per config above, this overrides the already called isMail method
		$phpmailer->Mailer = get_option('mailer');
		
		// Set the Sender (return-path) if required
		if (get_option('mail_set_return_path'))
			$phpmailer->Sender = $phpmailer->From;
		
		// Set the SMTPSecure value, if set to none, leave this blank
		$phpmailer->SMTPSecure = get_option('smtp_ssl') == 'none' ? '' : get_option('smtp_ssl');
		
		// If we're sending via SMTP, set the host
		if (get_option('mailer') == "smtp") {
			
			// Set the SMTPSecure value, if set to none, leave this blank
			$phpmailer->SMTPSecure = get_option('smtp_ssl') == 'none' ? '' : get_option('smtp_ssl');
			
			// Set the other options
			$phpmailer->Host = get_option('smtp_host');
			$phpmailer->Port = get_option('smtp_port');
			
			// If we're using smtp auth, set the username & password
			if (get_option('smtp_auth') == "true") {
				$phpmailer->SMTPAuth = TRUE;
				$phpmailer->Username = get_option('smtp_user');
				$phpmailer->Password = get_option('smtp_pass');
			}
		}
		
		// You can add your own options here, see the phpmailer documentation for more info:
		// http://phpmailer.sourceforge.net/docs/
		$phpmailer = apply_filters('mail_we_bs_smtp_custom_options', $phpmailer);
		
		
		// STOP adding options here.
		
	}
	
} // End of phpmailer_init_smtp() function definition
endif;

/**
 * This function sets the from email value
 */
if (!function_exists('mail_we_bs_smtp_mail_from')) :
function mail_we_bs_smtp_mail_from ($orig) {
	
	// This is copied from pluggable.php lines 348-354 as at revision 10150
	// http://trac.wordpress.org/browser/branches/2.7/wp-includes/pluggable.php#L348
	
	// Get the site domain and get rid of www.
	$sitename = strtolower( $_SERVER['SERVER_NAME'] );
	if ( substr( $sitename, 0, 4 ) == 'www.' ) {
		$sitename = substr( $sitename, 4 );
	}

	$default_from = 'wordpress@' . $sitename;
	// End of copied code
	
	// If the from email is not the default, return it unchanged
	if ( $orig != $default_from ) {
		return $orig;
	}
	
	if (defined('MAILWEBS_ON') && MAILWEBS_ON) {
		if (defined('MAILWEBS_MAIL_FROM') && MAILWEBS_MAIL_FROM != false)
			return MAILWEBS_MAIL_FROM;
	}
	elseif (is_email(get_option('mail_from'), false))
		return get_option('mail_from');
	
	// If in doubt, return the original value
	return $orig;
	
} // End of mail_we_bs_smtp_mail_from() function definition
endif;

/**
 * This function sets the from name value
 */
if (!function_exists('mail_we_bs_smtp_mail_from_name')) :
function mail_we_bs_smtp_mail_from_name ($orig) {
	
	// Only filter if the from name is the default
	if ($orig == 'WordPress') {
		if (defined('MAILWEBS_ON') && MAILWEBS_ON) {
			if (defined('MAILWEBS_MAIL_FROM_NAME') && MAILWEBS_MAIL_FROM_NAME != false)
				return MAILWEBS_MAIL_FROM_NAME;
		}
		elseif ( get_option('mail_from_name') != "" && is_string(get_option('mail_from_name')) )
			return get_option('mail_from_name');
	}
	
	// If in doubt, return the original value
	return $orig;
	
} // End of mail_we_bs_smtp_mail_from_name() function definition
endif;

// Add an action on phpmailer_init
add_action('phpmailer_init','phpmailer_init_smtp');

if (!defined('MAILWEBS_ON') || !MAILWEBS_ON) {
	// Whitelist our options
	add_filter('whitelist_options', 'mail_we_bs_smtp_whitelist_options');
	// Add an activation hook for this plugin
	register_activation_hook(__FILE__,'mail_we_bs_smtp_activate');
}

// Add filters to replace the mail from name and emailaddress
add_filter('wp_mail_from','mail_we_bs_smtp_mail_from');
add_filter('wp_mail_from_name','mail_we_bs_smtp_mail_from_name');
