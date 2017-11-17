<?php
/**
* Hero -partial
*/
?>

<div class="hero" style="background-image: url(<?php echo get_field('frontpage_background')['url']; ?>);">
  <div class="hero__inner">
    <?php
      get_template_part('partials/hero-logos');
    ?>
    <div class="container">
      <div class="row">
        <h1 class="heading-main text-center"><?php echo bloginfo('title') ?></h1>
        <h3 class="subheading text-center"><?php  echo bloginfo('description') ?></h3>

        <div class="col-md-8 col-md-offset-2">
          <div class="input-group">
            <div class="input-group-btn">
              <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span id="selected-domain" data-value="/dataset"><?php _e('Datasets', 'sixodp');?> </span> <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-right">
                <li><a data-value="/dataset"><?php _e('Datasets', 'sixodp');?> </a></li>
                <li><a data-value="/showcase"><?php _e('Applications', 'sixodp');?> </a></li>
                <li><a data-value="/collection"><?php _e('Collections', 'sixodp');?> </a></li>
                <li><a data-value="/posts"><?php _e('Articles', 'sixodp');?> </a></li>
                <li role="separator" class="divider"></li>
                <li><a data-value="/posts"><?php _e('Other', 'sixodp');?> </a></li>
              </ul>
            </div><!-- /btn-group -->
            <input type="text" id="q" class="form-control input-lg" aria-label="...">
            <span class="input-group-btn">
              <button id="search" class="btn btn-secondary" type="button"><?php _e('Search', 'sixodp');?> </button>
            </span>
          </div><!-- /input-group -->
        </div>
      </div>
    </div>
