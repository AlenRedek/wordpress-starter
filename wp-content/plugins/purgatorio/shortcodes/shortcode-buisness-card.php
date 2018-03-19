<?php

if( ! function_exists('pg_buisness_card_func') ){
	function pg_buisness_card_func( $atts ) {
		if( ! function_exists('get_field') ){
			return;
		}
		
		$organization_postal_address = get_field('organization_postal_address', 'option');
		$organization_contact_point = get_field('organization_contact_point', 'option');
		
		ob_start();
		?>
			<div>
				<p class="text-uppercase">
					<span itemprop="name"><?php the_field('organization_name', 'option'); ?></span>
				</p>
				<div>
					<?php $organization_postal_address = get_field('organization_postal_address', 'option'); ?>
					<div class="pull-left mb-5"><i class="fa fa-map-marker fa-meta"></i></div>
					<div>
						<div><?php echo $organization_postal_address['street_address']; ?></div>
						<span><?php echo $organization_postal_address['postal_code']; ?></span>&nbsp;<span><?php echo $organization_postal_address['address_locality']; ?></span>
						<div><?php echo $organization_postal_address['address_country']; ?></div>
					</div>
					<div class="clearfix"></div>
				</div>
				
				<p><i class="fa fa-envelope-o fa-meta"></i><a href="mailto:<?php echo $organization_contact_point['email']; ?>"><span itemprop="email"><?php echo $organization_contact_point['email']; ?></span></a></p>
				<p><i class="fa fa-phone fa-meta"></i><a href="tel:<?php echo $organization_contact_point['telephone']; ?>"><span itemprop="telephone"><?php echo $organization_contact_point['telephone']; ?></span></a></p>
			</div>
		
		<?php
		$html = ob_get_clean();
		
		return $html;
	}
}
	
?>