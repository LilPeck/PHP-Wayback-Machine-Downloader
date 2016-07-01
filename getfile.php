<?php
## getfile.php
## DOWNLOAD WEBSITES FROM ARCHIVE.ORG WAYBACK MACHINE WITH PHP
## inspired by https://gist.github.com/tuanle/7749c5af3cf2ce5e43df
## Questions: LilPeck@gmail.com
## CONSTRUCT YOUR JSON URL AND DOWNLOAD THE JSON OUTPUT TO A LOCAL FILE. TEST YOUR JSON URL IN BROWSER FIRST!

$domain = 'google.com'; //type the url you want without http:// and without https://

## GO TO https://web.archive.org/web/ AND FIND THE WEBSITE YOU WANT TO DOWNLOAD.
## SELECT A SPECIFIC DATE AND LOOK AT ITS URL: https://web.archive.org/web/19981111184551/http://google.com/
## COPY THE TIMESTAMP FROM THE DATE STRING:
$from = '19981111184551';

## FIND A LATER DATE FOR THE SAME PAGE AND COPY THE TIMESTAMP FROM ITS URL. (IT IS RECOMMENDED TO KEEP YOUR TIME SPAN FAIRLY NARROW TO AVOID OVERUSE OF RESOURCES.)
$to = '19990422191353';

## GOOD IDEA TO LIMIT THE RESULTS TO AVOID OVERUSE OF RESOURCES
$limit = '100';

## Set matchType to "prefix" if you have multiple subdomains, or "exact" if you want only one page
## If downloading something like an old ‘aol.members.com/memberid’ site, use "prefix" as matchType.
$matchType ='domain';

## The the url= value should be url encoded if the url itself contains a query.
$url='http://web.archive.org/cdx/search/cdx?url='.$domain.'&matchType='.$matchType.'&from='.$from.'&to='.$to.'&output=json&fl=timestamp,original&fastLatest=true&filter=statuscode:200&collapse=original&limit='.$limit;

$ci = curl_init();
$fp = fopen("rawlist.json", "w"); // Destination location same directory as this script - caution, it will overwrite another file of the same file name.
curl_setopt_array( $ci, array(
    CURLOPT_URL => $url,
    CURLOPT_TIMEOUT => 3600,
    CURLOPT_FILE => $fp
));
$contents = curl_exec($ci); // Returns '1' if successful
curl_close($ci);
fclose($fp);

## AFTER RUNNING THIS SCRIPT, OPEN THE RESULTING rawlist.json FILE IN A TEXT EDITOR TO MAKE SURE IT HAS THE URLS YOU CAN USE
## NEXT, EDIT THE process.php FILE.
?>
