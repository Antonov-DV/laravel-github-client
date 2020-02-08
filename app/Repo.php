<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * Class Repo
 * @package App
 */
class Repo extends Model
{
    protected $table        = 'repo';
    public    $incrementing = false;
    public    $timestamps   = true;
    protected $connection   = 'mysql';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'owner_login',
        'owner_id',
        'data',
    ];

    /**
     * @return array
     */
    public function isValid()
    {
        $validator = Validator::make($this->toArray(), [
            'id' => [
                'unique:'.$this->table,
            ],
        ]);

        return $validator->validate();
    }
}
