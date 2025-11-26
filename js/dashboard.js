document.addEventListener('DOMContentLoaded', function() {
    // Load user data
    loadUserData();
    
    // Load dashboard content
    loadDashboardContent();
    
    // Setup event listeners
    setupEventListeners();
});

function loadUserData() {
    // In a real app, you would fetch this from the server
    const userData = {
        name: "John Doe",
        role: "Student",
        email: "john@student.edu",
        profilePic: "images/profile-placeholder.png"
    };
    
    document.getElementById('username').textContent = userData.name;
    document.getElementById('user-role').textContent = userData.role;
    document.getElementById('profile-image').src = userData.profilePic;
    
    // Set greeting based on time of day
    const hour = new Date().getHours();
    const greetingName = userData.name.split(' ')[0];
    let greeting;
    
    if (hour < 12) {
        greeting = `Good morning, ${greetingName}`;
    } else if (hour < 18) {
        greeting = `Good afternoon, ${greetingName}`;
    } else {
        greeting = `Good evening, ${greetingName}`;
    }
    
    document.getElementById('greeting-name').textContent = greeting;
}

function loadDashboardContent() {
    // Fetch data from server
    fetch('php/dashboard.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update stats
                document.getElementById('active-courses').textContent = data.stats.courses;
                document.getElementById('pending-assignments').textContent = data.stats.assignments;
                document.getElementById('upcoming-classes').textContent = data.stats.classes;
                
                // Update schedule
                updateSchedule(data.schedule);
                
                // Update announcements
                updateAnnouncements(data.announcements);
            }
        });
}

function updateSchedule(schedule) {
    const container = document.getElementById('today-schedule');
    
    if (schedule.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <p>No classes scheduled for today</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = '';
    schedule.forEach(item => {
        const scheduleItem = document.createElement('div');
        scheduleItem.className = 'schedule-item';
        scheduleItem.innerHTML = `
            <div class="schedule-time">${item.time}</div>
            <div class="schedule-details">
                <h4>${item.course}</h4>
                <p>${item.instructor} • ${item.location}</p>
            </div>
            <button class="btn btn-primary join-btn">Join</button>
        `;
        container.appendChild(scheduleItem);
    });
}

function updateAnnouncements(announcements) {
    const container = document.getElementById('announcements-list');
    
    if (announcements.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-bell-slash"></i>
                <p>No recent announcements</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = '';
    announcements.forEach(announcement => {
        const announcementItem = document.createElement('div');
        announcementItem.className = 'announcement-item';
        announcementItem.innerHTML = `
            <h4><i class="fas fa-bullhorn"></i> ${announcement.title}</h4>
            <p>${announcement.content}</p>
            <div class="announcement-meta">
                <span>${announcement.course}</span>
                <span>${announcement.date}</span>
            </div>
        `;
        container.appendChild(announcementItem);
    });
}

function setupEventListeners() {
    // Mobile menu toggle
    document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('active');
    });
    
    // Update date and time
    updateDateTime();
    setInterval(updateDateTime, 60000);
    
    // Join class buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('join-btn')) {
            const course = e.target.closest('.schedule-item').querySelector('h4').textContent;
            alert(`Joining ${course} class...`);
            window.location.href = 'classroom.html';
        }
    });
}

function updateDateTime() {
    const now = new Date();
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    document.getElementById('datetime').textContent = now.toLocaleDateString('en-US', options);
}