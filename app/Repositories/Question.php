<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Question as QuestionModel;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Question
 * Eloquent Orm question
 * @package App\Repositories
 */
class Question
{
    /**
     * Creates new question
     * @param string $text
     * @param string $answer
     * @return bool
     * @throws \Throwable
     */
    public function create(string $text, string $answer): bool
    {
        $question = new QuestionModel();
        $question->{QuestionModel::FIELD_QUESTION_TEXT} = $text;
        $question->{QuestionModel::FIELD_ANSWER_TEXT} = $answer;

        return $question->save();
    }


    /**
     * @param string $text
     * @param int $limit
     * @return mixed
     */
    public function findByText(string $text)
    {
        return QuestionModel::where(
            QuestionModel::FIELD_QUESTION_TEXT, $text
        )->first();
    }


    /**
     * @param bool $sortedByLast
     * @return Collection
     */
    public function getAll($sortedByLast = false): Collection
    {
        return QuestionModel::all();
    }


    /**
     * @param bool $sortedByLast
     * @return Collection
     */
    public function getQuestionsWithAnswers(): Collection
    {
        return QuestionModel::all();
    }


    /**
     * @return mixed
     */
    public function truncate()
    {
        return QuestionModel::truncate();
    }
}
