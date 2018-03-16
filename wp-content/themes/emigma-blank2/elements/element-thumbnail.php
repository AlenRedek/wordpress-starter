<?php
    global $post, $attachment_id, $attachment_size, $attachment_title;
    if($attachment_id){
        // continue
    }elseif(has_post_thumbnail( $post->ID )){
        $attachment_id = get_post_thumbnail_id($post->ID);
    }

    $class = 'img-responsive';
    switch($post->post_type){
        case 'media':
            $attachment_size = 'gallery-thumbnail';
            $class = '';
        break;
        case 'post':
            $attachment_size = 'post-thumbnail';
            $class = '';
        break;
        default:
            $attachment_size = 'gallery-thumbnail';
            $class = '';
        break;
    }

    echo wp_get_attachment_image($attachment_id, $attachment_size, false, array('class'=>$class,'title'=>$attachment_title));
    $attachment_id = false;
?>
<div class="image-overlay"></div>
<i class="fa-wp-gallery <?php echo (get_post_format() === 'video' ? 'video' : 'gallery'); ?>"></i>