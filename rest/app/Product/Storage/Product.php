<?php

namespace App\Product\Storage;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';
    /** @var string */
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sku',
        'description',
        'image_path',
    ];

    /** @return int */
    public function getId() : int
    {
        return $this->attributes['id'];
    }

    /** @return string|null */
    public function getName() : ?string
    {
        if (isset($this->attributes['name'])) {
            return $this->attributes['name'];
        }

        return null;
    }

    /** @return string|null */
    public function getSku() : ?string
    {
        if (isset($this->attributes['sku'])) {
            return $this->attributes['sku'];
        }

        return null;
    }

    /** @return string|null */
    public function getDescription() : ?string
    {
        if (isset($this->attributes['description'])) {
            return $this->attributes['description'];
        }

        return null;
    }

    /** @return string|null */
    public function getImagePath() : ?string
    {
        if (isset($this->attributes['image_path'])) {
            return $this->attributes['image_path'];
        }

        return null;
    }
}
