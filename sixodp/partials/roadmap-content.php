<?php
  /**
  * Roadmap page content partial.
  */
?>

<div class="page-body">
  <h1 class="heading-main"><?php the_title(); ?></h1>
  <div class="article"><?php the_content(); ?></div>

  <?php
    // Get the board id for retrieving the lists
    $board_id = get_option('wptsettings_settings')['wptsettings_helper_boards'];

    // Render the widget
    echo do_shortcode('[wp-trello type="lists" id="'.$board_id.'" link="yes"]');
  ?>
</div>