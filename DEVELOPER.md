# 🧑‍💻 Developer Documentation

## Project Structure

```
app/
  Http/
    Controllers/         # Main controllers (Admin, Auth, Cart, Checkout, etc.)
    Middleware/          # Custom middleware
    Requests/            # Form request validation
  Models/                # Eloquent models (User, Product, Order, etc.)
  Observers/             # Model observers (e.g., ProductObserver)
  Providers/             # Service providers
  View/Components/       # Blade layout/view components
config/                  # Application configuration files
database/
  migrations/            # Migration files for all tables
  seeders/               # Seeders for test/sample data
public/                  # Public assets and entry point
resources/
  views/                 # Blade templates
  js/, css/              # Frontend assets
routes/                  # Route definitions (web.php, auth.php)
tests/                   # Feature and unit tests
```

## Key Components

- **Controllers**: Handle HTTP requests and business logic. Admin controllers are in `app/Http/Controllers/Admin`.
- **Models**: Represent database tables. Located in `app/Models`.
- **Observers**: Track model changes (e.g., product logs).
- **Seeders**: Populate database with test data (`database/seeders`).
- **Routes**: Defined in `routes/web.php` (main app) and `routes/auth.php` (auth).
- **Middleware**: Used for authentication, admin checks, etc.
- **Views**: Blade templates in `resources/views`.

## How to Extend

1. **Add a New Model**:
   - Create a model in `app/Models`.
   - Create a migration: `php artisan make:migration create_new_table`
   - Add relationships in the model as needed.

2. **Add a New Controller**:
   - Create a controller: `php artisan make:controller NewController`
   - Define routes in `routes/web.php` or `routes/api.php`.

3. **Add Admin Features**:
   - Use `app/Http/Controllers/Admin` for admin-only logic.
   - Protect routes with `auth` and `admin` middleware.

4. **Add/Modify Seeders**:
   - Edit or add seeder classes in `database/seeders`.
   - Run with `php artisan db:seed`.

5. **Add Observers**:
   - Create observer in `app/Observers`.
   - Register in a service provider.

6. **Testing**:
   - Add tests in `tests/Feature` or `tests/Unit`.
   - Run with `php artisan test`.

---
