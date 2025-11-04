<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/hello', function() {
//     return "Hello World";
// });

// Route::get('/world', function () {
// return 'World';
// });

// Route::get('/about', function() {
//     return "NIM = 23.51.0018, Nama = Akbar ATMA Riyahtha";
// }); 

// Route::get('/user/{name}', function ($name) {
//     return 'Nama saya '.$name;
// });

// Route::get('/posts/{post}/comments/{comment}', function
// ($postId, $commentId) {
// return 'Pos ke-'.$postId." Komentar ke-: ".$commentId;
// }); 

// Route::get('/user/{name?}', function ($name=null) {
//     return 'Nama saya '.$name;
// });

// Route::get('/user/{name?}', function ($name='Ariel') {
// return 'Nama saya '.$name;
// });

// Route::view('/Kontak', 'kontak');