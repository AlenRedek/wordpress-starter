<?php

/**
 * Cred fields model
 * (get custom fields for post types)
 */
class CRED_Fields_Model extends CRED_Fields_Abstract_Model implements CRED_Singleton {

    public function __construct() {
        parent::__construct();
	    $this->custom_fields_option = $this->get_custom_fields_option_name();
    }

	/**
	 * @return string
	 */
	protected function get_custom_fields_option_name() {
		return '__CRED_CUSTOM_FIELDS';
	}

	/**
	 * @param bool $_custom
	 *
	 * @return array
	 */
    public function getTypesDefaultFields($_custom = false) {
        if (!$_custom) {
            return array(
                'checkbox' => array('title' => __('Checkbox', 'wp-cred'), 'type' => 'checkbox', 'parameters' => array('name' => true, 'default' => true)),
                'checkboxes' => array('title' => __('Checkboxes', 'wp-cred'), 'type' => 'checkboxes', 'parameters' => array('name' => true, 'options' => true, 'labels' => true, 'default' => true)),
                'select' => array('title' => __('Select', 'wp-cred'), 'type' => 'select', 'parameters' => array('name' => true, 'options' => true, 'labels' => true, 'default' => true)),
                'multiselect' => array('title' => __('Multi Select', 'wp-cred'), 'type' => 'multiselect', 'parameters' => array('name' => true, 'options' => true, 'labels' => true, 'default' => true)),
                'radio' => array('title' => __('Radio', 'wp-cred'), 'type' => 'radio', 'parameters' => array('name' => true, 'options' => true, 'labels' => true, 'default' => true)),
                'date' => array('title' => __('Date', 'wp-cred'), 'type' => 'date', 'parameters' => array('name' => true, 'default' => true, 'format' => true)),
                'email' => array('title' => __('Email', 'wp-cred'), 'type' => 'email', 'parameters' => array('name' => true, 'default' => true)),
                'url' => array('title' => __('URL', 'wp-cred'), 'type' => 'url', 'parameters' => array('name' => true, 'default' => true)),
                'skype' => array('title' => __('Skype', 'wp-cred'), 'type' => 'skype', 'parameters' => array('name' => true, 'skypename' => true, 'style' => true)),
                'phone' => array('title' => __('Phone', 'wp-cred'), 'type' => 'phone', 'parameters' => array('name' => true, 'default' => true)),
                'textfield' => array('title' => __('Single Line', 'wp-cred'), 'type' => 'textfield', 'parameters' => array('name' => true, 'default' => true)),
                'hidden' => array('title' => __('Hidden', 'wp-cred'), 'type' => 'hidden', 'parameters' => array('name' => true, 'default' => true)),
                'password' => array('title' => __('Password', 'wp-cred'), 'type' => 'password', 'parameters' => array('name' => true)),
                'textarea' => array('title' => __('Multiple Lines', 'wp-cred'), 'type' => 'textarea', 'parameters' => array('name' => true, 'default' => true)),
                'wysiwyg' => array('title' => __('WYSIWYG', 'wp-cred'), 'type' => 'wysiwyg', 'parameters' => array('name' => true, 'default' => true)),
                'numeric' => array('title' => __('Numeric', 'wp-cred'), 'type' => 'numeric', 'parameters' => array('name' => true, 'default' => true)),
                'integer' => array('title' => __('Integer', 'wp-cred'), 'type' => 'integer', 'parameters' => array('name' => true, 'default' => true)),
                'file' => array('title' => __('File', 'wp-cred'), 'type' => 'file', 'parameters' => array('name' => true)),
                'image' => array('title' => __('Image', 'wp-cred'), 'type' => 'image', 'parameters' => array('name' => true)),
                //support Types new audio and video field types consider than as file type
                'audio' => array('title' => __('Audio', 'wp-cred'), 'type' => 'audio', 'parameters' => array('name' => true)),
                'video' => array('title' => __('Video', 'wp-cred'), 'type' => 'video', 'parameters' => array('name' => true)),
                //https://icanlocalize.basecamphq.com/projects/7393061-toolset/todo_items/187372519/comments
                //support for colorpicker and embedded media field
                'colorpicker' => array('title' => __('Colorpicker', 'wp-cred'), 'type' => 'colorpicker', 'parameters' => array('name' => true)),
                'embed' => array('title' => __('Embedded Media', 'wp-cred'), 'type' => 'embed', 'parameters' => array('name' => true)),
            );
        } else {
            return array(
                'checkbox' => array('title' => __('Checkbox', 'wp-cred'), 'type' => 'checkbox', 'parameters' => array('name' => true, 'default' => true)),
                'checkboxes' => array('title' => __('Checkboxes', 'wp-cred'), 'type' => 'checkboxes', 'parameters' => array('name' => true, 'options' => true, 'labels' => true, 'default' => true)),
                'select' => array('title' => __('Select', 'wp-cred'), 'type' => 'select', 'parameters' => array('name' => true, 'options' => true, 'labels' => true, 'default' => true)),
                /* 'multiselect'=> array ( 'title'=>__('Multi Select','wp-cred'), 'type' => 'multiselect', 'parameters'=>array('name'=>true,'options'=>true,'labels'=>true,'default'=>true)), */
                'radio' => array('title' => __('Radio', 'wp-cred'), 'type' => 'radio', 'parameters' => array('name' => true, 'options' => true, 'labels' => true, 'default' => true)),
                'date' => array('title' => __('Date', 'wp-cred'), 'type' => 'date', 'parameters' => array('name' => true, 'default' => true, 'format' => true)),
                'email' => array('title' => __('Email', 'wp-cred'), 'type' => 'email', 'parameters' => array('name' => true, 'default' => true)),
                'url' => array('title' => __('URL', 'wp-cred'), 'type' => 'url', 'parameters' => array('name' => true, 'default' => true)),
                'skype' => array('title' => __('Skype', 'wp-cred'), 'type' => 'skype', 'parameters' => array('name' => true, 'skypename' => true, 'style' => true)),
                'phone' => array('title' => __('Phone', 'wp-cred'), 'type' => 'phone', 'parameters' => array('name' => true, 'default' => true)),
                'textfield' => array('title' => __('Single Line', 'wp-cred'), 'type' => 'textfield', 'parameters' => array('name' => true, 'default' => true)),
                'hidden' => array('title' => __('Hidden', 'wp-cred'), 'type' => 'hidden', 'parameters' => array('name' => true, 'default' => true)),
                'password' => array('title' => __('Password', 'wp-cred'), 'type' => 'password', 'parameters' => array('name' => true)),
                'textarea' => array('title' => __('Multiple Lines', 'wp-cred'), 'type' => 'textarea', 'parameters' => array('name' => true, 'default' => true)),
                'wysiwyg' => array('title' => __('WYSIWYG', 'wp-cred'), 'type' => 'wysiwyg', 'parameters' => array('name' => true, 'default' => true)),
                'numeric' => array('title' => __('Numeric', 'wp-cred'), 'type' => 'numeric', 'parameters' => array('name' => true, 'default' => true)),
                'integer' => array('title' => __('Integer', 'wp-cred'), 'type' => 'integer', 'parameters' => array('name' => true, 'default' => true)),
                'file' => array('title' => __('File', 'wp-cred'), 'type' => 'file', 'parameters' => array('name' => true)),
                'image' => array('title' => __('Image', 'wp-cred'), 'type' => 'image', 'parameters' => array('name' => true)),
                //support Types new audio and video field types consider than as file type
                'audio' => array('title' => __('Audio', 'wp-cred'), 'type' => 'audio', 'parameters' => array('name' => true)),
                'video' => array('title' => __('Video', 'wp-cred'), 'type' => 'video', 'parameters' => array('name' => true)),
                //support for colorpicker and embedded media field
                'colorpicker' => array('title' => __('Colorpicker', 'wp-cred'), 'type' => 'colorpicker', 'parameters' => array('name' => true)),
                'embed' => array('title' => __('Embedded Media', 'wp-cred'), 'type' => 'embed', 'parameters' => array('name' => true)),
            );
        }
    }

