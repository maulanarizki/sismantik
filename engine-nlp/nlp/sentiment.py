import os
import re
from typing import Dict, List, Any, Optional

# Kamus Kata Sentimen & Emosi Bahasa Indonesia + Dialek Lokal (Jawa/Sunda/Gaul)
BASE_LOCAL_SENTIMENT_LEXICON = {
    # Positif (Apresiasi / Harapan)
    "bagus": 0.8, "baik": 0.7, "mantap": 0.9, "mantul": 0.9, "keren": 0.8,
    "top": 0.8, "jos": 0.8, "josss": 0.9, "apik": 0.8, "sae": 0.7,
    "hebat": 0.9, "terima kasih": 0.7, "suwun": 0.7, "matur nuwun": 0.8,
    "cepat": 0.7, "gercep": 0.85, "ramah": 0.8, "bersih": 0.7, "puas": 0.85,
    "teratur": 0.7, "mudah": 0.6, "lancar": 0.7, "sukses": 0.8, "maju": 0.7,
    "tertib": 0.65, "bangga": 0.8, "terbantu": 0.8, "beres": 0.75, "selesai": 0.6,
    "semoga": 0.5, "berharap": 0.5, "sukses selalu": 0.9, "amanah": 0.8,
    "berkualitas": 0.8, "responsif": 0.8, "transparan": 0.8, "adil": 0.8,
    
    # Negatif (Marah / Kecewa / Sedih / Takut)
    "rusak": -0.8, "ajur": -0.9, "remuk": -0.9, "hancur": -0.9, "ancur": -0.85,
    "lambat": -0.7, "lemot": -0.75, "lambat pol": -0.85, "bapuk": -0.85,
    "buruk": -0.8, "jelek": -0.75, "bobrok": -0.9, "korup": -0.95, "pungli": -0.95,
    "kecewa": -0.8, "parah": -0.85, "parah bgt": -0.9, "parah banget": -0.9,
    "bego": -0.9, "bodoh": -0.85, "payah": -0.8, "tidak becus": -0.9, "gak becus": -0.9,
    "zalim": -0.95, "jahat": -0.85, "bohong": -0.8, "palsu": -0.75, "tipu": -0.9,
    "banjir": -0.7, "kebanjiran": -0.75, "tergenang": -0.6, "macet": -0.65,
    "peteng": -0.6, "gelap": -0.5, "mati lampu": -0.7, "antrean panjang": -0.7,
    "antre": -0.4, "antri": -0.4, "antrian parah": -0.85, "ribet": -0.7,
    "sulit": -0.6, "susah": -0.6, "ngelu": -0.65, "mumet": -0.7, "pusing": -0.6,
    "ora ceto": -0.8, "ra cetho": -0.8, "rugi": -0.7, "sia-sia": -0.75,
    "marah": -0.85, "emosi": -0.7, "geram": -0.85, "kesal": -0.75, "benci": -0.9,
    "was-was": -0.7, "takut": -0.75, "khawatir": -0.65, "cemas": -0.65,
    "sedih": -0.7, "prihatin": -0.6, "menangis": -0.7, "tangis": -0.65
}

def _load_inset_lexicon() -> Dict[str, float]:
    """Memuat kamus InSet (Indonesian Sentiment Lexicon) dan menggabungkannya dengan kamus lokal."""
    lex = {}
    base_dir = os.path.abspath(os.path.join(os.path.dirname(__file__), "..", "data", "inset"))
    pos_file = os.path.join(base_dir, "positive.tsv")
    neg_file = os.path.join(base_dir, "negative.tsv")

    if os.path.exists(pos_file):
        try:
            with open(pos_file, "r", encoding="utf-8") as f:
                for line in f:
                    parts = line.strip().split("\t")
                    if len(parts) == 2:
                        try:
                            w = parts[0].strip().lower()
                            score = float(parts[1]) / 5.0
                            lex[w] = min(1.0, max(0.1, score))
                        except ValueError:
                            pass
        except Exception as e:
            print(f"Warning loading InSet positive: {e}")

    if os.path.exists(neg_file):
        try:
            with open(neg_file, "r", encoding="utf-8") as f:
                for line in f:
                    parts = line.strip().split("\t")
                    if len(parts) == 2:
                        try:
                            w = parts[0].strip().lower()
                            score = float(parts[1]) / 5.0
                            lex[w] = max(-1.0, min(-0.1, score))
                        except ValueError:
                            pass
        except Exception as e:
            print(f"Warning loading InSet negative: {e}")

    # Override with curated regional & local Jombang words
    lex.update(BASE_LOCAL_SENTIMENT_LEXICON)
    return lex

