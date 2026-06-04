# CookAI - Intelligent Culinary Platform

[Русский](README.md) | English

A modern web application for culinary enthusiasts that combines recipe discovery, meal planning, and AI-powered features using Yandex Cloud APIs.

## ✨ Key Features

- **🤖 AI Recipe Generator** - Create unique recipes from available ingredients using Yandex GPT
- **📸 Calorie Scanner** - Analyze food photos to determine nutrition info using Computer Vision
- **📋 Meal Planning** - Generate personalized meal plans based on dietary goals
- **🔍 Recipe Search** - Full-text search with filtering by difficulty, time, and diet
- **🎯 User Accounts** - Secure authentication with session management
- **💾 Recipe Collection** - Save favorite recipes and create personal cookbooks
- **⭐ Rating System** - Community ratings and reviews
- **🌾 Seasonal Content** - Discover seasonal recipes and ingredients

## 🛠 Tech Stack

- **Backend**: PHP 8+ (MVC Architecture)
- **Database**: MySQL 8.0+
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **API Integration**: Yandex Cloud (GPT + Vision)
- **Web Server**: Apache/Nginx

## 📋 Requirements

- PHP >= 8.0
- MySQL >= 8.0
- Composer (optional)
- Web server (Apache/Nginx)
- Yandex Cloud API keys

## 🚀 Installation

### 1. Clone the repository

```bash
git clone https://github.com/huntand-50/CookAI.git
cd CookAI
```

### 2. Set up environment

```bash
cp .env.example .env
# Edit .env with your settings
```

### 3. Create database

```bash
mysql -u root -p < database/schema.sql
```

### 4. Install dependencies (optional)

```bash
composer install
```

### 5. Configure web server

**Apache** (enable mod_rewrite):
```apache
<Directory /path/to/CookAI/public>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [QSA,L]
</Directory>
```

**Nginx**:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 6. Create upload directories

```bash
mkdir -p storage/uploads/recipes
mkdir -p storage/cache
chmod 755 storage/
```

## 📖 Usage

### Local development

```bash
cd CookAI/public
php -S localhost:8000
```

Open browser: `http://localhost:8000`

### API Endpoints

#### Recipes
- `GET /recipes` - List recipes
- `GET /recipe/:id` - View recipe
- `GET /recipes/search?q=...` - Search
- `POST /recipes/create` - Create recipe (requires auth)

#### AI
- `POST /ai/generate-recipe` - Generate recipe
- `POST /ai/scan-calories` - Analyze food image
- `POST /ai/advice` - Get cooking advice
- `GET /ai/meal-plan` - Meal planning

#### Auth
- `GET /login` - Login form
- `POST /login` - Process login
- `GET /register` - Registration form
- `POST /register` - Process registration
- `GET /logout` - Logout

## 📁 Project Structure

```
CookAI/
├── app/
│   ├── controllers/     # Controllers
│   ├── models/          # Data models
│   ├── views/           # Templates (HTML)
│   ├── services/        # Business logic
│   └── core/            # Framework classes
├── database/
│   └── schema.sql       # Database schema
├── public/
│   ├── index.php        # Entry point
│   ├── css/             # Stylesheets
│   └── js/              # JavaScript
├── storage/
│   ├── uploads/         # User uploads
│   └── cache/           # Cache files
├── .env.example         # Example config
└── README.md            # This file
```

## 🔒 Security

- Protection against SQL injection (prepared statements)
- CSRF tokens for all forms
- Password hashing (bcrypt)
- Input validation and sanitization
- File upload size limits
- Session security

## 📝 License

MIT License - see LICENSE file for details

## 👨‍💻 Author

**huntand-50** - Project Developer

- Email: ag19751808@gmail.com
- GitHub: [@huntand-50](https://github.com/huntand-50)

## 🤝 Contributing

Contributions are welcome! For major changes, please open an issue first to discuss proposed changes.

## 📞 Support

For issues, questions, or suggestions, please open a GitHub issue.

---

**Made with ❤️ for cooking enthusiasts**
