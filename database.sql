CREATE DATABASE verivote;
USE verivote;

-- STUDENTS
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) UNIQUE,
    number VARCHAR(50),
    has_voted BOOLEAN DEFAULT 0
);

-- POSITIONS
CREATE TABLE positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_name VARCHAR(100) NOT NULL
);
-- CANDIDATES
CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    photo LONGBLOB,  -- stores the actual image file
    position_id INT,
    FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE CASCADE
); 
-- VOTES
CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    candidate_id INT,
    position_id INT,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (candidate_id) REFERENCES candidates(id),
    FOREIGN KEY (position_id) REFERENCES positions(id)
);

-- ADMIN
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255)
);

INSERT INTO admin (username, password)
VALUES ('admin', MD5('admin123'));