<?php

$session = new Session();
$language = new Language();
$lang = $language->getLang();

$session = new Session();

if (!$session->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user = new User();
$userAdmin = new UserAdmin();

$userId = $_SESSION['user_id'];

?>

<header>
    <div class="logo">
        <a href="index.php"><?= $language->get('website-name'); ?></a>
    </div>
    <nav>
        <ul>
            <?php if ($session->isLoggedIn()): ?>
                <li><a href="logout.php"><?= $language->get('logout'); ?></a></li>
                <li><a href="me/settings.php"><?= $language->get('page-text-settings'); ?></a></li>
                <li><a href="me/workhistory.php"><?= $language->get('page-text-workhistory'); ?></a></li>
                <?php if ($userAdmin->isAccountAnAdmin($userId)): ?>
                    <li><a href="me/panel.php"><?= $language->get('text-admin'); ?></a></li>
                <?php endif; ?>
            <?php else: ?>
                <li><a href="login.php"><?= $language->get('login'); ?></a></li>
                <li><a href="index.php"><?= $language->get('home'); ?></a></li>
                <li><a href="register.php"><?= $language->get('register'); ?></a></li>
            <?php endif; ?>
        </ul>
    </nav>

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

    <div class="hamburger" id="hamburger-menu">
        <span>&#9776;</span>
    </div>
</header>
</body>
<script>
    document.getElementById('hamburger-menu').onclick = function () {
        document.querySelector('nav').classList.toggle('active');
    }
</script>
</html>
