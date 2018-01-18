<?php
//[管理人版]評価用実装されています。
  $err_msg_n = ""; $err_msg1 = ""; $err_msg2 = ""; $err_msg3 = "";
  $number = ( isset( $_POST["number"] ) === true ) ? $_POST["number"]:"";
  $evaluation = ( isset( $_POST["evaluation"] ) === true ) ? $_POST["evaluation"]:"";

    if ( isset($_POST["select"]) === true )
    {
      if ( $number === "" ) $err_msg_n = "投稿番号を入力してください!";
      if( $err_msg_n == "" )
      {
          $user = '';
          $pass = '';
          try
            {$pdo = new PDO('mysql:dbname=;host=',$user,$pass);
            }
          catch (PDOException $e)
            {exit('データベースに接続できませんでした。'. $e->getMessage());
            }
          $sql_s = 'SELECT * FROM personal_posting';
          $result = $pdo->query($sql_s);
          foreach($result as $row)
          {
            if ( $row['ID'] == $number){
              $sql = "UPDATE personal_posting SET evaluation = :evaluation WHERE ID = :ID";
             $stmt = $pdo->prepare($sql);
             $params = array(':evaluation' => $evaluation, ':ID' => $number);
             $stmt->execute($params);
               $err_msg3 = "正しく送信されました。";
               break;
            }
            if ( $row['ID'] !== $number)
              {$err_msg3 = " 投稿番号が存在しません。";}
          }

          if( $err_msg3 == "正しく送信されました。" )
          {
            $sql_s2 = 'SELECT * FROM personal_posting';
            $result3 = $pdo->query($sql_s2);
            foreach($result3 as $row2)
            {
              if( $row2['ID'] == $number )
              {
                $imgcomment = '';
                 if( strlen($row2['image']) > 2 ) $imgcomment ="<img src=\"M3loadpicture.php?ID=" . $row2['ID']  . "\">";
                 $opinion = '<h2>'.$row2['evaluation'].'</h2>'.'<h3>'.$row2['created'].'：'.$row2['ID'].','.$row2['name'].','.$row2['comment'].'<br>'.$imgcomment.'<h3>';
               }
            }
          }
      }
    }
    if ( isset($_POST["all"]) === true )
    {
      $user = '';
      $pass = '';
      try
        {$pdo = new PDO('mysql:dbname=;host=',$user,$pass);
        }
      catch (PDOException $e)
        {exit('データベースに接続できませんでした。'. $e->getMessage());
        }
      $sql_s = 'SELECT * FROM personal_posting';
      $result2 = $pdo->query($sql_s);
      $dataArr_all = array();
      foreach($result2 as $row2){
        $imgcomment = '';
         if( strlen($row2['image']) > 2 ) $imgcomment ='<a href="M3loadpicture.php?ID='.$row2['ID'].'">画像を確認</a>';
         $p = $row2['evaluation'];
         if( $p != "" ) $p = $p.',______';
          $opinion2 = '<h3>'.$p.$row2['created'].'：'.$row2['ID'].','.$row2['name'].','.$row2['comment'].$imgcomment.'<h3>';
         $dataArr_all[] = $opinion2;
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
    <h2>評価したい投稿を選び、評価してください。</h2><br>
    <form method="post" action="">
      <h2>投稿番号：</h2><input type="number" name="number" value="<?php echo $number; ?>" >
            <?php echo $err_msg_n; ?><br>
      <h2>評価：</h2><input type="text" name="evaluation" value="<?php echo $evaluation; ?>" >
           <br>
      <input type="submit" name="select" value="投稿" >
      <input type="submit" name="all" value="これまでの評価をみる" >
    </form>
    <h3>--------------------------------評価--------------------------------</h3><br>
    <h3>評価後、表示されます。</h3><br>
    <?php
       if( isset($_POST["select"]) === true )
       {
            echo "$opinion";
       }
       if( isset($_POST["all"]) === true ){
         $a = 0;
         while( $a < count($dataArr_all) ){
          echo "$dataArr_all[$a]"; $a++;
         }
       }
    ?>
  </body>
</div>
</html>
