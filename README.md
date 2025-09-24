# 🚀 Laravel 10 + Vue 3 SPA Admin Portal

A modern, professional admin portal built with Laravel 10 backend and Vue 3 frontend, featuring role-based authentication, dark mode, and a sleek user interface.

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-red.svg" alt="Laravel Version">
  <img src="https://img.shields.io/badge/Vue-3.x-green.svg" alt="Vue Version">
  <img src="https://img.shields.io/badge/Tailwind-3.x-blue.svg" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/License-MIT-yellow.svg" alt="License">
</p>

## ✨ Features

### 🔐 **Authentication System**
- **Sanctum API Authentication** with token-based security
- **Role-based Access Control** (Super Admin, Editor, Reporter)
- **Password Reset Flow** with 6-digit email codes
- **Professional Login/Register Pages** with validation

### 🎨 **Modern UI/UX**
- **Vue 3 SPA** with Vue Router and Pinia state management
- **Tailwind CSS** with dark/light mode toggle
- **Responsive Design** that works on all devices
- **Professional Layout** with header, navigation, and sidebar

### 🛡️ **Security & Permissions**
- **Middleware-based Permissions** for API routes
- **Frontend Permission Validation** using Pinia stores
- **Role Hierarchy System** with flexible permission levels
- **Secure Password Reset** with time-limited codes

### 📊 **Admin Features**
- **Dashboard** with real-time date/time display
- **Role-specific Navigation** and quick actions
- **User Management** (Super Admin only)
- **System Settings** and configuration options

## 🚀 Quick Start

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js 16+ and npm
- MySQL/PostgreSQL database

### Installation

1. **Clone the repository**
   ```bash
   git clone <your-repo-url>
   cd newsvioso
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your database in `.env`**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Configure mail settings in `.env`**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=your_smtp_host
   MAIL_PORT=587
   MAIL_USERNAME=your_email
   MAIL_PASSWORD=your_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@yourdomain.com
   MAIL_FROM_NAME="Admin Portal"
   ```

7. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

8. **Build assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   npm run dev
   ```

10. **Access the admin portal**
    Visit: `http://localhost:8000/secure/administrator`

## 👥 Default Test Users

| Role | Email | Password |
|------|-------|----------|
| **Super Admin** | `superadmin@admin.com` | `password123` |
| **Editor** | `editor@admin.com` | `password123` |
| **Reporter** | `reporter@admin.com` | `password123` |

## 🏗️ Project Structure

```
├── app/
│   ├── Constants/
│   │   └── UserRole.php          # Role management constants
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   │   ├── AuthController.php    # Authentication API
│   │   │   └── DashboardController.php
│   │   └── Middleware/
│   │       ├── CheckRole.php         # Role-based middleware
│   │       └── CheckPermissionLevel.php
│   ├── Mail/
│   │   └── PasswordResetMail.php     # Email templates
│   └── Models/
│       └── User.php                   # Enhanced user model
├── resources/
│   ├── js/
│   │   ├── components/
│   │   │   ├── App.vue
│   │   │   └── layouts/
│   │   │       └── AdminLayout.vue
│   │   ├── pages/
│   │   │   ├── auth/
│   │   │   │   ├── Login.vue
│   │   │   │   ├── ForgotPassword.vue
│   │   │   │   └── ResetPassword.vue
│   │   │   └── Dashboard.vue
│   │   ├── stores/
│   │   │   ├── auth.js               # Authentication store
│   │   │   └── theme.js              # Theme management
│   │   └── app.js                    # Vue application entry
│   ├── views/
│   │   ├── admin.blade.php           # SPA entry point
│   │   └── emails/
│   │       └── password-reset.blade.php
│   └── css/
│       └── app.css                   # Tailwind CSS
└── routes/
    ├── api.php                       # API routes
    └── web.php                       # Web routes
```

## 🔌 API Endpoints

### Authentication Routes
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `POST` | `/api/auth/login` | User login | ❌ |
| `POST` | `/api/auth/logout` | User logout | ✅ |
| `GET` | `/api/auth/me` | Get current user | ✅ |
| `POST` | `/api/auth/forgot-password` | Send reset code | ❌ |
| `POST` | `/api/auth/reset-password` | Reset password | ❌ |

### Protected Routes
| Method | Endpoint | Description | Role Required |
|--------|----------|-------------|---------------|
| `GET` | `/api/dashboard` | Dashboard data | Any authenticated user |

### Role-based Routes
- **Super Admin**: Full access to all routes
- **Editor**: Content management and editing routes
- **Reporter**: Report generation and analytics routes

## 🎨 Customization

### Adding New Roles
1. Add role constant to `app/Constants/UserRole.php`
2. Update role hierarchy levels
3. Create role-specific middleware
4. Add role-specific UI components

### Styling
- Modify Tailwind classes in Vue components
- Update color scheme in `tailwind.config.js`
- Customize global styles in `resources/css/app.css`

### Email Templates
- Customize password reset email in `resources/views/emails/password-reset.blade.php`
- Modify email styling and content as needed

## 🛠️ Development

### Available Scripts
```bash
npm run dev          # Start Vite development server
npm run build        # Build for production
php artisan serve    # Start Laravel development server
php artisan migrate  # Run database migrations
php artisan db:seed  # Seed database with test data
```

### Testing
```bash
# Test Tailwind CSS
Visit: http://localhost:8000/secure/administrator/test

# Test authentication flow
Visit: http://localhost:8000/secure/administrator/login
```

## 🚨 Troubleshooting

### Common Issues

1. **Tailwind CSS not working**
   ```bash
   npm install -D tailwindcss @tailwindcss/forms autoprefixer postcss
   npm run build
   ```

2. **Authentication errors**
   - Check API routes in `routes/api.php`
   - Verify Sanctum configuration
   - Check browser console for CORS errors

3. **Email not sending**
   - Configure SMTP settings in `.env`
   - Test with mail testing tools like Mailtrap

4. **Assets not loading**
   - Run `npm run build`
   - Check Vite configuration
   - Clear browser cache

### Development Tips
- Use browser dev tools to debug API calls
- Check Laravel logs in `storage/logs/laravel.log`
- Use Vue DevTools for frontend debugging
- Use `php artisan route:list` to see all routes

## 🔒 Security Notes

- Remove test reset codes from API responses in production
- Use HTTPS in production environment
- Regularly update Laravel and dependencies
- Never commit `.env` files to version control
- Use strong passwords for database and mail configuration

## 📚 Documentation

- [Laravel 10 Documentation](https://laravel.com/docs/10.x)
- [Vue 3 Documentation](https://vuejs.org/guide/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Laravel Sanctum Documentation](https://laravel.com/docs/sanctum)
- [Pinia Documentation](https://pinia.vuejs.org/)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com/) framework
- Frontend powered by [Vue.js](https://vuejs.org/)
- Styled with [Tailwind CSS](https://tailwindcss.com/)
- Authentication by [Laravel Sanctum](https://laravel.com/docs/sanctum)
- State management with [Pinia](https://pinia.vuejs.org/)

---

**Happy coding! 🚀**
