<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mission;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
{
    public static function completeMission($mission_id): void
    {
        $user = User::find(Auth::id());

        if(is_null($user->missions()->find($mission_id))) {

            $user->missions()->attach($mission_id);
    
            $user->update([
                'user_xp' => $user->user_xp + Mission::find($mission_id)->mission_xp
            ]);

            if($user->user_xp >= 125) {
                $user->update([
                    'level_id' => 2
                ]);
            }
            if($user->user_xp >= 250) {
                $user->update([
                    'level_id' => 3
                ]);
            }
            if($user->user_xp >= 500) {
                $user->update([
                    'level_id' => 4
                ]);
            }
        }
    }
}
