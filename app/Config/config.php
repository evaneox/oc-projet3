<?php
/**
 * Fichier de configuration du blog
 */
/* ==================================
  URLs
=========================================*/
define('URL_PUBLIC_FOLDER',     'public');
define('URL_PROTOCOL',          '//');
define('URL_DOMAIN',            $_SERVER['HTTP_HOST']);
define('URL_SUB_FOLDER',        str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME']) ));
define('BASE_URL',              URL_PROTOCOL . URL_DOMAIN . substr(URL_SUB_FOLDER , 0, -1));

/* ==================================
  Pagination
=========================================*/
define('PAG_KEY',               'page');
define('PAG_ITEM_PER_PAGE',     30);
define('PAG_MAX_PAGE',          20);

/* ==================================
  BDD
=========================================*/
define('DB_TYPE',               'mysql');
define('DB_HOST',               'localhost');
define('DB_NAME',               'oc_projet_3');
define('DB_USER',               '');
define('DB_PASS',               '');
define('DB_CHARSET',            'utf8');


