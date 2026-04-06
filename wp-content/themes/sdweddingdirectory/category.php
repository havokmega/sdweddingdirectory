<?php
/**
 * SDWeddingDirectory - Category Archive Template
 * ----------------------------------------------
 */
global $wp_query;

$paged = get_query_var( 'paged' )

	? absint( get_query_var( 'paged' ) )

	: absint( '1' );

if ( empty( $paged ) ) {
	$paged = absint( '1' );
}

$current_category = get_queried_object();
$current_cat_id   = 0;
$parent_cat_id    = 0;

if ( $current_category instanceof WP_Term && $current_category->taxonomy === 'category' ) {
	$current_cat_id = absint( $current_category->term_id );
	$parent_cat_id  = absint( $current_category->parent );
}

$category_ids = [];

if ( ! empty( $current_cat_id ) ) {
	if ( empty( $parent_cat_id ) ) {
		$child_category_ids = get_term_children( $current_cat_id, 'category' );

		if ( is_wp_error( $child_category_ids ) ) {
			$child_category_ids = [];
		}

		$category_ids = array_merge( [ $current_cat_id ], array_map( 'absint', (array) $child_category_ids ) );
	} else {
		$category_ids = [ $current_cat_id ];
	}
}

$category_ids = array_values( array_unique( array_map( 'absint', (array) $category_ids ) ) );

$category_query_args = [
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => absint( get_option( 'posts_per_page', 10 ) ),
	'paged'               => absint( $paged ),
	'ignore_sticky_posts' => true,
];

if ( ! empty( $category_ids ) ) {
	$category_query_args['category__in'] = $category_ids;
}

$category_query = new WP_Query( $category_query_args );

$archive_title       = single_cat_title( '', false );
$archive_description = '';

if ( ! empty( $current_cat_id ) ) {
	$archive_description = term_description( $current_cat_id, 'category' );
}

$parent_categories = get_categories(
	[
		'taxonomy'   => 'category',
		'hide_empty' => true,
		'parent'     => 0,
		'orderby'    => 'name',
		'order'      => 'ASC',
	]
);

$active_ancestor_ids = [];

if ( ! empty( $current_cat_id ) ) {
	$active_ancestor_ids = array_map( 'absint', (array) get_ancestors( $current_cat_id, 'category' ) );
}

