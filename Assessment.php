<?php
declare(strict_types=1);

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use NunoMaduro\LaravelConsoleMenu\Menu;

class PracticeQuestion extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'practice:question';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Chooses one question from the list to practice';

    /**
     * Question model layer repositoty
     *
     * @var QuestionRepository repository
     */
    protected $repository;


    /**
     * AddQuestion constructor.
     * @param QuestionRepository $questionRepository
     */
    public function __construct(QuestionRepository $questionRepository)
    {
        parent::__construct();
        $this->repository = $questionRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        /**
         * @var Menu $menu
         */
        $menu = $this->menu('Select question to practice')
            ->setForegroundColour('green')
            ->setWidth(200)
            ->setExitButtonText("Abort")
            ->setTitleSeparator('_');



        $menu->addOption('mozzarella', 'Question 1')
            ->addOption('chicken_parm', 'Question 1')
            ->addOption('sausage', 'Question 1')
            ->addOption('Back', 'Back')
            ->open();
    }

}
