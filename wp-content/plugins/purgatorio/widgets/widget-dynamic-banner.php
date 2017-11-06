<?php

class pg_dynamic_banner_widget extends WP_Widget {

    public function __construct(){
        $widget_details = array(
            'classname' => 'pg_dynamic_banner_widget',
            'description' => __('Displays banner related to current page.', 'purgatorio')
        );

        parent::__construct( 'pg_dynamic_banner_widget', __('[Purgatorio] Dynamic banner', 'purgatorio'), $widget_details );
    }
    
    public function update( $new_instance, $old_instance ) {
	    return $new_instance;
	}

    public function form( $instance ){

		$image_width = '';
		if(isset($instance['image_width'])) {
		    $image_width = $instance['image_width'];
		}
		
		$image_height = '';
		if(isset($instance['image_height'])) {
		    $image_height = $instance['image_height'];
		}
        ?>
        
        <p>
            <label for="<?php echo $this->get_field_name( 'image_width' ); ?>"><?php _e( 'Image width', 'purgatorio' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'image_width' ); ?>" name="<?php echo $this->get_field_name( 'image_width' ); ?>" type="text" value="<?php echo esc_attr( $image_width ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_name( 'image_height' ); ?>"><?php _e( 'Image height', 'purgatorio' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'image_height' ); ?>" name="<?php echo $this->get_field_name( 'image_height' ); ?>" type="text" value="<?php echo esc_attr( $image_height ); ?>" />
        </p>
    <?php
    }

    public function widget( $args, $instance ) {
	    global $post;
	    if( ! $post->banner_image ) return;
	    
		echo $args['before_widget'];

		?>

		<div id="ar-dynamic-banner">
			<a href="<?php echo $post->banner_url; ?>" target="<?php echo $post->banner_url_target; ?>" alt="<?php echo $post->banner_title; ?>">
				<div class="row">
					<div class="col-xs-12 col-md-4">
						<h3 class="text-white"><?php echo $post->banner_title; ?></h3>
					</div>
					<div class="col-xs-12 col-md-8">
						<div class="background-image no-height" style="height: <?php echo $instance['image_height']; ?>px;background-image: url(<?php echo wp_get_attachment_url($post->banner_image); ?>);"></div>
					</div>
				</div>
			</a>
		</div>

        <?php
		echo $args['after_widget'];
    }
}

?>