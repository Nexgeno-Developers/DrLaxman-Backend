<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\ApiPayloadCache;
use Illuminate\Http\JsonResponse;

class MetaDataController extends Controller
{
    public function index(): JsonResponse
    {
        $cached = ApiPayloadCache::getCachedMetadataPayload();
        if ($cached !== null) {
            return response()->json(['data' => $cached]);
        }

        $payload = [
            'centre_details' => $this->pageOptionsByLayout('centre_detail'),
            'condition_details' => $this->pageOptionsByLayout('condition_details'),
            'timezones' => $this->staticOptions($this->timezones()),
            'time_slots' => $this->staticOptions($this->timeSlots()),
            'tests' => $this->staticOptions($this->tests()),
        ];

        ApiPayloadCache::storeMetadataPayload($payload);

        return response()->json(['data' => $payload]);
    }

    public function reviews(): JsonResponse
    {
        $cached = ApiPayloadCache::getCachedReviewsPayload();
        if ($cached !== null) {
            return response()->json(['data' => $cached]);
        }

        $payload = $this->googleReview();
        ApiPayloadCache::storeReviewsPayload($payload);

        return response()->json(['data' => $payload]);
    }

    /**
     * @return array<int, array{id:int,title:string}>
     */
    private function pageOptionsByLayout(string $layout): array
    {
        return Page::query()
            ->where('layout', $layout)
            ->where('is_active', true)
            ->orderBy('title')
            ->get(['id', 'title'])
            ->map(fn (Page $page) => [
                'id' => (int) $page->id,
                'title' => (string) $page->title,
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<int, string>  $items
     * @return array<int, array{id:string,title:string}>
     */
    private function staticOptions(array $items): array
    {
        return collect($items)
            ->map(fn (string $item) => [
                'id' => $item,
                'title' => $item,
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function timezones(): array
    {
        return [
            'Asia/Kolkata',
            'Asia/Dubai',
            'Asia/Singapore',
            'Asia/Bangkok',
            'Asia/Kathmandu',
            'Asia/Dhaka',
            'Asia/Colombo',
            'Asia/Kuala_Lumpur',
            'Europe/London',
            'Europe/Paris',
            'Europe/Berlin',
            'America/New_York',
            'America/Chicago',
            'America/Denver',
            'America/Los_Angeles',
            'Australia/Sydney',
        ];
    }

    /**
     * @return array<int, string>
     */
    private function timeSlots(): array
    {
        return [
            '7.00 - 9.00 AM',
            '9.00 - 11.00 AM',
            '11.00 AM - 1.00 PM',
            '1.00 - 3.00 PM',
            '3.00 - 5.00 PM',
            '5.00 - 7.00 PM',
            '7.00 - 9.00 PM',
        ];
    }

    /**
     * @return array<int, string>
     */
    private function tests(): array
    {
        return [
            'Pre-Surgery Package (all-in-one)',
            'CBC',
            'Blood Sugar / HbA1c',
            'Lipid Profile',
            'Liver / Kidney Function',
            'Thyroid',
            'Other / Multiple',
        ];
    }

    /**
     * @return array{reviews: array<int, array<string, mixed>>, counts: int, average: float|int, star_counts: array<int, array{star:int,count:int}>}
     */
    private function googleReview(): array
    {
        $reviews = [
            [
                'author_name' => 'Ananya Mehta',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=11',
                'rating' => 5,
                'relative_time_description' => '1 week ago',
                'text' => 'Dr. Laxman explained the treatment very clearly and the clinic staff handled everything professionally.',
            ],
            [
                'author_name' => 'Rohit Sharma',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=12',
                'rating' => 5,
                'relative_time_description' => '2 weeks ago',
                'text' => 'Excellent consultation experience. The diagnosis was detailed and the follow-up guidance was very helpful.',
            ],
            [
                'author_name' => 'Priya Nair',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=13',
                'rating' => 4,
                'relative_time_description' => '3 weeks ago',
                'text' => 'The doctor was patient and answered all my questions. Booking and front desk support were smooth.',
            ],
            [
                'author_name' => 'Vikram Singh',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=14',
                'rating' => 5,
                'relative_time_description' => '1 month ago',
                'text' => 'Very professional team and clean center. I felt confident throughout the consultation and treatment planning.',
            ],
            [
                'author_name' => 'Sneha Kapoor',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=15',
                'rating' => 4,
                'relative_time_description' => '1 month ago',
                'text' => 'Good overall experience. The doctor listened carefully and the prescribed plan was practical and reassuring.',
            ],
            [
                'author_name' => 'Arjun Patel',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=16',
                'rating' => 5,
                'relative_time_description' => '1 month ago',
                'text' => 'Highly recommended for anyone looking for expert care. Staff coordination and appointment management were excellent.',
            ],
            [
                'author_name' => 'Neha Verma',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=17',
                'rating' => 5,
                'relative_time_description' => '2 months ago',
                'text' => 'I appreciated how clearly everything was explained. The doctor was knowledgeable and approachable.',
            ],
            [
                'author_name' => 'Karan Malhotra',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=18',
                'rating' => 4,
                'relative_time_description' => '2 months ago',
                'text' => 'Consultation was on time and well managed. The center was organized and the staff were courteous.',
            ],
            [
                'author_name' => 'Pooja Desai',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=19',
                'rating' => 5,
                'relative_time_description' => '2 months ago',
                'text' => 'Very positive experience from start to finish. The treatment advice felt personalized and thoughtful.',
            ],
            [
                'author_name' => 'Rahul Bansal',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=20',
                'rating' => 5,
                'relative_time_description' => '3 months ago',
                'text' => 'Dr. Laxman and team were excellent. Communication was clear and the support after the visit was prompt.',
            ],
            [
                'author_name' => 'Ishita Roy',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=21',
                'rating' => 4,
                'relative_time_description' => '3 months ago',
                'text' => 'The doctor gave enough time during consultation and explained the next steps in simple language.',
            ],
            [
                'author_name' => 'Sandeep Joshi',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=22',
                'rating' => 5,
                'relative_time_description' => '3 months ago',
                'text' => 'One of the best healthcare experiences I have had. Friendly staff and excellent medical guidance.',
            ],
            [
                'author_name' => 'Meera Iyer',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=23',
                'rating' => 5,
                'relative_time_description' => '4 months ago',
                'text' => 'Everything was handled efficiently. The clinic environment was comfortable and professional.',
            ],
            [
                'author_name' => 'Aditya Kulkarni',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=24',
                'rating' => 4,
                'relative_time_description' => '4 months ago',
                'text' => 'Very good doctor consultation and clear communication. I would recommend this center to family and friends.',
            ],
            [
                'author_name' => 'Nisha Thomas',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=25',
                'rating' => 5,
                'relative_time_description' => '5 months ago',
                'text' => 'The team was responsive and helpful throughout. The doctor was kind and very experienced.',
            ],
            [
                'author_name' => 'Amit Chawla',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=26',
                'rating' => 5,
                'relative_time_description' => '5 months ago',
                'text' => 'Great service, minimal waiting time, and detailed treatment explanation. A very reassuring experience.',
            ],
            [
                'author_name' => 'Kavya Reddy',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=27',
                'rating' => 4,
                'relative_time_description' => '6 months ago',
                'text' => 'The doctor was attentive and the support staff coordinated the visit very well. Overall very satisfied.',
            ],
            [
                'author_name' => 'Harsh Gupta',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=28',
                'rating' => 5,
                'relative_time_description' => '6 months ago',
                'text' => 'Professional consultation with genuine attention to patient concerns. The entire process felt streamlined.',
            ],
            [
                'author_name' => 'Ritu Arora',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=29',
                'rating' => 5,
                'relative_time_description' => '7 months ago',
                'text' => 'Very impressed with the doctor’s expertise and the quality of patient care at the center.',
            ],
            [
                'author_name' => 'Manoj Sethi',
                'profile_photo_url' => 'https://i.pravatar.cc/100?img=30',
                'rating' => 4,
                'relative_time_description' => '8 months ago',
                'text' => 'Helpful staff, clean environment, and a well-structured consultation. I had a positive experience overall.',
            ],
        ];

        $counts = count($reviews);
        $average = $counts > 0 ? round(collect($reviews)->avg('rating'), 1) : 0;
        $starCounts = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0,
        ];

        foreach ($reviews as $review) {
            $bucket = (int) floor((float) ($review['rating'] ?? 0));
            $bucket = max(1, min(5, $bucket));
            $starCounts[$bucket]++;
        }

        return [
            'reviews' => $reviews,
            'counts' => $counts,
            'average' => $average,
            'star_counts' => collect($starCounts)
                ->map(fn (int $count, int $star) => [
                    'star' => $star,
                    'count' => $count,
                ])
                ->values()
                ->all(),
        ];
    }

}
