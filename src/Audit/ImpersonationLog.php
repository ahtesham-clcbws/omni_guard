<?php

namespace OmniGuard\Audit;

use Illuminate\Database\Eloquent\Model;

class ImpersonationLog extends Model
{
    protected $table = 'omni_impersonations';

    protected $guarded = [];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function impersonator()
    {
        $userModel = config('auth.providers.users.model');
        return $this->belongsTo($userModel, 'impersonator_id');
    }

    public function impersonated()
    {
        $userModel = config('auth.providers.users.model');
        return $this->belongsTo($userModel, 'impersonated_id');
    }
}
