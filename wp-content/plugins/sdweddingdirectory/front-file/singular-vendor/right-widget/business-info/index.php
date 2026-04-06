<?php
/**
 *  SDWeddingDirectory - Vendor Right Widget: Business Info
 *  ------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Singular_Right_Side_Widget_Business_Info' ) && class_exists( 'SDWeddingDirectory_Vendor_Singular_Right_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Vendor Right Widget: Business Info
     *  ------------------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Singular_Right_Side_Widget_Business_Info extends SDWeddingDirectory_Vendor_Singular_Right_Side_Widget{

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
             *  Business Info
             *  ------------
             */
            add_action( 'sdweddingdirectory/vendor/singular/right-side/widget', [ $this, 'widget' ], absint( '40' ), absint( '1' ) );
        }

        /**
         *  Business Info
         *  ------------
         */
        public static function widget( $args = [] ){

            if( parent:: _is_array( $args ) ){

                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( '0' )

                ] ) );

                if( empty( $post_id ) ){

                    return;
                }

                $business_icon   = absint( get_post_meta( absint( $post_id ), sanitize_key( 'business_icon' ), true ) );

                $company_name    = get_post_meta( absint( $post_id ), sanitize_key( 'company_name' ), true );

                $company_email   = get_post_meta( absint( $post_id ), sanitize_key( 'company_email' ), true );

                $company_phone   = get_post_meta( absint( $post_id ), sanitize_key( 'company_contact' ), true );

                $company_website = get_post_meta( absint( $post_id ), sanitize_key( 'company_website' ), true );

                $company_address = get_post_meta( absint( $post_id ), sanitize_key( 'company_address' ), true );

                $company_img     = parent:: _have_media( $business_icon )

                                    ?   apply_filters( 'sdweddingdirectory/media-data', [

                                            'media_id'      =>  absint( $business_icon ),

                                            'image_size'    =>  esc_attr( 'thumbnail' )

                                        ] )

                                    :   esc_url( parent:: placeholder( 'vendor-brand-image' ) );

                $company_name    = parent:: _have_data( $company_name )

                                    ?   $company_name

                                    :   get_the_title( absint( $post_id ) );

                ?>
                <div class="widget">

                    <h3 class="widget-title"><?php esc_attr_e( 'Business Info', 'sdweddingdirectory' ); ?></h3>

                    <div class="profile-avatar">

                        <img src="<?php echo esc_url( $company_img ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="w-25">

                        <div class="content"> <small><?php esc_attr_e( 'Vendor', 'sdweddingdirectory' ); ?></small> <span class="d-block fw-bold"><?php echo esc_attr( $company_name ); ?></span> </div>

                    </div>

                    <ul class="list-unstyled mb-0">

                        <?php if( parent:: _have_data( $company_phone ) ){

                            printf( '<li><i class="fa fa-phone"></i> <a href="tel:%2$s" class="btn-link btn-link-secondary">%1$s</a></li>',

                                esc_attr( $company_phone ),

                                esc_attr( preg_replace( '/[^\d+]/', '',  $company_phone ) )
                            );
                        }

                        if( parent:: _have_data( $company_email ) ){

                            printf( '<li><i class="fa fa-envelope"></i> <a href="mailto:%2$s" class="btn-link btn-link-secondary">%1$s</a></li>',

                                esc_attr( $company_email ),

                                esc_attr( $company_email )
                            );
                        }

                        if( parent:: _have_data( $company_website ) ){

                            printf( '<li><i class="fa fa-globe"></i> <a href="%1$s" target="_blank" class="btn-link btn-link-secondary">%2$s</a></li>',

                                esc_url( $company_website ),

                                esc_attr__( 'Website', 'sdweddingdirectory' )
                            );
                        }

                        if( parent:: _have_data( $company_address ) ){

                            printf( '<li><i class="fa fa-map-marker"></i> %1$s</li>',

                                esc_attr( $company_address )
                            );
                        }
                        ?>

                    </ul>

                </div>
                <?php
            }
        }
    }

    /**
     *  SDWeddingDirectory - Vendor Right Widget: Business Info
     *  ------------------------------------------------------
     */
    SDWeddingDirectory_Vendor_Singular_Right_Side_Widget_Business_Info::get_instance();
}
