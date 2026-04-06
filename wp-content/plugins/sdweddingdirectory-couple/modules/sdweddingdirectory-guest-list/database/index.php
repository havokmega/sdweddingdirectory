<?php
/**
 *  SDWeddingDirectory Couple Guest List Database
 *  -------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Guest_List_Database' ) && class_exists( 'SDWeddingDirectory_Form_Fields' ) ){

    /**
     *  SDWeddingDirectory Couple Guest List Database
     *  -------------------------------------
     */
    class SDWeddingDirectory_Guest_List_Database extends SDWeddingDirectory_Form_Fields{

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
             *  Default Guest List [ Event ] Data
             *  ---------------------------------
             */
            add_action( 'sdweddingdirectory/user-register/couple', [ $this, 'default_guest_list_event_data' ], absint( '50' ), absint( '1' ) );

            /**
             *  Default Guest List [ Group ] Data
             *  ---------------------------------
             */
            add_action( 'sdweddingdirectory/user-register/couple', [ $this, 'default_guest_list_group_data' ], absint( '60' ), absint( '1' ) );

            /**
             *  Default Guest List [ Menu ] Data
             *  ---------------------------------
             */
            add_action( 'sdweddingdirectory/user-register/couple', [ $this, 'default_guest_list_menu_data' ], absint( '70' ), absint( '1' ) );
        }

        /**
         *  Default Guest List [ Event ] Data
         *  ---------------------------------
         */
        public static function default_guest_list_event_data( $args = [] ){

            /**
             *  Make sure get args
             *  ------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'           =>      absint( '0' ),

                    'default_list'      =>      sdweddingdirectory_option( 'guest_list_event_group' ),

                    'handler'           =>      [],

                    'meta_key'          =>      sanitize_key( 'guest_list_event_group' ),

                    'menu_list'         =>      sdweddingdirectory_option( 'guest_list_menu_group' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /** 
                 *  Have Pre Defind Data 
                 *  --------------------
                 */
                if( parent:: _is_array( $default_list ) ){

                    /**
                     *  Loop
                     *  ----
                     */
                    foreach( $default_list as $key => $value ){

                        $handler[]              =       [

                            'title'             =>      esc_attr( $value[ 'event_list' ] ),

                            'event_list'        =>      esc_attr( $value[ 'event_list' ] ),

                            'event_unique_id'   =>      absint( rand() ),

                            'event_icon'        =>      esc_attr( $value[ 'event_icon' ] ),

                            'have_meal'         =>      esc_attr( $value[ 'have_meal' ] ),

                            'event_meal'        =>      json_encode( $menu_list )
                        ];
                    }
                }
            }

            /**
             *  Make sure checklist data exists
             *  -------------------------------
             */
            if( parent:: _is_array( $handler ) ){

                update_post_meta( $post_id, sanitize_key( $meta_key ), $handler );
            }
        }

        /**
         *  Default Guest List [ Group ] Data
         *  ---------------------------------
         */
        public static function default_guest_list_group_data( $args = [] ){

            /**
             *  Make sure get args
             *  ------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'           =>      absint( '0' ),

                    'default_list'      =>      sdweddingdirectory_option( 'guest_list_group' ),

                    'handler'           =>      [],

                    'meta_key'          =>      sanitize_key( 'guest_list_group' ),

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /** 
                 *  Have Pre Defind Data 
                 *  --------------------
                 */
                if( parent:: _is_array( $default_list ) ){

                    /**
                     *  Loop
                     *  ----
                     */
                    foreach( $default_list as $key => $value ){

                        $handler[]              =       [

                            'title'             =>      esc_attr( $value[ 'group_name' ] ),

                            'group_name'        =>      esc_attr( $value[ 'group_name' ] ),

                            'group_unique_id'   =>      absint( rand() ),
                        ];
                    }
                }
            }

            /**
             *  Make sure checklist data exists
             *  -------------------------------
             */
            if( parent:: _is_array( $handler ) ){

                update_post_meta( $post_id, sanitize_key( $meta_key ), $handler );
            }
        }

        /**
         *  Default Guest List [ Menu ] Data
         *  --------------------------------
         */
        public static function default_guest_list_menu_data( $args = [] ){

            /**
             *  Make sure get args
             *  ------------------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'           =>      absint( '0' ),

                    'default_list'      =>      sdweddingdirectory_option( 'guest_list_menu_group' ),

                    'handler'           =>      [],

                    'meta_key'          =>      sanitize_key( 'guest_list_menu_group' ),

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /** 
                 *  Have Pre Defind Data 
                 *  --------------------
                 */
                if( parent:: _is_array( $default_list ) ){

                    /**
                     *  Loop
                     *  ----
                     */
                    foreach( $default_list as $key => $value ){

                        $handler[]              =       [

                            'title'             =>  esc_attr( $value[ 'menu_list' ] ),

                            'menu_list'         =>  esc_attr( $value[ 'menu_list' ] ),

                            'menu_unique_id'    =>  absint( rand() ),
                        ];
                    }
                }
            }

            /**
             *  Make sure checklist data exists
             *  -------------------------------
             */
            if( parent:: _is_array( $handler ) ){

                update_post_meta( $post_id, sanitize_key( $meta_key ), $handler );
            }
        }

        /**
         *  Couple Story
         *  ------------
         */
        public static function add_guest_fields( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'post_id'                   =>      absint( '0' ),

                'first_name'                =>      esc_attr__( 'First Name', 'sdweddingdirectory-guest-list' ),

                'last_name'                 =>      esc_attr__( 'Last Name', 'sdweddingdirectory-guest-list' ),

                'body_content'              =>      '',

                'counter'                   =>      absint( '0' ),

                'first_name_alert'          =>      esc_attr__( 'First name not Empty!', 'sdweddingdirectory-guest-list' ),

                'last_name_alert'           =>      esc_attr__( 'Last name not Empty!', 'sdweddingdirectory-guest-list' )

            ) ) );
        
            /**
             *  Column Start
             *  ------------
             */
            $body_content      .=      '<div class="col collpase_section">';

                $body_content      .=      '<div class="card mb-3 text-center">';

                $body_content      .=      $counter >= absint( '1' )    ?   self:: removed_section_icon( true ) :   '';

                    $body_content      .=      '<div class="card-body">';

                        $body_content      .=      '<div class="row gx-2">';

                            $body_content      .=      parent:: create_input_field( array(

                                                                'column'        =>  array(

                                                                                        'start'     =>  true,

                                                                                        'end'       =>  true,

                                                                                        'grid'      =>  sanitize_html_class( 'col-md' )
                                                                                    ),

                                                                'name'          =>  esc_attr( 'first_name' ),

                                                                'id'            =>  esc_attr( 'first_name' ),

                                                                'class'         =>  sanitize_html_class( 'first_name' ),

                                                                'placeholder'   =>  esc_attr( $first_name ),

                                                                'formgroup'     =>  false,

                                                                'attr'          =>  sprintf( 'data-alert="%1$s"',

                                                                                        esc_attr( $first_name_alert )
                                                                                    ),

                                                                'require'       =>  true,
                                                        ) );

                            $body_content      .=      parent:: create_input_field( array(

                                                                'column'        =>  array(

                                                                                        'start'     =>  true,

                                                                                        'end'       =>  true,

                                                                                        'grid'      =>  sanitize_html_class( 'col-md' )
                                                                                    ),

                                                                'name'          =>  esc_attr( 'last_name' ),

                                                                'id'            =>  esc_attr( 'last_name' ),

                                                                'class'         =>  sanitize_html_class( 'last_name' ),

                                                                'placeholder'   =>  esc_attr( $last_name ),

                                                                'formgroup'     =>  false,

                                                                'attr'          =>  sprintf( 'data-alert="%1$s"',

                                                                                        esc_attr( $last_name_alert )
                                                                                    ),
                                                        ) );

                        $body_content      .=      '</div><!-- End Row -->';

                    $body_content      .=      '</div>';

                $body_content      .=      '</div>';

            $body_content      .=      '</div>';


            /**
             *  Return Data
             *  -----------
             */
            return $body_content;
        }

        /**
         *  1. Get Menu list
         *  ----------------
         */
        public static function menu_list(){

            return parent:: get_data( sanitize_key( 'guest_list_menu_group' ) );
        }

        /**
         *  2. Get Group list
         *  -----------------
         */
        public static function group_list(){

            return parent:: get_data( sanitize_key( 'guest_list_group' ) );
        }

        /**
         *  3. Get Event list
         *  -----------------
         */
        public static function event_list(){

            return parent:: get_data( sanitize_key( 'guest_list_event_group' ) );
        }

        /**
         *  4. Get Guest list
         *  -----------------
         */
        public static function guest_list(){

            return parent:: get_data( sanitize_key( 'guest_list_data' ) );
        }

        /**
         *  Get Menu Card
         *  -------------
         */
        public static function get_age_data(){

            return  

            array(

                '0'         =>  esc_attr__( 'Guest Age', 'sdweddingdirectory-guest-list' ),

                'adult'     =>  esc_attr__( 'Adult', 'sdweddingdirectory-guest-list' ),

                'child'     =>  esc_attr__( 'Child', 'sdweddingdirectory-guest-list' ),

                'baby'      =>  esc_attr__( 'Baby', 'sdweddingdirectory-guest-list' ),
            );
        }

        public static function get_group_data(){

            $data = self:: group_list();

            $_get_data = [];

            $_get_data[ absint( '0' ) ] = esc_attr__( 'Select Guest Group', 'sdweddingdirectory-guest-list' );

            /**
             *  Group Options
             *  -------------
             */
            if( parent:: _is_array( $data ) ){

                foreach ( $data as $key => $value) {
                    
                    $_get_data[ sanitize_title( $value[ 'group_name' ] ) ] = esc_attr( $value[ 'group_name' ] );
                }
            }

            return $_get_data;
        }


        public static function get_event_data( $event_unique_id ){

            $data = self:: event_list();

            if( parent:: _is_array( $data ) ){

                foreach ( $data as $key => $value) {
                    
                    if( absint( $value[ 'event_unique_id' ] ) == absint( $event_unique_id ) ){

                        return $data[ $key ];
                    }
                }
            }
        }

        public static function get_guest_data( $guest_unique_id ){

            $data = self:: guest_list();

            if( parent:: _is_array( $data ) ){

                foreach ( $data as $key => $value) {
                    
                    if( absint( $value[ 'guest_unique_id' ] ) == absint( $guest_unique_id ) ){

                        return $data[ $key ];
                    }
                }
            }
        }

        public static function get_event_guest_data( $unique_id, $member_id ){

            if( ! empty( $unique_id ) && ! empty( $member_id ) ){

                $_data = self:: event_list();

                if( parent:: _is_array( $_data ) ){


                }
            }
        }

        /**
         *  Guest Attendance Dropdown option
         *  --------------------------------
         */
        public static function get_event_attendance( $args = [] ){

            if( parent:: _is_array( $args ) ){

                $_attendance = '';

                $_attendance_option = self:: get_attendance_options();

                if( parent:: _is_array( $_attendance_option )  ){

                    foreach( $_attendance_option as $key => $value) {
                        
                        $_attendance  .=

                        sprintf( '<option value="%1$s" %3$s>%2$s</option>',

                            /**
                             *  1. Get menu list with id
                             *  ------------------------
                             */
                            absint( $key ),

                            /**
                             *  2. Menu List with Proper name
                             *  -----------------------------
                             */
                            esc_attr( $value ),

                            /**
                             *  3. Select Meal
                             *  --------------
                             */
                            ( absint( $key ) == absint( $args[ 'guest_invited' ] ) )

                            ?   esc_attr( 'selected' )

                            :   ''

                        );
                    }
                }

                /**
                 *  Selected Meal
                 *  -------------
                 */
                printf( '<select 

                            data-event-id="%2$s" 

                            data-guest-id="%3$s" 

                            class="form-control sdweddingdirectory_event_attendance sdweddingdirectory_event_attendance_%2$s" 

                            name="attendance">

                            %1$s

                        </select>',

                        /** 
                         *  1. Get Dropdown Menu
                         *  --------------------
                         */
                        $_attendance,

                        /**
                         *  2. Event ID
                         *  -----------
                         */
                        absint( $args[ 'event_unique_id' ] ),
                        
                        /**
                         *  3. Member ID
                         *  ------------
                         */
                        absint( $args[ 'guest_unique_id' ] )
                );
            }
        }

        /**
         *  Get Event Meal Options
         *  ----------------------
         */
        public static function get_event_meal_option( $meal = '' ){

            $_option    = '';

            $_option    .=

            sprintf( '<option value="%1$s">%2$s</option>',

                /**
                 *  1. Get menu list with id
                 *  ------------------------
                 */
                absint( '0' ),

                /**
                 *  2. Menu List with Proper name
                 *  -----------------------------
                 */
                esc_attr__( 'Select Your Meal', 'sdweddingdirectory-guest-list' )
            );

            if( parent:: _is_array( $meal ) ){

                foreach ( $meal as $key => $value) {
                    
                    $_option .= 

                    sprintf( '<option value="%1$s">%2$s</option>', 

                        // 1
                        sanitize_title( $value[ 'menu_list' ] ),

                        // 2
                        esc_attr( $value[ 'menu_list' ] )
                    );
                }
            }

            return $_option;
        }

        /**
         *  Guest Meals Dropdown option
         *  ---------------------------
         */
        public static function get_event_meals( $_list_of_menu = [], $print = true, $style = 'light' ){

            $_menu_items = '';

            $_menu_items  .=

            sprintf( '<option value="%1$s">%2$s</option>',

                /**
                 *  1. Get menu list with id
                 *  ------------------------
                 */
                absint( '0' ),

                /**
                 *  2. Menu List with Proper name
                 *  -----------------------------
                 */
                esc_attr__( 'Select Your Meal', 'sdweddingdirectory-guest-list' )
            );

            if( parent:: _is_array( $_list_of_menu[ 'event_meal' ] )  ){

                foreach( $_list_of_menu[ 'event_meal' ] as $key => $value) {
                    
                    $_menu_items  .=

                    sprintf( '<option value="%1$s" %3$s>%2$s</option>',


                        /**
                         *  1. Get menu list with id
                         *  ------------------------
                         */
                        sanitize_title( $value[ 'menu_list' ] ),

                        /**
                         *  2. Menu List with Proper name
                         *  -----------------------------
                         */
                        esc_attr( $value[ 'menu_list' ] ),

                        /**
                         *  3. Select Meal
                         *  --------------
                         */
                        ( sanitize_title( $value[ 'menu_list' ] ) == sanitize_title( $_list_of_menu[ 'guest_meal' ] )  )

                        ?   esc_attr( 'selected' )

                        :   ''

                    );
                }
            }

            $_meal_data     =

            /**
             *  Selected Meal
             *  -------------
             */
            sprintf( '<select data-event-id="%2$s" data-guest-id="%3$s" class="%4$s sdweddingdirectory_event_meals_%2$s sdweddingdirectory_event_meals" name="event_meal">%1$s</select>',

                    /**
                     *  1. Meal List
                     *  ------------
                     */
                    $_menu_items,

                    /**
                     *  2. Event ID
                     *  -----------
                     */
                    absint( $_list_of_menu[ 'event_unique_id' ] ),
                    
                    /**
                     *  3. Member ID
                     *  ------------
                     */
                    absint( $_list_of_menu[ 'guest_unique_id' ] ),

                    /**
                     *  4. Selection Style
                     *  ------------------
                     */
                    sanitize_html_class( 'form-control' )
            );

            /**
             *  Is Print ?
             *  ----------
             */
            if( $print ){

                print $_meal_data;

            }else{

                return $_meal_data;
            }
        }

        /**
         *  Guest Attendance Dropdown option
         *  --------------------------------
         */
        public static function edit_guest_event_attendance( $args = [] ){

            if( parent:: _is_array( $args ) ){

                $_attendance = '';

                if( parent:: _is_array( self:: get_attendance_options() )  ){

                    foreach( self:: get_attendance_options() as $key => $value) {
                        
                        $_attendance  .=

                        sprintf( '<option value="%1$s" %3$s>%2$s</option>',

                            /**
                             *  1. Get menu list with id
                             *  ------------------------
                             */
                            absint( $key ),

                            /**
                             *  2. Menu List with Proper name
                             *  -----------------------------
                             */
                            esc_attr( $value ),

                            /**
                             *  3. Select Meal
                             *  --------------
                             */
                            ( absint( $key ) == absint( $args[ 'guest_invited' ] ) )

                            ?   esc_attr( 'selected' )

                            :   ''

                        );
                    }
                }

                /**
                 *  Selected Meal
                 *  -------------
                 */
                printf( '<select data-event-id="%2$s" data-guest-id="%3$s" class="form-control sdweddingdirectory_event_attendance" name="attendance">%1$s</select>', 

                        /** 
                         *  1. Get Dropdown Menu
                         *  --------------------
                         */
                        $_attendance,

                        /**
                         *  2. Event ID
                         *  -----------
                         */
                        absint( $args[ 'event_unique_id' ] ),
                        
                        /**
                         *  3. Member ID
                         *  ------------
                         */
                        absint( $args[ 'guest_unique_id' ] )
                );
            }
        }

        /**
         *  Guest Group Dropdown option
         *  ---------------------------
         */
        public static function guest_group( $args = [] ){

            $_group_data            =  self:: group_list();

            if( parent:: _is_array( $_group_data ) ){

                $_guest_group_data      =   sprintf( '<option value="%1$s">%2$s</option>',

                                                /**
                                                 *  1. Value
                                                 *  --------
                                                 */
                                                absint( '0' ),

                                                /**
                                                 *  2. Name
                                                 *  -------
                                                 */
                                                esc_attr__( 'Select Group', 'sdweddingdirectory-guest-list' )
                                            );
                /**
                 *  Group Options
                 *  -------------
                 */
                if( parent:: _is_array( $_group_data )  ){

                    foreach( $_group_data as $key => $value) {
                        
                        $_guest_group_data  .=

                        sprintf( '<option value="%1$s" %3$s>%2$s</option>',

                            /**
                             *  1. Get menu list with id
                             *  ------------------------
                             */
                            sanitize_title( $value[ 'group_name' ] ),

                            /**
                             *  2. Menu List with Proper name
                             *  -----------------------------
                             */
                            esc_attr( $value[ 'group_name' ] ),

                            /**
                             *  3. Select Meal
                             *  --------------
                             */
                            ( sanitize_title( $value[ 'group_name' ] ) == sanitize_title( $args[ 'group_name' ] ) )

                            ?   esc_attr( 'selected' )

                            :   ''

                        );
                    }
                }

                /**
                 *  Selected Meal
                 *  -------------
                 */
                printf( '<select data-guest-id="%2$s" class="form-control sdweddingdirectory_guest_group" name="attendance">%1$s</select>', 

                        /** 
                         *  1. Get Dropdown Menu
                         *  --------------------
                         */
                        $_guest_group_data,
                        
                        /**
                         *  2. Member ID
                         *  ------------
                         */
                        absint( $args[ 'guest_unique_id' ] )
                );
            }
        }

        /**
         *  Get Attendence Options
         *  ----------------------
         */
        public static function get_attendance_options(){

            return  

            array(

                '0'     =>     esc_attr__( 'Not Invited', 'sdweddingdirectory-guest-list' ),
                '1'     =>     esc_attr__( 'Invited', 'sdweddingdirectory-guest-list' ),
                '2'     =>     esc_attr__( 'Attending', 'sdweddingdirectory-guest-list' ),
                '3'     =>     esc_attr__( 'Declined', 'sdweddingdirectory-guest-list' ),
            );
        }

        /**
         *  Get Global Counter Notification
         *  -------------------------------
         */
        public static function global_counter_notification(){

            /**
             *  Event Data
             *  ----------
             */
            $_event_data    =   self:: event_list();

            $_guest_data    =   self:: guest_list();

            /**
             *  Get Overview Counter
             *  --------------------
             */
            $_counter       =   [];

            /**
             *  Total Guest
             *  -----------
             */
            $_counter[ esc_attr( 'total_guest_counter' ) ]  =   absint( count(  self:: guest_list()  ) );
            
            if( parent:: _is_array( $_event_data ) ){

                foreach ( $_event_data as $_event_key => $_event_value ) {
                    
                    $_event_unique_id =  absint( $_event_value[ 'event_unique_id' ] );

                    $_counter[] = self:: get_counter_notification( $_event_unique_id );
                }
            }

            return $_counter;
        }

        /**
         *  Get Counter Notification
         *  ------------------------
         */
        public static function get_counter_notification( $event_id = '' ){

            if( empty( $event_id ) ){

                return;

            }else{

                /**
                 *  Get Overview Counter
                 *  --------------------
                 */
                $_counter   =   [];

                /**
                 *  Total Guest
                 *  -----------
                 */
                $_counter[  sprintf( '%1$s_total_guest_counter',  absint( $event_id ) )   ]

                =   absint( count(  self:: guest_list()  ) );


                /**
                 *  Total Attended
                 *  --------------
                 */
                $_counter[  sprintf( '%1$s_attending_counter',  absint( $event_id ) )   ]

                =   absint(  self:: get_event_counter( array(

                                    /**
                                     *  1. Event Unique ID
                                     *  ------------------
                                     */
                                    'event_unique_id'   =>  absint( $event_id ),

                                    /**
                                     *  2. Get * Attended * Counter
                                     *  --------------------------
                                     */
                                    '__FIND__'          =>   array(

                                        'guest_invited'     =>  absint( '2' )
                                    )

                    ) )  );

                /**
                 *  Invited Guest Counter
                 *  ---------------------
                 */
                $_counter[  sprintf( '%1$s_invited_counter',  absint( $event_id ) )   ]

                =   absint(  self:: get_event_counter( array(

                                    /**
                                     *  1. Event Unique ID
                                     *  ------------------
                                     */
                                    'event_unique_id'   =>  absint( $event_id ),

                                    /**
                                     *  2. Get * Invided ( Pending ) * Counter
                                     *  --------------------------------------
                                     */
                                    '__FIND__'              =>   array(

                                        'guest_invited'     =>  absint( '1' )
                                    )

                    ) ) );

                /**
                 *  Declain Guest Counter
                 *  ---------------------
                 */
                $_counter[  sprintf( '%1$s_declain_counter',  absint( $event_id ) )   ]

                =   absint(  self:: get_event_counter( array(

                                    /**
                                     *  1. Event Unique ID
                                     *  ------------------
                                     */
                                    'event_unique_id'   =>  absint( $event_id ),

                                    /**
                                     *  2. Get * Invided ( Pending ) * Counter
                                     *  --------------------------------------
                                     */
                                    '__FIND__'              =>   array(

                                        'guest_invited'     =>  absint( '3' )
                                    )

                    ) ) );


                return $_counter;
            }
        }

        /**
         *  Get Event Counter Notification
         *  ------------------------------
         */
        public static function get_event_counter( $args = [] ){

            $_guest_data        =   self:: guest_list();

            $_event_unique_id   =   absint( $args[ 'event_unique_id' ] );

            $_counter           =   absint( '0' );

            if( parent:: _is_array( $_guest_data ) && $_event_unique_id !== '' ){

                foreach ( $_guest_data as $_guest_key => $_guest_value ) {
                    
                    $_get_event_list =  json_decode( $_guest_value[ 'guest_event' ], true );

                    if( parent:: _is_array( $_get_event_list ) ){

                        foreach ( $_get_event_list as $_event_key => $_event_value ) {

                            if(  absint( $_event_value[ 'event_unique_id' ] ) == absint( $_event_unique_id )  ){

                                if( parent:: _is_array( $args[ '__FIND__' ] )  ){

                                    foreach ( $args[ '__FIND__' ] as $_find_key => $_find_value ) {

                                        if(  parent:: _is_array( $_find_value )  ){

                                            foreach ( $_find_value as $_find_array_key => $_find_array_value ) {

                                                if( $_event_value[ $_find_array_key  ] == $_find_array_value ){

                                                    $_counter++;
                                                }
                                            }

                                        }else{

                                            if( in_array( $_find_value, $_event_value ) && $_event_value[ $_find_key  ] == $_find_value ){

                                                $_counter++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return absint( $_counter );
        }

        /**
         *  Get Guest Counter Notification
         *  ------------------------------
         */
        public static function get_counter_counter( $args = [] ){

            $_guest_data        =   self:: guest_list();

            $_counter           =   absint( '0' );

            if( parent:: _is_array( $_guest_data ) ){

                foreach ( $_guest_data as $_guest_key => $_guest_value ) {

                    if( parent:: _is_array( $args[ '__FIND__' ] )  ){

                        foreach ( $args[ '__FIND__' ] as $_find_key => $_find_value ) {

                            if(  parent:: _is_array( $_find_value )  ){

                                foreach ( $_find_value as $_find_array_key => $_find_array_value ) {

                                    if( $_guest_value[ $_find_array_key  ] == $_find_array_value ){

                                        $_counter++;
                                    }
                                }

                            }else{

                                if( $_guest_value[ $_find_key  ] == $_find_value ){

                                    $_counter++;
                                }
                            }
                        }
                    }
                }
            }

            return absint( $_counter );
        }


        /**
         *  Get Attended Options
         *  --------------------
         */
        public static function get_attended_option( $select = '' ){

            $_option = '';

            if( parent:: _is_array( self:: get_attendance_options() ) ){

                foreach ( self:: get_attendance_options() as $key => $value) {
                    
                    $_option .= 

                    sprintf( '<option value="%1$s" %3$s>%2$s</option>',

                        // 1
                        absint( $key  ),

                        // 2
                        esc_attr( $value ),

                        // 3
                        ( $key == $select ) ? esc_attr( 'selected' ) : ''
                    );

                }
            }

            return $_option;
        }

        /**
         *  Get Event Details via * Unique ID *
         *  -----------------------------------
         */
        public static function get_event_details( $args = [] ){

            $_event_data = self:: event_list();

            if( parent:: _is_array( $_event_data )  ){

                foreach ( $_event_data as $key => $value) {
                    
                    if( $value[ 'event_unique_id' ] == $args[ 'event_unique_id' ] ){

                        return $value[ $args[ 'get_value' ] ];
                    }
                }
            }
        }

        /**
         *  Get Event Data
         *  --------------
         */
        public static function get_event_overview_data(){

            $_get_data          =   [];

            $_event_data        =   self:: event_list();

            /**
             *  Event Overview Array ( Icon + Attended + Invited + Declain )
             *  ------------------------------------------------------------
             */
            if( parent:: _is_array( $_event_data ) ) {

                foreach ( $_event_data as $key => $value) {

                    $_get_data[  sanitize_title( $value[ 'event_list' ] )   ]  =

                    array(

                        /**
                         *  1. Event Title
                         *  --------------
                         */
                        'event_name'            =>      esc_attr( $value[ 'event_list' ] ),

                        /**
                         *  2. Event Unique ID
                         *  ------------------
                         */
                        'event_unique_id'       =>      absint( $value[ 'event_unique_id' ] ),

                        /**
                         *  Attended Guest
                         *  --------------
                         */
                        'attendance'            =>      self:: get_event_counter( array(

                                                            /**
                                                             *  1. Event Unique ID
                                                             *  ------------------
                                                             */
                                                            'event_unique_id'   =>  absint( $value[ 'event_unique_id' ] ),

                                                            /**
                                                             *  2. Get * Attended * Counter
                                                             *  --------------------------
                                                             */
                                                            '__FIND__'          =>   array(

                                                                'guest_invited'     =>  absint( '2' )
                                                            )

                                                        ) ),

                        /**
                         *  4. Get this event Invited guest
                         *  -------------------------------
                         */
                        'invited'               =>      self:: get_event_counter( array(

                                                            /**
                                                             *  1. Event Unique ID
                                                             *  ------------------
                                                             */
                                                            'event_unique_id'   =>  absint( $value[ 'event_unique_id' ] ),

                                                            /**
                                                             *  2. Get * Attended * Counter
                                                             *  --------------------------
                                                             */
                                                            '__FIND__'          =>   array(

                                                                'guest_invited'     =>  absint( '1' )
                                                            )

                                                        ) ),

                        /**
                         *  5. Get this event declain guest
                         *  -------------------------------
                         */
                        'declain'               =>      self:: get_event_counter( array(

                                                            /**
                                                             *  1. Event Unique ID
                                                             *  ------------------
                                                             */
                                                            'event_unique_id'   =>  absint( $value[ 'event_unique_id' ] ),

                                                            /**
                                                             *  2. Get * Declain * Counter
                                                             *  --------------------------
                                                             */
                                                            '__FIND__'          =>   array(

                                                                'guest_invited'     =>  absint( '3' )
                                                            )

                                                        ) ),
                        /**
                         *  6. Icon
                         *  -------
                         */
                        'event_icon'            =>      ( isset( $value[ 'event_icon' ] ) && $value[ 'event_icon' ] !== '' )

                                                        ?   esc_attr( $value[ 'event_icon' ] )

                                                        :   ''
                    );

                }
            }

            return $_get_data;
        }

        /**
         *  Form Fields
         *  -----------
         */
        public static function guest_list_create_field( $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [

                'id'            =>      esc_attr( parent:: _rand() ),

                'placeholder'   =>      '',

                'required'      =>      true,

                'type'          =>      esc_attr( 'text' ),

                'value'         =>      ''

            ] ) );

            /**
             *  Form Field
             *  ----------
             */
            printf(  '<div class="col">

                        <div class="mb-3">

                            <p class="mb-1">%2$s</p>

                            <label class="control-label sr-only" for="%1$s"></label>

                            <input autocomplete="off" id="%1$s" type="%4$s" name="%1$s" placeholder="%2$s" class="form-control" value="%5$s" %3$s />

                        </div>

                    </div>',

                    /**
                     *  1. Id & Name
                     *  ------------
                     */
                    esc_attr( $id ),

                    /**
                     *  2. Placeholder
                     *  --------------
                     */
                    esc_attr( $placeholder ),

                    /**
                     *  3. Required Fields
                     *  ------------------
                     */
                    apply_filters( 'sdweddingdirectory/guest-fields/required/' . $id, 

                        /**
                         *  Have Any Filter ?
                         *  -----------------
                         */
                        $required 

                        ?   esc_attr( 'required' ) 

                        :   '' 
                    ),

                    /**
                     *  4. Type
                     *  -------
                     */
                    esc_attr( $type ),

                    /**
                     *  5. Have Value ?
                     *  ---------------
                     */
                    esc_attr( $value )
            );
        }

        /**
         *  Form Fields
         *  -----------
         */
        public static function guest_list_create_radio_field( $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [

                'name'          =>      esc_attr( parent:: _rand() ),

                'options'       =>      [],

                'checked'       =>      '',

                'label'         =>      '',

                'collection'    =>      ''

            ] ) );

            /**
             *  Have Options
             *  ------------
             */
            if( parent:: _is_array( $options ) ){

                foreach( $options as $key => $value ){

                    $collection     .=      sprintf(    '<input type="radio" class="btn-check" name="%1$s" id="%2$s" value="%2$s" %4$s autocomplete="off">
                                                    
                                                        <label class="btn btn-outline-primary" for="%2$s">%3$s</label>',

                                                        /**
                                                         *  1. Name
                                                         *  -------
                                                         */
                                                        esc_attr( $name ),

                                                        /**
                                                         *  2. ID
                                                         *  -----
                                                         */
                                                        esc_attr( $key ),

                                                        /**
                                                         *  3. Value
                                                         *  --------
                                                         */
                                                        esc_attr( $value ),

                                                        /**
                                                         *  4. Is Checked ?
                                                         *  ---------------
                                                         */
                                                        $checked == $key  ?   esc_attr( 'checked' )   :   ''
                                            );
                }
            }

            /**
             *  Form Field
             *  ----------
             */
            printf(  '<div class="col">

                        <div class="mb-3">

                            <p class="mb-1">%1$s</p>
                            
                            <div class="btn-group d-flex" role="group" aria-label="Basic radio toggle button group">%2$s</div>

                        </div>

                    </div>',

                    /**
                     *  1. Label
                     *  --------
                     */
                    esc_attr( $label ),

                    /**
                     *  2. Options
                     *  ----------
                     */
                    $collection
            );
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Guest_List_Database:: get_instance();
}