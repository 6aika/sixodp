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
            <div class="input-group-btn">
              <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span id="selected-domain" data-value="/dataset"><?php _e('Datasets', 'sixodp');?> </span>&nbsp;<span class="fa fa-chevron-down"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-right">
                <li><a data-value="/dataset"><?php _e('Datasets', 'sixodp');?> </a></li>
                <li><a data-value="/showcase"><?php _e('Applications', 'sixodp');?> </a></li>
                <li role="separator" class="divider"></li>
                <li><a data-value="/posts"><?php _e('Other', 'sixodp');?> </a></li>
              </ul>
            </div><!-- /btn-group -->
            <input type="text" id="q" class="form-control input-lg" aria-label="..." placeholder="<?php _e('Keyword', 'sixodp');?>">
            <span class="input-group-btn">
              <button id="search" class="btn btn-secondary--search" type="button"><?php _e('Search', 'sixodp');?> </button>
            </span>
          </div><!-- /input-group -->
        </div>
      </div>
      <?php include(locate_template('/partials/stats.php')); ?>
    </div>
  </div> <!-- end hero__inner in categories -->
</div> <!-- end hero in categories -->
