CREATE TABLE users_sessions (
    ID INT NOT NULL AUTO_INCREMENT PRIMARY_KEY,
    USER_ID INT NOT NULL,
    CREATED_AT timestamp default CURRENT_TIMESTAMP,
    EXPIRES_AT timestamp NOT NULL,
    SESSION_TOKEN CHAR(64) NOT NULL UNIQUE,
    IP_ADDRESS VARCHAR(39) NOT NULL,
    FOREIGN KEY (USER_ID) REFERENCES USERS(ID)
);