
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamController;

Route::get('/', [ExamController::class, 'index'])->name('home');

Route::post('/generate', [ExamController::class, 'generate'])->name('question.generate');
