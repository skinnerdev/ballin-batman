<?php
$connect_error='Sorry, the Factionizer is not working right now.';
mysql_connect('factionizer.com', 'faction', 'qwer1234') or die($connect_error);
mysql_select_db('faction_factionizer') die($connect_error);



?>