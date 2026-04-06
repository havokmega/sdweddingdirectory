<?php
/**
 *  SDWeddingDirectory Couple Todo List AJAX Script Action HERE
 *  ---------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Todo_AJAX' ) && class_exists( 'SDWeddingDirectory_Todo_Database' ) ){

    /**
     *  SDWeddingDirectory Couple Todo List AJAX Script Action HERE
     *  ---------------------------------------------------
     */
    class SDWeddingDirectory_Todo_AJAX extends SDWeddingDirectory_Todo_Database{

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
             *  1. Have AJAX action ?
             *  ---------------------
             */
            if(  wp_doing_ajax()  ){

                /**
                 *  Have AJAX action ?
                 *  ------------------
                 */
                if( isset( $_POST['action'] ) && $_POST['action'] !== '' ){

                    /**
                     *  Have AJAX action ?
                     *  ------------------
                     */
                    $action     =   esc_attr( trim( $_POST['action'] ) );

                    /**
                     *  1.  Bit of security
                     *  -------------------
                     */
                    $allowed_actions = array(

                        /**
                         *  1. Add New Todo Task
                         *  --------------------
                         */
                        esc_attr( 'sdweddingdirectory_couple_add_todo_list' ),

                        /**
                         *  2. Complete Todo Action
                         *  -----------------------
                         */
                        esc_attr( 'sdweddingdirectory_couple_complete_todo_task' ),

                        /**
                         *  3. Removed Todo Action
                         *  ----------------------
                         */
                        esc_attr( 'sdweddingdirectory_couple_remove_todo_id' ),

                        /**
                         *  4. Edit Todo Action
                         *  -------------------
                         */
                        esc_attr( 'sdweddingdirectory_couple_edit_todo_id' ),

                        /**
                         *  5. Reset Todo
                         *  -------------
                         */
                        esc_attr( 'sdweddingdirectory_couple_reset_todo' )
                    );

                    /**
                     *  If have action then load this object members
                     *  --------------------------------------------
                     */
                    if( in_array( $action, $allowed_actions) ) {

                        /**
                         *  Check the AJAX action with login user
                         *  -------------------------------------
                         */
                        if( is_user_logged_in() ){

                            /**
                             *  1. If user already login then AJAX action
                             *  -----------------------------------------
                             */
                            add_action( 'wp_ajax_' . $action, [ $this, $action ] );

                        }else{

                            /**
                             *  2. If user not login that mean is front end AJAX action
                             *  -------------------------------------------------------
                             */
                            add_action( 'wp_ajax_nopriv_' . $action, [ $this, $action ] );
                        }
                    }
                }
            }
        }

        /**
         *  1. Add New Todo Task
         *  --------------------
         */
        public static function sdweddingdirectory_couple_add_todo_list(){

            global $post, $wp_query;
            
            $new_request_list   =   [];

            $request_array      =   array( array(

                'title'             =>  esc_attr( $_POST['todo_title'] ),

                'todo_title'        =>  esc_attr( $_POST['todo_title'] ),

                'todo_overview'     =>  esc_attr( $_POST['todo_overview'] ),

                'todo_timestrap'    =>  strtotime( esc_attr( $_POST['todo_date'] ) ),

                'todo_date'         =>  esc_attr( $_POST['todo_date'] ),

                'todo_year'         =>  date_parse( esc_attr( $_POST['todo_date'] ) )['year'],

                'todo_month'        =>  date_parse( esc_attr( $_POST['todo_date'] ) )['month'],

                'todo_unique_id'    =>  absint( rand() ),

                'todo_status'       =>  absint( '0' ),
            ) );

            $request_list_array   = parent:: get_todo_list();

            if( $request_list_array != '' && is_array( $request_list_array ) ){

                $new_request_list  =  array_merge_recursive( $request_list_array, $request_array );

            }else{

                $new_request_list  =  $request_array;
            }

            update_post_meta( absint( parent:: post_id() ), 'todo_list', $new_request_list );

            die( json_encode( array(

                'message' => esc_attr__( 'Task Added', 'sdweddingdirectory-todo-list' ),

                'notice'  => absint( '1' )

            ) ) );
        }

        /**
         *  2. Complete Todo Action
         *  -----------------------
         */
        public static function sdweddingdirectory_couple_complete_todo_task(){

            if( isset( $_POST[ 'todo_unique_id' ] ) && $_POST[ 'todo_unique_id' ] !== '' && 
                isset( $_POST[ 'todo_status' ] )    && $_POST[ 'todo_status' ]    !== '' ){

                $_todo_list = parent:: get_todo_list();

                if( SDWeddingDirectory_Loader:: _is_array( $_todo_list ) ){

                    foreach ( $_todo_list as $key => $value) {
                        
                        if( absint( $_POST[ 'todo_unique_id' ] ) == $value[ 'todo_unique_id' ] ){

                                $_todo_list[ $key ] = array(

                                    'title'             =>  esc_attr( $value['todo_title'] ),

                                    'todo_title'        =>  esc_attr( $value['todo_title'] ),

                                    'todo_overview'     =>  esc_attr( $value['todo_overview'] ),

                                    'todo_timestrap'    =>  esc_attr( $value['todo_timestrap'] ),

                                    'todo_date'         =>  esc_attr( $value['todo_date'] ),

                                    'todo_year'         =>  esc_attr( $value['todo_year'] ),

                                    'todo_month'        =>  esc_attr( $value['todo_month'] ),

                                    'todo_unique_id'    =>  esc_attr( $value['todo_unique_id'] ),

                                    'todo_status'       =>  absint( $_POST[ 'todo_status' ]  ),
                                );


                                /**
                                 *  Task updated in database
                                 *  ------------------------
                                 */
                                update_post_meta( parent:: post_id(), 'todo_list', $_todo_list );

                                /**
                                 *  Task Done Alert
                                 *  ---------------
                                 */
                                
                                die( json_encode( array(

                                    'message'           =>  ( absint( $_POST[ 'todo_status' ]  ) == absint( '1' ) )

                                                            ?   esc_attr__( 'Task Done Successfully!', 'sdweddingdirectory-todo-list' )

                                                            :   esc_attr__( 'Task In Pending Mode!', 'sdweddingdirectory-todo-list' ),

                                    'notice'            =>  ( absint( $_POST[ 'todo_status' ]  ) == absint( '1' ) )

                                                            ?   absint( '1' )

                                                            :   absint( '2' ),

                                    'pending_task'      =>  absint( parent:: number_of_todo( 'pending' ) ),

                                    'done_task'         =>  absint( parent:: number_of_todo( 'complete' ) ),

                                    'todo_progressbar'  =>  number_format_i18n(


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

                                ) ) );
                        }

                    }

                }

            }
        }

        /**
         *  3. Removed Todo Action
         *  ----------------------
         */
        public static function sdweddingdirectory_couple_remove_todo_id(){

            if( isset( $_POST['todo_unique_id'] )  && $_POST['todo_unique_id'] !== '' ){

                $get_data   =   parent:: get_todo_list();

                unset( $get_data[ absint( self:: todo_id( $_POST['todo_unique_id'] )  ) ] );

                update_post_meta( absint( parent:: post_id() ), 'todo_list', $get_data );

                $get_data   =   parent:: get_todo_list();

                /**
                 *  Make sure have data
                 *  -------------------
                 */
                if( parent:: _is_array( $get_data ) ){

                    die( json_encode( array(

                        'notice'            =>  absint( '1' ),

                        'message'           =>  esc_attr__( 'Todo Removed Successfully!', 'sdweddingdirectory-todo-list' ),

                        'pending_task'      =>  absint( parent:: number_of_todo( 'pending' ) ),

                        'done_task'         =>  absint( parent:: number_of_todo( 'complete' ) ),

                        'todo_progressbar'  =>  number_format_i18n(


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

                    ) ) );
                }

                else{

                    /**
                     *  Default Todo Update
                     *  -------------------
                     */
                    parent:: default_checklist_data( [

                        'post_id'   =>      absint( parent:: post_id() )

                    ] );
                }
            }
        }

        /**
         *  4. Edit Todo Action
         *  -------------------
         */
        public static function sdweddingdirectory_couple_edit_todo_id(){

            if( isset( $_POST['todo_unique_id'] ) ){

                $get_data       =   parent:: get_todo_list();

                if( SDWeddingDirectory_Loader:: _is_array( $get_data ) ) {

                    foreach ( $get_data as $index => $data ) {

                        if ( absint( $data['todo_unique_id'] ) == absint( $_POST['todo_unique_id'] ) ) {

                            $get_data[ $index ]     =   '';

                            $get_data[ $index ]     =   array(

                                'title'             =>  esc_attr( $_POST['todo_title'] ),

                                'todo_title'        =>  esc_attr( $_POST['todo_title'] ),

                                'todo_overview'     =>  esc_attr( $_POST['todo_overview'] ),

                                'todo_timestrap'    =>  strtotime( esc_attr( $_POST['todo_date'] ) ),

                                'todo_date'         =>  esc_attr( $_POST['todo_date'] ),

                                'todo_year'         =>  date_parse( esc_attr( $_POST['todo_date'] ) )['year'],

                                'todo_month'        =>  date_parse( esc_attr( $_POST['todo_date'] ) )['month'],

                                'todo_unique_id'    =>  absint( $_POST['todo_unique_id'] ),

                                'todo_status'       =>  absint( $data[ 'todo_status' ] ),
                            );
                        }
                    }

                    update_post_meta( absint( parent:: post_id() ), 'todo_list', $get_data );
                  
                    die( json_encode( array( 

                          'redirect'  =>  false, 

                          'message'   =>  esc_attr__( 'Successfully Update', 'sdweddingdirectory-todo-list' )
                    ) ) );
                }
            }
        }

        /**
         *  Reset Todo
         *  ----------
         */
        public static function sdweddingdirectory_couple_reset_todo(){

            /**
             *  Couple Registration through AJAX action With Test Security
             *  ----------------------------------------------------------
             */
            if( wp_verify_nonce( $_POST['security'], 'sdweddingdirectory_couple_todo_security' )  ){

                /**
                 *  Default Todo Update
                 *  -------------------
                 */
                parent:: default_checklist_data( [

                    'post_id'       =>      absint( parent:: post_id() )

                ] );

                /**
                 *  Reset Todo
                 *  ----------
                 */
                die( json_encode( [

                    'message'       =>      esc_attr__( 'Your Todo Reset Successfully!', 'sdweddingdirectory-todo-list' ),

                    'notice'        =>      absint( '1' )

                ] ) );
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Todo_AJAX:: get_instance();
}