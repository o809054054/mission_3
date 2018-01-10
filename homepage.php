
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
            
            //ログイン情報
            if(isset($_COOKIE["userid"]) && isset($_COOKIE["password"])){
                //認証が終わるかチェックする
                $sql = "SELECT * FROM GuestInfo";
                $results = $pdo->query($sql);
                foreach ($results as $row){
                    if($row["userid"] == $_COOKIE["userid"]){
                        $verification = $row["uniqueid"];
                    }
                }
                //認証が終わっていない場合、「認証してください」と表示させる
                if($verification == "ok"){
                    echo "<div id='welcome'><p>こんにちは、";
                    echo $_COOKIE["userid"];
                    echo "　さん。パスワードは";
                    echo $_COOKIE["password"];
                    echo "です。</p>";
                    echo "<form method='post'>クリック➜<input type='radio' name='logout' value='logout'>  <input type='submit' value='ログアウト'></form></div>";
                }else{

                    echo "<div id='welcome'><p>こんにちは、";
                    echo $_COOKIE["userid"];
                    echo "　さん。パスワードは";
                    echo $_COOKIE["password"];
                    echo "です。</p>";
                    echo "<h5>✰✰✰アカウント認証してください！✰✰✰</h5>";
                    echo "<form method='post'>クリック➜<input type='radio' name='logout' value='logout'>  <input type='submit' value='ログアウト'></form></div>";

                }  
            }else{
                //ログインしていないと、ログインページへ
                header('Location: /login.php');
            }
            
            //ログアウト機能
            if(!empty($_POST["logout"])){

                    setcookie("userid", "", time()-(3600));
                    setcookie("password", "", time()-(3600));
                    header('Location: /login.php');

            }
            
            //削除機能
            if(isset($_POST["submit2"])){
                    if (!empty($_POST["remove"]) && !empty($_POST["password_remove"])){
                        //保存したパスワードを取得する
                        $id = $_POST["remove"];
                        $sql = "SELECT * FROM MyGuests";
                        $results = $pdo->query($sql);
                        foreach($results as $row){
                            if($id == $row["id"]){
                                $password = $row["password"];
                            }         
                        }
                        //パスワードは合っているかどうか確認する
                        if($password != $_POST["password_remove"]){
                            echo "<script type='text/javascript'>alert('パスワードが間違っています');</script>";
                        }else{
                            //削除する
                            $sql = "DELETE FROM MyGuests WHERE id= '$id'";
                            $result = $pdo->query($sql);
                            header('Location: /homepage.php');
                        }
                    }
            }
            
            //編集機能
            if(!empty($_POST["edit"]) && !empty($_POST["password_edit"])){
                    //保存したパスワードを取得する
                    $id = $_POST["edit"];
                    $sql = "SELECT * FROM MyGuests";
                    $results = $pdo->query($sql);
                    foreach($results as $row){
                        if($id == $row["id"]){
                            $password = $row["password"];
                        }         
                    }
					//パスワードは合っているかどうか確認する
					if($password != $_POST["password_edit"]){
							echo "<script type='text/javascript'>alert('パスワードが間違っています');</script>";
					}else{
                        //それぞれの値を取得する
                        $sql = "SELECT * FROM MyGuests";
                        $results = $pdo->query($sql);
                        foreach($results as $row){
                            if($id == $row["id"]){
                                $username_edit=$row["username"];
                                $comment_edit=$row["comment"];
                                $password_edit=$row["password"];
                            }         
                        }
					}
				}
        }
        //データベースへ接続できない時にエラーを表示させる
        catch(PDOException $e){
            echo "Error: "."<br>". $e->getMessage();
            die();
        }
    ?>
	<head>
		<title>〜日本留学中〜</title>
		<style type="text/css">
            
			body{
				margin-left: 50px;
				margin-top: 15;
			}
			
            #welcome{
                
                width: 550px;
				padding-top: 10px;
				border: 1px solid black;
				border-radius: 5px;
                text-align: center;
                margin-bottom: 10px;
                
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
        
        <!--入力フォーム-->
        <div class="container">
		<form method="post" id="myForm" enctype="multipart/form-data">
			<h3>日本での留学生活について、今日の感想は?</h3>
			<p>ユーザーネーム: </p>
			<input type="text" name="username" placeholder="Username"
                <?php  
                    //編集モードなら配列値を表示させる
                    if(!empty($_POST["edit"]) && !empty($_POST["password_edit"])){
                                echo "value=$username_edit";
                    //自動でログイン中のユーザーの名前が入るようにする
                    }elseif(!empty($_COOKIE["userid"])){
                        echo "value=".$_COOKIE["userid"];
                    }
                ?>
			>
			
			<p>コメント:</p>
			<input type="text" name="comment" placeholder="Comment here"
                <?php
                        if(!empty($_POST["edit"]) && !empty($_POST["password_edit"])){
                            echo "value=$comment_edit"; 
                        }
                ?>
			>
            
			<p>パスワード:</p>
			<input type="text" name="password" placeholder="password"
                <?php
                        if(!empty($_POST["edit"]) && !empty($_POST["password_edit"])){
                            echo "value=$password_edit";
                        }
                ?>
			>
            <br>
            <br>
            <p><input type="file" name="fileToUpload" style="padding-left:70;"></p>
            <br>
			<?php  if(!empty($_POST["edit"]) && !empty($_POST["password_edit"])){echo "<input type='hidden' name='editConfirmation' value=".$_POST['edit'].">";}?>
			<input type="submit" name="submit" value="投稿" onclick="return confirm('確認しましたか?');">
			<?php
				//編集モードかどうか判別する
				if(empty($_POST["editConfirmation"])){
					if (empty($_POST["username"]) || empty($_POST["comment"]) || empty($_POST["password"])){
						echo '<h5>✰必須項目を記入してください✰<h5>';
					}else{
                        //データベースへの接続
                        $servername = "localhost";
                        $username = "co-779.it.99sv-c";
                        $password= "KuGs7j";
                        $dbname = "co_779_it_99sv_coco_com";
                        $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8";
                        $pdo= new PDO($dsn, $username, $password);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        //画像や動画があるかどうか確認する
                        if(!empty($_FILES["fileToUpload"]["name"])){
                            //画像や動画のアップロード機能
                            $target_dir = "uploads/";
                            $target_file = $target_dir.basename($_FILES["fileToUpload"]["name"]);
                            $uploadOk=1;
                            $fileType = pathinfo($target_file, PATHINFO_EXTENSION);

                            //ファイルはもうアップロードされたかどうか確認する
                            if(file_exists($target_file)){
                                echo "<p>ファイルはもうアップロードされました。</p>";
                                $uploadOk=0;
                            }
                            //ファイルのサイズを確認する
                            if($_FILES["fileToUpload"]["size"] > 1000000){
                                echo "<p>ファイルはサイズが大きすぎます</p>";
                                $uploadOk=0;
                            }
                            
                            if($uploadOk==0){
                                echo "<p>ファイルはアップロードできません。</p>";
                            }else{
                                //拡張子で画像か動画か判断する
                                //画像
                                if($fileType=="jpg"||$fileType=="png"||$fileType=="jpeg"||$fileType=="gif"){
                                    
                                    $sql = $pdo -> prepare("INSERT INTO MyGuests (username, comment, password, image) VALUES (:username, :comment, :password, :image)");
                                    $sql -> bindParam(':username',$username,PDO::PARAM_STR);
                                    $sql -> bindParam(':comment',$comment,PDO::PARAM_STR);
                                    $sql -> bindParam(':password',$password,PDO::PARAM_STR);
                                    $sql -> bindParam(':image',$image,PDO::PARAM_STR);
                                    $username = $_POST["username"];
                                    $comment = $_POST["comment"];
                                    $password = $_POST["password"];
                                    $image = $_FILES["fileToUpload"]["name"];
                                    $sql->execute();

                                    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                                    
                                    header('Location: /homepage.php');
                                //動画
                                }elseif($fileType=="mp4"){
                                    
                                    $sql = $pdo -> prepare("INSERT INTO MyGuests (username, comment, password, video) VALUES (:username, :comment, :password, :video)");
                                    $sql -> bindParam(':username',$username,PDO::PARAM_STR);
                                    $sql -> bindParam(':comment',$comment,PDO::PARAM_STR);
                                    $sql -> bindParam(':password',$password,PDO::PARAM_STR);
                                    $sql -> bindParam(':video',$video,PDO::PARAM_STR);
                                    $username = $_POST["username"];
                                    $comment = $_POST["comment"];
                                    $password = $_POST["password"];
                                    $video = $_FILES["fileToUpload"]["name"];
                                    $sql->execute();
                                    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
                                    
                                    header('Location: /homepage.php');

                                }else{
                                    echo "<p>ファイル形式はサポートされていません。</p>";
                                }
                            }
                        }else{
                            //ファイルなし
                            $sql = $pdo -> prepare("INSERT INTO MyGuests (username, comment, password) VALUES (:username, :comment, :password)");
                            $sql -> bindParam(':username',$username,PDO::PARAM_STR);
                            $sql -> bindParam(':comment',$comment,PDO::PARAM_STR);
                            $sql -> bindParam(':password',$password,PDO::PARAM_STR);
                            $username = $_POST["username"];
                            $comment = $_POST["comment"];
                            $password = $_POST["password"];
                            $sql->execute();
                            header('Location: /homepage.php');
                        }
                    }
                }else{
                    //編集機能
                    $id = $_POST["editConfirmation"];
                    $username = $_POST["username"];
                    $comment = $_POST["comment"];
                    $password = $_POST["password"];
                    
                    $sql ="UPDATE MyGuests SET username='$username', comment='$comment', password='$password' WHERE id='$id'";
                    $results = $pdo->query($sql);
                    
                    header('Location: /homepage.php');
                }
								
			?>
			
		</form>
		</div>
        
        <!--掲示板-->
		<div>
			<h3>コメント</h3>
			<?php
                //入力したデータをselectによって表示する
                $sql = "SELECT * FROM MyGuests ORDER BY id ASC";
                $results = $pdo->query($sql);
                foreach ($results as $row){
                    echo "<div class='container'>";
                    echo "【".$row["id"]."】".$row["username"]."、 ".$row["comment"]."、 ".$row["date"]."<br>";
                    if(!empty($row['image'])){echo "*img ".$row["id"]."*<br> <img src='uploads/".$row['image']."' width='240' height='240'>";}
                    if(!empty($row['video'])){echo "*video ".$row["id"]."*<br> <video width='360' height='240' controls><source src='uploads/".$row['video']."'>";}
                    echo "</div><br>"; 
                }
			?>
		</div>
		<br>
		<!--削除フォーム-->
        <div class="container">
		<form method="post"　id="removeForm">
			<p>削除したい内容がありますか?</p>
			<p>削除対象番号：</p><input name="remove" type="text" placeholder="number">
			<p>パスワード:</p><input type="text" name="password_remove" placeholder="Password">
            <br><br>
			<input type="submit" name="submit2" value="削除" onclick="return confirm('確認しましたか?');">
		</form>
		</div>
        
		<!--編集フォーム-->
        <div class="container">
		<form method="post" id="editForm">
			<p>編集したい内容がありますか?</p>
			<p>編集対象番号：</p><input name="edit" type="text" placeholder="number">
			<p>パスワード:</p><input type="text" name="password_edit" placeholder="password">
            <br><br>
			<input type="submit" name="submit3" value="編集">
		</form>
        </div>
	</body>
</html>