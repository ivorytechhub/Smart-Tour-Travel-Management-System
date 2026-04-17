<?php
require '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Stats
$stats = [
    'revenue' => $pdo->query("SELECT SUM(total_price) FROM bookings WHERE status != 'cancelled'")->fetchColumn() ?: 0,
    'bookings' => $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn(),
    'tours' => $pdo->query("SELECT COUNT(*) FROM tours")->fetchColumn()
];

// Recent Bookings
$bookings = $pdo->query("SELECT b.*, t.title, u.name FROM bookings b JOIN tours t ON b.tour_id = t.id JOIN users u ON b.user_id = u.id ORDER BY b.created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white hidden md:block">
        <div class="p-6 font-bold text-xl">SmartAdmin</div>
        <nav class="mt-6">
            <a href="index.php" class="block px-6 py-3 bg-gray-700">Dashboard</a>
            <a href="tours.php" class="block px-6 py-3 hover:bg-gray-700">Manage Tours</a>
            <a href="../index.php" class="block px-6 py-3 hover:bg-gray-700">Back to Website</a>
        </nav>
    </aside>

    <!-- Content -->
    <div class="flex-1 p-8">
        <h1 class="text-3xl font-bold mb-8">Dashboard Overview</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <p class="text-gray-500">Total Revenue</p>
                <p class="text-3xl font-bold text-green-600">$<?= number_format($stats['revenue']) ?></p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <p class="text-gray-500">Total Bookings</p>
                <p class="text-3xl font-bold text-blue-600"><?= $stats['bookings'] ?></p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <p class="text-gray-500">Active Tours</p>
                <p class="text-3xl font-bold text-purple-600"><?= $stats['tours'] ?></p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="font-bold">Recent Bookings</h3>
            </div>
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-6 py-3">User</th>
                        <th class="px-6 py-3">Tour</th>
                        <th class="px-6 py-3">Amount</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td class="px-6 py-4"><?= htmlspecialchars($b['name']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($b['title']) ?></td>
                        <td class="px-6 py-4">$<?= $b['total_price'] ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-bold <?= $b['status'] == 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                                <?= $b['status'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>