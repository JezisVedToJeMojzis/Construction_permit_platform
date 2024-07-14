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

    public function submitApplication($role, $street, $house_number, $city, $post_code,
                                      $country, $parcel_number, $zoning_district, $current_use,
                                      $proposed_use, $project_title, $project_description, $estimated_time, $estimated_cost,
                                      $territorial_decision, $legal_document, $construction_specification, $project_documentation,
                                      $designer, $supervision, $electricity, $water, $gas, $road, $traffic, $enviro, $supporting_documents){
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
            VALUES (:street, :house_number, :city, :post_code, :country, :parcel_number, :zoning_district, :current_use, :proposed_use)
        ");
            $stmtProperty->execute([
                ':street' => $street,
                ':house_number' => $house_number,
                ':city' => $city,
                ':post_code' => $post_code,
                ':country' => $country,
                ':parcel_number' => $parcel_number,
                ':zoning_district' => $zoning_district,
                ':current_use' => $current_use,
                ':proposed_use' => $proposed_use,
            ]);

            $propertyId = $pdo->lastInsertId(); // Retrieve last inserted ID for property

            // Insert into project table
            $stmtProject = $pdo->prepare("
            INSERT INTO project (project_title, project_description, estimated_time, estimated_cost)
            VALUES (:project_title, :project_description, :estimated_time, :estimated_cost)
        ");
            $stmtProject->execute([
                ':project_title' => $project_title,
                ':project_description' => $project_description,
                ':estimated_time' => $estimated_time,
                ':estimated_cost' => $estimated_cost,
            ]);

            $projectId = $pdo->lastInsertId(); // Retrieve last inserted ID for project

            // Insert into documents table
            $stmtDocuments = $pdo->prepare("
            INSERT INTO documents (territorial_decision, ownership_certificate_or_contractor_agreement, construction_specification, project_documentation, architectural_plan, certificate_of_competence_of_the_designer, declaration_of_construction_supervision, statement_from_electricity_networks_administrator, statement_from_water_networks_administrator, statement_from_gas_networks_administrator, statement_from_telecommunications_networks_administrator, statement_from_road_administrator, statement_from_traffic_inspectorate, statement_from_environmental_authority, supporting_documents)
            VALUES (:territorial_decision, :ownership_certificate_or_contractor_agreement, :construction_specification, :project_documentation, :architectural_plan, :certificate_of_competence_of_the_designer, :declaration_of_construction_supervision, :statement_from_electricity_networks_administrator, :statement_from_water_networks_administrator, :statement_from_gas_networks_administrator, :statement_from_telecommunications_networks_administrator, :statement_from_road_administrator, :statement_from_traffic_inspectorate, :statement_from_environmental_authority, :supporting_documents)
        ");
            $stmtDocuments->execute([
                ':territorial_decision' => $territorial_decision,
                ':ownership_certificate_or_contractor_agreement' => $legal_document,
                ':construction_specification' => $construction_specification,
                ':project_documentation' => $project_documentation,
                ':architectural_plan' => $designer,
                ':certificate_of_competence_of_the_designer' => $designer,
                ':declaration_of_construction_supervision' => $supervision,
                ':statement_from_electricity_networks_administrator' => $electricity,
                ':statement_from_water_networks_administrator' => $water,
                ':statement_from_gas_networks_administrator' => $gas,
                ':statement_from_telecommunications_networks_administrator' => '',
                ':statement_from_road_administrator' => $road,
                ':statement_from_traffic_inspectorate' => $traffic,
                ':statement_from_environmental_authority' => $enviro,
                ':supporting_documents' => $supporting_documents,
            ]);

            $documentsId = $pdo->lastInsertId(); // Retrieve last inserted ID for documents

            // Insert into application table
            $stmtApplication = $pdo->prepare("
            INSERT INTO application (account_id, role_id, submission_date_time, status_id, last_change, comments_id, objection_id, property_id, project_id, documents_id, log_id)
            VALUES (:account_id, :role_id, NOW(), :status_id, NOW(), :comments_id, :objection_id, :property_id, :project_id, :documents_id, :log_id)
        ");
            $stmtApplication->execute([
                ':account_id' => $_SESSION['user_id'], // Assuming you have stored user_id in session
                ':role_id' => ($role == 'owner') ? 1 : 2, // Assuming role_id 1 is for owner and 2 is for contractor
                ':status_id' => 1, // Assuming initial status is 1 (you can change according to your status table)
                ':comments_id' => null, // Replace with actual comments_id if needed
                ':objection_id' => null, // Replace with actual objection_id if needed
                ':property_id' => $propertyId,
                ':project_id' => $projectId,
                ':documents_id' => $documentsId,
                ':log_id' => null, // Replace with actual log_id if needed
            ]);

            // Commit the transaction
            $pdo->commit();

            // Redirect to success page or show success message
            header("Location: success.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            // Close the connection
            $pdo = null;
        }

    }


    public function getApplication(){

    }

    public function getAllApplications(){

    }
}