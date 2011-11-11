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
// file: russian.php
//----------------------------------------------------------------------
// translation on russian: Goodluck Dmitry(Cema) http://goodluck.org.ua
//----------------------------------------------------------------------
//  Last update: 11/11/2011
//----------------------------------------------------------------------
$language= array();
//installation
$language['install']="Установка";
$language['welcome_install'] ="Добро пожаловать в CrawlTrack, лёгкая установка в три этапа.";
$language['menu_install_1']="1) Ввод значений в базу данных.";
$language['menu_install_2']="2) Установка веб сайтов.";
$language['menu_install_3']="3) Настройка учётной записи администратора.";
$language['go_install']="Установить";
$language['step1_install'] ="Пожалуйста, введите в следующей форме информацию относительно базы данных. После того, как форма подтверждена, таблицы и файлы соединения будут созданы";
$language['step1_install_login_mysql']="Пользователи MySQL";
$language['step1_install_password_mysql']="Пароль MySQL";
$language['step1_install_host_mysql']="Хост MySQL";
$language['step1_install_database_mysql']="База данных MySQL";
$language['step1_install_ok'] ="соединение установлено OK.";
$language['step1_install_ok2'] ="Таблицы созданы OK.";
$language['step1_install_no_ok'] ="Не хватает информации для создания таблиц и файлов, пожалуйста после коррекции, подтвердите содержание формы.";
$language['step1_install_no_ok2'] ="Файл не был создан, проверьте, если папка Chmod 777.";
$language['step1_install_no_ok3'] ="Во время создания таблицы возникают проблемы, попробуйте еще раз.";
$language['back_to_form'] ="Перейти к форме";
$language['retry'] ="Попробуйте ещё раз";
$language['step2_install_no_ok']="Подключение к базе данных не представляется возможным, пожалуйста, проверьте данные подключения.";
$language['step3_install_no_ok']="Выбранная база данных не отвечает, пожалуйста, проверьте данные подключения.";
$language['step4_install']="Вперёд";
//site creation
//modified in 1.5.0
$language['set_up_site']="Пожалуйста, введите ниже название сайта и URL-адрес, название это то, что будет использоваться в CrawlTrack. Адрес должен быть как: www.example.com (без http:// в начале и / в конце)."; 
$language['site_name']="Название сайта:";
//modified in 2.0.0
$language['site_no_ok']="Вы должны ввести название сайта и URL-адреса.";
$language['site_ok']="Этот веб сайт добавлен в базу данных.";
$language['new_site']="Добавить новый веб-сайт";
//tag creation
$language['tag']="Метка для вставки на ваши страницы";
//modified in 2.3.0
$language['create_tag']="<p><b>Как использовать метки CrawlTrack:</b><br><ul id=\"listtag\">
<li>Метки CrawlTrack это php файлы, Вы должны вставить их в .php страницы.</li>
<li>CrawlTrack метка должна быть между &#60;?php и ?&#62 тегами, если нет таких тегов на вашей странице, вы должны добавить их до и после CrawlTrack меток.</li>
<li>Если ваш сайт не использует .php страницы, смотрите документацию на www.crawltrack.net.</li>
<li>лучшей анти-взлом защитой будет, установка тега CrawlTrack вначале вашей страницы, сразу после &#60;?php.</li>
<li>Если вы используете какой-либо скрипт управления содержанием   (форум, блог, галерея, CMS и.т.д.), посмотрите на www.crawltrack.net/doccms.php где можно найти наилучшее решение, куда поставить метку.</li>
<li>Метка CrawlTrack абсолютна невидима на ваших страницах (даже в исходном коде).</li>
<li>Чтобы поддержать CrawlTrack вы можете установить логотип с ссылкой на www.crawltrack.net,логотип модели, который вы найдете ниже, можно поставить в любом месте, на ваших страницах.</li>
<li>по любым другим вопросам, обратитесь к документации на www.crawltrack.net или пользуйтесь форумом поддержки на этом сайте.</li></ul></p><br>" ;
$language['site_name2']="Название сайта";
//modified in 1.5.0
$language['local_tag']="Стандартные метки, которые будут использоваться на сайте размещаются на одном и том же сервере, что и Crawltrack.";
$language['non_local_tag']="Метки, которые будут использоваться, если сайт размещен на одном сервере, а плагин Crawltrack на другом, в этом случае  у вас функции fsockopen и fputs должны быть активированы.";
//login set_up
$language['admin_creation']="Администратор создания учетной записи";
$language['admin_setup']="Пожалуйста, введите ниже логин и пароль администратора.";
$language['user_creation']="Настройка Учетной записи пользователя";
$language['user_setup']="Пожалуйста, введите ниже имя пользователя и пароль.";
$language['user_site_creation']="Пользователь-сайт созданой учетной записи";
$language['user_site_setup']="Пожалуйста, введите ниже логин и пароль пользователя на сайте.";
$language['admin_rights']="Администратор имеет доступ ко всей статистике и настройкам на веб-сайте";
$language['login']="логин";
$language['password']="пароль";
$language['valid_password']="Введите еще раз пароль.";
$language['login_no_ok']="Данные отсутствуют или пароли являются разными, пожалуйста,подтвердите форму и содержание после коррекции.";
$language['login_ok']="Установки аккаунта.";
$language['login_no_ok2']="Возникают проблемы во время создания учетной записи, попробуйте еще раз.";
$language['login_user']="Создать учетную запись";
$language['login_user_what']="Пользователь имеет доступ ко всей статистике веб-сайта";
$language['login_user_site']="Создать учетную запись пользователя на сайте";
$language['login_user_site_what']="Пользователь сайта имеет доступ к статистике одного сайта";
//modified in 1.5.0
$language['login_finish']="Установка в настоящее время закончена. Не забудьте поставить метку (доступна на странице <img src=\"./images/wrench.png\" width=\"16\" height=\"16\" border=\"0\" >) на страницах вашего сайта.";
//access
$language['restrited_access']="Ограниченный доступ.";
$language['enter_login']="Пожалуйста, введите ниже Ваш логин и пароль.";
//display
$language['crawler_name']="Роботы";
$language['nbr_visits']="Посещения";
$language['nbr_pages']="Просмотренные страницы";
$language['date_visits']="Последнее посещение";
$language['display_period']="Отчёт за :";
$language['today']="День";
$language['days']="Неделя";
//modified in 1.5.0
$language['month']="Месяц";
$language['one_year']="Год";
$language['no_visit']="Нет посещений.";
$language['page']="Страницы";
//modified in 1.5.0
$language['admin']="Инструментальные средства";
$language['nbr_tot_visits']="Всего посещений";
$language['nbr_tot_pages']="Количество просмотренных страниц";
$language['nbr_tot_crawlers']="Количество роботов";
$language['visit_per-crawler']="Подробности посещений";
$language['100_visit_per-crawler']="Подробности посещений (показ ограничен %d линиями).";
$language['user_agent']="User agent";
$language['Origin']="Пользователи";
$language['help']="Помощь";
//search
$language['search']="Поиск";
$language['search2']="Поиск";
$language['search_crawler']="роботов";
$language['search_user_agent']=" user-agent";
$language['search_page']="страниц";
$language['search_user']="роботов пользователей";
$language['go_search']="Поиск";
$language['result_crawler']="Здесь роботы которые вы ищете.";
$language['result_ua']="Здесь user-agents которые вы ищете.";
$language['result_page']="Здесь страницы, которые вы ищете.";
$language['result_user']="Здесь робота пользователей, которые вы ищете.";
$language['result_user_crawler']="Вот роботы этого пользователя.";
$language['result_user_1']="Пользователь:&nbsp;";
$language['result_crawler_1']="Поиск по ключевым словам:&nbsp;";
$language['no_answer']="Ответа не существует.";
$language['to_many_answer']="Существует более 100 ответов (отображать только 100 линий).";
//admin
$language['user_create']="Создать новую учетную запись пользователя.";
$language['user_site_create']="Создать новый аккаунт пользователя веб сайта.";
$language['new_site']="Добавить веб сайт.";
$language['see_tag']="Показать включённые метки.";
$language['new_crawler']="Добавить новые роботы";
$language['crawler_creation']="Пожалуйста, заполните эту форму с новыми данными роботов."; 
$language['crawler_name2']="Имя робота:";
$language['crawler_user_agent']="User agent:";
$language['crawler_user']="Робот пользователя:";
$language['crawler_url']="URL-адрес пользователя (например: http://www.example.com)";
$language['crawler_url2']="URL-адрес пользователя:";
$language['crawler_ip']="IP:";
$language['crawler_no_ok']="Данные отсутствуют, пожалуйста,после коррекции подтверждите содержимое формы.";
$language['exist']="Этот робот уже есть в базе данных";
$language['exist_data']="Здесь представлены данные, касающиеся его в базе данных:";
$language['crawler_no_ok2']="Возникают проблемы во время создания робота, попробуйте еще раз.";
$language['crawler_ok']="Робот был добавлен в базу данных.";
$language['user_suppress']="Удалить пользователя или аккаунты пользователей сайта.";
$language['user_list']="Список пользователей и аккаунты пользователей вебсайтов";
$language['suppress_user']="Удалить этот аккаунт";
$language['user_suppress_validation']="Вы уверены, что вы хотите удалить этот аккаунт?";
$language['yes']="Да";
$language['no']="Нет";
$language['user_suppress_ok']="Аккаунт был успешно удален.";
$language['user_suppress_no_ok']="Проблема при попытке удаления аккаунта, попробуйте еще раз.";
$language['site_suppress']="Удалить свой сайт.";
$language['site_list']="Список сайтов";
$language['suppress_site']="Удалить этот сайт";
$language['site_suppress_validation']="Вы уверены что хотите удалить этот веб сайт?";
$language['site_suppress_ok']="Веб сайт успешно удалён.";
$language['site_suppress_no_ok']="Проблема при попытке удаления веб сайта, попробуйте еще раз.";
$language['crawler_suppress']="Удалить этот робот.";
$language['crawler_list']="Список роботов";
$language['suppress_crawler']="Робот удалён успешно";
$language['crawler_suppress_validation']="Вы уверены что хотите удалить этот робот?";
$language['crawler_suppress_ok']="Робот успешно удалён.";
$language['crawler_suppress_no_ok']="Проблема при попытке удаления робота, попробуйте еще раз.";
$language['crawler_test_creation']="Создать тестовый робот.";
$language['crawler_test_suppress']="Удалить тестовый робот.";
$language['crawler_test_text']="После создания тестового робота, зайдите на сайт с компьютера и браузера используемого для создания робота."; 
$language['crawler_test_text2']="Если все ОК, ваш визит будет отображаться в CrawlTrack качестве тестового визита для робота Crawltrack. Не забудьте, удалить тестовый робот после проверки.";
$language['crawler_test_no_exist']="Тестовый робот не существует в базе данных.";
$language['exist_site']="Этот сайт уже есть в базе данных";
$language['exist_login']="Этот логин уже есть в базе данных";
//1.2.0
$language['update_title']="Обновить список роботов.";
$language['update_crawler']="Обновить список роботов.";
$language['list_up_to_date']="Нет действительно доступного скорректированного списка.";
$language['update_ok']="Обновленно успешно.";
$language['crawler_add']="роботы добавлены в базу данных";
$language['no_access']="Интернет обновление недоступно.<br><br>Для обновления, нажмите на ссылку ниже, чтобы скачать последние роботы списке, загрузите crawlerlist.php файл в вашем CrawlTrack включить папку и перезагрузить процедуру обновления.";
$language['no_access2']="Связь с www.CrawlTrack.net неудачна, попробуйте еще раз позже.";
$language['download_update']="Если вы уже загрузили на ваш сайт новый список роботов, нажмите на кнопку ниже, чтобы обновить базу данных.";
$language['download']="Загрузить список роботов.";
$language['your_list']="В списке вы используете:";
$language['crawltrack_list']="Доступный список на www.Crawltrack.net:";
$language['no_update']="Не обновлять список роботов.";
$language['no_crawler_list']="Файл crawlerlist.php не существует в вашей папке установки.";
//1.3.0
$language['use_user_agent']="Робот определяется либо по user agent или по IP. Вы должны выбрать один из этих двух вариантов.";
$language['user_agent_or_ip']="User agent или IP";
$language['crawler_ip']="IP:";
$language['table_mod_ok']="Crawlt_crawler таблица обновлена OK.";
$language['files_mod_ok']="Configconnect.php и crawltrack.php файлы обновлены OK.";
$language['update_crawltrack_ok']="CrawlTrack обновление завершено, вы теперь используете версию:";
$language['table_mod_no_ok']="Crawlt_crawler таблица обновлена неудачно.";
$language['files_mod_no_ok']="Проблемы возникли при обновлении configconnect.php и crawltrack.php файлов.";
$language['update_crawltrack_no_ok']="Проблемы возникли при обновлении CrawlTrack.";
$language['no_logo']="Нет логотипа.";
//modified in 1.5.0
$language['data_suppress_ok']="Данные успешно архивированы.";
$language['data_suppress_no_ok']="Проблемы возникли при архивировании данных, попробуйте ещё раз.";
$language['data_suppress_validation']="Вы хотите архивировать всё &nbsp;";
$language['data_suppress']="Архив сохранённых данных из таблицы посещений.";
$language['data_suppress2']="Архивировать всё";
$language['one_year_data']="данные более чем за один год";
$language['six_months_data']="данные более чем за шесть месяцев";
$language['one_month_data']="данные более чем за один месяц";
$language['oldest_data']="Ранние данные датируются &nbsp;";
$language['no_data']="Нет данных в таблице визитов.";
//1.4.0
$language['time_set_up']="Используемое время";
$language['server_time']="Время и дата на сервере =";
$language['local_time']="Локальное время и дата=";
$language['time_difference']="Разница в часах между сервером и местное время=";
$language['time_server']="Вы хотите использовать сервер времени, чтобы отображать данные по местному времени?";
$language['time_local']="Вы использовать местное время, чтобы использовать сервер времени для отображения данных?";
$language['decal_ok']="CrawlTrack, будет использовать местное время, вы можете вернуться к серверу времени в любое время";
$language['nodecal_ok']="CrawlTrack, будет использовать сервер времени, вы можете вернуться к местному времени в любое время";
$language['need_javascript']="Вы должны активировать JavaScript, чтобы использовать эту функцию.";
//1.5.0 
$language['origin']="Источник";
$language['crawler_ip_used']="использованное IP";
$language['crawler_country']="Страна происхождения";
$language['other']="Другие";
$language['pc-page-view']="Процент посещённых страниц";
$language['pc-page-noview']="Процент непосещённых страниц";
$language['print']="Печать";
$language['ip_suppress_ok']="Эти посещения были успешно удалены.";
$language['ip_suppress_no_ok']="Возникают проблемы во время удаления посещений, попробуйте еще раз.";
$language['no_ip']="Нет IP записей для этого периода.";
$language['ip_suppress_validation']="Это IP были использовано различными роботами, так что есть сомнения относительно происхождения этих посещений. Хотите удалить от посещения этим IP?";
$language['ip_suppress_validation2']="Вы уверены, что вы хотите удалить посещения от этого IP?";
$language['ip_suppress_validation3']="Если вы хотите запретить доступ на сайт с этого IP, добавьте следующие строки в. htaccess файл на вашем сайте корень:";
$language['ip_suppress']="Удалить IP";
$language['diff-day-before']="сначала сравнить день";
$language['daily-stats']="Ежедневная статистика";
$language['top-crawler']="Более активный робот:";
$language['stat-access']="Смотреть детали статистики";
$language['stat-crawltrack']="Эти данные собраны:";
$language['nbr-pages-top-crawler']="он посещает";
$language['of-site']="на сайте";
$language['mail']="Получать ежедневно отчёт на Email.";
$language['set_up_mail']="Если вы хотите получать ежедневно статистику по электронной почте, введите ниже свой адрес электронной почты.";
$language['email-address']="Email адрес:";
$language['address_no_ok']="Введённый адрес некорректный.";
$language['set_up_mail2']="Отправка ежедневной сводки по почте включена. Хотите отключить ее ?";
$language['update']="Изменения сделаны.";
$language['search_ip']="Следить за IP адресом";
$language['ip']="IP адрес";
$language['maxmind']="эту дорожку было сделано с использованием GeoLite создать базу данных по MaxMind доступна по следующему адресу:";
$language['ip_no_ok']="Введённый IP адрес некорректный.";
$language['public']="Дайте свободный доступ к статистике.";
$language['public-set-up2']="Доступ к вашей статистике, свободный, Вы хотели бы защитить его паролем?";
$language['public-set-up']="Доступ к вашей статистике защищён паролем, вы хотели бы сделать его свободным?";
$language['public2']="Только страница инструментальных средств останется защищенной";
$language['admin_protected']="Доступ к странице инструментальных средств защищен.";
$language['no_data_to_suppress']="Нет данных в архиве за указанный период.";
$language['data_suppress3']="В данном архиве уменьшить размер базы данных, но соответствующие данные являются не более доступна для отображения статистики. Лишь в сводной таблице эти данные останутся в наличии (стр. Роботы разделе архивов). Так что лучше архивировать данные, только если вам действительно нужно уменьшить размер базы данных, сведения из архивов данных, это непоправимо потерять.";
$language['archive']="Архивы";
$language['month2']="Месяц";
$language['top_visits']="Top 3 из всего количества визитов";
$language['top_pages']="Top 3 из всех просмотренных страниц";
$language['no-archive']="Нет архивных данных.";
$language['use-archive']="Так как часть данных архивирована, эти величины не полные.";
$language['url_update']="Обновление данных на сайте";
$language['set_up_url']="Заполните следующую таблицу с URL сайтов, как: www.example.com (без http:// в начале и / в конце)."; 
$language['site_url']="URL сайта:";
//1.6.0
$language['page_cache']="Последний подсчет: ";
//1.7.0
$language['step1_install_no_ok4']="Проблемы возникают во время заполнения таблицы IP, это может произойти из-за хостинга, как эта таб рассчитана более чем 78 000 строк. Вы можете повторить попытку или продолжить без этой таблицы. В этом случае у вас не будет отображения из индексаторов стран происхождения. О 'Устранение неполадок' на странице документации по www.crawltrack.net, вы найдете ручную процедуру , чтобы заполнить таблицу.";
$language['show_all']="Показать все строки";
$language['from']="от";
$language['to']="на";
$language['firstweekday-title']="Выбор первого дня недели";
$language['firstweekday-set-up2']="Первый день недели понедельник, Вы бы хотели изменить на воскресенье?";
$language['firstweekday-set-up']="Первый день недели воскресенье, Вы хотели бы изменить на понедельник?";
$language['01']="Январь";
$language['02']="Февраль";
$language['03']="Март";
$language['04']="Апрель";
$language['05']="Май";
$language['06']="Июнь";
$language['07']="Июль";
$language['08']="Август";
$language['09']="Сентябрь";
$language['10']="Октябрь";
$language['11']="Ноябрь";
$language['12']="Декабрь";
$language['day0']="Понедельник";
$language['day1']="Вторник";
$language['day2']="Среда";
$language['day3']="Четверг";
$language['day4']="Пятница";
$language['day5']="Суббота";
$language['day6']="Воскресенье";
//2.0.0
$language['ask']="Ask";
$language['google']="Google";
$language['msn']="Bing";  //change for 3.1.1
$language['yahoo']="Yahoo";
$language['delicious']="Del.icio.us";
$language['index']="Индексация";
$language['keyword']="Ключевые слова";
$language['entry-page']="Страницы входа";
$language['searchengine']="Поиск";
$language['social-bookmark']="Социальные закладки";
$language['tag']="Метки";
$language['nbr_tot_bookmark']="Закладки";
$language['nbr_tot_link']="Обратные ссылки";
$language['nbr_tot_pages_index']="Проиндексированные страницы";
$language['nbr_visits_crawler']="Количество посещений робота";
$language['nbr_tot_visit_seo']="Посетители отправить на сайт";
$language['100_lines']="Показать ограничивается %d линий.";
$language['8days']="Последние 8 дней";
$language['close']="Закрыть";
$language['date']="Дата";
$language['modif_site']="Изменить название или URL одного сайта.";
$language['site_url2']="URL сайта";
$language['modif_site2']="Изменить данные сайта.";
$language['no-info-day-before']="Нет информации за предыдущий день";
$language['data_human_suppress_ok']="Данные были успешно удалены.";
$language['data_human_suppress_no_ok']="Проблема при удалении, попробуйте еще раз.";
$language['data_human_suppress_validation']="Вы уверены, что хотите всё удалить &nbsp;";
$language['data_human_suppress']="Удалить устаревшие данные в таблице посещений пользователей (ключевых слов и страниц входа).";
$language['data_human_suppress2']="Скрыть всё";
$language['one_year_human_data']="данные более чем на один год";
$language['six_months_human_data']="данные более чем за шесть месяцев";
$language['one_month_human_data']="данные более чем на один месяц";
$language['data_human_suppress3']="В данный пресечения уменьшить размер базы данных, но соответствующие данные более не 
доступны для отображения статистики. Так что лучше удалять данные, только если вам действительно нужно уменьшить размер базы данных; возможна окончательная потеря данных.";
$language['no_data_human_to_suppress']="Нет данных в таблице о посещениях посетителей.";
$language['choose_language']="Выбрать язык.";
//2.1.0
$language['since_beginning']="Всё";
//2.2.0
$language['admin_database']="Просмотр размера базы данных";
$language['table_name']="Имя таблицы";
$language['nbr_of_data']="Количество информации";
$language['table_size']="Размер таблицы";
$language['database_size']="Размер базы данных";
$language['total']="Всего:";
$language['mailsubject']="CrawlTrack ежедневный журнал";
$language['yesterday']="Вчера";
$language['beginmonth']="С начала месяца";
$language['evolution']="Изменение по сравнению с";
$language['lastthreemonths']="3 последних месяца";
$language['set_up_mail3']="Вы используете следующий почтовый адрес:";
$language['set_up_mail4']="Добавить адрес";
$language['set_up_mail5']="Введите ниже новый Email адрес.";
$language['set_up_mail6']="Удалить один или несколько адресов электронной почты";
$language['set_up_mail7']="Удалить выбранный адрес";
$language['chmod_no_ok']="В crawltrack.php файле обновление не удалось, поставить Chmod 777 на вашу CrawlTrack папку и перезапустите обновление. Не забудьте в конце процесса обновления, вернуться к Chmod 711 по соображениям безопасности.";
$language['display_parameters']="Показать параметры";
$language['ordertype']="Порядок:";
$language['orderbydate']="на сегодняшний день";
$language['orderbypagesview']="количество просмотров страниц";
$language['orderbyvisites']="количество посещений";
$language['orderbyname']="в алфавитном порядке";
$language['numberrowdisplay']="Количество отображаемых строк:";
//2.2.1
$language['french']="Французский";
$language['english']="Английский";
$language['german']="Немецкий";
$language['spanish']="Испанский";
$language['russian']="Русский";
$language['turkish']="Турецкий";
$language['dutch']="Голландский";
//2.3.0
$language['hacking']="Атаки";
$language['hacking2']="Попытки взлома";
$language['hacking3']="Код для инъекций";
$language['hacking4']="SQL инъекция";
$language['no_hacking']="Нет попыток";
$language['attack_detail']="Детали атаки";
$language['attack']="Параметры кода используемого для попытки инъекций";
$language['attack_sql']="Параметры используются для попытки SQL-инъекций";
$language['bad_site']="Файл/скрипт попытка иньекции хакера";
$language['bad_sql']="SQL запросы попыток иньекции хакера";
$language['bad_url']="Url запросы";
$language['hacker']="Атаки";
$language['date_hacking']="Время";
$language['unknown']="Неизвестное";
$language['danger']="Вы можете быть в опасности, если вы используете один из этих сценариев";
$language['attack_number_display']="Детали атаки (отображается ограниченное %d атакующих).";
$language['update_attack']="Обновить список атак.";
$language['no_update_attack']="Не обновлять список атак.";
$language['update_title_attack']="Обновить список атак.";
$language['attack_type']="Тип атаки";
$language['parameter']="Параметры";
$language['script']="Скрипт";
$language['attack_add']="Атаки, которые были добавлены в базу данных";
$language['no_access_attack']="Интернет обновление недоступно. <br> <br> Для обновления, нажмите на ссылку ниже, чтобы скачать последние нападения список, загрузить файл attacklist.php в вашу папку CrawlTrack и перезапустить процедуру обновления.";
$language['download_update_attack']="Если вы уже загрузили на ваш сайт новый список нападений , нажмите на кнопку ниже, чтобы обновить базу данных.";
$language['download_attack']="Загрузка списка атак.";
$language['no_attack_list']="Файл attacklist.php не существуют в папке установки.";
$language['change_password']="Изменить пароль";
$language['old_password']="Старый пароль";
$language['new_password']="Новый пароль";
$language['valid_new_password']="Введите еще раз новый пароль.";
$language['goodsite_update']="Обновить список доверенных сайтов";
$language['goodsite_list']="Довереные сайты";
$language['goodsite_list2']="Ссылка на эти сайты в один URL, не учитывается как атака";
$language['goodsite_list3']="Актуальные список довереных сайтов";
$language['suppress_goodsite']="Удалить этот сайт из списка.";
$language['goodsite_suppress_validation']="Вы уверены, что вы хотите удалить этот сайт?";
$language['good_site']="Довереный сайт";
$language['goodsite_suppress_ok']="Сайт был успешно удалён.";
$language['goodsite_suppress_no_ok']="Проблемы при удалении сайта, попробуйте ещё раз.";
$language['list_empty']="Пока нет доверенных сайтов";
$language['add_goodsite']="Добавьте новый доверенный сайт в список";
$language['goodsite_no_ok']="Вы должны ввести URL сайта.";
$language['attack-blocked']="Все эти атаки были заблокированы по запросу CrawlTrack ";
$language['attack-no-blocked']="Будьте осторожны ваш CrawlTrack не настроен для блокирования атак (см. стр. настроек)";
$language['attack_parameters']="Параметры хакинг защиты";
$language['attack_action']="Действия при обнаружении атаки";
$language['attack_block']="Записать и заблокировать";
$language['attack_no_block']="Только записать";
$language['attack_block_alert']="Прежде чем блокировать атаку, что является лучшей безопасностью на сайте, смотрите документацию (на www.crawltrack.net) чтобы быть уверенным, что  не будет проблем с вашими нормальными посетителями.";
$language['crawltrack-backlink']="CrawlTrack является свободно распостраняемым, и если он вам нравится и вы хотите поделиться им, почему не поставить на обратной странице? <br> Если вы выбираете 
в nologo вариант, эта ссылка будет невидимым на вашей странице. Вы найдете ниже два варианта логотипа, один для PHP-страниц, а второй для HTML-страницы. Вы можете поставить ссылку, в любом месте на своей странице.";
$language['session_id_parameters']="id сессии обработки";
$language['remove_session_id']="Удалить id сессии из URL страниц";
$language['session_id_alert']="Чтобы удалять сеанс id на страницах, URL не будет иметь несколько входов в таблице страниц, если вы используете сценарий, который имел сессии ID в URL.";
$language['session_id_used']="Используется id сессии";
//3.0.0
$language['webmaster_dashboard']="Доска объявлений вебмастера";
$language['summary']="Итог из всех сайтов";
$language['charge']="Загрузка сервера";
$language['unidentified']="Неиндефицировано";
$language['display_period2']="Выбор периода";
$language['visitors']="Посетители";
$language['unique_visitors']="Уникальные посетители";
$language['visits']="Посещения";
$language['nbr_tot_visits2']="Всего посещений";
$language['nbr_tot_visits3']="Всего";
$language['referer']="Реферер";
$language['website']="Веб сайты & другие поисковые системы";
$language['website2']="Сайты";
$language['website3']="Веб сайты";
$language['country']="Страна";
$language['direct']="Прямые входы";
$language['average_pages']="Просмотров страниц за посещение";
$language['stats_visitors']="Статистические посещения";
$language['count_in_stats']="Не учитывать собственные визиты на следующих сайтах:";
$language['stats_visitors_other_domain']="Если один из этих объектов находится на другом сервере, вы должны скопировать файл crawltsetcookie.php (вы найдете его в папке Crawltrack PHP) в корневой каталог сайта, прежде чем нажать на OK, чтобы ваш выбор был принят.";
$language['main_crawlers']="Главные роботы";
$language['magnifier']="Выполнить поиск в базе данных CrawlTrack";
$language['refresh']="Очистить кэш и пересчитать данные";
$language['wrench']="Откройте Вашу административную страницу Crawltrack";
$language['printer']="Печать этой страницы";
$language['information']="Документация по www.crawltrack.net";
$language['help']="Об CrawlTrack";
$language['cross']="Выход";
$language['home']="Назад на главную страницу";
$language['badreferer']="Вы уверены, что хотите установить этот адрес в список ссылающихся спамеров? Добавив к этому списку, посещения с этих адресов больше не будут приниматься во внимание CrawlTrack.";
$language['spamreferer']="Установить эту область в списке спамеров";
$language['badreferer_update']="Обновить список сайтов для ссылок спамеров";
$language['add_badreferer']="Добавить ссылку сайта к списку спамеров";
$language['listbadreferer_empty']="Нет сайтов для ссылок спамеров";
$language['badreferer_list']="Ссылки сайтов спамеров";
$language['badreferer_list2']="Посетители с этих сайтов, не будут приниматься во внимание CrawlTrack.";
$language['badreferer_list3']="Текущий список сайтов для ссылок спамеров";
$language['badreferer_site']="Веб сайт ссылок спамеров";

