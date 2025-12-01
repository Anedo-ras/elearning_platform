// Admin panel functionality
window.onload = function() {
    const isAdmin = localStorage.getItem('is_admin');
    if (isAdmin !== '1' && isAdmin !== 'true') {
        window.location.href = 'login.html';
        return;
    }
    
    loadStats();
};

function showSection(section) {
    document.querySelectorAll('.admin-section').forEach(s => s.classList.add('hidden'));
    document.querySelectorAll('.menu-item').forEach(m => m.classList.remove('active'));
    
    document.getElementById(`${section}Section`).classList.remove('hidden');
    event.target.classList.add('active');
    
    if (section === 'users') {
        loadUsers();
    } else if (section === 'courses') {
        loadCourses();
    }
}

async function loadStats() {
    try {
        const response = await fetch('api/admin/get_stats.php');
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('totalUsers').textContent = data.stats.total_users;
            document.getElementById('totalCourses').textContent = data.stats.total_courses;
            document.getElementById('totalLessons').textContent = data.stats.total_lessons;
            document.getElementById('activeToday').textContent = data.stats.active_today;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

async function loadUsers() {
    try {
        const response = await fetch('api/admin/get_users.php');
        const data = await response.json();
        
        if (data.success) {
            renderUsersTable(data.users);
        }
    } catch (error) {
        console.error('Error loading users:', error);
    }
}

function renderUsersTable(users) {
    const tableDiv = document.getElementById('usersTable');
    tableDiv.innerHTML = `
        <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
            <thead>
                <tr style="background: var(--light); text-align: left;">
                    <th style="padding: 1rem;">ID</th>
                    <th style="padding: 1rem;">Username</th>
                    <th style="padding: 1rem;">Email</th>
                    <th style="padding: 1rem;">Full Name</th>
                    <th style="padding: 1rem;">Points</th>
                    <th style="padding: 1rem;">Streak</th>
                    <th style="padding: 1rem;">Joined</th>
                </tr>
            </thead>
            <tbody>
                ${users.map(user => `
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 1rem;">${user.id}</td>
                        <td style="padding: 1rem;">${user.username}</td>
                        <td style="padding: 1rem;">${user.email}</td>
                        <td style="padding: 1rem;">${user.full_name || 'N/A'}</td>
                        <td style="padding: 1rem;">${user.total_points}</td>
                        <td style="padding: 1rem;">${user.current_streak}</td>
                        <td style="padding: 1rem;">${new Date(user.created_at).toLocaleDateString()}</td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
}

async function loadCourses() {
    try {
        const response = await fetch('api/admin/manage_courses.php?action=list');
        const data = await response.json();
        
        if (data.success) {
            renderCoursesTable(data.courses);
        }
    } catch (error) {
        console.error('Error loading courses:', error);
    }
}

function renderCoursesTable(courses) {
    const tableDiv = document.getElementById('coursesTable');
    tableDiv.innerHTML = `
        <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
            <thead>
                <tr style="background: var(--light); text-align: left;">
                    <th style="padding: 1rem;">Icon</th>
                    <th style="padding: 1rem;">Title</th>
                    <th style="padding: 1rem;">Slug</th>
                    <th style="padding: 1rem;">Difficulty</th>
                    <th style="padding: 1rem;">Active</th>
                    <th style="padding: 1rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                ${courses.map(course => `
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 1rem; font-size: 1.5rem;">${course.icon}</td>
                        <td style="padding: 1rem;">${course.title}</td>
                        <td style="padding: 1rem;">${course.slug}</td>
                        <td style="padding: 1rem;">${course.difficulty}</td>
                        <td style="padding: 1rem;">${course.is_active ? '✓' : '✗'}</td>
                        <td style="padding: 1rem;">
                            <button onclick="toggleCourseStatus(${course.id}, ${course.is_active})" class="btn-secondary" style="padding: 0.5rem 1rem; margin-right: 0.5rem;">
                                ${course.is_active ? 'Deactivate' : 'Activate'}
                            </button>
                            <button onclick="deleteCourse(${course.id})" class="btn-secondary" style="padding: 0.5rem 1rem; background: var(--error); color: white;">
                                Delete
                            </button>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
}

function showAddCourseModal() {
    document.getElementById('addCourseModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('addCourseModal').classList.add('hidden');
}

async function saveCourse() {
    const courseData = {
        title: document.getElementById('courseTitle').value,
        slug: document.getElementById('courseSlug').value,
        description: document.getElementById('courseDescription').value,
        youtube_link: document.getElementById('courseYouTube').value,
        icon: document.getElementById('courseIcon').value
    };
    
    if (!courseData.title || !courseData.slug) {
        alert('Please fill in required fields');
        return;
    }
    
    try {
        const response = await fetch('api/admin/manage_courses.php?action=add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(courseData)
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Course added successfully!');
            closeModal();
            loadCourses();
            
            document.getElementById('courseTitle').value = '';
            document.getElementById('courseSlug').value = '';
            document.getElementById('courseDescription').value = '';
            document.getElementById('courseYouTube').value = '';
            document.getElementById('courseIcon').value = '';
        } else {
            alert('Error adding course: ' + data.message);
        }
    } catch (error) {
        console.error('Error saving course:', error);
        alert('Error saving course');
    }
}

async function toggleCourseStatus(courseId, currentStatus) {
    try {
        const response = await fetch('api/admin/manage_courses.php?action=toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                course_id: courseId,
                is_active: !currentStatus
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            loadCourses();
        }
    } catch (error) {
        console.error('Error toggling course status:', error);
    }
}

async function deleteCourse(courseId) {
    if (!confirm('Are you sure you want to delete this course? This will also delete all lessons.')) {
        return;
    }
    
    try {
        const response = await fetch('api/admin/manage_courses.php?action=delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                course_id: courseId
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Course deleted successfully');
            loadCourses();
        } else {
            alert('Error deleting course');
        }
    } catch (error) {
        console.error('Error deleting course:', error);
    }
}

function logout() {
    localStorage.clear();
    window.location.href = 'login.html';
}