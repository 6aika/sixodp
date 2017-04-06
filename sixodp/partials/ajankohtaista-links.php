<?php
  /**
  * Ajankohtaista links
  */
?>

<div class="wrapper--gray">
  <div class="container">
    <div class="row boxlinks">
      <?php
        $count = 0;
        foreach ( get_child_pages('Ajankohtaista') as $child_page ) :
          $boxlink_url      = get_permalink($child_page);
          $boxlink_heading  = $child_page->post_title;
          $bg = wp_get_attachment_image_src( get_post_thumbnail_id($child_page->ID), 'page')[0];
          
          if ( $bg && $count < 3 ) {
            echo '<div class="boxlink" style="background-image: url('.$bg.');"><div class="boxlink__footer"><h2 class="heading">';
            echo '<a href="'.$boxlink_url.'">'.$boxlink_heading.'</a></h2>';
            echo '<span class="round"><i class="material-icons">arrow_forward</i></span>';
            echo '</div></div>';
            $count++;
          }

        endforeach;
      ?>
    </div>
  </div>
</div>