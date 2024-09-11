CREATE DATABASE IF NOT EXISTS todo_app;

USE todo_app;

CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255) NOT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,  -- Allow NULL by default
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Set the default to current timestamp
);
