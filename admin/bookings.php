<?php
require '../includes/db.php';
require '../includes/auth_check.php';

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['booking_id']]);
    $success = "Booking status updated successfully.";
}

// Fetch Bookings with Filters
$filter = $_GET['status'] ?? 'all';
$sql = "SELECT b.*, t.title as tour_title, u.name as user_name, u.email 
        FROM bookings b 
        JOIN tours t ON b.tour_id = t.id 
        JOIN users u ON b.user_id = u.id";

if ($filter !== 'all') {
    $sql .= " WHERE b.status = :status";
}
$sql .= " ORDER BY b.created_at DESC";

$stmt = $pdo->prepare($sql);
if ($filter !== 'all') {
    $stmt->bindParam(':status', $filter);
}
$stmt->execute();
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Bookings - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-screen flex">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white hidden md:flex flex-col">
        <div class="p-6 text-2xl font-bold tracking-tight">SmartAdmin</div>
        <nav class="flex-1 px-4 space-y-2 mt-4">
            <a href="index.php" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition-colors">
                <span>Dashboard</span>
            </a>
            <a href="tours.php" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition-colors">
                <span>Manage Tours</span>
            </a>
            <a href="bookings.php" class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-lg shadow-lg">
                <span>Bookings</span>
            </a>
            <a href="../index.php" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition-colors mt-auto">
                <span>Back to Website</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Bookings Management</h1>
            <div class="flex items-center gap-4">
                <a href="bookings.php?status=all" class="px-3 py-1 rounded-full text-sm <?= $filter === 'all' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600' ?>">All</a>
                <a href="bookings.php?status=pending" class="px-3 py-1 rounded-full text-sm <?= $filter === 'pending' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-600' ?>">Pending</a>
                <a href="bookings.php?status=confirmed" class="px-3 py-1 rounded-full text-sm <?= $filter === 'confirmed' ? 'bg-green-600 text-white' : 'bg-white text-gray-600' ?>">Confirmed</a>
                <a href="bookings.php?status=cancelled" class="px-3 py-1 rounded-full text-sm <?= $filter === 'cancelled' ? 'bg-red-600 text-white' : 'bg-white text-gray-600' ?>">Cancelled</a>
            </div>
        </div>

        <?php if (isset($success)): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <?= $success ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Tour Info</th>
                        <th class="px-6 py-4">Date & Guests</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($bookings as $b): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500">#<?= $b['id'] ?></td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900"><?= htmlspecialchars($b['user_name']) ?></p>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($b['email']) ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800"><?= htmlspecialchars($b['tour_title']) ?></p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <p><?= date('M d, Y', strtotime($b['booking_date'])) ?></p>
                            <p><?= $b['guests'] ?> Guests</p>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900">
                            $<?= number_format($b['total_price']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php 
                                    if($b['status'] === 'confirmed') echo 'bg-green-100 text-green-800';
                                    elseif($b['status'] === 'pending') echo 'bg-yellow-100 text-yellow-800';
                                    else echo 'bg-red-100 text-red-800';
                                ?>">
                                <?= ucfirst($b['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form method="POST" class="inline-flex gap-2">
                                <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                <?php if($b['status'] !== 'confirmed'): ?>
                                    <button type="submit" name="status" value="confirmed" class="text-green-600 hover:bg-green-50 p-1 rounded" title="Confirm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                <?php endif; ?>
                                <?php if($b['status'] !== 'cancelled'): ?>
                                    <button type="submit" name="status" value="cancelled" class="text-red-600 hover:bg-red-50 p-1 rounded" title="Cancel">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($bookings)): ?>
                        <tr><td colspan="7" class="text-center py-8 text-gray-500">No bookings found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>