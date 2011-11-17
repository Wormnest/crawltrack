<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.3.2
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Website: www.crawltrack.net
//----------------------------------------------------------------------
// That script is distributed under GNU GPL license
//----------------------------------------------------------------------
// Translation: Giuseppe Velardo (http://www.blackvalley.info)
//----------------------------------------------------------------------
// file: italiano.php
//----------------------------------------------------------------------
//  Last update: 17/11/2011
//----------------------------------------------------------------------
$language= array();
//installation
$language['install']="Istallazione";
$language['welcome_install'] ="Benvenuti su CrawlTrack, istallazione in tre semplici passaggi.";
$language['menu_install_1']="1) Inserisci le informazioni per la connessione al database.";
$language['menu_install_2']="2) Setup del sito web.";
$language['menu_install_3']="3) Setup account Amministratore.";
$language['go_install']="Istalla";
$language['step1_install'] ="Inserisci le informazioni per la connessione al database. Appena esse saranno validate, saranno create le tabelle.";
$language['step1_install_login_mysql']="Username MySQL";
$language['step1_install_password_mysql']="Password MySQL";
$language['step1_install_host_mysql']="Host MySQL";
$language['step1_install_database_mysql']="Database MySQL";
$language['step1_install_ok'] ="Files di connessione OK.";
$language['step1_install_ok2'] ="Creazione tabelle OK.";
$language['step1_install_no_ok'] ="Informazioni mancanti per la creazione delle tabelle e dei files, controlla e reinserisci.";
$language['step1_install_no_ok2'] ="I files non sono stati creati, controlla se la cartella ha i permessi per la scrittura (CHMOD 777).";
$language['step1_install_no_ok3'] ="Problema durante la creazione delle tabelle, ritenta.";
$language['back_to_form'] ="Torna al Form";
$language['retry'] ="Ritenta";
$language['step2_install_no_ok']="Connessione al database impossibile, controlla la stringa di connessione.";
$language['step3_install_no_ok']="Selezione database fallita, controlla la stringa di connessione.";
$language['step4_install']="Vai";
//site creation
//modified in 1.5.0
$language['set_up_site']="Inserisci il nome del sito web(per identificarlo in WebCrawler) e, sotto, il dominio (es: www.dominio.com)."; 
$language['site_name']="Nome sito web:";
//modified in 2.0.0
$language['site_no_ok']="Devi inserire il nome del sito ed il dominio.";
$language['site_ok']="Il sito web e' stato aggiunto al database.";
$language['new_site']="Aggiungi un nuovo sito web";
//tag creation
$language['tag']="Tag da inserire nelle tue pagine";
//modified in 2.3.0
$language['create_tag']="<p><b>Tag Come usare CrawlTrack:</b><br><ul id=\"listtag\">
<li>Il tag CrawlTrack è un file php, è necessario inserirlo in un file .PHP</li>
<li>I tag CrawlTrack devono essere tra i tag <? e php?>, se non ci sono questi tag sulla tua pagina, è necessario aggiungere prima e dopo il tag CrawlTrack.</li>
<li>Se il vostro sito non usa pagine PHP, vedere la documentazione su www.crawltrack.net.</li>
<li>Per la migliore protezione anti-hacking, porre il tag CrawlTrack come prima riga nella tua pagina, subito dopo <?.</li>
<li>Se si utilizza qualsiasi tipo di script di gestione dei contenuti (forum di discussione, blog, gallery, CMS, ecc), dare una occhiata ai www.crawltrack.net / doccms.php per trovare il posto migliore per inserire l'etichetta.</li>
<li>Il tag CrawlTrack non darà assolutamente alcun output visibile sulle pagine (anche nel codice sorgente).</li>
<li>Se si desidera sostenere CrawlTrack e vedere il logo con un link al www.crawltrack.net, troverete modelli logo qui sotto che possono essere messi in qualsiasi posto sulle tue pagine.</li>
<li>Per qualsiasi altra domanda, consultare la documentazione sul www.crawltrack.net o utilizzare il forum di supporto sullo stesso sito.</li></ul></p><br>" ;
$language['site_name2']="Scegli un sito web";
//modified in 1.5.0
$language['local_tag']="Tag standard per un sito ospitato dallo stesso server dove risiede Crawltrack.";
$language['non_local_tag']="Tag da usare se il sito risiede su nun server diverso da quello su cui viene ospitato Crawltrack (avrai anche bisogno che le funzioni fsockopen e fputs siano attivate).";
//login set_up
$language['admin_creation']="Setup Account amministratore";
$language['admin_setup']="Inserisci qui sotto login e password di amministratore.";
$language['user_creation']="Set-up account utente";
$language['user_setup']="Inserisci qui sotto login e password di utente.";
$language['user_site_creation']="Set-up account utente sito singolo";
$language['user_site_setup']="Inserisci qui sotto login e password di utente singolo del sito.";
$language['admin_rights']="L'Amministratore ha accesso al set-up e a tutte le statistiche del sito";
$language['login']="Login";
$language['password']="Password";
$language['valid_password']="Inserisci di nuovo la password.";
$language['login_no_ok']="Informazione mancante o passwords non coincidenti, controlla e riprova.";
$language['login_ok']="L'Account è set-up.";
$language['login_no_ok2']="Problemi durante il set-up dell'account, riprova.";
$language['login_user']="Crea un account utente";
$language['login_user_what']="L'Utente ha accesso a tutte le statistiche del sito";
$language['login_user_site']="Creat account utente singolo del sito";
$language['login_user_site_what']="L'Account utente singolo del sito ha accesso a specifiche statistiche del sito";
//modified in 1.5.0
$language['login_finish']="Installazione terminata. non dimenticare di inserire il tag (disponibile sulla pagina dei tool <img src=\"./images/wrench.png\" width=\"16\" height=\"16\" border=\"0\" >) sulle pagine del tuo sito.";
//access
$language['restrited_access']="Accesso riservato.";
$language['enter_login']="Inserisci qui sotto login e password.";
//display
$language['crawler_name']="Crawlers";
$language['nbr_visits']="Visite";
$language['nbr_pages']="Pagine viste";
$language['date_visits']="Ultima visita";
$language['display_period']="Periodo visualizzato: ";
$language['today']="Giorno";
$language['days']="Settimana";
//modified in 1.5.0
$language['month']="Mese";
$language['one_year']="Anno";
$language['no_visit']="Non ci sono visite.";
$language['page']="Pagine";
//modified in 1.5.0
$language['admin']="Tools";
$language['nbr_tot_visits']="Visite totali";
$language['nbr_tot_pages']="Totale pagine viste";
$language['nbr_tot_crawlers']="Numero di crawlers";
$language['visit_per-crawler']="Dettaglio Visite";
$language['100_visit_per-crawler']="Dettaglio Visite (visualizzazione limitata a %d linee).";
$language['user_agent']="User agent";
$language['Origin']="Utente";
$language['help']="Aiuto";
//search
$language['search']="Cerca";
$language['search2']="Cerca...";
$language['search_crawler']="... crawler";
$language['search_user_agent']="... user-agent";
$language['search_page']="...pagina";
$language['search_user']="...utente crawler";
$language['go_search']="Cerca";
$language['result_crawler']="Ci sono dei crawlers che stai cercando.";
$language['result_ua']="Ci sono degli user-agents che stai cercando.";
$language['result_page']="Ci sono pagine che stai cercando.";
$language['result_user']="Ci sono degli utenti crawler che stai cercando.";
$language['result_user_crawler']="Ci sono dei crawlers di quell'utente.";
$language['result_user_1']="Utente:&nbsp;";
$language['result_crawler_1']="Cerca keyword:&nbsp;";
$language['no_answer']="Non ci sono risposte.";
$language['to_many_answer']="Ci sono più di 100 risposte (vista limitata a 100 linee).";
//admin
$language['user_create']="Crea un nuovo account utente.";
$language['user_site_create']="Crea un nuovo account utente singolo del sito.";
$language['new_site']="Aggiungi un sito.";
$language['see_tag']="Crea tags da inserire sul tuo sito.";
$language['new_crawler']="Aggiungi un nuovo crawler";
$language['crawler_creation']="Compila il seguente modulo con le informazioni del nuovo crawler."; 
$language['crawler_name2']="Nome Crawler:";
$language['crawler_user_agent']="User agent:";
$language['crawler_user']="Utente Crawler:";
$language['crawler_url']="URL Utente (tipo questo: http://www.example.com)";
$language['crawler_url2']="URL Utente:";
$language['crawler_ip']="IP:";
$language['crawler_no_ok']="Informazione mancante, controlla il modulo e riprova.";
$language['exist']="Questo crawler è già nel database";
$language['exist_data']="Ecco le informazioni che lo riguardano nel database:";
$language['crawler_no_ok2']="Problema con la creazione del crawler, riprova.";
$language['crawler_ok']="Il crawler è stato aggiunto al database.";
$language['user_suppress']="Elimina un account utente o utente-sito.";
$language['user_list']="Lista logins utente e utenti-sito";
$language['suppress_user']="Elimina questo account";
$language['user_suppress_validation']="Sicuro di voler eliminare questo account?";
$language['yes']="Si";
$language['no']="No";
$language['user_suppress_ok']="Account eliminato con successo.";
$language['user_suppress_no_ok']="Problema con la eliminazione dell'account, riprova.";
$language['site_suppress']="Elimina un sito.";
$language['site_list']="Lista Siti";
$language['suppress_site']="Elimina questo sito";
$language['site_suppress_validation']="Sicuro di voler eliminare questo sito?";
$language['site_suppress_ok']="Sito eliminato con successo.";
$language['site_suppress_no_ok']="Problema con la eliminazione del sito, riprova.";
$language['crawler_suppress']="Elimina un crawler.";
$language['crawler_list']="Lista Crawler";
$language['suppress_crawler']="Elimina questo crawler";
$language['crawler_suppress_validation']="Sicuro di voler eliminare questo crawler?";
$language['crawler_suppress_ok']="Crawler eliminato con successo.";
$language['crawler_suppress_no_ok']="Problema con la eliminazione del crawler, riprova.";
$language['crawler_test_creation']="Crea un test crawler.";
$language['crawler_test_suppress']="Elimina il test crawler.";
$language['crawler_test_text']="Una volta creato il test crawler, visita il tuo sito con lo stesso computer e browaser utilizzati per creare il crawler."; 
$language['crawler_test_text2']="Se tutto è OK, la tua visita sarà visualizzata in CrawlTrack con visita crawler Test-Crawltrack. Non dimenticare di eliminare il test crawler una volta terminato.";
$language['crawler_test_no_exist']="Il test crawler non esiste nel database.";
$language['exist_site']="Questo sito è già nel database";
$language['exist_login']="Questo login è già nel database";
//1.2.0
$language['update_title']="Aggiornamento lista Crawlers.";
$language['update_crawler']="Aggiorna la lista crawlers.";
$language['list_up_to_date']="Non esiste una lista crawlers aggiornata disponibile.";
$language['update_ok']="Aggiornamento effettuato.";
$language['crawler_add']="crawlers sono stati aggiunti al database";
$language['no_access']="Aggiornamento Online non disponibile.<br><br>Per aggiornare, clicca sul link qui sotto per scaricare l'ultima lista crawlers, fai l'upload di crawlerlist.php nella cartella include di CrawlTrack e riavvia la procedura di aggiornamento.";
$language['no_access2']="Link con www.CrawlTrack.net fallito, riprova più tardi.";
$language['download_update']="Se hai già effettuato l'upload della nuova lista sul tuo sito, clicca sul bottone qui sotto per aggiornare il tuo database.";
$language['download']="Download della lista crawlers.";
$language['your_list']="La lista che stai usando è:";
$language['crawltrack_list']="la lista disponibile su www.Crawltrack.net è:";
$language['no_update']="Non aggiornare la lista crawlers.";
$language['no_crawler_list']="Il file crawlerlist.php non esiste nella cartella include.";
//1.3.0
$language['use_user_agent']="L'intercettazione del Crawler è eseguita da user agent o da IP. Inserisci uno dei due.";
$language['user_agent_or_ip']="User agent o IP";
$language['crawler_ip']="IP:";
$language['table_mod_ok']="Aggiornamento Crawlt_crawler OK.";
$language['files_mod_ok']="Aggiornamento dei files Configconnect.php e crawltrack.php OK.";
$language['update_crawltrack_ok']="Aggiiornamento CrawlTrack terminato, stai usando ora la versione:";
$language['table_mod_no_ok']="Aggiornamento tabella Crawlt_crawler fallito.";
$language['files_mod_no_ok']="Problema durante l'aggiornamento di configconnect.php e crawltrack.php.";
$language['update_crawltrack_no_ok']="Problema durante l'aggiornamento di CrawlTrack.";
$language['no_logo']="Nessun logo.";
//modified in 1.5.0
$language['data_suppress_ok']="Informazione correttamente archiviata.";
$language['data_suppress_no_ok']="Problema durante l'archiviazione , riprova.";
$language['data_suppress_validation']="Sicuro di voler achiviare tutti &nbsp;";
$language['data_suppress']="Archivia le informazioni piu vecchie sulla tabella visite.";
$language['data_suppress2']="Archivia tutto...";
$language['one_year_data']="informazione più vecchie di un anno.";
$language['six_months_data']="informazione più vecchia di 6 mesi.";
$language['one_month_data']="informazione più vecchia di 1 mese.";
$language['oldest_data']="Le date più vecchie dal &nbsp;";
$language['no_data']="Non ci sono dati nella tabella visite.";
//1.4.0
$language['time_set_up']="Time shift";
$language['server_time']="Data e ora del Server =";
$language['local_time']="Data e ora locale=";
$language['time_difference']="Differenza in ore tra la data del server e quella locale=";
$language['time_server']="Stai usando l'ora del server, vuoi usare l'ora locale per mostrare le informazioni?";
$language['time_local']="Stai usando l'ora locale, vuoi usare l'ora del server per mostrare le informazioni?";
$language['decal_ok']="CrawlTrack usa adesso l'ora locale; puoi in qualsiasi momento tornare all'ora del server";
$language['nodecal_ok']="CrawlTrack usa adesso l'ora del server; puoi in qualsiasi momento tornare all'ora locale";
$language['need_javascript']="Devi attivare Javascript per usare questa funzione.";
//1.5.0 
$language['origin']="Sorgente";
$language['crawler_ip_used']="IP usato";
$language['crawler_country']="Paese di origine";
$language['other']="Altri";
$language['pc-page-view']="Percentuale del sito visitato";
$language['pc-page-noview']="Percentuale del sito non visitato";
$language['print']="Stampa";
$language['ip_suppress_ok']="Le visite sono state eliminate con successo.";
$language['ip_suppress_no_ok']="Problema con la cancellazione delle visite, riprova.";
$language['no_ip']="Non ci sono record IP per questo periodo.";
$language['ip_suppress_validation']="Questo IP è stato usato da differenti crawlers, quindi non ci sono dubbi concernenti l'origine di queste visite. Vuoi eliminare le visite da questo IP dalla tabella visite?";
$language['ip_suppress_validation2']="Sicuro di voler eliminare le visite partite da questo IP?";
$language['ip_suppress_validation3']="Se vuoi negare l'accesso al tuo sito da questo IP, aggiungi la seguente linea al tuo file .htaccess nella root del tuo sito:";
$language['ip_suppress']="Elimina un IP";
$language['diff-day-before']="compara con il giorno precedente";
$language['daily-stats']="Statistiche giornaliere";
$language['top-crawler']="Crawler più attivo:";
$language['stat-access']="Vedi statistiche dettagliate";
$language['stat-crawltrack']="Questa informazione è stata raccolta usando:";
$language['nbr-pages-top-crawler']="visita";
$language['of-site']="del sito";
$language['mail']="Ricevi sommario giornaliero tramite email.";
$language['set_up_mail']="Se vuoi ricevere un sommario giornaliero delle statistiche tramite Email inserisci il tuo indirizzo Email.";
$language['email-address']="Indirizzo Email:";
$language['address_no_ok']="Indirizzo immesso non corretto.";
$language['set_up_mail2']="Email del sommario giornaliero attivata. Vuoi disattivarla?";
$language['update']="Modifica effettuata.";
$language['search_ip']="Traccia un indirizzo IP";
$language['ip']="Indirizzo IP";
$language['maxmind']="Il tracking è stato effettuato usando il database GeoLite, creato da Maxmind, disponibile al seguente indirizzo:";
$language['ip_no_ok']="Indirizzo IP inserito non valido.";
$language['public']="Permetti l'acesso pubblico alle statistiche.";
$language['public-set-up2']="L'accesso alle tue statistiche è ora pubblico, vuoi proteggerlo con un password?";
$language['public-set-up']="L'accesso alle tue statistiche è ora protetto da password, vuoi renderlo pubblico?";
$language['public2']="Solo la pagina Tools rimarrà protetta da password";
$language['admin_protected']="L'accesso alla pagina Tools è protetta.";
$language['no_data_to_suppress']="Non ci sono dati da archiviare per il periodo richiesto.";
$language['data_suppress3']="L'archiviazione delle informazioni riduce la dimensione del database, ma i dati corrispondenti non saranno
 isponibili per la visualizzazione delle statistiche. Sarà disponibile solo una tabella sommaria (guarda Crawlers/Archivio).
 Arcdhiviare le informazioni è la cosa migliore solo se hai realmente bisogno di ridurre la dimensione del tuo database; i dettagli saranno persi definitivamente.";
