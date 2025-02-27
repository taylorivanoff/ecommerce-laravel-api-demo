# Squeak Boutique - API

## Overview

This is the backend part of the Squeak Boutique e-commerce store, built using Laravel. It provides APIs for user authentication, product management, and order processing.

[Demo](https://squeak-boutique.netlify.app/)

## Features

-   User authentication with Laravel Sanctum
-   CRUD operations for products
-   Order management functionalities
-   Data relationships for suppliers and products

## Technologies Used

-   **PHP** (Laravel)
-   **Composer** for dependency management

## Getting Started

### Prerequisites

-   PHP >= 8.0
-   Composer
-   Node.js (for some development tools)

### Installation

1. Clone the repository:

    ```bash
    git clone git@github.com:taylorivanoff/squeak-boutique-ecommerce-api.git
    ```

2. Navigate to the backend directory:

    ```bash
    cd squeak-boutique-ecommerce-api
    ```

3. Install dependencies:

    ```bash
    composer install
    ```

4. Set up your environment file:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. Run migrations:

    ```bash
    touch database/database.sqlite
    php artisan migrate --seed
    ```

6. Link storage:

    ```bash
    php artisan storage:link
    ```

7. Start the server:
    ```bash
    php artisan serve
    ```

### Usage

-   Access the API at `http://localhost:8000/api`.
-   Use tools like Postman to interact with the endpoints.
