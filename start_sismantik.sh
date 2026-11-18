#!/usr/bin/env bash
set -e

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" >/dev/null 2>&1 && pwd)"

echo "=========================================================="
echo "    MEMULAI SISMANTIK - MEDIA INTELLIGENCE (LOCAL MODE)   "
echo "=========================================================="

# 1. Pastikan MySQL & Redis aktif
echo "[1/4] Memeriksa konektivitas MySQL & Redis..."
mysql -h 127.0.0.1 -P 3306 -u dev -pmbg -e "USE hantu;" >/dev/null 2>&1 || {
    echo "ERROR: Tidak dapat terhubung ke MySQL 'hantu' di 127.0.0.1:3306 (user: dev, pass: mbg)"
    exit 1
}
redis-cli ping >/dev/null 2>&1 || {
    echo "ERROR: Server Redis tidak merespons di 127.0.0.1:6379"
    exit 1
}
echo "✓ MySQL & Redis terhubung aktif."

# 2. Jalankan Python Microservice di Port 8001 (jika belum berjalan)
echo "[2/4] Memeriksa Python Scraping & NLP Engine..."
if curl -s http://127.0.0.1:8001/health >/dev/null 2>&1; then
    echo "✓ Python Engine sudah aktif di http://127.0.0.1:8001"
else
    echo "-> Menjalankan Python FastAPI Microservice di latar belakang (Port 8001)..."
    cd "$DIR/engine-nlp"
    nohup ./venv/bin/uvicorn main:app --host 127.0.0.1 --port 8001 > uvicorn.log 2>&1 &
    sleep 2
    if curl -s http://127.0.0.1:8001/health >/dev/null 2>&1; then
        echo "✓ Python Engine berhasil dimulai."
    else
        echo "WARN: Periksa log di engine-nlp/uvicorn.log"
    fi
fi

# 3. Info Kredensial & URL
echo "[3/4] Konfigurasi Executive War-Room SISMANTIK:"
echo "   Dashboard : http://127.0.0.1:8000/dashboard (atau http://127.0.0.1:8000)"
echo "   Peta GIS  : http://127.0.0.1:8000/peta"
echo "   NLP Engine: http://127.0.0.1:8001/health"

# 4. Jalankan Laravel Web Server
echo "[4/4] Menjalankan Laravel Web Server (Port 8000)..."
cd "$DIR/core-web"
php artisan serve --host=127.0.0.1 --port=8000

