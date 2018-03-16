<?php

function get_pharmacy_schedule($meta_key){
    $opening_hours = get_field($meta_key);
    $working_hours = array();
    if($opening_hours){
        foreach($opening_hours as $d){
            $key = date('H', strtotime($d['from'])).date('H', strtotime($d['to']));
            $working_hours[$d['day']][$key]['from'] = $d['from'];
            $working_hours[$d['day']][$key]['to']   = $d['to'];
        }
    }
    return $working_hours;
}

function prepare_pharmacy_schedule($meta_key){
    $output = '';
    $keys = array(
        'opening_hours' => array(
            'title'     => __('Opening hours','emigma'),
            'border'    => 'gray-lighter'
        ),
        'standby_hours' => array(
            'title'     => __('Standby hours','emigma'),
            'border'    => 'danger'
        )
    );
    $working_hours = get_pharmacy_schedule($meta_key);
    if($working_hours){
        $output .= '<div class="panel border-'.$keys[$meta_key]['border'].'">';
        $output .= '<div class="panel-body">';
        $output .= '<h3 class="xs-mt-0">'.$keys[$meta_key]['title'].'</h3>';
        foreach($working_hours as $k=>$day){
            $day_of_week = date_i18n('l', strtotime("Sunday +{$k} days"));
            $output .= '<div class="row">';
            $output .= '<div class="col-xs-6 col-sm-4">';
            $output .= '<span class="uppercase">'.$day_of_week.'</span>';
            $output .= '</div>';
            $output .= '<div class="col-xs-6 col-sm-8">';
            $day_hours = array();
            foreach($day as $hours){
                if($hours['from'] && $hours['to']){
                    $day_hours[] = '<span class="inline-block">'.$hours['from'].' - '.$hours['to'].'</span>';
                }
            }
            if($day_hours){
                $output .= implode(', ', $day_hours);
            }else{
                $output .= __('Closed', 'emigma');
            }
            $output .= '</div>';
            $output .= '</div>';
        }
        $output .= '</div>';
        $output .= '</div>';
    }
    return $output;
}

function pharmacy_opening_hours($pharmacy){
    $todays_day                 = date('N');
    $pharmacy_status['open']    = false;
    $pharmacy_status['class']   = 'danger';
    $pharmacy_status['label']   = __('Closed', 'emigma');
    $pharmacy_status['text']    = date_i18n('l');
    $opening_hours              = get_pharmacy_schedule('opening_hours');
    $standby_hours              = get_pharmacy_schedule('standby_hours');
    $working_hours              = array();

    if( ! $opening_hours[$todays_day] ){
        $opening_hours[$todays_day] = array();
    }
    if( ! $standby_hours[$todays_day] ){
        $standby_hours[$todays_day] = array();
    }
    $working_hours = $opening_hours[$todays_day] + $standby_hours[$todays_day];
    if( $working_hours ){
        foreach($working_hours as $k=>$hours){
            if (is_pharmacy_open($hours)) {
                $pharmacy_status['open'] = true;
                $pharmacy_status['class'] = 'success';
                $pharmacy_status['label'] = __('Opened', 'emigma');
                $pharmacy_status['text'] .= ', ' . $hours['from'] . ' - ' . $hours['to'];
                break;
            }
        }
    }
    return $pharmacy_status;
}

function is_pharmacy_open($hours){
    $current_time = date('H:i');
    if($hours['to'] === '00:00')
        $hours['to'] = '24:00';
    if ($current_time >= $hours['from'] && $current_time <= $hours['to']) {
        return true;
    }else{
        return false;
    }
}

/**
 * ---------------------------------------------------------------
 * Home banners
 * ---------------------------------------------------------------
 */
function ar_footer_banners(){
    global $qury, $post;
    $banners = $qury->get_latest_posts('banners', 3);
	$total_width = 12;
	if( $banners->have_posts() ){
		while ( $banners->have_posts() ) {
		    $banners->the_post();
			$image 	= wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
			$url 	= $post->link_url;
			$size 	= $post->size;
			$total_width = $total_width - $size;
			if($total_width < 0) break;
			$html .= '<div class="col-xs-12 col-sm-'.$size.' widget">';
				$html .= '<a href="'.$url.'" target="_blank">';
					$html .= '<div class="background-image contain" style="background-image: url('.$image[0].')"></div>';
				$html .= '</a>';
			$html .= '</div>';

		}
	}
	wp_reset_postdata();
	return $html;
}

?>