<?php
//[管理人版]掲示板の閲覧が実装されています。
  $err_msg_n = ""; $err_msg1 = ""; $err_msg2 = ""; $err_msg3 = "";
  $number = ( isset( $_POST["number"] ) === true ) ? $_POST["number"]:"";
  $name2 = ( isset( $_POST["name2"] ) === true ) ? $_POST["name2"]:"";

    if ( isset($_POST["login"]) === true )
    {
      if ( $number === "" ) $err_msg_n = "IDを入力してください!";
      if ( $name2 === "" ) $err_msg2 = "名前を入力してください!";
      if( $err_msg_n === "" && $err_msg2 === "" )
      {
        $user = '';
        $pass = '';
        try
          {$pdo = new PDO('mysql:dbname=;host=',$user,$pass);
          }
        catch (PDOException $e)
          {exit('データベースに接続できませんでした。'. $e->getMessage());
          }
        $sql = 'SELECT * FROM personal_registration';
        $result = $pdo->query($sql);
        foreach($result as $row)
        {
          if ( $row['ID'] == $number && $row['name'] == $name2 )
            {
             $err_msg3 = "こちらです。";
             break;
            }
          if ( $row['ID'] !== $number || $row['name'] !== $name2 )
            {
              $err_msg3 = " IDか名前が異なります。";
            }
        }
       if( $err_msg3 == "こちらです。" )
       {
         $sql_s = 'SELECT * FROM personal_posting';
         $result2 = $pdo->query($sql_s);
         $dataArr3 = array();
         foreach($result2 as $row2){
           if( $row2['name'] == $name2 ){
             $imgcomment = '';
              if( strlen($row2['image']) > 2 ) $imgcomment ='<a href="M3loadpicture.php?ID='.$row2['ID'].'">画像を確認</a>';
              $opinion = '<h3>'.$row2['created'].'：'.$row2['ID'].','.$row2['name'].','.$row2['comment'].$imgcomment.'<h3>'.'<br>';
              $dataArr3[] = $opinion;

            }
          }
        }
      }
    }
?>

  <html lang="ja">
  <head>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <link rel="stylesheet" href="css/style1.css">

      <title>掲示板[管理人版]</title>
  </head>
  <div id="container">
  <body>
    <h1>ここは管理人のためのページです。</h1><br>
    <h2>評価したい人を選んでください。</h2><br>
    <form method="post" action="">
      <h2>ID：</h2><input type="number" name="number" value="<?php echo $number; ?>" >
            <?php echo $err_msg_n; ?><br>
      <h2>名前：</h2><input type="text" name="name2" value="<?php echo $name2; ?>" >
            <?php echo $err_msg2; ?><br>
      <input type="submit" name="login" value="評価" >
    </form>
    <h3>--------------------------------掲示板--------------------------------</h3><br>
    <br>
    <?php
       if( isset($_POST["login"]) === true )
       {
          $a = 0;
          while( $a < count($dataArr3) ){
           echo "$dataArr3[$a]"; $a++;
          }
          if( $a > 0 )
          {?>
            <form method="post" action="M3pra_4.php">
            <input type="submit" value="管理人用掲示板へ" >
     <?php
          }
        }?>
  </body>
</div>
</html>
