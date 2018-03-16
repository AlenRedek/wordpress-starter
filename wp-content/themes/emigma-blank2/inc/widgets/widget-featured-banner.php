<?php

class emigma_featured_banner_widget extends WP_Widget {

    public function __construct(){
        $widget_details = array(
            'classname' => 'emigma_featured_banner_widget',
            'description' => __('Creates a featured item banner consisting of a title, description and link.', 'emigma')
        );

        parent::__construct( 'emigma_featured_banner_widget', __('Featured Item banner', 'emigma'), $widget_details );

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

        $title = '';
	    if( !empty( $instance['title'] ) ) {
	        $title = $instance['title'];
	    }

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

	    $link_url_2 = '';
	    if( !empty( $instance['link_url_2'] ) ) {
	        $link_url_2 = $instance['link_url_2'];
	    }

	    $link_title_2 = '';
	    if( !empty( $instance['link_title_2'] ) ) {
	        $link_title_2 = $instance['link_title_2'];
	    }

	    $color = '';
		if(isset($instance['color'])) {
		    $color = $instance['color'];
		}

		$image = '';
		if(isset($instance['image'])) {
		    $image = $instance['image'];
		}

        ?>

        <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title', 'emigma' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

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
            <label for="<?php echo $this->get_field_name( 'link_url_2' ); ?>"><?php _e( 'Link 2 URL', 'emigma' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'link_url_2' ); ?>" name="<?php echo $this->get_field_name( 'link_url_2' ); ?>" type="text" value="<?php echo esc_attr( $link_url_2 ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_name( 'link_title_2' ); ?>"><?php _e( 'Link 2 Title', 'emigma' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'link_title_2' ); ?>" name="<?php echo $this->get_field_name( 'link_title_2' ); ?>" type="text" value="<?php echo esc_attr( $link_title_2 ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('color'); ?>"><?php _e( 'Color', 'emigma' ); ?></label>
            <select class='widefat' id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>" type="text">
                <option value='primary' <?php echo ($color === 'primary') ? 'selected' : ''; ?>>
                    <?php _e( 'Primary', 'emigma' ); ?>
                </option>
                <option value='secondary' <?php echo ($color === 'secondary') ? 'selected' : ''; ?>>
                    <?php _e( 'Secondary', 'emigma' ); ?>
                </option>
            </select>

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
        $color = $instance['color'] === '' ? 'primary' : $instance['color'];

        /* Determing all odd widget instances */
        preg_match('!\d+!', $args['id'], $counter);
        $counter = $counter[0];
        $extra_classes = '';
        if($counter % 2 != 0){
            $extra_classes = 'col-lg-offset-5';
        }
		?>

        <div class="background-image relative" style="background-image:url(<?php echo $instance['image'] ?>);">
            <div class="title-box-bottom bg-<?php echo $color; ?>">
                <div class="row">
                    <div class="col-xs-12 col-lg-7 <?php echo $extra_classes; ?>">
                        <h2 class="text-white xs-mb-15 xs-mt-0"><?php echo esc_html( $instance['title'] ); ?></h2>
                        <?php if($instance['description']): ?>
                            <h3 class=""><?php echo esc_html( $instance['description'] ); ?></h3>
                        <?php endif; ?>
                        <?php if($instance['link_url_1'] ): ?>
                            <a href="<?php echo esc_url( $instance['link_url_1'] ) ?>" class="btn btn-<?php echo $color; ?> xs-mr-15 xs-mb-10"><?php echo esc_html( $instance['link_title_1'] ) ?></a>
                        <?php endif; ?>
                        <?php if($instance['link_url_2'] ): ?>
                            <a href="<?php echo esc_url( $instance['link_url_2'] ) ?>" class="btn btn-<?php echo $color; ?> xs-mb-10"><?php echo esc_html( $instance['link_title_2'] ) ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

		<?php

		echo $args['after_widget'];
    }
}

?>