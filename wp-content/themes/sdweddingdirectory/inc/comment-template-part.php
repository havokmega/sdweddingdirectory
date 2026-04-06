<?php
/*
 *  SDWeddingDirectory - Comment Helper
 *  ---------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Comment_Helper' ) && class_exists( 'SDWeddingDirectory' ) ){

	/*
	 *  SDWeddingDirectory - Comment Helper
	 *  ---------------------------
	 */
	class SDWeddingDirectory_Comment_Helper extends SDWeddingDirectory{

	    /**
	     * Member Variable
	     *
	     * @var instance
	     */
	    private static $instance;

	    /**
	     *  Initiator
	     */
	    public static function get_instance() {
	    	
	      if ( ! isset( self::$instance ) ) {
	        self::$instance = new self;
	      }
	      return self::$instance;
	    }

		public function __construct(){

			/**
			 *  1. Comment Avatar Class Filter
			 *  ------------------------------
			 */
			add_filter( 'get_avatar', [ $this, 'comment_avatar' ] );

			/**
			 *  2. Comment Replay Link Filter Class
			 *  -----------------------------------
			 */
			add_filter('comment_reply_link', [ $this, 'comment_reply_btn_class' ] );
		}

		/**
		 *  1. Comment Class Filter
		 *  -----------------------
		 */
		public static function comment_avatar( $class ){

			$class = str_replace("class='avatar", "class='img-fluid avatar", $class) ;

			return $class;
		}

		/**
		 *  2. Comment Replay Link Filter Class
		 *  -----------------------------------
		 */
		public static function comment_reply_btn_class( $class ){

		    $class = str_replace( "class='comment-reply-link", "class='comment-reply-link reply-line", $class );

		    return $class;
		}

		/**
		 *  3. SDWeddingDirectory - Thread Comment
		 *  ------------------------------
		 */
	    public static function sdweddingdirectory_comment( $comment, $args, $depth ) {
			 
			$GLOBALS['comment'] = $comment;

			switch ( $comment->comment_type ) 	:

				/**
				 *  Is Pingback ?
				 *  -------------
				 */
				case 'pingback':

					?>
					<li class="trackback"><?php esc_html_e( 'Trackback:', 'sdweddingdirectory' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'sdweddingdirectory' ), '<span class="edit-link">', '</span>' ); ?>
					<?php

				break;

				/**
				 *  Is Trackback ?
				 *  --------------
				 */
				case 'trackback':

					?>
					<li class="pingback"><?php esc_html_e( 'Pingback:', 'sdweddingdirectory' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'sdweddingdirectory' ), '<span class="edit-link">', '</span>' ); ?>
					<?php

				break;

				/**
				 *  Default
				 *  -------
				 */
			 	default :

			 		?>
					<li id="li-comment-<?php comment_ID(); ?>" <?php comment_class( 'comment-list comment_item' ); ?>>

						<div id="comment-<?php comment_ID(); ?>" class="comment_body comment media">

							<div class="comment_author_avatar">
								<?php 

									echo 	get_avatar( 

										 		/**
										 		 *  1. ID / Email
										 		 *  -------------
										 		 */
										 		$comment,

										 		/**
										 		 *  2. Image size
										 		 *  -------------
										 		 */
										 		absint(	'120' ), 

										 		/**
										 		 *  3. Default Null
										 		 *  ---------------
										 		 *  @link - https://developer.wordpress.org/reference/functions/get_avatar/#parameters
										 		 *  ----------------------------------------------------------------------------------
										 		 */
										 		null, 

										 		/**
										 		 *  3. Default Null ( Image alt text )
										 		 *  ----------------------------------
										 		 *  @link - https://developer.wordpress.org/reference/functions/get_avatar/#parameters
										 		 *  ----------------------------------------------------------------------------------
										 		 */
										 		null,

										 		/**
										 		 *  Add Avatar Attributes such as Class / Height / extra attr
										 		 *  ---------------------------------------------------------
										 		 */
										 		array( 

										 			'class' 	=> 		sanitize_html_class( 'thumb' )
										 		)
										 	);
								?>
							</div>


								<div class="media-body">

									<div class="d-md-flex justify-content-between mb-3">

										<div class="">
										<?php 

											printf(	'<h4 class="mb-0 mt-0"> <a href="%2$s"> %1$s </a> </h4>',

												/**
												 *  1. Comment publish user name
												 *  ----------------------------
												 */
												ucwords( get_comment_author() ),

												/**
												 *  2. Comment User Profile Link
												 *  ----------------------------
												 */
												esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) )
											);

											printf(' <small> %1$s %2$s </small>',

												/**
												 *  Publish comment time showing
												 *  ----------------------------
												 */
												human_time_diff(

													get_comment_time(  'U'	),

													current_time(	'timestamp' )
												),

												/**
												 *  2. Translation Ready String
												 *  ---------------------------
												 */
												esc_attr__(' ago', 'sdweddingdirectory' )
											);

										?>
										</div>
										<?php

											comment_reply_link(

												array_merge(

													$args,

													array( 

												        'before'        => 	'<div class="media-reply">',

												        'after'         => 	'</div>',

														'reply_text' 	=> 	esc_attr__( 'Reply', 'sdweddingdirectory' ),

														'depth' 		=>	absint( 	$depth 		), 

														'max_depth' 	=> 	absint( 	$args['max_depth']		)
													)
												)
											);

											/**
											 *  Check Comment is Approved ?
											 *  ---------------------------
											 */
											if( $comment->comment_approved == absint( '0' ) ){

												printf( '<p><em>%1$s</em></p>',

													/**
													 *  1. Translation Ready String
													 *  ---------------------------
													 */
													esc_attr__('Your comment is awaiting moderation.', 'sdweddingdirectory' )
												);
											}

										?>

									</div>

									<div class="sdweddingdirectory_comment_content"><?php comment_text(); ?></div>

						  		</div>

						 	</div>

						 	<?php

				break;

			endswitch;
	   	}
	}

	/**
	 *  SDWeddingDirectory - Comment Helper Object
	 *  ----------------------------------
	 */
	SDWeddingDirectory_Comment_Helper::get_instance();
}