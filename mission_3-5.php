<?php
    $name_val = "名前";
    $comment_val = "コメント";
    $edit_num = 0;
    $filename = "mission_3-5.txt";
    $fp = fopen($filename, "a+");
    $history = array();
    while($cache = fgets($fp)){
        array_push($history, $cache);
    }
    //write $_POST data to mission_3-5.txt
    if(isset($_POST["name"]) && isset($_POST["comment"]) && isset($_POST["post_pass"]) && $_POST["name"] != "" && $_POST["comment"] != "" && $_POST["post_pass"] != ""){
        if($_POST["post_to_edit"] != 0){ //edit mode
            unlink($filename);
            $fp = fopen($filename, "w");
            for($i = 0; $i < count($history); $i++){
                $history_split = explode("<>",$history[$i]);
                if($history_split[0] == $_POST["post_to_edit"]){
                    if($history_split[4] == $_POST["post_pass"]){
                        $date = date("Y年m月d日H:i:s");
                        fwrite($fp, $history_split[0]."<>".$_POST["name"]."<>".$_POST["comment"]."<>".$date."<>".$_POST["post_pass"]."<>\n");
                    }else $error = "編集エラー：パスワードが誤っています";
                }else fwrite($fp, $history[$i]);
            }
        }else{ //post mode
            if(count($history) > 0){
                $latest_post = explode("<>", $history[count($history) - 1]);
                $postnumber = $latest_post[0] + 1;
            } else $postnumber = 1;
            $date = date("Y年m月d日H:i:s");
            fwrite($fp, $postnumber."<>".$_POST["name"]."<>".$_POST["comment"]."<>".$date."<>".$_POST["post_pass"]."<>\n");
        }
    }
    //delete comment
    if(isset($_POST["del_num"]) && isset($_POST["del_pass"])){
        unlink($filename);
        $fp = fopen($filename, "a");
        $count = count($history);
        $post_to_delete = $_POST["del_num"];
        $is_deleted = 0;
        if(is_numeric($post_to_delete)){
            for($i = 0; $i < $count; $i++){
                $history_split = explode("<>", $history[$i]);
                if($history_split[0] == $post_to_delete){
                    if($history_split[4] == $_POST["del_pass"]){
                    $is_deleted = 1;
                    continue;
                    }else{
                        $is_deleted = -1;
                        $error = "削除エラー：パスワードが間違っています";
                    }
                }
                fwrite($fp, $history[$i]);
            }
            if($is_deleted == 0) $error = "削除エラー：指定された投稿番号は存在しません";
        }else $error = "削除エラー：番号指定が不正です";
    }
    //edit comment
    if(isset($_POST["edit_num"])){
        $edit_num = $_POST["edit_num"];
        $matched = 0;
        if(is_numeric($edit_num)){
            $fp = fopen($filename, "r");
            for($i = 0; $i < count($history); $i++){
                $history_split = explode("<>", $history[$i]);
                if($edit_num == $history_split[0]){
                    $edit_num = $history_split[0];
                    $name_val = $history_split[1];
                    $comment_val = $history_split[2];
                    $password = $history_split[4];
                    $matched = 1;
                    break;
                }
            }
            if($matched = 0) $error = "編集エラー：指定された投稿番号は存在しません";
        }else $error = "編集エラー：番号指定が不正です";
    }
    fclose($fp);
    //show history of comments
    $fp = fopen($filename, "r");
    $history = array();
    while($cache = fgets($fp)){
        array_push($history, $cache);
    }
    foreach($history as $info){
        $comment_data = explode("<>",$info);
        for($i = 0; $i < count($comment_data); $i++){
            echo $comment_data[$i]." ";
        }
        echo "<br/>";
    }
    fclose($fp);
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
