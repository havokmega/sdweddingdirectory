<?php
/**
 *  SDWeddingDirectory - Create Couple
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Admin_Page_Create_Couple' ) && class_exists( 'SDWeddingDirectory_Setting_Page' ) ){

    /**
     *  SDWeddingDirectory - Create Couple
     *  --------------------------
     */
    class SDWeddingDirectory_Admin_Page_Create_Couple extends SDWeddingDirectory_Setting_Page{

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
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return  esc_attr__( 'Create Couple', 'sdweddingdirectory' );
        }

        /**
         *  Tab ID
         *  ------
         */
        public static function tab_id(){

            return  sanitize_title( 'create-couple' );
        }

        /**
         *  Tab Filter
         *  ----------
         */
        public static function tab_filter( $args = [] ){

            /**
             *  Merge Filter
             *  ------------
             */
            return      array_merge( $args, [

                            self:: tab_id()     =>      [

                                'tab'           =>      self:: tab_name(),

                                'active'        =>      parent:: setting_page_tab_active( self:: tab_id() ),

                                'callback'      =>      [ __CLASS__, 'tab_content' ]
                            ],

                        ] );
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  SDWeddingDirectory - Setting - Tab
             *  --------------------------
             */
            add_filter( 'sdweddingdirectory/setting-page/tabs', [ $this, 'tab_filter' ], absint( '60' ), absint( '1' ) );

            /**
             *  Load Script
             *  -----------
             */
            add_action( 'admin_enqueue_scripts', [ $this, 'sdweddingdirectory_script' ] );
        }

        /**
         *  Load Script
         *  -----------
         */
        public static function sdweddingdirectory_script( $hook ){

            /**
             *  Make sure it's SDWeddingDirectory Page
             *  ------------------------------
             */
            if( $hook !== 'toplevel_page_sdweddingdirectory' ){

                return;
            }

            /**
             *  Check Condition
             *  ---------------
             */
            if( parent:: setting_page_tab_active( self:: tab_id() ) ){

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_enqueue_script( 

                    /**
                     *  1. File Name
                     *  ------------
                     */
                    esc_attr( sanitize_title( __CLASS__ ) ),

                    /**
                     *  2. File Path
                     *  ------------
                     */
                    esc_url(   plugin_dir_url( __FILE__ )   .   'script.js'   ),

                    /**
                     *  3. Load After "JQUERY" Load
                     *  ---------------------------
                     */
                    array( 'jquery' ),

                    /**
                     *  4. Bootstrap - Library Version
                     *  ------------------------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ )   .   'script.js' ) ),

                    /**
                     *  5. Load in Footer
                     *  -----------------
                     */
                    true
                );

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_localize_script( esc_attr( sanitize_title( __CLASS__ ) ), 'SDWEDDINGDIRECTORY_AJAX_OBJECT', [

                    'ajaxurl'   =>  admin_url( 'admin-ajax.php' ),

                ] );
            }
        }

        /**
         *  Tab Content
         *  -----------
         */
        public static function tab_content(){

          ?>

            <div class="sdweddingdirectory_content_style">

                <form id="sdweddingdirectory_couple_registration_form" method="post" autocomplete="off" > 

                    <table class="form-table">

                        <tbody>

                        <?php

                            /**
                             *  Group of Fields
                             *  ---------------
                             */
                            $_input_fields    =   [

                                'sdweddingdirectory_couple_register_username'               =>  [

                                    'type'          =>      esc_attr( 'text' ),

                                    'name'          =>      esc_attr__( 'Username', 'sdweddingdirectory' ),
                                ],

                                'sdweddingdirectory_couple_register_first_name'             =>  [

                                    'type'          =>      esc_attr( 'text' ),

                                    'name'          =>      esc_attr__( 'First Name', 'sdweddingdirectory' ),
                                ],

                                'sdweddingdirectory_couple_register_last_name'              =>  [

                                    'type'          =>      esc_attr( 'text' ),

                                    'name'          =>      esc_attr__( 'Last Name', 'sdweddingdirectory' ),
                                ],

                                'sdweddingdirectory_couple_register_password'               =>  [

                                    'type'          =>      esc_attr( 'text' ),

                                    'name'          =>      esc_attr__( 'Password', 'sdweddingdirectory' ),
                                ],

                                'sdweddingdirectory_couple_register_email'                  =>  [

                                    'type'          =>      esc_attr( 'email' ),

                                    'name'          =>      esc_attr__( 'Email ID', 'sdweddingdirectory' ),
                                ],

                                'sdweddingdirectory_couple_register_wedding_date'           =>  [

                                    'type'          =>      esc_attr( 'date' ),

                                    'name'          =>      esc_attr__( 'Wedding Date', 'sdweddingdirectory' ),
                                ],
                            ];

                            /**
                             *  Input Fields
                             *  ------------
                             */
                            if( parent:: _is_array( $_input_fields ) ){

                                foreach( $_input_fields as $key => $value ){

                                    /**
                                     *  Extract
                                     *  -------
                                     */
                                    extract( $value );

                                    printf( '<tr class="form-field form-required">

                                                <th scope="row"><label for="%1$s">%2$s</label></th>

                                                <td><input autocomplete="off" name="%1$s" class="form-control" type="%3$s" id="%1$s" value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60"></td>

                                            </tr>',

                                            /**
                                             *  1. Key
                                             *  ------
                                             */
                                            sanitize_key( $key ),

                                            /**
                                             *  2. Name
                                             *  -------
                                             */
                                            esc_attr( $name ),

                                            /**
                                             *  3. Type
                                             *  -------
                                             */
                                            esc_attr( $type )
                                    );
                                }
                            }

                            /**
                             *  Group of Fields
                             *  ---------------
                             */
                            $_select_fields    =   [

                                'sdweddingdirectory_register_couple_person'               =>  [

                                    'name'          =>      esc_attr__( 'I am', 'sdweddingdirectory' ),

                                    'options'       =>      SDWeddingDirectory_Taxonomy:: create_select_option(

                                                                [
                                                                    'i_am_couple'       =>      esc_attr__( 'Planning my wedding', 'sdweddingdirectory' ),

                                                                    'i_am_guest'        =>      esc_attr__( 'A wedding guest', 'sdweddingdirectory' )
                                                                ],

                                                                [],

                                                                '',

                                                                false
                                                            )
                                ],

                                'sending_email'     =>  [

                                    'name'          =>      esc_attr__( 'Sending Email ?', 'sdweddingdirectory' ),

                                    'options'       =>      SDWeddingDirectory_Taxonomy:: create_select_option(

                                                                [
                                                                    '0'     =>      esc_attr__( 'No', 'sdweddingdirectory' ),

                                                                    '1'     =>      esc_attr__( 'Yes', 'sdweddingdirectory' )
                                                                ],

                                                                [],

                                                                '',

                                                                false
                                                            )
                                ],
                            ];

                            /**
                             *  Input Fields
                             *  ------------
                             */
                            if( parent:: _is_array( $_select_fields ) ){

                                foreach( $_select_fields as $key => $value ){

                                    /**
                                     *  Extract
                                     *  -------
                                     */
                                    extract( $value );

                                    printf( '<tr class="form-field form-required">

                                                <th scope="row"><label for="%1$s">%2$s</label></th>

                                                <td><select id="%1$s" name="%1$s">%3$s</select></td>

                                            </tr>',

                                            /**
                                             *  1. Key
                                             *  ------
                                             */
                                            sanitize_key( $key ),

                                            /**
                                             *  2. Name
                                             *  -------
                                             */
                                            esc_attr( $name ),

                                            /**
                                             *  3. Type
                                             *  -------
                                             */
                                            $options
                                    );
                                }
                            }

                        ?>
                        </tbody>

                    </table>

                    <hr/>

                    <p class="status"></p>

                    <p class="submit">
                    <?php

                        /**
                         *  Submit
                         *  ------
                         */
                        printf( '<button type="submit" class="button button-large button-primary" id="%1$s" name="%1$s">
                            
                                    %2$s %3$s

                                </button>',

                                /**
                                 *  1. Name / ID
                                 *  ------------
                                 */
                                esc_attr( 'sdweddingdirectory_register_couple_registration_form_button' ),

                                /**
                                 *  2. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Add New Couple', 'sdweddingdirectory' ),

                                /**
                                 *  Security
                                 *  --------
                                 */
                                wp_nonce_field( 'sdweddingdirectory_couple_registration_security', 'sdweddingdirectory_couple_registration_security', true, false )
                        );

                    ?>
                    </p>

                </form>

            </div>

          <?php
        }        
    }

    /**
     *  SDWeddingDirectory - Create Couple
     *  --------------------------
     */
    SDWeddingDirectory_Admin_Page_Create_Couple:: get_instance();
}