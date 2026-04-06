<?php
/**
 * Blog list with sticky sidebar.
 * Usage: get_template_part( 'template-parts/blog/blog-list-with-sidebar' );
 */

defined( 'ABSPATH' ) || exit;

$posts_per_page = 10;
$initial_offset = 5;
$paged          = max( 1, absint( get_query_var( 'paged' ) ), absint( get_query_var( 'page' ) ) );
$offset         = $initial_offset + ( ( $paged - 1 ) * $posts_per_page );

$blog_list_query = new WP_Query(
	[
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $posts_per_page,
		'offset'              => $offset,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => false,
	]
);

$total_posts = max( 0, absint( $blog_list_query->found_posts ) - $initial_offset );
$total_pages = ( $posts_per_page > 0 ) ? (int) ceil( $total_posts / $posts_per_page ) : 0;
$instance_id = wp_unique_id( 'blog-list-with-sidebar-' );

$parent_categories = get_categories(
	[
		'taxonomy'   => 'category',
		'hide_empty' => true,
		'parent'     => 0,
		'orderby'    => 'name',
		'order'      => 'ASC',
	]
);

$planning_links  = [];
$menu_locations  = get_nav_menu_locations();
$planning_root   = 0;
$planning_raw    = [];

if ( ! empty( $menu_locations['primary-menu'] ) ) {
	$menu_items = wp_get_nav_menu_items( absint( $menu_locations['primary-menu'] ) );

	if ( is_array( $menu_items ) && ! empty( $menu_items ) ) {
		foreach ( $menu_items as $menu_item ) {
			$classes = is_array( $menu_item->classes ) ? $menu_item->classes : [];

			if (
				absint( $menu_item->menu_item_parent ) === 0 &&
				in_array( 'sd-mega', $classes, true ) &&
				in_array( 'sd-mega-planning', $classes, true )
			) {
				$planning_root = absint( $menu_item->ID );
				break;
			}
		}

		if ( $planning_root > 0 ) {
			foreach ( $menu_items as $menu_item ) {
				if ( absint( $menu_item->menu_item_parent ) !== $planning_root ) {
					continue;
				}

				$classes = is_array( $menu_item->classes ) ? $menu_item->classes : [];
				if ( ! in_array( 'sd-menu-icon', $classes, true ) ) {
					continue;
				}

				$planning_raw[] = [
					'title'      => $menu_item->title,
					'url'        => $menu_item->url,
					'menu_order' => absint( $menu_item->menu_order ),
				];
			}
		}
	}
}

if ( ! empty( $planning_raw ) ) {
	usort(
		$planning_raw,
		static function( $left, $right ) {
			return absint( $left['menu_order'] ) <=> absint( $right['menu_order'] );
		}
	);

	foreach ( array_slice( $planning_raw, 0, 6 ) as $planning_item ) {
		$planning_links[] = [
			'title' => $planning_item['title'],
			'url'   => $planning_item['url'],
		];
	}
}

