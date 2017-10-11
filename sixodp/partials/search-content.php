<?php
  /**
  * search-content content box on.
  */

  global $wp_query;
  $searchterm = trim($_GET['s']);
  $baseurl = CKAN_API_URL;
  $url = $baseurl."/action/package_search?q=".$searchterm;
  $data_dataset = get_ckan_data($url."&fq=dataset_type:dataset");
  $data_dataset = $data_dataset['result'];
  $data_showcase = get_ckan_data($url."&fq=dataset_type:showcase");
  $data_showcase = $data_showcase['result'];
  $searchcount = get_posts(array('s' => $searchterm, 'post_type' => 'any' ));
  $searchcount =  count($searchcount);
?>
<div class="container">
  <div class="row">

    <div class="search-sidebar col-sm-3">
      <ul class="sidebar-section">
        <li class="sidebar-heading">
          <?php _e('Results in groups', 'sixodp');?>
        </li>
        <li class="sidebar-item">
            <a href="<?php echo get_site_url(); ?>/<?php echo get_current_locale() ?>/?s=<?php echo $searchterm;?>&datasearch" title=""> <span><?php _e('Datasets', 'sixodp');?>  (<?php echo $data_dataset['count']; ?>)</span></a>
        </li>
        <li class="sidebar-item">
            <a href="<?php echo get_site_url(); ?>/<?php echo get_current_locale() ?>/?s=<?php echo $searchterm;?>&datasearch&showcase" title=""> <span><?php _e('Applications', 'sixodp');?>  (<?php echo $data_showcase['count']; ?>)</span></a>
        </li>
        <li class="sidebar-item">
            <a href="<?php echo get_site_url(); ?>/<?php echo get_current_locale() ?>/?s=<?php echo $searchterm;?>" title="" class="active"> <span><?php _e('Others', 'sixodp');?>  (<?php  echo $searchcount; ?>)</span></a>
        </li>
      </ul>
    </div>
    <div class="col-md-9 search-container">
      <h3 class="heading"><?php printf( esc_html( _n( 'Found %d result', 'Found %d results', $wp_query->found_posts, 'sixodp' ) ), $wp_query->found_posts ); ?></h3>
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
