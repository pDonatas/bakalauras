<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manCategories = [
            'Plaukų kirpimas',
            'Barzdos kirpimas',
            'Gražinamoji keratininė plaukų procedūra',
            'Manikiūras',
            'Pedikiūras',
            'Masažai',
            'Kontūrinis veido modeliavimas',
            'Antakių korekcija',
        ];

        $womenCategories = [
            'Plaukų kirpimas',
            'Plaukų dažymas',
            'Šukuosenos',
            'Manikiūras',
            'Pedikiūras',
            'Veido procedūros',
            'Masažai',
            'Kūno procedūros',
            'Depiliacija',
            'Soliariumas',
            'SPA procedūros',
            'Skaistinanti kosmetika',
            'Ekspresinis makiažas',
            'Vakarinis makiažas',
            'Rankų masažas',
            'Kojų masažas',
            'Blakstienų priauginimas',
            'Kontūrinis veido modeliavimas',
            'Krioterapija',
        ];

        $mainCategories = [
            'Vyrams',
            'Moterims',
        ];

        foreach ($mainCategories as $mainCategory) {
            $category = Category::factory()->create([
                'name' => $mainCategory,
                'slug' => str_replace(' ', '-', strtolower($mainCategory)),
            ]);

            if ('Vyrams' === $mainCategory) {
                foreach ($manCategories as $manCategory) {
                    Category::factory()->create([
                        'name' => $manCategory,
                        'slug' => str_replace(' ', '-', strtolower($manCategory)),
                        'parent_id' => $category->id,
                    ]);
                }
            } else {
                foreach ($womenCategories as $womenCategory) {
                    Category::factory()->create([
                        'name' => $womenCategory,
                        'slug' => str_replace(' ', '-', strtolower($womenCategory)),
                        'parent_id' => $category->id,
                    ]);
                }
            }
        }
    }
}
