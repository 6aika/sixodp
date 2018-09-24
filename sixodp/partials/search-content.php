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
  $count = $wp_query->found_posts;
?>
<div class="container">
  <div class="row">

    <div class="sidebar col-md-3 col-sm-12">
      <h3 class="heading-sidebar"><?php _e('Results in groups', 'sixodp');?></h3>
      <ul>
        <li class="sidebar-item">
          <a href="<?php echo get_site_url(); ?>/<?php echo get_current_locale() ?>/?s=<?php echo $searchterm;?>&datasearch" title="">
            <span class="sidebar-icon-wrapper"><span class="fa fa-long-arrow-right"></span></span>
            <span><?php _e('Datasets', 'sixodp');?>  (<?php echo $data_dataset['count']; ?>)</span>
          </a>
        </li>
        <li class="sidebar-item">
          <a href="<?php echo get_site_url(); ?>/<?php echo get_current_locale() ?>/?s=<?php echo $searchterm;?>&datasearch&showcase" title="">
            <span class="sidebar-icon-wrapper"><span class="fa fa-long-arrow-right"></span></span>
            <span><?php _e('Applications', 'sixodp');?>  (<?php echo $data_showcase['count']; ?>)</span>
          </a>
        </li>
        <li class="sidebar-item active">
          <a href="<?php echo get_site_url(); ?>/<?php echo get_current_locale() ?>/?s=<?php echo $searchterm;?>" title="" class="active">
            <span class="sidebar-icon-wrapper"><span class="fa fa-long-arrow-right"></span></span>
            <span><?php _e('Others', 'sixodp');?>  (<?php  echo $count; ?>)</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="col-md-9 col-sm-12 search-container border-left">
      <div class="search-options">
        <h3 class="search-results-heading">
          <?php $heading = esc_html( _n('Search results: Others (%d)', 'Search results: Others (%d)', $count, 'sixodp') ); ?>
          <h3 class="search-results-heading"><?php printf($heading, $count) ?></h3>
        </h3>
      </div>
      <div>
        <?php
        // Start the loop.
        while ( have_posts() ) : the_post(); ?>
          <div class="row search-item">
            <div class="col-md-10 col-md-offset-1 col-sm-12 col-sm-offset-0 search-item-content">
              <span class="search-content__type"><?php echo $item['type']; ?></span>
              <h4 class="search-content__title">
                <a class="search-content__link" href="<?php the_permalink(); ?>">
                  <?php the_title(); ?>
                </a>
              </h4>
              <div class="search-content__body">
                <div class="metadata">
                  <span class="time"><?php echo get_the_date();?></span>
                </div>
                <p class="search-content__info"><?php the_excerpt(); ?></p>
              </div>
            </div>
          </div>
        <?php
        // End the loop.
        endwhile;
        wp_reset_postdata();
        ?>
    </div>

    <div class="navigation pagination">
        <div class="nav-links">
            <?php if (get_previous_posts_link()): ?>
                <a href="<?php previous_posts(); ?>" class="next page-numbers"><span class="fa fa-chevron-left" title="<?php _e('Previous page', 'sixodp'); ?>"></span></a>
            <?php endif; ?>
            <?php if (get_next_posts_link()): ?>
                <a href="<?php next_posts(); ?>" class="prev page-numbers"><span class="fa fa-chevron-right" title="<?php _e('Next page', 'sixodp'); ?>"></span></a>
            <?php endif; ?>
        </div>
    </div>
    </div>
  </div>
</div>
