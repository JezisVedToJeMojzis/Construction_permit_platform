<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
class PDOAccountManager
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

    public function getAllUserAccounts() {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM account WHERE role != 'admin'");
            $stmt->execute();
            $accounts =  $stmt->fetchAll(PDO::FETCH_ASSOC);

            $conn = null;
            return $accounts;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getAccountDetailsByAccountId($accountId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT 
                a.id AS account_id,
                a.email,
                a.role,
                COALESCE(pp.first_name, o.name) AS name_or_first_name,
                COALESCE(pp.last_name, o.contact_last_name) AS last_name_or_contact_last_name,
                COALESCE(pp.phone_number, o.phone_number) AS phone_number,
                COALESCE(pp.street, o.street) AS street,
                COALESCE(pp.house_number, o.house_number) AS house_number,
                COALESCE(pp.city, o.city) AS city,
                COALESCE(pp.post_code, o.post_code) AS post_code,
                COALESCE(pp.country, o.country) AS country,
                COALESCE(pp.identification_number, o.registration_number) AS identification_or_registration_number
            FROM 
                account a
            LEFT JOIN 
                private_person_account pp ON a.id = pp.account_id
            LEFT JOIN 
                organization_account o ON a.id = o.account_id
            WHERE 
                a.id = :account_id;");
            $stmt->bindParam(':account_id', $accountId);
            $stmt->execute();
            $account =  $stmt->fetch(PDO::FETCH_ASSOC);;

            $conn = null;
            return $account;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }


}