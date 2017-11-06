<?php

class Purgatorio {
	
	public $pg_options;
	
	/**
	 * Holds the singleton instance of this class
	 * @since 2.3.3
	 * @var Jetpack
	 */
	static $instance = false;

	/**
	 * Singleton
	 * @static
	 */
	public static function init() {
		if ( ! self::$instance ) {
			self::$instance = new Purgatorio;
		}

		return self::$instance;
	}
	
	private function __construct() {
		$this->pg_options = get_option( PURGATORIO__SETTINGS );
		add_action( 'wp_enqueue_scripts', array( $this, 'pg_enqueue_scripts' ) );
	}
	
	public function get_option($key){
		if(isset($this->pg_options[$key])){
			return $this->pg_options[$key];
		}else{
			return false;
		}
	}
	
	public static function plugin_textdomain() {
		load_plugin_textdomain( 'purgatorio', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

    public function pg_enqueue_scripts() {
	    
	    /* Vendors */
		wp_enqueue_script('jquery-ui-widget');
		wp_enqueue_script('purgatorio-jquery-easing', PURGATORIO__PLUGIN_URL . 'assets/js/vendor/jquery-easing.js', array('jquery'), '1.3', true);
		
		if($this->get_option('include_bootstrap')){
			wp_enqueue_style('purgatorio-bootstrap', PURGATORIO__PLUGIN_URL . 'assets/css/bootstrap-purgatorio.css', array(), PURGATORIO_VERSION);
			wp_enqueue_script('purgatorio-bootstrap', PURGATORIO__PLUGIN_URL . 'assets/js/vendor/bootstrap.min.js', array('jquery'), '3.3.7', true);
		}
		if($this->get_option('include_ekko_lightbox')){
			wp_enqueue_style('purgatorio-ekko-lightbox', PURGATORIO__PLUGIN_URL . 'assets/css/vendor/ekko-lightbox.min.css');
			wp_enqueue_script('purgatorio-ekko-lightbox', PURGATORIO__PLUGIN_URL . 'assets/js/vendor/ekko-lightbox.min.js', array('jquery'), '4.0.2', true);
		}
		if($this->get_option('include_moment')){
			wp_enqueue_script('purgatorio-moment', PURGATORIO__PLUGIN_URL . 'assets/js/vendor/moment-with-locales.min.js', array('jquery'), '2.18.1', true);
		}
		if($this->get_option('include_bootstrap_datetimepicker')){
			wp_enqueue_style('purgatorio-bootstrap-datetimepicker', PURGATORIO__PLUGIN_URL . 'assets/css/vendor/bootstrap-datetimepicker.min.css', array(), '4.17.47');
			wp_enqueue_script('purgatorio-bootstrap-datetimepicker', PURGATORIO__PLUGIN_URL . 'assets/js/vendor/bootstrap-datetimepicker.min.js', array('jquery'), '4.17.47', true);
		}
		if($this->get_option('include_fontawesome')){
			wp_enqueue_style('purgatorio-fontawesome', PURGATORIO__PLUGIN_URL . 'assets/css/vendor/font-awesome.min.css', array(), '4.7.0');
		}
		if($ga_tracking_id = $this->get_option('ga_tracking_id')){
			wp_enqueue_script('purgatorio-ga-tracking', PURGATORIO__PLUGIN_URL . 'assets/js/ga_tracking.js', array(), PURGATORIO_VERSION, false);
			wp_localize_script('purgatorio-ga-tracking', 'ga_tracking_id', $ga_tracking_id);
		}
		if($typekit_id = $this->get_option('typekit_id')){
			wp_enqueue_script('purgatorio-typekit-src', 'https://use.typekit.net/'.$typekit_id.'.js', array(), PURGATORIO_VERSION, false);
	    	wp_enqueue_script('purgatorio-typekit', PURGATORIO__PLUGIN_URL . 'assets/js/typekit.js', array('tpurgatorio-typekit-src'), PURGATORIO_VERSION, false);
		}
		if ( is_singular() && comments_open() && get_option('thread_comments') ){
            wp_enqueue_script( 'comment-reply' );
        }
        
        /* Custom */
		wp_enqueue_script('purgatorio-share', PURGATORIO__PLUGIN_URL . 'assets/js/share.js', array('jquery'), PURGATORIO_VERSION, true);
		wp_enqueue_script('purgatorio-bootstrap-hover', PURGATORIO__PLUGIN_URL . 'assets/js/vendor/bootstrap-hover-dropdown.min.js', array('jquery'), '2.2.1', true);
		wp_enqueue_style('purgatorio', PURGATORIO__PLUGIN_URL . 'assets/css/purgatorio.css', array(), PURGATORIO_VERSION);
	    wp_enqueue_script('purgatorio', PURGATORIO__PLUGIN_URL . 'assets/js/purgatorio.js', array('jquery', 'jquery-ui-widget', 'purgatorio-jquery-easing'), PURGATORIO_VERSION, true );
	}

}

?>
