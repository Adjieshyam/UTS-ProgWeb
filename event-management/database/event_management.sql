CREATE DATABASE IF NOT EXISTS event_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE event_management;

CREATE TABLE IF NOT EXISTS users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS events (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(180) NOT NULL,
 description TEXT NOT NULL,
 event_date DATETIME NOT NULL,
 location VARCHAR(180) NOT NULL,
 capacity INT UNSIGNED NOT NULL DEFAULT 100,
 registered_count INT UNSIGNED NOT NULL DEFAULT 0,
 category VARCHAR(80) NOT NULL DEFAULT 'Seminar',
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS registrations (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id INT UNSIGNED NOT NULL,
 event_id INT UNSIGNED NOT NULL,
 phone VARCHAR(30) NULL,
 notes VARCHAR(255) NULL,
 status ENUM('Registered','Attended','Cancelled') NOT NULL DEFAULT 'Registered',
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_reg_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
 CONSTRAINT fk_reg_event FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
 CONSTRAINT uq_user_event UNIQUE (user_id,event_id)
) ENGINE=InnoDB;

-- Demo login: demo@event.test / password
INSERT INTO users (name,email,password_hash)
VALUES ('Demo User','demo@event.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.')
ON DUPLICATE KEY UPDATE email=VALUES(email);

INSERT INTO events (title,description,event_date,location,capacity,registered_count,category)
SELECT 'National Seminar: AI & Cloud Innovations','Join industry experts exploring cutting-edge generative AI, distributed systems, and real-world implementations.','2026-11-24 13:00:00','Auditorium Main Tower 3rd Floor',100,45,'Seminar'
WHERE NOT EXISTS (SELECT 1 FROM events WHERE title='National Seminar: AI & Cloud Innovations');

INSERT INTO events (title,description,event_date,location,capacity,registered_count,category)
SELECT 'Gebyar Career Expo & Networking Fair','Connect directly with top technology and corporate partner recruiters, join resume workshops, and explore career opportunities.','2026-12-05 08:30:00','Horizon Multipurpose Hall',250,120,'Career Expo'
WHERE NOT EXISTS (SELECT 1 FROM events WHERE title='Gebyar Career Expo & Networking Fair');
