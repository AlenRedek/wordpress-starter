
<?php
	/* translators: used between list items, there is a space after the comma */
	$categories_list = get_the_category_list( __( ', ', 'emigma' ) );
	if ( $categories_list && emigma_categorized_blog() ) :
?>
<span class="cat-links"><i class="fa fa-folder-open-o"></i>
	<?php printf( __( ' %1$s', 'emigma' ), $categories_list ); ?>
</span>
<?php endif; // End if categories ?>
<span class="uppercase"><?php echo get_date_format($post->post_date); ?></span>

<?php edit_post_link( __( 'Edit', 'emigma' ), '<i class="fa fa-pencil-square-o"></i><span class="edit-link">', '</span>' ); ?>