<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ReportCardTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'page_layout',
        'height',
        'width',
        'style',
        'colors',
        'configuration',
        'fields',
        'header_content',
        'footer_content'
    ];

    protected $casts = [
        'style' => 'json',
        'colors' => 'json',
        'configuration' => 'json',
        'fields' => 'json',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Scope to filter by current user's school
     */
    public function scopeOwner($query)
    {
        if (Auth::check() && Auth::user()->school_id) {
            return $query->where('school_id', Auth::user()->school_id);
        }
        return $query;
    }
}

