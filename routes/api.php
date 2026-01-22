<?php

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('task-lists')->group(function () {
    Route::get('/', [TaskListController::class, 'index'])->name('api.task-lists.index');
    Route::post('/', [TaskListController::class, 'store'])->name('api.task-lists.store');
    Route::get('/{id}', [TaskListController::class, 'show'])->name('api.task-lists.show');
    Route::put('/{id}', [TaskListController::class, 'update'])->name('api.task-lists.update');
    Route::delete('/{id}', [TaskListController::class, 'destroy'])->name('api.task-lists.destroy');
});

Route::prefix('tasks')->group(function () {
    Route::get('/list/{taskListID}', [TaskController::class, 'index'])->name('api.tasks.index');
    Route::post('/', [TaskController::class, 'store'])->name('api.tasks.store');
    Route::get('/{id}', [TaskController::class, 'show'])->name('api.tasks.show');
    Route::put('/{id}', [TaskController::class, 'update'])->name('api.tasks.update');
    Route::delete('/{id}', [TaskController::class, 'destroy'])->name('api.tasks.destroy');

});