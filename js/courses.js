document.addEventListener('DOMContentLoaded', function() {
    const coursesContainer = document.getElementById('courses-container');
    const addCourseBtn = document.getElementById('add-course-btn');
    const courseModal = document.getElementById('course-modal');
    const closeBtn = document.querySelector('.close-btn');
    const courseForm = document.getElementById('course-form');
    
    // Load courses
    fetchCourses();
    
    // Modal controls
    addCourseBtn.addEventListener('click', () => courseModal.style.display = 'block');
    closeBtn.addEventListener('click', () => courseModal.style.display = 'none');
    window.addEventListener('click', (e) => {
        if (e.target === courseModal) courseModal.style.display = 'none';
    });
    
    // Handle form submission
    courseForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const courseData = {
            title: document.getElementById('course-title').value,
            code: document.getElementById('course-code').value,
            description: document.getElementById('course-desc').value
        };
        
        fetch('php/courses.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(courseData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                courseModal.style.display = 'none';
                courseForm.reset();
                fetchCourses();
            } else {
                alert('Error: ' + data.message);
            }
        });
    });
    
    function fetchCourses() {
        fetch('php/courses.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderCourses(data.courses);
                }
            });
    }
    
    function renderCourses(courses) {
        coursesContainer.innerHTML = '';
        
        if (courses.length === 0) {
            coursesContainer.innerHTML = '<p class="no-courses">No courses found</p>';
            return;
        }
        // Highlight current page in sidebar
document.addEventListener('DOMContentLoaded', function() {
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.sidebar nav a');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
});
        
        courses.forEach(course => {
            const courseCard = document.createElement('div');
            courseCard.className = 'course-card';
            courseCard.innerHTML = `
                <div class="course-header">
                    <h3>${course.title}</h3>
                    <span class="course-code">${course.code}</span>
                </div>
                <p class="course-desc">${course.description || 'No description'}</p>
                <div class="course-actions">
                    <a href="classroom.html?course=${course.id}" class="btn enter-btn">Enter Classroom</a>
                    <a href="assignments.html?course=${course.id}" class="btn assignments-btn">View Assignments</a>
                </div>
            `;
            coursesContainer.appendChild(courseCard);
        });
    }
});