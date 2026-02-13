<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->boolean('ticket_offer_pending')->default(false);
            $table->string('ticket_draft_subject')->nullable();
            $table->text('ticket_draft_description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn(['ticket_offer_pending','ticket_draft_subject','ticket_draft_description']);
        });
    }
};
