/*
/////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////+------------------------------------------------------------------------+///////////////
////////////////|This script offers a way to get and store information about http clients|///////////////
////////////////|to a MySQL databae.                                                     |///////////////
////////////////|                                                                        |///////////////
////////////////+------------------------------------------------------------------------+///////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////

*/
<?php

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {//get the http client ip
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}


$hostname = gethostbyaddr($ip);/// get hostname

printf( "the hostname is: $hostname");

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}else{echo "database connected";}

$line = geoip_record_by_name($hostname);

mysql_select_db('VisitorInfo');

$sql = "INSERT INTO tracker ".
       "(id,ip,hostname,continent_code,country_code,country_code3,country_name,region,".
       "city,postal_code,latitude,longitude,dma_code,area_code) ".
       "VALUES ".
       "('','$ip','$hostname','$line[continent_code]','$line[country_code]','$line[country_code3]',".
	   "'$line[country_name]','$line[region]','$line[city]','$line[postal_code]','$line[latitude]',".
	   "'$line[longitude]','$line[dma_code]','$line[area_code]')";

$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}
echo "Entered data successfully\n";
mysql_close($conn);

?>
