<?php
  /**
  * Search content box on.
  */
?>
<div class="container">
  <div class="row">
      <div class="col-md-4 datasets">

      </div>
      <div class="col-md-8 datasets">
        <h3 class="heading">Viimeisimmät päivitykset</h3>
            <ul class="dataset__list">
              <?php
              // Start the loop.
              while ( have_posts() ) : the_post(); ?>
              <li class="dataset">
                <div class="dataset__content">
                  <span class="dataset__type"><?php echo $item['type']; ?></span>
                  <h4 class="dataset__title">
                    <a class="dataset__link" href="<?php the_permalink(); ?>">
                      <?php the_title(); ?>
                    </a>
                  </h4>
                  <div class="dataset__body">
                    <div class="metadata">
                        <span class="time">
                            <?php echo get_the_date();?>
                        </span>
                    </div>
                    <p class="dataset__info"><?php the_excerpt(); ?></p>
                  </div>
                </div>
              </li>
              <?php /*
              <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
                </header><!-- .entry-header -->
                <div class="entry-summary">
                    <?php the_excerpt(); ?>
                </div><!-- .entry-summary -->
              </article><!-- #post-## -->
              */ ?>
              <?php
              // End the loop.
              endwhile;
              ?>
          </ul>
      </div>
  </div>
</div>
