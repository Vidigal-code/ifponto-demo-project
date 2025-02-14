<?php

require_once '../config/config.php';
require_once '../classes/Session.php';
require_once '../classes/User.php';
require_once '../classes/Language.php';

$session = new Session();

$session->checkLogin();

$user = new User();
$username = $_SESSION['username'];
$userId = $_SESSION['user_id'];

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['register_entry'])) {
        $response = $user->registerEntry($userId);
        $message = $response['success'] ? $response['message'] : $response['error'];
    }

    if (isset($_POST['register_exit'])) {
        $response = $user->registerExit($userId);
        $message = $response['success'] ? $response['message'] : $response['error'];
    }
}

$language = new Language();
$lang = $language->getLang();

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $language->setLanguage($lang);
    $_SESSION['lang'] = $lang;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
} elseif (isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang'];
}

?>

<!DOCTYPE html>
<html lang="<?= $lang == 'pt' ? 'pt-br' : 'en' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work History</title>
    <link rel="stylesheet" href="../styles/login-or-register.css">
</head>
<body>
<div class="form-container">
    <div class="language-switcher">
        <form action="" method="GET">
            <select name="lang" id="language-select" onchange="this.form.submit()">
                <option value="pt" <?= $lang == 'pt' ? 'selected' : ''; ?>>
                    <?= $language->get('nav-language')['pt']; ?>
                </option>
                <option value="en" <?= $lang == 'en' ? 'selected' : ''; ?>>
                    <?= $language->get('nav-language')['en']; ?>
                </option>
            </select>
        </form>
    </div>

    <h2><?= $language->get('page-text-workhistory'); ?></h2>

    <form method="POST" action="workhistory.php">
        <h3><?= $language->get('page-work-entry'); ?></h3>
        <button type="submit" name="register_entry"><?= $language->get('page-register-entry'); ?></button>
    </form>

    <br>

    <form method="POST" action="workhistory.php">
        <h3><?= $language->get('page-work-exit'); ?></h3>
        <button type="submit" name="register_exit"><?= $language->get('page-register-exit'); ?></button>
    </form>

    <p style="text-align: center; color: black;">
        <?= $message; ?>
    </p>

    <div class="login-link">
        <a href="/index.php" style="color: black;"><?= $language->get('page-input-back'); ?></a>
    </div>
</div>
</body>
</html>
