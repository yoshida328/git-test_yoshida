<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Git・PHP・SQL テスト課題</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/each.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
</head>

<body>
    <?php
    session_start(); // セッションの開始

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // POSTされたデータを取得
        $to = $_POST['to'];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $message = $_POST["message"];

        // フォームからの選択された値に応じて subject を設定
        if ($to !== 'その他') {
        $subject = $to;
        } else {
        $subject = $_POST['subject'];
        }

        // バリデーション
        if (empty($name) || empty($email) || empty($message)) {
            echo "全ての項目を入力してください。";
        } else {
            $host = 'localhost';
            $dbname = 'git-test';
            $username = 'root';
            $password = '';

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error: " . $e->getMessage());
            }

            $sql = "INSERT INTO comments (name, email, message, subject, created_at) 
            VALUES (:name, :email, :message, :subject, NOW())";

            try {
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':message', $message, PDO::PARAM_STR);
                $stmt->bindParam(':subject', $subject, PDO::PARAM_STR); // 宛先をバインド

                $stmt->execute();
                echo "データが正常に挿入されました。";

                // データベースへの挿入が完了したので、セッションからform_submittedを削除
                unset($_SESSION['form_submitted']);
            } catch (PDOException $e) {
                die("Error: " . $e->getMessage());
            }
        }
    }
    ?>



    <header>

    </header>



    <h1 class="sitename titles"> Git・PHP・SQL テスト課題</h1>


    <h2 class="headingL">プロフィール</h2>
    </section><!-- このサイトについてここまで -->
    <section class="profile bg-white">
        <!-- プロフィール -->
        <h3 class="headingM">Profile</h3>
        <div class="profile__inner grid">

            <div class="profile__img"><img src="images/image0.jpeg" alt=""></div>
            <!-- プロフィール文章 -->
            <div class="profile__text">
                <h2>基本情報</h2>
                <p>名前: 吉田 光希</p>
                <p>年齢: 27歳</p>
                <p>性別: 男性</p>
                <p>出身: 奈良県</p>
                <h2 class="headingL">自己紹介</h2>
                <section class="aboutsite bg-white">
                    <h3 class="headingM">簡単な自分解説</h3>
                    <p>1996年奈良県で生まれる</p>
                    <p>5歳からサッカーを始め15年ぐらい続ける</p>
                    <p>社会人になり大阪・滋賀・岐阜・埼玉に住む（短期間なら他にも）今は大阪</p>
                    <p>直近ではフォークリフトメーカーで営業2年半</p>
                    <p>2023年12月よりプログラミング訓練校入校←6月まで予定</p>
                    <p>就職は東京予定</p>
                </section>
                



            </div><!-- プロフィール文章ここまで -->
        </div>
    </section><!-- プロフィールここまで -->
    </section>
    <section class="profile bg-white">
                    <h2>自己紹介</h2>
                    <div class="profile__img"><img src="./images/bastet.png" alt="My Photo" width="400"></div>
                    <p>
                        はじめまして、私は藤澤と申します。日々、家庭と仕事をバランスよくこなしながら、充実した生活を送っています。

                        趣味の一つは動画鑑賞です。映画やドラマ、YouTubeなど、様々なジャンルの動画を楽しんでいます。特に、感動的なストーリーや興味深いドキュメンタリーに心惹かれます。また、新しい作品を見つけるために、時には映画館に足を運ぶこともあります。

                        日常生活では、仕事に情熱を注ぎながらも、家族との時間を大切に過ごしています。夫とはお互いの支え合いながら、幸せな家庭を築いています。

                        これからも、自分自身を成長させながら、家族や仲間と共に笑顔あふれる日々を過ごしていきたいと考えています。よろしくお願いします。
                    </p>
    </section>
    




    <!-- コンタクト -->
    <section id="contact">
        <h2 class="headingL">お問い合わせフォーム</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="mailform">
            <label for="to">宛先：</label>
            <select id="to" name="to" required>
                <option value="">宛先を選択してください</option>
                <option value="吉田さん">吉田さん</option>
                <option value="藤澤">藤澤</option>
                <option value="その他">その他</option>
            </select><br>
            <input id="name" type="text" name="name" placeholder="お名前" required><br>
            <input id="mail" type="email" name="email" placeholder="メールアドレス" required><br>
            <textarea id="message" name="message" placeholder="メッセージ" required></textarea><br>
            <button type="submit" name="submit" class="btn">送信</button>
        </form>
    </section>

    <section id="comments">
        <h2 class="headingL">今日もらったコメント</h2>

        <?php
        // データベース接続情報
        $host = 'localhost';
        $dbname = 'git-test';
        $username = 'root';
        $password = '';

        try {
            // PDOオブジェクトの作成
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // データベースからVisibleのデータを取得
            $query = "SELECT name, message FROM comments";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $visibleContacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // データベース接続を切断
            $pdo = null;
        } catch (PDOException $e) {
            echo "データベースエラー: " . $e->getMessage();
            $visibleContacts = []; // エラーの場合は空の配列を代入
        }
        ?>
        <section class="bg-white">
            <ul>
                <?php foreach ($visibleContacts ?? [] as $contact) : ?>
                    <li>
                        <strong><?= $contact['name'] ?>:</strong> <?= $contact['message'] ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </section>



    </main>


    <footer>

    </footer>

    <?php
    // データベース接続を切断
    $pdo = null;
    ?>
</body>

</html>