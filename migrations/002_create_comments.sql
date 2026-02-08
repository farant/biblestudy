-- Comments on posts.
-- Anyone can leave a comment with just a name — no account required.

CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    post_id INTEGER NOT NULL REFERENCES posts(id) ON DELETE CASCADE,
    author_name VARCHAR(100) NOT NULL DEFAULT 'Guest',
    body TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_comments_post_id ON comments (post_id);
