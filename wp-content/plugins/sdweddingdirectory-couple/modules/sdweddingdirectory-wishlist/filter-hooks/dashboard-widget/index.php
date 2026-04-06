<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_WishList_Overview_Left_Side_Filters' ) && class_exists( 'SDWeddingDirectory_WishList_Filters' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_WishList_Overview_Left_Side_Filters extends SDWeddingDirectory_WishList_Filters{

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
             *  2. Dashboard Sidebar Widget
             *  ---------------------------
             */
            add_action( 'sdweddingdirectory/couple/dashboard/widget/left-side', [ $this, 'find_vendor_widget' ], absint( '10' ) );
        }

        /**
         *  3. Find Vendor Full Width Widget
         *  --------------------------------
         */
        public static function find_vendor_widget(){

            ?>
            <div class="card-shadow">

                <div class="card-shadow-header">
                    
                    <div class="dashboard-head">
                        <?php

                            printf(

                                '<h3><span>%1$s of %2$s %3$s </span> %4$s</h3>

                                <div class="links"><a href="%5$s">%6$s <i class="fa fa-angle-right"></i></a></div>',

                                /**
                                 *  1. Couple Hire Vendors ( Count )
                                 *  --------------------------------
                                 */
                                absint( parent:: count_hire_category_vendor() ),

                                /**
                                 *  2. SDWeddingDirectory - have number of venue category
                                 *  -----------------------------------------------
                                 */
                                count( 

                                    (array) 

                                    SDWeddingDirectory_Taxonomy:: get_taxonomy_depth(

                                          /**
                                           *  1. Venue Category Slug
                                           *  ------------------------
                                           */
                                          esc_attr( 'venue-type' ),

                                          /**
                                           *  2. Parent
                                           *  ---------
                                           */
                                          absint( '1' )
                                    )
                                ),

                                /**
                                 *  3. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'categories hired', 'sdweddingdirectory-wishlist' ),

                                /**
                                 *  4. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Your vendor team', 'sdweddingdirectory-wishlist' ),

                                /**
                                 *  5. Couple Wishlist Page Link
                                 *  ----------------------------
                                 */
                                apply_filters( 'sdweddingdirectory/couple-menu/page-link', esc_attr( 'vendor-manager' ) ),

                                /**
                                 *  6. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'View all my vendors', 'sdweddingdirectory-wishlist' )
                                
                            );
                        ?>
                    </div>

                </div>

                <div class="bg-light">

                    <div class="card-shadow-body">
                        <?php

                        /**
                         *  Random ID
                         *  ---------
                         */
                        $parent_id      =       esc_attr( parent:: _rand() );

                        /**
                         *  Find Vendor
                         *  -----------
                         */
                        printf(   '<form class="sdweddingdirectory-result-page" method="post" action="%1$s">

                                        <div class="row align-items-center sdweddingdirectory-dropdown-parent" id="%2$s">

                                            %3$s    <!-- Select Category -->

                                            %4$s    <!-- Select Country Location -->

                                            %5$s    <!-- Search Button -->

                                            %6$s    <!-- hidden inputs -->

                                        </div>

                                    </form>',

                                    /**
                                     *  1. Form Action
                                     *  --------------
                                     */
                                    apply_filters( 'sdweddingdirectory/template/link', esc_attr( 'search-venue.php' ) ),

                                    /**
                                     *  2. Random ID
                                     *  ------------
                                     */
                                    esc_attr( $parent_id ),

                                    /**
                                     *  3. Venue Category
                                     *  -------------------
                                     */
                                    apply_filters( 'sdweddingdirectory/input-category', [

                                        'before_input'      =>      '<div class="col-md mb-3 mb-md-0">',

                                        'after_input'       =>      '</div>',

                                        'parent_id'         =>      $parent_id

                                    ] ),

                                    /**
                                     *  4. Venue Location
                                     *  -------------------
                                     */
                                    apply_filters( 'sdweddingdirectory/input-location', [

                                        'before_input'      =>      '<div class="col-md mb-3 mb-md-0">',

                                        'after_input'       =>      '</div>',

                                        'parent_id'         =>      $parent_id

                                    ] ),

                                    /**
                                     *  5. Submit Button
                                     *  ----------------
                                     */
                                    sprintf(   '<div class="col-md-auto">

                                                    <button type="submit" id="seach_result_btn" class="btn btn-default">%1$s</button>

                                                </div>',

                                                // 1
                                                esc_attr__( 'Search', 'sdweddingdirectory-wishlist' )
                                    ),

                                    /**
                                     *  6. Have Hidded Input ?
                                     *  ----------------------
                                     */
                                    apply_filters( 'sdweddingdirectory/find-venue/hidden-input', [

                                        'id'        =>      $parent_id

                                    ] )
                        );

                        ?>
                    </div>
                </div>

                <div class="card-shadow-body pb-2">
                    <?php

                        /**
                         *  Wishlist : Layout [ Box ]
                         *  -------------------------
                         */
                        do_action( 'sdweddingdirectory/wishlist/category-widget', array(

                            'row_class'  => esc_attr( 'row row-cols-1 row-cols-md-3 row-cols-sm-2' )

                        ) );
                    ?>
                </div>
                
            </div>
            <?php
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_WishList_Overview_Left_Side_Filters:: get_instance();
}