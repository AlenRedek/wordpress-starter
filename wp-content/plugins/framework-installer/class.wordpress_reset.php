<?php
/*
 * EMERSON: Extending WordPress Reset Class
 * 
*/
if (!class_exists('WordPressReset')) {	
	require_once WPVDEMO_ABSPATH . '/wordpress-reset/wordpress-reset.php';
}
if ( is_admin() ) :
class WPVDemo_WordPress_Reset extends WordPressReset
{
	// Constructor
	function __construct() {
		add_action( 'admin_menu', array( &$this, 'add_page' ),20);
		add_action( 'admin_init', array( &$this, 'admin_init' ) );	
		add_filter( 'wp_mail', array( &$this, 'hijack_mail' ), 1 );
		
		//Support for unified menu		
		add_action( 'toolset_enqueue_scripts',	array( $this, 'toolset_enqueue_scripts' ) );
		add_filter( 'toolset_filter_toolset_register_settings_section', array( $this, 'wpvdemo_register_settings_resetdemosite_section' ), 100 );
		add_filter( 'toolset_filter_toolset_register_settings_wpvdemo-reset_section', array( $this, 'wpvdemo_resetdemosite_section_callback' ), 100 );
	}
	
	function toolset_enqueue_scripts( $current_page ) {
		switch ( $current_page ) {
			case 'toolset-settings':
				add_action( "admin_print_scripts", array( &$this, 'admin_js' ) );
				add_action( "admin_footer", array( &$this, 'footer_js' ) );	
				break;
		}
	}
	
	function hijack_mail( $args ) {
		if ( preg_match( '/Your new WordPress (blog|site) has been successfully set up at/i', $args['message'] ) ) {
			$args['message'] = str_replace( 'Your new WordPress site has been successfully set up at:', 'Your WordPress site has been successfully reset, and can be accessed at:', $args['message'] );
			$args['message'] = preg_replace( '/Password:.+/', 'Password: previously specified password', $args['message'] );
		}
		return $args;
	}
	
	public function wpvdemo_register_settings_resetdemosite_section( $sections ) {
	
		$sections['wpvdemo-reset'] = array(
				'slug'	=> 'wpvdemo-reset',
				'title'	=> __( 'Reset Demo Site', 'wpvdemo' )
		);	
		return $sections;
		 
	}
	
	public function wpvdemo_resetdemosite_section_callback( $sections ) {
		ob_start();
	
		echo $this->admin_page('unified');			
		$section_content = ob_get_clean();
			
		$sections['wpvdemo-reset'] = array(
				'slug'		=> 'wpvdemo-reset',
				'title'		=> __( 'Reset Demo Site', 'wpvdemo' ),
				'content'	=> $section_content
		);
	
		return $sections;
	
		 
	}	
	
	function add_page() {
		global $submenu, $frameworkinstaller;
		if ( current_user_can('manage_options') ) {
			if ( ! ( $frameworkinstaller->wpvdemo_can_implement_unified_menu() ) ) {
				//Cannot implement new unified menu.
				//Add backward compatibility
				$hook = add_submenu_page( 'manage-refsites', __( 'Framework Installer - Reset Demo Site to Create New WordPress Install', 'wpvdemo' ),__( 'Reset Demo Site', 'wpvdemo' ), 'manage_options', 'wpvdemo-reset',array(&$this, 'admin_page' )  );
				if (isset($submenu['manage-refsites'][0][0])) {
					$submenu['manage-refsites'][0][0] = 'Reference Sites';
				}
				add_action( "admin_print_scripts-{$hook}", array( &$this, 'admin_js' ) );
				add_action( "admin_footer-{$hook}", array( &$this, 'footer_js' ) );				
				
			}
		}
	}		

