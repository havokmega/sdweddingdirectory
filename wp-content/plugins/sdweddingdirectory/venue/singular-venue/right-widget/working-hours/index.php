<?php
/**
 *  SDWeddingDirectory - Venue Filter & Hooks
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Right_Side_Widget_Working_Hours' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Right_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Venue Filter & Hooks
     *  -----------------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Right_Side_Widget_Working_Hours extends SDWeddingDirectory_Venue_Singular_Right_Side_Widget {

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
             *  Working Hours
             *  -------------
             */
            add_action( 'sdweddingdirectory/venue/singular/right-side/widget', [ $this, 'widget' ], absint( '32' ), absint( '1' ) );
        }

        /**
         *  Working Hours
         *  -------------
         */
        public static function widget( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( '0' )

                ] ) );

                ?><div class="widget">

                    <h3 class="widget-title"><?php esc_attr_e( 'Working Hours', 'sdweddingdirectory-venue' ); ?></h3>

                    <div class="working-hours">

                    <?php

                    $days_array    =   [
                                            'monday'       => esc_attr__( 'Monday', 'sdweddingdirectory-venue' ),

                                            'tuesday'      => esc_attr__( 'Tuesday', 'sdweddingdirectory-venue' ),

                                            'wednesday'    => esc_attr__( 'Wednesday', 'sdweddingdirectory-venue' ),

                                            'thursday'     => esc_attr__( 'Thursday', 'sdweddingdirectory-venue' ),

                                            'friday'       => esc_attr__( 'Friday', 'sdweddingdirectory-venue' ),

                                            'saturday'     => esc_attr__( 'Saturday', 'sdweddingdirectory-venue' ),

                                            'sunday'       => esc_attr__( 'Sunday', 'sdweddingdirectory-venue' ),
                                        ];
                    /**
                     *  Days
                     *  ----
                     */
                    if( parent:: _is_array( $days_array ) ){

                        foreach( $days_array as $key => $value ){

                            $_enable    =   get_post_meta( $post_id, sanitize_key( $key . '_enable' ), true );

                            $_open      =   get_post_meta( $post_id, sanitize_key( $key . '_open' ), true );

                            $_start     =   get_post_meta( $post_id, sanitize_key( $key . '_start' ), true );

                            $_close     =   get_post_meta( $post_id, sanitize_key( $key . '_close' ), true );
                            
                            $_label_highligh     =   $_time_highligh     =   '';

                            /**
                             *  Enable
                             *  ------
                             */
                            if( $_enable == esc_attr( 'on' ) ){

                                if( date( 'l' ) == esc_attr( $value ) ){

                                    $_label_highligh        =       'fw-bold';

                                    $_time_highligh         =       'text-white bg-secondary';
                                }

                                else{

                                    $_label_highligh        =       'fw-bold';

                                    $_time_highligh         =       'text-dark';
                                }

                                /**
                                 *  Display Data
                                 *  ------------
                                 */
                                printf( '<div class="d-flex align-items-center justify-content-between days %3$s">
                                            %1$s
                                            <span class="badge border rounded p-2 %4$s">%2$s</span>
                                        </div>', 

                                        /**
                                         *  1. Day Name
                                         *  -----------
                                         */
                                        esc_attr( $value ),

                                        /**
                                         *  2. Is 24H Open
                                         *  --------------
                                         */
                                        $_open ==  esc_attr( 'on' ) && ! empty( $_open )

                                        ?   esc_attr__( '24 Hours Open', 'sdweddingdirectory-venue' )

                                        :   $_start .' - '. $_close,

                                        /**
                                         *  3. Is Label Highlight ?
                                         *  -----------------------
                                         */
                                        esc_attr( $_label_highligh ),

                                        /**
                                         *  4. Is Time Highlight ?
                                         *  ----------------------
                                         */
                                        esc_attr( $_time_highligh )
                                );
                            }

                            else{

                                if( date( 'l' ) == esc_attr( $value ) ){

                                    $_label_highligh        =       'fw-bold';

                                    $_time_highligh         =       'text-white bg-danger';
                                }

                                /**
                                 *  Display Data
                                 *  ------------
                                 */
                                printf( '<div class="d-flex align-items-center justify-content-between days %3$s">
                                            %1$s
                                            <span class="badge border text-dark rounded p-2 %4$s">%2$s</span>
                                        </div>', 

                                        /**
                                         *  1. Day Name
                                         *  -----------
                                         */
                                        esc_attr( $value ),

                                        /**
                                         *  2. Is 24H Open
                                         *  --------------
                                         */
                                        esc_attr__( 'Closed', 'sdweddingdirectory-venue' ),

                                        /**
                                         *  3. Is Label Highlight ?
                                         *  -----------------------
                                         */
                                        esc_attr( $_label_highligh ),

                                        /**
                                         *  4. Is Time Highlight ?
                                         *  ----------------------
                                         */
                                        esc_attr( $_time_highligh )
                                );
                            }
                        }
                    }

                ?></div></div><?php

            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Venue_Singular_Right_Side_Widget_Working_Hours:: get_instance();
}