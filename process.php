<?php
## process.php
## PART 2 OF PHP WAYBACK MACHINE DOWNLOADER - QUESTIONS: LilPeck@gmail.com
## inspired by https://gist.github.com/tuanle/7749c5af3cf2ce5e43df

$domain = "google.com"; //folders with the domain name will be created inside the same folder this script runs in
$domainurl = "http://google.com";
$webarchive = "http://web.archive.org/web/";
$fileext = array('.htm','.html','.png','.jpg','.jpeg','.gif','.pdf','.xml','.ico'); // IF YOU WANT TO LIMIT FILE EXTENSIONS
$myFolderArray =("");

//curl function retrieves each url when called
function get_data($url) {
        $ch = curl_init();
        $timeout = 500;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
}
//The Usage
//$returned_content = get_data('https://davidwalsh.name');

           if (!file_exists($domain)) {
           mkdir($domain, 0775, true);
           }
           
           //get your list from the json file
           $data = file_get_contents ("rawlist.json");
           $json = json_decode($data, true);
 
           $orgArray=array("");
           $myArray = ("");
           
           //how many subarrays in json?
           echo sizeof($json);
           echo "<hr/>";
           
           foreach ($json as $key => $value) {      
           if (!is_array($value)) {
             
           echo $key . '=>' . $value . '<br/>';
            } else {
           
           foreach ($value as $key => $val) {
               
           echo "Key: $key; Value: $val<br />\n";
 
           //PUT TIMESTAMP AS A KEY NAME AND ORIGINAL AS ITS VALUE INTO MYARRAY        
              if ($key== 0){      
              $keyname = $val;
              }
               if ($key== 1){
           $bname = basename($val); // $name == '1.jpg'
           $bExt = pathinfo($bname, PATHINFO_EXTENSION);
           if (in_array(".".$bExt, $fileext))
           {
           echo $key . '=>' . $val . '<br/>';
           $keyvalue = $val;
           } else {
           echo $key . '=>' . $val . 'index.html<br/>';
           $keyvalue = $val . 'index.html';
           }
           }
           $myArray[$keyname] = $keyvalue ;  
           $without80=str_replace(":80","",$myArray[$keyname]); //:80 in the archive.org file urls appear to be redirectors
           array_push($myArray, $without80);
          //END of PUT TIMESTAMP AS A KEY NAME AND ORIGINAL AS ITS VALUE INTO MYARRAY  
       
           }
           }
           }
           $result = array_unique($myArray);
           function myFilter($string) {
           return strpos($string, ':80') === false;
           }
           $result = array_filter($result, 'myFilter');
           
           //GET URLS THAT HAVE FILE NAMES ONLY
           //get urls with folders only to create local folders
           $fileArr = array_unique($result);
           
           echo "<hr/>";
           echo "<pre>";
           
           echo "NEW ARRAY WITH TIMESTAMP AS KEYS AND ORIGINAL AS VALUE". "<br />\n";
           echo "EXTRACT FOLDER NAMES AND PUT INTO FOLDER NAME ARRAY". "<br />\n";
           
           echo "<pre>";
           $xn = 0;
           foreach ($result as $fileKey => $fileValue) {
           echo $xn. '<br/>';  
           if ($xn > 1) {          
           echo $fileKey ." = ". $fileValue . '<br/>';
           
           $filename = basename($fileValue); // $name == '1.jpg'
           $myFolderVal=str_replace($filename,"",$fileValue);
           $myFolderVal=str_replace($domainurl,$domain,$myFolderVal);
           echo " FOLDER: ".$myFolderVal. '<br/>';
           
           $myFolderArray[] = $myFolderVal;//ADD ITEM TO ARRAY
           }
           echo '<hr/>';
           $xn = $xn +1;
           }
           echo "</pre>";
           echo "<hr/>";
           
           //CREATE LOCAL FOLDERS
           $myFolderArray = array_unique($myFolderArray);
           echo "FOLDER NAMES FOR LOCAL DIRECTORIES". '<br/>';
           //get urls with folders only to create local folders
           echo "<pre>";
           foreach ($myFolderArray as $fKey => $fValue) {
           echo $fKey . '=>' . $fValue . '<br/>';
           if (!file_exists($fValue)) {
           mkdir($fValue, 0775, true);
           }
           }
           echo "</pre>";
           echo "<hr/>";
           
           echo "FILE NAMES ARRAY FOR DOWNLOADING FILES.";
           echo "<pre>";
           $xa = 0;
           foreach ($result as $aKey => $aValue) {
            echo $xa. '<br/>';  
           if ($xa > 1) {        
           echo $aKey . '=>' . $aValue . '<br/>';
           $makeURLa=$aKey ."id_/";
           $makeURLb=$aValue;
           $filestring = $webarchive.$makeURLa.$makeURLb;
           echo "URL: ".$filestring. "<br />\n";
           $myFolderVal=str_replace($domainurl,$domain,$makeURLb);
           
           echo " SAVE TO FOLDER: ".$myFolderVal. '<br/>';
            $url  = trim($filestring);
           $path = trim($myFolderVal);
           $returned_content = get_data($url); //running curl function
           // the following lines write the contents to a file
           if (($fp = fopen($path, "w")) !== false) { //new line
           $fp = fopen($path, 'w');
           fwrite($fp, $returned_content);
           fclose($fp);
           }
           sleep(5); //to avoid excessive use of resources
           }
           echo  '<hr/>';
           $xa = $xa +1;}
           echo "</pre>";
?>
