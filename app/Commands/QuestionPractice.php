<?php
declare(strict_types=1);

namespace App\Commands;

use App\Assessment;
use App\Question;
use App\Repositories\Question as QuestionRepository;
use App\Repositories\Assessment as AssessmentRepository;
use LaravelZero\Framework\Commands\Command;
use NunoMaduro\LaravelConsoleMenu\Menu;
use PhpSchool\CliMenu\CliMenu;
use Symfony\Component\Console\Output\ConsoleOutput;

class QuestionPractice extends Command
{

    const QUESTION_ANSWER_CONFIRMATION_TEXT = 'You sure you wanna answer %s with %s? (y/n)';
    const QUESTION_TOTAL_RIGHT_REPORT = '* Total %s of %s questions correct';
    const QUESTION_REPORT_HEADER_TABLE = [
        'Question',
        'Correct',
        'Given Answer',
        'Correct Answer ',
    ];

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'question:practice';


    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Creates a new question and answer to the tool';

    /**
     *
     *
     * @var array $questions
     */
    protected $questions;


    /**
     * Question model layer repository
     *
     * @var QuestionRepository repository
     */
    protected $questionRepository;


    /**
     * Assessment model layer repository
     *
     * @var QuestionRepository repository
     */
    protected $assessmentRepository;


    /**
     * Assessment constructor.
     * @param QuestionRepository $questionRepository
     * @param AssessmentRepository $assessmentRepository
     */
    public function __construct(QuestionRepository $questionRepository, AssessmentRepository $assessmentRepository)
    {
        parent::__construct();
        $this->questionRepository = $questionRepository;
        $this->assessmentRepository = $assessmentRepository;
    }


    /**
     *
     */
    public function handle()
    {
        $this->questions = $this->assembleQuestionItemsArray();

        if (empty($this->questions)) {
            $this->error('No questions found.');
            exit();
        }

        $menu = $this->assembleMenu($this->questions);

        $mappedQuestions = array_map(function ($question) use ($menu) {
            return [$question['text'], $this->assembleQuestionCallback($menu, $question)];

        }, $this->questions);

        $menu->addItems($mappedQuestions);
        $menu->open();
    }


    /**
     * @return array
     */
    private function assembleQuestionItemsArray(): array
    {
        $questions = $this->questionRepository->getAll()->map(
            function (Question $question) {
                return [
                    'id' => $question->id,
                    'text' => $question->text,
                    'answer' => $question->answer,
                ];
            }
        )->toArray();

        return $questions;
    }


    /**
     * @param array $questions
     * @return Menu
     */
    private function assembleMenu(array $questions): Menu
    {
        return $this->menu('Practice question')
            ->setForegroundColour('white')
            ->setBackgroundColour('blue')
            ->setWidth(80);
    }


    /**
     * @param Menu $menu
     * @param array $question
     * @return \Closure
     */
    private function assembleQuestionCallback(Menu $menu, array $question): \Closure
    {
        $callback = function (CliMenu $cliMenu) use (&$callback, $menu, $question) {

            $userAnswer = $cliMenu->askText()
                ->setPromptText($question['text'])
                ->ask()->fetch();

            $confirmationMessage = vsprintf(
                self::QUESTION_ANSWER_CONFIRMATION_TEXT, [$question['text'], $userAnswer]
            );

            $shouldSave = $cliMenu->askText()
                ->setPromptText($confirmationMessage)
                ->ask();


            if ($shouldSave->fetch() !== 'y') {
                return $callback($cliMenu);
            }

            $this->assessmentRepository->add(
                $question['id'],
                $question['answer'],
                $userAnswer
            );

            $this->verifyAllQuestionsWereAnwsered();

        };

        return $callback;
    }


    /**
     *
     */
    private function verifyAllQuestionsWereAnwsered()
    {
        $questions = array_column($this->questions, 'id');
        $questionsAnswered = $this->assessmentRepository->getAllQuestionIds()->toArray();
        $countedAnswers = count(array_intersect($questions, $questionsAnswered));
        $areAllQuestionsAnswered = $countedAnswers === count($questions);

        if ($areAllQuestionsAnswered) {
            return $this->renderReportTable();
        }
    }


    /**
     * This could be improved in a number of ways
     */
    private function renderReportTable()
    {
        $rightQuestionsCount = 0;

        $questionTable = $this->assessmentRepository->getAllAssessmentsWithQuestions()->map(
            function (Assessment $assessment) use (&$rightQuestionsCount) {
                $assessment = $assessment->toArray();
                $isCorrect = 'no';

                if ($assessment[Assessment::FIELD_IS_CORRECT_BOOL]) {
                    $isCorrect = 'yes';
                    $rightQuestionsCount++;
                }

                return [
                    $assessment['question'][Question::FIELD_QUESTION_TEXT],
                    $isCorrect,
                    $assessment[Assessment::FIELD_USER_ANSWER_TEXT],
                    $assessment['question'][Question::FIELD_ANSWER_TEXT]
                ];
            })->toArray();

        $this->output = new ConsoleOutput();

        $this->info(
            $this->assembleReportSummaryMessage($rightQuestionsCount)
        );

        $this->table(self::QUESTION_REPORT_HEADER_TABLE, $questionTable);
        exit();
    }


    /**
     * @param int $totalRightQuestions
     * @return string
     */
    private function assembleReportSummaryMessage(int $totalRightQuestions): string
    {
        return vsprintf(self::QUESTION_TOTAL_RIGHT_REPORT, [
            $totalRightQuestions,
            count($this->questions),
        ]);
    }
}
