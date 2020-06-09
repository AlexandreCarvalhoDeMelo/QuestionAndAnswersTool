<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    const TABLE_NAME = 'questions';
    const FIELD_ID = 'id';
    const FIELD_QUESTION_TEXT = 'text';
    const FIELD_ANSWER_TEXT = 'answer';


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE_NAME;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [self::FIELD_QUESTION_TEXT, self::FIELD_ANSWER_TEXT];

}
