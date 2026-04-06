<?php
/**
 *  SDWeddingDirectory Request Quote
 *  ------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Request_Quote_Form_Fields' ) && class_exists( 'SDWeddingDirectory_Request_Quote_Database' ) ){

    /**
     *  SDWeddingDirectory Request Quote
     *  ------------------------
     */
    class SDWeddingDirectory_Request_Quote_Form_Fields extends SDWeddingDirectory_Request_Quote_Database{

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
             *  First Name + Last Name
             *  ----------------------
             */
            add_filter( 'sdweddingdirectory/request-quote/form-fields', [ $this, 'first_last_name' ], absint( '10' ), absint( '2' ) );

            /**
             *  Email Field
             *  -----------
             */
            add_filter( 'sdweddingdirectory/request-quote/form-fields', [ $this, 'email_field' ], absint( '20' ), absint( '2' ) );

            /**
             *  Budget Field
             *  ------------
             */
            add_filter( 'sdweddingdirectory/request-quote/form-fields', [ $this, 'budget_field' ], absint( '30' ), absint( '2' ) );

            /**
             *  Phone Number Field
             *  ------------------
             */
            add_filter( 'sdweddingdirectory/request-quote/form-fields', [ $this, 'phone_number_field' ], absint( '40' ), absint( '2' ) );

            /**
             *  Message Field
             *  -------------
             */
            add_filter( 'sdweddingdirectory/request-quote/form-fields', [ $this, 'message_field' ], absint( '5' ), absint( '2' ) );

            /**
             *  Event Date Field
             *  ----------------
             */
            add_filter( 'sdweddingdirectory/request-quote/form-fields', [ $this, 'event_date_field' ], absint( '60' ), absint( '2' ) );

            /**
             *  Guest Field
             *  -----------
             */
            add_filter( 'sdweddingdirectory/request-quote/form-fields', [ $this, 'guest_field' ], absint( '70' ), absint( '2' ) );

            /**
             *  Contact Me Field
             *  ----------------
             */
            add_filter( 'sdweddingdirectory/request-quote/form-fields', [ $this, 'contact_me_field' ], absint( '80' ), absint( '2' ) );

            /**
             *  Term & Condition Field
             *  ----------------------
             */
            add_filter( 'sdweddingdirectory/request-quote/form-fields', [ $this, 'term_and_condition_note' ], absint( '90' ), absint( '2' ) );

            /**
             *  Submit Button Fields
             *  --------------------
             */
            add_filter( 'sdweddingdirectory/request-quote/form-fields', [ $this, 'submit_field' ], absint( '150' ), absint( '2' ) );
        }

        /**
         *  First Name + Last Name
         *  ----------------------
         */
        public static function first_last_name( $collection = '', $args = [] ){

            /**
             *  Extract args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'venue_post_id'     =>    absint( '0' ),

                'couple_post_id'      =>    absint( '0' ),

                'new_fields'          =>    '',

            ) ) );

            $new_fields    =

            sprintf(    '<div class="mb-3">

                            <input autocomplete="off" type="text" name="%1$s" placeholder="%2$s" class="form-control" value="%3$s" required />

                        </div>',

                        /**
                         *  1. Field ID
                         *  -----------
                         */
                        esc_attr( 'sdweddingdirectory_request_quote_full_name' ),

                        /**
                         *  2. Placeholder : Translation String
                         *  -----------------------------------
                         */
                        esc_attr__( 'First and last name', 'sdweddingdirectory-request-quote' ),

                        /**
                         *  3. Is Couple ? - First Name + Last Name
                         *  ---------------------------------------
                         */
                        !   empty( $couple_post_id )

                        ?   sprintf( '%1$s %2$s',

                              /**
                               *  1. Get First name of couple
                               *  ---------------------------
                               */
                              parent:: get_data( 'first_name' ),

                              /**
                               *  2. Get Last name of couple
                               *  --------------------------
                               */
                              parent:: get_data( 'last_name' )
                          )

                        :   ''
            );

            /**
             *  Return
             *  ------
             */
            return      $collection     .       $new_fields;
        }

        /**
         *  Email Field
         *  -----------
         */
        public static function email_field( $collection = '', $args = [] ){

            /**
             *  Extract args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'venue_post_id'     =>    absint( '0' ),

                'couple_post_id'      =>    absint( '0' ),

                'new_fields'          =>    '',

            ) ) );

            /**
             *  New Fields
             *  ----------
             */
            $new_fields        =

            sprintf(    '<div class="mb-3">

                            <input autocomplete="off" type="email" name="%1$s" placeholder="%2$s" value="%3$s" class="form-control" required />

                        </div>',

                        /**
                         *  1. Field ID
                         *  -----------
                         */
                        esc_attr( 'sdweddingdirectory_request_quote_email' ),

                        /**
                         *  2. Placeholder : Translation String
                         *  -----------------------------------
                         */
                        esc_attr__( 'Your Email', 'sdweddingdirectory-request-quote' ),

                        /**
                         *  3. Is Couple ? - Get Couple Email ID
                         *  ------------------------------------
                         */
                        !   empty( $couple_post_id )

                        ?   sanitize_email( parent:: get_data( 'user_email' ) )

                        :   ''
            );

            /**
             *  Return
             *  ------
             */
            return      $collection     .       $new_fields;
        }

        /**
         *  Budget Field
         *  ------------
         */
        public static function budget_field( $collection = '', $args = [] ){

            /**
             *  Extract args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'venue_post_id'     =>    absint( '0' ),

                'couple_post_id'      =>    absint( '0' ),

                'new_fields'          =>    '',

            ) ) );

            /**
             *  Budget Field Enable for this category ?
             *  ---------------------------------------
             */
            if( self:: this_fields_enable( 'request_quote_budget_field', $venue_post_id ) ){

                /**
                 *  Your Budget
                 *  -----------
                 */
                $new_fields        =

                sprintf(  '<div class="mb-3">
                              <input autocomplete="off" type="number" name="%1$s" placeholder="%2$s" class="form-control" />
                          </div>',

                          /**
                           *  1. Field ID
                           *  -----------
                           */
                          esc_attr( 'sdweddingdirectory_request_quote_budget' ),

                          /**
                           *  2. Placeholder : Translation String
                           *  -----------------------------------
                           */
                          esc_attr__( 'Your Budget Amount', 'sdweddingdirectory-request-quote' )
                );

                /**
                 *  Return
                 *  ------
                 */
                return      $collection     .       $new_fields;
            }

            /**
             *  Return Default Data
             *  -------------------
             */
            else{


                return      $collection;
            }
        }

        /**
         *  Phone Number Field
         *  ------------------
         */
        public static function phone_number_field( $collection = '', $args = [] ){

            /**
             *  Extract args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'venue_post_id'     =>    absint( '0' ),

                'couple_post_id'      =>    absint( '0' ),

                'new_fields'          =>    '',

            ) ) );

            /**
             *  New Fields
             *  ----------
             */
            $new_fields        =

            sprintf(    '<div class="mb-3">
                        
                            <input autocomplete="off" type="text" name="%1$s" placeholder="%2$s" value="%3$s" class="form-control" required />
                        
                        </div>',

                        /**
                         *  1. Field ID
                         *  -----------
                         */
                        esc_attr( 'sdweddingdirectory_request_quote_contact' ),

                        /**
                         *  2. Placeholder : Translation String
                         *  -----------------------------------
                         */
                        esc_attr__( 'Phone Number', 'sdweddingdirectory-request-quote' ),

                        /**
                         *  3. Is Couple ? - Get Couple Contact Number
                         *  ------------------------------------------
                         */
                        !   empty( $couple_post_id )

                        ?   esc_attr( parent:: get_data( 'user_contact' ) )

                        :   ''
            );

            /**
             *  Return
             *  ------
             */
            return      $collection     .       $new_fields;
        }

        /**
         *  Message Field
         *  -------------
         */
        public static function message_field( $collection = '', $args = [] ){

            /**
             *  Extract args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'venue_post_id'       =>      absint( '0' ),

                'couple_post_id'        =>      absint( '0' ),

                'new_fields'            =>      '',

                'rows'                  =>      apply_filters( 'sdweddingdirectory/request-quote/message/rows', absint( '4' ) )

            ) ) );

            /**
             *  New Fields
             *  ----------
             */
            $new_fields        =

            sprintf(    '<div class="mb-3">
                            
                            <textarea rows="%4$s" placeholder="%2$s" name="%1$s" class="form-control" required>%3$s</textarea>
                        
                        </div>',

                        /**
                         *  1. Field ID
                         *  -----------
                         */
                        esc_attr( 'sdweddingdirectory_request_quote_message' ),

                        /**
                         *  2. Placeholder : Translation String
                         *  -----------------------------------
                         */
                        esc_attr__( 'Your message', 'sdweddingdirectory-request-quote' ),

                        /**
                         *  3. Get Venue Parent Category Default Message
                         *  ----------------------------------------------
                         */
                        !   empty( $venue_post_id )

                        ?   self:: random_message_value( $venue_post_id )

                        :   '',

                        /**
                         *  4. Rows
                         *  -------
                         */
                        $rows
            );

            /**
             *  Return
             *  ------
             */
            return      $collection     .       $new_fields;
        }

        /**
         *  Event Date Field
         *  ----------------
         */
        public static function event_date_field( $collection = '', $args = [] ){

            /**
             *  Extract args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'venue_post_id'     =>    absint( '0' ),

                'couple_post_id'      =>    absint( '0' ),

                'new_fields'          =>    '',

            ) ) );

            /**
             *  New Fields
             *  ----------
             */
            $new_fields        =

            sprintf(  '<div class="mb-3">

                        <p class="mb-2">%1$s</p>

                        <div class="%10$s collapse show" id="open_date">

                            <div class="mb-3">

                                <div class="row">

                                    <div class="col">%2$s</div>

                                    <div class="col">%3$s</div>

                                </div>

                            </div>

                            <a class="btn-link btn-link-primary btn p-0 flexible_date_chose" data-bs-toggle="collapse" data-bs-target=".%10$s" aria-expanded="false" aria-controls="fix_date open_date"><small>%4$s</small></a>

                        </div>

                        <div class="collapse %10$s" id="fix_date">
                        
                            <div class="mb-3">

                                <input autocomplete="off" type="date" id="%5$s" name="%5$s" value="%6$s" class="form-control" />

                            </div>

                            <div class="mb-3">

                                <input autocomplete="off" type="checkbox" class="form-check-input" name="%7$s" id="%7$s">

                                <label class="form-check-label" for="%7$s">%8$s</label>

                            </div>

                            <a class="btn-link btn-link-primary btn p-0 flexible_date_chose" data-bs-toggle="collapse" data-bs-target=".%10$s" aria-expanded="false" aria-controls="fix_date open_date"><small>%9$s</small></a>
                            
                        </div>

                      </div>',

                      /**
                       *  1. Translation Ready String
                       *  ---------------------------
                       */
                      esc_attr__( 'Event date', 'sdweddingdirectory-request-quote' ),

                      /**
                       *  2. Month list
                       *  -------------
                       */
                      self:: _month_list(),

                      /**
                       *  3. Year list
                       *  ------------
                       */
                      self:: _next_five_year_list(),

                      /**
                       *  4. Translation Ready String
                       *  ---------------------------
                       */
                      esc_attr__( 'Do you have an exact date?', 'sdweddingdirectory-request-quote' ),

                      /**
                       *  5. Field ID
                       *  -----------
                       */
                      esc_attr( 'sdweddingdirectory_request_quote_wedding_date' ),

                      /**
                       *  6. Is Couple ? - Get Couple Wedding Date
                       *  ----------------------------------------
                       */
                      !     empty( $couple_post_id )

                      ?     esc_attr( parent:: get_data( 'wedding_date' ) )

                      :     '',

                      /**
                       *  7. Flexible Date ?
                       *  ------------------
                       */
                      esc_attr( 'sdweddingdirectory-request-quote-flexible-date' ),

                      /**
                       *  8. Translation Ready String
                       *  ---------------------------
                       */
                      esc_attr__( 'Open to other dates', 'sdweddingdirectory-request-quote' ),

                      /**
                       *  9. Translation Ready String
                       *  ---------------------------
                       */
                      esc_attr__( 'Haven\'t decided on a date yet?', 'sdweddingdirectory-request-quote' ),

                      /**
                       *  10. Collapse Toggle Rand ID
                       *  ---------------------------
                       */
                      esc_attr(   parent:: _rand()  )
            );

            /**
             *  Return
             *  ------
             */
            return      $collection     .       $new_fields;
        }

        /**
         *  Month list
         *  ----------
         */
        public static function _month_list(){

            $_collection    =   '';

            $list           =    [

                '0'     =>  esc_attr__( 'Month', 'sdweddingdirectory-request-quote' ),

                '1'     =>  esc_attr__( 'January','sdweddingdirectory-request-quote' ),

                '2'     =>  esc_attr__( 'February','sdweddingdirectory-request-quote' ),

                '3'     =>  esc_attr__( 'March','sdweddingdirectory-request-quote' ),

                '4'     =>  esc_attr__( 'April','sdweddingdirectory-request-quote' ),

                '5'     =>  esc_attr__( 'May','sdweddingdirectory-request-quote' ),

                '6'     =>  esc_attr__( 'June','sdweddingdirectory-request-quote' ),

                '7'     =>  esc_attr__( 'July','sdweddingdirectory-request-quote' ),

                '8'     =>  esc_attr__( 'August','sdweddingdirectory-request-quote' ),

                '9'     =>  esc_attr__( 'September','sdweddingdirectory-request-quote' ),

                '10'    =>  esc_attr__( 'October','sdweddingdirectory-request-quote' ),

                '11'    =>  esc_attr__( 'November','sdweddingdirectory-request-quote' ),

                '12'    =>  esc_attr__( 'December', 'sdweddingdirectory-request-quote' ),
            ];

            $_collection    .=  '<select class="form-control" name="request_quote_select_month">';

            foreach( $list as $key => $value ){

                $_collection    .=  sprintf( '<option value="%1$s">%2$s</option>', absint( $key ), esc_attr( $value ) );
            }
            
            $_collection    .=  '</select>';

            /**
             *  Month list
             *  ----------
             */
            return      $_collection;
        }

        /**
         *  Month list
         *  ----------
         */
        public static function _next_five_year_list(){

            $_collection    =   '';

            $_collection    .=  '<select class="form-control" name="request_quote_select_year">';

            $_collection    .=  sprintf( '<option value="%1$s">%2$s</option>', absint( '0' ), esc_attr__( 'Year', 'sdweddingdirectory-request-quote' ) );

            for ( $i= esc_attr( date( 'Y' ) ); $i < esc_attr( date( 'Y' ) ) + absint( '5' ) ; $i++ ){

                $_collection    .=  sprintf( '<option value="%1$s">%1$s</option>', absint( $i ) );
            }

            $_collection    .=  '</select>';

            /**
             *  Next 5 Year list
             *  ----------------
             */
            return      $_collection;
        }

        /**
         *  Guest Field
         *  -----------
         */
        public static function guest_field( $collection = '', $args = [] ){

            /**
             *  Extract args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'venue_post_id'     =>    absint( '0' ),

                'couple_post_id'      =>    absint( '0' ),

                'new_fields'          =>    '',

            ) ) );

            /**
             *  Guest Fields Enable ?
             *  ---------------------
             */
            if( self:: this_fields_enable( 'request_quote_number_of_guest_field', $venue_post_id ) ){

                /**
                 *  Number Of Guest
                 *  ---------------
                 */
                $new_fields        =

                sprintf(   '<div class="mb-3">

                                <p>%1$s</p>

                                <div class="btn-group d-flex" role="group">

                                    <input type="radio" class="btn-check" value="%3$s" name="%2$s" id="%7$s_one" autocomplete="off">
                                    <label class="btn btn-outline-primary" for="%7$s_one">%3$s</label>

                                    <input type="radio" class="btn-check" value="%4$s" name="%2$s" id="%7$s_sec" autocomplete="off">
                                    <label class="btn btn-outline-primary" for="%7$s_sec">%4$s</label>

                                    <input type="radio" class="btn-check" value="%5$s" name="%2$s" id="%7$s_third" autocomplete="off">
                                    <label class="btn btn-outline-primary" for="%7$s_third">%5$s</label>

                                    <input type="radio" class="btn-check" value="%6$s" name="%2$s" id="%7$s_four" autocomplete="off">
                                    <label class="btn btn-outline-primary" for="%7$s_four">%6$s</label>

                                </div>

                            </div>',

                            /**
                             *  1. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr__( 'Approx. guest count', 'sdweddingdirectory-request-quote' ),

                            /**
                             *  2. Field ID
                             *  -----------
                             */
                            esc_attr( 'sdweddingdirectory_request_quote_guest' ),

                            /**
                             *  3. Value 1st radio
                             *  ------------------
                             */
                            esc_attr( '0-75' ),

                            /**
                             *  4. Value 2st radio
                             *  ------------------
                             */
                            esc_attr( '75-125' ),

                            /**
                             *  5. Value 3st radio
                             *  ------------------
                             */
                            esc_attr( '125-175' ),

                            /**
                             *  6. Value 3st radio
                             *  ------------------
                             */
                            esc_attr( '+175' ),

                            /**
                             *  7. ID
                             *  -----
                             */
                            parent:: _rand()
                );

                /**
                 *  Return
                 *  ------
                 */
                return      $collection     .       $new_fields;
            }

            /**
             *  Return Default Collection
             *  -------------------------
             */
            else{

                return      $collection;
            }
        }

        /**
         *  Contact Me Field
         *  ----------------
         */
        public static function contact_me_field( $collection = '', $args = [] ){

            /**
             *  Extract args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'venue_post_id'     =>    absint( '0' ),

                'couple_post_id'      =>    absint( '0' ),

                'new_fields'          =>    '',

            ) ) );

            /**
             *  New Fields
             *  ----------
             */
            $new_fields     =      '';

            $new_fields    .=      sprintf(   '<div class="mb-3">

                                                    <p><strong><small class="txt-orange">%1$s</small></strong></p>

                                                </div>',

                                                /**
                                                 *  1. Translation Ready String
                                                 *  ---------------------------
                                                 */
                                                esc_attr__( 'Preferred Contact Method', 'sdweddingdirectory-request-quote' )
                                        );

            
            $new_fields    .=      '<div class="d-flex justify-content-between mb-3">';

            /**
             *  Contact Couple Option : Email
             *  -----------------------------
             */
            $new_fields    .=      sprintf(    '<div class="form-check form-check-inline">

                                                    <input autocomplete="off" type="radio" class="form-check-input"  checked="checked" 

                                                    name="%2$s" id="%1$s" value="2" />

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
                                                esc_attr( 'sdweddingdirectory_request_quote_contact_option' ),

                                                /**
                                                 *  3. Placeholder : Translation Ready String
                                                 *  -----------------------------------------
                                                 */
                                                esc_attr__( 'Email', 'sdweddingdirectory-request-quote' )
                                        );

            /**
             *  Contact Couple Option : Video Call
             *  ----------------------------------
             */
            $new_fields    .=      sprintf(    '<div class="form-check form-check-inline">

                                                    <input autocomplete="off" type="radio" class="form-check-input" 

                                                    name="%2$s" id="%1$s" value="3" />

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
                                                esc_attr( 'sdweddingdirectory_request_quote_contact_option' ),

                                                /**
                                                 *  3. Placeholder : Translation Ready String
                                                 *  -----------------------------------------
                                                 */
                                                esc_attr__( 'Video Chat', 'sdweddingdirectory-request-quote' )
                                        );

            /**
             *  Contact Couple Option : Call
             *  ----------------------------
             */
            $new_fields    .=      sprintf(    '<div class="form-check form-check-inline">

                                                    <input autocomplete="off" type="radio" class="form-check-input" 

                                                    name="%2$s" id="%1$s" value="1" />

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
                                                esc_attr( 'sdweddingdirectory_request_quote_contact_option' ),

                                                /**
                                                 *  3. Placeholder : Translation Ready String
                                                 *  -----------------------------------------
                                                 */
                                                esc_attr__( 'Phone', 'sdweddingdirectory-request-quote' )
                                    );


            $new_fields    .=      '</div>';


            /**
             *  Return
             *  ------
             */
            return      $collection     .       $new_fields;
        }

        /**
         *  Term & Condition Field
         *  ----------------------
         */
        public static function term_and_condition_note( $collection = '', $args = [] ){

            /**
             *  Extract args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'venue_post_id'     =>    absint( '0' ),

                'couple_post_id'      =>    absint( '0' ),

                'new_fields'          =>    '',

            ) ) );

            /**
             *  Privacy Policy Note
             *  -------------------
             */
            $new_fields        =

            apply_filters( 'sdweddingdirectory/term_and_condition_note', [

                'name'          =>      esc_attr__( 'Request pricing', 'sdweddingdirectory-request-quote' ),

                'before'        =>      '<div class="mb-3">',

                'after'         =>      '</div>'

            ] );

            /**
             *  Return
             *  ------
             */
            return      $collection     .       $new_fields;
        }

        /**
         *  Phone Number Field
         *  ------------------
         */
        public static function submit_field( $collection = '', $args = [] ){

            /**
             *  Extract args
             *  ------------
             */
            extract( wp_parse_args( $args, array(

                'venue_post_id'     =>    absint( '0' ),

                'couple_post_id'      =>    absint( '0' ),

                'new_fields'          =>    '',

                'hidden_input'        =>    '',

                'form_id'             =>    ''

            ) ) );

            /**
             *  Hidden Fields
             *  -------------
             */
            $_hidden_fields         =       apply_filters( 'sdweddingdirectory/request-quote/form-fields/hidden', [

                                                /**
                                                 *  1. Venue Post ID
                                                 *  ------------------
                                                 */
                                                esc_attr( 'sdweddingdirectory_request_quote_venue_post_id' )  

                                                =>  absint(  $venue_post_id  ),

                                                /**
                                                 *  2. Couple Post ID
                                                 *  -----------------
                                                 */
                                                esc_attr( 'sdweddingdirectory_request_quote_couple_post_id' )   

                                                =>  absint( $couple_post_id ),

                                                /**
                                                 *  3. Couple Security
                                                 *  ------------------
                                                 */
                                                esc_attr( 'sdweddingdirectory_request_quote_couple_id_security' )

                                                =>  wp_create_nonce( 'sdweddingdirectory_request_quote_couple_id_security-' . absint( $couple_post_id )  ),

                                                /**
                                                 *  4. Request Quote Security
                                                 *  -------------------------
                                                 */
                                                esc_attr( 'sdweddingdirectory_request_quote_security' )

                                                =>  wp_create_nonce( 'sdweddingdirectory_request_quote_security-' . absint( $couple_post_id )  ),

                                            ] );
            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array(  $_hidden_fields  ) ){

                foreach( $_hidden_fields as $key => $value ){

                    /**
                     *  Venue Post ID + Couple Post ID
                     *  --------------------------------
                     */
                    $hidden_input     .=    sprintf( '<input autocomplete="off" type="hidden" name="%1$s" value="%2$s" />',

                                                /**
                                                 *  1. Field ID
                                                 *  -----------
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
             *  Translation Ready String
             *  ------------------------
             */
            $_submit_btn        =       esc_attr__( 'Request Pricing', 'sdweddingdirectory-request-quote' );

            /**
             *  Submit Button
             *  -------------
             */
            if( parent:: is_couple() ){

                /**
                 *  Submit Button + Security
                 *  ------------------------
                 */
                $new_fields        =   sprintf( '<div class="d-grid">

                                                    <button type="submit" class="btn btn-primary btn-block" name="%1$s" 

                                                        id="%1$s">%2$s</button>

                                                    <div class="security-fields">%3$s</div>

                                                </div>',

                                                /**
                                                 *  1. Name + ID
                                                 *  -----------
                                                 */
                                                esc_attr( 'sdweddingdirectory_request_quote_submit_button' ),

                                                /**
                                                 *  2. Value
                                                 *  --------
                                                 */
                                                esc_attr( $_submit_btn ),

                                                /**
                                                 *  3. Couple Security
                                                 *  ------------------
                                                 */
                                                $hidden_input
                                        );
            }

            /**
             *  Not User Login Button
             *  ---------------------
             */
            elseif( ! is_user_logged_in() ){

                /**
                 *  Couple Login Required
                 *  ---------------------
                 */
                $new_fields        =   sprintf(   '<div class="d-grid">

                                                        <a  class="btn btn-primary btn-block" %2$s>%1$s</a>

                                                    </div>',

                                                    /**
                                                     *  1. Translation Ready String
                                                     *  ---------------------------
                                                     */
                                                    esc_attr( $_submit_btn ),

                                                    /**
                                                     *  2. Couple Model Popup ID
                                                     *  ------------------------
                                                     */
                                                    apply_filters( 'sdweddingdirectory/couple-login/attr', '' )
                                        );
            }

            /**
             *  Return
             *  ------
             */
            return      $collection     .       $new_fields;
        }

        /**
         *  Venue Category Wise Get Default Message
         *  -----------------------------------------
         */
        public static function random_message_value( $venue_post_id = 0 ){

            /**
             *  Category ID
             *  -----------
             */
            $_get_category_id       =       apply_filters( 'sdweddingdirectory/post/term-parent', [

                                                'post_id'       =>      absint( $venue_post_id ),

                                                'taxonomy'      =>      esc_attr( 'venue-type' ),

                                            ] );

            /**
             *  Make sure couple is login ?
             *  ---------------------------
             */
            if( parent:: is_couple() ){

                /**
                 *  ACF : Request Quote Message Data
                 *  --------------------------------
                 */
                $_messages  =   

                sdwd_get_term_field(

                    /**
                     *  1. Venue Category Taxonomy Key Meta
                     *  -------------------------------------
                     */
                    'couple_login_request_message',

                    /**
                     *  2. Term ID
                     *  ----------
                     */
                    $_get_category_id
                );

            }else{

                $_messages  =   

                sdwd_get_term_field(

                    /**
                     *  1. Venue Category Taxonomy Key Meta
                     *  -------------------------------------
                     */
                    'front_end_user_message',

                    /**
                     *  2. Term ID
                     *  ----------
                     */
                    $_get_category_id
                );
            }

            /**
             *  Is Array ?
             *  ----------
             */
            if( parent:: _is_array( $_messages ) ){

                $limit          =   count( $_messages );

                $number         =   rand( absint( '0' ), $limit - absint( '1' ) );
                
                $template       =   $_messages[ $number ][ 'request_message' ];

                $variables      =   [
                                        'site_name'     =>      esc_attr( get_bloginfo( 'name' ) ),

                                        'wedding_date'  =>      esc_attr( parent:: get_data( 'wedding_date' ) )
                                    ];

                if( parent:: _is_array( $variables ) ){

                      foreach( $variables as $key => $value ){

                          if( isset( $key ) ){

                              $template     =   str_replace('{{'.$key.'}}', preg_replace('/\s+/', ' ', $value  ), $template );
                          }
                      }
                }

                /**
                 *  Return : String
                 *  ---------------
                 */
                return      $template;

            }else{

                return;
            }
        }

        /**
         *  Make sure acf condition is ON
         *  -----------------------------
         */
        public static function this_fields_enable( $acf_id = '', $venue_post_id = 0 ){

            /**
             *  Category ID
             *  -----------
             */
            $_get_category_id       =       apply_filters( 'sdweddingdirectory/post/term-parent', [

                                                'post_id'       =>      absint( $venue_post_id ),

                                                'taxonomy'      =>      esc_attr( 'venue-type' ),

                                            ] );

            /**
             *  ACF : Request Quote Message Data
             *  --------------------------------
             */
            return

            sdwd_get_term_field(

                /**
                 *  1. Venue Category Taxonomy Key Meta
                 *  -------------------------------------
                 */
                sanitize_key( $acf_id ),

                /**
                 *  2. Term ID
                 *  ----------
                 */
                $_get_category_id
            );
        }
    }   

    /**
     *  SDWeddingDirectory - Request Quote OBJ call
     *  -----------------------------------
     */
    SDWeddingDirectory_Request_Quote_Form_Fields:: get_instance();
}