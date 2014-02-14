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
    $result_any = $rs->query($host, 'ANY');
    $result_mx = $rs->query($host, 'MX');
    $result_ns = $rs->query($host, 'NS');
  } catch(Exception $e) {
    echo "見つかりませんでした。";
  }


  if(isset($result_any->answer)){
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
	$mx_host = strtolower($record_mx_info->exchange);
	try {
	  $result_a = $rs->query($mx_host, 'A');
	  echo '<tr><td>'.$record_mx_info->exchange.'</td><td>'.$result_a->answer[0]->address.'</td></tr>';
	} catch(Exception $e) {
	  echo '<tr style="color:red;"><td>'.$record_mx_info->exchange.'</td><td>NXDOMAIN</td></tr>';
	}
	try {
	  $result_aaaa = $rs->query($mx_host, 'AAAA');
	  echo '<tr><td>'.$record_mx_info->exchange.' AAAA </td><td>'.$result_aaaa->answer[0]->address.'</td></tr>';
	} catch(Exception $e) {
	}
      }
    }
    
    //NSレコードがあるならば表示させる
    if($result_ns->answer){
      echo '<tr><td colspan=2 class="r-type">NS records - Nameservers</td>';
      foreach ($result_ns->answer as $record_ns_info){
	$ns_host = strtolower($record_ns_info->nsdname);
	$result_ns = $rs->query($ns_host, 'A');
	echo '<tr><td>'.$record_ns_info->nsdname.'</td><td>'.$result_ns->answer[0]->address.'</td></tr>';
	try {
	  $result_aaaa = $rs->query($ns_host, 'AAAA');
	  echo '<tr><td>'.$record_ns_info->nsdname.' AAAA </td><td>'.$result_aaaa->answer[0]->address.'</td></tr>';
	} catch(Exception $e) {
	}
      }
    }
    echo "</table>";
  }
  ?>
      <h3><a href="index.html">戻る</a></h2>
    </div>
  </body>
</html>

