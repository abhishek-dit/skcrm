<?php
$hostname="localhost";
$username="iCRM";
$password="kjs452sk";
$dbname="iCRM";
 
//Connect to the database
$connection = mysql_connect($hostname, $username, $password);
mysql_select_db($dbname, $connection);
$file = fopen('product_prices.csv', "r");
$j=0;
$found = array(); $not_found = array(); $price_changed = array();
while (($fData = fgetcsv($file, 10000, ",")) !== FALSE)
    { 

        #exclude the first record as contain heading
        $product_code = trim($fData[1]);
        $price = str_replace(',', '', trim($fData[3]));
        if($j==0) { $j++; continue; } 
        $qry1 = 'SELECT * FROM product where name = "'.$product_code.'"';
        $res1 = mysql_query($qry1);
        $num = mysql_num_rows($res1);
        if($num>0)
        {
        	$row = mysql_fetch_array($res1);
        	$found[$product_code] = $row;
        	if($row['mrp']!=$price)
        	{
        		$price_changed[$product_code] = array('mrp_old'=>$row['mrp'],'mrp_new'=>$price);
        	}
        	/*$qry2 = 'UPDATE product set mrp = '.$price.', modified_by = 1, modified_time = "'.date('Y-m-d H:i:s').'"
        	 WHERE product_id = '.$row['product_id'];
        	mysql_query($qry2);*/

        }
        else
        {
        	$not_found[$fData[0]] = $product_code;
        }
        //echo $ibData[1].'<br>';
        $j++;
        //if($j==10) break;
    } 
    echo 'Total Records : '.($j-1);
    echo '<br>Found '.count($found).' Records ';
    echo '<br> Updated '.count($price_changed).' Records';
    echo '<br> Details : ';
    echo '<pre>'; print_r($price_changed); echo '</pre>';
    echo '<br> Not Found Records : '.count($not_found);
    echo '<pre>'; print_r($not_found); echo '</pre>';
?>