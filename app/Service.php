<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Service extends Model implements Auditable
{
    use AuditableTrait;

    protected $fillable = ['arn', 'cluster_id'];

    protected $casts = [
        'scheduled' => 'boolean'
    ];

    /**
     * Derive short name from Amazon Resource Name
     *
     * @return string
     */
    public function getNameAttribute()
    {
        $parts = explode('/', $this->arn);
        return end($parts);
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

    public function scopeVisible($query)
    {
        return $query->where('hidden', false);
    }
}
