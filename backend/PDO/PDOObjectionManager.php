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
}
?>
