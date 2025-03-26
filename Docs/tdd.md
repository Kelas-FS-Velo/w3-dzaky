# Smart Library with AI

## Technical Design Document (TDD)

### **1. Architecture Overview**
The system follows a microservices-inspired modular architecture:
- **Frontend:** Nuxt.js application with SSR for improved SEO and performance.
- **Backend:** Laravel REST API handling business logic and database interactions.
- **AI Engine:** External or integrated AI module for personalized recommendations.
- **Database:** MySQL or PostgreSQL, structured for scalability and performance.
- **Authentication:** JWT-based authentication using Laravel Passport or Sanctum.

### **2. API Design**
#### **Endpoints**
| Method | Endpoint                 | Description                       |
|--------|--------------------------|-----------------------------------|
| GET    | /api/books               | Fetch all books                  |
| GET    | /api/books/{id}          | Get book details                 |
| POST   | /api/books               | Add a new book                   |
| PUT    | /api/books/{id}          | Update book details              |
| DELETE | /api/books/{id}          | Remove a book                    |
| POST   | /api/auth/login          | User login                       |
| POST   | /api/auth/register       | User registration                |
| GET    | /api/recommendations     | Get AI-powered book recommendations |

### **3. Database Schema**
#### **Users Table**
| Column      | Type        | Description                |
|------------|------------|----------------------------|
| id         | UUID       | Primary Key                |
| name       | String     | User full name             |
| email      | String     | Unique email address       |
| password   | String     | Hashed password            |
| role       | Enum       | User role (admin, member)  |

#### **Books Table**
| Column      | Type        | Description                |
|------------|------------|----------------------------|
| id         | UUID       | Primary Key                |
| title      | String     | Book title                 |
| author     | String     | Book author                |
| available  | Boolean    | Availability status        |

#### **Genres Table**
| Column | Type  | Description  |
|--------|------|-------------|
| id     | UUID | Primary Key  |
| name   | String | Genre name |

#### **Book_Genre (Pivot Table)**
| Column    | Type  | Description                          |
|-----------|------|--------------------------------------|
| book_id   | UUID | Foreign Key to Books table         |
| genre_id  | UUID | Foreign Key to Genres table        |

#### **Borrowed Books Table**
| Column      | Type        | Description                |
|------------|------------|----------------------------|
| id         | UUID       | Primary Key                |
| user_id    | UUID       | Foreign Key to Users table |
| book_id    | UUID       | Foreign Key to Books table |
| borrowed_at| Timestamp  | Borrow date                |
| returned_at| Timestamp  | Return date (nullable)     |

### **4. AI Engine Design**
- The AI module will analyze user borrowing history and preferences.
- It will use a recommendation algorithm such as collaborative filtering or content-based filtering.
- Integration will be done via API calls to an AI microservice.