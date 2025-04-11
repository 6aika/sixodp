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
				<div class="row footer-row justify-content-end">
					<div class="col-md-4 footer-column">
						<div class="popper">
							<ul class="popper__list">
								<li class="popper__item">
									<a href="/data/user/login" class="popper__link"><?php _e('Data portal', 'sixodp');?> <i class="material-icons">arrow_forward</i></a>
								</li>
								<li class="popper__item">
									<a href="/admin" class="popper__link"><?php _e('Content manangement system', 'sixodp');?> <i class="material-icons">arrow_forward</i></a>
								</li>
							</ul>
						</div>
						<button type="button" class="float-end btn btn-transparent" data-trigger="popper"><?php _e('Management', 'sixodp');?></button>
					</div>
				</div>
				<div class="row footer-row">
					<div class="col-md-2 footer-column">
						<div class="footer-logo-wrapper">
							<img class="footer-logo" src="<?php echo assets_url(); ?>/images/footer_logo.png" alt="6Aika logo">
						</div>
					</div>
					<div class="col-md-4 footer-column">
						<div class="row">
						  <div class="col-xs-12">
						    <div class="footer-follow-links">
						      <?php dynamic_sidebar( 'footer_content' ); ?>
						    </div>
						  </div>
						</div>
					</div>
					<div class="col-md-6 pull-right footer-column">
						<?php
							get_template_part( 'partials/social_links' );
						?>
					</div>
				</div>

        <hr>

				<div class="row footer-row">
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
			</div>
		</footer><!-- .site-footer -->
	</div><!-- .site-inner -->
</div><!-- .site -->

<?php wp_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
