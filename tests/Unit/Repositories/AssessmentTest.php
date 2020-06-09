<?php
declare(strict_types=1);

namespace Tests\Unit\Repositories;

use App\Assessment as AssessmentModel;
use App\Question as QuestionModel;
use App\Repositories\Assessment as AssessmentRepository;
use Tests\TestCase;

class AssessmentTest extends TestCase
{

    /**
     * @var AssessmentRepository
     */
    public $repository;


    /**
     * @return array
     */
    public function assessmentCorrectAnswerProvider()
    {
        return [
            'assessment_question' => [
                'text' => 'What is the value of the following equation: (10-9) * 100 / 10 ?',
                'answer' => 10,
                'givenAnswer' => 10,
            ],
        ];
    }


    /**
     * @return array
     */
    public function assessmentIncorrectAnswerProvider()
    {
        return [
            'assessment_question' => [
                'text' => 'What is the value of the following equation: (10 + 10) / 10 ?',
                'answer' => 20,
                'givenAnswer' => 10,
            ],
        ];
    }


    /**
     * @dataProvider assessmentCorrectAnswerProvider
     * @param string $text
     * @param string $answer
     * @param string $givenAnswer
     */
    public function test_find_by_question(string $text, string $answer, string $givenAnswer)
    {

        $question = new QuestionModel();
        $question->{QuestionModel::FIELD_QUESTION_TEXT} = $text;
        $question->{QuestionModel::FIELD_ANSWER_TEXT} = $answer;
        $question->save();

        $this->repository->add(
            $question->id,
            $question->{QuestionModel::FIELD_ANSWER_TEXT},
            $givenAnswer
        );

        $subject = $this->repository->findByQuestionId(
            $question->id
        );

        self::assertTrue($subject->{AssessmentModel::FIELD_QUESTION_ID} === $question->id);


    }

    /**
     * @dataProvider assessmentIncorrectAnswerProvider
     * @param string $text
     * @param string $answer
     * @param string $givenAnswer
     */
    public function test_add_incorrect(string $text, string $answer, string $givenAnswer)
    {

        $question = new QuestionModel();
        $question->{QuestionModel::FIELD_QUESTION_TEXT} = $text;
        $question->{QuestionModel::FIELD_ANSWER_TEXT} = $answer;
        $question->save();

        $this->repository->add(
            $question->id,
            $question->{QuestionModel::FIELD_ANSWER_TEXT},
            $givenAnswer
        );

        $assessment = AssessmentModel::where([AssessmentModel::FIELD_QUESTION_ID => $question->id])->first();

        $subject = $assessment->{AssessmentModel::FIELD_USER_ANSWER_TEXT} === $givenAnswer;
        self::assertTrue($subject);
        self::assertFalse((bool)$assessment->{AssessmentModel::FIELD_IS_CORRECT_BOOL});

    }


    /**
     * @dataProvider assessmentCorrectAnswerProvider
     * @param string $text
     * @param string $answer
     * @param string $givenAnswer
     */
    public function test_add_correct(string $text, string $answer, string $givenAnswer)
    {

        $question = new QuestionModel();
        $question->{QuestionModel::FIELD_QUESTION_TEXT} = $text;
        $question->{QuestionModel::FIELD_ANSWER_TEXT} = $answer;
        $question->save();

        $this->repository->add(
            $question->id,
            $question->{QuestionModel::FIELD_ANSWER_TEXT},
            $givenAnswer
        );

        $assessment = AssessmentModel::where([AssessmentModel::FIELD_QUESTION_ID => $question->id])->first();

        $subject = $assessment->{AssessmentModel::FIELD_USER_ANSWER_TEXT} === $givenAnswer;
        self::assertTrue($subject);
        self::assertTrue((bool)$assessment->{AssessmentModel::FIELD_IS_CORRECT_BOOL});

    }


    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new AssessmentRepository();
        $this->repository->truncate();
    }


    /**
     *
     */
    public function tearDown(): void
    {
        $this->repository->truncate();
        parent::tearDown();
    }


}
