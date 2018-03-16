<?php

class emigma_banner_widget extends WP_Widget {

    public function __construct(){
        $widget_details = array(
            'classname' => 'emigma_banner_widget',
            'description' => __('Creates a top banner item consisting of a title, description and link.', 'emigma')
        );

        parent::__construct( 'emigma_banner_widget', __('Top banner item', 'emigma'), $widget_details );

        add_action('admin_enqueue_scripts', array($this, 'mfc_assets'));
    }

    public function mfc_assets() {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('widget-media-upload', get_stylesheet_directory_uri() . '/assets/js/widget-media-upload.js', array('jquery'));
        wp_enqueue_style('thickbox');
    }

	public function update( $new_instance, $old_instance ) {
	    return $new_instance;
	}

    public function form( $instance ){

	    $description = '';
	    if( !empty( $instance['description'] ) ) {
	        $description = $instance['description'];
	    }

	    $link_url_1 = '';
	    if( !empty( $instance['link_url_1'] ) ) {
	        $link_url_1 = $instance['link_url_1'];
	    }

	    $link_title_1 = '';
	    if( !empty( $instance['link_title_1'] ) ) {
	        $link_title_1 = $instance['link_title_1'];
	    }

		$image = '';
		if(isset($instance['image'])) {
		    $image = $instance['image'];
		}
        ?>

        <p>
            <label for="<?php echo $this->get_field_name( 'description' ); ?>"><?php _e( 'Description', 'emigma' ); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" type="text" ><?php echo esc_attr( $description ); ?></textarea>
        </p>

        <p>
            <label for="<?php echo $this->get_field_name( 'link_url_1' ); ?>"><?php _e( 'Link 1 URL', 'emigma' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'link_url_1' ); ?>" name="<?php echo $this->get_field_name( 'link_url_1' ); ?>" type="text" value="<?php echo esc_attr( $link_url_1 ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_name( 'link_title_1' ); ?>"><?php _e( 'Link 1 Title', 'emigma' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'link_title_1' ); ?>" name="<?php echo $this->get_field_name( 'link_title_1' ); ?>" type="text" value="<?php echo esc_attr( $link_title_1 ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_name( 'image' ); ?>"><?php _e( 'Image', 'emigma' ); ?></label>
            <input name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" class="widefat" type="text" size="36"  value="<?php echo esc_url( $image ); ?>" />
            <input class="upload_image_button" type="button" value="<?php _e( 'Upload Image', 'emigma' ); ?>" />
        </p>
    <?php
    }

    public function widget( $args, $instance ) {
		echo $args['before_widget'];

		?>

        <div class="jumbotron background-image display-table" style="background-image:url(<?php echo $instance['image'] ?>);">
            <div class="display-table-cell">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-md-9 col-lg-6">
                            <?php if( isset($instance['title']) ): ?>
                                <h2 class="widget-title"><?php echo esc_html( $instance['title'] ); ?></h2>
                            <?php endif; ?>
                            <h3 class=""><?php echo esc_html( $instance['description'] ); ?></h3>
                            <?php if($instance['link_url_1'] ): ?>
                                <a href="<?php echo esc_url( $instance['link_url_1'] ) ?>" class="btn btn-lg btn-primary xs-mb-50"><?php echo esc_html( $instance['link_title_1'] ) ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

		<?php

		echo $args['after_widget'];
    }
}

?>