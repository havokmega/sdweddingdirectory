<?php
/**
 * ------------
 *  Option Tree
 * ------------
 *
 *  @link( https://github.com/valendesigns/option-tree )
 *
 *  @link( https://github.com/valendesigns/option-tree-theme/blob/master/inc/meta-boxes.php, link)
 *  
 */
if( ! class_exists( 'SDWeddingDirectory_CheckList_Meta' ) ){

    /**
     *  SDWeddingDirectory CheckList
     *  ---------------------
     */
    class SDWeddingDirectory_CheckList_Meta {

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
             *  1. Todo - Checklist Tools Meta Filter
             *  -------------------------------------
             */
            add_filter( 'sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_couple_meta' ], absint('10') );
        }

        /**
         *  1. Todo - Checklist Tools Meta Filter
         *  -------------------------------------
         */
        public static function sdweddingdirectory_couple_meta( $args = [] ){

            $new_args   =   array(

                'id'        =>  esc_attr( 'SDWeddingDirectory_Todo_List' ),

                'title'     =>  esc_attr__( 'Couple Todo List', 'sdweddingdirectory-todo-list' ),

                'pages'     =>  array( 'couple' ),

                'context'   =>  esc_attr( 'normal' ),

                'priority'  =>  esc_attr( 'high' ),

                'fields'    =>  array(

                    array(

                        'id'        =>  sanitize_key( 'todo_list' ),

                        'type'      =>  esc_attr( 'list-item' ),

                        'section'   =>  esc_attr( 'option_types' ),

                        'settings'  =>  array(

                            array(

                                'id'     =>  sanitize_key( 'todo_title' ),

                                'label'  =>  esc_attr__( 'Todo Title', 'sdweddingdirectory-todo-list' ),

                                'type'   =>  esc_attr( 'text' ),
                            ),
                            array(

                                'id'     =>  sanitize_key( 'todo_timestrap' ),

                                'label'  =>  esc_attr__( 'Todo TimeStrap', 'sdweddingdirectory-todo-list' ),

                                'type'   =>  esc_attr( 'text' ),
                            ),
                            array(

                                'id'     =>  sanitize_key( 'todo_date' ),

                                'label'  =>  esc_attr__( 'Todo Date', 'sdweddingdirectory-todo-list' ),

                                'type'   =>  esc_attr( 'date-time-picker' ),
                            ),
                            array(

                                'id'     =>  sanitize_key( 'todo_year' ),

                                'label'  =>  esc_attr__( 'Todo Year', 'sdweddingdirectory-todo-list' ),

                                'type'   =>  esc_attr( 'text' ),
                            ),
                            array(

                                'id'     =>  sanitize_key( 'todo_month' ),

                                'label'  =>  esc_attr__( 'Todo Month', 'sdweddingdirectory-todo-list' ),

                                'type'   =>  esc_attr( 'text' ),
                            ),
                            array(

                                'id'     =>  sanitize_key( 'todo_status' ),

                                'label'  =>  esc_attr__( 'Todo Status', 'sdweddingdirectory-todo-list' ),

                                'type'   =>  esc_attr( 'text' ),
                            ),
                            array(

                                'id'     =>  sanitize_key( 'todo_overview' ),

                                'label'  =>  esc_attr__( 'Todo Overview', 'sdweddingdirectory-todo-list' ),

                                'type'   =>  esc_attr( 'text' ),
                            ),
                            array(

                                'id'     =>  sanitize_key( 'todo_unique_id' ),

                                'label'  =>  esc_attr__( 'Todo Unique ID', 'sdweddingdirectory-todo-list' ),

                                'type'   =>  esc_attr( 'text' ),
                            ),
                        ),
                    ),
                ),
            );

            /**
             *  Return : Meta
             *  -------------
             */
            return  array_merge( $args, array( $new_args ) );
        }
    }

    /**
     *  Todo Checklist : Meta Object
     *  ----------------------------
     */
    SDWeddingDirectory_CheckList_Meta::get_instance();
}