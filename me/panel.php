<?php

require_once '../config/config.php';
require_once '../classes/Session.php';
require_once '../classes/User.php';
require_once '../classes/Language.php';
require_once '../classes/UserAdmin.php';

$session = new Session();
$session->checkLogin();

$user = new User();
$userAdmin = new UserAdmin();

$username = $_SESSION['username'];
$userId = $_SESSION['user_id'];

if (!$userAdmin->isAccountAnAdmin($userId)) {
    header("Location: ../index.php");
}

if (!$user->isAccountApprovedAccount($userId)) {
    header("Location: ../verifyaccount.php");
}

//$userData = $user->getUserData($userId);
//$business = htmlspecialchars($userData['business']);

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

$message = '';


$users = [];
$logsEntryBusiness = [];
$logsExitBusiness = [];

$logsEntry = [];
$logsExit = [];


if (isset($_POST['search_name_or_email'])) {
    $searchTerm = $_POST['search_name_or_email'];
    $users = $userAdmin->searchUsersByNameOrEmail($searchTerm);
}

if (isset($_POST['search_business'])) {
    $businessSearch = $_POST['search_business'];
    $logsEntryBusiness = $userAdmin->getUsersWithWorkEntryBusiness($businessSearch);
    $logsExitBusiness = $userAdmin->getUsersWithWorkExit($businessSearch);
}


/*
if (empty($users)) {
    $users = $user->getUsers();
}*/



if (isset($_POST['delete_user'])) {
    list($cpf, $rg) = explode(',', $_POST['delete_user']);

    $result = $userAdmin->deleteUserByCpfandRG($cpf, $rg);

    if ($result) {
        $message = "User deleted successfully!";
        $users = $userAdmin->getUsers();
    } else {
        $message = "An error occurred while deleting the user.";
    }
}




if (isset($_POST['user-management-sql'])) {
    $sql = $_POST['sql_query'];
    $result = $userAdmin->executeSQLQuery($sql);
    if ($result === true) {
        $message = "User SQL executed successfully!";
        $users = $userAdmin->getUsers();
    } else {
        $message = "Error: " . $result . "";
    }
}

if (isset($_POST['work-management-sql'])) {
    $sql = $_POST['sql_query'];
    $result = $userAdmin->executeSQLQuery($sql);
    if ($result === true) {
        $message = "Work SQL executed successfully!";
        $logsEntry = $userAdmin->getUsersWithWorkEntryAll();
        $logsExit = $userAdmin->getUsersWithWorkExitAll();
    } else {
        $message = "Error: " . $result . "";
    }
}

if (isset($_POST['user-management'])) {
    $users = $userAdmin->getUsers();
}

if (isset($_POST['work-management'])) {
    $logsEntry = $userAdmin->getUsersWithWorkEntryAll();
}

if (isset($_POST['work-management'])) {
    $logsExit = $userAdmin->getUsersWithWorkExitAll();
}


?>

<!DOCTYPE html>
<html lang="<?= $lang == 'pt' ? 'pt-br' : 'en' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $language->get('text-admin'); ?> - Panel</title>
    <link rel="stylesheet" href="../styles/panel.css">
</head>
<body>

