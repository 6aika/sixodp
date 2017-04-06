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
<div class="container">
  <div class="row">
      <div class="col-md-4 search-content">
          <div class="filters secondary">
              <div>
                  <section class="module module-narrow module-shallow">
                      <h2 class="module-heading">
                        <i class="icon-medium icon-filter"></i>
                        Hakutuloksia ryhmissä
                      </h2>
                      <nav>
                          <ul class="unstyled nav nav-simple nav-facet filtertype-res_format">
                              <li class="nav-item">
                                  <a href="<?php echo get_site_url(); ?>?s=<?php echo $searchterm;?>&datasearch" title=""> <span>Tietoaineistot (<?php echo $data_dataset['count']; ?>)</span></a>
                              </li>
                              <li class="nav-item">
                                  <a href="<?php echo get_site_url(); ?>?s=<?php echo $searchterm;?>&datasearch&showcase" title=""> <span>Sovellukset (<?php echo $data_showcase['count']; ?>)</span></a>
                              </li>
                              <li class="nav-item">
                                  <a href="<?php echo get_site_url(); ?>?s=<?php echo $searchterm;?>" title=""> <span>Muut (<?php  echo $searchcount; ?>)</span></a>
                              </li>
                          </ul>
                      </nav>
                      <p class="module-footer"> </p>
                  </section>
              </div>
          </div>
      </div>
      <div class="col-md-8 search-content">
        <?php
            if(isset($_GET['showcase'])) {
               $results = $data_showcase;
            } else {
               $results = $data_dataset;
            }
        ?>
        <h3 class="heading">Hakutuloksia <?php echo $results['count']; ?> kappaletta</h3>
            <ul class="search-content__list">
              <?php foreach ( $results['results'] as $result ) : ?>
              <li class="search-content">
                <div class="search-content__content">
                  <span class="search-content__type"><?php echo $item['type']; ?></span>
                  <h4 class="search-content__title">
                    <a class="search-content__link" href="/data/dataset/<?php echo $result['name']; ?>">
                      <?php echo $result['name']; ?>
                    </a>
                  </h4>
                  <div class="search-content__body">
                    <div class="metadata">
                        <span class="time">
                            <?php echo $result['date_updated'];?>
                        </span>
                    </div>
                    <p class="search-content__info"><?php if(isset($result['notes_translated']['fi'])) {echo $result['notes_translated']['fi']; } ?></p>
                  </div>
                </div>
              </li>
              <?php endforeach; ?>
          </ul>
      </div>
  </div>
</div>
