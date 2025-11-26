// Global JavaScript for all pages
document.addEventListener('DOMContentLoaded', function() {
    // Handle login form submission
    if (document.getElementById('loginForm')) {
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            
            // Simulate login (replace with actual fetch)
            console.log('Login attempt with:', Object.fromEntries(formData));
            
            // Redirect to dashboard on success
            window.location.href = 'dashboard.html';
        });
    }
    
    // Handle registration form submission
    if (document.getElementById('registerForm')) {
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            
            // Simulate registration (replace with actual fetch)
            console.log('Registration with:', Object.fromEntries(formData));
            
            // Redirect to dashboard on success
            window.location.href = 'dashboard.html';
        });
    }
    
    // Mobile menu toggle
    if (document.querySelector('.mobile-menu-btn')) {
        document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    }
    
    // Profile dropdown toggle
    if (document.querySelector('.profile-section')) {
        document.querySelector('.profile-section').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('profile-dropdown').classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            document.getElementById('profile-dropdown').classList.remove('show');
        });
    }
    
    // Logout functionality
    if (document.getElementById('logout-link')) {
        document.getElementById('logout-link').addEventListener('click', function(e) {
            e.preventDefault();
            // In a real app, you would call your logout API here
            alert('Logging out... Redirecting to login page.');
            window.location.href = 'index.html';
        });
    }
    
    // Classroom tab switching
    if (document.querySelectorAll('.tab-btn')) {
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Remove active class from all tabs and buttons
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                document.getElementById(`${tabId}-tab`).classList.add('active');
            });
        });
    }
    
    // Whiteboard functionality (simplified)
    if (document.getElementById('whiteboard')) {
        const canvas = document.getElementById('whiteboard');
        const ctx = canvas.getContext('2d');
        let isDrawing = false;
        
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);
        
        function startDrawing(e) {
            isDrawing = true;
            draw(e);
        }
        
        function draw(e) {
            if (!isDrawing) return;
            
            ctx.lineWidth = document.getElementById('draw-width').value;
            ctx.lineCap = 'round';
            ctx.strokeStyle = document.getElementById('draw-color').value;
            
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(e.offsetX, e.offsetY);
        }
        
        function stopDrawing() {
            isDrawing = false;
            ctx.beginPath();
        }
        
        if (document.getElementById('clear-whiteboard')) {
            document.getElementById('clear-whiteboard').addEventListener('click', function() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            });
        }
    }
    
    // Classroom chat functionality (simplified)
    if (document.getElementById('send-message')) {
        document.getElementById('send-message').addEventListener('click', sendMessage);
        document.getElementById('message-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMessage();
        });
        
        function sendMessage() {
            const input = document.getElementById('message-input');
            const message = input.value.trim();
            
            if (message) {
                const chatContainer = document.getElementById('chat-messages');
                const messageElement = document.createElement('div');
                messageElement.className = 'message';
                messageElement.innerHTML = `
                    <div class="message-sender">You</div>
                    <div class="message-content">${message}</div>
                    <div class="message-time">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
                `;
                chatContainer.appendChild(messageElement);
                input.value = '';
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        }
    }
});