<?php
/*
******************************************************************************************************
	Main custom post type class to include and initialize in functions.php
******************************************************************************************************
*/
class CptClass{
    private $cpt = array();
	private $settingsCpt = array();
	private $cptArray = array();

	private $hideMenus = array();

    public function __construct($cpts = array(), $hide = array()){
		$this->cptArray = $cpts;
		$this->mh_createCustomMetaPosts();

		if(count($hide)){
			$this->hideMenus = $hide;
			add_action('admin_menu', array($this, 'mh_customMenuPageRemoving'), 99);
		}
    }

	private function mh_createCustomMetaPosts(){
		foreach($this->cptArray as $k=>$c){
			$this->settingsCpt[$k] = new $c();
			$this->cpt[$k] = new mhCptClass($this->settingsCpt[$k]->getSettings());
			$this->trans[$k] = $this->cpt[$k]->mh_getTrans();
		}
	}

    public function mh_getCustomFunctionValue($id, $postType, $key, $format = 'd.m.Y'){
		if(isset($this->settingsCpt[$postType]) && isset($this->settingsCpt[$postType]->getSettings()['columns'][$key]) && isset($this->settingsCpt[$postType]->getSettings()['columns'][$key]['function'])){
			return call_user_func($this->settingsCpt[$postType]->getSettings()['columns'][$key]['function'], $id, 'return', $format);
		}
		return false;
	}

	public function mh_getCptMetaString($cpt, $key, $val){
		if(isset($this->settingsCpt[$cpt])){
			return $this->settingsCpt[$cpt]->getMetaString($cpt, $key, $val);
		}else return $val;
	}

	public function mh_getCptMetaTitle($cpt, $key){
		if(isset($this->settingsCpt[$cpt])){
			return $this->settingsCpt[$cpt]->getMetaTitle($cpt, $key);
		}else return $key;
	}

    public function mh_customMenuPageRemoving(){
		foreach($this->hideMenus as $m){
			$t = remove_menu_page($m);
		}
	}

    public function mh_getDate($time, $add = 'l, ', $format = false){
		return date_i18n($add.($format ? $format : get_option('date_format')), $time);
	}

    static public function __mhAutoload($className){
		if(file_exists(get_stylesheet_directory().'/inc/'.$className.'.php')){
			require_once(get_stylesheet_directory().'/inc/'.$className.'.php');
		}else if(file_exists(get_stylesheet_directory().'/inc/cpt/'.$className.'.php')){
			require_once(get_stylesheet_directory().'/inc/cpt/'.$className.'.php');
		}
	}
}

spl_autoload_register('CptClass::__mhAutoload');


/*
******************************************************************************************************
	Custom class that creates custom post types from the settings in cpt individual files
******************************************************************************************************
*/
class mhCPTclass{
	private $tax;
	private $columns;
	private $metaBoxes;
	private $metaBoxes_cmb;
	private $changes;
	private $search;

	private $stringReplace;

	private $translateCode;
	private $slug;

	private $transCounter = array();

