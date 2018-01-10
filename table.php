 <?php
    //データベースへの接続
    $servername = "ホスト";
    $username = "ユーザー名";
    $password= "パスワード";
    $dbname = "データベース名";
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //テーブルGuestInfoはアカウントを保存しているもの
    $sql = "CREATE TABLE GuestInfo(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        userid VARCHAR(30) NOT NULL,
        password VARCHAR(30) NOT NULL,
        date TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "TABLE GuestInfo created successfully";

    $sql = "ALTER TABLE GuestInfo 
    ADD email VARCHAR(255),
    ADD uniqueid VARCHAR(255)";
    $result = $pdo->query($sql);

    //テーブルMyGuestsは投稿した内容を保存しているもの
    $sql = "CREATE TABLE MyGuests(

        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30) NOT NULL,
        comment VARCHAR(255) NOT NULL,
        password VARCHAR(30) NOT NULL,
        date TIMESTAMP

    )";
    $pdo->exec($sql);
    
    $sql = "ALTER TABLE MyGuests 
            ADD image VARCHAR(255),
            ADD video VARCHAR(255)";
    $result = $pdo->query($sql);
?>