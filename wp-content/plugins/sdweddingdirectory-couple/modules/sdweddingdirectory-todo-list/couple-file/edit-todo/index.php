<?php
/**
 *  SDWeddingDirectory Couple Todo List
 *  ---------------------------
 */

if( ! class_exists( 'SDWeddingDirectory_Edit_Todo_Form' ) && class_exists( 'SDWeddingDirectory_Todo' ) ){

    /**
     *  SDWeddingDirectory Requests
     *  -------------------
     */
    class SDWeddingDirectory_Edit_Todo_Form extends SDWeddingDirectory_Todo{

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
             *  1. Add New Todo Task Form
             *  -------------------------
             */            
            add_action( 'wp_footer', [$this, 'sdweddingdirectory_edit_todo_form_markup'] );
        }

        /**
         *  1. Load Edit Todo Form
         *  ------------------------
         */
        public static function sdweddingdirectory_edit_todo_form_markup(){

            ?>

            <div id="sdweddingdirectory_edit_todo_form" class="sliding-panel">

                <div class="card-shadow-header mb-0">
                    <div class="dashboard-head">

                        <h3><?php esc_html_e( 'Edit Task', 'sdweddingdirectory-todo-list' ); ?></h3>

                    </div>
                </div>

                <div class="card-shadow-body">

                    <form id="couple_edit_todo_list" method="post" autocomplete="off" >

                        <div class="row"><?php self:: sdweddingdirectory_edit_todo_task_content(); ?></div>

                    </form>

                </div>

            </div>

            <?php
        }

        /**
         *  1. Load Edit Todo Form
         *  ------------------------
         */
        public static function sdweddingdirectory_edit_todo_task_content(){

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <label class="control-label" for="%1$s">%2$s</label>
                            <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%3$s" class="form-control form-light" required="">
                        </div>
                    </div>',

                    // 1
                    esc_html( 'edit_todo_title' ),

                    // 2
                    esc_html__( 'Edit Task Title', 'sdweddingdirectory-todo-list' ),

                    // 3
                    esc_attr__( 'Edit Task Title', 'sdweddingdirectory-todo-list' )
            );

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <label class="control-label" for="%1$s">%2$s</label>
                            <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%3$s" class="form-control form-light" required="">
                        </div>
                    </div>',

                    // 1
                    esc_html( 'edit_todo_overview' ),

                    // 2
                    esc_html__( 'Edit Task Overview', 'sdweddingdirectory-todo-list' ),

                    // 3
                    esc_attr__( 'Edit Task Overview', 'sdweddingdirectory-todo-list' )
            );

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <label class="control-label" for="%1$s">%2$s</label>
                            <input autocomplete="off" id="%1$s" type="date" name="%1$s" placeholder="%2$s" class="sdweddingdirectory_datepicker form-control form-light" />
                        </div>
                    </div>',

                    // 1
                    esc_html( 'edit_todo_date' ),

                    // 2
                    esc_html__( 'Edit Todo Date', 'sdweddingdirectory-todo-list' )
            );

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <button type="submit" id="edit_todo_list_btn" class="btn btn-default">%1$s</button>
                            <input autocomplete="off" type="hidden" id="edit_todo_unique_id" value="" />
                            %2$s
                        </div>
                    </div>',

                    // 1
                    esc_html__( 'Edit To Do', 'sdweddingdirectory-todo-list' ),

                    // 2
                    wp_nonce_field( 'edit_todo_list', 'edit_todo_list', true, false )
            );
        }

    } /* class end **/

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Edit_Todo_Form:: get_instance();
}