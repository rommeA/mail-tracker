<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use rommea\MailTracker\Model\SentEmailUrlClicked;

class CreateSentEmailsUrlClickedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection((new SentEmailUrlClicked())->getConnectionName())->create('sent_emails_url_clicked', function (Blueprint $table) {
            if (config('mail-tracker.use_uuids')) {
                $table->uuid('id')->primary();
                $table->uuid('sent_email_id')->unsigned();
            } else {
                $table->increments('id');
                $table->integer('sent_email_id')->unsigned();
            }
            $table->foreign('sent_email_id')->references('id')->on('sent_emails')->onDelete('cascade');
            $table->text('url')->nullable();
            $table->char('hash', 32);
            $table->integer('clicks')->default('1');
            $table->timestamps();
        });
        if (config('mail-tracker.use_uuids')) {
            DB::statement('ALTER TABLE sent_emails_url_clicked ALTER COLUMN id SET DEFAULT uuid_generate_v4();');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection((new SentEmailUrlClicked())->getConnectionName())->drop('sent_emails_url_clicked');
    }
}
