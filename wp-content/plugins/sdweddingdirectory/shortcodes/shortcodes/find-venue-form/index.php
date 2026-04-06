<?php
/**
 *  ----------------------------------------------
 *  SDWeddingDirectory - ShortCode - [ Find Venue Form ]
 *  ----------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Shortcode_Find_Venue_Form' ) && class_exists( 'SDWeddingDirectory_Shortcode' ) ){

    /**
     *  ----------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Find Venue Form ]
     *  ----------------------------------------------
     */
    class SDWeddingDirectory_Shortcode_Find_Venue_Form extends SDWeddingDirectory_Shortcode{

        /**
         *  A reference to an instance of this class
         *  ----------------------------------------
         */
        private static $instance;

        /**
         *  Returns an instance of this class.
         *  ----------------------------------
         */
        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         *  ShortCode Atts
         *  --------------
         */
        public static function default_args(){

            return      [
                            'layout'                        =>      absint( '1' ),

                            'category_placeholder'          =>      esc_attr__( 'Search By Vendor', 'sdweddingdirectory-shortcodes' ),

                            'location_placeholder'          =>      esc_attr__( 'Location', 'sdweddingdirectory-shortcodes' ),

                            'search_button_text'            =>      esc_attr__( 'Search Now', 'sdweddingdirectory-shortcodes' )
                        ];
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  1. Find Venue Form Start
             *  --------------------------
             */
            add_shortcode( 'sdweddingdirectory_find_venue_form', [ $this, 'sdweddingdirectory_find_venue_form' ] );

            /**
             *  2. SDWeddingDirectory ShortCode Filter
             *  ------------------------------
             */
            add_filter( 'sdweddingdirectory_shortcode_list', function( $args = [] ){

                return  array_merge( $args, array(

                            'sdweddingdirectory_find_venue_form'  =>

                                sprintf( '[sdweddingdirectory_find_venue_form %1$s][/sdweddingdirectory_find_venue_form]', 

                                    /**
                                     *  1. Default Form Attribute
                                     *  -------------------------
                                     */
                                    parent:: _shortcode_atts( self:: default_args() )
                                )
                            )
                        );
            } );
        }

        /**
         *  Venue Form Start
         *  ------------------
         */
        public static function sdweddingdirectory_find_venue_form( $atts, $content = '' ){

            /**
             *  Extract Attr Data
             *  -----------------
             */
            extract( shortcode_atts( self:: default_args(), $atts, esc_attr( __FUNCTION__ ) ) );

            /**
             *  Random ID
             *  ---------
             */
            $parent_id      =       esc_attr( parent:: _rand() );

            /**
             *  Layout 1 / 2
             *  ------------
             */
            if( $layout == absint( '1' ) || $layout == absint( '2' ) ){

                /**
                 *  Home + Venues + Vendors: Dual mode search (Venue + Vendor)
                 *  -----------------------------------------------------------
                 */
                if( $layout == absint( '2' ) && ( is_front_page() || is_page( 'venues' ) || is_page( 'vendors' ) ) ){

                    $venue_action   =   home_url( '/venues/' );

                    $vendor_action  =   home_url( '/vendors/' );

                    $default_mode   =   is_page( 'vendors' )

                                        ?   esc_attr( 'vendors' )

                                        :   esc_attr( 'venues' );

                    $default_action =   $default_mode == esc_attr( 'vendors' )

                                        ?   $vendor_action

                                        :   $venue_action;

                    $venue_checked  =   $default_mode == esc_attr( 'venues' )

                                        ?   'checked="checked"'

                                        :   '';

                    $vendor_checked =   $default_mode == esc_attr( 'vendors' )

                                        ?   'checked="checked"'

                                        :   '';

                    $venue_style    =   $default_mode == esc_attr( 'vendors' )

                                        ?   ' style="display:none;"'

                                        :   '';

                    $vendor_style   =   $default_mode == esc_attr( 'vendors' )

                                        ?   ''

                                        :   ' style="display:none;"';

                    return      sprintf(   '<form class="sdweddingdirectory-result-page" action="%1$s" data-venue-action="%2$s" data-vendor-action="%3$s">

                                                <div class="sd-search-toggle" id="sd-search-toggle">
                                                    <span class="sd-search-toggle__label">%14$s</span>
                                                    <label class="sd-search-toggle__option">
                                                        <input type="radio" name="sd_search_mode" value="venues" %18$s>
                                                        <span>%15$s</span>
                                                    </label>
                                                    <label class="sd-search-toggle__option">
                                                        <input type="radio" name="sd_search_mode" value="vendors" %19$s>
                                                        <span>%16$s</span>
                                                    </label>
                                                </div>

                                                <div class="slider-form rounded">

                                                    <div class="row align-items-center %4$s">

                                                        <div class="col-md-10">

                                                            <div class="sd-venue-search-fields"%20$s>

                                                                <div class="row sdweddingdirectory-dropdown-parent %11$s" id="%10$s">

                                                                    %5$s

                                                                    %6$s

                                                                </div>

                                                            </div>

                                                            <div class="sd-vendor-search-fields"%21$s>

                                                                <div class="row sdweddingdirectory-dropdown-parent %11$s" id="%12$s">

                                                                    %7$s

                                                                    %13$s

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="col-md-2">

                                                            %8$s

                                                            %9$s

                                                            %17$s

                                                        </div>

                                                    </div>

                                                </div>

                                            </form>
                                            %22$s',

                                            /**
                                             *  1. Form Action (default venue mode)
                                             *  -----------------------------------
                                             */
                                            esc_url( $default_action ),

                                            /**
                                             *  2. Venue Action
                                             *  ---------------
                                             */
                                            esc_url( $venue_action ),

                                            /**
                                             *  3. Vendor Action
                                             *  ----------------
                                             */
                                            esc_url( $vendor_action ),

                                            /**
                                             *  4. Layout Class
                                             *  ---------------
                                             */
                                            esc_attr( 'form-bg gx-1' ),

                                            /**
                                             *  5. Venue Type Input
                                             *  -------------------
                                             */
                                            apply_filters( 'sdweddingdirectory/input-category', [

                                                'before_input'      =>      sprintf( '<div class="col-12 col-md-6 %1$s">',

                                                                                $layout == absint( '1' ) && is_rtl()

                                                                                ?   sanitize_html_class( 'left-border' )

                                                                                :   ''
                                                                            ),

                                                'after_input'       =>      '</div>',

                                                'placeholder'       =>      esc_attr__( 'Search by type', 'sdweddingdirectory-shortcodes' ),

                                                'post_type'         =>      esc_attr( 'venue' ),

                                                'taxonomy'          =>      esc_attr( 'venue-type' ),

                                                'parent_id'         =>      $parent_id
                                            ] ),

                                            /**
                                             *  6. Venue Location Input
                                             *  -----------------------
                                             */
                                            apply_filters( 'sdweddingdirectory/input-location', [

                                                'before_input'      =>      sprintf( '<div class="col-12 col-md-6 %1$s">',

                                                                                $layout == absint( '1' ) && ! is_rtl()

                                                                                ?   sanitize_html_class( 'left-border' )

                                                                                :   ''
                                                                            ),

                                                'after_input'       =>      '</div>',

                                                'placeholder'       =>      esc_attr( $location_placeholder ),

                                                'post_type'         =>      esc_attr( 'venue' ),

                                                'taxonomy'          =>      esc_attr( 'venue-location' ),

                                                'parent_id'         =>      $parent_id
                                            ] ),

                                            /**
                                             *  7. Vendor Category Input
                                             *  ------------------------
                                             */
                                            apply_filters( 'sdweddingdirectory/input-category', [

                                                'before_input'      =>      '<div class="col-12 col-md-6">',

                                                'after_input'       =>      '</div>',

                                                'placeholder'       =>      esc_attr__( 'Search vendor category or name', 'sdweddingdirectory-shortcodes' ),

                                                'post_type'         =>      esc_attr( 'vendor' ),

                                                'taxonomy'          =>      esc_attr( 'vendor-category' ),

                                                'parent_id'         =>      $parent_id . '_vendor'
                                            ] ),

                                            /**
                                             *  8. Submit Button
                                             *  ----------------
                                             */
                                            sprintf(   '<div class="d-grid">

                                                                <button type="submit" class="btn btn-default text-nowrap btn-block">

                                                                %1$s

                                                            </button>

                                                        </div>',

                                                        !   empty( $search_button_text )

                                                        ?   esc_attr( $search_button_text )

                                                        :   esc_attr__( 'Search Now', 'sdweddingdirectory-shortcodes' )
                                            ),

                                            /**
                                             *  9. Hidden Query Fields
                                             *  ----------------------
                                             */
                                            apply_filters( 'sdweddingdirectory/find-venue/hidden-input', [

                                                'id'        =>      $parent_id
                                            ] ),

                                            /**
                                             *  10. Venue Parent ID
                                             *  -------------------
                                             */
                                            esc_attr( $parent_id ),

                                            /**
                                             *  11. Venue Row Layout Class
                                             *  --------------------------
                                             */
                                            esc_attr( 'gx-1' ),

                                            /**
                                             *  12. Vendor Parent ID
                                             *  --------------------
                                             */
                                            esc_attr( $parent_id . '_vendor' ),

                                            /**
                                             *  13. Vendor Location Input
                                             *  -------------------------
                                             */
                                            '',

                                            /**
                                             *  14. Toggle Label
                                             *  ----------------
                                             */
                                            esc_attr__( 'Choose what you want to search for:', 'sdweddingdirectory-shortcodes' ),

                                            /**
                                             *  15. Toggle Option
                                             *  -----------------
                                             */
                                            esc_attr__( 'Venues', 'sdweddingdirectory-shortcodes' ),

                                            /**
                                             *  16. Toggle Option
                                             *  -----------------
                                             */
                                            esc_attr__( 'Vendors', 'sdweddingdirectory-shortcodes' ),

                                            /**
                                             *  17. Vendor Hidden Query Fields
                                             *  -------------------------------
                                             */
                                            apply_filters( 'sdweddingdirectory/find-venue/hidden-input', [

                                                'id'        =>      $parent_id . '_vendor'
                                            ] ),

                                            /**
                                             *  18. Venue Checked Attribute
                                             *  ----------------------------
                                             */
                                            $venue_checked,

                                            /**
                                             *  19. Vendor Checked Attribute
                                             *  -----------------------------
                                             */
                                            $vendor_checked,

                                            /**
                                             *  20. Venue Fields Style
                                             *  ----------------------
                                             */
                                            $venue_style,

                                            /**
                                             *  21. Vendor Fields Style
                                             *  -----------------------
                                             */
                                            $vendor_style,

                                            /**
                                             *  22. Inline Toggle Fallback
                                             *  --------------------------
                                             */
                                            '<script>(function(){var t=document.getElementById("sd-search-toggle");if(!t){return;}var f=t.closest("form.sdweddingdirectory-result-page");if(!f){return;}var venue=f.querySelector(".sd-venue-search-fields");var vendor=f.querySelector(".sd-vendor-search-fields");var setMode=function(mode){if(mode==="vendors"){if(venue){venue.style.display="none";}if(vendor){vendor.style.display="block";}if(f.dataset.vendorAction){f.setAttribute("action",f.dataset.vendorAction);}}else{if(vendor){vendor.style.display="none";}if(venue){venue.style.display="block";}if(f.dataset.venueAction){f.setAttribute("action",f.dataset.venueAction);}}};t.addEventListener("change",function(e){if(e.target&&e.target.name==="sd_search_mode"){setMode(e.target.value);}});var checked=t.querySelector("input[name=\"sd_search_mode\"]:checked");setMode(checked?checked.value:"venues");})();</script>'
                                );
                }

                /**
                 *  Find Venue Form
                 *  -----------------
                 */
                return      sprintf(   '<form class="sdweddingdirectory-result-page" action="%1$s">

                                            <div class="slider-form rounded">

                                                <div class="row align-items-center %2$s">

                                                    <div class="col-md-10">

                                                        <div class="row sdweddingdirectory-dropdown-parent %8$s" id="%7$s">
                                                            
                                                            %3$s    <!-- Select Category -->

                                                            %4$s    <!-- Select Location -->

                                                        </div>

                                                    </div>

                                                    <div class="col-md-2">

                                                        %5$s    <!-- Submit Button -->

                                                        %6$s    <!-- Input Hidden Field -->

                                                    </div>

                                                </div>

                                            </div>

                                        </form>',

                                        /**
                                         *  1. --  Search Template ( Form Action )
                                         *  --------------------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/find-venue-page', [] ),

                                        /**
                                         *  2. - Layout
                                         *  -----------
                                         */
                                        $layout == absint( '2' )

                                        ?   esc_attr( 'form-bg gx-1' )

                                        :   sanitize_html_class( 'g-0' ),

                                        /**
                                         *  3. Venue Category
                                         *  -------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/input-category', [

                                            'before_input'      =>      sprintf( '<div class="col-12 col-md-6 %1$s">', 

                                                                            $layout == absint( '1' ) && is_rtl()

                                                                            ?   sanitize_html_class( 'left-border' )

                                                                            :   ''
                                                                        ),

                                            'after_input'       =>      '</div>',

                                            'parent_id'         =>      $parent_id

                                        ] ),

                                        /**
                                         *  4. -- Select Location Location [ Country ]
                                         *  ------------------------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/input-location', [

                                            'before_input'      =>      sprintf( '<div class="col-12 col-md-6 %1$s">', 

                                                                            $layout == absint( '1' ) && ! is_rtl()

                                                                            ?   sanitize_html_class( 'left-border' )

                                                                            :   ''
                                                                        ),

                                            'after_input'       =>      '</div>',

                                            'parent_id'         =>      $parent_id

                                        ] ),

                                        /**
                                         *  5. Submit Button
                                         *  ----------------
                                         */
                                        sprintf(   '<div class="d-grid">

                                                            <button type="submit" class="btn btn-default text-nowrap btn-block"> 

                                                            %1$s 

                                                        </button>

                                                    </div>',

                                                    /**
                                                     *  1. Have Button Text ?
                                                     *  ---------------------
                                                     */
                                                    !   empty( $search_button_text )

                                                    ?   esc_attr( $search_button_text )

                                                    :   esc_attr__( 'Search Now', 'sdweddingdirectory-shortcodes' )
                                        ),

                                        /**
                                         *  6. Have Hidded Input ?
                                         *  ----------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/find-venue/hidden-input', [

                                            'id'        =>      $parent_id

                                        ] ),

                                        /**
                                         *  7. Parent ID
                                         *  ------------
                                         */
                                        esc_attr( $parent_id ),

                                        /**
                                         *  8. Home page 2 Layout
                                         *  ---------------------
                                         */
                                        $layout == absint( '2' )

                                        ?   esc_attr( 'gx-1' )

                                        :   ''
                            );
            }

            /**
             *  Layout 3
             *  --------
             */
            elseif( $layout == absint( '3' ) ){

                /**
                 *  Find Venue Form
                 *  -----------------
                 */
                return      sprintf(   '<form class="sdweddingdirectory-result-page" action="%1$s">

                                            <div class="row sdweddingdirectory-dropdown-parent form-layout-3" id="%2$s">

                                                <div class="col-md-12">%3$s</div>

                                                <div class="col-md-12">%4$s</div>

                                                <div class="col-md-12">%5$s %6$s</div>

                                            </div>

                                        </form>',

                                        /**
                                         *  1. --  Search Template ( Form Action )
                                         *  --------------------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/find-venue-page', [] ),

                                        /**
                                         *  2. - Parent ID
                                         *  --------------
                                         */
                                        esc_attr( $parent_id ),

                                        /**
                                         *  3. Venue Category
                                         *  -------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/input-category', [

                                            'before_input'      =>      '<div class="col-12">',

                                            'after_input'       =>      '</div>',

                                            'parent_id'         =>      $parent_id,

                                            'input_class'       =>      'rounded border'

                                        ] ),

                                        /**
                                         *  4. -- Select Location Location [ Country ]
                                         *  ------------------------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/input-location', [

                                            'before_input'      =>      '<div class="col-12">',

                                            'after_input'       =>      '</div>',

                                            'parent_id'         =>      $parent_id,

                                            'input_class'       =>      'rounded border'

                                        ] ),

                                        /**
                                         *  5. Submit Button
                                         *  ----------------
                                         */
                                        sprintf(   '<div class="d-grid">

                                                            <button type="submit" class="btn btn-default text-nowrap btn-block"> 

                                                            %1$s 

                                                        </button>

                                                    </div>',

                                                    /**
                                                     *  1. Have Button Text ?
                                                     *  ---------------------
                                                     */
                                                    !   empty( $search_button_text )

                                                    ?   esc_attr( $search_button_text )

                                                    :   esc_attr__( 'Search Now', 'sdweddingdirectory-shortcodes' )
                                        ),

                                        /**
                                         *  6. Have Hidded Input ?
                                         *  ----------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/find-venue/hidden-input', [

                                            'id'        =>      $parent_id

                                        ] )
                            );
            }
        }

        /**
         *  Page Builder : Args
         *  -------------------
         */
        public static function page_builder( $args = [] ){

            /** 
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Setting with Merge Empty Fields
                 *  ---------------------------------------
                 */
                extract( wp_parse_args( $args, self:: default_args() ) );

                /**
                 *  Return Button HTML
                 *  ------------------
                 */
                return  do_shortcode( sprintf(

                            '[sdweddingdirectory_find_venue_form 

                                layout="%1$s" 

                                category_placeholder="%2$s" 

                                location_placeholder="%3$s" 

                                search_button_text="%4$s"][/sdweddingdirectory_find_venue_form]',

                            /**
                             *  1. Layout
                             *  ---------
                             */
                            absint( $layout ),

                            /**
                             *  2. Category Placeholder
                             *  -----------------------
                             */
                            esc_attr( $category_placeholder ),

                            /**
                             *  3. Location Placeholder
                             *  -----------------------
                             */
                            esc_attr( $location_placeholder ),

                            /**
                             *  4. Search Button
                             *  ----------------
                             */
                            esc_attr( $search_button_text )

                        ) );
            }
        }

        /**
         *  Load Script
         *  -----------
         */
        public static function load_script(){

            ?>
            <script>

                (function($) {

                    "use strict";

                    /**
                     *  Object Call
                     *  -----------
                     */
                    $(document).ready( function(){

                        /**
                         *  Find Post Dropdown Object Available ?
                         *  -------------------------------------
                         */
                        if( typeof SDWeddingDirectory_Find_Post_Dropdown === 'object' ){

                            /**
                             *  Load Owl Carouse : Slider Script
                             *  --------------------------------
                             */
                            SDWeddingDirectory_Find_Post_Dropdown.init();
                        }

                    } );

                })(jQuery);

            </script>
            <?php
        }
    }

    /**
     *  ----------------------------------------------
     *  SDWeddingDirectory - ShortCode - [ Find Venue Form ]
     *  ----------------------------------------------
     */
    SDWeddingDirectory_Shortcode_Find_Venue_Form::get_instance();
}
