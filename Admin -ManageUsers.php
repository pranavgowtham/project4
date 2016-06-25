<?php @session_start(); ?>
<?php require_once('Connections/User_Registration.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "0";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "Login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
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

$currentPage = $_SERVER["PHP_SELF"];

if ((isset($_POST['DeleteUserhiddenField'])) && ($_POST['DeleteUserhiddenField'] != "")) {
  $deleteSQL = sprintf("DELETE FROM users WHERE UserId=%s",
                       GetSQLValueString($_POST['DeleteUserhiddenField'], "int"));

  mysql_select_db($database_User_Registration, $User_Registration);
  $Result1 = mysql_query($deleteSQL, $User_Registration) or die(mysql_error());

  $deleteGoTo = "Admin -ManageUsers.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$colname_User = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_User = $_SESSION['MM_Username'];
}
mysql_select_db($database_User_Registration, $User_Registration);
$query_User = sprintf("SELECT * FROM users WHERE Username = %s", GetSQLValueString($colname_User, "text"));
$User = mysql_query($query_User, $User_Registration) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);

$maxRows_ManageUsers = 10;
$pageNum_ManageUsers = 0;
if (isset($_GET['pageNum_ManageUsers'])) {
  $pageNum_ManageUsers = $_GET['pageNum_ManageUsers'];
}
$startRow_ManageUsers = $pageNum_ManageUsers * $maxRows_ManageUsers;

mysql_select_db($database_User_Registration, $User_Registration);
$query_ManageUsers = "SELECT * FROM users ORDER BY `TimeStamp` DESC";
$query_limit_ManageUsers = sprintf("%s LIMIT %d, %d", $query_ManageUsers, $startRow_ManageUsers, $maxRows_ManageUsers);
$ManageUsers = mysql_query($query_limit_ManageUsers, $User_Registration) or die(mysql_error());
$row_ManageUsers = mysql_fetch_assoc($ManageUsers);

if (isset($_GET['totalRows_ManageUsers'])) {
  $totalRows_ManageUsers = $_GET['totalRows_ManageUsers'];
} else {
  $all_ManageUsers = mysql_query($query_ManageUsers);
  $totalRows_ManageUsers = mysql_num_rows($all_ManageUsers);
}
$totalPages_ManageUsers = ceil($totalRows_ManageUsers/$maxRows_ManageUsers)-1;

$queryString_ManageUsers = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_ManageUsers") == false && 
        stristr($param, "totalRows_ManageUsers") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_ManageUsers = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_ManageUsers = sprintf("&totalRows_ManageUsers=%d%s", $totalRows_ManageUsers, $queryString_ManageUsers);
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
<div id="Header">
  <h1 class="StyleTxtField">Account </h1>
</div>
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
  <h1 class="StyleTxtField">Admin CP</h1>
</div>
<div id="ContentLeft">Account links</div>
<div id="ContentRight">
  <table width="670" border="0" align="center">
    <tr>
      <td align="right" valign="top">Showing&nbsp;<?php echo ($startRow_ManageUsers + 1) ?> to <?php echo min($startRow_ManageUsers + $maxRows_ManageUsers, $totalRows_ManageUsers) ?>of <?php echo $totalRows_ManageUsers ?></td>
    </tr>
    <tr>
      <td align="center" valign="top"><?php if ($totalRows_ManageUsers > 0) { // Show if recordset not empty ?>
        <?php do { ?>
            <table width="500" border="0" align="center">
              <tr>
                <td><?php echo $row_User['Fname']; ?> <?php echo $row_User['Lname']; ?> <?php echo $row_User['Email']; ?></td>
                </tr>
              <tr>
                <td><form action="" method="post" name="DeleteUserForm" id="DeleteUserForm">
                  <input name="DeleteUserhiddenField" type="hidden" id="DeleteUserhiddenField" value="<?php echo $row_User['UserId']; ?>">
                  <input type="submit" name="DeleteUser" id="DeleteUser" value="DeleteUser">
                   </form></td>
                </tr>
              <tr>
                <td>&nbsp;</td>
                </tr>
            </table>
            <?php } while ($row_ManageUsers = mysql_fetch_assoc($ManageUsers)); ?>
        <?php } // Show if recordset not empty ?></td>
    </tr>
    <tr>
      <td align="right" valign="top"><?php if ($pageNum_ManageUsers < $totalPages_ManageUsers) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_ManageUsers=%d%s", $currentPage, min($totalPages_ManageUsers, $pageNum_ManageUsers + 1), $queryString_ManageUsers); ?>">Next</a> |
          <?php } // Show if not last page ?>
        <?php if ($pageNum_ManageUsers > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_ManageUsers=%d%s", $currentPage, max(0, $pageNum_ManageUsers - 1), $queryString_ManageUsers); ?>">Previous</a>
          <?php } // Show if not first page ?>      </td>
    </tr>
  </table>
</div>
</div>
<div id="Footer"></div>
</div>
</body>
</html>
<?php
mysql_free_result($User);

mysql_free_result($ManageUsers);
?>
