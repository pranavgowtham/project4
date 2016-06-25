<?php require_once('Connections/User_Registration.php'); ?>
<?php
// *** Logout the current user.
$logoutGoTo = "Login.php";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['MM_Username'] = NULL;
$_SESSION['MM_UserGroup'] = NULL;
unset($_SESSION['MM_Username']);
unset($_SESSION['MM_UserGroup']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_LogOut = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_LogOut = $_SESSION['MM_Username'];
}
mysql_select_db($database_User_Registration, $User_Registration);
$query_LogOut = sprintf("SELECT * FROM users WHERE Username = %s", GetSQLValueString($colname_LogOut, "text"));
$LogOut = mysql_query($query_LogOut, $User_Registration) or die(mysql_error());
$row_LogOut = mysql_fetch_assoc($LogOut);
$totalRows_LogOut = mysql_num_rows($LogOut);
?>
<!doctype html>
<html>
<head>
<link href="CSS/Layout.css" rel="stylesheet"  type="text/css" />
<link href="CSS/Menu.css" rel="stylesheet"  type="text/css" />
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<div id="Holder">
<div id="Header"></div>
<div id="Navbar">
 <nav>
 <ul>
 <li><a href="#">Login</a></li>
 <li><a href="#">Register</a></li>
 <li><a href="#">Forgot Password</a></li>
 </ul>
 </nav>
 </div>
<div id="Content">
<div id="PageHeading">
  <h1>You Have Logged Out !</h1>
</div>
<div id="ContentLeft"></div>
<div id="ContentRight"></div>
</div>
<div id="Footer"></div>
</div>
</body>
</html>
<?php
mysql_free_result($LogOut);
?>
