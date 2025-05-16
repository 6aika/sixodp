# WordPress

WordPress is used with custom built theme available in [https://github.com/6aika/sixodp/tree/master/sixodp](https://github.com/6aika/sixodp/tree/master/sixodp). Following list on plugins are installed with the theme:

* [Advanced Custom Fields](https://www.advancedcustomfields.com/), adds new fields to posts.
* [Custom Twitter Feed](https://smashballoon.com/custom-twitter-feeds/), handles twitter feeds.
* [Polylang](https://polylang.pro/), handles localization of content to different languages.
* [Disqus Comment System](https://wordpress.org/plugins/disqus-comment-system/), adds disqus comments to posts.
* [ReCaptcha Integration for WordPress](https://wordpress.org/plugins/wp-recaptcha-integration/), adds recaptcha checks to anonymous data and showcase requests.
* [WordPress Importer](https://fi.wordpress.org/plugins/wordpress-importer/), allows importing WP content from other site. Used in the past but could be removed.
* [WP User Avatars](https://fi.wordpress.org/plugins/wp-user-avatars/), adds avatar images on articles.
* [Classic Editor](https://fi.wordpress.org/plugins/classic-editor/), adds the old wysiwyg editor.
* [Yoast SEO](https://fi.wordpress.org/plugins/wordpress-seo/), adds search engine optimization features.
* [Wordfence](https://wordpress.org/plugins/wordfence/), securing WordPress.
* [WP Mail SMTP](https://wordpress.org/plugins/wp-mail-smtp/), configuring WP to send emails via service

Following plugins are modified in some way and as such not installed through WordPress plugin repository:

* Contact Widgets ([https://github.com/6aika/sixodp/tree/master/wordpress/contact-widgets](https://github.com/6aika/sixodp/tree/master/wordpress/contact-widgets)), adds managings of social media profiles.
* Menu routes for WordPress ([https://github.com/6aika/wp-api-menus](https://github.com/6aika/wp-api-menus)), provides menus in api for CKAN.

### Installation

WordPress is installed by running ansible ([https://github.com/6aika/sixodp/tree/master/ansible/roles/wordpress](https://github.com/6aika/sixodp/tree/master/ansible/roles/wordpress)). Installation is done with the help of WP CLI.
