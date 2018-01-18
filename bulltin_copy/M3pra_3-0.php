<?php
//[管理人版]ログインが実装されています。

session_start();
  $err_msg_n = ""; $err_msg1 = ""; $err_msg2 = ""; $err_msg3 = "";
  $number = ( isset( $_POST["number"] ) === true ) ? $_POST["number"]:"";
  $name = ( isset( $_POST["name"] ) === true ) ? $_POST["name"]:"";
  $password =  ( isset( $_POST["password"] )  === true ) ? ($_POST["password"]):"";

    if ( isset($_POST["login"]) === true)
    {
      if ( $number === "" ) $err_msg_n = "IDを入力してください!";
      if ( $name === "" ) $err_msg2 = "名前を入力してください!";
      if ( $password === "" ) $err_msg1 = "パスワードを入力してください!";
      if ( $err_msg_n == "" && $err_msg1 == "" && $err_msg2 == "")
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
          if ( $row['ID'] == $number && $row['name'] == $name && $row['password'] == $password && $row['flag'] == "0")
          {
            $err_msg3 = "本登録がまだ済んでいないようです。";
          }
          if ( $row['ID'] == $number && $row['name'] == $name && $row['password'] == $password )
          {
            $err_msg3 = "正しく送信されました。";
            $_SESSION['number'] = $number;
            $_SESSION['password'] = $password;
            $_SESSION['name'] = $name;
            break;
          }
          if ( $row['ID'] !== $number || $row['name'] !== $name )
            { $err_msg3 = " IDか名前かパスワードが異なります。";
            }
        }
      }
      if( $err_msg3 == "正しく送信されました。")
      {
        $sql_s = 'SELECT * FROM personal_registration';
        $result2 = $pdo->query($sql_s);
        $dataArr2 = array();
        foreach($result2 as $row2){
           $opinion2 = '<h3>'.'登録時刻:'.$row2['created'].','.$row2['ID'].','.$row2['name'].','.'</h3>';
           $dataArr2[] = $opinion2;
          }
       }
    }
?>

  <html lang="ja">
  <head>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <link rel="stylesheet" href="css/style1.css">
      <title>掲示板</title>
  </head>
  <div id="container">
  <body>
    <h1>管理人としてログインしますか？</h1>
    <form method="post" enctype="multipart/form-data" action="">
      <a href="M3pra_1.php">仮登録画面へはこちらから</a>
      <h2>ID：</h2><input type="number" name="number" value="<?php print $_SESSION['number']; ?>" value="<?php echo $number; ?>" >
            <?php echo $err_msg_n; ?><br>
      <h2>パスワード：</h2><input type="text" name="password" value="<?php print $_SESSION['password']; ?>" value="<?php echo $password; ?>" >
            <?php echo $err_msg1; ?><br>
      <h2>名前：</h2><input type="text" name="name" value="<?php print $_SESSION['name']; ?>" value="<?php echo $name; ?>" >
            <?php echo $err_msg2; ?><br>
      <input type="submit" name="login" value="ログイン" >
    </form>

  <h2>--------------------------------登録者一覧--------------------------------</h2><br>
    <?php
       if( isset($_POST["login"]) === true && $err_msg3 == "正しく送信されました。" )
       {
          $a = 0;
          while( $a < count($dataArr2) ){
            echo "$dataArr2[$a]";
            $a=$a+1;
          }
          if( $a > 0 )
          {?>
            <form method="post" action="M3pra_3.php">
            <input type="submit" value="管理人用ページはこちらから" >
          <?php
          }
       }
    ?>
  </body>
</div>
</html>
