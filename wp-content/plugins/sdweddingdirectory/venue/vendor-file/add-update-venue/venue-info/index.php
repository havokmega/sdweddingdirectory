<?php
/**
 *  ---------------------------
 *  SDWeddingDirectory - Venue Fields
 *  ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Fields_Post_Info' ) && class_exists( 'SDWeddingDirectory_Dashboard_Venue_Update' ) ){

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    class SDWeddingDirectory_Venue_Fields_Post_Info extends SDWeddingDirectory_Dashboard_Venue_Update{

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

            return  self::$instance;
        }

        /**
         *  Tab ID
         *  ------
         */
        public static function tab_id(){

            return      sanitize_title( self:: tab_name() );
        }

        /**
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return      esc_attr__( 'About Venue', 'sdweddingdirectory-venue' );
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Add Filter
             *  ----------
             */
            add_filter( 'sdweddingdirectory/add-update-venue/tabs', [ $this, 'tab_info' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Tab Info
         *  --------
         */
        public static function tab_info( $args = [] ){

            /**
             *  Merge Tabs
             *  ----------
             */
            return      array_merge( $args, [

                            self:: tab_id()         =>      [

                                'active'            =>      true,

                                'id'                =>      esc_attr( parent:: _rand() ),

                                'name'              =>      esc_attr( self:: tab_name() ),

                                'callback'          =>      [ 'class' => __CLASS__, 'member' => 'tab_content' ],
                            ]

                        ] );
        }

        /**
         *  Select Category Field
         *  ---------------------
         */
        public static function tab_content(){

            /**
             *  Post ID
             *  -------
             */
            $post_id        =       parent:: venue_post_ID();

            /**
             *   Section Information
             *   -------------------
             */
            parent:: create_section( array(

                'field'     =>   array(

                    'field_type'  =>   esc_attr( 'info' ),

                    'title'       =>   esc_attr__( 'About Venue', 'sdweddingdirectory-venue' ),

                    'class'       =>   sanitize_html_class( 'mb-0' )
                )

            ) );

            /**
             *  Venue Title
             *  -------------
             */
            parent:: create_section( array(

                /**
                 *  DIV Managment
                 *  -------------
                 */
                'div'        =>  array(

                    'id'          =>   '',

                    'class'       =>   sanitize_html_class( 'card-body' ),

                    'start'       =>   true,

                    'end'         =>   false,
                ),

                /**
                 *  Row Managment
                 *  -------------
                 */
                'row'        =>  array(

                    'id'          =>   '',

                    'class'       =>   '',

                    'start'       =>   true,

                    'end'         =>   false,
                ),

                /**
                 *  Column Managment
                 *  ----------------
                 */
                'column'     =>   array(

                    'grid'        =>   absint( '12' ),

                    'id'          =>   '',

                    'class'       =>   '',

                    'start'       =>   true,

                    'end'         =>   true,
                ),

                /**
                 *  Fields Arguments
                 *  ----------------
                 */
                'field'                 =>      array(

                    'field_type'        =>      esc_attr( 'input' ),

                    'type'              =>      esc_attr( 'text' ),

                    'placeholder'       =>      esc_attr__( 'Venue Title', 'sdweddingdirectory-venue' ),

                    'name'              =>      esc_attr( 'post_title' ),

                    'id'                =>      esc_attr( 'post_title' ),

                    'value'             =>      !   empty( $post_id )

                                                ?   esc_attr( get_the_title( absint( $post_id ) ) )

                                                :   ''
                )

            ) );

            /**
             *  Profile URL Slug
             *  -----------------
             */
            if( ! empty( $post_id ) ){

                ?><div class="col-md-12 mb-3">
                    <div class="form-group">
                        <label><?php esc_html_e( 'Profile URL Slug', 'sdweddingdirectory-venue' ); ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><?php echo esc_url( home_url( '/venues/' ) ); ?></span>
                            <input type="text" class="form-control" name="sdweddingdirectory_venue_slug" id="sdweddingdirectory_venue_slug" value="<?php echo esc_attr( get_post_field( 'post_name', absint( $post_id ) ) ); ?>" />
                        </div>
                        <small class="form-text text-muted"><?php esc_html_e( 'This controls your public venue URL. Use lowercase letters, numbers, and hyphens only.', 'sdweddingdirectory-venue' ); ?></small>
                    </div>
                </div><?php
            }

            /**
             *  Write Description ( Editor )
             *  ----------------------------
             */
            parent:: create_section( array(

                /**
                 *  DIV Managment
                 *  -------------
                 */
                'div'        =>  array(

                    'id'          =>   '',

                    'class'       =>   sanitize_html_class( 'card-body' ),

                    'start'       =>   false,

                    'end'         =>   true,
                ),

                /**
                 *  Row Managment
                 *  -------------
                 */
                'row'        =>  array(

                    'id'          =>   '',

                    'class'       =>   '',

                    'start'       =>   false,

                    'end'         =>   true,
                ),

                /**
                 *  Column Managment
                 *  ----------------
                 */
                'column'     =>   array(

                    'grid'        =>   absint( '12' ),

                    'id'          =>   '',

                    'class'       =>   '',

                    'start'       =>   true,

                    'end'         =>   true,
                ),

                /**
                 *  Fields Arguments
                 *  ----------------
                 */
                'field'                 =>  array(

                    'formgroup'         =>  true,

                    'field_type'        =>  esc_attr( 'textarea' ),

                    'type'              =>  esc_attr( 'textarea' ),

                    'placeholder'       =>  esc_attr__( 'Write Your Venue Information Here', 'sdweddingdirectory-venue' ),

                    'name'              =>  esc_attr( 'post_content' ),

                    'id'                =>  esc_attr( 'post_content' ),

                    'height'            =>  absint( '300' ),

                    'limit'             =>  absint( '5000' ),

                    'value'             =>  self:: _have_value( $post_id ),
                )

            ) );
        }

        /**
         *  Have Post ID ?
         *  --------------
         */
        public static function _have_value( $post_id = 0 ){

            /**
             *  Post ID
             *  -------
             */
            if( ! empty( $post_id ) ){

                /**
                 *  Get Content ?
                 *  -------------
                 */
                $post               =   get_post( absint( $post_id ) );

                $the_content        =   apply_filters( 'the_content', $post->post_content );

                /**
                 *  Return Content
                 *  --------------
                 */
                return      $the_content;
            }

            /**
             *  Return Information Text
             *  -----------------------
             */
            else{

                return      esc_attr__( 'Write Your Venue Information', 'sdweddingdirectory-venue' );
            }
        }
    }

    /**
     *  ---------------------------
     *  SDWeddingDirectory - Venue Fields
     *  ---------------------------
     */
    SDWeddingDirectory_Venue_Fields_Post_Info::get_instance();
}
