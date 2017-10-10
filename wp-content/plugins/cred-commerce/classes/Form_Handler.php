<?php

/**
 *   CRED Commerce Form Handler
 *
 * */
final class CRED_Commerce_Form_Handler {

	private $plugin = null;
	private $form = null;
	private $model;
	private $_data = false;

	public function __construct() {

	}

	// dependency injection
	public function init( $plugin, $model ) {
		$this->model = $model;

		$this->plugin = $plugin;

		// add necessary hooks to manage the form submission
		//add_action('cred_save_data', array(&$this, 'onSaveData'), 10, 2);
		add_action( 'cred_submit_complete', array( &$this, 'onSubmitComplete' ), 1, 2 );
		add_action( 'cred_custom_success_action', array( &$this, 'onFormSuccessAction' ), 1, 4 );
		//add_action('cred_commerce_payment_complete', array(&$this, 'onPaymentComplete'), 1, 1 );
		$this->plugin->attach( '_cred_commerce_order_received', array( &$this, 'onOrderReceived' ) );
		$this->plugin->attach( '_cred_commerce_payment_failed', array( &$this, 'onPaymentFailed' ) );
		$this->plugin->attach( '_cred_commerce_payment_completed', array( &$this, 'onPaymentComplete' ) );
		$this->plugin->attach( '_cred_order_status_changed', array( &$this, 'onOrderChange' ) );
		$this->plugin->attach( '_cred_commerce_order_on_hold', array( &$this, 'onHold' ) );
		$this->plugin->attach( '_cred_commerce_payment_refunded', array( &$this, 'onRefund' ) );
		$this->plugin->attach( '_cred_commerce_payment_cancelled', array( &$this, 'onCancel' ) );
		$this->plugin->attach( '_cred_order_created', array( &$this, 'onOrderCreated' ) );
		$this->plugin->attach( '_cred_commerce_order_completed', array( &$this, 'onOrderComplete' ) );
	}

	public function getProducts() {
		return $this->plugin->getProducts();
	}

	public function getProduct( $id ) {
		return $this->plugin->getProduct( $id );
	}

	public function getRelativeProduct( $id ) {
		return $this->plugin->getRelativeProduct( $id );
	}

	public function getAbsoluteProduct( $id2 ) {
		return $this->plugin->getAbsoluteProduct( $id2 );
	}

	public function getCredData() {
		return $this->plugin->getCredData();
	}

	public function getNewProductLink() {
		return $this->plugin->getNewProductLink();
	}

	/**
	 * Change the post status according to the cred commerce form settings
	 *
	 * @param $post_id
	 * @param $form
	 * @param $data
	 * @param $is_user_form
	 *
	 * @return bool|int|WP_Error
	 */
	private function try_to_change_post_status($post_id, $form, $data, $is_user_form) {
		if ( $form->isCommerce
			&& ! $is_user_form
		) {
			$new_status = $data['new_status'];
			$previous_status = $data['previous_status'];
			$processed_post = array(
				'ID' => $post_id,
				'post_status' => $form->commerce[ 'order_' . $new_status ]['post_status'],
			);

			// Update the post into the database
			return wp_update_post( $processed_post );
		}

		return false;
	}

	/**
	 * @param int $post_id
	 * @param array $form_data
	 */
	public function onSubmitComplete( $post_id, $form_data ) {
		// get form meta data related to cred commerce
		$this->form   = $this->model->getForm( $form_data[ 'id' ], false );
		$is_user_form = ( get_post_type( $form_data[ 'id' ] ) == CRED_USER_FORMS_CUSTOM_POST_NAME ) ? true : false;

		if ( $this->form->isCommerce ) {

			do_action( 'cred_commerce_before_add_to_cart', $this->form->ID, $post_id );

			// clear cart if needed
			if ( $this->form->clearCart ) {
				$this->plugin->clearCart();
			}

			// add product to cart
			if ( 'post' == $this->form->associateProduct ) {
				if ( $is_user_form ) {
					if ( ! is_numeric( $post_id ) ) {
						$draft_users = CRED_StaticClass::get_draft_users();

						if ( isset( $draft_users[ $post_id ] ) ) {
							$tmp_user = $draft_users[ $post_id ];
							if ( isset( $tmp_user ) &&
							     isset( $tmp_user[ 'usermeta' ] ) &&
							     isset( $tmp_user[ 'usermeta' ][ $this->form->productField ] )
							) {
								$product = $tmp_user[ 'usermeta' ][ $this->form->productField ];
							}
						}
					}
				} else {
					$product = $this->model->getPostMeta( $post_id, $this->form->productField );
				}
			} else {
				if ( isset( $this->form->product ) ) {
					$product = $this->form->product;
				} else {
					// No product so return.
					return;
				}
			}

			// HOOKS API allow plugins to filter the product
			$product = apply_filters( 'cred_commerce_add_product_to_cart', $product, $this->form->ID, $post_id );

			$this->plugin->addTocart( $product, array(
				'cred_product_id' => $product,
				'cred_form_id'    => $this->form->ID,
				'cred_post_id'    => $post_id
			) );

			// HOOKS API
			do_action( 'cred_commerce_after_add_to_cart', $this->form->ID, $post_id );
		}
	}

