<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.8
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Website: www.crawltrack.net
//----------------------------------------------------------------------
// That script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: french.php
//----------------------------------------------------------------------
//  Last update: 12/02/2011
//----------------------------------------------------------------------
$language= array();
//installation
$language['install']="Installation";
$language['welcome_install'] ="Bienvenue sur CrawlTrack, l'installation va se faire simplement en 3 étapes.";
$language['menu_install_1']="1) Saisie des données de connexion.";
$language['menu_install_2']="2) Paramétrage des sites à auditer.";
$language['menu_install_3']="3) Création du compte administrateur.";
$language['go_install']="Installer";
$language['step1_install'] ="Veuillez saisir dans le formulaire ci-dessous les informations concernant les identifiants de connexion à la base de données. Une fois le formulaire validé, les tables et  le fichier de connexion vont être automatiquement créés.";
$language['step1_install_login_mysql']="Identifiant MySQL";
$language['step1_install_password_mysql']="Mot de passe MySQL";
$language['step1_install_host_mysql']="Serveur MySQL";
$language['step1_install_database_mysql']="Base MySQL";
$language['step1_install_table_mysql']="Préfixe des tables";
$language['step1_install_ok'] ="Fichier de connexion OK.";
$language['step1_install_ok2'] ="Création des tables OK.";
$language['step1_install_no_ok'] ="Il manque des informations pour créer les tables et le fichier de connexion, veuillez vérifier les infos saisies dans le formulaire et revalider après correction.";
$language['step1_install_no_ok2'] ="Le fichier n'a pas pu être créé, vérifier que le répertoire est en CHMOD 777.";
$language['step1_install_no_ok3'] ="Un problème est survenu lors de la création des tables, essayer de nouveau la procédure.";
$language['back_to_form'] ="Retour au formulaire de saisie";
$language['retry'] ="Essayer de nouveau";
$language['step2_install_no_ok']="La connexion à la base n'a pas pu s'effectuer, veuillez vérifier les données saisies.";
$language['step3_install_no_ok']="La sélection de la base n'a pas pu s'effectuer, veuillez vérifier les données saisies.";
$language['step4_install']="Suite";
//site creation
//modified in 1.5.0
$language['set_up_site']="Veuillez noter ci-dessous le nom et l'url du site à auditer, le nom est celui qui sera utilisé pour identifier le site lors de l'utilisation de CrawlTrack. L'url du site doit ête sous la forme :www.example.com (sans http :// au début ni / à la fin).";
$language['site_name']="Nom du site :";
//modified in 2.0.0
$language['site_no_ok']="Vous devez entrer un nom et une url de site.";
$language['site_ok']="Le site a été ajouté à la base de données.";
$language['new_site']="Ajouter un autre site";
//tag creation
$language['tag']="Tag à insérer dans vos pages";
//modified in 2.3.0
$language['create_tag']="<p><b>Comment utiliser le tag CrawlTrack :</b><br><ul id=\"listtag\">
<li>le tag Crawltrack est un fichier php, vous devez le mettre sur une page en .php</li>
<li>le tag CrawlTrack doit être entre les balises &#60;?php et ?&#62, si il n'y a pas ce type de balises sur votre page, vous devez les ajouter avant et après le tag.</li>
<li>si votre site n'utilise pas des pages en .php, voir documentation sur www.crawltrack.net.</li>
<li>le mieux pour la protection anti piratage est que le tag CrawlTrack soit la première chose sur votre page juste après la balise &#60;?php.</li>
<li>si vous utiliser un CMS ou un forum, aller voir sur www.crawltrack.net/fr/doccms.php pour trouver la meilleurs solution pour placer le tag.</li>
<li>le tag CrawlTrack sera parfaitement invisible sur vos pages (y compris dans le code source).</li>
<li>si pour aider au développement de CrawlTrack vous souhaiter mettre un logo avec un lien vers www.crawltrack.net, vous trouverez plus bas des modèles que vous pouvez mettre n'importe où sur vos pages.</li>
<li>pour toutes autres questions, voir la documentation sur www.crawltrack.net ou utiliser le forum de support sur le même site.</li></ul></p><br>" ;
$language['site_name2']="Nom du site";
//modified in 1.5.0
$language['local_tag']="Tag standard, à utiliser pour un site hébergé sur le même serveur que CrawlTrack. ";
$language['non_local_tag']="Tag à utiliser si le site est hébergé sur un autre serveur que Crawltrack, attention il faut dans ce cas que les fonctions fsockopen et fputs soit activées sur votre hébergement.";
//login set_up
$language['admin_creation']="Création du compte administrateur";
$language['admin_setup']="Veuillez saisir ci-dessous l'identifiant et le mot de passe qui seront utilisés par l'administrateur.";
$language['user_creation']="Création du compte utilisateur";
$language['user_setup']="Veuillez saisir ci-dessous l'identifiant et le mot de passe qui seront utilisés par l'utilisateur.";
$language['user_site_creation']="Création du compte utilisateur-site";
$language['user_site_setup']="Veuillez saisir ci-dessous l'identifiant et le mot de passe qui seront utilisés par l'utilisateur-site.";
$language['admin_rights']="L'administrateur a accès à la zone de configuration ainsi qu'aux stats de tous les sites audités.";
$language['login']="Identifiant";
$language['password']="Mot de passe";
$language['valid_password']="Saisissez une deuxième fois votre mot de passe.";
$language['login_no_ok']="Il manque des informations ou les mots de passe saisies sont différents, veuillez vérifier les infos saisies dans le formulaire et revalider après correction.";
$language['login_ok']="Le compte a été créé.";
$language['login_no_ok2']="Un problème est survenu lors de la création du compte, essayer de nouveau la procédure.";
$language['login_user']="Créer un compte utilisateur";
$language['login_user_what']="Un utilisateur a accès à l'ensemble des stats des sites";
$language['login_user_site']="Créer un compte utilisateur-site";
$language['login_user_site_what']="Un utilisateur-site a accès aux stats d'un seul site";
//modified in 1.5.0
$language['login_finish']="L'installation est terminée.N'oubliez pas de mettre le tag (disponible page outils <img src=\"./images/wrench.png\" width=\"16\" height=\"16\" border=\"0\" > ) sur les pages de votre site.";
//access
$language['restrited_access']="L'accès aux statistiques est protégé.";
$language['enter_login']="Veuillez saisir ci-dessous votre identifiant et votre mot de passe.";
//display
$language['crawler_name']="Robots";
//modifié en 3.0.0
$language['nbr_visits']="Hits";
$language['nbr_pages']="Pages vues";
$language['date_visits']="Dernière visite";
$language['display_period']="Période étudiée :";
$language['today']="Jour";
$language['days']="Semaine";
$language['month']="Mois";
$language['one_year']="Année";
$language['no_visit']="Il n'y a pas eu de visite.";
$language['page']="Pages";
//modified in 1.5.0
$language['admin']="Outils";
$language['nbr_tot_visits']="Total hits";
$language['nbr_tot_pages']="Total pages vues";
$language['nbr_tot_crawlers']="Nbre de robots";
$language['visit_per-crawler']="Détail des visites";
$language['100_visit_per-crawler']="Détail des visites (affichage limité à %d lignes).";
$language['user_agent']="User agent";
$language['Origin']="Utilisateur";
$language['help']="Aide";
//search
$language['search']="Recherche";
$language['search2']="Rechercher";
$language['search_crawler']="un robot";
$language['search_user_agent']="un user-agent";
$language['search_page']="une page";
$language['search_user']="un utilisateur de robot";
$language['go_search']="Chercher";
$language['result_crawler']="Voici les robots qui correspondent à votre recherche.";
$language['result_ua']="Voici les user-agents qui correspondent à votre recherche.";
$language['result_page']="Voici les pages qui correspondent à votre recherche.";
$language['result_user']="Voici les utilisateurs qui correspondent à votre recherche.";
$language['result_user_crawler']="Voici les robots de cet utilisateur.";
$language['result_user_1']="Utilisateur :&nbsp;";
$language['result_crawler_1']="Mot recherché :&nbsp;";
$language['no_answer']="Il n'y a pas de réponse correspondant à votre recherche.";
$language['to_many_answer']="Il y a plus de 100 réponses (affichage limité à 100 lignes).";
//admin
$language['user_create']="Créer un nouveau compte utilisateur.";
$language['user_site_create']="Créer un nouveau compte utilisateur-site.";
$language['new_site']="Ajouter un site à auditer.";
$language['see_tag']="Voir les tags à insérer.";
$language['new_crawler']="Ajouter un nouveau robot.";
$language['crawler_creation']="Veuillez complêter le formulaire ci-dessous avec les données du nouveau robot."; 
$language['crawler_name2']="Nom du robot :";
$language['crawler_user_agent']="User agent :";
$language['crawler_user']="Utilisateur du robot :";
$language['crawler_url']="Adresse de l'utilisateur (sous la forme http ://www.example.com)";
$language['crawler_url2']="Adresse de l'utilisateur :";
$language['crawler_no_ok']="Il manque des informations, veuillez vérifier les infos saisies dans le formulaire et revalider après correction.";
$language['exist']="Ce robot existe déjà dans la base de données";
$language['exist_data']="Voici les informations le concernant dans la base :";
$language['crawler_no_ok2']="Un problème est survenu lors de la création du robot, essayer de nouveau la procédure.";
$language['crawler_ok']="Le robot a été ajouté à la base de données.";
$language['user_suppress']="Supprimer un compte utilisateur ou utilisateur-site.";
$language['user_list']="Liste des logins utilisateurs et utilisateur-sites";
$language['suppress_user']="Supprimer ce compte";
$language['user_suppress_validation']="Etes vous sûr de vouloir supprimer ce compte?";
$language['yes']="Oui";
$language['no']="Non";
$language['user_suppress_ok']="Le compte a été supprimé avec succès.";
$language['user_suppress_no_ok']="Un problème est survenu lors de la suppression du compte, essayer de nouveau la procédure.";
$language['site_suppress']="Supprimer un site.";
$language['site_list']="Liste des sites";
$language['suppress_site']="Supprimer ce site";
$language['site_suppress_validation']="Etes vous sûr de vouloir supprimer ce site?";
$language['site_suppress_ok']="Le site a été supprimé avec succès.";
$language['site_suppress_no_ok']="Un problème est survenu lors de la suppression du site, essayer de nouveau la procédure.";
$language['crawler_suppress']="Supprimer un robot.";
$language['crawler_list']="Liste des robots";
$language['suppress_crawler']="Supprimer ce robot";
$language['crawler_suppress_validation']="Etes vous sûr de vouloir supprimer ce robot?";
$language['crawler_suppress_ok']="Le robot a été supprimé avec succès.";
$language['crawler_suppress_no_ok']="Un problème est survenu lors de la suppression du robot, essayer de nouveau la procédure.";
$language['crawler_test_creation']="Créer un robot de test.";
$language['crawler_test_suppress']="Supprimer le robot de test.";
$language['crawler_test_text']="Une fois le robot de test créé, allez visiter votre site avec l'ordinateur et le navigateur utilisés pour créer le robot."; 
$language['crawler_test_text2']="Si tout va bien, votre visite apparaitra dans CrawlTrack comme étant celle du robot Test-Crawltrack. N'oubliez pas ensuite de supprimer ce robot de test.";
$language['crawler_test_no_exist']="Le robot de test n'existe pas dans la base de données.";
$language['exist_site']="Ce site existe déjà dans la base de données";
$language['exist_login']="Ce login existe déjà dans la base de données";
//1.2.0
$language['update_title']="Mise à jour de la liste de robots.";
$language['update_crawler']="Mettre à jour la liste de robots.";
$language['list_up_to_date']="Il n'y a pas de mise à jour disponible actuellement.";
$language['update_ok']="La mise à jour s'est bien passée.";
$language['crawler_add']="robots ont été ajoutés à la base de données";
$language['no_access']="La mise à jour en ligne ne fonctionne pas.<br><br>Pour mettre à jour veuillez cliquer sur le lien ci-dessous pour télécharger la dernière liste de robot, placez le fichier crawlerlist.php dans le répertoire include de CrawlTrack et relancez la procédure de mise à jour.";
$language['no_access2']="La liaison avec CrawlTrack.net a échoué, veuillez réessayer ultérieurement.";
$language['download_update']="Si vous avez déjà téléchargé et uploadé sur votre site la liste de robot, cliquez sur le bouton ci-dessous pour faire la mise à jour.";
$language['download']="Télécharger la liste de robot";
$language['your_list']="La liste que vous utilisez est :";
$language['crawltrack_list']="La liste disponible sur Crawltrack.net est :";
$language['no_update']="Ne pas mettre à jour la liste.";
$language['no_crawler_list']="Le fichier crawlerlist.php n'est pas présent dans votre répertoire include";
//1.3.0
$language['use_user_agent']="La détection peux se faire par le user agent ou par l'IP. Vous devez donc mettre l'une ou l'autre des informations.";
$language['user_agent_or_ip']="User agent ou IP";
$language['crawler_ip']="IP :";
$language['table_mod_ok']="Modification de la table crawlt_crawler OK.";
$language['files_mod_ok']="Modification des fichiers configconnect.php et crawltrack.php OK.";
$language['update_crawltrack_ok']="La mise à jour de CrawlTrack est terminée, vous utilisez maintenant la version :";
$language['table_mod_no_ok']="La modification  de la table crawlt_crawler n'a pas pu se faire.";
$language['files_mod_no_ok']="Il y a eu un problème lors de la mise à jour des fichiers configconnect.php et crawltrack.php.";
$language['update_crawltrack_no_ok']="La mise à jour de CrawlTrack n'a pas pu se faire.";
//modified in 2.3.0
$language['no_logo']="Pas de logo.";
//modified in 1.5.0