	public function __construct($args = array()){
		$search = false;

		if(isset($args['registration']) && isset($args['registration']['label']) && isset($args['registration']['slug'])){
			$this->slug = $args['registration']['slug'];
			$this->translateCode = (isset($args['registration']['translation_code'])) ? $args['registration']['translation_code'] : 'gledalisce';
			$this->mh_registerPostType($args['registration']);
		}

		if(isset($args['registration']['change'])){
			$this->changes = $args['registration']['change'];
			add_filter('wp_insert_post_data', array($this, 'mh_modifyPostInputs'), '99', 2);
		}

		if(isset($args['strings']) && count($args['strings'])){
			$this->stringReplace = $args['strings'];
			foreach($args['strings'] as $key=>$str){
				add_filter($key, array($this, 'mh_StringReplace_'.$key));
			}
		}

		if(isset($args['taxonomy'])){
			$this->tax = $args['taxonomy'];
			add_action('init', array($this, 'mh_customPostTaxonomy'));
			foreach($args['taxonomy'] as $t) if(isset($t['show_admin_column'])) $search = $search || $t['show_admin_column'];
		}

		if(isset($args['columns']) && isset($args['registration']['slug'])){
			$this->columns = $args['columns'];
			add_filter('manage_edit-'.$args['registration']['slug'].'_columns', array($this, 'mh_registerNewColumns'), 10);
			add_action('manage_'.$args['registration']['slug'].'_posts_custom_column', array($this, 'mh_customColumnsData'), 10, 2);

			foreach($args['columns'] as $c) if(isset($c['search'])) $search = $search || $c['search'];
		}

		if(isset($args['meta_boxes']) && isset($args['registration']['slug'])){
			$this->metaBoxes = $args['meta_boxes'];
			add_filter('rwmb_meta_boxes', array($this, 'mh_cmbMeta'));
		}

		if(isset($args['meta_boxes_cmb']) && isset($args['registration']['slug'])){
			$this->metaBoxes_cmb = $args['meta_boxes_cmb'];
			add_filter('cmb_meta_boxes', array($this, 'mh_cmbMeta_cmb'));
		}

		if(isset($args['remove_meta_boxes'])){
			$this->removeMetaBoxes = $args['remove_meta_boxes'];
			add_action('admin_menu', array($this, 'mh_removeMetaBoxes'));
		}

		if(isset($args['search'])){
			$this->search = $args['search'];
			$search = true;
			add_action('admin_head', array($this, 'mh_adminHideSearch'));
		}

		if($search){
			add_action('restrict_manage_posts', array($this, 'mh_adminPostsFilterSelect'));
			add_filter('parse_query', array($this, 'mh_adminPostsFilter'));

		}
	}

	public function mh_getTrans(){
		return $this->transCounter;
	}

	public function __call($method, $args){
		$screen = get_current_screen();
		if(method_exists($this, $method)){
			call_user_func_array($method, $args);
		}else{
			if($screen->post_type == $this->slug){
				switch($method){
					case 'mh_StringReplace_enter_title_here':
						$args[0] = $this->stringReplace['enter_title_here'];
					break;
					default:
						var_dump('Method '.$method.' does not exist for cpt '.$this->slug);
					break;
				}
			}
			return $args[0];
		}
	}

