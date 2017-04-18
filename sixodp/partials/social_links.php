<div class="row">
  <div class="col-xs-12">
    <div class="footer-follow-links">
      <?php dynamic_sidebar( 'footer_widgets' ); ?>
      <span class="footer-follow-title">Seuraa meitä</span>
       <?php
            foreach(wp_get_nav_menu_items('socialmedia') as $item) {
                $socialiconclass = '';
                $service = $item->post_name;
                 if($service == 'facebook') {$socialiconclass = 'icon-facebook-sign';}
                 else if($service == 'twitter') {$socialiconclass = 'icon-twitter-sign';}
                 else if($service == 'youtube') {$socialiconclass = 'icon-youtube-sign';}
                 else if($service == 'rss') {$socialiconclass = 'icon-rss-sign';}
                 else if($service == 'tumblr') {$socialiconclass = 'icon-tumblr-sign';}
                 else if($service == 'github') {$socialiconclass = 'icon-github-sign';}
                 else if($service == 'instagram') {$socialiconclass = 'icon-instagram';}
                 else if($service == 'linkedin') {$socialiconclass = 'icon-linkedin-sign';}
                 else if($service == 'flickr') {$socialiconclass = 'icon-flickr';}
                 else {$socialiconclass = 'icon-external-link-sign';} ?>
                    <a href="<?php echo $item->url; ?>" title="<?php echo $item->title; ?>" class="footer-follow-link"><i class="<?php echo $socialiconclass; ?> icon-2x"></i></a>
       <?php } ?>
    </div>
  </div>
</div>
