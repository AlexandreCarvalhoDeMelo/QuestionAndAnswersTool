<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Assessment as AssessmentModel;
use App\Question as QuestionModel;

class CreateAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create(AssessmentModel::TABLE_NAME, function (Blueprint $table) {
            $table->bigIncrements(AssessmentModel::FIELD_ID);

            $table->integer(AssessmentModel::FIELD_QUESTION_ID)
                ->unsigned()
                ->references(QuestionModel::FIELD_ID)
                ->on(QuestionModel::TABLE_NAME)
                ->onDelete('CASCADE');

            $table->text(AssessmentModel::FIELD_USER_ANSWER_TEXT);
            $table->boolean(AssessmentModel::FIELD_IS_CORRECT_BOOL);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assessments');
    }
}
