<?php

require_once 'config/config.php';
require_once 'classes/Session.php';
require_once 'classes/User.php';
require_once 'classes/Language.php';
include 'classes/Registration.php';

$session = new Session();


if (!isset($_SESSION['step'])) {
    $_SESSION['step'] = 1;
}

$language = new Language();
$lang = $language->getLang();

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $language->setLanguage($lang);
    $_SESSION['lang'] = $lang;
} elseif (isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang'];
}

$registration = new Registration();

$registration->process();
$error = $registration->getError();

?>

<!DOCTYPE html>
<html lang="<?= $lang == 'pt' ? 'pt-br' : 'en' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $language->get('register'); ?> - <?= $language->get('website-name'); ?></title>
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

    <h2><?= $language->get('register'); ?> - <?= $language->get('website-name'); ?></h2>

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

    <?php if ($_SESSION['step'] == 1): ?>
        <form method="POST" action="">
            <label><?= $language->get('page-input-username'); ?></label>
            <input type="text" name="username" value="<?= isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>"
                   required>

            <label><?= $language->get('page-input-email'); ?></label>
            <input type="email" name="email" value="<?= isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>"
                   required>

            <label>CPF</label>
            <input type="text" name="cpf" value="<?= isset($_SESSION['cpf']) ? $_SESSION['cpf'] : ''; ?>" required>

            <label>RG</label>
            <input type="text" name="rg" value="<?= isset($_SESSION['rg']) ? $_SESSION['rg'] : ''; ?>" required>

            <button type="submit" name="next"><?= $language->get('page-input-next'); ?></button>
        </form>
    <?php elseif ($_SESSION['step'] == 2): ?>
        <form method="POST" action="">
            <label><?= $language->get('page-input-password'); ?></label>
            <div style="position: relative;">
                <input type="password" name="password" id="password" required>
                <img src="../assets/icon/eye-closed.png" id="password-icon"
                     style="position: absolute; right: 10px; top: 17px; cursor: pointer; width: 20px; height: 20px"
                     onclick="togglePassword('password', 'password-icon')">
            </div>

            <label><?= $language->get('page-input-confirm-password'); ?></label>
            <div style="position: relative;">
                <input type="password" name="confirm-password" id="confirm-password" required>
                <img src="../assets/icon/eye-closed.png" id="confirm-password-icon"
                     style="position: absolute; right: 10px; top: 17px; cursor: pointer; width: 20px; height: 20px"
                     onclick="togglePassword('confirm-password', 'confirm-password-icon')">
            </div>


            <label><?= $language->get('page-input-role'); ?></label>
            <input type="text" name="role" value="<?= isset($_SESSION['role']) ? $_SESSION['role'] : 'user'; ?>"
                   required>

            <label><?= $language->get('page-input-business'); ?></label>
            <input type="text" name="business" value="<?= isset($_SESSION['business']) ? $_SESSION['business'] : ''; ?>"
                   required>

            <label><?= $language->get('page-input-salary'); ?></label>
            <input type="number" name="salary" step="0.01"
                   value="<?= isset($_SESSION['salary']) ? $_SESSION['salary'] : ''; ?>" required>

            <label><?= $language->get('page-input-work-entry'); ?></label>
            <input type="time" name="work_entry"
                   value="<?= isset($_SESSION['work_entry']) ? $_SESSION['work_entry'] : ''; ?>" required>

            <label><?= $language->get('page-input-work-exit'); ?></label>
            <input type="time" name="work_exit"
                   value="<?= isset($_SESSION['work_exit']) ? $_SESSION['work_exit'] : ''; ?>" required>

            <label><?= $language->get('page-input-period'); ?></label>
            <input type="text" name="period" value="<?= isset($_SESSION['period']) ? $_SESSION['period'] : ''; ?>"
                   required>

            <button type="submit" name="next"><?= $language->get('page-input-next'); ?></button>
            <button type="submit" name="back"><?= $language->get('page-input-back'); ?></button>
        </form>
    <?php elseif ($_SESSION['step'] == 3): ?>


        <p style="color: black;"><?= $language->get('page-text-confirm-your-details'); ?></p>
        <p style="color: black;"><?= $language->get('page-input-username'); ?> <?= $_SESSION['username']; ?></p>
        <p style="color: black;"><?= $language->get('page-input-email'); ?> <?= $_SESSION['email']; ?></p>
        <p style="color: black;"><?= $language->get('page-input-cpf'); ?> <?= $_SESSION['cpf']; ?></p>
        <p style="color: black;"><?= $language->get('page-input-rg'); ?> <?= $_SESSION['rg']; ?></p>
        <p style="color: black;"><?= $language->get('page-input-role'); ?> <?= $_SESSION['role']; ?></p>
        <p style="color: black;"><?= $language->get('page-input-business'); ?> <?= $_SESSION['business']; ?></p>
        <p style="color: black;"><?= $language->get('page-input-salary'); ?> <?= $_SESSION['salary']; ?></p>
        <p style="color: black;"><?= $language->get('page-input-work-entry'); ?> <?= $_SESSION['work_entry']; ?></p>
        <p style="color: black;"><?= $language->get('page-input-work-exit'); ?> <?= $_SESSION['work_exit']; ?></p>
        <p style="color: black;"><?= $language->get('page-input-period'); ?> <?= $_SESSION['period']; ?></p>

        <form method="POST" action="">
            <button type="submit" name="next"><?= $language->get('page-input-submit'); ?></button>
            <button type="submit" name="back"><?= $language->get('page-input-back'); ?></button>
        </form>

    <?php endif; ?>

    <p class="error-message"><?php echo $registration->getError(); ?></p>

    <div class="login-link">
        <p style="color: black; text-align: center"><?= $language->get('page-text-info-have-an-account'); ?> <a
                    href="login.php" style="color: black;"><?= $language->get('page-input-info-log-in'); ?></a></p>
    </div>

</div>
</body>
</html>
