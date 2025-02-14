<?php

require_once 'exeception/MessageException.php';

class Registration
{
    private $error;

    public function __construct()
    {
        $this->error = '';
    }

    public function process()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (isset($_POST['next'])) {
                    $this->handleNextStep();
                } elseif (isset($_POST['back'])) {
                    $this->handleBackStep();
                }
            } catch (MessageException $e) {
                $this->error = $e->getMessage();
            }
        }
    }

    private function handleNextStep()
    {
        try {
            switch ($_SESSION['step']) {
                case 1:
                    $this->stepOne();
                    break;
                case 2:
                    $this->stepTwo();
                    break;
                case 3:
                    $this->stepThree();
                    break;
                default:
                    throw new MessageException("ERROR_UNKNOWN_STEP");
            }
        } catch (MessageException $e) {
            throw new MessageException($e->getMessage());
        }
    }

    private function stepOne()
    {
        try {
            if (isset($_POST['username'], $_POST['email'], $_POST['cpf'], $_POST['rg'])) {
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['cpf'] = $_POST['cpf'];
                $_SESSION['rg'] = $_POST['rg'];
                $_SESSION['step'] = 2;
            } else {
                throw new MessageException("ERROR_STEP_ONE_FIELDS");
            }
        } catch (MessageException $e) {
            throw new MessageException($e->getMessage());
        }
    }

    private function stepTwo()
    {
        try {
            if (isset($_POST['password']) && !empty($_POST['password'])) {
                $password = $_POST['password'];

                $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}|:"<>?]).{8,}$/';

                if (!preg_match($passwordRegex, $password)) {
                    throw new MessageException("ERROR_PASSWORD_REGEX");
                }

                if ($_POST['password'] !== $_POST['confirm-password']) {
                    throw new MessageException("ERROR_PASSWORD_MISMATCH");
                }

                $_SESSION['password'] = $password;
            } else {
                throw new MessageException("ERROR_PASSWORD_REQUIRED");
            }

            $_SESSION['role'] = $_POST['role'] ?? 'user';
            $_SESSION['business'] = $_POST['business'] ?? '';
            $_SESSION['salary'] = (isset($_POST['salary']) && $_POST['salary'] !== '') ? (is_numeric($_POST['salary']) ? (float)$_POST['salary'] : 0.00) : 0.00;
            $_SESSION['work_entry'] = $_POST['work_entry'] ?? null;
            $_SESSION['work_exit'] = $_POST['work_exit'] ?? null;
            $_SESSION['period'] = $_POST['period'] ?? null;

            $_SESSION['step'] = 3;
        } catch (MessageException $e) {
            throw new MessageException($e->getMessage());
        }
    }

    private function stepThree()
    {
        try {
            if (!isset($_SESSION['username'], $_SESSION['email'], $_SESSION['password'], $_SESSION['role'], $_SESSION['business'], $_SESSION['salary'], $_SESSION['cpf'], $_SESSION['rg'])) {
                throw new MessageException("ERROR_FILL_REQUIRED_FIELDS");
            }

            $user = new User();
            $register = $user->register(
                $_SESSION['username'],
                $_SESSION['email'],
                $_SESSION['password'],
                $_SESSION['cpf'],
                $_SESSION['rg'],
                $_SESSION['role'],
                $_SESSION['business'],
                false,
                $_SESSION['salary'],
                $_SESSION['work_entry'],
                $_SESSION['work_exit'],
                $_SESSION['period']
            );

            if ($register['success']) {
                $_SESSION['username'] = $_SESSION['username'];
                session_destroy();
                header("Location: login.php");
                exit();
            } else {
                $this->error = $register['error'];
            }
        } catch (MessageException $e) {
            throw new MessageException($e->getMessage());
        }
    }

    private function handleBackStep()
    {
        $_SESSION['step']--;
    }

    public function getError()
    {
        return $this->error;
    }
}

?>
