<?php
/**
 * The Sidebar widget area for footer.
 *
 * @package emigma
 */

global $mailchimp;

?>

	<?php
	// If footer sidebars do not have widget let's bail.

	if ( ! is_active_sidebar( 'footer-widget-1' ) && ! is_active_sidebar( 'footer-widget-2' ) && ! is_active_sidebar( 'footer-widget-3' ) && ! is_active_sidebar( 'footer-widget-4' ) )
		return;
	// If we made it this far we must have widgets.
	?>

	<div class="footer-widget-area">
		<div class="row">
			<?php if ( is_active_sidebar( 'footer-widget-1' ) ) : ?>
			<div class="col-xs-12 col-sm-6 col-md-3 footer-widget" role="complementary">
				<?php dynamic_sidebar( 'footer-widget-1' ); ?>
			</div><!-- .widget-area .first -->
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'footer-widget-2' ) ) : ?>
			<div class="col-xs-12 col-sm-6 col-md-3 footer-widget" role="complementary">
				<?php dynamic_sidebar( 'footer-widget-2' ); ?>
			</div><!-- .widget-area .second -->
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'footer-widget-3' ) ) : ?>
			<div class="col-xs-12 col-sm-6 col-md-3 footer-widget" role="complementary">
				<?php dynamic_sidebar( 'footer-widget-3' ); ?>
			</div><!-- .widget-area .second -->
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'footer-widget-4' ) ) : ?>
			<div class="col-xs-12 col-sm-6 col-md-3 footer-widget" role="complementary">
				<?php dynamic_sidebar( 'footer-widget-4' ); ?>
			</div><!-- .widget-area .second -->
			<?php endif; ?>
		</div>
	</div>