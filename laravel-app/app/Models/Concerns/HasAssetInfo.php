<?php

namespace App\Models\Concerns;

use App\Enums\AssetStatus;
use Illuminate\Support\Facades\Storage;

trait HasAssetInfo
{
    // يُستدعى تلقائياً عند إنشاء أي مودل يستخدم الـ Trait
    public function initializeHasAssetInfo(): void
    {
        $this->mergeFillable(['status', 'serial_number', 'image']);
        $this->mergeCasts(['status' => AssetStatus::class]);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }
}
