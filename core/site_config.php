<?php
require 'site_functions.php';
define('USER_TYPE_NORMAL', 0);
define('USER_TYPE_ADMIN', 1);
define('ALPHA', 'alpha');
define('BETA', 'beta');
define('RELEASE', 'release');
$site_status = get_site_status();
define('SITE_STATUS', $site_status);
define('CHARACTER_LIMIT', 12);
define('FACTION_LIMIT', 12);
$opinionWords = array(
	'Trust' => 'Trust',
	'Distrust' => 'Distrust',
	'Neutral' => 'Neutral',
	'Unknown' => 'Unknown',
	'Lust' => 'Lust',
	'Love' => 'Love',
	'Hate' => 'Hate',
	'S/O' => 'Significant Other',
	'Married' => 'Married',
	'Related' => 'Related',
	'Connection' => 'Connection',
	'Special' => 'Special'
);