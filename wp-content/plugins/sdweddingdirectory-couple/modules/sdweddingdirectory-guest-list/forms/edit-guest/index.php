<?php
/**
 *  SDWeddingDirectory Couple Add New Guest Form
 *  ------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_Edit_Form' ) && class_exists( 'SDWeddingDirectory_Guest_List_Form_Handler' ) ){

	/**
	 *  SDWeddingDirectory Couple Add New Guest Form
	 *  ------------------------------------
	 */
    class SDWeddingDirectory_Guest_Edit_Form extends SDWeddingDirectory_Guest_List_Form_Handler{

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
            
            if( parent:: guest_list() ){

                add_action( 'wp_footer', [$this, 'sidebar_panel'] );
            }
        }

        /**
         *  1. Load Create Todo Form
         *  ------------------------
         */
        public static function sidebar_panel(){

            ?>

            <div id="sdweddingdirectory_guest_edit_sidepanel" class="sliding-panel bg-white">

                <div class="card-shadow-header mb-0">
                    <div class="dashboard-head">

                        <h3><?php esc_attr_e( 'Edit Guest', 'sdweddingdirectory-guest-list' ); ?></h3>

                    </div>
                </div>

                <div class="card-shadow-body">

                    <form id="sdweddingdirectory_edit_guest_member_form" method="post" autocomplete="off" >

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
                    <div class="row row-cols-md-2 row-cols-1">

                        <?php

                            printf('<div class="col">
                                        <div class="mb-3">
                                            <label class="control-label" for="%1$s">%2$s</label>
                                            <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%3$s" class="form-control">
                                        </div>
                                    </div>',

                                    // 1
                                    esc_attr( 'edit_first_name' ),

                                    // 2
                                    esc_attr__( 'First Name', 'sdweddingdirectory-guest-list' ),

                                    // 3
                                    esc_attr__( 'First Name', 'sdweddingdirectory-guest-list' )
                            );


                            printf('<div class="col">
                                        <div class="mb-3">
                                            <label class="control-label" for="%1$s">%2$s</label>
                                            <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%3$s" class="form-control">
                                        </div>
                                    </div>',

                                    // 1
                                    esc_attr( 'edit_last_name' ),

                                    // 2
                                    esc_attr__( 'Last Name', 'sdweddingdirectory-guest-list' ),

                                    // 3
                                    esc_attr__( 'Last Name', 'sdweddingdirectory-guest-list' )
                            );

                        ?>

                    </div>
                    <!-- / First -->

                    <!-- Sec -->
                    <div class="row row-cols-lg-3 row-cols-md-2 row-cols-1">

                        <?php

                            printf('<div class="col">

                                        <div class="mb-3">

                                            <label class="control-label" for="%1$s">%2$s</label>

                                            <select id="%1$s" name="%1$s" class="form-control">%3$s</select>

                                        </div>

                                    </div>',

                                    // 1
                                    esc_attr( 'edit_guest_age' ),

                                    // 2
                                    esc_attr__( 'Guest Age', 'sdweddingdirectory-guest-list' ),

                                    // 3
                                    self:: get_options( parent:: get_age_data() )
                            );

                            printf('<div class="col">

                                        <div class="mb-3">

                                            <label class="control-label" for="%1$s">%2$s</label>

                                            <select id="%1$s" name="%1$s" class="form-control %1$s">%3$s</select>

                                        </div>

                                    </div>',

                                    // 1
                                    esc_attr( 'edit_guest_group' ),

                                    // 2
                                    esc_attr__( 'Group', 'sdweddingdirectory-guest-list' ),

                                    // 3
                                    self:: get_options( parent:: get_group_data() )
                            );

                            printf('<div class="col">
                                        <label>&nbsp;</label>
                                        <div class="mb-3 mt-2">
                                            <div class=" form-light">
                                                <input autocomplete="off" type="checkbox" class="form-check-input" id="%1$s" checked="" />
                                                <label class="form-check-label" for="%1$s">%2$s</label>
                                            </div>
                                        </div>
                                    </div>',

                                    // 1
                                    esc_attr( 'edit_guest_need_hotel' ),

                                    // 2
                                    esc_attr__( 'Need Hotel ?', 'sdweddingdirectory-guest-list' ),

                                    // 3
                                    esc_attr__( 'Need Hotel ?', 'sdweddingdirectory-guest-list' )
                            );

                        ?>

                    </div>
                    <!-- / Sec -->
                    
                    <!-- Third -->
                    <div class="row mb-3"><div class="col-12">
                        <?php

                            $_events = parent:: event_list();

                            if( parent:: _is_array( $_events ) ){

                                $_tab_counter = absint( '1' );

                                ?><ul class="nav nav-pills mb-3 horizontal-tab-second tabbing-scroll justify-content-center nav-fill" id="edit-guest-form" role="tablist"><?php

                                foreach ( $_events as $key => $value) {
                                  
                                    printf('<li class="nav-item" role="presentation">
                                                <a class="nav-link %1$s" id="edit-guest-form-%2$s-tab" data-bs-toggle="pill" href="#edit-guest-form-%2$s" role="tab" aria-controls="edit-guest-form-%2$s" aria-selected="true">%3$s</a>
                                            </li>',

                                        /**
                                         *  1. Tab is Active ?
                                         *  ------------------
                                         */
                                         ( $_tab_counter == absint( '1' ) ) 

                                         ?  sanitize_html_class( 'active' )

                                         :  '',

                                         /**
                                          *  2. Tab id
                                          *  ---------
                                          */
                                         sanitize_title( $value[ 'event_unique_id' ] ),

                                         /**
                                          *  3. Tab Name
                                          *  -----------
                                          */
                                         esc_attr( $value[ 'event_list' ] )
                                    );

                                    $_tab_counter++;
                                }

                                ?></ul><?php

                                print '<div class="tab-content" id="edit-guest-form-container">';

                                $_tabcontent_counter = absint( '1' );

                                foreach ( $_events as $key => $value) {
                                  
                                    /**
                                     *  Tab Content
                                     *  -----------
                                     */
                                    printf('<div class="tab-pane fade %1$s" id="edit-guest-form-%2$s" role="tabpanel" aria-labelledby="edit-guest-form-%2$s-tab">',

                                        /**
                                         *  1. Tab is Active ?
                                         *  ------------------
                                         */
                                        ( $_tabcontent_counter == absint( '1' ) )

                                        ?   sprintf( '%1$s %2$s', sanitize_html_class( 'active' ), sanitize_html_class( 'show' ) )

                                        :   '',

                                        /**
                                         *  2. Tab id
                                         *  ---------
                                         */
                                        absint( $value[ 'event_unique_id' ] )
                                    );

                                    /**
                                     *  Tab Content Start
                                     *  -----------------
                                     */
                                    ?>
                                    <div class="edit_guest_events_list" 

                                        data-event-name="<?php echo esc_attr( $value[ 'event_list' ] ); ?>" 

                                        data-event-unique-id="<?php echo esc_attr( $value[ 'event_unique_id' ] ); ?>">

                                            <div class="card card-body">

                                                <?php

                                                    printf( '<div class="mb-3">

                                                                <h3 class="%1$s_edit_guest_event_name">%2$s</h3>

                                                            </div>',

                                                            /**
                                                             *  1. Event Unique ID
                                                             *  ------------------
                                                             */
                                                            absint( $value[ 'event_unique_id' ] ),

                                                            /**
                                                             *  2. Event Name
                                                             *  -------------
                                                             */
                                                            esc_attr( $value[ 'event_list' ] )
                                                    );
                                                ?>

                                                <div class="row row-cols-md-2 row-cols-1">

                                                    <?php

                                                        printf('<div class="col">

                                                                    <div class="mb-3">

                                                                        <label class="control-label" for="%1$s">%2$s</label>

                                                                        <select id="guest_invited-%1$s" name="guest_invited" class="form-control">%3$s</select>

                                                                    </div>

                                                                </div>',

                                                                // 1
                                                                absint( $value[ 'event_unique_id' ] ),

                                                                // 2
                                                                esc_attr__( 'Attended', 'sdweddingdirectory-guest-list' ),

                                                                // 3
                                                                parent:: get_attended_option()
                                                        );


                                                        if( $value[ 'have_meal' ] == esc_attr( 'on' ) ){

                                                            printf('<div class="col">

                                                                        <div class="mb-3">

                                                                            <label class="control-label" for="%1$s">%2$s</label>

                                                                            <select id="meal-%1$s" name="meal" class="form-control">%3$s</select>

                                                                        </div>

                                                                    </div>',

                                                                    // 1
                                                                    absint( $value[ 'event_unique_id' ] ),

                                                                    // 2
                                                                    esc_attr__( 'Menu', 'sdweddingdirectory-guest-list' ),

                                                                    // 3
                                                                    parent:: get_event_meal_option(

                                                                        // 1
                                                                        json_decode( $value[ 'event_meal' ], true )
                                                                    )
                                                            );
                                                        }

                                                    ?>

                                                </div>


                                            </div>

                                        </div>
                                        <?php


                                    /**
                                     *  Tab Content END
                                     *  ---------------
                                     */

                                    ?></div><?php

                                    $_tabcontent_counter++;
                                }

                                print '</div>';
                            }

                        ?>
                    </div></div>
                    <!-- / Third -->
                    
                    <!-- Four -->
                    <div class="row">

                        <div class="d-flex justify-content-between card-body">

                            <h3><?php esc_attr_e( 'Contact Information', 'sdweddingdirectory-guest-list' ); ?></h3>

                            <?php

                            printf( '<a  class="btn-link-default edit_form_request_missing_info_link request_missing_info_link fw-bolder" href="javascript:" role="button" 

                                        data-href="%3$s"

                                        data-bs-toggle="modal" data-bs-target="#%1$s" data-bs-dismiss="modal">%2$s</a>',

                                    /**
                                     *  1. Form ID
                                     *  ----------
                                     */
                                    esc_attr( parent:: popup_id( 'request_missing_info' ) ),

                                    /**
                                     *  2. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( 'Request missing info', 'sdweddingdirectory-guest-list' ),

                                    /**
                                     *  3. Data Href
                                     *  ------------
                                     */
                                    ''
                            );

                        ?>

                        </div>

                        <?php

                            printf('<div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            <label class="control-label" for="%1$s">%2$s</label>
                                            <input autocomplete="off" id="%1$s" name="%1$s" type="email" placeholder="%3$s" class="form-control">
                                        </div>
                                    </div>',

                                    // 1
                                    esc_attr( 'edit_guest_email' ),

                                    // 2
                                    esc_attr__( 'Email', 'sdweddingdirectory-guest-list' ),

                                    // 3
                                    esc_attr__( 'Email', 'sdweddingdirectory-guest-list' )
                            );

                            printf('<div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            <label class="control-label" for="%1$s">%2$s</label>
                                            <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%3$s" class="form-control">
                                        </div>
                                    </div>',

                                    // 1
                                    esc_attr( 'edit_guest_contact' ),

                                    // 2
                                    esc_attr__( 'Phone Number', 'sdweddingdirectory-guest-list' ),

                                    // 3
                                    esc_attr__( 'Phone Number', 'sdweddingdirectory-guest-list' )
                            );

                            printf('<div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            <label class="control-label" for="%1$s">%2$s</label>
                                            <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%3$s" class="form-control">
                                        </div>
                                    </div>',

                                    // 1
                                    esc_attr( 'edit_guest_address' ),

                                    // 2
                                    esc_attr__( 'Address', 'sdweddingdirectory-guest-list' ),

                                    // 3
                                    esc_attr__( 'Address', 'sdweddingdirectory-guest-list' )
                            );

                            printf('<div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            <label class="control-label" for="%1$s">%2$s</label>
                                            <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%2$s" class="form-control">
                                        </div>
                                    </div>',

                                    // 1
                                    esc_attr( 'edit_guest_city' ),

                                    // 2
                                    esc_attr__( 'City / Town', 'sdweddingdirectory-guest-list' )
                            );

                            printf('<div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            <label class="control-label" for="%1$s">%2$s</label>
                                            <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%2$s" class="form-control">
                                        </div>
                                    </div>',

                                    // 1
                                    esc_attr( 'edit_guest_state' ),

                                    // 2
                                    esc_attr__( 'State', 'sdweddingdirectory-guest-list' )
                            );

                            printf('<div class="col-md-6 col-12">
                                        <div class="mb-3">
                                            <label class="control-label" for="%1$s">%2$s</label>
                                            <input autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%2$s" class="form-control">
                                        </div>
                                    </div>',

                                    // 1
                                    esc_attr( 'edit_guest_zip_code' ),

                                    // 2
                                    esc_attr__( 'Zip Code', 'sdweddingdirectory-guest-list' )
                            );

                        ?>
                    </div>
                    <!-- / Four -->

                    <!-- Four -->
                    <div class="row">

                        <h3><?php esc_attr_e( 'Notes', 'sdweddingdirectory-guest-list' ); ?></h3>
                        <?php

                        printf('<div class="col-12">
                                    <div class="mb-3">
                                        <textarea autocomplete="off" id="%1$s" name="%1$s" type="text" placeholder="%2$s" class="form-control"></textarea>
                                    </div>
                                </div>',

                                // 1
                                esc_attr( 'edit_guest_comment' ),

                                // 2
                                esc_attr__( 'Notes', 'sdweddingdirectory-guest-list' )
                        );

                    ?>
                    </div>
                    <!-- / Four -->

                    <!-- Five -->
                    <div class="row">                        
                        <?php

                                printf('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                                            <div class="mb-3">

                                                <button type="submit" id="edit_guest_member_button" class="btn btn-sm btn-default">%1$s</button> 

                                                <button type="button" data-guest-removed-alert="%4$s" id="removed_guest_member_button" class="btn btn-sm btn-primary">%2$s</button>

                                                <input autocomplete="off" type="hidden" id="edit_guest_unique_id" value="" />

                                                %3$s

                                            </div>

                                        </div>',

                                        /**
                                         *  1. Button Text
                                         *  --------------
                                         */
                                        esc_attr__( 'Update Guest', 'sdweddingdirectory-guest-list' ),

                                        /**
                                         *  2. Button Text
                                         *  --------------
                                         */
                                        esc_attr__( 'Removed Guest', 'sdweddingdirectory-guest-list' ),

                                        /**
                                         *  3. Security
                                         *  -----------
                                         */
                                        wp_nonce_field( 'edit_guest_member_security', 'edit_guest_member_security', true, false ),

                                        /**
                                         *  4. Confirm Alert
                                         *  ----------------
                                         */
                                        esc_attr__( 'Are you sure to removed this guest ?', 'sdweddingdirectory-guest-list' )
                                );

                        ?>

                    </div>
                    <!-- / Five -->

                </div>
            </div>
            
            <?php
        }

    } /* class end **/

    /**
     *  Kicking this off by calling 'get_instance()' method
     *  ---------------------------------------------------
     */
    SDWeddingDirectory_Guest_Edit_Form:: get_instance();
}