//modified in 2.0.0


//1.4.0
$language['time_set_up']="Décalage horaire";
$language['server_time']="Date et heure du serveur =";
$language['local_time']="Date et heure locale=";
$language['time_difference']="Différence en heures entre l'heure du serveur et l'heure locale=";
$language['time_server']="Vous utilisez actuellement l'heure du serveur, voulez vous que les données soient affichées en utilisant votre heure locale ?";
$language['time_local']="Vous utilisez actuellement l'heure locale, voulez vous que les données soient affichées en utilisant votre heure du serveur ?";
$language['decal_ok']="CrawlTrack, utilisera maintenant votre heure locale; vous pouvez à tout moment revenir en heure serveur";
$language['nodecal_ok']="CrawlTrack, utilisera maintenant l'heure du serveur; vous pouvez à tout moment revenir en heure locale";
$language['need_javascript']="Vous devez activer javascript pour utiliser cette fonctionnalité.";
//1.5.0 
$language['origin']="Provenance";
$language['crawler_ip_used']="IP utilisées";
$language['crawler_country']="Pays d'origine";
$language['other']="Autres";
$language['pc-page-view']="Part du site visitée";
$language['pc-page-noview']="Part du site non visitée";
$language['print']="Imprimer";
$language['ip_suppress_ok']="Les visites ont été supprimée avec succès.";
$language['ip_suppress_no_ok']="Un problème est survenu lors de la suppression des visites, essayer de nouveau la procédure.";
$language['no_ip']="Il n'y a pas eu d'IP enregistrée sur la période.";
$language['ip_suppress_validation']="Cette IP a été utilisée par plusieurs robots différents, il y a donc un doute sur l'origine réelle de ces 
visites.Voulez vous supprimer les visites correspondantes à cette IP de la base?";
$language['ip_suppress_validation2']="Etes vous sûr de vouloir supprimer les visites venant de cette IP de la base de données?";
$language['ip_suppress_validation3']="Si vous voulez interdire l'accès à votre site depuis cette IP, ajoutez la ligne suivante dans votre fichier .htaccess 
à la racine de votre site :";
$language['ip_suppress']="Supprimer une IP";
$language['diff-day-before']="par rapport à la veille";
$language['daily-stats']="Statistiques journalières";
$language['top-crawler']="Robot le plus actif :";
$language['stat-access']="Voir les statistiques détaillées";
$language['stat-crawltrack']="Ces données sont enregistrées grâce à :";
$language['nbr-pages-top-crawler']="Il a visité";
$language['of-site']="du site";
$language['mail']="Recevoir un résumé journalier par Email.";
$language['set_up_mail']="Si vous voulez recevoir un résumé journalier de vos statistiques par Email, entrez ci-dessous votre adresse Email.";
$language['email-address']="Adresse Email :";
$language['address_no_ok']="L'adresse que vous avez saisie n'est pas correcte.";
$language['set_up_mail2']="L'envoi du résumé journalier par Email est actuellement activé. Voulez vous le désactiver?";
$language['update']="La modification a été prise en compte";
$language['no-visits-day-before']="Il n'y a pas eu de visites hier.";
$language['search_ip']="Localiser une adresse IP";
$language['ip']="Adresse IP";
$language['maxmind']="La géolocalisation a été faite en utilisant la base de données GeoLite créée par Maxmind disponible à l'adresse suivante :";
$language['ip_no_ok']="L'adresse IP que vous avez saisie n'est pas correcte.";
$language['public']="Mettre les statistiques en accès libre.";
$language['public-set-up2']="L'accès aux statistiques est actuellement libre, voulez vous le protéger par mot de passe?";
$language['public-set-up']="L'accès aux statistiques est actuellement protégé par mot de passe, voulez vous le rendre libre?";
$language['public2']="Seul l'accès à la page Outils restera protégée par votre mot de passe";
$language['admin_protected']="L'accès à la page Outils est protégé.";
$language['no_data_to_suppress']="Il n'y a pas de données à archiver pour la période demandée.";
$language['data_suppress3']="L'archivage des données permet de réduire la taille de la base de données, mais en contre partie
les données correspondantes ne sont plus accessibles dans les pages de statistiques. Après l'archivage, vous trouverez dans le dossier archive les fichiers permettant en cas de besoin de remettre les données dans la base en utilisant phpMyAdmin.";
$language['archive']="Archives";
$language['month2']="Mois";
$language['top_visits']="Top 3 en nombre de visites";
$language['top_pages']="Top 3 en nombre de pages vues";
$language['no-archive']="Il n'y a pas de données archivées.";
$language['use-archive']="Attention une partie des données a été archivée, ces valeurs sont donc tronquées.";
$language['url_update']="Mettre à jour les données des sites";
$language['set_up_url']="Complétez le tableau ci-dessous en mettant les urls des sites sous la forme : www.example.com (sans http :// au début ni / à la fin)."; 
$language['site_url']="Url du site :";
//1.6.0
$language['page_cache']="Dernier calcul : ";
//1.7.0
$language['step1_install_no_ok4']="Un problème est survenu lors du remplissage de la table des IP, cela arrive sur certain hébergements car cette table comporte plus de 78 000 enregistrements. Vous pouvez soit essayer de nouveau la procédure, soit continuer sans cette table. Dans ce cas les pays d'origine des robots ne pourront pas être déterminés. Sur la page 'Problèmes connus' de la documentation sur www.crawltrack.net vous trouverez un moyen pour remplir la table des IP manuellement. ";
$language['show_all']="Voir toutes les lignes";
$language['from']="du";
$language['to']="au";
$language['firstweekday-title']="Choix du 1er jour de la semaine";
$language['firstweekday-set-up2']="Le premier jour de la semaine est actuellement le lundi, voulez vous changer pour le dimanche?";
$language['firstweekday-set-up']="Le premier jour de la semaine est actuellement le dimanche, voulez vous changer pour le lundi?";
$language['01']="Janvier";
$language['02']="Février";
$language['03']="Mars";
$language['04']="Avril";
$language['05']="Mai";
$language['06']="Juin";
$language['07']="Juillet";
$language['08']="Août";
$language['09']="Septembre";
$language['10']="Octobre";
$language['11']="Novembre";
$language['12']="Décembre";
$language['day0']="Lundi";
$language['day1']="Mardi";
$language['day2']="Mercredi";
$language['day3']="Jeudi";
$language['day4']="Vendredi";
$language['day5']="Samedi";
$language['day6']="Dimanche";
//2.0.0
$language['ask']="Ask";
$language['google']="Google";
$language['msn']="Bing";  //change for 3.1.1
$language['yahoo']="Yahoo";
$language['delicious']="Del.icio.us";
$language['index']="Indexation";
$language['keyword']="Mots clefs";
$language['entry-page']="Pages d'entrée";
$language['searchengine']="Moteur de recherche";
$language['social-bookmark']="Social bookmarks";
$language['tag']="Tags";
$language['nbr_tot_bookmark']="Bookmarks";
$language['nbr_tot_link']="Liens vers votre site";
$language['nbr_tot_pages_index']="Pages indexées";
$language['nbr_visits_crawler']="Nombre de visites du robot";


