<?php
declare(strict_types=1);

namespace App\Commands;

use App\Repositories\Assessment as AssessmentRepository;
use App\Repositories\Question as QuestionRepository;
use LaravelZero\Framework\Commands\Command;

class Reset extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'reset';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = "Resets saved progress";

    /**
     * @param AssessmentRepository $assessmentRepository
     * @param QuestionRepository $questionRepository
     */
    public function handle(AssessmentRepository $assessmentRepository, QuestionRepository $questionRepository)
    {
        $remove = $this->ask('Wanna remove user progress (y/n)');
        if($remove === 'y') {
            $assessmentRepository->truncate();
            $this->alert('clean user progress');
        }

        $remove = $this->ask('Wanna remove questions (y/n)');
        if($remove === 'y') {
            $questionRepository->truncate();
            $this->alert('clean questions');
        }
    }
}
