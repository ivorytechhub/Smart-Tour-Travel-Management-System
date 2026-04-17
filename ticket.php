<?php
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) die("Unauthorized");

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("
    SELECT b.*, t.title, t.location, u.name as user_name, u.email 
    FROM bookings b 
    JOIN tours t ON b.tour_id = t.id 
    JOIN users u ON b.user_id = u.id 
    WHERE b.id = ? AND b.user_id = ?
");
$stmt->execute([$id, $_SESSION['user_id']]);
$booking = $stmt->fetch();

if (!$booking) die("Ticket not found");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>E-Ticket #<?= $booking['id'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { .no-print { display: none; } }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg shadow-2xl rounded-2xl overflow-hidden">
        <div class="bg-blue-600 p-6 text-white text-center">
            <h1 class="text-2xl font-bold">SmartTour E-Ticket</h1>
            <p class="text-blue-100">Booking Reference #<?= str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></p>
        </div>
        <div class="p-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($booking['title']) ?></h2>
                <p class="text-gray-500"><?= htmlspecialchars($booking['location']) ?></p>
            </div>
            
            <div class="border-t border-b border-gray-100 py-4 space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Passenger</span>
                    <span class="font-bold"><?= htmlspecialchars($booking['user_name']) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Date</span>
                    <span class="font-bold"><?= $booking['booking_date'] ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Guests</span>
                    <span class="font-bold"><?= $booking['guests'] ?></span>
                </div>
            </div>

            <div class="flex justify-center my-8">
                 <!-- Simulated QR Code -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=BOOKING-<?= $booking['id'] ?>" alt="QR Code">
            </div>

            <div class="text-center no-print">
                <button onclick="window.print()" class="bg-gray-900 text-white px-6 py-3 rounded-lg font-bold hover:bg-gray-800">Print Ticket</button>
            </div>
        </div>
    </div>
</body>
</html>