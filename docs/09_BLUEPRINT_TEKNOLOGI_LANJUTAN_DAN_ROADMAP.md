# BLUEPRINT TEKNOLOGI LANJUTAN & ROADMAP PENGEMBANGAN
## Mengubah SISMANTIK Menjadi Platform Intelijen Berstandar Enterprise
**Target Lingkungan:** Laptop Lokal / Server On-Premise (100% Mandiri, Non-Docker, Zero Paid API)

---

### 1. Peningkatan Arsitektur Teknologi Lanjutan

Untuk mentransformasikan sistem yang ada saat ini menjadi platform intelijen tingkat tinggi, berikut adalah 4 komponen teknologi baru yang akan diintegrasikan:

```
+-----------------------------------------------------------------------------------------+
|                              ARSITEKTUR ENTERPRISE SISMANTIK                            |
+-----------------------------------------------------------------------------------------+
| 1. SEMANTIC VECTOR EMBEDDINGS (ChromaDB / Qdrant Local)                                 |
|    - Model: BAAI/bge-m3 atau intfloat/multilingual-e5-base                              |
|    - Fungsi: Mengelompokkan ribuan berita/komentar secara semantik (Semantic Clustering)  |
|      tanpa perlu mendefinisikan kata kunci secara manual di awal.                       |
+-----------------------------------------------------------------------------------------+
| 2. LOCAL REASONING LLM (Ollama Engine - Llama-3.1-8B / Qwen2.5-7B Q4_K_M)               |
|    - Menghasilkan ringkasan memorandum eksekutif otomatis (Presidential Daily Brief).   |
|    - Melakukan Root-Cause Analysis (Analisis 5-Why).                                    |
|    - Menyusun draf siaran pers (*press release*) klarifikasi krisis untuk Humas Pemda.  |
+-----------------------------------------------------------------------------------------+
| 3. MASSIVE CRAWLER FLEET (Scrapy-Redis + Tor/Free Proxy Pool + Universal RSS-Bridge)   |
|    - Kapasitas penarikan: 500+ portal berita nasional, regional, dan media internasional|
|    - Bypass proteksi Cloudflare dan anti-bot dengan Playwright Stealth Sessions.        |
+-----------------------------------------------------------------------------------------+
| 4. STATISTICAL INTELLIGENCE CORE (Bayesian Smoothing & STL Anomaly Detection)           |
|    - Wilson Score Interval untuk margin of error sentimen.                              |
|    - CUSUM & PELT untuk deteksi titik balik opini (*inflection point*).                 |
+-----------------------------------------------------------------------------------------+
```

---

### 2. Ekstensi Skema Basis Data MySQL (Database Schema Evolution)

Tabel-tabel baru berikut akan ditambahkan ke database `hantu` untuk mendukung analisis intelijen:

