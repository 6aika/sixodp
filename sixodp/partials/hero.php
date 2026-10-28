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
          <div class="search-container">
              <div class="input-group">
                  <input type="text" id="q" class="form-control form-control-lg" aria-label="..." placeholder="<?php _e('Keyword', 'sixodp');?>">
                  <button id="search" class="btn btn-tertiary--search" type="button"><?php _e('Search', 'sixodp');?> </button>
              </div><!-- /input-group -->
          </div>
      </div>
    </div>
  </div> <!-- end hero__inner in categories -->
</div> <!-- end hero in categories -->
