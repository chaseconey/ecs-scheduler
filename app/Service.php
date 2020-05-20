<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
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
}
