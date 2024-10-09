<?php

use App\Http\Controllers\InfoController;
use App\Http\Controllers\ProjectsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProjectsController::class, 'index'])->name('projects.index');

Route::get('/project/{project}', [ProjectsController::class, 'show'])->name('projects.show');

Route::get('/test',  function () {
    return view('test');
});
