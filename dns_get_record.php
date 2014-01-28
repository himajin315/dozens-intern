<!DOCTYPE html>
  <head>
    <title>DNS GET RECORD</title>
    <meta charset="UTF-8">
    <link href="./style.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <div class="contents">
      <h1>DNS GET RECORD</h1>
      <form action="dns_get_record.php" method="get">
        ホスト名： <input type="text" name="host">
        <input type="submit" value="送信">
     </form>
    <?php
        if(isset($_GET['host'])){
          $host_name = $_GET['host'];
          //DNSレコードを取得する
          $result = dns_get_record($host_name, DNS_ANY, $authns, $addtl);
          //結果を表示
          if($result){
            $ns_hosts = array();
            $host_to_ip = array();

            //追加情報で渡されたホスト名とIPを連想配列に格納する
            foreach ($addtl as $add){
              $host_to_ip += array($add["host"] => $add["ip"]);
           }

            echo '<table>';
            foreach ($result as $record_info){
              //各レコードを表示させる
              if($record_info["type"]=="SOA"){
                echo '<tr><td colspan=2 class="r-type">SOA records - Source Of Authority</td>';
                echo '<tr><td>'.$record_info["host"].'. serial number </td><td>'.$record_info["serial"].'</td></tr>';
                echo '<tr><td>'.$record_info["host"].'. MNAME </td><td>'.$record_info["mname"].'</td></tr>';
                echo '<tr><td>'.$record_info["host"].'. RNAME </td><td>'.$record_info["rname"].'</td></tr>';
                echo '<tr><td>'.$record_info["host"].'. REFRESH </td><td>'.$record_info["refresh"].'</td></tr>';
                echo '<tr><td>'.$record_info["host"].'. RETRY </td><td>'.$record_info["retry"].'</td></tr>';
                echo '<tr><td>'.$record_info["host"].'. TTL </td><td>'.$record_info["ttl"].'</td></tr>';
              }
              if($record_info["type"]=="TXT"){
                echo '<tr><td colspan=2 class="r-type">TXT records</td>';
                echo '<tr><td>'.$record_info["host"].'. TXT </td><td>'.$record_info["txt"].'</td></tr>';
              }
              if($record_info["type"]=="A"){
                echo '<tr><td colspan=2 class="r-type">A records</td>';
                echo '<tr><td>'.$record_info["host"].'. A </td><td>'.$record_info["ip"].'</td></tr>';
              }
              if($record_info["type"]=="MX"){
                echo '<tr><td colspan=2 class="r-type">MX records - Mailservers</td></tr>';
                $mx_ip = '後で実装します';
                if(isset($host_to_ip[$record_info["target"]])) $mx_ip = $host_to_ip[$record_info["target"]];
                echo '<tr><td>'.$record_info["pri"].' '.$record_info["target"] .'</td><td>'.$mx_ip.'</td></tr>';
              }

              //NSレコードは複数あるので、配列に格納する
              if($record_info["type"]=="NS"){
                $ns_hosts[] = $record_info["target"];
              }
            }
            //NSレコードがあるならば表示させる
            if($ns_hosts){
              echo '<tr><td colspan=2 class="r-type">NS records - Nameservers</td>';
              foreach ($ns_hosts as $ns_host){
                echo '<tr><td>'.$ns_host.'</td><td>'.$host_to_ip[$ns_host].'</td></tr>';
              }
            }
            echo "</table>";
          }else{
            echo "見つかりませんでした。";
          }
        }
    ?>
      <h3><a href="index.html">戻る</a></h2>
    </div>
  </body>
</html>

