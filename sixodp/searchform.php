<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
  <label>
    <input type="search" class="form-control input-lg"
        placeholder="<?php echo esc_attr_x( 'Search…', 'placeholder', 'sixodp' ) ?>"
        value="<?php echo get_search_query() ?>" name="s"
        title="<?php echo esc_attr_x( 'Search for', 'label', 'sixodp' ) ?>" />
  </label>
  <button type="submit" class="btn btn-lg btn-primary"><?php _e('Search', 'sixodp');?></button>
</form>
