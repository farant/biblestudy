-- Migration 007: Correct currently reading chapter

UPDATE settings SET value = 'Chapter 1, Verses 18–25' WHERE key = 'currently_reading_chapter';
