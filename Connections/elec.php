<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_elecx = "localhost";
$database_elecx = "elecciones";
$username_elecx = "root";
$password_elecx = "";
$elecx = mysql_pconnect($hostname_elecx, $username_elecx, $password_elecx) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_query("SET NAMES 'utf8'"); 
?>