
<html>
    <?php
        header("Content-Type: text/html; charset = UTF-8");
        try{
            //データベースへの接続
            $servername = "ホスト";
            $username = "ユーザー名";
            $password= "パスワード";
            $dbname = "データベース名";
            $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8";
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            if(!empty($_POST["uniqueid"])&&!empty($_POST["email"])){
                //保存したEmailとUniqueIDを取得する
                $sql = "SELECT * FROM GuestInfo";
                $results = $pdo->query($sql);
                foreach ($results as $row){
                    if($row["uniqueid"] == $_POST["uniqueid"]){
                        $verification = $row["uniqueid"];
                        $email = $row["email"];
                    }
                }
                //EmailとUniqueIDは合っているかどうか確認する
                if($verification == $_POST["uniqueid"] && $email == $_POST["email"]){
                    //一致すると、UniqueIDを「OK」に変える
                    $sql ="UPDATE GuestInfo SET uniqueid='ok' WHERE email= '$email'";
                    $pdo->exec($sql);

                    header('Location: /login.php');
                }else{
                    echo "<script type='text/javascript'>alert('メールや認証IDが間違っています！');</script>";
                }
            }
        }
        //データベースへ接続できない時にエラーを表示させる
        catch(PDOException $e){
            echo "Error: "."<br>". $e->getMessage();
            die();
        }
        $pdo = null;
    ?>
	<head>
		<title>日本留学中</title>
		<style type="text/css">
            body{
				margin-left: 15px;
				margin-top: 15;
				text-align: center;
			}
            
            .container{
                width: 550px;
				padding-top: 10px;
				border: 1px solid black;
				border-radius: 5px;
                text-align: center;
                margin-bottom: 10px;
                
            }
            .container input{
                width:300px;
                margin-top: -5px;
            }
		</style>
	</head>
	<body>
        <div class="container">
        <form method="post" id="singUp">
           <h3>アカウント認証</h3>
            <p>メール：</p>
            <input type="text" name="email" placeholder="xxx@xxx.com">
            <p>認証ID：</p>
            <input type="text" name="uniqueid" placeholder="認証ID">
            <br><br>
           <input type="submit" value="認証" onclick="return confirm('確認しましたか?');">
            <?php
                if(empty($_POST["uniqueid"])||empty($_POST["email"])){
                    echo "<h5>必須項目を記入してください！</h5>";
                }
            ?>
        </form>
        </div>
	</body>
</html>


















