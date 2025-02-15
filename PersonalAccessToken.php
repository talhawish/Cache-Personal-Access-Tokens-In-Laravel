<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    protected bool $isDataCached = true;

    /**
     * Cache getting token from database
     *
     * We do not want to get token from database each time
     * so we have cached it for 360 seconds
     * @param  string  $token
     * @return null|string
     *
     */

    public static function findToken($token)
    {
        $token = cache()->remember("AccessToken::$token", 360, fn() => parent::findToken($token) ?: '_null_');
        return $token === '_null_' ? null : $token;
    }

    /**
     * Cache getting User Data with id
     *
     * We do not want to get data from database each time
     * so we have cached it for 60 seconds
     *
     */

    public function getTokenableAttribute()
    {
        return cache()->remember("token_{$this->id}::id_" . app()->environment(), 360, function () {
            $this->isDataCached = false;
            return parent::tokenable()->first();
        });
    }

    /**
     * Limit saving of PersonalAccessToken records
     *
     * We only want to actually save when there is something other than
     * the last_used_at column that has changed. It prevents extra DB writes
     * since we aren't going to use that column for anything.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $changes = $this->getDirty();
        // Check for 2 changed values because one is always the updated_at column
        if (!$this->isDataCached || !array_key_exists('last_used_at', $changes) || count($changes) > 2) {
            parent::save();
        }

        return false;
    }
}
