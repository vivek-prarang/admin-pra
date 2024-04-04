<?php

use App\Http\Controllers\DBFiles;
use App\Http\Controllers\Portal;
use Illuminate\Support\Facades\Route;






Route::prefix('portal')->group(function () {
    Route::get('/',[Portal::class,'index']);

});

Route::post('/upload',[DBFiles::class,'index'])->name('image.upload');
Route::get('/images/{imageId}',[DBFiles::class,'loadImages']);
