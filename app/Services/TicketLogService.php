<?php

namespace App\Services;

use App\Models\TktLog;

class TicketLogService
{
    public static function log($ticketId, $action, $field = null, $old = null, $new = null)
    {
        TktLog::create([
            'idticket' => $ticketId,
            'iduser' => auth()->id(),
            'action' => $action,
            'field' => $field,
            'old_value' => $old,
            'new_value' => $new,
        ]);
    }
}