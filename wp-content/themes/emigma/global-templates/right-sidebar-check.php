<?php
/**
 * Right sidebar check.
 *
 * @package understrap
 */
?>

<?php $sidebar_pos = get_theme_mod( 'understrap_sidebar_position' ); ?>

<?php if ( 'right' === $sidebar_pos || 'both' === $sidebar_pos ) : ?>

  <?php get_template_part( 'sidebars/sidebar', 'right' ); ?>

<?php endif; ?>
