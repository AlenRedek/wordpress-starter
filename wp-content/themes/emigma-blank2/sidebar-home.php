<?php
/**
 * The Sidebar widget area for static frontpage.
 *
 * @package emigma
 */
?>

	<?php
	// If footer sidebars do not have widget let's bail.

	if ( ! is_active_sidebar( 'home-widget-1' ) && ! is_active_sidebar( 'home-widget-2' ) )
		return;
	// If we made it this far we must have widgets.
	?>

	<div class="home-widget-area">
		<?php if ( is_active_sidebar( 'home-widget-1' ) ) : ?>
		<div class="col-xs-12 col-sm-6 home-widget" role="complementary">
			<?php dynamic_sidebar( 'home-widget-1' ); ?>
		</div><!-- .widget-area .first -->
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'home-widget-2' ) ) : ?>
		<div class="col-xs-12 col-sm-6 home-widget" role="complementary">
			<?php dynamic_sidebar( 'home-widget-2' ); ?>
		</div><!-- .widget-area .second -->
		<?php endif; ?>
		<div class="clearfix"></div>
	</div>

