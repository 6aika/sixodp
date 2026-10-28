<?php
/**
* Hero -partial
*/
?>

<div class="hero" style="background-image: url(<?php echo get_field('frontpage_background')['url']; ?>);">
  <div class="hero__inner">
    <div class="container">
      <div class="row">
        <h1 class="heading-main text-center"><?php echo bloginfo('title') ?></h1>
        <h2 class="subheading text-center"><?php  echo bloginfo('description') ?></h2>
      </div>
    </div>
  </div> <!-- end hero__inner in categories -->
</div> <!-- end hero in categories -->
