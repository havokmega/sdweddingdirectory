<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_Left_Side_Filters' ) && class_exists( 'SDWeddingDirectory_Guest_List_Filters' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_Guest_List_Left_Side_Filters extends SDWeddingDirectory_Guest_List_Filters{

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
             *  3. Dashboard Full Width Guest Summary
             *  -------------------------------------
             */
            add_action( 'sdweddingdirectory/couple/dashboard/widget/left-side', [ $this, 'widget' ], absint( '40' ) );
        }

        /**
         *  3. Dashboard Full Width Guest Summary
         *  -------------------------------------
         */
        public static function widget(){

            /**
             *  Event Data
             *  ----------
             */
            $_event_data      =   parent:: get_event_overview_data();

          /**
           *  Have Data ?
           *  -----------
           */
          if( parent:: _is_array( $_event_data ) ){

              $counter = absint( '1' );

                ?>
                <!-- Guest List -->
                <div class="card-shadow">

                    <!-- / Header -->
                    <div class="card-shadow-header">
                        <div class="dashboard-head">
                            <?php

                              /**
                               *  Guest List Wiget Summery Title + Redirect link
                               *  ------------------------------------------
                               */
                              printf(  '<h3>%1$s</h3><div class="links"><a href="%2$s">%3$s <i class="fa fa-angle-right"></i></a></div>',

                                        /**
                                         *  1. String Translation
                                         *  ---------------------
                                         */
                                        esc_attr__( 'Guest List Overview', 'sdweddingdirectory-guest-list' ),

                                        /**
                                         *  2. Guest List Page Link
                                         *  -------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'guest-management' ) ),

                                        /**
                                         *  3. String Translation
                                         *  ---------------------
                                         */
                                        esc_attr__( 'Guest List', 'sdweddingdirectory-guest-list' )
                              );

                            ?>
                        </div>
                    </div>
                    <!-- / Header -->

                    <!-- Load Events -->
                    <div class="card-shadow-body">
                        <div class="empty-guest-list p-0">
                            <div class="guest-events-cards non-scrolling">
                              <div class="row">
                      <?php

                        /**
                         *  Event Overview
                         *  --------------
                         */
                        if( parent:: _is_array( $_event_data ) ) {

                              foreach ( $_event_data as $key => $value) {

                                  if( $counter !== absint( '5' ) ){

                                        printf('<div class="col-md-6 col-sm-12 my-2">
                                                    <div class="card-body">
                                                        <div class="overview_event_icon counter %2$s_event_icon">%6$s</div>

                                                        <div class="event-text">
                                                            <div class="card-title %2$s_event_name">%1$s</div>

                                                            <span><strong class="%2$s_attending_counter">%3$s</strong> Attended</span>

                                                            <span><strong class="%2$s_invited_counter">%4$s</strong> Invited</span>

                                                            <span><strong class="%2$s_declain_counter">%5$s</strong> Declined</span>
                                                        </div>
                                                    </div>
                                                </div>',

                                                /**
                                                 *  1. Event Title
                                                 *  --------------
                                                 */
                                                esc_attr( $value[ 'event_name' ] ),

                                                /**
                                                 *  2. Event Unique ID
                                                 *  ------------------
                                                 */
                                                absint( $value[ 'event_unique_id' ] ),

                                                /**
                                                 *  3. Attending Counter
                                                 *  --------------------
                                                 */
                                                absint( $value[ 'attendance' ] ),

                                                /**
                                                 *  4. Get this event Invited guest
                                                 *  -------------------------------
                                                 */
                                                absint( $value[ 'invited' ] ),

                                                /**
                                                 *  5. Get this event declain guest
                                                 *  -------------------------------
                                                 */
                                                absint( $value[ 'declain' ] ),

                                                /**
                                                 *  6. Event Icon
                                                 *  -------------
                                                 */
                                                ( $value[ 'event_icon' ] !== '' )

                                                ?   sprintf( '<i class="%1$s"></i>', esc_attr( $value[ 'event_icon' ] )  )

                                                :   ''
                                        );

                                        $counter++;
                                  }
                              }
                        }

                    ?>  </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Load Events -->

                </div>
                <!-- Guest List -->
                <?php
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Guest_List_Left_Side_Filters:: get_instance();
}