<?php

class UserAdmin extends User
{

    public function executeSQLQuery($sql)
    {
        try {
            if (empty($sql)) {
                return "Error: SQL query cannot be empty.";
            }

            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }


    public function deleteUserByCpfandRG($cpf, $rg)
    {
        try {
            $sqlDeletePoints = "DELETE FROM confirm_point_entry WHERE user_id IN (SELECT id FROM users WHERE cpf = :cpf AND rg = :rg)";
            $stmtDeletePoints = $this->db->getConnection()->prepare($sqlDeletePoints);
            $stmtDeletePoints->bindParam(':cpf', $cpf);
            $stmtDeletePoints->bindParam(':rg', $rg);
            $stmtDeletePoints->execute();

            $sqlDeletePoints = "DELETE FROM confirm_point_exit WHERE user_id IN (SELECT id FROM users WHERE cpf = :cpf AND rg = :rg)";
            $stmtDeletePoints = $this->db->getConnection()->prepare($sqlDeletePoints);
            $stmtDeletePoints->bindParam(':cpf', $cpf);
            $stmtDeletePoints->bindParam(':rg', $rg);
            $stmtDeletePoints->execute();

            $sql = "DELETE FROM users WHERE cpf = :cpf AND rg = :rg";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':rg', $rg);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getUsers()
    {
        try {
            $sql = "SELECT id, username, email, cpf, rg, role, business, salary, period, work_entry, work_exit, approved_account, is_an_admin 
                FROM users";

            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function searchUsersByNameOrEmail($searchTerm)
    {
        try {
            $sql = "SELECT id, username, email, cpf, rg, role, business, salary, period, work_entry, work_exit, approved_account, is_an_admin
                FROM users
                WHERE username LIKE :searchTerm OR email LIKE :searchTerm";

            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%');
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }


    public function isAccountAnAdmin($userId)
    {
        $checkSql = "SELECT is_an_admin FROM users WHERE id = :id";
        $checkStmt = $this->db->getConnection()->prepare($checkSql);

        $checkStmt->bindParam(':id', $userId);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            $user = $checkStmt->fetch(PDO::FETCH_ASSOC);
            return $user['is_an_admin'] == 1;
        } else {
            return false;
        }
    }


    public function getUsersWithWorkEntryAll()
    {
        try {
            $sql = "SELECT * FROM confirm_point_entry";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getUsersWithWorkExitAll()
    {
        try {
            $sql = "SELECT * FROM confirm_point_exit";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }


}