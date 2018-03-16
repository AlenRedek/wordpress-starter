<?php
class mhEventCpt extends mhCptAbstract{
    public function createSettings(){
        $this->settings = array(
            'registration' => array(
				'label' => __('Events', 'emigma'),
				'slug' => 'event',
				'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions'),
				'menu_icon' => 'dashicons-calendar-alt',
			)
        );
    }
}
?>