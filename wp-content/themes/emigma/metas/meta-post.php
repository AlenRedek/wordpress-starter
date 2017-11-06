<?php global $post; ?>

<div><?php echo date_i18n(get_option('date_format'), strtotime($post->post_date)); ?></div>