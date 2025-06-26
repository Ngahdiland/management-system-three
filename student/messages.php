<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

$page_title = 'Messages';
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$user_role = $_SESSION['role'];
ob_start();
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Messages</h1>
            <p class="text-muted">Private chat with lecturers and support (admin).</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0 me-3"><i class="fas fa-comments me-2"></i>Private Chat</h5>
                    <select id="recipientSelect" class="form-select w-auto ms-auto" style="min-width:200px;"></select>
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

<style>
.contact-item {
    cursor: pointer;
    transition: background-color 0.2s;
}

.contact-item:hover {
    background-color: #f8f9fa;
}

.contact-item.active {
    background-color: #e3f2fd;
}

.contact-item.active .contact-group-header {
    background-color: #e3f2fd !important;
}

.messages-area {
    height: 400px;
    overflow-y: auto;
    padding: 20px;
}

.message-bubble {
    margin-bottom: 15px;
    max-width: 70%;
}

.message-bubble.sent {
    margin-left: auto;
}

.message-bubble .bubble {
    padding: 10px 15px;
    border-radius: 18px;
    display: inline-block;
    position: relative;
}

.message-bubble.received .bubble {
    background: #e9ecef;
    color: #333;
}

.message-bubble.sent .bubble {
    background: #667eea;
    color: white;
}

.message-time {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 5px;
}

.message-bubble.sent .message-time {
    text-align: right;
}

.chat-contacts {
    height: 600px;
    overflow-y: auto;
}

.contacts-list {
    height: calc(100% - 70px);
    overflow-y: auto;
}
</style>

<script>
const chatBody = document.getElementById('chatBody');
const chatForm = document.getElementById('chatForm');
const chatInput = document.getElementById('chatInput');
const recipientSelect = document.getElementById('recipientSelect');
const userId = <?php echo json_encode($user_id); ?>;
const userName = <?php echo json_encode($user_name); ?>;
const userRole = <?php echo json_encode($user_role); ?>;
let recipientId = null;
let recipientName = '';
let recipientRole = '';

function fetchUsers() {
    fetch('../getUsers.php')
        .then(res => res.json())
        .then(users => {
            recipientSelect.innerHTML = '<option value="">Select recipient...</option>';
            users.forEach(u => {
                const label = `${u.name} (${u.role.charAt(0).toUpperCase() + u.role.slice(1)})`;
                recipientSelect.innerHTML += `<option value="${u.id}" data-role="${u.role}">${label}</option>`;
            });
        });
}

function fetchMessages() {
    if (!recipientId) {
        chatBody.innerHTML = '<div class="text-center text-muted mt-5">Select a recipient to start chatting.</div>';
        return;
    }
    fetch('../messages.json')
        .then(res => res.json())
        .then(data => {
            chatBody.innerHTML = '';
            data.filter(msg =>
                (msg.from_id == userId && msg.to_id == recipientId) ||
                (msg.from_id == recipientId && msg.to_id == userId)
            ).forEach(msg => {
                const align = msg.from_id == userId ? 'text-end' : 'text-start';
                const bg = msg.from_id == userId ? 'bg-primary text-white' : 'bg-light';
                chatBody.innerHTML += `<div class='mb-2 ${align}'><span class='badge ${bg}'>${msg.from_name}: ${msg.text}</span><br><small class='text-muted'>${msg.time}</small></div>`;
            });
            chatBody.scrollTop = chatBody.scrollHeight;
        });
}

recipientSelect.addEventListener('change', function() {
    recipientId = this.value;
    recipientName = this.options[this.selectedIndex].text;
    recipientRole = this.options[this.selectedIndex].getAttribute('data-role');
    fetchMessages();
});

chatForm.addEventListener('submit', function(e) {
    e.preventDefault();
    if (!recipientId) return;
    const text = chatInput.value.trim();
    if (!text) return;
    const now = new Date();
    const time = now.toLocaleString();
    const msg = {
        from_id: userId,
        from_role: userRole,
        from_name: userName,
        to_id: recipientId,
        to_role: recipientRole,
        to_name: recipientName,
        text,
        time
    };
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
fetchUsers();
</script>

<?php
$page_content = ob_get_clean();
include '../includes/layout.php';
?> 