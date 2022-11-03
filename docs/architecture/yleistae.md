# General

The portal consists of two services: WordPress and CKAN, which are installed side by side behind NGINX server. Application architecture is depicted in the following figure.

![Application architecture](../.gitbook/assets/application-architecture.png)

WordPress is executed with [PHP FPM](https://www.php.net/manual/en/install.fpm.php) and CKAN with Apaches [WSGI ](https://modwsgi.readthedocs.io/en/master/)module. In the future Apache will probably be replaced with [uWSGI](https://uwsgi-docs.readthedocs.io/en/latest/). Additional to these, the service has multiple databases: WordPress requires MySQ and CKAN requires PostgreSQL with additionally required Redis-database and Solr search index.

