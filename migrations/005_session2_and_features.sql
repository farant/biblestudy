-- Migration 005: Update currently reading for Session 2, add quote of the week

BEGIN;

-- Update currently reading to Session 2
UPDATE settings SET value = 'Chapter 2, Pages 62–86' WHERE key = 'currently_reading_chapter';

-- Add a topic/description for the current reading
INSERT INTO settings (key, value) VALUES
    ('currently_reading_topic', 'The Magi, the Flight to Egypt, the Massacre of the Innocents, and the Return from Egypt.');

-- Quote of the Week (editable by admins from the dashboard)
INSERT INTO settings (key, value) VALUES
    ('quote_of_the_week_text', ''),
    ('quote_of_the_week_source', '');

COMMIT;
