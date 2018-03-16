<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package emigma
 */
?>
		                </div><!-- close .row -->
		            </div><!-- close .container -->
		        </div><!-- close .site-content -->
			<footer id="footer-area" class="site-footer">

				<div class="footer-content">
					<div class="container footer-inner">
						<?php get_sidebar( 'footer' ); ?>
					</div>
			        <?php if( of_get_option('footer_social') ) emigma_social_icons(); ?>
				</div>

				<div id="colophon" class="site-info" role="contentinfo">
					<div class="container">
						<div class="row">
			                <div class="col-md-12">
			                	<div class="copyright">
									<div><?php emigma_footer_copyright(); ?></div>
									<div class="visible-xs-inline-block inline-separator"></div>
									<div class="pull-right-sm"><?php emigma_footer_authors(); ?></div>
								</div>
								<div class="hidden-xs hidden-sm footer-notice"><small><?php emigma_footer_notice(); ?></small></div>
							</div>
						</div>
					</div><!-- .site-info -->
				</div><!-- #colophon -->

				<a id="scroll-top" class="btn btn-default btn-scroll"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
			</footer>
		</div><!-- #page -->
		<?php wp_footer(); ?>
	</body>
</html>