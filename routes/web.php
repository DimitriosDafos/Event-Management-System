<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\BarShiftController;
use App\Http\Controllers\DoorController;
use App\Http\Controllers\DjController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\BrandingController;
use App\Http\Controllers\NewsletterController;

// Öffentliche Seite (kein Login)
Route::get('/', [PublicController::class, 'index'])->name('public.index');
Route::get('/party/{id}', [PublicController::class, 'party'])->name('public.party');
Route::get('/newsletter',  [NewsletterController::class, 'show'])->name('newsletter.show');
Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Intern (Login erforderlich)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Party-Liste
    Route::get('/partys', [PartyController::class, 'index'])->name('parties.index');

    // Spezifische Routes VOR dem Wildcard {id} registrieren
    Route::get('/partys/neu', [PartyController::class, 'create'])->middleware('admin')->name('parties.create');
    Route::post('/partys',    [PartyController::class, 'store'])->middleware('admin')->name('parties.store');

    // Party-Detail (Wildcard nach den spezifischen Routes)
    Route::get('/partys/{id}', [PartyController::class, 'show'])->name('parties.show');

    // Party bearbeiten
    Route::get('/partys/{id}/bearbeiten', [PartyController::class, 'edit'])->middleware('admin')->name('parties.edit');
    Route::patch('/partys/{id}',          [PartyController::class, 'update'])->middleware('admin')->name('parties.update');
    Route::patch('/partys/{id}/status',   [PartyController::class, 'updateStatus'])->middleware('admin')->name('parties.status');

    // Bar-Dienstplan + Einlass (alle Mitglieder)
    Route::post('/partys/{id}/bar',              [BarShiftController::class, 'store'])->name('bar.store');
    Route::delete('/partys/{id}/bar/{shiftId}',  [BarShiftController::class, 'destroy'])->name('bar.destroy');
    Route::post('/partys/{id}/einlass',          [DoorController::class, 'store'])->name('door.store');

    // ToDos
    Route::post('/partys/{id}/todos',                  [TodoController::class, 'store'])->name('todos.store');
    Route::patch('/partys/{id}/todos/{todoId}/done',   [TodoController::class, 'markDone'])->name('todos.done');
    Route::patch('/partys/{id}/todos/{todoId}/kosten', [TodoController::class, 'updateCosts'])->name('todos.costs');
    Route::patch('/partys/{id}/todos/{todoId}',        [TodoController::class, 'adminUpdate'])->middleware('admin')->name('todos.admin_update');
    Route::delete('/partys/{id}/todos/{todoId}',       [TodoController::class, 'destroy'])->middleware('admin')->name('todos.destroy');

    // DJ + Marketing + Admin (DJ-Lineup)
    Route::middleware('dj')->group(function () {
        Route::post('/partys/{id}/dj',           [DjController::class, 'store'])->name('dj.store');
        Route::delete('/partys/{id}/dj/{djId}',  [DjController::class, 'destroy'])->name('dj.destroy');
    });

    // Marketing + Admin
    Route::middleware('marketing')->group(function () {
        Route::patch('/partys/{id}/beschreibung', [PartyController::class, 'updateDescription'])->name('parties.description');
        Route::post('/partys/{id}/flyer',         [PartyController::class, 'uploadFlyer'])->name('parties.flyer');
    });

    // Admin: Einlass löschen + Einnahmen
    Route::middleware('admin')->group(function () {
        Route::delete('/partys/{id}/einlass/{dId}',   [DoorController::class, 'destroy'])->name('door.destroy');
        Route::post('/partys/{id}/einnahmen',         [FinanceController::class, 'store'])->name('income.store');
        Route::delete('/partys/{id}/einnahmen/{iId}', [FinanceController::class, 'destroy'])->name('income.destroy');
    });

    // Admin-Bereich
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/',                              [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/mitglieder',                    [AdminController::class, 'users'])->name('users');
        Route::get('/mitglieder/neu',                [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/mitglieder',                   [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/mitglieder/{id}/bearbeiten',    [AdminController::class, 'editUser'])->name('users.edit');
        Route::patch('/mitglieder/{id}',             [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/mitglieder/{id}',            [AdminController::class, 'deleteUser'])->name('users.delete');
    });

    // Newsletter (Admin)
    Route::middleware('admin')->group(function () {
        Route::get('/newsletter/abonnenten',        [NewsletterController::class, 'adminIndex'])->name('newsletter.admin');
        Route::get('/newsletter/export',            [NewsletterController::class, 'export'])->name('newsletter.export');
        Route::patch('/newsletter/{id}/toggle',     [NewsletterController::class, 'toggleActive'])->name('newsletter.toggle');
        Route::delete('/newsletter/{id}',           [NewsletterController::class, 'adminDestroy'])->name('newsletter.destroy');
    });

    // Branding (Admin)
    Route::middleware('admin')->group(function () {
        Route::get('/branding',  [BrandingController::class, 'index'])->name('branding.index');
        Route::patch('/branding', [BrandingController::class, 'update'])->name('branding.update');
    });

    // Ankündigungen (Admin)
    Route::middleware('admin')->group(function () {
        Route::get('/ankuendigungen',          [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/ankuendigungen',         [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::patch('/ankuendigungen/{id}',  [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/ankuendigungen/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    });
});
