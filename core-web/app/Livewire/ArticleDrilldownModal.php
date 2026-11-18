<?php

namespace App\Livewire;

use App\Models\RawContent;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleDrilldownModal extends Component
{
    use WithPagination;

    public bool $isOpen = false;
    public string $title = 'Daftar Berita Terkait';
    public string $subtitle = '';
    public ?string $sentiment = null;
    public ?int $agencyId = null;
    public ?string $districtName = null;
    public ?string $framingCategory = null;
    public ?string $keyword = null;
    public ?string $scope = null;
    public ?string $startDate = null;
    public ?string $endDate = null;

    #[On('open-article-drilldown')]
    public function openModal(array $params = []): void
    {
        $this->resetPage();
        $this->title = $params['title'] ?? 'Daftar Berita Terkait';
        $this->subtitle = $params['subtitle'] ?? '';
        $this->sentiment = $params['sentiment'] ?? null;
        $this->agencyId = isset($params['agencyId']) ? (int) $params['agencyId'] : null;
        $this->districtName = $params['districtName'] ?? null;
        $this->framingCategory = $params['framingCategory'] ?? null;
        $this->keyword = $params['keyword'] ?? null;
        $this->scope = $params['scope'] ?? null;
        $this->startDate = $params['startDate'] ?? null;
        $this->endDate = $params['endDate'] ?? null;

        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
    }

    public function render()
    {
        $articles = collect();
        $totalCount = 0;

        if ($this->isOpen) {
            $query = RawContent::query()
                ->with(['sentimentResult', 'geoExtractions', 'endpoint.source'])
                ->orderByDesc('published_at');

            if ($this->startDate && $this->endDate) {
                $query->whereBetween('published_at', [
                    Carbon::parse($this->startDate)->startOfDay(),
                    Carbon::parse($this->endDate)->endOfDay(),
                ]);
            }

            if ($this->scope && $this->scope !== 'all') {
                $query->where('scope', $this->scope);
            }

            if ($this->sentiment && $this->sentiment !== 'all') {
                $query->whereHas('sentimentResult', function ($q) {
                    $q->where('sentiment_label', $this->sentiment);
                });
            }

            if ($this->framingCategory && $this->framingCategory !== 'all') {
                $query->whereHas('sentimentResult', function ($q) {
                    $q->where('framing_category', $this->framingCategory);
                });
            }

            if ($this->districtName) {
                $dist = $this->districtName;
                $alias = str_replace('Bandar Kedungmulyo', 'Bandarkedung', $dist);
                $query->whereHas('geoExtractions', function ($q) use ($dist, $alias) {
                    $q->where('district_name', 'LIKE', "%{$dist}%")
                      ->orWhere('district_name', 'LIKE', "%{$alias}%");
                });
            }

            if ($this->agencyId) {
                $aId = $this->agencyId;
                $query->whereHas('aspectSentiments', function ($q) use ($aId) {
                    $q->where('agency_id', $aId);
                });
            }

            if ($this->keyword) {
                $kw = $this->keyword;
                $query->where(function ($q) use ($kw) {
                    $q->where('title', 'LIKE', "%{$kw}%")
                      ->orWhere('clean_text', 'LIKE', "%{$kw}%");
                });
            }

            $totalCount = $query->count();
            $articles = $query->paginate(8);
        }

        return view('livewire.article-drilldown-modal', [
            'articles' => $articles,
            'totalCount' => $totalCount,
        ]);
    }
}

