-- Posts table for Study Aids and Community blog posts.
-- Amanda and Fran can create posts with a title, body text, and optional image.

CREATE TABLE posts (
    id SERIAL PRIMARY KEY,
    section VARCHAR(50) NOT NULL DEFAULT 'community',
        -- 'community' or 'study_aids'
    author_name VARCHAR(100) NOT NULL,
    title VARCHAR(500),
    body TEXT NOT NULL,
    image_url VARCHAR(1000),
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE INDEX idx_posts_section ON posts (section);
CREATE INDEX idx_posts_created_at ON posts (created_at DESC);
