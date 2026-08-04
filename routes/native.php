<?php

use App\NativeComponents\Layouts\ShowcaseLayout;
use App\NativeComponents\SegmentedShowcase;
use App\NativeComponents\Captures\SegmentedCapture;
use Illuminate\Support\Facades\Route;

Route::native('/segmented', SegmentedShowcase::class)
    ->name('segmented')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/segmented', SegmentedCapture::class)
    ->name('captures.segmented')
    ->layout(ShowcaseLayout::class);
