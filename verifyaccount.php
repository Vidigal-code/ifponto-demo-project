<?php

require_once 'config/config.php';
require_once 'classes/Session.php';
require_once 'classes/User.php';

require_once 'classes/Language.php';

$session = new Session();

if (!$session->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user = new User();
$userAdmin = new UserAdmin();

$userId = $_SESSION['user_id'];


if ($userAdmin->isAccountApprovedAccount($userId)) {
    header("Location: index.php");
}

$userData = $user->getUserData($userId);

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

if (!$userData) {
    header("Location: login.php");
    exit();
}

$username = htmlspecialchars($userData['username']);
$email = htmlspecialchars($userData['email']);
$role = htmlspecialchars($userData['role']);
$business = htmlspecialchars($userData['business']);
$salary = htmlspecialchars($userData['salary']);


?>

<!DOCTYPE html>
<html lang="<?= $lang == 'pt' ? 'pt-br' : 'en' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $language->get('login'); ?> - <?= $language->get('website-name'); ?></title>
    <link rel="stylesheet" href="styles/login-or-register.css">
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

    <p style="color: black;">
        <?= $language->get('text-content-verify-account'); ?>
    </p>
    <p><a href="me/logout.php" class="btn-logout"><?= $language->get('text-logout'); ?></a></p>
</div>
</body>
</html>