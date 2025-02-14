<?php

require_once 'Database.php';
require_once 'exeception/MessageException.php';


class User
{
    protected $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function register($username, $email, $password, $cpf, $rg, $role = 'user', $business = null, $is_an_admin = false, $salary = null, $work_entry = null, $work_exit = null, $period = null)
    {
        try {

            $checkSql = "SELECT id, email, cpf, rg FROM users WHERE email = :email OR cpf = :cpf OR rg = :rg";
            $checkStmt = $this->db->getConnection()->prepare($checkSql);

            $checkStmt->bindParam(':email', $email);
            $checkStmt->bindParam(':cpf', $cpf);
            $checkStmt->bindParam(':rg', $rg);

            $checkStmt->execute();

            $existingUser = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                if ($existingUser['email'] === $email) {
                    throw new MessageException("ERROR_EMAIL_ALREADY_REGISTERED");
                }
                if ($existingUser['cpf'] === $cpf) {
                    throw new MessageException("ERROR_CPF_ALREADY_REGISTERED");
                }
                if ($existingUser['rg'] === $rg) {
                    throw new MessageException("ERROR_RG_ALREADY_REGISTERED");
                }
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            if ($work_entry) {
                $work_entry = date('Y-m-d H:i:s', strtotime('today') + strtotime($work_entry) - strtotime('00:00'));
            }
            if ($work_exit) {
                $work_exit = date('Y-m-d H:i:s', strtotime('today') + strtotime($work_exit) - strtotime('00:00'));
            }

            if ($salary === '' || $salary === null) {
                $salary = null;
            }

            $sql = "INSERT INTO users (username, email, password, role, business, is_an_admin, salary, cpf, rg, work_entry, work_exit, period) 
                    VALUES (:username, :email, :password, :role, :business, :is_an_admin, :salary, :cpf, :rg, :work_entry, :work_exit, :period)";
            $stmt = $this->db->getConnection()->prepare($sql);

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':business', $business);
            $stmt->bindParam(':is_an_admin', $is_an_admin, PDO::PARAM_BOOL);
            $stmt->bindParam(':salary', $salary);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':rg', $rg);
            $stmt->bindParam(':work_entry', $work_entry);
            $stmt->bindParam(':work_exit', $work_exit);
            $stmt->bindParam(':period', $period);

