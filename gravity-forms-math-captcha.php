<?php
/**
 * Gravity Forms Math Captcha.
 *
 * @package   Gravity_Forms_Math_Captcha
 * @author    Mandi Wise <hello@mandiwise.com>
 * @license   GPL-2.0+
 * @link      http://mandiwise.com
 * @copyright 2014 Mandi Wise
 *
 * @wordpress-plugin
 * Plugin Name:       Gravity Forms Math Captcha
 * Plugin URI:        http://mandiwise.com/wordpress/gravity-forms-math-captcha/
 * Description:       Add a simple, non-image-based math captcha field to Gravity Forms.
 * Version:           1.0.1
 * Author:            Mandi Wise
 * Author URI:        http://mandiwise.com
 * Text Domain:       gravity-forms-math-captcha
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/mandiwise/gravity-forms-math-captcha
 *
 * WordPress-Plugin-Boilerplate: v2.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-gravity-forms-math-captcha.php' );
add_action( 'plugins_loaded', array( 'Gravity_Forms_Math_Captcha', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-gravity-forms-math-captcha-admin.php' );
	add_action( 'plugins_loaded', array( 'Gravity_Forms_Math_Captcha_Admin', 'get_instance' ) );

}
