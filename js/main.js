function toggleChat() {
    const chat = document.getElementById('chat-window');
    chat.classList.toggle('hidden');
}

function handleEnter(e) {
    if (e.key === 'Enter') sendMessage();
}

function sendMessage() {
    const input = document.getElementById('chat-input');
    const msg = input.value.trim();
    if (!msg) return;

    const chatBody = document.getElementById('chat-messages');
    
    // User Message
    const userDiv = document.createElement('div');
    userDiv.className = 'flex justify-end';
    userDiv.innerHTML = `<div class="bg-blue-600 text-white p-3 rounded-2xl rounded-tr-none text-sm max-w-[80%]">${escapeHtml(msg)}</div>`;
    chatBody.appendChild(userDiv);
    
    input.value = '';
    
    // Loading Indicator
    const loadDiv = document.createElement('div');
    loadDiv.id = 'loading';
    loadDiv.className = 'flex justify-start';
    loadDiv.innerHTML = `<div class="bg-white border p-3 rounded-2xl rounded-tl-none text-sm text-gray-500 italic">Thinking...</div>`;
    chatBody.appendChild(loadDiv);
    chatBody.scrollTop = chatBody.scrollHeight;

    // Send to PHP API
    const formData = new FormData();
    formData.append('message', msg);

    fetch('api/gemini.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.text())
    .then(resp => {
        document.getElementById('loading').remove();
        const aiDiv = document.createElement('div');
        aiDiv.className = 'flex justify-start';
        aiDiv.innerHTML = `<div class="bg-white border p-3 rounded-2xl rounded-tl-none text-sm max-w-[80%] shadow-sm text-gray-700">${resp}</div>`;
        chatBody.appendChild(aiDiv);
        chatBody.scrollTop = chatBody.scrollHeight;
    })
    .catch(() => {
        document.getElementById('loading').remove();
        alert('Error connecting to AI assistant.');
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}