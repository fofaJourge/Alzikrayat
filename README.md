# Alzikrayat

### MVC-Based Photo Sharing Web Application

**Advanced Web Technology Course Project**
**Department of Software Engineering — Sudan University of Science and Technology**

---

## Project Overview

Alzikrayat is a database-driven photo sharing web application developed using **PHP and MySQL**.

The application follows the **Model-View-Controller (MVC)** design pattern and **Three-Tier Architecture**. It provides registered users with a secure environment to create accounts, upload and manage photos, browse a responsive gallery, view photo details, and interact through comments.

The project was developed without a backend framework or ORM, using a manually implemented routing system and structured MVC components.

---

## Main Features

### User Authentication

* User registration with input validation.
* Unique email verification.
* Secure password hashing.
* Login and logout.
* Session-based authentication.
* Session ID regeneration after successful login.
* Last-login timestamp stored in a browser cookie.
* Dynamic navigation based on authentication status.

### Photo Management

* Multiple photo uploads.
* Photo title and description.
* Secure uploaded-file validation.
* Responsive photo gallery.
* Detailed photo pages.
* Photo author and upload information.
* Owner-only photo deletion.
* Removal of both the database record and physical image file.

### Comments

* Authenticated users can comment on photos.
* Comments are associated with the current user and selected photo.
* Comment timestamps are stored automatically.
* Server-side comment validation.
* Safe output escaping when comments are displayed.

### Responsive Interface

* HTML5
* CSS3
* Bootstrap 5
* JavaScript
* Responsive navigation and layouts
* Mobile-friendly gallery, forms, and photo pages

---

## Technologies

| Technology       | Purpose                                |
| ---------------- | -------------------------------------- |
| **PHP**          | Backend application logic              |
| **MySQL**        | Relational database                    |
| **PDO**          | Secure database communication          |
| **HTML5**        | Page structure and validation          |
| **CSS3**         | Styling and responsive design          |
| **Bootstrap 5**  | Responsive user interface              |
| **JavaScript**   | Client-side interaction and validation |
| **Apache**       | Web server                             |
| **XAMPP/LAMPP**  | Local development environment          |
| **Git & GitHub** | Version control                        |

---

## Architecture

Alzikrayat follows both **MVC** and **Three-Tier Architecture**.

### MVC

**Model**
Handles database communication and SQL operations.

**View**
Responsible for presenting application data to the user.

**Controller**
Processes requests, validation, authentication, application logic, and communication between Models and Views.

### Request Flow

```text
┌──────────────────┐
│  Browser / User  │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│ public/index.php │
│  Front Controller│
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│      Router      │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│    Controller    │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│      Model       │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│   MySQL Database │
└──────────────────┘
```

### Three-Tier Structure

| Tier                       | Responsibility                                            |
| -------------------------- | --------------------------------------------------------- |
| **Presentation**           | HTML5, CSS3, Bootstrap, JavaScript, PHP Views             |
| **Application / Business** | Router, Controllers, Authentication, Validation, Sessions |
| **Data**                   | Models, PDO, SQL, MySQL Database                          |

---

## Project Structure

```text
Alzikrayat/
│
├── config/
│   ├── database.php
│   └── database.example.php
│
├── core/
│   ├── Controller.php
│   ├── Model.php
│   └── Router.php
│
├── controllers/
│   ├── AuthController.php
│   ├── PhotoController.php
│   └── CommentController.php
│
├── models/
│   ├── User.php
│   ├── Photo.php
│   └── Comment.php
│
├── views/
│   ├── auth/
│   ├── photos/
│   └── layout/
│
└── public/
    ├── index.php
    ├── .htaccess
    └── images/
        └── uploads/
```

### Directory Responsibilities

* `config/` — Database configuration and template.
* `core/` — Core MVC functionality and manual routing.
* `controllers/` — Request handling and application logic.
* `models/` — Database operations and SQL queries.
* `views/` — User-interface pages and reusable layouts.
* `public/` — Web-accessible entry point and public resources.
* `public/images/uploads/` — Uploaded image storage.

---

## Database

The application uses a MySQL database named:

```text
alzikrayat
```

