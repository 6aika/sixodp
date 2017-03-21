<?php
  /**
  * Featured content box on Tuki frontpage.
  */
?>

<?php $bg = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'page')[0]; ?>

<div class="wrapper--contentbox" style="background-image: url(<?php echo $bg; ?>);">
  <div class="wrapper--inner-contentbox">
    <div class="container">
      <div class="row"><?php
        foreach ( get_tuki_links() as $tuki_page ) : ?>
          <div class="contentbox col-md-4">
            <h1 class="heading">
              <a class="contentbox__link" href="<?php echo $tuki_page->post_name; ?>">
                <?php echo $tuki_page->post_title; ?>
              </a>
            </h1>
            <?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($tuki_page->ID), 'page');  ?>
            <div class="contentbox__img-wrapper">
              <img src="<?php echo $thumb[0]; ?>" alt="thumb">
            </div>
            <div class="contentbox__body">
              <p class="contentbox__desc" style="float:left;width:90%;font-size: 18px;">
                <a class="contentbox__link" href="<?php echo $tuki_page->post_name; ?>"><?php echo get_field("page_description", $tuki_page->ID); ?></a>
              </p>
              <p style="float:right;width:10%;">
                <a class="contentbox__link" href="<?php echo $tuki_page->post_name; ?>">-></a>
              </p>
              <span class="clearfix"></span>
            </div>    
          </div><?php
        endforeach; ?>
      </div>
    </div>
  </div>
</div>