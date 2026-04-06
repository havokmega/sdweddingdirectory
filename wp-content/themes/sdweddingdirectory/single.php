<?php
global $wp_query, $post, $page;

$is_single_post_view = is_single() && get_post_type() === 'post';

if ( $is_single_post_view && class_exists( 'SDWeddingDirectory_Page_Header_Banner' ) ) {
	remove_action(
		'sdweddingdirectory/page-header-banner',
		[ SDWeddingDirectory_Page_Header_Banner::get_instance(), 'sdweddingdirectory_default_page_header' ],
		absint( '10' )
	);
}

/**
 *  SDWeddingDirectory - Container Start
 *  ------------------------------------
 */
do_action( 'sdweddingdirectory_main_container' );

/**
 *  Have Post ?
 *  -----------
 */
if ( have_posts() ) {

	/**
	 *  Load Post One By One
	 *  --------------------
	 */
	while ( have_posts() ) {
		the_post();

		$post_id = absint( get_the_ID() );

		$post_categories   = get_the_category();
		$primary_category  = '';
		$primary_cat_url   = '';
		$inspiration_url   = '';
		$full_content_html = apply_filters( 'the_content', get_the_content() );
		$intro_html        = '';
		$remaining_html    = $full_content_html;
		$layout_id         = 'sd-single-post-layout-' . $post_id;

		if ( ! empty( $post_categories ) && ! is_wp_error( $post_categories ) ) {
			$primary_category = $post_categories[0]->name;
			$term_link        = get_term_link( $post_categories[0] );
			if ( ! is_wp_error( $term_link ) ) {
				$primary_cat_url = $term_link;
			}
		}

		$posts_page_id = absint( get_option( 'page_for_posts' ) );
		if ( ! empty( $posts_page_id ) ) {
			$inspiration_url = get_permalink( $posts_page_id );
		}
		if ( empty( $inspiration_url ) ) {
			$inspiration_url = home_url( '/wedding-inspiration/' );
		}

		$full_content_html = str_replace( ']]>', ']]&gt;', $full_content_html );

		if ( preg_match( '/(<p\b[^>]*>.*?<\/p>)/is', $full_content_html, $first_paragraph ) ) {
			$intro_html     = $first_paragraph[1];
			$remaining_html = preg_replace( '/(<p\b[^>]*>.*?<\/p>)/is', '', $full_content_html, 1 );
		} elseif ( ! empty( $full_content_html ) ) {
			$intro_html = sprintf( '<p>%1$s</p>', esc_html( wp_trim_words( wp_strip_all_tags( $full_content_html ), 45, '' ) ) );
		}
		?>
		<section class="sd-single-post-layout" id="<?php echo esc_attr( $layout_id ); ?>">
				<style>
					body.single-post .main-content.content.wide-tb-90 {
						padding-top: 6px;
					}

					body.single-post .main-content.content.wide-tb-90 > .container > .row > #primary {
						flex: 0 0 100%;
						max-width: 100%;
					}

				body.single-post .main-content.content.wide-tb-90 > .container > .row > #secondary {
					display: none;
				}

				.sd-single-post-layout {
					width: 100%;
				}

					.sd-single-post-layout__topbar {
						display: flex;
						align-items: center;
						justify-content: space-between;
						gap: 18px;
						background: #fff;
						padding: 4px 0 10px;
						margin: 0 0 8px;
					}

				/* Breadcrumbs now handled by sdwd-foundation.css (.sdwd-breadcrumb) */

				.sd-single-post-layout__search {
					position: relative;
					display: flex;
					align-items: center;
					width: 360px;
					max-width: 100%;
					height: 44px;
					border: 1px solid #d9d9d9;
					border-radius: 10px;
					background: #fff;
					overflow: hidden;
				}

				.sd-single-post-layout__search-icon {
					position: absolute;
					left: 14px;
					top: 50%;
					transform: translateY(-50%);
					font-size: 0.92rem;
					color: #999;
					pointer-events: none;
				}

				.sd-single-post-layout__search-input {
					flex: 1 1 auto;
					min-width: 0;
					height: 100%;
					border: 0;
					background: transparent;
					padding: 0 12px 0 38px;
					font-size: 0.92rem;
					color: #2d2d2d;
				}

				.sd-single-post-layout__search-input::placeholder {
					color: #999;
					opacity: 1;
				}

				.sd-single-post-layout__search-submit {
					width: 44px;
					min-width: 44px;
					height: 100%;
					border: 0;
					border-left: 1px solid #ececec;
					background: #fff;
					color: #8a8a8a;
					font-size: 0.9rem;
					line-height: 1;
				}

				.sd-single-post-layout__search-submit:hover,
				.sd-single-post-layout__search-submit:focus {
					color: var(--sdweddingdirectory-color-cyan, #00aeaf);
				}

				.sd-single-post-layout__row--intro {
					display: grid;
					grid-template-columns: repeat(2, minmax(0, 1fr));
					gap: 24px;
					align-items: stretch;
				}

				.sd-single-post-layout__media {
					min-height: 320px;
					height: 100%;
					background: #f4f4f4;
					border-radius: 8px;
					overflow: hidden;
				}

				.sd-single-post-layout__media img {
					display: block;
					width: 100%;
					height: 100%;
					object-fit: cover;
				}

				.sd-single-post-layout__intro {
					min-width: 0;
					display: flex;
					flex-direction: column;
					overflow: hidden;
				}

				.sd-single-post-layout__meta {
					margin-bottom: 12px;
				}

				.sd-single-post-layout__category {
					margin: 0 0 10px;
					font-size: 0.74rem;
					font-weight: 700;
					text-transform: uppercase;
					letter-spacing: 0.04em;
					color: #6f6f6f;
				}

				.sd-single-post-layout__category a {
					color: #6f6f6f;
					text-decoration: none;
				}

				.sd-single-post-layout__category a:hover {
					color: var(--sdweddingdirectory-color-cyan, #00aeaf);
				}

				.sd-single-post-layout__title {
					margin: 0 0 10px;
					font-size: 2rem;
					line-height: 1.2;
					font-weight: 700;
					color: #2d2d2d;
				}

				.sd-single-post-layout__date {
					margin: 0;
					font-size: 0.86rem;
					color: #8a8a8a;
				}

				.sd-single-post-layout__intro-content {
					min-height: 0;
					overflow: hidden;
					color: #303030;
				}

				.sd-single-post-layout__intro-content > *:last-child {
					margin-bottom: 0;
				}

				.sd-single-post-layout__row--content {
					margin-top: 22px;
				}

				.sd-single-post-layout__content {
					width: 100%;
				}

				.sd-single-post-layout__content > *:last-child {
					margin-bottom: 0;
				}

				.sd-single-post-layout__source {
					display: none;
				}

				@media (max-width: 991.98px) {
					.sd-single-post-layout__title {
						font-size: 1.8rem;
					}
				}

				@media (max-width: 767.98px) {
					.sd-single-post-layout__row--intro {
						grid-template-columns: 1fr;
						gap: 16px;
					}

					.sd-single-post-layout__topbar {
						flex-direction: column;
						align-items: flex-start;
						gap: 10px;
					}

					.sd-single-post-layout__search {
						width: 100%;
					}

					.sd-single-post-layout__media {
						min-height: 240px;
					}

					.sd-single-post-layout__title {
						font-size: 1.6rem;
					}

					.sd-single-post-layout__intro-content {
						display: none;
					}
				}
			</style>

			<article class="sd-single-post-layout__article">
				<div class="sd-single-post-layout__topbar">
					<nav class="sdwd-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'sdweddingdirectory' ); ?>">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Wedding', 'sdweddingdirectory' ); ?></a>
						<span class="sdwd-breadcrumb-sep" aria-hidden="true">/</span>
						<a href="<?php echo esc_url( $inspiration_url ); ?>"><?php esc_html_e( 'Wedding Inspiration', 'sdweddingdirectory' ); ?></a>
						<span class="sdwd-breadcrumb-sep" aria-hidden="true">/</span>
						<span><?php the_title(); ?></span>
					</nav>

					<form class="sd-single-post-layout__search" method="get" action="<?php echo esc_url( $inspiration_url ); ?>" role="search">
						<span class="sd-single-post-layout__search-icon" aria-hidden="true"><i class="fa fa-search"></i></span>
						<label class="screen-reader-text" for="sd-single-post-search-<?php echo esc_attr( $post_id ); ?>"><?php esc_html_e( 'Search SDWeddingDirectory Articles', 'sdweddingdirectory' ); ?></label>
						<input
							id="sd-single-post-search-<?php echo esc_attr( $post_id ); ?>"
							class="sd-single-post-layout__search-input"
							type="search"
							name="s"
							value="<?php echo esc_attr( get_search_query() ); ?>"
							placeholder="<?php esc_attr_e( 'Search SDWeddingDirectory Articles', 'sdweddingdirectory' ); ?>"
						/>
						<input type="hidden" name="post_type" value="post" />
						<button class="sd-single-post-layout__search-submit" type="submit" aria-label="<?php esc_attr_e( 'Search', 'sdweddingdirectory' ); ?>">
							<i class="fa fa-search" aria-hidden="true"></i>
						</button>
					</form>
				</div>

				<div class="sd-single-post-layout__row sd-single-post-layout__row--intro">
					<div class="sd-single-post-layout__media" data-featured-media>
						<?php
						if ( has_post_thumbnail() ) {
							echo get_the_post_thumbnail(
								$post_id,
								'large',
								[
									'loading' => 'eager',
									'alt'     => the_title_attribute( [ 'echo' => false ] ),
								]
							);
						}
						?>
					</div>

					<div class="sd-single-post-layout__intro" data-intro-wrap>
						<div class="sd-single-post-layout__meta" data-intro-meta>
							<?php if ( ! empty( $primary_category ) ) : ?>
								<p class="sd-single-post-layout__category">
									<?php if ( ! empty( $primary_cat_url ) ) : ?>
										<a href="<?php echo esc_url( $primary_cat_url ); ?>"><?php echo esc_html( $primary_category ); ?></a>
									<?php else : ?>
										<span><?php echo esc_html( $primary_category ); ?></span>
									<?php endif; ?>
								</p>
							<?php endif; ?>
							<h1 class="sd-single-post-layout__title"><?php the_title(); ?></h1>
							<p class="sd-single-post-layout__date"><?php echo esc_html( get_the_date() ); ?></p>
						</div>

						<div class="sd-single-post-layout__intro-content" data-intro-content>
							<?php echo wp_kses_post( $intro_html ); ?>
						</div>
					</div>
				</div>

				<div class="sd-single-post-layout__row sd-single-post-layout__row--content">
					<div class="sd-single-post-layout__content" data-rest-content>
						<?php echo wp_kses_post( $remaining_html ); ?>
					</div>
				</div>

				<div class="sd-single-post-layout__source" data-source-content>
					<?php echo wp_kses_post( $full_content_html ); ?>
				</div>
			</article>

			<script>
				(function() {
					var root = document.getElementById(<?php echo wp_json_encode( $layout_id ); ?>);
					if (!root) {
						return;
					}

					var media = root.querySelector('[data-featured-media]');
					var introWrap = root.querySelector('[data-intro-wrap]');
					var introMeta = root.querySelector('[data-intro-meta]');
					var introContent = root.querySelector('[data-intro-content]');
					var restContent = root.querySelector('[data-rest-content]');
					var sourceContent = root.querySelector('[data-source-content]');
					var mobileQuery = window.matchMedia('(max-width: 767.98px)');

					if (!introWrap || !introContent || !restContent || !sourceContent) {
						return;
					}

					var getNodes = function(html) {
						var temp = document.createElement('div');
						temp.innerHTML = html;

						return Array.from(temp.childNodes).filter(function(node) {
							return !(node.nodeType === 3 && node.textContent.trim() === '');
						});
					};

					var splitContent = function() {
						var sourceHtml = sourceContent.innerHTML || '';

						if (mobileQuery.matches) {
							introWrap.style.maxHeight = 'none';
							introContent.style.maxHeight = 'none';
							introContent.innerHTML = '';
							restContent.innerHTML = sourceHtml;
							return;
						}

						if (!media) {
							restContent.innerHTML = sourceHtml;
							return;
						}

						var mediaHeight = media.offsetHeight;
						if (!mediaHeight || mediaHeight < 80) {
							restContent.innerHTML = sourceHtml;
							return;
						}

						introWrap.style.maxHeight = mediaHeight + 'px';
						introContent.innerHTML = '';
						restContent.innerHTML = '';

						var available = Math.max(0, mediaHeight - (introMeta ? introMeta.offsetHeight : 0));
						introContent.style.maxHeight = available + 'px';

						var nodes = getNodes(sourceHtml);
						if (!nodes.length || available < 20) {
							restContent.innerHTML = sourceHtml;
							return;
						}

						var overflowIndex = -1;
						for (var i = 0; i < nodes.length; i++) {
							var nodeClone = nodes[i].cloneNode(true);
							introContent.appendChild(nodeClone);

							if (introContent.scrollHeight > available + 1) {
								introContent.removeChild(nodeClone);
								overflowIndex = i;
								break;
							}
						}

						if (overflowIndex === -1) {
							return;
						}

						if (!introContent.hasChildNodes()) {
							introContent.appendChild(nodes[overflowIndex].cloneNode(true));
						}

						for (var j = overflowIndex; j < nodes.length; j++) {
							restContent.appendChild(nodes[j].cloneNode(true));
						}
					};

					var rafId = 0;
					var queueSplit = function() {
						if (rafId) {
							cancelAnimationFrame(rafId);
						}
						rafId = requestAnimationFrame(splitContent);
					};

					var mediaImage = media ? media.querySelector('img') : null;
					if (mediaImage && !mediaImage.complete) {
						mediaImage.addEventListener('load', queueSplit);
					}

					window.addEventListener('resize', queueSplit);

					if (mobileQuery.addEventListener) {
						mobileQuery.addEventListener('change', queueSplit);
					} else if (mobileQuery.addListener) {
						mobileQuery.addListener(queueSplit);
					}

					queueSplit();
				})();
			</script>
		</section>

		<?php
		/**
		 *  Keep existing Previous / Next post section as-is.
		 */
		if ( class_exists( 'SDWeddingDirectory_Blog_Helper' ) && method_exists( 'SDWeddingDirectory_Blog_Helper', 'sdweddingdirectory_single_post_link' ) ) {
			SDWeddingDirectory_Blog_Helper::sdweddingdirectory_single_post_link(
				[
					'post_id' => $post_id,
				]
			);
		} else {
			do_action(
				'sdweddingdirectory/blog/details',
				[
					'post_id' => $post_id,
				]
			);
		}
	}

	/**
	 *  Reset Query
	 *  -----------
	 */
	if ( isset( $wp_query ) ) {
		wp_reset_postdata();
	}
} else {

	/**
	 *  Not Found Post
	 *  --------------
	 */
	do_action( 'sdweddingdirectory_empty_article' );
}

/**
 *  SDWeddingDirectory - Container END
 *  ----------------------------------
 */
do_action( 'sdweddingdirectory_main_container_end' );
?>
