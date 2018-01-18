<?php
if (isset($_GET['ID'])) {
  $num = $_GET['ID'];
}
// 拡張子によってMIMEタイプを切り替えるための配列
$MIMETypes = array(
   'png'  => 'image/png',
   'jpg'  => 'image/jpeg',
   'jpeg' => 'image/jpeg',
   'gif'  => 'image/gif',
   'bmp'  => 'image/bmp',
   'mp4'  => 'video/mp4',
);
try
  {
    $user = '';
    $pass = '';
    $pdo = new PDO('mysql:dbname=;host=',$user,$pass);
    $tableName = "personal_posting";
    // データベースから条件に一致する行を取り出す
    $data = $pdo->query('SELECT * FROM ' . $tableName . ' WHERE ID = "' . $num . '"')->fetch(PDO::FETCH_ASSOC);
    // 画像or動画として扱うための設定
    header('Content-type: ' . $MIMETypes[$data['extension']]);
    echo $data['image'];
  } catch (Exception $e) {
  echo "load failed: " . $e;
}
?>
