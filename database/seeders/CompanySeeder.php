<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\CompanyMeta;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::firstOrCreate(
            ['email' => 'info@example.com'],
            [
                'name' => '{Company}',
                'phone' => '02134567890',
                'whatsapp' => '02134567890',
                'website' => 'https://example.com',
                'short_description' => 'About company placeholder text.',
                'is_active' => 1,
            ],
        );

        $metaItems = [
            'secondary_contact' => '02134567891',
            'strip_text' => 'Sample strip text.',
            'medical_privacy_disclaimer' => 'Sample medical and privacy disclaimer.',
            'facebook_url' => 'https://facebook.com/example',
            'instagram_url' => 'https://instagram.com/example',
            'linkedin_url' => 'https://linkedin.com/company/example',
            'twitter_url' => 'https://twitter.com/example',
        ];

        foreach ($metaItems as $key => $value) {
            CompanyMeta::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'meta_key' => $key,
                ],
                [
                    'meta_value' => $value,
                ],
            );
        }
    }
}
