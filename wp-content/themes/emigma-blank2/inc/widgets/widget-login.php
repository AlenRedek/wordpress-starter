<?php

class emigma_login_widget extends WP_Widget {

    public function __construct(){
        $widget_details = array(
            'classname' => 'emigma_login_widget',
            'description' => __('Login form with redirection to selected page.', 'emigma')
        );

        parent::__construct( 'emigma_sidenav_widget', __('Login form', 'emigma'), $widget_details );
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

	    $page_id = '';
	    if( !empty( $instance['page_id'] ) ) {
	        $page_id = $instance['page_id'];
	    }
	    $args = array(
		    'post_type'			=> 'page',
		    'posts_per_page'	=> -1,
		    'post_status' => array(
		        'publish',
		        'future',
		        'private'
		    )
		);
	    $pages = get_posts($args);
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
            <label for="<?php echo $this->get_field_name( 'page_id' ); ?>"><?php _e( 'Redirect page', 'emigma' ); ?></label>

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
        if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}

		if ( ! empty( $instance['description'] ) ) {
			echo '<p>'.$instance['description'].'</p>';
		}

		?>

        <?php
            $redirect_page = get_post($instance['page_id']);
            if(is_user_logged_in()){
                $user = wp_get_current_user();
                echo '<p><a href="'.get_permalink($redirect_page->ID).'">'.$redirect_page->post_title.'</a></p>';
                echo '<p>'.__('You are currently logged in as:', 'emigma') . '<br />' . $user->data->display_name.'</p>';
                echo '<p><a href="'.wp_logout_url( home_url() ).'" class="btn btn-primary">'.__('Logout', 'emigma').'</a></p>';
            }else{ ?>

                <form name="loginform" id="loginform" action="<?php echo esc_url( wp_login_url(get_permalink($redirect_page->ID)) ); ?>" method="post" _lpchecked="1">

                	<p class="login-submit form-group">
                		<input type="submit" name="wp-submit" id="wp-submit" class="btn btn-primary" value="<?php _e('Log in','emigma'); ?>">
                	</p>

                </form>

            <?php
            }
        ?>

		<?php

		echo $args['after_widget'];
    }
}

?>