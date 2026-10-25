<?php

use App\NativeComponents\ActivityIndicatorShowcase;
use App\NativeComponents\AlertDialogShowcase;
use App\NativeComponents\BadgeShowcase;
use App\NativeComponents\BottomSheetShowcase;
use App\NativeComponents\ButtonShowcase;
use App\NativeComponents\CalloutShowcase;
use App\NativeComponents\Captures\ActivityIndicatorCapture;
use App\NativeComponents\Captures\AlertDialogCapture;
use App\NativeComponents\Captures\BadgeCapture;
use App\NativeComponents\Captures\BottomSheetCapture;
use App\NativeComponents\Captures\ButtonCapture;
use App\NativeComponents\Captures\CalloutCapture;
use App\NativeComponents\Captures\CheckboxCapture;
use App\NativeComponents\Captures\ChoiceGroupCapture;
use App\NativeComponents\Captures\CodeEditorCapture;
use App\NativeComponents\Captures\ConfirmationDialogCapture;
use App\NativeComponents\Captures\DatePickerCapture;
use App\NativeComponents\Captures\FormErrorsCapture;
use App\NativeComponents\Captures\IconButtonCapture;
use App\NativeComponents\Captures\ListCapture;
use App\NativeComponents\Captures\ListItemCapture;
use App\NativeComponents\Captures\ListItemContextMenuCapture;
use App\NativeComponents\Captures\ListSectionCapture;
use App\NativeComponents\Captures\MediaCapture;
use App\NativeComponents\Captures\MetricCapture;
use App\NativeComponents\Captures\ModalCapture;
use App\NativeComponents\Captures\PillGroupCapture;
use App\NativeComponents\Captures\ProgressCapture;
use App\NativeComponents\Captures\SearchFieldCapture;
use App\NativeComponents\Captures\SegmentedCapture;
use App\NativeComponents\Captures\SelectCapture;
use App\NativeComponents\Captures\SliderCapture;
use App\NativeComponents\Captures\StatusLabelCapture;
use App\NativeComponents\Captures\StepperCapture;
use App\NativeComponents\Captures\SwitchCapture;
use App\NativeComponents\Captures\TabsCapture;
use App\NativeComponents\Captures\TextAreaCapture;
use App\NativeComponents\Captures\TextFieldCapture;
use App\NativeComponents\Captures\TimePickerCapture;
use App\NativeComponents\Captures\TransientFeedbackCapture;
use App\NativeComponents\Captures\ValidateFieldsCapture;
use App\NativeComponents\CheckboxShowcase;
use App\NativeComponents\ChoiceGroupShowcase;
use App\NativeComponents\CodeEditorShowcase;
use App\NativeComponents\ConfirmationDialogShowcase;
use App\NativeComponents\CreatePostScreen;
use App\NativeComponents\DatePickerShowcase;
use App\NativeComponents\DocumentationArticleScreen;
use App\NativeComponents\DocumentationScreen;
use App\NativeComponents\GalleryInboxScreen;
use App\NativeComponents\GalleryScreen;
use App\NativeComponents\IconButtonShowcase;
use App\NativeComponents\Layouts\AppLayout;
use App\NativeComponents\Layouts\ShowcaseLayout;
use App\NativeComponents\ListItemShowcase;
use App\NativeComponents\ListShowcase;
use App\NativeComponents\MediaShowcase;
use App\NativeComponents\MetricShowcase;
use App\NativeComponents\ModalShowcase;
use App\NativeComponents\PillGroupShowcase;
use App\NativeComponents\PostScreen;
use App\NativeComponents\PostCoverScreen;
use App\NativeComponents\PostsScreen;
use App\NativeComponents\ContactScreen;
use App\NativeComponents\ContactsScreen;
use App\NativeComponents\PrivacyScreen;
use App\NativeComponents\ProgressShowcase;
use App\NativeComponents\RecipesScreen;
use App\NativeComponents\SearchFieldShowcase;
use App\NativeComponents\SegmentedShowcase;
use App\NativeComponents\SelectShowcase;
use App\NativeComponents\SettingsScreen;
use App\NativeComponents\ShowcaseHome;
use App\NativeComponents\SliderShowcase;
use App\NativeComponents\StatusLabelShowcase;
use App\NativeComponents\StepperShowcase;
use App\NativeComponents\SwitchShowcase;
use App\NativeComponents\TabsShowcase;
use App\NativeComponents\TextAreaShowcase;
use App\NativeComponents\TextFieldShowcase;
use App\NativeComponents\TimePickerShowcase;
use App\NativeComponents\TransientFeedbackDestination;
use App\NativeComponents\TransientFeedbackShowcase;
use App\NativeComponents\ValidationShowcase;
use Illuminate\Support\Facades\Route;

