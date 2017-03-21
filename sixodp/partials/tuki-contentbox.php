<?php
  /**
  * Featured content box on Tuki frontpage.
  */
?>

<div class="wrapper--contentbox">
  <div class="wrapper--innter-contentbox">
    <div class="container">
      <div class="row"><?php
        foreach ( get_tuki_links() as $tuki_page ) : ?>
          <div class="contentbox col-md-4">
            <h3 class="title">
              <a href="<?php echo $tuki_page->post_name; ?>">
                <?php echo $tuki_page->post_title; ?>
              </a>
              <?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($tuki_page->ID), 'page');  ?>
              <div class="contentbox__img-wrapper">
                <img src="<?php echo $thumb[0]; ?>" alt="thumb">
              </div>
            </h3>    
          </div><?php
        endforeach; ?>

      </div>
    </div>
  </div>
</div>