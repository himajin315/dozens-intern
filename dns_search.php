<!DOCTYPE html>
  <head>
    <title>DNS Search</title>
    <link ref="stylesheet" href="style.css" type="text/css">
  </head>
  <body>
  <h1>DNS Search</h1>
  <form action="whois.php" method="get">
     ホスト名： <input type="text" name="host">
     <input type="submit" value="送信">
  </form>
  <?php
     if($_GET['host']){
       $host_name = $_GET['host'];
       //DNSレコードを取得する
       $result = dns_get_record($host_name);
       //結果を表示
       if($result){
         echo "<pre>";
         print_r($result);
         echo "</pre>";
         foreach ($result as $array){
           foreach ($array as $value){
             echo '現在の配列要素の値は[ '.$value.' ]です。<br/>';
           }
           echo '<hr>';
         }
       }else{
         echo '見つかりませんでした。';
       }
     }
  ?>
  </body>
</html>