	/**
	 * @param string $text
	 * @param null $post_type
	 * @param int $limit
	 *
	 * @return mixed
	 */
	public function suggestPostsByTitle( $text, $post_type = null, $limit = 20 ) {
        $post_status = "('publish','private')";
        $not_in_post_types = "('view','view-template','attachment','revision','" . CRED_FORMS_CUSTOM_POST_NAME . "')";
        $text = esc_sql(cred_wrap_esc_like($text));

        $sql = "SELECT ID, post_title FROM {$this->wpdb->posts} WHERE post_title LIKE '%$text%' AND post_status IN $post_status";
        if ($post_type !== null) {
            if (is_array($post_type)) {
                $post_type_str = "";
                foreach ($post_type as $pt) {
                    $post_type_str .= "'$pt',";
                }
                $post_type_str = rtrim($post_type_str, ',');
                $sql.= " AND post_type in ($post_type_str)";
            } else {
	            $sql.= $this->wpdb->prepare(' AND post_type="%s"', $post_type);
            }

        }
        $limit = intval($limit);
	    if ( $limit > 0 ) {
		    $sql .= " LIMIT 0, $limit";
	    }

        $results = $this->wpdb->get_results($sql);

        return $results;
    }

	/**
	 * @param array $custom_exclude
	 *
	 * @return array
	 */
    public function getPostTypes($custom_exclude = array()) {
        $exclude = array('revision', 'attachment', 'nav_menu_item');
	    if ( ! empty( $custom_exclude ) ) {
		    $exclude = array_merge( $exclude, $custom_exclude );
	    }

        $post_types = get_post_types(array('public' => true, 'publicly_queryable' => true, 'show_ui' => true), 'names');
        $post_types = array_merge($post_types, get_post_types(array('public' => true, '_builtin' => true,), 'names', 'and'));
        $post_types = array_diff($post_types, $exclude);
        sort($post_types, SORT_STRING);
        $returned_post_types = array();
        foreach ($post_types as $pt) {
            $pto = get_post_type_object($pt);
            $returned_post_types[] = array('type' => $pt, 'name' => $pto->labels->name);
        }
        unset($post_types);
        return $returned_post_types;
    }

	/**
	 * @return array
	 */
	public function getPostTypesWithoutTypes() {
		$wpcf_custom_types = get_option( 'wpcf-custom-types', false );
		if ( $wpcf_custom_types ) {
			return $this->getPostTypes( array_keys( $wpcf_custom_types ) );
		} else {
			return $this->getPostTypes();
		}
	}