// Tab roots. Gallery owns '/', so it must stay the first tab (see ShowcaseLayout).
Route::native('/', GalleryScreen::class)
    ->name('showcase')
    ->layout(ShowcaseLayout::class);

Route::native('/gallery/inbox', GalleryInboxScreen::class)
    ->name('gallery.inbox')
    ->layout(ShowcaseLayout::class);

Route::native('/recipes', RecipesScreen::class)
    ->name('recipes')
    ->layout(ShowcaseLayout::class);

Route::native('/components', ShowcaseHome::class)
    ->name('components')
    ->layout(ShowcaseLayout::class);

Route::native('/docs', DocumentationScreen::class)->name('docs')->layout(ShowcaseLayout::class);
Route::native('/docs/{group}/{slug}', DocumentationArticleScreen::class)->name('docs.article')->layout(ShowcaseLayout::class);

// Component demos live under /components so they push inside that tab.

Route::native('/components/activity-indicator', ActivityIndicatorShowcase::class)
    ->name('activity-indicator')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/activity-indicator', ActivityIndicatorCapture::class)
    ->name('captures.activity-indicator')
    ->layout(ShowcaseLayout::class);

Route::native('/components/button', ButtonShowcase::class)
    ->name('button')
    ->layout(ShowcaseLayout::class);

Route::native('/components/badge', BadgeShowcase::class)
    ->name('badge')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/badge', BadgeCapture::class)
    ->name('captures.badge')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/button', ButtonCapture::class)
    ->name('captures.button')
    ->layout(ShowcaseLayout::class);

Route::native('/components/callout', CalloutShowcase::class)
    ->name('callout')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/callout', CalloutCapture::class)
    ->name('captures.callout')
    ->layout(ShowcaseLayout::class);

Route::native('/components/transient-feedback', TransientFeedbackShowcase::class)
    ->name('transient-feedback')
    ->layout(ShowcaseLayout::class);

Route::native('/components/transient-feedback/destination', TransientFeedbackDestination::class)
    ->name('transient-feedback.destination')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/transient-feedback', TransientFeedbackCapture::class)
    ->name('captures.transient-feedback')
    ->layout(ShowcaseLayout::class);

Route::native('/components/checkbox', CheckboxShowcase::class)
    ->name('checkbox')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/checkbox', CheckboxCapture::class)
    ->name('captures.checkbox')
    ->layout(ShowcaseLayout::class);

Route::native('/components/media', MediaShowcase::class)
    ->name('media')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/media', MediaCapture::class)
    ->name('captures.media')
    ->layout(ShowcaseLayout::class);

Route::native('/components/choice-group', ChoiceGroupShowcase::class)
    ->name('choice-group')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/choice-group', ChoiceGroupCapture::class)
    ->name('captures.choice-group')
    ->layout(ShowcaseLayout::class);

Route::native('/components/alert-dialog', AlertDialogShowcase::class)
    ->name('alert-dialog')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/alert-dialog', AlertDialogCapture::class)
    ->name('captures.alert-dialog')
    ->layout(ShowcaseLayout::class);

Route::native('/components/confirmation-dialog', ConfirmationDialogShowcase::class)
    ->name('confirmation-dialog')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/confirmation-dialog', ConfirmationDialogCapture::class)
    ->name('captures.confirmation-dialog')
    ->layout(ShowcaseLayout::class);

Route::native('/components/modal', ModalShowcase::class)
    ->name('modal')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/modal', ModalCapture::class)
    ->name('captures.modal')
    ->layout(ShowcaseLayout::class);

Route::native('/components/bottom-sheet', BottomSheetShowcase::class)
    ->name('bottom-sheet')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/bottom-sheet', BottomSheetCapture::class)
    ->name('captures.bottom-sheet')
    ->layout(ShowcaseLayout::class);

Route::native('/components/date-picker', DatePickerShowcase::class)
    ->name('date-picker')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/date-picker', DatePickerCapture::class)
    ->name('captures.date-picker')
    ->layout(ShowcaseLayout::class);

Route::native('/components/time-picker', TimePickerShowcase::class)
    ->name('time-picker')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/time-picker', TimePickerCapture::class)
    ->name('captures.time-picker')
    ->layout(ShowcaseLayout::class);

Route::native('/components/icon-button', IconButtonShowcase::class)
    ->name('icon-button')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/icon-button', IconButtonCapture::class)
    ->name('captures.icon-button')
    ->layout(ShowcaseLayout::class);

Route::native('/components/list-item', ListItemShowcase::class)
    ->name('list-item')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/list-item', ListItemCapture::class)
    ->name('captures.list-item')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/list-item-context-menu', ListItemContextMenuCapture::class)
    ->name('captures.list-item-context-menu')
    ->layout(ShowcaseLayout::class);

