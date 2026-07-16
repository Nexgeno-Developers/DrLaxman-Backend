<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Post;
use App\Models\SeoMeta;
use App\Services\ApiPayloadCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    // Keep these resolver lists aligned with resources/views/backend/pages/edit-layouts/*
    // whenever a page layout adds a new upload, post-category, or page-reference meta field.
    private array $uploadMetaKeys = [
        'banner_images',
        'single_image',
        'multiple_image',
        'single_document',
        'multiple_document',
        'single_video',
        'multiple_video',
        'image',
        'icon',
        'breadcrumb_image',
        'overview_image',
        'about_treatment_image',
        'benefits_image',
        'treatment_journey_secondary_image',
        'banner_desktop',
        'banner_mobile',
        'global_scale_image',
        'knowledge_center_image',
        'sustainability_image',
        'business_services_image',
    ];

    private array $post_category_MetaKeys = [
        'post_block_categories',
    ];

    private array $pageSectionMetaKeys = [
        'conditions',
        'centres',
    ];

    // JSON/repeater keys used by current page layouts.
    private array $dynamicJsonMetaKeys = [
        'dynamic_field',
        'select_multiple',
        'checkbox_options',
        'types_stages_items',
        'treatment_items',
        'treatment_recommendation',
        'why_treat_description',
        'recovery_timeline_items',
        'faq_items',
        'treatment_journey_items',
        'global_scale_year_items',
        'global_scale_stat_items',
        'knowledge_center_items',
        'sustainability_items',
        'business_services_items',
    ];

    /**
     * Fetch by ID
     */
    public function showById(int $id, Request $request): JsonResponse
    {
        $autofetch = $request->get('autofetch');

        $cached = ApiPayloadCache::getCachedPagePayload($id, $autofetch);
        if ($cached !== null) {
            return response()->json(['data' => $cached]);
        }

        $page = Page::query()
            ->with('meta')
            ->where('id', $id)
            ->where('is_active', true)
            ->first();

        if (!$page) {
            return response()->json([
                'error' => [
                    'message' => 'Page not found',
                    'code' => 'PAGE_NOT_FOUND',
                ],
            ], 404);
        }

        $data = $this->pagePayload($page, $autofetch);
        ApiPayloadCache::storePagePayload((int) $page->id, $autofetch, $data);

        return response()->json(['data' => $data]);
    }

    /**
     * Fetch by slug
     */
    public function showBySlug(string $slug, Request $request): JsonResponse
    {
        $normalizedSlug = trim($slug, '/');
        $autofetch = $request->get('autofetch');

        $pageId = Page::query()
            ->where('slug', $normalizedSlug)
            ->where('is_active', true)
            ->value('id');

        if ($pageId === null) {
            return response()->json([
                'error' => [
                    'message' => 'Page not found',
                    'code' => 'PAGE_NOT_FOUND',
                ],
            ], 404);
        }

        $pageId = (int) $pageId;

        $cached = ApiPayloadCache::getCachedPagePayload($pageId, $autofetch);
        if ($cached !== null) {
            return response()->json(['data' => $cached]);
        }

        $page = Page::query()
            ->with('meta')
            ->where('id', $pageId)
            ->where('is_active', true)
            ->first();

        if (!$page) {
            return response()->json([
                'error' => [
                    'message' => 'Page not found',
                    'code' => 'PAGE_NOT_FOUND',
                ],
            ], 404);
        }

        $data = $this->pagePayload($page, $autofetch);
        ApiPayloadCache::storePagePayload($pageId, $autofetch, $data);

        return response()->json(['data' => $data]);
    }

    /**
     * Main Payload Builder
     */
    private function pagePayload(Page $page, $additionalParams = null): array
    {
        $seoMeta = SeoMeta::query()
            ->where('slug', $page->slug)
            ->first();

        //additionalParams

        $autofetchSections = [];
        if(!empty($additionalParams)){
            $additionalParams = explode(',', $additionalParams);
            
            foreach($additionalParams as $param):
                // if($param === 'services') {
                //     $ids = Page::query()->whereIn('layout', ['marketing_services', 'technical_services'])->where('is_active', true)->pluck('id')->toArray();
                //     $autofetchSections['services'] = page_details_from_ids($ids);
                // }

                // if($param === 'industries') {
                //     $ids = Page::query()->whereIn('layout', ['product_industry_detail'])->where('is_active', true)->pluck('id')->toArray();
                //     $autofetchSections['industries'] = page_details_from_ids($ids);
                // }                

                // if($param === 'sustainabilities') {
                //     $ids = Page::query()->whereIn('layout', ['sustainability_1', 'sustainability_2', 'sustainability_3','sustainability_4','sustainability_5','sustainability_6'])->where('is_active', true)->pluck('id')->toArray();
                //     $autofetchSections['sustainabilities'] = page_details_from_ids($ids);
                // }                

                // if($param === 'product_categories') {
                //     $ids = Page::query()->whereIn('layout', ['product_category_detail_1','product_category_detail_2','product_category_detail_3','product_category_detail_4','product_category_detail_5'])->where('is_active', true)->pluck('id')->toArray();
                //     $autofetchSections['product_categories'] = page_details_from_ids($ids);
                // }   
                
                // if($param === 'marketing_services') {
                //     $ids = Page::query()->whereIn('layout', ['marketing_service_detail'])->where('is_active', true)->pluck('id')->toArray();
                //     $autofetchSections['marketing_services'] = page_details_from_ids($ids);
                // }     

                // if($param === 'technical_services') {
                //     $ids = Page::query()->whereIn('layout', ['technical_service_detail'])->where('is_active', true)->pluck('id')->toArray();
                //     $autofetchSections['technical_services'] = page_details_from_ids($ids);
                // } 

                // if($param === 'related_products') {
                //     $currentPageId = $page->id;
                //     $currentPageLayout = $page->layout;
                //     $ids = Page::query()->whereIn('layout', ['products'])->where('is_active', true)
                //         // ->whereHas('meta', function ($q) use ($currentPageLayout) {
                //         //     $q->where('meta_key', 'relation_category')
                //         //     ->where('meta_value', $currentPageLayout);
                //         // })
                //         ->pluck('id')->toArray();
                //     $autofetchSections['related_products'] = page_details_from_ids($ids);
                // }    

                // if($param === 'sustainable_products') {
                //     $ids = Page::query()->whereIn('layout', ['products'])->where('is_active', true)
                //         ->whereHas('meta', function ($q) {
                //             $q->where('meta_key', 'relation_category')
                //             ->where('meta_value', 17); // Assuming 17 is the ID for the sustainable category
                //         })->pluck('id')->toArray();
                //     $autofetchSections['sustainable_products'] = page_details_from_ids($ids);
                // }     
                
                // if($param === 'lamistraw_products') {
                //     $ids = Page::query()->whereIn('layout', ['products'])->where('is_active', true)
                //         ->whereHas('meta', function ($q) {
                //             $q->where('meta_key', 'relation_category')
                //             ->where('meta_value', 7); // Assuming 6 is the ID for the lamistraw category
                //         })->pluck('id')->toArray();
                //     $autofetchSections['lamistraw_products'] = page_details_from_ids($ids);
                // }                 

                // if($param === 'featured_products') {
                //     $ids = Page::query()->whereIn('layout', ['products'])->where('is_active', true)
                //             ->whereHas('meta', function ($q) {
                //                 $q->where('meta_key', 'relation_featured')
                //                 ->where('meta_value', 'yes');
                //             })->pluck('id')->toArray();
                //     $autofetchSections['featured_products'] = page_details_from_ids($ids);
                // }  
                
                // if ($param === 'standard_products') {
                //     $currentPageId = $page->id;

                //     $ids = Page::query()
                //         ->whereIn('layout', ['products'])
                //         ->where('is_active', 1)
                //         ->whereHas('meta', function ($q) use ($currentPageId) {
                //             $q->where('meta_key', 'relation_category')
                //             ->where('meta_value', $currentPageId);
                //         })
                //         ->whereHas('meta', function ($q) {
                //             $q->where('meta_key', 'relation_type')
                //             ->where('meta_value', 'standard');
                //         })
                //         ->pluck('id')
                //         ->toArray();

                //     $autofetchSections['standard_products'] = page_details_from_ids($ids);
                // } 
                
                // if ($param === 'premium_products') {
                //     $currentPageId = $page->id;

                //     $ids = Page::query()
                //         ->whereIn('layout', ['products'])
                //         ->where('is_active', 1)
                //         ->whereHas('meta', function ($q) use ($currentPageId) {
                //             $q->where('meta_key', 'relation_category')
                //             ->where('meta_value', $currentPageId);
                //         })
                //         ->whereHas('meta', function ($q) {
                //             $q->where('meta_key', 'relation_type')
                //             ->where('meta_value', 'premium');
                //         })
                //         ->pluck('id')
                //         ->toArray();

                //     $autofetchSections['premium_products'] = page_details_from_ids($ids);
                // }                
                
                // if($param === 'latest_insights') {
                //     $categoryId = 1;
                //     $postsQuery = Post::query()
                //         ->where('is_active', true)
                //         ->whereHas('categories', function ($q) use ($categoryId) {
                //             $q->where('categories.id', $categoryId);
                //         })
                //         ->with('meta')
                //         ->orderByDesc('published_at')
                //         ->limit(8);

                //     if (auth()->user()?->company_id) {
                //         $postsQuery->where('company_id', auth()->user()->company_id);
                //     }

                //     $latestPosts = $postsQuery->get();

                //     $autofetchSections['latest_insights'] = $latestPosts->map(function (Post $post) {
                //         $summary = $post->meta->firstWhere('meta_key', 'short_summary')?->meta_value;
                //         if (!filled($summary)) {
                //             $summary = $post->meta->firstWhere('meta_key', 'summary')?->meta_value;
                //         }

                //         $date = $post->meta->firstWhere('meta_key', 'date')?->meta_value;
                //         $time = $post->meta->firstWhere('meta_key', 'time')?->meta_value;

                //         return [
                //             'id' => $post->id,
                //             'title' => $post->title,
                //             'slug' => $post->slug,
                //             'featured_image' => filled($post->featured_image)
                //                 ? uploaded_asset_details_from_ids($post->featured_image)
                //                 : null,
                //             'summary' => $summary,
                //             'date' => filled($date) ? $date : null,
                //             'time' => filled($time) ? $time : null,
                //         ];
                //     })->values()->all();
                // }  
                
                // if($param === 'latest_news') {
                //     $categoryId = 18;
                //     $postsQuery = Post::query()
                //         ->where('is_active', true)
                //         ->whereHas('categories', function ($q) use ($categoryId) {
                //             $q->where('categories.id', $categoryId);
                //         })
                //         ->with('meta')
                //         ->orderByDesc('published_at')
                //         ->limit(8);

                //     if (auth()->user()?->company_id) {
                //         $postsQuery->where('company_id', auth()->user()->company_id);
                //     }

                //     $latestPosts = $postsQuery->get();

                //     $autofetchSections['latest_news'] = $latestPosts->map(function (Post $post) {
                //         $summary = $post->meta->firstWhere('meta_key', 'short_summary')?->meta_value;
                //         if (!filled($summary)) {
                //             $summary = $post->meta->firstWhere('meta_key', 'summary')?->meta_value;
                //         }

                //         $date = $post->meta->firstWhere('meta_key', 'date')?->meta_value;
                //         $time = $post->meta->firstWhere('meta_key', 'time')?->meta_value;

                //         return [
                //             'id' => $post->id,
                //             'title' => $post->title,
                //             'slug' => $post->slug,
                //             'featured_image' => filled($post->featured_image)
                //                 ? uploaded_asset_details_from_ids($post->featured_image)
                //                 : null,
                //             'summary' => $summary,
                //             'date' => filled($date) ? $date : null,
                //             'time' => filled($time) ? $time : null,
                //         ];
                //     })->values()->all();
                // }                 
            endforeach;

        }

        return [
            'id' => $page->id,
            'slug' => $page->slug,
            'language' => $page->language,
            'title' => $page->title,
            'content' => $page->content,
            'is_active' => (bool) $page->is_active,
            'layout' => $page->layout,
            'company_id' => $page->company_id,

            'meta' => $page->meta
                ->mapWithKeys(function ($m) {

                    $value = $m->meta_value;

                    if (in_array($m->meta_key, $this->dynamicJsonMetaKeys)) {
                        $value = $this->resolveDynamicJson($value);
                    } else {
                        $value = $this->resolveMetaValue($m->meta_key, $value);
                    }

                    return [$m->meta_key => $value];
                })
                ->all(),

            'seo' => [
                'title'       => filled($seoMeta?->meta_title) ? $seoMeta->meta_title : $page->seo_title,
                'description' => filled($seoMeta?->meta_description) ? $seoMeta->meta_description : $page->seo_description,
                'keywords'    => filled($seoMeta?->meta_keywords) ? $seoMeta->meta_keywords : $page->seo_keywords,
                'schema'              => $seoMeta?->schema_json,
                'canonical_url'       => $seoMeta?->canonical_url,
                'robots_index'        => $seoMeta?->robots_index,
                'robots_follow'       => $seoMeta?->robots_follow,
                'og_title'            => $seoMeta?->og_title,
                'og_description'      => $seoMeta?->og_description,
                'og_image'            => filled($seoMeta?->og_image) ? uploaded_asset_details_from_ids($seoMeta->og_image) : null,
                'twitter_title'       => $seoMeta?->twitter_title,
                'twitter_description' => $seoMeta?->twitter_description,
                'twitter_image'       => filled($seoMeta?->twitter_image) ? uploaded_asset_details_from_ids($seoMeta->twitter_image) : null,
                'sitemap_priority'    => $seoMeta?->sitemap_priority,
            ],
            'autofetch' => $autofetchSections,
        ];
    }

    /**
     * Resolve normal meta values
     */
    private function resolveMetaValue(string $key, $value)
    {
        if (in_array($key, $this->uploadMetaKeys)) {
            return filled($value)
                ? uploaded_asset_details_from_ids($value)
                : null;
        }

        if (in_array($key, $this->pageSectionMetaKeys)) {
            return page_details_from_ids($value);
        }

        if (in_array($key, $this->post_category_MetaKeys)) {
            return post_category_details_from_ids($value);
        }

        return $value;
    }

    /**
     * Resolve dynamic JSON meta
     */
    private function resolveDynamicJson($json)
    {
        $decoded = json_decode($json, true);

        if (!is_array($decoded)) {
            return $json;
        }

        foreach ($decoded as $key => $values) {

            if (!is_array($values)) continue;

            foreach ($values as $index => $val) {
                $decoded[$key][$index] = $this->resolveMetaValue($key, $val);
            }
        }

        return $decoded;
    }
}
