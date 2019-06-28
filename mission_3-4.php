<?php
    if(!isset($name_val)) $name_val = "名前";
    if(!isset($comment_val)) $comment_val = "コメント";
    if(!isset($edit_num)) $edit_num = 0;
    $filename = "mission_3-4.txt";
    $fp = fopen($filename, "a+");
    $history = array();
    while($cache = fgets($fp)){
        array_push($history, $cache);
    }
    //write $_POST data to mission_3-4.txt
    if(isset($_POST["name"]) && isset($_POST["comment"]) && $_POST["name"] != "" && $_POST["comment"] != ""){
        if($_POST["post_to_edit"] != 0){ //edit mode
            unlink($filename);
            $fp = fopen($filename, "w");
            for($i = 0; $i < count($history); $i++){
                $history_split = explode("<>",$history[$i]);
                if($history_split[0] == $_POST["post_to_edit"]){
                    $date = date("Y年m月d日H:i:s");
                    fwrite($fp, $history_split[0]."<>".$_POST["name"]."<>".$_POST["comment"]."<>".$date."\n");
                }else fwrite($fp, $history[$i]);
            }
        }else{ //post mode
            $postnumber = count($history) + 1;
            if(!isset($postnumber)){ // if $history is null, $postnumber is undifined so define it as 1
                $postnumber = 1;
            }
            $date = date("Y年m月d日H:i:s");
            fwrite($fp, $postnumber."<>".$_POST["name"]."<>".$_POST["comment"]."<>".$date."\n");
        }
    }
    //show history of comments
    foreach($history as $info){
        $comment_data = explode("<>",$info);
        for($i = 0; $i < count($comment_data); $i++){
            echo $comment_data[$i]." ";
        }
        echo "<br/>";
    }
    //delete comment
    if(isset($_POST["del_num"])){
        unlink($filename);
        $fp = fopen($filename, "a");
        $count = count($history);
        $post_to_delete = $_POST["del_num"];
        $is_deleted = 0;
        if(is_numeric($post_to_delete)){
            for($i = 0; $i < $count; $i++){
                $history_split = explode("<>", $history[$i]);
                if($history_split[0] == $post_to_delete){
                    $is_deleted = 1;
                    continue;
                }
                fwrite($fp, $history[$i]);
            }
        }
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
                    $matched = 1;
                    $edit_num = $history_split[0];
                    $name_val = $history_split[1];
                    $comment_val = $history_split[2];
                    break;
                }
            }
        }
    }
    fclose($fp);
?>

<html>
    <head>
        <meta charset = "utf-8">
        <title> Form sample </title>
    </head>
    <body>
        <form action = "mission_3-4.php" method = "POST">
        コメント：<br/>
        <input type = "text" name = "name" size 15 value = <?=$name_val?> /><br/>
        <input type = "text" name = "comment" size = 30 value = <?=$comment_val?> /><br/>
        <input type = "hidden" name = "post_to_edit" size 5 value = <?=$edit_num?> /><br/>
        <input type = "submit" value = "送信する"/><br/>
        </form>
        削除フォーム<br/>
        <form action = "mission_3-4.php" method = "POST">
        削除する番号 <input type = "text" name = "del_num" size 5 /><br/>
        <input type = "submit" value = "削除"/><br/>
        </form>
        編集番号指定<br/>
        <form action = "mission_3-4.php" method = "POST">
        編集する番号<input type = "text" name = "edit_num" size 5 /><br/>
        <input type = "submit" value = "編集"/><br/>
        </form>
    </body>
</html>
