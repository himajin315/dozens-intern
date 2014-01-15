<!DOCTYPE html>
  <head>
    <title>Whois GET</title>
  </head>
  <body>
  <h1>Whois</h1>
  <form action="whois.php" method="get">
     ホスト名： <input type="text" name="host">
     <input type="submit" value="送信">
  </form>
  <?php
     if($_GET['host']){
       $host_name = $_GET['host'];
       echo '入力したホスト名は '.$host_name.' です。';
    }
  ?>
  </body>
</html>