	private function mh_registerPostType($args){

		$single = (isset($args['label_single']) && $args['label_single'] != '') ? $args['label_single'] : $args['label'];
		$menuLabel = (isset($args['menu_label']) && $args['menu_label'] != '') ? $args['menu_label'] : $args['label'];
		$labels = array(
			'name'                => $args['label'],
			'singular_name'       => $single,
			'menu_name'           => $menuLabel,
			'parent_item_colon'   => sprintf(__('Parent %s', 'gledalisce'), $single),
			'all_items'           => sprintf(__('All %s', 'gledalisce'), $args['label']),
			'view_item'           => sprintf(__('View %s', 'gledalisce'), $single),
			'add_new_item'        => sprintf(__('Add new %s', 'gledalisce'), $single),
			'add_new'             => __('Add new', 'gledalisce'),
			'edit_item'           => sprintf(__('Edit ', 'gledalisce'), $single),
			'update_item'         => sprintf(__('Update %s', 'gledalisce'), $single),
			'search_items'        => sprintf(__('Find %s', 'gledalisce'), $single),
			'not_found'           => sprintf(__('No %s found', 'gledalisce'), $args['label']),
			'not_found_in_trash'  => __('Not found in trash', 'gledalisce'),
		);

		$capability = array();
		if(isset($args['capabilities']) && is_array($args['capabilities'])){
			$capability = $args['capabilities'];
		}else if(isset($args['capabilities']) && is_string($args['capabilities'])){
			$capability = array(
				'edit_post'          => $args['capabilities'],
				'read_post'          => $args['capabilities'],
				'delete_post'        => $args['capabilities'],
				'edit_posts'         => $args['capabilities'],
				'edit_others_posts'  => $args['capabilities'],
				'publish_posts'      => $args['capabilities'],
				'read_private_posts' => $args['capabilities'],
				'create_posts'       => $args['capabilities'],
			);
		}

		$data = array(
			'label'               => $args['slug'],
			'description'         => (isset($args['description']) && $args['description'] != '') ? $args['description'] : $args['label'],
			'labels'              => $labels,
			'supports'            => isset($args['supports']) ? $args['supports'] : array('title', 'thumbnail', 'editor', 'excerpt', 'page-attributes'),
			'taxonomies'          => isset($args['taxonomies']) ? $args['taxonomies'] : array(''),
			'hierarchical'        => isset($args['hierarchical']) ? : false,
			'public'              => isset($args['public']) ? $args['public'] : true,
			'show_ui'             => isset($args['show_ui']) ? $args['show_ui'] : true,
			'show_in_menu'        => isset($args['show_in_menu']) ? $args['show_in_menu'] : true,
			'show_in_nav_menus'   => isset($args['show_in_nav_menus']) ? $args['show_in_nav_menus'] : true,
			'show_in_admin_bar'   => isset($args['show_in_admin_bar']) ? $args['show_in_admin_bar'] : true,
			'menu_icon'			  => isset($args['menu_icon']) ? $args['menu_icon'] : 'dashicons-admin-generic',
			'can_export'          => isset($args['can_export']) ? $args['can_export'] : true,
			'has_archive'         => isset($args['has_archive']) ? $args['has_archive'] : true,
			'rewrite' 			  => isset($args['rewrite']) ? $args['rewrite'] : array('slug' => $args['slug']),
			'exclude_from_search' => isset($args['exclude_from_search']) ? $args['exclude_from_search'] : false,
			'publicly_queryable'  => isset($args['publicly_queryable']) ? $args['publicly_queryable'] : true,
			'capability_type'     => isset($args['capability_type']) ? $args['capability_type'] : 'post',
			'capabilities'		  => $capability,
		);
		register_post_type($args['slug'], $data);
	}

	public function mh_customPostTaxonomy(){
		foreach($this->tax as $t){
			if(isset($t['tax_name']) && isset($t['tax_post_types']) && isset($t['tax_label'])){
				register_taxonomy(
					$t['tax_name'],  																				//taxonomy name
					$t['tax_post_types'],   		 																//post type name
					array(
						'show_ui'			=> isset($t['show_ui']) ? $t['show_ui'] : true,
						'show_in_menu'		=> isset($t['show_in_menu']) ? $t['show_in_menu'] : true,
						'show_in_nav_menus' => isset($t['show_in_nav_menus']) ? $t['show_in_nav_menus'] : true,
						'show_admin_column' => isset($t['show_admin_column']) ? $t['show_admin_column'] : false,
						'hierarchical' 		=> isset($t['hierarchical']) ? $t['hierarchical'] : true,
						'label' 			=> $t['tax_label'],  													//display name
						'query_var' 		=> isset($t['query_var']) ? $t['query_var'] : true,
						'rewrite'			=> array(
							'slug' 			=> (isset($t['tax_slug']) ? $t['tax_slug'] : $t['tax_name']), 			//This controls the base slug that will display before each term
							'with_front' 	=> (isset($t['with_front']) ? $t['with_front'] : true )					//Don't display the category base before
						)
					)
				);
			}
		}
	}

	public function mh_registerNewColumns($columns){
		$cols = array();
		foreach($columns as $k=>$c){
			$cols[$k] = $c;
			if($k == 'title'){
				foreach($this->columns as $kk=>$col){
					$cols[$kk] = $col['label'];
				}
			}
		}
		return $cols;
	}

