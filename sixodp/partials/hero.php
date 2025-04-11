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
              <button type="button" class="btn btn-tertiary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span id="selected-domain" data-value="/dataset"><?php _e('Datasets', 'sixodp');?> </span>&nbsp;<span class="fa fa-chevron-down"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-right">
                <li ><a class="dropdown-item" data-value="/dataset"><?php _e('Datasets', 'sixodp');?> </a></li>
                <li><a class="dropdown-item" data-value="/showcase"><?php _e('Applications', 'sixodp');?> </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" data-value="/posts"><?php _e('Other', 'sixodp');?> </a></li>
              </ul>
            <input type="text" id="q" class="form-control form-control-lg" aria-label="..." placeholder="<?php _e('Keyword', 'sixodp');?>">
            <button id="search" class="btn btn-tertiary--search" type="button"><?php _e('Search', 'sixodp');?> </button>
          </div><!-- /input-group -->
        </div>
      </div>
      <?php include(locate_template('/partials/stats.php')); ?>
    </div>
  </div> <!-- end hero__inner in categories -->
</div> <!-- end hero in categories -->
