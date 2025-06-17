# 📚 Library OOP System

A simple Object-Oriented PHP backend for managing books, built without frameworks, using PDO and MS SQL Server.  
Includes full CRUD endpoints and Postman tests.

---

## 1. System Architecture
+------------+ HTTP +--------------+ PDO/MS SQL +-------------+
| Client | <---------------> | PHP API | <---------------------> | Database |
| (JS/HTML) | (GET/POST/...) | (books.php) | (BookRepository) | (Books) |
+------------+ +--------------+ +-------------+

- **Client:** Frontend or API client (Postman, browser, JS app)
- **PHP API:** Entry point is `public/api/books.php`, which handles all HTTP requests.
- **Repository:** `classes/BookRepository.php` is the data access layer between the PHP API and the SQL database.
- **Database:** MS SQL Server, with a `Books` table.

---

## 2. Installation & Configuration

### Prerequisites
- PHP 8.x (with PDO + sqlsrv extensions enabled)
- MS SQL Server (local or remote)
- XAMPP/WAMP/Laragon or other local dev server (with Apache)
- Composer (optional, for dependency management)
- Postman (for API testing)

### Setup Steps

1. **Clone the repository**
    ```bash
    git clone https://github.com/MarsoMonika/Library.git
    ```

2. **Database setup**
    - Create a database called `Library`.
    - Create the `Books` table:
      ```sql
      CREATE TABLE Books (
          Id INT IDENTITY(1,1) PRIMARY KEY,
          Title NVARCHAR(255) NOT NULL,
          Author NVARCHAR(255) NOT NULL,
          PublishYear INT NOT NULL,
          IsAvailable BIT NOT NULL
      );
      ```
    - Insert some sample data if you want.

3. **Environment configuration**
    - Copy `.env.example` to `.env` and fill in your DB credentials:
      ```
      DB_HOST=localhost
      DB_NAME=Library
      DB_USER=your_username
      DB_PASS=your_password
      ```
    - **Do not commit your real credentials!** The real `.env` is in `.gitignore`.

4. **PHP/Apache config**
    - Serve the `public` directory as your DocumentRoot (see your VirtualHost config).
    - Or, with XAMPP/WAMP, access via:
      ```
      http://localhost/Library/public/api/books.php
      ```
    - Make sure the `pdo_sqlsrv` PHP extension is enabled.

5. **Composer dependencies**
    - If you use Composer:
      ```bash
      composer install
      ```

---

## 3. API Documentation

**Base URL:**  
http://localhost/Library/public/api/books.php

### Endpoints

| Method | Endpoint                 | Description                | Params / Body                       |
|--------|--------------------------|----------------------------|-------------------------------------|
| GET    | `/books.php`             | List all books             | -                                   |
| GET    | `/books.php?id={id}`     | Get book by ID             | `id` as query param                 |
| POST   | `/books.php`             | Create new book            | JSON: `title`, `author`, `publishYear`, `isAvailable` |
| PUT    | `/books.php`             | Update a book              | JSON: `id`, `title`, `author`, `publishYear`, `isAvailable` |
| DELETE | `/books.php?id={id}`     | Delete book by ID          | `id` as query param or JSON body    |

### Example Response (GET)
```json
[
{
"id": 1,
"title": "1984",
"author": "George Orwell",
"publishYear": 1949,
"isAvailable": true
},
...
]
```

### Sample POST Body

```json
{
  "title": "Clean Code",
  "author": "Robert C. Martin",
  "publishYear": 2008,
  "isAvailable": true
}

```
### Sample PUT Body
```json
{
  "id": 1,
  "title": "Clean Code",
  "author": "Robert C. Martin",
  "publishYear": 2008,
  "isAvailable": false
}
```
### Error Responses
400 Bad Request: Invalid input

404 Not Found: Book not found

500 Internal Server Error: Server/database error

### CORS
All endpoints accept cross-origin requests (CORS enabled).

### Testing
Import the provided Postman collection:

/postman/Library API.postman_collection.json
Run all requests to verify endpoints.

### Contact
   For questions, please open an issue or contact the maintainer: [marso.monika04@gmail.com]