<?php

namespace App\Traits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\LoggingActivity;



trait LogActivityTrait {

    public static function addToLog($subject, $details=null) {
        try {
            $log = [];
            $logp['subject'] = $subject;
            $logp['details'] = $details;
            $log['performed_by'] = Auth::user()->firstname.' '.Auth::user()->lastname;

            LoggingActivity::create($log);

        }catch(\Exception $e) {
            Log::channel('daily')->error('Log message', array('message' => $e->getMessage(), 
            'type' => 'HAndling the logs'));
        }
    }
}


