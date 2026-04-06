<?php
/**
 *  SDWeddingDirectory Couple Add New Guest Form
 *  ------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_Form' ) && class_exists( 'SDWeddingDirectory_Guest_List_Form_Handler' ) ){

	/**
	 *  SDWeddingDirectory Couple Add New Guest Form
	 *  ------------------------------------
	 */
    class SDWeddingDirectory_Guest_List_Form extends SDWeddingDirectory_Guest_List_Form_Handler{

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

            <div id="sdweddingdirectory_guest_add_sidepanel" class="sliding-panel bg-white">

                <div class="card-shadow-header mb-0">
                    <div class="dashboard-head">

                        <h3><?php esc_attr_e( 'Add Guest', 'sdweddingdirectory-guest-list' ); ?></h3>

                    </div>
                </div>

                <div class="card-shadow-body">

                    <form id="sdweddingdirectory_add_new_guest_form" method="post" autocomplete="off" >

                        <div class="row"><?php self:: load_form(); ?></div>

                    </form>

                </div>

            </div>

            <?php
        }

        public static function get_options( $_data ){

            $_option = '';

            if( parent:: _is_array( $_data ) ){

                foreach ( $_data as $key => $value) {
                    
                    $_option .= 

                    sprintf( '<option value="%1$s">%2$s</option>', 

                        // 1
                        sanitize_title( $key ),

                        // 2
                        esc_attr( $value )
                    );
                }
            }

            return $_option;
        }

        /**
         *  1. Load Create Todo Form
         *  ------------------------
         */
        public static function load_form(){

            ?>
            <div class="col-12">

                <div class="accordion theme-accordian" id="accordionExample">

                    <!-- First -->
                    <div class="card-header" id="guest_fields_information">
                        <a href="javascript:" class="" data-bs-toggle="collapse"
                                data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                            <?php esc_attr_e( 'Add Guest', 'sdweddingdirectory-guest-list' ); ?>
                        </a>
                    </div>

                    <div id="collapseFour" class="collapse show" aria-labelledby="guest_fields_information" data-bs-parent="#accordionExample">
                    <?php

                        /**
                         *  Collpase
                         *  --------
                         */
                        printf( '<div class="pb-3 %1$s">

                                    <div class="row row-cols-1 %1$s" id="%1$s">%4$s</div>

                                    <div class="text-center">

                                        <a href="javascript:void(0)" 

                                        class="btn btn-primary btn-rounded sdweddingdirectory_core_group_accordion btn-sm %1$s" 

                                        data-class="SDWeddingDirectory_Guest_List_Database"

                                        data-member="add_guest_fields"

                                        data-parent="%1$s"

                                        id="%2$s">%3$s</a>

                                    </div>

                                </div>',

                                /**
                                 *  1. Parent Collapse ID
                                 *  ---------------------
                                 */
                                esc_attr( 'add-new-guest' ),

                                /**
                                 *  2. Section Button ID
                                 *  --------------------
                                 */
                                esc_attr( parent:: _rand() ),

                                /**
                                 *  3. Button Text : Translation Ready
                                 *  ----------------------------------
                                 */
                                esc_attr__( 'Add New Member', 'sdweddingdirectory-guest-list' ),

                                /**
                                 *  4. Data
                                 *  -------
                                 */
                                parent:: add_guest_fields( [ 'post_id' => parent:: post_id() ] )
                        );

                    ?>
                    </div>
                    <!-- / First -->

                    <!-- Sec -->
                    <div class="card-header" id="guest_information_section">
                        <a href="javascript:" data-bs-toggle="collapse" class="collapsed" 
                            data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            <?php esc_attr_e( 'Guest Information', 'sdweddingdirectory-guest-list' ); ?>
                        </a>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="guest_information_section" data-bs-parent="#accordionExample">
                        

                        <div class="card-body px-0">
                            <div class="row">

                                <?php

                                    printf('<div class="col-md">
                                                <div class="mb-3">
                                                    <label class="control-label mb-2" for="%1$s">%2$s</label>
                                                    <select id="%1$s" name="%1$s" class="form-control">%3$s</select>
                                                </div>
                                            </div>',

                                            // 1
                                            esc_attr( 'guest_age' ),

                                            // 2
                                            esc_attr__( 'Guest Age', 'sdweddingdirectory-guest-list' ),

                                            // 3
                                            self:: get_options( parent:: get_age_data() )
                                    );

                                    printf('<div class="col-md">
                                                <div class="mb-3">
                                                    <label class="control-label mb-2" for="%1$s">%2$s</label>
                                                    <select id="%1$s" name="%1$s" class="form-control">%3$s</select>
                                                </div>
                                            </div>',

                                            // 1
                                            esc_attr( 'guest_group' ),

                                            // 2
                                            esc_attr__( 'Group', 'sdweddingdirectory-guest-list' ),

                                            // 3
                                            self:: get_options( parent:: get_group_data() )
                                    );

                                    printf('<div class="col-12">
                                                <div class="mb-3">
                                                    <div class=" form-light">
                                                        <p>%2$s</p>
                                                        <input autocomplete="off" type="checkbox" class="form-check-input" id="%1$s" />
                                                        <label class="form-check-label" for="%1$s">%3$s</label>
                                                    </div>
                                                </div>
                                            </div>',

                                            // 1
                                            esc_attr( 'need_hotel' ),

                                            // 2
                                            esc_attr__( 'Need Hotel ?', 'sdweddingdirectory-guest-list' ),

                                            // 3
                                            esc_attr__( 'Yes', 'sdweddingdirectory-guest-list' )
                                    );

                                    /**
                                     *  Have Event
                                     *  ----------
                                     */
                                    $events         =     parent:: event_list();

                                    /**
                                     *  Have Event ?
                                     *  ------------
                                     */
                                    if( parent:: _is_array( $events ) ){

                                        /**
                                         *  Label
                                         *  -----
                                         */
                                        printf( '<div class="row">

                                                    <div class="col-md-12">

                                                        <p>%1$s</p>

                                                    </div>

                                                </div>',

                                                /**
                                                 *  1. Translation String
                                                 *  ---------------------
                                                 */
                                                esc_attr__( 'Invited To', 'sdweddingdirectory-guest-list' ) 
                                        );

                                        ?><div class="row row-cols-xxl-3 row-cols-xl-3 row-cols-lg-3 row-cols-md-3 row-cols-sm-2 row-cols-1"><?php

                                        $_counter   =   absint( '1' );

                                        foreach ( $events as $key => $value) {

                                            printf('<div class="col">
                                                        <div class="mb-3">
                                                            <div class="form-light">
                                                                <input autocomplete="off" type="checkbox" class="form-check-input sdweddingdirectory_event" data-event-unique-id="%3$s" id="%1$s" %4$s />
                                                                <label class="form-check-label" for="%1$s">%2$s</label>
                                                            </div>
                                                        </div>
                                                    </div>',

                                                    /**
                                                     *  1. Menu Name
                                                     *  ------------
                                                     */
                                                    sanitize_title( $value[ 'event_list' ] ),

                                                    // 2
                                                    esc_attr( $value[ 'event_list' ] ),

                                                    // 3
                                                    absint( $value[ 'event_unique_id' ] ),

                                                    /**
                                                     *  4. Is First Event ?
                                                     *  -------------------
                                                     */
                                                    $_counter == absint( '1' )

                                                    ?   esc_attr( 'checked' )

                                                    :   ''
                                            );

                                            /**
                                             *  Counter
                                             *  -------
                                             */
                                            $_counter++;
                                        }

                                        ?></div><?php
                                    }

                            ?>
                            </div>
                        </div>

                    </div>
                    <!-- / Sec -->
                
                    
                    <!-- Four -->
                    <div class="card-header" id="contact_information_section">
                        <a href="javascript:" class="collapsed" data-bs-toggle="collapse"
                                data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <?php esc_attr_e( 'Contact Information', 'sdweddingdirectory-guest-list' ); ?>
                            </a>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="contact_information_section" data-bs-parent="#accordionExample">
                        <div class="card-body px-0">
                            <div class="row">
                                <?php

                                    printf('<div class="col-md-6 col-12">
                                                <div class="mb-3">
                                                    <label class="control-label mb-2" for="%1$s">%2$s</label>
                                                    <input autocomplete="off" id="%1$s" name="%1$s" type="email" placeholder="%3$s" class="form-control form-dark">
                                                </div>
                                            </div>',

                                            // 1
                                            esc_attr( 'guest_email' ),

                                            // 2
                                            esc_attr__( 'Email', 'sdweddingdirectory-guest-list' ),

                                            // 3
                                            esc_attr__( 'Email', 'sdweddingdirectory-guest-list' )
                                    );

                                    printf('<div class="col-md-6 col-12">
                                                <div class="mb-3">
                                                    <label class="control-label mb-2" for="%1$s">%2$s</label>
                                                    <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%3$s" class="form-control form-dark">
                                                </div>
                                            </div>',

                                            // 1
                                            esc_attr( 'guest_contact' ),

                                            // 2
                                            esc_attr__( 'Phone Number', 'sdweddingdirectory-guest-list' ),

                                            // 3
                                            esc_attr__( 'Phone Number', 'sdweddingdirectory-guest-list' )
                                    );

                                    printf('<div class="col-md-6 col-12">
                                                <div class="mb-3">
                                                    <label class="control-label mb-2" for="%1$s">%2$s</label>
                                                    <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%3$s" class="form-control form-dark">
                                                </div>
                                            </div>',

                                            // 1
                                            esc_attr( 'guest_address' ),

                                            // 2
                                            esc_attr__( 'Address', 'sdweddingdirectory-guest-list' ),

                                            // 3
                                            esc_attr__( 'Address', 'sdweddingdirectory-guest-list' )
                                    );

                                    printf('<div class="col-md-6 col-12">
                                                <div class="mb-3">
                                                    <label class="control-label mb-2" for="%1$s">%2$s</label>
                                                    <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%2$s" class="form-control form-dark">
                                                </div>
                                            </div>',

                                            // 1
                                            esc_attr( 'guest_city' ),

                                            // 2
                                            esc_attr__( 'City / Town', 'sdweddingdirectory-guest-list' )
                                    );

                                    printf('<div class="col-md-6 col-12">
                                                <div class="mb-3">
                                                    <label class="control-label mb-2" for="%1$s">%2$s</label>
                                                    <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%2$s" class="form-control form-dark">
                                                </div>
                                            </div>',

                                            // 1
                                            esc_attr( 'guest_state' ),

                                            // 2
                                            esc_attr__( 'State', 'sdweddingdirectory-guest-list' )
                                    );

                                    printf('<div class="col-md-6 col-12">
                                                <div class="mb-3">
                                                    <label class="control-label mb-2" for="%1$s">%2$s</label>
                                                    <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%2$s" class="form-control form-dark">
                                                </div>
                                            </div>',

                                            /**
                                             *  1. Zip Code
                                             *  -----------
                                             */
                                            esc_attr( 'guest_zip_code' ),

                                            /**
                                             *  2. Translation Ready String
                                             *  ---------------------------
                                             */
                                            esc_attr__( 'Zip Code', 'sdweddingdirectory-guest-list' )
                                    );

                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- / Four -->

                </div>
            </div>
            
            <?php

            printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                        <div class="mb-3">

                            <button type="submit" id="add_new_guest_btn" class="btn btn-default">%1$s</button>

                            %2$s

                        </div>

                    </div>',

                    // 1
                    esc_attr__( 'Save', 'sdweddingdirectory-guest-list' ),

                    // 2
                    wp_nonce_field( 'add_new_guest_security', 'add_new_guest_security', true, false )
            );
        }

    } /* class end **/

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Guest_List_Form:: get_instance();
}