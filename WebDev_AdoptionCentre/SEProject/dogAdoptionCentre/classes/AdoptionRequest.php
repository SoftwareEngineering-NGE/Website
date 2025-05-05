<?php

class AdoptionRequest
{
    private $db;
    private $id;
    private $user_id;
    private $dog_id;
    private $request_date;
    private $status;
    private $decision_date;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getUserId() { return $this->user_id; }
    public function getDogId() { return $this->dog_id; }
    public function getRequestDate() { return $this->request_date; }
    public function getStatus() { return $this->status; }
    public function getDecisionDate() { return $this->decision_date; }

    // Setters
    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function setDogId($dog_id) { $this->dog_id = $dog_id; }
    public function setStatus($status) { $this->status = $status; }
    public function setDecisionDate($date) { $this->decision_date = $date; }

    // Read a single request
    public function readOne($id)
    {
        $query = "SELECT * FROM adoption_requests WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update request status
    public function updateStatus($id, $newStatus)
    {
        $query = "UPDATE adoption_requests SET status = :status, decision_date = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Get all requests with user & dog info
    public function readAllWithDetails()
    {
        $query = "
        SELECT ar.*, u.first_name, u.email, d.name AS dog_name 
        FROM adoption_requests ar
        JOIN users u ON ar.user_id = u.id
        LEFT JOIN dogs d ON ar.dog_id = d.id 
        ORDER BY ar.request_date DESC
    ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
