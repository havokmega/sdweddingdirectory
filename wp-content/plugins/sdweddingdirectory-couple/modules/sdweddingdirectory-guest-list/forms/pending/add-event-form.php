<?php
/**
 *  SDWeddingDirectory Couple Add New Group Form
 *  ------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_Add_Event' ) && class_exists( 'SDWeddingDirectory_Guest_List_Form_Handler' ) ){

	/**
	 *  SDWeddingDirectory Couple Add New Group Form
	 *  ------------------------------------
	 */
    class SDWeddingDirectory_Guest_List_Add_Event extends SDWeddingDirectory_Guest_List_Form_Handler{

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
             *  1. Add New Budget Category Task Form
             *  ------------------------------------
             */            
            add_action( 'wp_footer', [$this, 'sidebar_panel'] );
        }

        /**
         *  1. Load Create Todo Form
         *  ------------------------
         */
        public static function sidebar_panel(){

            ?>

            <div id="sdweddingdirectory_guest_event_sidepanel" class="sliding-panel bg-white">

                <div class="card-shadow-header mb-0">
                    <div class="dashboard-head">

                        <h3><?php esc_attr_e( 'Add Event', 'sdweddingdirectory-guest-list' ); ?></h3>

                    </div>
                </div>

                <div class="card-shadow-body">

                    <form id="sdweddingdirectory_guest_add_new_event_form" method="post" autocomplete="off" >

                        <div class="row"><?php self:: load_form(); ?></div>

                    </form>

                </div>

            </div>

            <?php
        }

        /**
         *  1. Load Create Todo Form
         *  ------------------------
         */
        public static function load_form(){

            print

            parent:: call_field_type( [

                'column'        =>      [ 'start'   =>  true,   'end'   =>  true ],

                'field_type'    =>      esc_attr( 'input' ),
                        
                'id'            =>      esc_attr( 'add_new_guest_event' ),

                'placeholder'   =>      esc_attr__( 'Event Name', 'sdweddingdirectory-guest-list' ),

            ] );

            print

            parent:: call_field_type( [

                'column'        =>      [ 'start'   =>  true,   'end'   =>  true ],

                'field_type'    =>      esc_attr( 'select' ),
                        
                'id'            =>      esc_attr( 'event_icon' ),

                'options'       =>      apply_filters(  'sdweddingdirectory/icons',  []  ),

            ] );

            print

            parent:: call_field_type( [

                'column'        =>      [ 'start'   =>  true,   'end'   =>  true ],

                'field_type'    =>      esc_attr( 'checkbox' ),
                        
                'id'            =>      esc_attr( 'event_have_meal_option' ),

                'options'       =>      [  'event_have_meal_option'     =>      esc_attr__( 'Event Have Meal ?', 'sdweddingdirectory-guest-list' ) ],

            ] );

            ?>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 collapse show meal_option_list_box" id="event_have_mean_option">

                <div class="card">

                    <div class="card-header py-3">

                        <h4 class="mb-0"><?php esc_attr_e( 'Add New Meal Choice', 'sdweddingdirectory-guest-list' ); ?></h4>

                    </div>

                    <div class="card-body">

                        <div class="meal-list">

                            <ul class="list-unstyled" id="add_new_event_meals_options">
                            <?php

                                $data       =       parent:: menu_list();

                                /**
                                 *  Have Event ?
                                 *  ------------
                                 */
                                if( parent:: _is_array( $data ) ){

                                    foreach( $data as $key => $value ){

                                        printf(     '<li class="d-flex justify-content-between align-items-center">

                                                        <span>%1$s</span>

                                                        <a href="javascript:" class="action-links"><i class="fa fa-trash"></i></a>

                                                    </li>',

                                                    /**
                                                     *  1. Menu Name
                                                     *  ------------
                                                     */
                                                    esc_attr( $value[ 'menu_list' ] )
                                        );
                                    }
                                }
                            ?>
                            </ul>

                        </div>

                    </div>
        
                    <div class="card-footer">
                    <?php

                        printf( '<div class="input-group">
                                  
                                    <input autocomplete="off" type="text" class="form-control form-light" id="%1$s" placeholder="%2$s" />

                                    <div class="input-group-append">

                                        <button id="%3$s" class="btn btn-secondary btn-lg" type="button">

                                            <i class="fa fa-plus"></i>

                                        </button>

                                    </div>

                                </div>',

                                /**
                                 *  1. ID
                                 *  -----
                                 */
                                esc_attr( 'new_event_new_input_value' ),

                                /**
                                 *  2. Placeholder
                                 *  --------------
                                 */
                                esc_attr__( 'Add New Meal Choice', 'sdweddingdirectory-guest-list' ),

                                /**
                                 *  3. Button ID
                                 *  ------------
                                 */
                                esc_attr( 'new_event_list_update_button' )
                        );
                    ?>
                    </div>

                </div>

            </div>
            <?php

            /**
             *  Add New Event Button
             *  --------------------
             */
            printf( '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                        <div class="mb-3">

                            <button type="submit" id="add_new_guest_event_btn" class="btn btn-default">%1$s</button>

                            %2$s

                        </div>
                        
                    </div>',

                    // 1
                    esc_attr__( 'Add Event', 'sdweddingdirectory-guest-list' ),

                    // 2
                    wp_nonce_field( 'add_new_guest_event_security', 'add_new_guest_event_security', true, false )
            );
        }

    } /* class end **/

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Guest_List_Add_Event:: get_instance();
}