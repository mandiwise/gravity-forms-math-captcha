<?php
/**
 * Gravity Forms Math Captcha.
 *
 * @package   Gravity_Forms_Math_Captcha
 * @author    Mandi Wise <hello@mandiwise.com>
 * @license   GPL-2.0+
 * @link      http://mandiwise.com
 * @copyright 2014 Mandi Wise
 */

class Gravity_Forms_Math_Captcha {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.1';

	/**
	 * Unique identifier for the plugin.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'gravity-forms-math-captcha';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

	   // Render the field in the form on the front-end
      add_action( 'gform_field_input' , array( $this, 'math_captcha_field_input' ), 10, 5 );

      // Validate the captcha field.
      add_filter( 'gform_validation', array( $this, 'math_captcha_validation' ) );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
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

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

   /**
    * Randomly generate a math equation to include in the form field.
    *
    * @since    1.0.0
    *
    * @param    string    $display_type Whether to display 'mixed' (default), 'numbers' or 'words'
    * @return   array                   An array of equation components
    * @link     http://stackoverflow.com/questions/10206625/simple-math-based-captcha
    */
   public function generate_equation( $display_type ) {

      // Build and array of numbers and their corresponding word versions.
      $word_numbers = array(
			0 => __( 'zero', $this->plugin_slug ),
         1 => __( 'one', $this->plugin_slug ),
         2 => __( 'two', $this->plugin_slug ),
         3 => __( 'three', $this->plugin_slug ),
         4 => __( 'four', $this->plugin_slug ),
         5 => __( 'five', $this->plugin_slug ),
         6 => __( 'six', $this->plugin_slug ),
         7 => __( 'seven', $this->plugin_slug ),
         8 => __( 'eight', $this->plugin_slug ),
         9 => __( 'nine', $this->plugin_slug ),
         10 => __( 'ten', $this->plugin_slug ),
         11 => __( 'eleven', $this->plugin_slug ),
         12 => __( 'twelve', $this->plugin_slug ),
         13 => __( 'thirteen', $this->plugin_slug ),
         14 => __( 'fourteen', $this->plugin_slug ),
         15 => __( 'fifteen', $this->plugin_slug ),
         16 => __( 'sixteen', $this->plugin_slug ),
         17 => __( 'seventeen', $this->plugin_slug ),
         18 => __( 'eighteen', $this->plugin_slug ),
         19 => __( 'nineteen', $this->plugin_slug ),
         20 => __( 'twenty', $this->plugin_slug ),
      );

      // Set up an empty array to build the equation.
      $equation = array();

      // Get the passed display type, otherwise set it to 'mixed'
      $display = isset( $display_type ) ? $display_type : 'mixed';

      // Get first number, between 7 and 13 inclusive.
      $n1 = rand( 7, 13 );
      $random = rand( 0, 1 );

      // Return $n1 as digit or text ($random == 0 will display digit).
      if ( $display ==  'numbers' || ( $display == 'mixed' && $random == 0 ) ) {
         $equation[0] = $n1;
      } else {
         $equation[0] = $word_numbers[$n1];
      }

      // Get the operator ($random == 0 will display symbol).
      $operator = rand( 0, 1 );
      $random = rand( 0, 1 );

      if ( $operator == 0 ){
         // subtraction
         if ( $display ==  'numbers' || ( $display == 'mixed' && $random == 0 ) ) {
            $equation[1] = '&#8722;';
         } else {
            $equation[1] = __( 'minus', $this->plugin_slug );
         }
      } else {
         // addition
         if ( $display ==  'numbers' || ( $display == 'mixed' && $random == 0 ) ) {
            $equation[1] = '+';
         } else {
            $equation[1] = __( 'plus', $this->plugin_slug );
         }
      }

      // Get second number, between 0 and 7 inclusive, so no negative answers.
      $n2 = rand( 1, 7 );
      $random = rand( 0, 1 );

      // Return $n2 as digit or text ($random == 0 will display digit).
      if ( $display ==  'numbers' || ( $display == 'mixed' && $random == 0 ) ) {
         $equation[2] = $n2;
      } else {
         $equation[2] = $word_numbers[$n2];
      }

      // Get the answer for the equation.
      if ( $operator == 0 ){
         $answer = $n1 - $n2;
      } else {
         $answer = $n1 + $n2;
      }

      // Answer in numeral format
      $equation[3] = $answer;

      // Answer in text format
      $equation[4] = $word_numbers[$answer];

      return $equation;

   }

