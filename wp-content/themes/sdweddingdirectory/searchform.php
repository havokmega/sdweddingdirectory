<?php
/**
 *  ------------------------
 *  SDWeddingDirectory - Search Form
 *  ------------------------
 */

printf(    '<form method="get" class="sidebar-search" action="%1$s">

				<div class="row">

				    <div class="col-md-12">

				        <div class="input-group">

							<input autocomplete="off" class="form-control" type="text" value="%2$s"  name="s" placeholder="%3$s">

							<button type="submit" class="btn"><i class="fa fa-search"></i></button>

				        </div>

				    </div>

				</div>

			</form>',

			/**
			 *  1. Get Home Page Action
			 *  -----------------------
			 */
			esc_url( 

				home_url( '/' ) 
			),

			/**
			 *  2. Get Search Query
			 *  -------------------
			 */
			esc_attr( 

				trim( 

					get_search_query() 
				) 
			),

			/**
			 *  3. Translation Ready String
			 *  ---------------------------
			 */
			esc_attr__( 'Search', 'sdweddingdirectory' )
);