<?php
/**
 *  SDWeddingDirectory - Vendor Header Section
 *  -----------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Singular_Header_Section' ) && class_exists( 'SDWeddingDirectory_Singular_Vendor' ) ){

    /**
     *  SDWeddingDirectory - Vendor Header Section
     *  -----------------------------------------
     */
    class SDWeddingDirectory_Vendor_Singular_Header_Section extends SDWeddingDirectory_Singular_Vendor {

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

            /**
             *  Vendor Header Section Left Content [ Title Section ]
             *  ----------------------------------------------------
             */
            add_action( 'sdweddingdirectory/vendor/header/left/section', [ $this, 'vendor_title' ], absint( '10' ), absint( '1' ) );

            /**
             *  Vendor Header Section Left Content [ Category + Location ]
             *  ----------------------------------------------------------
             */
            add_action( 'sdweddingdirectory/vendor/header/left/section', [ $this, 'vendor_category_location' ], absint( '20' ), absint( '1' ) );
        }

        /**
         *  Vendor Header Section Left Content [ Title Section ]
         *  ----------------------------------------------------
         */
        public static function vendor_title( $args = [] ){

            if( parent:: _is_array( $args ) ){

                extract( $args );

                $company_name = get_post_meta( absint( $post_id ), sanitize_key( 'company_name' ), true );

                $title = parent:: _have_data( $company_name )

                    ?   esc_attr( $company_name )

                    :   esc_attr( get_the_title( absint( $post_id ) ) );

                if( ! empty( $title ) ){

                    printf( '<h1 class="heading">%1$s</h1>', $title );
                }
            }
        }

        /**
         *  Vendor Header Section Left Content [ Category + Location ]
         *  ----------------------------------------------------------
         */
        public static function vendor_category_location( $args = [] ){

            if( parent:: _is_array( $args ) ){

                extract( $args );

                /**
                 *  Category
                 *  --------
                 */
                $terms = wp_get_post_terms( absint( $post_id ), sanitize_key( 'vendor-category' ), [ 'fields' => 'names' ] );

                if( parent:: _is_array( $terms ) ){

                    printf( '<p class="mb-md-0 mb-lg-3 d-flex align-items-center"><i class="fa fa-tag me-1"></i>%1$s</p>',

                        esc_attr( implode( ', ', $terms ) )
                    );
                }

            }
        }

    }

    /**
     *  SDWeddingDirectory - Vendor Header Section
     *  -----------------------------------------
     */
    SDWeddingDirectory_Vendor_Singular_Header_Section::get_instance();
}
