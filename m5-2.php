<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    
</body>

    <!--mission3と同内容の機能-->
    <!--
    ・新規投稿機能
    ・削除機能
    ・編集機能
    ・パスワード機能
    ・（投稿一覧表示機能）
    -->
    
    <h1>m5-1 Web簡易掲示板(SQL文ver.)</h1>
    <h2>掲示板の機能</h2>
    <h3>・新規投稿機能</h3>
    <h3>・投稿削除機能(m3-02→m3-03)</h3>
    <h3>・投稿編集機能←追加機能(m3-03→m3-04)</h3>
    <h3>・投稿内容表示ボタン(課題とは関係なし)</h3>
    <h3>・パスワード機能
    （現状は，mission効率化のため投稿内容と一緒にパスワードも表示しているが，普通は表示してはいけない情報）</h3>

    <!--投稿入力フォーム（新規・編集）-->
    <form action="" method="post">
        ・投稿入力フォーム
        <p><input type="text" name="name" placeholder="名前"></p>
        <p><input type="text" name="comment" placeholder="コメント"></p>
        <p><input type="password" name="pass" placeholder="パスワードを設定してください" 
        value="<?php if(!empty($_POST['pass']) && !empty($_POST['pass_flag']))if($_POST['pass_flag']==1){ echo $_POST['pass'];}?>">
        <input type="hidden" name="edit_num" placeholder="編集番号を入力してください" value="<?php if(!empty($_POST['edit_num'])){ echo $_POST['edit_num'];} ?>">
        <input type="submit" value="投稿"></p>
        <!--<input type="submit" name="submit">-->
    </form>

    <!--編集番号指定用フォーム-->
    <form action="" method="post">
        ・編集番号入力フォーム
        <p><input type="number" name="edit_num" placeholder="編集番号を入力してください"></p>
        <input type="hidden" name="pass_flag" value="1"> <!--パスワード保存するかどうか判定-->
        <p><input type="number" name="pass" placeholder="パスワードを入力してください">
        <input type="submit" value="編集"></p>
    </form>

    <!--削除フォーム-->
    <form action="" method="post">
        ・削除番号入力フォーム
        <p><input type="number" name="del_num" placeholder="削除番号を入力してください"></p>
        <p><input type="number" name="pass" placeholder="パスワードを入力してください">
        <input type="submit" value="削除"></p>
    </form>

    <!--投稿内容一覧表示ボタン-->
    ・投稿内容表示ボタン<br>
    <form action="" method="post">
        <p><button type="submit" name="dis_push">投稿一覧表示</button></p>
    </form>


