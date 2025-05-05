<?php

class Admin
{
    private $db;
    private $id;
    private $email;
    private $password;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }

    // Setters
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = $password; }
    public function setId($id) { $this->id = $id; }

    // Authenticate admin
    public function authenticate($email, $passwordInput)
    {
        $query = "SELECT * FROM admin WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($passwordInput, $admin['password'])) {
            return $admin;
        }

        return false;
    }

}
