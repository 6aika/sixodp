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
            $lang = get_current_locale_ckan();
            $url       = CKAN_BASE_URL. '/' . $lang . '/group/'.$name;
            $image_url = $category['image_display_url'];
            $package_count = $category['package_count']; ?>
            <div class="category__wrapper">
              <div class="category--box">
                <a href="<?php echo $url; ?>" class="category__link">
                  <div class="category__link-content">
                    <span class="category__icon"><?php echo file_get_contents($image_url); ?></span>
                    <span class="category__name"><?php echo $title; ?></span>
                    <span class="category__count"><?php echo $package_count; ?></span>
                  </div>
                </a>
              </div>
            </div><?php
          endforeach;
        ?>
      </div>
    </div>
    <div class="row justify-content-center" style="padding: 32px 0;">
      <a style="width: auto" href="<?php echo CKAN_BASE_URL; ?>/<?php echo get_current_locale_ckan(); ?>/dataset" class="btn btn-transparent btn-categories btn--banner"><?php _e('All datasets', 'sixodp');?></a>
    </div>
  </div>
</div>
