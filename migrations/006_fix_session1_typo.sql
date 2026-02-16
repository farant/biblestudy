-- Migration 006: Fix typo in Session 1 post ("though" -> "thought")

UPDATE posts
SET body = REPLACE(body, ' though ', ' thought ')
WHERE section = 'session_notes';
