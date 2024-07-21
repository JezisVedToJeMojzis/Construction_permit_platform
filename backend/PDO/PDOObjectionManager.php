<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class PDOObjectionManager
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

    public function submitObjection($accountId, $briefSummary, $detailedExplanation, $affectedParties, $supportingDocument, $applicationId)
    {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Add objection
            $stmtObjection = $conn->prepare("INSERT INTO objection (account_id, status_id, brief_summary, detailed_explanation, affected_parties, submission_date_and_time, last_change, application_id) 
            SELECT :account_id, 1, :brief_summary, :detailed_explanation, :affected_parties, NOW(), NOW(), :application_id 
            FROM application 
            WHERE id = :application_id AND status_id = 8;");
            $stmtObjection->bindParam(':account_id', $accountId);
            $stmtObjection->bindParam(':application_id', $applicationId);
            $stmtObjection->bindParam(':brief_summary', $briefSummary);
            $stmtObjection->bindParam(':detailed_explanation', $detailedExplanation);
            $stmtObjection->bindParam(':affected_parties', $affectedParties);
            $stmtObjection->execute();

            $objectionId = $conn->lastInsertId();

           // Add objection supporting document
            $stmtSupportingDocuments = $conn->prepare("INSERT INTO objection_supporting_document (objection_id, document)
            VALUES (:objection_id, :document)");
            $stmtSupportingDocuments->bindParam(':objection_id', $objectionId);
            $stmtSupportingDocuments->bindParam(':document', $supportingDocument);
            $stmtSupportingDocuments->execute();

          //   Add objection log
            $descriptionObjectionLog = "Objection against application (id: " . $applicationId . ") has been submitted and created";
            $stmtObjectionLog = $conn->prepare("INSERT INTO objection_log (objection_id, description, timestamp) VALUES (:objection_id, :description, NOW())");
            $stmtObjectionLog->bindParam(':objection_id', $objectionId);
            $stmtObjectionLog->bindParam(':description', $descriptionObjectionLog);
            $stmtObjectionLog->execute();

            // Change application status to under objection
            $stmtApplicationStatus = $conn->prepare("UPDATE application SET status_id = 9 WHERE id = :application_id");
            $stmtApplicationStatus->bindParam(':application_id', $applicationId);
            $stmtApplicationStatus->execute();

            // Add application log
            $descriptionApplicationLog = "User (id: " . $accountId . ") has raised an objection (id: " . $objectionId . ")";
            $stmtApplicationLog = $conn->prepare("INSERT INTO application_log (application_id, description, timestamp) VALUES (:application_id, :description, NOW())");
            $stmtApplicationLog->bindParam(':application_id', $applicationId);
            $stmtApplicationLog->bindParam(':description', $descriptionApplicationLog);
            $stmtApplicationLog->execute();

            $conn = null;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getAllClosedObjections() {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT 
                o.*, 
                s.status AS status_status 
            FROM 
                objection o
            JOIN 
                objection_status s ON o.status_id = s.id
            WHERE 
                o.status_id IN (3, 5, 6)
            ");
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

    public function getAllOpenObjections() {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT 
                o.*, 
                s.status AS status_status 
            FROM 
                objection o
            JOIN 
                objection_status s ON o.status_id = s.id
            WHERE 
                o.status_id NOT IN (3, 5, 6)
            ");
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

    public function getAllOpenObjectionsByAccountId($accountId) {
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
                o.account_id = :account_id 
                AND o.status_id NOT IN (3, 5, 6)
            ");
            $stmt->bindParam(':account_id', $accountId);
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

    public function getAllClosedObjectionsByAccountId($accountId) {
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
                o.account_id = :account_id 
                AND o.status_id IN (3, 5, 6)
            ");
            $stmt->bindParam(':account_id', $accountId);
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

    public function getObjectionDetailsById($objectionId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Fetch main objection details
            $stmt = $conn->prepare("SELECT
            o.id AS objection_id,
            o.application_id,
            o.account_id,
            o.admin_id,
            s.status AS objection_status,
            o.submission_date_and_time,
            o.last_change,
            o.brief_summary,
            o.detailed_explanation,
            o.affected_parties
        FROM objection o
        LEFT JOIN objection_status s ON o.status_id = s.id
        WHERE o.id = :objection_id");
            $stmt->bindParam(':objection_id', $objectionId);
            $stmt->execute();
            $objectionDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$objectionDetails) {
                return null;
            }

            // Fetch supporting documents
            $stmt = $conn->prepare("SELECT document FROM objection_supporting_document WHERE objection_id = :objection_id");
            $stmt->bindParam(':objection_id', $objectionId);
            $stmt->execute();
            $objectionDetails['supporting_documents'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch comments
            $stmt = $conn->prepare("SELECT account_id AS comment_account_id, comment, timestamp AS comment_timestamp FROM objection_comment WHERE objection_id = :objection_id");
            $stmt->bindParam(':objection_id', $objectionId);
            $stmt->execute();
            $objectionDetails['comments'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch logs
            $stmt = $conn->prepare("SELECT description AS log_description, timestamp AS log_timestamp FROM objection_log WHERE objection_id = :objection_id");
            $stmt->bindParam(':objection_id', $objectionId);
            $stmt->execute();
            $objectionDetails['logs'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $conn = null;
            return $objectionDetails;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
