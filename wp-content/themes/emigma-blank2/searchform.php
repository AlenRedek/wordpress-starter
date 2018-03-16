<?php
/**
 * The template for displaying search forms in Dazzling
 *
 * @package emigma
 */
?>
<form method="get" class="form-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="input-group">
  		<span class="screen-reader-text"><?php _ex( 'Search for:', 'label', 'emigma' ); ?></span>
    	<input type="text" class="form-control form-control-search search-query" placeholder="<?php _e( 'Search keyword', 'emigma' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
    	<span class="input-group-btn">
      		<button type="submit" class="btn btn-search" name="submit" id="searchsubmit" value="Search"><i class="fa fa-search-custom" aria-hidden="true"></i></button>
    	</span>
    </div>
</form>