<?php
/*
 *   SDWeddingDirectory - No Result Found Template
 *   -------------------------------------
 */ 

printf( '<h2 class="nothing-found">%1$s</h2>',

	/**
	 *  1. Translation Ready String
	 *  ---------------------------
	 */
	esc_attr__( 'Nothing Found', 'sdweddingdirectory' )
);

/**
 * 	----------------------------------------
 *  Is Home Page + Not Found Post + Is Admin
 *  ----------------------------------------
 */
if ( is_home() && current_user_can( 'publish_posts' ) ) {

	printf(	   '<p class="nothing-found">

					%1$s  <a href="%2$s"> %3$s </a>

				</p>',

				/**
				 *  1. Translation Ready String
				 *  ---------------------------
				 */
				esc_attr__( 'Ready to publish your first post ?', 'sdweddingdirectory' ),

				/**
				 *  2. Add New Post ( Backend )
				 *  ---------------------------
				 */
				esc_url( admin_url( 'post-new.php' ) ),

				/**
				 *  3. Translation Ready String
				 *  ---------------------------
				 */
				esc_attr__( 'Get started here.', 'sdweddingdirectory' )
	);

}elseif ( is_search() ){

	/**
	 *  Post Not Found !
	 *  ----------------
	 */
	printf( '<p class="nothing-found"> %1$s </p>',

		/**
		 *  1. Translation Ready String
		 *  ---------------------------
		 */
		esc_attr__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'sdweddingdirectory' )
	);

	/**
	 * 
	 *  SDWeddingDirectory -  Load Search Form
	 *  ------------------------------
	 *  
	 */
	get_search_form();

}else{

	/**
	 *  Post Not Found !
	 *  ----------------
	 */
	printf( '<p class="nothing-found"> %1$s </p>',

		/**
		 *  1. Translation Ready String
		 *  ---------------------------
		 */
		esc_attr__( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'sdweddingdirectory' )
	);

	/**
	 * 
	 *  SDWeddingDirectory -  Load Search Form
	 *  ------------------------------
	 *  
	 */
	get_search_form();
}