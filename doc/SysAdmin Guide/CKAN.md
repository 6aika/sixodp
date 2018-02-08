# Komentorivikäskyjä ajettavaksi EC2 koneelta

## Käyttäjien listaus

```
sudo /usr/lib/ckan/default/bin/paster --plugin=ckan user --config=/etc/ckan/default/production.ini
```

## Käyttäjien luonti

```   
sudo /usr/lib/ckan/default/bin/paster --plugin=ckan user add tunnus email=sähköposti --config=/etc/ckan/default/production.ini
```


## Admin oikeuksien antaminen

```   
sudo /usr/lib/ckan/default/bin/paster --plugin=ckan sysadmin add tunnus --config=/etc/ckan/default/production.ini
```
