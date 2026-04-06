<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_Summary_Widget_Filters' ) && class_exists( 'SDWeddingDirectory_Guest_List_Filters' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_Guest_List_Summary_Widget_Filters extends SDWeddingDirectory_Guest_List_Filters{

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
             *  Dashboard Summary
             *  -----------------
             */            
            add_action( 'sdweddingdirectory/couple/dashboard/widget/summary', [ $this, 'dashboard_summary' ], absint( '30' ) );
        }

        /**
         *  Guest List : First Event Guest Attended Summary
         *  -----------------------------------------------
         */
        public static function dashboard_summary(){

            /**
             *  Event Data
             *  ----------
             */
            $_event_data  =   parent:: get_event_overview_data();

            /**
             *  Attended
             *  --------
             */
            $attendance   =   absint( '0' );

            /**
             *  Have Event ?
             *  ------------
             */
            if( parent:: _is_array( $_event_data ) ){

                /**
                 *  Counter
                 *  -------
                 */
                $counter    =   absint( '1' );

                foreach ( $_event_data as $key => $value ) {

                    /**
                     *  Get First Event Data
                     *  --------------------
                     */
                    if( $counter == absint( '1' ) ){

                        /**
                         *  1. Attending Counter
                         *  --------------------
                         */
                        $attendance   = absint( $value[ 'attendance' ] );
                    }

                    $counter++;
                }
            }

            ?>
            <div class="col">

                <div class="couple-status-item">

                    <div class="counter"><?php echo absint( $attendance ); ?></div>

                    <div class="text">

                        <strong>
                        <?php

                            /**
                             *  1. Showing Pending Todo
                             *  -----------------------
                             */
                            printf( esc_attr__( 'Out of %1$s', 'sdweddingdirectory-guest-list' ),

                                /**
                                 *  1. Get Pending Todo Counter
                                 *  ---------------------------
                                 */
                                parent:: _is_array( parent:: guest_list() )

                                ?   absint( count( parent:: guest_list() ) )

                                :   absint( '0' )
                            );

                        ?>
                        </strong>

                        <small><?php esc_attr_e( 'Guests Attending', 'sdweddingdirectory-guest-list' ); ?></small>

                    </div>

                </div>
                
            </div>
            <?php
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Guest_List_Summary_Widget_Filters:: get_instance();
}