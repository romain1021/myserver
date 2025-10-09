-- Création de la base de données
CREATE DATABASE IF NOT EXISTS forhomeserver CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE forhomeserver;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    identifiant VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    admin BOOLEAN NOT NULL DEFAULT 0,
    photo VARCHAR(255) DEFAULT NULL -- chemin de la photo de profil
);

-- Table des articles
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    date_publication DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    auteur VARCHAR(255) NOT NULL
);

-- Exemple d'insertion d'utilisateur admin
INSERT INTO users (email, password, admin, photo) VALUES (
    'admin@exemple.com',
    '$2y$10$exempledehashdemotdepasse', -- Remplacez par un vrai hash
    1,
    'photos/admin.jpg' -- exemple de photo de profil
);
