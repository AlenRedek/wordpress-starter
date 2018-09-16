<?php
/**
 * Hero setup.
 *
 * @package understrap
 */

?>

<?php if ( is_active_sidebar( 'hero' ) || is_active_sidebar( 'statichero' ) ) : ?>

	<div class="wrapper" id="wrapper-hero">
	
		<?php get_template_part( 'sidebars/sidebar', 'hero' ); ?>
		
		<?php get_template_part( 'sidebars/sidebar', 'statichero' ); ?>

	</div>

<?php endif; ?>
