<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_User_Registration = "localhost";
$database_User_Registration = "root_wordpress-trunk";
$username_User_Registration = "root";
$password_User_Registration = "";
$User_Registration = mysql_pconnect($hostname_User_Registration, $username_User_Registration, $password_User_Registration) or trigger_error(mysql_error(),E_USER_ERROR); 
?>