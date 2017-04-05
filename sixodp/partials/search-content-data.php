<?php
  /**
  * search-content content box on.
  */

  global $wp_query;
  $baseurl = "https://generic-qa.dataportaali.com";
  $url = $baseurl."/data/api/action/package_search?q=".trim($_GET['s']);
  $data_dataset = get_ckan_data($url."&fq=dataset_type:dataset");
  $data_dataset = $data_dataset['result'];
  $data_showcase = get_ckan_data($url."&fq=dataset_type:showcase");
  $data_showcase = $data_showcase['result'];
?>
<div class="container">
  <div class="row">
      <div class="col-md-4 search-content">
        <div class="results-container">
            <div class="heading">
                <span>Hakutuloksia ryhmissä</span>
            </div>
            <span class="result">
                Tietoaineistot
                <span><?php echo $data_dataset['count']; ?></span>
            </span>
            <span class="result">
                Sovellukset
                <span><?php echo $data_showcase['count']; ?></span>
            </span>
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
              <?php

              foreach ( $results['results'] as $result ) : ?>
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
                    <p class="search-content__info">Asiasanat: <?php foreach($result['keywords']['fi'] as $keyword) {
                            echo $keyword.", ";
                    } ?></p>
                  </div>
                </div>
              </li>
              <?php endforeach; ?>
          </ul>
      </div>
  </div>
</div>
