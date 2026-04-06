<?php
/**
 *  SDWeddingDirectory - Real Wedding Details - Style Filters
 *  -------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Real_Wedding_Details_Style_Filters' ) && class_exists( 'SDWeddingDirectory_Real_Wedding_Left_Widget_Filter' ) ){

    /**
     *  SDWeddingDirectory - Real Wedding Details - Style Filters
     *  -------------------------------------------------
     */
    class SDWeddingDirectory_Real_Wedding_Details_Style_Filters extends SDWeddingDirectory_Real_Wedding_Left_Widget_Filter{

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
            // add_action( 'sdweddingdirectory/couple/dashboard/real-wedding/left-widget', [ $this, 'widget' ], absint( '30' ) );
        }

        /**
         *  Taxonomy
         *  --------
         */
        public static function taxonomy_name(){

            return      esc_attr( 'real-wedding-style' );
        }

        /**
         *  Get list
         *  --------
         */
        public static function get_taxonomy_terms(){

            return      SDWeddingDirectory_Taxonomy:: get_taxonomy_parent( self:: taxonomy_name() );
        }

        /**
         *  Name of widget
         *  --------------
         */
        public static function widget_name(){

            return      esc_attr__( 'Style', 'sdweddingdirectory-real-wedding' );
        }

        /**
         *  Term ID
         *  -------
         */
        public static function get_term_id(){

            /**
             *  Term ID
             *  -------
             */
            return      apply_filters( 'sdweddingdirectory/post/term-parent', [

                            'post_id'       =>      absint( parent:: realwedding_post_id() ),

                            'taxonomy'      =>      self:: taxonomy_name(),

                        ] );
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
            $_term_id   =   absint(  self:: get_term_id()  );

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
            return  esc_attr( parent:: get_taxonomy_term_name( self:: taxonomy_name(), $_term_id ) );
        }

        /**
         *  Color Code
         *  ----------
         */
        public static function get_term_icon( $term_id = '' ){

            /**
             *  Make sure term id is not empty!
             *  -------------------------------
             */
            if( empty( $term_id ) ){

                return;
            }

            /**
             *  Color Code
             *  ----------
             */
            return  sdwd_get_term_field(

                        /**
                         *  1. Term Key
                         *  -----------
                         */
                        'icon',

                        /**
                         *  2. Term ID
                         *  ----------
                         */
                        $term_id
                    );
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
            $_term_id   =   absint(  self:: get_term_id()  );

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
                    $_target        =   esc_attr( parent:: _rand() );

                    /**
                     *  List of Colors
                     *  --------------
                     */
                    $_icons         =   self:: get_taxonomy_terms();

                    /**
                     *  Term ID
                     *  -------
                     */
                    $_term_id       =   absint( self:: get_term_id() );

                    /**
                     *  My Color
                     *  --------
                     */
                    $_icon          =   self:: get_term_icon( $_term_id );

                    /**
                     *  Have Color ?
                     *  ------------
                     */
                    if( ! empty( $_icon ) ){

                        /**
                         *  Print Color
                         *  -----------
                         */
                        printf( '<div class="real-wedding-summary-widget-icon">

                                    <i class="fa fa-3x %1$s" id="%2$s-taxonomy-icon" aria-hidden="true"></i>

                                </div>',

                                /**
                                 *  Color
                                 *  -----
                                 */
                                $_icon,

                                /**
                                 *  Target ID
                                 *  ---------
                                 */
                                $_target
                        );
                    }

                    /**
                     *  Else
                     *  ----
                     */
                    else{

                        parent:: real_wedding_widget_value_not_found();
                    }

                    ?>
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

                    <button type="button" class="btn" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">

                        <i class="fa fa-pencil"></i>

                    </button>

                    <div class="dropdown-menu style-icons">
                    <?php

                        /**
                         *  Have Colors ?
                         *  -------------
                         */
                        if( parent:: _is_array( $_icons ) ){

                            ?>
                            <div class="py-3"><ul class="list-unstyled">
                                <?php

                                    /**
                                     *  Have Colors
                                     *  -----------
                                     */
                                    if( parent:: _is_array( $_icons ) ){

                                        /**
                                         *  List of colors
                                         *  --------------
                                         */
                                        foreach( $_icons as $term_id => $name ){

                                            printf( '<li>

                                                        <a href="javascript:">

                                                            <div    class="%1$s" 

                                                                    data-term-name="%2$s" 

                                                                    data-term-id="%3$s" 

                                                                    data-taxonomy="%5$s" 

                                                                    data-target="%6$s">


                                                                <i class="fa fa-2x %4$s"></i>

                                                            </div>

                                                            <span>%2$s</span>

                                                        </a>

                                                    </li>',

                                                    /**
                                                     *  1. Target for JS
                                                     *  ----------------
                                                     */
                                                    self:: target_js(),

                                                    /**
                                                     *  2. Color Name
                                                     *  -------------
                                                     */
                                                    esc_attr( $name ),

                                                    /**
                                                     *  3. Term ID
                                                     *  ----------
                                                     */
                                                    $term_id,

                                                    /**
                                                     *  4. Icon
                                                     *  -------
                                                     */
                                                    self:: get_term_icon( $term_id ),

                                                    /**
                                                     *  5. Taxonomy
                                                     *  -----------
                                                     */
                                                    self:: taxonomy_name(),

                                                    /**
                                                     *  6. Target ID
                                                     *  ------------
                                                     */
                                                    esc_attr( $_target )
                                            );
                                        }
                                    }

                                ?>
                            </ul></div>
                            <?php
                        }

                        else{

                            /**
                             *  Error Message
                             *  -------------
                             */
                            printf( '<div class="card-body">%1$s</div>', 

                                /**
                                 *  1. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Please update style taxonomy in real wedding post', 'sdweddingdirectory-real-wedding' )
                            );
                        }

                    ?>
                    </div>

                </div>
            </div>
            <!-- color code -->

            <?php
        }

    } /* class end **/

    /**
     *  SDWeddingDirectory - Real Wedding Details - Style Filters
     *  -------------------------------------------------
     */
    SDWeddingDirectory_Real_Wedding_Details_Style_Filters:: get_instance();
}