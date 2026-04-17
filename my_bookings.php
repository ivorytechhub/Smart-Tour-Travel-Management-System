<?php 
require 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT b.*, t.title, t.location, t.image 
    FROM bookings b 
    JOIN tours t ON b.tour_id = t.id 
    WHERE b.user_id = ? 
    ORDER BY b.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();
?>

<div class="max-w-7xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold mb-8">My Bookings</h1>
    <div class="space-y-6">
        <?php foreach ($bookings as $booking): ?>
        <div class="bg-white p-6 rounded-xl shadow-sm border flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <img src="<?= htmlspecialchars($booking['image']) ?>" class="w-24 h-24 object-cover rounded-lg">
                <div>
                    <h3 class="text-xl font-bold"><?= htmlspecialchars($booking['title']) ?></h3>
                    <p class="text-gray-500"><?= $booking['booking_date'] ?> • <?= $booking['guests'] ?> Guests</p>
                    <span class="inline-block px-2 py-1 text-xs font-bold rounded mt-2 
                        <?= $booking['status'] == 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                        <?= strtoupper($booking['status']) ?>
                    </span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xl font-bold mb-2">$<?= number_format($booking['total_price']) ?></p>
                <?php if ($booking['status'] === 'confirmed'): ?>
                    <a href="ticket.php?id=<?= $booking['id'] ?>" target="_blank" class="inline-block border border-blue-600 text-blue-600 px-4 py-2 rounded hover:bg-blue-50">View E-Ticket</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>