	/**
	 *
	 * @param string $action
	 * @param int $post_id
	 * @param array $form_data
	 * @param bool $is_ajax (since cred 1.7)
	 */
	public function onFormSuccessAction( $action, $post_id, $form_data, $is_ajax = false ) {
		if ( $this->form->ID == $form_data['id']
			&& $this->form->isCommerce
		) {
			do_action( 'cred_commerce_form_action', $action, $this->form->ID, $post_id, $form_data, $is_ajax );

			$url = $this->plugin->getPageUri( $action );
			switch ( $action ) {
				case 'cart':
					if ( class_exists( "CRED_Generic_Response" ) ) {
						$cred_response = new CRED_Generic_Response( CRED_GENERIC_RESPONSE_RESULT_REDIRECT, $url, $is_ajax, $form_data );
						$cred_response->show();
					} else {
						wp_redirect( $url );
					}
					exit;
					break;
				case 'checkout':
					if ( class_exists( "CRED_Generic_Response" ) ) {
						$cred_response = new CRED_Generic_Response( CRED_GENERIC_RESPONSE_RESULT_REDIRECT, $url, $is_ajax, $form_data );
						$cred_response->show();
					} else {
						wp_redirect( $url );
					}
					exit;
					break;
			}
		}
	}

	/**
	 * @param $post_id
	 * @param $form_id
	 *
	 * @return mixed
	 */
	public function getCustomer( $post_id, $form_id ) {
		return $this->plugin->getCustomer( $post_id, $form_id );
	}

	/**
	 * Trigger notifications on order created (on checkout)
	 *
	 * @param $data
	 */
	public function onOrderCreated( $data ) {
		$this->plugin->detach( '_cred_order_created', array( &$this, 'onOrderCreated' ) );

		if ( isset( $data['cred_meta'] )
			&& $data['cred_meta']
		) {
			$model = CREDC_Loader::get( 'MODEL/Main' );

			foreach ( $data['cred_meta'] as $ii => $meta ) {
				if ( ! isset( $meta['cred_form_id'] ) ) {
					continue;
				}
				$form_id = $meta['cred_form_id'];
				$form_slug = '';
				$cred_form_post = get_post( $form_id );
				if ( $cred_form_post ) {
					$form_slug = $cred_form_post->post_name;
					$is_user_form = ( $cred_form_post->post_type == CRED_USER_FORMS_CUSTOM_POST_NAME );
				}
				$post_id = $meta['cred_post_id'];
				$form = $model->getForm( $form_id, true );

				if ( $form->isCommerce
					&& isset( $form->fields['notification'] )
				) {
					$can = false;
					$to_delete = -1;
					if ( $is_user_form ) {
						if ( isset( $form->fields['notification']->notifications ) ) {
							foreach ( $form->fields['notification']->notifications as $n => $notif ) {
								if ( $notif['event']['type'] == 'order_created' ) {
									$can = true;
									break;
								}
							}
						}
						if ( $can ) {
							$new_user_id = CRED_StaticClass::create_temporary_user_from_draft( $post_id, $data['order_id'] );

							if ( $new_user_id != -1 ) {
								$to_delete = $post_id;

								$post_id = $new_user_id;
								$meta['cred_post_id'] = $post_id;
								if ( ! function_exists( "delete_temporary_user" ) ) {

									function delete_temporary_user( $post_id ) {
										CRED_StaticClass::delete_temporary_user( $post_id );
									}

								}
								add_action( 'cred_after_send_notifications', 'delete_temporary_user', 10, 1 );
							}
						}
					}

					$this->_data = array(
						'order_id' => $data['order_id'],
						'cred_meta' => $meta,
					);

					add_filter( 'cred_custom_notification_event', array(&$this, 'notificationOrderCreatedEvent', ), 1, 4 );
					if ( method_exists( 'CRED_Notification_Manager', 'get_instance' ) ) {
						CRED_Notification_Manager::get_instance()->triggerNotifications( $post_id, array(
							'event' => 'order_created',
							'form_id' => $form_id,
							'notification' => $form->fields['notification'],
							'customer' => $this->getCustomer( $post_id, $form_id ),
						) );
					} else {
						CRED_Notification_Manager::triggerNotifications( $post_id, array(
							'event' => 'order_created',
							'form_id' => $form_id,
							'notification' => $form->fields['notification'],
							'customer' => $this->getCustomer( $post_id, $form_id ),
						) );
					}
					remove_filter( 'cred_custom_notification_event', array( &$this, 'notificationOrderCreatedEvent', ), 1, 4 );

					if ( $can
						&& $is_user_form
						&& $to_delete != -1
					) {
						CRED_StaticClass::delete_temporary_user( $post_id );
					}

					$this->_data = false;
				}
			}
		}
	}

