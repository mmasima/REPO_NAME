# Insurance Claim System - Backend

A PHP backend system for managing insurance claims, houses, and user authentication.

## Getting Started

### Setup Project
1. Clone the repository
2. Navigate to project directory
3. Start Docker containers:
   ```bash
   docker-compose up -d
   ```

The application will be running at `http://localhost:8080`

### Database Setup
Database will automatically initialize with the required tables when the container starts. Default credentials:
```
Host: localhost
Port: 13306
Database: insurance_claim
Username: someuser
Password: 123qwe
```

## Testing APIs with Postman

### Authentication APIs

#### 1. Register User
- **URL**: `http://localhost:8080/api/auth/register.php`
- **Method**: POST
- **Headers**: 
  - `Content-Type: application/json`
- **Body**:
  ```json
  {
    "username": "testuser",
    "email": "test@example.com",
    "password": "password123"
  }
  ```

#### 2. Login
- **URL**: `http://localhost:8080/api/auth/login.php`
- **Method**: POST
- **Headers**: 
  - `Content-Type: application/json`
- **Body**:
  ```json
  {
    "email": "test@example.com",
    "password": "password123"
  }
  ```

### House Management APIs

#### 1. Add House
- **URL**: `http://localhost:8080/api/houses/add.php`
- **Method**: POST
- **Headers**: 
  - `Content-Type: application/json`
- **Body**:
  ```json
  {
    "address": "Millbrok, Midrand",
    "geolocation": "40.7128,-74.0060",
    "description": "Water leak"
  }
  ```

### Example Response Formats

#### Success Response
```json
{
    "message": "Operation successful",
    "data": {
        // response data here
    }
}
```

#### Error Response
```json
{
    "error": "Error message here"
}
```

## Troubleshooting

1. If API returns connection error:
   - Check if Docker containers are running: `docker-compose ps`
   - Restart containers: `docker-compose restart`

2. If database connection fails:
   - Check if MySQL container is running: `docker logs mysql`
   - Verify database credentials in `.docker/env/docker.env`

3. To view API logs:
   ```bash
   docker-compose logs app
   ```