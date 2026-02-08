-- Site settings stored in the database so Amanda can update things
-- like "Currently Reading" without editing code.

CREATE TABLE settings (
    key VARCHAR(100) PRIMARY KEY,
    value TEXT NOT NULL
);

-- Default settings
INSERT INTO settings (key, value) VALUES
    ('currently_reading_book', 'The Gospel According to St. Matthew'),
    ('currently_reading_chapter', 'Chapter 1, Verses 1–17');
