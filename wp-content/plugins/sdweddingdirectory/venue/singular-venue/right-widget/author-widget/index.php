<?php
/**
 *  SDWeddingDirectory Vendor Profile
 *  -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Venue_Singular_Right_Side_Widget_Author_Info' ) && class_exists( 'SDWeddingDirectory_Venue_Singular_Right_Side_Widget' ) ){

    /**
     *  SDWeddingDirectory Vendor Profile
     *  -------------------------
     */
    class SDWeddingDirectory_Venue_Singular_Right_Side_Widget_Author_Info extends SDWeddingDirectory_Venue_Singular_Right_Side_Widget{

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
             *  1. Venue Owner Information
             *  ----------------------------
             */
            add_action( 'sdweddingdirectory/venue/singular/right-side/widget', [ $this, 'widget' ], absint( '40' ), absint( '1' ) );
        }

        /**
         *  1. Venue Owner Information
         *  ----------------------------
         */
        public static function widget( $args = [] ){

        	/**
        	 *  Have Args ?
        	 *  -----------
        	 */
        	if( parent:: _is_array( $args ) ){

        		/**
        		 *  Extract Args
        		 *  ------------
        		 */
        		extract( wp_parse_args( $args, [

        			'post_id'		=>		absint( '0' )

        		] ) );

        		/**
        		 *  Make sure post id not emtpy!
        		 *  ----------------------------
        		 */
        		if( empty( $post_id ) ){

        			return;
        		}

	            /**
	             *  This venue created with "Vendor" role + Sending Vendor Post ID
	             *  ----------------------------------------------------------------
	             */
	            if( parent:: venue_author_is_vendor( $post_id ) ){

	                /**
	                 *  Load Venue Author Info
	                 *  ------------------------
	                 */
	                $_vendor_id    		= 	parent:: venue_author_post_id( absint( $post_id ) );

	                $_have_image   		= 	absint( get_post_meta( absint( $_vendor_id ), sanitize_key( 'business_icon' ), true ) );

	                $_vendor_img 		=	parent:: _have_media( $_have_image )

	                						?	apply_filters( 'sdweddingdirectory/media-data', [

													'media_id'		=>	$_have_image,

													'image_size'	=>	esc_attr( 'thumbnail' ),
												] )

	                						:	esc_url( parent:: placeholder( 'vendor-brand-image' ) );

	                $_vendor_contact   	=  	get_post_meta( absint( $_vendor_id ), sanitize_key( 'company_contact' ), true );

	                $_vendor_email 		=	get_post_meta( absint( $_vendor_id ), sanitize_key( 'company_email' ), true );

	                $_vendor_website	=	get_post_meta( absint( $_vendor_id ), sanitize_key( 'company_website' ), true );

	                $_business_name		=	get_post_meta( absint( $_vendor_id ), sanitize_key( 'company_name' ), true );

                    $_vendor_name  		=  	sprintf( '%1$s %2$s',

	                                            get_post_meta( absint( $_vendor_id ), sanitize_key( 'first_name' ), true ),

	                                            get_post_meta( absint( $_vendor_id ), sanitize_key( 'last_name' ), true )
	                                        );

	                ?>
	                <div class="widget">

	                    <h3 class="widget-title"><?php esc_attr_e( 'Supplier Profile', 'sdweddingdirectory-venue' ); ?></h3>

	                    <?php

	                    	/**
	                    	 *  1. Vendor information
	                    	 *  ---------------------
	                    	 */
	                    	printf('<div class="profile-avatar">

				                        <img src="%1$s" alt="%4$s" class="w-25">

				                        <div class="content"> <small>%2$s</small> <a href="%5$s">%3$s</a> </div>

				                    </div>', 

				                    /**
				                     *  1. Vendor Image
				                     *  ---------------
				                     */
				                    esc_url( $_vendor_img ),

				                    /**
				                     *  2. Translation String
				                     *  ---------------------
				                     */
				                    esc_attr__( 'Featuring', 'sdweddingdirectory-venue' ),

				                    /**
				                     *  3. Business Name
				                     *  ----------------
				                     */
				                    esc_attr( $_business_name ),

				                    /**
				                     *  4. Image alt
				                     *  ------------
				                     */
				                    esc_attr( get_bloginfo( 'name' ) ),

				                    /**
				                     *  5. Vendor Singular Link
				                     *  -----------------------
				                     */
				                    esc_url( get_the_permalink( $_vendor_id ) )
				            );

	                    	$_vendor_fields 	=	[];

	                    	/**
	                    	 *  Have Contact information ?
	                    	 *  --------------------------
	                    	 */
	                    	if( parent:: _have_data( $_vendor_contact ) ){

		                    	$_vendor_fields[]	= 	array(

	                    			'icon'			=>	'<i class="fa fa-phone"></i>',

	                    			'value'			=>	sprintf( '<a href="tel:%2$s" class="btn-link btn-link-secondary">%1$s</a>',

	                                                        /**
	                                                         *  1. Get Vendor Contact Information
	                                                         *  ---------------------------------
	                                                         */
	                                                        esc_attr(

	                                                            /**
	                                                             *  1. get backend email id via vendor post
	                                                             *  ---------------------------------------
	                                                             */
	                                                            esc_attr( $_vendor_contact )
	                                                        ),

	                                                        /**
	                                                         *  2. Click to Call Tel
	                                                         *  --------------------
	                                                         */
	                                                        esc_attr( 	preg_replace( '/[^\d+]/', '',  $_vendor_contact  )   )
		                    							)
	                    		);
	                    	}

	                    	/**
	                    	 *  Have Email ID ?
	                    	 *  ---------------
	                    	 */
	                    	if( parent:: _have_data( $_vendor_email ) ){

		                    	$_vendor_fields[]	= 	array(

	                    			'icon'			=>	'<i class="fa fa-envelope-o"></i>',

	                    			'value'			=>	sprintf( '<a href="mailto:%1$s" class="btn-link btn-link-secondary">%1$s</a>',

		                    								/**
		                    								 *  1. Email ID
		                    								 *  -----------
		                    								 */
		                    								sanitize_email( $_vendor_email )
		                    							)
	                    		);
	                    	}

	                    	/**
	                    	 *  Have Website ?
	                    	 *  --------------
	                    	 */
	                    	if( parent:: _have_data( $_vendor_website ) ){

		                    	$_vendor_fields[]	= 	array(

	                    			'icon'			=>	'<i class="fa fa-globe"></i>',

	                    			'value'			=>	sprintf( '<a href="%1$s" target="_blank" class="btn-link btn-link-secondary">%1$s</a>',

		                    								/**
		                    								 *  1. Email ID
		                    								 *  -----------
		                    								 */
		                    								esc_url( $_vendor_website )
		                    							)
	                    		);
	                    	}

	                    	/**
	                    	 *  2. Load Meta Data
	                    	 *  -----------------
	                    	 */
							if( parent:: _is_array( $_vendor_fields ) ){

								foreach ( $_vendor_fields as $key => $value) {

									/**
									 *  List Of Vendors information
									 *  ---------------------------
									 */
			                    	printf('<div class="icon-box-style-3 sided mt-3 mb-0">
						                        %1$s <span> %2$s </span>
						                    </div>',

						                    /**
						                     *  1. Icon
						                     *  -------
						                     */
						                    $value[ 'icon' ],

						                    /**
						                     *  2. Value
						                     *  --------
						                     */
						                    $value[ 'value' ]
						            );
								}
							}

				            /**
				             *  Load Social Media
				             *  -----------------
				             */
							$_vendor_social_media 		= 	[];

							/**
							 *  Have Facebook ?
							 *  ---------------
							 */
							$_social_profile_list	 	=	get_post_meta( absint( $_vendor_id ), sanitize_key( 'social_profile' ), true );

							$_list_of_icon 				=	apply_filters( 'sdweddingdirectory/social-media', [] );

							/**
							 *  Have Data ?
							 *  -----------
							 */
							if( parent:: _is_array( $_social_profile_list ) ){

								/**
								 *  Have Data ?
								 *  -----------
								 */
								foreach( $_social_profile_list as $key => $value ){

									/**
									 *  Extract
									 *  -------
									 */
									extract( $value );

									$_vendor_social_media[] 	= array(

										'icon'		=>	sprintf( '<i class="fa %1$s"></i>', $platform ),

										'class'		=>	$_list_of_icon[ $platform ][ 'color' ],

										'link'		=>	esc_url( $link ),

									);
								}
							}

							/**
							 *  Have Social Profile ?
							 *  ---------------------
							 */
							if( parent:: _is_array( $_vendor_social_media ) ){

								?><div class="social-sharing sidebar-social border-top"><?php

									foreach ( $_vendor_social_media as $key => $value ) {

										/**
										 *  Extract
										 *  -------
										 */
										extract( $value );
							
										/**
										 *  Load Vendor Social Media
										 *  ------------------------
										 */
										printf( '<a href="%1$s" target="_blank" class="%2$s">%3$s</a>', 

												/**
												 *  1. Social Link
												 *  --------------
												 */
												esc_url( $link ),

												/**
												 *  2. Class
												 *  --------
												 */
												sanitize_html_class( $class ),

												/**
												 *  3. Icon
												 *  -------
												 */
												$icon
										);
									}

								?></div><?php
							}
						?>
	                </div>
	                <?php
	            }
        	}
        }
    }

    /**
     *  Venue Author Widget
     *  ---------------------
     */
    SDWeddingDirectory_Venue_Singular_Right_Side_Widget_Author_Info::get_instance();
}