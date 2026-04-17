<?php 
require 'includes/db.php';
include 'includes/header.php';

// Fetch featured tours
$stmt = $pdo->query("SELECT * FROM tours ORDER BY rating DESC LIMIT 3");
$featured_tours = $stmt->fetchAll();
?>

<div class="relative h-[600px]">
    <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=2021&q=80" alt="Hero" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="text-center text-white px-4">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">Discover the World Smarter</h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto">AI-powered itineraries, seamless bookings, and unforgettable experiences.</p>
            <a href="tours.php" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold text-lg transition-colors">Explore Tours</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">Featured Destinations</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php foreach ($featured_tours as $tour): ?>
        <a href="tour_details.php?id=<?= $tour['id'] ?>" class="group bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 block">
            <div class="relative h-64 overflow-hidden">
                <img src="<?= htmlspecialchars($tour['image']) ?>" alt="<?= htmlspecialchars($tour['title']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-semibold text-gray-800">
                    $<?= number_format($tour['price']) ?>
                </div>
            </div>
            <div class="p-6">
                <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded-md text-xs font-bold"><?= htmlspecialchars($tour['category']) ?></span>
                <h3 class="text-xl font-bold text-gray-900 mt-2 mb-2 group-hover:text-blue-600"><?= htmlspecialchars($tour['title']) ?></h3>
                <p class="text-gray-600 text-sm line-clamp-2 mb-4"><?= htmlspecialchars($tour['description']) ?></p>
                <div class="flex items-center justify-between text-gray-500 text-sm border-t pt-4">
                    <span><?= $tour['duration'] ?> Days</span>
                    <span><?= htmlspecialchars($tour['location']) ?></span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>