$language['100_lines']="Affichage limité à %d lignes.";
$language['8days']="Depuis 8 jours";
$language['close']="Fermer la fenêtre";
$language['date']="Date";
$language['modif_site']="Modifier le nom où l'url d'un site.";
$language['site_url2']="Url du site";
$language['modif_site2']="Modifier les données de ce site.";
$language['n/a']="N/A";
$language['no-info-day-before']="Pas d'information pour la veille";
$language['data_human_suppress_ok']="Les données ont été supprimées avec succès.";
$language['data_human_suppress_no_ok']="Un problème est survenu lors de la suppression des données, essayer de nouveau la procédure.";
$language['data_human_suppress_validation']="Etes vous sûr de vouloir supprimer toutes les &nbsp;";
$language['data_human_suppress']="Suppression des données les plus anciennes de la table des visites d'internautes (mots clefs et pages d'entrées).";
$language['data_human_suppress2']="Supprimer les";
$language['one_year_human_data']="données vieilles de plus d'un an";
$language['six_months_human_data']="données vieilles de plus de six mois";
$language['one_month_human_data']="données vieilles de plus d'un mois";
$language['data_human_suppress3']="La suppresion des données permet de réduire la taille de la base de données, mais en contre partie
les données correspondantes ne sont plus accessibles dans les pages de statistiques. Il est donc conseiller de ne faire la suppression que si il faut absolument réduire la taille 
la base de données; les données supprimées n'étant absolument pas récupérables.";
$language['no_data_human_to_suppress']="Il n'y a pas de données à supprimer pour la période demandée.";
$language['choose_language']="Choisissez votre langue.";
//2.1.0
$language['since_beginning']="Tout";
//2.2.0
$language['admin_database']="Voir la taille de la base de données";
$language['table_name']="Nom de la table";
$language['nbr_of_data']="Nombre d'enregistrements";
$language['table_size']="Taille de la table";
$language['database_size']="Taille de la base de données";
$language['total']="Total :";
$language['mailsubject']="Résumé journalier CrawlTrack";
$language['beginmonth']="Depuis le début du mois";
$language['evolution']="Evolution par rapport à";
$language['lastthreemonths']="3 derniers mois";
$language['set_up_mail3']="Vous utilisez actuellement les adresses suivantes :";
$language['set_up_mail4']="Ajouter une adresse";
$language['set_up_mail5']="Entrez ci-dessous l'adresse Email supplémentaire.";
$language['set_up_mail6']="Supprimer une ou plusieurs adresses";
$language['set_up_mail7']="Supprimer les adresses sélectionnées";
$language['chmod_no_ok']="Le fichier crawltrack.php n'a pas pu être modifié, mettez  le répertoire de CrawlTrack en CHMOD 777 et relancez la mise à jour. N'oubliez pas ensuite pour des raisons de sécurité de le remettre en CHMOD 711.";
$language['display_parameters']="Paramètres d'affichage";
$language['ordertype']="Classement :";
$language['orderbydate']="par date et heure de visite";
$language['orderbypagesview']="par nombre de pages vues";
$language['orderbyvisites']="par nombre de visites";
$language['orderbyname']="par ordre alphabétique";
$language['numberrowdisplay']="Nombre de lignes affichées :";
//2.2.1
$language['french']="Français";
$language['english']="Anglais";
$language['german']="Allemand";
$language['spanish']="Espagnol";
$language['turkish']="Turc";
$language['dutch']="Hollandais";
//2.3.0
$language['hacking']="Attaques";
$language['hacking2']="Tentatives de piratage";
$language['hacking3']="Injection de code";
$language['hacking4']="Injection SQL";
$language['no_hacking']="CrawlTrack n'a pas détecté de tentatives";
$language['attack_detail']="Détail des attaques qui n'ont pas données une erreur 404";
$language['attack']="Paramètres utilisés pour les tentatives d'injection de code";
$language['attack_sql']="Paramètres utilisés pour les tentatives d'injection SQL";
$language['bad_site']="Fichier/script que le hacker a tenté d'injecter";
$language['bad_sql']="Requète sql que le hacker a tenté d'injecter";
$language['bad_url']="Url demandées";
$language['hacker']="Attaquants";
$language['date_hacking']="Heures";
$language['unknown']="Inconnu";
$language['danger']="Vous pouvez être exposé si vous utilisez un de ces scripts";
$language['attack_number_display']="Détails des attaques qui n'ont pas données une erreur 404 (affichage limité à %d attaquants).";
$language['update_attack']="Mettre à jour la liste des attaques."; 
$language['no_update_attack']="Ne pas mettre à jour la liste des attaques.";
$language['update_title_attack']="Mise à jour de la liste des attaques.";
$language['attack_type']="Type d'attaque";
$language['parameter']="Paramètre";
$language['script']="Script";
$language['attack_add']="attaques ont été ajoutées à la base de données";
$language['no_access_attack']="La mise à jour en ligne ne fonctionne pas.<br><br>Pour mettre à jour veuillez cliquer sur le lien ci-dessous pour télécharger la dernière liste d'attaques, placez le fichier attacklist.php dans le répertoire include de CrawlTrack et relancez la procédure de mise à jour.";
$language['download_update_attack']="Si vous avez déjà téléchargé et uploadé sur votre site la liste d'attaques, cliquez sur le bouton ci-dessous pour faire la mise à jour.";
$language['download_attack']="Télécharger la liste d'attaques.";
$language['no_attack_list']="Le fichier attacklist.php n'existe pas dans votre répertoire include.";
$language['change_password']="Changer votre mot de passe";
$language['old_password']="Mot de passe actuel";
$language['new_password']="Nouveau mot de passe";
$language['valid_new_password']="Entrer une deuxième fois votre nouveau mot de passe.";
$language['goodsite_update']="Mettre à jour la liste de sites de confiance";
$language['goodsite_list']="Sites de confiance";
$language['goodsite_list2']="Un lien vers un de ces sites présent dans une url n'est pas considéré comme une attaque.";
$language['goodsite_list3']="Liste actuelle des sites de confiance";
$language['suppress_goodsite']="Supprimer ce site de la liste.";
$language['goodsite_suppress_validation']="Etes vous sûr de vouloir supprimer ce site?";
$language['good_site']="Site de confiance";
$language['goodsite_suppress_ok']="Le site a été supprimé avec succès.";
$language['goodsite_suppress_no_ok']="Un problème est survenu lors de la suppression du site, essayer de nouveau la procédure.";
$language['list_empty']="Il n'y a pas de site de confiance";
$language['add_goodsite']="Ajouter un site de confiance dans la liste";
$language['goodsite_no_ok']="Vous devez entrer une url de site.";
$language['attack-blocked']="Toutes ces attaques ont été bloquées par CrawlTrack comme demandé";
$language['attack-no-blocked']="Attention, votre CrawlTrack n'est pas paramètré pour bloquer ces attaques (voir page outils)";
$language['attack_parameters']="Paramètres de protection anti-piratage";
$language['attack_action']="Action en cas de détection d'une attaque";
$language['attack_block']="L'enregistrer et la bloquer";
$language['attack_no_block']="Seulement l'enregistrer";
$language['attack_block_alert']="Avant de choisir le bloquage des attaques, ce qui est le mieux pour la sécurité de votre site, lisez la documentation (sur www.crawltrack.net) pour 
être sûr qu'il n'y aura pas de problème avec vos visiteurs normaux.";
$language['crawltrack-backlink']="CrawlTrack est gratuit, si vous l'apprécier et voulez le faire connaitre pourquoi ne pas mettre un lien vers www.crawltrack.net sur vos pages?<br>Si vous choisissez
l'option pas de logo, ce lien sera invisible. Vous avez ci-dessous deux options pour chaque logo, une en php et la deuxième en html. Vous pouvez mettre ce lien à n'importe quelle position sur vos pages.";
$language['session_id_parameters']="Traitement des identifiants de session";
$language['remove_session_id']="Retirer les identifiants de session des url";
$language['session_id_alert']="Enlever les identifiants de session des url, va éviter les entrées multiples dans la table des pages si vous avez un script qui ajoute des identifiants de session dans l'url.";
$language['session_id_used']="Identifiants de session utilisés";
//3.0.0
$language['webmaster_dashboard']="Tableau de bord du webmaster";
$language['summary']="Résumé tous sites";
$language['charge']="Charge serveur";
$language['unidentified']="Non identifiés";
$language['display_period2']="Choix période";
$language['visitors']="Visiteurs";
$language['unique_visitors']="Visiteurs uniques";
$language['visits']="Visites";
$language['nbr_tot_visits2']="Total visites";
$language['nbr_tot_visits3']="Total";
$language['referer']="Affluents";
$language['website']="Sites internets & autres moteurs";
$language['website2']="sites";
$language['website3']="Sites internet";
$language['country']="pays";
$language['direct']="Entrées directes";
$language['average_pages']="Pages vues par visites";
$language['stats_visitors']="Statistiques visiteurs";
$language['count_in_stats']="Ne pas compter vos propres visites dans les sites suivants :";
$language['stats_visitors_other_domain']="Si l'un des sites concerné est hébergé sur un autre serveur (utilisation du tag 
le plus long); il faut copier le fichier crawltsetcookie.php (vous le trouverez dans le répertoire php de CrawlTrack) à la racine de ce site avant de cliquer sur OK pour que votre choix puisse être pris en compte.";
$language['main_crawlers']="Robots principaux";
$language['magnifier']="Faire une recherche dans la base de données de CrawlTrack";
$language['refresh']="Vider le cache et recalculer les données";
$language['wrench']="Accèder à la page d'administration de votre CrawlTrack";
$language['printer']="Imprimer la page en cours";
$language['information']="Documentation sur www.crawltrack.net";
$language['help']="A propos de CrawlTrack";
$language['cross']="Se déconnecter";
$language['home']="Retour à l'accueil";
$language['badreferer']="Etes vous sur de vouloir mettre ce domaine dans la liste des spammeurs de referer? Une fois celui-ci ajouter dans cette liste, les visites venant de ce domaine ne seront plus prises en compte par CrawlTrack.";
$language['spamreferer']="Mettre ce domaine dans la liste des spammeurs";
$language['badreferer_update']="Mettre à jour la liste de sites spammeurs de referer";
$language['add_badreferer']="Ajouter un site spammeur de referer dans la liste";
$language['listbadreferer_empty']="Il n'y a pas de site spammeur de referer";
$language['badreferer_list']="Sites spammeurs de referer";
$language['badreferer_list2']="Les visites venant de ces sites ne seront pas prises en compte par CrawlTrack.";
$language['badreferer_list3']="Liste actuelle des sites spammeurs de referer";
$language['badreferer_site']="Site spammeurs de referer";

