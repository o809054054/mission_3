
<html>
    <?php
        header("Content-Type: text/html; charset = UTF-8");
        
        //ログインしている状態なら、掲示板に移る
        if(isset($_COOKIE["userid"]) && isset($_COOKIE["password"])){
            header('Location: /homepage.php');
        }

        try{
            //データベースへの接続
            $servername = "ホスト";
            $username = "ユーザー名";
            $password= "パスワード";
            $dbname = "データベース名";
            $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8";
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            if(!empty($_POST["userid"]) && !empty($_POST["password"])){
                //保存したユーザーネーム、パスワード、認証を取得する
                $sql = "SELECT * FROM GuestInfo";
                $results = $pdo->query($sql);
                foreach ($results as $row){
                    if($row["userid"] == $_POST["userid"]){
                        $userid = $row["userid"];
                        $password = $row["password"];
                        $verification = $row["uniqueid"];
                    }
                }
                
                //ログイン機能
                if($userid != $_POST["userid"] || $password != $_POST["password"]){
                    echo "<p>名前やパスワードが間違っています</p>";
                }else{
                    //本登録状態のもののみログイン可能
                    if($verification == "ok"){
                        //ログインの状態を保持する
                        setcookie("userid", $_POST["userid"], time()+(86400));
                        setcookie("password", $_POST["password"], time()+(86400));
                        
                        header('Location: /homepage.php');
                    }else{
                        echo "<script type='text/javascript'>alert('アカウント認証してください！');</script>";
                    }
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
        <form method="post" id="logIn">
           <h3>ログイン</h3>
           <p>名前：</p>
               <input type="text" name="userid" placeholder="名前">
           <p>パスワード：</p>
               <input type="text" name="password" placeholder="パスワード">
            <br><br>
           <input type="submit" value="ログイン">
        
        <?php
            if(empty($_POST["userid"]) || empty($_POST["password"])){
				echo "<h5>✰必須項目を記入してください✰</h5>";
            }
        ?>
            <p>✰登録は<a href="signup.php">こちら</a>✰</p>
        </form>
        </div>
	</body>
</html>


