	/**
	 * @param array $data
	 */
	public function onOrderChange( $data ) {
		$this->plugin->detach( '_cred_order_status_changed', array( &$this, 'onOrderChange' ) );

		// send notifications
		if ( ! isset( $data['new_status'] )
			|| ! in_array( $data['new_status'], array(
				'pending',
				'failed',
				'processing',
				'completed',
				'on-hold',
				'cancelled',
				'refunded',
			) )
		) {
			return;
		} // not spam with useless notifications ;)

		if ( isset( $data['cred_meta'] )
			&& $data['cred_meta']
		) {
			$model = CREDC_Loader::get( 'MODEL/Main' );

			foreach ( $data[ 'cred_meta' ] as $ii => $meta ) {
				if ( ! isset( $meta[ 'cred_form_id' ] ) ) {
					continue;
				}

				$form_slug = '';
				$form_id = $meta['cred_form_id'];
				$cred_form_post = get_post( $form_id );
				if ( $cred_form_post ) {
					$form_slug = $cred_form_post->post_name;
					$is_user_form = ( $cred_form_post->post_type == CRED_USER_FORMS_CUSTOM_POST_NAME );
				}
				$post_id = $meta['cred_post_id'];

				$form = $model->getForm( $form_id, true );

				if ( $form->isCommerce
					&& ! $is_user_form
				) {
					$result = $this->try_to_change_post_status($post_id, $form, $data, $is_user_form);
				}

				if ( $form->isCommerce
					&& isset( $form->fields['notification'] )
				) {
					$can_handle_temporary_user = false;
					$to_delete = -1;
					if ( $is_user_form
						&& $data['previous_status'] != $data['new_status']
					) {
						if ( isset( $form->fields['notification']->notifications ) ) {
							foreach ( $form->fields['notification']->notifications as $n => $notif ) {
								if ( $notif['event']['type'] == 'order_modified' ) {
									$can_handle_temporary_user = ( $notif['event']['order_status'] == $data['new_status'] );
									if ( $can_handle_temporary_user ) {
										break;
									}
								}
							}
						}
						if ( $can_handle_temporary_user ) {
							if ( $data['new_status'] == 'completed' ) {
								$order_id = isset( $data['order_id'] ) ? $data['order_id'] : $data['transaction_id'];

								$model = CRED_Loader::get( 'MODEL/UserForms' );
								$new_user_id = $model->publishTemporaryUser( $post_id, $order_id );
								$post_id = $new_user_id;
								$meta['cred_post_id'] = $post_id;
							} else {
								$to_delete = CRED_StaticClass::create_temporary_user_from_draft( $post_id, $data['order_id'] );

								if ( $to_delete != -1 ) {
									$post_id = $to_delete;
									$meta['cred_post_id'] = $post_id;
									if ( ! function_exists( "delete_temporary_user" ) ) {
										function delete_temporary_user( $post_id ) {
											CRED_StaticClass::delete_temporary_user( $post_id );
										}
									}
									add_action( 'cred_after_send_notifications', 'delete_temporary_user', 10, 1 );
								}
							}
						}

						if ( $data['previous_status'] != $data['new_status'] &&
							$data['new_status'] == 'cancelled' &&
							$this->form->commerce['order_cancelled']['post_status'] == 'delete'
						) {
							$model = CRED_Loader::get( 'MODEL/UserForms' );
							$new_user_id = $model->deleteTemporaryUser( $post_id );
						}
					}

					$this->_data = array(
						'order_id' => $data['order_id'],
						'previous_status' => $data['previous_status'],
						'new_status' => $data['new_status'],
						'cred_meta' => $meta,
					);

					add_filter( 'cred_custom_notification_event', array( &$this, 'notificationOrderEvent' ), 1, 4 );
					if ( method_exists( 'CRED_Notification_Manager', 'get_instance' ) ) {
						CRED_Notification_Manager::get_instance()->triggerNotifications( $post_id, array(
							'event' => 'order_modified',
							'form_id' => $form_id,
							'notification' => $form->fields['notification'],
						) );
					} else {
						CRED_Notification_Manager::triggerNotifications( $post_id, array(
							'event' => 'order_modified',
							'form_id' => $form_id,
							'notification' => $form->fields['notification'],
						) );
					}
					remove_filter( 'cred_custom_notification_event', array( &$this, 'notificationOrderEvent' ), 1, 4 );

					if ( $can_handle_temporary_user
						&& $is_user_form
						&& $to_delete != -1
						&& $data['new_status'] != 'completed'
					) {
						CRED_StaticClass::delete_temporary_user( $post_id );
					}

					$this->_data = false;
				}

				do_action( 'cred_commerce_after_send_notifications_form_' . $form_slug, $data );
				do_action( 'cred_commerce_after_send_notifications', $data );
			}
		}
	}

