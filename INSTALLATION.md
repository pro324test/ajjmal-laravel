# Ajjmal Laravel Installation Guide

## Prerequisites

Before installing this Laravel application, ensure you have the following installed on your system:

- PHP 8.2 or higher
- Composer (PHP package manager)
- Node.js 18+ and npm
- SQLite (or MySQL/PostgreSQL if preferred)

## Installation Steps

### 1. Clone the Repository

```bash
git clone <your-repository-url>
cd ajjmal-laravel
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the environment example file and configure it:

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Database Setup

The application is configured to use SQLite by default. Create the database file:

```bash
touch database/database.sqlite
```

If you prefer to use MySQL or PostgreSQL, update the database configuration in your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 7. Run Database Migrations

```bash
php artisan migrate
```

### 8. Build Frontend Assets

```bash
npm run build
```

For development with hot reload:

```bash
npm run dev
```

### 9. Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Alternative: Using Laravel's Composer Dev Command

For a full development setup with multiple services running concurrently:

```bash
composer dev
```

This command will start:
- PHP development server
- Queue worker
- Laravel Pail (log viewer)
- Vite development server

## Features

This Laravel application is a **multivendor marketplace** with the following features:

- **Homepage**: Welcome page with marketplace overview
- **Product Catalog**: Browse products from multiple vendors
- **User Authentication**: Login and registration system
- **User Dashboard**: Personal dashboard for users
- **Vendor System**: Support for multiple vendors
- **Shopping Cart**: Add products to cart
- **Responsive Design**: Built with TailwindCSS
- **Livewire Components**: Dynamic frontend components

## Available Routes

- `/` - Homepage
- `/products` - Product listing
- `/categories` - Category listing
- `/vendors` - Vendor listing
- `/cart` - Shopping cart
- `/login` - User login
- `/register` - User registration
- `/dashboard` - User dashboard
- `/vendor/register` - Vendor registration
- `/vendor/dashboard` - Vendor dashboard

## Technology Stack

- **Backend**: Laravel 12.x
- **Frontend**: TailwindCSS 4.x, Livewire 3.x
- **Build Tool**: Vite 7.x
- **Database**: SQLite (default) / MySQL / PostgreSQL
- **PHP Version**: 8.2+

## Screenshots

The application includes several functional pages:

1. **Homepage** - Marketing landing page
2. **Products Page** - Product catalog (currently empty, ready for products)
3. **Login Page** - User authentication
4. **Register Page** - User registration  
5. **Dashboard** - User dashboard with stats and quick actions

## Troubleshooting

### Common Issues

1. **Permission Errors**: Make sure storage and bootstrap/cache directories are writable:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

2. **Node Module Issues**: Clear npm cache and reinstall:
   ```bash
   npm cache clean --force
   rm -rf node_modules package-lock.json
   npm install
   ```

3. **Database Issues**: Reset and re-run migrations:
   ```bash
   php artisan migrate:fresh
   ```

4. **Asset Building Issues**: Clear Vite cache:
   ```bash
   rm -rf node_modules/.vite
   npm run build
   ```

## Development

For development, you can use:

```bash
# Run development server with hot reload
npm run dev

# In another terminal, start Laravel
php artisan serve
```

## Production Deployment

For production deployment:

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Run `php artisan view:cache`
6. Run `npm run build`

## Support

If you encounter any issues during installation or setup, please check:

1. PHP and Composer versions meet requirements
2. All dependencies are properly installed
3. Database connection is configured correctly
4. File permissions are set properly

For further assistance, please refer to the [Laravel Documentation](https://laravel.com/docs) or create an issue in the repository.