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

				<?php
					get_template_part( 'partials/social_links' );
				?>

				<hr />
				<div class="row">
			    <div class="col-md-12 col-lg-4 footer-section">
			      <div class="footer-logo-wrapper">
			        <img class="footer-logo" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/6aika_logo_w.png" alt="6Aika logo">
			      </div>
			    </div>
			    <div class="col-sm-6 col-md-6 col-lg-4 footer-section footer-section--links">
						<?php
							if ( has_nav_menu( 'footer' ) ) :
			          wp_nav_menu( array(
			            'theme_location' => 'footer',
			            'menu_class'     => 'footer-links',
			           ) );
							endif;
						?>
			    </div>

					<div class="col-sm-6 col-md-6 col-lg-4 footer-section">
			      6Aika Twitter
			      <a class="twitter-timeline"
			        data-width="320"
			        data-height="240"
			        data-link-color="#fff"
			        data-theme="dark"
			        href="https://twitter.com/Kuutosaika"
			        data-chrome="noheader nofooter noborders transparent">
			        Tweets by Kuutosaika
			      </a>
			      <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
			    </div>
			  </div>
				<hr />
				<div class="text-center copyrights">
    			© 6Aika
  			</div>
				<div class="header-logos" style="display:none;">
			    <div class="logo">
			      <img src="https://demo.dataportaali.com//base/images/EU_ERDF_FI.png" alt="European Regional Development Fund logo">
			    </div>
			    <div class="logo">
			      <img src="https://demo.dataportaali.com//base/images/LeverageEU_FI.png" alt="Leverage with EU logo">
			    </div>
			    <div class="flag-ends"></div>
			  </div>
			</div>
		</footer><!-- .site-footer -->
	</div><!-- .site-inner -->
</div><!-- .site -->

<?php wp_footer(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
