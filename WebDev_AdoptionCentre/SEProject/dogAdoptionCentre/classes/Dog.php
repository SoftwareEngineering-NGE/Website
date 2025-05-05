<?php

class Dog
{
    private $db;
    private $id;
    private $name;
    private $breed;
    private $age;
    private $gender;
    private $description;
    private $image;
    private $created_at;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getBreed() { return $this->breed; }
    public function getAge() { return $this->age; }
    public function getGender() { return $this->gender; }
    public function getDescription() { return $this->description; }
    public function getImage() { return $this->image; }
    public function getCreatedAt() { return $this->created_at; }

    // Setters
    public function setName($name) { $this->name = $name; }
    public function setBreed($breed) { $this->breed = $breed; }
    public function setAge($age) { $this->age = $age; }
    public function setGender($gender) { $this->gender = $gender; }
    public function setDescription($description) { $this->description = $description; }
    public function setImage($image) { $this->image = $image; }

    // Create a new dog record
    public function create()
    {
        $query = "INSERT INTO dogs (name, breed, age, gender, description, image) 
                  VALUES (:name, :breed, :age, :gender, :description, :image)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':breed', $this->breed);
        $stmt->bindParam(':age', $this->age);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image);
        return $stmt->execute();
    }

    // Get all dogs
    public function readAll()
    {
        $query = "SELECT * FROM dogs ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get one dog by ID
    public function readOne($id)
    {
        $query = "SELECT * FROM dogs WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Update a dog by ID
    public function update($id)
    {
        $query = "UPDATE dogs 
              SET name = :name, breed = :breed, age = :age, gender = :gender, description = :description, image = :image 
              WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':breed', $this->breed);
        $stmt->bindParam(':age', $this->age);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }


    // Delete a dog by ID
    public function delete($id)
    {
        $query = "DELETE FROM dogs WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
