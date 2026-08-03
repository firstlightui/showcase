<?php

use App\NativeComponents\Layouts\ShowcaseLayout;
use App\NativeComponents\SegmentedShowcase;
use Illuminate\Support\Facades\Route;

Route::native('/segmented', SegmentedShowcase::class)
    ->name('segmented')
    ->layout(ShowcaseLayout::class);
