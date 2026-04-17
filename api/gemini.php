<?php
// api/gemini.php
header('Content-Type: text/html; charset=utf-8');

// In a real production env, this is where you would call the Google Gemini API using cURL.
// For this PHP implementation without node_modules, we act as a simulated smart agent.

$action = $_POST['action'] ?? 'chat';

if ($action === 'itinerary') {
    $tour = htmlspecialchars($_POST['tour'] ?? 'Destination');
    $days = intval($_POST['days'] ?? 3);

    // Generate a structured HTML itinerary
    echo "<div class='space-y-4'>";
    for ($i = 1; $i <= $days; $i++) {
        $activities = [
            "Visit the famous landmarks of $tour.",
            "Enjoy authentic local cuisine at a top-rated restaurant.",
            "Explore hidden gems and take scenic photos.",
            "Relax at the hotel spa or take a evening stroll.",
            "Shop for souvenirs in the local market."
        ];
        $activity = $activities[($i - 1) % count($activities)];
        
        echo "
        <div class='flex gap-4'>
            <div class='flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600'>
                Day $i
            </div>
            <div class='bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex-1'>
                <h4 class='font-bold text-gray-800'>Exploration & Adventure</h4>
                <p class='text-gray-600 text-sm mt-1'>$activity</p>
            </div>
        </div>";
    }
    echo "</div>";
    exit;
}

// Chat Logic
$message = strtolower($_POST['message'] ?? '');

$responses = [
    'price' => "Our tours are very affordable! They range from <strong>$1,200</strong> to <strong>$3,500</strong>. You can filter by price on the Tours page.",
    'bali' => "<strong>Bali</strong> is one of our top destinations! <br>We recommend the <em>Bali Island Escape</em> (7 Days) for relaxation and culture.",
    'paris' => "Ah, Paris! The city of love. Our <em>Parisian Romance</em> tour includes a sunset cruise and Eiffel Tower tickets.",
    'contact' => "You can reach our support team at <a href='mailto:support@smarttour.com' class='text-blue-600 underline'>support@smarttour.com</a>.",
    'booking' => "To book a tour, simply go to the <strong>Tour Details</strong> page and select your dates. You'll get an instant confirmation!",
    'refund' => "Cancellations made 48 hours before the trip are fully refundable. Check our terms for more details.",
    'hello' => "Hello there! 👋 I'm your AI Travel Assistant. Ask me about destinations, prices, or itineraries!",
    'hi' => "Hi! How can I help you plan your next adventure today?",
    'thank' => "You're welcome! Let me know if you need anything else. Safe travels! ✈️"
];

$reply = "That sounds interesting! Could you be more specific? I can tell you about our <strong>destinations</strong>, <strong>prices</strong>, or help with <strong>bookings</strong>.";

foreach ($responses as $keyword => $resp) {
    if (strpos($message, $keyword) !== false) {
        $reply = $resp;
        break;
    }
}

echo $reply;
?>