	public function mh_customColumnsData($column, $postid){
		if(isset($this->columns[$column])){
			$dFormat = (isset($this->columns[$column]['date_forat']) ? $this->columns[$column]['date_forat'] : 'd.m.Y');
			switch($this->columns[$column]['type']){
				case 'meta_key':
					if($meta = get_post_meta($postid, $this->columns[$column]['meta_key'], true)){
						$string = $this->mh_getMetaKeyValueFromArray($column, $meta);
						if($string != '' && isset($this->columns[$column]['length']) && $this->columns[$column]['length'] > 0){
							$string = substr($string, 0, $this->columns[$column]['length']).'...';
						}
						echo $string;
					}else echo '';
					break;
				case 'boolean':
					$yes = $this->columns[$column]['true_text'] ? $this->columns[$column]['true_text'] : __('Yes', 'gledalisce');
					$no = $this->columns[$column]['false_text'] ? $this->columns[$column]['false_text'] : __('No', 'gledalisce');
					if($meta = get_post_meta($postid, $this->columns[$column]['meta_key'], true)){
						echo $meta ? $yes : $no;
					}else echo $no;
					break;
				case 'meta_key_date':
					if($meta = get_post_meta($postid, $this->columns[$column]['meta_key'], true)){
						echo date($dFormat, strtotime($this->mh_getMetaKeyValueFromArray($column, $meta)));
					}else echo '';
					break;
				case 'multy_meta_date':
					$dates = array();
					foreach($this->columns[$column]['meta_key'] as $mk){
						$dates[] = date($dFormat, strtotime($this->mh_getMetaKeyValueFromArray($column, get_post_meta($postid, $mk, true))));
					}
					if($dates) echo implode(' - ', $dates);
					else echo '';
					break;
				case 'multy_meta_value':
					$vals = array();
					foreach($this->columns[$column]['meta_key'] as $mk){
						$vals[] = $this->mh_getMetaKeyValueFromArray($column, get_post_meta($postid, $mk, true));
					}
					if($vals) echo implode(' - ', $vals);
					else echo '';
					break;
				case 'post':
					if($ids = get_post_meta($postid, $this->columns[$column]['meta_key'])){
						$i = 0;
						foreach($ids as $id){
							echo get_the_title($id);
							$i++;
							if($i < count($ids)) echo ', ';
						}
					}else echo '';
					break;
				case 'meta_acf':
					if(function_exists('get_field')){
						if($ma = get_field($this->columns[$column]['meta_key'], $postid)){
							echo $ma;
						}
					}
					break;
				case 'post_acf':
					if(function_exists('get_field')){
						$ps = get_field($this->columns[$column]['meta_key'], $postid);
						if($this->columns[$column]['single']){
							echo $ps->post_title;
						}else{
							$i = 0;
							foreach($ps as $p){
								echo $p->post_title;
								$i++;
								if($i < count($ps)) echo ', ';
							}
						}
					}else echo '';
					break;
				case 'taxonomy_acf':
					if(function_exists('get_field')){
						$ps = get_field($this->columns[$column]['meta_key'], $postid);
						if($this->columns[$column]['single']){
							echo $ps->name;
						}else{
							$i = 0;
							foreach($ps as $p){
								echo $p->name;
								$i++;
								if($i < count($ps)) echo ', ';
							}
						}
					}else echo '';
					break;
				case 'taxonomy':
					if($terms = wp_get_post_terms($postid, $this->columns[$column]['tax_key'])){
						$i = 0;
						foreach($terms as $t){
							echo $t->name;
							$i++;
							if($i < count($terms)) echo ' ,';
						}
					}else echo '';
					break;
				case 'custom_function':
					if(isset($this->columns[$column]['function']) && $this->columns[$column]['function']){
						call_user_func($this->columns[$column]['function'], $postid);
					}
					break;
			}
		}
	}

	private function mh_getMetaKeyValueFromArray($id, $key){
		foreach($this->metaBoxes as $mb){
			foreach($mb['fields'] as $m){
				if($m['id'] == $id && isset($m['options']) && isset($m['options'][$key])) return $m['options'][$key];
			}
		}
		return $key;
	}

