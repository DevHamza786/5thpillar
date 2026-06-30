<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class HomepageUrduSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::where('slug', 'home')->first();
        if (!$page) return;

        $c = $page->content;

        // Replace Slider with the requested GIF for Urdu homepage
        $gif_url = asset('assets/images/Comp-1_2.gif');
        $c = str_replace('[smartslider3 slider="2"]', '<div class="urdu-homepage-hero" style="text-align:center; padding: 20px 0;"><img src="'.$gif_url.'" style="width:100%; max-width: 1280px; height:auto; border-radius: 10px;" /></div>', $c);

        // Translations
        $translations = [
            'Prayer Times' => 'نماز کے اوقات',
            '5th Pillar Family Takaful' => 'ففتھ پلر فیملی تکافل',
            'Read More' => 'مزید پڑھیں',
            'ABOUT' => 'ہمارے بارے میں',
            '5th Pillar<br />' => 'ففتھ پلر<br />',
            'Mission & Vision' => 'مشن اور وژن',
            'Our Vision' => 'ہمارا وژن',
            'Our Mission' => 'ہمارا مشن',
            'Value Chain' => 'ویلیو چین',
            '5th Pillar End-to-End Value Chain Explained' => 'ففتھ پلر اینڈ ٹو اینڈ ویلیو چین کی وضاحت',
            'Download the Value Chain' => 'ویلیو چین ڈاؤن لوڈ کریں',
            'Shariah Advisor' => 'شریعہ ایڈوائزر',
            'News & Events' => 'خبریں اور واقعات',
            'More About Us' => 'ہمارے بارے میں مزید',
        ];

        foreach ($translations as $en => $ur) {
            $c = str_replace($en, $ur, $c);
        }

        // Descriptions
        $c = str_replace(
            'Strengthen the financial capacity of our clients through innovative Shariah compliant Takaful products empowering them to achieve their cherished goals in life.',
            'جدید شریعہ کے مطابق تکافل مصنوعات کے ذریعے اپنے کلائنٹس کی مالی صلاحیت کو مضبوط بنانا اور انہیں زندگی میں اپنے عزیز مقاصد حاصل کرنے کے لیے بااختیار بنانا۔',
            $c
        );

        $c = str_replace(
            'Provide structured Takaful savings and protection solutions specifically to Muslims in Pakistan to perform Hajj, the 5th Pillar of Islam.',
            'پاکستان میں مسلمانوں کو حج، اسلام کا پانچواں ستون، ادا کرنے کے لیے خاص طور پر منظم تکافل بچت اور تحفظ کے حل فراہم کرنا۔',
            $c
        );

        $c = str_replace(
            '5th Pillar Family Takaful Limited is a new entrant into the Family<br />\r\nTakaful sector of Pakistan which is supported by eminent<br />\r\nbusiness houses from Kuwait and Pakistan.',
            'ففتھ پلر فیملی تکافل لمیٹڈ پاکستان کے فیملی تکافل سیکٹر میں ایک نیا شامل ہونے والا ادارہ ہے جسے کویت اور پاکستان کے نامور کاروباری اداروں کی حمایت حاصل ہے۔',
            $c
        );

        $page->update([
            'content_ur' => $c,
            'title_ur' => 'ہوم',
            'hero_title_ur' => 'ففتھ پلر فیملی تکافل',
        ]);
    }
}