$language['goodreferer']="Вы уверены, что хотите добавить этот адрес, в список объектов, которые имеют ссылки на ваш сайт? Добавленный к этому списку, ссылки с него будет принимать во внимание CrawlTrack без дополнительного контроля.";
$language['goodreferer2']="Установка данной области в перечень объектов, которые имеют ссылки на ваш сайт";

$language['goodreferer_update']="Обновить список сайтов, которые имеют ссылки на ваш сайт";
$language['add_goodreferer']="Добавить веб-ссылку на ваш сайт из списка";
$language['listgoodreferer_empty']="Существует сайт, который не имеет ссылку на сайт";
$language['goodreferer_list']="Сайты, которые имеют ссылки на ваш сайт";
$language['goodreferer_list2']="Посетители с этих сайтов будут учитываться CrawlTrack без дополнительного контроля.";
$language['goodreferer_list3']="Текущий список сайтов, которые имеют ссылки на ваш сайт";
$language['goodreferer_site']="Сайт ссылающийся на ваш сайт";

$language['download']="Загрузить";
$language['file']="Файл";
$language['download_period']="За время";
$language['download_link']="Счётчик загрузок";
$language['download_link2']="<b>Ваши загрузки учитываются CrawlTrack:</b><br><br>
-Загружаемый файл должен быть размещен на одном из сайтов, которые обслуживает CrawlTrack.<br>
-загрузить ссылку (для файлов расположенных как http://www.example.com/folder/file.zip) должно быть в виде:";
$language['download_link3']="http://www.example.com/folder/file.zip";
$language['download_link4']="Это все, никаких дополнительных действий не требуется.";
$language['error']="Ошибка 404";
$language['number']="Количество";
$language['outer-referer']="Внешние ссылки";
$language['inner-referer']="Внутренние ссылки";
$language['error-attack']="В том числе попыток взлома";
$language['total_hacking']="Количество нападений";
$language['error_hacking']="Атаки которые выдали 404";
$language['error_page']="Запрашиваемый URL-адрес";
$language['crawler_error']="Детали 404 ошибок для роботов";
$language['direct_error']="Детали 404 ошибок, из-за прямых посещений";
$language['extern_error']="Детали 404 ошибок, связанных с внешней ссылкой на сайт";
$language['intern_error']="Детали 404 ошибок, из-за внутренней ссылки на сайт";
$language['error_referer']="Исходный URL";
$language['404_no_in_graph']="Эти нападения не принимать во внимание в количестве IP, графике и в подробной таблице.";
$language['404_no_in_graph2']="Результаты нападения в 404 не отображать на графике.";
$language['exalead']="Exalead";
$language['connect']="Вы идентифицированы";
$language['connect_you']="Логин";
$language['notcheck']="Непроверенная ссылка, нажмите на 'Проверить ссылки' для начала проверки.";
$language['checklink']="Проверить ссылки";
$language['linkok']="Ссылка проверена";
$language['first_date_visits']="Первое посещение";
$language['next_visits']="Следующее посещение";
$language['data_suppress']="Уменьшить размер базы данных."; //modified in 3.0.0
$language['data_suppress2']="Удалить";
$language['other_bot']="все посещения за исключением следующих роботов; Ask Jeeves / Teoma, Exabot, Googlebot, MSN Bot and Slurp Inktomi (Yahoo)";
$language['one_year_data']="все старые посещения роботов в течении года";
$language['six_months_data']="все старые посещения роботов в течении 6 месяцев";
$language['five_months_data']="все старые посещения роботов в течении 5 месяцев";
$language['four_months_data']="все старые посещения роботов в течении 4 месяцев";
$language['three_months_data']="все старые посещения роботов в течении 3 месяцев";
$language['two_months_data']="все старые посещения роботов более чем за 2 месяца";
$language['one_month_data']="все старые посещения роботов более чем за 1 месяц";
$language['one_year_data_human']="все старые посещения более чем за год";
$language['six_months_data_human']="все старые посещения в течении 6 месяцев";
$language['five_months_data_human']="все старые посещения в течении 5 месяцев";
$language['four_months_data_human']="все старые посещения в течении 4 месяцев";
$language['three_months_data_human']="все старые посещения в течении 3 месяцев";
$language['two_months_data_human']="все старые посещения в течении 2 месяцев";
$language['one_month_data_human']="все старые посещения в течении 1 месяца";
$language['attack_data']="Все данные, касающиеся попытки пиратства";
$language['oldest_data']="Самая старая из &nbsp;";
$language['no_data']="Не существует данных в таблице о хитах.";
$language['no_data_to_suppress']="Нет данных, которые будут удалены в указанный период.";
$language['data_suppress3']="Предупреждение! Удаление данных уменьшает размер базы данных, но в отношении части данных, будут безвозвратно потеряны.";
$language['data_suppress_ok']="Данные удалены успешно.";
$language['data_suppress_no_ok']="Проблема при удалении данных, выполните ещё раз.";
$language['data_suppress_validation']="Вы уверены, что хотите всё удалить &nbsp;";
$language['deltatime']="Частота посещений";
$language['nbr_tot_visit_seo']="Присхождение посещений";
$language['url_parameters']="Параметры в URL";
$language['remove_parameter']="Удалить URL параметры";
$language['remove_parameter_alert']="Удаление URL параметров позволит предотвратить чрезмерный рост таблиц, по изменению с любого рода URL: : www.example.com/index.php?article=225 будут записаны в виде www.example.com / index.php говоря при этом менее подробно о посещенных страницах.";
$language['bookmark']="Использовать этот адрес для Вашей страницы Избранное";
$language['evolution']="Направление количества уникальных посетителей";
$language['perday']="За день";
$language['shortterm']="Последние 7 дней:";
$language['longterm']="30 дней:";
$language['bounce_rate']="Показатель отказов";
$language['visit_summary']="Всего посещений на всех сайтах";
$language['data']="Данные";
$language['index']="Индекс";
$language['sponsorship']="Они поддерживают CrawlTrack:";

//3.1.0
$language['browser']="Браузеры";
$language['visitor-browser']="Браузеры используемые посетителями";
$language['hits-per-hour']="Хиты за час";
//3.1.2
$language['besponsor']="Использование CrawlTrack знать ваши продукты и услуги тысячам вебмастеров.";
$language['ad-on-crawltrack']="<a href=\"http://translate.google.fr/translate?hl=fr&sl=fr&tl=ru&u=http%3A%2F%2Fwww.ad42.com%2Fzone.aspx%3Fidz%3D6690%26ida%3D-1\" target=\"_blank\">Почему бы не использовать CrawlTrack представить свою продукцию и услуги на тысячах веб-мастерам?</a>";
//3.2.0
$language['baidu']="Baidu";
$language['googleposition']="Position<br>in Google";
$language['position']="Actual position";
$language['positiononemonth']="Position one month ago";
$language['positiontwomonth']="Position two months ago";
$language['positionthreemonth']="Position three months ago";
$language['googledetail']="Details of position in Google and hits generated";
//3.2.3
$language['bulgarian']="болгарский";
//3.2.8
$language['italian']="Итальянский";
$language['two_year_data']="все старые  посещений  робот  в течение двух лет";
$language['two_year_data_human']="всех старых посетителей из более чем двух лет";
//3.3.1
$language['googleimage']="Google-Images";
//3.3.2
$language['yandex']="Яндекс";
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
