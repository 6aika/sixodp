<?php
  /**
  * Featured posts partial.
  */
?>

<div class="container--home container--padded container--inverse">
    <div class="container">
      <div class="flex">
        <div>
          <h3 class="highlight-header">Uusin sisältö</h3>
          <ul class="topic-list">
            <?php foreach ( get_recent_content() as $item ) : ?>
              <li class="topic-list-item">
                <a href="/dataset/tampereen-rakennukset-alueina">
                  <h4 class="topic-header color-primary"><?php echo $item['title']; ?></h4>
                  <span class="topic-timestamp"><?php echo parse_date($item['metadata_modified']); ?></span>
                  <span class="topic-type"><?php echo $item['type']; ?></span>
                  <p class="topic-info"><?php echo $item['notes']; ?></p>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