	/**
	 * @param array $data
	 */
	public function onOrderComplete( $data ) {
		$this->plugin->detach( '_cred_commerce_order_completed', array( &$this, 'onOrderComplete' ) );

		// get form data
		if ( isset( $data['extra_data'] )
			&& $data['extra_data']
			&& is_array( $data['extra_data'] )
		) {
			$update_cred_meta = false;
			// possible to be multiple commerce forms/posts on same order
			foreach ( $data['extra_data'] as &$cred_data ) {
				$model = CREDC_Loader::get( 'MODEL/Main' );

				// get form meta data related to cred commerce
				$this->form = isset( $cred_data['cred_form_id'] ) ? $this->model->getForm( $cred_data['cred_form_id'], false ) : false;

				$form_slug = '';
				if ( isset( $cred_data['cred_form_id'] ) ) {
					$cred_form_post = get_post( $cred_data['cred_form_id'] );

					if ( $cred_form_post ) {
						$form_slug = $cred_form_post->post_name;
						$is_user_form = ( $cred_form_post->post_type == CRED_USER_FORMS_CUSTOM_POST_NAME );
					}
				}
				$form_id = $cred_data['cred_form_id'];
				$form = $model->getForm( $form_id, true );
				$post_id = isset( $cred_data['cred_post_id'] ) ? $cred_data['cred_post_id'] : false;
				$user_id = isset( $data['user_id'] ) ? intval( $data['user_id'] ) : false;
				if ( $this->form
					&& $this->form->isCommerce
				) {
					if ( $is_user_form ) {

						//Move user from wp_options to wp_user $post_id is the counter
						$order_id = isset( $data['order_id'] ) ? $data['order_id'] : $data['transaction_id'];

						$model = CRED_Loader::get( 'MODEL/UserForms' );
						$new_user_id = $model->publishTemporaryUser( $post_id, $order_id );
						$post_id = $new_user_id;

						$cred_data['cred_post_id'] = $post_id;

						$this->_data = array(
							'order_id' => $order_id,
							'cred_meta' => $cred_data,
						);

						add_filter( 'cred_custom_notification_event', array( &$this, 'notificationOrderCompleteEvent', ), 1, 4 );
						if ( method_exists( 'CRED_Notification_Manager', 'get_instance' ) ) {
							CRED_Notification_Manager::get_instance()->triggerNotifications( $post_id, array(
								'event' => 'order_completed',
								'form_id' => $form_id,
								'notification' => $form->fields['notification'],
								'customer' => $this->getCustomer( $post_id, $form_id ),
							) );
						} else {
							CRED_Notification_Manager::triggerNotifications( $post_id, array(
								'event' => 'order_completed',
								'form_id' => $form_id,
								'notification' => $form->fields['notification'],
								'customer' => $this->getCustomer( $post_id, $form_id ),
							) );
						}
						remove_filter( 'cred_custom_notification_event', array( &$this, 'notificationOrderCompleteEvent', ), 1, 4 );

						$update_cred_meta = true;
					} else {
						if ( $post_id ) {
							// check if post actually exists !!
							$_post = get_post( $post_id );

							if ( $_post ) {
								$post_data = array();
								if ( $this->form->fixAuthor && $user_id ) {
									$post_data['post_author'] = $user_id;
								}
								if (
									isset( $this->form->commerce['order_completed'] ) &&
									isset( $this->form->commerce['order_completed']['post_status'] ) &&
									in_array( $this->form->commerce['order_completed']['post_status'], array(
										'draft',
										'pending',
										'private',
										'publish',
									) )
								) {
									$post_data['post_status'] = $this->form->commerce['order_completed']['post_status'];
								}
								if ( ! empty( $post_data ) ) {
									$post_data['ID'] = $post_id;
									wp_update_post( $post_data );
								}
							}
						}
					}

					// HOOKS API
					do_action( 'cred_commerce_after_order_completed_form_' . $form_slug, $data );
					do_action( 'cred_commerce_after_order_completed', $data );
				}
			}

			if ( $update_cred_meta ) {
				update_post_meta( $order_id, '_cred_meta', serialize( $data['extra_data'] ) );
			}
		}
	}

