# Laravel microshop simple application 

## Introduction

A simple internet shop.

**Based on Laravel 10**

## Requirements

- php >= 8.1
- MySQL >= 8.0.13 | MariaDB >= 10.2.1
- composer
- NodeJs

## Installation

1. Clone project.

    `SSH SOURCE: git@github.com:itstructure/laravel-microshop-simple.git`
    
    `HTTPS SOURCE: https://github.com/itstructure/laravel-microshop-simple.git`
    
2. Install dependencies by running from the project root `composer install`

3. Copy and rename file `.env.example` to `.env`.

4. Generate `APP_KEY` in `.env` file, run: `php artisan key:generate`

5. Set a database connect options in `.env` file.

6. Run migrations: `php artisan migrate`

7. Run seeders: `php artisan db:seed`

8. Run command: `npm install`

9. Run command: `npm run build`

10. Register in a system.

11. Config according with the point 2 in [Laravel RBAC package](https://github.com/itstructure/laravel-rbac)

12. Run RBAC seeders: `php artisan rbac:database --only=seed`

13. Run admin setting for RBAC: `php artisan rbac:admin`

14. Go to `/admin` and manage content

## Possibilities

You can:

- Manage users with setting roles and permissions.
- Manage product categories.
- Manage products.
- See new orders.