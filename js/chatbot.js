// AI Learning Assistant Chatbot
const chatbotResponses = {
    greetings: ["Hello! I'm your AI learning assistant. How can I help you today?"],
    html: [
        "HTML is the foundation of web development. It stands for HyperText Markup Language and is used to structure web content.",
        "Start with basic tags like <html>, <head>, <body>, <h1>, <p>, and <a>. Practice creating simple web pages!",
        "HTML uses elements enclosed in angle brackets. For example: <h1>This is a heading</h1>"
    ],
    css: [
        "CSS (Cascading Style Sheets) is used to style HTML elements. You can change colors, fonts, layouts, and more!",
        "Basic CSS syntax: selector { property: value; }. For example: h1 { color: blue; }",
        "Learn about selectors (element, class, ID), the box model, flexbox, and grid layout."
    ],
    javascript: [
        "JavaScript adds interactivity to websites. You can manipulate the DOM, handle events, and create dynamic content.",
        "Start with variables (let, const), functions, if/else statements, and loops. Then move to DOM manipulation.",
        "JavaScript runs in the browser and can respond to user actions like clicks, typing, and scrolling."
    ],
    python: [
        "Python is a versatile, beginner-friendly language. It's used for web development, data science, AI, and automation.",
        "Python syntax is clean and readable. Start with print(), variables, if/else, loops, and functions.",
        "Practice with simple projects like calculators, to-do lists, or text-based games."
    ],
    php: [
        "PHP is a server-side language perfect for building dynamic websites and web applications.",
        "PHP code runs on the server and generates HTML sent to the browser. It's great for form processing and databases.",
        "Start with basic syntax, variables, arrays, and then learn about connecting to MySQL databases."
    ],
    java: [
        "Java is a powerful object-oriented language used for enterprise applications, Android apps, and more.",
        "Java syntax is strict but helps you write organized code. Learn about classes, objects, and methods.",
        "Practice with small programs like calculators or simple games to understand OOP concepts."
    ],
    cpp: [
        "C++ is a high-performance language used for system programming, game development, and more.",
        "C++ gives you control over hardware resources. Start with basic syntax, pointers, and memory management.",
        "Build projects like calculators, sorting algorithms, or simple text-based games."
    ],
    sql: [
        "SQL (Structured Query Language) is used to manage and query databases.",
        "Learn basic commands: SELECT, INSERT, UPDATE, DELETE, and how to use WHERE, JOIN, and ORDER BY.",
        "Practice creating tables, inserting data, and writing queries to retrieve information."
    ],
    flutter: [
        "Flutter is Google's UI toolkit for building natively compiled applications for mobile, web, and desktop.",
        "Flutter uses Dart programming language. Learn about widgets, which are the building blocks of Flutter apps.",
        "Start with StatelessWidget and StatefulWidget, then explore layouts and navigation."
    ],
    nodejs: [
        "Node.js is a JavaScript runtime for building server-side applications.",
        "With Node.js, you can create web servers, APIs, and real-time applications using JavaScript.",
        "Learn about modules, npm packages, Express.js framework, and asynchronous programming."
    ],
    motivation: [
        "You're doing great! Every programmer started exactly where you are. Keep coding!",
        "Remember: coding is about practice and persistence. Don't give up when things get tough!",
        "Every bug you fix makes you a better programmer. Embrace challenges!",
        "Small progress is still progress. Celebrate your learning wins!"
    ],
    help: [
        "I can help you with: HTML, CSS, JavaScript, PHP, Python, Java, C++, SQL, Flutter, and Node.js.",
        "Ask me about any programming concept, tips for learning, or motivational support!",
        "Try asking things like 'How do I start with Python?' or 'What is CSS used for?'"
    ]
};

function toggleChatbot() {
    const chatWindow = document.getElementById('chatbotWindow');
    chatWindow.classList.toggle('hidden');
    
    const messagesDiv = document.getElementById('chatMessages');
    if (messagesDiv.children.length === 0) {
        addBotMessage("Hello! I'm your AI learning assistant. Ask me anything about programming!");
    }
}

function sendMessage() {
    const input = document.getElementById('chatInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    addUserMessage(message);
    input.value = '';
    
    setTimeout(() => {
        const response = generateResponse(message.toLowerCase());
        addBotMessage(response);
    }, 500);
}

function addUserMessage(message) {
    const messagesDiv = document.getElementById('chatMessages');
    const messageEl = document.createElement('div');
    messageEl.className = 'chat-message user';
    messageEl.textContent = message;
    messagesDiv.appendChild(messageEl);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

function addBotMessage(message) {
    const messagesDiv = document.getElementById('chatMessages');
    const messageEl = document.createElement('div');
    messageEl.className = 'chat-message bot';
    messageEl.textContent = message;
    messagesDiv.appendChild(messageEl);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

function generateResponse(message) {
    if (message.includes('hello') || message.includes('hi') || message.includes('hey')) {
        return chatbotResponses.greetings[0];
    }
    
    if (message.includes('help') || message.includes('what can you do')) {
        return chatbotResponses.help[Math.floor(Math.random() * chatbotResponses.help.length)];
    }
    
    if (message.includes('motivat') || message.includes('encourage') || message.includes('hard')) {
        return chatbotResponses.motivation[Math.floor(Math.random() * chatbotResponses.motivation.length)];
    }
    
    const languages = ['html', 'css', 'javascript', 'python', 'php', 'java', 'cpp', 'sql', 'flutter', 'nodejs'];
    
    for (const lang of languages) {
        if (message.includes(lang) || message.includes(lang.replace('js', '')) || 
            (lang === 'cpp' && (message.includes('c++') || message.includes('c plus')))) {
            const responses = chatbotResponses[lang];
            return responses[Math.floor(Math.random() * responses.length)];
        }
    }
    
    return "That's a great question! I can help you with programming languages like HTML, CSS, JavaScript, Python, PHP, Java, C++, SQL, Flutter, and Node.js. What would you like to learn about?";
}

document.addEventListener('DOMContentLoaded', function() {
    const chatInput = document.getElementById('chatInput');
    if (chatInput) {
        chatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }
});