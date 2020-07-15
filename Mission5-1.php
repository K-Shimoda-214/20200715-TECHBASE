<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "UTF-8">
        <title>mission 5-1</title>
    </head>
    <body>
        <span style = "font-size: 50px;"><b>掲示板(Mission 5-1)<br></b></span>
        下田のMission遂行にご協力いただきありがとうございます。
        <br>以下のフォームから書き込みや、削除、編集を試してみてください。(※パスワードの設定が必要です)
        <br>投稿内容が正しく表示されているか確認をお願いします。
        <br>投稿番号は必ずしも連番になるとは限りませんのでご了承ください。<br>
        <br>
        
        <b>投稿内容（投稿番号. [名前][コメント][投稿日時]）<br></b>
        <?php
            #データベース接続
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => ERRMODE_WARNING));
            
            #データベース内にテーブルを作成
            $sql = 'CREATE TABLE IF NOT EXISTS tb5_1'
            . "("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "name char(32),"
            . "comment TEXT,"
            . "date TEXT,"
            . "password TEXT"
            . ");";
            $stmt = $pdo -> query($sql);
            
            #投稿内容の取得
            $name = $_POST["input_name"];
            $comment = $_POST["input_comment"];
            $password = $_POST["input_password"];
            $date = date("Y/m/d (D) H:i:s");
            
            #投稿内容をデータベースへ送信
            if(!empty($name) && !empty($comment) && !empty($password)){
                $sql = $pdo -> prepare('INSERT INTO tb5_1(name, comment, date, password) VALUES(:name, :comment, :date, :password)');
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                $sql -> execute();
            }
            #ここまでで、名前・コメント・パスワードが入力されたときに限って投稿された内容をデータベース上のテーブルに書き込んだ
            
            #削除の挙動
            $del_num = $_POST['del_num'];
            $del_pass = $_POST['del_pass'];
            #入力されたパスワードをデータベースから取得
            $sql = 'SELECT * FROM tb5_1';
            $stmt = $pdo -> query($sql);
            $results = $stmt -> fetchAll();
            foreach($results as $result){
                $dbnum = $result['id'];
                $dbpass = $result['password'];
                #入力されたパスワードが一致したとき、指定された投稿番号のレコードを削除
                if($del_num == $dbnum && $del_pass == $dbpass){
                    $id = $del_num;
                    $sql = 'delete from tb5_1 where id = :id';
                    $stmt = $pdo -> prepare($sql);
                    $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt -> execute();
                }
            }
            #ここまでで、削除機能を実装
            #編集機能の実装
            #編集番号が入力されて、パスワードが一致したときのみ動かすようにする
            $arr_num = $_POST['arr_num'];
            $arr_pass = $_POST['arr_pass'];
            #データベース内の投稿番号とパスワードを取得する
            $sql = 'SELECT * FROM tb5_1';
            $stmt = $pdo -> query($sql);
            $results = $stmt -> fetchAll();
            foreach($results as $result){
                $dbnum = $result['id'];
                $dbname = $result['name'];
                $dbcomment = $result['comment'];
                $dbpass = $result['password'];
                #入力されたパスワードが一致した場合、レコードを編集する
                if($arr_num == $dbnum && $arr_pass == $dbpass){
                    #フォームに投稿内容を表示させる
                    $new_num = $dbnum;
                    $new_name = $dbname;
                    $new_comment = $dbcomment;
                    $new_pass = $dbpass;
                }
            }
            #レコードの編集
            #ここから先はfor文の外に出さなきゃいけない
            $arr_comment = $_POST['arr_post_comment'];
            $arr_post_num = $_POST['arr_post_num'];
            $arr_post_name = $_POST['arr_post_name'];
            $arr_post_pass = $_POST['arr_post_pass'];
            $arr_date = date("Y/m/d (D) H:i:s");
            $id = $arr_post_num;
            if(!empty($arr_comment) && !empty($arr_post_num) && !empty($arr_post_name)){
                $sql = 'UPDATE tb5_1 SET name = :name, comment = :comment, date = :date, password = :password WHERE id = :id'; 
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(':name', $arr_post_name, PDO::PARAM_STR);
                $stmt -> bindParam(':comment', $arr_comment, PDO::PARAM_STR);
                $stmt -> bindParam(':date', $arr_date, PDO::PARAM_STR);
                $stmt -> bindParam(':password', $arr_post_pass, PDO::PARAM_STR);
                $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                $stmt -> execute();
                
                }
        
            
            #データベース内テーブルの内容を表示
            $sql = 'SELECT * FROM tb5_1';
            $stmt = $pdo -> query($sql);
            $results = $stmt -> fetchAll();
            foreach($results as $result){
                if(!empty($result['name']) && !empty($result['comment'])){
                    echo $result['id']. ".";
                    echo "[";
                    echo $result['name'];
                    echo "]";
                    echo "[";
                    echo $result['comment'];
                    echo "]";
                    echo "[";
                    echo $result['date'];
                    echo "]";
                    echo "<br>";
                    }            
            }
        ?>
         <!--フォームの作成-->
        <form action = "", method = "post">
            <!--新規投稿フォーム-->
            <b><br>■新規投稿<br></b>
            <input type = 'text', name = 'input_name', placeholder = '名前を入力'>
            <input type = "text", name = "input_comment", placeholder = "コメントを入力">
            <input type = "password", name = "input_password", placeholder = "パスワードを入力">
            <input type = "submit", name = "submit">
            <br>
            <!--削除フォーム-->
            <br>
            <b>■削除<br></b>
            <input type = 'number' name = 'del_num', placeholder = "削除したい投稿番号">
            <input type = 'password' name = 'del_pass', placeholder = "パスワードを入力">
            <input type = 'submit' name = 'delete', value = '削除'>
            <br>
            <!--編集フォーム-->
            <br>
            <b>■編集<br></b>
            <input type = 'number' name = 'arr_num', placeholder = '編集したい投稿番号'>
            <input type = 'password' name = 'arr_pass' placeholder = 'パスワードを入力'>
            <input type = 'submit' name = 'arrange', value = '編集'>
            <br>
            <!--編集用投稿フォーム-->
            <br>
            <?php
                echo "<input readonly type = 'number' name = 'arr_post_num' value = $new_num>";
                echo "<input readonly type = 'text' name = 'arr_post_name' value = $new_name>";
                echo "<input type = 'text' name = 'arr_post_comment' value = $new_comment>";
                echo "<input readonly type = 'hidden' name = 'arr_post_pass' value = $new_pass>";
            ?>
            <input type = "submit" name = "arrange_post" value = "編集完了">
            <br>
            ※番号と名前は編集できません
        </form>
    </body>
</html>