<?php
/**
 * 	-------------------------------------
 *  SDWeddingDirectory - Author Bio Page Template
 *  -------------------------------------
 */
?>
<div class="sdweddingdirectory-section-author-bio author-wrap">

	<div class="thumb">
	<?php

		/**
		 * 	 ------------------------------
		 *  SDWeddingDirectory - Load Aurhor Avatar
		 *  ----------------------------------------------------------------
		 *  @link https://codex.wordpress.org/Function_Reference/get_avatar
		 *  ----------------------------------------------------------------
		 */

        echo 	get_avatar( 

        			/**
        			 *  1. Load Email
        			 *  -------------
        			 */
            		sanitize_email( get_the_author_meta( 'user_email' ) ),

            		/**
            		 *  2. Size
            		 *  -------
            		 */
                    absint( apply_filters( 'author_bio_avatar_size', absint( '300' ) ) ),

                    /**
                     *  3. NULL
                     *  -------
                     */
                    null, null,

                    /**
                     *  4. Args
                     *  -------
                     */
                    array( 'class' => sanitize_html_class( 'img-fluid' ) )
                );
	?>
	</div>

	<div class="content">
	<?php

		/**
		 *  Load Author Information
		 *  -----------------------
		 */
		printf(    '<h3 class="author-title">

						<a class="title" href="%1$s"> %2$s </a>

						<small> %3$s </small>

					</h3>

					<p> %4$s </p>

					<a href="%1$s" class="btn btn-default btn-rounded btn-md"> %5$s </a>',

					/**
					 *  1. Author Page Link 
					 *  -------------------
					 */
					esc_url( 

						/**
						 *  Get Author Link
						 *  ---------------
						 */
						get_author_posts_url( 

							/**
							 *  Get Author ID
							 *  -------------
							 */
							absint( get_the_author_meta( 'ID' ) )
						) 
					),

					/**
					 *  2. Get Author Name
					 *  ------------------
					 */
					ucwords( 

						esc_attr( 

							/**
							 *  Get Author Name
							 *  ---------------
							 */
							get_the_author()
						) 
					),

					/**
					 *  3. Translation Ready String
					 *  ---------------------------
					 */
					esc_attr__( '(AUTHOR)', 'sdweddingdirectory' ),

					/**
					 *  4. Author Description
					 *  ---------------------
					 */
					esc_attr( get_the_author_meta( 'description' ) ),

					/**
					 *  5. Translation Ready String
					 *  ---------------------------
					 */
					esc_attr__( 'View All Post', 'sdweddingdirectory' )
		);
	?>
	</div>

</div>