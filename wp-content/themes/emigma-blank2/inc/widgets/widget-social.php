<?php

/**
 * Social  Widget
 * Emigma Theme
 */
class emigma_social_widget extends WP_Widget
{
    function emigma_social_widget(){

       $widget_ops = array('classname' => 'emigma-social','description' => esc_html__( "Social Widget" ,'emigma') );
       parent::__construct('emigma-social', esc_html__('Social Widget','emigma'), $widget_ops);
    }

    function widget($args , $instance) {
    	extract($args);
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Follow us' , 'emigma');

        echo $before_widget;
        echo $before_title;
        echo $title;
        echo $after_title;

        /**
         * Widget Content
         */ ?>

        <!-- social icons -->
        <div class="social-icons sticky-sidebar-social">

            <?php emigma_social_icons(); ?>

        </div><!-- end social icons --><?php

        echo $after_widget;
    }

    function form($instance) {
      if(!isset($instance['title'])) $instance['title'] = esc_html__('Follow us' , 'emigma'); ?>

      <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title ','emigma') ?></label>

      <input type="text" value="<?php echo esc_attr($instance['title']); ?>"
                          name="<?php echo $this->get_field_name('title'); ?>"
                          id="<?php $this->get_field_id('title'); ?>"
                          class="widefat" />
      </p><?php
    }

}
?>