	/**
	 * @param array $data
	 */
	public function onOrderReceived( $data ) {
		// get form data
		if ( isset( $data[ 'extra_data' ] ) && $data[ 'extra_data' ] && is_array( $data[ 'extra_data' ] ) ) {
			// possible to be multiple commerce forms/posts on same order
			foreach ( $data[ 'extra_data' ] as $cred_data ) {
				// get form meta data related to cred commerce
				$this->form = isset( $cred_data[ 'cred_form_id' ] ) ? $this->model->getForm( $cred_data[ 'cred_form_id' ], false ) : false;
				if ( isset( $cred_data[ 'cred_form_id' ] ) ) {
					$cred_form_post = get_post( $cred_data[ 'cred_form_id' ] );
					if ( $cred_form_post ) {
						$is_user_form = ( $cred_form_post->post_type == CRED_USER_FORMS_CUSTOM_POST_NAME );
					}
				}
				$post_id = isset( $cred_data[ 'cred_post_id' ] ) ? $cred_data[ 'cred_post_id' ] : false;
				$user_id = isset( $data[ 'user_id' ] ) ? intval( $data[ 'user_id' ] ) : false;
				if ( $this->form && $this->form->isCommerce ) {
					if ( $is_user_form ) {
					} else {
						if ( $post_id ) {
							// check if post actually exists !!
							$_post = get_post( $post_id );
							//if (!$_post) return;

							if ( $_post ) {
								$postdata = array();
								if ( $this->form->fixAuthor && $user_id ) {
									$postdata[ 'post_author' ] = $user_id;
								}
								if (
									isset( $this->form->commerce[ 'order_pending' ] ) &&
									isset( $this->form->commerce[ 'order_pending' ][ 'post_status' ] ) &&
									in_array( $this->form->commerce[ 'order_pending' ][ 'post_status' ], array(
										'draft',
										'pending',
										'private',
										'publish'
									) )
								) {
									$postdata[ 'post_status' ] = $this->form->commerce[ 'order_pending' ][ 'post_status' ];
								}
								if ( ! empty( $postdata ) ) {
									$postdata[ 'ID' ] = $post_id;
									wp_update_post( $postdata );
								}
							}
						}
					}

					// HOOKS API
					do_action( 'cred_commerce_after_order_received', $data );
				}
			}
		}
	}

	/**
	 * @param array $data
	 */
	public function onPaymentFailed( $data ) {
		// get form data
		if ( isset( $data['extra_data'] )
			&& $data['extra_data']
			&& is_array( $data['extra_data'] )
		) {
			// possible to be multiple commerce forms/posts on same order
			foreach ( $data[ 'extra_data' ] as $cred_data ) {
				// get form meta data related to cred commerce
				$this->form = isset( $cred_data[ 'cred_form_id' ] ) ? $this->model->getForm( $cred_data[ 'cred_form_id' ], false ) : false;
				if ( isset( $cred_data[ 'cred_form_id' ] ) ) {
					$cred_form_post = get_post( $cred_data[ 'cred_form_id' ] );
					if ( $cred_form_post ) {
						$is_user_form = ( $cred_form_post->post_type == CRED_USER_FORMS_CUSTOM_POST_NAME );
					}
				}
				$post_id = isset( $cred_data[ 'cred_post_id' ] ) ? $cred_data[ 'cred_post_id' ] : false;
				$user_id = isset( $data[ 'user_id' ] ) ? intval( $data[ 'user_id' ] ) : false;
				if ( $this->form && $this->form->isCommerce ) {
					if ( !$is_user_form ) {
						if ( $post_id ) {
							// check if post actually exists !!
							$_post = get_post( $post_id );
							//if (!$_post) return;

							if ( $_post ) {
								$postdata = array();
								if ( $this->form->fixAuthor && $user_id ) {
									$postdata[ 'post_author' ] = $user_id;
								}
								if (
									isset( $this->form->commerce[ 'order_failed' ] ) &&
									isset( $this->form->commerce[ 'order_failed' ][ 'post_status' ] ) &&
									in_array( $this->form->commerce[ 'order_failed' ][ 'post_status' ], array(
										'draft',
										'pending',
										'private',
										'publish'
									) )
								) {
									$postdata[ 'post_status' ] = $this->form->commerce[ 'order_failed' ][ 'post_status' ];
								}
								if ( ! empty( $postdata ) ) {
									$postdata[ 'ID' ] = $post_id;
									wp_update_post( $postdata );
								}
							}
						}
					}
					// HOOKS API
					do_action( 'cred_commerce_after_payment_failed', $data );
				}
			}
		}
	}

