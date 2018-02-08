<div id="page-highlight-carousel" class="carousel slide mobile-only" data-ride="carousel" data-interval="false">
  <div class="carousel-inner" role="listbox">
    <?php foreach($cards as $card) : ?>
    <?php $extra_classes = $card === reset($items) ? ' active' : ''; ?>

      <div class="item<?php echo $extra_classes ?>">
        <?php
        $item = array(
          'image_url' => get_post_thumbnail_url($post),
          'title' => $post->post_title,
          'show_rating' => false,
          'date_updated' => $post->post_date,
          'notes' => $post->post_content,
          'url' => get_the_permalink(),
        );
        include(locate_template( 'partials/card-image.php' ));
        ?>
      </div>
    <?php endforeach; ?>
  </div>

  <a class="left carousel-control" href="#page-highlight-carousel" role="button" data-slide="prev">
    <span class="fa fa-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#page-highlight-carousel" role="button" data-slide="next">
    <span class="fa fa-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>

  <ol class="carousel-indicators">
    {% for item in items %}
    {%- set extra_classes = ' active' if loop.index == 1 else ''  -%}
    <li data-target="#page-highlight-carousel" data-slide-to="{{ loop.index - 1 }}" class="{{extra_classes}}"></li>
    {% endfor %}
  </ol>
</div>