<?php
/**
 *  SDWeddingDirectory Form Fields
 *  ----------------------
 */
if ( ! class_exists('SDWeddingDirectory_Form_Fields') && class_exists( 'SDWeddingDirectory_Config' ) ) {

    /**
     *  SDWeddingDirectory Form Fields
     *  ----------------------
     */
    class SDWeddingDirectory_Form_Fields extends SDWeddingDirectory_Config {

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

            if ( ! isset ( self::$instance ) ) {

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
         *  Div Start
         *  ---------
         */
        public static function div_start( $args = [] ) {

            if( parent:: _is_array( $args ) && $args[ 'start' ] == true ){

                extract( $args );

                /**
                 *  Row Setup
                 *  ---------
                 */
                return  sprintf( '<div class="%1$s" %2$s>',

                            /**
                             *  1. Have Extra Class ?
                             *  ---------------------
                             */
                            ( isset( $class ) && $class !== '' )

                            ?   esc_attr( $class )

                            :   '',

                            /**
                             *  2. Have ID ?
                             *  ------------
                             */
                            ( isset( $id ) && $id !== '' )

                            ?   sprintf( 'id="%1$s"', esc_attr( $id ) )

                            :   ''
                        );
            }
        }

        /**
         *  Div End
         *  -------
         */
        public static function div_end( $args = [] ) {

            if( parent:: _is_array( $args ) && $args[ 'end' ] == true ){

                return  '</div>';
            }
        }

        /**
         *  Row Start
         *  ---------
         */
        public static function row_start( $args = [] ) {

            if( parent:: _is_array( $args ) && $args[ 'start' ] == true ){

                extract( $args );

                /**
                 *  Row Setup
                 *  ---------
                 */
                return  sprintf( '<div class="row %1$s" %2$s>',

                            /**
                             *  1. Have Extra Class ?
                             *  ---------------------
                             */
                            ( isset( $class ) && $class !== '' )

                            ?   esc_attr( $class )

                            :   '',

                            /**
                             *  2. Have ID ?
                             *  ------------
                             */
                            ( isset( $id ) && $id !== '' )

                            ?   sprintf( 'id="%1$s"', esc_attr( $id ) )

                            :   ''
                        );
            }
        }

        /**
         *  Row End
         *  -------
         */
        public static function row_end( $args = [] ) {

            if( parent:: _is_array( $args ) && $args[ 'end' ] == true ){

                return  '</div>';
            }
        }

        /**
         *  Column Start
         *  ------------
         */
        public static function column_start( $args = [] ) {

            if( parent:: _is_array( $args ) && $args[ 'start' ] == true ){

                /**
                 *  Row Setup
                 *  ---------
                 */
                return  sprintf( '<div class="%1$s %2$s" %3$s>',

                            /**
                             *  1. Have ID ?
                             *  ------------
                             */
                            self:: grid_setup( $args[ 'grid' ] ),

                            /**
                             *  1. Have Extra Class ?
                             *  ---------------------
                             */
                            isset( $args[ 'class' ] ) && $args[ 'class' ] !== ''    

                            ?   esc_attr( $args[ 'class' ] )

                            :   '',

                            /**
                             *  3. Have ID ?
                             *  ------------
                             */
                            ( isset( $args[ 'id' ] ) && $args[ 'id' ] !== '' )

                            ?   sprintf( 'id="%1$s"', esc_attr( $args[ 'id' ] ) )

                            :   ''
                        );
            }
        }

        /**
         *  Column End
         *  ----------
         */
        public static function column_end( $args = [] ) {

            if( parent:: _is_array( $args ) && $args[ 'end' ] == true ){

                return  '</div>';
            }
        }

        /**
         *  Grid - Column ( SETUP )
         *  -----------------------
         */
        public static function grid_setup( $grid = '' ) {

            /**
             *  Handler
             *  -------
             */
            $handler        =       '';

            if ( $grid == absint( '12' ) ) {

                $handler    =      'col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12';
            } 

            elseif ( $grid == absint( '6') )  {

                $handler    =      'col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12';
            } 

            elseif ( $grid == absint( '4') )  {

                $handler    =      'col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12';
            }

            elseif ( $grid == absint( '3') )  {

                $handler    =      'col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12';
            }

            elseif ( $grid == absint( '2') )  {

                $handler    =      'col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12';
            }

            elseif ( $grid == absint( '8') )  {

                $handler    =      'col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12';
            }

            else {

                if( ! empty( $grid ) ){

                    $handler        =       $grid;
                }

                else{

                    $handler        =       self:: grid_setup( '12' );
                }
            }

            /**
             *  Return Grid
             *  -----------
             */
            return          $handler;
        }

        /**
         *  Setup Select Option
         *  -------------------
         */
        public static function create_select_option( $args = [] ){

            $defaults = array(

                'div'               =>      [],

                'row'               =>      [],

                'column'            =>      [],

                'id'                =>      '',

                'class'             =>      '',

                'name'              =>      '',

                'options'           =>      '',

                'echo'              =>      false,

                'require'           =>      false,

                'formgroup'         =>      true,

                'layout'            =>      '',

                'label'             =>      '',
            );

            $args   =   wp_parse_args( $args, $defaults );

            extract( $args );

            $data   =   '';

            $data   .=   self:: _div_start_setup( $args );

            /**
             *  Select Option Setup
             *  -------------------
             */
            $data   .=

            sprintf('<div class="mb-3 %10$s">

                        %3$s

                        <select id="%1$s" name="%2$s" %9$s class="mb20 %4$s %11$s" %5$s %6$s>%7$s</select>

                        %8$s

                    </div>',

                /**
                 *  1. Have ID ?
                 *  ------------
                 */
                ( isset( $id )  && $id !== '' )

                ?   esc_attr( $id )

                :   '',

                /**
                 *  2. Have Name ?
                 *  --------------
                 */
                ( isset( $name )  && $name !== '' )

                ?   esc_attr( $name )

                :   '',

                /**
                 *  3. Have Label ?
                 *  ---------------
                 */
                ! empty( $label )

                ?   sprintf( '<label class="control-label mb-2" for="%2$s">%1$s</label>',

                        /**
                         *  1. Lable
                         *  --------
                         */
                        esc_attr( $label ),

                        /**
                         *  2. ID
                         *  -----
                         */
                        esc_attr( $id )
                    )

                :   '',

                /**
                 *  4. Have Class ?
                 *  ---------------
                 */
                ( isset( $class ) && $class !== '' ) 

                ?   esc_attr( $class )

                :   '',

                /**
                 *  5. Have Data Taxonomy Option ?
                 *  ------------------------------
                 */
                ( isset( $data_taxonomy ) && $data_taxonomy !== '' )

                ?   sprintf( 'data-taxonomy="%1$s"', $data_taxonomy )

                :   '',

                /**
                 *  6. Is Required Field ? 
                 *  ----------------------
                 */
                ( isset( $require ) && $require == true ) 

                ?   esc_attr( 'required' )

                :   '',

                /**
                 *  7. Have Option ?
                 *  ----------------
                 */
                ( isset( $options ) && $options !== '' )

                ?   $options

                :   '',

                /**
                 *  8. Have Description ?
                 *  ---------------------
                 */
                ( isset( $description ) && $description !== '' )

                ?   sprintf( '<small>%1$s</small>', $description )

                :   '',

                /**
                 *  9. Have Placeholder ?
                 *  ---------------------
                 */
                ( isset( $placeholder ) && $placeholder !== '' )

                ?   sprintf( 'data-placeholder="%1$s"', $placeholder )

                :   '',

                /**
                 *  10. Have Form Group ?
                 *  ---------------------
                 */
                isset( $formgroup ) && $formgroup !== ''

                ?   sanitize_html_class( 'formgroup' )

                :   '',

                /**
                 *  11. Layout
                 *  ----------
                 */
                self:: _select_option_layout( $layout )
            );

            $data   .=   self:: _div_end_setup( $args );

            /**
             *  Is print ?
             *  ----------
             */
            if( $echo ){

                print  $data;

            }else{

                return  $data;
            }
        }

        /**
         *  Select Option Layout
         *  --------------------
         */
        public static function _select_option_layout( $layout ){

            /**
             *  Make sure it's not empty!
             *  -------------------------
             */
            if( empty( $layout ) ){

                return;
            }

            elseif( $layout == absint( '1' ) ){

                return      sanitize_html_class( 'sdweddingdirectory-dark-select' );
            }

            elseif( $layout == absint( '2' ) ){

                return      sanitize_html_class( 'sdweddingdirectory-light-select ' );
            }
        }

        /**
         *  Input Field Start
         *  -----------------
         */
        public static function section_start( $args = [] ) {

            /**
             *  Default Array
             *  -------------
             */
            $defaults       = array(

                'lable'         => '',

                'icon'          => '',
            );

            $args = wp_parse_args( $args, $defaults);

            extract( $args );


            ?><div class="card-shadow"><?php

                /**
                 *  Create Title + Icon Section
                 *  ---------------------------
                 */
                if( isset( $label ) && $label !== '' ){

                    printf('<div class="card-shadow-header p-0 d-flex align-items-center">
                                %1$s <span class="head-simple">%2$s</span>
                            </div>',

                        /**
                         *  1. Section Icon
                         *  ---------------
                         */
                        ( isset( $icon ) && $icon !== '' )

                        ?   sprintf( '<span class="budget-head-icon"> <i class="%1$s"></i></span>',

                                /**
                                 *  1. Icon
                                 *  -------
                                 */
                                esc_attr( $icon )
                            )

                        :   '',

                        /**
                         *  2. Section Title
                         *  ----------------
                         */
                        esc_attr( $label )
                    );
                }

            ?><div class="card-shadow-body p-3"><?php
        }

        /**
         *  Input Field Start
         *  -----------------
         */
        public static function section_end() {

            ?></div></div><?php
        }

        /**
         *  Setup Section
         *  -------------
         */
        public static function setup_section_info( $args = [] ){

            if( parent:: _is_array( $args ) ){

                /**
                 *  Default Array
                 *  -------------
                 */
                $defaults       =   array(

                    'div'           =>  [],

                    'row'           =>  [],

                    'column'        =>  [],

                    'class'       =>   '',

                    'title'       =>   '',

                    'echo'        =>   false,
                );

                $args = wp_parse_args( $args, $defaults );

                extract( $args );

                $data   =   '';

                $data   .=   self:: _div_start_setup( $args );

                $data   .=   

                sprintf( '<div class="todo-subhead %1$s" %2$s><h3>%3$s</h3></div>',

                        /**
                         *  1. Have Extra Class ?
                         *  ---------------------
                         */
                        ( isset( $class ) && $class !== '' ) 

                        ?   esc_attr( $class )

                        :   '',

                        /**
                         *  2. Have ID ?
                         *  ------------
                         */
                        ( isset( $id )  && $id !== '' )

                        ?   sprintf( 'id="%1$s"', esc_attr( $id ) )

                        :   '',

                        /**
                         *  3. Have Title ?
                         *  ---------------
                         */
                        ( isset( $title ) && $title !== '' ) 

                        ?   esc_attr( $title )

                        :   ''
                );

                $data   .=   self:: _div_end_setup( $args );


                if( $echo ){

                    print  $data;

                }else{

                    return  $data;
                }
            }
        }

        /**
         *  PDF File Upload Field
         *  ---------------------
         */
        public static function pdf_upload_field( $args = [] ) {

            /**
             *  Extract Args
             *  ------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Default Set Parameter
                 *  ---------------------
                 */
                $args       =       wp_parse_args(  $args, [

                                        'div'                       =>      [],

                                        'row'                       =>      [],

                                        'column'                    =>      [],

                                        'echo'                      =>      false,

                                        'menu_file'                 =>      absint( '0' ),

                                        'media_frame_heading'       =>      esc_attr__( 'Select / Upload PDF File', 'sdweddingdirectory' ),

                                        'media_frame_button'        =>      esc_attr__( 'Select File', 'sdweddingdirectory' ),

                                        'allowed_type'              =>      esc_attr( 'application' ),

                                        'data'                      =>      '',

                                        'unique_id'                 =>      esc_attr( parent:: _rand() )

                                    ] );

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $args );

                /**
                 *  Have Before Div ?
                 *  -----------------
                 */
                $data       .=      self:: _div_start_setup( $args );

                /**
                 *  PDF Upload Fields
                 *  -----------------
                 */
                $data       .=      sprintf(    '<div class="card upload-menu-file-section">

                                                    <div class="card-body text-center pt-4">

                                                        <a  href="javascript:" id="button_id_%1$s" class="btn upload-multiple-pdf-file upload-menu-file"

                                                            data-file-id="file_id_%1$s" 

                                                            data-file-name="file_name_%1$s"

                                                            data-frame-name="%4$s"

                                                            data-frame-button="%5$s"

                                                            data-allowed-type="%6$s"

                                                        ><i class="fa fa-pencil"></i></a>

                                                        <i class="fa fa-4x fa-file-pdf-o text-danger"></i>

                                                    </div>

                                                    <div class="card-footer text-center">

                                                        <span class="document-name" id="file_name_%1$s">%2$s</span>

                                                        <input autocomplete="off" type="hidden" id="file_id_%1$s" class="pdf-file menu_file" value="%3$s" />

                                                    </div>

                                                </div>',

                                                /**
                                                 *  1. Button ID
                                                 *  ------------
                                                 */
                                                esc_attr( $unique_id ),

                                                /**
                                                 *  2. Translation Ready String
                                                 *  ---------------------------
                                                 */
                                                parent:: _have_data( $menu_file ) && parent:: _have_media( $menu_file )

                                                ?   basename( get_attached_file( $menu_file ) )

                                                :   esc_attr__( 'Upload File..', 'sdweddingdirectory' ),

                                                /**
                                                 *  3. Media ID
                                                 *  -----------
                                                 */
                                                absint( $menu_file ),

                                                /**
                                                 *  4. Media Frame Heading
                                                 *  ----------------------
                                                 */
                                                esc_attr( $media_frame_heading ),

                                                /**
                                                 *  5. Media Frame Button Text
                                                 *  --------------------------
                                                 */
                                                esc_attr( $media_frame_button ),

                                                /** 
                                                 *  6. Allowed Type
                                                 *  ---------------
                                                 */
                                                esc_attr( $allowed_type )
                                    );

                /**
                 *  Have After Setup ?
                 *  ------------------
                 */
                $data           .=      self:: _div_end_setup( $args );

                /**
                 *  Print ?
                 *  -------
                 */
                if( $echo ){

                    print       $data;
                }

                /**
                 *  Return
                 *  ------
                 */
                else{

                    return      $data;
                }
            }
        }

        /**
         *   Get Field Type to Call Back Function
         *   ------------------------------------
         */
        public static function call_field_type( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $args );

                /**
                 *  Call back function
                 *  ------------------
                 */
                if( $field_type == 'input' ){

                    return      self:: create_input_field( $args );
                }

                elseif( $field_type == 'input_group' ){

                    return      self:: create_input_group_field( $args );
                }

                elseif( $field_type == 'select' ){

                    return      self:: create_select_option( $args );
                }

                elseif( $field_type == 'multiple_select' ){

                    return      apply_filters( 'sdweddingdirectory/field/multiple-select', $args );
                }

                elseif( $field_type == 'select-option' ){

                    return      apply_filters( 'sdweddingdirectory/field/select-option', $args );
                }

                elseif( $field_type == 'info' ){

                    return      self:: setup_section_info( $args );
                }

                elseif( $field_type == 'checkbox' ){

                    return      apply_filters( 'sdweddingdirectory/field/checkbox', $args );
                }

                elseif( $field_type == 'textarea' ){

                    return      self:: create_textarea( $args );
                }

                elseif( $field_type == 'textarea-simple' ){

                    return      self:: create_textarea_simple( $args );
                }

                elseif( $field_type == 'single_img_upload' ){

                    return      apply_filters( 'sdweddingdirectory/field/single-media', $args );
                }

                elseif( $field_type == 'gallery_img_upload' ){

                    return      apply_filters( 'sdweddingdirectory/field/gallery-upload', $args );
                }

                elseif( $field_type == 'find_map_with_address' ){

                    return      self:: find_map_with_address( $args );
                }

                elseif( $field_type == 'password' ){

                    return      self:: create_password_field( $args );
                }

                elseif( $field_type == 'select-location' ){

                    return      apply_filters( 'sdweddingdirectory/field/input-location', $args );
                }

                elseif( $field_type == 'calendar' ){

                    return      self:: create_calendar_field( $args );
                }

                elseif( $field_type == 'radio' ){

                    return      self:: create_radio_field( $args );
                }
            }
        }

        /**
         *  Create Section
         *  --------------
         */
        public static function create_section( $args = [] ) {

            if( parent:: _is_array( $args ) ){

                /**
                 *  Default Attributes
                 *  ------------------
                 */
                $defaults   = array(

                    'div'       =>  [],

                    'row'       =>  [],

                    'column'    =>  [],

                    'field'     =>  [],
                );

                $args   =   wp_parse_args( $args, $defaults );

                extract( $args );

                $data   =   '';

                $data   .=   self:: _div_start_setup( $args );

                /**
                 *  Setup Select Option
                 *  -------------------
                 */
                if ( parent:: _is_array( $field ) ){

                    $data     .=   self:: call_field_type( $field );
                }

                $data   .=   self:: _div_end_setup( $args );

                /**
                 *  Print Data ?
                 *  ------------
                 */
                print  $data;
            }
        }

        /**
         *  Setup Input Field
         *  -----------------
         */
        public static function create_input_field( $args = [] ) {

            /**
             *  Default Args
             *  ------------
             */
            $defaults       =   array(

                'div'           =>  [],

                'row'           =>  [],

                'column'        =>  [],

                'name'          =>  '',

                'label'         =>  '',

                'placeholder'   =>  '',

                'require'       =>  false,

                'value'         =>  '',

                'class'         =>  '',

                'type'          =>  esc_attr( 'text' ),

                'disable'       =>  false,

                'formgroup'     =>  true,

                'echo'          =>  false,

                'limit'         =>  '',

                'attr'          =>  '',

                'pattern'       =>  '',

                'title'         =>  '',
            );

            /**
             *  Merge Args
             *  ----------
             */
            $args = wp_parse_args( $args, $defaults);

            extract( $args );

            /**
             *  Create Field
             *  ------------
             */
            $data   =   '';

            $data   .=   self:: _div_start_setup( $args );

            $data   .=   

            sprintf('<div class="%11$s">

                        %2$s

                        <input autocomplete="off" id="%1$s" name="%3$s" value="%4$s" type="%5$s" placeholder="%6$s" 
                                class="form-control input-md %7$s %10$s" %8$s %9$s %12$s %13$s %14$s %15$s>

                    </div>',

                /**
                 *  1. Input ID
                 *  -----------
                 */
                $id,

                /**
                 *  2. Input Label
                 *  --------------
                 */
                ! empty( $label )

                ?   sprintf( '<label class="control-label mb-2" for="%2$s">%1$s</label>',

                        /**
                         *  1. Lable
                         *  --------
                         */
                        esc_attr( $label ),

                        /**
                         *  2. ID
                         *  -----
                         */
                        esc_attr( $id )
                    )

                :   '',

                /**
                 *  3. Input Name
                 *  -------------
                 */
                $name,

                /**
                 *  4. Input Value
                 *  --------------
                 */
                $value,

                /**
                 *  5. Input Value
                 *  --------------
                 */
                $type,

                /**
                 *  6. Input Placeholder
                 *  --------------------
                 */
                $placeholder,

                /**
                 *  7. Input Field Class
                 *  --------------------
                 */
                $class,

                /**
                 *  8. Input Field Requird ?
                 *  ------------------------
                 */
                $require

                ?   esc_attr( 'required' )

                :   '',

                /**
                 *  9. Is Disable ?
                 *  ------------
                 */
                $disable    ?   esc_attr( 'disabled' )      :   '',

                /**
                 *  10 If is datepicker update unique class
                 *  ---------------------------------------
                 */
                $type == esc_attr( 'date' )

                ?   sanitize_html_class( 'sdweddingdirectory_datepicker' )

                :   '',

                /**
                 *  11. Have From Group Class
                 *  -------------------------
                 */
                $formgroup

                ?   sanitize_html_class( 'mb-3' )

                :   '',

                /**
                 *  12. Have From Group Class
                 *  -------------------------
                 */
                !   empty( $limit )

                ?   sprintf( 'maxlength="%1$s"', absint( $limit ) )

                :   '',

                /**
                 *  13. Have Extra Attr
                 *  -------------------
                 */
                !   empty( $attr )

                ?   $attr

                :   '',

                /**
                 *  14. Have Pattern
                 *  ----------------
                 */
                !   empty( $pattern )

                ?   sprintf( 'pattern="%1$s"', $pattern )

                :   '',

                /**
                 *  15. Have Alert Title
                 *  --------------------
                 */
                !   empty( $title )

                ?   sprintf( 'title="%1$s"', $title )

                :   ''
            );

            $data   .=   self:: _div_end_setup( $args );

            if( $echo ){

                print   $data;

            }else{

                return  $data;
            }
        }

        /**
         *  Setup Input Field
         *  -----------------
         */
        public static function create_input_group_field( $args = [] ) {

            /**
             *  Default Args
             *  ------------
             */
            $defaults       =   array(

                'div'           =>  [],

                'row'           =>  [],

                'column'        =>  [],

                'name'          =>  '',

                'lable'         =>  '',

                'placeholder'   =>  '',

                'require'       =>  false,

                'value'         =>  '',

                'class'         =>  '',

                'type'          =>  esc_attr( 'text' ),

                'disable'       =>  false,

                'formgroup'     =>  true,

                'echo'          =>  false,

                'limit'         =>  '',

                'before_input'  =>  '',

                'after_input'   =>  ''
            );

            /**
             *  Merge Args
             *  ----------
             */
            $args = wp_parse_args( $args, $defaults); 

            extract( $args );

            /**
             *  Create Field
             *  ------------
             */
            $data   =   '';

            $data   .=   self:: _div_start_setup( $args );

            $data   .=   

            sprintf('<div class="%11$s">

                        %2$s

                        <input autocomplete="off" id="%1$s" name="%3$s" value="%4$s" type="%5$s" placeholder="%6$s" 
                                class="form-control input-md %7$s %10$s" %8$s %9$s %12$s>

                        %13$s

                    </div>',

                /**
                 *  1. Input ID
                 *  -----------
                 */
                !   empty( $id )

                ?   esc_attr( $id )

                :   '',

                /**
                 *  2. Input Label
                 *  --------------
                 */
                !   empty( $before_input )

                ?   $before_input

                :   '',

                /**
                 *  3. Input Name
                 *  -------------
                 */
                !   empty( $name )

                ?   esc_attr( $name )

                :   '',

                /**
                 *  4. Input Value
                 *  --------------
                 */
                !   empty( $value )

                ?   esc_attr( $value )

                :   '',

                /**
                 *  5. Input Value
                 *  --------------
                 */
                parent:: _have_data( $type ) && $type !== esc_attr( 'date' )

                ?   esc_attr( $type )

                :   esc_attr( 'text' ),

                /**
                 *  6. Input Placeholder
                 *  --------------------
                 */
                !   empty( $placeholder )

                ?   esc_attr( $placeholder )

                :   '',

                /**
                 *  7. Input Field Class
                 *  --------------------
                 */
                !   empty( $class )

                ?   esc_attr( $class )

                :   '',

                /**
                 *  8. Input Field Requird ?
                 *  ------------------------
                 */
                $require    ==      true

                ?   esc_attr( 'required' )

                :   '',

                /**
                 *  9. Is Disable ?
                 *  ------------
                 */
                $disable    ==      true

                ?   esc_attr( 'disabled' )

                :   '',

                /**
                 *  10 If is datepicker update unique class
                 *  ---------------------------------------
                 */
                $type       ==      esc_attr( 'date' )

                ?   sanitize_html_class( 'sdweddingdirectory_datepicker' )

                :   '',

                /**
                 *  11. Have From Group Class
                 *  -------------------------
                 */
                apply_filters( 'sdweddingdirectory/class', explode( ' ', 'input-group mb-3' ) ),

                /**
                 *  12. Have From Group Class
                 *  -------------------------
                 */
                !   empty( $limit )

                ?   sprintf( 'maxlength="%1$s"', absint( $limit ) )

                :   '',

                /**
                 *  13. Input Label
                 *  ---------------
                 */
                !   empty( $after_input )

                ?   $after_input

                :   ''
            );

            /**
             *  After Setup End of Element
             *  --------------------------
             */
            $data       .=      self:: _div_end_setup( $args );

            /**
             *  Print ?
             *  -------
             */
            if( $echo ){

                print   $data;
            }

            /**
             *  Return Data
             *  -----------
             */
            else{

                return  $data;
            }
        }

        /**
         *  Setup Input Radio Field
         *  -----------------------
         */
        public static function create_radio_field( $args = [] ) {

            /**
             *  Default Args
             *  ------------
             */
            $defaults       =   array(

                'div'           =>  [],

                'row'           =>  [],

                'column'        =>  [],

                'name'          =>  '',

                'label'         =>  '',

                'placeholder'   =>  '',

                'require'       =>  false,

                'value'         =>  '',

                'class'         =>  '',

                'type'          =>  esc_attr( 'text' ),

                'disable'       =>  false,

                'formgroup'     =>  true,

                'echo'          =>  false,

                'limit'         =>  '',

                'before_input'  =>  '',

                'after_input'   =>  '',

                'options'       =>  [],

                'checked'       =>  '',
            );

            /**
             *  Merge Args
             *  ----------
             */
            $args = wp_parse_args( $args, $defaults); 

            extract( $args );

            /**
             *  Create Field
             *  ------------
             */
            $data   =   '';

            $data   .=   self:: _div_start_setup( $args );

            /**
             *  Have Options ?
             *  --------------
             */
            if( parent:: _is_array( $options ) ){

                /**
                 *  Have label ?
                 *  ------------
                 */
                if( ! empty( $label ) ){

                    $data   .=      sprintf(    '<p class="text-start"><strong><small class="txt-orange">%1$s</small></strong></p>', 

                                                /**
                                                 *  1. Label
                                                 *  --------
                                                 */
                                                esc_attr( $label )
                                    );
                }

                $data   .=

                sprintf( '<div class="d-flex justify-content-between mb-3 %1$s">', 

                    /**
                     *  1. Have Class ?
                     *  ---------------
                     */
                    $class
                );

                foreach( $options as $key => $value ){

                    /**
                     *  Contact Couple Option : Email
                     *  -----------------------------
                     */
                    $data   .=      sprintf(    '<div class="form-check form-check-inline">

                                                    <input autocomplete="off" type="radio" class="form-check-input" %4$s 

                                                    name="%2$s" id="%1$s" value="%3$s" />

                                                    <label class="form-check-label" for="%1$s">%3$s</label>
                                                
                                                </div>',

                                            /**
                                             *  1. Field ID
                                             *  -----------
                                             */
                                            esc_attr( parent:: _rand() ),

                                            /**
                                             *  2. Field ID
                                             *  -----------
                                             */
                                            esc_attr( $name ),

                                            /**
                                             *  3. Placeholder : Translation Ready String
                                             *  -----------------------------------------
                                             */
                                            esc_attr( $value ),

                                            /**
                                             *  4. Have Checked ?
                                             *  -----------------
                                             */
                                            $key  ==  $checked 

                                            ?   'checked="checked"'

                                            :   ''
                                    );
                }

                $data   .=      '</div>';
            }

            $data   .=   self:: _div_end_setup( $args );

            if( $echo ){

                print   $data;

            }else{

                return  $data;
            }
        }

        /**
         *  Simple Textarea
         *  ---------------
         */
        public static function create_textarea_simple( $args = [] ) {

            $defaults = array(

                'div'           =>  [],

                'row'           =>  [],

                'column'        =>  [],

                'name'          =>  '',

                'id'            =>  '',

                'lable'         =>  '',

                'placeholder'   =>  '',

                'require'       =>  false,

                'value'         =>  '',

                'class'         =>  '',

                'description'   =>  '',

                'limit'         =>  '',

                'rows'          =>  absint( '6' ),

                'echo'          =>  false,

                'formgroup'     =>  true
            );

            $args = wp_parse_args( $args, $defaults );

            extract( $args );

            $data   =   '';

            $data   .=   self:: _div_start_setup( $args );

            $data   .=   

            sprintf('<div class="%13$s">

                        %2$s %7$s

                        <textarea class="%5$s form-control %9$s" id="%1$s" %10$s name="%8$s" rows="%12$s" placeholder="%4$s" %6$s>%3$s</textarea>

                        %11$s

                    </div>',

                /**
                 *  1. Have ID ?
                 *  ------------
                 */
                ( isset( $id ) && $id !== '' )

                ?   $id

                :   '',

                /**
                 *  2. Have Label ?
                 *  ---------------
                 */
                isset( $label ) && ! empty( $label )

                ?   sprintf('<label class="control-label mb-2" for="%2$s">%1$s</label>', esc_attr($label), esc_attr($id) )

                :   '',

                /**
                 *  3. Have Value ?
                 *  ---------------
                 */
                ( isset( $value ) && $value !== '' )

                ?   $value

                :   '',

                /**
                 *  4. Have Placeholder ?
                 *  ---------------------
                 */
                ( isset( $placeholder ) && $placeholder !== '' )

                ?   $placeholder

                :   '',

                /**
                 *  5. Have Class ?
                 *  ---------------
                 */
                ( isset( $class ) && $class !== '' )

                ?   $class

                :   '',

                /**
                 *  6. Is Required Fields
                 *  ------------------
                 */
                ( isset( $require ) && $require == true) 

                ?   esc_attr( 'required' ) 

                :   '',

                /**
                 *  7. Have Description ?
                 *  ---------------------
                 */
                (  isset( $description ) && $description !== '' )

                ?   sprintf( '<small>%1$s</small>', $description )

                :   '',

                /**
                 *  8. Have Name ?
                 *  --------------
                 */
                ( isset( $name ) && $name !== '' )

                ?   $name

                :   '',

                /**
                 *  9. Have Character Limit ?
                 *  -------------------------
                 */
                ( isset( $limit ) && parent:: _have_data( $limit ) )

                ?   sanitize_html_class( 'sdweddingdirectory-textarea-limit' )

                :   '',

                /**
                 *  10. Have Character Limit ?
                 *  -------------------------
                 */
                ( isset( $limit ) && parent:: _have_data( $limit ) )

                ?   sprintf( 'maxlength="%1$s" data-limit="%1$s"', absint( $limit ) )

                :   '',

                /**
                 *  11. Have Character Limit ?
                 *  -------------------------
                 */
                ( isset( $limit ) && parent:: _have_data( $limit ) )

                ?   sprintf( '<span class="alert alert-dark textarea count_message">%1$s</span>',

                        /**
                         *  1. Limit
                         *  --------
                         */
                        absint( $limit )
                    )

                :   '',

                /**
                 *  12. Have Rows ?
                 *  ---------------
                 */
                ( isset( $rows ) && $rows !== '' )

                ?   $rows

                :   absint( '6' ),

                /**
                 *  13. Have Form Group ?
                 *  ---------------------
                 */
                $formgroup

                ?   sanitize_html_class( 'mb-3' )

                :   ''
            );

            $data   .=   self:: _div_end_setup( $args );

            if( $echo ){

                print   $data;

            }else{

                return  $data;
            }
        }

        /**
         *  Editor
         *  ------
         */
        public static function create_textarea( $args = [] ) {

            $defaults = array(

                'div'           =>  [],

                'row'           =>  [],

                'column'        =>  [],

                'name'          =>  '',

                'id'            =>  esc_attr( parent:: _rand() ),

                'lable'         =>  '',

                'placeholder'   =>  '',

                'require'       =>  false,

                'value'         =>  '',

                'class'         =>  '',

                'description'   =>  '',

                'formgroup'     =>  true,

                'height'        =>  '',

                'limit'         =>  absint( '3000' ),

                'echo'          =>  false,
            );

            $args = wp_parse_args( $args, $defaults );

            extract( $args );

            $data   =   '';

            $data   .=   self:: _div_start_setup( $args );

            /**
             *  Field
             *  -----
             */
            $data     .=

            sprintf('<div class="%9$s">

                        %2$s %7$s

                        <textarea class="summerynotes sdweddingdirectory-editor %5$s" id="%1$s" name="%8$s" rows="6" placeholder="%4$s" 

                            data-height="%10$s" data-limit="%11$s" %6$s>%3$s</textarea>

                            <div class="mt-2">

                                <span id="%1$s-limit" class="alert alert-dark sdweddingdirectory-editor count_message">%11$s</span>

                            </div>

                    </div>',

                /**
                 *  1. Have ID ?
                 *  ------------
                 */
                esc_attr( $id ),

                /**
                 *  2. Have Label ?
                 *  ---------------
                 */
                ( isset( $label ) && $label !== '' && isset( $id ) && $id !== '' )

                ?   sprintf('<label class="control-label mb-2" for="%2$s">%1$s</label>', esc_attr($label), esc_attr($id) )

                :   '',

                /**
                 *  3. Have Value ?
                 *  ---------------
                 */
                ( isset( $value ) && $value !== '' )

                ?   $value

                :   '',

                /**
                 *  4. Have Placeholder ?
                 *  ---------------------
                 */
                ( isset( $placeholder ) && $placeholder !== '' )

                ?   $placeholder

                :   '',

                /**
                 *  5. Have Class ?
                 *  ---------------
                 */
                ( isset( $class ) && $class !== '' )

                ?   $class

                :   '',

                /**
                 *  6. Is Required Fields
                 *  ------------------
                 */
                ( isset( $require ) && $require == true) 

                ?   esc_attr( 'required' ) 

                :   '',

                /**
                 *  7. Have Description ?
                 *  ---------------------
                 */
                (  isset( $description ) && $description !== '' )

                ?   sprintf( '<small>%1$s</small>', $description )

                :   '',

                /**
                 *  8. Have Name ?
                 *  --------------
                 */
                ( isset( $name ) && $name !== '' )

                ?   $name

                :   '',

                /**
                 *  9. Have From Group Class
                 *  ------------------------
                 */
                $formgroup      ==      true

                ?   sanitize_html_class( 'mb-3' )

                :   '',

                /**
                 *  10. Have Height ?
                 *  -----------------
                 */
                !   empty( $height )

                ?   absint( $height )

                :   '',

                /**
                 *  11. Have Editor Character Limit ?
                 *  --------------------------------
                 */
                !   empty( $limit )

                ?   absint( $limit )

                :   '',

                /**
                 *  12. Value in limit
                 *  ------------------
                 */
                $value !== '' && $value >= $limit

                ?   sprintf( '<span style="color:green;">%1$s</span>', absint( strlen( trim( $value ) ) ) )

                :   '',

                /**
                 *  13. value out of limit
                 *  ----------------------
                 */
                $value !== '' && $value <= $limit

                ?   sprintf( '<span style="color:red;">%1$s</span>', absint( strlen( trim( $value ) ) ) )

                :   ''
            );

            $data   .=   self:: _div_end_setup( $args );

            /**
             *  Is pring ?
             *  ----------
             */
            if( $echo ){

                print   $data;

            }else{

                return  $data;
            }
        }

        /**
         *  Setup Password Field
         *  --------------------
         */
        public static function create_password_field( $args = [] ) {

            /**
             *  Default Args
             *  ------------
             */
            $defaults               =       array(

                'id'                =>      '',

                'div'               =>      [],

                'row'               =>      [],

                'column'            =>      [],

                'name'              =>      '',

                'lable'             =>      '',

                'placeholder'       =>      '',

                'require'           =>      false,

                'value'             =>      '',

                'class'             =>      '',

                'type'              =>      esc_attr( 'password' ),

                'disable'           =>      false,

                'formgroup'         =>      true,

                'echo'              =>      false,

                'pattern'           =>      '',

                'title'             =>      '',

                'data'              =>      '',
            );

            /**
             *  Merge Args
             *  ----------
             */
            $args           =       wp_parse_args( $args, $defaults );

            /**
             *  Extract
             *  -------
             */
            extract( $args );

            $data           .=      self:: _div_start_setup( $args );

            $data           .=

            sprintf(    '<div class="%10$s">

                        %2$s

                        <div class="password-eye">
                            
                            <input autocomplete="off" 

                                id="%1$s" name="%3$s" value="%4$s" type="%5$s" 

                                placeholder="%6$s" class="form-control input-md %7$s"

                            %8$s %9$s %11$s %12$s>

                            <span class="fa fa-fw fa-eye"></span>

                        </div>

                    </div>',

                /**
                 *  1. Input ID
                 *  -----------
                 */
                !   empty( $id )

                ?   esc_attr( $id )

                :   '',

                /**
                 *  2. Input Label
                 *  --------------
                 */
                !   empty( $label )

                ?   sprintf( '<label class="control-label mb-2" for="%2$s">%1$s</label>',

                        /**
                         *  1. Lable
                         *  --------
                         */
                        esc_attr( $label ),

                        /**
                         *  2. ID
                         *  -----
                         */
                        esc_attr( $id )
                    )

                :   '',

                /**
                 *  3. Input Name
                 *  -------------
                 */
                !   empty( $name )

                ?   esc_attr( $name )

                :   '',

                /**
                 *  4. Input Value
                 *  --------------
                 */
                !   empty( $value )

                ?   esc_attr( $value )

                :   '',

                /**
                 *  5. Input Value
                 *  --------------
                 */
                ( ! empty( $type ) && isset( $type ) )

                ?   esc_attr( $type )

                :   esc_attr( 'password' ),

                /**
                 *  6. Input Placeholder
                 *  --------------------
                 */
                !   empty( $placeholder )

                ?   esc_attr( $placeholder )

                :   '',

                /**
                 *  7. Input Field Class
                 *  --------------------
                 */
                !   empty( $class )

                ?   esc_attr( $class )

                :   '',

                /**
                 *  8. Input Field Requird ?
                 *  ------------------------
                 */
                $require   ?   esc_attr( 'required' )   :   '',

                /**
                 *  9. Is Disable ?
                 *  ------------
                 */
                !   empty( $disable )

                ?   esc_attr( 'disabled' )

                :   '',

                /**
                 *  10. Have From Group Class
                 *  -------------------------
                 */
                $formgroup      ==      true

                ?   sanitize_html_class( 'mb-3' )

                :   '',

                /**
                 *  11. Have Pattern
                 *  ----------------
                 */
                !   empty( $pattern )

                ?   sprintf( 'pattern="%1$s"', $pattern )

                :   '',

                /**
                 *  12. Have Alert Title
                 *  --------------------
                 */
                !   empty( $title )

                ?   sprintf( 'title="%1$s"', $title )

                :   ''
            );

            $data   .=   self:: _div_end_setup( $args );

            if( $echo ){

                print   $data;

            }else{

                return  $data;
            }
        }

        /**
         *  Create Calendar Field
         *  ---------------------
         */
        public static function create_calendar_field( $args = [] ) {

            /**
             *  Default Args
             *  ------------
             */
            $defaults       =   array(

                'div'                   =>  [],

                'row'                   =>  [],

                'column'                =>  [],

                'lable'                 =>  '',

                'placeholder'           =>  '',

                'require'               =>  false,

                'class'                 =>  '',

                'echo'                  =>  false,

                'id'                    =>  '',

                'name'                  =>  '',
            );

            /**
             *  Merge Args
             *  ----------
             */
            $args = wp_parse_args( $args, $defaults); 

            extract( $args );

            $data    =      '';

            $data   .=      self:: _div_start_setup( $args );

            $data   .=      sprintf( '<div class="mb-3 sdweddingdirectory-calender-style calender-data %3$s" id="%1$s" data-date="%2$s"></div>

                                    <input type="hidden" id="%1$s-input-data" name="%1$s-input-data" value="%2$s" />', 

                                    /**
                                     *  1. ID
                                     *  -----
                                     */
                                    esc_attr( $id ),

                                    /**
                                     *  2. Values
                                     *  ---------
                                     */
                                    esc_attr( $value ),

                                    /**
                                     *  3. Class
                                     *  --------
                                     */
                                    esc_attr( $class )
                            );

            $data   .=      self:: _div_end_setup( $args );

            if( $echo ){

                print   $data;

            }else{

                return  $data;
            }
        }

        /**
         *  Find Map Address to get latitude and longitude
         *  ----------------------------------------------
         */
        public static function find_map_with_address( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Merge Args with Default
                 *  -----------------------
                 */
                $args       =       wp_parse_args( $args, [

                                        'div'                   =>      [],

                                        'row'                   =>      [],

                                        'column'                =>      [],

                                        'id'                    =>      '',

                                        'class'                 =>      '',

                                        'echo'                  =>      false,

                                        'handler'               =>      '',

                                        /**
                                         *  Layout
                                         *  ------
                                         */
                                        'layout'                =>      absint( '1' ),

                                        /**
                                         *  Map Default Args
                                         *  ----------------
                                         */
                                        'map_id'                =>      esc_attr( parent:: _rand() ),

                                        'map_class'             =>      'map_height',

                                        'map_hidden_in_div'     =>      false,

                                        'map_hidden_class'      =>      'is-hidden-map',
                                        
                                        /**
                                         *  Have Grid
                                         *  ---------
                                         */
                                        'map_address_grid'     =>      'col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12',

                                        'map_latitude_grid'    =>      'col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12',

                                        'map_longitude_grid'   =>      'col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12',


                                        /**
                                         *  Have Value ?
                                         *  ------------
                                         */
                                        'map_address_value'     =>      '',

                                        'map_latitude_value'    =>      '',

                                        'map_longitude_value'   =>      '',

                                        /**
                                         *  Before / After - Input 
                                         *  ----------------------
                                         */
                                        'before_input'          =>      '',

                                        'after_input'           =>      '',

                                        /**
                                         *  Map Address Placeholder 
                                         *  -----------------------
                                         */
                                        'map_address_placeholder'       =>      esc_attr__( 'Write Your Address', 'sdweddingdirectory' )

                                    ] );

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $args );

                /**
                 *  Search Address Input
                 *  --------------------
                 */
                $map_address    =       sprintf(   '<div class="%4$s">
                                                                
                                                        <div class="mb-3">

                                                            <input  type="text" autocomplete="off" class="form-control input-md map_address"

                                                                    id="%1$s" name="%1$s" 

                                                                    placeholder="%2$s" value="%3$s" />

                                                        </div>

                                                        <div class="row">

                                                            <div class="col-12">

                                                                <div class="d-none leaflet-geocode-list-result" id="%5$s">

                                                                    <ul class="list-group"></ul>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>', 

                                                    /**
                                                     *  1. Map Address ID
                                                     *  -----------------
                                                     */
                                                    esc_attr( 'map_address_' . $map_id ),

                                                    /**
                                                     *  2. Translation Ready String
                                                     *  ---------------------------
                                                     */
                                                    esc_attr( $map_address_placeholder ),

                                                    /**
                                                     *  3. Map Address Value
                                                     *  --------------------
                                                     */
                                                    esc_attr( $map_address_value ),

                                                    /**
                                                     *  4. Grid
                                                     *  -------
                                                     */
                                                    $map_address_grid,

                                                    /**
                                                     *  5. Map Result
                                                     *  -------------
                                                     */
                                                    esc_attr( 'map_result_' . $map_id )
                                        );
                /**
                 *  Latitude Input
                 *  --------------
                 */
                $map_latitude       =       sprintf(   '<div class="%4$s">

                                                            <div class="mb-3">

                                                                <input  autocomplete="off" type="text"

                                                                        class="form-control input-md map_latitude"  

                                                                        id="%1$s" name="%1$s" 

                                                                        placeholder="%2$s" value="%3$s" />

                                                            </div>

                                                        </div>',

                                                        /**
                                                         *  1. Latitude ID
                                                         *  --------------
                                                         */
                                                        esc_attr( 'map_latitude_' . $map_id ),

                                                        /**
                                                         *  2. Translation Ready String
                                                         *  ---------------------------
                                                         */
                                                        esc_attr__( 'Latitude', 'sdweddingdirectory' ),

                                                        /**
                                                         *  3. Map Address Value
                                                         *  --------------------
                                                         */
                                                        esc_attr( $map_latitude_value ),

                                                        /**
                                                         *  4. Grid
                                                         *  -------
                                                         */
                                                        $map_latitude_grid
                                            );
                /**
                 *  Latitude Input
                 *  --------------
                 */
                $map_longitude      =       sprintf(   '<div class="%4$s">

                                                            <div class="mb-3">

                                                                <input autocomplete="off"

                                                                        type="text" class="form-control input-md map_longitude" 

                                                                        id="%1$s" name="%1$s" 

                                                                        placeholder="%2$s" value="%3$s" />

                                                            </div>

                                                        </div>',

                                                        /**
                                                         *  1. Longitude ID
                                                         *  ---------------
                                                         */
                                                        esc_attr( 'map_longitude_' . $map_id ),

                                                        /**
                                                         *  2. Translation Ready String
                                                         *  ---------------------------
                                                         */
                                                        esc_attr__( 'Longitude', 'sdweddingdirectory' ),

                                                        /**
                                                         *  3. Map Address Value
                                                         *  --------------------
                                                         */
                                                        esc_attr( $map_longitude_value ),

                                                        /**
                                                         *  4. Grid
                                                         *  -------
                                                         */
                                                        $map_longitude_grid
                                            );

                /**
                 *  Dropdown Section
                 *  ----------------
                 */
                $map_handler    =       sprintf(   '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">

                                                        <div id="%1$s" class="rounded %2$s"></div>

                                                    </div>',

                                                    /**
                                                     *  1. ID
                                                     *  -----
                                                     */
                                                    esc_attr( 'map_' . $map_id ),

                                                    /**
                                                     *  2. Layout
                                                     *  ---------
                                                     */
                                                    $map_class
                                        );

                /**
                 *  Div Start ?
                 *  -----------
                 */
                $handler    .=      self:: _div_start_setup( $args );

                /**
                 *  Layout
                 *  ------
                 */
                if( $layout == absint( '1' ) ){

                    /**
                     *  Map Div Handler
                     *  ---------------
                     */
                    $handler   .=       sprintf(   '<div class="row sdweddingdirectory_map_handler %1$s" id="%2$s">%3$s</div>',

                                                    /**
                                                     *  1. Fields
                                                     *  ---------
                                                     */
                                                    $map_hidden_in_div  ?   $map_hidden_class : '',

                                                    /**
                                                     *  2. Map ID
                                                     *  ---------
                                                     */
                                                    esc_attr( $map_id ),

                                                    /**
                                                     *  3. Map Fields
                                                     *  -------------
                                                     */
                                                    $before_input . $map_address . $map_latitude . $map_longitude . $map_handler . $after_input
                                        );
                }

                elseif( $layout == absint( '2' ) ){

                    $handler   .=       sprintf(   '<div class="row g-0 d-flex align-items-center sdweddingdirectory_map_handler %2$s" id="%3$s">

                                                        <div class="col-lg-6 col-12">

                                                            <div class="card-body">%1$s</div>

                                                        </div>

                                                        <div class="col-lg-6 col-12">

                                                            <div class="card-body">%4$s</div>

                                                        </div>

                                                    </div>',

                                                    /**
                                                     *  1. Map
                                                     *  ------
                                                     */
                                                    $map_handler,

                                                    /**
                                                     *  2. Fields
                                                     *  ---------
                                                     */
                                                    $map_hidden_in_div  ?   $map_hidden_class : '',

                                                    /**
                                                     *  3. ID
                                                     *  -----
                                                     */
                                                    esc_attr( $map_id ),

                                                    /**
                                                     *  4. Map Fields
                                                     *  -------------
                                                     */
                                                    $before_input . $map_address . $map_latitude . $map_longitude . $after_input
                                        );
                }

                elseif( $layout == absint( '3' ) ){

                    $handler   .=       sprintf(   '<div class="row g-0 d-flex align-items-center sdweddingdirectory_map_handler %2$s" id="%3$s">

                                                        <div class="col-lg-6 col-12 pe-2">%1$s</div>

                                                        <div class="col-lg-6 col-12 ps-2">%4$s</div>

                                                    </div>',

                                                    /**
                                                     *  1. Map
                                                     *  ------
                                                     */
                                                    $map_handler,

                                                    /**
                                                     *  2. Fields
                                                     *  ---------
                                                     */
                                                    $map_hidden_in_div  ?   $map_hidden_class : '',

                                                    /**
                                                     *  3. ID
                                                     *  -----
                                                     */
                                                    esc_attr( $map_id ),

                                                    /**
                                                     *  4. Map Fields
                                                     *  -------------
                                                     */
                                                    $before_input . $map_address . $map_latitude . $map_longitude . $after_input
                                        );
                }

                /**
                 *  Div End ?
                 *  ---------
                 */
                $handler   .=   self:: _div_end_setup( $args );


                /**
                 *  Is Print ?
                 *  ----------
                 */
                if( $echo ){

                    print       $handler;
                }

                /**
                 *  Return Div ?
                 *  ------------
                 */
                else{

                    return      $handler;
                }
            }
        }

        /**
         *  Div > Row > Column Setup
         *  ------------------------
         */
        public static function _div_start_setup( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                $defaults = array(

                    'div'           =>  [],

                    'row'           =>  [],

                    'column'        =>  [],

                    'echo'          =>  false
                );

                $args = wp_parse_args($args, $defaults);

                extract( $args );

                $data         =   '';

                /**
                 *  Extra Start Div
                 *  ---------------
                 */
                if ( parent:: _is_array( $div ) && $div[ 'start' ] == true ) {

                    $data     .=   self:: div_start( $div );
                }

                /**
                 *  Row Start Setup
                 *  ---------------
                 */
                if ( parent:: _is_array( $row ) && $row[ 'start' ] == true ) {

                    $data     .=   self:: row_start( $row );
                }

                /**
                 *  Column Start Setup
                 *  ------------------
                 */
                if ( parent:: _is_array( $column ) && $column[ 'start' ] == true ) {

                    $data     .=   self:: column_start( $column );
                }

                /**
                 *  Is Print ?
                 *  ----------
                 */
                if( $echo ){

                    print   $data;

                }else{

                    return  $data;
                }
            }
        }

        /**
         *  Div > Row > Column Setup
         *  ------------------------
         */
        public static function _div_end_setup( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                $defaults = array(

                    'div'           =>  [],

                    'row'           =>  [],

                    'column'        =>  [],

                    'echo'          =>  false
                );

                $args = wp_parse_args($args, $defaults);

                extract( $args );

                $data         =   '';

                /**
                 *  Column Start Setup
                 *  ------------------
                 */
                if ( parent:: _is_array( $column ) && $column[ 'end' ] == true ) {

                    $data     .=   self:: column_end( $column );
                }

                /**
                 *  Row End Setup
                 *  -------------
                 */
                if ( parent:: _is_array( $row ) && $row[ 'end' ] == true ) {

                    $data     .=   self:: row_end( $row );
                }

                /**
                 *  Extra Div End
                 *  -------------
                 */
                if ( parent:: _is_array( $div ) && $div[ 'end' ] == true ) {

                    $data     .=   self:: div_end( $div );
                }

                /**
                 *  Is Print ?
                 *  ----------
                 */
                if( $echo ){

                    print   $data;

                }else{

                    return  $data;
                }
            }
        }

        /**
         *  [Helper] = Show Data with Accordion
         *  -----------------------------------
         */
        public static function data_show_in_collapse( $title = '', $id = '', $content = '' ){

            return

            sprintf( '<div class="accordion_section">

                        <div  class="card-header"   id="%1$s">

                                <a  href="javascript:" 

                                    class="collapsed" 

                                    data-bs-toggle="collapse"

                                    data-bs-target="#collapse_%1$s" 

                                    aria-expanded="false" 

                                    aria-controls="collapse_%1$s">%2$s<i class="remove_collapse fa fa-close"></i>

                                </a>

                        </div>

                        <div    id="collapse_%1$s" 

                                class="collapse" 

                                aria-labelledby="%1$s" 

                                data-parent="#%3$s">
                            
                                %4$s
                        </div>

                    </div>', 

                /**
                 *  1. Random ID
                 *  ------------
                 */
                esc_attr( parent:: _rand() ),

                /**
                 *  2. Heading
                 *  ----------
                 */
                esc_attr( $title ),

                /**
                 *  3. Section Parent ID
                 *  --------------------
                 */
                esc_attr( $id ),

                /**
                 *  4. Collapse Body Content
                 *  ------------------------
                 */
                $content
            );
        }

        /**
         *  [Helper] = Remove Section Icon
         *  ------------------------------
         */
        public static function removed_section( $_is_active = true ){

            /**
             *  Removed Collapse
             *  ----------------
             */
            if( $_is_active ){

                return  sprintf(   '<a href="javascript:" class="btn btn-primary remove_collapse text-start">

                                        <i class="fa fa-trash"></i>

                                    </a>'
                        );
            }
        }

        /**
         *  [Helper] = Remove Section Icon
         *  ------------------------------
         */
        public static function removed_section_icon( $_is_active = true ){

            /**
             *  Removed Collapse
             *  ----------------
             */
            if( $_is_active ){

                return  sprintf(   '<a href="javascript:" class="remove_collapse card-box-remove-icon">

                                        <i class="fa fa-trash"></i>

                                    </a>'
                        );
            }
        }

        /**
         *  [Helper] = Remove Section Icon
         *  ------------------------------
         */
        public static function removed_core_section_icon( $_is_active = true ){

            /**
             *  Removed Collapse
             *  ----------------
             */
            if( $_is_active ){

                return  sprintf(   '<a href="javascript:" class="remove_core_collapse card-box-remove-icon">

                                        <i class="fa fa-trash"></i>

                                    </a>'
                        );
            }
        }
    }

    /**
     *  SDWeddingDirectory Form Fields
     *  ----------------------
     */
    SDWeddingDirectory_Form_Fields::get_instance();
}