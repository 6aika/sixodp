<?php
  /**
  * Ajankohtaista links
  */
?>

<div class="wrapper--featured">
  <div class="container">
    <h2 class="heading-page"><?php _e('Categories', 'sixodp') ?></h2>

    <div class="row boxlinks">
      <?php
        $count = 0;
        $parent_category = get_translated_category_by_slug('ajankohtaista');

        if ($parent_category) {
          foreach ( get_categories(array('parent' => $parent_category->cat_ID, 'hide_empty' => false)) as $category ) :
            $boxlink_url      = get_category_link($category->term_id);
            $boxlink_heading  = $category->name;

            $bg = category_image_src(array(
              'term_id' => $category->term_id
            ));
            
            if ( $bg && $count < 3 ) {
              echo '<div class="boxlink" style="background-image: url('.$bg.');"><div class="boxlink__footer"><h2 class="heading">';
              echo '<a href="'.$boxlink_url.'">'.$boxlink_heading.'</a></h2>';
              echo '<a href="'.$boxlink_url.'" class="round"><i class="material-icons">arrow_forward</i></a>';
              echo '</div></div>';
              $count++;
            }

          endforeach;
        }
      ?>
    </div>
  </div>
</div>