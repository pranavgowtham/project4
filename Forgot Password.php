<!doctype html>
<html>
<head>
<link href="CSS/Layout.css" rel="stylesheet"  type="text/css" />
<link href="CSS/Menu.css" rel="stylesheet"  type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<meta charset="utf-8">
<title>Untitled Document</title>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
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
  <h1>Email Password</h1>
</div>
<div id="ContentLeft">
  <h2>EMPW Message</h2>
</div>
<div id="ContentRight">
  <form action="EMPW-Script.php" method="post" name="EMPWForm" id="EMPWForm">
    <span id="sprytextfield1">
    <label for="Email"></label>
    <input type="text" name="Email" id="Email">
    <br>
    <br>
    <span class="textfieldRequiredMsg">A value is required.</span></span>
    <input type="submit" name="EMPWButton" id="EMPWButton" value="Email Password">
  </form>
</div>
</div>
<div id="Footer"></div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
</script>
</body>
</html>