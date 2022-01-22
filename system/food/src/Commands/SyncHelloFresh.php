<?php

namespace Spork\Food\Commands;

use Spork\Food\Models\Allergen;
use Spork\Food\Contracts\Services\HelloFreshServiceInterface;
use Spork\Food\Models\Cuisine;
use Spork\Food\Models\Ingredient;
use Spork\Food\Models\Family;
use Spork\Food\Models\Recipe;
use Spork\Food\Models\Step;
use Spork\Food\Models\Utensil;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SyncHelloFresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:hello-fresh {token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Recipes from HelloFresh';


    public function handle(HelloFreshServiceInterface $service)
    {
        $page = 1;
        do {
            $results = $service->findAll($this->argument('token'), $page++, 100);

            foreach ($results as $result) {
                $this->handleSingleResult($result);
            }

        } while ($results->hasMorePages());
    }

    protected function handleSingleResult($result)
    {
        if (empty($result->steps) || empty($result->ingredients)) {
            $this->info('[!] Skipping ' . $result->name . ' due to a lack of info!');
            return;
        }

        $data = $fillable = array_merge(Arr::only(json_decode(json_encode($result), true), [
            'id',
            'country',
            'type',
            'name',
            'seoName',
            'slug',
            'headline',
            'description',
            'descriptionMarkdown',
            'seoDescription',
            'difficulty',
            'prepTime',
            'totalTime',
            'servingSize',
            'link',
            'imageLink',
            'cardLink',
            'videoLink',
            'clonedFrom',
            'canonical',
            'canonicalLink',
        ]), [
            'category' => isset($result->category) ? $result->category->name : null,
            'yields' => json_encode($result->yields),
            'imageLink' => 'https://img.hellofresh.com/c_fill,f_auto,fl_lossy,h_432,q_auto,w_768/hellofresh_s3'. $result->imagePath
        ]);

        /** @var Recipe $recipe */
        $recipe = Recipe::firstOrCreate(['id' => $result->id], $data);
        $recipe->recipe_id = $result->id;

        $message = $recipe->wasRecentlyCreated  ? '[+] Creating recipe ' : '[-] Updating recipe ';
        $this->info($message . $recipe->name);

        $recipe->wines()->sync($result->wines);

        /**
         * Steps
         */
        foreach ($result->steps as $step) {
            /** @var Step $stepModel */
            $stepModel = Step::updateOrCreate(['index' => $step->index, 'recipe_id' => $result->id], [
                'index' => $step->index,
                'instructionsMarkdown' => $step->instructionsMarkdown,
                'instructions' => $step->instructions,
                'images' => 'https://img.hellofresh.com/f_auto,fl_lossy,q_auto,w_1280/hellofresh_s3'. (Arr::first($step->images)->path ?? '/notfound.png'),
                'recipe_id' => $result->id,
            ]);
            $stepModel->utensils()->sync($step->utensils);
        }

        /**
         * Utensils
         */
        $utensils = [];
        foreach ($result->utensils as $utensil) {
            $utensils[] = Utensil::updateOrCreate(['id' => $utensil->id], $this->fillable($utensil));
        }
        $recipe->utensils()->sync(array_map(function ($part) { return $part->id; }, $utensils));

        /**
         * Allergens
         */
        $allergens = [];
        foreach ($result->allergens as $allergen) {
            $allergens[] = Allergen::updateOrCreate(['id' => $allergen->id], $this->fillable($allergen));
        }
        $recipe->allergens()->sync(array_map(function ($part) { return $part->id; }, $allergens));

        /**
         * Ingredients
         */
        $ingredients = [];
        foreach ($result->ingredients as $ingredient) {
            /** @var Ingredient $ingredientModel */
            $ingredients[] = $ingredientModel = Ingredient::updateOrCreate(['id' => $ingredient->id], $this->fillable($ingredient));

            if (!empty($ingredient->family)) {
                $hasRelation = DB::table('ingredient_families')
                    ->where([
                        'ingredient_id' => $ingredient->id,
                        'family_id' => $ingredient->family->id,
                    ])
                    ->first();

                if (empty($hasRelation)) {
                    $family = Family::updateOrCreate(['id' => $ingredient->family->id], $this->fillable($ingredient->family));
                    $ingredientModel->family()->attach($family);
                }
            }
        }
        $recipe->ingredients()->sync(array_map(function ($part) { return $part->id; }, $ingredients));

        if (!empty($result->yields)) {
            foreach ($result->yields[0]->ingredients as $ingredient) {
                $recipe->ingredients()->updateExistingPivot($ingredient->id, [
                    'amount' => $ingredient->amount,
                    'unit' => $ingredient->unit,
                ]);
            }
        }

        /**
         * Cuisines
         */
        $cuisines = [];
        foreach ($result->cuisines as $cuisine) {
            $cuisines[] = Cuisine::updateOrCreate(['id' => $cuisine->id, ], $this->fillable($cuisine));
        }
        $recipe->cuisines()->sync(array_map(function ($part) { return $part->id; }, $cuisines));
    }

    /**
     * @param string|Model $class
     * @param array $fillable
     * @param $field
     * @return Model
     */
    protected function fillable(\stdClass $result): array
    {
        $fillable = Arr::only(json_decode(json_encode($result), true), [
            'id',
            'country',
            'type',
            'name',
            'seoName',
            'slug',
            'headline',
            'description',
            'descriptionMarkdown',
            'seoDescription',
            'difficulty',
            'prepTime',
            'totalTime',
            'servingSize',
            'link',
            'imageLink',
            'cardLink',
            'videoLink',
            'clonedFrom',
            'canonical',
            'canonicalLink',
        ]);

        return array_merge($fillable, [
            'category' => isset($result->category) ? $result->category->name : null
        ]);
    }
}
