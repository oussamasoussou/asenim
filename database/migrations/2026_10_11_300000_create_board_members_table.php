<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Membres du Bureau exécutif affichés sur le site public (accueil + page « Bureau exécutif »).
     */
    public function up(): void
    {
        Schema::create('board_members', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('role');                       // Président, Trésorière…
            $table->string('organization')->nullable();   // ENIMonastir…
            $table->string('email')->nullable();
            $table->string('quote', 255)->nullable();     // Citation affichée sur l'accueil
            $table->string('photo')->nullable();          // chemin sur le disque « public »
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->index(['is_visible', 'position']);
        });

        // Reprise des membres présents dans la maquette du site, pour que le site reste identique.
        $members = [
            ['Maher BenChiekh', 'Président', '« Fédérer les talents pour une ENIM plus forte. »', 'maher benchiekh.jpg'],
            ['Lamis Allegue', 'Secrétaire Générale', '« Créer du lien, aujourd\'hui et pour demain. »', 'Lamis Allegue2.png'],
            ['Faten Debbabi', 'Trésorière', '« Une gestion transparente pour un plus grand impact. »', 'DEBBABI FATEN2.png'],
        ];

        foreach ($members as $i => [$name, $role, $quote, $file]) {
            $photo = null;
            $source = public_path('front/assets/img/team/' . $file);
            if (is_file($source)) {
                $photo = 'bureau/' . \Illuminate\Support\Str::slug($name) . '.' . strtolower(pathinfo($file, PATHINFO_EXTENSION));
                Storage::disk('public')->put($photo, file_get_contents($source));
            }

            DB::table('board_members')->insert([
                'full_name' => $name,
                'role' => $role,
                'organization' => 'ENIMonastir',
                'quote' => $quote,
                'photo' => $photo,
                'position' => $i + 1,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('board_members');
    }
};
