<?php
  /**
  * search-content for ckan data.
  */

  global $wp_query;
  $page_size = 12;
  $page_index = get_query_var('page', 1) - 1;
  $offset = (int)$page_index * $page_size;
  $searchterm = trim($_GET['s']);
  $baseurl = CKAN_API_URL;
  $url = $baseurl."/action/package_search?q=".$searchterm;
  $data_dataset = get_ckan_data($url."&fq=dataset_type:dataset&rows=" . (string)$page_size . '&start=' . $offset);
  $data_dataset = $data_dataset['result'];
  $data_showcase = get_ckan_data($url."&fq=dataset_type:showcase&rows=" . (string)$page_size . '&start=' . $offset);
  $data_showcase = $data_showcase['result'];
  $count = $wp_query->found_posts;
?>

<div class="row">
  <div class="sidebar col-md-3 col-sm-12">
    <h3 class="heading-sidebar"><?php _e('Results in groups', 'sixodp');?></h3>
    <ul>
      <li class="sidebar-item<?php if (!isset($_GET['showcase'])){ echo ' active';} ?>">
        <a href="<?php echo get_site_url(); ?>/<?php echo get_current_locale() ?>/?s=<?php echo $searchterm;?>&datasearch" title="<?php _e('Datasets', 'sixodp');?>">
          <span class="sidebar-icon-wrapper"><span class="fa fa-long-arrow-right"></span></span>
          <?php _e('Datasets', 'sixodp');?>&nbsp;(<?php echo $data_dataset['count']; ?>)
        </a>
      </li>
      <li class="sidebar-item<?php if (isset($_GET['showcase'])){ echo ' active';} ?>">
        <a href="<?php echo get_site_url(); ?>/<?php echo get_current_locale() ?>/?s=<?php echo $searchterm;?>&datasearch&showcase" title="<?php _e('Applications', 'sixodp');?>">
          <span class="sidebar-icon-wrapper"><span class="fa fa-long-arrow-right"></span></span>
          <?php _e('Applications', 'sixodp');?>&nbsp;(<?php echo $data_showcase['count']; ?>)
        </a>
      </li>
      <li class="sidebar-item">
        <a href="<?php echo get_site_url(); ?>/<?php echo get_current_locale() ?>/?s=<?php echo $searchterm;?>" title="<?php _e('Others', 'sixodp');?>">
          <span class="sidebar-icon-wrapper"><span class="fa fa-long-arrow-right"></span></span>
          <?php _e('Others', 'sixodp');?>&nbsp;(<?php echo $count; ?>)
        </a>
      </li>
    </ul>
  </div>
  <div class="col-md-9 col-sm-12 search-container border-left">
    <?php
      $type = 'dataset';
      if(isset($_GET['showcase'])) {
        $results = $data_showcase;
        $type = 'showcase';
        $heading = esc_html( _n('Search results: Applications (%d)', 'Search results: Applications (%d)', $results['count'], 'sixodp') );
      }
      else {
        $results = $data_dataset;
        $heading = esc_html( _n('Search results: Datasets (%d)', 'Search results: Datasets (%d)', $results['count'], 'sixodp') );
      }
    ?>
    <div class="search-options">
      <h3 class="search-results-heading"><?php printf($heading, $results['count']) ?></h3>
    </div>
    <div>
      <?php foreach ( $results['results'] as $result ) : ?>
        <div class="row search-item">
          <div class="col-md-10 col-md-offset-1 col-sm-12 col-sm-offset-0 search-item-content">
            <span class="search-content__type"><?php echo $item['type']; ?></span>
            <h4 class="search-content__title">
              <a class="search-content__link" href="<?php echo CKAN_BASE_URL ?>/<?php echo get_lang(); ?>/<?php echo $type ?>/<?php echo $result['name']; ?>">
                <?php echo get_translated($result, 'title'); ?>
              </a>
            </h4>
            <div class="search-content__body">
              <div class="metadata">
                <span class="time">
                  <?php
                    if (isset($result['date_updated'])) {
                      $date = DateTime::createFromFormat('Y-m-j', $result['date_updated']);
                      echo $date->format('d.m.Y');
                    }
                  ?>
                </span>
              </div>
              <p class="search-content__info"><?php echo wp_html_excerpt( strip_shortcodes(render_markdown(get_translated($result, 'notes'))), 300, '...'); ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

      <div class="navigation pagination">
          <div class="nav-links">
              <?php if (get_previous_page_link()): ?>
              <a href="<?php echo get_previous_page_link(); ?>" class="next page-numbers"><span class="fa fa-chevron-left" title="<?php _e('Previous page', 'sixodp'); ?>"></span></a>
              <?php endif; ?>
              <?php if (get_next_page_link($results['count'], $page_size)): ?>
              <a href="<?php echo get_next_page_link($results['count'], $page_size); ?>" class="prev page-numbers"><span class="fa fa-chevron-right" title="<?php _e('Next page', 'sixodp'); ?>"></span></a>
              <?php endif; ?>
          </div>
      </div>
  </div>
</div>

