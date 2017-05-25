<?php
  /**
  * Category list partial.
  */
?>

    <div class="wrapper wrapper--categories">
      <div class="container">
        <div class="row">
          <div class="categories">
            <?php
              foreach(get_ckan_categories() as $category) : 
                $title     = get_translated($category, 'title');
                $name      = $category['name'];
                $url       = CKAN_BASE_URL.'/groups/'.$name;
                $image_url = $category['image_display_url'];
                $package_count = $category['package_count']; ?>
                <div class="category__wrapper">
                  <div class="category">
                    <a href="<?php echo $url; ?>" class="category__link">
                      <img class="category__icon" src="<?php echo $image_url; ?>">
                      <span class="category__name"><?php echo $title; ?></span>
                      <span class="category__count"><?php echo $package_count; ?></span>
                    </a>
                  </div>
                </div><?php
              endforeach;
            ?>
          </div>
        </div>
      </div>
      <?php include(locate_template('/partials/stats.php')); ?>
    </div>
  </div> <!-- end hero__inner in categories -->
</div> <!-- end hero in categories -->