$language['archive']="Archivi";
$language['month2']="Mese";
$language['top_visits']="Top 3 nel numero di visite";
$language['top_pages']="Top 3 nel numero di pagine viste";
$language['no-archive']="Non ci sono dati archiviati.";
$language['use-archive']="Dal momento che parte dei dati è stata archiviata, questi valori non sono completi.";
$language['url_update']="Aggiorna i dati del sito";
$language['set_up_url']="Completa la tabella seguente con il dominio del sito tipo: www.example.com (senza http:// all'inizio e / alla fine)."; 
$language['site_url']="Dominio Sito:";
//1.6.0
$language['page_cache']="Cache Pagina a: ";
//1.7.0
$language['step1_install_no_ok4']="Problema durante il riempimento della tabella IP, questo può succedere su qualche host la cui tabella contenga più di 78,000 linee. Puoi sia riprovare che continuare senza questa tabella. Se continui, non potrai visualizzare il paese di origine dei crawlers. Vedi la pagina 'Troubleshooting' della documentazione su www.crawltrack.net per riempire manualmente la tabella.";
$language['show_all']="Mostra tutte le linee";
$language['from']="da";
$language['to']="a";
$language['firstweekday-title']="Scegli il primo giorno della settimana";
$language['firstweekday-set-up2']="Il primo giorno della settimana è Lunedì, vuoi cambiarlo in Domenica?";
$language['firstweekday-set-up']="Il primo giorno della settimana è Domenica, vuoi cambiarlo in Lunedì?";
$language['01']="Gennaio";
$language['02']="Febbraio";
$language['03']="Marzo";
$language['04']="Aprile";
$language['05']="Maggio";
$language['06']="Giugno";
$language['07']="Luglio";
$language['08']="Agosto";
$language['09']="Settembre";
$language['10']="Ottobre";
$language['11']="Novembre";
$language['12']="Dicembre";
$language['day0']="Lunedì";
$language['day1']="Martedì";
$language['day2']="Mercoledì";
$language['day3']="Giovedì";
$language['day4']="Venerdì";
$language['day5']="Sabatoy";
$language['day6']="Domenica";
//2.0.0
$language['ask']="Ask";
$language['google']="Google";
$language['msn']="Bing";  //change for 3.1.1
$language['yahoo']="Yahoo";
$language['delicious']="Del.icio.us";
$language['index']="Indexation";
$language['keyword']="Keywords";
$language['entry-page']="Pagina iniziale";
$language['searchengine']="Motori di ricerca";
$language['social-bookmark']="Bookmarks Sociali";
$language['tag']="Tags";
$language['nbr_tot_bookmark']="Bookmarks";
$language['nbr_tot_link']="Backlinks";
$language['nbr_tot_pages_index']="Pagine indicizzate";
$language['nbr_visits_crawler']="Numero di visite crawler";
$language['nbr_tot_visit_seo']="Visitatori inviati al sito";
$language['100_lines']="Visualizzazione limitata a %d linee.";
$language['8days']="Ultimi 8 giorni";
$language['close']="Chiudi";
$language['date']="Data";
$language['modif_site']="Modifica il nome o il dominio di un sito.";
$language['site_url2']="Dominio";
$language['modif_site2']="Modifica questa informazione del sito.";
$language['no-info-day-before']="Nessuna informazione del giorno precedente";
$language['data_human_suppress_ok']="Informazione eliminata con successo.";
$language['data_human_suppress_no_ok']="Problema con la eliminazione dell'informazione, riprova.";
$language['data_human_suppress_validation']="Sicuro di voler eliminare tutte &nbsp;";
$language['data_human_suppress']="Elimina le informazioni più vecchie nella tabella delle visite umane(keywords e pagine iniziali).";
$language['data_human_suppress2']="Elimina...";
$language['one_year_human_data']="informazione vecchie più di un anno";
$language['six_months_human_data']="informazione vecchia più di 6 mesi";
$language['one_month_human_data']="informazione vecchia più di un mese";
$language['data_human_suppress3']="L'eliminazione dell'informazione riduce la dimensione del database, ma le informazioni non saranno disponibili
per la visualizzazione delle statistiche. La cosa migliore è cancellare l'informazione solo se veramente vuoi ridurre la dimensione del database; l'informazione è irrimediabilmente persa.";
$language['no_data_human_to_suppress']="Non ci sono dati nella tabella delle visite umane.";
$language['choose_language']="Scegli il tuo linguaggio.";
//2.1.0
$language['since_beginning']="Tutto";
//2.2.0
$language['admin_database']="Vedi la dimensione del database";
$language['table_name']="Nome Tabella";
$language['nbr_of_data']="Numero di records";
$language['table_size']="Dimensione Tabella";
$language['database_size']="Dimensione Database";
$language['total']="Totale:";
$language['mailsubject']="Sommario giornaliero CrawlTrack";
$language['yesterday']="Ieri";
$language['beginmonth']="Sin dall'inizio del mese";
$language['evolution']="Cambia compara con";
$language['lastthreemonths']="3 ultimi mesi";
$language['set_up_mail3']="Stai usando il seguente indirizzo:";
$language['set_up_mail4']="Aggiungi un indirizzo";
$language['set_up_mail5']="Inserisci il nuovo indirizzo Email.";
$language['set_up_mail6']="Elimina uno o più indirizzi Email";
$language['set_up_mail7']="Elimina gli indirizzi selezionati";
$language['chmod_no_ok']="L'aggiornamento del file crawltrack.php file non è andato a buon fine, CHMOD 777 la cartella CrawlTrack e rifai l'aggiornamento. Per ragioni di sicurezza, non dimenticare di fare CHMOD 711 al termine dell'aggiornamento.";
$language['display_parameters']="Mostra i parametri";
$language['ordertype']="Ordina:";
$language['orderbydate']="per data";
$language['orderbypagesview']="per numero di pagine viste";
$language['orderbyvisites']="per numero di visite";
$language['orderbyname']="in ordine alfabetico";
$language['numberrowdisplay']="Numero di righe visualizzate:";
//2.2.1
$language['french']="Francese";
$language['english']="Inglese";
$language['german']="Tedesco";
$language['spanish']="Spagnolo";
$language['turkish']="Turco";
$language['dutch']="Olandese";
//2.3.0
$language['hacking']="Attacchi";
$language['hacking2']="Tentativi di Hacking";
$language['hacking3']="Iniezioni di codice";
$language['hacking4']="Iniezioni SQL";
$language['no_hacking']="CrawlTrack non ha intercettato tentativi";
$language['attack_detail']="Dettagli attacchi";
$language['attack']="Parametri usati per tentativi di iniezione di codice";
$language['attack_sql']="Parametri usati per temtativi di iniezione SQL";
$language['bad_site']="File/script che l'hacker ha tentato di iniettare";
$language['bad_sql']="Query SQL che l'hacker ha tentato di iniettare";
$language['bad_url']="Richieste URL";
$language['hacker']="Assaltatori";
$language['date_hacking']="Ora";
$language['unknown']="Sconosciuto";
$language['danger']="Potresti essere a rischio se tu esegui uno di questi scripts";
$language['attack_number_display']="Dettagli degli Attacchi (visualizzazione limitata a %d assaltatori).";
$language['update_attack']="Aggiorna la lista degli attacchi.";
$language['no_update_attack']="Non aggiornare la lista degli attacchi.";
$language['update_title_attack']="Aggiornamento della lista degli attacchi.";
$language['attack_type']="Tipo di attacco";
$language['parameter']="Parametri";
$language['script']="Script";
$language['attack_add']="Gli attacchi sono stati aggiunti al database";
$language['no_access_attack']="Aggiornamento online non disponibile.<br><br>Per aggiornare, clicca sul link qui sotto per scaricare l'ultima lista di attacchi, fai l'upload del file attacklist.php nella cartella include di CrawlTrack e riavvia l'aggiornamento.";
$language['download_update_attack']="Se hai già fatto l'upload della nuova lista di attacchi sul tuo sito, clicca sul bottone qui sotto per aggiornare il database.";
$language['download_attack']="Scarica la lista di attacchi.";
$language['no_attack_list']="Il file attacklist.php non esiste nella tua cartella include.";
$language['change_password']="Cambia la tua password";
$language['old_password']="Password attuale";
$language['new_password']="Nuova password";
$language['valid_new_password']="Inserisci di nuovo la password.";
$language['goodsite_update']="Aggiornamento lista siti puliti";
$language['goodsite_list']="Siti puliti";
$language['goodsite_list2']="Un link a questo sito incluso non sara valutato come un attacco";
$language['goodsite_list3']="Lista corrente di siti puliti";
$language['suppress_goodsite']="Elimina il sito dalla lista.";
$language['goodsite_suppress_validation']="Sicuro di voler eliminare questo sito?";
$language['good_site']="Sito pulito";
$language['goodsite_suppress_ok']="Sito eliminato con successo.";
$language['goodsite_suppress_no_ok']="Problema durante l'eliminazione del sito, riprova.";
$language['list_empty']="Non ci sono ancora siti puliti";
$language['add_goodsite']="Aggiungi un sito pulito alla lista";
$language['goodsite_no_ok']="Devi inserire un URL del sito.";
$language['attack-blocked']="Tutti questi attacchi sono stati bloccati da CrawlTrack come richiesto";
$language['attack-no-blocked']="Stai attento che il tuo CrawlTrack non è settato per bloccare attacchi (guarda la pagina Tools)";
$language['attack_parameters']="Hacking protection parameters";
$language['attack_action']="Azione da intraprendere quando un attacco viene intercettato";
$language['attack_block']="Registralo e bloccalo";
$language['attack_no_block']="Limitati a registrarlo";
$language['attack_block_alert']="Prima di bloccare gli attacchi (importante per la sicurezza del tuo sito), dai uno sguardo alla documentazione
(su www.crawltrack.net) per assicurarti che bloccare gli attacchi non causi un problema con i visitatori normali del tuo sito.";
$language['crawltrack-backlink']="CrawlTrack è free, se ti piace e vuoi condividerlo, perché non metti un link nelle pagine del tuo sito?<br><br>Se scegli
l'opzione 'nologo' per il tag tracking, puoi usare questa grafica alternativa (una versione per un PHP ed un'altra per HTML) da inserire ovunque sulle tue pagine.";
$language['session_id_parameters']="Trattamento Session ID";
$language['remove_session_id']="Rimuovi ID Sessione da pagina URL";
$language['session_id_alert']="Rimuovendo l'ID Sessione dalla pagina URL non si avrà l'opportunità di avere più voci nella tabella delle pagine se usi scripts con ID Sessione nell'URL.";
$language['session_id_used']="ID Sessione usati";
//3.0.0
$language['webmaster_dashboard']="Postazione Webmaster";
$language['summary']="Sommario tutti i siti";
$language['charge']="Carico del server";
$language['unidentified']="non identificato";
$language['display_period2']="Scelta periodo";
$language['visitors']="Visitatori";
$language['unique_visitors']="Visitatori unici";
$language['visits']="Visite";
$language['nbr_tot_visits2']="Totale visite";
$language['nbr_tot_visits3']="Totale";
$language['referer']="Referer";
$language['website']="Motori di ricerca Website & altri";
$language['website2']="siti";
$language['website3']="Websites";
$language['country']="paese";
$language['direct']="Arrivi Direti";
$language['average_pages']="Pagine viste per visita";
$language['stats_visitors']="Statistiche Visitatori";
$language['count_in_stats']="Non contare le tue visite nei seguenti siti:";
$language['stats_visitors_other_domain']="Se uno dei siti è ospitato su un altro server; devi copiare il file crawltsetcookie.php (puoi trovarlo nella cartella php di Crawltrack) nella root del tuo sito prima clicca su OK perché a tua scelta sia presa in considerazione.";
$language['main_crawlers']="Crawlers principali";
$language['magnifier']="Fai una ricerca nel database CrawlTrack";
$language['refresh']="Svuota la cache e ricalcola i dati";
$language['wrench']="Vai alla pagina di Amministrazione Crawltrack";
$language['printer']="Stampa questa pagina";
$language['information']="Documentazione su www.crawltrack.net";
$language['help']="Circa CrawlTrack";
$language['cross']="Logout";
$language['home']="Torna alla home page";
$language['badreferer']="Sicuro di voler mettere questa area nella lista dei referer spammers? Una volta aggiunto a questa lista le visite in questa area non saranno prese in considerazione da CrawlTrack.";
$language['spamreferer']="Setta questo campo nella lista di spammers";
$language['badreferer_update']="Aggiorna la lista di siti per i referer spammers";
$language['add_badreferer']="Aggiungi un sito di referer spammer alla lista";
$language['listbadreferer_empty']="Non ci sono siti di referer spamming";
$language['badreferer_list']="Siti di referer spammers";
$language['badreferer_list2']="Le Visite da questi siti non saranno prese in considerazione da CrawlTrack.";
$language['badreferer_list3']="La lista di siti attuale per i referer spammers";
$language['badreferer_site']="Sito Webs di referer spammers";

