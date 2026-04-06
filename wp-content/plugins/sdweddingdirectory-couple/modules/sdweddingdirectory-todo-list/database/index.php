<?php
/**
 *  SDWeddingDirectory - Todo Database
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Todo_Database' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory - Todo Database
     *  --------------------------
     */
    class SDWeddingDirectory_Todo_Database extends SDWeddingDirectory_Config{

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
         *  File Version
         *  ------------
         */
        public static function _file_version( $file = '' ){

            /**
             *  Is Empty ?
             *  ----------
             */
            if( empty( $file ) ){

                /**
                 *  Get Style Version
                 *  -----------------
                 */

                return      esc_attr( SDWEDDINGDIRECTORY_TODO_VERSION );

            }else{

                /*
                 *  For File Save timestrap version to clear the catch auto
                 *  -------------------------------------------------------
                 *  # https://developer.wordpress.org/reference/functions/wp_enqueue_style/#comment-6386
                 *  ------------------------------------------------------------------------------------
                 */

                return      esc_attr( SDWEDDINGDIRECTORY_TODO_VERSION ) . '.' . absint( filemtime(  $file ) );
            }
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  ---------------------
             *  Get Default Todo Data
             *  ---------------------
             */
            add_action( 'sdweddingdirectory/user-register/couple', [ $this, 'default_checklist_data' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Default Checklist Data
         *  ----------------------
         */
        public static function default_checklist_data( $args = [] ){

            /**
             *  Make sure get args
             *  ------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'           =>      absint( '0' ),

                    'enable'            =>      sdweddingdirectory_option( 'default_checklist_data_switch' ),

                    'default_list'      =>      sdweddingdirectory_option( 'admin_create_default_todo_list' ),

                    'handler'           =>      [],

                    'meta_key'          =>      sanitize_key( 'todo_list' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Wedding Date
                 *  ------------
                 */
                $wedding_date       =   get_post_meta( $post_id, sanitize_key( 'wedding_date' ), true );

                /**
                 *  Make sure couple have wedding date 
                 *  ----------------------------------
                 */
                if( empty( $wedding_date ) ){

                    $wedding_date       =       esc_attr( date( 'Y-m-d' ) );
                }

                /** 
                 *  Default Checklist Data Import Setting is *Enable* ?
                 *  ---------------------------------------------------
                 */
                if( $enable == esc_attr( 'on' ) &&  parent:: _is_array( $default_list ) &&  ! empty( $wedding_date ) ){

                    /**
                     *  Get Time
                     *  --------
                     */
                    $time                     =   strtotime( esc_attr( $wedding_date ) );

                    /**
                     *  Loop
                     *  ----
                     */
                    foreach( $default_list as $key => $value ){

                        $handler[]              =       [

                            'title'             =>      esc_attr( $value[ 'title' ] ),

                            'todo_title'        =>      esc_attr( $value[ 'todo_title' ] ),

                            'todo_timestrap'    =>      strtotime(  date( "Y-m-d", strtotime( $value[ 'todo_period' ], $time ) )  ),

                            'todo_overview'     =>      esc_attr( $value[ 'todo_overview' ] ),

                            'todo_date'         =>      date( "Y-m-d", strtotime( $value[ 'todo_period' ], $time ) ),

                            'todo_year'         =>      date( "Y", strtotime( $value[ 'todo_period' ], $time ) ),

                            'todo_month'        =>      date( "m", strtotime( $value[ 'todo_period' ], $time ) ),

                            'todo_status'       =>      absint('0'),

                            'todo_unique_id'    =>      absint( rand() ),
                        ];
                    }
                }
            }

            /**
             *  Make sure checklist data exists
             *  -------------------------------
             */
            if( parent:: _is_array( $handler ) ){

                update_post_meta( $post_id, sanitize_key( $meta_key ), $handler );
            }
        }

        /**
         *  1. Get Todo List Data
         *  ----------------------
         */
        public static function get_todo_list(){

            return parent:: get_data( sanitize_key( 'todo_list' ) );
        }

        /**
         *  2. Default Alert Message
         *  ------------------------
         */
        public static function get_default_todo_message(){

            if( sdweddingdirectory_option( 'default_todo_alert' ) !== '' ){

                return sdweddingdirectory_option( 'default_todo_alert' );

            }else{

                return esc_attr__( 'Yes, this is awesome!', 'sdweddingdirectory-todo-list' );
            }
        }

        /**
         *  3. Get Todo key through "unique_id" using
         *  -----------------------------------------
         */
        public static function todo_id( $unique_id ){

            $data = self:: get_todo_list();

          	foreach ( $data as $key => $value) {

              	if( $unique_id == $value[ 'todo_unique_id' ] ){

	                return $key;
              	}
            }
        }

        /**
         *  4. Print : Get Todo Summary Details
         *  ------------------------------------
         */
        public static function number_of_todo_e( $args ){

            echo self:: number_of_todo( $args );
        }

        /**
         *  5. Return : Get Todo Summary Details
         *  ------------------------------------
         */
        public static function number_of_todo( $args ){

            $counter = absint( '0' );

            if( empty( $args ) ) return $counter;

            $get_data  = self:: get_todo_list();

            if( SDWeddingDirectory_Loader:: _is_array( $get_data ) ){

                if( $args == esc_attr( 'counter' ) ){

                    return absint( count( $get_data ) );
                }

                if( $args == esc_attr( 'pending' ) ){

                    foreach( $get_data as $key => $value ){

                        if( $value['todo_status'] == absint( '0' ) ){

                            $counter++;
                        }
                    }

                    return absint( $counter );
                }

                if( $args == esc_attr( 'due' ) ){

                    foreach( $get_data as $key => $value ){

                        if( $value['todo_status'] !=  absint( '1' )  ){

                              if( strtotime( date( 'm/d/Y' ) ) >= strtotime( $value[ 'todo_date' ] ) ){

                                    $counter++;
                              }
                        }
                    }

                    return absint( $counter );
                }

                if( $args == esc_attr( 'complete' ) ){

                    foreach( $get_data as $key => $value ){

                        if( $value['todo_status'] == absint( '1' ) ){

                            $counter++;
                        }
                    }

                    return absint( $counter );
                }
            }

            return absint( $counter );
        }

        /**
         *  6. Todo Status Check through unique_id using.
         *  ---------------------------------------------
         */
        public static function is_todo_complete( $uniqid ){

              $error = false;

              if( empty( $uniqid ) ) 
                  return false;

              $get_data   =   self:: get_todo_list();

              if( ! is_array( $get_data ) ) 
                    return false;

              foreach( $get_data as $key => $value ){

                  if( $key == self:: todo_id( $uniqid ) ){

                      if( $value['todo_status'] == absint( '1' ) ){

                          $error = true;
                      }
                  }
              }

              return $error;
        }

        /**
         *  7. Todo Status
         *  --------------
         */
        public static function todo_status( $uniqid ){

            if( empty( $uniqid ) ){

                return false;
            }

            $get_status = '';

            $get_data   = self:: get_todo_list();


            if( ! is_array( $get_data ) ){

                  return false;
            }

            foreach( $get_data as $key => $value ){

                if( $key == self:: todo_id( $uniqid ) ){

                    if( $value['todo_status'] ==  absint( '1' )  ){

                        $get_status .= sprintf('<span class="badge bg-success">%1$s</span>', esc_html__( 'Complete', 'sdweddingdirectory-todo-list' ) );
                    }

                    if( $value['todo_status'] == absint( '0' )  ){

                        $get_status .= sprintf('&nbsp;<span class="badge bg-warning">%1$s</span>', esc_html__( 'Pending', 'sdweddingdirectory-todo-list' ) );
                    }
                }
            }

            return $get_status;
        }

    } /* class end **/

    /**
     *  SDWeddingDirectory - Todo Database
     *  --------------------------
     */
    SDWeddingDirectory_Todo_Database:: get_instance();
}