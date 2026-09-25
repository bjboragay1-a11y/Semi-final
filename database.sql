-- Task Manager Database
-- Import kini nga file sa phpMyAdmin o sa MySQL command line

CREATE DATABASE IF NOT EXISTS task_manager;
USE task_manager;

CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_name VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('Pending', 'Completed') NOT NULL DEFAULT 'Pending',
    due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample data (optional, pwede i-delete)
INSERT INTO tasks (task_name, description, status, due_date) VALUES
('Sample Task 1', 'This is a sample task description.', 'Pending', '2026-10-01'),
('Sample Task 2', 'Another sample task.', 'Completed', '2026-09-20');
