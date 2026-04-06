<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Todo_Summary_Widget_Filters' ) && class_exists( 'SDWeddingDirectory_Todo_Filters' ) ){

    /**
     *  SDWeddingDirectory Todo Filter
     *  ----------------------
     */
    class SDWeddingDirectory_Todo_Summary_Widget_Filters extends SDWeddingDirectory_Todo_Filters{

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
             *  1. Dashboard Summary
             *  --------------------
             */            
            add_action( 'sdweddingdirectory/couple/dashboard/widget/summary', [ $this, 'summary_widget' ], absint( '20' ) );

            /**
             *  4. SDWeddingDirectory - Couple Mariage Status via Todo
             *  ----------------------------------------------
             */
            add_action( 'sdweddingdirectory/couple/dashboard/todo/progressbar', [ $this, 'progressbar_status' ], absint( '10' ) );

            /**
             *  Todo Overview Widget
             *  --------------------
             */
            add_action( 'sdweddingdirectory/couple-dashboard/overview/widget', [ $this, 'todo_overview' ], absint( '30' ), absint( '1' ) );
        }

        /**
         *  1. Todo Summary showing on Couple Dashboard as Overview
         *  -------------------------------------------------------
         */
        public static function summary_widget(){

          ?>
          <div class="col">
              <div class="couple-status-item">
                  <div class="counter TODO_DONE_COUNTER"><?php echo absint( parent:: number_of_todo( 'complete' ) ); ?></div>
                  <div class="text">
                      <strong>
                      <?php

                          /**
                           *  1. Showing Pending Todo
                           *  -----------------------
                           */
                          printf( esc_attr__( 'Out of %1$s', 'sdweddingdirectory-todo-list' ),

                              /**
                               *  1. Get Pending Todo Counter
                               *  ---------------------------
                               */
                              sprintf( '<span class="TODO_PENDING_COUNTER">%1$s</span>',

                                  /**
                                   *  1. Get Pending Todo
                                   *  -------------------
                                   */
                                  absint( parent:: number_of_todo( 'pending' ) )
                              )
                          );

                      ?>
                      </strong>

                      <small><?php esc_attr_e( 'Tasks completed', 'sdweddingdirectory-todo-list' ); ?></small>

                  </div>
              </div>
          </div>
          <?php
        }

        /**
         *  Todo : Processbar ( Status )
         *  ----------------------------
         */
        public static function progressbar_status(){

            /**
             *  Todo Status Overview Show Processbar with Done Task
             *  ---------------------------------------------------
             */
            if( absint( parent:: number_of_todo( 'counter' ) ) !== absint( '0' ) ){

                printf( '<div class="progress">

                            <div id="todo_progressbar" class="progress-bar bg-info" role="progressbar" style="width: %1$s"></div>

                        </div>',

                        /**
                         *  1. Get percentage value of todo status
                         *  --------------------------------------
                         */
                        number_format_i18n(

                            /**
                             *  1. Percentage
                             *  -------------
                             */
                            ( absint( parent:: number_of_todo( 'complete' ) ) * absint( '100' )  ) 

                            /  absint( parent:: number_of_todo( 'counter' ) ),

                            /**
                             *  2. pointer
                             *  ----------
                             */

                            absint( '1' )

                        ) . '%'
                );
            }
        }

        /**
         *  Todo Overview
         *  -------------
         */
        public static function todo_overview( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Args
                 *  ----
                 */
                extract( wp_parse_args( $args, [

                    'layout'        =>      absint( '1' ),

                    'post_id'       =>      absint( '0' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Is Layout 1 ?
                 *  -------------
                 */
                if( $layout == absint( '1' ) ){

                    ?><div class="couple-status"><?php

                        /**
                         *  Progrecss Bar
                         *  -------------
                         */
                        do_action( 'sdweddingdirectory/couple/dashboard/todo/progressbar' );

                        /**
                         *  SDWeddingDirectory - Just Said Yes
                         *  --------------------------
                         */
                        printf( '<div class="small-text">

                                    <span>%1$s</span><span>%2$s</span>

                                </div>',

                                /**
                                 *  1. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Status', 'sdweddingdirectory-todo-list' ),

                                /**
                                 *  2. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Just said yes? Let\'s get started!', 'sdweddingdirectory-todo-list' )
                        );

                    ?></div><?php
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Todo_Summary_Widget_Filters:: get_instance();
}