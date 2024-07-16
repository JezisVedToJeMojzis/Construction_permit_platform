<?php

class PDOApplicationManager{
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

    public function submitApplication(
        $accountId, $role, $street, $houseNumber, $city, $postCode,
        $country, $parcelNumber, $zoningDistrict, $currentUse,
        $proposedUse, $projectTitle, $projectDescription, $projectType, $estimatedTime, $estimatedCost,
        $territorialDecision, $ownershipOrContractor, $constructionSpecification, $projectDocumentation,
        $designerCertificate, $supervisionDeclaration, $electricityStatement, $waterStatement,
        $gasStatement, $roadStatement, $trafficStatement, $environmentalStatement, $supportingDocument
    ) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );

            // Insert into application table
            $stmtApplication = $conn->prepare("INSERT INTO application (account_id, status_id, role, submission_date_and_time, last_change) VALUES (:account_id, 3, :role, NOW(), NOW())");
            $stmtApplication->bindParam(':account_id', $accountId);
            $stmtApplication->bindParam(':role', $role);
            $stmtApplication->execute();

            $applicationId = $conn->lastInsertId();

            // Insert into property table

            $stmtProperty = $conn->prepare("INSERT INTO property (application_id, street, house_number, city, post_code, country, parcel_number, zoning_district, current_use, proposed_use)
            VALUES (:application_id, :street, :house_number, :city, :post_code, :country, :parcel_number, :zoning_district, :current_use, :proposed_use)");
            $stmtProperty->bindParam(':application_id', $applicationId);
            $stmtProperty->bindParam(':street', $street);
            $stmtProperty->bindParam(':house_number', $houseNumber);
            $stmtProperty->bindParam(':city', $city);
            $stmtProperty->bindParam(':post_code', $postCode);
            $stmtProperty->bindParam(':country', $country);
            $stmtProperty->bindParam(':parcel_number', $parcelNumber);
            $stmtProperty->bindParam(':zoning_district', $zoningDistrict);
            $stmtProperty->bindParam(':current_use', $currentUse);
            $stmtProperty->bindParam(':proposed_use', $proposedUse);
            $stmtProperty->execute();

            $conn=null;

        }catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
    }
//
//
//
//    public function getApplication(){
//
//    }
//
//    public function getAllApplications(){
//
//    }
}