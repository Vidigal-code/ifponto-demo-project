<?php

$session = new Session();

if (!$session->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user = new User();
$userAdmin = new UserAdmin();


$userId = $_SESSION['user_id'];



if (!$user->isAccountApprovedAccount($userId)) {
    header("Location: verifyaccount.php");
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
$work_entry = htmlspecialchars($userData['work_entry']);
$work_exit = htmlspecialchars($userData['work_exit']);


$logsEntry = $user->getWorkLogsByUserEntry($userId);
$logsExit = $user->getWorkLogsByUserExit($userId);


?>

<p><?= $language->get('welcome_message'); ?> <?php echo $username; ?></p>

<p><?= $language->get('page-input-email'); ?>   <?php echo $email; ?>!</p>

<p><?= $language->get('page-input-business'); ?> <?php echo $business; ?></p>


<p><?= $language->get('page-input-role'); ?>   <?php echo $role; ?>!</p>

<p><?= $language->get('page-input-work-entry'); ?> <?php echo date('H:i', strtotime($userData['work_entry'])); ?></p>

<p><?= $language->get('page-input-work-exit'); ?> <?php echo date('H:i', strtotime($userData['work_exit'])); ?></p>


<?php if ($salary): ?>
    <p><?= $language->get('page-input-salary'); ?> <?php echo $salary; ?></p>
<?php else: ?>
    <p style="color: black;"><?= $language->get('page-input-not-salary'); ?></p>
<?php endif; ?>

<a href="me/logout.php" class="btn-logout"
   style="display: inline-block; margin-right: 10px;"><?= $language->get('text-logout'); ?></a>
<p>

    <?php if ($userAdmin->isAccountAnAdmin($userId)): ?>
    <a href="me/panel.php" class="btn-action"
       style="display: inline-block; margin-right: 10px;"><?= $language->get('text-admin'); ?></a>
<p>
    <?php endif; ?>
    <a href="me/settings.php" class="btn-action"
       style="display: inline-block;"><?= $language->get('page-text-settings'); ?></a>
    <a href="me/workhistory.php" class="btn-action"
       style="display: inline-block;"><?= $language->get('page-text-workhistory'); ?></a>
</p>

<h3><?= $language->get('page-text-work-logs-entry'); ?></h3>
<table border="1" cellpadding="10" cellspacing="0">
    <thead>
    <tr>
        <th><?= $language->get('page-text-work-entry'); ?></th>
        <th><?= $language->get('page-text-status'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php if ($logsEntry): ?>
        <?php foreach ($logsEntry as $log): ?>
            <tr>
                <td><?= (new DateTime($log['work_entry']))->format('d/m/Y H:i:s'); ?></td>
                <td><?= htmlspecialchars($log['status']); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="2"><?= $language->get('page-text-not-logs-entry'); ?></td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<h3><?= $language->get('page-text-work-logs-exit'); ?></h3>
<table border="1" cellpadding="10" cellspacing="0">
    <thead>
    <tr>
        <th><?= $language->get('page-text-work-exit'); ?></th>
        <th><?= $language->get('page-text-status'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php if ($logsExit): ?>
        <?php foreach ($logsExit as $log): ?>
            <tr>
                <td><?= (new DateTime($log['work_exit']))->format('d/m/Y H:i:s'); ?></td>
                <td><?= htmlspecialchars($log['status']); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="2"><?= $language->get('page-text-not-logs-exit'); ?></td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>



