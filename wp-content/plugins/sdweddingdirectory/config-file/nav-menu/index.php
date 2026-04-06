<?php

if( ! class_exists( 'WP_Bootstrap_Navwalker' ) ){

    /**
     * Modified version of wp_bootstrap_navwalker for Multiple dropdown
     * GitHub URI: https://github.com/circlewaves/bootstrap-multilevel-dropdown-menu
     * Author: Max Kostinevich - http://circlewaves.com
     * Version 1.0.0
     * Changelog:
     *  - Changes across the line 126 (see $item_output); 
     *
     * ORIGINAL SOURCE:
     * Class Name: wp_bootstrap_navwalker
     * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
     * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
     * Version: 2.0.4
     * Author: Edward McIntyre - @twittem
     * License: GPL-2.0+
     * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
     */
    class WP_Bootstrap_Navwalker extends Walker_Nav_Menu {

        // /**
        //  *  1st Depth
        //  *  ---------
        //  */
        // public function start_lvl( &$output, $depth = 0, $args = array() ) {

        //  $indent  = str_repeat( "\t", $depth );

        //  $submenu = ( $depth > 0 ) ? ' sub-menu' : '';

        //  $output .= "\n$indent<ul class=\"dropdown-menu$submenu depth_$depth\">\n";
        // }


        /**
         * @see Walker::start_el()
         * @since 3.0.0
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param object $item Menu item data object.
         * @param int $depth Depth of menu item. Used for padding.
         * @param int $current_page Menu item ID.
         * @param object $args
         */
        public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {

            $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

            /**
             *  Is parent menu
             *  --------------
             */
            $parent                 =       0 == $item->menu_item_parent        ? true : false;

            $child              =       false;

            $enable_dropdown        =       $item->sdweddingdirectory_taxonomy != '' || $args->has_children;

            /**
             *  SDWeddingDirectory - Navigation Code Start Here
             *  ---------------------------------------
             */
            $is_mega_menu           =       $item->sdweddingdirectory_menu == 'mega_menu'   ?   true : false;

            $container_class        =       [];

            $container_class[]  =       'dropdown-menu';

            $container_class[]  =       $is_mega_menu ? sanitize_html_class( 'megamenu' )  : '';


            /**
             *  Have Child Element Available ?
             *  ------------------------------
             */
            if ( is_array( $item->classes ) && in_array( 'menu-item-has-children', $item->classes ) ) {

                    $child = true;
            }

            $class_names = $value = '';

            $classes = empty( $item->classes ) ? [] : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;

            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

            if ( $enable_dropdown )
                $class_names .= ' dropdown';

            if ( in_array( 'current-menu-item', $classes ) )
                $class_names .= ' active';

            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

            $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
            $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

            $output .= $indent . '<li' . $id . $value . $class_names .'>';

            $atts = [];
            $atts['title']  = ! empty( $item->title )   ? $item->title  : '';
            $atts['target'] = $item->sdweddingdirectory_target ? $item->sdweddingdirectory_target   : '';
            $atts['rel']    = ! empty( $item->xfn )     ? $item->xfn    : '';


            // If item has_children add atts to a.
            if ( $enable_dropdown && $depth === 0 ) {

                $atts['href']               = '#';

                $atts['data-bs-toggle'] = 'dropdown';

                $atts['class']              = 'dropdown-toggle nav-link';

                $atts['aria-haspopup']  = 'true';
            } 

            else {

                if( $depth === 0 ){

                    $atts['href']       =   ! empty( $item->url ) ? $item->url : '';
                    $atts['class']  =   'nav-link';
                }

                else{
                    $atts['href']       =   ! empty( $item->url ) ? $item->url : '';
                    $atts['class']  =   'dropdown-item';
                }
            }

            $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

            $attributes = '';
            foreach ( $atts as $attr => $value ) {
                if ( ! empty( $value ) ) {
                    $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }


            $item_output = $args->before;

            if ( ! empty( $item->attr_title ) ){

                $item_output .= sprintf(
                    '<a%1$s><span class="menu-icon"><i class="%2$s"></i></span> ',
                    $attributes,
                    esc_attr( $item->attr_title )
                );
            }else{

                $item_output .= '<a'. $attributes .'>';
            }

            $item_output    .=  $args->link_before . apply_filters( 'nav_menu_item_title', $item->title, $item ) . $args->link_after;

            $item_output    .=  '</a>';

            $item_output    .=  $args->after;

            $output             .=  apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );



            /**
             *  Handler
             *  -------
             */
            $output         .=  sprintf( '<div class="%1$s">', implode( ' ', $container_class ) );


            /**
             *  Is Mega Menu ?
             *  --------------
             */
            if( $is_mega_menu ){

                $output         .=  '<div class="container">';
            }


            /**
             *  Is Taxonomy Dropdown ?
             *  ----------------------
             */
            if( $item->sdweddingdirectory_taxonomy != '' ){

                $taxonomy_list      =       apply_filters( 'sdweddingdirectory/tax/parent', $item->sdweddingdirectory_taxonomy );

                if( is_array( $taxonomy_list ) && count( $taxonomy_list ) >= absint( '1' ) ){

                    if( $is_mega_menu ){

                        $output         .=  '<div class="row">';
                    }

                    else{

                        $output         .=  '<ul class="sub-menu">';
                    }

                    foreach( $taxonomy_list as $tax_key => $tax_name ){
                        $term_icon = apply_filters( 'sdweddingdirectory/term/icon', [
                            'term_id'   => absint( $tax_key ),
                            'taxonomy'  => esc_attr( $item->sdweddingdirectory_taxonomy ),
                            'default_icon' => ''
                        ] );

                        $icon_markup = ! empty( $term_icon )
                            ? sprintf( '<i class="%1$s"></i> ', esc_attr( $term_icon ) )
                            : '';

                        if( $is_mega_menu ){

                            $output         .=  sprintf( '<div class="%1$s %2$s col-12">

                                                                    <p><a title="%3$s" target="_self" href="%4$s" class="dropdown-item">%5$s%3$s</a></p>

                                                                </div>',

                                                                /**
                                                                 *  1. Column MD
                                                                 *  ------------
                                                                 */
                                                                esc_attr( $item->sdweddingdirectory_md_column ),
                                                                
                                                                /**
                                                                 *  2. Column SM
                                                                 *  ------------
                                                                 */
                                                                esc_attr( $item->sdweddingdirectory_sm_column ),

                                                                /**
                                                                 *  3. Term Name
                                                                 *  ------------
                                                                 */
                                                                esc_attr( $tax_name ),

                                                                /**
                                                                 *  4. Term Link
                                                                 *  ------------
                                                                 */
                                                                esc_url( get_term_link( $tax_key, $item->sdweddingdirectory_taxonomy ) ),

                                                                /**
                                                                 *  5. Icon Markup
                                                                 *  -------------
                                                                 */
                                                                $icon_markup
                                                    );
                        }

                        else{

                            $output         .=  sprintf( '<li class="">

                                                                    <a title="%1$s" target="_self" href="%2$s" class="dropdown-item">%3$s%1$s</a>

                                                                </li>',

                                                                /**
                                                                 *  1. Term Name
                                                                 *  ------------
                                                                 */
                                                                esc_attr( $tax_name ),

                                                                /**
                                                                 *  2. Term Link
                                                                 *  ------------
                                                                 */
                                                                esc_url( get_term_link( $tax_key, $item->sdweddingdirectory_taxonomy ) ),

                                                                /**
                                                                 *  3. Icon Markup
                                                                 *  -------------
                                                                 */
                                                                $icon_markup
                                                    );
                        }
                    }

                    if( $is_mega_menu ){

                        $output         .=      '</div>';   
                    }

                    else{

                        $output         .=      '</ul>';
                    }
                }
            }


            /**
             *  Is Mega Menu ?
             *  --------------
             */
            if( $is_mega_menu ){

                $output         .=  '</div>';
            }


            /**
             *  No Child Found
             *  --------------
             */
            if ( empty( $child ) ) {

                $output         .=  '</div>';
            }
        }

        /**
         *  End Level
         *  ---------
         */
        public function end_lvl( &$output, $depth = 0, $args = array() ) {

            $indent   =     str_repeat( "\t", $depth );

            $output     .=  0 == $depth ? "$indent</ul></div>\n" : "$indent</ul>\n";
        }

        /**
         * Traverse elements to create list from elements.
         *
         * Display one element if the element doesn't have any children otherwise,
         * display the element and its children. Will only traverse up to the max
         * depth and no ignore elements under that depth.
         *
         * This method shouldn't be called directly, use the walk() method instead.
         *
         * @see Walker::start_el()
         * @since 2.5.0
         *
         * @param object $element Data object
         * @param array $children_elements List of elements to continue traversing.
         * @param int $max_depth Max depth to traverse.
         * @param int $depth Depth of current element.
         * @param array $args
         * @param string $output Passed by reference. Used to append additional content.
         * @return null Null on failure with no changes to parameters.
         */
        public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {

          if ( ! $element ){ return; }

          $id_field = $this->db_fields['id'];

          // Display this element.
          if ( is_object( $args[0] ) ){

            $args[0]->has_children  =   ! empty( $children_elements[ $element->$id_field ] );
          }

          parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
        }
    }

    /**
     *  SDWeddingDirectory - Nav Menu
     *  ---------------------
     *  @link - https://pressidium.com/blog/adding-custom-fields-to-wordpress-menu-items/
     *  ---------------------------------------------------------------------------------
     *  @link - https://wordpress.stackexchange.com/questions/378190/add-custom-fields-to-specific-menus
     *  ------------------------------------------------------------------------------------------------
     */
    if( ! class_exists( 'SDWeddingDirectory_Nav_Menu' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

        /**
         *  SDWeddingDirectory - Nav Menu
         *  ---------------------
         */
        class SDWeddingDirectory_Nav_Menu extends SDWeddingDirectory_Config {

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
                 *  1. Load Script
                 *  --------------
                 */
                add_action( 'admin_enqueue_scripts', [$this, 'sdweddingdirectory_script'] );

                /**
                 *  Add Menu Fields
                 *  ---------------
                 */
                add_action( 'wp_nav_menu_item_custom_fields', [ $this, 'add_menu_fields' ], absint( '10' ), absint( '4' ) );

                /**
                 *  Save Data
                 *  ---------
                 */
                add_action( 'wp_update_nav_menu_item', [ $this, 'walker_menu_save' ], absint( '10' ), absint( '3' ) );

                /**
                 *  Save Data
                 *  ---------
                 */
                add_action( 'wp_setup_nav_menu_item', [ $this, 'walker_menu_loader' ], absint( '10' ), absint( '1' ) );

                /**
                 *  Menu Object
                 *  -----------
                 */
                add_filter( 'wp_nav_menu_objects', [ $this, 'walker_menu_objects' ], absint( '10' ), absint( '2' ) );
            }

            /**
             *  1. Load Script
             *  --------------
             */
            public static function sdweddingdirectory_script( $hook ){

                if( $hook !== 'nav-menus.php' ){

                    return;
                }

                /**
                 *  Load Style
                 *  ----------
                 */
                wp_enqueue_style( 

                    /**
                     *  1. File Name
                     *  ------------
                     */
                    esc_attr( sanitize_title( __CLASS__ ) ),

                    /**
                     *  2. File Path
                     *  ------------
                     */
                    esc_url(   plugin_dir_url( __FILE__ )   .   'style.css'   ),

                    /**
                     *  3. Have Dependancy ?
                     *  --------------------
                     */
                    [ ],

                    /**
                     *  4. Bootstrap - Library Version
                     *  ------------------------------
                     */
                    esc_attr( parent:: _file_version(   plugin_dir_path( __FILE__ )   .   'style.css' )     ),

                    /**
                     *  5. Load All Media
                     *  -----------------
                     */
                    esc_attr( 'all' )
                );
            }

            /**
             *  Edit Nav Menu Fields
             *  --------------------
             */
            public static function menu_item_fields(){

                /**
                 *  Extract Args
                 *  ------------
                 */
                $fields             =       [];

                /**
                 *  Select Option
                 *  -------------
                 */
                $fields[]           =       [

                    'depth_level'   =>      absint( '0' ),

                    'type'          =>      'select',

                    'id'            =>      'sdweddingdirectory_menu',

                    'width'         =>      'description-wide',

                    'label'         =>      esc_html__( 'Select Menu Style ?', 'sdweddingdirectory' ),

                    'std'           =>      '',

                    'options'       =>      [
                                                ''                  =>      esc_attr__( 'Default Menu', 'sdweddingdirectory' ),

                                                'mega_menu'         =>      esc_attr__( 'Mega Menu', 'sdweddingdirectory' ),
                                            ]
                ];

                /**
                 *  Venue Category
                 *  ----------------
                 */
                $fields[]           =       [

                    'depth_level'   =>      absint( '1' ),

                    'type'          =>      'select',

                    'id'            =>      'sdweddingdirectory_md_column',

                    'width'         =>      'description-thin',

                    'label'         =>      esc_html__( 'Medium Device Column ?', 'sdweddingdirectory' ),

                    'std'           =>      '',

                    'options'       =>      [
                                                ''              =>      esc_attr__( '-- Medium Device Column --', 'sdweddingdirectory' ),

                                                'col-md-12'     =>      esc_attr( 'col-md-12' ),

                                                'col-md-8'      =>      esc_attr( 'col-md-8' ),

                                                'col-md-6'      =>      esc_attr( 'col-md-6' ),

                                                'col-md-4'      =>      esc_attr( 'col-md-4' ),

                                                'col-md-3'      =>      esc_attr( 'col-md-3' ),
                                            ]
                ];

                /**
                 *  Venue Category
                 *  ----------------
                 */
                $fields[]           =       [

                    'depth_level'   =>      absint( '1' ),

                    'type'          =>      'select',

                    'id'            =>      'sdweddingdirectory_sm_column',

                    'width'         =>      'description-thin',

                    'label'         =>      esc_html__( 'Small Device Column ?', 'sdweddingdirectory' ),

                    'std'           =>      '',

                    'options'       =>      [
                                                ''              =>      esc_attr__( '-- Small Device Column --', 'sdweddingdirectory' ),

                                                'col-sm-12'     =>      esc_attr( 'col-sm-12' ),

                                                'col-sm-8'      =>      esc_attr( 'col-sm-8' ),

                                                'col-sm-6'      =>      esc_attr( 'col-sm-6' ),

                                                'col-sm-4'      =>      esc_attr( 'col-sm-4' ),

                                                'col-sm-3'      =>      esc_attr( 'col-sm-3' ),
                                            ]
                ];


                /**
                 *  Venue Category
                 *  ----------------
                 */
                $fields[]           =       [

                    'depth_level'   =>      absint( '1' ),

                    'type'          =>      'select',

                    'id'            =>      'sdweddingdirectory_taxonomy',

                    'width'         =>      'description-wide',

                    'label'         =>      esc_html__( 'Select Taxonomy ?', 'sdweddingdirectory' ),

                    'std'           =>      '',

                    'options'       =>      [
                                                ''                      =>      esc_attr__( '-- Select Taxonomy --', 'sdweddingdirectory' ),

                                                'vendor-category'       =>      esc_attr__( 'Vendor Category', 'sdweddingdirectory' ),

                                                'venue-type'      =>      esc_attr__( 'Venue Category', 'sdweddingdirectory' )
                                            ]
                ];

                /**
                 *  Radio Option
                 *  ------------
                 */
                $fields[]           =       [

                    'depth_level'   =>      absint( '1' ),

                    'type'          =>      'radio',

                    'id'            =>      'sdweddingdirectory_target',

                    'width'         =>      'description-thin',

                    'label'         =>      esc_html__( 'Open link new tab ?', 'sdweddingdirectory' ),

                    'std'           =>      '_self',

                    'options'       =>      [
                                                '_self'         =>      esc_attr__( 'No', 'sdweddingdirectory' ),

                                                '_target'       =>      esc_attr__( 'Yes', 'sdweddingdirectory' ),
                                            ]
                ];

                /**
                 *  Venue Posts
                 *  -------------
                 */
                // $fields[]       =       [

                //     'type'      =>      'radio',

                //     'id'        =>      'sdweddingdirectory_venue_post',

                //     'width'     =>      'description-wide',

                //     'label'     =>      esc_html__( 'Select Venue Post ?', 'sdweddingdirectory' ),

                //     'std'       =>      '',

                //     'options'   =>      apply_filters( 'sdweddingdirectory/post/data', [  'post_type'  =>  esc_attr( 'post' ) ] )
                // ];

                /**
                 *  Input Field
                 *  -----------
                 */
                // $fields[]           =       [

                //     'depth_level'   =>      absint( '1' ),

                //     'type'          =>      'input',

                //     'id'            =>      'sdweddingdirectory_input',

                //     'width'         =>      'description-wide',

                //     'label'         =>      esc_html__( 'Open in New Tab ?', 'sdweddingdirectory' ),

                //     'std'           =>      '',
                // ];

                /**
                 *  Return Fields
                 *  -------------
                 */
                return          $fields;
            }

            /**
             *  Add Menu Fields
             *  ---------------
             */
            public static function add_menu_fields( $item_id, $item, $depth, $args ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( [

                    'fields'        =>      self:: menu_item_fields()

                ] );

                /**
                 *  Have Request
                 *  ------------
                 */
                if( parent:: _is_array( $fields ) ){

                    foreach( $fields as $fields_key => $fields_value ){

                        /**
                         *  Extract Args
                         *  ------------
                         */
                        extract( $fields_value );

                        /**
                         *  Option String
                         *  -------------
                         */
                        $option_string      =   '';

                        /**
                         *  Current Field Value
                         *  -------------------
                         */
                        $field_value     =   empty( $item->$id )     ?   $std     :   $item->$id;

                        $field_enable    =   $depth_level != $depth  ?   'style="display:none;"'     :   '';

                        /**
                         *  Is Select Option ?
                         *  ------------------
                         */
                        if( $type  ==  esc_attr( 'select' ) ){

                            /**
                             *  Have Options
                             *  ------------
                             */
                            if( parent:: _is_array( $options ) ){

                                foreach( $options as $_option_key => $_option_value ){

                                    $option_string  .=  

                                    sprintf( '<option value="%1$s" %2$s>%3$s</option>',

                                        /**
                                         *  Option Key
                                         *  ----------
                                         */
                                        $_option_key,

                                        /**
                                         *  Is Selected
                                         *  -----------
                                         */
                                        selected( $field_value, $_option_key, false ),

                                        /**
                                         *  Option Value
                                         *  ------------
                                         */
                                        $_option_value
                                    );
                                }
                            }

                            printf( '<p class="field-%1$s %2$s depth_level_%7$s %1$s">

                                        <label for="%1$s-%3$s"> <span>%4$s</span> <br />

                                            <select class="widefat code %1$s" id="%1$s-%3$s" name="menu_name_%1$s[%3$s]">

                                                %5$s

                                            </select>

                                        </label>

                                    </p>',

                                    /**
                                     *  1. Field ID
                                     *  -----------
                                     */
                                    esc_attr( $id ),

                                    /**
                                     *  2. Field Width
                                     *  --------------
                                     */
                                    esc_attr( $width ),

                                    /**
                                     *  3. Menu Item ID
                                     *  ---------------
                                     */
                                    esc_attr( $item_id ),

                                    /**
                                     *  4. Field Lable
                                     *  --------------
                                     */
                                    esc_attr( $label ),

                                    /**
                                     *  5. Dropdown List
                                     *  ----------------
                                     */
                                    $option_string,

                                    /**
                                     *  6. Is Enable ?
                                     *  --------------
                                     */
                                    $field_enable,

                                    /**
                                     *  7. Depth Level
                                     *  --------------
                                     */
                                    $depth_level
                            );
                        }

                        /**
                         *  Is Radio Option ?
                         *  ------------------
                         */
                        elseif( $type  ==  esc_attr( 'radio' ) ){

                            /**
                             *  Have Options
                             *  ------------
                             */
                            if( parent:: _is_array( $options ) ){

                                foreach( $options as $_option_key => $_option_value ){

                                    $option_string  .=

                                    sprintf(   '<input  type="radio" id="%1$s-%2$s" class="widefat code %1$s" name="menu_name_%1$s[%2$s]" value="%3$s" %4$s />

                                                <label for="%1$s-%2$s">%5$s</label>',

                                                /**
                                                 *  1. Field ID
                                                 *  -----------
                                                 */
                                                esc_attr( $id ),

                                                /**
                                                 *  2. Menu Item ID
                                                 *  ---------------
                                                 */
                                                esc_attr( $item_id ),

                                                /**
                                                 *  3. Option Key
                                                 *  -------------
                                                 */
                                                $_option_key,

                                                /**
                                                 *  4. Is Checked ?
                                                 *  ---------------
                                                 */
                                                checked( $field_value, $_option_key, false ),

                                                /**
                                                 *  5. Option Value
                                                 *  ---------------
                                                 */
                                                $_option_value
                                    );
                                }
                            }

                            printf( '<p class="field-%1$s %2$s depth_level_%7$s %1$s"><span>%4$s</span> <br />%5$s </p>',

                                    /**
                                     *  1. Field ID
                                     *  -----------
                                     */
                                    esc_attr( $id ),

                                    /**
                                     *  2. Field Width
                                     *  --------------
                                     */
                                    esc_attr( $width ),

                                    /**
                                     *  3. Menu Item ID
                                     *  ---------------
                                     */
                                    esc_attr( $item_id ),

                                    /**
                                     *  4. Field Lable
                                     *  --------------
                                     */
                                    esc_attr( $label ),

                                    /**
                                     *  5. Dropdown List
                                     *  ----------------
                                     */
                                    $option_string,

                                    /**
                                     *  6. Is Enable ?
                                     *  --------------
                                     */
                                    $field_enable,

                                    /**
                                     *  7. Depth Level
                                     *  --------------
                                     */
                                    $depth_level
                            );
                        }

                        /**
                         *  Is Input Type ?
                         *  ---------------
                         */
                        elseif( $type  ==  esc_attr( 'input' ) ){

                            printf( '<p class="field-%1$s %2$s depth_level_%7$s %1$s">

                                        <label for="%1$s-%3$s"> <span>%4$s</span> <br />

                                            <input type="text" class="widefat code %1$s" id="%1$s-%3$s" name="menu_name_%1$s[%3$s]" value="%5$s" />

                                        </label>

                                    </p>',

                                    /**
                                     *  1. Field ID
                                     *  -----------
                                     */
                                    esc_attr( $id ),

                                    /**
                                     *  2. Field Width
                                     *  --------------
                                     */
                                    esc_attr( $width ),

                                    /**
                                     *  3. Menu Item ID
                                     *  ---------------
                                     */
                                    esc_attr( $item_id ),

                                    /**
                                     *  4. Field Lable
                                     *  --------------
                                     */
                                    esc_attr( $label ),

                                    /**
                                     *  5. Input Value
                                     *  --------------
                                     */
                                    $field_value,

                                    /**
                                     *  6. Is Enable ?
                                     *  --------------
                                     */
                                    $field_enable,

                                    /**
                                     *  7. Depth Level
                                     *  --------------
                                     */
                                    $depth_level
                            );
                        }
                    }
                }
            }

            /**
             *  Menu Items
             *  ----------
             */
            public static function walker_menu_loader( $menu_item ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( [

                    'handler'       =>      [],

                    'fields'        =>      array_column( self:: menu_item_fields(), 'std', 'id'  )

                ] );

                /**
                 *  Have Request
                 *  ------------
                 */
                if( parent:: _is_array( $fields ) ){

                    foreach( $fields as $key => $value ){

                        $handler[ $key ]    =   get_post_meta( $menu_item->ID, '_menu_' . $key, true ) == ''

                                            ?   $value

                                            :   get_post_meta( $menu_item->ID, '_menu_' . $key, true );
                    }
                }

                /**
                 *  Make sure handler exists
                 *  ------------------------
                 */
                if( parent:: _is_array( $handler ) ){

                    return      (object) array_merge( (array) $handler, (array) $menu_item );
                }

                else{

                    return      $menu_item;    
                }
            }

            /**
             *  Save Data
             *  ---------
             */
            public static function walker_menu_save( $menu_id, $menu_item_db_id, $menu_item_data ) {

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( [

                    'handler'       =>      [],

                    'fields'        =>      array_column( self:: menu_item_fields(), 'std', 'id'  )
                ] );

                /**
                 *  Have Request
                 *  ------------
                 */
                if( parent:: _is_array( $fields ) ){

                    foreach( $fields as $key => $value ){

                        if( isset( $_POST[ 'menu_name_' . $key ][ $menu_item_db_id ] ) ){

                            update_post_meta( $menu_item_db_id, '_menu_' . $key, $_POST[ 'menu_name_' . $key ][ $menu_item_db_id ] );
                        }

                        else{

                            update_post_meta( $menu_item_db_id, '_menu_' . $key, 0 );
                        }
                    }
                }
            }

            /**
             *  Menu Object
             *  -----------
             */
            public static function walker_menu_objects( $object, $args ) {

                $main   = ! empty( $args->theme_location ) && esc_attr( 'primary-menu' ) == $args->theme_location   ? true  :   false;

                foreach ( $object as $menu ) {

                    $menu->classes[]    =  'sdweddingdirectory-menu';

                    if ( 0 == $menu->menu_item_parent ) {

                        $menu->classes[] = 'sdweddingdirectory-parent-menu';

                        if( $menu->sdweddingdirectory_menu == 'mega_menu' ){

                            $menu->classes[] = 'has-megamenu';
                        }
                    }
                }

                return $object;
            }

        } /* class end **/

        /**
         *  SDWeddingDirectory - Nav Menu
         *  ---------------------
         */
        SDWeddingDirectory_Nav_Menu:: get_instance();
    }

}
