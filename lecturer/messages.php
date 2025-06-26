<?php
include '../includes/layout.php';
$page_title = 'Messages';
ob_start();
?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Messages</h1>
            <p class="text-muted">Chat with students and admin for academic communication.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Lecturer Chat</h5>
                </div>
                <div class="card-body" id="chatBody" style="height: 350px; overflow-y: auto;"></div>
                <div class="card-footer">
                    <form id="chatForm" class="d-flex">
                        <input type="text" class="form-control me-2" id="chatInput" placeholder="Type your message...">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
const chatBody = document.getElementById('chatBody');
const chatForm = document.getElementById('chatForm');
const chatInput = document.getElementById('chatInput');
const userRole = 'lecturer';
const userName = '<?php echo $_SESSION['name'] ?? "Lecturer"; ?>';

function fetchMessages() {
    fetch('../messages.json')
        .then(res => res.json())
        .then(data => {
            chatBody.innerHTML = '';
            data.forEach(msg => {
                const align = msg.role === userRole ? 'text-end' : 'text-start';
                const bg = msg.role === userRole ? 'bg-primary text-white' : 'bg-light';
                chatBody.innerHTML += `<div class='mb-2 ${align}'><span class='badge ${bg}'>${msg.name}: ${msg.text}</span><br><small class='text-muted'>${msg.time}</small></div>`;
            });
            chatBody.scrollTop = chatBody.scrollHeight;
        });
}

chatForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const text = chatInput.value.trim();
    if (!text) return;
    const now = new Date();
    const time = now.toLocaleString();
    const msg = { role: userRole, name: userName, text, time };
    fetch('../sendMessage.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(msg)
    }).then(() => {
        chatInput.value = '';
        fetchMessages();
    });
});

setInterval(fetchMessages, 1500);
fetchMessages();
</script>
<?php
$page_content = ob_get_clean();
include '../includes/layout.php'; 