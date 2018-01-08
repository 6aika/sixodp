<?php
  /**
  * search-content for ckan data.
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

<div class="row">
  <div class="search-sidebar col-md-3 col-sm-12">
    <h3 class="heading-sidebar"><?php _e('Results in groups', 'sixodp');?></h3>
    <ul>
      <li class="sidebar-item">
        <a href="<?php echo get_site_url(); ?>/<?php echo get_current_locale() ?>/?s=<?php echo $searchterm;?>&datasearch" title="<?php _e('Datasets', 'sixodp');?>">
          <?php _e('Datasets', 'sixodp');?>&nbsp;(<?php echo $data_dataset['count']; ?>)
        </a>
      </li>
      <li class="sidebar-item">
        <a href="<?php echo get_site_url(); ?>/<?php echo get_current_locale() ?>/?s=<?php echo $searchterm;?>&datasearch&showcase" title="<?php _e('Applications', 'sixodp');?>">
          <?php _e('Applications', 'sixodp');?>&nbsp;(<?php echo $data_showcase['count']; ?>)
        </a>
      </li>
      <li class="sidebar-item">
        <a href="<?php echo get_site_url(); ?>/<?php echo get_current_locale() ?>/?s=<?php echo $searchterm;?>" title="<?php _e('Others', 'sixodp');?>">
          <?php _e('Others', 'sixodp');?>&nbsp;(<?php echo $searchcount; ?>)
        </a>
      </li>
    </ul>
  </div>
  <div class="col-md-9 col-sm-12 search-container">
    <?php
      $type = 'dataset';
      if(isset($_GET['showcase'])) {
        $results = $data_showcase;
        $type = 'showcase';
      }
      else {
        $results = $data_dataset;
      }
    ?>
    <h3 class="search-results-heading"><?php printf( esc_html( _n( 'Found %d result', 'Found %d results', $results['count'], 'sixodp' ) ), $results['count'] ); ?></h3>
        <ul class="search-content__list">
          <?php foreach ( $results['results'] as $result ) : ?>
          <li class="search-content">
            <div class="search-content__content">
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
                <p class="search-content__info"><?php echo wp_trim_words( get_translated($result, 'notes'), 55, '...' ); ?></p>
              </div>
            </div>
          </li>
          <?php endforeach; ?>
      </ul>
  </div>
</div>
