<?php
/**
 *  SDWeddingDirectory Couple Todo List
 *  ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Create_Todo_Form' ) && class_exists( 'SDWeddingDirectory_Todo' ) ){

    /**
     *  SDWeddingDirectory Requests
     *  -------------------
     */
    class SDWeddingDirectory_Create_Todo_Form extends SDWeddingDirectory_Todo{

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
            add_action( 'wp_footer', [ $this, 'sdweddingdirectory_add_new_todo_form_markup' ] );
        }

        /**
         *  1. Load Create Todo Form
         *  ------------------------
         */
        public static function sdweddingdirectory_add_new_todo_form_markup(){

            ?>

            <div id="sdweddingdirectory_add_todo_form" class="sliding-panel">

                <div class="card-shadow-header mb-0">

                    <div class="dashboard-head">

                        <h3><?php esc_attr_e( 'Create Task', 'sdweddingdirectory-todo-list' ); ?></h3>

                    </div>

                </div>

                <div class="card-shadow-body">

                    <form id="couple_add_todo_list" method="post" autocomplete="off" >

                        <div class="row"><?php self:: sdweddingdirectory_create_todo_task_content(); ?></div>

                    </form>

                </div>

            </div>

            <?php
        }

        /**
         *  1. Load Create Todo Form
         *  ------------------------
         */
        public static function sdweddingdirectory_create_todo_task_content(){

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <label class="control-label" for="%1$s">%2$s</label>
                            <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%3$s" class="form-control form-light" required="">
                        </div>
                    </div>',

                    /**
                     *  1. Todo 
                     *  ------------
                     */
                    esc_html( 'todo_title' ),

                    /**
                     *  2. Translation Ready String
                     *  ---------------------------
                     */
                    esc_attr__( 'Task Title', 'sdweddingdirectory-todo-list' ),

                    /**
                     *  3. Translation Ready String
                     *  ---------------------------
                     */
                    esc_attr__( 'Write Task Title', 'sdweddingdirectory-todo-list' )
            );

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <label class="control-label" for="%1$s">%2$s</label>
                            <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%3$s" class="form-control form-light" required="">
                        </div>
                    </div>',

                    /**
                     *  1. Todo 
                     *  ------------
                     */
                    esc_html( 'todo_overview' ),

                    /**
                     *  2. Translation Ready String
                     *  ---------------------------
                     */
                    esc_attr__( 'Task Overview', 'sdweddingdirectory-todo-list' ),

                    /**
                     *  3. Translation Ready String
                     *  ---------------------------=
                     */
                    esc_attr__( 'Write Task Overview', 'sdweddingdirectory-todo-list' )
            );

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <label class="control-label" for="%1$s">%2$s</label>
                            <input autocomplete="off" id="%1$s" type="date" name="%1$s" placeholder="%2$s" class="sdweddingdirectory_datepicker form-control form-light" />
                        </div>
                    </div>',

                    /**
                     *  1. Todo 
                     *  ------------
                     */
                    esc_html( 'todo_date' ),

                    /**
                     *  2. Translation Ready String
                     *  ---------------------------
                     */
                    esc_attr__( 'Todo Date', 'sdweddingdirectory-todo-list' )
            );

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <button type="submit" id="add_todo_list_btn" class="btn btn-default">%1$s</button>
                            %2$s
                        </div>
                    </div>',

                    /**
                     *  1. Todo 
                     *  ------------
                     */
                    esc_attr__( 'Add To Do', 'sdweddingdirectory-todo-list' ),

                    /**
                     *  2. Nonce Field
                     *  --------------
                     */
                    wp_nonce_field( 'add_todo_list', 'add_todo_list', true, false )
            );
        }

    } /* class end **/

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Create_Todo_Form:: get_instance();
}