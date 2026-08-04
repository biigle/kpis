<?php

return [
    /**
     * Token to verify cron job request to save visits/actions
     */
    'token' => env('KPIS_TOKEN'),

    /**
     * Defines the works to fetch the citation count from Semantic Scholar.
     * The key is the bulk endpoint URL query that returns the works and the array values
     * are the work IDs to track from this endpoint.
     *
     * Example query URL:
     * https://api.semanticscholar.org/graph/v1/paper/search/bulk?query=BIIGLE&fields=title,citationCount
     */
    'citations' => [
        'BIIGLE' => [
            // BIIGLE 1.0 Paper
            "2302af4f52822a9be5798bbc7ff168d834c765c8",
            // BIIGLE 2.0 Paper
            "2d17918a543b6f42e2efda0fcf2ec305af41adab",
            // BIIGLE 2.0 Paper Corrigendum
            "b2322956e394f24125a9edb0402f83d56c3f035d",
            // Current Trends paper
            "ff0e0b9c44d749beee3edb75ff7af0f0ee432b1a",
            // BIIGLE2Go Paper
            "e8a5541ebb07de68e1b90257fbf97c3469f92569"
        ]
    ],

    'scorpion' => [
        'token' => env('KPIS_SCORPION_TOKEN'),
        'service' => env('KPIS_SCORPION_SERVICE', 'BIIGLE'),
    ],
];
