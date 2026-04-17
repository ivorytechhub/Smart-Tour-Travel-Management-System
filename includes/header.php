<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Tour System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="index.php" class="flex items-center gap-2">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span class="text-xl font-bold text-gray-800">SmartTour</span>
                </a>
            </div>
            <div class="hidden sm:flex sm:space-x-8 items-center">
                <a href="index.php" class="text-gray-600 hover:text-blue-600 px-3 py-2 font-medium">Home</a>
                <a href="tours.php" class="text-gray-600 hover:text-blue-600 px-3 py-2 font-medium">Tours</a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="my_bookings.php" class="text-gray-600 hover:text-blue-600 px-3 py-2 font-medium">My Bookings</a>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="admin/index.php" class="text-blue-600 font-bold px-3 py-2">Admin Dashboard</a>
                    <?php endif; ?>
                    <a href="logout.php" class="bg-gray-100 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-200">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="text-gray-600 hover:text-blue-600 px-3 py-2 font-medium">Login</a>
                    <a href="register.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<main class="flex-grow">