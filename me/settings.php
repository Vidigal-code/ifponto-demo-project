<?php


require_once '../config/config.php';
require_once '../classes/Session.php';
require_once '../classes/User.php';
require_once '../classes/Language.php';

$session = new Session();


$session->checkLogin();

$user = new User();
$username = $_SESSION['username'];

$message = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}|:"<>?]).{8,}$/';

    if ($password !== $confirmPassword) {
        $message = "The passwords do not match. Please try again.";
    } elseif (!preg_match($passwordRegex, $password)) {
        $message = "Password must be at least 8 characters long and include at least one lowercase letter, one uppercase letter, one number, and one special character.";
    } else {

        $response = $user->updateSettings($username, $email, $password);

        if ($response['success']) {
            $message = "Settings updated successfully!";
            header('Location: ../index.php');
            exit();
        } else {
            $message = "Error updating settings: " . htmlspecialchars($response['error']);
        }
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
    <title>Configurações</title>
    <link rel="stylesheet" href="../styles/login-or-register.css">
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

    <h2><?= $language->get('page-update-email-password-settings'); ?></h2>
    <form method="POST" action="settings.php">
        <label for="email"><?= $language->get('page-update-settings-new-email'); ?></label>
        <input type="email" name="email" id="email" value="" required><br><br>

        <label><?= $language->get('page-update-settings-new-password'); ?></label>
        <div style="position: relative;">
            <input type="password" name="password" id="password" required>
            <img src="../assets/icon/eye-closed.png" id="password-icon"
                 style="position: absolute; right: 10px; top: 17px; cursor: pointer; width: 20px; height: 20px"
                 onclick="togglePassword('password', 'password-icon')">
        </div>

        <label><?= $language->get('page-input-confirm-password'); ?></label>
        <div style="position: relative;">
            <input type="password" name="confirm_password" id="confirm-password" required>
            <img src="../assets/icon/eye-closed.png" id="confirm-password-icon"
                 style="position: absolute; right: 10px; top: 17px; cursor: pointer; width: 20px; height: 20px"
                 onclick="togglePassword('confirm-password', 'confirm-password-icon')">
        </div>

        <button type="submit"><?= $language->get('page-update-settings-text'); ?></button>
        <div class="login-link">
            <a href="/index.php" style="color: black;"><?= $language->get('page-input-back'); ?></a>
        </div>

    </form>
    <p style="text-align: center; color: black;"><?php echo $message; ?></p>
</div>
</body>
</html>