$language['goodreferer']="Etes vous sur de vouloir mettre ce domaine dans la liste des sites qui ont un lien vers votre site? Une fois celui-ci ajouter dans cette liste, les visites venant de ce domaine seront prises en compte par CrawlTrack sans nouveau contrôle.";
$language['goodreferer2']="Mettre ce domaine dans la liste des sites qui ont un lien vers votre site";

$language['goodreferer_update']="Mettre à jour la liste des sites qui ont un lien vers votre site";
$language['add_goodreferer']="Ajouter un site qui a un lien vers votre site dans la liste";
$language['listgoodreferer_empty']="Il n'y a pas de site qui ont un lien vers votre site";
$language['goodreferer_list']="Sites qui ont un lien vers votre site";
$language['goodreferer_list2']="Les visites venant de ces sites seront prises en compte par CrawlTrack sans nouveau contrôle.";
$language['goodreferer_list3']="Liste actuelle des sites qui ont un lien vers votre site";
$language['goodreferer_site']="Site qui a un lien vers votre site";

$language['download']="Téléchargements";
$language['file']="Fichier";
$language['download_period']="Sur la période";
$language['download_link']="Compteur de téléchargements";
$language['download_link2']="<b>Pour que vos téléchargements soient comptés par CrawlTrack :</b><br><br>
-le fichier proposé en téléchargement doit être hébergé sur un des sites suivis par CrawlTrack.<br>
-le lien de téléchargement(pour un fichier dont l'adresse est http ://www.example.com/dossier/fichier.zip) doit être de la forme :";
$language['download_link3']="http ://www.example.com/dossier/fichier.zip";
$language['download_link4']="C'est tout, aucune autre manipulation n'est nécessaire.";
$language['error']="Erreurs 404";
$language['number']="Nombre";
$language['outer-referer']="Liens externes";
$language['inner-referer']="Liens internes";
$language['error-attack']="Dont tentatives de piratage";
$language['total_hacking']="Nombre total d'attaques";
$language['error_hacking']="Attaques qui ont données une erreur 404";
$language['error_page']="Url demandées";
$language['crawler_error']="Détail des erreurs 404 venant de robots";
$language['direct_error']="Détail des erreurs 404 dues à une arrivée directe";
$language['extern_error']="Détail des erreurs 404 dues à un lien externe au site";
$language['intern_error']="Détail des erreurs 404 dues à un lien interne au site";
$language['error_referer']="Url d'origine";
$language['404_no_in_graph']="Ces attaques ne sont pas prises en compte pour le nombre d'Ip, le graphe et le tableau de détail.";
$language['404_no_in_graph2']="Les attaques qui ont abouties à une erreur 404 n'apparaissent pas sur le graphe.";
$language['exalead']="Exalead";
$language['connect']="Vous êtes identifié";
$language['connect_you']="S'identifier";
$language['notcheck']="Lien non vérifié, clickez sur 'Vérifier les liens' pour lancer la vérification.";
$language['checklink']="Vérifier les liens";
$language['linkok']="Lien validé";
$language['first_date_visits']="Première visite";
$language['next_visits']="Prochaine visite";
$language['data_suppress']="Réduire la taille de la base de données."; //modified in 3.0.0
$language['data_suppress2']="Supprimer ";
$language['other_bot']="toutes les visites de robot sauf celles de Ask Jeeves/Teoma, Exabot, Googlebot, MSN Bot et Slurp Inktomi (Yahoo)";
$language['one_year_data']="toutes les visites de robot vieilles de plus d'un an";
$language['six_months_data']="toutes les visites de robot vieilles de plus de six mois";
$language['five_months_data']="toutes les visites de robot vieilles de plus de cinq mois";
$language['four_months_data']="toutes les visites de robot vieilles de plus de quatre mois";
$language['three_months_data']="toutes les visites de robot vieilles de plus de trois mois";
$language['two_months_data']="toutes les visites de robot vieilles de plus de deux mois";
$language['one_month_data']="toutes les visites de robot vieilles de plus d'un mois";
$language['one_year_data_human']="toutes les données visiteurs vieilles de plus d'un an";
$language['six_months_data_human']="toutes les données visiteurs vieilles de plus de six mois";
$language['five_months_data_human']="toutes les données visiteurs vieilles de plus de cinq mois";
$language['four_months_data_human']="toutes les données visiteurs vieilles de plus de quatre mois";
$language['three_months_data_human']="toutes les données visiteurs vieilles de plus de trois mois";
$language['two_months_data_human']="toutes les données visiteurs vieilles de plus de deux mois";
$language['one_month_data_human']="toutes les données visiteurs vieilles de plus d'un mois";
$language['attack_data']="toutes les données concernant les tentatives de piratages";
$language['oldest_data']="La donnée la plus ancienne date du &nbsp;";
$language['no_data']="Il n'y a pas de donnée dans la table des visites.";
$language['no_data_to_suppress']="Il n'y a pas de données à supprimer pour la période demandée.";
$language['data_suppress3']="Attention!!! La suppression des données permet de réduire la taille de la base de données, mais en contre partie les données sont irrémédiablement perdues.";
$language['data_suppress_ok']="Les données ont été supprimées avec succès.";
$language['data_suppress_no_ok']="Un problème est survenu lors de la suppression des données, essayer de nouveau la procédure.";
$language['data_suppress_validation']="Etes vous sûr de vouloir supprimer toutes les &nbsp;";
$language['deltatime']="Fréquence de visite";
$language['nbr_tot_visit_seo']="Origine des visites";
$language['url_parameters']="Paramètres dans les url";
$language['remove_parameter']="Retirer les paramètres des url";
$language['remove_parameter_alert']="Enlever les paramètres des url, va éviter que la table des pages ne grossisse éxagérément, par contre toute url du type : www.example.com/index.php?article=225 ne sera plus enregistrée que sous la forme www.example.com/index.php ce qui donnera moins de détails sur les pages visitées.";
$language['bookmark']="Utilisez cette adresse pour mettre cette page dans vos favoris";
$language['evolution']="Tendance nombre de visiteurs uniques";
$language['perday']="par jour";
$language['shortterm']="7 derniers jours :";
$language['longterm']="30 derniers jours :";
$language['bounce_rate']="Taux de rebond";
$language['visit_summary']="Visites cumulées sur l'ensemble des sites";
$language['data']="Données";
$language['index']="Index";
$language['sponsorship']="Ils supportent CrawlTrack :";
//3.1.0
$language['browser']="Navigateurs";
$language['visitor-browser']="Navigateurs utilisés par les visiteurs";
$language['hits-per-hour']="Hits par heure";
$language['russian']="Russe";
//3.1.2
$language['besponsor']="Utilisez CrawlTrack pour faire connaitre vos produits et services à des milliers de webmasters.";
$language['ad-on-crawltrack']="<a href=\"http://www.ad42.com/zone.aspx?idz=6690&ida=-1\" target=\"_blank\">Et si vous utilisiez CrawlTrack pour faire connaitre vos produits et services à des milliers de webmasters?</a>";
//3.2.0
$language['baidu']="Baidu";
$language['googleposition']="Position<br>dans Google";
$language['position']="Position actuelle";
$language['positiononemonth']="Position il y a un mois";
$language['positiontwomonth']="Position il y a deux mois";
$language['positionthreemonth']="Position il y a trois mois";
$language['googledetail']="Détail position dans Google et nombre de hits correspondant";
//3.2.3
$language['bulgarian']="Bulgare";
//3.2.8
$language['italian']="Italien";
$language['two_year_data']="toutes les visites de robot vieilles de plus de deux ans";
$language['two_year_data_human']="toutes les données visiteurs vieilles de plus de deux ans";
//country code
$country = array(
"ad" => "Andorre",
"ae" => "Emirats Arabes Unis",
"af" => "Afghanistan",
"ag" => "Antigua et Barbuda",
"ai" => "Anguilla",
"al" => "Albanie",
"am" => "Arménie",
"an" => "Antilles Neerlandaises",
"ao" => "Angola",
"ap" => "Asie/Pacifique",
"aq" => "Antarctique",
"ar" => "Argentine",
"as" => "American Samoa",
"at" => "Autriche",
"au" => "Australie",
"aw" => "Aruba",
"az" => "Azerbaidjan",
"ba" => "Bosnie Herzégovine",
"bb" => "Barbade",
"bd" => "Bangladesh",
"be" => "Belgique",
"bf" => "Burkina Faso",
"bg" => "Bulgarie",
"bh" => "Bahrein",
"bi" => "Burundi",
"bj" => "Bénin",
"bm" => "Bermudes",
"bn" => "Brunei",
"bo" => "Bolivie",
"br" => "Brésil",
"bs" => "Bahamas",
"bt" => "Bhoutan",
"bw" => "Botswana",
"by" => "Biélorussie",
"bz" => "Bélize",
"ca" => "Canada",
"cd" => "Rép. dém. du Congo",
"cf" => "Rép Centrafricaine",
"cg" => "Congo",
"ch" => "Suisse",
"ci" => "Côte d'Ivoire",
"ck" => "Cook (îles)",
"cl" => "Chili",
"cm" => "Cameroun",
"cn" => "Chine",
"co" => "Colombie",
"cr" => "Costa Rica",
"cs" => "Serbie et Monténégro",    
"cu" => "Cuba",
"cv" => "Cap Vert",
"cx" => "Christmas (île)",
"cy" => "Chypre",
"cz" => "Tchéquie",
"de" => "Allemagne",
"dj" => "Djibouti",
"dk" => "Danemark",
"dm" => "Dominique",
"do" => "Rép Dominicaine",
"dz" => "Algérie",
"ec" => "Equateur",
"ee" => "Estonie",
"eg" => "Egypte",
"er" => "Erythrée",
"es" => "Espagne",
"et" => "Ethiopie",
"fi" => "Finlande",
"fj" => "Fidji",
"fk" => "Malouines (îles)",
"fm" => "Micronésie",
"fo" => "Faroe (îles)",
"fr" => "France",
"ga" => "Gabon",
"gb" => "Grande Bretagne",   
"gd" => "Grenade",
"ge" => "Géorgie",
"gf" => "Guyane Française",
"gg" => "Guernesey",
"gh" => "Ghana",
"gi" => "Gibraltar",
"gl" => "Groenland",
"gm" => "Gambie",
"gn" => "Guinée",
"gp" => "Guadeloupe",
"gq" => "Guinée Equatoriale",
"gr" => "Grèce",
"gs" => "Géorgie du sud",
"gt" => "Guatemala",
"gu" => "Guam",
"gw" => "Guinée-Bissau",
"gy" => "Guyana",
"hk" => "Hong Kong",
"hn" => "Honduras",
"hr" => "Croatie",
"ht" => "Haiti",
"hu" => "Hongrie",
"id" => "Indonésie",
"ie" => "Irlande",
"il" => "Israël",
"in" => "Inde",
"io" => "Ter. Brit. Océan Indien",
"iq" => "Iraq",
"ir" => "Iran",
"is" => "Islande",
"it" => "Italie",
"je" => "Jersey",
"jm" => "Jamaïque",
"jo" => "Jordanie",
"jp" => "Japon",
"ke" => "Kenya",
"kg" => "Kirghizistan",
"kh" => "Cambodge",
"ki" => "Kiribati",
"km" => "Comores",
"kn" => "Saint Kitts et Nevis",
"kr" => "Corée du sud",
"kw" => "Koweït",
"ky" => "Caïmanes (îles)",
"kz" => "Kazakhstan",
"la" => "Laos",
"lb" => "Liban",
"lc" => "Sainte Lucie",
"li" => "Liechtenstein",
"lk" => "Sri Lanka",
"lr" => "Liberia",
"ls" => "Lesotho",
"lt" => "Lituanie",
"lu" => "Luxembourg",
"lv" => "Lettonie",
"ly" => "Libye",
"ma" => "Maroc",
"mc" => "Monaco",
"md" => "Moldavie",
"me" => "Monténégro",
"mg" => "Madagascar",
"mh" => "Marshall (îles)",
"mk" => "Macédoine",
"ml" => "Mali",
"mm" => "Myanmar",
"mn" => "Mongolie",
"mo" => "Macao",
"mp" => "Mariannes du nord (îles)",
"mq" => "Martinique",
"mr" => "Mauritanie",
"mt" => "Malte",
"mu" => "Maurice (île)",
"mv" => "Maldives",
"mw" => "Malawi",
"mx" => "Mexique",
"my" => "Malaisie",
"mz" => "Mozambique",
"na" => "Namibie",
"nc" => "Nouvelle Calédonie",
"ne" => "Niger",
"nf" => "Norfolk (île)",
"ng" => "Nigéria",
"ni" => "Nicaragua",
"nl" => "Pays Bas",
"no" => "Norvège",
"np" => "Népal",
"nr" => "Nauru",
"nu" => "Niue",
"nz" => "Nouvelle Zélande",
"om" => "Oman",
"pa" => "Panama",
"pe" => "Pérou",
"pf" => "Polynésie Française",
"pg" => "Papouasie Nvelle Guinée",
"ph" => "Philippines",
"pk" => "Pakistan",
"pl" => "Pologne",
"pm" => "Saint Pierre et Miquelon",
"pr" => "Porto Rico",
"ps" => "Territoires Palestiniens",   
"pt" => "Portugal",
"pw" => "Palau",
"py" => "Paraguay",
"qa" => "Qatar",
"re" => "Réunion (île de la)",
"ro" => "Roumanie",
"ru" => "Russie",
"rs" => "Russie",
"rw" => "Rwanda",
"sa" => "Arabie Saoudite",
"sb" => "Salomon (îles)",
"sc" => "Seychelles",
"sd" => "Soudan",
"se" => "Suède",
"sg" => "Singapour",
"sh" => "St. Hélène",
"si" => "Slovénie",
"sj" => "Svalbard/Jan Mayen (îles)",
"sk" => "Slovaquie",
"sl" => "Sierra Leone",
"sm" => "Saint-Marin",
"sn" => "Sénégal",
"so" => "Somalie",
"sr" => "Suriname",
"st" => "Sao Tome et Principe",
"sv" => "Salvador",
"sy" => "Syrie",
"sz" => "Swaziland",
"tc" => "Turques-et-Caïques (îles)",
"td" => "Tchad",
"tf" => "Territoires Fr du sud",
"tg" => "Togo",
"th" => "Thailande",
"tj" => "Tadjikistan",
"tk" => "Tokelau",
"tl" => "Timor Leste",   
"tm" => "Turkménistan",
"tn" => "Tunisie",
"to" => "Tonga",
"tr" => "Turquie",
"tt" => "Trinité et Tobago",
"tv" => "Tuvalu",
"tw" => "Taiwan",
"tz" => "Tanzanie",
"ua" => "Ukraine",
"ug" => "Ouganda",
"us" => "États-Unis",
"uy" => "Uruguay",
"uz" => "Ouzbékistan",
"va" => "Vatican",
"vc" => "St Vincent et les Grenadines",
"ve" => "Venezuela",
"vg" => "Vierges Brit. (îles)",
"vi" => "Vierges USA (îles)",
"vn" => "Viêt Nam",
"vu" => "Vanuatu",
"wf" => "Wallis et Futuna",
"ws" => "Western Samoa",
"ye" => "Yemen",
"yt" => "Mayotte",
"za" => "Afrique du Sud",
"zm" => "Zambie",
"zw" => "Zimbabwe",
"xx" => "Inconnu",
"a2" => "Inconnu",
"eu" => "Union Européenne",  
);
?>
