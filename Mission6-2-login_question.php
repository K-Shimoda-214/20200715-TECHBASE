<!DOCTYPE html>
<html lang = 'ja'>
    <head>
        <meta charset = "UTF-8">
        <title>WEB実験ノート-ログイン画面</title> 
    </head>
    <body>
        <span style = "font-size: 30px;"><b>WEB実験ノート ログイン画面</b></span>
        <form action = "", method = "post">
            研究室ID　　　　<input type = "text", name = "user", placeholder = "研究室ID"><br>
            研究室パスワード<input type = "password", name = "password", placeholder = "研究室パスワード"><br>
            <input type = "submit", name = "submit", value = "確認"><br>
        </form>
        <?php
            #入力内容の取得
            if(!empty($_POST['user']) && !empty($_POST['password'])){
                $input_labuser = $_POST['user'];
                $input_labpass = $_POST['password'];
            }
            #入力された研究室ID、研究室パスワードがデータベースに登録されたものと一致するかどうかを確認する
            #データベースへの接続
            $dsn = 'mysql:dbname=*********;host=localhost';
            $user = '*******';
            $password = '********';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
            #研究室ID、パスワードを取得
            $sql = 'SELECT * FROM tblabname';
            $stmt = $pdo -> query($sql);
            $results = $stmt -> fetchAll();
            foreach($results as $result){
                $dblabname = $result['labname'];
                $dblabpass = $result['labpass'];
                
                #入力ID、パスワードが一致したらプルダウンメニューを表示
                #プルダウンメニューに表示させるメンバー一覧を取得
                if($dblabname == $input_labuser && $dblabpass == $input_labpass){
                    $sql = "SELECT * FROM $dblabname";
                    $stmt = $pdo -> query($sql);
                    $results2 = $stmt -> fetchAll();
                    echo "<form action = '' method = 'post'>";
                    echo "<br>";
                    echo "個人IDを選択　　　　";
                    echo "<select name = 'pulldown'>";
                    foreach($results2 as $result2){
                        $db_personalid = $result2['personalid'];
                        echo "<option value = $db_personalid>";
                        echo $db_personalid;
                        echo "</option>";
                    }
                    echo "</select>";
                    echo "<br>";
                    echo "個人パスワードを入力";
                    echo "<input type = 'password' name = 'p_pass' placeholder = '個人パスワード'>";
                    echo "<br>";
                    echo "<input type = 'submit' name = 'login' value = 'ログイン'";
                    echo "</form>";
                }
            }
            #入力された個人ID・パスワードの取得
            if(!empty($_POST['pulldown']) && !empty($_POST['p_pass'])){
                $loginname = $_POST['pulldown'];
                $loginpass = $_POST['p_pass'];
            }
            
            #データベースとの比較
            #データベーステーブルから登録された個人IDとパスワードを取得
            
            $sql = "SELECT * FROM $input_labuser";
            $stmt = $pdo -> query($sql);
            #ここまでは$loginname, $loginpassはechoできる
            $items = $stmt -> fetchAll();
            #ここから先で$loginname, $loginpassをechoしようとしても表示されない
            foreach($items as $item){
                $db_loginname = $item['personalid'];
                $db_loginpass = $item['personalpass'];
                
                #入力データとデータベースデータが一致したとき、ログイン成功
                if($db_loginname == $loginname && $db_loginpass == $loginpass){
                    echo "ログイン成功";
                }
            }
            
        ?>
        <br><a href = "飛ばしたいリンク先のアドレス" target = "_blank">新規登録はこちら</a>
        <br><a href = "https://home.tech-base.net/info/forportal/editor/userfile/u220143/Mission6-2-LABregist.php" target = "_blank">研究室登録はこちら</a><br>
    </body>
</html>