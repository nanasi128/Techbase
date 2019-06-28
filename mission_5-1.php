<?php
    require("db_login.php");
    
    $sql = "CREATE TABLE IF NOT EXISTS bbstest"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name char(32),"
    ."comment TEXT,"
    ."date TEXT,"
    ."password char(32)"
    .");";
    $stmt = $pdo -> query($sql);
    
    $name_val = "名前";
    $comment_val = "コメント";
    $edit_num = 0;
    
    //load history
    $sql = "SELECT * FROM bbstest";
    $stmt = $pdo -> query($sql);
    $history = $stmt -> fetchAll();
    
    //write $_POST data
    if(isset($_POST["name"]) && isset($_POST["comment"]) && isset($_POST["post_pass"]) && $_POST["name"] != "" && $_POST["comment"] != "" && $_POST["post_pass"] != ""){
        $date = date("Y/m/d H:i:s");
        if($_POST["post_to_edit"] != 0){ //edit mode
            $sql = "update bbstest set name = :name, comment = :comment, date = :date where id = :id";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(':name', $_POST["name"], PDO::PARAM_STR);
            $stmt -> bindParam(':comment', $_POST["comment"], PDO::PARAM_STR);
            $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
            $stmt -> bindParam(':id', $_POST["post_to_edit"], PDO::PARAM_INT);
            $stmt -> execute();
        }else{ //post mode
            $sql = $pdo -> prepare("INSERT INTO bbstest(name, comment, date, password) VALUES(:name, :comment, :date, :password)");
            $sql -> bindParam(':name', $_POST["name"], PDO::PARAM_STR);
            $sql -> bindParam(':comment', $_POST["comment"], PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':password', $_POST["post_pass"], PDO::PARAM_STR);
            $sql -> execute();
        }
    }
    //delete comment
    if(isset($_POST["del_num"]) && isset($_POST["del_pass"])){
        $post_to_delete = $_POST["del_num"];
        $is_deleted = 0;
        if(is_numeric($post_to_delete)){
            foreach($history as $row){
                if($row['id'] == $post_to_delete){
                    if($row['password'] == $_POST["del_pass"]){
                        $sql = "delete from bbstest where id = :id";
                        $stmt = $pdo -> prepare($sql);
                        $stmt -> bindParam(':id', $post_to_delete, PDO::PARAM_INT);
                        $stmt -> execute();
                    }else $error = "削除エラー：パスワードが不正です";
                    break;
                }
            }
        }else $error = "削除エラー：番号指定が不正です。数字を入力してください";
    }
    //edit comment
    if(isset($_POST["edit_num"])){
        $edit_num = $_POST["edit_num"];
        $matched = 0;
        if(is_numeric($edit_num)){
            foreach($history as $row){
                if($row['id'] == $edit_num){
                    $name_val = $row['name'];
                    $comment_val = $row['comment'];
                    $matched = 1;
                    break;
                }
            }
            if($matched == 0) $error = "編集エラー：指定された投稿番号は存在しません";
        }else $error = "編集エラー：番号指定が不正です";
    }
    //show history of comments
    $sql = "SELECT * FROM bbstest";
    $stmt = $pdo -> query($sql);
    $history = $stmt -> fetchAll();
    foreach($history as $row){
        echo $row['id'].",";
        echo $row['name'].",";
        echo $row['comment'].",";
        echo $row['date'].",";
        echo $row['password']."<br/>";
        echo "<hr>";
    }
?>

<html>
    <head>
        <meta charset = "utf-8">
        <title> Form sample </title>
    </head>
    <body>
        <form action = " " method = "POST">
        名前<input type = "text" name = "name" size 15 value = <?=$name_val?> /><br/>
        コメント<input type = "text" name = "comment" size = 30 value = <?=$comment_val?> /><br/>
        パスワード<input type = "text" name = "post_pass" size = 10 /><br/>
        <input type = "hidden" name = "post_to_edit" size 5 value = <?=$edit_num?> /><br/>
        <input type = "submit" value = "送信する"/><br/>
        </form>
        削除フォーム<br/>
        <form action = " " method = "POST">
        削除する番号 <input type = "text" name = "del_num" size 5 /><br/>
        パスワード<input typr = "password" name = "del_pass" size 10 /><br/>
        <input type = "submit" value = "削除"/><br/>
        </form>
        編集番号指定<br/>
        <form action = " " method = "POST">
        編集する番号<input type = "text" name = "edit_num" size 5 /><br/>
        <input type = "submit" value = "編集"/><br/>
        </form>
    </body>
</html>

<?php
    if(isset($error)) echo $error;
?>
