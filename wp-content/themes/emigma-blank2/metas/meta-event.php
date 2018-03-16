<?php global $post; ?>

<?php if($post->event_location): ?>
    <div><?php echo $post->event_location; ?></div>
<?php endif; ?>
<?php if($post->event_date && is_single()): ?>
    <div><?php echo get_date_format($post->event_date, 'd. M Y H:i'); ?></div>
<?php endif; ?>