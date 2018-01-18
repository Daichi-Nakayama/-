<?php
//掲示板へのログインと掲示板の閲覧が実装されています。
session_start();
  $err_msg_n = ""; $err_msg1 = ""; $err_msg2 = ""; $err_msg3 = "";
  $number = ( isset( $_POST["number"] ) === true ) ? $_POST["number"]:"";
  $name = ( isset( $_POST["name"] ) === true ) ? $_POST["name"]:"";
  $password =  ( isset( $_POST["password"] )  === true ) ? ($_POST["password"]):"";
  $comment  = ( isset( $_POST["comment"] )  === true ) ? ($_POST["comment"]):"";
  $p = 0;
  if (!isset($_SESSION["visited"])){
       print('初回の訪問です。掲示板へようこそ！注意：セッションを開始します。');
       $_SESSION["visited"] = 1;
   }else{
       $visited = $_SESSION["visited"];
       $visited++;
       print('訪問回数は'.$visited.'です。<br>');

       $_SESSION["visited"] = $visited;
   }

    if ( isset($_POST["login"]) === true || isset($_POST["show"]) === true)
    {
      if ( $number === "" ) $err_msg_n = "IDを入力してください!";
      if ( $name === "" ) $err_msg2 = "名前を入力してください!";
      if ( $password === "" ) $err_msg1 = "パスワードを入力してください!";
      if ( $comment === "" ) $err_msg3 = "何か投稿してみましょう。";
      if ( $err_msg_n == "" && $err_msg1 == "" && $err_msg2 == "" && $err_msg3 == "" || isset($_POST["show"]) === true )
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
          if ( $row['ID'] == $number && $row['name'] == $name && $row['password'] == $password && $row['flag'] == "0" && isset($_POST["show"]) !== true)
          {
            $err_msg3 = "本登録がまだ済んでいないようです。";
          }
          if ( $row['ID'] == $number && $row['name'] == $name && $row['password'] == $password && $row['flag'] != "0" && $comment != "" && isset($_POST["show"]) !== true)
          {
            $imagePath = $_FILES['upfile']['tmp_name'];
            $image = file_get_contents($imagePath);
            $extension = pathinfo($imagePath, PATHINFO_EXTENSION);

            $tableName = "personal_posting";
            $insert = $pdo->prepare('INSERT INTO ' . $tableName . ' (name,comment,password,image, extension,created) VALUES (:name, :comment, :password, :image, :extension, :created)');
            $insert->bindValue(':name', $name, PDO::PARAM_STR);
            $insert->bindValue(':comment', $comment, PDO::PARAM_STR);
            $insert->bindValue(':password', $password, PDO::PARAM_STR);
            $insert->bindValue(':image', $image, PDO::PARAM_LOB);
            $insert->bindValue(':extension', $extension, PDO::PARAM_STR);
            $created = date("Y-m-d H:i:s");
            $insert->bindValue(':created', $created, PDO::PARAM_STR);
            $insert->execute();

            $err_msg3 = "正しく送信されました。";
            $_SESSION['number'] = $number;
            $_SESSION['password'] = $password;
            $_SESSION['name'] = $name;
            break;
          }
          if ( $row['ID'] != $number || $row['name'] != $name || $row['password'] != $password )
            {
              if ( isset($_POST["show"]) !== true )
              $err_msg3 = " IDか名前かパスワードが異なります。";
            }
        }
      }
      if( $err_msg3 == "正しく送信されました。" || isset($_POST["show"]) === true )
      {
        $sql_s = 'SELECT * FROM personal_posting';
        $result2 = $pdo->query($sql_s);
        $dataArr2 = array();
        foreach($result2 as $row2){
          $imgcomment = '';
           if( strlen($row2['image']) > 2 ) $imgcomment ='<a href="M3loadpicture.php?ID='.$row2['ID'].'">画像を確認</a>';
           $opinion2 = '<h3>'.$row2['created'].'：'.$row2['ID'].','.$row2['name'].','.$row2['comment'].$imgcomment.'<h3>'.'<br>';
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
    <h1>掲示板</h1>
    <form method="post" enctype="multipart/form-data" action="">
      <h2>投稿一覧はここをクリックすると見れますよ！<input type="submit" name="show" value="掲示板表示" ></h2><br>
      <a href="M3pra_1.php">仮登録画面へはこちらから</a><br>
      <a href="M3pra_3-0.php">管理人用画面へはこちらから</a>
      <h2>ID：</h2><input type="number" name="number" value="<?php print $_SESSION['number']; ?>" value="<?php echo $number; ?>" >
            <?php echo $err_msg_n; ?><br>
      <h2>パスワード：</h2><input type="text" name="password" value="<?php print $_SESSION['password']; ?>" value="<?php echo $password; ?>" >
            <?php echo $err_msg1; ?><br>
      <h2>名前：</h2><input type="text" name="name" value="<?php print $_SESSION['name']; ?>" value="<?php echo $name; ?>" >
            <?php echo $err_msg2; ?><br>
      <!-- <h2>投稿内容：</h2><input type="text" name="comment" size="60" value="<?php echo $comment; ?>" >
            <?php echo $err_msg3; ?><br> -->
      <p>
      <label for="posting"><h2>投稿内容：</h2></label><br>
      <textarea id="posting" name="comment" cols="40" rows="4" maxlength="50" value="<?php echo $comment; ?>"></textarea>
      </p>  <?php echo $err_msg3; ?><br>
      <h2>画像(もし投稿したければどうぞ！)：</h2><input type="file" name="upfile"><br>
      <input type="submit" name="login" value="投稿" >
    </form>

  <h2>--------------------------------掲示板--------------------------------</h2><br>
    <?php
       if( isset($_POST["login"]) === true || isset($_POST["show"]) === true )
       {
          $a = 0;
          while( $a < count($dataArr2) ){
            echo "$dataArr2[$a]";
            $a=$a+1;
          }
       }
    ?>
  </body>
</div>
</html>
