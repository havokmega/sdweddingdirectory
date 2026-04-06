<?php
/**
 * Blog Grid 5 Template Part
 * Usage: get_template_part( 'template-parts/blog/blog-grid-5' );
 */

defined( 'ABSPATH' ) || exit;

$blog_grid_query = new WP_Query(
	[
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 5,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	]
);
?>

<div class="blog-grid-5">
	<style>
		.blog-grid-5 {
			width: 100%;
		}

		.blog-grid-5__grid {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			grid-template-rows: repeat(2, 1fr);
			gap: 18px;
			align-items: stretch;
		}

		.blog-grid-5__card {
			display: flex;
			flex-direction: column;
			height: 100%;
			background: #fff;
			border: 1px solid #e0e0e0;
			border-radius: 8px;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
			overflow: hidden;
		}

		.blog-grid-5__card:nth-child(1) {
			grid-column: 1 / 3;
			grid-row: 1 / 3;
		}

		.blog-grid-5__card:nth-child(2) {
			grid-column: 3 / 4;
			grid-row: 1 / 2;
		}

		.blog-grid-5__card:nth-child(3) {
			grid-column: 4 / 5;
			grid-row: 1 / 2;
		}

		.blog-grid-5__card:nth-child(4) {
			grid-column: 3 / 4;
			grid-row: 2 / 3;
		}

		.blog-grid-5__card:nth-child(5) {
			grid-column: 4 / 5;
			grid-row: 2 / 3;
		}

		.blog-grid-5__media {
			display: block;
			flex: 0 0 60%;
			min-height: 170px;
			background: #f7f7f7;
		}

		.blog-grid-5__media img {
			display: block;
			width: 100%;
			height: 100%;
			object-fit: cover;
		}

		.blog-grid-5__body {
			display: flex;
			flex-direction: column;
			flex: 1 1 40%;
			padding: 12px 14px 14px;
		}

		.blog-grid-5__category {
			margin: 0 0 8px;
			font-size: 0.72rem;
			font-weight: 700;
			letter-spacing: 0.04em;
			text-transform: uppercase;
			color: #6f6f6f;
		}

		.blog-grid-5__title {
			margin: 0;
			font-size: 1rem;
			line-height: 1.35;
			font-weight: 700;
		}

		.blog-grid-5__title a {
			color: #242424;
			text-decoration: none;
		}

		.blog-grid-5__title a:hover,
		.blog-grid-5__title a:focus {
			color: var(--sdweddingdirectory-color-cyan, #00aeaf);
			text-decoration: none;
		}

		.blog-grid-5__empty {
			margin: 0;
			font-size: 0.95rem;
			color: #606060;
		}

		@media (max-width: 991.98px) {
			.blog-grid-5__grid {
				grid-template-columns: repeat(2, 1fr);
				grid-template-rows: auto;
			}

			.blog-grid-5__card:nth-child(1) {
				grid-column: 1 / -1;
				grid-row: auto;
			}

			.blog-grid-5__card:nth-child(2),
			.blog-grid-5__card:nth-child(3),
			.blog-grid-5__card:nth-child(4),
			.blog-grid-5__card:nth-child(5) {
				grid-column: auto;
				grid-row: auto;
			}
		}

		@media (max-width: 575.98px) {
			.blog-grid-5__grid {
				grid-template-columns: 1fr;
			}
		}
	</style>

	<?php if ( $blog_grid_query->have_posts() ) : ?>
		<div class="blog-grid-5__grid">
			<?php while ( $blog_grid_query->have_posts() ) : ?>
				<?php
				$blog_grid_query->the_post();
				$post_categories = get_the_category();
				$category_name   = '';

				if ( ! empty( $post_categories ) && ! is_wp_error( $post_categories ) ) {
					$category_name = $post_categories[0]->name;
				}
				?>
				<article <?php post_class( 'blog-grid-5__card' ); ?>>
					<a class="blog-grid-5__media" href="<?php echo esc_url( get_permalink() ); ?>">
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
					<div class="blog-grid-5__body">
						<?php if ( $category_name !== '' ) : ?>
							<p class="blog-grid-5__category"><?php echo esc_html( $category_name ); ?></p>
						<?php endif; ?>
						<h3 class="blog-grid-5__title">
							<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
						</h3>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
	<?php else : ?>
		<p class="blog-grid-5__empty"><?php esc_html_e( 'No posts found.', 'sdweddingdirectory' ); ?></p>
	<?php endif; ?>

	<?php wp_reset_postdata(); ?>
</div>
