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

        /** @var Recipe $recipe */
        $model = Recipe::where('id', $result->id)
            ->orWhere('slug', $result->slug)
            ->first();

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

        // At some point since it's creation in 2019, they updated their CDN link, but not what's served from their API it seems lol...
        $fillable['imageLink'] = str_replace('https://d3hvwccx09j84u.cloudfront.net/0,0/image/', 'https://img.hellofresh.com/c_fill,f_auto,fl_lossy,h_214,q_auto,w_381/hellofresh_s3/image/', $fillable['imageLink']);

        if (!empty($model)) {
            $this->info('[-] Updating: ' . $result->name);

            $model->update(array_merge($fillable, [
                'category' => isset($result->category) ? $result->category->name : null,
                'yields' => json_encode($result->yields),
            ]));
            return $model->refresh();
        }

        Recipe::create(array_merge($fillable, [
            'recipe_id' => $result->id,
            'category' => isset($result->category) ? $result->category->name : null
        ]));

        $recipe = Recipe::where('id', $result->id)->first();
        $recipe->recipe_id = $result->id;

        $this->info('[+] Creating recipe ' . $recipe->name);

        $recipe->wines()->sync($result->wines);

        /**
         * Steps
         */
        foreach ($result->steps as $step) {
            /** @var Step $stepModel */
            $stepModel = $recipe->steps()->create([
                'index' => $step->index,
                'instructionsMarkdown' => $step->instructionsMarkdown,
                'instructions' => $step->instructions,
                'images' => Arr::first($step->images)->link ?? null,
                'recipe_id' => $result->id,
            ])->refresh();

            $stepModel->utensils()->sync($step->utensils);
        }

        /**
         * Utensils
         */
        $utensils = [];
        foreach ($result->utensils as $utensil) {
            $utensils[] = $this->findOrCreate(Utensil::class, $this->fillable($utensil), 'id');
        }
        $recipe->utensils()->sync(array_map(function ($part) { return $part->id; }, $utensils));

        /**
         * Allergens
         */
        $allergens = [];
        foreach ($result->allergens as $allergen) {
            $allergens[] = $this->findOrCreate(Allergen::class, $this->fillable($allergen), 'id');
        }
        $recipe->allergens()->sync(array_map(function ($part) { return $part->id; }, $allergens));

        /**
         * Ingredients
         */
        $ingredients = [];
        foreach ($result->ingredients as $ingredient) {
            /** @var Ingredient $ingredientModel */
            $ingredients[] = $ingredientModel = $this->findOrCreate(Ingredient::class, $this->fillable($ingredient), 'id');

            if (!empty($ingredient->family)) {
                $hasRelation = DB::table('ingredient_families')
                    ->where([
                        'ingredient_id' => $ingredient->id,
                        'family_id' => $ingredient->family->id,
                    ])
                    ->first();

                if (empty($hasRelation)) {
                    $family = $this->findOrCreate(Family::class, $this->fillable($ingredient->family), 'id');
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
            $cuisines[] = $this->findOrCreate(Cuisine::class, $this->fillable($cuisine), 'id');
        }
        $recipe->cuisines()->sync(array_map(function ($part) { return $part->id; }, $cuisines));
    }

    /**
     * @param string|Model $class
     * @param array $fillable
     * @param $field
     * @return Model
     */
    protected function findOrCreate(string $class, array $fillable, $field): Model
    {
        $model = $class::where($field, $fillable[$field])->first();

        if (!empty($model)) {
            return $model;
        }

        $class::create($fillable);

        return $class::where($field, $fillable[$field])->first();
    }

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
