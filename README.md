# MailerLite Subscriber Management System

This project is a subscriber management system built with Laravel v12 and Vue.js v3. It allows you to manage subscribers and custom fields through a RESTful API.

## Features

- RESTful JSON API for managing subscribers and fields
- Validation of email addresses, including domain checking
- Custom field support with different data types (string, number, date, boolean)
- Relationships between subscribers and fields
- Vue.js frontend for easy management
- Test coverage
- Error handling

## Requirements

- PHP 8.1+
- Composer
- Node.js and NPM
- SQLite

## Installation

Follow these steps to set up the project:

```bash
# Clone the repository
git clone <repository-url>
cd subscriber-management

# Install PHP dependencies
composer install

# Create SQLite database if it doesn't exist
touch database/database.sqlite

# Set up environment file (.env is provided pushed by default)
cp .env.example .env
```

Update your `.env` file with the following database configuration:
```

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Replace `/absolute/path/to/database/database.sqlite` with the actual path to your SQLite database file.

Then continue with:

```bash
# Generate application key
php artisan key:generate

# Run database migrations and seeders
php artisan migrate --seed

# Install Node.js dependencies
npm install

# Build frontend assets
npm run build
```

## Starting the Application
```bash
php artisan serve
```

The application will be available at http://localhost:8000

### Subscribers

- `GET /api/subscribers`: Get all subscribers
    - Query parameters:
        - `search`: Search by email or name
        - `state`: Filter by state

- `GET /api/subscribers/{id}`: Get a specific subscriber

- `POST /api/subscribers`: Create a new subscriber
    - Required fields:
        - `email`: Valid email address with active domain
        - `name`: Subscriber name
        - `state`: One of: active, unsubscribed, junk, bounced, unconfirmed
    - Optional fields:
        - `fields`: Array of field values

- `PUT /api/subscribers/{id}`: Update a subscriber
    - Optional fields:
        - `email`: Valid email address
        - `name`: Subscriber name
        - `state`: Subscriber state
        - `fields`: Array of field values

- `DELETE /api/subscribers/{id}`: Delete a subscriber

### Fields

- `GET /api/fields`: Get all fields
    - Query parameters:
        - `search`: Search by title
        - `type`: Filter by type

- `GET /api/fields/{id}`: Get a specific field

- `POST /api/fields`: Create a new field
    - Required fields:
        - `title`: Field title
        - `type`: One of: date, number, string, boolean

- `PUT /api/fields/{id}`: Update a field
    - Optional fields:
        - `title`: Field title
        - `type`: Field type

- `DELETE /api/fields/{id}`: Delete a field (fails if field is in use)

## Testing

Run tests with:

```bash
php artisan test
```