if ( empty( $planning_links ) ) {
	$planning_links = [
		[ 'title' => esc_html__( 'Wedding Checklist', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-planning/wedding-checklist/' ) ],
		[ 'title' => esc_html__( 'Wedding Budget', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-planning/wedding-budget/' ) ],
		[ 'title' => esc_html__( 'Vendor Manager', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-planning/vendor-manager/' ) ],
		[ 'title' => esc_html__( 'Guest List', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-planning/wedding-guest-list/' ) ],
		[ 'title' => esc_html__( 'Seating Chart', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-planning/wedding-seating-chart/' ) ],
		[ 'title' => esc_html__( 'Wedding Website', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-planning/wedding-website/' ) ],
	];
}
?>

<div class="blog-list-with-sidebar" id="<?php echo esc_attr( $instance_id ); ?>">
	<style>
		.blog-list-with-sidebar {
			width: 100%;
		}

		.blog-list-with-sidebar__layout {
			display: grid;
			grid-template-columns: minmax(0, 2fr) minmax(280px, 1fr);
			gap: 22px;
			align-items: start;
		}

		.blog-list-with-sidebar__posts {
			min-width: 0;
		}

		.blog-list-with-sidebar__posts-grid {
			display: grid;
			grid-template-columns: repeat(2, minmax(0, 1fr));
			gap: 18px;
		}

		.blog-list-with-sidebar__card {
			display: flex;
			flex-direction: column;
			height: 100%;
			background: #fff;
			border: 1px solid #e0e0e0;
			border-radius: 8px;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
			overflow: hidden;
		}

		.blog-list-with-sidebar__media {
			display: block;
			flex: 0 0 60%;
			min-height: 180px;
			background: #f4f4f4;
		}

		.blog-list-with-sidebar__media img {
			display: block;
			width: 100%;
			height: 100%;
			object-fit: cover;
		}

		.blog-list-with-sidebar__body {
			flex: 1 1 40%;
			padding: 12px 14px 14px;
			display: flex;
			flex-direction: column;
		}

		.blog-list-with-sidebar__category {
			margin: 0 0 8px;
			font-size: 0.72rem;
			font-weight: 700;
			letter-spacing: 0.04em;
			text-transform: uppercase;
			color: #6f6f6f;
		}

		.blog-list-with-sidebar__title {
			margin: 0;
			font-size: 1rem;
			line-height: 1.35;
			font-weight: 700;
		}

		.blog-list-with-sidebar__title a {
			color: #252525;
			text-decoration: none;
		}

		.blog-list-with-sidebar__title a:hover,
		.blog-list-with-sidebar__title a:focus {
			color: var(--sdweddingdirectory-color-cyan, #00aeaf);
			text-decoration: none;
		}

		.blog-list-with-sidebar__empty {
			margin: 0;
			font-size: 0.95rem;
			color: #616161;
		}

		.blog-list-with-sidebar__pagination {
			margin-top: 18px;
		}

		.blog-list-with-sidebar__pagination .page-numbers {
			display: inline-block;
			margin: 0 6px 6px 0;
			padding: 7px 12px;
			border: 1px solid #d8d8d8;
			border-radius: 6px;
			color: #2d2d2d;
			text-decoration: none;
			font-size: 0.9rem;
		}

		.blog-list-with-sidebar__pagination .page-numbers.current,
		.blog-list-with-sidebar__pagination .page-numbers:hover {
			border-color: var(--sdweddingdirectory-color-cyan, #00aeaf);
			color: var(--sdweddingdirectory-color-cyan, #00aeaf);
		}

		.blog-list-with-sidebar--infinite-ready .blog-list-with-sidebar__pagination {
			display: none;
		}

		.blog-list-with-sidebar__loading {
			display: none;
			margin-top: 12px;
			font-size: 0.9rem;
			color: #666;
		}

		.blog-list-with-sidebar.is-loading .blog-list-with-sidebar__loading {
			display: block;
		}

		.blog-list-with-sidebar__sentinel {
			height: 1px;
		}

		.blog-list-with-sidebar__sidebar {
			position: sticky;
			top: 20px;
			display: grid;
			gap: 16px;
		}

		.blog-list-with-sidebar__widget {
			background: #f9f9f9;
			border: 1px solid #ececec;
			border-radius: 8px;
			padding: 16px;
		}

		.blog-list-with-sidebar__widget-title {
			margin: 0 0 12px;
			font-size: 1rem;
			font-weight: 700;
			color: #2a2a2a;
		}

		.blog-list-with-sidebar__category-list,
		.blog-list-with-sidebar__planning-list,
		.blog-list-with-sidebar__child-list {
			list-style: none;
			margin: 0;
			padding: 0;
		}

		.blog-list-with-sidebar__category-item,
		.blog-list-with-sidebar__planning-item {
			margin: 0 0 8px;
		}

		.blog-list-with-sidebar__category-item:last-child,
		.blog-list-with-sidebar__planning-item:last-child {
			margin-bottom: 0;
		}

		.blog-list-with-sidebar__category-link,
		.blog-list-with-sidebar__planning-link,
		.blog-list-with-sidebar__child-link {
			font-size: 0.92rem;
			line-height: 1.4;
			color: #2d2d2d;
			text-decoration: none;
		}

		.blog-list-with-sidebar__category-link:hover,
		.blog-list-with-sidebar__planning-link:hover,
		.blog-list-with-sidebar__child-link:hover {
			color: var(--sdweddingdirectory-color-cyan, #00aeaf);
		}

		.blog-list-with-sidebar__category-children {
			margin: 0 0 8px;
		}

		.blog-list-with-sidebar__category-children summary {
			cursor: pointer;
			font-size: 0.92rem;
			font-weight: 600;
			color: #2d2d2d;
		}

		.blog-list-with-sidebar__category-children[open] summary {
			margin-bottom: 8px;
		}

		.blog-list-with-sidebar__category-children > summary::-webkit-details-marker {
			color: #727272;
		}

		.blog-list-with-sidebar__child-list {
			padding-left: 14px;
		}

		.blog-list-with-sidebar__child-item {
			margin: 0 0 6px;
		}

		.blog-list-with-sidebar__child-item:last-child {
			margin-bottom: 0;
		}

		@media (max-width: 991.98px) {
			.blog-list-with-sidebar__layout {
				grid-template-columns: 1fr;
			}

			.blog-list-with-sidebar__sidebar {
				position: static;
				top: auto;
			}
		}

		@media (max-width: 767.98px) {
			.blog-list-with-sidebar__posts-grid {
				grid-template-columns: 1fr;
			}
		}
	</style>

	<div class="blog-list-with-sidebar__layout">
		<div class="blog-list-with-sidebar__posts">
			<?php if ( $blog_list_query->have_posts() ) : ?>
				<div class="blog-list-with-sidebar__posts-grid">
					<?php while ( $blog_list_query->have_posts() ) : ?>
						<?php
						$blog_list_query->the_post();
						$post_categories = get_the_category();
						$category_name   = '';

						if ( ! empty( $post_categories ) && ! is_wp_error( $post_categories ) ) {
							$category_name = $post_categories[0]->name;
						}
						?>
						<article <?php post_class( 'blog-list-with-sidebar__card' ); ?>>
							<a class="blog-list-with-sidebar__media" href="<?php echo esc_url( get_permalink() ); ?>">
								<?php
								if ( has_post_thumbnail() ) {
									echo get_the_post_thumbnail(
										get_the_ID(),
										'large',
										[
											'loading' => 'lazy',
											'alt'     => the_title_attribute( [ 'echo' => false ] ),
										]
									);
								}
								?>
							</a>
							<div class="blog-list-with-sidebar__body">
								<?php if ( $category_name !== '' ) : ?>
									<p class="blog-list-with-sidebar__category"><?php echo esc_html( $category_name ); ?></p>
								<?php endif; ?>
								<h3 class="blog-list-with-sidebar__title">
									<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
								</h3>
							</div>
						</article>
					<?php endwhile; ?>
				</div>

				<?php if ( $total_pages > 1 ) : ?>
					<nav class="blog-list-with-sidebar__pagination" aria-label="<?php esc_attr_e( 'Pagination', 'sdweddingdirectory' ); ?>">
						<?php
						echo wp_kses_post(
							paginate_links(
									[
										'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
										'format'    => '',
										'current'   => $paged,
										'total'     => $total_pages,
									'prev_text' => esc_html__( 'Previous', 'sdweddingdirectory' ),
									'next_text' => esc_html__( 'Next', 'sdweddingdirectory' ),
									'type'      => 'plain',
								]
							)
						);
						?>
					</nav>
				<?php endif; ?>

				<div class="blog-list-with-sidebar__loading" aria-live="polite" aria-atomic="true"></div>
				<div class="blog-list-with-sidebar__sentinel" aria-hidden="true"></div>
			<?php else : ?>
				<p class="blog-list-with-sidebar__empty"><?php esc_html_e( 'No posts found.', 'sdweddingdirectory' ); ?></p>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>
		</div>

		<aside class="blog-list-with-sidebar__sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'sdweddingdirectory' ); ?>">
			<section class="blog-list-with-sidebar__widget">
				<h3 class="blog-list-with-sidebar__widget-title"><?php esc_html_e( 'Category Browser', 'sdweddingdirectory' ); ?></h3>
				<ul class="blog-list-with-sidebar__category-list">
					<?php foreach ( $parent_categories as $parent_category ) : ?>
						<?php
						$child_categories = get_categories(
							[
								'taxonomy'   => 'category',
								'hide_empty' => true,
								'parent'     => absint( $parent_category->term_id ),
								'orderby'    => 'name',
								'order'      => 'ASC',
							]
						);
						?>
						<li class="blog-list-with-sidebar__category-item">
							<?php if ( ! empty( $child_categories ) ) : ?>
								<details class="blog-list-with-sidebar__category-children">
									<summary><?php echo esc_html( $parent_category->name ); ?></summary>
									<ul class="blog-list-with-sidebar__child-list">
										<li class="blog-list-with-sidebar__child-item">
											<a class="blog-list-with-sidebar__child-link" href="<?php echo esc_url( get_term_link( $parent_category ) ); ?>">
												<?php echo esc_html( $parent_category->name ); ?>
											</a>
										</li>
										<?php foreach ( $child_categories as $child_category ) : ?>
											<li class="blog-list-with-sidebar__child-item">
												<a class="blog-list-with-sidebar__child-link" href="<?php echo esc_url( get_term_link( $child_category ) ); ?>">
													<?php echo esc_html( $child_category->name ); ?>
												</a>
											</li>
										<?php endforeach; ?>
									</ul>
								</details>
							<?php else : ?>
								<a class="blog-list-with-sidebar__category-link" href="<?php echo esc_url( get_term_link( $parent_category ) ); ?>">
									<?php echo esc_html( $parent_category->name ); ?>
								</a>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>

			<section class="blog-list-with-sidebar__widget">
				<h3 class="blog-list-with-sidebar__widget-title"><?php esc_html_e( 'Wedding Planning Links', 'sdweddingdirectory' ); ?></h3>
				<ul class="blog-list-with-sidebar__planning-list">
					<?php foreach ( $planning_links as $planning_link ) : ?>
						<li class="blog-list-with-sidebar__planning-item">
							<a class="blog-list-with-sidebar__planning-link" href="<?php echo esc_url( $planning_link['url'] ); ?>">
								<?php echo esc_html( $planning_link['title'] ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		</aside>
	</div>

	<script>
		(function() {
			var root = document.getElementById(<?php echo wp_json_encode( $instance_id ); ?>);

			if (!root || !('IntersectionObserver' in window) || !('DOMParser' in window)) {
				return;
			}

			var postsGrid = root.querySelector('.blog-list-with-sidebar__posts-grid');
			var pagination = root.querySelector('.blog-list-with-sidebar__pagination');
			var sentinel = root.querySelector('.blog-list-with-sidebar__sentinel');
			var loadingMessage = root.querySelector('.blog-list-with-sidebar__loading');

			if (!postsGrid || !pagination || !sentinel) {
				return;
			}

			var getNextUrl = function(container) {
				if (!container) {
					return '';
				}

				var nextLink = container.querySelector('a.next.page-numbers');
				return nextLink && nextLink.href ? nextLink.href : '';
			};

			var nextUrl = getNextUrl(pagination);

			if (!nextUrl) {
				return;
			}

			var loadingText = <?php echo wp_json_encode( esc_html__( 'Loading more articles...', 'sdweddingdirectory' ) ); ?>;
			var isLoading = false;

			root.classList.add('blog-list-with-sidebar--infinite-ready');

			var setLoading = function(active) {
				isLoading = active;
				root.classList.toggle('is-loading', active);

				if (loadingMessage) {
					loadingMessage.textContent = active ? loadingText : '';
				}
			};

			var observer = new IntersectionObserver(function(entries) {
				for (var i = 0; i < entries.length; i++) {
					if (entries[i].isIntersecting) {
						loadNextPage();
						break;
					}
				}
			}, {
				rootMargin: '260px 0px'
			});

			var loadNextPage = function() {
				if (isLoading || !nextUrl) {
					if (!nextUrl) {
						observer.disconnect();
					}
					return;
				}

				setLoading(true);

				fetch(nextUrl, {
					credentials: 'same-origin'
				})
					.then(function(response) {
						if (!response.ok) {
							throw new Error('Failed to fetch next page.');
						}

						return response.text();
					})
					.then(function(markup) {
						var parser = new DOMParser();
						var doc = parser.parseFromString(markup, 'text/html');
						var incomingRoot = doc.querySelector('.blog-list-with-sidebar');
						var incomingGrid = incomingRoot ? incomingRoot.querySelector('.blog-list-with-sidebar__posts-grid') : null;

						if (!incomingGrid) {
							nextUrl = '';
							observer.disconnect();
							return;
						}

						var incomingCards = incomingGrid.querySelectorAll('.blog-list-with-sidebar__card');

						for (var i = 0; i < incomingCards.length; i++) {
							postsGrid.appendChild(incomingCards[i].cloneNode(true));
						}

						var incomingPagination = incomingRoot.querySelector('.blog-list-with-sidebar__pagination');

						if (incomingPagination) {
							pagination.innerHTML = incomingPagination.innerHTML;
							nextUrl = getNextUrl(pagination);
						} else {
							pagination.innerHTML = '';
							nextUrl = '';
						}

						if (!nextUrl) {
							observer.disconnect();
						}
					})
					.catch(function() {
						observer.disconnect();
						root.classList.remove('blog-list-with-sidebar--infinite-ready');
					})
					.finally(function() {
						setLoading(false);
					});
			};

			observer.observe(sentinel);
		})();
	</script>
</div>
