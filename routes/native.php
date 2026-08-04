<?php

use App\NativeComponents\ButtonShowcase;
use App\NativeComponents\BadgeShowcase;
use App\NativeComponents\Captures\BadgeCapture;
use App\NativeComponents\Captures\ButtonCapture;
use App\NativeComponents\Captures\IconButtonCapture;
use App\NativeComponents\Captures\PillGroupCapture;
use App\NativeComponents\Captures\ProgressCapture;
use App\NativeComponents\Captures\SegmentedCapture;
use App\NativeComponents\Captures\StatusLabelCapture;
use App\NativeComponents\Captures\SwitchCapture;
use App\NativeComponents\Captures\TextFieldCapture;
use App\NativeComponents\Layouts\ShowcaseLayout;
use App\NativeComponents\IconButtonShowcase;
use App\NativeComponents\PillGroupShowcase;
use App\NativeComponents\ProgressShowcase;
use App\NativeComponents\SegmentedShowcase;
use App\NativeComponents\ShowcaseHome;
use App\NativeComponents\StatusLabelShowcase;
use App\NativeComponents\SwitchShowcase;
use App\NativeComponents\TextFieldShowcase;
use Illuminate\Support\Facades\Route;

Route::native('/', ShowcaseHome::class)
    ->name('showcase')
    ->layout(ShowcaseLayout::class);

Route::native('/button', ButtonShowcase::class)
    ->name('button')
    ->layout(ShowcaseLayout::class);

Route::native('/badge', BadgeShowcase::class)
    ->name('badge')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/badge', BadgeCapture::class)
    ->name('captures.badge')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/button', ButtonCapture::class)
    ->name('captures.button')
    ->layout(ShowcaseLayout::class);

Route::native('/icon-button', IconButtonShowcase::class)
    ->name('icon-button')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/icon-button', IconButtonCapture::class)
    ->name('captures.icon-button')
    ->layout(ShowcaseLayout::class);

Route::native('/pill-group', PillGroupShowcase::class)
    ->name('pill-group')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/pill-group', PillGroupCapture::class)
    ->name('captures.pill-group')
    ->layout(ShowcaseLayout::class);

Route::native('/progress', ProgressShowcase::class)
    ->name('progress')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/progress', ProgressCapture::class)
    ->name('captures.progress')
    ->layout(ShowcaseLayout::class);

Route::native('/segmented', SegmentedShowcase::class)
    ->name('segmented')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/segmented', SegmentedCapture::class)
    ->name('captures.segmented')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/switch', SwitchCapture::class)
    ->name('captures.switch')
    ->layout(ShowcaseLayout::class);

Route::native('/status-label', StatusLabelShowcase::class)
    ->name('status-label')
    ->layout(ShowcaseLayout::class);

Route::native('/switch', SwitchShowcase::class)
    ->name('switch')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/status-label', StatusLabelCapture::class)
    ->name('captures.status-label')
    ->layout(ShowcaseLayout::class);

Route::native('/text-field', TextFieldShowcase::class)
    ->name('text-field')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/text-field', TextFieldCapture::class)
    ->name('captures.text-field')
    ->layout(ShowcaseLayout::class);
