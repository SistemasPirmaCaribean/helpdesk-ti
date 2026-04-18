<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Auth\UsuarioController;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

use App\Http\Controllers\UsuarioPerfilController;
///////////////////////////////////////////////////////////
////// Define Gates for role-based access control /////////
///////////////////////////////////////////////////////////

Gate::define('tickets.catalogo', fn($user) => $user->idperfil == 1);
Gate::define('tickets', fn($user) => in_array($user->idperfil, [1, 2, 3, 4, 5]));


//////////////////////////////////////
////// Rutes web free access /////////
//////////////////////////////////////

Route::get('/', function () {

    if (Auth::check()) {
        return redirect()->route('tickets.create');
    }

    return Inertia::render('auth/Login');

})->name('home');


///////////////////////////////////////////
////// Rutes web with authentication /////////
///////////////////////////////////////////

Route::get('dashboard', fn() => Inertia::render('Dashboard'))
    ->middleware('auth:sanctum')
    ->name('dashboard');

Route::get('usuarios/profile', fn() => Inertia::render('User'))
    ->middleware('auth:sanctum')
    ->name('usuarios');

///////////////////////////////////////////
////// Rutes web with authentication tickets /////////
///////////////////////////////////////////

Route::get('tickets/catalogo/departamentos', fn() => Inertia::render('tickets/catalogo/Departament'))
    ->middleware('auth:sanctum')
    ->can('tickets.catalogo')
    ->name('tickets.catalogo.departamentos');

Route::get('tickets/catalogo/categorias', fn() => Inertia::render('tickets/catalogo/Category'))
    ->middleware('auth:sanctum')
    ->can('tickets.catalogo')
    ->name('tickets.catalogo.categorias');

Route::get('tickets/catalogo/prioridades', fn() => Inertia::render('tickets/catalogo/Priority'))
    ->middleware('auth:sanctum')
    ->can('tickets.catalogo')
    ->name('tickets.catalogo.prioridades');

Route::get('tickets/catalogo/estados', fn() => Inertia::render('tickets/catalogo/Status'))
    ->middleware('auth:sanctum')
    ->can('tickets.catalogo')
    ->name('tickets.catalogo.estados');

Route::get('tickets', fn() => Inertia::render('tickets/ticket/Ticket'))
    ->middleware('auth:sanctum')
    ->can('tickets')
    ->name('tickets.create');


//////////////////////////////////////////
////// Rutes login web /////////
//////////////////////////////////////////
Route::post('/web/login', function (Request $request) {

    $request->validate([
        'login' => 'required|string|max:255',
        'password' => 'required|string|max:255',
    ]);

    $credentials = [
        'usuario' => $request->login,
        'password' => $request->password
    ];

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Credenciales inválidas'], 401, [], JSON_UNESCAPED_UNICODE);
    }

    $request->session()->regenerate();

    $user = Auth::user();

    if ($user->idperfil == 1) {
        $abilities = ['*'];
    } elseif ($user->idperfil == 2) {
        $abilities = ['dashboard', 'usuarios'];
    } else {
        $abilities = [];
    }

    $token = $user->createToken(
        'auth_token',
        $abilities,
        now()->addHours(8)
    )->plainTextToken;

    return response()->json([
        'message' => 'Bienvenido ' . $user->nombre,
        'token' => $token,
        'user' => $user,
    ], 200, [], JSON_UNESCAPED_UNICODE);
});

Route::post('/web/logout', function (Request $request) {
    
    // Revocar tokens API si existen
    if ($request->user()) {
        $request->user()->tokens()->delete();
    }

    // Cerrar sesión web usando el guard web
    Auth::guard('web')->logout(); // 🔹 Importante: especificar el guard web

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json(['message' => 'Sesión cerrada correctamente']);
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
