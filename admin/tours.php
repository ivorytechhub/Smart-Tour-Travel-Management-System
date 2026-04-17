<?php
require '../includes/db.php';
require '../includes/auth_check.php';

$tours = $pdo->query("SELECT * FROM tours ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Tours - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
    <script>
        function openModal(tour = null) {
            const modal = document.getElementById('tourModal');
            const form = document.getElementById('tourForm');
            const modalTitle = document.getElementById('modalTitle');

            if (tour) {
                modalTitle.innerText = 'Edit Tour';
                form.id.value = tour.id;
                form.title.value = tour.title;
                form.location.value = tour.location;
                form.price.value = tour.price;
                form.duration.value = tour.duration;
                form.category.value = tour.category;
                form.description.value = tour.description;
                form.image_url.value = tour.image.startsWith('http') ? tour.image : '';
                form.current_image.value = tour.image;
            } else {
                modalTitle.innerText = 'Add New Tour';
                form.reset();
                form.id.value = '';
            }
            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('tourModal').classList.add('hidden');
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex">
    
    <aside class="w-64 bg-gray-900 text-white hidden md:flex flex-col">
        <div class="p-6 text-2xl font-bold tracking-tight">SmartAdmin</div>
        <nav class="flex-1 px-4 space-y-2 mt-4">
            <a href="index.php" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition-colors">
                <span>Dashboard</span>
            </a>
            <a href="tours.php" class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-lg shadow-lg">
                <span>Manage Tours</span>
            </a>
            <a href="bookings.php" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition-colors">
                <span>Bookings</span>
            </a>
            <a href="../index.php" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg transition-colors mt-auto">
                <span>Back to Website</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Manage Tours</h1>
            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold flex items-center gap-2 shadow-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Tour
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($tours as $t): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col group hover:shadow-md transition-shadow">
                <div class="relative h-48 overflow-hidden">
                    <img src="<?= strpos($t['image'], 'http') === 0 ? $t['image'] : '../' . $t['image'] ?>" alt="Tour" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-2 right-2 bg-white/90 px-2 py-1 rounded text-xs font-bold shadow-sm">
                        $<?= number_format($t['price']) ?>
                    </div>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg text-gray-900"><?= htmlspecialchars($t['title']) ?></h3>
                        <span class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded font-medium"><?= $t['category'] ?></span>
                    </div>
                    <p class="text-gray-500 text-sm line-clamp-2 mb-4 flex-1"><?= htmlspecialchars($t['description']) ?></p>
                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                        <span class="text-sm text-gray-400 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <?= htmlspecialchars($t['location']) ?>
                        </span>
                        <div class="flex gap-2">
                            <button onclick='openModal(<?= json_encode($t) ?>)' class="p-2 text-blue-600 hover:bg-blue-50 rounded transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form action="tour_actions.php" method="POST" onsubmit="return confirm('Are you sure?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                <button class="p-2 text-red-600 hover:bg-red-50 rounded transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Modal -->
    <div id="tourModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white">
                <h2 id="modalTitle" class="text-xl font-bold">Add New Tour</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <form id="tourForm" action="tour_actions.php" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                <input type="hidden" name="id">
                <input type="hidden" name="current_image">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" required class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" name="location" required class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price ($)</label>
                        <input type="number" name="price" required class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Duration (Days)</label>
                        <input type="number" name="duration" required class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category" class="w-full border rounded-lg px-3 py-2">
                            <option value="Adventure">Adventure</option>
                            <option value="Relaxation">Relaxation</option>
                            <option value="Cultural">Cultural</option>
                            <option value="Nature">Nature</option>
                            <option value="Luxury">Luxury</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Image</label>
                        <input type="file" name="image" accept="image/*" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <p class="text-xs text-gray-500 mt-1">Or paste URL below</p>
                        <input type="text" name="image_url" placeholder="https://..." class="w-full border rounded-lg px-3 py-1 mt-1 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4" class="w-full border rounded-lg px-3 py-2"></textarea>
                </div>
                <div class="flex justify-end pt-4">
                    <button type="button" onclick="closeModal()" class="mr-3 px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700">Save Tour</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>