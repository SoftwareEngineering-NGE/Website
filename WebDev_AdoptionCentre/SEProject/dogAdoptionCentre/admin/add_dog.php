<?php require_once '../template/header.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../public/login.php');
    exit;
}

require '../lib/db.php';
require '../classes/Dog.php';

$db = new Database();
$pdo = $db->getConnection();
$dog = new Dog($pdo);

$uploadDir = '../uploads/';
$imagePath = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = uniqid('dog_', true) . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $imagePath = $destPath;
            } else {
                echo "Error moving uploaded file.";
                exit;
            }
        } else {
            echo "Only JPG, JPEG, and PNG files are allowed.";
            exit;
        }
    }

    $dog->setName($_POST['name']);
    $dog->setBreed($_POST['breed']);
    $dog->setAge($_POST['age']);
    $dog->setGender($_POST['gender']);
    $dog->setDescription($_POST['description']);
    $dog->setImage($imagePath);

    if ($dog->create()) {
        header('Location: manage_dogs.php');
        exit;
    } else {
        echo "Failed to add dog.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Dog</title>
    <link rel="stylesheet" href="../css/theme.css">
</head>
<body>
<div class="container">
    <h2>Add a New Dog</h2>
    <form method="POST" enctype="multipart/form-data">
        Name:<br>
        <input type="text" name="name" required><br><br>

        Breed:<br>
        <input type="text" name="breed" required><br><br>

        Age (years):<br>
        <input type="number" name="age" required><br><br>

        Gender:<br>
        <select name="gender">
            <option>Male</option>
            <option>Female</option>
        </select><br><br>

        Description:<br>
        <textarea name="description" rows="5"></textarea><br><br>

        Image Upload:<br>
        <input type="file" name="image" accept=".jpg, .jpeg, .png"><br><br>

        <button type="submit" class="button">Add Dog</button>
        <a href="manage_dogs.php" class="button">Cancel</a>
    </form>
</div>
</body>
</html>
