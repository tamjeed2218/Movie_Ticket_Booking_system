
-- Insert roles
INSERT INTO roles (role_name) VALUES
('Registered User'),
('Admin');

-- Insert users
INSERT INTO users (name, email, password, age, role_id) VALUES
('Ali Khan', 'ali@example.com', 'password123', 25, 1),
('Sara Ahmed', 'sara@example.com', 'password123', 10, 1),
('Admin User', 'admin@example.com', 'adminpass', 30, 2);

-- Insert cinemas
INSERT INTO cinemas (name, location, image_path) VALUES
('CineStar Lahore', 'Gulberg, Lahore', 'images/cinestar_lahore.jpg'),
('Atrium Cinema', 'Saddar, Karachi', 'images/atrium_karachi.jpg');

-- Insert movies
INSERT INTO movies (title, genre, duration, description, trailer_link, image_path) VALUES
('The Legend of Maula Jatt', 'Action', 155, 'An action-packed film about rivalry and redemption.', 'https://youtube.com/trailer1', 'images/maula_jatt.jpg'),
('Load Wedding', 'Romantic Comedy', 130, 'A comedy-drama revolving around societal pressures.', 'https://youtube.com/trailer2', 'images/load_wedding.jpg');

-- Insert shows
INSERT INTO shows (movie_id, cinema_id, show_date, show_time, seat_class, price) VALUES
(1, 1, '2025-08-10', '18:00:00', 'Gold', 1050),
(1, 1, '2025-08-10', '21:00:00', 'Platinum', 1350),
(2, 2, '2025-08-11', '17:30:00', 'Box', 900);
