<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
class PDOAdminManager
{
    private string $serverName;
    private string $userName;
    private string $userPassword;
    private string $databaseName;

    public function __construct($serverName, $userName, $userPassword, $databaseName)
    {
        $this->serverName = $serverName;
        $this->userName = $userName;
        $this->userPassword = $userPassword;
        $this->databaseName = $databaseName;
    }

    public function assignAdminToApplication($applicationId, $accountId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmtAdminId = $conn->prepare("SELECT id FROM admin_account WHERE account_id = :account_id");
            $stmtAdminId->bindParam(':account_id', $accountId);
            $stmtAdminId->execute();
            $adminId =  $stmtAdminId->fetchColumn();

            $stmtAssign = $conn->prepare("UPDATE application SET admin_id = :admin_id WHERE id = :application_id");
            $stmtAssign->bindParam(':application_id', $applicationId);
            $stmtAssign->bindParam(':admin_id', $adminId);
            $stmtAssign->execute();

            $conn = null;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function unassignAdminFromApplication($applicationId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmtUnassign = $conn->prepare("UPDATE applicaiton SET admin_id = NULL WHERE id = :application_id");
            $stmtUnassign->bindParam(':application_id', $applicationId);
            $stmtUnassign->execute();

            $conn = null;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function assignAdminToObjection($objectionId, $accountId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmtAdminId = $conn->prepare("SELECT id FROM admin_account WHERE account_id = :account_id");
            $stmtAdminId->bindParam(':account_id', $accountId);
            $stmtAdminId->execute();
            $adminId =  $stmtAdminId->fetchColumn();

            $stmtAssign = $conn->prepare("UPDATE objection SET admin_id = :admin_id WHERE id = :objection_id");
            $stmtAssign->bindParam(':objection_id', $objectionId);
            $stmtAssign->bindParam(':admin_id', $adminId);
            $stmtAssign->execute();

            $description = "Admin (id: " . $adminId . ") was assigned to the objection (id: " . $objectionId . ")";
            $stmtLog = $conn->prepare("INSERT INTO objection_log (objection_id, description, timestamp) VALUES (:objection_id, :description, NOW())");
            $stmtLog->bindParam(':description', $description);
            $stmtLog->bindParam(':objection_id', $objectionId);
            $stmtLog->execute();

            $conn = null;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function unassignAdminFromObjection($objectionId, $accountId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmtUnassign = $conn->prepare("UPDATE objection SET admin_id = NULL WHERE id = :objection_id");
            $stmtUnassign->bindParam(':objection_id', $objectionId);
            $stmtUnassign->execute();

            $stmtAdminId = $conn->prepare("SELECT id FROM admin_account WHERE account_id = :account_id");
            $stmtAdminId->bindParam(':account_id', $accountId);
            $stmtAdminId->execute();
            $adminId =  $stmtAdminId->fetchColumn();

            $description = "Admin (id: " . $adminId . ") was unassigned from the objection (id: " . $objectionId . ")";
            $stmtLog = $conn->prepare("INSERT INTO objection_log (objection_id, description, timestamp) VALUES (:objection_id, :description, NOW())");
            $stmtLog->bindParam(':description', $description);
            $stmtLog->bindParam(':objection_id', $objectionId);
            $stmtLog->execute();

            $conn = null;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

}