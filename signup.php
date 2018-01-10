
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
            
            if(!empty($_POST["userid"]) && !empty($_POST["password"]) && !empty($_POST["email"])){
                //同じユーザーネーム、メールが使われているかをチェック
                $sql = "SELECT * FROM GuestInfo";
                $results = $pdo->query($sql);
                foreach ($results as $row){
                    if($row["userid"] == $_POST["userid"]){
                        $userid_repeat = $row["userid"];
                    }
                    if($row["email"] == $_POST["email"]){
                        $email_repeat = $row["email"];
                    }
                }
                
                if($userid_repeat == $_POST["userid"]){
                    echo "<div class='container'><p>この名前はすでに使われています。他の名前を使ってください!</p></div>";
                }elseif($email_repeat == $_POST["email"]){
                    echo "<div class='container'><p>このメールはもう登録されています。</p></div>";
                }else{
                    $sql = $pdo -> prepare("INSERT INTO GuestInfo (userid, password, email, uniqueid) VALUES (:userid, :password, :email, :uniqueid)");
                    $sql -> bindParam(':userid', $userid, PDO::PARAM_STR);
                    $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                    $sql -> bindParam(':email', $email, PDO::PARAM_STR);
                    $sql -> bindParam(':uniqueid', $uniqueid, PDO::PARAM_STR);
                    $userid = $_POST["userid"];
                    $password = $_POST["password"];
                    $email = $_POST["email"];
                    $uniqueid = uniqid();
                    $sql->execute();
                    
                    //認証メールを送る
                    $to = $_POST["email"];
                    $subject = "アカウントを認証してください！";
                    $message = "
                        ご登録してありがとうございました！
                
                        ユーザーネーム： $userid
                        パスワード： $password
                        
                        認証ID： $uniqueid

                        こちらのリンクをクリックして、アカウントを認証してください！
                        http://データベース名/account_verification.php";  //認証ページへ
                    $headers = "From:noreply@ryugakusei.com"."\r\n";
                    mail($to,$subject, $message,$headers);
                    
                    //ログインの状態を保持する
                    setcookie("userid", $_POST["userid"], time()+(86400));
                    setcookie("password", $_POST["password"], time()+(86400));
                    
                    header('Location: /homepage.php');
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
           <h3>会員登録</h3>
           <p>ユーザーネーム：</p>
            <input type="text" name="userid" placeholder="名前">
           <p>パスワード：</p>
            <input type="text" name="password" placeholder="パスワード">
           <p>メール：</p>
            <input type="text" name="email" placeholder="xxx@xxx.com">
            <br><br>
           <input type="submit" value="登録">
        
        <?php
            if(empty($_POST["userid"]) || empty($_POST["password"])||empty($_POST["email"])){
				echo "<h5>必須項目を記入してください！</h5>";
            }
        ?>
           <p>✰ログインは<a href="login.php">こちら</a>✰</p>
        </form>
        </div>
	</body>
</html>


















