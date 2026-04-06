<?php
/**
 *  SDWeddingDirectory - Vendor Right Widget: Working Hours
 *  ------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Singular_Right_Side_Widget_Working_Hours' ) && class_exists( 'SDWeddingDirectory_Vendor_Singular_Right_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory - Vendor Right Widget: Working Hours
     *  ------------------------------------------------------
     */
    class SDWeddingDirectory_Vendor_Singular_Right_Side_Widget_Working_Hours extends SDWeddingDirectory_Vendor_Singular_Right_Side_Widget{

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
            add_action( 'sdweddingdirectory/vendor/singular/right-side/widget', [ $this, 'widget' ], absint( '32' ), absint( '1' ) );
        }

        /**
         *  Working Hours
         *  -------------
         */
        public static function widget( $args = [] ){

            if( parent:: _is_array( $args ) ){

                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( '0' )

                ] ) );

                if( empty( $post_id ) ){

                    return;
                }

                ?>
                <div class="widget">

                    <h3 class="widget-title"><?php esc_attr_e( 'Working Hours', 'sdweddingdirectory' ); ?></h3>

                    <div class="working-hours">

                    <?php

                    $days_array    =   [
                                            'monday'       => esc_attr__( 'Monday', 'sdweddingdirectory' ),

                                            'tuesday'      => esc_attr__( 'Tuesday', 'sdweddingdirectory' ),

                                            'wednesday'    => esc_attr__( 'Wednesday', 'sdweddingdirectory' ),

                                            'thursday'     => esc_attr__( 'Thursday', 'sdweddingdirectory' ),

                                            'friday'       => esc_attr__( 'Friday', 'sdweddingdirectory' ),

                                            'saturday'     => esc_attr__( 'Saturday', 'sdweddingdirectory' ),

                                            'sunday'       => esc_attr__( 'Sunday', 'sdweddingdirectory' ),
                                        ];

                    if( parent:: _is_array( $days_array ) ){

                        foreach( $days_array as $key => $value ){

                            $_enable    =   get_post_meta( $post_id, sanitize_key( $key . '_enable' ), true );

                            $_open      =   get_post_meta( $post_id, sanitize_key( $key . '_open' ), true );

                            $_start     =   get_post_meta( $post_id, sanitize_key( $key . '_start' ), true );

                            $_close     =   get_post_meta( $post_id, sanitize_key( $key . '_close' ), true );
                            
                            $_label_highligh     =   $_time_highligh     =   '';

                            if( $_enable == esc_attr( 'on' ) ){

                                if( date( 'l' ) == esc_attr( $value ) ){

                                    $_label_highligh        =       'fw-bold';

                                    $_time_highligh         =       'text-white bg-secondary';
                                }

                                else{

                                    $_label_highligh        =       'fw-bold';

                                    $_time_highligh         =       'text-dark';
                                }

                                printf( '<div class="d-flex align-items-center justify-content-between days %3$s">
                                            %1$s
                                            <span class="badge border rounded p-2 %4$s">%2$s</span>
                                        </div>', 

                                        esc_attr( $value ),

                                        $_open ==  esc_attr( 'on' ) && ! empty( $_open )

                                        ?   esc_attr__( '24 Hours Open', 'sdweddingdirectory' )

                                        :   $_start .' - '. $_close,

                                        esc_attr( $_label_highligh ),

                                        esc_attr( $_time_highligh )
                                );
                            }

                            else{

                                if( date( 'l' ) == esc_attr( $value ) ){

                                    $_label_highligh        =       'fw-bold';

                                    $_time_highligh         =       'text-white bg-danger';
                                }

                                printf( '<div class="d-flex align-items-center justify-content-between days %3$s">
                                            %1$s
                                            <span class="badge border text-dark rounded p-2 %4$s">%2$s</span>
                                        </div>', 

                                        esc_attr( $value ),

                                        esc_attr__( 'Closed', 'sdweddingdirectory' ),

                                        esc_attr( $_label_highligh ),

                                        esc_attr( $_time_highligh )
                                );
                            }
                        }
                    }
                    ?>
                    </div>

                </div>
                <?php
            }
        }
    }

    /**
     *  SDWeddingDirectory - Vendor Right Widget: Working Hours
     *  ------------------------------------------------------
     */
    SDWeddingDirectory_Vendor_Singular_Right_Side_Widget_Working_Hours::get_instance();
}
