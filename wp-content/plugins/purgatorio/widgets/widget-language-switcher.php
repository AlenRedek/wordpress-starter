<?php

class pg_language_switcher_widget extends WP_Widget {

    public function __construct(){
        $widget_details = array(
            'classname' => 'pg_language_switcher_widget',
            'description' => __('Creates a language switcher.', 'purgatorio')
        );

        parent::__construct( 'pg_language_switcher_widget', __('[Purgatorio] Language Switcher', 'purgatorio'), $widget_details );
    }

    public function widget( $args, $instance ) {
		echo $args['before_widget'];

		if ( ! function_exists("pll_languages_list") ) {
			return false;
		}

		$languages_raw = pll_the_languages(array('raw'=>1));
		$lang_switcher = array();
		$i = 1;
		foreach($languages_raw as $lang){
			if($lang['current_lang'] === true){
				$lang_switcher[0] = $lang;
			}else{
				$lang_switcher[$i] = $lang;
				$i++;
			}
		}
		?>

		<div id="ar-language-switcher">
		    <a href="<?php echo esc_url($lang_switcher[0]['url']); ?>" class="<?php echo implode(' ', $lang_switcher[0]['classes']); ?>">
		        <img src="<?php echo esc_url($lang_switcher[0]['flag']); ?>" title="<?php echo esc_attr($lang_switcher[0]['name']); ?>">
		    </a>
		    <div class="language-switcher-wrapper">
    		    <?php unset($lang_switcher[0]); foreach($lang_switcher as $lan): ?>
    				<a href="<?php echo esc_url($lan['url']); ?>" class="<?php echo implode(' ', $lan['classes']); ?>"><img src="<?php echo esc_url($lan['flag']); ?>" title="<?php echo esc_attr($lan['name']); ?>"></a>
    			<?php endforeach; ?>
		    </div>
		</div>

        <?php
		echo $args['after_widget'];
    }
}

?>