$language['goodreferer']="Sicuro di voler mettere questo campo nella lista di siti che ha un link al tuo sito? Una volta aggiunto a questa lista, le visite non saranno più prese in considerazione da CrawlTrack senza ulteriore controllo.";
$language['goodreferer2']="Setta questo campo nella lista di siti che hanno un link al tuo sito";

$language['goodreferer_update']="Aggiorna la lista di siti che hanno un link al tuo sito";
$language['add_goodreferer']="Aggiungi il link ad un sito web al tuo sito dalla lista";
$language['listgoodreferer_empty']="Non ci sono siti che hanno un link al tuo sito";
$language['goodreferer_list']="Siti che hanno un link al tuo sito";
$language['goodreferer_list2']="Le visits da questi siti non saranno più prese in considerazione da CrawlTrack senza ulteriore controllo.";
$language['goodreferer_list3']="La lista attuale di siti che hanno un link al tuo sito";
$language['goodreferer_site']="Sito con un link al tuo sito";

$language['download']="Downloads";
$language['file']="File";
$language['download_period']="Oltre il periodo";
$language['download_link']="Counta downloads";
$language['download_link2']="<b>I tuoi downloads che sono stati conteggiati da CrawlTrack:</b><br><br>
-I files scaricabili devono essere ospitati su uno dei siti controllati da CrawlTrack.<br>
-il link per il download (per un file la cui collocazione è http://www.example.com/folder/file.zip) deve essere nella forma :";
$language['download_link3']="http://www.example.com/folder/file.zip";
$language['download_link4']="Questo è tutto, nessun'altra manipolazione è richiesta.";
$language['error']="Errori 404";
$language['number']="Numero";
$language['outer-referer']="Links Esterni";
$language['inner-referer']="Links Interni";
$language['error-attack']="Inclusi attacchi di hackers";
$language['total_hacking']="Numero di attacchi";
$language['error_hacking']="Attacchi che hanno riportato un 404";
$language['error_page']="URL richiesto";
$language['crawler_error']="Dettagli degli errori 404 dai robots";
$language['direct_error']="Dettagli degli errori 404 dovuti ad arrivi diretti";
$language['extern_error']="Dettagli degli errori 404 dovuti a link esterno al sito";
$language['intern_error']="Dettagli degli errori 404 dovuti ad un link interno al sito";
$language['error_referer']="URL Originale";
$language['404_no_in_graph']="Questi attacchi non saranno presi in considerazione  per il numero IP, i dettagli in grafico e tabella.";
$language['404_no_in_graph2']="Gli attacchi 404 risultanti non appaiono sul grafico.";
$language['exalead']="Exalead";
$language['connect']="Sei identificato";
$language['connect_you']="Login";
$language['notcheck']="Link non sottoposto a revisione, clicca su 'Controlla links' per iniziare la verifica.";
$language['checklink']="Controlla links";
$language['linkok']="Validazione Link";
$language['first_date_visits']="Prima visita";
$language['next_visits']="Prossima visita";
$language['data_suppress']="Riduce la dimensione del database."; //modified in 3.0.0
$language['data_suppress2']="Rimuovi ";
$language['other_bot']="tutte le visite eccetto i seguenti robots; Ask Jeeves / Teoma, Exabot, Googlebot, MSN Bot e Slurp Inktomi (Yahoo)";
$language['one_year_data']="tutte le visite ai vecchi robots in un anno";
$language['six_months_data']="tutte le visite ai vecchi robots in 6 mesi";
$language['five_months_data']="tutte le visite ai vecchi robots in 5 months";
$language['four_months_data']="tutte le visite ai vecchi robots in 4 mesi";
$language['three_months_data']="tutte le visite ai vecchi robots in 3 mesi";
$language['two_months_data']="tutte le visite ai vecchi robots in 2 mesi";
$language['one_month_data']="tutte le visite ai vecchi robots in 1 mese";
$language['one_year_data_human']="tutti i vecchi visitatori da più di un anno";
$language['six_months_data_human']="tutti i vecchi visitatori altre i 6 mesi";
$language['five_months_data_human']="tutti i vecchi visitatori altre i 5 mesi";
$language['four_months_data_human']="tutti i vecchi visitatori altre i 4 mesi";
$language['three_months_data_human']="tutti i vecchi visitatori altre i 3 mesi";
$language['two_months_data_human']="tutti i vecchi visitatori altre i 2 mesi";
$language['one_month_data_human']="tutti i vecchi visitatori altre 1 mese";
$language['attack_data']="tutti i dati concernenti gli attacchi di pirateria";
$language['oldest_data']="Il più vecchi di &nbsp;";
$language['no_data']="Non ci sono datiNon ci sono dati nella tabella di visite.";
$language['no_data_to_suppress']="Non ci sono dati da eliminare per il periodo richiesto.";
$language['data_suppress3']="Attenzione! L'eliminazione dei dati riduce la dimensione del database, ma a parte questo tutti i dati andranno rrimediabilmente perduti.";
$language['data_suppress_ok']="Dati eliminati con successo.";
$language['data_suppress_no_ok']="Problema durante l'eliminazione dei dati, riprova.";
$language['data_suppress_validation']="Sicuro di voler eliminare tutti &nbsp;";
$language['deltatime']="Frequenza di visite";
$language['nbr_tot_visit_seo']="Origine visite";
$language['url_parameters']="Parametri nell'URL";
$language['remove_parameter']="Rimuovi i parametri URL";
$language['remove_parameter_alert']="RImuovendo i parametri URL sicuramente previene un eccessivo ingrossamento della pagina della tabella, per contro ogni tipo di URL: www.example.com/index.php?article=225 sarà registrato nella forma www.example.com / index.php dando un minor numero di dettagli sulle pagine visitate.";
$language['bookmark']="Usa l'indirizzo di questa pagina nei Preferiti";
$language['evolution']="Trend numero di visitatori unici";
$language['perday']="per giorno";
$language['shortterm']="Ultimi 7 giorni:";
$language['longterm']="30 giorni:";
$language['bounce_rate']="Frequenza di rimbalzo";
$language['visit_summary']="Visite cumulative su tutti i siti";
$language['data']="Dati";
$language['index']="Indice";
$language['sponsorship']="Supportano CrawlTrack:";
//3.1.0
$language['browser']="Browsers";
$language['visitor-browser']="Browsers usati dai visitatori";
$language['hits-per-hour']="Accessi per ora";
$language['russian']="Russo";
//3.1.2
$language['besponsor']="Usa CrawlTrack per presentare i tuoi prodotti e servizi a centinaia di Webmasters.";
$language['ad-on-crawltrack']="<a href=\"http://translate.google.fr/translate?u=http%3A%2F%2Fwww.ad42.com%2Fzone.aspx%3Fidz%3D6690%26ida%3D-1&sl=fr&tl=en&hl=fr&ie=UTF-8\" target=\"_blank\">Perché non usare CrawlTrack per presentare i tuoi prodotti e servizi a centinaia di webmasters?</a>";
//3.2.0
$language['baidu']="Baidu";
$language['googleposition']="Posizione<br>in Google";
$language['position']="Posizione corrente";
$language['positiononemonth']="Posizione un mese fa";
$language['positiontwomonth']="Posizione due mesi fa";
$language['positionthreemonth']="Posizione tre mesi fa";
$language['googledetail']="Dettagli di posizione in Google e accessi generati";
//3.2.3
$language['bulgarian']="Bulgaro";
//3.2.8
$language['italian']="Italiano";
$language['two_year_data']="tutte le visite ai vecchi robots in 2 anni";
$language['two_year_data_human']="tutti i vecchi visitatori da più di 2 anni";
//3.3.1
$language['googleimage']="Google-Images";
//3.3.2
$language['yandex']="Yandex";
$language['aol']="Aol";
$language['no_visitors_stats']="Count only crawlers";
$language['no_visitors_stats2']="Don't count any visit";
$language['no_cookie']="You have to set up your browser to accept cookies";
//country code
$country = array(

    "ad" => "Andorra",
    "ae" => "United Arab Emirates",
    "af" => "Afghanistan",
    "ag" => "Antigua and Barbuda",
    "ai" => "Anguilla",
    "al" => "Albania",
    "am" => "Armenia",
    "an" => "Netherlands Antilles",
    "ao" => "Angola",
    "ap" => "Asia/Pacific Region",    
    "aq" => "Antarctica",
    "ar" => "Argentina",
    "as" => "American Samoa",
    "at" => "Austria",
    "au" => "Australia",
    "aw" => "Aruba",
    "az" => "Azerbaijan",
    "ba" => "Bosnia and Herzegovina",
    "bb" => "Barbados",
    "bd" => "Bangladesh",
    "be" => "Belgium",
    "bf" => "Burkina Faso",
    "bg" => "Bulgaria",
    "bh" => "Bahrain",
    "bi" => "Burundi",
    "bj" => "Benin",
    "bm" => "Bermuda",
    "bn" => "Bruneo",
    "bo" => "Bolivia",
    "br" => "Brazil",
    "bs" => "Bahamas",
    "bt" => "Bhutan",
    "bw" => "Botswana",
    "by" => "Belarus",
    "bz" => "Belize",
    "ca" => "Canada",
    "cd" => "The Democratic Republic of the Congo",
    "cf" => "Central African Republic",
    "cg" => "Congo",
    "ch" => "Switzerland",
    "ci" => "Cote D'Ivoire",
    "ck" => "Cook Islands",
    "cl" => "Chile",
    "cm" => "Cameroon",
    "cn" => "China",
    "co" => "Colombia",
    "cr" => "Costa Rica",
    "cs" => "Serbia and Montenegro",
    "cu" => "Cuba",
    "cv" => "Cape Verde",
    "cx" => "Christmas Island",
    "cy" => "Cyprus",
    "cz" => "Czech Republic",
    "de" => "Germany",
    "dj" => "Djibouti",
    "dk" => "Denmark",
    "dm" => "Dominica",
    "do" => "Dominican Republic",
    "dz" => "Algeria",
    "ec" => "Ecuador",
    "ee" => "Estonia",
    "eg" => "Egypt",
    "er" => "Eritrea",
    "es" => "Spain",
    "et" => "Ethiopia",
    "fi" => "Finland",
    "fj" => "Fiji",
    "fk" => "Falkland Islands (Malvinas)",
    "fm" => "Federated States of Micronesia ",
    "fo" => "Faroe Islands",
    "fr" => "France",
    "ga" => "Gabon",
    "gb" => "Great Britain",
    "gd" => "Grenada",
    "ge" => "Georgia",
    "gf" => "French Guyana",
    "gg" => "Guernesey",
    "gh" => "Ghana",
    "gi" => "Gibraltar",
    "gl" => "Greenland",
    "gm" => "Gambia",
    "gn" => "Guinea",
    "gp" => "Guadeloupe",
    "gq" => "Equatorial Guinea",
    "gr" => "Greece",
    "gs" => "South Georgia and the South Sandwich Islands",
    "gt" => "Guatemala",
    "gu" => "Guam",
    "gw" => "Guinea-Bissau",
    "gy" => "Guyana",
    "hk" => "Hong Kong",
    "hn" => "Honduras",
    "hr" => "Croatia",
    "ht" => "Haiti",
    "hu" => "Hungary",
    "id" => "Indonesia",
    "ie" => "Ireland",
    "il" => "Israel",
    "im" => "Isle of Man",
    "in" => "India",
    "io" => "British Indian Ocean Territory",
    "iq" => "Iraq",
    "ir" => "Iran",
    "is" => "Iceland",
    "it" => "Italy",
    "je" => "Jersey",
    "jm" => "Jamaica",
    "jo" => "Jordan",
    "jp" => "Japan",
    "ke" => "Kenya",
    "kg" => "Kyrgyzstan",
    "kh" => "Cambodia",
    "ki" => "Kiribati",
    "km" => "Comoros",
    "kn" => "Saint Kitts and Nevis",
    "kr" => "Republic of Korea",
    "kw" => "Kuwait",
    "ky" => "Cayman Islands",
    "kz" => "Kazakhstan",
    "la" => "Laos",
    "lb" => "Lebanon",
    "lc" => "Saint Lucia",
    "li" => "Liechtenstein",
    "lk" => "Sri Lanka",
    "lr" => "Liberia",
    "ls" => "Lesotho",
    "lt" => "Lithuania",
    "lu" => "Luxembourg",
    "lv" => "Latvia",
    "ly" => "Libya",
    "ma" => "Morocco",
    "mc" => "Monaco",
    "md" => "Moldova",
    "me" => "Montenegro",
    "mg" => "Madagascar",
    "mh" => "Marshall Islands",
    "mk" => "Macedonia",
    "ml" => "Mali",
    "mm" => "Myanmar",
    "mn" => "Mongolia",
    "mo" => "Macau",
    "mp" => "Northern Mariana Islands",
    "mq" => "Martinique",
    "mr" => "Mauritania",
    "ms" => "Montserrat",
    "mt" => "Malta",
    "mu" => "Mauritius",
    "mv" => "Maldives",
    "mw" => "Malawi",
    "mx" => "Mexico",
    "my" => "Malaysia",
    "mz" => "Mozambique",
    "na" => "Namibia",
    "nc" => "New Caledonia",
    "ne" => "Niger",
    "nf" => "Norfolk Island",
    "ng" => "Nigeria",
    "ni" => "Nicaragua",
    "nl" => "Netherlands",
    "no" => "Norway",
    "np" => "Nepal",
    "nr" => "Nauru",
    "nu" => "Niue",
    "nz" => "New Zealand",
    "om" => "Oman",
    "pa" => "Panama",
    "pe" => "Peru",
    "pf" => "French Polynesia",
    "pg" => "Papua New Guinea",
    "ph" => "Philippines",
    "pk" => "Pakistan",
    "pl" => "Poland",
    "pm" => "Saint Pierre et Miquelon",
    "pr" => "Puerto Rico",
    "ps" => "Palestinian territory",
    "pt" => "Portugal",
    "pw" => "Palau",
    "py" => "Paraguay",
    "qa" => "Qatar",
    "re" => "Reunion Island",
    "ro" => "Romania",
    "ru" => "Russian Federation",
    "rs" => "Russia",
    "rw" => "Rwanda",
    "sa" => "Saudi Arabia",
    "sb" => "Solomon Islands",
    "sc" => "Seychelles",
    "sd" => "Sudan",
    "se" => "Sweden",
    "sg" => "Singapore",
    "sh" => "Saint Helena",
    "si" => "Slovenia",
    "sj" => "Svalbard",
    "sk" => "Slovakia",
    "sl" => "Sierra Leone",
    "sm" => "San Marino",
    "sn" => "Senegal",
    "so" => "Somalia",
    "sr" => "Suriname",
    "st" => "Sao Tome and Principe",
    "sv" => "El Salvador",
    "sy" => "Syrian Arab Republic",
    "sz" => "Switzerland",
    "tc" => "Turks and Caicos Islands",
    "td" => "Chad",
    "tf" => "French Southern Territories",
    "tg" => "Togo",
    "th" => "Thailand",
    "tj" => "Tajikistan",
    "tk" => "Tokelau",
    "tl" => "Timor Leste",
    "tm" => "Turkmenistan",
    "tn" => "Tunisia",
    "to" => "Tonga",
    "tr" => "Turkey",
    "tt" => "Trinidad and Tobago",
    "tv" => "Tuvalu",
    "tw" => "Taiwan",
    "tz" => "Tanzania",
    "ua" => "Ukraine",
    "ug" => "Uganda",
    "us" => "United States",
    "uy" => "Uruguay",
    "uz" => "Uzbekistan",
    "va" => "Vatican City",
    "vc" => "Saint Vincent and the Grenadines",
    "ve" => "Venezuela",
    "vg" => "Virgin Islands, British",
    "vi" => "Virgin Islands, U.S.",
    "vn" => "Vietnam",
    "vu" => "Vanuatu",
	"wf" => "Wallis et Futuna",
    "ws" => "Samoa",
    "ye" => "Yemen",
    "yt" => "Mayotte",
    "za" => "South Africa",
    "zm" => "Zambia",
    "zw" => "Zimbabwe",
    "xx" => "Unknown",
    "a2" => "Unknown",
    "eu" => "European Union",    
);

?>
