<?php
declare(strict_types=1);

namespace Tests\Unit\Repositories;

use App\Repositories\Question as QuestionRepository;
use Tests\TestCase;

class QuestionTest extends TestCase
{

    /**
     * @var QuestionRepository
     */
    public $repository;


    /**
     * @return array
     */
    public function questionProvider()
    {
        return [
            'question' => [
                'text' => 'What is the value of the following equation: (10 + 10) / 10 ?',
                'answer' => '2',
            ]
        ];
    }

    /**
     * @dataProvider questionProvider
     * @param string $text
     * @param string $answer
     * @throws \Throwable
     */
    public function test_it_can_create(string $text, string $answer)
    {
        $subject = $this->repository->create($text, $answer);

        self::assertTrue($subject);
    }


    /**
     * @dataProvider questionProvider
     * @param string $text
     * @param string $answer
     * @throws \Throwable
     */
    public function test_it_can_search(string $text, string $answer)
    {
        $this->repository->create($text, $answer);
        $subject = $this->repository->findByText($text);

        self::assertEquals($subject->text, $text);
        self::assertEquals($subject->answer, $answer);
    }


    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new QuestionRepository();
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