	/**
	 * @param string $post_type
	 * @param array $exclude_fields
	 * @param bool $show_private
	 * @param int $paged
	 * @param int $perpage
	 * @param string $orderby
	 * @param string $order
	 *
	 * @return mixed
	 */
	public function getPostTypeCustomFields( $post_type, $exclude_fields = array(), $show_private = true, $paged, $perpage = 10, $orderby = 'meta_key', $order = 'asc' ) {
        /*
          TODO:
          make search incremental to avoid large data issues
         */

        $exclude = array('_edit_last', '_edit_lock', '_wp_old_slug', '_thumbnail_id', '_wp_page_template',);
		if ( ! empty( $exclude_fields ) ) {
			$exclude = array_merge( $exclude, $exclude_fields );
		}

        $exclude = "'" . implode("','", $exclude) . "'"; //wrap in quotes

        if ($paged < 0) {
	        if ( $show_private ) {
		        $sql = $this->wpdb->prepare( "
                SELECT COUNT(DISTINCT(pm.meta_key)) FROM {$this->wpdb->postmeta} as pm, {$this->wpdb->posts} as p
                WHERE
                    pm.post_id=p.ID
                AND
                    p.post_type=%s
                AND pm.meta_key NOT IN ({$exclude})
                AND pm.meta_key NOT LIKE '%s' 
                ", $post_type, "wpcf-%" );
	        } else {
		        $sql = $this->wpdb->prepare( "
                SELECT COUNT(DISTINCT(pm.meta_key)) FROM {$this->wpdb->postmeta} as pm, {$this->wpdb->posts} as p
                WHERE
                    pm.post_id=p.ID
                AND
                    p.post_type=%s
                AND pm.meta_key NOT IN ({$exclude})

                AND pm.meta_key NOT LIKE '%s' 

                AND pm.meta_key NOT LIKE '%s'
                ", $post_type, "wpcf-%", "\_%" );
	        }

            return $this->wpdb->get_var($sql);
        }
        $paged = intval($paged);
        $perpage = intval($perpage);
        $paged--;
        $order = strtoupper($order);
		if ( ! in_array( $order, array( 'ASC', 'DESC' ) ) ) {
			$order = 'ASC';
		}
		if ( ! in_array( $orderby, array( 'meta_key' ) ) ) {
			$orderby = 'meta_key';
		}

		if ( $show_private ) {
			$sql = $this->wpdb->prepare( "
            SELECT DISTINCT(pm.meta_key) FROM {$this->wpdb->postmeta} as pm, {$this->wpdb->posts} as p
            WHERE
                pm.post_id=p.ID
            AND
                p.post_type=%s
            AND pm.meta_key NOT IN ({$exclude})
            AND pm.meta_key NOT LIKE '%s' 
            ORDER BY pm.{$orderby} {$order}
            LIMIT " . ( $paged * $perpage ) . ", " . $perpage
				, $post_type, "wpcf-%" );
		} else {
			$sql = $this->wpdb->prepare( "
            SELECT DISTINCT(pm.meta_key) FROM {$this->wpdb->postmeta} as pm, {$this->wpdb->posts} as p
            WHERE
                pm.post_id=p.ID
            AND
                p.post_type=%s
            AND pm.meta_key NOT IN ({$exclude})

            AND pm.meta_key NOT LIKE '%s' 

            AND pm.meta_key NOT LIKE '%s'
            ORDER BY pm.{$orderby} {$order}
            LIMIT " . ( $paged * $perpage ) . ", " . $perpage
				, $post_type, "wpcf-%", "\_%" );
		}

        $fields = $this->wpdb->get_col($sql);

        return $fields;
    }

	/**
	 * @param null $post_type
	 * @param bool $force_all
	 *
	 * @return array|mixed|void
	 */
	public function getCustomFields( $post_type = null, $force_all = false ) {
        $custom_field_options = ($post_type == 'cred-user-form') ? $this->_custom_user_fields_option : $this->custom_fields_option;
        $custom_fields = get_option($custom_field_options, false);

        if ($force_all) {
	        if ( $custom_fields && ! empty( $custom_fields ) ) {
		        return $custom_fields;
	        }
        }

	    if ( $post_type !== null ) {
		    if ( $custom_fields && ! empty( $custom_fields ) && isset( $custom_fields[ $post_type ] ) ) {
			    return $custom_fields[ $post_type ];
		    }

		    return array();
	    } else {
		    if ( $custom_fields && ! empty( $custom_fields ) ) {
			    return $custom_fields;
		    }

		    return array();
	    }
    }

	/**
	 * @param array $fields
	 * @param string $post_type
	 */
	public function saveCustomFields( $fields, $post_type ) {
		$custom_field_options = ( $post_type == CRED_USER_FORMS_CUSTOM_POST_NAME ) ? $this->_custom_user_fields_option : $this->custom_fields_option;
		update_option( $custom_field_options, $fields );
	}

	/**
	 * Create and init CRED custom field
	 *
	 * @param null $field_data
	 */
	public function setCustomField( $field_data = null ) {
		if ( $field_data !== null
			&& isset( $field_data['post_type'] )
		) {
			$post_type = $field_data['post_type'];
			$custom_field_options = ( $post_type == 'cred-user-form' ) ? $this->_custom_user_fields_option : $this->custom_fields_option;
			$field = array(
				'id' => $field_data['name'],
				'post_type' => $field_data['post_type'],
				'cred_custom' => true,
				'slug' => $field_data['name'],
				'type' => $field_data['type'],
				'name' => $field_data['name'],
				//added isset for back compatibility
				'default' => isset( $field_data['default'] ) ? $field_data['default'] : "",
				'data' => array(
					'repetitive' => 0,
					'validate' => array(
						'required' => array(
							'active' => isset( $field_data['required'] ),
							'value' => isset( $field_data['required'] ),
							'message' => __( 'This field is required', 'wp-cred' ),
						),
					),
					'validate_format' => isset( $field_data['validate_format'] ),
				),
			);

			if ( ! isset( $field_data['include_scaffold'] ) ) {
				$field['_cred_ignore'] = true;
			}

			switch ( $field_data['type'] ) {
				case 'checkbox':
					$field['data']['set_value'] = $field_data['default'];
					break;
				case 'checkboxes':
					$field['data']['options'] = array();
					if ( ! isset( $field_data['options']['value'] ) ) {
						$field_data['options'] = array( 'value' => array(), 'label' => array(), 'option_default' => array() );
					}
					foreach ( $field_data['options']['value'] as $ii => $option ) {
						$option_id = $option;
						$field['data']['options'][ $option_id ] = array(
							'title' => $field_data['options']['label'][ $ii ],
							'set_value' => $option,
						);
						if ( isset( $field_data['options']['option_default'] ) && in_array( $option, $field_data['options']['option_default'] ) ) {
							$field['data']['options'][ $option_id ]['checked'] = true;
						}
					}
					break;
				case 'date':
					$field['data']['validate']['date'] = array(
						'active' => isset( $field_data['validate_format'] ),
						'format' => 'mdy',
						'message' => __( 'Please enter a valid date', 'wp-cred' ),
					);
					break;
				case 'radio':
				case 'select':
					$field['data']['options'] = array();
					$default_option = 'no-default';
					if ( ! isset( $field_data['options']['value'] ) ) {
						$field_data['options'] = array( 'value' => array(), 'label' => array(), 'option_default' => '' );
					}
					foreach ( $field_data['options']['value'] as $ii => $option ) {
						$option_id = $option;
						//$option_id=$atts['field'].'_option_'.$ii;
						$field['data']['options'][ $option_id ] = array(
							'title' => $field_data['options']['label'][ $ii ],
							'value' => $option,
							'display_value' => $option,
						);
						if ( isset( $field_data['options']['option_default'] ) && ! empty( $field_data['options']['option_default'] ) && $field_data['options']['option_default'] == $option ) {
							$default_option = $option_id;
						}
					}
					$field['data']['options']['default'] = $default_option;
					break;
				case 'email':
					$field['data']['validate']['email'] = array(
						'active' => isset( $field_data['validate_format'] ),
						'message' => __( 'Please enter a valid email address', 'wp-cred' ),
					);
					break;
				case 'numeric':
					$field['data']['validate']['number'] = array(
						'active' => isset( $field_data['validate_format'] ),
						'message' => __( 'Please enter numeric data', 'wp-cred' ),
					);
					break;
				case 'integer':
					$field['data']['validate']['integer'] = array(
						'active' => isset( $field_data['validate_format'] ),
						'message' => __( 'Please enter integer data', 'wp-cred' ),
					);
					break;
				case 'embed':
				case 'url':
					$field['data']['validate']['url'] = array(
						'active' => isset( $field_data['validate_format'] ),
						'message' => __( 'Please enter a valid URL address', 'wp-cred' ),
					);
					break;
				case 'colorpicker':
					$field['data']['validate']['hexadecimal'] = array(
						'active' => isset( $field_data['validate_format'] ),
						'message' => __( 'Please use a valid hexadecimal value', 'wp-cred' ),
					);
					break;
				default:
					break;
			}
			$custom_fields = get_option( $custom_field_options );

			if ( $custom_fields && ! empty( $custom_fields ) && isset( $custom_fields[ $post_type ] ) ) {
				if ( is_array( $custom_fields[ $post_type ] ) ) {
					$custom_fields[ $post_type ][ $field_data['name'] ] = $field;
				} else {
					$custom_fields[ $post_type ] = array( $field_data['name'] => $field );
				}
			} else {
				if ( ! $custom_fields || empty( $custom_fields ) ) {
					$custom_fields = array();
				}
				$custom_fields[ $post_type ] = array( $field_data['name'] => $field );
			}
			update_option( $custom_field_options, $custom_fields );
		}
	}

	/**
	 * @param string $post_type
	 * @param string $field_name
	 * @param bool $types_format
	 *
	 * @return array|mixed
	 */
	public function getCustomField( $post_type, $field_name, $types_format = false ) {
		$custom_fields = $this->getCustomFields( $post_type );
		if ( isset( $custom_fields[ $field_name ] ) ) {
			if ( $types_format ) {
				return $custom_fields[ $field_name ];
			} else {
				$field_data = $custom_fields[ $field_name ];
				$field = array(
					'post_type' => $post_type,
					'name' => $field_name,
					'default' => $field_data['default'],
					'type' => $field_data['type'],
					'required' => isset( $field_data['data']['validate']['required']['active'] ) && $field_data['data']['validate']['required']['active'],
					'validate_format' => isset( $field_data['data']['validate_format'] ) && $field_data['data']['validate_format'],
					'include_scaffold' => (bool) ( ! isset( $field_data['_cred_ignore'] ) || ! $field_data['_cred_ignore'] ),
				);
				switch ( $field_data['type'] ) {
					case 'checkbox':
						$field['default'] = $field_data['data']['set_value'];
						break;
					case 'checkboxes':
						$field['options'] = array( 'value' => array(), 'label' => array(), 'option_default' => array() );
						foreach ( $field_data['data']['options'] as $ii => $option ) {
							$field['options']['value'][] = $option['set_value'];
							$field['options']['label'][] = $option['title'];
							if ( isset( $option['checked'] ) && $option['checked'] ) {
								$field['options']['option_default'][] = $option['set_value'];
							}
						}
						break;
					case 'radio':
					case 'select':
						$field['options'] = array( 'value' => array(), 'label' => array(), 'option_default' => '' );
						foreach ( $field_data['data']['options'] as $ii => $option ) {
							if ( $ii == 'default' ) {
								continue;
							}
							$field['options']['value'][] = $option['value'];
							$field['options']['label'][] = $option['title'];
							if ( isset( $field_data['data']['options']['default'] ) && $option['value'] == $field_data['data']['options']['default'] ) {
								$field['options']['option_default'] = $option['value'];
							}
						}
						break;
					default:
						break;
				}

				return $field;
			}
		} else {
			return array();
		}
	}

	/**
	 * @param string $post_type
	 * @param array $field_names
	 * @param string $action
	 */
	public function ignoreCustomFields( $post_type, $field_names, $action = 'ignore' ) {
		$custom_fields = $this->getCustomFields( $post_type, true );

		if ( ! $custom_fields
			|| ! isset( $custom_fields[ $post_type ] )
		) {
			return;
		}

		$custom_field_names = array_keys( $custom_fields[ $post_type ] );
		foreach ( $field_names as $field_name ) {

			if ( in_array( $field_name, $custom_field_names ) ) {
				switch ( $action ) {
					case 'ignore':
						$custom_fields[ $post_type ][ $field_name ]['_cred_ignore'] = true;
						break;
					case 'unignore':
						unset( $custom_fields[ $post_type ][ $field_name ]['_cred_ignore'] );
						break;
					case 'reset':
						unset( $custom_fields[ $post_type ][ $field_name ] );
						break;
				}
			}
		}
		$this->saveCustomFields( $custom_fields, $post_type );
	}

	/**
	 * Function responsible to create correct Post Fields array structure for CRED
	 * getting all the Types Fields and Types Controlled ones
	 * NOTE: Types controlled fields do not have prefix 'wpcf-'
	 *
	 * @param string $post_type
	 * @param bool $add_default
	 * @param null $localized_message_callback
	 *
	 * @return array
	 */
    public function getFields($post_type, $add_default = true, $localized_message_callback = null) {
	    if ( empty( $post_type )
		    || $post_type == null
		    || $post_type == false
	    ) {
		    return array();
	    }

	    $po = get_post_type_object( $post_type );
	    if ( ! $po ) {
		    return array();
	    }

        // ALL FIELDS
        $fields_all = array();
        // default post types
        $default_post_types = array('post', 'page');

        // POST FIELDS
        $fields = array();
        $groups = array();
        $post_type_orig = $post_type;
        $post_type = '%,' . $post_type . ',%';
        $isTypesActive = defined('WPCF_VERSION');
        $wpcf_custom_types = get_option('wpcf-custom-types');
        $isTypesPost = ($isTypesActive && $wpcf_custom_types) ? array_key_exists($post_type_orig, $wpcf_custom_types) : false;
        $credCustomFields = $this->getCustomFields($post_type_orig);
        $isCredCustomPost = (bool) (!empty($credCustomFields));

        // fetch custom fields for post type even if not created by types or default
        $groups = array();
        $fields = array();
        $groups_conditions = array();
        if ($isTypesActive) {
            if ((defined('TYPES_VERSION') && version_compare(TYPES_VERSION, '2.0', '<')) || !defined('TYPES_VERSION')) {
                $sql = 'SELECT post_id FROM ' . $this->wpdb->postmeta . '
                    WHERE meta_key="_wp_types_group_post_types"
                    AND (meta_value LIKE "%s" OR meta_value="all")
                    ORDER BY post_id ASC';
                $post_ids = $this->wpdb->get_col($this->wpdb->prepare($sql, $post_type));
            } else {
                $post_ids = apply_filters('types_filter_get_field_group_ids_by_post_type', array(), $post_type);
            }

	        $post_ids = implode( ',', $post_ids );

            if (empty($post_ids)) {
                $groups = array();
                $fields = array();
                $groups_conditions = array();
            } else {
                //Added AND post_status = "publish" in order to fix
                //https://icanlocalize.basecamphq.com/projects/7393061-toolset/todo_items/186130240/comments
                //####################################################
                $sql = 'SELECT P.post_title, M.meta_value FROM ' . $this->wpdb->posts . ' As P, ' . $this->wpdb->postmeta . ' As M
                WHERE P.ID IN (' . $post_ids . ')
                AND M.post_id=P.ID
                AND M.meta_key="_wp_types_group_fields"
                AND NOT (M.meta_value IS NULL)
                AND post_status = "publish"
                ORDER BY ID ASC';
                $group_fields = $this->wpdb->get_results($sql);
                $cc = count($group_fields);
                $fieldnames = array();
                for ($counter = 0; $counter < $cc; $counter++) {
                    $groups[$group_fields[$counter]->post_title] = trim($group_fields[$counter]->meta_value, ' ,');
                    $fieldnames[] = $group_fields[$counter]->meta_value;
                }
                unset($group_fields);
                $fieldnames = str_replace(',,', ',', trim(implode('', $fieldnames), ' ,'));
                $fields = get_option('wpcf-fields', array());
                $field_names = explode(',', $fieldnames);
	            if ( isset( $fields )
		            && ! empty( $fields ) ) {
		            foreach ( $fields as $key => $field ) {
			            if ( ! in_array( $key, $field_names ) ) {
				            unset( $fields[ $key ] );
			            }
		            }
	            }

                $plugin = 'types';
	            if ( isset( $fields )
		            && ! empty( $fields ) ) {
		            foreach ( $fields as $key => $field ) {
			            $fields[ $key ]['post_labels'] = $po->labels;
			            $fields[ $key ]['post_type'] = $post_type_orig;
			            $fields[ $key ]['plugin_type'] = $plugin;

			            $is_types_controlled = ( isset( $fields[ $key ]['data']['controlled'] ) && (bool) $fields[ $key ]['data']['controlled'] );
			            /*
						 * Types Controlled Fields do not have prefix wpcf-
						 */
			            $fields[ $key ]['plugin_type_prefix'] = ! $is_types_controlled ? 'wpcf-' : '';
		            }
	            }

                $sql_conditional = 'SELECT P.post_title, M.meta_value FROM ' . $this->wpdb->posts . ' As P, ' . $this->wpdb->postmeta . ' As M
                WHERE P.ID IN (' . $post_ids . ')
                AND M.post_id=P.ID
                AND M.meta_key="_wpcf_conditional_display"
                AND NOT (M.meta_value IS NULL)
                AND post_status = "publish"
                ORDER BY ID ASC';
	            $group_fields_conditional = $this->wpdb->get_results( $sql_conditional );
	            $group_fields_conditional_count = count( $group_fields_conditional );
	            for ( $counter = 0; $counter < $group_fields_conditional_count; $counter ++ ) {
		            $condition_data = maybe_unserialize( $group_fields_conditional[ $counter ]->meta_value );
		            if ( isset( $condition_data['custom'] ) && isset( $condition_data['custom_use'] ) && $condition_data['custom_use'] == 1 ) {
			            $groups_conditions[ $group_fields_conditional[ $counter ]->post_title ] = $condition_data['custom'];
		            } elseif ( isset( $condition_data['conditions'] ) && is_array( $condition_data['conditions'] ) && isset( $condition_data['relation'] ) ) {
			            $conditional_string_parts = array();
			            foreach ( $condition_data['conditions'] as $cond ) {
				            $conditional_string_parts[] = '($(' . $cond['field'] . ') ' . $cond['operation'] . ' \'' . $cond['value'] . '\')';
			            }
			            $conditional_string = implode( ' ' . $condition_data['relation'] . ' ', $conditional_string_parts );
			            $groups_conditions[ $group_fields_conditional[ $counter ]->post_title ] = $conditional_string;
		            }
	            }
            }
        }

        // add additional cred custom fields
        if ($isCredCustomPost) {
            $fields = array_merge($fields, $credCustomFields);
            foreach ($credCustomFields as $f => $fdata) {
	            if ( ! isset( $fdata['_cred_ignore'] )
		            || ! $fdata['_cred_ignore'] ) {
                    $groups['_CRED_Custom_Fields_'] = implode(',', array_keys($credCustomFields));
                    // has at least one field not ingored  from scaffold
                    break;
                }
            }
        }

        $post_fields = array();

        if ($add_default) {

            if ($localized_message_callback) {
                $message = call_user_func($localized_message_callback, 'field_required');
            } else {
                $message = __('This field is required', 'wp-cred');
            }
            $post_fields['post_title'] = array('post_type' => $post_type_orig, 'post_labels' => $po->labels, 'id' => 'post_title', 'wp_default' => true, 'slug' => 'post_title', 'type' => 'textfield', 'name' => esc_js(sprintf(__('%s Name', 'wp-cred'), $po->labels->singular_name)), 'description' => esc_js(sprintf(__('Title of %s (default)', 'wp-cred'), $po->labels->singular_name)), 'data' => array('repetitive' => 0, 'validate' => array('required' => array('active' => 1, 'value' => true, 'message' => $message)), 'conditional_display' => array(), 'disabled_by_type' => 0));
            $post_fields['post_content'] = array('post_type' => $post_type_orig, 'post_labels' => $po->labels, 'id' => 'post_content', 'wp_default' => true, 'slug' => 'post_content', 'type' => 'wysiwyg', 'name' => esc_js(sprintf(__('%s Description', 'wp-cred'), $po->labels->singular_name)), 'description' => esc_js(sprintf(__('Content of %s (default)', 'wp-cred'), $po->labels->singular_name)), 'data' => array(/* 'repetitive' => 0, 'validate' => array ( 'required' => array ( 'active' => 1, 'value' => true, 'message' => __('This field is required','wp-cred') ) ), 'conditional_display' => array ( ), 'disabled_by_type' => 0 */));
            $post_fields['post_excerpt'] = array('post_type' => $post_type_orig, 'post_labels' => $po->labels, 'id' => 'post_excerpt', 'wp_default' => true, 'slug' => 'post_excerpt', 'type' => 'textarea', 'name' => esc_js(sprintf(__('%s Excerpt', 'wp-cred'), $po->labels->singular_name)), 'description' => esc_js(sprintf(__('Excerpt of %s (default)', 'wp-cred'), $po->labels->singular_name)), 'data' => array(/* 'repetitive' => 0, 'validate' => array ( 'required' => array ( 'active' => 1, 'value' => true, 'message' => __('This field is required','wp-cred') ) ), 'conditional_display' => array ( ), 'disabled_by_type' => 0 */));

	        if (
	        post_type_supports( $post_type_orig, 'editor' )
	        ) {
		        $post_fields['post_content']['supports'] = true;
	        } else {
		        $post_fields['post_content']['supports'] = false;
	        }
	        if (
	        post_type_supports( $post_type_orig, 'excerpt' )
	        ) {
		        $post_fields['post_excerpt']['supports'] = true;
	        } else {
		        $post_fields['post_excerpt']['supports'] = false;
	        }
        }

        $parents = array();

        // add parent fields
        if ($isTypesActive) {
            if ($isTypesPost) {
	            if (
		            array_key_exists( 'post_relationship', $wpcf_custom_types[ $post_type_orig ] )
		            && array_key_exists( 'belongs', $wpcf_custom_types[ $post_type_orig ]['post_relationship'] )
	            ) {
		            // get parents defined via 'belongs' relationship
		            foreach ( $wpcf_custom_types[ $post_type_orig ]['post_relationship']['belongs'] as $ptype => $belong ) {
			            if ( $belong ) {
				            $_slug = '_wpcf_belongs_' . $ptype . '_id';
				            $parents[ $_slug ] = array( 'is_parent' => true, 'plugin_type' => 'types', 'data' => array( 'post_type' => $ptype, 'repetitive' => false, 'options' => array() ), 'id' => $_slug, 'slug' => $_slug, 'name' => esc_js( sprintf( __( '%s Parent', 'wp-cred' ), $ptype ) ), 'type' => 'select', 'description' => esc_js( sprintf( __( 'Set the %s Parent', 'wp-cred' ), $ptype ) ) );
			            }
		            }
	            }
                // get parents defined via 'has' relationship (reverse)
                foreach ($wpcf_custom_types as $ptype => $pdata) {
	                if (
		                isset( $pdata['post_relationship']['has'] )
		                && isset( $pdata['post_relationship']['has'][ $post_type_orig ] )
		                && $pdata['post_relationship']['has'][ $post_type_orig ]
	                ) {

                        $_slug = '_wpcf_belongs_' . $ptype . '_id';
                        $parents[$_slug] = array('is_parent' => true, 'plugin_type' => 'types', 'data' => array('post_type' => $ptype, 'repetitive' => false, 'options' => array()), 'id' => $_slug, 'slug' => $_slug, 'name' => esc_js(sprintf(__('%s Parent', 'wp-cred'), $ptype)), 'type' => 'select', 'description' => esc_js(sprintf(__('Set the %s Parent', 'wp-cred'), $ptype)));
                    }
                }
            } else {
                // get parents defined via 'has' relationship (reverse)
	            if ( isset( $wpcf_custom_types )
		            && is_array( $wpcf_custom_types )
		            && count( $wpcf_custom_types ) > 0
	            ) {
                    foreach ($wpcf_custom_types as $ptype => $pdata) {
	                    if (
		                    isset( $pdata['post_relationship']['has'] )
		                    && isset( $pdata['post_relationship']['has'][ $post_type_orig ] )
		                    && $pdata['post_relationship']['has'][ $post_type_orig ]
	                    ) {
                            $_slug = '_wpcf_belongs_' . $ptype . '_id';
                            $parents[$_slug] = array('is_parent' => true, 'plugin_type' => 'types', 'data' => array('post_type' => $ptype, 'repetitive' => false, 'options' => array()), 'id' => $_slug, 'slug' => $_slug, 'name' => esc_js(sprintf(__('%s Parent', 'wp-cred'), $ptype)), 'type' => 'select', 'description' => esc_js(sprintf(__('Set the %s Parent', 'wp-cred'), $ptype)));
                        }
                    }
                }
            }
        }

	    if (
		    post_type_supports( $post_type_orig, 'page-attributes' )
		    && is_post_type_hierarchical( $post_type_orig )
	    ) {
		    $_slug = 'post_parent';
		    $ptype = $post_type_orig;
		    $parents[ $_slug ] = array(
			    'is_parent' => true,
			    'data' => array(
				    'post_type' => $ptype,
				    'repetitive' => false,
				    'options' => array(),
			    ),
			    'id' => $_slug,
			    'slug' => $_slug,
			    'name' => esc_js( sprintf( __( '%s Parent', 'wp-cred' ), $ptype ) ),
			    'type' => 'select',
			    'description' => esc_js( sprintf( __( 'Set the %s Parent', 'wp-cred' ), $ptype ) ),
		    );
	    }

        // EXTRA FIELDS
        $extra_fields = array();
        $extra_fields['recaptcha'] = array('id' => 're_captcha', 'slug' => 'recaptcha', 'name' => esc_js(__('reCaptcha', 'wp-cred')), 'type' => 'recaptcha', 'cred_builtin' => true, 'description' => esc_js(__('Adds Image Captcha to your forms to prevent automatic submision by bots', 'wp-cred')));
        $setts = CRED_Loader::get('MODEL/Settings')->getSettings();
	    if (
		    ! isset( $setts['recaptcha']['public_key'] )
		    || ! isset( $setts['recaptcha']['private_key'] )
		    || empty( $setts['recaptcha']['public_key'] )
		    || empty( $setts['recaptcha']['private_key'] ) )
	    {
            // no keys set for API
            $extra_fields['recaptcha']['disabled'] = true;
            $extra_fields['recaptcha']['disabled_reason'] = sprintf('<a href="%s" target="_blank">%s</a> %s', CRED_CRED::$settingsPage, __('Get and Enter your API keys', 'wp-cred'), esc_js(__('to use the Captcha field.', 'wp-cred')));
        }

        // featured image field
        $extra_fields['_featured_image'] = array('id' => '_featured_image', 'slug' => '_featured_image', 'name' => esc_js(__('Featured Image', 'wp-cred')), 'type' => 'image', 'cred_builtin' => true, 'description' => esc_js(sprintf(__('Set %s Featured Image', 'wp-cred'), $po->labels->singular_name)));
	    if (
	    post_type_supports( $post_type_orig, 'thumbnail' )
	    ) {
		    $extra_fields['_featured_image']['supports'] = true;
	    } else {
		    $extra_fields['_featured_image']['supports'] = false;
	    }

        // BASIC FORM FIELDS
        $form_fields = array();
        $form_fields['form'] = array('id' => 'credform', 'name' => esc_js(__('Form Container', 'wp-cred')), 'slug' => 'credform', 'type' => 'credform', 'cred_builtin' => true, 'description' => esc_js(__('Form (required)', 'wp-cred', 'wp-cred')));
        $form_fields['form_submit'] = array('value' => __('Submit', 'wp-cred'), 'id' => 'form_submit', 'name' => esc_js(__('Form Submit', 'wp-cred')), 'slug' => 'form_submit', 'type' => 'form_submit', 'cred_builtin' => true, 'description' => esc_js(__('Form Submit Button', 'wp-cred')));
        $form_fields['form_messages'] = array('value' => '', 'id' => 'form_messages', 'name' => esc_js(__('Form Messages', 'wp-cred')), 'slug' => 'form_messages', 'type' => 'form_messages', 'cred_builtin' => true, 'description' => esc_js(__('Form Messages Container', 'wp-cred')));

        // TAXONOMIES FIELDS
        // get post type taxonomies
        $all_taxonomies = get_taxonomies(array(
            'public' => true,
            '_builtin' => false,
                ), 'objects', 'or');
        $taxonomies = array();
        foreach ($all_taxonomies as $tax) {
            $taxonomy = &$tax;
            //$taxonomy = get_taxonomy($tax);
            if (!in_array($post_type_orig, $taxonomy->object_type))
                continue;
            if (in_array($taxonomy->name, array('post_format')))
                continue;

            $key = $taxonomy->name;
            $taxonomies[$key] = array(
                'type' => ($taxonomy->hierarchical) ? 'taxonomy_hierarchical' : 'taxonomy_plain',
                'label' => $taxonomy->label,
                'name' => $taxonomy->name,
                'hierarchical' => $taxonomy->hierarchical
            );
            if ($taxonomy->hierarchical) {
                $taxonomies[$key]['all'] = $this->buildTerms(get_terms($taxonomy->name, array('hide_empty' => 0, 'fields' => 'all')));
            } else {
                $taxonomies[$key]['most_popular'] = $this->buildTerms(get_terms($taxonomy->name, array('number' => 8, 'order_by' => 'count', 'fields' => 'all')));
            }
        }
        unset($all_taxonomies);

        $fields_all['_post_data'] = $po->labels;
        $fields_all['groups'] = $groups;
        $fields_all['groups_conditions'] = $groups_conditions;
        $fields_all['form_fields'] = $form_fields;
        $fields_all['post_fields'] = $post_fields;
        $fields_all['custom_fields'] = $fields;
        $fields_all['taxonomies'] = $taxonomies;
        $fields_all['parents'] = $parents;
        $fields_all['extra_fields'] = $extra_fields;
        $fields_all['form_fields_count'] = count($form_fields);
        $fields_all['post_fields_count'] = count($post_fields);
        $fields_all['custom_fields_count'] = count($fields);
        $fields_all['taxonomies_count'] = count($taxonomies);
        $fields_all['parents_count'] = count($parents);
        $fields_all['extra_fields_count'] = count($extra_fields);

        return $fields_all;
    }

	/**
	 * @param string $post_type
	 * @param null $post_id
	 * @param int $results
	 * @param string $order
	 * @param string $ordering
	 *
	 * @return array|mixed|void
	 */
    public function getPotentialParents($post_type, $post_id = null, $results = 0, $order = 'date', $ordering = 'desc') {
        $post_status = "('publish','private')";

	    if ( $order != 'title' ) {
		    $order = 'post_date';
	    }

        $ordering = strtoupper($ordering);
        $ordering = in_array($ordering, array('ASC', 'DESC')) ? $ordering : 'DESC';

	    if ( ! is_numeric( $results ) || is_nan( $results ) ) {
		    $results = 0;
	    } else {
		    $results = intval( $results );
	    }

        $args = array(
            'posts_per_page' => ($results > 0) ? $results : -1,
            'numberposts' => ($results > 0) ? $results : -1,
            'offset' => 0,
            'category' => '',
            'orderby' => $order,
            'order' => $ordering,
            'include' => '',
            'exclude' => '',
            'meta_key' => '',
            'meta_value' => '',
            'post_type' => $post_type,
            'post_mime_type' => '',
            'post_parent' => '',
            'post_status' => apply_filters('cred_get_potential_parents_post_status', array('publish', 'private')),
            'suppress_filters' => false,
        );
        $parents = get_posts($args);

        $parents = apply_filters('wpml_cred_potential_parents_filter', $parents, $args);

        return $parents;
    }

	private function buildTerms( $obj_terms ) {
		$tax_terms = array();
		foreach ( $obj_terms as $term ) {
			$tax_terms[] = array(
				'name' => $term->name,
				'count' => $term->count,
				'parent' => $term->parent,
				'term_taxonomy_id' => $term->term_taxonomy_id,
				'term_id' => $term->term_id,
			);
		}

		return $tax_terms;
	}

    public function getAllFields() {
        return get_option('wpcf-fields');
    }

}
