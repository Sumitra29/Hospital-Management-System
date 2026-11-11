ALTER TABLE users
ADD reset_token_hash VARCHAR(255) NULL,
ADD reset_token_expires DATETIME NULL;