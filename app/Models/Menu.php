<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Menu extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'url',
        'type',
        'target',
        'icon',
        'order',
        'position',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * Get the parent menu.
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Get the child menus.
     */
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get active children menus.
     */
    public function activeChildren()
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('order');
    }

    /**
     * Scope a query to only include active menus.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to exclude static menus (Beranda, Kontak, and main menus).
     */
    public function scopeExcludeStatic($query)
    {
        return $query->whereNotIn('slug', [
            'beranda', 
            'kontak',
            'profil',
            'informasi',
            'layanan-kesehatan',
            // Submenu Profil
            'sambutan',
            'profil-puskemas',
            'visi-misi',
            'struktur-organisasi',
            // Submenu Informasi
            'berita',
            'pengumuman',
            'agenda',
            'galeri',
            'berkas',
            // Submenu Layanan
            'layanan',
            'alur-pelayanan'
        ]);
    }

    /**
     * Scope a query to only include parent menus (no parent_id).
     */
    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope a query to get menus by position.
     */
    public function scopePosition($query, $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Get menus ordered.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Check if menu has children.
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get the page associated with the menu.
     */
    public function page()
    {
        return $this->hasOne(Page::class);
    }

    /**
     * Get full URL for the menu.
     */
    public function getFullUrlAttribute()
    {
        if ($this->type === 'external') {
            return $this->url;
        }

        // For internal links, ensure URL starts with /
        $url = $this->url;
        if (!empty($url) && $url[0] !== '/') {
            $url = '/' . $url;
        }

        return $url;
    }
}
