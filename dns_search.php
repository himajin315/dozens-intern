<!DOCTYPE html>
  <head>
    <title>DNS Search</title>
    <meta charset="UTF-8">
    <link ref="stylesheet" href="style.css" type="text/css">
    <style>
    table {
      margin:30px;
      border-collapse: collapse;
    }
    td {
      border: solid 1px;
      padding: 0.5em;
    }
   .r-type{
     text-align: center;
     background-color: #ddd;
    }
    </style>
  </head>
  <body>
  <h1>DNS Search</h1>
  <form action="dns_search.php" method="get">
     ホスト名： <input type="text" name="host">
     <input type="submit" value="送信">
  </form>
  <?php
     if($_GET['host']){
       $host_name = $_GET['host'];
       //DNSレコードを取得する
       $result = dns_get_record($host_name, DNS_ANY, $authns, $addtl);
       //結果を表示
       if($result){
         $ns_hosts = array();
         echo '<table>';
         foreach ($result as $array){
           if($array["type"]=="SOA"){
             echo '<tr><td colspan=2 class="r-type">SOA records - Source Of Authority</td>';
             echo '<tr><td>'.$array["host"].'. serial number </td><td>'.$array["serial"].'</td></tr>';
             echo '<tr><td>'.$array["host"].'. MNAME </td><td>'.$array["mname"].'</td></tr>';
             echo '<tr><td>'.$array["host"].'. RNAME </td><td>'.$array["rname"].'</td></tr>';
             echo '<tr><td>'.$array["host"].'. REFRESH </td><td>'.$array["refresh"].'</td></tr>';
             echo '<tr><td>'.$array["host"].'. RETRY </td><td>'.$array["retry"].'</td></tr>';
             echo '<tr><td>'.$array["host"].'. TTL </td><td>'.$array["ttl"].'</td></tr>';
           }
           if($array["type"]=="TXT"){
             echo '<tr><td colspan=2 class="r-type">TXT records</td>';
             echo '<tr><td>'.$array["host"].'. TXT </td><td>'.$array["txt"].'</td></tr>';
           }
           if($array["type"]=="A"){
             echo '<tr><td colspan=2 class="r-type">A records</td>';
             echo '<tr><td>'.$array["host"].'. A </td><td>'.$array["ip"].'</td></tr>';
           }
           if($array["type"]=="MX"){
             echo '<tr><td colspan=2 class="r-type">MX records - Mailservers</td></tr>';
             echo '<tr><td>'.$array["pri"].' '.$array["target"] .'</td><td></td></tr>';
           }
           if($array["type"]=="NS"){
             $ns_hosts[] = $array["target"];
           }
         }
         if($ns_hosts){
           echo '<tr><td colspan=2 class="r-type">NS records - Nameservers</td>';
           foreach ($ns_hosts as $ns_host){
             echo '<tr><td>'.$ns_host.'</td><td></td></tr>';
           }
         }
         echo "</table>";
       }else{
         echo "見つかりませんでした。";
       }
     }
  ?>
  </body>
</html>

