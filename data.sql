-- Drop existing tables (safe for development)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS behavior;
DROP TABLE IF EXISTS results;
DROP TABLE IF EXISTS teachers;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS parents;
SET FOREIGN_KEY_CHECKS = 1;

-- Parents
CREATE TABLE IF NOT EXISTS parents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Teachers
CREATE TABLE teachers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  teacher_code VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(150),
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Students
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admission_number VARCHAR(20) UNIQUE,
    full_name VARCHAR(100),
    date_of_birth DATE,
    date_of_enrollment DATE,
    parent_id INT,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES parents(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Academic results: one row per student/subject/term
CREATE TABLE results (
  id INT AUTO_INCREMENT PRIMARY KEY,  mysql -u username -p database_name < data.sql  mysql -u username -p database_name < data.sql
  student_id INT NOT NULL,
  subject VARCHAR(100) NOT NULL,
  term ENUM('Term1','Term2','Term3') NOT NULL,
  score DECIMAL(5,2),
  grade VARCHAR(5),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_results_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Behavior records
CREATE TABLE behavior (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  teacher_id INT,
  behavior_date DATE NOT NULL,
  behavior_type ENUM('Excellent','Good','Fair','Poor') NOT NULL,
  comments TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_behavior_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  CONSTRAINT fk_behavior_teacher FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Payments
CREATE TABLE payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  method VARCHAR(50),
  reference VARCHAR(150),
  paid_at DATE DEFAULT CURRENT_DATE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_payments_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed sample parents
INSERT INTO parents (full_name, phone, email, password) VALUES
('Alice Mwangi', '+254712345678', 'alice@example.com', 'password123'),
('John Otieno', '+254700111222', 'john@example.com', 'password123');

-- Seed sample teachers
INSERT INTO teachers (teacher_code, full_name, email, password) VALUES
('TCH001', 'Mrs. Wanjiru', 'wanjiru@school.local', 'password123'),
('TCH002', 'Mr. Kamau', 'kamau@school.local', 'password123');

-- Seed sample students (link to parent ids)
INSERT INTO students (admission_number, full_name, date_of_birth, date_of_enrollment, parent_id, password) VALUES
('STU001', 'Grace Njeri', '2010-05-12', '2018-01-10', 1, 'password123'),
('STU002', 'David Otieno', '2009-07-22', '2017-01-10', 2, 'password123'),
('STU003', 'Samuel Kariuki', '2011-03-02', '2019-01-10', 1, 'password123');

-- Seed sample results (multiple subjects & terms)
INSERT INTO results (student_id, subject, term, score, grade) VALUES
(1, 'Mathematics', 'Term1', 78.50, 'B+'),
(1, 'Mathematics', 'Term2', 82.00, 'A-'),
(1, 'English', 'Term1', 74.00, 'B'),
(1, 'English', 'Term2', 76.50, 'B+'),

(2, 'Mathematics', 'Term1', 65.00, 'C'),
(2, 'Mathematics', 'Term2', 70.00, 'B-'),
(2, 'English', 'Term1', 68.00, 'C+'),
(2, 'English', 'Term2', 72.50, 'B-'),

(3, 'Mathematics', 'Term1', 90.00, 'A'),
(3, 'English', 'Term1', 88.00, 'A-');

-- Seed sample behavior records
INSERT INTO behavior (student_id, teacher_id, behavior_date, behavior_type, comments) VALUES
(1, 1, '2025-10-01', 'Good', 'Participated well in class'),
(1, 2, '2025-10-02', 'Excellent', 'Helped classmates with exercises'),
(2, 1, '2025-10-01', 'Fair', 'Was late to class'),
(3, 2, '2025-10-03', 'Excellent', 'Outstanding project presentation');

-- Seed sample payments
INSERT INTO payments (student_id, amount, method, reference, paid_at) VALUES
(1, 5000.00, 'Mpesa', 'MPESA12345', '2025-09-15'),
(1, 2000.00, 'Cash', 'CASH2025-01', '2025-01-10'),
(2, 3000.00, 'Mpesa', 'MPESA67890', '2025-08-20');

