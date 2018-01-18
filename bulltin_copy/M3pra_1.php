<?php
//ここはユーザー仮登録用画面のみです

  $err_msg1 = ""; $err_msg2 = ""; $err_msg3 = "";
  $message = ""; $opinion = ""; $err_msg_mail = "";
  $name = ( isset( $_POST["name"] ) === true ) ? $_POST["name"]:"";
  $password = ( isset( $_POST["password"] ) === true ) ? ($_POST["password"]):"";
  $created = ( isset( $_POST["created"] ) === true ) ? ($_POST["created"]):"";
  $mail = ( isset( $_POST["mail"] ) === true ) ? ($_POST["mail"]):"";

  if (  isset($_POST["send"] ) ===  true ) {
      if ( $name === "" ) $err_msg1 = "名前を入力してください!";
      if ( $password === "" ) $err_msg3 = "パスワードを入力してください!";
      if ( $mail === "" ) $err_msg_mail = "メールアドレスを入力してください!";
      if( $err_msg1 =="" && $err_msg3 =="" && $err_msg_mail=="" )
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
             if ( $row['name'] === $name )
               {
                $message = "既に使われている名前です。別の名前を選んでください。";
                $pdo = null;
               }
           }
           if($message == "")
           {
             $created = date("Y-m-d H:i:s");
             $sql = "INSERT INTO personal_registration (name, password, created) VALUES ('$name', '$password', '$created')";
             $result = $pdo->query($sql);
             $sql2 = 'SELECT * FROM personal_registration';
             $result2 = $pdo->query($sql2);
             $dataArr= array();
             foreach($result2 as $row)
               {
                $opinion  =  $row['ID'].','.$row['name'].','."passwordは安全のため非表示,".$row['created'].'<br>';
                $dataArr[] = $opinion;
               }
             $message ="送信されたメールアドレスを確認してください。";
           }
       }
  }

if( $message =="送信されたメールアドレスを確認してください。" ){
  $id = count($dataArr);
  $url = "http:~.php"."?id=".$id;
  // 言語と文字エンコーディングを正しくセット
  mb_language("Japanese");
  mb_internal_encoding("UTF-8");
  // 宛先情報をエンコード
  $to_name = "$name";
  $to_addr = "$mail";
  $to_name_enc = mb_encode_mimeheader($to_name,"ISO-2022-JP");
  $to = "$to_name_enc<$to_addr>";
  // 送信元情報をエンコード
  $from_name = "";
  // ;
  $from_addr = "";

  $from_name_enc = mb_encode_mimeheader($from_name, "ISO-2022-JP");
  $from = "$from_name_enc<$from_addr>";
  // メールヘッダを作成
  $header  = "From: $from\n";
  $header .= "Reply-To: $from";
  // 件名や本文をセット(自動的にエンコード)
  $subject = "メールのテスト";
  $body = " こんにちは、$to_name さん(ID:".$id."は忘れないでください)。掲示板へようこそ！このリンクに招待します。{$url}";
  $result = mb_send_mail($to, $subject, $body, $header, $id);
  if ($result) {
    echo "Success!";
  } else {
    echo "メールアドレスが正しく送信されませんでした。アドレスが正しいものか再度確認をお願いいたします。";
  }
}
?>

<html lang="ja">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="css/style1.css">
        <title>ユーザー(仮)登録画面</title>
    </head>
    <div id="container">
    <body>
    <h1>掲示板ユーザー登録画面</h1>
    <form method="post" action="">
      <h2>名前：</h2><input type="text" name="name" maxlength='10' placeholder='10文字以内でお願いします。' value="<?php echo $name; ?>" >
            <?php echo $err_msg1; ?><br>
      <h2>パスワード：</h2><input type="text" name="password" maxlength='10' placeholder="パスワード(10文字以内)を設定してください。" value="<?php echo $password; ?>" >
                  <?php echo $err_msg3; ?><br>
      <h2>登録用メールアドレス：</h2><input type="text" name="mail" placeholder="必ず正しいメールアドレスを打ち込んでください。" value="<?php echo $mail; ?>" >
                  <?php echo $err_msg_mail; ?><br>
      <input type="submit" name="send" value="登録" >
      <?php echo $message; ?><br>
    </form>
      <?php
      if( $message =="送信されたメールアドレスを確認してください。" )
      {
        $a = count($dataArr)-1;
        echo '<h3>'.'ID'."$dataArr[$a]".'</h3>';
      }
      ?>
    </body>
    </div>
</html>
