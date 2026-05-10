-- Discussion answers: stores each person's responses to the discussion questions.
-- The responses column is JSONB containing an array of {question, answer} objects,
-- so if questions change in the future, old answers keep their original questions.
CREATE TABLE IF NOT EXISTS discussion_answers (
    id SERIAL PRIMARY KEY,
    chapter_slug VARCHAR(50) NOT NULL,
    author_name VARCHAR(100) NOT NULL,
    author_password_hash VARCHAR(255) NOT NULL,
    responses JSONB NOT NULL DEFAULT '[]',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- One entry per person per chapter (case-insensitive name matching)
CREATE UNIQUE INDEX IF NOT EXISTS discussion_answers_chapter_author
    ON discussion_answers (chapter_slug, LOWER(author_name));
