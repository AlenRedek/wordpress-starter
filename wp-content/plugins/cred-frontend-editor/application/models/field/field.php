<?php

class CRED_Field extends CRED_Field_Abstract {

	/**
	 * CRED_Field constructor.
	 *
	 * @param array $atts
	 * @param CRED_Form_Rendering $credRenderingForm
	 * @param CRED_Helper $formHelper
	 * @param object $formData
	 * @param CRED_Translate_Field_Factory $translate_field_factory
	 */
	public function __construct( $atts, $credRenderingForm, $formHelper, $formData, $translate_field_factory ) {
		parent::__construct( $atts, $credRenderingForm, $formHelper, $formData, $translate_field_factory );
	}

	/**
	 * @return string
	 */
	public function get_field() {
		$formHelper = $this->_formHelper;
		$form = $this->_formData;
		$_fields = $form->getFields();
		$form_type = $_fields['form_settings']->form['type'];
		$post_type = $_fields['form_settings']->post['post_type'];

		$filtered_attributes = shortcode_atts( array(
			'class' => '',
			'post' => '',
			'field' => '',
			'value' => null,
			'urlparam' => '',
			'placeholder' => null,
			'escape' => false,
			'readonly' => false,
			'taxonomy' => null,
			'single_select' => null,
			'type' => null,
			'display' => null,
			'max_width' => null,
			'max_height' => null,
			'max_results' => null,
			'order' => null,
			'ordering' => null,
			'required' => false,
			//for parent select fields @deprecated since 1.9.1  use select_text
			'no_parent_text' => null,
			//Select default label for select/parent fields
			'select_text' => null,
			'validate_text' => $formHelper->getLocalisedMessage( 'field_required' ),
			'show_popular' => false,
			'output' => '',
		), $this->_atts );

		$class = ( isset( $filtered_attributes['class'] ) ) ? $filtered_attributes['class'] : '';
		$post = ( isset( $filtered_attributes['post'] ) ) ? $filtered_attributes['post'] : '';
		$field = ( isset( $filtered_attributes['field'] ) ) ? $filtered_attributes['field'] : '';
		$value = ( isset( $filtered_attributes['value'] ) ) ? $filtered_attributes['value'] : false;
		$urlparam = ( isset( $filtered_attributes['urlparam'] ) ) ? $filtered_attributes['urlparam'] : '';
		$placeholder = ( isset( $filtered_attributes['placeholder'] ) ) ? $filtered_attributes['placeholder'] : null;
		$escape = ( isset( $filtered_attributes['escape'] ) ) ? $filtered_attributes['escape'] : false;
		$readonly = ( isset( $filtered_attributes['readonly'] ) ) ? $filtered_attributes['readonly'] : false;
		$taxonomy = ( isset( $filtered_attributes['taxonomy'] ) ) ? $filtered_attributes['taxonomy'] : null;
		$single_select = ( isset( $filtered_attributes['single_select'] ) ) ? $filtered_attributes['single_select'] : null;
		$type = ( isset( $filtered_attributes['type'] ) ) ? $filtered_attributes['type'] : null;
		$display = ( isset( $filtered_attributes['display'] ) ) ? $filtered_attributes['display'] : null;
		$max_width = ( isset( $filtered_attributes['max_width'] ) ) ? $filtered_attributes['max_width'] : null;
		$max_height = ( isset( $filtered_attributes['max_height'] ) ) ? $filtered_attributes['max_height'] : null;
		$max_results = ( isset( $filtered_attributes['max_results'] ) ) ? $filtered_attributes['max_results'] : null;
		$order = ( isset( $filtered_attributes['order'] ) ) ? $filtered_attributes['order'] : null;
		$ordering = ( isset( $filtered_attributes['ordering'] ) ) ? $filtered_attributes['ordering'] : null;
		$required = ( isset( $filtered_attributes['required'] ) ) ? $filtered_attributes['required'] : false;
		$no_parent_text = ( isset( $filtered_attributes['no_parent_text'] ) ) ? $filtered_attributes['no_parent_text'] : null;
		$select_text = ( isset( $filtered_attributes['select_text'] ) ) ? $filtered_attributes['select_text'] : null;
		$validate_text = ( isset( $filtered_attributes['validate_text'] ) ) ? $filtered_attributes['validate_text'] : $formHelper->getLocalisedMessage( 'field_required' );
		$show_popular = ( isset( $filtered_attributes['show_popular'] ) ) ? $filtered_attributes['show_popular'] : false;
		$output = ( isset( $filtered_attributes['output'] ) ) ? $filtered_attributes['output'] : false;

		$field_name = $field;

		/*
		 * result of this use fix_cred_field_shortcode_value_attribute_by_single_quote
		 */
		$value = str_replace( "@_cred_rsq_@", "'", $value );

		if ( $field == 'form_messages' ) {
			$post_not_saved_singular = str_replace( "%PROBLEMS_UL_LIST", "", $formHelper->getLocalisedMessage( 'post_not_saved_singular' ) );
			$post_not_saved_plural = str_replace( "%PROBLEMS_UL_LIST", "", $formHelper->getLocalisedMessage( 'post_not_saved_plural' ) );

			return '<div id="wpt-form-message-' . $form->getForm()->ID . '"
              data-message-single="' . esc_attr( $post_not_saved_singular ) . '"
              data-message-plural="' . esc_attr( $post_not_saved_plural ) . '"
              style="display:none;" class="wpt-top-form-error wpt-form-error alert alert-danger"></div><!CRED_ERROR_MESSAGE!>';
		}

		$escape = false;
		$readonly = (bool) ( strtoupper( $readonly ) === 'TRUE' );
		$required = (bool) ( strtoupper( $required ) === 'TRUE' );

		if ( ! $taxonomy ) {

			$field_object = null;

			//Post Fields
			if (
				array_key_exists( 'post_fields', CRED_StaticClass::$out['fields'] ) &&
				is_array( CRED_StaticClass::$out['fields']['post_fields'] ) &&
				in_array( $field_name, array_keys( CRED_StaticClass::$out['fields']['post_fields'] ) )
			) {
				if ( $post != $post_type ) {
					return '';
				}

				$field = CRED_StaticClass::$out['fields']['post_fields'][ $field_name ];
				$name = $name_orig = $field['slug'];

				if ( ( ! isset( $placeholder ) || empty( $placeholder ) ) && isset( $field['data']['placeholder'] ) ) {
					$placeholder = $field['data']['placeholder'];
				}

				if ( isset( $field['plugin_type_prefix'] ) ) {
					$name = $field['plugin_type_prefix'] . $name;
				}

				$additional_attributes = array(
					'class' => $class,
					'output' => $output,
					'preset_value' => $value,
					'urlparam' => $urlparam,
				);

				if ( in_array( $field['type'], array( 'credimage', 'image', 'file', 'credfile' ) ) ) {
					$additional_attributes['is_tax'] = false;
					$additional_attributes['max_width'] = $max_width;
					$additional_attributes['max_height'] = $max_height;
				} else {
					$additional_attributes['value_escape'] = $escape;
					$additional_attributes['make_readonly'] = $readonly;
					$additional_attributes['placeholder'] = $placeholder;
					$additional_attributes['select_text'] = $select_text;
				}

				$field_object = $this->_translate_field_factory->cred_translate_field( $name, $field, $additional_attributes );

				/*
				 * check which fields are actually used in form
				 */
				CRED_StaticClass::$out['form_fields'][ $name_orig ] = $this->_translate_field_factory->cred_translate_form_name( $name, $field );
				CRED_StaticClass::$out['form_fields_info'][ $name_orig ] = array(
					'type' => $field['type'],
					'repetitive' => ( isset( $field['data']['repetitive'] ) && $field['data']['repetitive'] ),
					'plugin_type' => ( isset( $field['plugin_type'] ) ) ? $field['plugin_type'] : '',
					'name' => $name,
				);

				//Custom Fields
			} elseif (
				array_key_exists( 'custom_fields', CRED_StaticClass::$out['fields'] )
				&& is_array( CRED_StaticClass::$out['fields']['custom_fields'] )
				&& in_array( strtolower( $field_name ), array_keys( CRED_StaticClass::$out['fields']['custom_fields'] ) )
			) {
				if ( $post != $post_type ) {
					return '';
				}

				$field = CRED_StaticClass::$out['fields']['custom_fields'][ $field_name ];
				$name = $name_orig = $field['slug'];

				if ( isset( $field['plugin_type_prefix'] ) ) {
					$name = $field['plugin_type_prefix'] . $name;
				}

				$additional_attributes = array(
					'class' => $class,
					'output' => $output,
					'preset_value' => $value,
					'urlparam' => $urlparam,
				);

				if ( in_array( $field['type'], array( 'credimage', 'image', 'file', 'credfile' ) ) ) {
					$additional_attributes['is_tax'] = false;
					$additional_attributes['max_width'] = $max_width;
					$additional_attributes['max_height'] = $max_height;
				} else {
					$additional_attributes['value_escape'] = $escape;
					$additional_attributes['make_readonly'] = $readonly;
					$additional_attributes['placeholder'] = $placeholder;
				}
				$field_object = $this->_translate_field_factory->cred_translate_field( $name, $field, $additional_attributes );

				/*
				 * check which fields are actually used in form
				 */
				CRED_StaticClass::$out['form_fields'][ $name_orig ] = $this->_translate_field_factory->cred_translate_form_name( $name, $field );
				CRED_StaticClass::$out['form_fields_info'][ $name_orig ] = array(
					'type' => $field['type'],
					'repetitive' => ( isset( $field['data']['repetitive'] ) && $field['data']['repetitive'] ),
					'plugin_type' => ( isset( $field['plugin_type'] ) ) ? $field['plugin_type'] : '',
					'name' => $name,
				);

				//Parents Fields
			} elseif (
				array_key_exists( 'parents', CRED_StaticClass::$out['fields'] )
				&& is_array( CRED_StaticClass::$out['fields']['parents'] )
				&& in_array( $field_name, array_keys( CRED_StaticClass::$out['fields']['parents'] ) )
			) {
				$field = CRED_StaticClass::$out['fields']['parents'][ $field_name ];
				$name = $name_orig = $field_name;

				$potential_parents = CRED_Loader::get( 'MODEL/Fields' )->getPotentialParents( $field['data']['post_type'], $this->_cred_rendering->_post_id, $max_results, 'title', 'ASC' );
				$field['data']['options'] = array();

				$default_option = '';
				/*
				 * enable setting parent form url param
				 */
				if ( array_key_exists( 'parent_' . $field['data']['post_type'] . '_id', $_GET ) ) {
					$default_option = $_GET[ 'parent_' . $field['data']['post_type'] . '_id' ];
				}

				$field['data']['validate'] = array();
				if ( $required ) {
					$field['data']['validate'] = array(
						'required' => array( 'message' => $validate_text, 'active' => 1 ),
					);
				}

				foreach ( $potential_parents as $ii => $option ) {
					$option_id = (string) ( $option->ID );
					$field['data']['options'][ $option_id ] = array(
						'title' => $option->post_title,
						'value' => $option_id,
						'display_value' => $option_id,
					);
				}
				$field['data']['options']['default'] = $default_option;

				$additional_attributes = array(
					'preset_value' => $value,
					'urlparam' => $urlparam,
					'make_readonly' => $readonly,
					'max_width' => $max_width,
					'max_height' => $max_height,
					'class' => $class,
					'output' => $output,
					'select_text' => $select_text
				);
				$field_object = $this->_translate_field_factory->cred_translate_field( $name, $field, $additional_attributes );

				/*
				 * check which fields are actually used in form
				 */
				CRED_StaticClass::$out['form_fields'][ $name_orig ] = $this->_translate_field_factory->cred_translate_form_name( $name, $field );
				CRED_StaticClass::$out['form_fields_info'][ $name_orig ] = array(
					'type' => $field['type'],
					'repetitive' => ( isset( $field['data']['repetitive'] ) && $field['data']['repetitive'] ),
					'plugin_type' => ( isset( $field['plugin_type'] ) ) ? $field['plugin_type'] : '',
					'name' => $name,
				);

				//Form Fields/User Fields
			} elseif (
				( array_key_exists( 'form_fields', CRED_StaticClass::$out['fields'] )
					&& is_array( CRED_StaticClass::$out['fields']['form_fields'] )
					&& in_array( $field_name, array_keys( CRED_StaticClass::$out['fields']['form_fields'] ) ) )
				|| ( array_key_exists( 'user_fields', CRED_StaticClass::$out['fields'] )
					&& is_array( CRED_StaticClass::$out['fields']['user_fields'] )
					&& in_array( $field_name, array_keys( CRED_StaticClass::$out['fields']['user_fields'] ) ) )
			) {
				$field = CRED_StaticClass::$out['fields']['form_fields'][ $field_name ];
				$name = $name_orig = $field_name;

				$additional_attributes = array(
					'preset_value' => $value,
					'urlparam' => $urlparam,
					'make_readonly' => $readonly,
					'max_width' => $max_width,
					'max_height' => $max_height,
					'class' => $class,
					'output' => $output,
					'placeholder' => $placeholder,
				);
				$field_object = $this->_translate_field_factory->cred_translate_field( $name, $field, $additional_attributes );

				if ( $form_type == 'edit'
					&& ( $field_object['name'] == 'user_pass' ||
						$field_object['name'] == 'user_pass2' )
				) {
					if ( isset( $field_object['data']['validate'] )
						&& isset( $field_object['data']['validate']['required'] )
					) {
						unset( $field_object['data']['validate']['required'] );
					}
				}

				// check which fields are actually used in form
				CRED_StaticClass::$out['form_fields'][ $name_orig ] = $this->_translate_field_factory->cred_translate_form_name( $name, $field );
				CRED_StaticClass::$out['form_fields_info'][ $name_orig ] = array(
					'type' => $field['type'],
					'repetitive' => ( isset( $field['data']['repetitive'] ) && $field['data']['repetitive'] ),
					'plugin_type' => ( isset( $field['plugin_type'] ) ) ? $field['plugin_type'] : '',
					'name' => $name,
				);

				//Extra Fields
			} elseif (
				array_key_exists( 'extra_fields', CRED_StaticClass::$out['fields'] )
				&& is_array( CRED_StaticClass::$out['fields']['extra_fields'] )
				&& in_array( $field_name, array_keys( CRED_StaticClass::$out['fields']['extra_fields'] ) )
			) {
				$field = CRED_StaticClass::$out['fields']['extra_fields'][ $field_name ];
				$name = $name_orig = $field['slug'];

				$additional_attributes = array(
					'preset_value' => $value,
					'urlparam' => $urlparam,
					'make_readonly' => $readonly,
					'max_width' => $max_width,
					'max_height' => $max_height,
					'class' => $class,
					'output' => $output,
					'placeholder' => $placeholder,
				);
				$field_object = $this->_translate_field_factory->cred_translate_field( $name, $field, $additional_attributes );

				// check which fields are actually used in form
				CRED_StaticClass::$out['form_fields'][ $name_orig ] = $this->_translate_field_factory->cred_translate_form_name( $name, $field );
				CRED_StaticClass::$out['form_fields_info'][ $name_orig ] = array(
					'type' => $field['type'],
					'repetitive' => ( isset( $field['data']['repetitive'] ) && $field['data']['repetitive'] ),
					'plugin_type' => ( isset( $field['plugin_type'] ) ) ? $field['plugin_type'] : '',
					'name' => $name,
				);

				//Taxonomy Fields
			} elseif (
				array_key_exists( 'taxonomies', CRED_StaticClass::$out['fields'] )
				&& is_array( CRED_StaticClass::$out['fields']['taxonomies'] )
				&& in_array( $field_name, array_keys( CRED_StaticClass::$out['fields']['taxonomies'] ) )
			) {
				$field = CRED_StaticClass::$out['fields']['taxonomies'][ $field_name ];
				$name = $name_orig = $field['name'];

				$single_select = ( $single_select === 'true' );
				$additional_attributes = array(
					'preset_value' => $display,
					'is_tax' => true,
					'single_select' => $single_select,
					'show_popular' => $show_popular,
					'placeholder' => $placeholder,
					'class' => $class,
					'output' => $output,
				);
				$field_object = $this->_translate_field_factory->cred_translate_field( $name, $field, $additional_attributes );

				// check which fields are actually used in form
				CRED_StaticClass::$out['form_fields'][ $name_orig ] = $this->_translate_field_factory->cred_translate_form_name( $name, $field );
				CRED_StaticClass::$out['form_fields_info'][ $name_orig ] = array(
					'type' => $field['type'],
					'repetitive' => ( isset( $field['data']['repetitive'] ) && $field['data']['repetitive'] ),
					'plugin_type' => ( isset( $field['plugin_type'] ) ) ? $field['plugin_type'] : '',
					'name' => $name,
					'display' => $value,
				);
			}

			if ( $field_object ) {
				return $this->_cred_rendering->renderField( $field_object );
			} elseif ( current_user_can( 'manage_options' ) ) {
				return sprintf(
					'<p class="alert">%s</p>', sprintf(
						__( 'There is a problem with %s field. Please check CRED form.', 'wp-cred' ), $field
					)
				);
			}

			//is Taxonomy
		} else {

			//Taxonomy Fields
			if (
				array_key_exists( 'taxonomies', CRED_StaticClass::$out['fields'] ) &&
				is_array( CRED_StaticClass::$out['fields']['taxonomies'] ) &&
				in_array( $taxonomy, array_keys( CRED_StaticClass::$out['fields']['taxonomies'] ) ) &&
				in_array( $type, array( 'show_popular', 'add_new' ) )
			) {
				if ( // auxilliary field type matches taxonomy type
					( $type == 'show_popular' && ! CRED_StaticClass::$out['fields']['taxonomies'][ $taxonomy ]['hierarchical'] ) ||
					( $type == 'add_new' && CRED_StaticClass::$out['fields']['taxonomies'][ $taxonomy ]['hierarchical'] )
				) {
					// add a placeholder for the 'show_popular' or 'add_new' buttons.
					// the real buttons will be copied to this position via js
					// added data-label text from value shortcode attribute
					switch ( $type ) {
						case 'show_popular':
							return '<div class="js-taxonomy-button-placeholder" data-taxonomy="' . esc_attr( $taxonomy ) . '" data-label="' . esc_attr( $value ) . '" style="display:none"></div>';
						case 'add_new':
							return '<div class="js-taxonomy-hierarchical-button-placeholder" data-taxonomy="' . esc_attr( $taxonomy ) . '" data-label="' . esc_attr( $value ) . '" style="display:none"></div>';
					}
				}
			}

		}

		return '';
	}
}
