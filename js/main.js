// Main JavaScript functionality
let currentUser = null;

window.onload = function() {
    checkAuth();
    loadUserData();
    loadCourses();
    loadLeaderboard();
    displayMotivationalQuote();
};

function checkAuth() {
    const userId = localStorage.getItem('user_id');
    if (!userId && window.location.pathname.includes('dashboard')) {
        window.location.href = 'login.html';
    }
}

async function loadUserData() {
    const userId = localStorage.getItem('user_id');
    const username = localStorage.getItem('username');
    
    if (username) {
        const greetingEl = document.getElementById('userGreeting');
        if (greetingEl) {
            greetingEl.textContent = `Hello, ${username}!`;
        }
    }
    
    if (userId) {
        await loadUserBadges(userId);
        await loadStreak(userId);
    }
}

async function loadCourses() {
    const userId = localStorage.getItem('user_id');
    if (!userId) return;
    
    try {
        const response = await fetch(`api/get_courses.php?user_id=${userId}`);
        const data = await response.json();
        
        if (data.success) {
            renderCourses(data.courses);
        }
    } catch (error) {
        console.error('Error loading courses:', error);
    }
}

function renderCourses(courses) {
    const gridDiv = document.getElementById('coursesGrid');
    if (!gridDiv) return;
    
    gridDiv.innerHTML = courses.map(course => {
        const progress = course.progress || 0;
        const status = progress === 0 ? 'Not Started' : 
                      progress === 100 ? 'Completed' : 
                      'In Progress';
        
        return `
            <div class="course-card" onclick="openCourse('${course.slug}')">
                <div class="course-icon">${course.icon}</div>
                <div class="course-title">${course.title}</div>
                <div class="course-status">${status}</div>
                <div class="progress-bar-container">
                    <div class="progress-bar" style="width: ${progress}%"></div>
                </div>
                <div class="course-actions">
                    <button class="btn-primary" onclick="event.stopPropagation(); openCourse('${course.slug}')">
                        ${progress > 0 ? 'Continue' : 'Start'} Learning
                    </button>
                    <button class="btn-secondary" onclick="event.stopPropagation(); window.open('${course.youtube_link}', '_blank')">
                        📺 Video
                    </button>
                </div>
            </div>
        `;
    }).join('');
}

function openCourse(slug) {
    window.location.href = `course.html?course=${slug}`;
}

async function loadUserBadges(userId) {
    try {
        const response = await fetch(`api/get_progress.php?user_id=${userId}`);
        const data = await response.json();
        
        if (data.success && data.badges) {
            renderBadges(data.badges);
        }
    } catch (error) {
        console.error('Error loading badges:', error);
    }
}

function renderBadges(badges) {
    const badgesDiv = document.getElementById('badgesDisplay');
    if (!badgesDiv) return;
    
    if (badges.length === 0) {
        badgesDiv.innerHTML = '<p style="color: #9CA3AF;">No badges yet. Start learning!</p>';
        return;
    }
    
    badgesDiv.innerHTML = badges.map(badge => 
        `<div class="badge-item" title="${badge.name}">${badge.icon}</div>`
    ).join('');
}

async function loadStreak(userId) {
    try {
        const response = await fetch(`api/get_progress.php?user_id=${userId}`);
        const data = await response.json();
        
        if (data.success) {
            const streakDiv = document.getElementById('streakDisplay');
            if (streakDiv) {
                streakDiv.querySelector('.streak-number').textContent = data.streak || 0;
            }
        }
    } catch (error) {
        console.error('Error loading streak:', error);
    }
}

async function loadLeaderboard() {
    try {
        const response = await fetch('api/get_leaderboard.php');
        const data = await response.json();
        
        if (data.success) {
            renderLeaderboard(data.leaderboard);
        }
    } catch (error) {
        console.error('Error loading leaderboard:', error);
    }
}

function renderLeaderboard(leaderboard) {
    const leaderboardDiv = document.getElementById('leaderboardDisplay');
    if (!leaderboardDiv) return;
    
    leaderboardDiv.innerHTML = leaderboard.slice(0, 5).map((user, index) => `
        <div class="leaderboard-item">
            <span>${index + 1}. ${user.username}</span>
            <span>${user.points} pts</span>
        </div>
    `).join('');
}

const motivationalQuotes = [
    "The only way to learn a new programming language is by writing programs in it. - Dennis Ritchie",
    "Code is like humor. When you have to explain it, it's bad. - Cory House",
    "First, solve the problem. Then, write the code. - John Johnson",
    "Experience is the name everyone gives to their mistakes. - Oscar Wilde",
    "The best error message is the one that never shows up. - Thomas Fuchs",
    "Simplicity is the soul of efficiency. - Austin Freeman",
    "Make it work, make it right, make it fast. - Kent Beck",
    "The most disastrous thing that you can ever learn is your first programming language. - Alan Kay",
    "Programs must be written for people to read, and only incidentally for machines to execute. - Harold Abelson",
    "Learning to code is learning to create and innovate. - Unknown"
];

function displayMotivationalQuote() {
    const quoteDiv = document.getElementById('quoteDisplay');
    if (!quoteDiv) return;
    
    const randomQuote = motivationalQuotes[Math.floor(Math.random() * motivationalQuotes.length)];
    quoteDiv.textContent = randomQuote;
}

function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDark);
    
    const button = event.target;
    button.textContent = isDark ? '☀️' : '🌙';
}

if (localStorage.getItem('darkMode') === 'true') {
    document.body.classList.add('dark-mode');
}

function logout() {
    localStorage.clear();
    window.location.href = 'login.html';
}