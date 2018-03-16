<?php
/**
 * _s Theme Customizer
 *
 * @package emigma
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function emigma_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->default   = '#1FA67A';
}
add_action( 'customize_register', 'emigma_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function emigma_customize_preview_js() {
	wp_enqueue_script( 'emigma_customizer', get_template_directory_uri() . '/inc/js/customizer.js', array( 'customize-preview' ), '20150423', true );
}
add_action( 'customize_preview_init', 'emigma_customize_preview_js' );

/**
 * Options for Emigma Theme Customizer.
 */
function emigma_customizer( $wp_customize ) {

    /* Main option Settings Panel */
    $wp_customize->add_panel('emigma_main_options', array(
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => __('Theme Options', 'emigma'),
        'description' => __('Panel to update emigma theme options', 'emigma'), // Include html tags such as <p>.
        'priority' => 10 // Mixed with top-level-section hierarchy.
    ));

        /* Emigma Main Options */
        $wp_customize->add_section('emigma_slider_options', array(
            'title' => __('Slider options', 'emigma'),
            'priority' => 31,
            'panel' => 'emigma_main_options'
        ));
            $wp_customize->add_setting( 'emigma[emigma_slider_checkbox]', array(
                    'default' => 0,
                    'type' => 'option',
                    'sanitize_callback' => 'emigma_sanitize_checkbox',
            ) );
            $wp_customize->add_control( 'emigma[emigma_slider_checkbox]', array(
                    'label'	=> esc_html__( 'Check if you want to enable slider', 'emigma' ),
                    'section'	=> 'emigma_slider_options',
                    'priority'	=> 5,
                    'type'      => 'checkbox',
            ));

            // Pull all the categories into an array
            global $options_categories;
            $wp_customize->add_setting('emigma[emigma_slide_categories]', array(
                'default' => '',
                'type' => 'option',
                'capability' => 'edit_theme_options',
                'sanitize_callback' => 'emigma_sanitize_slidecat'
            ));
            $wp_customize->add_control('emigma[emigma_slide_categories]', array(
                'label' => __('Slider Category', 'emigma'),
                'section' => 'emigma_slider_options',
                'type'    => 'select',
                'description' => __('Select a category for the featured post slider', 'emigma'),
                'choices'    => $options_categories
            ));

            $wp_customize->add_setting('emigma[emigma_slide_number]', array(
                'default' => 3,
                'type' => 'option',
                'sanitize_callback' => 'emigma_sanitize_number'
            ));
            $wp_customize->add_control('emigma[emigma_slide_number]', array(
                'label' => __('Number of slide items', 'emigma'),
                'section' => 'emigma_slider_options',
                'description' => __('Enter the number of slide items', 'emigma'),
                'type' => 'text'
            ));

        $wp_customize->add_section('emigma_layout_options', array(
            'title' => __('Layout options', 'emigma'),
            'priority' => 31,
            'panel' => 'emigma_main_options'
        ));
            // Layout options
            global $site_layout;
            $wp_customize->add_setting('emigma[site_layout]', array(
                 'default' => 'side-pull-left',
                 'type' => 'option',
                 'sanitize_callback' => 'emigma_sanitize_layout'
            ));
            $wp_customize->add_control('emigma[site_layout]', array(
                 'label' => __('Website Layout Options', 'emigma'),
                 'section' => 'emigma_layout_options',
                 'type'    => 'select',
                 'description' => __('Choose between different layout options to be used as default', 'emigma'),
                 'choices'    => $site_layout
            ));

            $wp_customize->add_setting('emigma[element_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[element_color]', array(
                'label' => __('Element Color', 'emigma'),
                'description'   => __('Default used if no color is selected','emigma'),
                'section' => 'emigma_layout_options',
                'settings' => 'emigma[element_color]',
            )));

            $wp_customize->add_setting('emigma[element_color_hover]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[element_color_hover]', array(
                'label' => __('Element color on hover', 'emigma'),
                'description'   => __('Default used if no color is selected','emigma'),
                'section' => 'emigma_layout_options',
                'settings' => 'emigma[element_color_hover]',
            )));

         /* Emigma Action Options */
        $wp_customize->add_section('emigma_action_options', array(
            'title' => __('Action Button', 'emigma'),
            'priority' => 31,
            'panel' => 'emigma_main_options'
        ));
            $wp_customize->add_setting('emigma[w2f_cfa_text]', array(
                'default' => '',
                'type' => 'option',
                'sanitize_callback' => 'emigma_sanitize_strip_slashes'
            ));
            $wp_customize->add_control('emigma[w2f_cfa_text]', array(
                'label' => __('Call For Action Text', 'emigma'),
                'description' => sprintf(__('Enter the text for call for action section', 'emigma')),
                'section' => 'emigma_action_options',
                'type' => 'textarea'
            ));

            $wp_customize->add_setting('emigma[w2f_cfa_button]', array(
                'default' => '',
                'type' => 'option',
                'sanitize_callback' => 'emigma_sanitize_nohtml'
            ));
            $wp_customize->add_control('emigma[w2f_cfa_button]', array(
                'label' => __('Call For Action Button Title', 'emigma'),
                'section' => 'emigma_action_options',
                'description' => __('Enter the title for Call For Action button', 'emigma'),
                'type' => 'text'
            ));

            $wp_customize->add_setting('emigma[w2f_cfa_link]', array(
                'default' => '',
                'type' => 'option',
                'sanitize_callback' => 'esc_url_raw'
            ));
            $wp_customize->add_control('emigma[w2f_cfa_link]', array(
                'label' => __('CFA button link', 'emigma'),
                'section' => 'emigma_action_options',
                'description' => __('Enter the link for Call For Action button', 'emigma'),
                'type' => 'text'
            ));

            $wp_customize->add_setting('emigma[cfa_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[cfa_color]', array(
                'label' => __('Call For Action Text Color', 'emigma'),
                'description'   => __('Default used if no color is selected','emigma'),
                'section' => 'emigma_action_options',
            )));
            $wp_customize->add_setting('emigma[cfa_bg_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[cfa_bg_color]', array(
                'label' => __('Call For Action Background Color', 'emigma'),
                'description'   => __('Default used if no color is selected','emigma'),
                'section' => 'emigma_action_options',
            )));
            $wp_customize->add_setting('emigma[cfa_btn_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[cfa_btn_color]', array(
                'label' => __('Call For Action Button Border Color', 'emigma'),
                'description'   => __('Default used if no color is selected','emigma'),
                'section' => 'emigma_action_options',
            )));
            $wp_customize->add_setting('emigma[cfa_btn_txt_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[cfa_btn_txt_color]', array(
                'label' => __('Call For Action Button Text Color', 'emigma'),
                'description'   => __('Default used if no color is selected','emigma'),
                'section' => 'emigma_action_options',
            )));

        /* Emigma Typography Options */
        $wp_customize->add_section('emigma_typography_options', array(
            'title' => __('Typography', 'emigma'),
            'priority' => 31,
            'panel' => 'emigma_main_options'
        ));
        global $typography_defaults;
		global $typography_options;
		global $typography_types;
		// Typography Options
			foreach ($typography_types as $type => $element ) {
				// font
				$wp_customize->add_setting('emigma[main_'.$type.'_font]', array(
					'default' => $typography_defaults['face'],
					'type' => 'option',
                	'sanitize_callback' => 'emigma_sanitize_typo_face'
				));
				$wp_customize->add_control('emigma[main_'.$type.'_font]', array(
					 'label'    => sprintf(__('%s Font Family', 'emigma' ), $element),
					 'section'  => 'emigma_typography_options',
					 'type'     => 'select',
					 'choices'  => $typography_options['faces']
				));
			} // foreach
            $wp_customize->add_setting('emigma[main_body_typography][size]', array(
                'default' => $typography_defaults['size'],
                'type' => 'option',
                'sanitize_callback' => 'emigma_sanitize_typo_size'
            ));
            $wp_customize->add_control('emigma[main_body_typography][size]', array(
                'label' => __('Main Body Text Style', 'emigma'),
                //'description' => __('Used in p tags', 'emigma'),
                'section' => 'emigma_typography_options',
                'type'    => 'select',
                'choices'    => $typography_options['sizes']
            ));
            $wp_customize->add_setting('emigma[main_body_typography][face]', array(
                'default' => $typography_defaults['face'],
                'type' => 'option',
                'sanitize_callback' => 'emigma_sanitize_typo_face'
            ));
            $wp_customize->add_control('emigma[main_body_typography][face]', array(
                'section' => 'emigma_typography_options',
                'type'    => 'select',
                'choices'    => roman_google_fonts()
            ));
            $wp_customize->add_setting('emigma[main_body_typography][style]', array(
                'default' => $typography_defaults['style'],
                'type' => 'option',
                'sanitize_callback' => 'emigma_sanitize_typo_style'
            ));
            $wp_customize->add_control('emigma[main_body_typography][style]', array(
                'section' => 'emigma_typography_options',
                'type'    => 'select',
                'choices'    => $typography_options['styles']
            ));
            $wp_customize->add_setting('emigma[main_body_typography][color]', array(
                'default' => $typography_defaults['color'],
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[main_body_typography][color]', array(
                'section' => 'emigma_typography_options',
            )));
            /*$wp_customize->add_setting('emigma[heading_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[heading_color]', array(
                'label' => __('Heading Color', 'emigma'),
                'description'   => __('Color for all headings (h1-h6)','emigma'),
                'section' => 'emigma_typography_options',
            )));*/
            $wp_customize->add_setting('emigma[link_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[link_color]', array(
                'label' => __('Link Color', 'emigma'),
                'description'   => __('Default used if no color is selected','emigma'),
                'section' => 'emigma_typography_options',
            )));
            $wp_customize->add_setting('emigma[link_hover_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[link_hover_color]', array(
                'label' => __('Link:hover Color', 'emigma'),
                'description'   => __('Default used if no color is selected','emigma'),
                'section' => 'emigma_typography_options',
            )));

        /* Emigma Header Options */
        $wp_customize->add_section('emigma_header_options', array(
            'title' => __('Header', 'emigma'),
            'priority' => 31,
            'panel' => 'emigma_main_options'
        ));
		
			$wp_customize->add_setting('emigma[header_image]', array(
                'default' => 0,
                'type' => 'option',
                'sanitize_callback' => 'emigma_sanitize_checkbox'
            ));
            $wp_customize->add_control('emigma[header_image]', array(
                'label' => __('Header Featured image', 'emigma'),
                'description' => sprintf(__('Display Featured image in header', 'emigma')),
                'section' => 'emigma_header_options',
                'type' => 'checkbox',
            ));
            $wp_customize->add_setting('emigma[top_nav_bg_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[top_nav_bg_color]', array(
                'label' => __('Top nav background color', 'emigma'),
                'description'   => __('Default used if no color is selected','emigma'),
                'section' => 'emigma_header_options',
            )));
            $wp_customize->add_setting('emigma[top_nav_link_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[top_nav_link_color]', array(
                'label' => __('Top nav item color', 'emigma'),
                'description'   => __('Link color','emigma'),
                'section' => 'emigma_header_options',
            )));

            $wp_customize->add_setting('emigma[top_nav_dropdown_bg]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[top_nav_dropdown_bg]', array(
                'label' => __('Top nav dropdown background color', 'emigma'),
                'description'   => __('Background of dropdown item hover color','emigma'),
                'section' => 'emigma_header_options',
            )));

            $wp_customize->add_setting('emigma[top_nav_dropdown_item]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[top_nav_dropdown_item]', array(
                'label' => __('Top nav dropdown item color', 'emigma'),
                'description'   => __('Dropdown item color','emigma'),
                'section' => 'emigma_header_options',
            )));

        /* Emigma Footer Options */
        $wp_customize->add_section('emigma_footer_options', array(
            'title' => __('Footer', 'emigma'),
            'priority' => 31,
            'panel' => 'emigma_main_options'
        ));
            $wp_customize->add_setting('emigma[footer_widget_bg_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[footer_widget_bg_color]', array(
                'label' => __('Footer widget area background color', 'emigma'),
                'section' => 'emigma_footer_options',
            )));

            $wp_customize->add_setting('emigma[footer_bg_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[footer_bg_color]', array(
                'label' => __('Footer background color', 'emigma'),
                'section' => 'emigma_footer_options',
            )));

            $wp_customize->add_setting('emigma[footer_text_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[footer_text_color]', array(
                'label' => __('Footer text color', 'emigma'),
                'section' => 'emigma_footer_options',
            )));

            $wp_customize->add_setting('emigma[footer_link_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[footer_link_color]', array(
                'label' => __('Footer link color', 'emigma'),
                'section' => 'emigma_footer_options',
            )));
            
            $wp_customize->add_setting('emigma[custom_footer_page]', array(
                'default' => '',
                'type' => 'option',
            ));
            $wp_customize->add_control('emigma[custom_footer_page]', array(
                'label' => __('Footer page', 'emigma'),
                'description' => sprintf(__('Custom page link in footer, like Legal notice page', 'emigma')),
                'section' => 'emigma_footer_options',
                'type' => 'dropdown-pages'
            ));

            $wp_customize->add_setting('emigma[custom_footer_text]', array(
                'default' => '',
                'type' => 'option',
                'sanitize_callback' => 'emigma_sanitize_strip_slashes'
            ));
            $wp_customize->add_control('emigma[custom_footer_text]', array(
                'label' => __('Footer information', 'emigma'),
                'description' => sprintf(__('Copyright text in footer', 'emigma')),
                'section' => 'emigma_footer_options',
                'type' => 'textarea'
            ));

        /* Emigma Social Options */
        $wp_customize->add_section('emigma_social_options', array(
            'title' => __('Social', 'emigma'),
            'priority' => 31,
            'panel' => 'emigma_main_options'
        ));
            $wp_customize->add_setting('emigma[social_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[social_color]', array(
                'label' => __('Social icon color', 'emigma'),
                'description' => sprintf(__('Default used if no color is selected', 'emigma')),
                'section' => 'emigma_social_options',
            )));

            $wp_customize->add_setting('emigma[social_hover_color]', array(
                'default' => '',
                'type'  => 'option',
                'sanitize_callback' => 'emigma_sanitize_hexcolor'
            ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'emigma[social_hover_color]', array(
                'label' => __('Social Icon:hover Color', 'emigma'),
                'description' => sprintf(__('Default used if no color is selected', 'emigma')),
                'section' => 'emigma_social_options',
            )));

            $wp_customize->add_setting('emigma[footer_social]', array(
                'default' => 0,
                'type' => 'option',
                'sanitize_callback' => 'emigma_sanitize_checkbox'
            ));
            $wp_customize->add_control('emigma[footer_social]', array(
                'label' => __('Footer Social Icons', 'emigma'),
                'description' => sprintf(__('Check to show social icons in footer', 'emigma')),
                'section' => 'emigma_social_options',
                'type' => 'checkbox',
            ));

        /* Emigma Other Options */
        $wp_customize->add_section('emigma_other_options', array(
            'title' => __('Other', 'emigma'),
            'priority' => 31,
            'panel' => 'emigma_main_options'
        ));
            $wp_customize->add_setting('emigma[custom_css]', array(
                'default' => '',
                'type' => 'option',
                'sanitize_callback' => 'emigma_sanitize_strip_slashes'
            ));
            $wp_customize->add_control('emigma[custom_css]', array(
                'label' => __('Custom CSS', 'emigma'),
                'description' => sprintf(__('Additional CSS', 'emigma')),
                'section' => 'emigma_other_options',
                'type' => 'textarea'
            ));

       /* $wp_customize->add_section('emigma_important_links', array(
            'priority' => 5,
            'title' => __('Support and Documentation', 'emigma')
        ));
            $wp_customize->add_setting('emigma[imp_links]', array(
              'sanitize_callback' => 'esc_url_raw'
            ));
            $wp_customize->add_control(
            new emigma_Important_Links(
            $wp_customize,
                'emigma[imp_links]', array(
                'section' => 'emigma_important_links',
                'type' => 'emigma-important-links'
            )));*/

}
add_action( 'customize_register', 'emigma_customizer' );

