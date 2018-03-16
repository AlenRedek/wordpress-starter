<?php

global $attachment_id, $attachment_size, $selector;
$attachment = get_post($attachment_id);
$image_link_full = wp_get_attachment_image_src( $attachment_id, 'full' );
$footer = 	'<div class="modal-footer-download"><a href="'.$image_link_full[0].'" download><i class="fa"></i></a></div>'.
			'<div class="modal-footer-description">'.
				'<h5 class="modal-title">'.$attachment->post_title.'</h5>'.
				'<p>'.$attachment->post_content.'</p>'.
			'</div>';

?>
<div class="gallery-item">
	<div class="gallery-article gallery-icon">
		<a href="<?php echo $image_link_full[0]; ?>" class="lightbox" data-toggle="lightbox" data-gallery="<?php echo $selector; ?>" data-footer='<?php echo $footer; ?>'>
			<div class="gallery-img-wrapper">
				<?php get_template_part('elements/element','thumbnail'); ?>
			</div>
		</a>
	</div>
</div>