import os
import hashlib
import feedparser
import trafilatura
import demoji
from datetime import datetime
from typing import List, Optional, Dict, Any
from fastapi import FastAPI, HTTPException, Query
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, Field

app = FastAPI(
    title="SISMANTIK NLP & Scraping Engine",
    description="Microservice Mandiri untuk Ingestion Berita & Analisis Sentimen Publik",
    version="1.0.0"
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Inisialisasi demoji database
try:
    demoji.download_codes()
except Exception:
    pass

# Kamus Normalisasi Slang & Dialek Daerah (Contoh Awal)
SLANG_LOCAL_DICTIONARY: Dict[str, str] = {
    # Slang & Singkatan Populer
    "gak": "tidak",
    "nggak": "tidak",
    "bgt": "banget",
    "banget": "sangat",
    "bgt": "sangat",
    "bener": "benar",
    "gimana": "bagaimana",
    "gmna": "bagaimana",
    "bapuk": "buruk",
    "ancur": "hancur",
    "parah": "sangat buruk",
    "bego": "bodoh",
    "korup": "korupsi",
    "gercep": "cepat tanggap",
    "mantul": "mantap betul",
    "top": "sangat baik",
    "jos": "bagus",
    "josss": "sangat bagus",
    
    # Dialek Jawa / Daerah
    "dalan": "jalan",
    "ajur": "rusak",
    "remuk": "rusak parah",
    "rusak parah": "rusak parah",
    "suwun": "terima kasih",
    "matur nuwun": "terima kasih",
    "ngelu": "pusing",
    "mumet": "pusing",
    "apik": "bagus",
    "apik tenan": "sangat bagus",
    "sae": "baik",
    "ora": "tidak",
    "ora ceto": "tidak jelas",
    "ra cetho": "tidak jelas",
    "lambat pol": "sangat lambat",
    "lemot": "lambat",
    "peteng": "gelap",
    "pepeteng": "kegelapan",
    "banjir rek": "banjir",
    "kebanjiran": "tergenang banjir",
}

class ScrapeFeedRequest(BaseModel):
    feed_url: str
    max_items: int = Field(default=30, ge=1, le=200)
    start_date: Optional[str] = None # format YYYY-MM-DD
    end_date: Optional[str] = None   # format YYYY-MM-DD

class ScrapedItem(BaseModel):
    title: str
    url: str
    author: Optional[str] = None
    published_at: Optional[str] = None
    summary: Optional[str] = None
    content: str
    content_hash: str

class ScrapeArticleRequest(BaseModel):
    url: str

class PreprocessRequest(BaseModel):
    text: str

class PreprocessResponse(BaseModel):
    original_text: str
    cleaned_text: str
    emojis_found: List[str]
    content_hash: str

@app.get("/health")
def health_check():
    return {
        "status": "healthy",
        "service": "SISMANTIK Python Microservice",
        "timestamp": datetime.now().isoformat(),
        "cpu_threads": os.cpu_count(),
    }

@app.post("/api/preprocess", response_model=PreprocessResponse)
def preprocess_text(req: PreprocessRequest):
    raw_text = req.text
    
    # 1. Ekstraksi Emoji
    emojis = demoji.findall(raw_text)
    clean_text = demoji.replace(raw_text, "")
    
    # 2. Lowercase & tokenisasi sederhana
    words = clean_text.lower().split()
    normalized_words = [SLANG_LOCAL_DICTIONARY.get(w, w) for w in words]
    normalized_text = " ".join(normalized_words)
    
    content_hash = hashlib.sha256(raw_text.encode("utf-8")).hexdigest()
    
    return PreprocessResponse(
        original_text=raw_text,
        cleaned_text=normalized_text,
        emojis_found=list(emojis.keys()),
        content_hash=content_hash
    )

@app.post("/api/scrape/feed", response_model=List[ScrapedItem])
def scrape_rss_feed(req: ScrapeFeedRequest):
    feed = feedparser.parse(req.feed_url)
    if feed.bozo and not feed.entries:
        raise HTTPException(status_code=400, detail=f"Gagal membaca RSS Feed: {feed.bozo_exception}")

    results: List[ScrapedItem] = []
    
    # Parsing batas tanggal jika ada
    start_dt = datetime.strptime(req.start_date, "%Y-%m-%d") if req.start_date else None
    end_dt = datetime.strptime(req.end_date, "%Y-%m-%d") if req.end_date else None

    for entry in feed.entries:
        if len(results) >= req.max_items:
            break

        pub_date = None
        pub_str = None
        if hasattr(entry, "published_parsed") and entry.published_parsed:
            try:
                pub_date = datetime(*entry.published_parsed[:6])
                pub_str = pub_date.strftime("%Y-%m-%d %H:%M:%S")
            except Exception:
                pass

        # Filter tanggal jika parameter disediakan
        if pub_date:
            if start_dt and pub_date.date() < start_dt.date():
                continue
            if end_dt and pub_date.date() > end_dt.date():
                continue

        title = getattr(entry, "title", "Tanpa Judul")
        url = getattr(entry, "link", "")
        author = getattr(entry, "author", "Redaksi")
        summary = getattr(entry, "summary", "")

        # Jika konten lengkap ada di feed, gunakan itu; jika tidak gunakan summary
        content = summary
        if hasattr(entry, "content"):
            content = entry.content[0].value

        content_hash = hashlib.sha256((url + title).encode("utf-8")).hexdigest()

        results.append(ScrapedItem(
            title=title,
            url=url,
            author=author,
            published_at=pub_str,
            summary=summary,
            content=content,
            content_hash=content_hash
        ))

    return results

@app.post("/api/scrape/article")
def scrape_single_article(req: ScrapeArticleRequest):
    downloaded = trafilatura.fetch_url(req.url)
    if not downloaded:
        raise HTTPException(status_code=400, detail="Tidak dapat mengunduh halaman dari URL tersebut.")

    extracted = trafilatura.extract(
        downloaded,
        include_comments=False,
        include_tables=False,
        no_fallback=False,
        output_format="json",
        with_metadata=True
    )

    if not extracted:
        # Fallback teks biasa
        plain_text = trafilatura.extract(downloaded) or ""
        return {
            "title": "",
            "content": plain_text,
            "author": "",
            "date": None,
            "url": req.url
        }

from nlp.sentiment import LocalIndoBERTEngine

nlp_engine = LocalIndoBERTEngine()

class SentimentAnalyzeRequest(BaseModel):
    text: str
    agencies: Optional[List[Dict[str, Any]]] = []

class SentimentAnalyzeResponse(BaseModel):
    sentiment: Dict[str, Any]
    emotion: Dict[str, Any]
    framing: Optional[Dict[str, Any]] = None
    aspects: List[Dict[str, Any]]
    geo_detected: Optional[Dict[str, Any]] = None

@app.post("/api/sentiment/analyze", response_model=SentimentAnalyzeResponse)
def analyze_text_sentiment(req: SentimentAnalyzeRequest):
    text = req.text
    if not text.strip():
        raise HTTPException(status_code=400, detail="Teks tidak boleh kosong.")

    sentiment = nlp_engine.analyze_sentiment(text)
    emotion = nlp_engine.analyze_emotion(text)
    framing = nlp_engine.analyze_framing(text)
    aspects = nlp_engine.analyze_absa(text, req.agencies or [])
    geo = nlp_engine.extract_geo(text)

    return SentimentAnalyzeResponse(
        sentiment=sentiment,
        emotion=emotion,
        framing=framing,
        aspects=aspects,
        geo_detected=geo
    )


