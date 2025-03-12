# API Documentation for TP2

This document provides instructions on how to use the API developed for TP2.

## Base URL

All API endpoints are relative to the following base URL:

```
{prefix}/BackEnd/tp2/api
```

{prefix} depends on your configuration (for me it is `http://localhost/`)
In the following sections the url above will be referred as {baseUrl}

## Endpoints

### Users

#### Create a User

-   **Endpoint:** `?users`
-   **Method:** `POST`
-   **Description:** Creates a new user.
-   **Request Body:**
    ```json
    {
        "username": "string",
        "password": "string",
        "email": "string"
    }
    ```
-   **Response:**
    -   `201 Created`: User created successfully.
    -   `400 Bad Request`: Invalid request body.
    -   `409 Conflict`: User already exists.
- **Example:**
```bash
curl -X POST -H "Content-Type: application/json" -d '{"name": "testuser", "email": "test@example.com"}' {baseUrl}?users
```

#### Get All Users

-   **Endpoint:** `?users`
-   **Method:** `GET`
-   **Description:** Retrieves all users.
-   **Response:**
    -   `200 OK`: Returns a list of users.
    -   `500 Internal Server Error`: An error occurred on the server.
- **Example:**
```bash
curl {baseUrl}?users
```

#### Get User by ID

-   **Endpoint:** `/users/{id}`
-   **Method:** `GET`
-   **Description:** Retrieves a user by their ID.
-   **Response:**
    -   `200 OK`: Returns the user.
    -   `404 Not Found`: User not found.
    -   `500 Internal Server Error`: An error occurred on the server.
- **Example:**
```bash
curl {baseUrl}?users/1
```

#### Update User by ID

-   **Endpoint:** `/users/{id}`
-   **Method:** `PUT`
-   **Description:** Updates a user by their ID.
-   **Request Body:**
    ```json
    {
        "username": "string",
        "password": "string",
        "email": "string"
    }
    ```
-   **Response:**
    -   `200 OK`: User updated successfully.
    -   `400 Bad Request`: Invalid request body.
    -   `404 Not Found`: User not found.
    -   `500 Internal Server Error`: An error occurred on the server.
- **Example:**
```bash
curl -X PUT -H "Content-Type: application/json" -d '{"name": "testuser", "email": "test@example.com"}' {baseUrl}?users/1
```

#### Delete User by ID

-   **Endpoint:** `/users/{id}`
-   **Method:** `DELETE`
-   **Description:** Deletes a user by their ID.
-   **Response:**
    -   `204 No Content`: User deleted successfully.
    -   `404 Not Found`: User not found.
    -   `500 Internal Server Error`: An error occurred on the server.
- **Example:**
```bash
curl -X DELETE {baseUrl}?users/1