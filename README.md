# Alzikrayat

Alzikrayat is an MVC-based photo sharing web application developed as an Advanced Web Technology course project.

## Project Overview

The application allows registered users to:

- Create an account and log in securely.
- Upload multiple photos.
- View photos in a responsive gallery.
- View detailed photo information.
- Delete only their own uploaded photos.
- Add comments to photos.
- Browse the application through a responsive Bootstrap interface.

## Technologies

- PHP
- MySQL
- HTML5
- CSS3
- Bootstrap 5
- JavaScript
- Apache
- XAMPP/LAMPP

## Architecture

The application follows:

- MVC (Model-View-Controller)
- Three-Tier Architecture

The project is organized into:

text
config/
core/
controllers/
models/
views/
public/

## Security

The application includes:

- Password hashing using PHP password hashing functions.
- Prepared SQL statements through PDO.
- Session-based authentication.
- Server-side validation.
- Client-side HTML5 validation.
- File upload validation.
- Ownership checks for photo deletion.
- Output escaping using htmlspecialchars().

## Database

The application uses a MySQL database named:

alzikrayat

The database contains:

- Users
- Photos
- Comments

Foreign-key relationships use cascading deletes where required.

## Local Setup

1. Install XAMPP/LAMPP with Apache, PHP, and MySQL.
2. Place the project inside the web server directory.
3. Create a MySQL database named alzikrayat.
4. Create the required database tables.
5. Copy:

config/database.example.php

to:

config/database.php

6. Update the database credentials if necessary.
7. Start Apache and MySQL.
8. Open the application through the public/ directory.

## Application Entry Point

The application uses:

public/index.php

as its front controller.

Routes are handled by the custom manual router in:

core/Router.php


## Running the Application

For a local Apache installation, the application can be accessed through:

http://localhost/Php-Course/Alzikrayat/public/

The `public/` directory is the application's web-accessible entry point.


## Author

fofaJourge
