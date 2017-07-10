CREATE TABLE IF NOT EXISTS tweets
(
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    json TEXT NOT NULL,
    tweet_id VARCHAR(22) UNIQUE,
    tweet TEXT,
    salutation VARCHAR(255),
    rejected BOOLEAN
);
