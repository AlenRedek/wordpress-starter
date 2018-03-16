<?php
class AR_Youtube_Class {
    static $api_key = 'AIzaSyA7o34KLShR34qiu4B0C-tKLUNtCkZmaMY';
    static $api_base = 'https://www.googleapis.com/youtube/v3/videos';
    static $thumbnail_base = 'https://i.ytimg.com/vi/';

    public static function getVideoInfo($text) {

        $video_url = false;

		if(strpos($text,'[embed]') !== false){
			$tmp = explode('[embed]',$text);
			$tmp2 = explode('[/embed]',$tmp[1]);
			$video_url = $tmp2[0];
		}elseif(function_exists('ar_extract_url')){
		    $match = ar_extract_url($text);
		    foreach($match as $k=>$m){
		        if($m && strpos($m[0],'youtu') !== false){
		            $video_url = $m[0];
		            break;
		        }
		    }
		}

		if( !$video_url ) return;

		parse_str(parse_url($video_url,PHP_URL_QUERY),$video_id);

        $params = array(
            'part' => 'contentDetails',
            'id' => $video_id['v'],
            'key' => self::$api_key,
        );

        $api_url = self::$api_base . '?' . http_build_query($params);
        $result = json_decode(@file_get_contents($api_url), true);

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

    public function get_min_duration($text){
        $video_info = $this->getVideoInfo($text);

        $duration = '';
        if(isset($video_info['duration_sec'])){
            $duration = gmdate("i:s", $video_info['duration_sec']);
        }

        return $duration;
    }
}
?>