INDONESIAN_SENTIMENT_LEXICON = _load_inset_lexicon()

# Pembalik Polaritas (Negation)
NEGATIONS = {"tidak", "tak", "gak", "nggak", "bukan", "belum", "ora", "ndak"}

# Penguat Sentimen (Intensifiers)
INTENSIFIERS = {
    "sangat": 1.4, "banget": 1.4, "bgt": 1.4, "pol": 1.4, "sekali": 1.3,
    "amat": 1.3, "bener-bener": 1.3, "tenan": 1.3, "luar biasa": 1.4
}

# Emosi Klasifikasi Kata Kunci
EMOTION_KEYWORDS = {
    "marah": ["marah", "ngamuk", "emosi", "benci", "geram", "kesal", "bego", "bodoh", "kurang ajar", "brengsek", "bangsat", "tidak becus", "gak becus", "zalim"],
    "kecewa": ["kecewa", "bapuk", "payah", "rugi", "sia-sia", "bobrok", "remuk", "rusak", "ajur", "ora ceto", "ra cetho", "hancur", "ancur", "parah", "lemot", "lambat"],
    "takut": ["takut", "khawatir", "was-was", "ngeri", "cemas", "ancaman", "bahaya", "waspada", "rawan"],
    "sedih": ["sedih", "menangis", "prihatin", "duka", "kasihan", "iba", "merana", "nelangsa"],
    "apresiasi": ["terima kasih", "suwun", "matur nuwun", "mantap", "mantul", "top", "jos", "josss", "bagus", "apik", "hebat", "terbantu", "puas", "bangga", "teratur"],
    "harapan": ["semoga", "berharap", "mudah-mudahan", "ke depan", "moga", "berdoa", "insyaallah", "harapan"]
}

# Pembingkaian Isu Politik & Birokrasi (Narrative Framing Dimensions)
FRAMING_KEYWORDS = {
    "administrative": ["layanan", "server", "lambat", "antre", "antrean", "dukcapil", "birokrasi", "sop", "berkas", "izin", "ktp", "dokumen", "pejabat", "asn", "pegawai", "sistem", "aplikasi"],
    "corruption": ["pungli", "korup", "korupsi", "suap", "bagi proyek", "anggaran", "gratifikasi", "nepotisme", "fee", "markup", "penyelewengan", "oknum"],
    "economic": ["pajak", "retribusi", "pasar", "pedagang", "tarif", "mahal", "bansos", "sembako", "inflasi", "harga", "ekonomi", "umkm", "bantuan", "subsidi"],
    "conflict": ["dprd", "bupati", "gubernur", "partai", "oposisi", "gugat", "sidang", "perang opini", "demo", "unjuk rasa", "protes", "konflik", "debat", "pilkada"],
    "human_interest": ["korban", "meninggal", "tewas", "roboh", "anak", "tangis", "putus sekolah", "banjir", "bencana", "kecelakaan", "darurat", "luka-luka", "stunting"]
}

