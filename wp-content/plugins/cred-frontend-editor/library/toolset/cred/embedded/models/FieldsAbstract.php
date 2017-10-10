<?php

/**
 * Abstraction Class that includes common functions used by post Fields and user UserFields
 *
 * @since 1.9.2
 */
abstract class CRED_Fields_Abstract_Model {

	protected $wpdb = null;
	protected $custom_fields_option = '';

	function __construct() {
		global $wpdb;

		$this->wpdb = $wpdb;
	}

	abstract public function getPostTypeCustomFields( $post_type, $exclude_fields = array(), $show_private = true, $paged, $perpage = 10, $orderby = 'meta_key', $order = 'asc' );

	abstract public function getAllFields();

	abstract public function getCustomFields( $post_type = null, $force_all = false );

	abstract protected function get_custom_fields_option_name();
}
