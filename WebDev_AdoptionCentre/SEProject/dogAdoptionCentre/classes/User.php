<?php

class User
{
    private $db;
    private $id;
    private $first_name;
    private $email;
    private $password;
    private $created_at;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getFirstName() { return $this->first_name; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getCreatedAt() { return $this->created_at; }

    // Setters
    public function setFirstName($name) { $this->first_name = $name; }
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = $password; }

    // Create user
    public function create()
    {
        $query = "INSERT INTO users (first_name, email, password) 
                  VALUES (:first_name, :email, :password)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    // Read by email
    public function readByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Authenticate
    public function authenticate($email, $passwordInput)
    {
        $user = $this->readByEmail($email);
        if ($user && password_verify($passwordInput, $user['password'])) {
            return $user;
        }
        return false;
    }

    // Read all
    public function readAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM users ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function getAllMessagesWithUserInfo()
    {
        $query = "SELECT c.*, u.first_name, u.email 
                  FROM contact_messages c 
                  JOIN users u ON c.user_id = u.id 
                  ORDER BY sent_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
