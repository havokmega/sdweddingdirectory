<?php
/**
 *  SDWeddingDirectory - Real Wedding Details - Honymoon Location Filters
 *  -------------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Real_Wedding_Details_Honeymoon_Location_Filters' ) && class_exists( 'SDWeddingDirectory_Real_Wedding_Left_Widget_Filter' ) ){

    /**
     *  SDWeddingDirectory - Real Wedding Details - Honymoon Location Filters
     *  -------------------------------------------------------------
     */
    class SDWeddingDirectory_Real_Wedding_Details_Honeymoon_Location_Filters extends SDWeddingDirectory_Real_Wedding_Left_Widget_Filter{

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
             *  1. Dashboard / Left Side / Widget / Wedding Details / Color
             *  -----------------------------------------------------------
             */
            // Removed — Wedding details widget eliminated.
            // add_action( 'sdweddingdirectory/couple/dashboard/real-wedding/left-widget', [ $this, 'widget' ], absint( '50' ) );
        }

        /**
         *  Taxonomy
         *  --------
         */
        public static function taxonomy_name(){

            return      esc_attr( 'real-wedding-location' );
        }

        /**
         *  Name of widget
         *  --------------
         */
        public static function widget_name(){

            return      esc_attr__( 'Honeymoon', 'sdweddingdirectory-real-wedding' );
        }

        /**
         *  Term Name
         *  ---------
         */
        public static function term_name(){

            /**
             *  Term ID
             *  -------
             */
            $_term_id   =   apply_filters( 'sdweddingdirectory/find-post-location/id', [

                                'post_id'       =>      parent:: realwedding_post_id(),

                                'taxonomy'      =>      self:: taxonomy_name()

                            ] );

            /**
             *  Term is empty
             *  -------------
             */
            if( empty( $_term_id ) ){

                return      '...';
            }

            /**
             *  Get Term Name - User Saved
             *  --------------------------
             */
            return      esc_attr( apply_filters( 'sdweddingdirectory/find-post-location/name', [

                            'post_id'       =>      parent:: realwedding_post_id(),

                            'taxonomy'      =>      self:: taxonomy_name()

                        ] ) );
        }

        /**
         *  Term Count
         *  ----------
         */
        public static function term_count(){

            /**
             *  Term ID
             *  -------
             */
            $_term_id   =   apply_filters( 'sdweddingdirectory/find-post-location/id', [

                                'post_id'       =>      parent:: realwedding_post_id(),

                                'taxonomy'      =>      self:: taxonomy_name()

                            ] );

            /**
             *  Term Count
             *  ----------
             */
            return      sprintf( esc_attr__( '%1$s couples', 'sdweddingdirectory-real-wedding' ),  

                            /**
                             *  Term Count
                             *  ----------
                             */
                            !   empty( $_term_id )

                            ?   absint( parent:: get_taxonomy_term_count( self:: taxonomy_name(), $_term_id ) )

                            :   ''
                        );
        }

        /**
         *  Target JS
         *  ---------
         */
        public static function target_js(){

            return      esc_attr( 'couple_real_wedding_select_season_filter' );
        }

        /**
         *  1. Dashboard / Left Side / Widget
         *  ---------------------------------
         */
        public static function widget(){

            ?>
            <!-- color code -->
            <div class="wedding-details-popups col">

                <div class="wedding-details-items">
                <?php

                    /**
                     *  Target ID
                     *  ---------
                     */
                    $_target        =   esc_attr( 'honeymoon' );

                    /**
                     *  Term ID
                     *  -------
                     */
                    $_term_id       =   apply_filters( 'sdweddingdirectory/find-post-location/id', [

                                            'post_id'       =>      parent:: realwedding_post_id(),

                                            'taxonomy'      =>      self:: taxonomy_name()

                                        ] );

                    ?>
                    <div class="real-wedding-summary-widget-icon">

                        <i class="sdweddingdirectory-plane-4 fa-3x" aria-hidden="true"></i>

                    </div>

                    <div>
                    <?php

                        /**
                         *  Print
                         *  -----
                         */
                        printf(     '<small>%1$s</small>

                                    <div class="head" id="%4$s-taxonomy-name">%2$s</div>

                                    <span class="count" id="%4$s-taxonomy-count">%3$s</span>',

                                /**
                                 *  1. Widget Name
                                 *  --------------
                                 */
                                self:: widget_name(),

                                /**
                                 *  2. Term Name
                                 *  ------------
                                 */
                                self:: term_name(),

                                /**
                                 *  3. Term Count
                                 *  -------------
                                 */
                                self:: term_count(),

                                /**
                                 *  4. Target ID
                                 *  ------------
                                 */
                                $_target
                        );
                    ?>
                    </div>

                </div>

                <div class="text-center">

                    <button type="button" class="btn wedding-summary-edit-icon" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">

                        <i class="fa fa-pencil"></i>

                    </button>

                    <div class="dropdown-menu">

                        <div class="card-body">

                            <p><strong><?php esc_attr_e( 'Honeymoon Destination', 'sdweddingdirectory-real-wedding' ); ?></strong></p>

                            <?php

                                /**
                                 *  Random ID
                                 *  ---------
                                 */
                                $parent_id      =       esc_attr( parent:: _rand() );

                                /**
                                 *  Print Input
                                 *  -----------
                                 */
                                print 

                                apply_filters( 'sdweddingdirectory/input-location', [

                                    'before_input'          =>      sprintf( '<div class="row sdweddingdirectory-dropdown-parent create-input-border" id="%1$s">', 

                                                                        $parent_id 
                                                                    ),

                                    'after_input'           =>      '</div>',

                                    'parent_id'             =>      esc_attr( $parent_id ),

                                    'input_style'           =>      sanitize_html_class( 'form-dark' ),

                                    'hide_empty'            =>      false,

                                    'ajax_load'             =>      true,

                                    'value'                 =>      apply_filters( 'sdweddingdirectory/find-post-location/name', [

                                                                        'post_id'       =>      parent:: realwedding_post_id(),

                                                                        'taxonomy'      =>      self:: taxonomy_name()

                                                                    ] ),

                                    'location_id'           =>      apply_filters( 'sdweddingdirectory/find-post-location/id', [

                                                                        'post_id'       =>      parent:: realwedding_post_id(),

                                                                        'taxonomy'      =>      self:: taxonomy_name()

                                                                    ] ),

                                    'post_type'             =>      esc_attr( 'real-wedding' )

                                ] );

                            ?>
                        </div>

                    </div>

                </div>
            </div>
            <!-- color code -->

            <?php
        }

    } /* class end **/

    /**
     *  SDWeddingDirectory - Real Wedding Details - Honymoon Location Filters
     *  -------------------------------------------------------------
     */
    SDWeddingDirectory_Real_Wedding_Details_Honeymoon_Location_Filters:: get_instance();
}