	/**
	 * Adds the Math Captcha input area to the site.
    *
	 * @since    1.0.0
	 */
   public function math_captcha_field_input( $input, $field, $value, $lead_id, $form_id ) {

      if ( $field['type'] == 'math_captcha' ) {

         // Render the math challenge question.
         $display_type = isset( $field['field_math_captcha_type'] ) ? $field['field_math_captcha_type'] : 'mixed';
         $equation = $this->generate_equation( $display_type );
         $question = $equation[0] . ' ' . $equation[1] . ' ' . $equation[2];
         if( $equation[1] != "+" && $equation[0] < $equation[2] ) {
            $question = $equation[2] . ' ' . $equation[1] . ' ' . $equation[0];
         }

         // Store the solution in a hex-encoded string.
         $answers = $equation[3] . ',' . $equation[4];
         $answers_no_spam = '';
         for ( $i = 0; $i < strlen( $answers ); $i++ ) {
            $answers_no_spam .= '%' . zeroise( dechex( ord( $answers[$i] ) ), 2 );
         }

         // Get the form/field properties to construct the input.
         $input_id = $form_id . '_' . $field['id'];
         $tab_index = GFCommon::get_tabindex();
         $css = !empty( $field['cssClass'] ) ? ' ' . $field['cssClass'] : '';

         if( is_rtl() ) {
            return sprintf(
               "<div class='ginput_container'><input name='input_%d' id='input_%s' type='text' class='%s' %s > &#61; %s <input name='math_captcha_answers_%d' type='hidden' value='%s'></div>",
               $field['id'],
               $input_id,
               $field['type'] . ' small' . esc_attr( $css ),
               $tab_index,
               $question,
               $field['id'],
               $answers_no_spam
            );
         } else {
            return sprintf(
               "<div class='ginput_container'>%s &#61; <input name='input_%d' id='input_%s' type='text' class='%s' %s ><input name='math_captcha_answers_%d' type='hidden' value='%s'></div>",
               $question,
               $field['id'],
               $input_id,
               $field['type'] . ' small' . esc_attr( $css ),
               $tab_index,
               $field['id'],
               $answers_no_spam
		      );
         }
      }
      return $input;
   }

   /**
    * Validates the solution to the math captcha question.
    *
    * @since    1.0.0
    */
   public function math_captcha_validation( $validation_result ) {
      $form = $validation_result['form'];

      $current_page = rgpost('gform_source_page_number_' . $form['id']) ? rgpost('gform_source_page_number_' . $form['id']) : 1;

      foreach ( $form['fields'] as &$field ) {

         // Check that we're validating a math captcha field.
         if ( $field['type'] != 'math_captcha' )
            continue;

         // Make sure that the field isn't hidden or on a different page of the form.
         $field_page = $field['pageNumber'];
         $is_hidden = RGFormsModel::is_field_hidden( $form, $field, array() );

         if ( $field_page != $current_page || $is_hidden )
            continue;

         // Get the accepted answers from the hidden input.
         $answers_no_spam = rgpost( "math_captcha_answers_{$field['id']}" );

         // Convert the encoded answers from hexidecimal format.
         $answers_unhex = '';
         $answers = preg_replace( '/[^A-Za-z0-9]/', '', $answers_no_spam );

         for ( $i=0; $i < strlen( $answers )-1; $i+=2 ) {
            $answers_unhex .= chr( hexdec( $answers[$i].$answers[$i+1] ) );
         }

         // Create an array of the accepted answers.
         $answer_array = explode( ',', $answers_unhex );

         // Check $_POST to see if one of the accepted answers was submitted.
         if ( ! in_array( strtolower( rgpost( "input_{$field['id']}" ) ), $answer_array ) ) {
            $validation_result['is_valid'] = false;
            $field['failed_validation'] = true;
            $field['validation_message'] = __( "Sorry, that wasn't the correct answer. Please try again.", $this->plugin_slug );
            break;
         }
      }

      // Assign modified $form object back to the validation result.
      $validation_result['form'] = $form;
      return $validation_result;
   }

}
