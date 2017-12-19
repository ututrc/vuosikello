<h1>Vuosikello</h1>

Contributors: Turun yliopisto

Donate link: utu.fi

Tags: 

Requires at least: 3.0.1

Tested up to: 3.4

Stable tag: 4.3

License: GPLv2 or later

License URI: http://www.gnu.org/licenses/gpl-2.0.html


<h2>Kuvaus</h2>

Vuosikello päiväkodeille

<h2>Dokumentaatio</h2>

<h3>Vaatimukset</h3>

Vuosikello-plugin <strong>vaatii<strong> toimiakseen Groups-pluginin ( https://github.com/itthinx/groups/releases/tag/2.2.0 ), Vuosikellon kehitys ja testaus on suoritettu groups-pluginin versiolla 2.2.0 ja sen vuoksi muiden versioiden toimivuutta ei voida taata. 

<h3>Kehitysympäristö ja asennus</h3>

Vuosikellon asennetaan vain kopioimalla vuosikello-kansio githubista WordPressin plugins kansioon. Samaan kansioon tulee asentaa yllämainittu groups-pluginin versio 2.2.0. Testaamisen ja kehittämisen helpottamiseksi kannattaa asentaa <strong>Vagrant</strong> (https://www.vagrantup.com/) ja siihen valmis VVV-konfiguraatio wordpressiä varten (https://github.com/Varying-Vagrant-Vagrants/VVV). Lisäksi olisi hyvä konfiguroida windowsin hosts-tiedostoa, jotta selaimella pääsee helpommin virtuaalikoneessa pyörivään wordpressiin käsiksi. hosts-tiedostossa olisi hyvä olla ainakin seuraavat rivit:

<code>192.168.50.4 local.wordpress.dev</code>

<code>192.168.50.4 vvv.dev</code>

Vagrantin käyttö on suhteellisen suoraviivaista. Tarkemmat ohjeet löytyvät netistä, mutta usein pelkillä käynnistys ja sammutus-komennoilla pärjää, sillä VVV-konfiguraatio sisältää asetukset valmiina. Käynnistys tapahtuu <code>vagrant up</code>-komennolla, ”pausettaminen” <code>vagrant suspend</code>-komennolla ja sammutus <code>vagrant halt</code>-komennolla.

<h3>WordPress-konfiguraatio ja käyttöohjeet</h3>

Myös WordPress vaatii hiukan säätämistä, jotta saadaan oikeanlainen vuosikello-elämys aikaiseksi. Kommentointi kannattaa avata kaikille käyttäjille, sillä rekisteröinti palveluun on suljettua. Kun vuosikello ja groups on asennettu ensimmäistä kertaa, <strong>adminin pitää painaa setup-nappia vuosikelloasetuksissa</strong> <em>(Eli WordPressin Dashboard -> Vuosikello -> Setup)</em>. Tämän jälkeen kaikki adminit pitää liittää admin-ryhmään. Samasta paikasta lisätään myös uusi päiväkoti, nappulasta <em>”Create daycare”</em>. Yllä olevaan kenttään tulee syöttää päiväkodin nimi, lisättävien käyttäjien sähköpostiosoitteet ja lisättävien ylläpitäjien sähköpostiosoitteet. Ylläpitäjät lisätään automaattisesti käyttäjäryhmään. Esimerkki uuden päiväkodin luonnista:

<em>daycare=PäiväkodinNimi;</em>

<em>users=etunimi.sukunimi@esimerkki.fi, etunimi2@esimerkki.com,</em>

<em>sukunimi3@esimerkki.net;</em>

<em>mods=etunimi4.sukunimi4@esimerkki.fi, etunimi5.sukunimi5@esimerkki.com;</em>


Käyttäjille ja ylläpitäjille luodaan tunnus sähköpostin alkuosasta <em>(ennen @-merkkiä)</em> ja sähköpostiosoitteeseen lähetetään kirjautumistunnukset. Käyttäjiä ja ylläpitäjiä voidaan luoda useita erottamalla heidät pilkulla. Käyttäjiä tai ylläpitäjiä ei ole pakko lisätä ollenkaan, mutta luontiin käytetyt sanat ja merkit <em>(users= ; ja mods= ;)</em> <strong>on silti pakko kirjoitettaa esimerkin järjestyksessä</strong>. Tässä vaiheessa lisättyjen käyttäjien ja ylläpitäjien oikeudet ovat automaattisesti oikein. Adminilla on oikeudet kaikkien käyttäjien ja ylläpitäjien tietojen ja oikeuksien muokkaamiseen. Luonnin jälkeen päiväkotiin on mahdollista liittää vapaasti lisää käyttäjiä.
Admin-asetuksista voidaan myös luoda kerralla useampi päiväkoti, ja näihin lukijat/sisällönluojat. Tämän jälkeen uudet käyttäjät pitää luoda manuaalisesti luomalla ensin käyttäjätunnus, ja sitten antaa tälle oikeudet tiettyyn päiväkotiin. Manuaalisessa käyttäjien luonnissa on hyvä muistaa, että sisältöä julkaisevien henkilöiden <em>(Modien)</em> tulee myös kuulua <em>User</em>-ryhmään. Modien julkaisemat <em>Postit</em> näkyvät automaattisesti vain ryhmille joihin he kuuluvat eivätkä he voi muuttaa tätä itse. 
Jotta <em>Oma vuosikello</em>-nappi toimisi, osoiterakenteen täytyy olla oletusasetuksella. Vuosikello-plugin on myös teemariippumaton, joten WP-asennukseen voi asentaa sopivan teeman. Teemassa kannattaa kuitenkin olla kirjautumisnappi näkyvillä. Omaa teemaa käyttäessä täytyy käyttäjien wordpress-paneeli piilottaa erikseen. Tämä tapahtuu lisäämällä seuraavat rivit teeman <em>functions.php (public_html/wp-content/themes/TEEMAN_NIMI/functions.php)</em> tiedostoon (esim. alimmalle riville):

<code>//Hide wordpress bar for subscribers viewing the site
if(current_user_can('subscriber')) {
	if(is_admin_bar_showing()) {
		show_admin_bar(false);
	}
}</code>


Edellä mainitun kaltaisessa tilanteessa tulee varmistaa, että vain henkilöt joilta halutaan palkki poistaa, kuuluvat <em>”subscriber”</em>-rooliin. Esimerkki tällaisesta henkilöstä on lapsen vanhempi. Adminien tulee kuulua <em>”admin”</em>-rooliin ja muut henkilöt joiden halutaan lisäävän vuosikellotapahtumia, voidaan liittää esimerkiksi <em>”contributor”</em>-rooliin.

<h3>Koodirakenne</h3>

Vuosikello-plugin aloittaa toimintansa <code>includes/class-vuosikello.php</code>-tiedostosta.  Tiedostossa alustetaan myös muut käytettävät php-tiedostot. <code>define_admin_hooks()</code> asettaa admin-kansion hookit, kun taas <code>define_public_hooks</code> tekee saman public-kansiolle. Tiedostot <code>admin/class-vuosikello-admin.php</code> ja <code>public/class-vuosikello-public.php</code> sisältävät hookkien toteutukset.
Vuosikellon toiminta perustuu kahteen eri custom post tyyppiin, eli vuosikello-postiin ja päiväkotiin. Vuosikello-post kuvaa yksittäistä vuosikellotapahtumaa ja päiväkoti kuvaa yhtä päiväkotia. Jokainen vuosikello-post liittyy johonkin tiettyyn päiväkotiin groupin perusteella. Määrittelyt postityypeille löytyy samoista tiedostoista hookkien toteutusten kanssa. Päiväkotiposteja on vain yksi per päiväkoti, ja niihin liitetään kyseisen päiväkodin vuosikello shortcoden avulla.
Vuosikello-util-luokka (<code>includes/class-vuosikello-utils.php</code>) sisältää funktioita helpottamaan päiväkotien ja niiden käyttäjien luomista ja muokkaamista. <code>admin/vuosikello-admin-display.php</code> sisältää aiemmin esitellyn setup-napin toiminnallisuuden sekä useamman päiväkodin ja käyttäjän kertaluomisen.
Vuosikellon graafinen osuus löytyy tiedostosta <code>include/partials/vuosikello-visualizations.php</code>. Tiedostossa on käytetty HTML:ää, CSS:ää, JavaScriptiä ja PHP:ta. Vuosikello piirretään D3.js-kirjaston avulla vektorigrafiikkana. Tällä hetkellä ainoa tapa vuosikellon värien muuttamiseen on suoraan muuttaa vkColors-muuttujaa <code>vuosikello-visualizations.php</code> tiedostossa. Esitettävä data kerätään PHP:lla serveriltä, minkä jälkeen se asetetaan JavaScript-muuttujaan renderöidyssä verkkosivussa. 


<h2>Usein kysytyt kysymykset</h2>

Q: En saa joitakin testiympäristön (VVV) sivuja aukeamaan selaimessa, miten tämä korjataan?

A: Lisää hosts tiedostoon rivi 192.168.50.4 sivun osoite joka ei aukea (esim. 192.168.50.4 local.wordpress.dev/wp-admin)


Q: WordPress ei lataa teemaa vaan näyttää pelkästään sivun HTML-elementit, miten saan teemaan näkyviin?

A: Usein tämäkin ongelma ratkeaa Hosts-tiedostoa muokkaamalla edellisessä vastauksessa esitetyllä tavalla. Alla lista jonka lisäämisen hosts-tiedostoon pitäisi saada teema näkyviin:

<code>192.168.50.4 src.wordpress-develop.dev
192.168.50.4 local.wordpress-trunk.dev
192.168.50.4 build.wordpress-develop.dev
192.168.50.4 local.wordpress.test
192.168.50.4 vvv.test</code>


Q: En pidä vuosikellon väreistä, miten niitä muutetaan?

A: Tällä hetkellä värien muutos onnistuu ainoastaan suoraan lähdekoodissa. Värejä voi muuttaa <code>vuosikello-visualizations.php</code> muuttujassa vkColors.


Q: Lisäsin taphtuman joka jatkuu joulukuulta tammikuulle ja nyt vuosikello näyttää oudolta, mitä tehdä?

A: Tällä hetkellä Vuosikello ei tue kuin yhtä vuotta kerrallaan.


Q: Olen Admin. Lisäsin taphtuman, miksei kukaan näe sitä Vuosikellossa?

A: Ylläpitäjien tulee määritellä uutta Vuosikello-tapahtumaa erikseen ne ryhmät joille tapahtuma näytetään, jos tätä ei tehdä, tapahtumaan ei näe kukaan.

<h2> Tarkemmin vuosikellon moduuleista (englanniksi)</h2>

class-vuosikello-i18n.php

- This class  loads and defines the internationalization files for this plugin
 so that it is ready for translation.


 
class-vuosikello-loader .php

- Register all actions and filters for the plugin.
 Maintain a list of all hooks that are registered throughout
 the plugin, and register them with the WordPress API. Call the
 run function to execute the list of actions and filters.


 
class-vuosikello-utils .php

- This class contains functions to ease the adding and editing of daycares and users. Also functions to integrate vuosikello daycares/users with the groups plugin e.g. Adding or removing users/daycares from groups and handling capability relations.



class-vuosikello.php

- A class definition that includes attributes and functions used across both the
 public-facing side of the site and the admin area. The core plugin class.
 This is used to define internationalization, admin-specific hooks, and
 public-facing site hooks.
 Also maintains the unique identifier of this plugin as well as the current
 version of the plugin.


 
vuosikello-visualizations.php

- This class contains the visualization for Vuosikello, it's a mixed bunch of JavaScript, HTML, CSS and PHP.
 It uses JavaScript's d3.js library to draw the Vuosikello.

 
 
class-vuosikello-public.php

- The public-facing functionality of the plugin.
 Defines the plugin name, version, and two examples hooks for how to
 enqueue the admin-specific stylesheet and JavaScript.

 
 
single-vuosikello_post.php

- The template for displaying all single posts



class-vuosikello-admin.php

- Contains some functions on calibrating vuosikello -settings like displaying the button for vuosikello-settings. It also defines the custom post type and the settings for vuosikello events.



vuosikello-admin-visualizations.php and vuosikello-admin-display.php

- These files are used to markup the admin-facing aspects of the plugin. They create all the elements and functions available to admins e.g. creating new daycares.



class-vuosikello-activator.php

- A stump forced by WP at creation. No content.



class-vuosikello-deactivator.php

- A stump forced by WP at creation. No content.






