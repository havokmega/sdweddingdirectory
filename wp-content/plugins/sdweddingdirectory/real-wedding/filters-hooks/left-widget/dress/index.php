<?php
/**
 *  SDWeddingDirectory - Real Wedding Details - Dress Filters
 *  -------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Real_Wedding_Details_Dress_Vendor_Filters' ) && class_exists( 'SDWeddingDirectory_Real_Wedding_Left_Widget_Filter' ) ){

    /**
     *  SDWeddingDirectory - Real Wedding Details - Dress Filters
     *  -------------------------------------------------
     */
    class SDWeddingDirectory_Real_Wedding_Details_Dress_Vendor_Filters extends SDWeddingDirectory_Real_Wedding_Left_Widget_Filter{

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
            // add_action( 'sdweddingdirectory/couple/dashboard/real-wedding/left-widget', [ $this, 'widget' ], absint( '40' ) );
        }

        /**
         *  Taxonomy
         *  --------
         */
        public static function taxonomy_name(){

            return      esc_attr( 'venue-type' );
        }

        /**
         *  Get list
         *  --------
         */
        public static function get_category_venues(){

            /**
             *  Get Terms of Dress Category Venues
             *  ------------------------------------
             */
            return      apply_filters(  'sdweddingdirectory/post/data', [

                            'post_type'         =>      esc_attr( 'venue' ),

                            'tax_query'         =>      [

                                [   'taxonomy'  =>  self:: taxonomy_name(),

                                    'field'     =>  esc_attr( 'id' ),

                                    'terms'     =>  absint( self:: get_term_id() )
                                ],
                            ],

                        ] );
        }

        /**
         *  Name of widget
         *  --------------
         */
        public static function widget_name(){

            return      esc_attr__( 'Designer\'s Name', 'sdweddingdirectory-real-wedding' );
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
            return      absint( sdweddingdirectory_option( 'realwedding-dress-category' ) );
        }

        /**
         *  Venue ID
         *  ----------
         */
        public static function venue_id(){

            /**
             *  Venue ID
             *  ----------
             */
            return  absint( get_post_meta( 

                        /**
                         *  Real Wedding Post ID
                         *  --------------------
                         */
                        parent:: realwedding_post_id(), 

                        /**
                         *  2. Meta Key
                         *  -----------
                         */
                        sanitize_key( 'realwedding-dress' ),

                        /**
                         *  3. TRUE
                         *  -------
                         */
                        true

                    ) );
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
            $_venue_id   =   self:: venue_id();

            /**
             *  Term is empty
             *  -------------
             */
            if( empty( $_venue_id ) ){

                return      '...';
            }

            /**
             *  Get Term Name - User Saved
             *  --------------------------
             */
            return  esc_attr( get_the_title( absint( $_venue_id ) ) );
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
            $_venue_id    =   absint(  self:: venue_id()  );

            /**
             *  Term Count
             *  ----------
             */
            return      sprintf( esc_attr__( '%1$s couples', 'sdweddingdirectory-real-wedding' ),  

                            /**
                             *  Term Count
                             *  ----------
                             */
                            !   empty( $_venue_id )

                            ?   parent:: realwedding_meta_value_count( [

                                    'meta_key'      =>      esc_attr( 'realwedding-dress' ),

                                    'meta_value'    =>      absint( $_venue_id ),

                                ] )

                            :   ''
                        );
        }

        /**
         *  Target JS
         *  ---------
         */
        public static function target_js(){

            return      esc_attr( 'couple_real_wedding_select_designer_filter' );
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

                    <div class="real-wedding-summary-widget-icon">

                        <i class="sdweddingdirectory-fashion fa-3x" aria-hidden="true"></i>

                    </div>
                    <?php

                        /**
                         *  Target ID
                         *  ---------
                         */
                        $_target        =   esc_attr( parent:: _rand() );

                        /**
                         *  Options
                         *  -------
                         */
                        $_options       =   self:: get_category_venues();

                        /**
                         *  Term ID
                         *  -------
                         */
                        $_term_id       =   absint( self:: get_term_id() );

                    ?>
                    <div>
                    <?php

                        /**
                         *  Print
                         *  -----
                         */
                        printf(    '<small>%1$s</small>

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

                    <div class="dropdown-menu season-icons">
                    <?php

                        /**
                         *  Have Colors ?
                         *  -------------
                         */
                        if( parent:: _is_array( $_options ) ){

                            ?>
                            <div class="card-body">

                                <p><strong><?php echo self:: widget_name(); ?></strong></p>

                                <select 

                                    class="form-control couple-select-dress form-dark <?php echo self:: target_js(); ?>" 

                                    name="realwedding-venue-type"

                                    data-target="<?php echo $_target; ?>"

                                    data-placeholder="<?php echo self:: widget_name(); ?>">

                                    <?php

                                        $_selected  =   self:: venue_id();

                                        /**
                                         *  List of colors
                                         *  --------------
                                         */
                                        foreach( $_options as $key => $value ){

                                            /**
                                             *  Options
                                             *  -------
                                             */
                                            printf( '<option value="%1$s" %3$s>%2$s</option>',

                                                    /**
                                                     *  1. ID
                                                     *  -----
                                                     */
                                                    $key,

                                                    /**
                                                     *  2. Name
                                                     *  -------
                                                     */
                                                    esc_attr( $value ),

                                                    /**
                                                     *  3. Is Selected ?
                                                     *  ----------------
                                                     */
                                                    ! empty( $_selected ) && $key == $_selected

                                                    ?   esc_attr( 'selected' )

                                                    :   ''
                                            );
                                        }

                                    ?>
                                </select>

                            </div>
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
                                esc_attr__( 'Please update real wedding setting option dress category.', 'sdweddingdirectory-real-wedding' )
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
     *  SDWeddingDirectory - Real Wedding Details - Dress Filters
     *  -------------------------------------------------
     */
    SDWeddingDirectory_Real_Wedding_Details_Dress_Vendor_Filters:: get_instance();
}