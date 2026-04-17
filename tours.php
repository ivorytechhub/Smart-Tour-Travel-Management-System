<?php 
require 'includes/db.php';
include 'includes/header.php';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? 'All';

$query = "SELECT * FROM tours WHERE (title LIKE :search OR location LIKE :search)";
$params = ['search' => "%$search%"];

if ($category !== 'All') {
    $query .= " AND category = :category";
    $params['category'] = $category;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$tours = $stmt->fetchAll();

$categories = $pdo->query("SELECT DISTINCT category FROM tours")->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        <h1 class="text-3xl font-bold">All Tours</h1>
        <form class="flex gap-4">
            <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>" class="border rounded-lg px-4 py-2">
            <select name="category" class="border rounded-lg px-4 py-2" onchange="this.form.submit()">
                <option value="All">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat ?>" <?= $category === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Filter</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php foreach ($tours as $tour): ?>
            <a href="tour_details.php?id=<?= $tour['id'] ?>" class="group bg-white rounded-xl shadow-sm hover:shadow-xl transition-all overflow-hidden border">
                <img src="<?= htmlspecialchars($tour['image']) ?>" class="w-full h-48 object-cover">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded"><?= $tour['category'] ?></span>
                            <h3 class="text-lg font-bold mt-2"><?= htmlspecialchars($tour['title']) ?></h3>
                        </div>
                        <span class="font-bold text-gray-900">$<?= number_format($tour['price']) ?></span>
                    </div>
                    <p class="text-gray-500 text-sm mt-2 line-clamp-2"><?= htmlspecialchars($tour['description']) ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>