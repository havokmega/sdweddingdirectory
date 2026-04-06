<?php
/**
 *  Option Tree Plugin
 *  ------------------
 *
 *  @link( https://github.com/valendesigns/option-tree )
 *
 *  @link( https://github.com/valendesigns/option-tree-theme/blob/master/inc/meta-boxes.php, link)
 */

if( ! class_exists( 'SDWeddingDirectory_Vendor_Venue_Meta' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory - Page Meta
     *  ----------------------
     */
    class SDWeddingDirectory_Vendor_Venue_Meta extends SDWeddingDirectory_Config{

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
             *  1. Venue Post Meta in Right Side Bar
             *  --------------------------------------
             */
            add_filter( 'sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_featured_venue_setting' ], absint('20') );

            /**
             *  2. Venue Post Meta in Left Side Bar
             *  -------------------------------------
             */
            add_filter( 'sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_venue_setting' ], absint('10') );

            /**
             *  3. Venue Page View
             *  --------------------
             */
            add_filter( 'sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_venue_page_view' ], absint('20') );

            /**
             *  2. Venue Post Menu
             *  --------------------
             */
            add_filter( 'sdweddingdirectory/meta', [ $this, 'menu_meta' ], absint('20') );

            /**
             *  3. Venue Post Facilities
             *  --------------------------
             */
            add_filter( 'sdweddingdirectory/meta', [ $this, 'facilities' ], absint('30') );

            /**
             *  1. Vendor Post Metabox Setting for SDWeddingDirectory!
             *  ----------------------------------------------
             */
            add_filter( 'sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_vendor_team_meta' ] );

            /**
             *  Category Wise Showing the Term Data
             *  -----------------------------------
             */
            add_filter( 'sdweddingdirectory/meta', [ $this, 'category_wise_term_meta' ], absint('90') );

            /**
             *  NOTE USED
             *  ---------
             */
            // add_filter( 'sdweddingdirectory/meta', [ $this, 'sdweddingdirectory_venue_filters_setting' ], absint('40') );
        }

        /**
         *  1. Venue Post Meta in Right Side Bar
         *  --------------------------------------
         */
        public static function sdweddingdirectory_featured_venue_setting( $args = [] ) {

            $new_args = array(

                'id'        =>  esc_attr( 'sdweddingdirectory_venue_badge_section' ),

                'title'     =>  esc_attr__('Venue Badge ?', 'sdweddingdirectory-venue'),

                'pages'     =>  array( 'venue' ),

                'context'   =>  esc_attr( 'side' ),

                'priority'  =>  esc_attr( 'low' ),

                'fields'    =>  array(

                    array(

                        'id'          =>  esc_attr( 'venue_badge' ),

                        'label'       =>  esc_attr__( 'Venue Badge', 'sdweddingdirectory-venue' ),

                        'std'         =>  '',

                        'type'        =>  esc_attr( 'select' ),

                        'section'     =>  esc_attr( __FUNCTION__ ),

                        'choices'     =>  array(

                            array(

                                'value'       =>  '',

                                'label'       =>  esc_attr__( 'No Badge', 'sdweddingdirectory-venue' ),

                                'src'         =>  ''
                            ),

                            array(

                              'value'       =>  esc_attr( 'spotlight' ),

                              'label'       =>  esc_attr__( 'Spotlight', 'sdweddingdirectory-venue' ),

                              'src'         =>  ''
                            ),

                            array(

                              'value'       =>  esc_attr( 'featured' ),

                              'label'       =>  esc_attr__( 'Featured', 'sdweddingdirectory-venue' ),

                              'src'         =>  ''
                            ),

                            array(

                              'value'       =>  esc_attr( 'professional' ),

                              'label'       =>  esc_attr__( 'Professional', 'sdweddingdirectory-venue' ),

                              'src'         =>  ''

                            ),
                        ),
                    ),
                ),
            );

            return array_merge($args, array($new_args));
        }

        /**
         *  2. Venue Post Meta in Left Side Bar
         *  -------------------------------------
         */
        public static function sdweddingdirectory_venue_setting( $args = [] ) {

            /**
             *  Translation Ready String
             *  ------------------------
             */
            $_string            =       esc_attr__( 'Select Category and update the post to visible the Amenities', 'sdweddingdirectory-venue' );

            $_is_venue_post   =       isset( $_GET[ 'post' ] ) && get_post_type( $_GET[ 'post' ] ) == esc_attr( 'venue' );

            /**
             *  New Data
             *  --------
             */
            $new_args   =   array(

                'id'        => 'sdweddingdirectory-venue-data',
                'title'     => esc_attr__('Venue Facilty', 'sdweddingdirectory-venue'),
                'pages'     => array('venue'),
                'context'   => esc_attr( 'normal' ),
                'priority'  => esc_attr( 'high' ),
                'fields'    => array(

                    array(
                        'label' => esc_attr__('Map Location', 'sdweddingdirectory-venue'),
                        'id'    => esc_attr('sdweddingdirectory_venue_map_location'),
                        'type'  => esc_attr( 'tab' ),
                    ),
                    array(
                        'id'    => esc_attr( 'venue_latitude' ),
                        'label' => esc_attr__('Venue Map Latitude', 'sdweddingdirectory-venue'),
                        'type'  => esc_attr( 'text' ),
                    ),
                    array(
                        'id'    => esc_attr('venue_longitude'),
                        'label' => esc_attr__('Venue Map Longitude', 'sdweddingdirectory-venue'),
                        'type'  => esc_attr( 'text' ),
                    ),
                    array(
                        'id'    => esc_attr('venue_pincode'),
                        'label' => esc_attr__('Venue Pincode', 'sdweddingdirectory-venue'),
                        'type'  => esc_attr( 'text' ),
                    ),
                    array(
                        'label' => esc_attr__('Features', 'sdweddingdirectory-venue'),
                        'id'    => esc_attr('sdweddingdirectory_venue_category_location'),
                        'type'  => esc_attr( 'tab' ),
                    ),
                    array(
                        'id'    => esc_attr('venue_min_price'),
                        'label' => esc_attr__('Venue Min Price', 'sdweddingdirectory-venue'),
                        'type'  => esc_attr( 'text' ),
                    ),
                    array(
                        'id'    => esc_attr('venue_max_price'),
                        'label' => esc_attr__('Venue Max Price', 'sdweddingdirectory-venue'),
                        'type'  => esc_attr( 'text' ),
                    ),
                    array(
                        'id'    => esc_attr('venue_address'),
                        'label' => esc_attr__('Venue Address', 'sdweddingdirectory-venue'),
                        'type'  => esc_attr( 'textarea-simple' ),
                    ),
                    array(
                        'id'    => esc_attr('map_address'),
                        'label' => esc_attr__('Map Address', 'sdweddingdirectory-venue'),
                        'type'  => esc_attr( 'textarea-simple' ),
                    ),

                    array(
                        'id'    => esc_attr('venue_seat_capacity'),
                        'label' => esc_attr__('Seat Capacity', 'sdweddingdirectory-venue'),
                        'desc'  => esc_attr__('For example : your venue is hotel, resort so what is the seat capacity ?', 'sdweddingdirectory-venue'),
                        'type'  => esc_attr( 'text' ),
                    ),

                    array(
                        'label' => esc_attr__('Media & Video', 'sdweddingdirectory-venue'),
                        'id'    => esc_attr( 'sdweddingdirectory_venue_media' ),
                        'type'  => esc_attr( 'tab' ),
                    ),
                    array(
                        'id'    => esc_attr( 'venue_video' ),
                        'label' => esc_attr__( 'Venue Video', 'sdweddingdirectory-venue' ),
                        'type'  => esc_attr( 'text' ),
                    ),
                    array(
                        'id'    => esc_attr('venue_gallery'),
                        'label' => esc_attr__('Upload Gallery', 'sdweddingdirectory-venue'),
                        'desc'  => esc_attr__('If you want to update post with gallery please upload your gallery images here. so on front side we present all images as gallery', 'sdweddingdirectory-venue'),
                        'type' => 'gallery',
                    ),

                    array(
                        'label' => esc_attr__('Have FAQ', 'sdweddingdirectory-venue'),
                        'id'    => esc_attr( 'sdweddingdirectory_venue_faq_tab' ),
                        'type'  => esc_attr( 'tab' ),
                    ),
                        array(
                            'id'        => sanitize_key( 'venue_faq' ),
                            'type'      => esc_attr( 'list-item' ),
                            'operator'  => esc_attr( 'or' ),
                            'choices'   => [],
                            'settings'  => array(

                                array(
                                    'id'        => sanitize_key( 'faq_title' ),
                                    'label'     => esc_attr__('Faq Title', 'sdweddingdirectory-venue'),
                                    'type'      => esc_attr( 'text' ),
                                ),
                                array(
                                    'id'        => sanitize_key( 'faq_description' ),
                                    'label'     => esc_attr__('Faq descripiton', 'sdweddingdirectory-venue'),
                                    'type'      => esc_attr( 'textarea-simple' ),
                                ),
                            ),
                        ),

                    /**
                     *  Prefer Vendors
                     *  --------------
                     */
                    array(

                        'label' =>  esc_attr__( 'Preferred Vendors', 'sdweddingdirectory-venue' ),

                        'id'    =>  esc_attr( 'sdweddingdirectory_preferred_venues_meta' ),

                        'type'  =>  esc_attr( 'tab' ),

                    ),
                        array(

                            'id'        => sanitize_key( 'preferred_venues' ),

                            'label'     => esc_attr__('Preferred Vendors', 'sdweddingdirectory-venue'),

                            'type'      => esc_attr( 'text' ),
                        ),

                    /**
                     *  Working Hours
                     *  -------------
                     */
                    array(

                        'label'         =>      esc_attr__('Working Hours', 'sdweddingdirectory-venue'),

                        'id'            =>      esc_attr('sdweddingdirectory_working_hours'),

                        'type'          =>      esc_attr( 'tab' ),
                    ),

                    /**
                     *  Saturday
                     *  --------
                     */
                    array(

                        'label'         =>      esc_attr__( 'Saturday Enable', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'saturday_enable' ),

                        'type'          =>      esc_attr( 'on-off' ),

                        'std'           =>      esc_attr(  'off' ),
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Saturday 24H Open', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'saturday_open' ),

                        'type'          =>      esc_attr( 'on-off' ),

                        'std'           =>      esc_attr(  'on' ),

                        'condition'     =>      esc_attr( 'saturday_enable:is(on)' )
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Saturday Start Time', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'saturday_start' ),

                        'type'          =>      esc_attr( 'text' ),

                        'condition'     =>      esc_attr( 'saturday_open:is(off),saturday_enable:is(on)' )
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Saturday End Time', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'saturday_close' ),

                        'type'          =>      esc_attr( 'text' ),

                        'condition'     =>      esc_attr( 'saturday_open:is(off),saturday_enable:is(on)' )
                    ),

                    /**
                     *  Sunday
                     *  ------
                     */
                    array(

                        'label'         =>      esc_attr__( 'Sunday Enable', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'sunday_enable' ),

                        'type'          =>      esc_attr( 'on-off' ),

                        'std'           =>      esc_attr(  'off' ),
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Sunday 24H Open', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'sunday_open' ),

                        'type'          =>      esc_attr( 'on-off' ),

                        'std'           =>      esc_attr(  'on' ),

                        'condition'     =>      esc_attr( 'sunday_enable:is(on)' )
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Sunday Start Time', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'sunday_start' ),

                        'type'          =>      esc_attr( 'text' ),

                        'condition'     =>      esc_attr( 'sunday_open:is(off),sunday_enable:is(on)' )
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Sunday End Time', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'sunday_close' ),

                        'type'          =>      esc_attr( 'text' ),

                        'condition'     =>      esc_attr( 'sunday_open:is(off),sunday_enable:is(on)' )
                    ),

                    /**
                     *  Monday
                     *  ------
                     */
                    array(

                        'label'         =>      esc_attr__( 'Monday Enable', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'monday_enable' ),

                        'type'          =>      esc_attr( 'on-off' ),

                        'std'           =>      esc_attr(  'off' ),
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Monday 24H Open', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'monday_open' ),

                        'type'          =>      esc_attr( 'on-off' ),

                        'std'           =>      esc_attr(  'on' ),

                        'condition'     =>      esc_attr( 'monday_enable:is(on)' )
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Monday Start Time', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'monday_start' ),

                        'type'          =>      esc_attr( 'text' ),

                        'condition'     =>      esc_attr( 'monday_open:is(off),monday_enable:is(on)' )
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Monday End Time', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'monday_close' ),

                        'type'          =>      esc_attr( 'text' ),

                        'condition'     =>      esc_attr( 'monday_open:is(off),monday_enable:is(on)' )
                    ),

                    /**
                     *  Tuesday
                     *  -------
                     */
                    array(

                        'label'         =>      esc_attr__( 'Tuesday Enable', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'tuesday_enable' ),

                        'type'          =>      esc_attr( 'on-off' ),

                        'std'           =>      esc_attr(  'off' ),
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Tuesday 24H Open', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'tuesday_open' ),

                        'type'          =>      esc_attr( 'on-off' ),

                        'std'           =>      esc_attr(  'on' ),

                        'condition'     =>      esc_attr( 'tuesday_enable:is(on)' )
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Tuesday Start Time', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'tuesday_start' ),

                        'type'          =>      esc_attr( 'text' ),

                        'condition'     =>      esc_attr( 'tuesday_open:is(off),tuesday_enable:is(on)' )
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Tuesday End Time', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'tuesday_close' ),

                        'type'          =>      esc_attr( 'text' ),

                        'condition'     =>      esc_attr( 'tuesday_open:is(off),tuesday_enable:is(on)' )
                    ),

                    /**
                     *  Wednesday
                     *  ---------
                     */
                    array(

                        'label'         =>      esc_attr__( 'Wednesday Enable', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'wednesday_enable' ),

                        'type'          =>      esc_attr( 'on-off' ),

                        'std'           =>      esc_attr(  'off' ),
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Wednesday 24H Open', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'wednesday_open' ),

                        'type'          =>      esc_attr( 'on-off' ),

                        'std'           =>      esc_attr(  'on' ),

                        'condition'     =>      esc_attr( 'wednesday_enable:is(on)' )
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Wednesday Start Time', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'wednesday_start' ),

                        'type'          =>      esc_attr( 'text' ),

                        'condition'     =>      esc_attr( 'wednesday_open:is(off),wednesday_enable:is(on)' )
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Wednesday End Time', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'wednesday_close' ),

                        'type'          =>      esc_attr( 'text' ),

                        'condition'     =>      esc_attr( 'wednesday_open:is(off),wednesday_enable:is(on)' )
                    ),

                    /**
                     *  Thursday
                     *  --------
                     */
                    array(

                        'label'         =>      esc_attr__( 'Thursday Enable', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'thursday_enable' ),

                        'type'          =>      esc_attr( 'on-off' ),

                        'std'           =>      esc_attr(  'off' ),
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Thursday 24H Open', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'thursday_open' ),

                        'type'          =>      esc_attr( 'on-off' ),

                        'std'           =>      esc_attr(  'on' ),

                        'condition'     =>      esc_attr( 'thursday_enable:is(on)' )
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Thursday Start Time', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'thursday_start' ),

                        'type'          =>      esc_attr( 'text' ),

                        'condition'     =>      esc_attr( 'thursday_open:is(off),thursday_enable:is(on)' )
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Thursday End Time', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'thursday_close' ),

                        'type'          =>      esc_attr( 'text' ),

                        'condition'     =>      esc_attr( 'thursday_open:is(off),thursday_enable:is(on)' )
                    ),

                    /**
                     *  Friday
                     *  ------
                     */
                    array(

                        'label'         =>      esc_attr__( 'Friday Enable', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'friday_enable' ),

                        'type'          =>      esc_attr( 'on-off' ),

                        'std'           =>      esc_attr(  'off' ),
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Friday 24H Open', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'friday_open' ),

                        'type'          =>      esc_attr( 'on-off' ),

                        'std'           =>      esc_attr(  'on' ),

                        'condition'     =>      esc_attr( 'friday_enable:is(on)' )
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Friday Start Time', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'friday_start' ),

                        'type'          =>      esc_attr( 'text' ),

                        'condition'     =>      esc_attr( 'friday_open:is(off),friday_enable:is(on)' )
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Friday End Time', 'sdweddingdirectory-venue' ),

                        'id'            =>      esc_attr( 'friday_close' ),

                        'type'          =>      esc_attr( 'text' ),

                        'condition'     =>      esc_attr( 'friday_open:is(off),friday_enable:is(on)' )
                    ),
                ),
            );

            return array_merge($args, array($new_args));
        }

        /**
         *  Venue Filter Data
         *  -------------------
         */
        public static function sdweddingdirectory_venue_filters_setting( $args = [] ){

            /**
             *  Taxonomy
             *  --------
             */
            $_tax       =       esc_attr( 'venue-type' );

            /**
             *  Find out Post Parent Term ID
             *  ----------------------------
             */
            $cat_id     =   apply_filters( 'sdweddingdirectory/post/term-parent', [

                                'post_id'           =>      isset( $_GET[ 'post' ] ) && $_GET[ 'post' ] !== ''

                                                            ?   absint( $_GET[ 'post' ] )

                                                            :   absint( '0' ),

                                'taxonomy'          =>      $_tax,

                            ] );

            $_filter    =   get_term_meta( absint( $cat_id ), sanitize_key( 'create_filters' ), true );

            $_data      =   [];

            $values     =   [];

            /**
             *  Filter
             *  ------
             */
            if( parent:: _is_array( $_filter ) ){

                foreach( $_filter as $filter_key => $filter_value ){

                    /**
                     *  Extract
                     *  -------
                     */
                    extract( $filter_value );

                    if( parent:: _is_array( $filter_data ) ){

                        $_choice    =   [];

                        $_data[]    =

                        array(

                            'id'          =>    sanitize_title( 'venue-filter-'. $filter_name ),

                            'label'       =>    esc_attr( $filter_name ),

                            'type'        =>    'tab',
                        );

                        foreach( $filter_data as $_key => $_value ){

                            extract( $_value );

                            $_choice[ $_key ]    =   $data_list;
                        }

                        $_data[]    =

                        /**
                         *  Amenities Checked Script
                         *  ------------------------
                         */
                        array(

                            'id'          =>    sanitize_title( $filter_name ),

                            'label'       =>    esc_attr( $filter_name ),

                            'type'        =>    esc_attr( 'checkbox' ),

                            'choices'     =>    apply_filters( 'sdweddingdirectory/ot-tree/options', $_choice )
                        );

                    }
                }
            }

            /**
             *  Make sure data is not empty!
             *  ----------------------------
             */
            if( ! parent:: _is_array( $_data ) ){

                /**
                 *  Tab
                 *  ---
                 */
                $_data[]    =   array(

                                    'id'          =>    esc_attr( 'venue_filter_tab' ),

                                    'label'       =>    esc_attr__( 'Venue Filters', 'sdweddingdirectory-venue' ),

                                    'type'        =>    esc_attr( 'tab' ),
                                );

                $_data[]    =   array(

                                    'label'     =>      esc_attr__( 'Venue Category Filters Data', 'sdweddingdirectory-venue' ),

                                    'id'        =>      esc_attr( 'venue_filter_textblock' ),

                                    'type'      =>      esc_attr( 'textblock-titled' ),

                                    'desc'      =>      sprintf( '%1$s <a href="%3$s" target="_blank">%2$s</a>',

                                                        esc_attr__( 'Make sure your this venue category is saved properly and this category have not empty filter data option', 'sdweddingdirectory-venue' ),

                                                        esc_attr__( 'Edit', 'sdweddingdirectory-venue' ),

                                                        /**
                                                         *  3. Link for edit amenities category
                                                         *  -----------------------------------
                                                         */
                                                        esc_url( 

                                                            get_edit_term_link(

                                                                /**
                                                                 *  1. Term ID
                                                                 *  ----------
                                                                 */
                                                                apply_filters( 'sdweddingdirectory/post/term-parent', [

                                                                    'taxonomy'      =>      sanitize_key( 'venue-type' ),

                                                                    'post_id'       =>      isset( $_GET[ 'post' ] ) && $_GET[ 'post' ] !== ''

                                                                                            ?   absint( $_GET[ 'post' ] )

                                                                                            :   absint( '0' ),

                                                                ] ),

                                                                /**
                                                                 *  Slug
                                                                 *  ----
                                                                 */
                                                                esc_attr( 'venue-type' )          
                                                            )
                                                        )
                                                    ),
                                );
            }

            /**
             *  Meta Section
             *  ------------
             */
            $new_args           =       array(

                'id'            =>      esc_attr( 'sdweddingdirectory_venue_filter_section' ),

                'title'         =>      esc_attr__('Venue Category Filters ?', 'sdweddingdirectory-venue'),

                'pages'         =>      array( 'venue' ),

                'context'       =>      esc_attr( 'normal' ),

                'priority'      =>      esc_attr( 'low' ),

                'fields'        =>      $_data
            );

            /**
             *  Return - Meta
             *  -------------
             */
            return      array_merge( $args, [ $new_args ] );
        }

        /**
         *  3. Venue Page View
         *  --------------------
         */
        public static function sdweddingdirectory_venue_page_view( $args = [] ) {

            /**
             *  Add New Meta
             *  ------------
             */
            $new_args           =       array(

                'id'            =>      esc_attr( 'sdweddingdirectory_venue_page_view_section' ),

                'title'         =>      esc_attr__('Venue Page Visit ?', 'sdweddingdirectory-venue'),

                'pages'         =>      array( 'venue' ),

                'context'       =>      esc_attr( 'side' ),

                'priority'      =>      esc_attr( 'low' ),

                'fields'        =>      array(

                    array(

                        'id'          =>    esc_attr( 'venue_page_view' ),

                        'label'       =>    esc_attr__( 'Venue Page Visit', 'sdweddingdirectory-venue' ),

                        'std'         =>    absint( '0' ),

                        'type'        =>    esc_attr( 'text' ),
                    ),
                ),
            );

            /**
             *  Meta
             *  ----
             */
            return      array_merge( $args, [ $new_args ] );
        }
    
        /**
         *  2. Venue Post Meta
         *  --------------------
         */
        public static function menu_meta( $args = [] ) {

            /**
             *  Meta Setting
             *  ------------
             */
            $new_args           =       array(

                'id'            =>      esc_attr( 'sdweddingdirectory_venue_menu_metabox' ),

                'title'         =>      esc_attr__( 'Venue Menu ?', 'sdweddingdirectory-venue'),

                'pages'         =>      array( 'venue' ),

                'context'       =>      esc_attr( 'normal' ),

                'priority'      =>      esc_attr( 'low' ),

                'fields'        =>      array(

                    array(

                        'id'            =>      sanitize_key( 'venue_menu' ),

                        'type'          =>      esc_attr( 'list-item' ),

                        'choices'       =>      [],

                        'settings'      =>      array(

                            array(

                                'id'        =>  sanitize_key( 'menu_title' ),

                                'label'     =>  esc_attr__('Menu Title', 'sdweddingdirectory-venue'),

                                'type'      =>  esc_attr( 'text' ),
                            ),

                            array(

                                'id'        =>  sanitize_key( 'menu_price' ),

                                'label'     =>  esc_attr__('Menu Price', 'sdweddingdirectory-venue'),

                                'type'      =>  esc_attr( 'text' ),
                            ),

                            array(

                                'id'        =>  esc_attr( 'menu_file' ),

                                'label'     =>  esc_attr__( 'Menu File', 'sdweddingdirectory-venue' ),

                                'type'      =>  esc_attr( 'upload' ),

                                'class'     => 'ot-upload-attachment-id',
                            ),

                        ),
                    ),
                ),
            );

            /**
             *  Merge Metabox
             *  -------------
             */
            return      array_merge( $args, [ $new_args ] );
        }

        /**
         *  2. Venue Facilities
         *  ---------------------
         */
        public static function facilities( $args = [] ) {

            /**
             *  Add Meta Setting
             *  ----------------
             */
            $new_args           =       array(

                'id'            =>      esc_attr( 'sdweddingdirectory_venue_facilities' ),

                'title'         =>      esc_attr__( 'Venue Facilities ?', 'sdweddingdirectory-venue'),

                'pages'         =>      array( 'venue' ),

                'context'       =>      esc_attr( 'normal' ),

                'priority'      =>      esc_attr( 'low' ),

                'fields'        =>      array(

                    array(

                        'id'        =>  sanitize_key( 'venue_facilities' ),

                        'type'      =>  esc_attr( 'list-item' ),

                        'choices'   =>  [],

                        'settings'  =>  array(

                            array(

                                'id'        =>  sanitize_key( 'facilities_cat' ),

                                'label'     =>  esc_attr__('Room Category', 'sdweddingdirectory-venue'),

                                'type'      =>  esc_attr( 'text' ),
                            ),

                            array(

                                'id'        =>  sanitize_key( 'facilities_name' ),

                                'label'     =>  esc_attr__('Facilities Name', 'sdweddingdirectory-venue'),

                                'type'      =>  esc_attr( 'text' ),
                            ),

                            array(

                                'id'        =>  sanitize_key( 'facilities_seating' ),

                                'label'     =>  esc_attr__('Facilities Seating', 'sdweddingdirectory-venue'),

                                'type'      =>  esc_attr( 'text' ),
                            ),

                            array(

                                'id'        =>  sanitize_key( 'facilities_desc' ),

                                'label'     =>  esc_attr__('Facilities descripiton', 'sdweddingdirectory-venue'),

                                'type'      =>  esc_attr( 'textarea-simple' ),
                            ),

                            array(

                                'id'        =>  sanitize_key( 'facilities_gallery' ),

                                'label'     =>  esc_attr__('Facilities Gallery', 'sdweddingdirectory-venue'),

                                'type'      =>  esc_attr( 'gallery' ),
                            ),

                            array(

                                'id'        =>  sanitize_key( 'facilities_price' ),

                                'label'     =>  esc_attr__('Facilities Price', 'sdweddingdirectory-venue'),

                                'type'      =>  esc_attr( 'text' ),
                            ),
                        ),
                    ),
                ),
            );

            /**
             *  Merge Metabox
             *  -------------
             */
            return      array_merge( $args, array( $new_args ) );
        }

        /**
         *  Vendor Profile Information
         *  --------------------------
         */
        public static function sdweddingdirectory_vendor_team_meta( $args = [] ) {

            /**
             *  Add Meta Setting
             *  ----------------
             */
            $new_args           =       array(

                'id'            =>      esc_attr('sdweddingdirectory_meet_the_team_info_meta'),

                'title'         =>      esc_attr__('Meet the Team', 'sdweddingdirectory-venue'),

                'pages'         =>      array( 'venue' ),

                'context'       =>      esc_attr( 'normal' ),

                'priority'      =>      esc_attr( 'high' ),

                'fields'        =>      array(

                    array(

                        'id'        =>  sanitize_key( 'venue_team' ),

                        'type'      =>  esc_attr( 'list-item' ),

                        'choices'   =>  [],

                        'settings'  =>  array(

                            array(

                                'id'        =>  esc_attr( 'first_name' ),

                                'label'     =>  esc_attr__('First Name', 'sdweddingdirectory-venue'),

                                'type'      =>  esc_attr( 'text' ),
                            ),

                            array(

                                'id'        =>  esc_attr( 'last_name' ),

                                'label'     =>  esc_attr__('Last Name', 'sdweddingdirectory-venue'),

                                'type'      =>  esc_attr( 'text' ),
                            ),

                            array(

                                'id'        =>  esc_attr( 'position' ),

                                'label'     =>  esc_attr__('Position', 'sdweddingdirectory-venue'),

                                'type'      =>  esc_attr( 'text' ),
                            ),

                            array(

                                'id'        =>  esc_attr( 'bio' ),

                                'label'     =>  esc_attr__('Bio', 'sdweddingdirectory-venue'),

                                'type'      =>  esc_attr( 'textarea-simple' ),
                            ),

                            array(

                                'id'        =>  esc_attr( 'image' ),

                                'label'     =>  esc_attr__('Image', 'sdweddingdirectory-venue'),

                                'type'      =>  esc_attr( 'upload' ),

                                'class'     =>  esc_attr( 'ot-upload-attachment-id' ),
                            )
                        )
                    )
                ),
            );

            return      array_merge( $args, array( $new_args ) );
        }

        /**
         *  1. Static Meta
         *  --------------
         */
        public static function category_wise_term_meta( $args = [] ){

            global $post, $wp_query, $page;

            /**
             *  Translation Ready String
             *  ------------------------
             */
            $_venue_id    =      isset( $_GET[ 'post' ] ) && get_post_type( $_GET[ 'post' ] ) == esc_attr( 'venue' );

            /**
             *  Translation Ready String
             *  ------------------------
             */
            $_string        =      esc_attr__( 'Select Category and update the post to visible the Amenities', 'sdweddingdirectory-venue' );

            /**
             *  Found Parent Category ?
             *  -----------------------
             */
            $term_id        =       $_venue_id

                                    ?       apply_filters( 'sdweddingdirectory/post/term-parent', [

                                                'post_id'   =>  absint( $_GET[ 'post' ] ),

                                                'taxonomy'  =>  esc_attr( 'venue-type' ),

                                            ] )

                                    :       absint( '0' );
            /**
             *  Term Meta
             *  ---------
             */
            $taxonomy               =       esc_attr( 'venue-type' );

            $fields                 =       [];

            $_dynamic_data          =       apply_filters( 'sdweddingdirectory/dynamic-acf-group-box', [] );

            /**
             *  Have Dynamic Term Data ?
             *  ------------------------
             */
            if( parent:: _is_array( $_dynamic_data ) ){

                /**
                 *  Loop
                 *  ----
                 */
                foreach( $_dynamic_data as $group_key => $group_data ){

                    /**
                     *  Extract Group Data
                     *  ------------------
                     */
                    extract( $group_data );


                    $fields[]   =   array(

                                        'label'     =>  esc_attr( $name ),

                                        'id'        =>  esc_attr( 'tab_' . $slug ),

                                        'type'      =>  esc_attr( 'tab' ),
                                    );

                    $fields[]   =   array(

                                        'id'            =>      esc_attr( $slug ),

                                        'label'         =>      !   empty( $term_id )

                                                        ?       sprintf( '%1$s <a href="%3$s" target="_blank">%2$s</a>',

                                                                    /**
                                                                     *  1. Name
                                                                     *  -------
                                                                     */
                                                                    esc_attr( $name ),

                                                                    /**
                                                                     *  2. Translation Ready String
                                                                     *  ---------------------------
                                                                     */
                                                                    esc_attr__( 'Edit', 'sdweddingdirectory-venue' ),

                                                                    /**
                                                                     *  3. Link for edit amenities category
                                                                     *  -----------------------------------
                                                                     */
                                                                    esc_url(    get_edit_term_link( absint( $term_id ), $taxonomy )   )
                                                                )

                                                        :       $_string,

                                        'type'          =>      esc_attr( 'checkbox' ),

                                        'choices'       =>      !   empty( $term_id )

                                                        ?       apply_filters( 'sdweddingdirectory/ot-tree/options',

                                                                    apply_filters( 'sdweddingdirectory/term-box-group', [

                                                                        'term_id'       =>      absint( $term_id ),

                                                                        'slug'          =>      esc_attr( $slug )

                                                                    ] )
                                                                )

                                                        :       []
                                    );
                }
            }

            /**
             *  Term Dynamic MetaBox
             *  --------------------
             */
            $new_args   =   array(

                'id'        =>  esc_attr( 'sdweddingdirectory_category_wise_term_metabox' ),

                'title'     =>  esc_attr__( 'Category Wise Filter Options Available', 'sdweddingdirectory-client-sinem-kiris' ),

                'pages'     =>  array( 'venue' ),

                'context'   =>  esc_attr( 'normal' ),

                'priority'  =>  esc_attr( 'high' ),

                'fields'    =>  $fields,
            );

            /**
             *  Return : Meta
             *  -------------
             */
            return      array_merge( $args, array( $new_args ) );
        }
    }

    /**
     *  Venue Post Meta
     *  -----------------
     */
    SDWeddingDirectory_Vendor_Venue_Meta::get_instance();
}