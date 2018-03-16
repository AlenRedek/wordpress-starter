<?php global $post; ?>

<?php if($post->location): ?>
    <div><?php echo $post->location; ?></div>
<?php endif; ?>
<?php if($post->datetime && is_single()): ?>
    <div><?php echo get_date_format($post->datetime, 'd. M Y H:i'); ?></div>
<?php endif; ?>