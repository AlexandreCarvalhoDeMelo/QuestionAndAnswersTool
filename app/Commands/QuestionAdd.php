<?php
declare(strict_types=1);

namespace App\Commands;

use App\Repositories\Question as QuestionRepository;
use LaravelZero\Framework\Commands\Command;

class QuestionAdd extends Command
{

    /**
     * Interactive CLI string messages
     */
    public const COMMAND_TITLE = 'Create new question';
    public const COMMAND_SUCCESS = 'Created!';
    public const COMMAND_QUIT_CODE_MESSAGE = 'Ended by the user';
    public const QUESTION_TEXT = 'Enter question text';
    public const ANOTHER_QUESTION_TEXT = 'Wanna create a another question? (y/n)';
    public const QUESTION_ANSWER_TEXT = 'Enter answer text';
    public const INPUT_EMPTY_ERROR = 'Error, empty %s!';
    public const INPUT_DUPLICATED_ERROR = 'Duplicated question, try another one';
    public const INPUT_INVALID_ERROR = 'Repository error, impossible to create question';


    /**
     * Possible commands
     */
    private const COMMAND_QUIT_CODE = '/exit';
    private const COMMAND_GO_BACK_CODE = '/question';
    private const COMMAND_QUESTION_LIST = '/list';


    /**
     * Menu items
     */
    public const MENU_OPTIONS = [
        ' or /question to redefine question',
        ' or /list to see question list',
        ' or /exit to quit',
    ];


    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'question:add';


    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Creates a new question and answer to the tool';


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
     * @return mixed
     * @throws \Throwable
     */
    public function handle(): void
    {
        try {
            $this->title(self::COMMAND_TITLE);

            $question = $this->prompt(self::QUESTION_TEXT, 'question');

            if ($this->isQuestionAlreadyAdded($question)) {
                throw new \Exception(self::INPUT_DUPLICATED_ERROR);
            }

            $answer = $this->prompt(self::QUESTION_ANSWER_TEXT, 'answer');

            $this->repository->create($question, $answer);

            $this->info(self::COMMAND_SUCCESS);

            $this->askAnotherQuestion();

        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $this->handle();
        }
    }


    /**
     * @param string $text
     * @param string $context
     * @return mixed
     * @throws \Throwable
     */
    private function prompt(string $text, string $context = null): string
    {

        $menu = $context ? $this->assembleMenu($context) : '';

        $input = $this->ask($text . $menu);

        if ($this->isBackCommand($input)) {
            $this->handle();
        }

        if ($this->isQuitCommand($input)) {
            $this->info(self::COMMAND_QUIT_CODE_MESSAGE . PHP_EOL);
            exit();
        }

        if (!$input) {
            $errorMessage = sprintf(self::INPUT_EMPTY_ERROR, $context);
            $this->error($errorMessage);
            return $this->prompt($text, $context);
        }

        return $input;
    }


    /**
     * @param $context
     * @return string
     */
    private function assembleMenu(string $context): string
    {
        $menuOptions = $context === 'question' ?
            [
                self::MENU_OPTIONS[2]
            ] :
            [
                self::MENU_OPTIONS[0],
                self::MENU_OPTIONS[2],
            ];


        return implode("", $menuOptions);
    }


    /**
     * @param $input
     * @return bool
     */
    private function isQuitCommand($input): bool
    {
        return $input === self::COMMAND_QUIT_CODE;
    }


    /**
     * @param $input
     * @return bool
     */
    private function isBackCommand($input): bool
    {
        return $input === self::COMMAND_GO_BACK_CODE;
    }


    /**
     * @param string $question
     * @return bool
     */
    private function isQuestionAlreadyAdded(string $question): bool
    {
        return $this->repository->findByText($question) !== null;
    }


    /**
     * @throws \Throwable
     */
    private function askAnotherQuestion(): void
    {
        $userIntent = $this->prompt(self::ANOTHER_QUESTION_TEXT . self::MENU_OPTIONS[1], 'question');
        if ($userIntent === 'y') {
            $this->handle();
        }

        if($userIntent === self::COMMAND_QUESTION_LIST){
            app()->call(QuestionPractice::class.'@handle');
        }
    }

}
