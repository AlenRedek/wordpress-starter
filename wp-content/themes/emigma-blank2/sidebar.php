<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package emigma
 */
if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<div id="secondary" class="widget-area col-sm-12 col-md-4" role="complementary">

	<?php dynamic_sidebar( 'sidebar-1' ); ?>
	
</div><!-- #secondary -->