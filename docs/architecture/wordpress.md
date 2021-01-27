# WordPress

WordPress is used with custom built theme available in [https://github.com/6aika/sixodp/tree/master/sixodp](https://github.com/6aika/sixodp/tree/master/sixodp). Following list on plugins are installed with the theme:

* [Advanced Custom Fields](https://www.advancedcustomfields.com/)
* [Custom Twitter Feed](https://smashballoon.com/custom-twitter-feeds/)
* [AddThis](https://www.addthis.com/)
* [Polylang](https://polylang.pro/)
* [Google Analytics dashboard for WP](https://fi.wordpress.org/plugins/google-analytics-dashboard-for-wp/)
* [Disqus Comment System](https://wordpress.org/plugins/disqus-comment-system/)
* [ReCaptcha Integration for WordPress](https://wordpress.org/plugins/wp-recaptcha-integration/)
* [WordPress Importer](https://fi.wordpress.org/plugins/wordpress-importer/)
* [WP User Avatar](https://fi.wordpress.org/plugins/wp-user-avatar/)
* [Classic Editor](https://fi.wordpress.org/plugins/classic-editor/)
* [Yoast SEO](https://fi.wordpress.org/plugins/wordpress-seo/)

Following plugins are modified in some way and as such not installed through WordPress plugin repository:

* WP Trello \([https://github.com/6aika/wp-trello](https://github.com/6aika/wp-trello)\)
* Contact Widgets \([https://github.com/6aika/sixodp/tree/master/wordpress/contact-widgets](https://github.com/6aika/sixodp/tree/master/wordpress/contact-widgets)\)
* Menu routes for WordPress \([https://github.com/6aika/wp-api-menus](https://github.com/6aika/wp-api-menus)\)

### Installation

WordPress is installed by running ansible \([https://github.com/6aika/sixodp/tree/master/ansible/roles/wordpress](https://github.com/6aika/sixodp/tree/master/ansible/roles/wordpress)\). Installation is done with the help of WP CLI. Currently using old 1.5.1 cli version as Polylang CLI development was abandoned \([https://github.com/diggy/polylang-cli/issues/118](https://github.com/diggy/polylang-cli/issues/118)\) 

