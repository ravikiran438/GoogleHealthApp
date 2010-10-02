<html>
  <head>
    <title>MyGHAppv1.0</title>
    <style type="text/css">
	body {
	  margin-left: 15%;
	  margin-right:15%;
	  border: 1px dotted gray;
 	  padding: 10px 10px 10px 10px;
	  text-align: center;
	  font-family: sans-serif;
	}
    </style>
  </head>
<body>
  <h2>The user accounts database is still under construction.</h2>
  <p>Please use the link provided to link this application with your H9 Account.</p>
  
  <?php
  require_once 'Zend/Loader.php';
  Zend_Loader::loadClass('Zend_Gdata_AuthSub');
  Zend_Loader::loadClass('Zend_Gdata_Health'); 

  function generateAuthSubURL() {   
    $next = 'http://localhost/test1.php';   
    $scope = 'https://www.google.com/h9/feeds';   
    $authSubHandler = 'https://www.google.com/h9/authsub';  
    $secure = 0;  
    $session = 1;   
    $authSubURL = Zend_Gdata_AuthSub::getAuthSubTokenUri($next, $scope, $secure, $session, $authSubHandler);    
    // 1 - allows posting notices && allows reading profile data  
    $permission = 1;   
    $authSubURL .= '&permission=' . $permission;      
    return '<a href="' . $authSubURL . '">Click here to link your H9 Account</a>';
  }  
  echo generateAuthSubURL();
  ?>
</body>
</html>