### Main Tables

#### Users

Stores registered user information.

#### Photos

Stores photo metadata including:

* Photo ID
* User ID
* File name
* Title
* Description
* Date and time

#### Comments

Stores:

* Comment ID
* Photo ID
* User ID
* Comment text
* Date and time

### Relationships

```text
Users
  │
  ├──────────< Photos
  │              │
  │              └──────────< Comments
  │
  └──────────< Comments
```

Foreign keys maintain referential integrity, with cascading deletes where required.

---

## Manual Routing

The application uses a custom manual routing system instead of a backend framework.

The **Front Controller** is:

```text
public/index.php
```

Routes are handled by:

```text
core/Router.php
```

Examples of application routes include:

```text
GET  /photos
GET  /photo/{id}
POST /photo/store
GET  /photo/{id}/delete

POST /comment/store

GET  /register
POST /register

GET  /login
POST /login

GET  /logout
```

Dynamic route parameters are handled using regular expressions.

---

## Security

Security was considered throughout the application.

### Password Security

* Passwords are stored using PHP's `password_hash()`.
* Passwords are verified using `password_verify()`.
* Plain-text passwords are never stored.

### Database Security

* PDO is used for database communication.
* Prepared statements protect database operations from SQL injection.

### Authentication & Authorization

* Protected operations require an authenticated session.
* Session IDs are regenerated after successful login.
* Photo deletion includes a server-side ownership check.

### Input Validation

* Server-side validation is applied to important user input.
* HTML5 and JavaScript provide client-side validation.

### File Upload Security

Uploaded files are validated for:

* Upload status
* File size
* MIME type
* Valid image content

Generated filenames are used for stored images.

The application also limits the number of files processed in a single upload request.

### XSS Protection

User-generated output is escaped using:

```php
htmlspecialchars()
```

### Uploaded Directory Protection

The upload directory disables directory listing and prevents server-side script execution.

### HTTP Security Headers

The application sets:

```text
X-Content-Type-Options
X-Frame-Options
Referrer-Policy
```

---

## Validation & Testing

The application was tested across functional, security, authorization, and responsive-interface requirements.

Testing included:

* User registration and validation
* Duplicate email prevention
* Login and logout
* Session authentication
* Last-login cookie
* Protected operations
* Multiple photo uploads
* Invalid file handling
* File size validation
* Gallery display
* Photo details
* Owner-only deletion
* Unauthorized deletion attempts
* Comment validation
* XSS input handling
* Password hashing
* Database relationships
* Responsive navigation
* Responsive pages
* Final application smoke testing

The final testing phase confirmed that the main application workflows operate correctly.

---

## Local Setup

### Requirements

* Apache
* PHP
* MySQL
* XAMPP/LAMPP
* Modern web browser

### Installation

1. Start **Apache** and **MySQL** using XAMPP/LAMPP.

2. Place the project inside the Apache web directory.

3. Create a MySQL database named:

```text
alzikrayat
```

4. Create the required tables and foreign-key relationships.

5. Copy:

```text
config/database.example.php
```

to:

```text
config/database.php
```

6. Configure the local database credentials if necessary.

7. Ensure the upload directory is writable by the web server.

8. Start the application through Apache.

---

## Running the Application

The application uses:

```text
public/index.php
```

as its Front Controller.

For the configured local environment, open:

```text
http://localhost/Php-Course/Alzikrayat/public/
```

The `public/` directory is the application's web-accessible entry point.

---

## Version Control

The project is maintained using **Git** and hosted on GitHub.

The repository contains **10+ commits**, satisfying the course requirement for Git-based development tracking.

Development commits document major project milestones, including:

* Initial MVC structure
* Database configuration
* README documentation
* Local application documentation
* Authentication security
* Uploaded-directory protection
* HTTP security headers
* Photo upload protection

---

## Author

**Anfal Abdelhamed Ali**

**Software Engineering 4th year**
**Sudan University of Science and Technology**

GitHub: **fofaJourge**

---

## Project Documentation

The project documentation includes:

* Project README
* Technical project report
* Testing documentation
* GitHub version history

---

**Alzikrayat — Advanced Web Technology Project**
