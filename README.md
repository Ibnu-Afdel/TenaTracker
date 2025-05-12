# Tenatracker

Tenatracker is a Laravel-based web application for tracking personal challenges and maintaining journal entries. The application helps users document their progress, share achievements, and stay motivated through a structured tracking system.

## Features

- **Challenge Management**: Create, edit, and track challenges with start/end dates and status tracking
- **Journal Entries**: Document progress with rich-text journal entries for each challenge
- **Tag System**: Organize challenges and journal entries with custom tags
- **Sharing Capabilities**: Share individual challenges or journal entries publicly using secure tokens

## Tech Stack

- **PHP**: 8.2+
- **Laravel**: 11.0
- **Livewire**: 3.5+ for interactive UI components
- **Alpine.js**: 3.13+ for frontend interactivity
- **Tailwind CSS**: 3.4+ for styling
- **MySQL/PostgreSQL**: For database storage

## Prerequisites

- PHP 8.2+
- Composer
- Node.js and NPM
- MySQL or PostgreSQL database

## Installation

1. Clone the repository:
   ```
   git clone https://github.com/ibnu-afdel/tenatracker.git
   cd tenatracker
   ```

2. Install PHP dependencies:
   ```
   composer install
   ```

3. Install JavaScript dependencies:
   ```
   npm install
   ```

4. Create a copy of the environment file:
   ```
   cp .env.example .env
   ```

5. Generate application key:
   ```
   php artisan key:generate
   ```

6. Configure your database connection in the `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tenatracker
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

7. Run database migrations:
   ```
   php artisan migrate
   ```

8. Build frontend assets:
   ```
   npm run build
   ```

9. Start the development server:
   ```
   php artisan serve
   ```

## Development

For local development, you can use the development script which starts the Laravel server, queue worker, logs, and Vite:

```
composer run dev
```




## Deployment

The project is configured for deployment with Railway (see `railway.toml`). For production environments:

```
npm run build:prod
```

## License

This project is open-sourced software licensed under the MIT license.
