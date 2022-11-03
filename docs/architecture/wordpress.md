# WordPress

WordPress is used with custom built theme available in [https://github.com/6aika/sixodp/tree/master/sixodp](https://github.com/6aika/sixodp/tree/master/sixodp). Following list on plugins are installed with the theme:

* [Advanced Custom Fields](https://www.advancedcustomfields.com/), adds new fields to posts.
* [Custom Twitter Feed](https://smashballoon.com/custom-twitter-feeds/), handles twitter feeds.
* [AddThis](https://www.addthis.com/), adds social media sharing buttons to content.
* [Polylang](https://polylang.pro/), handles localization of content to different languages.
* [Google Analytics dashboard for WP](https://fi.wordpress.org/plugins/google-analytics-dashboard-for-wp/), adds google analytics intergration,
* [Disqus Comment System](https://wordpress.org/plugins/disqus-comment-system/), adds disqus comments to posts.
* [ReCaptcha Integration for WordPress](https://wordpress.org/plugins/wp-recaptcha-integration/), adds recaptcha checks to anonymous data and showcase requests.
* [WordPress Importer](https://fi.wordpress.org/plugins/wordpress-importer/), allows importing WP content from other site. Used in the past but could be removed.
* [WP User Avatar](https://fi.wordpress.org/plugins/wp-user-avatar/), adds avatar images on articles.
* [Classic Editor](https://fi.wordpress.org/plugins/classic-editor/), adds the old wysiwyg editor.
* [Yoast SEO](https://fi.wordpress.org/plugins/wordpress-seo/), adds search engine optimization features.

Following plugins are modified in some way and as such not installed through WordPress plugin repository:

* WP Trello \([https://github.com/6aika/wp-trello](https://github.com/6aika/wp-trello)\), provides integration to trello boards.
* Contact Widgets \([https://github.com/6aika/sixodp/tree/master/wordpress/contact-widgets](https://github.com/6aika/sixodp/tree/master/wordpress/contact-widgets)\), adds managings of social media profiles.
* Menu routes for WordPress \([https://github.com/6aika/wp-api-menus](https://github.com/6aika/wp-api-menus)\), provides menus in api for CKAN.

### Installation

WordPress is installed by running ansible \([https://github.com/6aika/sixodp/tree/master/ansible/roles/wordpress](https://github.com/6aika/sixodp/tree/master/ansible/roles/wordpress)\). Installation is done with the help of WP CLI. Currently using old 1.5.1 cli version as Polylang CLI development was abandoned \([https://github.com/diggy/polylang-cli/issues/118](https://github.com/diggy/polylang-cli/issues/118)\) 

