<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
class PDOLogManager
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

    public function getAllLogsByApplicationId($applicationId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM application_log WHERE application_id = :application_id");
            $stmt->bindParam(':application_id', $applicationId);
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
}