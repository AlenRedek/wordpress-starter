<?php

class emigma_sidenav_widget extends WP_Widget {

    public function __construct(){
        $widget_details = array(
            'classname' => 'emigma_sidenav_widget',
            'description' => __('Creats a side navigation from selected page.', 'emigma')
        );

        parent::__construct( 'emigma_sidenav_widget', __('Side navigation', 'emigma'), $widget_details );

    }

	public function update( $new_instance, $old_instance ) {
	    return $new_instance;
	}

    public function form( $instance ){

	    $page_id = '';
	    if( !empty( $instance['page_id'] ) ) {
	        $page_id = $instance['page_id'];
	    }
	    $args = array(
		    'post_type'			=> 'page',
		    'posts_per_page'	=> -1
		);
	    $pages = get_posts($args);
        ?>
        <p>
            <label for="<?php echo $this->get_field_name( 'page_id' ); ?>"><?php _e( 'Page', 'emigma' ); ?></label>

            <select class="widefat" id="<?php echo $this->get_field_id( 'page_id' ); ?>" name="<?php echo $this->get_field_name( 'page_id' ); ?>">
                <?php foreach($pages as $page): ?>
                <?php $selected = ( $page->ID == $page_id ) ? 'selected' : '';  ?>
                    <option value="<?php echo $page->ID; ?>" <?php echo $selected; ?>><?php echo $page->post_title; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
    <?php
    }

    public function widget( $args, $instance ) {
		echo $args['before_widget'];

		?>

        <div id="ar-side-nav">
            <a href="<?php echo get_permalink($instance['page_id']); ?>" class="btn btn-primary"><?php echo get_the_title($instance['page_id']); ?></a>
        </div>

		<?php

		echo $args['after_widget'];
    }
}

?>