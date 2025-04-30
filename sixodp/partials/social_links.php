<div class="row">
  <div class="col-xs-12">
    <div class="footer-follow-links">
      <span class="footer-follow-title"><?php _e('Follow us', 'sixodp');?></span>
       <?php
          foreach(wp_get_nav_menu_items('socialmedia') as $item) {
            $socialiconclass = '';
            $service = strtolower($item->post_title);
             if($service == 'facebook') {$socialiconclass = 'fa-brands fa-square-facebook';}
             else if($service == 'twitter') {$socialiconclass = 'fa-brands fa-square-x-twitter';}
             else if($service == 'x') {$socialiconclass = 'fa-brands fa-square-x-twitter';}
             else if($service == 'youtube') {$socialiconclass = 'fa-brands fa-youtube-square';}
             else if($service == 'rss') {$socialiconclass = 'fa-solid fa-square-rss';}
             else if($service == 'tumblr') {$socialiconclass = 'fa-brands fa-square-tumblr';}
             else if($service == 'github') {$socialiconclass = 'fa-brands fa-square-github';}
             else if($service == 'instagram') {$socialiconclass = 'fa-brands fa-square-instagram';}
             else if($service == 'linkedin') {$socialiconclass = 'fa-brands fa-linkedin';}
             else if($service == 'flickr') {$socialiconclass = 'fa-brands fa-flickr';}
             else if($service == 'slideshare') {$socialiconclass = 'fa-brands fa-slideshare';}
             else if($service == 'newsletter') {$socialiconclass = 'fa-solid fa-envelope';}
             else if($service == 'uutiskirje') {$socialiconclass = 'fa-solid fa-envelope';}
             else if($service == 'speakerdeck') {$socialiconclass = 'fa-brands fa-speaker-deck';}
             else if($service == 'mastodon') {$socialiconclass = 'fa-brands fa-mastodon';}
             else if($service == 'bluesky') {$socialiconclass = 'fa-brands fa-bluesky';}
             else {$socialiconclass = 'fa-solid fa-external-link-square';} ?>
                <a href="<?php echo $item->url; ?>" title="<?php echo $item->title; ?>" class="footer-follow-link"><i class="<?php echo $socialiconclass; ?> fa-2x"></i></a>
       <?php } ?>
    </div>
  </div>
</div>
