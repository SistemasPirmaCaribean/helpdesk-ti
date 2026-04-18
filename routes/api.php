<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TktCategoryController;
use App\Http\Controllers\TktDepartamentController;
use App\Http\Controllers\TktLogController;
use App\Http\Controllers\TktPriorityController;
use App\Http\Controllers\TktStatusController;
use App\Http\Controllers\TktTicketController;
use App\Http\Controllers\TktTicketClaimsController;
use App\Http\Controllers\TktTicketCommentController;
use App\Http\Controllers\TktCategoryTechnicianController;

use Illuminate\Support\Facades\Hash;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::middleware('auth:sanctum')->group(function () {
    ///////////////////////////////////////////////////////
    // USER INFO
    ///////////////////////////////////////////////////////
    Route::get('/me', [UsuarioController::class, 'me']);

    ///////////////////////////////////////////////////////
    // USER Change Password
    ///////////////////////////////////////////////////////
    Route::post('usuarios/{id}/change-password', function (Request $request, $id) {
        $request->validate([
            'current_password' => 'required|string|max:255',
            'new_password' => 'required|string|max:255|confirmed',
            'nombre' => 'required|string|max:255'
        ]);

        $user = Auth::user();

        if ($user->id != $id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!Hash::check($request->current_password, Auth::user()->getAuthPassword())) {
            return response()->json([
                'message' => 'La contraseña actual es incorrecta'
            ], 400);
        }

        $user->hash = Hash::make($request->new_password);
        $user->nombre = $request->nombre;
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    });

    Route::apiResource('usuarios', UsuarioController::class);

    ////////////////////////////////////////////////////////
    // Endpoints for Tkt all
    ////////////////////////////////////////////////////////

    //////////////////////////////////////////////////
    ///////// Enpoints for Tkt Tickets
    //////////////////////////////////////////////////
    Route::get('tkt-ticket-comments/{id}', [TktTicketCommentController::class, 'showByTicket']);
    Route::post('tkt-tickets/{id}/status', [TktTicketController::class, 'updateStatus']);

    //////////////////////////////////////////////////
    ///////// Enpoints for Tkt Technician
    //////////////////////////////////////////////////
    Route::get('tkt-technicians', [TktCategoryTechnicianController::class, 'getTechnicians']);

    //////////////////////////////////////////////////
    ///////// Enpoints for Tkt Claims
    //////////////////////////////////////////////////
    Route::get('tkt-claims/{idticket}', [TktTicketClaimsController::class, 'showByTicket']);
    Route::post('tkt-claims/{id}/approve', [TktTicketClaimsController::class, 'approve']);
    Route::post('tkt-claims/{id}/reject', [TktTicketClaimsController::class, 'reject']);
    Route::post('tkt-claims/{id}/approve-unclaim', [TktTicketClaimsController::class, 'approveUnclaim']);
    Route::post('tkt-claims/{id}/reject-unclaim', [TktTicketClaimsController::class, 'rejectUnclaim']);


    //////////////////////////////////////////////////
    ///////// Enpoints for Tkt Catalogs
    //////////////////////////////////////////////////

    Route::apiResource('tkt-categories', TktCategoryController::class);
    Route::apiResource('tkt-departaments', TktDepartamentController::class);
    Route::apiResource('tkt-logs', TktLogController::class);
    Route::apiResource('tkt-priorities', TktPriorityController::class);
    Route::apiResource('tkt-status', TktStatusController::class);
    Route::apiResource('tkt-tickets', TktTicketController::class);
    Route::apiResource('tkt-claims', TktTicketClaimsController::class);
    Route::apiResource('tkt-ticket-comments', TktTicketCommentController::class);
}); 