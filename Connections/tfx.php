<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_tfx = "localhost";
$database_tfx = "tfaltas";
$username_tfx = "root";
$password_tfx = "";
$tfx = mysql_pconnect($hostname_tfx, $username_tfx, $password_tfx) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_query("SET NAMES 'utf8'"); 
?>