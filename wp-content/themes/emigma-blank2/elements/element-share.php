<?php

  global $post;
  if( ! $post ) return;

?>
<ul class="social-cards" aria-labelledby="shareDropdown">
  <li class="facebook-share"><a href="<?php echo get_permalink($post->ID); ?>" class="btn btn-default"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
  <li class="twitter-share"><a href="<?php echo get_permalink($post->ID); ?>" class="btn btn-default"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
  <li class="googleplus-share"><a href="<?php echo get_permalink($post->ID); ?>" class="btn btn-default"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
</ul>