	public function mh_cmbMeta($meta_boxes){
		$prefix = '_'.$this->slug.'_';
		foreach($this->metaBoxes as $mb){
			$m = array();
			$m['id'] = $mb['id'];
			$m['title'] = $mb['title'];
			$m['pages'] = $this->slug;
			$m['content'] = isset($mb['content']) ? $mb['content'] : 'normal';
			$m['priority'] = isset($mb['priority']) ? $mb['priority'] : 'high';
			$m['autosave'] = isset($mb['autoset']) ? $mb['autoset'] : false;
			$fields = array();
			foreach($mb['fields'] as $fi){
				$f = array();
				$f['name'] = $fi['name'];
				$f['id'] = $prefix.$fi['id'];
				if(isset($fi['desc'])) $f['desc'] = $fi['desc'];
				$f['type'] = $fi['type'];
				if(isset($fi['std'])) $f['std'] = $fi['std'];
				$f['clone'] = isset($fi['clone']) ? $fi['clone'] : false;
				if(isset($fi['options']) && $fi['type'] != 'taxonomy'){
					foreach($fi['options'] as $k=>$d) $f['options'][$k] = $d;
				}else if(isset($fi['options'])){
					$f['options'] = $fi['options'];
				}
				if(isset($fi['post_type']) && $fi['type'] == 'post') $f['post_type'] = $fi['post_type'];
				if(isset($fi['field_type']) && $fi['type'] == 'post') $f['field_type'] = $fi['field_type'];
				if(isset($fi['query_args']) && $fi['type'] == 'post') $f['query_args'] = $fi['query_vars'];
				if(isset($fi['min'])) $f['min'] = $fi['min'];
				if(isset($fi['max'])) $f['max'] = $fi['max'];
				if(isset($fi['step'])) $f['step'] = $fi['step'];
				if(isset($fi['multiple'])) $f['multiple'] = $fi['multiple'];
				if(isset($fi['placeholder'])) $f['placeholder'] = $fi['placeholder'];
				if(isset($fi['size'])) $f['size'] = $fi['size'];
				if(isset($fi['cols'])) $f['cols'] = $fi['cols'];
				if(isset($fi['rows'])) $f['rows'] = $fi['rows'];
				if(isset($fi['max_file_uploads'])) $f['max_file_uploads'] = $fi['max_file_uploads'];
				if(isset($fi['mime_type'])) $f['mime_type'] = $fi['mime_type'];
				if(isset($fi['js_options'])) $f['js_options'] = $fi['js_options'];
				if(isset($fi['std'])) $f['std'] = $fi['std'];
				$fields[] = $f;
			}
			$m['fields'] = $fields;
			$meta_boxes[] = $m;
		}
		return $meta_boxes;
	}

	public function mh_cmbMeta_cmb($meta_boxes){
		$prefix = '_'.$this->slug.'_';
		foreach($this->metaBoxes_cmb as $mb){
			$m = array();
			$m['id'] = $mb['id'];
			$m['title'] = $mb['title'];
			$m['pages'] = array($this->slug);
			$m['context'] = isset($mb['context']) ? $mb['context'] : 'normal';
			$m['priority'] = isset($mb['priority']) ? $mb['priority'] : 'high';
			$fields = array();
			foreach($mb['fields'] as $fi){
				$fields[] = $this->mh_cmb_Fields($fi, $prefix);
			}
			$m['fields'] = $fields;
			$meta_boxes[] = $m;
		}
		return $meta_boxes;
	}

	public function mh_cmb_Fields($fi, $prefix){
		$f = array();
		if(isset($fi['name'])) $f['name'] = $fi['name'];
		$f['id'] = $prefix.$fi['id'];
		if(isset($fi['desc'])) $f['desc'] = $fi['desc'];
		$f['type'] = $fi['type'];
		if($f['type'] == 'post_select' && isset($fi['post_type'])){
			$f['type'] = 'select';
			$args = array(
				'post_type' => $fi['post_type'],
				'posts_per_page' => -1,
			);
			$query = new WP_Query($args);
			wp_reset_postdata();
			$opts = array();
			foreach($query->posts as $p){
				$opts[$p->ID] = $p->post_title;
			}
			$f['options'] = $opts;
		}
		if(isset($fi['options'])) $f['options'] = $fi['options'];
		if($fi['type'] == 'group' && isset($fi['fields'])){
			foreach($fi['fields'] as $ff){
				$f['fields'][] = $this->mh_cmb_Fields($ff, $prefix);
			}
		}
		return $f;
	}

