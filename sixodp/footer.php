<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

		</div><!-- .site-content -->

		<footer id="colophon" class="site-footer bgcolor-primary" role="contentinfo">
			<div class="container">
				<div class="row">
					<div class="col-md-1">
						<div class="footer-logo-wrapper">
							<img class="footer-logo" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/6aika_logo_w.png" alt="6Aika logo">
						</div>
					</div>
					<div class="col-md-5 pull-right">
						<?php
							get_template_part( 'partials/social_links' );
						?>
					</div>
				</div>

				<hr />
				<div class="row">
			    <div class="col-md-12 footer-section footer-section--links">
						<ul class="footer-links">
							<?php
								foreach ( wp_get_nav_menu_items("primary_$pagename") as $navItem ) {
									echo '<li class="footer__item"><a href="'.$navItem->url.'" class="footer__link" title="'.$navItem->title.'">'.$navItem->title.'</a></li>';
								}
			        ?>
						</ul>
			    </div>
			  </div>
				<hr />
				<div class="copyrights">
    			© 6Aika
  			</div>
			</div>
		</footer><!-- .site-footer -->
	</div><!-- .site-inner -->
</div><!-- .site -->

<?php wp_footer(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
