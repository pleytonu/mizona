
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();

            $table->string('name', 180);
            $table->string('slug', 200)->unique();

            $table->string('legal_name', 200)->nullable();
            $table->string('tax_identifier', 30)->nullable();

            $table->string('organization_type', 50)
                ->default('empresa');

            $table->text('description')->nullable();
            $table->string('website', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 50)->nullable();

            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->index(['organization_type', 'active']);
            $table->index('tax_identifier');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