class LocalIndoBERTEngine:
    """
    Local NLP Engine untuk Analisis Sentimen, Emosi, dan ABSA.
    Menggabungkan Model Transformer Lokal dengan Context Lexicon Engine
    untuk akurasi maksimal pada teks formal berita & bahasa gaul/daerah.
    """
    def __init__(self):
        self.transformer_pipeline = None
        self._init_transformer()

    def _init_transformer(self):
        try:
            import torch
            from transformers import pipeline, AutoTokenizer, AutoModelForSequenceClassification

            torch.set_num_threads(8) # Optimasi untuk 12-core laptop CPU
            
            # Cek apakah model lokal tersedia di disk atau load model ringan
            model_name = "wirasatya/indobert-sentiment-smsa"
            self.transformer_pipeline = pipeline(
                "sentiment-analysis",
                model=model_name,
                tokenizer=model_name,
                device=-1 # CPU device
            )
            print("✓ Local IndoBERT Transformer pipeline successfully loaded.")
        except Exception as e:
            print(f"INFO: Running with optimized Local Context & Rule-Based NLP Engine ({e})")
            self.transformer_pipeline = None

    def analyze_sentiment(self, text: str) -> Dict[str, Any]:
        """
        Menganalisis sentimen 3-kelas: Positive, Neutral, Negative
        dengan score (-1.0 s.d. +1.0) dan confidence (0.0 s.d. 1.0).
        """
        text_lower = text.lower()
        words = re.findall(r'\b\w+\b', text_lower)
        
        pos_score = 0.0
        neg_score = 0.0
        invert = False
        multiplier = 1.0

        for i, word in enumerate(words):
            if word in NEGATIONS:
                invert = not invert
                continue

            if word in INTENSIFIERS:
                multiplier = INTENSIFIERS[word]
                continue

            # Cek frasa 2 kata jika ada
            if i < len(words) - 1:
                bigram = f"{word} {words[i+1]}"
                if bigram in INDONESIAN_SENTIMENT_LEXICON:
                    val = INDONESIAN_SENTIMENT_LEXICON[bigram] * multiplier
                    if invert:
                        val = -val * 0.7
                    if val > 0:
                        pos_score += val
                    else:
                        neg_score += abs(val)
                    invert = False
                    multiplier = 1.0
                    continue

            if word in INDONESIAN_SENTIMENT_LEXICON:
                val = INDONESIAN_SENTIMENT_LEXICON[word] * multiplier
                if invert:
                    val = -val * 0.7 # Inversi parsial jika ada kata negasi
                if val > 0:
                    pos_score += val
                else:
                    neg_score += abs(val)
                invert = False
                multiplier = 1.0

        # Jika transformer pipeline tersedia, kita padukan (ensemble)
        if self.transformer_pipeline:
            try:
                res = self.transformer_pipeline(text[:512])[0]
                label_trans = res['label'].lower()
                score_trans = res['score']
                if "pos" in label_trans:
                    pos_score += score_trans * 1.5
                elif "neg" in label_trans:
                    neg_score += score_trans * 1.5
            except Exception:
                pass

        total = pos_score + neg_score
        if total == 0:
            return {
                "sentiment_label": "neutral",
                "sentiment_score": 0.0,
                "confidence_score": 0.85
            }

        net_score = (pos_score - neg_score) / (pos_score + neg_score + 0.1)
        # Batasi antara -1.0 s.d. 1.0
        net_score = max(-1.0, min(1.0, net_score))

        if net_score > 0.15:
            label = "positive"
            confidence = min(0.98, 0.70 + (pos_score / (total + 0.5)) * 0.28)
        elif net_score < -0.15:
            label = "negative"
            confidence = min(0.98, 0.70 + (neg_score / (total + 0.5)) * 0.28)
        else:
            label = "neutral"
            confidence = 0.80

        return {
            "sentiment_label": label,
            "sentiment_score": round(net_score, 4),
            "confidence_score": round(confidence, 4)
        }

    def analyze_emotion(self, text: str) -> Dict[str, Any]:
        """
        Klasifikasi emosi 6 sumbu: marah, kecewa, takut, sedih, apresiasi, harapan.
        """
        text_lower = text.lower()
        scores = {emo: 0.05 for emo in EMOTION_KEYWORDS} # baseline

        for emo, kws in EMOTION_KEYWORDS.items():
            for kw in kws:
                if kw in text_lower:
                    scores[emo] += 0.35

        # Normalisasi ke probabilitas
        total = sum(scores.values())
        norm_scores = {k: round(v / total, 4) for k, v in scores.items()}
        dominant = max(norm_scores, key=norm_scores.get)

        return {
            "dominant_emotion": dominant,
            "emotion_scores": norm_scores
        }

    def analyze_framing(self, text: str) -> Dict[str, Any]:
        """
        Klasifikasi Pembingkaian Isu (Framing Analysis 5 Dimensi):
        - administrative: Masalah birokrasi, server, SOP, antrean, kelambatan aparatur
        - corruption: Suap, pungli, penyimpangan anggaran, nepotisme
        - economic: Pajak, retribusi, inflasi, pasar, bansos, pedagang
        - conflict: Perang opini antar elit, gugatan hukum, unjuk rasa/demo
        - human_interest: Kisah pilu kemanusiaan, korban, anak, sekolah roboh, bencana
        """
        text_lower = text.lower()
        scores = {frame: 0.1 for frame in FRAMING_KEYWORDS}

        for frame, kws in FRAMING_KEYWORDS.items():
            for kw in kws:
                if kw in text_lower:
                    scores[frame] += 0.4

        total = sum(scores.values())
        norm_scores = {k: round(v / total, 4) for k, v in scores.items()}
        dominant = max(norm_scores, key=norm_scores.get)

        return {
            "dominant_frame": dominant,
            "framing_scores": norm_scores
        }

    def analyze_absa(self, text: str, agencies: List[Dict[str, Any]]) -> List[Dict[str, Any]]:
        """
        Aspect-Based Sentiment Analysis (ABSA):
        Memecah kalimat berdasarkan tanda baca atau kata penghubung ('tapi', 'namun', 'sedangkan')
        dan mengasosiasikan sentimen spesifik ke OPD terkait.
        """
        clauses = re.split(r'[,;.!?]|\btetapi\b|\btapi\b|\bnamun\b|\bsedangkan\b', text, flags=re.IGNORECASE)
        results = []

        for agency in agencies:
            agency_id = agency.get("id")
            agency_name = agency.get("name")
            keywords = agency.get("keywords", [])
            if isinstance(keywords, str):
                import json
                try:
                    keywords = json.loads(keywords)
                except Exception:
                    keywords = [keywords]

            # Cari klausa yang memuat kata kunci dinas
            matched_clauses = []
            matched_keywords = []

            for clause in clauses:
                clause_lower = clause.lower()
                for kw in keywords:
                    if kw.lower() in clause_lower:
                        matched_clauses.append(clause)
                        matched_keywords.append(kw)

            if matched_clauses:
                combined_clause = " ".join(matched_clauses)
                clause_sentiment = self.analyze_sentiment(combined_clause)
                results.append({
                    "agency_id": agency_id,
                    "agency_name": agency_name,
                    "aspect_keyword": ", ".join(list(set(matched_keywords))),
                    "aspect_sentiment_label": clause_sentiment["sentiment_label"],
                    "aspect_score": clause_sentiment["sentiment_score"]
                })

        return results

    def extract_geo(self, text: str) -> Optional[Dict[str, Any]]:
        """
        Ekstraksi otomatis nama kecamatan atau daerah lokal Kabupaten Jombang.
        """
        text_lower = text.lower()

        # Daftar Resmi 21 Kecamatan Kabupaten Jombang & Wilayah Penting
        jombang_map = {
            "bandar kedungmulyo": "Kecamatan Bandar Kedungmulyo",
            "bandarkedungmulyo": "Kecamatan Bandar Kedungmulyo",
            "ploso": "Kecamatan Ploso",
            "kabuh": "Kecamatan Kabuh",
            "plandaan": "Kecamatan Plandaan",
            "kudu": "Kecamatan Kudu",
            "ngusikan": "Kecamatan Ngusikan",
            "wonosalam": "Kecamatan Wonosalam",
            "mojoagung": "Kecamatan Mojoagung",
            "peterongan": "Kecamatan Peterongan",
            "diwek": "Kecamatan Diwek",
            "cukir": "Kecamatan Diwek",
            "tebuireng": "Kecamatan Diwek",
            "tembelang": "Kecamatan Tembelang",
            "kesamben": "Kecamatan Kesamben",
            "sumobito": "Kecamatan Sumobito",
            "perak": "Kecamatan Perak",
            "ngoro": "Kecamatan Ngoro",
            "bareng": "Kecamatan Bareng",
            "mojowarno": "Kecamatan Mojowarno",
            "gudo": "Kecamatan Gudo",
            "megaluh": "Kecamatan Megaluh",
            "jogoroto": "Kecamatan Jogoroto",
            "tambakberas": "Kecamatan Jombang",
            "denanyar": "Kecamatan Jombang",
            "jombang kota": "Kecamatan Jombang",
            "kota jombang": "Kecamatan Jombang",
            "alun-alun jombang": "Kecamatan Jombang",
            "pasar citra niaga": "Kecamatan Jombang",
            "kali gunting": "Kecamatan Mojoagung",
            "kali konto": "Kecamatan Bandar Kedungmulyo",
            "afvour besuk": "Kecamatan Bandar Kedungmulyo",
            "jembatan ploso": "Kecamatan Ploso",
            "terminal kepuhsari": "Kecamatan Peterongan",
            "tpa banjardowo": "Kecamatan Jombang",
        }

        for kw, dist_name in jombang_map.items():
            if re.search(r'\b' + re.escape(kw) + r'\b', text_lower):
                return {
                    "district_name": dist_name,
                    "match_confidence": 0.98
                }

        # Fallback pola eksplisit "Kecamatan <Nama>" yang divalidasi ke 21 Kecamatan Jombang
        match_explicit = re.search(r'\b(?:kecamatan|kec\.)\s+([A-Za-z]+(?:\s+[A-Za-z]+)?)', text_lower)
        if match_explicit:
            cand = match_explicit.group(1).strip()
            for kw, dist_name in jombang_map.items():
                if kw in cand or cand in kw:
                    return {
                        "district_name": dist_name,
                        "match_confidence": 0.95
                    }

        return None
