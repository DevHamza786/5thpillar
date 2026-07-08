<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DigitalSavingsPageSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Register the Digital Savings landing page so PageController::show()
     * resolves the `pages.static.digital-savings` template by slug.
     */
    public function run(): void
    {
        Page::query()->updateOrCreate(
            ['slug' => 'digital-savings'],
            [
                'title' => 'Digital Savings',
                'hero_title' => 'Digital Savings',
                'view_key' => null,
                'meta_title' => 'Digital Savings - 5th Pillar Family Takaful',
                'meta_description' => 'Start your Hajj and Umrah savings journey digitally with the 5th Pillar Niyyat app. Secure, Shariah-compliant Takaful enrolment through SECP\'s digital framework.',
                'meta_keywords' => 'digital savings, Niyyat app, 5th Pillar Family Takaful, Hajj savings, Umrah savings, digital Takaful, SECP digital framework',
                // Urdu locale (/urdu/digital-savings). Body copy is translated by
                // the site's Google Translate layer; these drive the masthead + SEO.
                'title_ur' => 'ڈیجیٹل بچت',
                'hero_title_ur' => 'ڈیجیٹل بچت',
                'meta_title_ur' => 'ڈیجیٹل بچت - ففتھ پلر فیملی تکافل',
                'meta_description_ur' => 'نیّت ایپ کے ذریعے اپنے حج اور عمرہ کی بچت کا سفر ڈیجیٹل انداز میں شروع کریں۔ محفوظ اور شریعت کے مطابق تکافل انرولمنٹ، ایس ای سی پی کے ڈیجیٹل فریم ورک کے تحت۔',
                'status' => Page::STATUS_PUBLISHED,
                'status_ur' => Page::STATUS_PUBLISHED,
                'is_published' => true,
                'sort_order' => 0,
            ],
        );
    }
}
