<?php
/**
 *  SDWeddingDirectory Couple Add New Group Form
 *  ------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_Group_Managment' ) && class_exists( 'SDWeddingDirectory_Guest_List_Form_Handler' ) ){

	/**
	 *  SDWeddingDirectory Couple Add New Group Form
	 *  ------------------------------------
	 */
    class SDWeddingDirectory_Guest_Group_Managment extends SDWeddingDirectory_Guest_List_Form_Handler{

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

            <div id="sdweddingdirectory_guest_group_managment_sidepanel" class="sliding-panel">

                <div class="card-shadow-header mb-0">
                    <div class="dashboard-head">

                        <h3><?php esc_attr_e( 'Guest Group Managment', 'sdweddingdirectory-guest-list' ); ?></h3>

                    </div>
                </div>

                <div class="card-shadow-body">

                    <form id="sdweddingdirectory_guest_group_managment_form" method="post" autocomplete="off" >

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

            ?>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 collapse show meal_option_list_box" id="guest_group_managment_option">
                <div class="mb-3">
                  <div class="">
                    <label class="control-label"><?php esc_attr_e( 'Add New Group', 'sdweddingdirectory-guest-list' ); ?></label>
                    <div class="meal-list">
                      <ul class="list-unstyled" id="guest_group_list_section">
                        <?php

                            $data = parent:: group_list();

                            if( SDWeddingDirectory_Loader:: _is_array( $data ) ){

                                foreach ( $data as $key => $value) {

                                    printf( '<li class="d-flex justify-content-between align-items-center">
                                                  <span>%1$s</span>
                                                  <a href="javascript:" data-group-id="%2$s" class="action-links"><i class="fa fa-trash"></i></a>
                                            </li>',

                                              /**
                                               *  1. Guest Name
                                               *  -------------
                                               */
                                              esc_attr( $value[ 'group_name' ] ),

                                              /**
                                               *  2. Guest ID
                                               *  -----------
                                               */
                                              absint( $value[ 'group_unique_id' ] )
                                    );
                                }
                            }
                        ?>
                        </ul>
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
                                esc_attr( 'add_new_group_input' ),

                                // 2
                                esc_attr__( 'Add In Group', 'sdweddingdirectory-guest-list' ),

                                // 3
                                esc_attr( 'add_new_group_button' )
                        );
                    ?>
                    </div>
                  </div>
                </div>
            </div>
            <?php

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="mb-3">
                            <button type="submit" id="%1$s" class="btn btn-default">%2$s</button>
                            %3$s
                        </div>
                    </div>',

                    /**
                     *  1. Update Guest Group Button ID
                     *  -------------------------------
                     */
                    esc_attr( 'update_guest_group_button' ),

                    // 1
                    esc_attr__( 'Update Group', 'sdweddingdirectory-guest-list' ),

                    // 2
                    wp_nonce_field( 'sdweddingdirectory_guest_group_security', 'sdweddingdirectory_guest_group_security', true, false )
            );

        }

    } /* class end **/

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Guest_Group_Managment:: get_instance();
}