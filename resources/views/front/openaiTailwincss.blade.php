<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Help Desk AI - BPB UNM</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
.fade-in {
    animation: fadeIn 0.3s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body class="flex flex-col h-screen bg-gray-100">

<!-- Header -->
<div class="bg-blue-900 text-white p-4 text-lg font-semibold flex justify-between items-center">
    Help Desk AI - BPB UNM
    <div>
        <button onclick="setLanguage('id')" class="px-2 py-1 rounded hover:bg-blue-700">ID</button>
        <button onclick="setLanguage('en')" class="px-2 py-1 rounded hover:bg-blue-700">EN</button>
    </div>
</div>

<!-- Chat Messages -->
<div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-2">
    <div class="text-center text-gray-500">Selamat datang di Help Desk AI BPB UNM!</div>
</div>

<!-- Input Box -->
<div class="flex p-4 bg-gray-200">
    <input id="userInput" type="text" placeholder="Tulis pertanyaan..." 
           class="flex-1 p-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
    <button onclick="sendMessage()" 
            class="ml-2 px-4 py-2 bg-blue-900 text-white rounded hover:bg-blue-700 transition">Kirim</button>
</div>

<script>
let currentLang = 'id';

function setLanguage(lang){
    currentLang = lang;
    addSystemMessage(lang === 'id' ? 'Bahasa diubah ke Indonesia.' : 'Language changed to English.');
}

const chatMessages = document.getElementById('chat-messages');

function addMessage(text, sender='ai'){
    const msgDiv = document.createElement('div');
    msgDiv.classList.add('fade-in','flex','items-start','space-x-2');

    if(sender === 'user'){
        msgDiv.classList.add('justify-end');
        msgDiv.innerHTML = `
            <span class="inline-block bg-blue-900 text-white p-2 rounded-lg max-w-xs break-words">${text}</span>
            <img src="https://cdn-icons-png.flaticon.com/512/1946/1946429.png" class="w-6 h-6 rounded-full">
        `;
    } else if(sender === 'ai'){
        msgDiv.classList.add('justify-start');
        msgDiv.innerHTML = `
            <img src="https://cdn-icons-png.flaticon.com/512/4712/4712027.png" class="w-6 h-6 rounded-full">
            <span class="inline-block bg-gray-300 text-gray-900 p-2 rounded-lg max-w-xs break-words">${text}</span>
        `;
    } else {
        msgDiv.classList.add('justify-center text-gray-500 text-sm italic');
        msgDiv.innerHTML = text;
    }

    chatMessages.appendChild(msgDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function addSystemMessage(text){
    addMessage(text, 'system');
}

async function sendMessage(){
    const input = document.getElementById('userInput');
    const text = input.value.trim();
    if(!text) return;

    // Tambahkan pesan user
    addMessage(text, 'user');
    input.value = '';

    // Panggil backend AI
    try {
        const response = await fetch('/api/ai-chat', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({message: text, lang: currentLang})
        });
        const data = await response.json();
        addMessage(data.reply, 'ai');
    } catch(err){
        addMessage(currentLang==='id' ? 'Maaf, gagal menghubungi server.' : 'Sorry, failed to reach server.', 'ai');
    }
}

// Kirim pesan dengan Enter
document.getElementById('userInput').addEventListener('keypress', function(e){
    if(e.key === 'Enter') sendMessage();
});
</script>

</body>
</html>
