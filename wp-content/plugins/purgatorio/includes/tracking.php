<?php

if( ! function_exists('google_analytics_tracking') ){
	add_action('wp_head','google_analytics_tracking');
	function google_analytics_tracking(){
		$ga_tracking_id = pg_get_option('ga_tracking_id');
		if( ! $ga_tracking_id ){
			return;
		}
		?>
		<!-- Google Analytics tracking Code -->
		<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
		
		ga('create', '<?php echo $ga_tracking_id; ?>', 'auto');
		ga('send', 'pageview');
		</script>
		<!-- End Google Analytics tracking Code -->
		<?php
	}
}

if( ! function_exists('facebook_pixel_tracking') ){
	add_action('wp_head','facebook_pixel_tracking');
	function facebook_pixel_tracking(){
		$fb_tracking_id = pg_get_option('fb_tracking_id');
		if( ! $fb_tracking_id ){
			return;
		}
		?>
		<!-- Facebook Pixel Code -->
		<script>
			!function(f,b,e,v,n,t,s)
			{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};
			if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
			n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t,s)}(window,document,'script',
			'https://connect.facebook.net/en_US/fbevents.js');
			fbq('init', '<?php echo $fb_tracking_id; ?>'); 
			fbq('track', 'PageView');
			fbq('track', 'ViewContent');
		</script>
		<noscript>
			<img height="1" width="1" src="https://www.facebook.com/tr?id=<?php echo $fb_tracking_id; ?>&ev=PageView&noscript=1"/>
		</noscript>
		<!-- End Facebook Pixel Code -->
		<?php
	}
}

?>