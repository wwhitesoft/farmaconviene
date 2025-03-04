# CLAUDE.md - Farmaconviene Project Guide

## Build Commands
- `php artisan serve` - Start Laravel development server
- `npm run dev` - Run Vite development server
- `npm run build` - Build assets for production
- `composer install` - Install PHP dependencies
- `npm install` - Install JS dependencies

## Test Commands
- `./vendor/bin/pest` - Run all tests
- `./vendor/bin/pest --filter=TestName` - Run a specific test
- `./vendor/bin/pest packages/Webkul/Admin/tests` - Test specific package

## Lint & Format Commands
- `./vendor/bin/pint` - Run Laravel Pint code formatter
- `./vendor/bin/pint --test` - Dry-run of formatting changes

## Code Style Guidelines
- Follow Laravel conventions and PSR-12 standards
- PHP arrays: use short array syntax `[]` and align `=>` operators
- Class organization: constants, properties, constructor, public methods, then private/protected
- Error handling: use exceptions with descriptive messages, log errors appropriately
- Type hints: use strict typing and declare return types
- Imports: group by system, vendor, and app namespaces with blank line between groups
- Naming: PascalCase for classes, camelCase for methods/variables, snake_case for database