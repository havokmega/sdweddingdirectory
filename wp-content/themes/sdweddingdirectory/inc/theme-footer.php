<?php
/**
 *  SDWeddingDirectory - Footer Action / Hooks
 *  ----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Footer' ) && class_exists( 'SDWeddingDirectory' ) ){

    /**
     *  SDWeddingDirectory - Footer Action / Hooks
     *  ----------------------------------
     */
    class SDWeddingDirectory_Footer extends SDWeddingDirectory {

        private static $instance;

        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  1. Footer Markup
             *  ----------------
             */
            add_action( 'sdweddingdirectory/footer', [$this, 'sdweddingdirectory_footer_markup' ],  absint( '10' ) );

            /**
             *  2. Tiny Footer
             *  --------------
             */
            add_action( 'sdweddingdirectory/footer', [$this, 'sdweddingdirectory_tiny_footer' ],  absint( '20' ) );

            /**
             *  3. Back To Top
             *  --------------
             */
            add_action( 'sdweddingdirectory/footer', [$this, 'back_to_top' ],  absint( '30' ) );
        }

        /**
         *  Footer Markup
         *  -------------
         */
        public static function sdweddingdirectory_footer_markup(){

            /**
             *  Default True
             *  ------------
             */
            $_footer_enable     =   esc_attr( 'on' );

            /**
             *  If plugin activate then get option in page so by default footer switch is *ON*
             *  ------------------------------------------------------------------------------
             */
            if(  ! empty( parent:: sdweddingdirectory_page_id() ) ){

                /**
                 *  Have Footer Switch is ON / OFF ?
                 *  --------------------------------
                 */
                $_have_footer   =       esc_attr( get_post_meta(

                                            /**
                                             *  1. Page Object ID
                                             *  -----------------
                                             */
                                            absint( parent:: sdweddingdirectory_page_id() ),

                                            /**
                                             *  2. Meta Key
                                             *  -----------
                                             */
                                            sanitize_key( 'page_footer_on_off' ),

                                            /**
                                             *  3. TRUE
                                             *  -------
                                             */
                                            true 

                                        ) );

                /**
                 *  This Page have Disable Meta ?
                 *  -----------------------------
                 */
                if( $_have_footer       ==      esc_attr( 'off' ) ){

                    $_footer_enable     =       esc_attr( 'off' );
                }
            }

            /**
             *  Footer Have One Widget ?
             *  ------------------------
             */
            $_have_footer_widget    =   false;

            /**
             *  Footer Widget Have Data ?
             *  -------------------------
             */
            if( is_active_sidebar( SDWEDDINGDIRECTORY_FOOTER_WIDGET . '-1' ) ||
                is_active_sidebar( SDWEDDINGDIRECTORY_FOOTER_WIDGET . '-2' ) ||
                is_active_sidebar( SDWEDDINGDIRECTORY_FOOTER_WIDGET . '-3' ) ||
                is_active_sidebar( SDWEDDINGDIRECTORY_FOOTER_WIDGET . '-4' ) ||
                is_active_sidebar( SDWEDDINGDIRECTORY_FOOTER_WIDGET . '-5' ) ||
                is_active_sidebar( SDWEDDINGDIRECTORY_FOOTER_WIDGET . '-6' ) ){

                $_have_footer_widget        =   true;
            }

            /**
             *  Footer Enable to Load 
             *  ---------------------
             */
            if( $_footer_enable !== esc_attr( 'off' ) ){

                ?>

                <footer itemtype="https://schema.org/WPFooter" itemscope="itemscope" id="colophon" class="footer">

                    <?php

                        /**
                         *  1. Footer Class condition
                         *  -------------------------
                         */
                        if(   parent:: is_dashboard()  ){

                            $_footer_class  =   sanitize_html_class( 'footer-inner' );

                        }else{

                            $_footer_class  =   sanitize_html_class( 'footer-section' );
                        }

                        /**
                         *  Before Start the Footer
                         *  -----------------------
                         */
                        printf( '<div class="%1$s %2$s"><div class="container">',

                                /**
                                 *  1. Footer Class condition
                                 *  -------------------------
                                 */
                                sanitize_html_class( $_footer_class ),

                                sanitize_html_class( 'sdweddingdirectory-footer-dark' )
                        );

                            if( $_have_footer_widget ){

                                /**
                                 *  Load the Widgets
                                 *  ----------------
                                 */
                                self:: sdweddingdirectory_footer_widget_markup();

                            }else{

                                /**
                                 *  Static Footer Fallback
                                 *  ----------------------
                                 */
                                self:: sdweddingdirectory_static_footer_markup();
                            }

                        /**
                         *  3. Close the Footer
                         *  -------------------
                         */
                        ?></div></div>

                </footer>

                <?php
            }
        }

        /**
         *  Tiny Footer
         *  -----------
         */
        public static function sdweddingdirectory_tiny_footer(){

            /**
             *  Default : OFF
             *  -------------
             */
            $tiny_footer_option         =   esc_attr( 'on' );

            /**
             *  If Option Tree Plugin is Activated ?
             *  ------------------------------------
             */
            if( ! empty( parent:: sdweddingdirectory_page_id() ) ){

                /**
                 *  Have Tiny Footer Switch ON ? ( Setting Option )
                 *  -----------------------------------------------
                 */
                $_have_footer     =   esc_attr(   get_post_meta(

                                                /**
                                                 *  Page ID
                                                 *  -------
                                                 */
                                                absint( parent:: sdweddingdirectory_page_id() ),

                                                /**
                                                 *  Meta Key
                                                 *  --------
                                                 */
                                                sanitize_key( 'page_tiny_footer_on_off' ),

                                                /**
                                                 *  TRUE
                                                 *  ----
                                                 */
                                                true 

                                            )   );

                /**
                 *  Footer is Enable ?
                 *  ------------------
                 */
                if( $_have_footer == esc_attr( 'off' ) ){

                    $tiny_footer_option =  esc_attr( 'off' );
                }
            }

            /**
             *  Have Copy Right Text in Settings
             *  -------------------------------
             */
            $_have_copyright_text       =   defined( 'SDW_FOOTER_COPYRIGHT_TEXT' ) ? SDW_FOOTER_COPYRIGHT_TEXT : '';

            /**
             *  Tiny Footer Menu ?
             *  ------------------
             */
            $_tiny_footer_menu          =   has_nav_menu( 'tiny-footer-menu' );

            /**
             *  Check the condition
             *  -------------------
             */
            if( $tiny_footer_option !== esc_attr( 'off' ) && ( ! empty( $_have_copyright_text ) || $_tiny_footer_menu ) ){

                /**
                 *  Show Copyright content
                 *  ----------------------
                 */
                printf( '<div class="copyrights">

                            <div class="container">

                                <div class="row justify-content-between"> %1$s %2$s </div>

                            </div>

                        </div>',

                        /**
                         *  SDWeddingDirectory Copyrignt Content here
                         *  ---------------------------------
                         */
                        !   empty( $_have_copyright_text )

                        ?   sprintf( '<div class="col-md-auto col-12">%1$s</div>', 

                                /**
                                 *  1. Copyright Content
                                 *  --------------------
                                 */
                                esc_attr__( $_have_copyright_text, 'sdweddingdirectory' )
                            )

                        :   '',

                        /**
                         *  Footer Navigation
                         *  -----------------
                         */
                        $_tiny_footer_menu

                        ?   sprintf( '<div class="col-md-auto col-12 copyrights-link ml-md-auto">%1$s</div>',

                                wp_nav_menu( [

                                    'theme_location'    =>  esc_attr( 'tiny-footer-menu' ),

                                    'depth'             =>  absint( '1' ),

                                    'container'         =>  false,

                                    'container_class'   =>  false,

                                    'container_id'      =>  false,

                                    'echo'              =>  false,
                                ] )
                            )

                        :   ''
                );
            }
        }

        /**
         *  Back To Top Markup
         *  ------------------
         */
        public static function back_to_top(){

            ?>
            <a id="back-to-top" href="javascript:" class="btn btn-outline-primary back-to-top d-flex justify-content-center align-items-center">

                <i class="fa fa-arrow-up"></i>

            </a>
            <?php
        }

        /**
         *  Footer Widget Have Grid ?
         *  -------------------------
         */
        public static function sdweddingdirectory_footer_widget_markup(){

            /**
             *  Have Footer Column
             *  ------------------
             */
            $_have_footer_column    =   defined( 'SDW_DEFAULT_FOOTER_COLUMNS' ) ? SDW_DEFAULT_FOOTER_COLUMNS : 'column_4';

            /**
             *  Is One Column ?
             *  ---------------
             */
            if(  $_have_footer_column == esc_attr( 'column_1' )  ){

                ?><div class="row"><?php

                    self:: widget_load(  absint( '12' ), absint( '1' )  );

                ?></div><?php
            }

            /**
             *  Is Two Column ?
             *  ---------------
             */
            if( $_have_footer_column == esc_attr( 'column_2' ) ){

                ?><div class="row"><?php

                    foreach( array_map( 'absint', range( absint( '1' ), absint( '2' ) ) ) as $args ){

                        self:: widget_load(  absint( '6' ), absint( $args )  );
                    }

                ?></div><?php
            }

            /**
             *  Is Three Column ?
             *  -----------------
             */
            if( $_have_footer_column == esc_attr( 'column_3' ) ){

                ?><div class="row"><?php

                    foreach(array_map( 'absint', range( absint( '1' ), absint( '3' ) ) ) as $args ){

                        self:: widget_load(  absint( '4' ), absint( $args )  );
                    }

                ?></div><?php
            }

            /**
             *  Is Four Column ?
             *  ----------------
             */
            if( $_have_footer_column == esc_attr( 'column_4' ) ){

                ?><div class="row"><?php

                    foreach( array_map( 'absint', range( absint( '1' ), absint( '4' ) ) ) as $args ){

                        self:: widget_load(  absint( '3' ), absint( $args )  );
                    }

                ?></div><?php
            }

            /**
             *  Is ( 6 / 3 / 3 ) Grid
             *  ---------------------
             */
            if( $_have_footer_column == esc_attr( 'column_5' ) ){

                ?><div class="row"><?php

                    self:: widget_load(  absint( '6' ), absint( '1' )  );

                    self:: widget_load(  absint( '4' ), absint( '2' )  );

                    self:: widget_load(  absint( '4' ), absint( '3' )  );

                ?></div><?php
            }

            /**
             *  Is ( 3 / 3 / 6 ) Grid
             *  ---------------------
             */
            if( $_have_footer_column == esc_attr( 'column_6' ) ){

                ?><div class="row"><?php

                    self:: widget_load(  absint( '4' ), absint( '1' )  );

                    self:: widget_load(  absint( '4' ), absint( '2' )  );

                    self:: widget_load(  absint( '6' ), absint( '3' )  );

                ?></div><?php
            }

            /**
             *  SDWeddingDirectory - Custom Grid Here
             *  -----------------------------
             */
            if( $_have_footer_column == esc_attr( 'column_7' ) ){

                ?>
                <div class="row g-0">

                    <!-- col-lg-7 -->
                    <div class="col-lg-7">

                        <div class="row">

                            <div class="col-md-5 sdweddingdirectory-footer-block">

                                <?php self:: load_widget( absint( '1' ) );  ?>

                            </div>
                        
                            <div class="col-md sdweddingdirectory-footer-block">

                                <?php self:: load_widget( absint( '2' ) );  ?>

                            </div>

                            <div class="col-md sdweddingdirectory-footer-block">

                                <?php self:: load_widget( absint( '3' ) );  ?>

                            </div>

                        </div>

                    </div>
                    <!--  / col-lg-7 -->

                    <!-- col-lg-5 -->
                    <div class="col-lg-5 mr-top-footer">

                        <div class="row">

                            <div class="col-md-6 col-12 sdweddingdirectory-footer-block">

                                <?php self:: load_widget( absint( '4' ) );  ?>

                            </div>

                            <div class="col-md-6 col-12 sdweddingdirectory-footer-block">

                                <?php self:: load_widget( absint( '5' ) );  ?>

                            </div>

                        </div>

                    </div>
                    <!-- col-lg-5 -->

                </div>
                <?php
            }
        }

        /**
         *  Static Footer Fallback Markup
         *  -----------------------------
         */
        public static function sdweddingdirectory_static_footer_markup(){

            ?>
            <div class="row g-4 sdweddingdirectory-static-footer">

                <div class="col-xl-3 col-lg-4 col-md-6 col-12 sdweddingdirectory-footer-block">

                    <div class="footer-widget sdweddingdirectory-footer-brand">

                        <h3 class="widget-title sdweddingdirectory-footer-brand-title">
                            <a class="sdweddingdirectory-footer-brand-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                                <img
                                    class="sdweddingdirectory-footer-brand-logo"
                                    loading="lazy"
                                    src="<?php echo esc_url( get_theme_file_uri( defined( 'SDW_LOGO_PATH' ) ? SDW_LOGO_PATH : 'assets/images/logo/logo_dark.svg' ) ); ?>"
                                    alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                                />
                            </a>
                        </h3>

                        <p><?php esc_html_e( "San Diego's wedding directory for venues, vendors, and inspiration.", 'sdweddingdirectory' ); ?></p>

                        <p class="mb-1"><a href="mailto:maildesk@sdweddingdirectory.com">maildesk@sdweddingdirectory.com</a></p>

                        <p class="mb-0"><a href="tel:+16195551212">(619) 555-1212</a></p>

                    </div>

                </div>

                <div class="col-xl-2 col-lg-4 col-md-6 col-12 sdweddingdirectory-footer-block">

                    <div class="footer-widget">

                        <h3 class="widget-title"><?php esc_html_e( 'Venue Types', 'sdweddingdirectory' ); ?></h3>

                        <ul class="widget-venue">
                            <?php
                                foreach( [
                                    'outdoor-weddings'        => esc_attr__( 'Outdoor Weddings', 'sdweddingdirectory' ),
                                    'beach-weddings'          => esc_attr__( 'Beach Weddings', 'sdweddingdirectory' ),
                                    'garden-weddings'         => esc_attr__( 'Garden Weddings', 'sdweddingdirectory' ),
                                    'barns-farms-weddings'    => esc_attr__( 'Barns & Farms', 'sdweddingdirectory' ),
                                ] as $slug => $label ){
                                    ?>
                                    <li><a href="<?php echo esc_url( self:: footer_venue_category_link( $slug ) ); ?>"><?php echo esc_html( $label ); ?></a></li>
                                    <?php
                                }
                            ?>
                            <li><a href="<?php echo esc_url( home_url( '/venues/' ) ); ?>"><?php echo esc_html__( 'See All Venue Types', 'sdweddingdirectory' ); ?></a></li>
                        </ul>

                    </div>

                </div>

                <div class="col-xl-2 col-lg-4 col-md-6 col-12 sdweddingdirectory-footer-block">

                    <div class="footer-widget">

                        <h3 class="widget-title"><?php esc_html_e( 'Vendor Categories', 'sdweddingdirectory' ); ?></h3>

                        <ul class="widget-venue">
                            <?php
                                foreach( [
                                    'photography'      => esc_attr__( 'Photography', 'sdweddingdirectory' ),
                                    'wedding-planners' => esc_attr__( 'Wedding Planning', 'sdweddingdirectory' ),
                                    'djs'              => esc_attr__( 'DJs', 'sdweddingdirectory' ),
                                    'catering'         => esc_attr__( 'Catering', 'sdweddingdirectory' ),
                                ] as $slug => $label ){
                                    ?>
                                    <li><a href="<?php echo esc_url( self:: footer_vendor_category_link( $slug ) ); ?>"><?php echo esc_html( $label ); ?></a></li>
                                    <?php
                                }
                            ?>
                            <li><a href="<?php echo esc_url( home_url( '/vendors/' ) ); ?>"><?php echo esc_html__( 'See All Vendor Categories', 'sdweddingdirectory' ); ?></a></li>
                        </ul>

                    </div>

                </div>

                <div class="col-xl-2 col-lg-4 col-md-6 col-12 sdweddingdirectory-footer-block">

                    <div class="footer-widget">

                        <h3 class="widget-title"><?php esc_html_e( 'Locations', 'sdweddingdirectory' ); ?></h3>

                        <ul class="widget-venue">
                            <?php
                                foreach( [
                                    'san-diego' => esc_attr__( 'San Diego', 'sdweddingdirectory' ),
                                    'carlsbad'  => esc_attr__( 'Carlsbad', 'sdweddingdirectory' ),
                                    'la-mesa'   => esc_attr__( 'La Mesa', 'sdweddingdirectory' ),
                                    'oceanside' => esc_attr__( 'Oceanside', 'sdweddingdirectory' ),
                                ] as $slug => $label ){
                                    ?>
                                    <li><a href="<?php echo esc_url( self:: footer_location_link( $slug ) ); ?>"><?php echo esc_html( $label ); ?></a></li>
                                    <?php
                                }
                            ?>
                            <li><a href="<?php echo esc_url( home_url( '/venues/' ) ); ?>"><?php echo esc_html__( 'See All Cities', 'sdweddingdirectory' ); ?></a></li>
                        </ul>

                    </div>

                </div>

                <div class="col-xl-3 col-lg-8 col-md-12 col-12 sdweddingdirectory-footer-block">

                    <div class="footer-widget">

                        <h3 class="widget-title"><?php esc_html_e( 'Newsletter', 'sdweddingdirectory' ); ?></h3>

                        <p><?php esc_html_e( 'Subscribe to our newsletter to receive exclusive offers and wedding tips.', 'sdweddingdirectory' ); ?></p>

                        <form class="sdweddingdirectory-footer-newsletter" action="javascript:;" method="post" onsubmit="return false;">

                            <label class="screen-reader-text" for="sdweddingdirectory-footer-email"><?php esc_html_e( 'Enter Email Address', 'sdweddingdirectory' ); ?></label>

                            <input id="sdweddingdirectory-footer-email" type="email" placeholder="<?php esc_attr_e( 'Enter Email Address', 'sdweddingdirectory' ); ?>" />

                        </form>

                    </div>

                </div>

            </div>
            <?php
        }

        /**
         *  Venue Type Footer Link
         *  ----------------------
         */
        public static function footer_venue_category_link( $slug = '' ){

            if( empty( $slug ) ){

                return home_url( '/venues/' );
            }

            $term = get_term_by( 'slug', sanitize_title( $slug ), sanitize_key( 'venue-type' ) );

            if( ! empty( $term ) && ! is_wp_error( $term ) ){

                return add_query_arg( 'cat_id', absint( $term->term_id ), home_url( '/venues/' ) );
            }

            return home_url( '/venues/' );
        }

        /**
         *  Vendor Category Footer Link
         *  ---------------------------
         */
        public static function footer_vendor_category_link( $slug = '' ){

            if( empty( $slug ) ){

                return home_url( '/vendors/' );
            }

            $term = get_term_by( 'slug', sanitize_title( $slug ), sanitize_key( 'vendor-category' ) );

            if( ! empty( $term ) && ! is_wp_error( $term ) ){

                $term_link = get_term_link( $term, sanitize_key( 'vendor-category' ) );

                if( ! is_wp_error( $term_link ) ){

                    return $term_link;
                }
            }

            return home_url( '/vendors/' );
        }

        /**
         *  Location Footer Link
         *  --------------------
         */
        public static function footer_location_link( $slug = '' ){

            if( empty( $slug ) ){

                return home_url( '/venues/' );
            }

            return add_query_arg( 'location', sanitize_title( $slug ), home_url( '/venues/' ) );
        }

        /**
         *  Load Widget in Footer
         *  ---------------------
         */
        public static function widget_load( $grid = '4' , $widget_id = '' ){

            /**
             *  Footer Widget Class
             *  -------------------
             */
            if( $grid == absint( '12' ) ){

                print '<div class="col-lg-12 col-md-12 col-12 sdweddingdirectory-footer-block">';

            }elseif( $grid == absint( '6' ) ){

                print '<div class="col-lg-6 col-md-6 col-12 sdweddingdirectory-footer-block">';

            }elseif( $grid == absint( '4' ) ){

                print '<div class="col-lg-4 col-md-4 col-sm-6 col-12 sdweddingdirectory-footer-block">';

            }elseif( $grid == absint( '3' ) ){

                print '<div class="col-xl-3 col-lg-4 col-md-6 col-12 sdweddingdirectory-footer-block">';

            }elseif( $grid == absint( '2' ) ){

                print '<div class="col-lg-2 col-md-2 col-sm-6 col-12 sdweddingdirectory-footer-block">';
            }
            
            /**
             *  Load Widget
             *  -----------
             */
            if( parent:: _have_data( $widget_id ) ){

                self:: load_widget( $widget_id );
            }

            print   '</div>';
        }

        /**
         *  Widget ID load
         *  --------------
         */
        public static function load_widget( $widget_id = '' ){

            /**
             *  Load Sidebar Widget
             *  -------------------
             */
            if ( is_active_sidebar( SDWEDDINGDIRECTORY_FOOTER_WIDGET . $widget_id ) ) {

                dynamic_sidebar( SDWEDDINGDIRECTORY_FOOTER_WIDGET . $widget_id );
            }
        }
    }

    /**
     *  SDWeddingDirectory - Footer Object Call
     *  -------------------------------
     */
    SDWeddingDirectory_Footer:: get_instance();
}
