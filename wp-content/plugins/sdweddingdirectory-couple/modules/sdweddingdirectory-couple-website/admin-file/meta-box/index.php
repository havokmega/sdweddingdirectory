<?php
/**
 *  Option Tree
 *  -----------
 *
 *  @link( https://github.com/valendesigns/option-tree )
 *
 *  @link( https://github.com/valendesigns/option-tree-theme/blob/master/inc/meta-boxes.php, link)
 *  
 *  SDWeddingDirectory Website Meta
 *  -----------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Website_Meta' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  SDWeddingDirectory Website Feature
     *  --------------------------
     */
    class SDWeddingDirectory_Couple_Website_Meta extends SDWeddingDirectory_Config{

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
             *  Register meta box
             *  -----------------
             */
            add_filter( 'sdweddingdirectory/meta', array($this, 'website_meta_box'), absint('10') );
        }

        /**
         *  SDWeddingDirectory - Couple Website
         *  ---------------------------
         */
        public static function website_meta_box( $args = [] ){

            $_new_args  =   array(

                'id'          =>    esc_attr( 'website_template_layout_meta_box' ),

                'title'       =>    esc_attr__( 'Select Website Template', 'sdweddingdirectory-couple-website' ),

                'pages'       =>    array( 'website' ),

                'context'     =>    esc_attr( 'normal' ),

                'priority'    =>    esc_attr( 'high' ),

                'fields'      =>    array(

                    /**
                     *  Tab : 1
                     *  -------
                     */
                    array(

                        'id'        =>  esc_attr( 'website_template_website_template_tab' ),

                        'label'     =>  esc_attr__( 'Website Template', 'sdweddingdirectory-couple-website' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                        array(

                            'id'          =>    esc_attr( 'website_template_layout' ),

                            'label'       =>    esc_attr__( 'Select Template', 'sdweddingdirectory-couple-website' ),

                            'std'         =>    esc_attr( 'website_template_layout_1' ),

                            'type'        =>    esc_attr( 'select' ),

                            'choices'     =>    array(

                                array(

                                    'value'       =>    '',

                                    'label'       =>    __( '-- Choose Template --', 'sdweddingdirectory-couple-website' ),

                                    'src'         =>    ''
                                ),
                                
                                array(

                                    'value'       =>    esc_attr( 'website_template_layout_1' ),

                                    'label'       =>    esc_attr__( 'Website Template One', 'sdweddingdirectory-couple-website' ),

                                    'src'         =>    ''
                                )
                            )
                        ),

                    /**
                     *  Couple info
                     *  -----------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Couple Information', 'sdweddingdirectory-couple-website' ),

                        'id'        =>  esc_attr( 'couple_information' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                    array(

                        'id'        => sanitize_key( 'bride_first_name' ),

                        'label'     => esc_attr__('Bride First Name', 'sdweddingdirectory-couple-website'),

                        'type'      => esc_attr( 'text' ),
                    ),

                    array(

                        'id'        => sanitize_key( 'bride_last_name' ),

                        'label'     => esc_attr__('Bride Last Name', 'sdweddingdirectory-couple-website'),

                        'type'      => esc_attr( 'text' ),

                    ),

                    array(

                        'id'        => sanitize_key( 'groom_first_name' ),

                        'label'     => esc_attr__('Groom  First Name', 'sdweddingdirectory-couple-website'),

                        'type'      => esc_attr( 'text' ),

                    ),

                    array(

                        'id'        => sanitize_key( 'groom_last_name' ),

                        'label'     => esc_attr__('Groom Last Name', 'sdweddingdirectory-couple-website'),

                        'type'      => esc_attr( 'text' ),

                    ),

                    array(

                        'id'        => sanitize_key( 'wedding_date' ),

                        'label'     => esc_attr__( 'Wedding Date', 'sdweddingdirectory-couple-website' ),

                        'type'      => esc_attr( 'date-picker' ),
                    ),

                    array(

                        'id'        => esc_attr( 'bride_image' ),

                        'label'     => esc_attr__('Bride Image', 'sdweddingdirectory-couple-website'),

                        'type'      => esc_attr( 'upload' ),

                        'class'     => sanitize_html_class( 'ot-upload-attachment-id' ),
                    ),

                    array(

                        'id'        => esc_attr( 'groom_image' ),

                        'label'     => esc_attr__('Groom Image', 'sdweddingdirectory-couple-website'),

                        'type'      => esc_attr( 'upload' ),

                        'class'     => sanitize_html_class( 'ot-upload-attachment-id' ),
                    ),

                    /**
                     *  Tab : About Couple
                     *  ------------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'About Couple', 'sdweddingdirectory-couple-website' ),

                        'id'        =>  esc_attr( 'about_couple_tab' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                        array(

                            'id'        =>  sanitize_key( 'couple_info_heading' ),

                            'label'     =>  esc_attr__( 'About Couple Section Heading', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'text' ),

                            'std'       =>  esc_attr__( 'The Couple', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_info_description' ),

                            'label'     =>  esc_attr__( 'About Couple Section Description', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'textarea-simple' ),

                            'rows'      =>  absint( '3' ),

                            'std'       =>  esc_attr__( 'We are so excited to celebrate our special day with our family and friends. Thank you so much for visiting our wedding website!', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'          =>    esc_attr( 'about_groom' ),

                            'label'       =>    esc_attr__( 'About Groom', 'sdweddingdirectory-couple-website' ),

                            'src'         =>    '',

                            'type'        =>    esc_attr( 'text' ),
                        ),

                        array(

                            'id'          =>    esc_attr( 'groom_instagram' ),

                            'label'       =>    esc_attr__( 'Instagram Profile', 'sdweddingdirectory-couple-website' ),

                            'src'         =>    '',

                            'type'        =>    esc_attr( 'text' ),
                        ),

                        array(

                            'id'          =>    esc_attr( 'groom_facebook' ),

                            'label'       =>    esc_attr__( 'Facebook Profile', 'sdweddingdirectory-couple-website' ),

                            'src'         =>    '',

                            'type'        =>    esc_attr( 'text' ),
                        ),

                        array(

                            'id'          =>    esc_attr( 'groom_twitter' ),

                            'label'       =>    esc_attr__( 'Twitter Profile', 'sdweddingdirectory-couple-website' ),

                            'src'         =>    '',

                            'type'        =>    esc_attr( 'text' ),
                        ),

                        array(

                            'id'          =>    esc_attr( 'about_bride' ),

                            'label'       =>    esc_attr__( 'About Bride', 'sdweddingdirectory-couple-website' ),

                            'src'         =>    '',

                            'type'        =>    esc_attr( 'text' ),
                        ),

                        array(

                            'id'          =>    esc_attr( 'bride_instagram' ),

                            'label'       =>    esc_attr__( 'Instagram Profile', 'sdweddingdirectory-couple-website' ),

                            'src'         =>    '',

                            'type'        =>    esc_attr( 'text' ),
                        ),

                        array(

                            'id'          =>    esc_attr( 'bride_facebook' ),

                            'label'       =>    esc_attr__( 'Facebook Profile', 'sdweddingdirectory-couple-website' ),

                            'src'         =>    '',

                            'type'        =>    esc_attr( 'text' ),
                        ),

                        array(

                            'id'          =>    esc_attr( 'bride_twitter' ),

                            'label'       =>    esc_attr__( 'Twitter Profile', 'sdweddingdirectory-couple-website' ),

                            'src'         =>    '',

                            'type'        =>    esc_attr( 'text' ),
                        ),


                    /**
                     *  Tab : Header Image
                     *  ------------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Header Image', 'sdweddingdirectory-couple-website' ),

                        'id'        =>  esc_attr( 'couple_website_header_image_tab' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                        array(

                            'id'        =>  sanitize_key( 'header_image' ),

                            'label'     =>  esc_attr__( 'Header Image', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'upload' ),

                            'class'     =>  sanitize_html_class( 'ot-upload-attachment-id' ),
                        ),

                    /**
                     *  Tab : Header Image
                     *  ------------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Footer Image', 'sdweddingdirectory-couple-website' ),

                        'id'        =>  esc_attr( 'couple_website_footer_image_tab' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                        array(

                            'id'        =>  sanitize_key( 'footer_image' ),

                            'label'     =>  esc_attr__( 'Footer Image', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'upload' ),

                            'class'     =>  sanitize_html_class( 'ot-upload-attachment-id' ),
                        ),



                    /**
                     *  Tab : Couple Story
                     *  ------------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Couple Story', 'sdweddingdirectory-couple-website' ),

                        'id'        =>  esc_attr( 'couple_story_tab' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                        array(

                            'id'        =>  sanitize_key( 'couple_story_heading' ),

                            'label'     =>  esc_attr__( 'Couple Story Section Heading', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'text' ),

                            'std'       =>  esc_attr__( 'OUR STORY', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_story_description' ),

                            'label'     =>  esc_attr__( 'Couple Story Section Description', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'textarea-simple' ),

                            'rows'      =>  absint( '3' ),

                            'std'       =>  esc_attr__( 'We are so excited to celebrate our special day with our family and friends. Thank you so much for visiting our wedding website!', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_story' ),

                            'type'      =>  esc_attr( 'list-item' ),

                            'operator'  =>  esc_attr( 'or' ),

                            'choices'   =>  [],

                            'settings'  =>  array(

                                array(

                                    'id'        =>  sanitize_key( 'story_title' ),

                                    'label'     =>  esc_attr__( 'Story Title', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'story_overview' ),

                                    'label'     =>  esc_attr__( 'Story Overview', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'textarea-simple' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'story_date' ),

                                    'label'     =>  esc_attr__( 'Story Date', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'date-picker' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'story_image' ),

                                    'label'     =>  esc_attr__( 'Story Image', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'upload' ),

                                    'class'     =>  sanitize_html_class( 'ot-upload-attachment-id' )
                                ),
                            ),
                        ),

                    /**
                     *  Tab : When & Where
                     *  ------------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'When & Where', 'sdweddingdirectory-couple-website' ),

                        'id'        =>  esc_attr( 'couple_event_tab' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                        array(

                            'id'        =>  sanitize_key( 'couple_event_heading' ),

                            'label'     =>  esc_attr__( 'When & Where Section Heading', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'text' ),

                            'std'       =>  esc_attr__( 'WHEN & WHERE', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_event_description' ),

                            'label'     =>  esc_attr__( 'When & Where Section Description', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'textarea-simple' ),

                            'rows'      =>  absint( '3' ),

                            'std'       =>  esc_attr__( 'We are so excited to celebrate our special day with our family and friends. Thank you so much for visiting our wedding website!', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_event' ),

                            'type'      =>  esc_attr( 'list-item' ),

                            'operator'  =>  esc_attr( 'or' ),

                            'choices'   =>  [],

                            'settings'  =>  array(

                                array(

                                    'id'        =>  sanitize_key( 'name' ),

                                    'label'     =>  esc_attr__( 'Event Name', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'content' ),

                                    'label'     =>  esc_attr__( 'Event Content', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'textarea-simple' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'date' ),

                                    'label'     =>  esc_attr__( 'Event Date', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'date-picker' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'image' ),

                                    'label'     =>  esc_attr__( 'Event Image', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'upload' ),

                                    'class'     =>  sanitize_html_class( 'ot-upload-attachment-id' ),
                                ),

                                array(

                                    'id'        =>  sanitize_key( 'map_address' ),

                                    'label'     =>  esc_attr__( 'Map Address', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),

                                array(

                                    'id'        =>  sanitize_key( 'latitude' ),

                                    'label'     =>  esc_attr__( 'Event latitude', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),

                                array(

                                    'id'        =>  sanitize_key( 'longitude' ),

                                    'label'     =>  esc_attr__( 'Event longitude', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),

                                array(

                                    'id'        =>  sanitize_key( 'icon' ),

                                    'label'     =>  esc_attr__( 'Event Icon', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),

                            ),
                        ),

                    /**
                     *  Tab : Gift & Registry
                     *  ---------------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Gift & Registry', 'sdweddingdirectory-couple-website' ),

                        'id'        =>  esc_attr( 'couple_gift_registry_tab' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                        array(

                            'id'        =>  sanitize_key( 'couple_gift_registry_heading' ),

                            'label'     =>  esc_attr__( 'Gift & Registry Section Heading', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'text' ),

                            'std'       =>  esc_attr__( 'GIFT REGISTRY', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_gift_registry_description' ),

                            'label'     =>  esc_attr__( 'Gift & Registry Section Description', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'textarea-simple' ),

                            'rows'      =>  absint( '3' ),

                            'std'       =>  esc_attr__( 'We are so excited to celebrate our special day with our family and friends. Thank you so much for visiting our wedding website!', 'sdweddingdirectory-couple-website' )
                        ),

                    /**
                     *  Tab : RSVP Tab
                     *  --------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'RSVP', 'sdweddingdirectory-couple-website' ),

                        'id'        =>  esc_attr( 'couple_rsvp_tab' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                        array(

                            'id'        =>  sanitize_key( 'couple_rsvp_heading' ),

                            'label'     =>  esc_attr__( 'RSVP Section Heading', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'text' ),

                            'std'       =>  esc_attr( 'Will You Attend?' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_rsvp_description' ),

                            'label'     =>  esc_attr__( 'RSVP Section Description', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'textarea-simple' ),

                            'rows'      =>  absint( '3' ),

                            'std'       =>  esc_attr( 'Kindly respond before 30 August' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_rsvp_image' ),

                            'label'     =>  esc_attr__( 'RSVP Background Image', 'sdweddingdirectory-couple-website'),

                            'type'      =>  esc_attr( 'upload' ),

                            'class'     =>  sanitize_html_class( 'ot-upload-attachment-id' )
                        ),

                    /**
                     *  Tab : Counter
                     *  -------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Counter', 'sdweddingdirectory-couple-website' ),

                        'id'        =>  esc_attr( 'couple_counter_tab' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                        array(

                            'id'        =>  sanitize_key( 'couple_counter_heading' ),

                            'label'     =>  esc_attr__( 'Counter Section Heading', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'text' ),

                            'std'       =>  esc_attr__( '~ St. Augustine ~', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_counter_description' ),

                            'label'     =>  esc_attr__( 'Counter Section Description', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'textarea-simple' ),

                            'rows'      =>  absint( '3' ),

                            'std'       =>  esc_attr__( 'Better to have loved and lost, than to have never loved at all.', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_counter_date' ),

                            'label'     =>  esc_attr__( 'Counter Section Date', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'date-picker' ),

                            'std'       =>  ''
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_counter_image' ),

                            'label'     =>  esc_attr__( 'Counter Image', 'sdweddingdirectory-couple-website'),

                            'type'      =>  esc_attr( 'upload' ),

                            'class'     =>  sanitize_html_class( 'ot-upload-attachment-id' )
                        ),

                    /**
                     *  Tab : The Groomsmen
                     *  -------------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Groomsmen', 'sdweddingdirectory-couple-website' ),

                        'id'        =>  esc_attr( 'couple_groomsmen_tab' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                        array(

                            'id'        =>  sanitize_key( 'couple_groom_heading' ),

                            'label'     =>  esc_attr__( 'Groomsmen Section Heading', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'text' ),

                            'std'       =>  esc_attr__( 'THE GROOMSMEN', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_groom_description' ),

                            'label'     =>  esc_attr__( 'Groomsmen Section Description', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'textarea-simple' ),

                            'rows'      =>  absint( '3' ),

                            'std'       =>  esc_attr__( 'We are so excited to celebrate our special day with our family and friends. Thank you so much for visiting our wedding website!', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_groom' ),

                            'type'      =>  esc_attr( 'list-item' ),

                            'operator'  =>  esc_attr( 'or' ),

                            'choices'   =>  [],

                            'settings'  =>  array(

                                array(

                                    'id'        =>  sanitize_key( 'groom_name' ),

                                    'label'     =>  esc_attr__( 'Groom Name', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'groom_image' ),

                                    'label'     =>  esc_attr__( 'Groom Image', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'upload' ),

                                    'class'     =>  sanitize_html_class( 'ot-upload-attachment-id' )
                                ),
                            ),
                        ),

                    /**
                     *  Tab : The Bridesmaids
                     *  ---------------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Bridesmaids', 'sdweddingdirectory-couple-website' ),

                        'id'        =>  esc_attr( 'couple_bridesmaids_tab' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                        array(

                            'id'        =>  sanitize_key( 'couple_bride_heading' ),

                            'label'     =>  esc_attr__( 'Groomsmen Section Heading', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'text' ),

                            'std'       =>  esc_attr__( 'THE BRIDESMAIDS', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_bride_description' ),

                            'label'     =>  esc_attr__( 'Brides Section Description', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'textarea-simple' ),

                            'rows'      =>  absint( '3' ),

                            'std'       =>  esc_attr__( 'We are so excited to celebrate our special day with our family and friends. Thank you so much for visiting our wedding website!', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_bride' ),

                            'type'      =>  esc_attr( 'list-item' ),

                            'operator'  =>  esc_attr( 'or' ),

                            'choices'   =>  [],

                            'settings'  =>  array(

                                array(

                                    'id'        =>  sanitize_key( 'bride_name' ),

                                    'label'     =>  esc_attr__( 'Bride Title', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'bride_image' ),

                                    'label'     =>  esc_attr__( 'Bride Image', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'upload' ),

                                    'class'     =>  sanitize_html_class( 'ot-upload-attachment-id' )
                                ),
                            ),
                        ),

                    /**
                     *  Tab : WHAT THEY SAY
                     *  -------------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Testimonials', 'sdweddingdirectory-couple-website' ),

                        'id'        =>  esc_attr( 'couple_testimonial_tab' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                        array(

                            'id'        =>  sanitize_key( 'couple_testimonial_heading' ),

                            'label'     =>  esc_attr__( 'Testimonials Section Heading', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'text' ),

                            'std'       =>  esc_attr__( 'WHAT THEY SAY', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_testimonial_description' ),

                            'label'     =>  esc_attr__( 'Testimonials Section Description', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'textarea-simple' ),

                            'rows'      =>  absint( '3' ),

                            'std'       =>  esc_attr__( 'We are so excited to celebrate our special day with our family and friends. Thank you so much for visiting our wedding website!', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_testimonial_image' ),

                            'label'     =>  esc_attr__( 'Testimonials Background Image', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'upload' ),

                            'class'     =>  sanitize_html_class( 'ot-upload-attachment-id' ),
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_testimonial' ),

                            'type'      =>  esc_attr( 'list-item' ),

                            'operator'  =>  esc_attr( 'or' ),

                            'choices'   =>  [],

                            'settings'  =>  array(

                                array(

                                    'id'        =>  sanitize_key( 'name' ),

                                    'label'     =>  esc_attr__( 'Name', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'content' ),

                                    'label'     =>  esc_attr__( 'Content', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'textarea-simple' ),
                                ),
                                // array(

                                //     'id'        =>  sanitize_key( 'image' ),

                                //     'label'     =>  esc_attr__( 'Image', 'sdweddingdirectory-couple-website'),

                                //     'type'      =>  esc_attr( 'upload' ),
                                // ),
                            ),
                        ),

                    /**
                     *  Tab : CAPTURED MOMENTS
                     *  ----------------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Couple Gallery', 'sdweddingdirectory-couple-website' ),

                        'id'        =>  esc_attr( 'couple_gallery_tab' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                        array(

                            'id'        =>  sanitize_key( 'couple_gallery_heading' ),

                            'label'     =>  esc_attr__( 'Captured Moments Section Heading', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'text' ),

                            'std'       =>  esc_attr__( 'CAPTURED MOMENTS', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_gallery_description' ),

                            'label'     =>  esc_attr__( 'Captured Moments Section Description', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'textarea-simple' ),

                            'rows'      =>  absint( '3' ),

                            'std'       =>  esc_attr__( 'We are so excited to celebrate our special day with our family and friends. Thank you so much for visiting our wedding website!', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_gallery' ),

                            'type'      =>  esc_attr( 'list-item' ),

                            'operator'  =>  esc_attr( 'or' ),

                            'choices'   =>  [],

                            'settings'  =>  array(

                                array(

                                    'id'        =>  sanitize_key( 'gallery_name' ),

                                    'label'     =>  esc_attr__( 'Gallery Name', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'text' ),
                                ),
                                array(

                                    'id'        =>  sanitize_key( 'gallery_image' ),

                                    'label'     =>  esc_attr__( 'Gallery Image', 'sdweddingdirectory-couple-website'),

                                    'type'      =>  esc_attr( 'gallery' ),
                                ),
                            ),
                        ),

                    /**
                     *  Tab : Latest Blog
                     *  -----------------
                     */
                    array(

                        'label'     =>  esc_attr__( 'Your Blogs', 'sdweddingdirectory-couple-website' ),

                        'id'        =>  esc_attr( 'couple_blog_tab' ),

                        'type'      =>  esc_attr( 'tab' ),
                    ),

                        array(

                            'id'        =>  sanitize_key( 'couple_blog_heading' ),

                            'label'     =>  esc_attr__( 'Blog Section Heading', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'text' ),

                            'std'       =>  esc_attr__( 'LATEST NEWS & UPDATES', 'sdweddingdirectory-couple-website' )
                        ),

                        array(

                            'id'        =>  sanitize_key( 'couple_blog_description' ),

                            'label'     =>  esc_attr__( 'Blog Section Description', 'sdweddingdirectory-couple-website' ),

                            'type'      =>  esc_attr( 'textarea-simple' ),

                            'rows'      =>  absint( '3' ),

                            'std'       =>  esc_attr__( 'We are so excited to celebrate our special day with our family and friends. Thank you so much for visiting our wedding website!', 'sdweddingdirectory-couple-website' )
                        ),
                )
            );

            return array_merge( $args, array( $_new_args ) );
        }
    }  

    /**
     *  SDWeddingDirectory - Website Post Meta
     *  ------------------------------
     */
    SDWeddingDirectory_Couple_Website_Meta:: get_instance();
}