<html>
  <head>
    <title>MyGHAppv1.0</title>
    <style type="text/css">
	body {
	  margin-left: 15%;
	  margin-right:15%;
	  border: 1px dotted gray;
 	  padding: 10px 10px 10px 10px;
	  
	  font-family: sans-serif;
	}
    </style>
  </head>
  <body>
     <?php
  


    require_once 'Zend/Loader.php'; 
    Zend_Loader::loadClass('Zend_Gdata_AuthSub'); 
    Zend_Loader::loadClass('Zend_Gdata_Health');
    Zend_Loader::loadClass('zend_Gdata_Health_Query'); 
    Zend_Loader::loadClass('Zend_Gdata_Health_Extension_Ccr'); 
 
    function xmlpp($xml, $html_output=false) {  
     $xml_obj = new SimpleXMLElement($xml);  
     $level = 4;  
     $indent = 0; // current indentation level  
     $pretty = array();  
       
     // get an array containing each XML element  
     $xml = explode("\n", preg_replace('/>\s*</', ">\n<", $xml_obj->asXML()));  
   
     // shift off opening XML tag if present  
     if (count($xml) && preg_match('/^<\?\s*xml/', $xml[0])) {  
       $pretty[] = array_shift($xml);  
     }  
   
     foreach ($xml as $el) {  
       if (preg_match('/^<([\w])+[^>\/]*>$/U', $el)) {  
           // opening tag, increase indent  
           $pretty[] = str_repeat(' ', $indent) . $el;  
           $indent += $level;  
       } else {  
         if (preg_match('/^<\/.+>$/', $el)) {              
           $indent -= $level;  // closing tag, decrease indent  
         }  
         if ($indent < 0) {  
           $indent += $level;  
         }  
         $pretty[] = str_repeat(' ', $indent) . $el;  
       }  
     }     
     $xml = implode("\n", $pretty);     
     return ($html_output) ? htmlentities($xml) : $xml;  
 }  

    $sessionToken = Zend_Gdata_AuthSub::getAuthSubSessionToken($_GET['token']); 
    $client = Zend_Gdata_AuthSub::getHttpClient($sessionToken);  
    $useH9Sandbox = true; 
    $healthService = new Zend_Gdata_Health($client, 'MyGHAppNamev1.0', $useH9Sandbox);
    

    $query = new Zend_Gdata_Health_Query(); 
    $query->setDigest("true"); 
    $profileFeed = $healthService->getHealthProfileFeed($query);  
    $entry = $profileFeed->entry[0]; 
    
    

    //To print ccr
    $ccr =$entry->getCcr();
    $xmlStr =$ccr->saveXML($ccr);
    echo '<p>' . xmlpp($xmlStr, true) . '</p>';
    

    
    // digest=true was set so we only have 1 entry
    $allergies = $entry->getCcr()->getAllergies();
    $conditions = $entry->getCcr()->getConditions(); 
    $immunizations = $entry->getCcr()->getImmunizations(); 
    $lab_results = $entry->getCcr()->getLabResults(); 
    $medications = $entry->getCcr()->getMedications(); 
    $procedures = $entry->getCcr()->getProcedures();  

    //An example of using the medications data 
    foreach ($medications as $med) {   
      $xmlStr = $med->ownerDocument->saveXML($med);   
      $filename = 'c:\Users\Ravi\Desktop\xmldoc1.xml'; 
      // write to file 
      file_put_contents($filename, $xmlStr) or die('Could not write to file');
      //echo '<pre>' . xmlpp($xmlStr,true) . '</pre>'; 
    }
    
    
    
    /*  
    // Or, you can use XPath to extract just the names of each medication 
    $meds = $entry->getCcr()->getMedications()->item(0);

    $xpath = new DOMXpath($meds->ownerDocument); 
    $elements = $xpath->query("//ccr:Medications/ccr:Medication/ccr:Product/ccr:ProductName/ccr:Text"); 
    foreach ($elements as $element) {   
      echo $element->nodeValue . '<br />'; 
    }*/

  ?>

 

  
  </body>
</html>