<?php

class AR_Attachments_Class {

	private $keyOne;
	private $keyTwo;

	public function __construct($keyOne = 'attachments', $keyTwo = 'attachment'){
		$this->keyOne = $keyOne;
		$this->keyTwo = $keyTwo;
		add_filter('the_content', array($this, 'ar_display_media_after_content'));
	}

	/*
	******************************************************************************************************
		Display media files after content
	******************************************************************************************************
	*/
	public function ar_display_media_after_content($content, $title=false){
		global $post;
		$html = '';
		$files = $this->ar_get_files($post);
		$has_files = false;
		if( $files && (is_single() || is_page()) ){
			foreach($files as $file){
				if(is_array($file)){
					$has_files = true;
					break;
				}
			}
			if ( $has_files ) {
				if( ! $title ) $title = __('File downloads','emigma');
				$html .= $this->ar_before_files_output($title);
				$html .= $this->ar_files_output($files);
				$html .= $this->ar_after_files_output();
			}
		}
		return $content.$html;
	}

	/*
	******************************************************************************************************
		Get files
	******************************************************************************************************
	*/
	public function ar_get_files($post){
		$files = false;
		if( have_rows($this->keyOne) ):
	    	while ( have_rows($this->keyOne) ) : the_row();
	        	$files[] = get_sub_field($this->keyTwo);
	    	endwhile;
	    endif;
	    return $files;
	}

	/*
	******************************************************************************************************
		Display media files after content
	******************************************************************************************************
	*/
	public function ar_before_files_output($title){
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
	public function ar_files_output($files){
		$html = '';
		$i = 1;
		if($files){
			foreach($files as $f){
				if($f){
					$html .= '<div class="col-xs-12 col-sm-6 col-md-4 file-item">';
					$html .= $this->ar_generate_file_output($f);
					$html .= '</div>';
					if($i%3 === 0) $html .= '<div class="clearfix visible-md visible-lg"></div>';
					if($i%2 === 0) $html .= '<div class="clearfix visible-sm"></div>';
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
	public function ar_generate_file_output($file, $key=false){
		if($key) $file = get_field($key, $file);
		$size = $this->ar_get_size(get_attached_file($file['ID']));
		$icon = $this->ar_get_file_type($file['mime_type']);
		$html = '';
		$html .= '<a href="'.$file['url'].'" title="'.$file['title'].'" target="_blank">';
			$html .= '<div class="flex-xs">';
				$html .= '<i class="fa fa-file-' . $icon['icon'] . 'o"></i>';
				$html .= '<div><div class="text-bold">'.$file['title'].'</div>'.'<div class="text-uppercase">'.$icon['type'].'<span> | '.$size.'</span></div></div>';
			$html .= '</div>';
		$html .= '</a>';

		return $html;
	}

	/*
	******************************************************************************************************
		After files output
	******************************************************************************************************
	*/
	public function ar_after_files_output(){
		$html  = '</div>';
		$html .= '</div>';
		$html .= '</div>';
	    return $html;
	}

	/*
	******************************************************************************************************
		Get file size
	******************************************************************************************************
	*/
	protected function ar_get_size($file){
		$bytes = filesize($file);
		$s = array('b', 'kb', 'mb', 'gb');
		$e = floor(log($bytes)/log(1024));
		$size = $bytes/pow(1024, floor($e));
		$size = number_format_i18n($size, 2);
		return $size . ' ' . $s[$e];
	}

	/*
	******************************************************************************************************
		Get file type based on file type
	******************************************************************************************************
	*/
	protected function ar_get_file_type($mime_type){
		$general_types = explode('/',$mime_type);
		if($general_types[0] !== 'application')
			return array('type'=>$general_types[0], 'icon'=>$general_types[0].'-');
		if (strpos($general_types[1],'word') !== false)
			return array('type'=>'doc', 'icon'=>'word-');
		if ( (strpos($general_types[1],'excel') || strpos($general_types[1],'spreadsheet')) !== false )
			return array('type'=>'xls', 'icon'=>'excel-');
		if ( (strpos($general_types[1],'powerpoint') || strpos($general_types[1],'presentation')) !== false)
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
}

new AR_Attachments_Class();

?>