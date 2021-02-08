<?php

namespace App\Http\Middleware;

use App\Questionaire;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserQuestionaire
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if($user != null){
            $filled_questionnaire = Questionaire::where('user_id',$user->id)->first();
            if($filled_questionnaire == null){
                if(count($user->has_shops) > 0){
                    $array = $user->has_shops->pluck('id')->toArray();
                    $filled_questionnaire = Questionaire::whereIn('shop_id',$array)->first();
                    if($filled_questionnaire == null)
                        return redirect()->route('users.dashboard',['ftl' => '1'])->with('failure', 'Kindly fill the Questionaire to continue');
                    else
                        return $next($request);
                }
                else{
                    return redirect()->route('users.dashboard',['ftl' => '1'])->with('failure', 'Kindly fill the Questionaire to continue');
                }
            }
            else{
                return $next($request);
            }
        }
        else{
            return $next($request);
        }

    }
}
