<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatController;

Route::post('/chat/send', [ChatController::class, 'send']);
Route::get('/chat/poll', [ChatController::class, 'poll']);
Route::get('/', function () {
    return view('welcome');
});

Route::get('/demo', function () {
    return view('demo');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'admin'])->name('dashboard');

// ✅ Profile routes (أي user مسجل)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🔐 Admin routes (غير admin)
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard (اختياري ولكن pro)
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Tickets
        Route::get('/tickets', [\App\Http\Controllers\Admin\TicketController::class, 'index'])
            ->name('tickets.index');

        Route::get('/tickets/{ticket}', [\App\Http\Controllers\Admin\TicketController::class, 'show'])
            ->name('tickets.show');

        Route::post('/tickets/{ticket}/reply', [\App\Http\Controllers\Admin\TicketController::class, 'reply'])
            ->name('tickets.reply');

        Route::post('/tickets/{ticket}/status', [\App\Http\Controllers\Admin\TicketController::class, 'updateStatus'])
            ->name('tickets.status');

        // Conversations (support inbox)
        Route::get('/conversations', [\App\Http\Controllers\Admin\ConversationController::class, 'index'])
            ->name('conversations.index');

        Route::get('/conversations/{conversation}', [\App\Http\Controllers\Admin\ConversationController::class, 'show'])
            ->name('conversations.show');

        Route::post('/conversations/{conversation}/reply', [\App\Http\Controllers\Admin\ConversationController::class, 'reply'])
            ->name('conversations.reply');

        // Sites (multi-site)
        Route::get('/sites', [\App\Http\Controllers\Admin\SiteController::class, 'index'])
            ->name('sites.index');

        Route::post('/sites', [\App\Http\Controllers\Admin\SiteController::class, 'store'])
            ->name('sites.store');
    });

require __DIR__.'/auth.php';
