<?php

require_once 'config/config.php';
require_once 'classes/Session.php';
require_once 'classes/User.php';
require_once 'classes/Language.php';
require_once 'classes/UserAdmin.php';

include 'me/header.php';

$session = new Session();
$userAdmin = new UserAdmin();


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
    <title><?= $language->get('home'); ?> - <?= $language->get('website-name'); ?></title>
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>
<div class="container">

    <?php if ($session->isLoggedIn()): ?>
        <?php include 'me/home.php'; ?>
    <?php else: ?>
        <h2><?= $language->get('title-index'); ?></h2>
        <p>
            <?= $language->get('text-content'); ?>
        </p>
        <a href="login.php" class="btn-action"><?= $language->get('text-log-in'); ?></a>
        <a href="register.php" class="btn-action"><?= $language->get('text-register'); ?></a>
    <?php endif; ?>
</div>

<?php include 'me/footer.php'; ?>
</body>
</html>
