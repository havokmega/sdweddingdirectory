<?php
/**
 *  SDWeddingDirectory Couple Add New Group Form
 *  ------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_Edit_Event' ) && class_exists( 'SDWeddingDirectory_Guest_List_Form_Handler' ) ){

	/**
	 *  SDWeddingDirectory Couple Add New Group Form
	 *  ------------------------------------
	 */
    class SDWeddingDirectory_Guest_List_Edit_Event extends SDWeddingDirectory_Guest_List_Form_Handler{

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

            <div id="sdweddingdirectory_guest_edit_event_sidepanel" class="sliding-panel">

                <div class="card-shadow-header mb-0">
                    <div class="dashboard-head">

                        <h3><?php esc_attr_e( 'Edit Event', 'sdweddingdirectory-guest-list' ); ?></h3>

                    </div>
                </div>

                <div class="card-shadow-body">

                    <form id="sdweddingdirectory_edit_event_form" method="post" autocomplete="off" >

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

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <label class="control-label" for="%1$s">%2$s</label>
                            <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%3$s" class="form-control form-light" required="">
                        </div>
                    </div>',

                    // 1
                    esc_attr( 'edit_event_name' ),

                    // 2
                    esc_attr__( 'Event Name', 'sdweddingdirectory-guest-list' ),

                    // 3
                    esc_attr__( 'Event Name', 'sdweddingdirectory-guest-list' )
            );

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <label class="control-label" for="%1$s">%2$s</label>
                            <select id="%1$s" name="%1$s" class="sdweddingdirectory-light-select">%3$s</select>
                        </div>
                    </div>',

                    // 1
                    esc_attr( 'edit_event_icon' ),

                    // 2
                    esc_attr__( 'Edit Event Icon', 'sdweddingdirectory-guest-list' ),

                    // 3
                    self:: get_icon_options()
            );

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">                
                        <div class="mb-3">
                            <div class=" form-light">
                                <input autocomplete="off" type="checkbox" class="form-check-input" id="%1$s" data-bs-toggle="collapse" data-bs-target="#edit_event_meal_option" aria-expanded="false" aria-controls="edit_event_meal_option">
                                <label class="form-check-label" for="%1$s">%2$s</label>
                            </div>
                        </div>
                    </div>',

                    // 1
                    esc_attr( 'edit_have_meal' ),

                    // 2
                    esc_attr__( 'Event Have Meal ?', 'sdweddingdirectory-guest-list' ),

                    // 3
                    esc_attr__( 'Event Have Meal ?', 'sdweddingdirectory-guest-list' )
            );

            ?>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" id="edit_event_meal_option">
                <div class="mb-3 meal_option_list_box">
                  <div class="">
                    <label class="control-label"><?php esc_attr_e( 'Add New Meal Choice', 'sdweddingdirectory-guest-list' ); ?></label>
                    <div class="meal-list">
                      <ul class="list-unstyled" id="edit_event_meal"></ul>
                    </div>
                    <div class="text-muted">
                    <?php

                        printf('<div class="input-group mb-3">
                                  
                                    <input autocomplete="off" type="text" class="form-control form-light" id="%1$s" placeholder="%2$s" />

                                    <div class="input-group-append">
                                          <button id="%3$s" class="btn btn-secondary" type="button"><i class="fa fa-plus"></i></button>
                                    </div>

                                </div>',

                                // 1
                                esc_attr( 'edit_event_new_input_value' ),

                                // 2
                                esc_attr__( 'Edit Meal Choice', 'sdweddingdirectory-guest-list' ),

                                // 3
                                esc_attr( 'edit_event_list_update_button' )
                        );
                    ?>
                    </div>
                  </div>
                </div>
            </div>
            <?php

            /**
             *  Add New Event Button
             *  --------------------
             */
            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">

                            <button type="submit" id="edit_event_btn" class="btn btn-default">%1$s</button> 

                            <button type="button" id="edit_event_remove" class="btn btn-primary" data-remove-event-alert="%4$s">%3$s</button>

                            <input autocomplete="off" type="hidden" id="edit_event_unique_id" value="" />
                            %2$s
                        </div>
                    </div>',

                    // 1
                    esc_attr__( 'Update Event', 'sdweddingdirectory-guest-list' ),

                    // 2
                    wp_nonce_field( 'edit_guest_event_security', 'edit_guest_event_security', true, false ),

                    // 3
                    esc_attr__( 'Remove Event', 'sdweddingdirectory-guest-list' ),

                    // 4
                    esc_attr__( 'Are you sure to removed event ?', 'sdweddingdirectory-guest-list' )
                    
            );
        }

        public static function get_icon_options(){

            $_icons = apply_filters( 'sdweddingdirectory_icons_set_for_ot', [] );

            $_option = '';

            if( SDWeddingDirectory_Loader:: _is_array( $_icons ) ){

                foreach ( $_icons as $key => $value) {
                    
                    $_option .= 

                    sprintf( '<option value="%1$s">%1$s</option>', 

                        // 1
                        esc_attr( $value[ 'value' ] )
                    );
                }
            }

            return $_option;
        }

    } /* class end **/

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Guest_List_Edit_Event:: get_instance();
}