<div class="form-container">

    <h1><?= $language->get('text-admin'); ?> - <?= $language->get('page-panel-text-title'); ?></h1>

    <div style="display: flex; justify-content: center; align-items: center; gap: 20px;">
        <form action="" method="POST" style="margin: 0;">
            <input type="text" name="search_name_or_email" placeholder="<?= $language->get('page-panel-text-search-by-name-or-email'); ?>">
            <button type="submit"><?= $language->get('page-panel-text-search'); ?></button>
        </form>

        <form action="" method="POST" style="margin: 0;">
            <input type="text" name="search_business" placeholder="<?= $language->get('page-panel-text-search-by-business'); ?>">
            <button type="submit"><?= $language->get('page-panel-text-search'); ?></button>
        </form>

        <div class="language-switcher" style="margin: 0;">
            <form action="" method="GET" style="margin: 0;">
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
    </div>

    <?php if (!empty($users)): ?>
        <form action="" method="POST">
            <table id="userTable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th><?= $language->get('page-input-username'); ?></th>
                    <th><?= $language->get('page-input-email'); ?></th>
                    <th><?= $language->get('page-input-cpf'); ?></th>
                    <th><?= $language->get('page-input-rg'); ?></th>
                    <th><?= $language->get('page-input-business'); ?></th>
                    <th><?= $language->get('page-input-salary'); ?></th>
                    <th><?= $language->get('page-input-period'); ?></th>
                    <th><?= $language->get('page-input-work-entry'); ?></th>
                    <th><?= $language->get('page-input-work-exit'); ?></th>
                    <th>Account</th>
                    <th>Admin</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['username'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['email'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['cpf'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['rg'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['business'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['salary'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['period'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['work_entry'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['work_exit'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['approved_account'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['is_an_admin'] ?? ''); ?></td>
                        <td>
                            <form action="" method="POST">
                                <button type="submit" name="delete_user"
                                        value="<?= htmlspecialchars($user['cpf'] ?? '') . ',' . htmlspecialchars($user['rg'] ?? ''); ?>">Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    <?php else: ?>
        <!-- No entries found -->
    <?php endif; ?>

    <?php if (!empty($logsEntryBusiness)): ?>
        <form action="" method="POST">
            <h3><?= $language->get('page-text-work-logs-entry'); ?></h3>
            <table id="userTable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th><?= $language->get('page-input-username'); ?></th>
                    <th><?= $language->get('page-input-email'); ?></th>
                    <th><?= $language->get('page-input-cpf'); ?></th>
                    <th><?= $language->get('page-input-rg'); ?></th>
                    <th><?= $language->get('page-input-business'); ?></th>
                    <th><?= $language->get('page-input-salary'); ?></th>
                    <th><?= $language->get('page-input-period'); ?></th>
                    <th><?= $language->get('page-input-work-entry'); ?></th>
                    <th><?= $language->get('page-text-status'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($logsEntryBusiness as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['username'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['email'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['cpf'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['rg'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['business'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['salary'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['period'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['work_entry'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['status'] ?? ''); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    <?php else: ?>
        <!-- No entries found -->
    <?php endif; ?>

    <?php if (!empty($logsExitBusiness)): ?>
        <form action="" method="POST">
            <h3><?= $language->get('page-text-work-logs-entry'); ?></h3>
            <table id="userTable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th><?= $language->get('page-input-username'); ?></th>
                    <th><?= $language->get('page-input-email'); ?></th>
                    <th><?= $language->get('page-input-cpf'); ?></th>
                    <th><?= $language->get('page-input-rg'); ?></th>
                    <th><?= $language->get('page-input-business'); ?></th>
                    <th><?= $language->get('page-input-salary'); ?></th>
                    <th><?= $language->get('page-input-period'); ?></th>
                    <th><?= $language->get('page-input-work-exit'); ?></th>
                    <th><?= $language->get('page-text-status'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($logsExitBusiness as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['username'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['email'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['cpf'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['rg'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['business'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['salary'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['period'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['work_exit'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['status'] ?? ''); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    <?php else: ?>
        <!-- No entries found -->
    <?php endif; ?>


    <?php if (!empty($logsEntry)): ?>
        <form action="" method="POST">
            <h3><?= $language->get('page-text-work-logs-entry'); ?></h3>
            <table id="userTable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th><?= $language->get('page-input-username'); ?></th>
                    <th><?= $language->get('page-input-email'); ?></th>
                    <th><?= $language->get('page-input-cpf'); ?></th>
                    <th><?= $language->get('page-input-rg'); ?></th>
                    <th><?= $language->get('page-input-business'); ?></th>
                    <th><?= $language->get('page-input-salary'); ?></th>
                    <th><?= $language->get('page-input-period'); ?></th>
                    <th><?= $language->get('page-input-work-entry'); ?></th>
                    <th><?= $language->get('page-text-status'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($logsEntry as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['username'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['email'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['cpf'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['rg'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['business'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['salary'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['period'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['work_entry'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['status'] ?? ''); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    <?php else: ?>
        <!-- No entries found -->
    <?php endif; ?>

    <?php if (!empty($logsExit)): ?>
        <form action="" method="POST">
            <h3><?= $language->get('page-text-work-logs-exit'); ?></h3>
            <table id="userTable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th><?= $language->get('page-input-username'); ?></th>
                    <th><?= $language->get('page-input-email'); ?></th>
                    <th><?= $language->get('page-input-cpf'); ?></th>
                    <th><?= $language->get('page-input-rg'); ?></th>
                    <th><?= $language->get('page-input-business'); ?></th>
                    <th><?= $language->get('page-input-salary'); ?></th>
                    <th><?= $language->get('page-input-period'); ?></th>
                    <th><?= $language->get('page-input-work-exit'); ?></th>
                    <th><?= $language->get('page-text-status'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($logsExit as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['username'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['email'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['cpf'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['rg'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['business'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['salary'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['period'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['work_exit'] ?? ''); ?></td>
                        <td><?= htmlspecialchars($user['status'] ?? ''); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    <?php else: ?>
        <!-- No entries found -->
    <?php endif; ?>


    <form action="" method="POST" class="sql-form">
        <h2><?= $language->get('page-panel-code-sql-title'); ?></h2>
        <textarea name="sql_query" rows="6" cols="50" placeholder="<?= $language->get('page-panel-code-sql-input-placeholder'); ?>"></textarea>
        <div class="button-group">
            <button type="submit" name="user-management-sql"><?= $language->get('page-panel-text-user-management-sql'); ?></button>
            <button type="submit" name="user-management"><?= $language->get('page-panel-text-user-management'); ?></button>
            <button type="submit" name="work-management"><?= $language->get('page-panel-text-work-management'); ?></button>
            <button type="submit" name="work-management-sql"><?= $language->get('page-panel-text-work-management-sql'); ?></button>
            <a href="/index.php" class="button-link">
                <?= $language->get('page-input-back'); ?>
            </a>

            <style>
                .button-link {
                    display: inline-block;
                    text-decoration: none;
                    padding: 10px 20px;
                    background-color: #00AFAB;
                    color: white;
                    border-radius: 5px;
                    text-align: center;
                    transition: background-color 0.3s ease;
                }

                .button-link:hover {
                    background-color: #45a049;
                }
            </style>


        </div>
    </form>

    <p class="message"><?= $message; ?></p>
</div>

</body>
</html>
