<?php

class PDORegisterAccountManager{
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

    public function registerPrivateAccount($email, $password, $role, $first_name, $last_name,
                                           $phone_number, $street, $house_number, $city, $post_code,
                                           $country, $identification_number){
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );

            $stmt = $conn->prepare("INSERT INTO account (email, password, role) VALUES (:email, :password, :role)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);

            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->errorInfo()[2]);
            }

            $account_id = $conn->lastInsertId();

            $stmt_pp = $conn->prepare("INSERT INTO private_person_account (account_id, first_name, last_name, phone_number, street, house_number, city, post_code, country, identification_number) VALUES (:account_id, :first_name, :last_name, :phone_number, :street, :house_number, :city, :post_code, :country, :identification_number)");
            $stmt_pp->bindParam(':account_id', $account_id);
            $stmt_pp->bindParam(':first_name', $first_name);
            $stmt_pp->bindParam(':last_name', $last_name);
            $stmt_pp->bindParam(':phone_number', $phone_number);
            $stmt_pp->bindParam(':street', $street);
            $stmt_pp->bindParam(':house_number', $house_number);
            $stmt_pp->bindParam(':city', $city);
            $stmt_pp->bindParam(':post_code', $post_code);
            $stmt_pp->bindParam(':country', $country);
            $stmt_pp->bindParam(':identification_number', $identification_number);
            $stmt_pp->execute();

            $conn = null;

        } catch (PDOException $exception) {
            echo "PDO exception: ". $exception->getMessage();
        }
    }

    public function registerOrganizationAccount($email, $password, $role, $org_name, $contact_first_name,
                                                $contact_last_name, $phone_number, $street, $house_number,
                                                $city, $post_code, $country, $registration_number){
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );

            $stmt = $conn->prepare("INSERT INTO account (email, password, role) VALUES (:email, :password, :role)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);

            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->errorInfo()[2]);
            }

            $account_id = $conn->lastInsertId();

            $stmt_org = $conn->prepare("INSERT INTO organization_account (account_id, name, contact_first_name, contact_last_name, phone_number, street, house_number, city, post_code, country, registration_number) VALUES (:account_id, :org_name, :contact_first_name, :contact_last_name, :phone_number, :street, :house_number, :city, :post_code, :country, :registration_number)");
            $stmt_org->bindParam(':account_id', $account_id);
            $stmt_org->bindParam(':org_name', $org_name);
            $stmt_org->bindParam(':contact_first_name', $contact_first_name);
            $stmt_org->bindParam(':contact_last_name', $contact_last_name);
            $stmt_org->bindParam(':phone_number', $phone_number);
            $stmt_org->bindParam(':street', $street);
            $stmt_org->bindParam(':house_number', $house_number);
            $stmt_org->bindParam(':city', $city);
            $stmt_org->bindParam(':post_code', $post_code);
            $stmt_org->bindParam(':country', $country);
            $stmt_org->bindParam(':registration_number', $registration_number);
            $stmt_org->execute();

            $conn = null;

        } catch (PDOException $exception) {
            echo "PDO exception: ". $exception->getMessage();
        }
    }
}
?>
