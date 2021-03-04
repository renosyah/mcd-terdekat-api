CREATE TABLE user(
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name TEXT,
    username TEXT,
    password TEXT
);

CREATE TABLE location(
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name TEXT,
    address TEXT,
    description TEXT,
    latitude FLOAT(53),
    longitude FLOAT(53),
    url_image TEXT 
);