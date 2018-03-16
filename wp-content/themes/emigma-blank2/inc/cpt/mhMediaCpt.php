<?php
class mhMediaCpt extends mhCptAbstract{
    public function createSettings(){
        $this->settings = array(
            'registration' => array(
				'label' => __('Foto & Video', 'emigma'),
				'slug' => 'media',
				'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'post-formats'),
				'menu_icon' => 'dashicons-camera',
			)
        );
    }
}
?>