	/**
	 * @param array $data
	 */
	public function onPaymentComplete( $data ) {
		// get form data
		if ( isset( $data[ 'extra_data' ] ) && $data[ 'extra_data' ] && is_array( $data[ 'extra_data' ] ) ) {
			// possible to be multiple commerce forms/posts on same order
			foreach ( $data[ 'extra_data' ] as $cred_data ) {
				// get form meta data related to cred commerce
				$this->form = isset( $cred_data[ 'cred_form_id' ] ) ? $this->model->getForm( $cred_data[ 'cred_form_id' ], false ) : false;
				if ( isset( $cred_data[ 'cred_form_id' ] ) ) {
					$cred_form_post = get_post( $cred_data[ 'cred_form_id' ] );
					if ( $cred_form_post ) {
						$is_user_form = ( $cred_form_post->post_type == CRED_USER_FORMS_CUSTOM_POST_NAME );
					}
				}
				$post_id = isset( $cred_data[ 'cred_post_id' ] ) ? $cred_data[ 'cred_post_id' ] : false;
				$user_id = isset( $data[ 'user_id' ] ) ? intval( $data[ 'user_id' ] ) : false;
				if ( $this->form && $this->form->isCommerce ) {
					if ( !$is_user_form ) {
						if ( $post_id ) {
							// check if post actually exists !!
							$_post = get_post( $post_id );
							//if (!$_post) return;

							if ( $_post ) {
								$post_data = array();
								if ( $this->form->fixAuthor && $user_id ) {
									$post_data[ 'post_author' ] = $user_id;
								}
								if (
									isset( $this->form->commerce[ 'order_processing' ] ) &&
									isset( $this->form->commerce[ 'order_processing' ][ 'post_status' ] ) &&
									in_array( $this->form->commerce[ 'order_processing' ][ 'post_status' ], array(
										'draft',
										'pending',
										'private',
										'publish'
									) )
								) {
									$post_data[ 'post_status' ] = $this->form->commerce[ 'order_processing' ][ 'post_status' ];
								}
								if ( ! empty( $post_data ) ) {
									$post_data[ 'ID' ] = $post_id;
									wp_update_post( $post_data );
								}
							}
						}
					}

					// HOOKS API
					do_action( 'cred_commerce_after_payment_completed', $data );
				}
			}
		}
	}

	/**
	 * @param array $data
	 */
	public function onHold( $data ) {
		// get form data
		if ( isset( $data[ 'extra_data' ] ) && $data[ 'extra_data' ] && is_array( $data[ 'extra_data' ] ) ) {
			// possible to be multiple commerce forms/posts on same order
			foreach ( $data[ 'extra_data' ] as $cred_data ) {
				// get form meta data related to cred commerce
				$this->form = isset( $cred_data[ 'cred_form_id' ] ) ? $this->model->getForm( $cred_data[ 'cred_form_id' ], false ) : false;
				if ( isset( $cred_data[ 'cred_form_id' ] ) ) {
					$cred_form_post = get_post( $cred_data[ 'cred_form_id' ] );
					if ( $cred_form_post ) {
						$is_user_form = ( $cred_form_post->post_type == CRED_USER_FORMS_CUSTOM_POST_NAME );
					}
				}
				$post_id = isset( $cred_data[ 'cred_post_id' ] ) ? $cred_data[ 'cred_post_id' ] : false;
				$user_id = isset( $data[ 'user_id' ] ) ? intval( $data[ 'user_id' ] ) : false;
				if ( $this->form && $this->form->isCommerce ) {
					if ( !$is_user_form ) {
						if ( $post_id ) {
							// check if post actually exists !!
							$_post = get_post( $post_id );
							//if (!$_post) return;

							if ( $_post ) {
								$postdata = array();
								if ( $this->form->fixAuthor && $user_id ) {
									$postdata[ 'post_author' ] = $user_id;
								}
								if (
									isset( $this->form->commerce[ 'order_on_hold' ] ) &&
									isset( $this->form->commerce[ 'order_on_hold' ][ 'post_status' ] ) &&
									in_array( $this->form->commerce[ 'order_on_hold' ][ 'post_status' ], array(
										'draft',
										'pending',
										'private',
										'publish'
									) )
								) {
									$postdata[ 'post_status' ] = $this->form->commerce[ 'order_on_hold' ][ 'post_status' ];
								}
								if ( ! empty( $postdata ) ) {
									$postdata[ 'ID' ] = $post_id;
									wp_update_post( $postdata );
								}
							}
						}
					}

					// HOOKS API
					do_action( 'cred_commerce_after_order_on_hold', $data );
				}
			}
		}
	}