$planning_links = [];
$menu_locations = get_nav_menu_locations();
$planning_root  = 0;
$planning_raw   = [];

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
		[ 'title' => esc_html__( 'Checklist', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-planning/wedding-checklist/' ) ],
		[ 'title' => esc_html__( 'Seating Chart', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-planning/wedding-seating-chart/' ) ],
		[ 'title' => esc_html__( 'Vendor Manager', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-planning/vendor-manager/' ) ],
		[ 'title' => esc_html__( 'Guest Management', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-planning/wedding-guest-list/' ) ],
		[ 'title' => esc_html__( 'Budget Calculator', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-planning/wedding-budget/' ) ],
		[ 'title' => esc_html__( 'Wedding Website', 'sdweddingdirectory' ), 'url' => home_url( '/wedding-planning/wedding-website/' ) ],
	];
}

do_action( 'sdweddingdirectory_main_container' );
?>

<section class="sd-category-archive">
	<style>
		body.category .main-content.content.wide-tb-90 > .container > .row > #primary {
			flex: 0 0 100%;
			max-width: 100%;
		}

		body.category .main-content.content.wide-tb-90 > .container > .row > #secondary {
			display: none;
		}

		.sd-category-archive {
			width: 100%;
		}

		.sd-category-archive__header {
			margin: 0 0 24px;
		}

		.sd-category-archive__title {
			margin: 0;
			font-size: 2rem;
			line-height: 1.2;
			font-weight: 700;
			color: #2d2d2d;
		}

		.sd-category-archive__description {
			margin: 12px 0 0;
			font-size: 0.98rem;
			line-height: 1.65;
			color: #5f6d7b;
		}

		.sd-category-archive__layout {
			display: grid;
			grid-template-columns: minmax(0, 2fr) minmax(280px, 1fr);
			gap: 22px;
			align-items: start;
		}

		.sd-category-archive__posts {
			min-width: 0;
		}

		.sd-category-archive__grid {
			display: grid;
			grid-template-columns: repeat(2, minmax(0, 1fr));
			gap: 18px;
		}

		.sd-category-archive__card {
			display: flex;
			flex-direction: column;
			height: 100%;
			background: #fff;
			border: 1px solid #e0e0e0;
			border-radius: 8px;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
			overflow: hidden;
		}

		.sd-category-archive__media {
			display: block;
			flex: 0 0 60%;
			min-height: 180px;
			background: #f4f4f4;
		}

		.sd-category-archive__media img {
			display: block;
			width: 100%;
			height: 100%;
			object-fit: cover;
		}

		.sd-category-archive__body {
			flex: 1 1 40%;
			padding: 12px 14px 14px;
			display: flex;
			flex-direction: column;
		}

		.sd-category-archive__category {
			margin: 0 0 8px;
			font-size: 0.72rem;
			font-weight: 700;
			letter-spacing: 0.04em;
			text-transform: uppercase;
			color: #6f6f6f;
		}

		.sd-category-archive__title-link {
			margin: 0;
			font-size: 1rem;
			line-height: 1.35;
			font-weight: 700;
		}

		.sd-category-archive__title-link a {
			color: #252525;
			text-decoration: none;
		}

		.sd-category-archive__title-link a:hover,
		.sd-category-archive__title-link a:focus {
			color: var(--sdweddingdirectory-color-cyan, #00aeaf);
			text-decoration: none;
		}

		.sd-category-archive__empty {
			margin: 0;
			font-size: 0.95rem;
			color: #616161;
		}

		.sd-category-archive__pagination {
			margin-top: 20px;
		}

		.sd-category-archive__sidebar {
			position: sticky;
			top: 20px;
			display: grid;
			gap: 16px;
		}

		.sd-category-archive__widget {
			background: #f9f9f9;
			border: 1px solid #ececec;
			border-radius: 8px;
			padding: 16px;
		}

		.sd-category-archive__widget-title {
			margin: 0 0 12px;
			font-size: 1rem;
			font-weight: 700;
			color: #2a2a2a;
		}

		.sd-category-archive__category-list,
		.sd-category-archive__child-list,
		.sd-category-archive__planning-list {
			list-style: none;
			margin: 0;
			padding: 0;
		}

		.sd-category-archive__category-item,
		.sd-category-archive__planning-item,
		.sd-category-archive__child-item {
			margin: 0 0 8px;
		}

		.sd-category-archive__category-item:last-child,
		.sd-category-archive__planning-item:last-child,
		.sd-category-archive__child-item:last-child {
			margin-bottom: 0;
		}

		.sd-category-archive__category-link,
		.sd-category-archive__child-link,
		.sd-category-archive__planning-link {
			display: inline-block;
			font-size: 0.92rem;
			line-height: 1.4;
			color: #2d2d2d;
			text-decoration: none;
		}

		.sd-category-archive__category-link:hover,
		.sd-category-archive__child-link:hover,
		.sd-category-archive__planning-link:hover {
			color: var(--sdweddingdirectory-color-cyan, #00aeaf);
		}

		.sd-category-archive__category-link.is-active,
		.sd-category-archive__child-link.is-active {
			color: var(--sdweddingdirectory-color-cyan, #00aeaf);
			font-weight: 700;
		}

		.sd-category-archive__category-children {
			margin: 0 0 8px;
		}

		.sd-category-archive__category-children summary {
			cursor: pointer;
			font-size: 0.92rem;
			font-weight: 600;
			color: #2d2d2d;
		}

		.sd-category-archive__category-children > summary::-webkit-details-marker {
			color: #727272;
		}

		.sd-category-archive__category-children[open] summary {
			margin-bottom: 8px;
		}

		.sd-category-archive__child-list {
			padding-left: 14px;
		}

		@media (max-width: 991.98px) {
			.sd-category-archive__layout {
				grid-template-columns: 1fr;
			}

			.sd-category-archive__sidebar {
				position: static;
				top: auto;
			}
		}

		@media (max-width: 767.98px) {
			.sd-category-archive__grid {
				grid-template-columns: 1fr;
			}

			.sd-category-archive__title {
				font-size: 1.7rem;
			}
		}
	</style>

	<header class="sd-category-archive__header">
		<h1 class="sd-category-archive__title"><?php echo esc_html( $archive_title ); ?></h1>
		<?php if ( ! empty( $archive_description ) ) : ?>
			<div class="sd-category-archive__description"><?php echo wp_kses_post( wpautop( $archive_description ) ); ?></div>
		<?php endif; ?>
	</header>

	<div class="sd-category-archive__layout">
		<div class="sd-category-archive__posts">
			<?php if ( $category_query->have_posts() ) : ?>
				<div class="sd-category-archive__grid">
					<?php while ( $category_query->have_posts() ) : ?>
						<?php
						$category_query->the_post();
						$post_categories = get_the_category();
						$category_name   = '';

						if ( ! empty( $post_categories ) && ! is_wp_error( $post_categories ) ) {
							$category_name = $post_categories[0]->name;
						}
						?>
						<article <?php post_class( 'sd-category-archive__card' ); ?>>
							<a class="sd-category-archive__media" href="<?php echo esc_url( get_permalink() ); ?>">
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
							<div class="sd-category-archive__body">
								<?php if ( $category_name !== '' ) : ?>
									<p class="sd-category-archive__category"><?php echo esc_html( $category_name ); ?></p>
								<?php endif; ?>
								<h3 class="sd-category-archive__title-link">
									<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
								</h3>
							</div>
						</article>
					<?php endwhile; ?>
				</div>

				<div class="sd-category-archive__pagination">
					<?php
					print apply_filters(
						'sdweddingdirectory/pagination',
						[
							'numpages' => absint( $category_query->max_num_pages ),
							'paged'    => absint( $paged ),
						]
					);
					?>
				</div>
			<?php else : ?>
				<p class="sd-category-archive__empty"><?php esc_html_e( 'No posts found in this category.', 'sdweddingdirectory' ); ?></p>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>
		</div>

		<aside class="sd-category-archive__sidebar" aria-label="<?php esc_attr_e( 'Category Sidebar', 'sdweddingdirectory' ); ?>">
			<section class="sd-category-archive__widget">
				<h3 class="sd-category-archive__widget-title"><?php esc_html_e( 'Category Browser', 'sdweddingdirectory' ); ?></h3>
				<ul class="sd-category-archive__category-list">
					<?php foreach ( $parent_categories as $parent_category ) : ?>
						<?php
						$parent_term_id    = absint( $parent_category->term_id );
						$is_parent_active  = ( $current_cat_id === $parent_term_id ) || in_array( $parent_term_id, $active_ancestor_ids, true );
						$child_categories  = get_categories(
							[
								'taxonomy'   => 'category',
								'hide_empty' => true,
								'parent'     => $parent_term_id,
								'orderby'    => 'name',
								'order'      => 'ASC',
							]
						);
						$parent_term_link  = get_term_link( $parent_category );
						$parent_link_class = 'sd-category-archive__category-link' . ( $is_parent_active ? ' is-active' : '' );
						?>
						<li class="sd-category-archive__category-item">
							<?php if ( ! empty( $child_categories ) ) : ?>
								<details class="sd-category-archive__category-children" <?php echo $is_parent_active ? 'open' : ''; ?>>
									<summary><?php echo esc_html( $parent_category->name ); ?></summary>
									<ul class="sd-category-archive__child-list">
										<li class="sd-category-archive__child-item">
											<?php if ( ! is_wp_error( $parent_term_link ) ) : ?>
												<a class="<?php echo esc_attr( $parent_link_class ); ?>" href="<?php echo esc_url( $parent_term_link ); ?>">
													<?php echo esc_html( $parent_category->name ); ?>
												</a>
											<?php endif; ?>
										</li>
										<?php foreach ( $child_categories as $child_category ) : ?>
											<?php
											$child_term_id    = absint( $child_category->term_id );
											$is_child_active  = ( $current_cat_id === $child_term_id );
											$child_term_link  = get_term_link( $child_category );
											$child_link_class = 'sd-category-archive__child-link' . ( $is_child_active ? ' is-active' : '' );
											?>
											<li class="sd-category-archive__child-item">
												<?php if ( ! is_wp_error( $child_term_link ) ) : ?>
													<a class="<?php echo esc_attr( $child_link_class ); ?>" href="<?php echo esc_url( $child_term_link ); ?>">
														<?php echo esc_html( $child_category->name ); ?>
													</a>
												<?php endif; ?>
											</li>
										<?php endforeach; ?>
									</ul>
								</details>
							<?php else : ?>
								<?php if ( ! is_wp_error( $parent_term_link ) ) : ?>
									<a class="<?php echo esc_attr( $parent_link_class ); ?>" href="<?php echo esc_url( $parent_term_link ); ?>">
										<?php echo esc_html( $parent_category->name ); ?>
									</a>
								<?php endif; ?>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>

			<section class="sd-category-archive__widget">
				<h3 class="sd-category-archive__widget-title"><?php esc_html_e( 'Wedding Planning Links', 'sdweddingdirectory' ); ?></h3>
				<ul class="sd-category-archive__planning-list">
					<?php foreach ( $planning_links as $planning_link ) : ?>
						<li class="sd-category-archive__planning-item">
							<a class="sd-category-archive__planning-link" href="<?php echo esc_url( $planning_link['url'] ); ?>">
								<?php echo esc_html( $planning_link['title'] ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		</aside>
	</div>
</section>

<?php do_action( 'sdweddingdirectory_main_container_end' ); ?>
