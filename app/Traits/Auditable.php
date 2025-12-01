<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    /**
     * Boot the trait, automatically register event listeners
     */
    public static function bootAuditable()
    {
        // listen for the 'created' event
        static::created(function ($model) {
            self::logChange($model, 'created');
        });

        // listen for the 'updated' event
        static::updated(function ($model) {
            self::logChange($model, 'updated');
        });

        // listen for the 'deleted' event
        static::deleted(function ($model) {
            self::logChange($model, 'deleted');
        });
    }

    /**
     * main function that logs changes to the db
     */
    protected static function logChange($model, $event)
    {
        
        $oldValues = null;
        $newValues = null;

        if ($event === 'created') {
            $newValues = $model->getAttributes();
        } elseif ($event === 'updated') {
            $oldValues = $model->getOriginal(); // data before change
            $newValues = $model->getAttributes(); // data after change
        } elseif ($event === 'deleted') {
            $oldValues = $model->getAttributes();
        }

        // determine the user (if logged in)
        $userId = Auth::check() ? Auth::id() : null;

        // create the Audit Log entry
        AuditLog::create([
            'user_id'        => $userId,
            'event'          => $event,
            'auditable_type' => get_class($model), // ex: "App\Models\InventoryItem"
            'auditable_id'   => $model->getKey(),
            'old_values'     => $oldValues,
            'new_values'     => $newValues,
            'url'            => Request::fullUrl(),
            'ip_address'     => Request::ip(),
            'user_agent'     => Request::userAgent(),
        ]);
    }
}