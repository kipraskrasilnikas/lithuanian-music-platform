# Lithuanian Music Platform

A web application designed to showcase and promote Lithuanian music by allowing users to discover songs based on genres and moods. This platform is built using Laravel, providing an intuitive and user-friendly interface.

## Table of Contents

- [Features](#features)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Testing](#testing)

## Features

- User registration and authentication
- Browse and search for songs by genres and moods
- User-friendly interface
- Admin panel for managing songs and users
- Responsive design for mobile and desktop

## Technologies Used

- **Laravel**: PHP framework for building web applications
- **MySQL/MariaDB**: Database management system
- **Bootstrap**: Front-end framework for responsive design
- **Localtunnel**: For exposing the application to the internet during development

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/yourusername/lithuanian-music-platform.git
   cd lithuanian-music-platform

# Project Setup Instructions

2. **Install Composer dependencies:**

Ensure you have Composer installed on your machine, then run:

```bash
composer install
```

3. **Set up your environment file:**
Copy the example environment file and rename it:

```bash
cp .env.example .env
```

4. **Generate an application key:**
```bash
php artisan key:generate
```

5. **Set up the database:**
Update the `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

6. **Run migrations:**
```bash
php artisan migrate
```

7. **Seed the database (optional):**
If you have seeders set up, you can populate your database with sample data:

```bash
php artisan db:seed
```

8. **Run the application:**
You can start the built-in PHP server:

```bash
php artisan serve
```
Now visit `http://localhost:8000` in your browser.

## Configuration
Ensure your web server (Apache/Nginx) is configured to serve the public directory of your Laravel application. 
You might need to configure the appropriate virtual host or server block depending on your server setup.

## Usage
- **User Registration**: Users can sign up to create an account.
- **Authentication**: Users can log in to access the platform.
- **Browsing Songs and Artists**: Users can search for songs and artists by mood or genre.
- **Collaborating with artists and adding own music**: Logged in users can search for artists to collaborate, message them and add their own songs.

## Testing
You can run your tests using:

```bash
php artisan test
```
Make sure to set up your testing environment in the `.env.testing` file.