	// admin_init action hook operations
	// Checks for wordpress_reset post value and if there deletes all wp tables
	// and performs an install, populating the users previous password also
	function admin_init() {
		global $current_user;
	
		$wordpress_reset = ( isset( $_POST['wordpress_reset'] ) && $_POST['wordpress_reset'] == 'true' ) ? true : false;
		$wordpress_reset_confirm = ( isset( $_POST['wordpress_reset_confirm'] ) && $_POST['wordpress_reset_confirm'] == 'reset' ) ? true : false;
		$valid_nonce = ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'wordpress_reset' ) ) ? true : false;
	
		if ( $wordpress_reset && $wordpress_reset_confirm && $valid_nonce ) {
			require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
	
			$blogname = get_option( 'blogname' );
			$admin_email = get_option( 'admin_email' );
			$blog_public = get_option( 'blog_public' );
  
			if ( $current_user->user_login != 'admin' )
				$user = get_user_by( 'login', 'admin' );
	
			if (!isset( $user ) || (current_user_can( 'manage_options' )))
			
				$user = $current_user;
	
			global $wpdb;
	
			$prefix = str_replace( '_', '\_', $wpdb->prefix );
			$tables = $wpdb->get_col( "SHOW TABLES LIKE '{$prefix}%'" );
			foreach ( $tables as $table ) {
				$wpdb->query( "DROP TABLE $table" );
			}
			$this->wpvdemo_reset_fix_notices_after_reset();
			$result = wp_install( $blogname, $user->user_login, $user->user_email, $blog_public );
			extract( $result, EXTR_SKIP );
	
			$query = $wpdb->prepare( "UPDATE $wpdb->users SET user_pass = %s, user_activation_key = '' WHERE ID = %d", $user->user_pass, $user_id );
			$wpdb->query( $query );
	
			$get_user_meta = function_exists( 'get_user_meta' ) ? 'get_user_meta' : 'get_usermeta';
			$update_user_meta = function_exists( 'update_user_meta' ) ? 'update_user_meta' : 'update_usermeta';
	
			if ( $get_user_meta( $user_id, 'default_password_nag' ) )
				$update_user_meta( $user_id, 'default_password_nag', false );
	
			if ( $get_user_meta( $user_id, $wpdb->prefix . 'default_password_nag' ) )
				$update_user_meta( $user_id, $wpdb->prefix . 'default_password_nag', false );

			wp_clear_auth_cookie();
			wp_set_auth_cookie( $user_id );

			if ( defined('WPDEMO_REACTIVATE_FI') && WPDEMO_REACTIVATE_FI === true ) {
				$plugin = 'framework-installer/views-demo.php';
				activate_plugin( $plugin, add_query_arg( 'page', 'manage-refsites', admin_url() ) );
			} else {
				wp_redirect( add_query_arg( 'reset', '', admin_url() ) );
			}
			exit();
		}	        

	}
	
	function wpvdemo_reset_fix_notices_after_reset() {
	
		//Emerson: These filters can cause PHP notices, not needed during reset so remove them.
		remove_all_filters('gettext');
		remove_all_filters('gettext_with_context');
		remove_all_filters('update_option_blogname');
		remove_all_filters('pre_update_option_blogname');
		remove_all_filters('pre_option_page_on_front');
		remove_all_filters('pre_update_option_rewrite_rules');
		remove_all_filters('post_link');
		remove_all_filters('home_url');
		remove_all_filters('locale');
		remove_all_filters('option_rewrite_rules');
		remove_all_actions('shutdown');
	}
		
	// admin_print_scripts action hook operations
	// Enqueue jQuery to the head
	function admin_js() {
		wp_enqueue_script( 'jquery' );		
		$resetting_message=__('Are you really sure? Clicking "OK" will delete this WordPress site database! This action is not reversible! Click "Cancel" to abort.','wpvdemo');
		$invalid_confirmation_word=__('Invalid confirmation word. Please type the word \'reset\' in the confirmation field.','wpvdemo');
		
		wp_localize_script('jquery', 'wpv_wordpress_reset_object',
		array(
		'translated_message' => $resetting_message,
		'invalid_confirmation_word' =>$invalid_confirmation_word,
		)
		);
		
	}
	
	// admin_footer action hook operations
	// Do some jQuery stuff to warn the user before submission
	function footer_js() {
		?>
		<script type="text/javascript">
		/* <![CDATA[ */
			jQuery('#wordpress_reset_submit').click(function(){
				if ( jQuery('#wordpress_reset_confirm').val() == 'reset' ) {										
					var message = wpv_wordpress_reset_object.translated_message;
					var reset = confirm(message);
					if ( reset ) {
						
						//User confirms the reset, proceed to submitting the form
						//Let's validate the value
						var resetlogic= jQuery('#wordpress_reset').val();
						
						if ('false' == resetlogic) {
							
							//This is previously cancelled. Now let's switched to true to proceed since the reset has now been confirmed.
							jQuery('#wordpress_reset').val('true');
						}

						//Let's submit the form
						jQuery('#wordpress_reset_form').submit();
						
					} else {

						//User cancels the reset here
						jQuery('#wordpress_reset').val('false');
						return false;
					}
				} else {					
					alert(wpv_wordpress_reset_object.invalid_confirmation_word);
					return false;
				}
			});
		/* ]]> */
		</script>	
		<?php
		}
	
		// add_option_page callback operations
		// The settings page
		function admin_page( $mode = 'legacy' ) {
			global $current_user, $current_screen;
			if ( isset( $_POST['wordpress_reset_confirm'] ) && $_POST['wordpress_reset_confirm'] != 'reset' ) {
				echo '<div class="error fade"><p><strong>'.__('Invalid confirmation word. Please type the word \'reset\' in the confirmation field.','wpvdemo').'</strong></p></div>';
			} elseif ( isset( $_POST['_wpnonce'] ) ) {
				echo '<div class="error fade"><p><strong>'.__('Invalid nonce. Please try again.','wpvdemo').'</strong></p></div>';
			}
			$unified = FALSE;
			if ( 'unified' == $mode ) {
				$unified= TRUE;	
			}
			wp_enqueue_style('wpvdemo', WPVDEMO_RELPATH . '/css/basic.css', array(),WPVDEMO_VERSION);
		?>
		<?php if (! $unified ) {?>
		<div class="wrap">
		<?php } ?>
			<div id="icon-wpvdemo" class="icon32"><br /></div>
			<h2><?php _e('Reset','wpvdemo');?></h2>
			<h3><?php _e('Create a New WordPress Install by Resetting this Demo Site','wpvdemo');?></h3>
			<p><?php _e('Framework Installer requires a fresh website before you can install another demo site.','wpvdemo');?></p><p><strong><u><?php _e('Take note that this will delete all this website content, settings and database.','wpvdemo');?></u></strong></p>
			<?php $admin = get_user_by( 'login', 'admin' ); ?>
			<?php if ( ! isset( $admin->user_login ) || $admin->user_level < 10 ) : $user = $current_user; ?>
			<p><?php _e("The 'admin' user does not exist. The user ","wpvdemo");?>'<strong><?php echo esc_html( $user->user_login ); ?></strong>'<?php _e(" will be recreated with its <strong>current password</strong> and administrator privileges.","wpvdemo");?></p>
			<?php else : ?>
			<p><?php _e("The ","wpvdemo");?>'<strong>admin</strong>'<?php _e(" user exists and will be recreated with its ","wpvdemo");?><strong><?php _e("current password","wpvdemo");?></strong>.</p>
			<p><?php _e("You need to activate Framework Installer plugin after this reset.","wpvdemo");?></p>
			<?php endif; ?>
			<h3><?php _e('Reset','wpvdemo');?></h3>
			<p><?php _e("Type ","wpvdemo");?>'<strong><?php _e("reset","wpvdemo");?></strong>'<?php _e(" in the confirmation field to confirm the reset and then click the reset button:","wpvdemo");?></p>
			<form id="wordpress_reset_form" action="" method="post">
				<?php wp_nonce_field( 'wordpress_reset' ); ?>
				<input id="wordpress_reset" type="hidden" name="wordpress_reset" value="true" />
				<input id="wordpress_reset_confirm" type="text" name="wordpress_reset_confirm" value="" />
				<p class="submit">
					<input id="wordpress_reset_submit" style="width: 80px;" type="submit" name="Submit" class="button-primary" value="<?php _e('Reset','wpvdemo');?>" />
				</p>
			</form>
		<?php if (! $unified ) {?>	
		</div>
		<?php } ?>
		<?php
		}	
		
}
//WP reset is not available for multisite
if (!(is_multisite())) {
$WPVDemo_WordPress_Reset = new WPVDemo_WordPress_Reset();
}
endif;