<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{

    /**
     *
     */
    const TABLE_NAME = 'assessments';
    const FIELD_ID = 'id';
    const FIELD_QUESTION_ID = 'question_id';
    const FIELD_USER_ANSWER_TEXT = 'value';
    const FIELD_IS_CORRECT_BOOL = 'is_correct';


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
    protected $fillable = [
        self::FIELD_USER_ANSWER_TEXT,
        self::FIELD_IS_CORRECT_BOOL,
    ];


    /**
     *
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
