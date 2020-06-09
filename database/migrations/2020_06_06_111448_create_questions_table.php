<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Question as QuestionModel;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(QuestionModel::TABLE_NAME, function (Blueprint $table) {
            $table->bigIncrements(QuestionModel::FIELD_ID);
            $table->text(QuestionModel::FIELD_QUESTION_TEXT);
            $table->text(QuestionModel::FIELD_ANSWER_TEXT);
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
        Schema::dropIfExists('questions');
    }
}