Route::native('/components/list', ListShowcase::class)
    ->name('list')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/list', ListCapture::class)
    ->name('captures.list')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/list-section', ListSectionCapture::class)
    ->name('captures.list-section')
    ->layout(ShowcaseLayout::class);

Route::native('/components/pill-group', PillGroupShowcase::class)
    ->name('pill-group')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/pill-group', PillGroupCapture::class)
    ->name('captures.pill-group')
    ->layout(ShowcaseLayout::class);

Route::native('/components/progress', ProgressShowcase::class)
    ->name('progress')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/progress', ProgressCapture::class)
    ->name('captures.progress')
    ->layout(ShowcaseLayout::class);

Route::native('/components/segmented', SegmentedShowcase::class)
    ->name('segmented')
    ->layout(ShowcaseLayout::class);

Route::native('/components/search-field', SearchFieldShowcase::class)
    ->name('search-field')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/search-field', SearchFieldCapture::class)
    ->name('captures.search-field')
    ->layout(ShowcaseLayout::class);

Route::native('/components/select', SelectShowcase::class)
    ->name('select')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/select', SelectCapture::class)
    ->name('captures.select')
    ->layout(ShowcaseLayout::class);

Route::native('/components/slider', SliderShowcase::class)
    ->name('slider')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/slider', SliderCapture::class)
    ->name('captures.slider')
    ->layout(ShowcaseLayout::class);

Route::native('/components/stepper', StepperShowcase::class)
    ->name('stepper')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/stepper', StepperCapture::class)
    ->name('captures.stepper')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/segmented', SegmentedCapture::class)
    ->name('captures.segmented')
    ->layout(ShowcaseLayout::class);

Route::native('/components/tabs', TabsShowcase::class)
    ->name('tabs')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/tabs', TabsCapture::class)
    ->name('captures.tabs')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/switch', SwitchCapture::class)
    ->name('captures.switch')
    ->layout(ShowcaseLayout::class);

Route::native('/components/status-label', StatusLabelShowcase::class)
    ->name('status-label')
    ->layout(ShowcaseLayout::class);

Route::native('/components/switch', SwitchShowcase::class)
    ->name('switch')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/status-label', StatusLabelCapture::class)
    ->name('captures.status-label')
    ->layout(ShowcaseLayout::class);

Route::native('/components/metric', MetricShowcase::class)
    ->name('metric')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/metric', MetricCapture::class)
    ->name('captures.metric')
    ->layout(ShowcaseLayout::class);

Route::native('/components/text-field', TextFieldShowcase::class)
    ->name('text-field')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/text-field', TextFieldCapture::class)
    ->name('captures.text-field')
    ->layout(ShowcaseLayout::class);

Route::native('/components/text-area', TextAreaShowcase::class)
    ->name('text-area')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/text-area', TextAreaCapture::class)
    ->name('captures.text-area')
    ->layout(ShowcaseLayout::class);

Route::native('/components/code-editor', CodeEditorShowcase::class)
    ->name('code-editor')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/code-editor', CodeEditorCapture::class)
    ->name('captures.code-editor')
    ->layout(ShowcaseLayout::class);

Route::native('/components/validation', ValidationShowcase::class)
    ->name('validation')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/validate-fields', ValidateFieldsCapture::class)
    ->name('captures.validate-fields')
    ->layout(ShowcaseLayout::class);

Route::native('/captures/form-errors', FormErrorsCapture::class)
    ->name('captures.form-errors')
    ->layout(ShowcaseLayout::class);

// Recipe screens: copied verbatim from docs/how-to recipes so tests prove them.
Route::native('/posts', PostsScreen::class)
    ->name('recipes.posts')
    ->layout(AppLayout::class);

Route::native('/posts/create', CreatePostScreen::class)
    ->name('recipes.posts.create')
    ->layout(AppLayout::class);

Route::native('/posts/{post}/cover', PostCoverScreen::class)
    ->name('recipes.post.cover')
    ->layout(AppLayout::class);

Route::native('/posts/{post}', PostScreen::class)
    ->name('recipes.post')
    ->layout(AppLayout::class);

Route::native('/contacts', ContactsScreen::class)
    ->name('recipes.contacts')
    ->layout(AppLayout::class);

Route::native('/contacts/{contact}', ContactScreen::class)
    ->name('recipes.contact')
    ->layout(AppLayout::class);

Route::native('/settings', SettingsScreen::class)
    ->name('recipes.settings')
    ->layout(AppLayout::class);

Route::native('/settings/privacy', PrivacyScreen::class)
    ->name('recipes.privacy')
    ->layout(AppLayout::class);
