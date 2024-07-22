<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
        $architecturalPlan, $designerCertificate, $supervisionDeclaration, $electricityStatement, $waterStatement,
        $gasStatement, $telecommunicationsStatement, $roadStatement, $trafficStatement, $environmentalStatement, $supportingDocument
    ) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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

            // Insert into project table
            $stmtProject = $conn->prepare("INSERT INTO project (application_id, title, description, type, estimated_time, estimated_cost)
            VALUES (:application_id, :title, :description, :type, :estimated_time, :estimated_cost)");
            $stmtProject->bindParam(':application_id', $applicationId);
            $stmtProject->bindParam(':title', $projectTitle);
            $stmtProject->bindParam(':description', $projectDescription);
            $stmtProject->bindParam(':type', $projectType);
            $stmtProject->bindParam(':estimated_time', $estimatedTime);
            $stmtProject->bindParam(':estimated_cost', $estimatedCost);
            $stmtProject->execute();

            // Insert into application_documents table
            $stmtDocuments = $conn->prepare("INSERT INTO application_documents (application_id, territorial_decision, ownership_document_or_contractor_agreement, project_documentation, architectural_plan, designer_certificate, electricity_statement, water_statement, gas_statement, telecommunications_statement, road_statement, traffic_statement, environmental_statement, construction_specification, supervision_declaration)
VALUES (:application_id, :territorial_decision, :ownership_document_or_contractor_agreement, :project_documentation, :architectural_plan, :designer_certificate, :electricity_statement, :water_statement, :gas_statement, :telecommunications_statement, :road_statement, :traffic_statement, :environmental_statement, :construction_specification, :supervision_declaration)");
            $stmtDocuments->bindParam(':application_id', $applicationId);
            $stmtDocuments->bindParam(':territorial_decision', $territorialDecision);
            $stmtDocuments->bindParam(':ownership_document_or_contractor_agreement', $ownershipOrContractor);
            $stmtDocuments->bindParam(':project_documentation', $projectDocumentation);
            $stmtDocuments->bindParam(':architectural_plan', $architecturalPlan);
            $stmtDocuments->bindParam(':designer_certificate', $designerCertificate);
            $stmtDocuments->bindParam(':electricity_statement', $electricityStatement);
            $stmtDocuments->bindParam(':water_statement', $waterStatement);
            $stmtDocuments->bindParam(':gas_statement', $gasStatement);
            $stmtDocuments->bindParam(':telecommunications_statement', $telecommunicationsStatement);
            $stmtDocuments->bindParam(':road_statement', $roadStatement);
            $stmtDocuments->bindParam(':traffic_statement', $trafficStatement);
            $stmtDocuments->bindParam(':environmental_statement', $environmentalStatement);
            $stmtDocuments->bindParam(':construction_specification', $constructionSpecification);
            $stmtDocuments->bindParam(':supervision_declaration', $supervisionDeclaration);
            $stmtDocuments->execute();


            $documentsId = $conn->lastInsertId();

            // Insert into application_supporting_document table
            $stmtSupportingDocuments = $conn->prepare("INSERT INTO application_supporting_document (application_documents_id, document)
            VALUES (:documents_id, :document)");
            $stmtSupportingDocuments->bindParam(':documents_id', $documentsId);
            $stmtSupportingDocuments->bindParam(':document', $supportingDocument);
            $stmtSupportingDocuments->execute();

            $description = "Application has been successfully submitted by user and created in the system";
            $stmtLogs = $conn->prepare("INSERT INTO application_log (application_id, description, timestamp) VALUES (:application_id, :description, NOW())");
            $stmtLogs->bindParam(':application_id', $applicationId);
            $stmtLogs->bindParam(':description', $description);
            $stmtLogs->execute();

            $conn=null;

        }catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
    }

    public function getAllOpenApplications() {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT 
                a.*, 
                s.status AS status_status 
            FROM 
                application a
            JOIN 
                application_status s ON a.status_id = s.id
            WHERE 
                a.status_id NOT IN (7, 10, 12, 13)
            ");
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

    public function getAllClosedApplications() {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT 
                a.*, 
                s.status AS status_status 
            FROM 
                application a
            JOIN 
                application_status s ON a.status_id = s.id
            WHERE 
                a.status_id IN (7, 10, 12, 13)
            ");
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

    public function getApplicationDetailsById($applicationId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT
    a.id AS application_id,
    a.account_id,
    a.role,
    s.status AS application_status,
    a.submission_date_and_time,
    a.last_change,
    a.admin_id,
    
    
    -- Property details
    p.street,
    p.house_number,
    p.city,
    p.post_code,
    p.country,
    p.parcel_number,
    p.zoning_district,
    p.current_use,
    p.proposed_use,
    
    -- Project details
    pr.title AS project_title,
    pr.description AS project_description,
    pr.type AS project_type,
    pr.estimated_time,
    pr.estimated_cost,
    
    -- Application documents
    ad.territorial_decision,
    ad.ownership_document_or_contractor_agreement,
    ad.project_documentation,
    ad.architectural_plan,
    ad.designer_certificate,
    ad.electricity_statement,
    ad.water_statement,
    ad.gas_statement,
    ad.telecommunications_statement,
    ad.road_statement,
    ad.traffic_statement,
    ad.environmental_statement,
    
    -- Supporting documents
    asd.document AS supporting_document,
    
    -- Comments
    ac.account_id AS comment_account_id,
    ac.Comment AS comment,
    ac.timestamp AS comment_timestamp,
    
    -- Logs
    al.description AS log_description,
    al.timestamp AS log_timestamp

FROM
    application a
    LEFT JOIN application_status s ON a.status_id = s.id
    LEFT JOIN property p ON a.id = p.application_id
    LEFT JOIN project pr ON a.id = pr.application_id
    LEFT JOIN application_documents ad ON a.id = ad.application_id
    LEFT JOIN application_supporting_document asd ON ad.id = asd.application_documents_id
    LEFT JOIN application_comment ac ON a.id = ac.application_id
    LEFT JOIN application_log al ON a.id = al.application_id

WHERE
    a.id = :application_id;");
            $stmt->bindParam(':application_id', $applicationId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $conn = null;
            return $result;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    public function getAllClosedApplicationsByAccountId($accountId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("
            SELECT 
                a.*,
                s.status AS status_status
            FROM 
                application a
            JOIN 
                application_status s ON a.status_id = s.id
            WHERE 
                a.account_id = :account_id 
                AND a.status_id IN (7, 10, 12, 13)
            ");
            $stmt->bindParam(':account_id', $accountId);
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

    public function getAllOpenApplicationsByAccountId($accountId) {
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("
            SELECT 
                a.*,
                s.status AS status_status
            FROM 
                application a
            JOIN 
                application_status s ON a.status_id = s.id
            WHERE 
                a.account_id = :account_id 
                AND a.status_id NOT IN (7, 10, 12, 13)
            ");
            $stmt->bindParam(':account_id', $accountId);
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

    public function withdrawApplication($applicationId){
        try {
            $conn = new PDO(
                "mysql:host=$this->serverName;dbname=$this->databaseName",
                $this->userName,
                $this->userPassword
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Change status to Withdrawn
            $stmt = $conn->prepare("UPDATE application SET status_id = 10 WHERE id = :application_id");
            $stmt->bindParam(':application_id', $applicationId);
            $stmt->execute();

            // Update logs
            $description = "Application has been withdrawed by its creator";
            $stmtLogs = $conn->prepare("INSERT INTO application_log (application_id, description, timestamp) VALUES (:application_id, :description, NOW())");
            $stmtLogs->bindParam(':application_id', $applicationId);
            $stmtLogs->bindParam(':description', $description);
            $stmtLogs->execute();

            $conn = null;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}