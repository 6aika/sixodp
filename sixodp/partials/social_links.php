<div class="row">
  <div class="col-xs-12">
    <div class="footer-follow-links">
      <span class="footer-follow-title"><?php _e('Follow us', 'sixodp');?></span>
       <?php
          foreach(wp_get_nav_menu_items('socialmedia') as $item) {
            $socialiconclass = '';
            $service = strtolower($item->post_title);
             if($service == 'facebook') {$socialiconclass = 'fa fa-facebook-square';}
             else if($service == 'twitter') {$socialiconclass = 'fa fa-twitter-square';}
             else if($service == 'youtube') {$socialiconclass = 'fa fa-youtube-square';}
             else if($service == 'rss') {$socialiconclass = 'fa fa-rss-square';}
             else if($service == 'tumblr') {$socialiconclass = 'fa fa-tumblr-square';}
             else if($service == 'github') {$socialiconclass = 'fa fa-github-square';}
             else if($service == 'instagram') {$socialiconclass = 'fa fa-instagram';}
             else if($service == 'linkedin') {$socialiconclass = 'fa fa-linkedin-square';}
             else if($service == 'flickr') {$socialiconclass = 'fa fa-flickr';}
             else if($service == 'slideshare') {$socialiconclass = 'fa fa-slideshare';}
             else if($service == 'newsletter') {$socialiconclass = 'fa fa-news-o';}
             else if($service == 'speakerdeck') {$socialiconclass = 'fa fa-caret-square-o-right';}
             else {$socialiconclass = 'fa fa-external-link-square';} ?>
                <a href="<?php echo $item->url; ?>" title="<?php echo $item->title; ?>" class="footer-follow-link"><i class="<?php echo $socialiconclass; ?> fa-2x"></i></a>
       <?php } ?>
    </div>
  </div>
</div>
