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

            $stmt = $conn->prepare("
                SELECT 
                    a.id, a.email, a.password, a.role,
                    pp.first_name AS pp_first_name, pp.last_name AS pp_last_name,
                    pp.phone_number AS pp_phone_number, pp.street AS pp_street, 
                    pp.house_number AS pp_house_number, pp.city AS pp_city, 
                    pp.post_code AS pp_post_code, pp.country AS pp_country, 
                    pp.identification_number AS pp_identification_number,
                    oa.name AS oa_name, oa.contact_first_name AS oa_contact_first_name, 
                    oa.contact_last_name AS oa_contact_last_name,
                    oa.phone_number AS oa_phone_number, oa.street AS oa_street, 
                    oa.house_number AS oa_house_number, oa.city AS oa_city, 
                    oa.post_code AS oa_post_code, oa.country AS oa_country, 
                    oa.registration_number AS oa_registration_number,
                    aa.first_name AS aa_first_name, aa.last_name AS aa_last_name
                FROM account a
                LEFT JOIN private_person_account pp ON a.id = pp.account_id AND a.role = 'private'
                LEFT JOIN organization_account oa ON a.id = oa.account_id AND a.role = 'organization'
                LEFT JOIN admin_account aa ON a.id = aa.account_id AND a.role = 'admin'
                WHERE a.email = :email
            ");
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