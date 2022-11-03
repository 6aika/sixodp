# Technical documentation for data portal

## The Data portal

The data portal consists of two services: [WordPress](https://wordpress.org/) for content management and [CKAN](https://ckan.org/) publishing data. Services are built in such way that the front page, news, blogs and articles are made with WordPress and URLs starting with /data/ are made with CKAN. The demo environment is available at [https://generic-qa.dataportaali.com/fi/](https://generic-qa.dataportaali.com/fi/) with login to each service with its own account. WordPress login is available at [https://generic-qa.dataportaali.com/wp-login.php](https://generic-qa.dataportaali.com/wp-login.php) and CKAN is at [https://generic-qa.dataportaali.com/data/user/login](https://generic-qa.dataportaali.com/data/user/login). Access to services can be asked from the developer team.

## Setting up development environment

### Requirements:

* Virtualbox \(tested with 6.1.12\)
* Vagrant \(tested with 2.2.9\)

The development environment can be built on any machine that has required applications installed. Setting up development environment is done automatically when following commands are executed:

```bash
git clone https://github.com/6aika/sixodp.git
cd sixodp
git submodule update --init --recursive
npm install
npm run build
vagrant up
```

{% hint style="info" %}
Setting up the environment can take half an hour.
{% endhint %}

Once ansible has installed everything inside the virtual machine, the environment is available at [https://10.106.10.10/](https://10.106.10.10/). The default password in development environment are admin / admin in WordPress and admin / adminadmin in CKAN.





