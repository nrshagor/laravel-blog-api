# **Blog Platform - Backend**

## **Introduction**

This is the backend of the blog platform, built using Laravel. The backend provides RESTful API endpoints for creating, viewing, editing, and deleting blog posts and comments. User authentication is managed using Laravel Sanctum.

## **Setup**

### **Prerequisites**

-   PHP 8.2
-   Composer
-   MySQL

### **Steps**

1. **Clone the Repository:**

```bash
   git clone https://github.com/nrshagor/laravel-blog-api.git
   cd blog-platform-backend
```

2. **Install Dependencies:**

```bash
 composer install
```

3. **Environment Configuration:**

-   Copy the .env.example file to create a new .env file:

```bash
 cp .env.example .env
```

-   Update the .env file with your database credentials:

```bash
 DB_CONNECTION=mysql
 DB_HOST=127.0.0.1
 DB_PORT=3306
 DB_DATABASE=blog
 DB_USERNAME=root
 DB_PASSWORD=
```

4. Generate Application Key:

```bash
 php artisan key:generate
```

5. Run Migrations:

```bash
 php artisan migrate
```

6. Start the Server:

```bash
 php artisan serve
```

## **API Reference**

| Method   | Endpoint                                    | Description                                 |
| :------- | :------------------------------------------ | :------------------------------------------ |
| `POST`   | `api/register`                              | Register a new user                         |
| `POST`   | `/api/login`                                | Login a user and generate a token           |
| `POST`   | `api/logout`                                | Logout a user and invalidate the token      |
| `GET`    | `api/posts`                                 | Retrieve a list of posts                    |
| `POST`   | `api/posts`                                 | Create a new post                           |
| `GET`    | `/api/posts/{post_id}`                      | Retrieve a single post                      |
| `PATCH`  | `api/posts/{post_id}`                       | Update a specific post                      |
| `DELETE` | `api/posts/{post_id}`                       | Delete a specific post                      |
| `GET`    | `api/posts/{post_id}/comments`              | List comments for a specific post           |
| `POST`   | `api/posts/{post_id}/comments`              | Add a comment to a specific post            |
| `GET`    | `api/posts/{post_id}/comments/{comment_id}` | Retrieve a specific comment                 |
| `PATCH`  | `api/posts/{post_id}/comments/{comment_id}` | Update a specific comment                   |
| `DELETE` | `api/posts/{post_id}/comments/{comment_id}` | Delete a specific comment                   |
| `GET`    | `/api/top-users`                            | Retrieve top 5 users with the most posts    |
| `GET`    | `/api/top-posts`                            | Retrieve top 5 posts with the most comments |

## **Unit Testing**

### Setup

1.  Environment Configuration for Testing:

-   Create a .env.testing file in the backend root directory:

```bash
DB_CONNECTION=mysql
DB_DATABASE=blog_test
DB_USERNAME=root
DB_PASSWORD=
```

2. Run the Tests:

```bash
php artisan test
```

## Unit Test Results

-   The following results were obtained from running the unit tests using PHPUnit:

```bash
php artisan test

   PASS  Tests\Unit\ExampleTest
  ✓ post creation validation errors                                                                                                             0.55s
  ✓ post creation unauthorized                                                                                                                  0.01s
  ✓ that true is true                                                                                                                           0.01s

   PASS  Tests\Feature\ExampleTest
  ✓ post creation                                                                                                                               0.04s
  ✓ post update                                                                                                                                 0.02s
  ✓ post deletion                                                                                                                               0.01s
  ✓ user can create post and add comment                                                                                                        0.03s
  ✓ the application returns a successful response                                                                                               0.03s

  Tests:    8 passed (20 assertions)
  Duration: 0.90s
```
