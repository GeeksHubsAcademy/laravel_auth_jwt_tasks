<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    const ROLE_SUPER_ADMIN = 3;

    public function addSuperAdminRoleToUser($id)
    {
        try {
            $user = User::find($id);

            $user->roles()->attach(self::ROLE_SUPER_ADMIN);

            return response()->json(
                [
                    'success' => true,
                    'message' => "Super admin role added to user"
                ],
                200
            );

        } catch (\Exception $exception) {
            Log::error("Error addind superadmin role to User: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error adding superadmin role to user"
                ],
                500
            );
        }
    }
}