	public function mh_removeMetaBoxes(){
		foreach($this->removeMetaBoxes as $rm){
			if(isset($rm['id']) && isset($rm['page']) && isset($rm['context'])){
				remove_meta_box($rm['id'], $rm['page'], $rm['context']);
			}
		}
	}

	public function mh_modifyPostInputs($data, $postarr){
		if($data['post_type'] == $this->slug){
			foreach($this->changes as $d){
				$dFormat = (isset($d['date_format']) && $d['date_format'] != '') ? $d['date_format'] : 'l, d.m.Y';
				if(isset($data[$d['key']])){
					switch($d['replace_key']){
						case 'date':
							$data[$d['key']] = date($dFormat, strtotime($data['post_date']));
							break;
						case 'date_metavalue':
							if(isset($postarr[$d['meta_key']]) && $m = $postarr[$d['meta_key']]){
								$data[$d['key']] = date($dFormat, strtotime($data['post_date'])).' - '.$m;
							}
							break;
						case 'date_meta_value_post':
							if(isset($postarr[$d['meta_key']]) && $m = $postarr[$d['meta_key']]){
								$data[$d['key']] = date($dFormat, strtotime($data['post_date'])).' - '.get_the_title($m);
							}
							break;
						case 'date_meta_value_post_acf':
							if(isset($postarr['acf'][$d['meta_key']]) && $m = $postarr['acf'][$d['meta_key']]){
								$data[$d['key']] = date($dFormat, strtotime($data['post_date'])).' - '.get_the_title($m);
							}
							break;
						case 'date_text':
							$data[$d['key']] = (isset($d['before_text']) ? $d['before_text'].' - ' : '') . date($dFormat, strtotime($data['post_date'])) . (isset($d['after_text']) ? ' - '.$d['after_text'] : '');
							break;
						case 'meta_value':
							$length = isset($d['length']) ? $d['length'] : 100;
							$data[$d['key']] = (isset($postarr[$d['meta_key']]) ? substr($postarr[$d['meta_key']], 0, $length) : '') . (strlen($postarr[$d['meta_key']]) > $length ? '...' : '');
							break;
					}
				}
			}
		}
		return $data;
	}

	public function mh_adminPostsFilterSelect($post_type){
		if($post_type == $this->slug){
			?>
				<select name="ADMIN_FILTER_FIELD_NAME">
				<option value=""><?php _e('Filter By Custom Fields', 'gledalisce'); ?></option>
			<?php
		    $current = isset($_GET['ADMIN_FILTER_FIELD_NAME'])? urldecode($_GET['ADMIN_FILTER_FIELD_NAME']):'';
		    $current_v = isset($_GET['ADMIN_FILTER_FIELD_VALUE'])? $_GET['ADMIN_FILTER_FIELD_VALUE']:'';
		    foreach($this->search as $k=>$s){
		    	sprintf(
		    		'<option value="%s"%s>%s</option>',
	                $k.'#'.$k,
	               	$k.'#'.$k == $current? ' selected="selected"':'',
	                $s
		    	);
		    }
		    foreach($this->columns as $m){
		        if(isset($m['search']) && $m['search']){
		        	sprintf(
		                '<option value="%s"%s>%s</option>',
		                $m['meta_key'].'#metavalue',
		               	$m['meta_key'].'#metavalue' == $current? ' selected="selected"':'',
		                $m['label']
		            );
		        }
		    }
		    foreach($this->tax as $t){
		    	if(isset($t['show_admin_column']) && $t['show_admin_column']){
		    		sprintf(
		                '<option value="%s"%s>%s</option>',
		                $t['tax_name'].'#taxonomy',
		               	$t['tax_name'].'#taxonomy' == $current? ' selected="selected"':'',
		                $t['tax_label']
		            );
		    	}
		    }
			?>
				</select> <?php _e('Value:', 'gledalisce'); ?><input type="TEXT" name="ADMIN_FILTER_FIELD_VALUE" value="<?php echo $current_v; ?>" />
			<?php
		}
	}

