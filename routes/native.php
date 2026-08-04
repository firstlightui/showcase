<?php

use App\NativeComponents\Layouts\ShowcaseLayout;
use App\NativeComponents\SegmentedShowcase;
use App\NativeComponents\Captures\SegmentedCapture;
use App\NativeComponents\StatusLabelShowcase;
use App\NativeComponents\Captures\StatusLabelCapture;
use Illuminate\Support\Facades\Route;

Route::native('/segmented', SegmentedShowcase::class)
    ->name('segmented')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/segmented', SegmentedCapture::class)
    ->name('captures.segmented')
    ->layout(ShowcaseLayout::class);

Route::native('/status-label', StatusLabelShowcase::class)
    ->name('status-label')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/status-label', StatusLabelCapture::class)
    ->name('captures.status-label')
    ->layout(ShowcaseLayout::class);
