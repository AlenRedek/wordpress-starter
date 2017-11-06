<?php

class PG_Attachments_Class {

	private $metakey;
	protected $title;

	public function __construct(){
		$this->metakey = pg_get_option('attachments_metakey');
		$this->title = __('File downloads','purgatorio');
        
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_styles' ) );
        
        add_shortcode('pg_attachments', array($this, 'attachments_shortcode'));
	}
	
	/*
	******************************************************************************************************
		Enqueue scripts & styles
	******************************************************************************************************
	*/
	public function enqueue_scripts_styles(){
		wp_enqueue_style('purgatorio-attachments', PURGATORIO__PLUGIN_URL . 'assets/css/attachments.css', array(), PURGATORIO_VERSION);
	}

	/*
	******************************************************************************************************
		Display media files after content
	******************************************************************************************************
	*/
	public function display_attachments($post_id, $title){
		$html = '';
		$files = $this->get_files($post_id);
		$has_files = false;
		if( ! $title ) $title = $this->title;
		if( $files && (is_single() || is_page()) ){
			foreach($files as $file){
				if(is_array($file)){
					$has_files = true;
					break;
				}
			}
			
			if ( $has_files ) {
				$html .= $this->before_files_output($title);
				$html .= $this->files_output($files);
				$html .= $this->after_files_output();
			}
		}
		return $html;
	}

	/*
	******************************************************************************************************
		Get files
	******************************************************************************************************
	*/
	public function get_files($post_id){
		$files = array();
		$files_urls = get_post_meta($post_id, $this->metakey);
		if($files_urls){
			foreach($files_urls as $file_url){
				$file_id = pg_get_file_id($file_url);
				if($file_id){
					$file_mime_type = get_post_mime_type( $file_id );
					
					$files[$file_id]['id'] = $file_id;
					$files[$file_id]['dir'] = get_attached_file($file_id);
					$files[$file_id]['url'] = $file_url;
					$files[$file_id]['mime_type'] = $file_mime_type;
					$files[$file_id]['icon'] = $this->get_file_type($file_mime_type);
					$files[$file_id]['size'] = pg_get_filesize( $file_id );
					$files[$file_id]['title'] = get_the_title($file_id);
				}
			}
		}
		
	    return $files;
	}

	/*
	******************************************************************************************************
		Display media files after content
	******************************************************************************************************
	*/
	public function before_files_output($title){
		$html  = '<div class="panel files-panel">';
		if($title) $html .= '<div class="panel-heading"><h3>'.$title.'</h3></div>';
		$html .= '<div class="panel-body">';
		$html .= '<div class="row">';
	    return $html;
	}

	/*
	******************************************************************************************************
		Files output
	******************************************************************************************************
	*/
	public function files_output($files){
		$html = '';
		$i = 1;
		if($files){
			foreach($files as $f){
				if($f){
					$html .= '<div class="col-xs-12 col-sm-6 file-item">';
					$html .= $this->generate_file_output($f);
					$html .= '</div>';
					if($i%2 === 0) $html .= '<div class="clearfix hidden-xs"></div>';
					$i++;
				}
			}
		}
	    return $html;
	}

	/*
	******************************************************************************************************
		Generate file output
	******************************************************************************************************
	*/
	public function generate_file_output($file){
		$html = '';
		$html .= '<a href="'.$file['url'].'" title="'.$file['title'].'" target="_blank" class="text-decoration-none">';
			$html .= '<div class="md-flex">';
				$html .= '<i class="fa fa-file-' . $file['icon']['icon'] . 'o"></i>';
				$html .= '<div><div class="font-weight-bold">'.$file['title'].'</div>'.'<div class="text-uppercase">'.$file['icon']['type'].'<span> | '.$file['size'].'</span></div></div>';
			$html .= '</div>';
		$html .= '</a>';

		return $html;
	}

	/*
	******************************************************************************************************
		After files output
	******************************************************************************************************
	*/
	public function after_files_output(){
		$html  = '</div>';
		$html .= '</div>';
		$html .= '</div>';
	    return $html;
	}

	/*
	******************************************************************************************************
		Get file type based on file type
	******************************************************************************************************
	*/
	protected function get_file_type($mime_type){
		$general_types = explode('/',$mime_type);
		if($general_types[0] !== 'application')
			return array('type'=>$general_types[0], 'icon'=>$general_types[0].'-');
		if (strpos($general_types[1],'word') !== false)
			return array('type'=>'doc', 'icon'=>'word-');
		if ( (strpos($general_types[1],'excel') || strpos($general_types[1],'spreadsheet')) !== false )
			return array('type'=>'xls', 'icon'=>'excel-');
		if (strpos($general_types[1],'powerpoint') !== false)
			return array('type'=>'ppt', 'icon'=>'powerpoint-');
		switch($general_types[1]){
			case 'pdf':
				return array('type'=>'pdf', 'icon'=>'pdf-');
			break;

			case 'x-tar':
			case 'zip':
			case 'x-gzip':
			case 'rar':
			case 'x-7z-compressed':
				return array('type'=>'zip', 'icon'=>'archive-');
			break;

			case 'javascript':
			case 'java':
				return array('type'=>'code', 'icon'=>'code-');
			break;
			default:
				return array('type'=>'file', 'icon'=>'');
		}
	}
	
	public function attachments_shortcode($atts, $content = null){
		global $post;
		extract(
			shortcode_atts(
				array(
					'post_id' => $post->ID,
					'title' => $this->title
				), $atts, 'purgatorio'
			)
		);
		
	    $shortcode_string = $this->display_attachments($post_id, $title);
	    
	    /**
		 * Filters the shortcode.
		 *
		 * @since Unknown
		 *
		 * @param string $shortcode_string The full shortcode string.
		 * @param array  $attributes       The attributes within the shortcode.
		 * @param string $content          The content of the shortcode, if available.
		 */
		
		$shortcode_string = apply_filters( "pg_attachments_shortcode", $shortcode_string, $atts, $content );

		return $shortcode_string;
    }
}