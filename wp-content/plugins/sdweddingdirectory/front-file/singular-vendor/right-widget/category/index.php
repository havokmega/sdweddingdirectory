<?php
/**
 *  SDWeddingDirectory - Vendor Right Widget: Category
 *  -------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Singular_Right_Side_Widget_Category' ) && class_exists( 'SDWeddingDirectory_Vendor_Singular_Right_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Vendor Right Widget: Category
     *  -------------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Singular_Right_Side_Widget_Category extends SDWeddingDirectory_Vendor_Singular_Right_Side_Widget{

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
             *  Category Widget
             *  --------------
             */
            add_action( 'sdweddingdirectory/vendor/singular/right-side/widget', [ $this, 'widget' ], absint( '20' ), absint( '1' ) );
        }

        /**
         *  Category Widget
         *  --------------
         */
        public static function widget( $args = [] ){

            if( parent:: _is_array( $args ) ){

                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( '0' )

                ] ) );

                if( empty( $post_id ) ){

                    return;
                }

                $terms = wp_get_post_terms( absint( $post_id ), sanitize_key( 'vendor-category' ) );

                if( ! parent:: _is_array( $terms ) ){

                    return;
                }

                ?>
                <div class="widget">

                    <h3 class="widget-title"><?php esc_attr_e( 'Category', 'sdweddingdirectory' ); ?></h3>

                    <ul class="list-unstyled mb-0">
                    <?php foreach( $terms as $term ){

                        printf( '<li><a class="btn-link btn-link-secondary" href="%1$s">%2$s</a></li>',

                            esc_url( get_term_link( $term ) ),

                            esc_attr( $term->name )
                        );
                    } ?>
                    </ul>

                </div>
                <?php
            }
        }
    }

    /**
     *  SDWeddingDirectory - Vendor Right Widget: Category
     *  -------------------------------------------------
     */
    SDWeddingDirectory_Vendor_Singular_Right_Side_Widget_Category::get_instance();
}
