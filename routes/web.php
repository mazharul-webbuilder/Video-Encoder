<?php

use App\Http\Controllers\VideoEncodeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\EncoderController;
use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\DashboardController;
use App\Http\Controllers\web\AdminProfileController;


/**
 * Web Encoder Routes
 * =====================================================================================================
 */
// Auth Routes
Route::middleware('guest')->prefix('admin/')->as('admin.')->group(function (){
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'authCheck'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout')->withoutMiddleware('guest')->middleware('auth');
});

Route::middleware('auth')->group(function (){
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('admin/admin/update', [AdminProfileController::class, 'editProfile'])->name('admin.edit');
    Route::post('admin/admin/update', [AdminProfileController::class, 'updateProfile'])->name('admin.profile.update');
    // Encoder UI
    Route::get('/fetch-videos', [EncoderController::class, 'fetchVideos'])->name('fetch.videos');
    Route::get('/encoded-videos', [EncoderController::class, 'encodedVideos'])->name('encoded.videos');
    Route::get('/fetch-encoding-videos', [EncoderController::class, 'getAllEncodingVideos'])->name('fetch.encoding.videos'); // Ajax
    Route::get('/fetch-all-videos', [EncoderController::class, 'fetchAllVideos'])->name('fetch.all.videos');
    Route::get('/encoding-list', [EncoderController::class, 'encodingList'])->name('encoding.list');
    Route::get('/get-encoding-list', [EncoderController::class, 'getEncodingList'])->name('encoding.video.list');
    // Soft Delete
    Route::post('/soft-delete-video/{id}', [EncoderController::class, 'softDelete']);
    // Force Delete
    Route::post('/force-delete/{id}', [EncoderController::class, 'forceDelete']);
    // Restore Video
    Route::post('/restore-soft-deleted-video/{id}', [EncoderController::class, 'restore']);
    // Retry Failed Video
    Route::post('/retry-failed-video/{id}', [VideoEncodeController::class, 'retryFailedVideo']); // AJAX
    // Encode Video
    Route::post('/encode-video', [VideoEncodeController::class, 'encodeVideo'])->name('encoding.video');
});


// Unused routes
Route::post('/start-video-encoding', [VideoEncodeController::class, 'startVideoEncoding'])->name('singleVideo.encoding');
Route::post('/start-bulk-video-encoding', [VideoEncodeController::class, 'startBulkVideoEncoding'])->name('bulkVideo.encoding');
Route::get('/fetch-encode-video-progress-status/{id}', [VideoEncodeController::class, 'encodingVideoStatus']);

/**
 * =====================================================================================================
*/



Route::group(['prefix' => 'email'], function(){
    Route::get('inbox', function () { return view('pages.email.inbox'); });
    Route::get('read', function () { return view('pages.email.read'); });
    Route::get('compose', function () { return view('pages.email.compose'); });
});

Route::group(['prefix' => 'apps'], function(){
    Route::get('chat', function () { return view('pages.apps.chat'); });
    Route::get('calendar', function () { return view('pages.apps.calendar'); });
});

Route::group(['prefix' => 'ui-components'], function(){
    Route::get('accordion', function () { return view('pages.ui-components.accordion'); });
    Route::get('alerts', function () { return view('pages.ui-components.alerts'); });
    Route::get('badges', function () { return view('pages.ui-components.badges'); });
    Route::get('breadcrumbs', function () { return view('pages.ui-components.breadcrumbs'); });
    Route::get('buttons', function () { return view('pages.ui-components.buttons'); });
    Route::get('button-group', function () { return view('pages.ui-components.button-group'); });
    Route::get('cards', function () { return view('pages.ui-components.cards'); });
    Route::get('carousel', function () { return view('pages.ui-components.carousel'); });
    Route::get('collapse', function () { return view('pages.ui-components.collapse'); });
    Route::get('dropdowns', function () { return view('pages.ui-components.dropdowns'); });
    Route::get('list-group', function () { return view('pages.ui-components.list-group'); });
    Route::get('media-object', function () { return view('pages.ui-components.media-object'); });
    Route::get('modal', function () { return view('pages.ui-components.modal'); });
    Route::get('navs', function () { return view('pages.ui-components.navs'); });
    Route::get('navbar', function () { return view('pages.ui-components.navbar'); });
    Route::get('pagination', function () { return view('pages.ui-components.pagination'); });
    Route::get('popovers', function () { return view('pages.ui-components.popovers'); });
    Route::get('progress', function () { return view('pages.ui-components.progress'); });
    Route::get('scrollbar', function () { return view('pages.ui-components.scrollbar'); });
    Route::get('scrollspy', function () { return view('pages.ui-components.scrollspy'); });
    Route::get('spinners', function () { return view('pages.ui-components.spinners'); });
    Route::get('tabs', function () { return view('pages.ui-components.tabs'); });
    Route::get('tooltips', function () { return view('pages.ui-components.tooltips'); });
});

Route::group(['prefix' => 'advanced-ui'], function(){
    Route::get('cropper', function () { return view('pages.advanced-ui.cropper'); });
    Route::get('owl-carousel', function () { return view('pages.advanced-ui.owl-carousel'); });
    Route::get('sortablejs', function () { return view('pages.advanced-ui.sortablejs'); });
    Route::get('sweet-alert', function () { return view('pages.advanced-ui.sweet-alert'); });
});

Route::group(['prefix' => 'forms'], function(){
    Route::get('basic-elements', function () { return view('pages.forms.basic-elements'); });
    Route::get('advanced-elements', function () { return view('pages.forms.advanced-elements'); });
    Route::get('editors', function () { return view('pages.forms.editors'); });
    Route::get('wizard', function () { return view('pages.forms.wizard'); });
});

Route::group(['prefix' => 'charts'], function(){
    Route::get('apex', function () { return view('pages.charts.apex'); });
    Route::get('chartjs', function () { return view('pages.charts.chartjs'); });
    Route::get('flot', function () { return view('pages.charts.flot'); });
    Route::get('peity', function () { return view('pages.charts.peity'); });
    Route::get('sparkline', function () { return view('pages.charts.sparkline'); });
});

Route::group(['prefix' => 'tables'], function(){
    Route::get('basic-tables', function () { return view('pages.tables.basic-tables'); });
    Route::get('data-table', function () { return view('pages.tables.data-table'); });
});

Route::group(['prefix' => 'icons'], function(){
    Route::get('feather-icons', function () { return view('pages.icons.feather-icons'); });
    Route::get('mdi-icons', function () { return view('pages.icons.mdi-icons'); });
});

Route::group(['prefix' => 'general'], function(){
    Route::get('blank-page', function () { return view('pages.general.blank-page'); });
    Route::get('faq', function () { return view('pages.general.faq'); });
    Route::get('invoice', function () { return view('pages.general.invoice'); });
    Route::get('admin', function () { return view('pages.general.admin'); });
    Route::get('pricing', function () { return view('pages.general.pricing'); });
    Route::get('timeline', function () { return view('pages.general.timeline'); });
});

Route::group(['prefix' => 'auth'], function(){
    Route::get('login', function () { return view('pages.auth.login'); });
    Route::get('register', function () { return view('pages.auth.register'); });
});

Route::group(['prefix' => 'error'], function(){
    Route::get('404', function () { return view('pages.error.404'); });
    Route::get('500', function () { return view('pages.error.500'); });
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// 404 for undefined routes
Route::any('/{page?}',function(){
    return View::make('pages.error.404');
})->where('page','.*');
