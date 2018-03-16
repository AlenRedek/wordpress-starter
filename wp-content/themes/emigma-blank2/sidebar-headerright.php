<?php
/**
 * The sidebar containing header area.
 *
 * @package understrap
 */

if ( ! is_active_sidebar( 'headerright' ) ) {
	return;
}
?>

<div id="headerright" class="pull-right-md">

	<?php dynamic_sidebar( 'headerright' ); ?>
	
</div><!-- #secondary -->
