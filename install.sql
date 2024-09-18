CREATE DATABASE IF NOT EXISTS todo_app;

USE todo_app;

CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255) NOT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,  -- Allow NULL by default
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Set the default to current timestamp
);

CREATE TABLE IF NOT EXISTS task_attachments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    attachment_type VARCHAR(255) CHECK (attachment_type = 'note' OR attachment_type = 'url' OR attachment_type = 'file'),
    body VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FK_attachment FOREIGN KEY (task_id)
    REFERENCES tasks(id)
);