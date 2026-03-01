<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100);
            $table->string('iso2', 2)->unique();
            $table->string('iso3', 3);
            $table->string('phone_code', 10);
            $table->string('currency_code', 3);
            $table->string('timezone_default', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $json = File::get(database_path('data/countries.json'));
        $countries = json_decode($json, true);

        DB::table('countries')->upsert(
            $countries,
            ['iso2'], // unique key
            ['name', 'iso3', 'phone_code', 'currency_code', 'timezone_default', 'is_active'],
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
