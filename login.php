<?php

require_once 'config/config.php';
require_once 'classes/Session.php';
require_once 'classes/User.php';

require_once 'classes/Language.php';

$language = new Language();

$lang = $language->getLang();

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $language->setLanguage($lang);
    $_SESSION['lang'] = $lang;
} elseif (isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang'];
}


$session = new Session();

if ($session->isLoggedIn()) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User();
    $loggedInUser = $user->login($email, $password);

    if ($loggedInUser) {
        $_SESSION['user_id'] = $loggedInUser['id'];
        $_SESSION['username'] = $loggedInUser['username'];
        $session->regenerate();
        header("Location: index.php");
        exit();
    } else {
        $error = $language->get('error-login-text');
    }
}


?>

<!DOCTYPE html>
<html lang="<?= $lang == 'pt' ? 'pt-br' : 'en' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $language->get('login'); ?> - <?= $language->get('website-name'); ?></title>
    <link rel="stylesheet" href="styles/login-or-register.css">
    <script>
        function togglePassword(fieldId, iconId) {
            var passwordField = document.getElementById(fieldId);
            var passwordIcon = document.getElementById(iconId);

            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordIcon.src = "../assets/icon/eye-visible.png";
            } else {
                passwordField.type = "password";
                passwordIcon.src = "../assets/icon/eye-closed.png";
            }
        }

    </script>
</head>
<body>
<div class="form-container">
    <h2><?= $language->get('login'); ?> - <?= $language->get('website-name'); ?></h2>

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

    <form method="POST" action="">
        <label><?= $language->get('page-input-email'); ?></label>
        <input type="email" name="email" required>

        <label><?= $language->get('page-input-password'); ?></label>
        <div style="position: relative;">
            <input type="password" name="password" id="password" required>
            <img src="../assets/icon/eye-closed.png" id="password-icon"
                 style="position: absolute; right: 10px; top: 17px; cursor: pointer; width: 20px; height: 20px"
                 onclick="togglePassword('password', 'password-icon')">
        </div>

        <button type="submit"><?= $language->get('login'); ?></button>
    </form>

    <?php
    if (isset($error)) {
        echo "<p class='error-message'>$error</p>";
    }
    ?>


    <div class="login-link">
        <p style="color: black; text-align: center"><?= $language->get('page-text-info-not-have-an-account'); ?> <a
                    href="register.php" style="color: black;"><?= $language->get('page-text-info-register-here'); ?></a>
        </p>
    </div>
</div>
</body>
</html>



