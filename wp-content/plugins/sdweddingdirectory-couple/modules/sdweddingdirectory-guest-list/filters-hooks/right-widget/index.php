<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_Right_Side_Filters' ) && class_exists( 'SDWeddingDirectory_Guest_List_Filters' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_Guest_List_Right_Side_Filters extends SDWeddingDirectory_Guest_List_Filters{

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
             *  Dashboard Sidebar Summary
             *  -------------------------
             */
            add_action( 'sdweddingdirectory/couple/dashboard/widget/right-side', [ $this, 'widget' ], absint( '40' ) );
        }

        /**
         *  Dashboard Sidebar Summary
         *  -------------------------
         */
        public static function widget(){

            /**
             *  Guest List
             *  ----------
             */
            $_guest_data    =   parent:: guest_list();

            /**
             *  Handler
             *  -------
             */
            $_counter       =   absint( '1' );

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $_guest_data ) ){

                ?>
                <div class="card-shadow">

                    <div class="card-shadow-header">

                        <div class="dashboard-head">
                        <?php

                              /**
                               *  Guest List Wiget Summery Title + Redirect link
                               *  ------------------------------------------
                               */
                              printf(  '<h3>%1$s</h3>

                                        <div class="links">

                                            <a href="%2$s">%3$s <i class="fa fa-angle-right"></i></a>

                                        </div>',

                                        /**
                                         *  1. String Translation
                                         *  ---------------------
                                         */
                                        esc_attr__( 'RSVP Pending', 'sdweddingdirectory-guest-list' ),

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

                    <div class="card-shadow-body p-0">

                        <div class="guest-list-avatar">
                        <?php

                          foreach( $_guest_data as $key => $value ){

                              /**
                               *  Extract
                               *  -------
                               */
                              extract( $value );

                              /**
                               *  Invitation Status
                               *  -----------------
                               */
                              if( $invitation_status == absint( '0' ) && $_counter !== absint( '3' ) ){

                                    /**
                                     *  Load the RSVP Guests
                                     *  --------------------
                                     */
                                    printf(   '<a href="%1$s" class="">
                                              
                                                  <div class="link-arrow">

                                                      <i class="fa fa-angle-right"></i>

                                                  </div>

                                                  <i class="fa fa-male"></i>

                                                  <div class="group-info">%2$s %3$s<span>%4$s</span></div>

                                              </a>',

                                            /**
                                             *  1. Guest List Page Link
                                             *  -------------------
                                             */
                                            esc_url_raw(  add_query_arg(

                                                /**
                                                 *  Guest ID
                                                 *  --------
                                                 */
                                                [ 'guest-id'   =>  $guest_unique_id ],

                                                /**
                                                 *  2. Page link
                                                 *  ------------
                                                 */
                                                apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'guest-management-rsvp' ) )

                                            ) ),

                                            /**
                                             *  2. Guest First Name
                                             *  -------------------
                                             */
                                            esc_attr( $value[ 'first_name' ] ),

                                            /**
                                             *  3. Guest Last Name
                                             *  ------------------
                                             */
                                            esc_attr( $value[ 'last_name' ] ),

                                            /**
                                             *  4. Group Name
                                             *  -------------
                                             */
                                            esc_attr( $guest_group )
                                    );

                                    /**
                                     *  Counter
                                     *  -------
                                     */
                                    $_counter++;
                              }
                          }

                        ?>
                        </div>

                    </div>

                </div>
                <?php
            }

            /**
             *  Guestlist
             *  ---------
             */
            else{

                  ?>
                  
                  <div class="card-shadow">
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
                                          esc_attr__( 'See Guest List', 'sdweddingdirectory-guest-list' ),

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

                      <div class="card-shadow-body">
                          <div class="empty-guest-list">
                              <?php

                                /**
                                 *  Guest List Wiget Summery Title + Redirect link
                                 *  ------------------------------------------
                                 */
                                printf( '<i class="sdweddingdirectory-guest-member"></i>
                                          <p>%1$s</p>
                                          <a class="btn btn-outline-default btn-rounded" href="%2$s">%3$s</a>',

                                          /**
                                           *  1. String Translation
                                           *  ---------------------
                                           */
                                          esc_attr__( 'You haven\'t added any guests yet', 'sdweddingdirectory-guest-list' ),

                                          /**
                                           *  2. Guest List Page Link
                                           *  -------------------
                                           */
                                          apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'guest-management' ) ),

                                          /**
                                           *  3. String Translation
                                           *  ---------------------
                                           */
                                          esc_attr__( 'Add guest', 'sdweddingdirectory-guest-list' )
                                );

                              ?>
                          </div>
                      </div>
                      
                  </div>
                  
                  <?php
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Guest_List_Right_Side_Filters:: get_instance();
}