            $stmt->execute();
            return ['success' => true];
        } catch (MessageException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new MessageException("ERROR_DATABASE_DUPLICATE_ENTRY");
            }
            return [
                'success' => false,
                'error' => 'Database error: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Unknown error: ' . $e->getMessage()
            ];
        }
    }


    public function login($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function getUserData($userId)
    {

        $sql = "SELECT id, username, email, role, business, salary, cpf, rg, work_entry, work_exit, period 
            FROM users 
            WHERE id = :id";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $userData ? $userData : false;
    }

    public function updateSettings($username, $email, $password)
    {
        try {

            $checkSql = "SELECT username FROM users WHERE email = :email AND username != :username";
            $checkStmt = $this->db->getConnection()->prepare($checkSql);
            $checkStmt->bindParam(':username', $username);
            $checkStmt->bindParam(':email', $email);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                throw new MessageException("ERROR_EMAIL_ALREADY_REGISTERED");
            }

            $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : null;

            $sql = "UPDATE users 
                SET email = :email, password = COALESCE(:password, password)
                WHERE username = :username";

            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();

            return ['success' => true];

        } catch (MessageException $e) {
            return ['success' => false, 'error' => $e->getMessage()];

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return ['success' => false, 'error' => 'Database error: Duplicate entry'];
            }
            return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];

        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Unknown error: ' . $e->getMessage()];
        }
    }



    public function registerEntry($userId)
    {
        try {

            $sql = "SELECT id, username, email, password, cpf, rg, lang, role, business, is_an_admin, salary, period, work_entry FROM users WHERE id = :userId";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                throw new MessageException("ERROR_USER_NOT_FOUND");
            }

            $workEntryTime = new DateTime($user['work_entry'], new DateTimeZone('America/Sao_Paulo'));
            $currentTime = new DateTime("now", new DateTimeZone('America/Sao_Paulo'));

            /* TEST HERE - Remove once testing is done */




          /* $currentTime->setDate(2025, 2, 13);
           $currentTime->setTime(14, 00, 0);*/


            /* TEST HERE - Remove once testing is done */


            /*$workEntryTime->setDate(2025, 2, 13);
            $workEntryTime->setTime(14, 00, 0);*/


            $earlyLimit = clone $workEntryTime;
            $earlyLimit->modify('-1 minute');
            $lateLimit = clone $workEntryTime;
            $lateLimit->modify('+30 minutes');

            $formattedCurrentTime = $currentTime->format('Y-m-d H:i:s');
            $currentDate = $currentTime->format('Y-m-d');

            $sql = "SELECT COUNT(*) FROM confirm_point_entry WHERE user_id = :userId AND DATE(work_entry) = :currentDate";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':currentDate', $currentDate);
            $stmt->execute();
            $entryCount = $stmt->fetchColumn();

            if ($entryCount > 0) {
                throw new MessageException("ERROR_ALREADY_REGISTERED_ENTRY");
            }

            $status = '';
            $delay = 0;

            if ($currentTime < $earlyLimit) {
                throw new MessageException("ERROR_BEFORE_TIME");
            }

            if ($currentTime >= $workEntryTime && $currentTime <= $lateLimit) {
                $status = 'Entrada no prazo';
            }

            if ($currentTime > $lateLimit) {
                $status = 'Entrada irregular';
            }

            if ($currentTime > $workEntryTime && $currentTime <= $lateLimit) {

                $delay = $currentTime->getTimestamp() - $workEntryTime->getTimestamp();
                $delaySeconds = round($delay);

                $days = floor($delaySeconds / 86400);
                $hours = floor(($delaySeconds % 86400) / 3600);
                $minutes = floor(($delaySeconds % 3600) / 60);
                $seconds = $delaySeconds % 60;

                if ($days > 0) {
                    $status = 'Entrada com atraso (' . $days . ' dia' . ($days > 1 ? 's' : '') . ', ' . $hours . ' hora' . ($hours != 1 ? 's' : '') . ', ' . $minutes . ' minuto' . ($minutes != 1 ? 's' : '') . ' e ' . $seconds . ' segundo' . ($seconds != 1 ? 's' : '') . ' de atraso)';
                } elseif ($hours > 0) {
                    $status = 'Entrada com atraso (' . $hours . ' hora' . ($hours > 1 ? 's' : '') . ', ' . $minutes . ' minuto' . ($minutes != 1 ? 's' : '') . ' e ' . $seconds . ' segundo' . ($seconds != 1 ? 's' : '') . ' de atraso)';
                } elseif ($minutes > 0) {
                    $status = 'Entrada com atraso (' . $minutes . ' minuto' . ($minutes != 1 ? 's' : '') . ' e ' . $seconds . ' segundo' . ($seconds != 1 ? 's' : '') . ' de atraso)';
                } else {
                    $status = 'Entrada com atraso (' . $seconds . ' segundo' . ($seconds != 1 ? 's' : '') . ' de atraso)';
                }
            }


            $sql = "INSERT INTO confirm_point_entry (user_id, username, email, password, cpf, rg, lang, role, business, is_an_admin, salary, period, work_entry, status) 
                VALUES (:user_id, :username, :email, :password, :cpf, :rg, :lang, :role, :business, :is_an_admin, :salary, :period, :work_entry, :status)";

            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':user_id', $user['id']);
            $stmt->bindParam(':username', $user['username']);
            $stmt->bindParam(':email', $user['email']);
            $stmt->bindParam(':password', $user['password']);
            $stmt->bindParam(':cpf', $user['cpf']);
            $stmt->bindParam(':rg', $user['rg']);
            $stmt->bindParam(':lang', $user['lang']);
            $stmt->bindParam(':role', $user['role']);
            $stmt->bindParam(':business', $user['business']);
            $stmt->bindParam(':is_an_admin', $user['is_an_admin']);
            $stmt->bindParam(':salary', $user['salary']);
            $stmt->bindParam(':period', $user['period']);
            $stmt->bindParam(':work_entry', $formattedCurrentTime);
            $stmt->bindParam(':status', $status);

            try {
                $stmt->execute();
                return ['success' => true, 'message' => 'Entry registered successfully. Status: ' . $status];
            } catch (PDOException $e) {
                return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function registerExit($userId)
    {
        try {

            $sql = "SELECT id, username, email, password, cpf, rg, lang, role, business, is_an_admin, salary, period, work_exit, work_entry FROM users WHERE id = :userId";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                throw new MessageException("ERROR_USER_NOT_FOUND");
            }

            $workExitTime = new DateTime($user['work_exit'], new DateTimeZone('America/Sao_Paulo'));
            $currentTime = new DateTime("now", new DateTimeZone('America/Sao_Paulo'));
            $currentDate = $currentTime->format('Y-m-d');


            /* TEST HERE - Remove once testing is done */


           /* $currentTime->setDate(2025, 2, 13);
            $currentTime->setTime(19, 00, 0);*/



            /* TEST HERE - Remove once testing is done */




            /*  $workExitTime->setDate(2025, 2, 13);
              $workExitTime->setTime(14, 00, 0);*/


            $sql = "SELECT COUNT(*) FROM confirm_point_entry WHERE user_id = :userId AND DATE(work_entry) = :currentDate";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':currentDate', $currentDate);
            $stmt->execute();
            $entryCount = $stmt->fetchColumn();

            if ($entryCount == 0) {
                throw new MessageException("ERROR_NOT_REGISTERED_ENTRY");
            }


            $sql = "SELECT COUNT(*) FROM confirm_point_exit WHERE user_id = :userId AND DATE(work_exit) = :currentDate";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':currentDate', $currentDate);
            $stmt->execute();
            $entryCount = $stmt->fetchColumn();

            if ($entryCount > 0) {
                throw new MessageException("ERROR_ALREADY_REGISTERED_EXIT");
            }


            $earlyLimit = clone $workExitTime;
            $earlyLimit->modify('-5 minutes');
            $lateLimit = clone $workExitTime;
            $lateLimit->modify('+5 minutes');

            $formattedCurrentTime = $currentTime->format('Y-m-d H:i:s');
            $status = '';
            $delay = 0;

            if ($currentTime < $earlyLimit) {
                $status = 'Saída antes do prazo';
                $delay = $earlyLimit->getTimestamp() - $currentTime->getTimestamp();
                $delaySeconds = round($delay);

                $hours = floor($delaySeconds / 3600);
                $minutes = floor(($delaySeconds % 3600) / 60);
                $seconds = $delaySeconds % 60;

                if ($hours > 0) {
                    $status .= ' (' . $hours . ' hora' . ($hours > 1 ? 's' : '') . ', ' . $minutes . ' minuto' . ($minutes != 1 ? 's' : '') . ' e ' . $seconds . ' segundo' . ($seconds != 1 ? 's' : '') . ' antes do prazo)';
                } else {
                    $status .= ' (' . $minutes . ' minuto' . ($minutes != 1 ? 's' : '') . ' e ' . $seconds . ' segundo' . ($seconds != 1 ? 's' : '') . ' antes do prazo)';
                }
            } elseif ($currentTime >= $workExitTime && $currentTime <= $lateLimit) {
                $status = 'Saída no prazo';
            } elseif ($currentTime > $lateLimit) {
                $status = 'Saída com atraso';
                $delay = $currentTime->getTimestamp() - $workExitTime->getTimestamp();
                $delaySeconds = round($delay);
                $hours = floor($delaySeconds / 3600);
                $minutes = floor(($delaySeconds % 3600) / 60);
                $seconds = $delaySeconds % 60;

                if ($hours > 0) {
                    $status .= ' (' . $hours . ' hora' . ($hours > 1 ? 's' : '') . ', ' . $minutes . ' minuto' . ($minutes != 1 ? 's' : '') . ' e ' . $seconds . ' segundo' . ($seconds != 1 ? 's' : '') . ' de atraso)';
                } else {
                    $status .= ' (' . $minutes . ' minuto' . ($minutes != 1 ? 's' : '') . ' e ' . $seconds . ' segundo' . ($seconds != 1 ? 's' : '') . ' de atraso)';
                }
            }


            $sql = "INSERT INTO confirm_point_exit (user_id, username, email, password, cpf, rg, lang, role, business, is_an_admin, salary, period, work_exit, status) 
                VALUES (:user_id, :username, :email, :password, :cpf, :rg, :lang, :role, :business, :is_an_admin, :salary, :period, :work_exit, :status)";

            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':user_id', $user['id']);
            $stmt->bindParam(':username', $user['username']);
            $stmt->bindParam(':email', $user['email']);
            $stmt->bindParam(':password', $user['password']);
            $stmt->bindParam(':cpf', $user['cpf']);
            $stmt->bindParam(':rg', $user['rg']);
            $stmt->bindParam(':lang', $user['lang']);
            $stmt->bindParam(':role', $user['role']);
            $stmt->bindParam(':business', $user['business']);
            $stmt->bindParam(':is_an_admin', $user['is_an_admin']);
            $stmt->bindParam(':salary', $user['salary']);
            $stmt->bindParam(':period', $user['period']);
            $stmt->bindParam(':work_exit', $formattedCurrentTime);
            $stmt->bindParam(':status', $status);

            try {
                $stmt->execute();
                return ['success' => true, 'message' => 'Exit registered successfully. Status: ' . $status];
            } catch (PDOException $e) {
                return ['success' => false, 'error' => 'Database error: ' . $e->getMessage()];
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }


    public function getWorkLogsByUserEntry($userId)
    {
        $sql = "SELECT work_entry, status FROM confirm_point_entry WHERE user_id = :user_id ORDER BY work_entry DESC";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $logsEntry = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $logsEntry ? $logsEntry : [];
    }

    public function getWorkLogsByUserExit($userId)
    {
        $sql = "SELECT work_exit, status FROM confirm_point_exit WHERE user_id = :user_id ORDER BY work_exit DESC";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $logsExit = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $logsExit ? $logsExit : [];
    }


    public function getUsersWithWorkEntryBusiness($business)
    {
        try {
            $sql = "SELECT * FROM confirm_point_entry WHERE business = :business AND work_entry IS NOT NULL";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':business', $business);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getUsersWithWorkExit($business)
    {
        try {
            $sql = "SELECT * FROM confirm_point_exit WHERE business = :business AND work_exit IS NOT NULL";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':business', $business);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }


    public function isAccountApprovedAccount($userId)
    {
        $checkSql = "SELECT approved_account FROM users WHERE id = :id";
        $checkStmt = $this->db->getConnection()->prepare($checkSql);

        $checkStmt->bindParam(':id', $userId);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            $user = $checkStmt->fetch(PDO::FETCH_ASSOC);
            return $user['approved_account'] == 1;
        } else {
            return false;
        }
    }




}

?>
