<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>BPB UNM Chat Widget</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* Tombol chat melayang */
#chat-toggle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #0d6efd;
    color: #fff;
    border: none;
    font-size: 30px;
    cursor: pointer;
    z-index: 9999;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

/* Container chat melayang */
#chat-widget {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 350px;
    max-height: 500px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    display: none;
    flex-direction: column;
    overflow: hidden;
    z-index: 9998;
}

/* Header chat */
#chat-widget .chat-header {
    background: #0d6efd;
    color: #fff;
    padding: 12px;
    font-weight: bold;
    text-align: center;
}

/* Chat box */
#chat-box {
    flex: 1;
    padding: 10px;
    overflow-y: auto;
    background: #f8f9fa;
}

/* Form input */
#chat-form {
    display: flex;
    padding: 10px;
    border-top: 1px solid #ddd;
}

/* Chat bubble */
.chat-message { display: flex; margin-bottom: 10px; }
.chat-message.user { justify-content: flex-end; }
.chat-message.ai { justify-content: flex-start; }
.bubble { padding: 8px 12px; border-radius: 20px; max-width: 70%; }
.bubble.user { background: #0d6efd; color: #fff; border-bottom-right-radius: 0; }
.bubble.ai { background: #e9ecef; color: #000; border-bottom-left-radius: 0; }
</style>
</head>
<body>

<!-- Tombol toggle -->
<button id="chat-toggle">💬</button>

<!-- Chat widget -->
<div id="chat-widget">
    <div class="chat-header">BPB UNM Help Desk</div>
    <div id="chat-box"></div>
    <form id="chat-form">
        <input type="text" class="form-control me-2" id="message" placeholder="Tulis pertanyaan..." required>
        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
// Toggle widget
const toggleBtn = document.getElementById('chat-toggle');
const chatWidget = document.getElementById('chat-widget');

toggleBtn.addEventListener('click', () => {
    chatWidget.style.display = chatWidget.style.display === 'flex' ? 'none' : 'flex';
});

// Chat functionality
const chatBox = document.getElementById('chat-box');
const chatForm = document.getElementById('chat-form');
const messageInput = document.getElementById('message');

chatForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const msg = messageInput.value.trim();
    if(!msg) return;

    addMessage(msg, 'user');

    const typingBubble = addMessage('<em>Mengetik...</em>', 'ai', true);
    messageInput.value = '';

    try {
        const response = await axios.post('/api/ai-chat', { message: msg });
        const reply = response.data.reply;
        typingBubble.remove();
        addMessage(reply, 'ai');
    } catch (err) {
        typingBubble.remove();
        addMessage('Gagal menghubungi server.', 'ai');
    }
});

function addMessage(text, role, isTyping=false){
    const msgDiv = document.createElement('div');
    msgDiv.classList.add('chat-message', role);

    const bubble = document.createElement('div');
    bubble.classList.add('bubble', role);

    if(isTyping){
        bubble.innerHTML = text;
    } else {
        bubble.innerHTML = text.replace(/\n/g, '<br>');
    }

    msgDiv.appendChild(bubble);
    chatBox.appendChild(msgDiv);
    chatBox.scrollTop = chatBox.scrollHeight;

    return msgDiv;
}
</script>

</body>
</html>
