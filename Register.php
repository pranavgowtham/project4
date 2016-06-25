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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="Register.php";
  $loginUsername = $_POST['UserName'];
  $LoginRS__query = sprintf("SELECT Username FROM users WHERE Username=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_User_Registration, $User_Registration);
  $LoginRS=mysql_query($LoginRS__query, $User_Registration) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "RegisterForm")) {
  $insertSQL = sprintf("INSERT INTO users (Username, Password, `Confirm Password`, Fname, Lname, Email) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['UserName'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['FName'], "text"),
                       GetSQLValueString($_POST['LName'], "text"),
                       GetSQLValueString($_POST['email'], "text"));

  mysql_select_db($database_User_Registration, $User_Registration);
  $Result1 = mysql_query($insertSQL, $User_Registration) or die(mysql_error());

  $insertGoTo = "Login.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_User_Registration, $User_Registration);
$query_Register = "SELECT * FROM users";
$Register = mysql_query($query_Register, $User_Registration) or die(mysql_error());
$row_Register = mysql_fetch_assoc($Register);
$totalRows_Register = mysql_num_rows($Register);
?>
<!doctype html>
<html>
<head>
<link href="CSS/Layout.css" rel="stylesheet"  type="text/css" />
<link href="CSS/Menu.css" rel="stylesheet"  type="text/css" />
<meta charset="utf-8">
<title>Register</title>
</head>

<body>
<div id="Holder">
<div id="Header">
  <h1 class="StyleTxtField">REGISTRATIONS</h1>
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
  <h1 class="StyleTxtField">SIGN UP!</h1>
</div>
<div id="ContentLeft"></div>
<div id="ContentRight">
  <form action="<?php echo $editFormAction; ?>" id="RegisterForm" name="RegisterForm" method="POST">
    <table width="400" border="0" align="center">
      <tbody>
        <tr>
          <td><table border="0">
            <tbody>
              <tr>
                <td><label for="">First Name:</label>
                  <input name="FName" type="text" class="StyleTxtField" id="FName"></td>
                <td><label for="LName">Last Name:</label>
                  <input name="LName" type="text" class="StyleTxtField" id="LName"></td>
              </tr>
            </tbody>
          </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><label for="email">Email:<br>
          </label>
            <input name="email" type="email" class="StyleTxtField" id="email"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><p>
            <label for="UserName">UserName:</label>
          </p>
            <p>
              <input name="UserName" type="text" class="StyleTxtField" id="UserName">
            </p></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><table border="0">
            <tbody>
              <tr>
                <td><label for="Password">Password:</label>
                  <input name="Password" type="password" class="StyleTxtField" id="Password"></td>
                <td><label for="PasswordConfirm"> ConfirmPassword:</label>
                  <input name="PasswordConfirm" type="password" class="StyleTxtField" id="PasswordConfirm"></td>
              </tr>
            </tbody>
          </table>            <label for="Password">:</label></td>
        </tr>
        <tr>
          <td><input type="submit" name="RegisterButton" id="RegisterButton" value="Register"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </tbody>
    </table>
    <input type="hidden" name="MM_insert" value="RegisterForm">
  </form>
</div>
</div>
<div id="Footer"></div>
</div>
</body>
</html>
<?php
mysql_free_result($Register);
?>
