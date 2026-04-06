<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Profile_Database' ) && class_exists( 'SDWeddingDirectory_Form_Tabs' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_Couple_Profile_Database extends SDWeddingDirectory_Form_Tabs{

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

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

        }

        /**
         *  Social Media
         *  ------------
         */
        public static function social_platform( $select = '' ){

            $_social_data       =   '';

            $_social_profile    =   apply_filters( 'sdweddingdirectory/social-media', [] );

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $_social_profile ) ){

                foreach( $_social_profile as $key => $value ){

                    /**
                     *  Value
                     *  -----
                     */
                    extract( $value );

                    /**
                     *  Social
                     *  ------
                     */
                    $_social_data 	.=	

                    sprintf( '<option value="%1$s" %2$s><i class="fa %1$s"></i> %3$s</option>', 

                    	/**
                    	 *  1. Icon
                    	 *  -------
                    	 */
                    	esc_attr( $icon ),

                    	/**
                    	 *  2. Is Selected ?
                    	 *  ----------------
                    	 */
                    	$select != '' && $select == $icon  ? esc_attr( 'selected' ) : '',

                    	/**
                    	 *  3. Value
                    	 *  --------
                    	 */
                    	esc_attr( $name )
                	);
                }
            }

            return 	$_social_data;
        }

        /**
         *  Get Couple Social Media
         *  -----------------------
         */
        public static function get_couple_social_media( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'post_id'   =>  absint( '0' ),

                'platform'  =>  '',

                'link'   	=>  '',

            ) ) );

            $_get_data              =   '';

            $_body_content          =   '';

            $_create_collapse       =   false;

            $_get_data              =   '';

            $_body_content          =   '';

            /**
             *  Is empty!
             *  ---------
             */
            if( empty( $post_id ) ){

                $_body_content      .=      '<div class="col collpase_section">';

                    $_body_content      .=      '<div class="card mb-4">';

                        $_body_content      .=      parent:: removed_core_section_icon( true );

                            $_body_content      .=      '<div class="card-body">';

                            $_body_content      .=      parent:: create_select_option( array(

                                                                'row'           =>  array(

                                                                                        'start'     =>  true,

                                                                                        'end'       =>  false,
                                                                                    ),

                                                                'column'        =>  array(

                                                                                        'start'     =>  true,

                                                                                        'end'       =>  true,

                                                                                        'grid'      =>  esc_attr( 'col-md-3 col-12' ),
                                                                                    ),

                                                                'name'          =>  esc_attr( 'platform' ),

                                                                'id'            =>  esc_attr( parent:: _rand() ),

                                                                'class'         =>  esc_attr( 'form-control platform' ),

                                                                'formgroup'		=>	false,

                                                                'options'		=>	self:: social_platform( $platform )
                                                        ) );

                            $_body_content      .=      parent:: create_input_field( array(

                                                            'row'           =>  array(

                                                                                    'end'     =>  true,

                                                                                    'start'   =>  false,
                                                                                ),

                                                            'column'        =>  array(

                                                                                    'grid'      =>  esc_attr( 'col-md-9 col-12' ),

                                                                                    'start'     =>      true,

                                                                                    'end'       =>      true,
                                                                                ),

                                                            'name'          =>   esc_attr( 'link' ),

                                                            'class'         =>   sanitize_html_class( 'link' ),

                                                            'formgroup'		=>	false,

                                                            'id'            =>  esc_attr( parent:: _rand() ),

                                                            'type'			=>	esc_attr( 'url' ),

                                                            'placeholder'	=>	esc_attr__( 'Social Media Link', 'sdweddingdirectory' ),

                                                            'value'         =>   $link

                                                        ) );

                        $_body_content      .=      '</div>';

                    $_body_content      .=      '</div>';

                $_body_content      .=      '</div>';

                /**
                 *  Return Data
                 *  -----------
                 */
                return $_body_content;

            }else{

                $_have_data          =   get_post_meta( absint( $post_id ), sanitize_key( 'social_profile' ), true );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $_have_data ) ){

                    foreach ( $_have_data as $key => $value ) {
                    
                        /**
                         *  Create Collpase
                         *  ---------------
                         */
                        $_get_data  .=  call_user_func( [ __CLASS__, __FUNCTION__ ], $value );
                    }
                }
            }

            /**
             *  Return : Data
             *  -------------
             */
            return  $_get_data;
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Couple_Profile_Database:: get_instance();
}