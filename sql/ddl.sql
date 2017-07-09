DROP TABLE IF EXISTS tweets;
CREATE TABLE tweets
(
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    json TEXT NOT NULL,
    tweet_id VARCHAR(22) UNIQUE,
    tweet TEXT,
    salutation VARCHAR(255),
    rejected BOOLEAN
);
