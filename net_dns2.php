<!DOCTYPE html>
  <head>
<title>DNS GET RECORD (Net_DNS2)</title>
    <meta charset="UTF-8">
    <link href="./style.css" rel="stylesheet" type="text/css">
  </head>
  <body>
   <div class="contents">
     <h1>DNS GET RECORD (Net_DNS2)</h1>
     <form action="net_dns2.php" method="get">
     ホスト名： <input type="text" name="host" value="<?php if(isset($_GET['host'])) echo $_GET['host']; ?>">
     <input type="submit" value="送信">
   </form>
  <?php
  if(isset($_GET['host'])){
     $host = $_GET['host'];
     echo "<h2>ホスト名：".$host."</h2>";
  }
   if(isset($host)){
    $ns = array('8.8.8.8', '8.8.4.4');
    require_once 'Net/DNS2.php';
    $rs = new Net_DNS2_Resolver(array('nameservers' => $ns));
  } else {
    die("No host specified.");
  }

  try {
    $result_a = $rs->query($host, 'A');
    $result_aaaa = $rs->query($host, 'AAAA');
    $result_any = $rs->query($host, 'ANY');
    $result_mx = $rs->query($host, 'MX');
    $result_ns = $rs->query($host, 'NS');
    $result_soa = $rs->query($host, 'SOA');
    $result_txt = $rs->query($host, 'TXT');
  } catch(InvalidArgumentException $e) {
    echo "Failed to query: " . $e->getMessage() . "\n";
  }

  // print output
  echo "<h2>A Ansesr</h2>";
  print("<pre>");
  print_r($result_a->answer);
  print("</pre>");

  echo "<h2>AAAA Ansesr</h2>";
  print("<pre>");
  print_r($result_aaaa->answer);
  print("</pre>");

  echo "<h2>ANY Ansesr</h2>";
  print("<pre>");
  print_r($result_any->answer);
  print("</pre>");

  echo "<h2>MX Ansesr</h2>";
  print("<pre>");
  print_r($result_mx->answer);
  print("</pre>");

  echo "<h2>NS Ansesr</h2>";
  print("<pre>");
  print_r($result_ns->answer);
  print("</pre>");

  echo "<h2>SOA Ansesr</h2>";
  print("<pre>");
  print_r($result_soa->answer);
  print("</pre>");


  if($result_any->answer){
    echo '<table>';
    //各レコードを表示させる
    foreach ($result_any->answer as $record_info){
      if($record_info->type == "SOA"){
	echo '<tr><td colspan=2 class="r-type">SOA records - Source Of Authority</td>';
	echo '<tr><td>'.$record_info->name.'. serial number </td><td>'.$record_info->serial.'</td></tr>';
	echo '<tr><td>'.$record_info->name.'. MNAME </td><td>'.$record_info->mname.'</td></tr>';
	echo '<tr><td>'.$record_info->name.'. RNAME </td><td>'.$record_info->rname.'</td></tr>';
	echo '<tr><td>'.$record_info->name.'. REFRESH </td><td>'.$record_info->refresh.'</td></tr>';
	echo '<tr><td>'.$record_info->name.'. RETRY </td><td>'.$record_info->retry.'</td></tr>';
	echo '<tr><td>'.$record_info->name.'. TTL </td><td>'.$record_info->ttl.'</td></tr>';
      }

      if($record_info->type == "TXT"){
	echo '<tr><td colspan=2 class="r-type">TXT records</td>';
	echo '<tr><td>'.$record_info->name.'. TXT </td><td>';
	if($record_info->text){
	  foreach ($record_info->text as $record_txt_info){
	    echo $record_txt_info.'</td></tr>';
	  }
	}
      }

      if($record_info->type == "A"){
	echo '<tr><td colspan=2 class="r-type">A records</td>';
	echo '<tr><td>'.$record_info->name.'. A </td><td>'.$record_info->address.'</td></tr>';
      }
      
      if($record_info->type == "AAAA"){
	echo '<tr><td colspan=2 class="r-type">AAAA records</td>';
	echo '<tr><td>'.$record_info->name.'. AAAA </td><td>'.$record_info->address.'</td></tr>';
      }
      
    }
    
    if($result_mx->answer){
      echo '<tr><td colspan=2 class="r-type">MX records - Mailservers</td></tr>';
      foreach ($result_mx->answer as $record_mx_info){
	echo '<tr><td>'.$record_mx_info->exchange.'. MX </td><td>'.'IP address'.'</td></tr>';
      }
    }
    
    //NSレコードがあるならば表示させる
    if($result_ns->answer){
      echo '<tr><td colspan=2 class="r-type">NS records - Nameservers</td>';
      foreach ($result_ns->answer as $record_ns_info){
	echo '<tr><td>'.$record_ns_info->nsdname.'</td><td>'.'IP address'.'</td></tr>';
      }
    }
    echo "</table>";
    }
    
    else{
    echo "見つかりませんでした。";
  }

  ?>
      <h3><a href="index.html">戻る</a></h2>
    </div>
  </body>
</html>

