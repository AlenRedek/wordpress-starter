<?php global $post, $hide_readmore; ?>
<div class="read-more">
  <?php if( ! $hide_readmore): ?>
    <a href="<?php echo get_permalink($post->ID); ?>" class="inline-block btn btn-primary xs-mr-15 xs-mb-5"><?php _e('Read more', 'emigma'); ?></a>
  <?php endif; ?>
  <div class="dropdown inline-block xs-mb-5">
      <a href="" type="button" class="dropdown-toggle btn btn-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><?php _e('Share', 'emigma'); ?></a>
      <div class="dropdown-menu animated-dropdown">
        <?php get_template_part('elements/element','share'); ?>
      </div>
  </div>
</div>