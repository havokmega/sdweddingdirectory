<?php
/**
 *  SDWeddingDirectory - Tabs Object
 *  ------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Form_Tabs' ) && class_exists( 'SDWeddingDirectory_Form_Fields' ) ) {

    /**
     *  SDWeddingDirectory - Tabs Object
     *  ------------------------
     */
    class SDWeddingDirectory_Form_Tabs extends SDWeddingDirectory_Form_Fields {

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

            if( ! isset( self::$instance ) ){

                self::$instance = new self;
            }

            return  self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

        }

        /**
         *  Tab Layout Class
         *  ----------------
         */
        public static function tab_layout( $layout = 1 ){

            if( $layout == '1' ){

                return  'nav nav-pills mb-3 horizontal-tab-second justify-content-center nav-fill';
            }

            if( $layout == '2' ){

                return  'nav nav-pills theme-tabbing nav-fill';
            }

            if( $layout == '3' ){

                return  'nav flex-column nav-pills theme-tabbing-vertical';
            }
        }

        /**
         *  SDWeddingDirectory - Tabs
         *  -----------------
         */
        public static function _create_tabs( $_tabs = [], $_structure = [] ){

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $_tabs ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $_structure, [

                    'structure_layout'    =>  absint( '1' ),

                    'structure_id'        =>  esc_attr( parent:: _rand() ),

                    'structure_class'     =>  '',

                    'structure_before'    =>  '',

                    'structure_after'     =>  '',

                ] ) );

                /**
                 *  Have Before ?
                 *  -------------
                 */
                if( ! empty( $structure_before ) ){

                    print   $structure_before;
                }

                /**
                 *  Is Tab Layout 3 ?
                 *  -----------------
                 */
                if( $structure_layout == absint( '3' ) ){

                    printf( '<div class="row">

                                <div class="col-12 col-lg-3">
                            
                                    <div class="%1$s %2$s" id="%3$s" 

                                        role="tablist" aria-orientation="vertical">',

                            /**
                             *  1. Class
                             *  --------
                             */
                            self:: tab_layout( $structure_layout ),

                            /**
                             *  2. Have Class ?
                             *  ---------------
                             */
                            $structure_class,

                            /**
                             *  3. ID ?
                             *  -------
                             */
                            $structure_id
                    );
                }

                /**
                 *  Else
                 *  ----
                 */
                else{

                    /**
                     *  Structure Start
                     *  ---------------
                     */
                    printf( '<div class="card-shadow-body p-0">

                                <ul class="%1$s %2$s" id="%3$s" role="tablist">',

                                /**
                                 *  1. Class
                                 *  --------
                                 */
                                self:: tab_layout( $structure_layout ),

                                /**
                                 *  2. Have Class ?
                                 *  ---------------
                                 */
                                $structure_class,

                                /**
                                 *  3. ID ?
                                 *  -------
                                 */
                                $structure_id
                    );
                }


                /**
                 *  Load Tabs
                 *  ---------
                 */
                if( parent:: _is_array( $_tabs ) ){

                    /**
                     *  Have Data ?
                     *  -----------
                     */
                    foreach ( $_tabs as $key => $value) {

                        /**
                         *  Extract
                         *  -------
                         */
                        extract( wp_parse_args( $value, [

                            'id'            =>  '',

                            'li_class'      =>  '',

                            'a_class'       =>  '',

                            'suffix'        =>  '',

                            'prefix'        =>  '',

                            'callback'      =>  [],

                            'name'          =>  '',

                            'active'        =>  false,

                        ] ) );


                        /**
                         *  Is Tab Layout 3 ?
                         *  -----------------
                         */
                        if( $structure_layout == absint( '3' ) ){

                            /**
                             *  Anchore
                             *  -------
                             */
                            printf( '<a class="nav-link %2$s %3$s" id="%4$s-tab" href="#%4$s" aria-controls="%4$s" 

                                        data-bs-toggle="pill" role="tab" aria-selected="true">

                                        %5$s %6$s %7$s

                                    </a>',

                                /**
                                 *  1. Have Li Class ?
                                 *  ------------------
                                 */
                                $li_class,

                                /**
                                 *  2. Have Anchore Class ?
                                 *  -----------------------
                                 */
                                $a_class,

                                /**
                                 *  3. Tab Active
                                 *  -------------
                                 */
                                $active == true     ?   sanitize_html_class( 'active' )     :   '',

                                /**
                                 *  4. Tab ID
                                 *  ---------
                                 */
                                esc_attr( $id ),

                                /**
                                 *  5. Have Prefix ?
                                 *  ----------------
                                 */
                                $prefix,

                                /**
                                 *  6. Tab Name ?
                                 *  -------------
                                 */
                                $name,

                                /**
                                 *  7. Have Suffix ?
                                 *  ----------------
                                 */
                                $suffix
                            );
                        }

                        /**
                         *  Else
                         *  ----
                         */
                        else{

                            /**
                             *  1. Sidebar Tabs Name
                             *  --------------------
                             */
                            printf('<li class="nav-item %1$s">
                                        
                                        <a class="nav-link %2$s %3$s" id="%4$s-tab" 

                                        data-bs-toggle="tab" href="#%4$s" role="tab" aria-controls="%4$s" aria-selected="true">%5$s %6$s %7$s</a>

                                    </li>',

                                /**
                                 *  1. Have Li Class ?
                                 *  ------------------
                                 */
                                $li_class,

                                /**
                                 *  2. Have Anchore Class ?
                                 *  -----------------------
                                 */
                                $a_class,

                                /**
                                 *  3. Tab Active
                                 *  -------------
                                 */
                                $active == true     ?   sanitize_html_class( 'active' )     :   '',

                                /**
                                 *  4. Tab ID
                                 *  ---------
                                 */
                                esc_attr( $id ),

                                /**
                                 *  5. Have Prefix ?
                                 *  ----------------
                                 */
                                $prefix,

                                /**
                                 *  6. Tab Name ?
                                 *  -------------
                                 */
                                esc_attr( $name ),

                                /**
                                 *  7. Have Suffix ?
                                 *  ----------------
                                 */
                                $suffix
                            );
                        }

                    } // end foreach
                }

                /**
                 *  Is Tab Layout 3 ?
                 *  -----------------
                 */
                if( $structure_layout == absint( '3' ) ){

                    ?></div></div><?php

                    printf( '<div class="col-12 col-lg-9 mt-4 mt-lg-0">

                                <div class="card-shadow">

                                    <div class="tab-content" id="%1$s">',

                            /**
                             *  1. Tab Content ID
                             *  -----------------
                             */
                            $structure_id
                    );
                }

                /**
                 *  Else
                 *  ----
                 */
                else{

                    ?></ul></div><?php

                    /**
                     *  Tab Content Start
                     *  -----------------
                     */
                    printf( '<div class="card-shadow-body p-0">
                                
                                <div class="tab-content" id="%1$s">',

                            /**
                             *  1. Tab Content ID
                             *  -----------------
                             */
                            $structure_id
                    );
                }

                /**
                 *  Tabs Content Load
                 *  -----------------
                 */
                if( parent:: _is_array( $_tabs ) ){

                    /**
                     *  Have Data ?
                     *  -----------
                     */
                    foreach ( $_tabs as $key => $value ) {

                        /**
                         *  Extract
                         *  -------
                         */
                        extract( wp_parse_args( $value, [

                            'id'            =>  '',

                            'li_class'      =>  sanitize_html_class( 'nav-item' ),

                            'a_class'       =>  sanitize_html_class( 'nav-link' ),

                            'suffix'        =>  '',

                            'prefix'        =>  '',

                            'callback'      =>  [],

                            'name'          =>  '',

                            'create_form'   =>  [],

                            'active'        =>  false,

                        ] ) );
                        
                        /**
                         *  Tab Content Start
                         *  -----------------
                         */
                        printf( '<div class="tab-pane fade %1$s" id="%2$s" role="tabpanel" aria-labelledby="%2$s-tab">',

                            /**
                             *  1. Tab Active
                             *  -------------
                             */
                            $active == true     ?  esc_attr( 'active show' )     :   '',

                            /**
                             *  2. Tab ID
                             *  ---------
                             */
                            esc_attr( $id )
                        );

                            /**
                             *  Have Form ?
                             *  -----------
                             */
                            if( parent:: _is_array( $create_form ) ){

                                self:: form_start( $create_form );
                            }

                            /**
                             *  Call Back
                             *  ---------
                             */
                            call_user_func( [ $callback['class'], $callback['member'] ] );

                            /**
                             *  Have Form ?
                             *  -----------
                             */
                            if( parent:: _is_array( $create_form ) ){

                                self:: form_end( $create_form );
                            }

                        /**
                         *  Tab End
                         *  -------
                         */
                        ?></div><?php

                    } // end foreach
                }

                /**
                 *  Is Tab Layout 3 ?
                 *  -----------------
                 */
                if( $structure_layout == absint( '3' ) ){

                    ?></div></div></div></div><?php
                }

                /**
                 *  Else
                 *  ----
                 */
                else{

                    ?></div></div><?php
                }

                /**
                 *  Have After ?
                 *  ------------
                 */
                if( ! empty( $structure_after ) ){

                    print   $structure_after;
                }
            }
        }

        /**
         *  Form Content Load ?
         *  -------------------
         */
        public static function _load_form_content( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if ( parent:: _is_array( $args ) ){

                /**
                 *  Have Args ?
                 *  -----------
                 */
                foreach( $args as $key => $value ){

                    /**
                     *  Extract
                     *  -------
                     */
                    extract( $value );

                    /**
                     *  Have Form ?
                     *  -----------
                     */
                    if( parent:: _is_array( $create_form ) ){

                        self:: form_start( $create_form );
                    }

                    /**
                     *  Call Back
                     *  ---------
                     */
                    call_user_func( [ $callback['class'], $callback['member'] ] );

                    /**
                     *  Have Form ?
                     *  -----------
                     */
                    if( parent:: _is_array( $create_form ) ){

                        self:: form_end( $create_form );
                    }
                }
            }
        }

        /**
         *  Form Section Start
         *  ------------------
         */
        public static function form_start( $args = [] ) {

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract 
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'form_before'   =>  '',

                    'form_after'    =>  '',

                    'form_id'       =>  esc_attr( parent:: _rand() ),

                    'form_class'    =>  '',

                    'button_before' =>  '',

                    'button_after'  =>  '',

                    'button_id'     =>  esc_attr( parent:: _rand() ),

                    'button_class'  =>  '',

                    'button_name'   =>  esc_attr__( 'Button Name','sdweddingdirectory' ),

                    'security'      =>  esc_attr( parent:: _rand() ),

                ] ) );

                /**
                 *  Form Before
                 *  -----------
                 */
                if( ! empty( $form_before ) ){

                    print   $form_before;
                }

                /**
                 *  Form Start 
                 *  ----------
                 */
                printf( '<form id="%1$s" class="%2$s" enctype="multipart/form-data" method="post" autocomplete="off" >',

                        /**
                         *  1. Form ID
                         *  ----------
                         */
                        $form_id,

                        /**
                         *  2. Form Class
                         *  -------------
                         */
                        $form_class
                );
            }
        }

        /**
         *  Form Section End
         *  ----------------
         */
        public static function form_end( $args = [] ) {

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract 
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'form_before'       =>  '',

                    'form_after'        =>  '',

                    'form_id'           =>  esc_attr( parent:: _rand() ),

                    'form_class'        =>  '',

                    'button_before'     =>  '',

                    'button_after'      =>  '',

                    'submit_enable'     =>  true,

                    'button_id'         =>  esc_attr( parent:: _rand() ),

                    'button_class'      =>  '',

                    'button_name'       =>  esc_attr__( 'Button Name','sdweddingdirectory' ),

                    'security'          =>  esc_attr( parent:: _rand() ),

                ] ) );

                /**
                 *  Button Before
                 *  -------------
                 */
                if( ! empty( $button_before ) ){

                    print   $button_before;
                }

                /**
                 *  Submit Enable ?
                 *  ---------------
                 */
                if( $submit_enable ){

                    /**
                     *  Form Bottom Section
                     *  -------------------
                     */
                    printf( '<div class="card-body border-top">

                                <div class="row">

                                    <div class="col-md-12">

                                        <button type="submit" id="%1$s" name="%1$s" class="btn btn-primary btn-rounded %2$s">%3$s</button> 

                                        %4$s

                                    </div>

                                </div>

                            </div>',

                        /**
                         *  1. Button ID
                         *  ------------
                         */
                        esc_attr( $button_id ),

                        /**
                         *  2. Button Class
                         *  ---------------
                         */
                        esc_attr( $button_class ),

                        /**
                         *  3. Button Name
                         *  --------------
                         */
                        esc_attr( $button_name ),

                        /**
                         *  4. Security
                         *  -----------
                         */
                        wp_nonce_field( $security,  $security, true, false )
                    );                    
                }

                /**
                 *  Button After
                 *  -------------
                 */
                if( ! empty( $button_after ) ){

                    print   $button_after;
                }

                ?></form><?php

                /**
                 *  Form Before
                 *  -----------
                 */
                if( ! empty( $form_after ) ){

                    print   $form_after;
                }
            }
        }
    }

    /**
     *  SDWeddingDirectory - Tabs Object
     *  ------------------------
     */
    SDWeddingDirectory_Form_Tabs::get_instance();
}