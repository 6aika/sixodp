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
					<div class="col-md-4 pull-right text-right">
						<div class="popper">
							<ul class="popper__list">
								<li class="popper__item">
									<a href="/data/user/login" class="popper__link"><?php _e('Data portal');?> <i class="material-icons">arrow_forward</i></a>
								</li>
								<li class="popper__item">
									<a href="/admin" class="popper__link"><?php _e('Content manangement system');?> <i class="material-icons">arrow_forward</i></a>
								</li>
							</ul>
						</div>
						<button type="button" class="btn btn-lg btn-transparent" data-trigger="popper"><?php _e('Login');?></button>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<div class="footer-logo-wrapper">
							<img class="footer-logo" src="<?php echo assets_url(); ?>/images/main_logo_w.png" alt="6Aika logo">
						</div>
					</div>
					<div class="col-md-4">
						<div class="row">
						  <div class="col-xs-12">
						    <div class="footer-follow-links">
						      <?php dynamic_sidebar( 'footer_content' ); ?>
						    </div>
						  </div>
						</div>
					</div>
					<div class="col-md-6 pull-right">
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
			          foreach ( get_nav_menu_items("footer") as $navItem ) {
			            $class = '';
			            if ( $navItem["title"] === get_current_locale() ) {
			              $class = 'active';
			            }
			            echo '<li class="'.$class.'"><a href="'.$navItem["url"].'" title="'.$navItem["title"].'" class="nav-link">'.$navItem["title"].'</a></li>';
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
