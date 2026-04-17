<?php
require '../includes/db.php';
require '../includes/auth_check.php';

// Ensure upload directory exists
$target_dir = "../uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // DELETE ACTION
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM tours WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: tours.php?msg=deleted");
        exit;
    }

    // CREATE / UPDATE ACTION
    $title = $_POST['title'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    
    // Handle Image Upload
    $image_path = $_POST['current_image'] ?? 'https://via.placeholder.com/800x600?text=No+Image'; // Default
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $target_dir . $filename;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Validate file type
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = 'uploads/' . $filename; // Store relative path for DB
            }
        }
    } else if (!empty($_POST['image_url'])) {
        // Fallback to URL if provided
        $image_path = $_POST['image_url'];
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // UPDATE
        $sql = "UPDATE tours SET title=?, location=?, price=?, duration=?, category=?, description=?, image=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $location, $price, $duration, $category, $description, $image_path, $_POST['id']]);
        $msg = "updated";
    } else {
        // INSERT
        $sql = "INSERT INTO tours (title, location, price, duration, category, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $location, $price, $duration, $category, $description, $image_path]);
        $msg = "created";
    }

    header("Location: tours.php?msg=$msg");
    exit;
}
?>