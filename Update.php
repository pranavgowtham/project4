<?php session_start(); ?>
<?php require_once('Connections/User_Registration.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "UpdateForm")) {
  $updateSQL = sprintf("UPDATE users SET Password=%s, Email=%s WHERE UserId=%s",
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['UserIdhiddenField'], "int"));

  mysql_select_db($database_User_Registration, $User_Registration);
  $Result1 = mysql_query($updateSQL, $User_Registration) or die(mysql_error());

  $updateGoTo = "Account.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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
?>
<!doctype html>
<html>
<head>
<link href="CSS/Layout.css" rel="stylesheet"  type="text/css" />
<link href="CSS/Menu.css" rel="stylesheet"  type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css">
<meta charset="utf-8">
<title>Untitled Document</title>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
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
  <h1>Update Account!</h1>
</div>
<div id="ContentLeft">
  <h2>Account links</h2>
</div>
<div id="ContentRight">
  <form action="<?php echo $editFormAction; ?>" method="POST" name="UpdateForm" id="UpdateForm">
    <table width="600" border="0" align="center">
      <tr>
        <td>Account:<?php echo $row_User['Fname']; ?> <?php echo $row_User['Lname']; ?> Username:<?php echo $row_User['Username']; ?></td>
      </tr>
    </table>
    <table width="400" border="0" align="center">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><span id="sprytextfield1">
          <label for="Email"></label>
          Email:<br>
          <input name="Email" type="text" class="StyleTxtField" id="Email" value="<?php echo $row_User['Email']; ?>">
          <span class="textfieldRequiredMsg">A value is required.</span></span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><p>Password:</p>
          <span id="sprypassword1">
          <label for="Password"></label>
          <input name="Password" type="password" class="StyleTxtField" id="Password" value="<?php echo $row_User['Password']; ?>">
          <span class="passwordRequiredMsg">A value is required.</span></span></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="submit" name="UpdateButton" id="UpdateButton" value="Update">
          <input name="UserIdhiddenField" type="hidden" id="UserIdhiddenField" value="<?php echo $row_User['UserId']; ?>"></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="UpdateForm">
  </form>
</div>
</div>
<div id="Footer"></div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
</script>
</body>
</html>
<?php
mysql_free_result($User);
?>