<?php
    /*現状は，投稿内容と一緒にパスワードも表示しているが，ふつうは表示してはいけない情報*/
    //変数宣言
    $edit_flag = 0;
    $flag=0;
    $edit_num=0;
    
    // DB接続設定（自分のデータベースに接続）(m4-1)
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    #echo "実行完了<br>";
    
    //m5-1用のデータベースのテーブル作成(m4-2)
    $sql = "CREATE TABLE IF NOT EXISTS tbtest_5_1"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date TEXT,"
    . "pass INT"
    .");";
    $stmt = $pdo->query($sql);
    #echo "tbtest_5_1作成完了<br>";
    #echo "<hr>";
    
    //DB一覧表示(m4-3)
    #echo "DB一覧<br>";
    $sql ='SHOW TABLES';
    $result = $pdo -> query($sql);
    foreach ($result as $row){
        #echo $row[0];
        #echo '<br>';
    }
    #echo "<hr>";
    
    
    //DB構成詳細表示(m4-4)
    #echo "tbtest_5_1 構成詳細<br>";
    $sql ='SHOW CREATE TABLE tbtest_5_1';
    $result = $pdo -> query($sql);
    foreach ($result as $row){
        #echo $row[1];
    }
    #echo "<hr>";
    
    
    /*投稿内容一覧ボタン*/
    if(isset($_POST["dis_push"])){
        echo "============投稿内容ここから表示==============<br>";
        $sql = 'SELECT * FROM tbtest_5_1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo "id：".$row['id']."<br>";
            echo "name：".$row['name']."<br>";
            echo "comment：".$row['comment']."<br>";
            echo "date：".$row['date']."<br>";
            echo "pass：".$row['pass']."<br>";
            echo "<hr>";
        }
        #output_file();
    }
    
    
    /*投稿削除部*/
    if(!empty($_POST["del_num"]) && !empty($_POST["pass"])){
        $sql = 'SELECT * FROM tbtest_5_1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            if($row['id'] == $_POST["del_num"]){
                if($row['pass'] == $_POST["pass"]){
                    //4-8追加部分
                    //4-1で書いた「// DB接続設定」のコードの下に続けて記載する。
                    $id = $_POST["del_num"];
                    $sql = 'delete from tbtest_5_1 where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    echo  $_POST["del_num"]."番、削除完了<br>";
                }else{
                    echo "削除失敗<br>";
                    echo "パスワードが違います。<br>";
                }
            }else{
                #echo $_POST["del_num"]."番目の投稿はありません。<br>";
            }
        }
        echo "<hr>";
        /*正しく書き込みできたかの確認*/
        echo "============投稿内容ここから表示==============<br>";
        $sql = 'SELECT * FROM tbtest_5_1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo "id：".$row['id']."<br>";
            echo "name：".$row['name']."<br>";
            echo "comment：".$row['comment']."<br>";
            echo "date：".$row['date']."<br>";
            echo "pass：".$row['pass']."<br>";
            echo "<hr>";
        }
        #output_file($filename, $dis_arr);
    }
    
    /*編集機能*/
    if(!empty($_POST["edit_num"]) && !empty($_POST["pass"])){
        if($edit_flag==0){
            echo "投稿入力フォームに編集内容を入力してください。<br>";
        }
        $edit_flag = 1;
        $sql = 'SELECT * FROM tbtest_5_1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if($row['id'] == $_POST["edit_num"]){
                if(!empty($_POST["name"]) && !empty($_POST["comment"])){
                    if($edit_flag==1 && $row['name'] == $_POST["name"] && $row['pass'] == $_POST["pass"]){
                        //4-7追加部分(m4-7)
                        //bindParamの引数（:nameなど）は4-2でどんな名前のカラムを設定したかで変える必要がある。
                        $id = $row['id']; //変更する投稿番号
                        #$name = $row['name'];
                        $comment = $_POST["comment"]; //コメントの編集内容
                        $date = date("Y年m月d日 H時i分s秒");
                        #$pass = $row['pass'];
                        #echo "編集内容：".$row['comment']."→".$comment."<br>";
                        #echo $row['date']."→".$date."<br>";
                        //編集部分
                        $sql = 'UPDATE tbtest_5_1 SET comment=:comment, date=:date WHERE id=:id';
                        $stmt = $pdo->prepare($sql);
                        #$stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                        #$stmt->bindParam(':pass', $pass, PDO::PARAM_INT);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                        echo "編集成功！<br>";
                        echo "--------------------------<br>";
                        $flag=1;
                        $edit_num=$row['id'];
                        
                    }else{
                        echo "編集失敗！";
                    }
                }else{
                    echo "編集対象の投稿<br>";
                    echo "--------------------------<br>";
                    echo "id：".$row['id']."<br>";
                    echo "name：".$row['name']."<br>";
                    echo "comment：".$row['comment']."<br>";
                    echo "date：".$row['date']."<br>";
                    echo "pass：".$row['pass']."<br>";
                    echo "<hr>";
                }
            }
        }
    }
    //編集内容表示
    if($flag==1){
        echo "〇編集後<br>";
        $sql = 'SELECT * FROM tbtest_5_1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if($row['id']==$edit_num){
                echo "id：".$row['id']."<br>";
                echo "name：".$row['name']."<br>";
                echo "comment：".$row['comment']."<br>";
                echo "date：".$row['date']."<br>";
                echo "pass：".$row['pass']."<br>";
            }
        }
    }
    
    
    
    /*投稿追加部*/
    if($edit_flag==0 && !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){
        //DB書き込み(m4-5)
        $sql = $pdo -> prepare("INSERT INTO tbtest_5_1 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
        $name = $_POST["name"]; #名前
        $comment = $_POST["comment"]; #コメント
        $date =  date("Y年m月d日 H時i分s秒"); #投稿年月日
        $pass = $_POST["pass"]; #パスワード取得
        $sql -> execute();
        //bindParamの引数名（:name など）はテーブルのカラム名に併せるとミスが少なくなります。最適なものを適宜決めよう。
            
        #正しく書き込みできたかの確認
        echo "新規投稿完了！<br>";
        echo "============投稿内容ここから表示==============<br>";
        
        $sql = 'SELECT * FROM tbtest_5_1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo "id：".$row['id']."<br>";
            echo "name：".$row['name']."<br>";
            echo "comment：".$row['comment']."<br>";
            echo "date：".$row['date']."<br>";
            echo "pass：".$row['pass']."<br>";
            echo "<hr>";
        }
        #output_file();
    }


    //ファイルの中身出力
    function output_file(){
        $sql = 'SELECT * FROM tbtest_5_1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo "id：".$row['id']."<br>";
            echo "name：".$row['name']."<br>";
            echo "comment：".$row['comment']."<br>";
            echo "date：".$row['date']."<br>";
            echo "pass：".$row['pass']."<br>";
            echo "<hr>";
        }
    }

?>
</html>




