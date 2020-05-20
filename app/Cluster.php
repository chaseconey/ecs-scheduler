<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    protected $fillable = ['arn', 'region'];

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

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
