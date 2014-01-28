<!DOCTYPE html>
  <head>
    <title>DNS Search (sample)</title>
    <meta charset="UTF-8">
    <link href="./style.css" rel="stylesheet" type="text/css">
  </head>
  <body>
   <div class="contents">
   <h1>DNS Search (sample)</h1>
   <form action="sample_net_dns2.php" method="get">
     ホスト名： <input type="text" name="host">
     <input type="submit" value="送信">
   </form>
  <?php
  if(isset($_GET['host'])){
     $host = $_GET['host'];
  }
  if(isset($host)){
    $ns = array('8.8.8.8', '8.8.4.4');
    $type = "A";
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

  echo "<table>\n";
  echo "<tr><th>Host</th>";
  echo "<th>Type</th>";
  echo "<th>Data</th>";
  echo "<th>TTL</th></tr>\n";

  foreach($result->answer as $record){
    echo "<tr><td>{$record->name}</td>";
    echo "<td>{$record->type}</td>";
    echo "<td>{$record->address}</td>";
   echo "<td>{$record->ttl}</td></tr>\n";
  }

  echo "</table>\n";

  ?>
      <h3><a href="index.html">戻る</a></h2>
    </div>
  </body>
</html>

