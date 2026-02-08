-- Admin accounts for Amanda and Fran.
-- Default password for both: catena2025 (change it after first login!)

CREATE TABLE admins (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    display_name VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);

INSERT INTO admins (username, display_name, password_hash) VALUES
    ('amanda', 'Amanda', '$2y$12$QJaIrq10XWgYnvPqHkkWh.wjHHDPGAyYC4HueK/JwdvwzTFZ77Cmm'),
    ('fran', 'Fran', '$2y$12$QJaIrq10XWgYnvPqHkkWh.wjHHDPGAyYC4HueK/JwdvwzTFZ77Cmm');
