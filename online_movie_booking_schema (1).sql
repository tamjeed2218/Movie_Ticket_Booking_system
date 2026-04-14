
-- Table: roles
CREATE TABLE roles (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name ENUM('Registered User', 'Admin') NOT NULL
);

-- Table: users
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    age INT CHECK (age > 0),
    role_id INT,
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

-- Table: cinemas
CREATE TABLE cinemas (
    cinema_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(255),
    image_path VARCHAR(255)
);

-- Table: movies
CREATE TABLE movies (
    movie_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    genre VARCHAR(50),
    duration INT, -- in minutes
    description TEXT,
    trailer_link VARCHAR(255),
    image_path VARCHAR(255)
);

-- Table: shows
CREATE TABLE shows (
    show_id INT PRIMARY KEY AUTO_INCREMENT,
    movie_id INT,
    cinema_id INT,
    show_date DATE,
    show_time TIME,
    seat_class ENUM('Gold', 'Platinum', 'Box') NOT NULL,
    price INT CHECK (price >= 0),
    FOREIGN KEY (movie_id) REFERENCES movies(movie_id),
    FOREIGN KEY (cinema_id) REFERENCES cinemas(cinema_id)
);

-- Table: bookings
CREATE TABLE bookings (
    booking_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    show_id INT,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (show_id) REFERENCES shows(show_id)
);

-- Table: booking_details
CREATE TABLE booking_details (
    detail_id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT,
    seat_count INT CHECK (seat_count > 0),
    user_age INT CHECK (user_age > 0),
    seat_price INT GENERATED ALWAYS AS (
        CASE 
            WHEN user_age BETWEEN 3 AND 12 THEN 550
            ELSE 1050
        END
    ) STORED,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id)
);

-- Table: movie_ratings
CREATE TABLE movie_ratings (
    rating_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    movie_id INT,
    rating TINYINT CHECK (rating BETWEEN 1 AND 5),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (movie_id) REFERENCES movies(movie_id)
);

-- Table: user_reviews
CREATE TABLE user_reviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    cinema_id INT,
    review_text TEXT,
    rating TINYINT CHECK (rating BETWEEN 1 AND 5),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (cinema_id) REFERENCES cinemas(cinema_id)
);
