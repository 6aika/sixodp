# Dataportaalin tekninen dokumentaatio

## Dataportaali

Dataportaali koostuu kahdesta palvelusta: [WordPress](https://wordpress.org/) sisällöntuotantoon ja [CKAN](https://ckan.org/) datan julkaisuun. Palvelut on rakennettu siten, että portaalin etusivu on tehty WordPressillä ja osoitteissa /data/ alkuiset CKAN:lla. Projektin demoympäristö löytyy osoitteesta [https://generic-qa.dataportaali.com/fi/](https://generic-qa.dataportaali.com/fi/) ja kumpaiseenkin palveluun kirjaudutaan omilla tunnuksilla. WordPressin sisäänkirjautuminen löytyy osoitteesta [https://generic-qa.dataportaali.com/wp-login.php](https://generic-qa.dataportaali.com/wp-login.php) ja CKAN:in osoitteesta [https://generic-qa.dataportaali.com/data/user/login](https://generic-qa.dataportaali.com/data/user/login). Pääsyjä voi tarvittaessa kysyä kehitystiimiltä.

## Kehitysympäristön pystytys

### Vaatimukset:

* Virtualbox \(testattu versiolla 6.1.12\)
* Vagrant \(testattu versiolla 2.2.9\)

Kehitysympäristö voidaan pystyttää mille tahansa koneelle, johon on asennettu vaadittavat ohjelmistot. Kehitysympäristön pystytys tapahtuu automaattisesti ajamalla seuraavat komennot:

```bash
git clone https://github.com/6aika/sixodp.git
cd sixodp
git submodule update --init --recursive
npm install
npm run build
vagrant up
```

{% hint style="info" %}
 Pystytykseen voi mennä puolen tuntia.
{% endhint %}

Kun ansible on asentanut kaiken virtuaalikoneen sisälle, kehitysympäristö on saatavilla osoitteessa [https://10.106.10.10/](https://10.106.10.10/). Oletus salasanat kehitysympäristössä ovat WordPressiin admin / admin ja CKAN:iin admin / adminadmin.





