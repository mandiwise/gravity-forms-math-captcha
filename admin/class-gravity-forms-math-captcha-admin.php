<?php
/**
 * Gravity Forms Math Captcha.
 *
 * @package   Gravity_Forms_Math_Captcha_Admin
 * @author    Mandi Wise <hello@mandiwise.com>
 * @license   GPL-2.0+
 * @link      http://mandiwise.com
 * @copyright 2014 Mandi Wise
 */

class Gravity_Forms_Math_Captcha_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Call $plugin_slug from public plugin class.
		$plugin = Gravity_Forms_Math_Captcha::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

      // Add an admin notice if Gravity Forms isn't installed.
      add_action( 'admin_notices', array( $this, 'admin_warning' ) );

      // Add the math captcha field to the Gravity Forms editor.
      add_filter( 'gform_add_field_buttons', array( $this, 'add_math_captcha_field' ) );
      add_filter( 'gform_field_type_title' , array( $this, 'add_math_captcha_title' ), 10, 2 );
      add_action( 'gform_editor_js', array( $this, 'math_captcha_gform_editor_js' ) );
      add_action( 'gform_field_standard_settings', array( $this, 'math_captcha_settings' ), 10, 2 );
      add_filter( 'gform_tooltips', array( $this, 'math_captcha_type_tooltip' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

   public function admin_warning() {
      global $pagenow;

      // Check whether Gravity Forms is installed.
      if ( ! class_exists( 'RGForms' ) && $pagenow == 'plugins.php' ) {
      ?>
         <div class="error">
            <p>
                <?php _e( 'The Gravity Forms Math Captcha plugin requires Gravity Forms to be installed and activated. ', $this->plugin_slug ); ?><a href="http://www.gravityforms.com/" target="_blank"><?php _e( 'Please install Gravity Forms now.', $this->plugin_slug ); ?></a>
            </p>
        </div>
      <?php
      }
   }

   /**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 */
	public function enqueue_admin_scripts() {

      // Check for that the current page is the Gravity Forms editor, or return if it's not.
      if ( rgget( 'page' ) != 'gf_edit_forms' )
         return;

		wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery', 'gform_form_editor' ), Gravity_Forms_Math_Captcha::VERSION );
	}

   /**
    * Add a custom field button to the Advanced Fields tab in the field editor.
    *
    * @since    1.0.0
    */
   public function add_math_captcha_field( $field_groups ) {
      foreach ( $field_groups as &$group ){

         // Add button to the Advanced Fields tab.
         if ( $group['name'] == 'advanced_fields' ){
            $group['fields'][] = array(
               'class'   => 'button',
               'value'   => __( 'Math Captcha', $this->plugin_slug ),
               'onclick' => "StartAddField('math_captcha');"
            );
            break;
         }
      }
      return $field_groups;
   }

   /**
    * Adds name of the field to Gravity Forms field editor.
    *
    * @since    1.0.0
    */
   public function add_math_captcha_title( $title, $field_type ) {
      if ( $field_type == 'math_captcha' ) {
         return __( 'Math Captcha', $this->plugin_slug );
      }
   }

   /**
    * Execute javascript technicalitites for the field to load correctly.
    *
    * @since    1.0.0
    */
   public function math_captcha_gform_editor_js() {
   ?>
      <script type='text/javascript'>
         jQuery(document).ready(function($) {

            // Add the form field settings, including the custom "math_captcha_setting" class.
            fieldSettings['math_captcha'] = '.label_setting, .description_setting, .error_message_setting, .css_class_setting, .conditional_logic_field_setting, .math_captcha_setting';

            // Bind to the load field settings event to initialize the dropdown.
            $(document).bind('gform_load_field_settings', function(event, field, form){
               $('#field_math_captcha_type').val(field['field_math_captcha_type']);
            });

         });
      </script>
   <?php
   }

   /**
    * Add a custom setting to the Math Captcha field.
    *
    * @since    1.0.0
    */
   public function math_captcha_settings( $position, $form_id ) {

      // Create settings on position 25 (right after the Field Label).
      if ( $position == 25 ) {
      ?>
         <li class="math_captcha_setting field_setting">
            <label for="field_math_captcha_type">
                <?php _e( 'Math Captcha Display', $this->plugin_slug ); ?>
                <?php gform_tooltip( 'form_field_math_captcha_type' ); ?>
            </label>
            <select id="field_math_captcha_type" onchange="SetFieldProperty('field_math_captcha_type', jQuery(this).val());">
                <option value="mixed"><?php _e( 'A mix of numbers and words', $this->plugin_slug ); ?></option>
                <option value="numbers"><?php _e( 'Numbers only', $this->plugin_slug ); ?></option>
                <option value="words"><?php _e( 'Words only', $this->plugin_slug ); ?></option>
            </select>
        </li>
      <?php
      }
   }

   /**
    * Add a tooltip to the Math Capthca Type select menu.
    *
    * @since    1.0.0
    */
   function math_captcha_type_tooltip( $tooltips ) {
      $tooltips["form_field_math_captcha_type"] = '<h6>'. __( 'Math Captcha Display', $this->plugin_slug ) . '</h6>' . __( 'Select how to display the math-based captcha. The numbers in the math question can be displayed as numerals, words, or a mix of both.', $this->plugin_slug );
      return $tooltips;
   }

}
