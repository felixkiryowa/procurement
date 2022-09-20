<?php

namespace App\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\LoggingActivity;



trait LogActivityTrait {

    public static function addToLog($user_ip,$subject, $details=null) {
        try {

            if(Auth::check()) {
                $names = Auth::user()->firstname.' '.Auth::user()->lastname;
            }else {
                $names = NULL;
            }

            $log = [];
            $log['user_ip'] = $user_ip;
            $logp['subject'] = $subject;
            $logp['details'] = $details;
            $log['performed_by'] = $names;
            $log['company_id'] = Auth::user()->company_id;

            LoggingActivity::create($log);

        }catch(\Exception $e) {
            Log::channel('daily')->error('Log message', array('message' => $e->getMessage(), 
            'type' => 'HAndling the logs'));
        }
    }
}


