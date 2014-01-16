<!DOCTYPE html>
  <head>
    <title>DNS Search</title>
    <meta charset="UTF-8">
    <link ref="stylesheet" href="style.css" type="text/css">
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
       $result = dns_get_record($host_name);
       //結果を表示
       if($result){
         echo "<pre>";
         print_r($result);
         echo "</pre>";
         foreach ($result as $array){
           foreach ($array as $key => $value){
             echo 'key[ '.$key.' ], value[ '.$value.' ]です。<br/>';
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

