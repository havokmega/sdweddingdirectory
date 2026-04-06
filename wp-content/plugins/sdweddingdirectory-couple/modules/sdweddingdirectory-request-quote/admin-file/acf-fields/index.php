<?php
/**
 *  ACF Fields
 *  ----------
 */
if( ! class_exists( 'SDWeddingDirectory_Request_Quote_ACF' ) && class_exists( 'SDWeddingDirectory_ACF' ) ) {

	/**
	 *  Register Post and Taxonomy
	 *  --------------------------
	 */
	class SDWeddingDirectory_Request_Quote_ACF extends SDWeddingDirectory_ACF{

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Initiator
         *  ---------
         */
        public static function get_instance() {
          
            if ( ! isset( self::$instance ) ) {

              	self::$instance = new self;
            }
            
            return self::$instance;
        }

	    public function __construct(){

	    	/**
	    	 *  Have ACF Plugin Install ?
	    	 *  -------------------------
	    	 */
	    	if( function_exists( 'acf_add_local_field_group' ) ){

			    /**
			     *  Venue Post / Category Taxonomy / Request Quote Fields
			     *  -------------------------------------------------------
			     */
			    self:: request_qutoe_term_section(  [  'priority' => absint( '100' ) ]  );
	    	}
	    }

	    /**
	     *  Venue Post / Category Taxonomy / Request Quote Fields
	     *  -------------------------------------------------------
	     */
	    public static function request_qutoe_term_section( $args = [] ){

			extract( wp_parse_args( $args, [

				'priority'	=>	absint( '10' )

			] ) );

		    /**
		     *  Venue Post / Category Taxonomy / Request Quote Fields
		     *  -------------------------------------------------------
		     */
			acf_add_local_field_group(array(
				'key' => 'group_x54x9qkuls',
				'title' => 'Request Quote Fields For this Category ?',
				'fields' => array(

					array(
						'key' => 'field_61c895659323a',
						'label' => 'Couple Login Message',
						'name' => 'couple_login_request_message',
						'type' => 'repeater',
						'instructions' => 'login couple will auto fill random messages as added in list. We added two shortcodes in fields to show couple wedding date and your website name. {{site_name}} and {{wedding_date}}. you can add message and write this shortcode we will print it.',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'collapsed' => '',
						'min' => 0,
						'max' => 0,
						'layout' => 'table',
						'button_label' => 'Add Message',
						'sub_fields' => array(
							array(
								'key' => 'field_61c895919323b',
								'label' => 'Request Quote Message',
								'name' => 'request_message',
								'type' => 'textarea',
								'instructions' => '',
								'required' => 1,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'maxlength' => '',
								'rows' => 3,
								'new_lines' => '',
							),
						),
					),
					array(
						'key' => 'field_61c8960c9323e',
						'label' => 'Front End User Message',
						'name' => 'front_end_user_message',
						'type' => 'repeater',
						'instructions' => 'front end (without login) user will auto fill random messages as added in list',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'collapsed' => '',
						'min' => 0,
						'max' => 0,
						'layout' => 'table',
						'button_label' => 'Add Message',
						'sub_fields' => array(
							array(
								'key' => 'field_61c8960c9323f',
								'label' => 'Request Quote Message',
								'name' => 'request_message',
								'type' => 'textarea',
								'instructions' => '',
								'required' => 1,
								'conditional_logic' => 0,
								'wrapper' => array(
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'maxlength' => '',
								'rows' => 3,
								'new_lines' => '',
							),
						),
					),
					array(
						'key' => 'field_61c89738fddf2',
						'label' => 'Budget Fields Show ?',
						'name' => 'request_quote_budget_field',
						'type' => 'true_false',
						'instructions' => 'For this category do your want to enable budget field when couple quote the form ?',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'message' => '',
						'default_value' => 1,
						'ui' => 1,
						'ui_on_text' => 'Show',
						'ui_off_text' => 'Hide',
					),
					array(
						'key' => 'field_61c897da6dc86',
						'label' => 'Number Of Guest Fields Show ?',
						'name' => 'request_quote_number_of_guest_field',
						'type' => 'true_false',
						'instructions' => 'For this category do your want to enale capacity field when couple quote the form ?',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'message' => '',
						'default_value' => 0,
						'ui' => 1,
						'ui_on_text' => 'Show',
						'ui_off_text' => 'Hide',
					),
				),
				'location' => array(
					array(
						array(
							'param' => 'taxonomy',
							'operator' => '==',
							'value' => 'venue-type',
						),
					),
				),
				'menu_order' => absint( $priority ),
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => true,
				'description' => '',
			));
	    }
	}

	/**
	 *  Register Post and Taxonomy
	 *  --------------------------
	 */
    SDWeddingDirectory_Request_Quote_ACF::get_instance();	
}