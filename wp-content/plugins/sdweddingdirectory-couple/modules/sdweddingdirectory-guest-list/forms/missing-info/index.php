<?php
/**
 *  SDWeddingDirectory Guest List Reqeust Missing Info
 *  ------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_Request_Missing_Info' ) && class_exists( 'SDWeddingDirectory_Guest_List_Form_Handler' ) ){

	/**
	 *  SDWeddingDirectory Guest List Reqeust Missing Info
	 *  ------------------------------------------
	 */
    class SDWeddingDirectory_Guest_List_Request_Missing_Info extends SDWeddingDirectory_Guest_List_Form_Handler{

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

            /**
             *  2. Register Popup ID
             *  --------------------
             */
            add_filter( 'sdweddingdirectory/model-popup', [ $this, 'register_model' ], absint( '20' ), absint( '1' ) );

        }

        /**
         *  Register Model
         *  --------------
         */
        public static function register_model( $args = [] ){

            /**
             *  Merger Already Exists Model
             *  ---------------------------
             */
            return      array_merge( $args, array(

                            [
                                'slug'                  =>      esc_attr( 'request_missing_info' ),

                                'modal_id'              =>      esc_attr( 'guest_list_tsting_request_missing_info' ),

                                'redirect_link'         =>      '',

                                'name'                  =>      esc_attr__( 'Request Missing Information Modal Popup', 'sdweddingdirectory-guest-list' )
                            ]

                        ) );
        }

        /**
         *  1. Load Create Todo Form
         *  ------------------------
         */
        public static function sidebar_panel(){

            /**
             *  Make sure is couple guest page
             *  ------------------------------
             */
            if(  parent:: dashboard_page_set( 'guest-management' ) ) {

                /**
                 *  Random ID
                 *  ---------
                 */
                $rand_id    =   esc_attr( parent:: _rand() );

                /**
                 *  Model Start
                 *  -----------
                 */
                printf( '<div class="modal fade" id="%1$s" tabindex="-1" aria-labelledby="%1$s" aria-hidden="true">',

                    /**
                     *  1. Couple Register Popup ID
                     *  ---------------------------
                     */
                    esc_attr( parent:: popup_id( 'request_missing_info' ) )
                );

                ?>
                    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">            

                        <div class="modal-content">

                            <div class="modal-body p-0">

                                <div class="">

                                    <div class="row g-0">

                                        <!-- col-md-5 -->
                                        <div class="col-lg-5 d-none d-lg-block d-xl-block sidebar-img"
                                            <?php 

                                                /**
                                                 *  Background Image
                                                 *  ----------------
                                                 */
                                                printf( 'style="background: url(%1$s) no-repeat center;background-size: cover;"',

                                                    esc_url(  parent:: placeholder( 'request-missing-info-popup' ) )
                                                );

                                            ?>
                                        >
                                        </div>
                                        <!-- / col-md-5 -->

                                        <!-- col-md-7 -->
                                        <div class="col-lg-7 col-md-12 col-12">

                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>

                                            <!-- login section -->
                                            <div class="p-5">
                                            <?php

                                                /**
                                                 *  Have NextEnd Social Login
                                                 *  -------------------------
                                                 */
                                                printf( '<div class="col-12 mb-4 text-center"><h2>%1$s</h2></div>',

                                                    /**
                                                     *  1. Translation Ready String
                                                     *  ---------------------------
                                                     */
                                                    esc_attr__( 'Request missing info', 'sdweddingdirectory-guest-list' )
                                                );

                                                ?>

                                                <div class="col-12 text-center">
                                                <?php

                                                    /**
                                                     *  Content
                                                     *  -------
                                                     */
                                                    printf( '<p class="lead">%1$s</p>

                                                            <ol class="list-unstyled">
                                                                <li>%2$s</li>
                                                                <li>%3$s</li>
                                                                <li>%4$s</li>
                                                            </ol>',

                                                            /**
                                                             *  1. Translation Ready String
                                                             *  ---------------------------
                                                             */
                                                            esc_attr__( 'Collect addresses from all your guests with just one link.', 'sdweddingdirectory-guest-list' ),

                                                            /**
                                                             *  2. Translation Ready String
                                                             *  ---------------------------
                                                             */
                                                            esc_attr__( 'Send your link to family and friends.', 'sdweddingdirectory-guest-list' ),

                                                            /**
                                                             *  3. Translation Ready String
                                                             *  ---------------------------
                                                             */
                                                            esc_attr__( 'Guests fill out their contact details.', 'sdweddingdirectory-guest-list' ),

                                                            /**
                                                             *  4. Translation Ready String
                                                             *  ---------------------------
                                                             */
                                                            esc_attr__( 'That\'s it - they\'re on your list.', 'sdweddingdirectory-guest-list' )
                                                    );

                                                ?>
                                                </div>

                                                <div class="card-body text-center">

                                                    <div class="col-12">
                                                    <?php

                                                        /**
                                                         *  Message Display
                                                         *  ---------------
                                                         */
                                                        printf( '<p class="d-none text-success fw-bold" id="%1$s_message" data-success-message="%2$s"></p>',

                                                            /**
                                                             *  1. ID
                                                             *  -----
                                                             */
                                                            esc_attr( $rand_id ),

                                                            /**
                                                             *  4. Translation Ready String
                                                             *  ---------------------------
                                                             */
                                                            esc_attr__( 'link copied', 'sdweddingdirectory-guest-list' )
                                                        );
                                                    ?>
                                                    </div>

                                                    <div class="input-group input_copy_group">
                                                    <?php

                                                        /**
                                                         *  Weblink Widget
                                                         *  --------------
                                                         */
                                                        printf( '<input autocomplete="off" type="text" id="%1$s_text" value="%2$s" readonly 

                                                                        class="form-control copy_request_missing_info_link" />

                                                                <div class="input-group-append d-grid">

                                                                    <button class="input-group-text btn btn-primary __copy_text__" 

                                                                            data-success-message="#%1$s_message" 

                                                                            data-clipboard-target="#%1$s_text">

                                                                                <i class="fa fa-clone" aria-hidden="true"></i>

                                                                    </button>

                                                                </div>',

                                                                /**
                                                                 *  1. ID
                                                                 *  -----
                                                                 */
                                                                esc_attr( $rand_id ),

                                                                /**
                                                                 *  2. Website link
                                                                 *  ---------------
                                                                 */
                                                                add_query_arg( 

                                                                    /**
                                                                     *  Args
                                                                     *  ----
                                                                     */
                                                                    [
                                                                        'user'      =>      parent:: post_id(),

                                                                        'guest'     =>      absint( '21458829' )
                                                                    ],

                                                                    /**
                                                                     *  Page Template
                                                                     *  -------------
                                                                     */
                                                                    apply_filters( 'sdweddingdirectory/template/link', esc_attr( 'request-missing-info.php' ) ) 
                                                                )
                                                        );

                                                    ?>
                                                    </div>

                                                </div>

                                            </div>
                                            <!-- / login section -->

                                        </div>
                                        <!-- / col-md-7 -->

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                
                </div>
                <?php
            }
        }

        /**
         *  Register Model Have Placeholders ?
         *  ----------------------------------
         */
        public static function register_model_images( $args = [] ){

            /**
             *  Add Couple Model Popup Image
             *  ----------------------------
             */
            return  array_merge(

                        /**
                         *  Have Args ?
                         *  -----------
                         */
                        $args,

                        /**
                         *  Merge New Args
                         *  --------------
                         */
                        array(

                            'request-missing-info-popup'    =>      esc_url(

                                                                        /**
                                                                         *  1. Image Path
                                                                         *  -------------
                                                                         */
                                                                        plugin_dir_url( __FILE__ ) . 'images/' .

                                                                        /**
                                                                         *  2. Image Name
                                                                         *  -------------
                                                                         */
                                                                        esc_attr( 'missing-info.jpg' )
                                                                    ),
                        )
                    );
        }

    } /* class end **/

    /**
     *  SDWeddingDirectory Guest List Reqeust Missing Info
     *  ------------------------------------------
     */
    SDWeddingDirectory_Guest_List_Request_Missing_Info:: get_instance();
}
