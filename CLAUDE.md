# CLAUDE.md - Laravel Pusher Project

## Commands
- `composer dev`: Run development server, queue, logs and Vite
- `php artisan test`: Run all tests
- `php artisan test --filter=TestName`: Run a specific test
- `php artisan pint`: Run Laravel Pint code formatter
- `npm run dev`: Run Vite for frontend assets
- `php artisan serve`: Start the Laravel development server
- `docker exec -it --user root pusher-app bash`: Access the project container

## Coding Standards
- **PHP Version**: PHP 8.2+
- **Framework**: Laravel 11
- **Formatting**: Use Laravel Pint (PSR-12 based)
- **Naming**: PSR-4 autoloading, PascalCase for classes, camelCase for methods/variables
- **Frontend**: TailwindCSS, AlpineJS, Blade templates
- **Error Handling**: Use Laravel exception handlers, log errors appropriately
- **Broadcasting**: Configured with Pusher for real-time updates
- **Testing**: PHPUnit with Feature and Unit test suites
- **Project Structure**: Follow Laravel conventions for MVC organization
- **Database**: Define relationships in models, use migrations for schema changes

This project implements real-time product display with Pusher integration.