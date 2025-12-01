# 🎓 EduCode - E-Learning Platform for Underprivileged Students

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-777BB4?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?logo=mysql)
![Status](https://img.shields.io/badge/status-active-success.svg)

> **Democratizing tech education through free, accessible, and gamified learning experiences.**

A complete full-stack e-learning platform designed to provide free coding education to underprivileged students worldwide. Built with PHP, MySQL, and vanilla JavaScript, featuring an AI chatbot, gamification system, and comprehensive progress tracking.

---

## 📋 Table of Contents

- [Features](#-features)
- [Demo](#-demo)
- [Tech Stack](#-tech-stack)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
- [Database Setup](#-database-setup)
- [Configuration](#-configuration)
- [Usage](#-usage)
- [Project Structure](#-project-structure)
- [API Endpoints](#-api-endpoints)
- [Screenshots](#-screenshots)
- [Roadmap](#-roadmap)
- [Contributing](#-contributing)
- [License](#-license)
- [Contact](#-contact)

---

## ✨ Features

### 🎯 Core Features
- **10+ Programming Languages**: HTML, CSS, JavaScript, PHP, Python, Java, C++, SQL, Flutter, Node.js
- **100% Free Access**: No subscriptions, no hidden costs
- **Responsive Design**: Works seamlessly on mobile, tablet, and desktop
- **Secure Authentication**: Password hashing with bcrypt, session management
- **Progress Tracking**: Visual dashboards showing course completion rates

### 🎮 Gamification System
- **Daily Streak Tracking**: Duolingo-style streak system to build habits
- **Achievement Badges**: Earn 8+ badges for milestones (First Step, Week Warrior, Month Champion, etc.)
- **Leaderboard**: Compete with peers based on points and streaks
- **Points System**: Earn points for completing lessons and quizzes

### 📚 Learning Tools
- **Interactive Lessons**: Step-by-step tutorials with code examples
- **Video Tutorials**: Embedded YouTube videos for each course
- **Quizzes**: Multiple-choice questions to test knowledge
- **Downloadable Cheat Sheets**: Quick reference materials
- **AI Chatbot**: 24/7 learning assistant for instant help

### 👨‍💼 Admin Panel
- **User Management**: Track registrations and monitor student progress
- **Course Management**: Add, edit, delete courses and lessons
- **Analytics Dashboard**: View total users, active learners, completion rates
- **Activity Tracking**: Monitor daily active users and engagement

### 🎨 Additional Features
- **Dark/Light Mode**: Toggle for comfortable learning
- **Motivational Quotes**: Daily inspiration for students
- **Login History**: Track user login times and activity
- **Real-time Notifications**: In-app notifications for achievements

---

## 🎥 Demo

**Live Demo**: [Coming Soon]

**Login Credentials**:
- **Admin**:
  - Username: `admin`
  - Password: `admin123`
- **Test Student**: Create your own account

### Quick Tour:
1. Sign up for a free account
2. Browse 10+ programming courses
3. Start learning with interactive lessons
4. Take quizzes to test your knowledge
5. Earn badges and maintain your streak
6. Compete on the leaderboard

---

## 🛠️ Tech Stack

### Frontend
- **HTML5**: Semantic markup
- **CSS3**: Custom styling with responsive design
- **JavaScript (Vanilla)**: No frameworks, pure JS
- **Font Awesome 6.4.0**: Icon library (CDN)

### Backend
- **PHP 8.0+**: Server-side logic
- **MySQL 5.7+**: Database management
- **RESTful API**: JSON-based endpoints

### Security
- **Password Hashing**: bcrypt algorithm
- **SQL Injection Prevention**: Prepared statements
- **Session Management**: Secure session handling
- **CORS Configuration**: Proper cross-origin setup

### Development Tools
- **XAMPP/WAMP/Laragon**: Local development server
- **MySQL Workbench**: Database administration
- **VS Code**: Recommended code editor

---

## 📦 Prerequisites

Before installation, ensure you have:

- **PHP** >= 8.0
- **MySQL** >= 5.7
- **Apache Server** (via XAMPP, WAMP, or Laragon)
- **Web Browser** (Chrome, Firefox, Safari, Edge)
- **MySQL Workbench** (optional, for database management)

---

## 🚀 Installation

### Step 1: Clone the Repository

```bash
git clone https://github.com/Anedo-ras/educode-platform.git
cd educode-platform
```

### Step 2: Move to Web Server Directory

Copy the project to your web server's root directory:

**XAMPP (Windows)**:
```bash
xcopy /E /I educode-platform C:\xampp\htdocs\educode-platform
```

**XAMPP (Mac/Linux)**:
```bash
cp -r educode-platform /Applications/XAMPP/htdocs/educode-platform
```

**WAMP**:
```bash
copy educode-platform C:\wamp64\www\educode-platform
```

### Step 3: Start Services

1. Open **XAMPP Control Panel** (or WAMP/Laragon)
2. Start **Apache** service
3. Start **MySQL** service
4. Verify both are running (green indicators)

---

## 🗄️ Database Setup

### Method 1: Using MySQL Workbench (Recommended)

1. Open **MySQL Workbench**
2. Connect to `localhost` (default password is usually blank)
3. Open the SQL file:
   ```
   File → Open SQL Script → Select database/schema.sql
   ```
4. Execute the script: Click the ⚡ lightning bolt icon
5. Verify database creation:
   ```sql
   SHOW DATABASES;
   USE elearning_platform;
   SHOW TABLES;
   ```

### Method 2: Using phpMyAdmin

1. Navigate to `http://localhost/phpmyadmin`
2. Click **Import** tab
3. Choose file: `database/schema.sql`
4. Click **Go**
5. Verify the `elearning_platform` database appears

### Method 3: Command Line

```bash
mysql -u root -p
# Enter password (usually blank for local)

# Then run:
source /path/to/database/schema.sql
```

### Verify Database Setup

Run this test query:
```sql
SELECT * FROM users WHERE username = 'admin';
```

You should see the default admin user.

---

## ⚙️ Configuration

### 1. Database Configuration

Edit `api/config.php`:

```php
define('DB_HOST', 'localhost');      // Change if needed
define('DB_USER', 'root');           // Your MySQL username
define('DB_PASS', '');               // Your MySQL password
define('DB_NAME', 'elearning_platform');
```

### 2. Test Database Connection

Navigate to:
```
http://localhost/educode-platform/test_db.php
```

You should see all tests passing with green checkmarks ✓

### 3. Debug Mode (Optional)

For development, enable error reporting in `api/config.php`:

```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

**⚠️ Disable in production!**

---

## 📖 Usage

### For Students

1. **Sign Up**:
   - Navigate to `http://localhost/educode-platform/register.html`
   - Fill in: Full Name, Username, Email, Password
   - Click "Sign Up"

2. **Login**:
   - Go to `http://localhost/educode-platform/login.html`
   - Enter username/email and password
   - Click "Login"

3. **Browse Courses**:
   - View available courses on the dashboard
   - See progress bars for each course
   - Click "Start Learning" or "Continue Learning"

4. **Take Lessons**:
   - Read lesson content
   - Watch embedded video tutorials
   - Mark lessons as complete
   - Take quizzes to test knowledge

5. **Track Progress**:
   - View your daily streak
   - Earn badges for achievements
   - Check leaderboard position
   - Monitor course completion percentage

### For Administrators

1. **Admin Login**:
   - Username: `admin`
   - Password: `admin123`
   - Redirects to `admin.html`

2. **View Statistics**:
   - Total users
   - Total courses
   - Active users today
   - Completed lessons

3. **Manage Users**:
   - View all registered users
   - Monitor user progress
   - Track activity

4. **Manage Courses**:
   - Add new courses
   - Edit existing courses
   - Delete courses
   - Toggle course active status

---

## 📁 Project Structure

```
educode-platform/
├── index.html              # Landing page
├── login.html              # Login page
├── register.html           # Registration page
├── dashboard.html          # Student dashboard
├── course.html             # Course/lesson page
├── admin.html              # Admin panel
├── test_db.php            # Database connection tester
│
├── css/
│   └── style.css          # All styling (responsive)
│
├── js/
│   ├── main.js            # Core functionality
│   ├── chatbot.js         # AI chatbot logic
│   ├── streak.js          # Streak tracking
│   └── admin.js           # Admin panel JS
│
├── api/                   # Backend PHP files
│   ├── config.php         # Database configuration
│   ├── register.php       # User registration
│   ├── login.php          # User authentication
│   ├── logout.php         # Session destruction
│   ├── get_courses.php    # Fetch all courses
│   ├── get_course_detail.php  # Course details
│   ├── update_progress.php    # Save lesson progress
│   ├── get_progress.php       # Fetch user progress
│   ├── submit_quiz.php        # Submit quiz answers
│   ├── update_streak.php      # Update daily streak
│   ├── get_leaderboard.php    # Leaderboard data
│   │
│   └── admin/             # Admin-only endpoints
│       ├── get_stats.php       # Platform statistics
│       ├── get_users.php       # User list
│       └── manage_courses.php  # CRUD for courses
│
├── database/
│   └── schema.sql         # Complete database schema
│
├── img/                   # Images (optional)
│   └── badges/           # Badge icons
│
├── debug.log             # Auto-generated debug log
├── README.md             # This file
└── LICENSE               # MIT License
```

---

## 🔌 API Endpoints

### Authentication

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/register.php` | Register new user | No |
| POST | `/api/login.php` | User login | No |
| GET | `/api/logout.php` | User logout | Yes |

### Courses

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/get_courses.php?user_id={id}` | Get all courses with progress | Yes |
| GET | `/api/get_course_detail.php?slug={slug}&user_id={id}` | Get course details and lessons | Yes |

### Progress

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/update_progress.php` | Mark lesson as complete | Yes |
| GET | `/api/get_progress.php?user_id={id}` | Get user progress, badges, streak | Yes |
| POST | `/api/submit_quiz.php` | Submit quiz answers | Yes |
| POST | `/api/update_streak.php` | Update daily streak | Yes |

### Leaderboard

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/get_leaderboard.php` | Get top 10 users | No |

### Admin (Authentication Required)

| Method | Endpoint | Description | Admin Only |
|--------|----------|-------------|------------|
| GET | `/api/admin/get_stats.php` | Platform statistics | Yes |
| GET | `/api/admin/get_users.php` | All registered users | Yes |
| GET | `/api/admin/manage_courses.php?action=list` | List all courses | Yes |
| POST | `/api/admin/manage_courses.php?action=add` | Add new course | Yes |
| POST | `/api/admin/manage_courses.php?action=toggle` | Toggle course status | Yes |
| POST | `/api/admin/manage_courses.php?action=delete` | Delete course | Yes |

---

## 📸 Screenshots

### Landing Page
![Landing Page](screenshots/landing.png)

### Student Dashboard
![Dashboard](screenshots/dashboard.png)

### Course Page
![Course Page](screenshots/course.png)

### Admin Panel
![Admin Panel](screenshots/admin.png)

### Mobile Responsive
![Mobile View](screenshots/mobile.png)

*Note: Add actual screenshots to a `screenshots/` folder*

---

## 🗺️ Roadmap

### Phase 1: ✅ MVP (Current)
- [x] User authentication system
- [x] 10 programming courses
- [x] Progress tracking
- [x] Quiz system
- [x] Gamification (streaks, badges, leaderboard)
- [x] AI chatbot
- [x] Admin panel
- [x] Responsive design

### Phase 2: 🚧 Enhancement (Next 3 Months)
- [ ] Email verification
- [ ] Password reset functionality
- [ ] User profile customization
- [ ] Certificate generation
- [ ] Discussion forums
- [ ] Code playground/editor
- [ ] Peer-to-peer mentoring

### Phase 3: 🔮 Advanced Features (6 Months)
- [ ] Video course creation tools
- [ ] Live coding sessions
- [ ] Mobile app (React Native)
- [ ] Multi-language support
- [ ] Advanced analytics
- [ ] AI-powered personalized learning paths
- [ ] Integration with GitHub for projects

### Phase 4: 🌍 Scale (12 Months)
- [ ] Partnership with NGOs
- [ ] Corporate sponsorship program
- [ ] Scholarship opportunities
- [ ] Job placement assistance
- [ ] Community challenges/hackathons

---

## 🤝 Contributing

Contributions are welcome! Here's how you can help:

### Reporting Bugs
1. Check if the bug is already reported in [Issues](https://github.com/Anedo-ras/educode-platform/issues)
2. If not, create a new issue with:
   - Clear title
   - Detailed description
   - Steps to reproduce
   - Expected vs actual behavior
   - Screenshots (if applicable)

### Suggesting Features
1. Open a new issue with tag `enhancement`
2. Describe the feature and its benefits
3. Explain use cases

### Code Contributions
1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Make your changes
4. Test thoroughly
5. Commit: `git commit -m 'Add amazing feature'`
6. Push: `git push origin feature/amazing-feature`
7. Open a Pull Request

### Coding Standards
- Use meaningful variable names
- Comment complex logic
- Follow existing code style
- Test before submitting
- Update documentation if needed

---

## 🐛 Troubleshooting

### Issue: Login doesn't work
**Solution**:
1. Check database connection in `test_db.php`
2. Verify admin user exists: `SELECT * FROM users WHERE username='admin'`
3. Check browser console for errors (F12)
4. Check `debug.log` file for server errors

### Issue: Courses not showing
**Solution**:
1. Verify courses exist: `SELECT * FROM courses`
2. Check if courses are active: `is_active = 1`
3. Verify user_id in localStorage
4. Check API response in Network tab (F12)

### Issue: Progress not saving
**Solution**:
1. Check localStorage has user_id
2. Verify API endpoint responds: `/api/update_progress.php`
3. Check MySQL permissions
4. Check `debug.log` for errors

### Issue: Responsive design broken
**Solution**:
1. Clear browser cache (Ctrl+Shift+Delete)
2. Check if `style.css` loads correctly
3. Verify Font Awesome CDN is accessible
4. Test in different browsers

### Issue: Admin panel access denied
**Solution**:
1. Verify user has `is_admin = 1` in database
2. Check localStorage: `is_admin` should be '1'
3. Clear cookies and login again

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

```
MIT License

Copyright (c) 2024 EduCode

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software...
```

---

## 📞 Contact

**Project Maintainer**: john kennedy
- **Email**: johnkennedyonyango12@gmail.com
- **LinkedIn**: [linkedin.com/in/john-kennedy-88aa382b6](https://linkedin.com/in/john-kennedy-88aa382b6)
- **GitHub**: [@yourusername](https://github.com/Anedo-ras)
- **Website**: [www.educode.com](https://www.educode.com)

**Project Link**: [https://github.com/johnkennedy/educode-platform](https://github.com/johnkennedy/educode-platform)

---

## 🙏 Acknowledgments

- **Font Awesome** - Icon library
- **YouTube API** - Video embedding
- **Stack Overflow Community** - Problem-solving support
- **Open Source Community** - Inspiration and guidance
- **All Contributors** - Making this project better

---

## 📊 Project Statistics

![GitHub Stars](https://img.shields.io/github/stars/yourusername/educode-platform?style=social)
![GitHub Forks](https://img.shields.io/github/forks/yourusername/educode-platform?style=social)
![GitHub Issues](https://img.shields.io/github/issues/yourusername/educode-platform)
![GitHub Pull Requests](https://img.shields.io/github/issues-pr/yourusername/educode-platform)

---

## 💝 Support the Project

If this project helps you or your organization, consider:

- ⭐ **Star** this repository
- 🐛 **Report bugs** or suggest features
- 🔀 **Fork** and contribute
- 📢 **Share** with others who might benefit
- 💰 **Sponsor** development (if applicable)

---

<div align="center">

**Made with ❤️ for underprivileged students worldwide**

*"Education is the most powerful weapon which you can use to change the world." - Nelson Mandela*

</div>

---

**Last Updated**: November 2024  
**Version**: 1.0.0  
**Status**: Active Development
