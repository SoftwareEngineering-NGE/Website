<?php require_once '../template/header.php'; ?>

<?php
require '../lib/db.php';
$db = new Database();
$pdo = $db->getConnection();

// Get filters
$selectedGenders = $_GET['gender'] ?? [];
$selectedAgeGroups = $_GET['age_group'] ?? [];
$search = trim($_GET['search'] ?? '');

$query = "SELECT * FROM dogs WHERE is_available = TRUE";
$conditions = [];
$params = [];

// Gender filter
if (!empty($selectedGenders)) {
    $inClause = implode(',', array_fill(0, count($selectedGenders), '?'));
    $query .= " AND gender IN ($inClause)";
    $params = array_merge($params, $selectedGenders);
}

// Age group filter
if (!empty($selectedAgeGroups)) {
    $ageConditions = [];
    foreach ($selectedAgeGroups as $group) {
        switch ($group) {
            case '0-3':
                $ageConditions[] = "(age BETWEEN 0 AND 3)";
                break;
            case '4-6':
                $ageConditions[] = "(age BETWEEN 4 AND 6)";
                break;
            case '7+':
                $ageConditions[] = "(age >= 7)";
                break;
        }
    }
    if (!empty($ageConditions)) {
        $query .= " AND (" . implode(" OR ", $ageConditions) . ")";
    }
}

// Search filter
if (!empty($search)) {
    $query .= " AND (name LIKE ? OR breed LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$query .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$dogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <div class="container">
        <h1>Meet Our Dogs</h1>

        <!-- Filter Form -->
        <form method="GET" style="margin-bottom: 20px; padding: 15px; background-color: #f3e8d7; border-radius: 8px;">
            <fieldset>
                <legend><strong>Search by Name or Breed:</strong></legend>
                <input type="text" name="search" placeholder="e.g. Bella, Husky" value="<?= escape($search) ?>" style="width: 300px;">
            </fieldset>

            <fieldset>
                <legend><strong>Filter by Gender:</strong></legend>
                <label><input type="checkbox" name="gender[]" value="Male" <?= in_array("Male", $selectedGenders) ? 'checked' : '' ?>> Male</label>
                <label><input type="checkbox" name="gender[]" value="Female" <?= in_array("Female", $selectedGenders) ? 'checked' : '' ?>> Female</label>
            </fieldset>

            <fieldset>
                <legend><strong>Filter by Age Group:</strong></legend>
                <label><input type="checkbox" name="age_group[]" value="0-3" <?= in_array("0-3", $selectedAgeGroups) ? 'checked' : '' ?>> 0–3 years</label>
                <label><input type="checkbox" name="age_group[]" value="4-6" <?= in_array("4-6", $selectedAgeGroups) ? 'checked' : '' ?>> 4–6 years</label>
                <label><input type="checkbox" name="age_group[]" value="7+" <?= in_array("7+", $selectedAgeGroups) ? 'checked' : '' ?>> 7+ years</label>
            </fieldset>

            <br>
            <button type="submit" class="button">Apply Filters</button>
            <a href="index.php" class="button">Reset</a>
        </form>

        <!-- Dog List -->
        <?php if (!empty($dogs)): ?>
            <?php foreach ($dogs as $dog): ?>
                <div class="house">
                    <img src="<?= escape($dog['image']) ?>" alt="<?= escape($dog['name']) ?>">
                    <h2><?= escape($dog['name']) ?></h2>
                    <p><strong>Breed:</strong> <?= escape($dog['breed']) ?></p>
                    <p><strong>Age:</strong> <?= escape($dog['age']) ?> years</p>
                    <p><strong>Gender:</strong> <?= escape($dog['gender']) ?></p>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/dogs/show.php?id=<?= $dog['id'] ?>" class="button">View Details</a>
                    <?php else: ?>
                        <a href="/public/login.php" class="button">Login to Adopt</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No dogs match your selected filters.</p>
        <?php endif; ?>
    </div>

<?php require_once '../template/footer.php'; ?>