<?php

namespace App\Food\Http\Controllers;

use App\Core\Http\Controllers\Controller;

class MealPlanCrudController extends Controller
{

    public function destroy()
    {

    }
    public function store()
    {
        $this->validate(request(), [
            'name' => 'required',
            'description' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $mealPlan = auth()->user()->mealPlans()->create(request()->all());

        return redirect()->route('meal-plans.show', $mealPlan);
    }
}