<?php require_once '../template/header.php'; ?>
<?php
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../public/login.php');
    exit;
}

require '../lib/db.php';
require '../classes/Dog.php';

$db = new Database();
$pdo = $db->getConnection();
$dogModel = new Dog($pdo);

$uploadDir = '../uploads/';
$dog = ['name' => '', 'breed' => '', 'age' => '', 'gender' => '', 'description' => '', 'image' => ''];

if (isset($_GET['id'])) {
    $dog = $dogModel->readOne($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imagePath = $dog['image'];

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
                echo "Error uploading file.";
                exit;
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG allowed.";
            exit;
        }
    }

    $dogModel->setName($_POST['name']);
    $dogModel->setBreed($_POST['breed']);
    $dogModel->setAge($_POST['age']);
    $dogModel->setGender($_POST['gender']);
    $dogModel->setDescription($_POST['description']);
    $dogModel->setImage($imagePath);

    if ($dogModel->update($_GET['id'])) {
        header('Location: manage_dogs.php');
        exit;
    } else {
        echo "Failed to update dog.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Dog</title>
    <link rel="stylesheet" href="../css/theme.css">
</head>
<body>
<div class="container">
    <h2>Edit Dog</h2>
    <form method="POST" enctype="multipart/form-data">
        Name:<br>
        <input type="text" name="name" required value="<?= escape($dog['name']) ?>"><br><br>

        Breed:<br>
        <input type="text" name="breed" required value="<?= escape($dog['breed']) ?>"><br><br>

        Age:<br>
        <input type="number" name="age" required value="<?= escape($dog['age']) ?>"><br><br>

        Gender:<br>
        <select name="gender">
            <option <?= $dog['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
            <option <?= $dog['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
        </select><br><br>

        Description:<br>
        <textarea name="description" rows="5"><?= escape($dog['description']) ?></textarea><br><br>

        Current Image:<br>
        <?php if (!empty($dog['image'])): ?>
            <img src="<?= $dog['image'] ?>" alt="Current Image" width="150"><br>
        <?php endif; ?>

        Upload New Image (optional):<br>
        <input type="file" name="image" accept=".jpg, .jpeg, .png"><br><br>

        <button type="submit" class="button">Save</button>
        <a href="manage_dogs.php" class="button">Cancel</a>
    </form>
</div>
</body>
</html>
