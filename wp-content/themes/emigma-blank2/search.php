<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package emigma
 */

get_header(); ?>
		<section id="primary" class="content-area col-sm-12 col-md-8">
			<main id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<?php get_search_form(); ?>
				</header><!-- .page-header -->

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'search' ); ?>

				<?php endwhile; ?>

				<?php emigma_paging_nav(); ?>

			<?php else : ?>

				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>

			</main><!-- #main -->
		</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
