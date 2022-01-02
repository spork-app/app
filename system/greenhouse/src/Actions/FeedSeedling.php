<?php

namespace Spork\Greenhouse\Actions;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spork\Greenhouse\Models\Plant;
use Spork\Greenhouse\Models\Seed;

class FeedSeedling implements ActionInterface
{
    use ValidatesRequests;

    public function getName(): string
    {
        return 'Feed seedling';
    }

    public function getUrl(): string
    {
        return '/greenhouse/seeds/feed';
    }

    public function tags() {
        return ['seeds'];
    }

    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'plants' => 'required|array',
        ]);

        $plants = Seed::whereIn('id', $request->get('plants'))->get();

        foreach ($plants as $plant) {
            activity()->performedOn($plant)->causedBy($request->user())->log('feed');
        }

        return response()->json([
            'message' => sprintf('You fed %s seedling%s.', $plants->count(), $plants->count() !== 1 ? 's' : ''),
        ]);
    }
}