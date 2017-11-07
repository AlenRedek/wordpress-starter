<?php
	
class Purgatorio_Admin {

	/**
	 * @var Purgatorio_Admin
	 **/
	private static $instance = null;

	/**
	 * @var Purgatorio
	 **/
	private $purgatorio;

	static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new Purgatorio_Admin;
		}
		
		return self::$instance;
	}

	private function __construct() {
		$this->purgatorio = Purgatorio::init();
		
		add_filter( 'plugin_action_links_'.PURGATORIO__PLUGIN_BASENAME, array( $this, 'add_settings_link' ) );
		add_action( 'admin_head', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'settings_init' ) );
	}
	
	public function add_settings_link( $links ) {
	    $settings_link = '<a href="options-general.php?page=purgatorio">' . __( 'Settings' ) . '</a>';
	    array_push( $links, $settings_link );
	  	return $links;
	}
	
	public function admin_enqueue_scripts() {
		wp_enqueue_script('purgatorio-admin', PURGATORIO__PLUGIN_URL . '/assets/js/admin.js', array('jquery'), PURGATORIO_VERSION, true);
		
		$admin_js_data = array(
			'wp_docs_url' 	=> $this->purgatorio->get_option('wp_docs_url'),
			'wp_docs_text' 	=> __('Instructions', 'purgatorio')
		);
		wp_localize_script('purgatorio-admin', 'purgatorioData', $admin_js_data);
	}
	
	public function add_admin_menu(){
		add_options_page( 'Purgatorio', 'Purgatorio', 'manage_options', 'purgatorio', array($this, 'render_options_page'));
	}
	
	public function settings_init() {
		
		$option_group = 'purgatorio_general';
		$option_name = PURGATORIO__SETTINGS;
		register_setting( $option_group, $option_name );
		
		$section_id = 'purgatorio_general_section';
		add_settings_section(
			$section_id,
			__( 'General settings', 'purgatorio' ),
			false,
			$option_group
		);
		
		$field_key = 'dev_ip';
		add_settings_field(
			$field_key,
			__( 'Development IP address', 'purgatorio' ),
			array($this, 'render_input_text_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'enable_livereload';
		add_settings_field(
			$field_key,
			__( 'Enable LiveReload', 'purgatorio' ),
			array($this, 'render_input_checkbox_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'wp_docs_url';
		add_settings_field(
			$field_key,
			__( 'WordPress Guide URL', 'purgatorio' ),
			array($this, 'render_input_text_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'theme_option_id';
		add_settings_field(
			$field_key,
			__( 'Theme option ID', 'purgatorio' ),
			array($this, 'render_input_text_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'typekit_id';
		add_settings_field(
			$field_key,
			__( 'Typekit ID', 'purgatorio' ),
			array($this, 'render_input_text_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'author_id';
		add_settings_field(
			$field_key,
			__( 'Author ID', 'purgatorio' ),
			array($this, 'render_input_text_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'publisher_id';
		add_settings_field(
			$field_key,
			__( 'Publisher ID', 'purgatorio' ),
			array($this, 'render_input_text_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'attachments_metakey';
		add_settings_field(
			$field_key,
			__( 'Attachments metakey', 'purgatorio' ),
			array($this, 'render_input_text_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$section_id = 'purgatorio_3rdparty_section';
		add_settings_section(
			$section_id,
			__( '3rd party libraries', 'purgatorio' ),
			false,
			$option_group
		);
		
		$field_key = 'include_bootstrap';
		add_settings_field(
			$field_key,
			__( 'Include Bootstrap', 'purgatorio' ),
			array($this, 'render_input_checkbox_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'include_ekko_lightbox';
		add_settings_field(
			$field_key,
			__( 'Include Ekko Lightbox', 'purgatorio' ),
			array($this, 'render_input_checkbox_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'include_moment';
		add_settings_field(
			$field_key,
			__( 'Include Moment JS', 'purgatorio' ),
			array($this, 'render_input_checkbox_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'include_bootstrap_datetimepicker';
		add_settings_field(
			$field_key,
			__( 'Include Bootstrap Datetime picker', 'purgatorio' ),
			array($this, 'render_input_checkbox_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'include_fontawesome';
		add_settings_field(
			$field_key,
			__( 'Include Font Awesome', 'purgatorio' ),
			array($this, 'render_input_checkbox_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$section_id = 'purgatorio_google_section';
		add_settings_section(
			$section_id,
			__( 'Google settings', 'purgatorio' ),
			false,
			$option_group
		);
		
		$field_key = 'ga_tracking_id';
		add_settings_field(
			$field_key,
			__( 'GA tracking ID', 'purgatorio' ),
			array($this, 'render_input_text_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'gmaps_api_key';
		add_settings_field(
			$field_key,
			__( 'Google Maps API key', 'purgatorio' ),
			array($this, 'render_input_text_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'gmaps_marker';
		add_settings_field(
			$field_key,
			__( 'Google Maps marker URL', 'purgatorio' ),
			array($this, 'render_input_text_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'gmaps_style';
		add_settings_field(
			$field_key,
			__( 'Google Maps style (JSON)', 'purgatorio' ),
			array($this, 'render_input_textarea_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'gmaps_address_metakey';
		add_settings_field(
			$field_key,
			__( 'Google Maps address metakey', 'purgatorio' ),
			array($this, 'render_input_text_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'gmaps_lat_metakey';
		add_settings_field(
			$field_key,
			__( 'Google Maps latitude metakey', 'purgatorio' ),
			array($this, 'render_input_text_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
		$field_key = 'gmaps_lng_metakey';
		add_settings_field(
			$field_key,
			__( 'Google Maps longitude metakey', 'purgatorio' ),
			array($this, 'render_input_text_field'),
			$option_group,
			$section_id,
			array('field_key' => $field_key)
		);
		
	}
	
	public function render_input_text_field($args){
		$field_key = $args['field_key'];
		?>
		<input type="text" name="<?php echo PURGATORIO__SETTINGS.'['.$field_key.']'; ?>" value="<?php echo $this->purgatorio->get_option($field_key); ?>">
		<?php
	}
	
	public function render_input_textarea_field($args){
		$field_key = $args['field_key'];
		?>
		<textarea cols="40" rows="5" name="<?php echo PURGATORIO__SETTINGS.'['.$field_key.']'; ?>">
			<?php echo $this->purgatorio->get_option($field_key); ?>
	 	</textarea>
		<?php
	}
	
	public function render_input_checkbox_field($args){
		$field_key = $args['field_key'];
		?>
		<input type="checkbox" name="<?php echo PURGATORIO__SETTINGS.'['.$field_key.']'; ?>" <?php checked( $this->purgatorio->get_option($field_key), 1 ); ?> value='1'>
		<?php
	}
	
	public function render_options_page(){
		?>
		<form action='options.php' method='post'>
	
			<h1><?php _e('Purgatorio settings page', 'purgatorio'); ?></h1>
	
			<?php
			settings_fields( 'purgatorio_general' );
			do_settings_sections( 'purgatorio_general' );
			submit_button();
			?>
	
		</form>
		<?php
	}

}
	
?>