	/**
	 * @param array $data
	 */
	public function onRefund( $data ) {
		// get form data
		if ( isset( $data[ 'extra_data' ] ) && $data[ 'extra_data' ] && is_array( $data[ 'extra_data' ] ) ) {
			// possible to be multiple commerce forms/posts on same order
			foreach ( $data[ 'extra_data' ] as $cred_data ) {
				// get form meta data related to cred commerce
				$this->form = isset( $cred_data[ 'cred_form_id' ] ) ? $this->model->getForm( $cred_data[ 'cred_form_id' ], false ) : false;

				if ( isset( $cred_data[ 'cred_form_id' ] ) ) {
					$cred_form_post = get_post( $cred_data[ 'cred_form_id' ] );
					if ( $cred_form_post ) {
						$is_user_form = ( $cred_form_post->post_type == CRED_USER_FORMS_CUSTOM_POST_NAME );
					}
				}

				$post_id = isset( $cred_data[ 'cred_post_id' ] ) ? $cred_data[ 'cred_post_id' ] : false;
				$user_id = isset( $data[ 'user_id' ] ) ? intval( $data[ 'user_id' ] ) : false;

				if ( $this->form && $this->form->isCommerce ) {
					if ( $is_user_form ) {

						if ( $this->form->commerce[ 'order_refunded' ][ 'post_status' ] == 'delete' ) {
							$model = CRED_Loader::get( 'MODEL/UserForms' );
							wp_delete_user( $post_id );
							$new_user_id = $model->deleteTemporaryUser( $post_id );
						}

						if ( $this->form->commerce[ 'order_refunded' ][ 'post_status' ] == 'draft' ) {
							wp_delete_user( $post_id );
						}

					} else {
						if ( $post_id ) {
							// check if post actually exists !!
							$_post = get_post( $post_id );

							if ( $_post ) {
								$post_data = array();
								if (
									isset( $this->form->commerce[ 'order_refunded' ] ) &&
									isset( $this->form->commerce[ 'order_refunded' ][ 'post_status' ] ) &&
									in_array( $this->form->commerce[ 'order_refunded' ][ 'post_status' ], array(
										'draft',
										'pending',
										'private',
										'publish'
									) )
								) {
									$post_data[ 'post_status' ] = $this->form->commerce[ 'order_refunded' ][ 'post_status' ];
								}
								if ( ! empty( $post_data ) ) {
									$post_data[ 'ID' ] = $post_id;
									wp_update_post( $post_data );
								}

								if (
									isset( $this->form->commerce[ 'order_refunded' ] ) &&
									isset( $this->form->commerce[ 'order_refunded' ][ 'post_status' ] ) &&
									'trash' == $this->form->commerce[ 'order_refunded' ][ 'post_status' ]
								) {
									// move to trash
									wp_delete_post( $post_id, false );
								} elseif (
									isset( $this->form->commerce[ 'order_refunded' ] ) &&
									isset( $this->form->commerce[ 'order_refunded' ][ 'post_status' ] ) &&
									'delete' == $this->form->commerce[ 'order_refunded' ][ 'post_status' ]
								) {
									// delete
									wp_delete_post( $post_id, true );
								}
							}
						}
					}
					// HOOKS API
					do_action( 'cred_commerce_after_payment_refunded', $data );
				}
			}
		}
	}

