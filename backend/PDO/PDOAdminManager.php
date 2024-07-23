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

    public function assignAdminToApplication($applicationId, $adminId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmtAssign = $conn->prepare("UPDATE application SET admin_id = :admin_id WHERE id = :application_id");
            $stmtAssign->bindParam(':application_id', $applicationId);
            $stmtAssign->bindParam(':admin_id', $adminId);
            $stmtAssign->execute();

            $description = "Admin (id: " . $adminId . ") was assigned to the application (id: " . $applicationId . ")";
            $stmtLog = $conn->prepare("INSERT INTO application_log (application_id, description, timestamp) VALUES (:application_id, :description, NOW())");
            $stmtLog->bindParam(':description', $description);
            $stmtLog->bindParam(':application_id', $applicationId);
            $stmtLog->execute();

            $conn = null;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function unassignAdminFromApplication($applicationId, $adminId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmtUnassign = $conn->prepare("UPDATE application SET admin_id = NULL WHERE id = :application_id");
            $stmtUnassign->bindParam(':application_id', $applicationId);
            $stmtUnassign->execute();

            $description = "Admin (id: " . $adminId . ") was unassigned from the application (id: " . $applicationId . ")";
            $stmtLog = $conn->prepare("INSERT INTO application_log (application_id, description, timestamp) VALUES (:application_id, :description, NOW())");
            $stmtLog->bindParam(':description', $description);
            $stmtLog->bindParam(':application_id', $applicationId);
            $stmtLog->execute();

            $conn = null;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function assignAdminToObjection($objectionId, $adminId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

    public function unassignAdminFromObjection($objectionId, $adminId) {
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

    public function getAllOpenAssignedApplicationsByAdminId($adminId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("
            SELECT 
                a.*,
                s.status AS status_status
            FROM 
                application a
            JOIN 
                application_status s ON a.status_id = s.id
            WHERE 
                a.admin_id = :admin_id 
                AND a.status_id NOT IN (7, 10, 12, 13)
            ");
            $stmt->bindParam(':admin_id', $adminId);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $conn = null;
            return $result;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getAllClosedAssignedApplicationsByAdminId($adminId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("
            SELECT 
                a.*,
                s.status AS status_status
            FROM 
                application a
            JOIN 
                application_status s ON a.status_id = s.id
            WHERE 
                a.admin_id = :admin_id 
                AND a.status_id IN (7, 10, 12, 13)
            ");
            $stmt->bindParam(':admin_id', $adminId);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $conn = null;
            return $result;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getAllOpenAssignedObjectionsByAdminId($adminId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("
            SELECT 
                o.*,
                s.status AS status_status
            FROM 
                objection o
            JOIN 
                objection_status s ON o.status_id = s.id
            WHERE 
                o.admin_id = :admin_id 
                AND o.status_id NOT IN (3, 5, 6)
            ");
            $stmt->bindParam(':admin_id', $adminId);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $conn = null;
            return $result;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getAllClosedAssignedObjectionsByAdminId($adminId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("
            SELECT 
                o.*,
                s.status AS status_status
            FROM 
                objection o
            JOIN 
                objection_status s ON o.status_id = s.id
            WHERE 
                o.admin_id = :admin_id 
                AND o.status_id IN (3, 5, 6)
            ");
            $stmt->bindParam(':admin_id', $adminId);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $conn = null;
            return $result;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function setApplicationStatus($applicationId, $statusId){
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("UPDATE application SET status_id = :status_id WHERE id = :application_id");
            $stmt->bindParam(':application_id', $applicationId);
            $stmt->bindParam(':status_id', $statusId);
            $stmt->execute();

            $stmtStatus = $conn->prepare("SELECT status FROM application_status WHERE id = :status_id");
            $stmtStatus->bindParam(':status_id', $statusId);
            $stmtStatus->execute();
            $status = $stmtStatus->fetch(PDO::FETCH_ASSOC);

            // Update logs
            $description = "Application status has been changed to: " . $status['status'];
            $stmtLogs = $conn->prepare("INSERT INTO application_log (application_id, description, timestamp) VALUES (:application_id, :description, NOW())");
            $stmtLogs->bindParam(':application_id', $applicationId);
            $stmtLogs->bindParam(':description', $description);
            $stmtLogs->execute();

            $conn = null;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function setObjectionStatus($objectionId, $statusId){
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("UPDATE objection SET status_id = :status_id WHERE id = :objection_id");
            $stmt->bindParam(':objection_id', $objectionId);
            $stmt->bindParam(':status_id', $statusId);
            $stmt->execute();

            $stmtStatus = $conn->prepare("SELECT status FROM objection_status WHERE id = :status_id");
            $stmtStatus->bindParam(':status_id', $statusId);
            $stmtStatus->execute();
            $status = $stmtStatus->fetch(PDO::FETCH_ASSOC);

            // Update logs
            $description = "Objection status has been changed to: " . $status['status'];
            $stmtLogs = $conn->prepare("INSERT INTO objection_log (objection_id, description, timestamp) VALUES (:objection_id, :description, NOW())");
            $stmtLogs->bindParam(':objection_id', $objectionId);
            $stmtLogs->bindParam(':description', $description);
            $stmtLogs->execute();

            $conn = null;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}