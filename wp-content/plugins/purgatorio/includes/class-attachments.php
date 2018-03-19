<?php

class PG_Attachments_Class {

	public $meta_field;
	public $meta_subfield;
	public $title;

	public function __construct($meta_field='attachments', $meta_subfield='attachment'){
		$this->meta_field = $meta_field;
		$this->meta_subfield = $meta_subfield;
		$this->title = __('Download attachments','purgatorio');
        
        add_shortcode('pg_attachments', array($this, 'attachments_shortcode'));
	}
	
	public function display_attachments($post_id, $title){
		$html = '';
		$attachments = $this->get_attachments($post_id);
		if( ! $title ) $title = $this->title;
		
		if( $attachments ){
			$html .= $this->before_attachments_output($title);
			$html .= $this->attachments_output($attachments);
			$html .= $this->after_attachments_output();
		}
		
		return $html;
	}
	
	public function get_attachments($post_id){
		if( function_exists('have_rows') ){
            $attachments = $this->get_attachments_acf($post_id);
        }else{
	        $attachments = array();
			$attachments_urls = get_post_meta($post_id, $this->meta_field);
			if($attachments_urls){
				foreach($attachments_urls as $attachment_url){
					$attachment_id = pg_get_file_id($attachment_url);
					if($attachment_id){
						$attachment_mime_type = get_post_mime_type( $attachment_id );
						
						$attachments[$attachment_id]['id'] = $attachment_id;
						$attachments[$attachment_id]['dir'] = get_attached_file($attachment_id);
						$attachments[$attachment_id]['url'] = $attachment_url;
						$attachments[$attachment_id]['mime_type'] = $attachment_mime_type;
						$attachments[$attachment_id]['title'] = get_the_title($attachment_id);
					}
				}
			}
        }
        
        return $attachments;
	}
	
	public function get_attachments_acf($post_id){
		if( have_rows($this->meta_field) ){
			while ( have_rows($this->meta_field) ) : the_row();
				$attachment = get_sub_field($this->meta_subfield);
				if($attachment){
					$attachments[] = $attachment;
				}
	    	endwhile;
		}
	    
	    return $attachments;
	}
	
	public function before_attachments_output($title){
		$html  = '<div class="panel attachments-panel">';
		if($title) $html .= '<div class="panel-heading"><h3>'.$title.'</h3></div>';
		$html .= '<div class="panel-body">';
		$html .= '<div class="row">';
	    return $html;
	}
	
	public function attachments_output($attachments){
		$html = '';
		$i = 1;
		if($attachments){
			foreach($attachments as $attachment){
				if($attachment){
					$html .= '<div class="col-xs-12 col-sm-6 attachment-item">';
					$html .= $this->generate_attachment_output($attachment);
					$html .= '</div>';
					if($i%2 === 0) $html .= '<div class="clearfix hidden-xs"></div>';
					$i++;
				}
			}
		}
	    return $html;
	}
	
	public function generate_attachment_output($attachment){
		$icon = $this->get_filetype($attachment['mime_type']);
		$size = pg_get_filesize($attachment['id']);
		$html = '';
		$html .= '<a href="'.$attachment['url'].'" title="'.$attachment['title'].'" target="_blank" class="text-decoration-none">';
			$html .= '<div class="md-flex">';
				$html .= '<i class="fa fa-file-' . $icon['icon'] . 'o"></i>';
				$html .= '<div><div class="font-weight-bold">'.$attachment['title'].'</div>'.'<div class="text-uppercase">'.$icon['type'].'<span> | '.$size.'</span></div></div>';
			$html .= '</div>';
		$html .= '</a>';

		return $html;
	}
	
	public function after_attachments_output(){
		$html  = '</div>';
		$html .= '</div>';
		$html .= '</div>';
	    return $html;
	}
	
	public function get_filetype($mime_type){
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
		 * @since 1.0
		 *
		 * @param string $shortcode_string The full shortcode string.
		 * @param array  $attributes       The attributes within the shortcode.
		 * @param string $content          The content of the shortcode, if available.
		 */
		 
		$shortcode_string = apply_filters( "pg_attachments_shortcode", $shortcode_string, $atts, $content );

		return $shortcode_string;
    }
}