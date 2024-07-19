<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
class PDOCommentManager
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

    public function postApplicationComment($applicationId, $accountId, $comment)
    {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );

            //Comment
            $stmtComment = $conn->prepare("INSERT INTO application_comment (application_id, account_id, Comment, timestamp) VALUES (:application_id, :account_id, :comment, NOW())");
            $stmtComment->bindParam(':application_id', $applicationId);
            $stmtComment->bindParam(':account_id', $accountId);
            $stmtComment->bindParam(':comment', $comment);
            $stmtComment->execute();

            // Related log
            $description = "User (id: " . $accountId . ") left a comment on the application (id: " . $applicationId . ")";
            $stmtLogs = $conn->prepare("INSERT INTO application_log (application_id, description, timestamp) VALUES (:application_id, :description, NOW())");
            $stmtLogs->bindParam(':application_id', $applicationId);
            $stmtLogs->bindParam(':description', $description);
            $stmtLogs->execute();

            $conn = null;

        } catch (PDOException $exception) {
            echo "PDO exception: " . $exception->getMessage();
        }
        return false;
    }

    public function getAllApplicationCommentsByApplicationId($applicationId)
    {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );

            $stmt = $conn->prepare("SELECT * FROM application_comment WHERE application_id = :application_id");
            $stmt->bindParam(':application_id', $applicationId);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $conn = null;
            return $result;

        } catch (PDOException $exception) {
            echo "PDO exception: " . $exception->getMessage();
        }
        return false;
    }
}