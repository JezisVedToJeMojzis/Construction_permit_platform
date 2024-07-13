<?php

class PDOLoginManager
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

    public function checkEmail($email)
    {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );

            $stmt = $conn->prepare("SELECT id, email, password, role FROM account WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $conn = null;

            return $result;

        } catch (PDOException $exception) {
            echo "PDO exception: " . $exception->getMessage();
        }
        return false;
    }
}