```sql
-- 1. TABEL PENGELOMPOKAN ISU OTOMATIS BERBASIS SEMANTIK (ISSUE CLUSTERS)
CREATE TABLE `issue_clusters` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `cluster_uuid` CHAR(36) NOT NULL UNIQUE,
    `title` VARCHAR(255) NOT NULL, -- e.g. "Polemik Tarif Parkir Tepi Jalan Umum"
    `framing_category` ENUM('administrative', 'corruption', 'economic', 'conflict', 'human_interest') DEFAULT 'administrative',
    `lifecycle_stage` ENUM('dormant', 'emerging', 'crisis', 'resolution') DEFAULT 'emerging',
    `total_mentions` INT DEFAULT 0,
    `net_sentiment_score` DECIMAL(6,4) DEFAULT 0.0000,
    `confidence_lower` DECIMAL(6,4) DEFAULT 0.0000,
    `confidence_upper` DECIMAL(6,4) DEFAULT 0.0000,
    `is_high_alert` TINYINT(1) DEFAULT 0,
    `first_detected_at` DATETIME NOT NULL,
    `last_active_at` DATETIME NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_clusters_lifecycle` (`lifecycle_stage`),
    INDEX `idx_clusters_alert` (`is_high_alert`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TABEL AKTOR & JARINGAN MEDIA SOSIAL (ACTOR NODES & SNA)
CREATE TABLE `actor_nodes` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `platform` VARCHAR(50) NOT NULL, -- 'facebook', 'instagram', 'x', 'tiktok'
    `username` VARCHAR(255) NOT NULL,
    `display_name` VARCHAR(255) NULL,
    `account_type` ENUM('verified_media', 'official_gov', 'kol_influencer', 'citizen_organic', 'suspected_buzzer') DEFAULT 'citizen_organic',
    `degree_centrality` DECIMAL(8,4) DEFAULT 0.0000,
    `betweenness_centrality` DECIMAL(8,4) DEFAULT 0.0000,
    `total_interactions` INT DEFAULT 0,
    `bot_probability` DECIMAL(5,4) DEFAULT 0.0000,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_platform_user` (`platform`, `username`),
    INDEX `idx_account_type` (`account_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABEL MEMORANDUM INTELIJEN EKSEKUTIF (INTELLIGENCE BRIEFS)
CREATE TABLE `intelligence_briefs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `brief_date` DATE NOT NULL UNIQUE,
    `threat_level` ENUM('GREEN', 'YELLOW', 'ORANGE', 'RED') DEFAULT 'GREEN',
    `executive_summary` LONGTEXT NOT NULL,
    `key_actors` JSON NULL, -- Top influencers & buzzer networks
    `actionable_recommendations` JSON NOT NULL, -- Rekomendasi per dinas
    `draft_press_release` LONGTEXT NULL, -- Draf rilis pers siap pakai untuk Humas
    `generated_by` VARCHAR(100) DEFAULT 'ollama-qwen2.5-7b',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

### 3. Roadmap Implementasi Bertahap (Sprint Execution Plan)

Rencana kerja dibagi ke dalam 4 Sprint terukur:

```
[ SPRINT 1: INGESTION EXPANSION ]
-> Integrasi 50+ RSS Media Nasional, Regional, dan Internasional
-> Implementasi Scrapy-Redis & Google News Dynamic Aggregator
-> Integrasi RSS-Bridge untuk Penarikan Postingan Publik Medsos
Waktu: Pekan 1

[ SPRINT 2: STATISTICAL INTELLIGENCE CORE ]
-> Implementasi Bayesian Sentiment Smoothing di Service Analisis
-> Perhitungan Wilson Score Confidence Intervals pada Metrik Dashboard
-> Pemasangan Time-Series Anomaly Engine (STL + CUSUM)
Waktu: Pekan 2

[ SPRINT 3: SEMANTIC CLUSTERING & CIB DETECTION ]
-> Integrasi Vector Embedding Lokal (ChromaDB / Qdrant)
-> Klasterisasi Isu Otomatis (Topic Modeling tanpa Keyword Awal)
-> Engine Deteksi Buzzer (Temporal Sync + Text Duplication Clustering)
Waktu: Pekan 3

[ SPRINT 4: EXECUTIVE DASHBOARD V2 & OLLAMA MEMORANDUM ]
-> Tampilan Dashboard Baru: Threat Level Gauge, Framing Matrix, SNA Graph
-> Generator Otomatis Presidential Daily Brief & Draf Siaran Pers via Ollama
-> Sistem Notifikasi Darurat Multi-Kanal (Bot Telegram & WhatsApp Gateway)
Waktu: Pekan 4
```

---

### 4. Checklist Kesiapan Sebelum Eksekusi

| Komponen | Status | Catatan Teknis |
| :--- | :--- | :--- |
| **MySQL 8 (`hantu`)** | Ready | Siap ditambahkan 3 tabel migrasi lanjutan |
| **Laravel 11 & Native Blade UI** | Ready | Siap ditambahkan halaman visualisasi Framing & SNA |
| **Python 3.13 venv** | Ready | Pustaka `scipy`, `numpy`, `datasketch`, `torch` sudah terpasang |
| **Tor Proxy Daemon** | Optional | Dapat diaktifkan dengan `sudo apt install tor` untuk rotasi IP gratis |
| **Ollama Local LLM** | Ready to Hook | Endpoint `http://127.0.0.1:11434` untuk penalaran dokumen intelijen |

