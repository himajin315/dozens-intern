<!DOCTYPE html>
  <head>
<title>sample (Net_DNS2)</title>
    <meta charset="UTF-8">
    <link href="./style.css" rel="stylesheet" type="text/css">
  </head>
  <body>
   <div class="contents">
   <h1>sample (Net_DNS2)</h1>
   <form action="sample_net_dns2.php" method="get">
     ホスト名： <input type="text" name="host" value="<?php if(isset($_GET['host'])) echo $_GET['host']; ?>">
   <select name="record">
     <option>A</option>
     <option>AAAA</option>
<!--     <option>AFSDB</option>-->
     <option>ANY</option>
<!--     <option>APL</option>-->
<!--     <option>ATMA</option>-->
     <option>CAA</option>
<!--     <option>CDS</option>-->
<!--     <option>CERT</option>-->
     <option>CNAME</option>
<!--     <option>DHCID</option>-->
     <option>DLV</option>
<!--     <option>DNAME</option>-->
<!--     <option>DNSKEY</option>-->
<!--     <option>DS</option>-->
<!--     <option>EID</option>-->
<!--     <option>EUI48</option>-->
<!--     <option>EUI64</option>-->
<!--     <option>HINFO</option>-->
<!--     <option>HIP</option>-->
<!--     <option>IPSECKEY</option>-->
<!--     <option>ISDN</option>-->
<!--     <option>KEY</option>-->
<!--     <option>L32</option>-->
<!--     <option>L64</option>-->
<!--     <option>LOC</option>-->
<!--     <option>LP</option>-->
     <option>MX</option>
<!--     <option>NAPTR</option>-->
<!--     <option>NID</option>-->
<!--     <option>NIMLOCK</option>-->
     <option>NS</option>-->
<!--     <option>NSAP</option>-->
<!--     <option>NSEC</option>-->
<!--     <option>NSEC3</option>-->
<!--     <option>PX</option>-->
<!--     <option>RP</option>-->
<!--     <option>RRSIG</option>-->
<!--     <option>RT</option>-->
<!--     <option>SIG</option>-->
     <option>SPF</option>-->
<!--     <option>SRV</option>-->
<!--     <option>SSHFP</option>-->
<!--     <option>TA</option>-->
<!--     <option>TALINK</option>-->
<!--     <option>TKEY</option>-->
     <option>TXT</option>-->
<!--     <option>URI</option>-->
<!--     <option>WKS</option>-->
<!--     <option>X25</option>-->
   </select>
     <input type="submit" value="送信">
   </form>
  <?php
  if(isset($_GET['host'])&&isset($_GET['record'])){
     $host = $_GET['host'];
     $type = $_GET['record'];
     echo "<h2>ホスト名：".$host."　Record：".$type."</h2>";
  }
   if(isset($host)&&isset($type)){
    $ns = array('8.8.8.8', '8.8.4.4');
    require_once 'Net/DNS2.php';
    $rs = new Net_DNS2_Resolver(array('nameservers' => $ns));
  } else {
    die("No host specified.");
  }

  try {
    $result = $rs->query($host, $type);
  } catch(InvalidArgumentException $e) {
    echo "Failed to query: " . $e->getMessage() . "\n";
  }

  // print output
  echo "<h2>Ansesr</h2>";
  print("<pre>");
  print_r($result->answer);
  print("</pre>");
  echo "<h2>ALL</h2>";
  print("<pre>");
  print_r($result);
  print("</pre>");

  ?>
      <h3><a href="index.html">戻る</a></h2>
    </div>
  </body>
</html>

