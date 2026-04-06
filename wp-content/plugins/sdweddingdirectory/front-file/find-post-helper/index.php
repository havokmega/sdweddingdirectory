<?php
/**
 *  SDWeddingDirectory - Find Post Helper
 *  -----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Find_Post_Helper' ) && class_exists( 'SDWeddingDirectory_Front_End_Modules' ) ){

    /**
     *  SDWeddingDirectory - Find Post Helper
     *  -----------------------------
     */
    class SDWeddingDirectory_Find_Post_Helper extends SDWeddingDirectory_Front_End_Modules {

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
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }
        }

        /**
         *  Pagination Helper
         *  =================
         */
        public static function create_pagination( $total_element,$total_limit,$active_page  ){

            /**
             *  Pagination HTML
             *  ---------------
             */
            $pagination_html    =   '';
    
            /**
             *  How many adjacent pages should be shown on each side?
             *  -----------------------------------------------------
             */
            $adjacents          =   absint( '2' );
    
            /**
             *  Total Pages
             *  -----------
             */
            $total_pages = $total_element;
    
            /**
             *  how many items to show per page
             *  -------------------------------
             */
            $limit = $total_limit;

            /**
             *  Active Page Now
             *  ---------------
             */
            $page = $active_page;

            /**
             *  Have Active Page ?
             *  ------------------
             *  first item to display on this page
             *  ----------------------------------
             *  if no page var is given, set start to 0
             *  ---------------------------------------
             */
            if( $page ) {

                $start  =   ($page - 1) * $limit;

            }else{

                $start  =   absint( '0' );
            }
                
            /**
             *  Setup page vars for display
             *  ---------------------------
             *  if no page var is given, default to 1
             *  -------------------------------------
             */
            if ( $page == 0 ){

                $page = 1;
            }

            /**
             *  previous page is page - 1
             *  -------------------------
             */
            $prev = $page - 1;

            /**
             *  next page is page + 1
             *  ---------------------
             */
            $next = $page + 1;

            /**
             *  lastpage is = total pages / items per page, rounded up
             *  ------------------------------------------------------
             */
            $lastpage = ceil($total_pages/$limit);

            /**
             *  last page minus 1
             *  -----------------
             */
            $lpm1 = $lastpage - 1;
                
            /**
             *  Now we apply our rules and draw the pagination object 
             *  -----------------------------------------------------
             *  We're actually saving the code to a variable in case we want to draw it more than once
             *  --------------------------------------------------------------------------------------
             */
            $pagination     =   '';

            /**
             *  Make sure last page have at lest 1
             *  ----------------------------------
             */
            if($lastpage > 1){   
                
                /**
                 *  previous button
                 *  ---------------
                 */
                if ($page > 1) {

                    $pagination     .=  sprintf(    '<li class="page-item p-0 m-0">

                                                        <a href="javascript:" data-value="%1$s" class="pagination_number page-link">

                                                            <i class="fa fa-angle-left" aria-hidden="true"></i>

                                                        </a>

                                                    </li>',

                                                    /**
                                                     *  1. Pagination Number
                                                     *  --------------------
                                                     */
                                                    absint( $prev )
                                        );
                }else{

                    /**
                     *  I will : '<li><span class=\"disabled\"><i class="fa fa-arrow-left"></i></span></li>'
                     *  ------------------------------------------------------------------------------------
                     */
                    $pagination     .=  '';   
                }

                /**
                 *  Pages
                 *  -----
                 *  not enough pages to bother breaking it up
                 *  -----------------------------------------
                 */
                if ( $lastpage < absint( '7' ) + ( $adjacents * absint( '2' ) ) ){   

                    /**
                     *  Create Loop to load pagination
                     *  ------------------------------
                     */
                    for ( $counter = absint( '1' ); $counter <= $lastpage; $counter++ ){

                        if ( $counter == $page ){

                            $pagination     .=  self:: pagination_active( absint( $counter ) );

                        }else{

                            $pagination     .=  self:: pagination_number( absint( $counter ) );
                        }
                    }
                }

                /**
                 *  If Last page less then 5 ?
                 *  --------------------------
                 *  enough pages to hide some
                 *  -------------------------
                 */
                elseif( $lastpage > absint( '5' ) + ( $adjacents * absint( '2' ) ) ) {

                    /**
                     *  close to beginning; only hide later pages
                     *  -----------------------------------------
                     */
                    if( $page < absint( '1' ) + ( $adjacents * absint( '2' ) ) ) {

                        for ( $counter = absint( '1' ); $counter < absint( '4' ) + ($adjacents * absint( '2' ) ); $counter++ ){

                            if ( $counter == $page ){

                                $pagination     .=  self:: pagination_active( absint( $counter ) );

                            }else{

                                $pagination     .=  self:: pagination_number( absint( $counter ) );
                            }
                        }

                        $pagination     .=  self:: pagination_disable();

                        $pagination     .=  self:: pagination_number( absint( $lpm1 ) );

                        $pagination     .=  self:: pagination_number( absint( $lastpage ) );
                    }

                    /**
                     *  in middle; hide some front and some back
                     *  ----------------------------------------
                     */
                    elseif( $lastpage - ($adjacents * absint( '2' ) ) > $page && $page > ($adjacents * absint( '2' ) ) ) {

                        $pagination     .=  self:: pagination_number( absint( '1' ) );

                        $pagination     .=  self:: pagination_number( absint( '2' ) );

                        $pagination     .=  self:: pagination_disable();


                        for ( $counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++ ){

                            if ( $counter == $page ){

                                $pagination     .=  self:: pagination_active( absint( $counter ) );

                            }else{  

                                $pagination     .=  self:: pagination_number( absint( $counter ) );
                            }
                        }

                        $pagination     .=  self:: pagination_disable();

                        $pagination     .=  self:: pagination_number( absint( $lpm1 ) );

                        $pagination     .=  self:: pagination_number( absint( $lastpage ) );

                    }else{

                        /**
                         *  close to end; only hide early pages
                         *  -----------------------------------
                         */
                        $pagination     .=  self:: pagination_number( absint( '1' ) );

                        $pagination     .=  self:: pagination_number( absint( '2' ) );

                        $pagination     .=  self:: pagination_disable();

                        for ($counter = $lastpage - ( absint( '2' ) + ( $adjacents * absint( '2' ) ) ); $counter <= $lastpage; $counter++ ){

                            if ( $counter == $page ){

                                $pagination     .=  self:: pagination_active( absint( $counter ) );

                            }else{

                                $pagination     .=  self:: pagination_number( absint( $counter ) );
                            }
                        }
                    }
                }
                
                /**
                 *  Next button
                 *  -----------
                 */
                if( $page < $counter - 1 ){

                    $pagination     .=  sprintf(    '<li class="page-item p-0 m-0">

                                                        <a href="javascript:" data-value="%1$s" class="pagination_number page-link">

                                                            <i class="fa fa-angle-right" aria-hidden="true"></i>

                                                        </a>

                                                    </li>',

                                                    /**
                                                     *  1. Pagination Number
                                                     *  --------------------
                                                     */
                                                    absint( $next )
                                        );
                }else{

                    /**
                     *  I will : '<li><span class="disabled"><i class="fa fa-arrow-right"></i></span></li>'
                     *  -----------------------------------------------------------------------------------
                     */
                    $pagination     .=  '';
                }
                
                $pagination_html    =   sprintf(   '<div class="col-12 text-center">

                                                        <div class="theme-pagination mb-xl-0 mb-lg-0">

                                                            <ul class="d-block pagination sdweddingdirectory_venue_pagination_number">%1$s</ul>

                                                        </div>

                                                    </div>',

                                                    /**
                                                     *  1. Pagination List
                                                     *  ------------------
                                                     */
                                                    $pagination
                                        );
            }

            /**
             *  Return Pagination
             *  -----------------
             */
            return $pagination_html;
        }

        /**
         *  Pagination Activated
         *  ====================
         */
        public static function pagination_active( $number ){

            return  sprintf(    '<li class="page-item p-0 m-0 active">

                                    <a href="javascript:" class="page-link">%1$s</a>

                                </li>',

                                /**
                                 *  1. Pagination Number
                                 *  --------------------
                                 */
                                absint( $number )
                    );
        }

        /**
         *  Pagination Disabled
         *  ====================
         */
        public static function pagination_disable(){

            return  sprintf(    '<li class="page-item p-0 m-0 disabled"><a href="javascript:" class="page-link">%1$s</a></li>',

                                /**
                                 *  1. Pagination Number
                                 *  --------------------
                                 */
                                esc_attr( '...' )
                    );
        }

        /**
         *  Pagination Number Value
         *  =======================
         */
        public static function pagination_number( $number ){

            return  sprintf(    '<li class="page-item p-0 m-0">

                                    <a href="javascript:" data-value="%1$s" class="pagination_number page-link">%1$s</a>

                                </li>',

                                /**
                                 *  1. Pagination Number
                                 *  --------------------
                                 */
                                absint( $number )
                    );
        }

        /**
         *  Venue Not Found Error
         *  =======================
         */
        public static function venue_not_found(){

            /**
             *  Venue Not Found
             *  -----------------
             */            
            return      [

                /**
                 *  Venue HTML Data
                 *  -----------------
                 */
                'venue_html_data'     =>  sprintf(   '<div class="venue-not-found"><img src="%1$s" alt="%2$s" class="w-50" /></div>', 

                                                /**
                                                 *  1. Venue Not Found Image ( SVG )
                                                 *  ----------------------------------
                                                 */
                                                esc_url(    plugin_dir_url( __FILE__ )  . 'images/venue_not_found.svg'   ),

                                                /**
                                                 *  2. Venue Not Found : Translation Ready String
                                                 *  -----------------------------------------------
                                                 */
                                                esc_attr__( 'Venue Not Found', 'sdweddingdirectory' )
                                            ),
                /**
                 *  Pagination
                 *  ----------
                 */
                'have_pagination'       =>  '',

                /**
                 *  3. Total Post Found
                 *  -------------------
                 */
                'found_result'          =>  absint( '0' )
            ];
        }

        /**
         *  Get Venue Result Have Data To Show Filter
         *  ===========================================
         */
        public static function sdweddingdirectory_venue_layout( $args = [] ){

            /**
             *  Have Sort By ?
             *  --------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'layout'            =>      absint( '0' ),

                    'handler'           =>      '',

                    'list'              =>      [],

                    'found_result'      =>      absint( '0' )

                ] ) );

                /**
                 *  Venues Search Layout
                 *  --------------------
                 */
                if( $layout == absint( '8' ) ){

                    return sprintf( '<div class="venue-post-wrap">

                                        <div class="sorting">

                                            <div class="row align-items-center">

                                                <div class="col-12 d-flex align-items-center">

                                                    <p id="found_venues" class="mb-0 pb-0 text-uppercase fw-bold">

                                                        <span class="me-1">%1$s</span> %2$s

                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                    </div>',

                                    ! empty( $found_result ) ? absint( $found_result ) : '',

                                    esc_attr__( 'Results', 'sdweddingdirectory' )
                    );
                }

                /**
                 *  List View Args
                 *  --------------
                 */
                $list[]     =   [
                                    'id'        =>      esc_attr( 'list-view' ),

                                    'name'      =>      esc_attr__( 'List', 'sdweddingdirectory' ),

                                    'icon'      =>      '<i class="fa fa-list-ul"></i>'
                                ];

                /**
                 *  Grid View Args
                 *  --------------
                 */
                $list[]     =   [
                                    'id'        =>      esc_attr( 'grid-view' ),

                                    'name'      =>      esc_attr__( 'Grid', 'sdweddingdirectory' ),

                                    'icon'      =>      '<i class="fa fa-th-large"></i>'
                                ];

                /**
                 *  Have list ?
                 *  -----------
                 */
                if( parent:: _is_array( $list ) ){

                    foreach( $list as $key => $value ){

                        /**
                         *  Value
                         *  -----
                         */
                        extract( $value );

                        /**
                         *  Handler
                         *  -------
                         */
                        $handler    .=      sprintf(    '<li class="nav-item">

                                                            <a class="nav-link %1$s switch_layout" data-layout="%5$s" id="%2$s-tab" data-bs-toggle="pill" href="#%2$s" role="tab" aria-controls="%2$s" aria-selected="%3$s">%4$s</a>

                                                        </li>',

                                                        /**
                                                         *  1. Layout One is activate ?
                                                         *  ---------------------------
                                                         */
                                                        $layout == $key     ?   esc_attr( 'active show' )   :   '',
                                                        
                                                        /**
                                                         *  2. Name
                                                         *  -------
                                                         */
                                                        esc_attr( $id ),

                                                        /**
                                                         *  3. Layout One is activate ?
                                                         *  ---------------------------
                                                         */
                                                        $layout == $key     ?   esc_attr( 'true' )  :   esc_attr( 'false' ),

                                                        /**
                                                         *  4. Icon
                                                         *  -------
                                                         */
                                                        $icon,

                                                        /**
                                                         *  5. Layout Number
                                                         *  ----------------
                                                         */
                                                        absint( $key )
                                            );

                    }
                }

                /**
                 *  Return Content
                 *  --------------
                 */
                return 

                sprintf('   <div class="venue-post-wrap">

                                <div class="sorting">

                                    <div class="row align-items-center">

                                        <div class="col-lg-6 col-md-6 col-6 d-flex align-items-center">

                                            <p id="found_venues" class="mb-0 pb-0 text-uppercase fw-bold">

                                                <span class="me-1">%1$s</span> %2$s

                                            </p>

                                        </div>

                                        <div class="col-lg-6 col-md-6 col-6">

                                            <ul class="nav nav-pills theme-tabbing map-tabbing float-end" role="tablist">%3$s</ul>

                                        </div>

                                    </div>

                                </div>

                            </div>',

                            /**
                             *  1. Found Result ?
                             *  -----------------
                             */
                            !   empty( $found_result )

                            ?   absint( $found_result )

                            :   '',

                            /**
                             *  2. Transation Ready String
                             *  --------------------------
                             */
                            esc_attr__( 'Results', 'sdweddingdirectory' ),

                            /**
                             *  3. Layout
                             *  ---------
                             */
                            $handler
                );
            }
        }

        /**
         *  Hidden Input
         *  ============
         */
        public static function venue_filter_hidden_input(){

            /**
             *  Extract Args
             *  ------------
             */
            extract( [

                'echo'              =>      true,

                'collection'        =>      '',

                'handler'           =>      apply_filters( 'sdweddingdirectory/find-venue/hidden-inputs', [

                                                'paged'                 =>      absint( '1' ),

                                            ] )
            ] );

            /**
             *  Is Term Page ?
             *  --------------
             */
            if( is_tax( [ 'venue-location', 'venue-type' ] ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( (array) get_queried_object() );

                /**
                 *  Return Hidden Fields
                 *  --------------------
                 */
                $handler[ 'term_id' ]       =       absint ( $term_id );
            }

            /**
             *  Hidden Input
             *  ------------
             */
            if( parent:: _is_array( $handler ) ){

                foreach( $handler as $key => $value ){

                    /**
                     *  Collection
                     *  ----------
                     */
                    $collection     .=      sprintf( '<input type="hidden" name="%1$s" value="%2$s" />',

                                                /**
                                                 *  1. Current Venue Layout Showing
                                                 *  ---------------------------------
                                                 */
                                                esc_attr( $key ),

                                                /**
                                                 *  2. Value
                                                 *  --------
                                                 */
                                                esc_attr( $value )
                                            );
                }
            }

            /**
             *  Print
             *  -----
             */
            if( $echo ){

                print       $collection;
            }

            /**
             *  Return
             *  ------
             */
            else{

                return      $collection;
            }
        }
    }

    /**
     *  SDWeddingDirectory - Find Post Helper
     *  -----------------------------
     */
    SDWeddingDirectory_Find_Post_Helper::get_instance();
}
