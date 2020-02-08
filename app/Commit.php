<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Class Commit
 * @package App
 */
class Commit extends Model
{
    protected $table        = 'commit';
    public    $incrementing = false;
    public    $timestamps   = true;
    protected $connection   = 'mysql';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'repo_id',
        'message',
        'author_name',
        'sha',
        'data',
    ];

    /**
     * @return array
     */
    public function isValid()
    {
        $validator = Validator::make($this->toArray(), [
            'sha' => [
                'unique:'.$this->table,
            ],
        ]);

        return $validator->validate();
    }
}
