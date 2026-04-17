</main>
<footer class="bg-gray-800 text-white py-8 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-bold mb-4">SmartTour</h3>
                <p class="text-gray-400 text-sm">AI-powered travel experiences.</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="tours.php" class="hover:text-white">Destinations</a></li>
                    <li><a href="my_bookings.php" class="hover:text-white">My Bookings</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Contact</h3>
                <p class="text-gray-400 text-sm">support@smarttour.com</p>
            </div>
        </div>
        <div class="mt-8 border-t border-gray-700 pt-8 text-center text-gray-400 text-sm">
            &copy; <?php echo date('Y'); ?> SmartTour Systems.
        </div>
    </div>
</footer>

<!-- AI Chat Widget -->
<div class="fixed bottom-6 right-6 z-50">
    <button onclick="toggleChat()" class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg flex items-center gap-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        <span class="font-semibold hidden sm:inline">AI Advisor</span>
    </button>
</div>

<div id="chat-window" class="hidden fixed bottom-24 right-6 bg-white rounded-2xl shadow-2xl w-80 sm:w-96 flex flex-col h-[400px] border border-gray-200 z-50">
    <div class="bg-blue-600 text-white p-4 rounded-t-2xl flex justify-between items-center">
        <h3 class="font-bold">Travel Assistant</h3>
        <button onclick="toggleChat()" class="hover:text-gray-200">✕</button>
    </div>
    <div id="chat-messages" class="flex-grow p-4 overflow-y-auto bg-gray-50 space-y-4 text-sm">
        <div class="flex justify-start">
            <div class="bg-white border p-3 rounded-2xl rounded-tl-none text-gray-800 shadow-sm">
                Hello! I'm your AI assistant. Ask me about our tours!
            </div>
        </div>
    </div>
    <div class="p-3 border-t bg-white rounded-b-2xl flex gap-2">
        <input type="text" id="chat-input" onkeypress="handleEnter(event)" placeholder="Ask about destinations..." class="flex-grow border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button onclick="sendMessage()" class="bg-blue-600 text-white p-2 rounded-full hover:bg-blue-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/></svg>
        </button>
    </div>
</div>

<script src="js/main.js"></script>
</body>
</html>