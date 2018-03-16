<?php
    global $post;
    $url = get_permalink(get_page_id($post->post_type));
    switch($post->post_type){
        case 'post':
            $text = __('to news','emigma');
            break;
        case 'media':
            $text = __('to foto & video','emigma');
            break;
        case 'event':
            $text = __('to events','emigma');
            break;
        default:
            $text = '';
            $url = $_SERVER['HTTP_REFERER'];
    }
?>
<a href="<?php echo $url; ?>" class="btn btn-primary xs-mt-15 xs-mb-15"><?php printf( esc_html__( 'Back %s', 'emigma' ), $text ); ?></a>