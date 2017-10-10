<?php

class CRED_Translate_Skype_Command extends CRED_Translate_Field_Command_Base {

	public function execute() {
		$field_configuration = $this->field_configuration;

		//Note that generic skype fields are not repetitive and $field_configuration is a string...
		if ( isset( $this->field['cred_generic'] )
			&& $this->field['cred_generic']
		) {
			$field_configuration['skypename'] = isset( $field_configuration ) ? $field_configuration : '';
			$field_configuration['style'] = '';
		} else {
			if ( isset( $field_configuration ) ) {
				if ( isset( $this->field['data']['repetitive'] )
					&& $this->field['data']['repetitive'] == 0
					&& isset( $field_configuration[0] )
				) {
					$field_configuration = $field_configuration[0];
				}

				if ( isset( $this->field['data']['repetitive'] ) && $this->field['data']['repetitive'] == 1 && ! isset( $field_configuration[0] ) ) {
					$field_configuration = array( $field_configuration );
				}
			}
		}

		$field_configuration_exists = isset( $field_configuration ) && ! empty( $field_configuration );

		if ( $field_configuration_exists
			&& ( isset( $this->field['data']['repetitive'] )
				&& $this->field['data']['repetitive'] == 0 )
		) {
			$this->field_value = $field_configuration;
		} elseif ( $field_configuration_exists
			&& is_string( $field_configuration )
		) {
			$field_configuration = array( 'skypename' => $field_configuration, 'style' => '' );
			$this->field_value = $field_configuration;
		} else {
			$this->field_value = array( 'skypename' => '', 'style' => '' );
			$field_configuration = $this->field_value;
		}

		$this->field_attributes = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'edit_skype_text' => $this->cred_translate_field_factory->_formHelper->getLocalisedMessage( 'edit_skype_button' ),
			'value' => $this->field_value['skypename'],
			'_nonce' => wp_create_nonce( 'insert_skype_button' ),
		);

		return new CRED_Field_Translation_Result( $this->field_configuration, $this->field_type, $this->field_name, $this->field_value, $this->field_attributes, $this->field );
	}
}