document.addEventListener('DOMContentLoaded', function(){


const toggleBtn = document.getElementById('chat-toggle');
const chatWidget = document.getElementById('chat-widget');
const closeBtn = document.getElementById('chat-close');

toggleBtn.addEventListener('click', () => {
    chatWidget.style.display = chatWidget.style.display === 'flex' ? 'none' : 'flex';
});

toggleBtn.addEventListener('click', () => {
    if(window.innerWidth <= 768){ 
        // Mobile: buka full screen
        chatWidget.style.display = 'flex';
        chatWidget.classList.add('fullscreen');
        toggleBtn.style.display = 'none';
    } else  {
        
        chatWidget.classList.toggle('open');
        chatWidget.style.display = chatWidget.classList.contains('open') ? 'flex' : 'none';
    }
});
// Tombol close
closeBtn.addEventListener('click', () => {
    chatWidget.style.display = 'none';
    chatWidget.classList.remove('fullscreen');
    toggleBtn.style.display = 'flex';
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
});