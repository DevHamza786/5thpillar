<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class AboutUsUrduSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::where('slug', 'about-us')->first();
        if (!$page) return;

        // Hardcoded Urdu content for About Us to ensure it matches the screenshots and replaces all English
        $urduContent = '
[vc_row full_width="stretch_row" content_placement="top" hide_bg_image_on_tablet="" hide_bg_image_on_mobile="" css=".vc_custom_1691740105072{margin-top: 2em !important;padding-top: 5em !important;padding-bottom: 5em !important;background-image: url(' . asset('assets/images/inner-banners-2-64d5da6709c98-e1691742724167.webp') . ') !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}" el_class="shaha_about_quote"]
[vc_column width="2/3" icons_position="left"]
[trx_sc_content size="none" number_position="br" title_style="default" link_style="default" class="extra-about-quote-mr-negative"]
[trx_sc_title title_style="default" link_style="default" title="ہمارے سپانسرز"]
[vc_column_text css=".vc_custom_1760955435887{margin-top: 2em !important;margin-bottom: 9% !important;}"]
<p><strong>کویت انٹرنیشنل انویسٹمنٹ ہولڈنگ ایجنسی (KIIC):</strong> یہ ایک معروف سرمایہ کاری کمپنی ہے جس کا ہیڈ کوارٹر کویت سٹی، کویت میں واقع ہے۔ اس کمپنی کا قیام 1973 میں عمل میں آیا۔ کویت کے کئی معروف اور ممتاز کاروباری اداروں کے پاس KIIC کے ملکیتی حصص ہیں، جس میں سے کویت انویسٹمنٹ اتھارٹی (KIA) کمپنی کے 31.90% حصص کے ساتھ سرفہرست ہے۔</p>
<p><strong>الفاطر گروپ کویت:</strong> یہ بھی ایک کویتی کمپنی ہے اور 1974 میں اس کا قیام عمل میں آیا۔ البا گروپ کئی لسٹڈ کویتی کمپنیوں کے اشتراک سے وجود میں آئی ہے جن کی تکافل، میزبانی، مالیاتی خدمات اور رئیل اسٹیٹ کے شعبوں میں کئی بلین امریکی ڈالر پر مشتمل سرمایہ کاری ہے۔ اس گروپ کے چار براعظموں یعنی مشرقی وسطی، افریقہ، یورپ اور متحدہ امریکہ میں مختلف ہوٹلز کے تقریباً 11,500 فائیو اسٹار کمرے ہیں، جو میزبانی کے شعبے میں بے مثال خدمات فراہم کر رہے ہیں۔</p>
<p><strong>ففتھ پلر ہولڈنگ DIFC دبئی، یو اے ای:</strong> یہ کویت کے معروف کاروباری اداروں کی جانب سے خصوصی مقصد کے تحت بنائی گئی کمپنی ہے تاکہ کثیر مسلم آبادی والے ممالک میں تکافل کمپنیز اور حج کی خدمات کے حوالے سے ایک منظم ترتیب تشکیل دی جا سکے۔ جو کہ پاکستان سے آغاز کے ساتھ ساتھ اس مماثلت کے انتظامات بنگلہ دیش، انڈونیشیا، ترکی اور مصر میں بھی شروع کیے جائیں گے۔</p>
<p><strong>محمدی فیملی اینڈ ایسوسی ایٹس:</strong> اس میں محمدی فیملی بھی شامل ہے جو تین نسلوں سے پاکستان میں انشورنس سیکٹر کے بزنس میں پیش پیش ہے۔</p>
<p>اس اہم مالیاتی تعاون کی بدولت کمپنی اپنے صارفین کو جدید ٹیکنالوجی اور پراڈکٹ کے ذریعے بے مثال خدمات فراہم کرنے کے قابل ہو سکی ہے۔ ہمارے معزز اسپانسرز کے پاس ہمارے شرکاء تکافل کے لیے پاکستان اور دیگر منتخب ممالک سے انتہائی خصوصی سہولت اور آسانی کے ساتھ حج کی سعادت حاصل کرنے کے حوالے سے تمام ضروری مالی وسائل، تجربہ اور مہارت موجود ہے۔</p>
[/vc_column_text]
[/trx_sc_content]
[/vc_column]
[/vc_row]

[vc_row full_width="stretch_row" content_placement="middle" hide_bg_image_on_tablet="" hide_bg_image_on_mobile="" css=".vc_custom_1704194687871{background-image: url(' . asset('assets/images/About-Background.webp') . ') !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}"]
[vc_column]
[vc_custom_heading text="ففتھ پلر کے مکمل مرتب نظام کی تفصیل" font_container="tag:h2|text_align:center|color:%23b4a55c" google_fonts="font_family:Raleway%3A100%2C200%2C300%2Cregular%2C500%2C600%2C700%2C800%2C900|font_style:700%20bold%20regular%3A700%3Anormal"]
<div style="text-align:center; padding: 20px 0;"><img src="' . asset('assets/images/Journey-Takaful-Urdu.webp') . '" style="width:100%; height:auto;" /></div>
[/vc_column]
[/vc_row]

[vc_row full_width="stretch_row" hide_bg_image_on_tablet="" hide_bg_image_on_mobile="" css=".vc_custom_1691397797722{padding-top: 5em !important;padding-bottom: 5em !important;}" el_class="shaha_about_quote"]
[vc_column width="7/12" icons_position="left"]
[trx_sc_content size="none" number_position="br" title_style="default" link_style="default" class="extra-about-quote-mr-negative"]
[trx_sc_title title_style="default" link_style="default" title="ری تکافل کے انتظامات"]
[vc_column_text css=".vc_custom_1691409142759{margin-top: 8% !important;margin-bottom: 9% !important;}"]
<p>گروپ لائف تکافل پلان کے لیے ہم نے Hannover Re (دنیا کی نامی گرامی ری تکافل کمپنی) کے ساتھ ری تکافل کے انتظامات کیے ہیں۔ اس انتظام کی بدولت ہم دنیا بھر کے ایک ترقی یافتہ ادارے کے تجربے سے فائدہ اٹھاسکیں گے۔</p>
[/vc_column_text]
[/trx_sc_content]
[/vc_column]
[/vc_row]';

        $page->update([
            'content_ur' => $urduContent,
            'title_ur' => 'ہمارے بارے میں',
            'hero_title_ur' => 'ہمارے بارے میں',
            'masthead_bg_ur' => asset('assets/images/inner-banners-2-64d5da6709c98-e1691742724167.webp'),
            'is_published' => true,
        ]);

        echo "SUCCESS: Urdu content updated for page: " . $page->slug . "\n";
        echo "Content length: " . strlen($urduContent) . "\n";
    }
}

