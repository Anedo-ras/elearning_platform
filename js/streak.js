// Streak tracking system
async function updateStreak() {
    const userId = localStorage.getItem('user_id');
    if (!userId) return;
    
    try {
        const response = await fetch('api/update_streak.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                user_id: userId
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            updateStreakDisplay(data.streak);
            
            if (data.new_streak) {
                showStreakNotification(data.streak);
            }
        }
    } catch (error) {
        console.error('Error updating streak:', error);
    }
}

function updateStreakDisplay(streak) {
    const streakDisplay = document.getElementById('streakDisplay');
    if (streakDisplay) {
        streakDisplay.querySelector('.streak-number').textContent = streak;
    }
}

function showStreakNotification(streak) {
    let message = '';
    let emoji = '🔥';
    
    if (streak === 1) {
        message = "Great start! You've begun your learning streak!";
        emoji = '🎯';
    } else if (streak === 3) {
        message = "3 days in a row! Keep it up!";
    } else if (streak === 7) {
        message = "Week Warrior! 7 days streak achieved!";
        emoji = '⭐';
    } else if (streak === 14) {
        message = "Two weeks! You're on fire!";
    } else if (streak === 30) {
        message = "Month Champion! 30 days streak! Incredible!";
        emoji = '🏆';
    } else if (streak % 7 === 0) {
        message = `${streak} days streak! Amazing dedication!`;
    } else {
        message = `${streak} days streak! Keep going!`;
    }
    
    const notification = document.createElement('div');
    notification.className = 'notification streak-notification';
    notification.innerHTML = `${emoji} ${message}`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

function checkStreakStatus() {
    const lastActivity = localStorage.getItem('last_activity_date');
    const today = new Date().toISOString().split('T')[0];
    
    if (lastActivity !== today) {
        localStorage.setItem('last_activity_date', today);
        return true;
    }
    
    return false;
}

setInterval(async () => {
    if (checkStreakStatus()) {
        await updateStreak();
    }
}, 60000);