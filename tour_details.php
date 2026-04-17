<?php 
require 'includes/db.php';
include 'includes/header.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM tours WHERE id = ?");
$stmt->execute([$id]);
$tour = $stmt->fetch();

if (!$tour) die("Tour not found");

// Handle Booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
    
    $date = $_POST['date'];
    $guests = $_POST['guests'];
    $total = $tour['price'] * $guests;
    
    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, tour_id, booking_date, guests, total_price) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $id, $date, $guests, $total]);
    
    echo "<script>alert('Booking Confirmed!'); window.location='my_bookings.php';</script>";
}
?>

<div class="relative h-[400px]">
    <img src="<?= htmlspecialchars($tour['image']) ?>" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-black/50 flex items-end">
        <div class="max-w-7xl mx-auto px-8 py-8 w-full text-white">
            <h1 class="text-4xl font-bold"><?= htmlspecialchars($tour['title']) ?></h1>
            <p class="text-xl mt-2"><?= htmlspecialchars($tour['location']) ?> • <?= $tour['duration'] ?> Days</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
    <div class="lg:col-span-2">
        <h2 class="text-2xl font-bold mb-4">Description</h2>
        <p class="text-gray-600 leading-relaxed text-lg mb-8"><?= nl2br(htmlspecialchars($tour['description'])) ?></p>
        
        <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
            <h3 class="font-bold text-blue-900 mb-2">AI Itinerary Suggestion</h3>
            <div id="ai-itinerary" class="text-blue-800 text-sm">Loading itinerary...</div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border h-fit sticky top-24">
        <div class="flex justify-between items-center mb-6">
            <span class="text-gray-500">Price per person</span>
            <span class="text-3xl font-bold text-blue-600">$<?= number_format($tour['price']) ?></span>
        </div>
        
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Date</label>
                <input type="date" name="date" required class="w-full border rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Guests</label>
                <input type="number" name="guests" min="1" value="1" class="w-full border rounded-lg px-4 py-2">
            </div>
            <button type="submit" name="book" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700">Book Now</button>
        </form>
    </div>
</div>

<script>
// Simple fetch to local API
fetch('api/gemini.php', {
    method: 'POST',
    body: new URLSearchParams({
        'action': 'itinerary',
        'tour': '<?= htmlspecialchars($tour['title']) ?>',
        'days': <?= $tour['duration'] ?>
    })
})
.then(r => r.text())
.then(html => document.getElementById('ai-itinerary').innerHTML = html);
</script>

<?php include 'includes/footer.php'; ?>