<html>
<head lang="ja">
<meta charset="utf-8">
<title>mission_5-2.php</title>
</head>

<body>
<?php

	// DB接続設定
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
	//CREATE文：データベース内にテーブルを作成
	$sql = "CREATE TABLE IF NOT EXISTS 5no1"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT"
	. "date TEXT"
	.");";
	$stmt = $pdo->query($sql);


//削除機能
if(!empty($_POST["delete"])&& !empty($_POST["delpass"])) { 
    //削除パス設定
    if($_POST["delpass"]=="delpass"){
        $delete = $_POST["delete"];
        $delpass= $_POST["delpass"];
        //抽出する
        $sql = 'SELECT * FROM 5no1';
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
	    foreach ($results as $deletedata){
            $deletedata = explode("<>", $delline);
            //対象番号とパスの両方が一致してれば削除
            if ($deletedata[0] != $delete  && $deletedata[4]=$delpass) {
                $id = $delete;
	            $sql = 'delete from 5no1 where id=:id';
	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	               $stmt->execute();
            }
	    }
    }
}


//投稿機能
if(!empty($_POST["name"]) && !empty($_POST["comment"])) {
    //もしコメントと名前だけ空ではない場合（新規投稿の場合）
    if (empty($_POST["edit_num"])&& !empty($name)&&!empty($comment)){ 
        $sql = $pdo -> prepare("INSERT INTO 5no1 (name, comment, date) VALUES (:name, :comment, :date)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $name=$_POST["name"];
        $comment=$_POST["comment"];
        $date=date("Y年m月d日 H時i分s秒");
        $sql -> execute();
    }
}


//編集選択機能
if (!empty($_POST['edit'])&& !empty($_POST["editpass"])) {
    //編集パス設定
    if($_POST["editpass"]=="editpass"){
        $edit = $_POST['edit'];
        $editpass=$_POST["editpass"];
        //抽出する
        $sql = 'SELECT * FROM 5no1';
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
	    foreach ($results as $editline){
            $editdata = explode("<>",$editline);
            //投稿番号と編集対象番号、パスワードが一致したら「名前」と「コメントの取得」
            if ($edit==$editdata[0] && $editdata[4]=$editpass) {
                $editnum=$editdata[0];
                $editname=$editdata[1];
                $editcomment=$editdata[2];
            }
        }
    }
}
   
//編集実行機能
//名前、コメント、編集選択フォームが全部埋まっているとき（編集実行の場合）
if((!empty($_POST["name"]))&&(!empty($_POST["comment"]))&&(!empty($_POST["edit_num"]))){
    $edit_num=$_POST["edit_num"];
    //抽出する
    $sql = 'SELECT * FROM 5no1';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $editdata){
        $editdata=explode("<>",$line);
        //もし投稿番号と編集番号が一致したら
        if($editdata[0]==$edit_num){
            //編集フォームから送信された値を上書き
	        $id = $edit_num; //変更する投稿番号
	        $name = "name";
	        $comment = "comment"; //変更したい名前、変更したいコメントは自分で決めること
	        $sql = 'UPDATE tbtest SET name=:name,comment=:comment WHERE id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	        $stmt->execute();
        }
	}
	
	$sql = 'SELECT * FROM 5no1';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].'<br>';
	echo "<hr>";
	}
}
?>

<form action="mission_5-1.php" method="post">
    <input type="text" name="name" placeholder="名前" value="<?php if(isset($editname)) {echo $editname;} ?>"><br>
    <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($editcomment)) {echo $editcomment;} ?>"><br>
    <input type="text" name="edit_num" value="<?php if(isset($editnum)) {echo $editnum;} ?>"> <!--編集番号が送信されたら、投稿フォームの編集選択フォームに表示に表示-->
    <input type="submit" name="submit" value="送信">
</form>

<form action="mission_5-1.php" method="post">
    <input type="text" name="delete" placeholder="削除対象番号" ></br>
    <input type="text" name="delpass"  placeholder="パスワード" >
    <input type="submit"  value="削除">
</form>

<form action="mission_5-1.php" method="post">
    <input type="text" name="edit" placeholder="編集対象番号"></br>
    <input type="text" name="editpass" placeholder="パスワード">
    <input type="submit" value="編集">
</form>

 <?php
//ブラウザ表示
$sql = 'SELECT * FROM 5no1';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['date'].'<br>';
	echo "<hr>";
}
?>
</body>
</html>
