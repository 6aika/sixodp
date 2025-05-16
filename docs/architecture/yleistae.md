# General

The portal consists of two services: WordPress and CKAN, which are installed side by side behind NGINX server. Application architecture is depicted in the following figure.

<figure><img src="../.gitbook/assets/Application architecture (1).png" alt=""><figcaption></figcaption></figure>

WordPress is executed with [PHP FPM](https://www.php.net/manual/en/install.fpm.php) and CKAN with [uWSGI](https://uwsgi-docs.readthedocs.io/en/latest/). Additional to these, the service has multiple databases: WordPress requires MySQL and CKAN requires PostgreSQL with additionally required Redis-database and Solr search index.
