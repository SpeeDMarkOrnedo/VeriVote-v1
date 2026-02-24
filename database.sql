CREATE DATABASE verivote;
USE verivote;

-- =========================
-- STUDENTS
-- =========================
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    course VARCHAR(100) NOT NULL,
    has_voted BOOLEAN DEFAULT 0
);

-- =========================
-- POSITIONS
-- =========================
CREATE TABLE positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_name VARCHAR(100) NOT NULL
);

-- =========================
-- CANDIDATES
-- =========================
CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    photo LONGBLOB,
    position_id INT NOT NULL,
    FOREIGN KEY (position_id)
        REFERENCES positions(id)
        ON DELETE CASCADE
);

-- =========================
-- VOTES
-- =========================
CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    candidate_id INT NOT NULL,
    position_id INT NOT NULL,

    FOREIGN KEY (student_id)
        REFERENCES students(id)
        ON DELETE CASCADE,

    FOREIGN KEY (candidate_id)
        REFERENCES candidates(id)
        ON DELETE CASCADE,

    FOREIGN KEY (position_id)
        REFERENCES positions(id)
        ON DELETE CASCADE,

    -- Prevent double voting per position
    UNIQUE(student_id, position_id)
);

-- =========================
-- ADMIN
-- =========================
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Default Admin Login
INSERT INTO admin (username, password)
VALUES ('admin', MD5('admin123'));