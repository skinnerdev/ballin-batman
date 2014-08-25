<?php
$connect_error='Sorry, the Factionizer is not working right now.';
mysql_connect('localhost', 'faction', 'qwer1234') or die($connect_error);
mysql_select_db('faction_factionizer') or die($connect_error);