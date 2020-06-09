<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Question as QuestionModel;
use App\Assessment as AssessmentModel;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Question
 * Eloquent Orm question
 * @package App\Repositories
 */
class Assessment
{

    /**
     * @param int $questionId
     * @param string $questionAnswer
     * @param string $answer
     * @return bool
     */
    public function add(int $questionId, string $questionAnswer, string $answer): bool
    {
        $isCorrect = \trim($questionAnswer) === \trim($answer);

        $assessment = $this->findByQuestionId($questionId) ?? new AssessmentModel();
        $assessment->{AssessmentModel::FIELD_QUESTION_ID} = $questionId;
        $assessment->{AssessmentModel::FIELD_USER_ANSWER_TEXT} = $answer;
        $assessment->{AssessmentModel::FIELD_IS_CORRECT_BOOL} = $isCorrect;

        return $assessment->save();
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAllQuestionIds(): \Illuminate\Support\Collection
    {
        return AssessmentModel::all()->pluck(AssessmentModel::FIELD_QUESTION_ID);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAllAssessmentsWithQuestions(): \Illuminate\Support\Collection
    {
        return AssessmentModel::with('question')->get();
    }


    /**
     * @param string $questionId
     * @return mixed
     */
    public function findByQuestionId(int $questionId)
    {
        return AssessmentModel::where(
            AssessmentModel::FIELD_QUESTION_ID, $questionId
        )->first();
    }


    /**
     * @return mixed
     */
    public function truncate()
    {
        return AssessmentModel::truncate();
    }
}
