USE 'twitter';

CREATE TABLE users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(128) NOT NULL,
    username VARCHAR(255) NOT NULL,
    hash_pass VARCHAR(60) NOT NULL,
    UNIQUE (email)
);