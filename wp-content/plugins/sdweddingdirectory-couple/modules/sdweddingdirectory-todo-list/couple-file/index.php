<?php
/**
 *  SDWeddingDirectory - Couple Todo List
 *  -----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Todo' ) && class_exists( 'SDWeddingDirectory_Todo_Database' ) ){

    /**
     *  SDWeddingDirectory - Couple Todo List
     *  -----------------------------
     */
    class SDWeddingDirectory_Todo extends SDWeddingDirectory_Todo_Database{

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
        public function __construct(){

            /**
             *  1. Dashboard Scripts
             *  --------------------
             */
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '30' ) );

            /**
             *  2. Dashboard Page
             *  -----------------
             */
            add_action( 'sdweddingdirectory/couple-dashboard', [$this, 'dashboard_page'], absint( '30' ), absint( '1' ) );
        }

        /**
         *  1. Dashboard Scripts
         *  --------------------
         */
        public static function sdweddingdirectory_script(){

            $_condition_1   =   parent:: dashboard_page_set( 'checklist' );

            $_condition_2   =   parent:: dashboard_page_set( 'couple-dashboard' );

            /**
             *  Is Dashboard || Todo
             *  --------------------
             */
            if( $_condition_1 || $_condition_2 ){

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_enqueue_script( 

                    /**
                     *  1. File Name
                     *  ------------
                     */
                    esc_attr( sanitize_title( __CLASS__ ) ),

                    /**
                     *  2. File Path
                     *  ------------
                     */
                    esc_url(   plugin_dir_url( __FILE__ )   .   'script.js'   ),

                    /**
                     *  3. Load After "JQUERY" Load
                     *  ---------------------------
                     */
                    array( 'jquery' ),

                    /**
                     *  4. Bootstrap - Library Version
                     *  ------------------------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ )   .   'script.js' ) ),

                    /**
                     *  5. Load in Footer
                     *  -----------------
                     */
                    true
                );

                /**
                 *  If is todo page
                 *  ---------------
                 */
                if( $_condition_1 ){

                    /**
                     *  Load Library Condition
                     *  ----------------------
                     */
                    add_filter( 'sdweddingdirectory/enable-script/date-picker', function( $args = [] ){

                        return  array_merge( $args, [ 'checklist'   =>  true ] );

                    } );

                    /**
                     *  Load Library Condition
                     *  ----------------------
                     */
                    add_filter( 'sdweddingdirectory/enable-script/slide-reveal', function( $args = [] ){

                        return  array_merge( $args, [ 'checklist'   =>  true ] );

                    } );
                }
            }
        }

        /**
         *  2. Dashboard Page
         *  -----------------
         */
        public static function dashboard_page( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args,[

                    'layout'        =>      absint( '1' ),

                    'post_id'       =>      '',

                    'page'          =>      ''

                ] ) );

                /**
                 *  Make sure this page is couple dashboard page
                 *  --------------------------------------------
                 */
                if( ! empty( $page ) && $page == esc_attr( 'checklist' ) ){

                    /**
                     *  Load one by one shortcode file
                     *  ------------------------------
                     */
                    foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
                   
                        require_once $file;
                    }

                    ?><div class="container"><?php

                        /**
                         *  1. Page Title
                         *  -------------
                         */
                        printf('<div class="section-title">

                                    <div class="d-sm-flex justify-content-between align-items-center">

                                        <h2 class="mb-xxl-0 mb-xl-0 mb-lg-0 mb-md-0 mb-sm-0 mb-3">%1$s</h2>

                                        <div class="">

                                            <button class="btn btn-sm btn-danger reset_todo me-2" data-alert="%5$s">%4$s</button>

                                            <button class="btn btn-sm btn-default add_new_todo_btn" id="%2$s"><i class="fa fa-plus"></i> %3$s</button>

                                            %6$s

                                        </div>

                                    </div>

                                </div>',

                                /**
                                 *  1. Title
                                 *  --------
                                 */
                                esc_attr__( 'Checklist', 'sdweddingdirectory-todo-list' ),

                                /**
                                 *  2. Button ID
                                 *  ------------
                                 */
                                esc_attr( 'sdweddingdirectory_add_todo_button' ),

                                /**
                                 *  3. Button Text
                                 *  --------------
                                 */
                                esc_attr__( 'Add New', 'sdweddingdirectory-todo-list' ),

                                /**
                                 *  4. Reset Todo
                                 *  -------------
                                 */
                                esc_attr__( 'Reset Todo', 'sdweddingdirectory-todo-list' ),

                                /**
                                 *  5. Message
                                 *  ----------
                                 */
                                esc_attr__( 'Make sure you wish to reset todo list', 'sdweddingdirectory-todo-list' ),

                                /**
                                 *  6. Security
                                 *  -----------
                                 */
                                wp_nonce_field( 

                                    'sdweddingdirectory_couple_todo_security', 

                                    'sdweddingdirectory_couple_todo_security', 

                                    true, false 
                                )
                        );

                        ?><div class="card-shadow"><?php

                            /**
                             *  1. Overview Todo Pending + Complete
                             *  ------------------------------------
                             */
                            self:: todo_dashboard_overview();

                            /**
                             *  2. Page of content
                             *  ------------------
                             */
                            self:: sdweddingdirectory_display_todo_list();

                        ?></div><?php

                    ?></div><?php
                }
            }
        }

        /**
         *  1. Overview Todo Pending + Complete
         *  ------------------------------------
         */
        public static function todo_dashboard_overview(){

            ?>
            <div class="card-shadow-body">

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <div class="todo-status">

                            <div class="me-3">

                                <strong><small>
                                <?php

                                    /**
                                     *  Todo Status
                                     *  -----------
                                     */
                                    printf( esc_attr__( 'You have completed %1$s out of %2$s tasks', 'sdweddingdirectory-todo-list' ),

                                        /**
                                         *  1. Get Number to Done Todo
                                         *  ---------------------------
                                         */
                                        sprintf( '<span class="TODO_DONE_COUNTER">%1$s</span>',

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
                                        sprintf( '<span class="TODO_PENDING_COUNTER">%1$s</span>',

                                            /**
                                             *  1. Todo Progressbar done status
                                             *  -------------------------------
                                             */
                                            absint( parent:: number_of_todo( 'pending' ) )
                                        )
                                    );

                                ?>                                
                              </small></strong>

                            </div>
                            <?php

                                /**
                                 *  Todo Status Overview Show Processbar with Done Task
                                 *  ---------------------------------------------------
                                 */
                                do_action( 'sdweddingdirectory/couple/dashboard/todo/progressbar' );
                            ?>
                        </div>

                    </div>

                    <div class="col-md-4">
                        
                        <div class="d-flex justify-content-md-end w-100">
                            
                            <div class="todo-done">
                             
                                <div class="badge badge-success">&nbsp;</div>
                                <?php

                                    /**
                                     *  1. Todo Done Status Counter
                                     *  ---------------------------
                                     */
                                    printf( ' <div>

                                                  <div class="TODO_DONE_COUNTER">%1$s</div>

                                                  <span>%2$s</span>

                                              </div>', 

                                          /**
                                           *  1. Get Number to Done Todo
                                           *  --------------------------
                                           */
                                          absint( parent:: number_of_todo( 'complete' ) ),

                                          /**
                                           *  2. Done String
                                           *  --------------
                                           */
                                          esc_attr__( 'Done', 'sdweddingdirectory-todo-list' )
                                    );

                                ?>
                            </div>
                            
                            <div class="todo-done">
                                
                                <div class="badge badge-warning">&nbsp;</div>
                                <?php

                                    /**
                                     *  1. Todo Pending Status Counter
                                     *  ------------------------------
                                     */
                                    printf( '<div>

                                                <div class="TODO_PENDING_COUNTER">%1$s</div>

                                                <span>%2$s</span>

                                              </div>',

                                          /**
                                           *  1. Get Number to Pending Todo
                                           *  -----------------------------
                                           */
                                          absint( parent:: number_of_todo( 'pending' ) ),

                                          /**
                                           *  2. To Do String
                                           *  ---------------
                                           */
                                          esc_attr__( 'To Do', 'sdweddingdirectory-todo-list' )
                                    );

                                ?>
                            </div>

                        </div>

                    </div>

                </div>
              
            </div>
            <?php
        }

        /**
         *  3. Page of content
         *  ------------------
         */
        public static function sdweddingdirectory_display_todo_list(){

              /**
               *  Have Any data
               *  -------------
               */
              do_action( 'sdweddingdirectory/couple-tools/todo/before' );

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

                      foreach( $_SEQUENCE_TODO as $_task_year => $_task_year_value  ){

                          foreach ( $_task_year_value as $_task_month_key => $_task_month_value ) {

                              $i = '';

                              foreach ( $_task_month_value as $key => $value) {
                                
                                  $i = $_task_month_value[ $key ]['todo_date'];
                              }

                              printf(  '<div class="todo-subhead mb-0">
                                            <h3>%1$s <span>%2$s</span></h3>
                                        </div>',

                                        /**
                                         *  1. Month
                                         *  --------
                                         */
                                        date_i18n( 'F', strtotime( $i ) ),

                                        /**
                                         *  2. Year
                                         *  --------
                                         */
                                        absint( $_task_year )
                              );

                              print '<div class="upcoming-task"><ul class="list-unstyled">';

                              foreach ( $_task_month_value as $task_key => $task_value ) {

                                  printf('  <li id="%1$s">

                                              <div class="d-flex align-items-center">

                                                  <div class=" form-dark">

                                                      <input autocomplete="off" type="checkbox" class="form-check-input" id="todo_checkbox_%1$s" %10$s>

                                                      <label class="form-check-label %9$s" for="todo_checkbox_%1$s">

                                                          <span class="label-text" id="todo_title_%1$s">%2$s</span>

                                                          <small id="todo_overview_%1$s">%3$s</small>

                                                      </label>

                                                  </div>

                                              </div>

                                              <div class="info-venue align-items-center">

                                                  <div class="badge-wrap">

                                                      <div>%4$s</div>

                                                      <span class="badge badge-primary" data-date="%8$s" id="todo_date_%1$s">%5$s</span>

                                                  </div>

                                                  <div class="badge-wrap">

                                                      <div>%6$s</div> %7$s

                                                  </div>

                                                  <div class="badge-wrap text-center">

                                                      <span class="d-flex">

                                                          <a href="javascript:" data-unique-id="%1$s" id="edit_%1$s" class="action-links todo_edit edit mx-2"><i class="fa fa-pencil"></i></a>

                                                          <a href="javascript:" data-unique-id="%1$s" id="delete_%1$s" data-todo-removed-alert="%11$s" class="action-links todo_delete"><i class="fa fa-trash"></i></a>

                                                      </span>

                                                  </div>

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
                                             *  4. Task Date Label
                                             *  ------------------
                                             */
                                            esc_attr__( 'Task Date', 'sdweddingdirectory-todo-list' ),

                                            /**
                                             *  5. Todo Date Formate
                                             *  --------------------
                                             */
                                            apply_filters( 'sdweddingdirectory/date-format', [

                                                'date'      =>      $task_value[ 'todo_date' ]

                                            ] ),

                                            /**
                                             *  6. Todo Status Label
                                             *  --------------------
                                             */
                                            esc_attr__( 'Status', 'sdweddingdirectory-todo-list' ),

                                            /**
                                             *  7. Todo Status Badge
                                             *  --------------------
                                             */
                                            self:: todo_status_badge( absint( $task_value[ 'todo_status' ] ) ),

                                            /**
                                             *  8. Todo Date Formate accepted in database
                                             *  -----------------------------------------
                                             */
                                            apply_filters( 'sdweddingdirectory/date-format', [

                                                'date'      =>      $task_value[ 'todo_date' ]

                                            ] ),

                                            /**
                                             *  9. If this todo already done label added class
                                             *  ----------------------------------------------
                                             */
                                            ( absint( $task_value[ 'todo_status' ] ) == absint( '1' ) )

                                            ?   sanitize_html_class( 'checked-label-text' )

                                            :   '',

                                            /**
                                             *  10. If this todo already done checkbox checked attribute
                                             *  --------------------------------------------------------
                                             */
                                            ( absint( $task_value[ 'todo_status' ] ) == absint( '1' ) )

                                            ?   esc_attr( 'checked' )

                                            :   '',

                                            /**
                                             *  11. Delete Toto alert confirm via jQuery
                                             *  ----------------------------------------
                                             */
                                            esc_attr__( 'Are you sure to removed this task ?', 'sdweddingdirectory-todo-list' )
                                  );
                              }

                              print '</ul></div>';
                          }
                      }

                  } // if
            }

            /**
             *  Update Default Todo
             *  -------------------
             */
            else{

                /**
                 *  Update Default Todo
                 *  -------------------
                 */
                parent:: default_checklist_data( [

                    'post_id'           =>      absint( get_the_ID() ),

                ] );

                /**
                 *  ---------------------------------------
                 *  Data Updated : Again call this function
                 *  ---------------------------------------
                 */
                self:: sdweddingdirectory_display_todo_list();
            }
        }

        /**
         *  Status Badge
         *  ------------
         */
        public static function todo_status_badge( $status = '0' ){

            /**
             *  Extract
             *  -------
             */
            extract( [

                'todo_done_string'      =>   esc_attr__( 'Done', 'sdweddingdirectory-todo-list' ),

                'todo_pending_string'   =>   esc_attr__( 'Pending', 'sdweddingdirectory-todo-list' ),

            ] );

            /**
             *  Status
             *  ------
             */
            if( $status == absint( '1' ) ){

                return          sprintf( '<span class="todo_status_badge badge badge-success" 

                                            data-todo-done-string="%1$s" 

                                            data-todo-pending-string="%2$s">%1$s</span>',

                                            /**
                                             *  1. Task Done strings translation ready
                                             *  --------------------------------------
                                             */
                                            esc_attr( $todo_done_string ),

                                            /**
                                             *  2. Task Pending string translation ready
                                             *  ----------------------------------------
                                             */
                                            esc_attr( $todo_pending_string )
                                );
            }

            /**
             *  Default
             *  -------
             */
            else{

                return          sprintf( '<span class="todo_status_badge badge badge-pending" 

                                            data-todo-done-string="%1$s" 

                                            data-todo-pending-string="%2$s">%2$s</span>',

                                            /**
                                             *  1. Task Done strings translation ready
                                             *  --------------------------------------
                                             */
                                            esc_attr( $todo_done_string ),

                                            /**
                                             *  2. Task Pending string translation ready
                                             *  ----------------------------------------
                                             */
                                            esc_attr( $todo_pending_string )
                                );
            }
        }

    } /* class end **/

    /**
     *  SDWeddingDirectory - Couple Todo List
     *  -----------------------------
     */
    SDWeddingDirectory_Todo:: get_instance();
}