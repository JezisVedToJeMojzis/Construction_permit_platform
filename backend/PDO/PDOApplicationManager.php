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
        $role,
        $street,
        $houseNumber,
        $city,
        $postCode,
        $country,
        $parcelNumber,
        $zoningDistrict,
        $currentUse,
        $proposedUse,
        $projectTitle,
        $projectDescription,
        $estimatedTime,
        $estimatedCost,
        $territorialDecision,
        $legalDocument,
        $constructionSpecification,
        $projectDocumentation,
        $designer,
        $supervision,
        $electricity,
        $water,
        $gas,
        $road,
        $traffic,
        $enviro,
        $supportingDocument
    ) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );

            // Set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insert into property table
            $stmtProperty = $conn->prepare("
            INSERT INTO property (street, house_number, city, post_code, country, parcel_number, zoning_district, current_use, proposed_use)
            VALUES (:street, :houseNumber, :city, :postCode, :country, :parcelNumber, :zoningDistrict, :currentUse, :proposedUse)
        ");
            $stmtProperty->execute([
                ':street' => $street,
                ':houseNumber' => $houseNumber,
                ':city' => $city,
                ':postCode' => $postCode,
                ':country' => $country,
                ':parcelNumber' => $parcelNumber,
                ':zoningDistrict' => $zoningDistrict,
                ':currentUse' => $currentUse,
                ':proposedUse' => $proposedUse,
            ]);

            $propertyId = $conn->lastInsertId(); // Retrieve last inserted ID for property

            // Insert into project table
            $stmtProject = $conn->prepare("
            INSERT INTO project (project_title, project_description, estimated_time, estimated_cost)
            VALUES (:projectTitle, :projectDescription, :estimatedTime, :estimatedCost)
        ");
            $stmtProject->execute([
                ':projectTitle' => $projectTitle,
                ':projectDescription' => $projectDescription,
                ':estimatedTime' => $estimatedTime,
                ':estimatedCost' => $estimatedCost,
            ]);

            $projectId = $conn->lastInsertId(); // Retrieve last inserted ID for project

            // Insert into documents table
            $stmtDocuments = $conn->prepare("
            INSERT INTO documents (territorial_decision, ownership_certificate_or_contractor_agreement, construction_specification, project_documentation, certificate_of_competence_of_the_designer, declaration_of_construction_supervision, statement_from_electricity_networks_administrator, statement_from_water_networks_administrator, statement_from_gas_networks_administrator, statement_from_road_administrator, statement_from_traffic_inspectorate, statement_from_environmental_authority, supporting_documents)
            VALUES (:territorialDecision, :legalDocument, :constructionSpecification, :projectDocumentation, :designer, :supervision, :electricity, :water, :gas, :road, :traffic, :enviro, :supportingDocument)
        ");
            $stmtDocuments->execute([
                ':territorialDecision' => $territorialDecision,
                ':legalDocument' => $legalDocument,
                ':constructionSpecification' => $constructionSpecification,
                ':projectDocumentation' => $projectDocumentation,
                ':designer' => $designer,
                ':supervision' => $supervision,
                ':electricity' => $electricity,
                ':water' => $water,
                ':gas' => $gas,
                ':road' => $road,
                ':traffic' => $traffic,
                ':enviro' => $enviro,
                ':supportingDocument' => $supportingDocument,
            ]);

            $documentsId = $conn->lastInsertId(); // Retrieve last inserted ID for documents

            // Insert into application table
            $stmtApplication = $conn->prepare("
            INSERT INTO application (account_id, role_id, submission_date_time, status_id, last_change, comments_id, objection_id, property_id, project_id, documents_id, log_id)
            VALUES (:accountId, :roleId, NOW(), :statusId, NOW(), :commentsId, :objectionId, :propertyId, :projectId, :documentsId, :logId)
        ");
            $stmtApplication->execute([
                ':accountId' => $_SESSION['user_id'],
                ':roleId' => ($role == 'owner') ? 1 : 2,
                ':statusId' => 1,
                ':commentsId' => null,
                ':objectionId' => null,
                ':propertyId' => $propertyId,
                ':projectId' => $projectId,
                ':documentsId' => $documentsId,
                ':logId' => null,
            ]);

            $conn->commit();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            $conn = null;
        }
    }



    public function getApplication(){

    }

    public function getAllApplications(){

    }
}