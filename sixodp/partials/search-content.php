<?php
  /**
  * search-content content box on.
  */

  global $wp_query;
?>
<div class="container">
  <div class="row">
      <div class="col-md-4 search-content">
        <div class="results-container">
            <div class="heading">
                <span>Hakutuloksia ryhmissä</span>
            </div>
            <div class="result">
                
            </div>
        </div>
      </div>
      <div class="col-md-8 search-content">
        <h3 class="heading">Hakutuloksia <?php echo $wp_query->found_posts; ?> kappaletta</h3>
            <ul class="search-content__list">
              <?php
              // Start the loop.
              while ( have_posts() ) : the_post(); ?>
              <li class="search-content">
                <div class="search-content__content">
                  <span class="search-content__type"><?php echo $item['type']; ?></span>
                  <h4 class="search-content__title">
                    <a class="search-content__link" href="<?php the_permalink(); ?>">
                      <?php the_title(); ?>
                    </a>
                  </h4>
                  <div class="search-content__body">
                    <div class="metadata">
                        <span class="time">
                            <?php echo get_the_date();?>
                        </span>
                    </div>
                    <p class="search-content__info"><?php the_excerpt(); ?></p>
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
