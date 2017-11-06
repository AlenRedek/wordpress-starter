<?php
	
class PG_Youtube_Class {
    static $api_key = 'AIzaSyA7o34KLShR34qiu4B0C-tKLUNtCkZmaMY';
    static $api_base = 'https://www.googleapis.com/youtube/v3/videos';
    static $thumbnail_base = 'https://i.ytimg.com/vi/';

    public static function get_video_info($text) {

        $video_url = false;

		if(strpos($text,'[embed]') !== false){
			$tmp = explode('[embed]',$text);
			$tmp2 = explode('[/embed]',$tmp[1]);
			$video_url = $tmp2[0];
		}elseif(function_exists('pg_extract_url')){
		    $match = pg_extract_url($text);
		    foreach($match as $k=>$m){
		        if($m && strpos($m[0],'youtube') !== false){
		            $video_url = $m[0];
		            break;
		        }
		    }
		}
		if( ! $video_url ) return;

		parse_str(parse_url($video_url,PHP_URL_QUERY),$video_id);
		if( ! $video_id ) return;
		
        $params = array(
            'part' => 'contentDetails',
            'id' => $video_id['v'],
            'key' => self::$api_key,
        );
        
        $api_url = self::$api_base . '?' . http_build_query($params);
        $request = wp_remote_get($api_url);
        $response = wp_remote_retrieve_body($request);
        $result = json_decode($response, true);

        if(empty($result['items'][0]['contentDetails']))
            return null;
        $vinfo = $result['items'][0]['contentDetails'];

        $interval = new DateInterval($vinfo['duration']);
        $vinfo['duration_sec'] = $interval->h * 3600 + $interval->i * 60 + $interval->s;

        $vinfo['thumbnail']['default']       = self::$thumbnail_base . $video_id['v'] . '/default.jpg';
        $vinfo['thumbnail']['mqDefault']     = self::$thumbnail_base . $video_id['v'] . '/mqdefault.jpg';
        $vinfo['thumbnail']['hqDefault']     = self::$thumbnail_base . $video_id['v'] . '/hqdefault.jpg';

        $vinfo['thumbnail']['sdDefault']     = self::$thumbnail_base . $video_id['v'] . '/sddefault.jpg';
        $vinfo['thumbnail']['maxresDefault'] = self::$thumbnail_base . $video_id['v'] . '/maxresdefault.jpg';

        return $vinfo;
    }

    public function get_video_duration($text){
        $video_info = $this->get_video_info($text);

        $duration = '';
        if(isset($video_info['duration_sec'])){
            $duration = gmdate("i:s", $video_info['duration_sec']);
        }

        return $duration;
    }
}

?>