<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../login.php');
    exit();
}

$page_title = 'Messages';
ob_start();
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Messages</h1>
            <p class="text-muted">Communicate with lecturers and fellow students</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Contacts List -->
                        <div class="col-md-4">
                            <div class="chat-contacts">
                                <div class="p-3 border-bottom">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" placeholder="Search contacts..." id="searchContacts">
                                    </div>
                                </div>
                                
                                <div class="contacts-list">
                                    <!-- Lecturer Contacts -->
                                    <div class="contact-group">
                                        <div class="contact-group-header p-2 bg-light">
                                            <small class="text-muted fw-bold">Lecturers</small>
                                        </div>
                                        
                                        <div class="contact-item active" data-contact="prof-smith">
                                            <div class="d-flex align-items-center p-3 border-bottom">
                                                <div class="flex-shrink-0">
                                                    <img src="https://via.placeholder.com/40" class="rounded-circle" alt="Prof. Smith">
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">Prof. John Smith</h6>
                                                    <small class="text-muted">Mathematics 101</small>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <span class="badge bg-success rounded-pill">2</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="contact-item" data-contact="prof-johnson">
                                            <div class="d-flex align-items-center p-3 border-bottom">
                                                <div class="flex-shrink-0">
                                                    <img src="https://via.placeholder.com/40" class="rounded-circle" alt="Prof. Johnson">
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">Prof. Sarah Johnson</h6>
                                                    <small class="text-muted">Computer Science</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="contact-item" data-contact="prof-wilson">
                                            <div class="d-flex align-items-center p-3 border-bottom">
                                                <div class="flex-shrink-0">
                                                    <img src="https://via.placeholder.com/40" class="rounded-circle" alt="Prof. Wilson">
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">Prof. Michael Wilson</h6>
                                                    <small class="text-muted">Physics 101</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Student Contacts -->
                                    <div class="contact-group">
                                        <div class="contact-group-header p-2 bg-light">
                                            <small class="text-muted fw-bold">Students</small>
                                        </div>
                                        
                                        <div class="contact-item" data-contact="student-doe">
                                            <div class="d-flex align-items-center p-3 border-bottom">
                                                <div class="flex-shrink-0">
                                                    <img src="https://via.placeholder.com/40" class="rounded-circle" alt="John Doe">
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">John Doe</h6>
                                                    <small class="text-muted">Classmate - CS101</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="contact-item" data-contact="student-brown">
                                            <div class="d-flex align-items-center p-3 border-bottom">
                                                <div class="flex-shrink-0">
                                                    <img src="https://via.placeholder.com/40" class="rounded-circle" alt="Emma Brown">
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">Emma Brown</h6>
                                                    <small class="text-muted">Study Group</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Chat Window -->
                        <div class="col-md-8">
                            <div class="chat-messages">
                                <!-- Chat Header -->
                                <div class="chat-header">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img src="https://via.placeholder.com/40" class="rounded-circle" alt="Prof. Smith" id="chatAvatar">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0" id="chatName">Prof. John Smith</h6>
                                            <small class="text-muted" id="chatStatus">Online</small>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-phone"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary ms-1">
                                                <i class="fas fa-video"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Messages Area -->
                                <div class="messages-area" id="messagesArea">
                                    <!-- Messages will be loaded here -->
                                </div>
                                
                                <!-- Message Input -->
                                <div class="message-input p-3 border-top">
                                    <form id="messageForm">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Type your message..." id="messageInput">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
// Sample messages data
const messages = {
    'prof-smith': [
        { type: 'received', text: 'Hello! How can I help you with the calculus assignment?', time: '10:30 AM' },
        { type: 'sent', text: 'Hi Professor! I have a question about problem 3 in the homework.', time: '10:32 AM' },
        { type: 'received', text: 'Sure! Which part are you having trouble with?', time: '10:33 AM' },
        { type: 'sent', text: 'I\'m not sure how to apply the chain rule in this case.', time: '10:35 AM' },
        { type: 'received', text: 'Let me explain that step by step...', time: '10:36 AM' }
    ],
    'prof-johnson': [
        { type: 'received', text: 'Your programming project looks great!', time: '2:15 PM' },
        { type: 'sent', text: 'Thank you! I worked really hard on it.', time: '2:17 PM' }
    ],
    'prof-wilson': [],
    'student-doe': [
        { type: 'received', text: 'Hey! Are you going to the study group tonight?', time: '4:20 PM' },
        { type: 'sent', text: 'Yes, I\'ll be there at 6 PM.', time: '4:22 PM' }
    ],
    'student-brown': []
};

let currentContact = 'prof-smith';

// Contact click handler
document.querySelectorAll('.contact-item').forEach(item => {
    item.addEventListener('click', function() {
        // Remove active class from all contacts
        document.querySelectorAll('.contact-item').forEach(c => c.classList.remove('active'));
        
        // Add active class to clicked contact
        this.classList.add('active');
        
        // Load messages for this contact
        const contactId = this.dataset.contact;
        loadMessages(contactId);
        
        // Update chat header
        updateChatHeader(this);
    });
});

function loadMessages(contactId) {
    currentContact = contactId;
    const messagesArea = document.getElementById('messagesArea');
    const contactMessages = messages[contactId] || [];
    
    messagesArea.innerHTML = '';
    
    contactMessages.forEach(message => {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message-bubble ${message.type}`;
        messageDiv.innerHTML = `
            <div class="bubble">${message.text}</div>
            <div class="message-time">${message.time}</div>
        `;
        messagesArea.appendChild(messageDiv);
    });
    
    // Scroll to bottom
    messagesArea.scrollTop = messagesArea.scrollHeight;
}

function updateChatHeader(contactElement) {
    const nameElement = contactElement.querySelector('h6');
    const statusElement = contactElement.querySelector('small');
    
    document.getElementById('chatName').textContent = nameElement.textContent;
    document.getElementById('chatStatus').textContent = 'Online';
}

// Message form handler
document.getElementById('messageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (message && currentContact) {
        // Add message to the conversation
        const now = new Date();
        const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        const newMessage = {
            type: 'sent',
            text: message,
            time: timeString
        };
        
        if (!messages[currentContact]) {
            messages[currentContact] = [];
        }
        messages[currentContact].push(newMessage);
        
        // Reload messages
        loadMessages(currentContact);
        
        // Clear input
        input.value = '';
        
        // Simulate reply after 2 seconds
        setTimeout(() => {
            const replies = [
                'Thanks for your message!',
                'I\'ll get back to you soon.',
                'That\'s a good question.',
                'Let me check on that for you.',
                'I\'m available for office hours tomorrow.'
            ];
            
            const randomReply = replies[Math.floor(Math.random() * replies.length)];
            const replyTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            const replyMessage = {
                type: 'received',
                text: randomReply,
                time: replyTime
            };
            
            messages[currentContact].push(replyMessage);
            loadMessages(currentContact);
        }, 2000);
    }
});

// Search functionality
document.getElementById('searchContacts').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const contactItems = document.querySelectorAll('.contact-item');
    
    contactItems.forEach(item => {
        const name = item.querySelector('h6').textContent.toLowerCase();
        const description = item.querySelector('small').textContent.toLowerCase();
        
        if (name.includes(searchTerm) || description.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Load initial messages
loadMessages('prof-smith');
</script>

<?php
$page_content = ob_get_clean();
include '../includes/layout.php';
?> 