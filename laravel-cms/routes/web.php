<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AboutUsContentController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EditorUploadController;
use App\Http\Controllers\Admin\HomeContentController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProjectBlogController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectDetailPageController;
use App\Http\Controllers\Admin\ProjectVideoController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\BlogController as SiteBlogController;
use App\Http\Controllers\VideoController as SiteVideoController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::middleware('auth')->prefix('admin')->as('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('home-content', [HomeContentController::class, 'index'])->name('home-content.index');
    Route::put('home-content', [HomeContentController::class, 'update'])->name('home-content.update');
    Route::post('home-content/auto-source/{detailPage}/visibility', [HomeContentController::class, 'updateAutoSourceVisibility'])
        ->name('home-content.auto-source.visibility');
    Route::resource('pages', PageController::class)->except(['show']);
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('about-us-content', [AboutUsContentController::class, 'edit'])->name('about-content.edit');
    Route::put('about-us-content', [AboutUsContentController::class, 'update'])->name('about-content.update');
    Route::resource('menu-items', MenuItemController::class)->except(['show']);
    Route::resource('blogs', BlogController::class)->except(['show']);
    Route::resource('videos', VideoController::class)->except(['show']);
    Route::post('editor/upload-image', [EditorUploadController::class, 'store'])->name('editor.upload-image');
    Route::resource('projects', ProjectController::class)->except(['show']);
    Route::resource('projects.detail-pages', ProjectDetailPageController::class)
        ->parameters(['detail-pages' => 'detailPage'])
        ->except(['index', 'show']);
    Route::resource('projects.blogs', ProjectBlogController::class)->except(['index', 'show']);
    Route::resource('projects.videos', ProjectVideoController::class)->except(['index', 'show']);

    Route::get('contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::delete('contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
});

Route::post('/contact-submit', [SiteController::class, 'submitContact'])->name('site.contact.submit');
Route::get('/blog', [SiteBlogController::class, 'index'])->name('site.blog.index');
Route::get('/blog/{slug}', [SiteBlogController::class, 'show'])->name('site.blog.show');
Route::get('/video', [SiteVideoController::class, 'index'])->name('site.video.index');
Route::get('/video/{slug}', [SiteVideoController::class, 'show'])->name('site.video.show');
Route::get('/{slug?}', [SiteController::class, 'show'])->where('slug', '.*')->name('site.page');