/**
 * Sanitize checkbox for WordPress customizer
 */
function emigma_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
        return 1;
    } else {
        return '';
    }
}
/**
 * Adds sanitization callback function: colors
 * @package emigma
 */
function emigma_sanitize_hexcolor($color) {
    if ($unhashed = sanitize_hex_color_no_hash($color))
        return '#' . $unhashed;
    return $color;
}

/**
 * Adds sanitization callback function: Nohtml
 * @package emigma
 */
function emigma_sanitize_nohtml($input) {
    return wp_filter_nohtml_kses($input);
}

/**
 * Adds sanitization callback function: Number
 * @package emigma
 */
function emigma_sanitize_number($input) {
    if ( isset( $input ) && is_numeric( $input ) ) {
        return $input;
    }
}

/**
 * Adds sanitization callback function: Strip Slashes
 * @package emigma
 */
function emigma_sanitize_strip_slashes($input) {
    return wp_kses_stripslashes($input);
}

/**
 * Adds sanitization callback function: Slider Category
 * @package emigma
 */
function emigma_sanitize_slidecat( $input ) {
    global $options_categories;
    if ( array_key_exists( $input, $options_categories ) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * Adds sanitization callback function: Sidebar Layout
 * @package emigma
 */
function emigma_sanitize_layout( $input ) {
    global $site_layout;
    if ( array_key_exists( $input, $site_layout ) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * Adds sanitization callback function: Typography Size
 * @package emigma
 */
function emigma_sanitize_typo_size( $input ) {
    global $typography_options,$typography_defaults;
    if ( array_key_exists( $input, $typography_options['sizes'] ) ) {
        return $input;
    } else {
        return $typography_defaults['size'];
    }
}
/**
 * Adds sanitization callback function: Typography Face
 * @package emigma
 */
function emigma_sanitize_typo_face( $input ) {
    global $typography_options,$typography_defaults;
    if ( array_key_exists( $input, $typography_options['faces'] ) ) {
        return $input;
    } else {
        return $typography_defaults['face'];
    }
}
/**
 * Adds sanitization callback function: Typography Style
 * @package emigma
 */
function emigma_sanitize_typo_style( $input ) {
    global $typography_options,$typography_defaults;
    if ( array_key_exists( $input, $typography_options['styles'] ) ) {
        return $input;
    } else {
        return $typography_defaults['style'];
    }
}

/**
 * Add CSS for custom controls
 */
function emigma_customizer_custom_control_css() {
	?>
    <style>
        #customize-control-emigma-main_body_typography-size select, #customize-control-emigma-main_body_typography-face select,#customize-control-emigma-main_body_typography-style select { width: 60%; }
    </style><?php
}
add_action( 'customize_controls_print_styles', 'emigma_customizer_custom_control_css' );

if ( ! class_exists( 'WP_Customize_Control' ) )
    return NULL;
/**
 * Class to create a Emigma important links
 */
/*class emigma_Important_Links extends WP_Customize_Control {

   public $type = "emigma-important-links";

   public function render_content() {?>
        <div class="inside">
            <!--<div id="social-share">
              <div class="fb-like" data-href="<?php echo esc_url( 'https://www.facebook.com/colorlib' ); ?>" data-send="false" data-layout="button_count" data-width="90" data-show-faces="true"></div>
              <div class="tw-follow" ><a href="https://twitter.com/colorlib" class="twitter-follow-button" data-show-count="false">Follow @colorlib</a></div>
            </div>-->
            <p><b><a href="<?php echo esc_url( 'http://colorlib.com/wp/support/dazzling' ); ?>"><?php esc_html_e('Dazzling Documentation','emigma'); ?></a></b></p>
            <p><?php _e('The best way to contact us with <b>support questions</b> and <b>bug reports</b> is via','emigma') ?> <a href="<?php echo esc_url( 'http://colorlib.com/wp/forums' ); ?>"><?php esc_html_e('Colorlib support forum','emigma') ?></a>.</p>
<!--            <p><?php esc_html_e('If you like this theme, I\'d appreciate any of the following:','emigma') ?></p>
            <ul>
              <li><a class="button" href="<?php echo esc_url( 'http://wordpress.org/support/view/theme-reviews/dazzling?filter=5' ); ?>" title="<?php esc_attr_e('Rate this Theme', 'emigma'); ?>" target="_blank"><?php printf(esc_html__('Rate this Theme','emigma')); ?></a></li>
              <li><a class="button" href="<?php echo esc_url( 'http://www.facebook.com/colorlib' ); ?>" title="Like Colorlib on Facebook" target="_blank"><?php printf(esc_html__('Like on Facebook','emigma')); ?></a></li>
              <li><a class="button" href="<?php echo esc_url( 'http://twitter.com/colorlib/' ); ?>" title="Follow Colrolib on Twitter" target="_blank"><?php printf(esc_html__('Follow on Twitter','emigma')); ?></a></li>
            </ul>
-->        </div><?php
   }

}*/

/*
 * Custom Scripts
 */
add_action( 'customize_controls_print_footer_scripts', 'customizer_custom_scripts' );

function customizer_custom_scripts() { ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        /* This one shows/hides the an option when a checkbox is clicked. */
        jQuery('#customize-control-emigma-emigma_slide_categories, #customize-control-emigma-emigma_slide_number').hide();
        jQuery('#customize-control-emigma-emigma_slider_checkbox input').click(function() {
            jQuery('#customize-control-emigma-emigma_slide_categories, #customize-control-emigma-emigma_slide_number').fadeToggle(400);
        });

        if (jQuery('#customize-control-emigma-emigma_slider_checkbox input:checked').val() !== undefined) {
            jQuery('#customize-control-emigma-emigma_slide_categories, #customize-control-emigma-emigma_slide_number').show();
        }
    });
</script>
<style>
    li#accordion-section-emigma_important_links h3.accordion-section-title, li#accordion-section-emigma_important_links h3.accordion-section-title:focus { background-color: #00cc00 !important; color: #fff !important; }
    li#accordion-section-emigma_important_links h3.accordion-section-title:hover { background-color: #00b200 !important; color: #fff !important; }
    li#accordion-section-emigma_important_links h3.accordion-section-title:after { color: #fff !important; }
</style>
<?php
}