	public function mh_adminPostsFilter($query){
		global $pagenow;
	    if(is_admin() && $pagenow=='edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == $this->slug && isset($_GET['ADMIN_FILTER_FIELD_NAME']) && $_GET['ADMIN_FILTER_FIELD_NAME'] != ''){
	    	$val = explode('#', $_GET['ADMIN_FILTER_FIELD_NAME']);
	    	if(isset($_GET['ADMIN_FILTER_FIELD_VALUE']) && $_GET['ADMIN_FILTER_FIELD_VALUE'] != ''){
		        switch($val[1]){
		        	case 'metavalue':
		        		$query->query_vars['meta_key'] = $val[0];
					    $query->query_vars['meta_value'] = $_GET['ADMIN_FILTER_FIELD_VALUE'];
		        	break;
		        	case 'taxonomy':
		        		$query->query_vars[$val[0]] = $_GET['ADMIN_FILTER_FIELD_VALUE'];
		        	break;
		        	case 'title':
		        		$query->query_vars['mh_custom_var'] = array('key'=>$val[1], 'value'=>$_GET['ADMIN_FILTER_FIELD_VALUE']);
		        		add_filter('posts_where', array($this, 'mh_adminPostsFilterWhere'), 10, 2);
		        	break;
		        	case 'date':
		        		$date = strtotime($_GET['ADMIN_FILTER_FIELD_VALUE']);
		        		$query->query_vars['year'] = date('Y', $date);
		        		$query->query_vars['monthnum'] = date('m', $date);
		        		$query->query_vars['day'] = date('d', $date);
		        	break;
		        }

	    	}
	    }
	}

	public function mh_adminHideSearch(){
		if(isset($_GET['post_type']) && $_GET['post_type'] == $this->slug){
			?>
			<style type="text/css">
				.search-box{ display:none; }
			</style>
			<?php
		}
	}

	public function mh_adminPostsFilterWhere($where, &$query){
		global $wpdb;
	    if($this->search && $data = $query->get('mh_custom_var')){
	    	$key = '';
	    	switch($data['key']){
	    		case 'title':
	    			$key = 'post_title';
	    			$value = $data['value'];
	    		break;
	    	}
	    	if($key)
	        	$where .= ' AND ' . $wpdb->posts . '.' . $key . ' LIKE "%' . esc_sql($wpdb->esc_like($value)) . '%"';
	    }
		return $where;
	}

}

/*
******************************************************************************************************
	Abstract class that a custom post type class must extends
******************************************************************************************************
*/
abstract class mhCptAbstract{
    protected $settings;

    abstract public function createSettings();

    public function getSettings(){
        if(!$this->settings) $this->createSettings();
        return $this->settings;
    }

    public function getMetaString($cpt, $key, $val){
        $key = explode('_'.$cpt.'_', $key);
        if(isset($this->settings['meta_boxes']) && isset($key[1])){
            foreach($this->settings['meta_boxes'] as $boxes){
                foreach($boxes['fields'] as $field){
                    if($field['id'] == $key[1] && isset($field['options'][$val])) return $field['options'][$val];
                }
            }
        }
        return $val;
    }

    public function getMetaTitle($cpt, $key){
        $key = explode('_'.$cpt.'_', $key);
        if(isset($this->settings['meta_boxes']) && isset($key[1])){
            foreach($this->settings['meta_boxes'] as $boxes){
                foreach($boxes['fields'] as $field){
                    if($field['id'] == $key[1]) return $field['name'];
                }
            }
        }
    }

    protected function includeScripts($scriptArray){
        foreach($scriptArray as $sc){
            wp_enqueue_script(
                $sc['handle'],
                $sc['src'],
                $sc['deps'],
                $sc['ver'],
                $sc['media']);
        }
    }
}
?>