	/**
	 * @param array $data
	 */
	public function onCancel( $data ) {
		// get form data
		if ( isset( $data[ 'extra_data' ] ) && $data[ 'extra_data' ] && is_array( $data[ 'extra_data' ] ) ) {
			// possible to be multiple commerce forms/posts on same order
			foreach ( $data[ 'extra_data' ] as $cred_data ) {
				// get form meta data related to cred commerce
				$this->form = isset( $cred_data[ 'cred_form_id' ] ) ? $this->model->getForm( $cred_data[ 'cred_form_id' ], false ) : false;
				if ( isset( $cred_data[ 'cred_form_id' ] ) ) {
					$cred_form_post = get_post( $cred_data[ 'cred_form_id' ] );
					if ( $cred_form_post ) {
						$is_user_form = ( $cred_form_post->post_type == CRED_USER_FORMS_CUSTOM_POST_NAME );
					}
				}
				$post_id = isset( $cred_data[ 'cred_post_id' ] ) ? $cred_data[ 'cred_post_id' ] : false;
				$user_id = isset( $data[ 'user_id' ] ) ? intval( $data[ 'user_id' ] ) : false;
				if ( $this->form
					&& $this->form->isCommerce
				) {
					if ( $is_user_form ) {
						if ( $this->form->commerce[ 'order_cancelled' ][ 'post_status' ] == 'delete' ) {
							$model = CRED_Loader::get( 'MODEL/UserForms' );
							wp_delete_user( $post_id );
							$new_user_id = $model->deleteTemporaryUser( $post_id );
						}
						if ( $this->form->commerce[ 'order_refunded' ][ 'post_status' ] == 'draft' ) {
							wp_delete_user( $post_id );
						}
					} else {
						if ( $post_id ) {
							// check if post actually exists !!
							$_post = get_post( $post_id );

							if ( $_post ) {
								$post_data = array();
								if (
									isset( $this->form->commerce[ 'order_cancelled' ] )
									&& isset( $this->form->commerce[ 'order_cancelled' ][ 'post_status' ] )
									&& in_array( $this->form->commerce[ 'order_cancelled' ][ 'post_status' ], array(
										'draft',
										'pending',
										'private',
										'publish'
									) )
								) {
									$post_data[ 'post_status' ] = $this->form->commerce[ 'order_cancelled' ][ 'post_status' ];
								}
								if ( ! empty( $post_data ) ) {
									$post_data[ 'ID' ] = $post_id;
									wp_update_post( $post_data );
								}

								if (
									isset( $this->form->commerce[ 'order_cancelled' ] )
									&& isset( $this->form->commerce[ 'order_cancelled' ][ 'post_status' ] )
									&& 'trash' == $this->form->commerce[ 'order_cancelled' ][ 'post_status' ]
								) {
									// move to trash
									wp_delete_post( $post_id, false );
								} elseif (
									isset( $this->form->commerce[ 'order_cancelled' ] )
									&& isset( $this->form->commerce[ 'order_cancelled' ][ 'post_status' ] )
									&& 'delete' == $this->form->commerce[ 'order_cancelled' ][ 'post_status' ]
								) {
									// delete
									wp_delete_post( $post_id, true );
								}
							}
						}
					}
					// HOOKS API
					do_action( 'cred_commerce_after_payment_cancelled', $data );
				}
			}
		}
	}

	/**
	 * @param $result
	 * @param $notification
	 * @param $form_id
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function notificationOrderCreatedEvent( $result, $notification, $form_id, $post_id ) {
		if ( $this->_data ) {
			if (
				'order_created' == $notification[ 'event' ][ 'type' ] &&
				$form_id == $this->_data[ 'cred_meta' ][ 'cred_form_id' ] &&
				$post_id == $this->_data[ 'cred_meta' ][ 'cred_post_id' ]
			) {
				$result = true;
			}
		}

		return $result;
	}

	/**
	 * @param $result
	 * @param $notification
	 * @param $form_id
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function notificationOrderEvent( $result, $notification, $form_id, $post_id ) {
		if ( $this->_data ) {
			if (
				'order_modified' == $notification[ 'event' ][ 'type' ] &&
				$form_id == $this->_data[ 'cred_meta' ][ 'cred_form_id' ] &&
				$post_id == $this->_data[ 'cred_meta' ][ 'cred_post_id' ] &&
				isset( $notification[ 'event' ][ 'order_status' ] ) &&
				isset( $this->_data[ 'new_status' ] ) &&
				$this->_data[ 'new_status' ] == $notification[ 'event' ][ 'order_status' ]
			) {
				$result = true;
			}
		}

		return $result;
	}

	/**
	 * @param $result
	 * @param $notification
	 * @param $form_id
	 * @param $post_id
	 *
	 * @return bool
	 */
	public function notificationOrderCompleteEvent( $result, $notification, $form_id, $post_id ) {
		if ( $this->_data ) {
			if (
				'order_completed' == $notification[ 'event' ][ 'type' ] &&
				$form_id == $this->_data[ 'cred_meta' ][ 'cred_form_id' ] &&
				$post_id == $this->_data[ 'cred_meta' ][ 'cred_post_id' ]
			) {
				$result = true;
			}
		}

		return $result;
	}

}
