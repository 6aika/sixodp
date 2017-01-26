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

				<hr class="border-complementary"/>
				<div class="row">
			    <div class="col-md-12 col-lg-4 footer-section">
			      <div class="footer-logo">
			        <img class="footer-logo" src="<?php echo site_url(); ?>/wp-content/themes/sixodp/images/6aika_logo_w.png" alt="6Aika logo">
			      </div>
			    </div>
			    <div class="col-sm-6 col-md-6 col-lg-4 footer-section">
			      <div class="footer-links">
			        <table>
			          <tbody>
									<tr>
				            <td><a>Yhteystiedot</a></td>
				            <td><a>Organisaatio</a></td>
				          </tr>
									<tr>
				            <td><a>Käyttöehdot</a></td>
				            <td><a>Tapahtumat</a></td>
				          </tr>
									<tr>
				            <td><a>Rekisteriseloste</a></td>
				            <td><a>Palaute</a></td>
				          </tr>
									<tr>
				            <td><a>Tietoa palvelusta</a></td>
				            <td>
				      				<li>
												<a class="nav-link" href="/user/login">
													<i class="icon-signin"></i> Kirjaudu sisään
												</a>
											</li>
				            </td>
				          </tr>
								</tbody>
							</table>
			      </div>
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
				<hr  class="border-complementary"/>
				<div class="text-center footer-section">
    			© 6Aika
  			</div>
				<div class="header-logos">
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
