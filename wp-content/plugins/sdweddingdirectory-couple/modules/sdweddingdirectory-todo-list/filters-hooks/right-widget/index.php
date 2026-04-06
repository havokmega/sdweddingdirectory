<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Todo_Right_Widget_Filters' ) && class_exists( 'SDWeddingDirectory_Todo_Filters' ) ){

    /**
     *  SDWeddingDirectory Todo Filter
     *  ----------------------
     */
    class SDWeddingDirectory_Todo_Right_Widget_Filters extends SDWeddingDirectory_Todo_Filters{

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
             *  Dashboard Sidebar Widget
             *  ------------------------
             */
            add_action( 'sdweddingdirectory/couple/dashboard/widget/right-side', [ $this, 'widget' ], absint( '20' ) );
        }

        /**
         *  Dashboard Sidebar Widget : Todo
         *  -------------------------------
         */
        public static function widget(){

            ?>
            <div class="card-shadow">
                <div class="card-shadow-header">
                    <div class="dashboard-head">
                        <h3>
                            <span>
                              <?php

                                  printf(  esc_attr__( '%1$s of %2$s completed', 'sdweddingdirectory-todo-list' ),  

                                          /**
                                           *  1. Get Number to Done Todo
                                           *  ---------------------------
                                           */
                                          sprintf( '<small class="TODO_DONE_COUNTER">%1$s</small>',

                                              /**
                                               *  1. Todo Progressbar done status
                                               *  -------------------------------
                                               */
                                              absint( parent:: number_of_todo( 'complete' ) )
                                          ),


                                          /**
                                           *  2. Get Number to Pending Todo
                                           *  -----------------------------
                                           */
                                          sprintf( '<small class="TODO_PENDING_COUNTER">%1$s</small>',

                                              /**
                                               *  1. Todo Progressbar done status
                                               *  -------------------------------
                                               */
                                              absint( parent:: number_of_todo( 'pending' ) )
                                          )
                                  );

                              ?>
                            </span>
                            
                            <?php esc_attr_e( 'Upcoming tasks', 'sdweddingdirectory-todo-list' ); ?>

                        </h3>
                        <div class="links">
                            <?php

                                printf( '<a href="%1$s">%2$s <i class="fa fa-angle-right"></i></a>', 

                                    /**
                                     *  1. Todo page redirect link
                                     *  --------------------------
                                     */
                                    apply_filters( 'sdweddingdirectory/couple-menu/page-link', 'checklist' ),

                                    /**
                                     *  2. Button Text string translation ready
                                     *  ---------------------------------------
                                     */
                                    esc_attr__( 'View all tasks', 'sdweddingdirectory-todo-list' )
                                );

                            ?>
                        </div>
                    </div>
                </div>

                <div class="card-shadow-body p-0">
                <?php

                    /**
                     *  Get Todo
                     *  --------
                     */
                    $_get_todo    =   self:: get_todo_list();

                    /**
                    *  Check todo exists in database ?
                    *  -------------------------------
                    */
                    if( parent:: _is_array( $_get_todo ) ){

                      $_SEQUENCE_TODO = [];

                      /**
                       *  Array value "todo_timestrap" order set with assending
                       *  -----------------------------------------------------
                       */
                      array_multisort( array_column( $_get_todo, 'todo_timestrap'), SORT_ASC, $_get_todo );

                      foreach ( $_get_todo as $key => $value) {

                          $_SEQUENCE_TODO[ absint( $value[ 'todo_year' ] ) ][ absint( $value[ 'todo_month' ] ) ][] = $value;
                      }

                      /**
                       *  Year sorting order
                       *  ------------------
                       */
                      ksort( $_SEQUENCE_TODO );

                      /**
                       *  Accordion Section
                       *  -----------------
                       */
                      if( parent:: _is_array( $_SEQUENCE_TODO ) ){

                          print '<div class="upcoming-task"><ul class="list-unstyled">';

                          $_counter       = absint( '1' );

                          $_display_todo  = absint( '3' );

                          foreach( $_SEQUENCE_TODO as $_task_year => $_task_year_value  ){

                              foreach ( $_task_year_value as $_task_month_key => $_task_month_value ) {

                                  foreach ( $_task_month_value as $task_key => $task_value ) {

                                      if( $_counter !== $_display_todo && absint( $task_value[ 'todo_status' ] ) == absint( '0' ) ){

                                            printf('  <li id="%1$s" class="d-flex align-items-center">

                                                          <div class=" form-dark">

                                                              <input autocomplete="off" type="checkbox" class="form-check-input" id="todo_checkbox_%1$s" %5$s>

                                                              <label class="form-check-label %4$s" for="todo_checkbox_%1$s">

                                                                  <span class="label-text" id="todo_title_%1$s">%2$s</span>

                                                                  <small id="todo_overview_%1$s">%3$s</small>

                                                              </label>

                                                          </div>

                                                      </li>',

                                                      /**
                                                       *  1. Todo Unique ID
                                                       *  -----------------
                                                       */
                                                      absint( $task_value[ 'todo_unique_id' ] ),

                                                      /**
                                                       *  2. Todo Title
                                                       *  -------------
                                                       */
                                                      esc_attr( $task_value[ 'todo_title' ] ),

                                                      /**
                                                       *  3. Todo Overview
                                                       *  ----------------
                                                       */
                                                      esc_attr( $task_value[ 'todo_overview' ] ),

                                                      /**
                                                       *  4. If this todo already done label added class
                                                       *  ----------------------------------------------
                                                       */
                                                      ( absint( $task_value[ 'todo_status' ] ) == absint( '1' ) )

                                                      ?   sanitize_html_class( 'checked-label-text' )

                                                      :   '',

                                                      /**
                                                       *  5. If this todo already done checkbox checked attribute
                                                       *  --------------------------------------------------------
                                                       */
                                                      ( absint( $task_value[ 'todo_status' ] ) == absint( '1' ) )

                                                      ?   esc_attr( 'checked' )

                                                      :   ''
                                            );

                                            $_counter++;
                                      }
                                  }
                              }
                          }

                          print '</ul></div>';

                      } // if

                    } // if
                ?>
                </div>

            </div>
            <?php
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Todo_Right_Widget_Filters:: get_instance();
}