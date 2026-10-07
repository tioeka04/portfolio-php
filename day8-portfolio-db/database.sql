-- ==========================================================
-- portfolio_db — import lewat phpMyAdmin (tab Import) atau
-- jalankan di tab SQL.
-- ==========================================================

CREATE DATABASE IF NOT EXISTS portfolio_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE portfolio_db;

CREATE TABLE IF NOT EXISTS projects (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title       VARCHAR(150) NOT NULL,
  description TEXT         NOT NULL,
  tech        VARCHAR(255) NOT NULL COMMENT 'dipisah koma, contoh: PHP, MySQL',
  category    VARCHAR(30)  NOT NULL DEFAULT 'other',
  file_path   VARCHAR(255)          DEFAULT NULL COMMENT 'contoh: uploads/abc123.pdf',
  created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data awal (opsional). file_path NULL = belum ada file;
-- upload file-nya lewat tombol Edit di halaman portfolio.
INSERT INTO projects (title, description, tech, category, file_path) VALUES
('Deteksi Teks AI-Generated',
 'Capstone project: model fine-tuned XLM-RoBERTa untuk mendeteksi berita berbahasa Inggris yang ditulis AI. Disajikan lewat REST API agar bisa digabung dengan sistem klasifikasi fake news tim.',
 'Python, XLM-RoBERTa, REST API, Gradio', 'ml', NULL),
('Platform Donasi ala Saweria',
 'Platform tipping untuk kreator dengan integrasi payment gateway (QRIS), antrian job, dan notifikasi real-time ke OBS lewat WebSocket.',
 'NestJS, PostgreSQL, Redis, BullMQ', 'backend', NULL),
('Website Portfolio + CMS',
 'Portfolio pribadi dengan halaman admin untuk mengelola project, pengalaman, dan CV langsung dari database.',
 'HTML, CSS, JavaScript, Supabase', 'fullstack', NULL);
