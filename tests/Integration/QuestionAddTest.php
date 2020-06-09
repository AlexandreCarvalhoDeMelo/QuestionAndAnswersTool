<?php

namespace Tests\Integration;

use App\Commands\QuestionAdd;
use App\Question as QuestionModel;
use Tests\TestCase;

class QuestionAddTest extends TestCase
{
    /**
     *
     */
    public function test_it_can_create_cli_mode()
    {
        $menu = QuestionAdd::MENU_OPTIONS;

        $question = md5(uniqid(rand()));
        $answer = md5(uniqid(rand()));

        $this->artisan('question:add')
            ->expectsQuestion(QuestionAdd::QUESTION_TEXT.$menu[2], $question)
            ->expectsQuestion(QuestionAdd::QUESTION_ANSWER_TEXT.$menu[0].$menu[2], $answer)
            ->expectsQuestion(QuestionAdd::ANOTHER_QUESTION_TEXT.$menu[1].$menu[2], 'n')
            ->run();

        $dbQuestion = QuestionModel::where([QuestionModel::FIELD_QUESTION_TEXT => $question])->first();

        self::assertTrue($dbQuestion->{QuestionModel::FIELD_QUESTION_TEXT} == $question);

        QuestionModel::truncate();
    }
}
