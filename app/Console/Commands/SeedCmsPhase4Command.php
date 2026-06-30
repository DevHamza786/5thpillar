<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Console\Command;

class SeedCmsPhase4Command extends Command
{
    protected $signature = 'cms:seed-phase4
                            {--force : Replace existing Phase 4 sections}';

    protected $description = 'Seed homepage, about-us, and management-team CMS sections (Phase 4)';

    public function handle(): int
    {
        $homePage = $this->ensureHomePage();
        $aboutPage = Page::query()->where('slug', 'about-us')->orWhere('view_key', 'about-us')->first();
        $teamPage = Page::query()->where('slug', 'management-team')->orWhere('view_key', 'management-team')->first();

        if ($aboutPage === null) {
            $this->warn('about-us page not found — skipping.');
        }

        if ($teamPage === null) {
            $this->warn('management-team page not found — skipping.');
        }

        $seeded = 0;

        foreach ($this->homeSections() as $section) {
            if ($this->seedSection($homePage, $section)) {
                $seeded++;
            }
        }

        if ($aboutPage !== null) {
            foreach ($this->aboutSections() as $section) {
                if ($this->seedSection($aboutPage, $section)) {
                    $seeded++;
                }
            }
        }

        if ($teamPage !== null) {
            foreach ($this->teamSections() as $section) {
                if ($this->seedSection($teamPage, $section)) {
                    $seeded++;
                }
            }
        }

        $this->info("Phase 4 seed complete — {$seeded} section(s) created or updated.");

        return self::SUCCESS;
    }

    private function ensureHomePage(): Page
    {
        return Page::query()->firstOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'view_key' => 'home',
                'status' => Page::STATUS_PUBLISHED,
                'status_ur' => Page::STATUS_PUBLISHED,
                'is_published' => true,
                'sort_order' => 0,
            ]
        );
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function seedSection(Page $page, array $payload): bool
    {
        $type = (string) $payload['section_type'];
        $role = (string) ($payload['settings']['role'] ?? 'append');
        $slot = (string) ($payload['settings']['slot'] ?? '');

        $query = $page->sections()->where('section_type', $type)->where('settings->role', $role);

        if ($role === 'home' && $slot !== '') {
            $query->where('settings->slot', $slot);
        }

        $existing = $query->first();

        if ($existing !== null && ! $this->option('force')) {
            $this->line("  skip {$page->slug} / {$type} ({$role}".($slot !== '' ? " / {$slot}" : '').')');

            return false;
        }

        if ($existing !== null) {
            $existing->delete();
        }

        $maxOrder = (int) $page->sections()->max('sort_order');

        PageSection::create([
            'page_id' => $page->id,
            'section_type' => $type,
            'heading' => $payload['heading'] ?? null,
            'content' => $payload['content'],
            'settings' => $payload['settings'],
            'is_enabled' => true,
            'sort_order' => $payload['sort_order'] ?? ($maxOrder + 1),
        ]);

        $this->line("  seeded {$page->slug} / {$type} ({$role}".($slot !== '' ? " / {$slot}" : '').')');

        return true;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function homeSections(): array
    {
        return [
            [
                'section_type' => 'home_popup',
                'sort_order' => 1,
                'content' => [
                    'image' => 'assets/images/home/cdc-web-banner.webp',
                    'alt' => 'Locate Life Insurance Policies With Ease. The Policy Finder Service is now live. SMS the CNIC number to 99833.',
                    'alt_ur' => '',
                    'aria_label' => 'Policy Finder Service announcement',
                    'aria_label_ur' => '',
                    'enabled' => true,
                ],
                'settings' => ['role' => 'home', 'slot' => 'popup'],
            ],
            [
                'section_type' => 'hero_slider',
                'sort_order' => 2,
                'content' => [
                    'slides' => [
                        [
                            'subtitle' => '',
                            'subtitle_ur' => '',
                            'title' => 'Hajj Made Easy',
                            'title_ur' => 'حج کی ادائیگی اب آسان',
                            'title_line2' => 'Aaiye Saath Chalein',
                            'title_line2_ur' => 'آئیے ساتھ چلیں',
                            'bg' => 'assets/images/imgi_2_xHome-Banner-2.webp.pagespeed.ic.I94T3v3MOH.webp',
                            'cta_text' => 'Hajj Planner',
                            'cta_text_ur' => 'حج پلانر',
                            'cta_link' => '/hajj-planner',
                        ],
                        [
                            'subtitle' => '',
                            'subtitle_ur' => '',
                            'title' => 'Highest initial paid-up ',
                            'title_ur' => 'سب سے زیادہ ابتدائی پیڈ اپ',
                            'title_line2' => 'capital of PKR 2 Billion',
                            'title_line2_ur' => '2 ارب روپے کا سرمایہ',
                            'bg' => 'assets/images/imgi_3_x1-2.webp.pagespeed.ic.-s8QkwEh_F.webp',
                            'cta_text' => 'Hajj Planner',
                            'cta_text_ur' => 'حج پلانر',
                            'cta_link' => '/hajj-planner',
                        ],
                    ],
                ],
                'settings' => ['role' => 'home', 'slot' => 'hero'],
            ],
            [
                'section_type' => 'home_about_banner',
                'sort_order' => 3,
                'content' => [
                    'kicker' => 'ABOUT',
                    'kicker_ur' => 'ہمارے بارے میں',
                    'title' => '5th Pillar',
                    'title_ur' => 'ففتھ پلر',
                    'title_line2' => 'Family Takaful',
                    'title_line2_ur' => 'فیملی تکافل',
                    'text' => '5th Pillar Family Takaful Limited is a new entrant into the Family Takaful sector of Pakistan which is supported by eminent business houses from Kuwait and Pakistan.',
                    'text_ur' => 'ففتھ پلر فیملی تکافل لمیٹڈ پاکستان کے فیملی تکافل سیکٹر میں ایک نیا شامل ہونے والا ادارہ ہے جسے کویت اور پاکستان کے نامور کاروباری اداروں کی حمایت حاصل ہے۔',
                    'bg_image' => 'assets/images/home/Sec-bg.webp',
                    'cta_text' => 'More About Us',
                    'cta_text_ur' => 'ہمارے بارے میں مزید',
                    'cta_link' => '/about-us',
                ],
                'settings' => ['role' => 'home', 'slot' => 'about'],
            ],
            [
                'section_type' => 'icon_cards',
                'sort_order' => 4,
                'content' => [
                    'heading' => 'Mission & Vision',
                    'heading_ur' => 'مشن اور وژن',
                    'cards' => [
                        [
                            'icon' => 'assets/images/home/1-New.webp',
                            'title' => 'Our Vision',
                            'title_ur' => 'ہمارا وژن',
                            'text' => 'Strengthen the financial capacity of our clients through innovative Shariah compliant Takaful products empowering them to achieve their cherished goals in life.',
                            'text_ur' => 'جدید شریعہ کے مطابق تکافل مصنوعات کے ذریعے اپنے کلائنٹس کی مالی صلاحیت کو مضبوط بنانا اور انہیں زندگی میں اپنے عزیز مقاصد حاصل کرنے کے لیے بااختیار بنانا۔',
                        ],
                        [
                            'icon' => 'assets/images/home/2-New.webp',
                            'title' => 'Our Mission',
                            'title_ur' => 'ہمارا مشن',
                            'text' => 'Provide structured Takaful savings and protection solutions specifically to Muslims in Pakistan to perform Hajj, the 5th Pillar of Islam.',
                            'text_ur' => 'پاکستان میں مسلمانوں کو حج، اسلام کا پانچواں ستون، ادا کرنے کے لیے خاص طور پر منظم تکافل بچت اور تحفظ کے حل فراہم کرنا۔',
                        ],
                        [
                            'icon' => 'assets/images/home/3-1-1.webp',
                            'title' => 'Value Chain',
                            'title_ur' => 'ویلیو چین',
                            'text' => '5th Pillar Takaful Limited provides a complete end-to-end value chain, supporting you from the moment you start saving till the moment you’ve performed Hajj and are back home.',
                            'text_ur' => 'ففتھ پلر تکافل لمیٹڈ ایک مکمل اینڈ ٹو اینڈ ویلیو چین فراہم کرتا ہے، بچت شروع کرنے کے لمحے سے لے کر حج ادا کرنے اور گھر واپسی تک۔',
                            'icon_class' => 'laravel-mv-card__icon--wide',
                        ],
                    ],
                ],
                'settings' => ['role' => 'home', 'slot' => 'mission'],
            ],
            [
                'section_type' => 'value_chain',
                'sort_order' => 5,
                'content' => [
                    'title' => '5th Pillar End-to-End Value Chain Explained',
                    'title_ur' => 'ففتھ پلر اینڈ ٹو اینڈ ویلیو چین کی وضاحت',
                    'image' => 'assets/images/home/Takaful-5th-Pillar-Animation-V4-1-1.gif',
                    'image_ur' => 'assets/images/Comp-1_2.gif',
                    'pdf_path' => 'assets/pdf/funds/5th-Pillar-End-To-End-Value-Chain-v1.5.pdf',
                    'pdf_path_ur' => 'assets/pdf/5th-Pillar-Urdu-Animation-1.pdf',
                    'button_label' => 'Download the Value Chain',
                    'button_label_ur' => 'ویلیو چین ڈاؤن لوڈ کریں',
                ],
                'settings' => ['role' => 'home', 'slot' => 'value_chain'],
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function aboutSections(): array
    {
        return [
            [
                'section_type' => 'intro_milestones',
                'sort_order' => 1,
                'content' => [
                    'lead' => '5th Pillar Family Takaful Limited is a new entrant into the Family Takaful sector of Pakistan which is supported by eminent business houses from Kuwait and Pakistan. The company has set industry records with remarkable milestones such as:',
                    'lead_ur' => 'ففتھ پلر فیملی تکافل لمیٹڈ پاکستان کے فیملی تکافل سیکٹر میں ایک نیا شامل ہونے والا ادارہ ہے جسے کویت اور پاکستان کے نامور کاروباری اداروں کی حمایت حاصل ہے۔ کمپنی نے صنعت کے ریکارڈ قائم کیے ہیں جن میں نمایاں سنگ میل شامل ہیں جیسے کہ:',
                    'items' => [
                        ['text' => 'Largest FDI in Takaful sector of Pakistan', 'text_ur' => 'پاکستان کے تکافل سیکٹر میں سب سے بڑی ایف ڈی آئی'],
                        ['text' => 'Foreign shareholders own 68% of 5th Pillar Takaful and 32% is held by Pakistani interests', 'text_ur' => 'غیر ملکی شیئر ہولڈرز ففتھ پلر تکافل کے 68 فیصد کے مالک ہیں اور 32 فیصد پاکستانی مفادات کے پاس ہے'],
                        ['text' => 'Largest initial paid up capital of Rs 2.00 billion in Pakistan’s Takaful sector history', 'text_ur' => 'پاکستان کی تکافل سیکٹر کی تاریخ میں سب سے زیادہ 2.00 ارب روپے کا ابتدائی پیڈ اپ سرمایہ'],
                        ['text' => 'Highest initial credit rating “A+ Stable outlook” from Pakistan Credit Rating Agency (PACRA)', 'text_ur' => 'پاکستان کریڈٹ ریٹنگ ایجنسی (PACRA) سے سب سے زیادہ ابتدائی کریڈٹ ریٹنگ "A+ مستحکم آؤٹ لک"'],
                        ['text' => 'Licensed by the SECP to underwrite Shariah compliant Family Takaful business in Pakistan', 'text_ur' => 'پاکستان میں شریعہ کے مطابق فیملی تکافل کے کاروبار کی ضمانت دینے کے لیے SECP سے لائسنس یافتہ'],
                        ['text' => 'State of the art IT platform to support business operations throughout the membership lifecycle', 'text_ur' => 'ممبرشپ کے لائف سائیکل کے دوران کاروباری کارروائیوں میں مدد کے لیے جدید ترین آئی ٹی پلیٹ فارم'],
                        ['text' => 'Upcoming customer engagement mobile app/web portal to provide 24/7 information and assistance to members from the comfort of their homes', 'text_ur' => 'ممبران کو ان کے گھروں کے آرام سے 24/7 معلومات اور مدد فراہم کرنے کے لیے آنے والی کسٹمر انگیجمنٹ موبائل ایپ/ویب پورٹل'],
                    ],
                ],
                'settings' => ['role' => 'primary'],
            ],
            [
                'section_type' => 'sponsor_band',
                'sort_order' => 10,
                'content' => [
                    'heading' => 'Our Sponsors',
                    'heading_ur' => 'ہمارے سپانسرز',
                    'intro' => '5th Pillar Family Takaful Limited is backed by distinguished sponsors:',
                    'intro_ur' => 'ففتھ پلر فیملی تکافل لمیٹڈ کو ممتاز سپانسرز کی حمایت حاصل ہے:',
                    'blocks' => [
                        ['strong' => 'Kuwait International Investment Holding Company (KIIC)', 'strong_ur' => 'کویت انٹرنیشنل انویسٹمنٹ ہولڈنگ ایجنسی (KIIC):', 'text' => ' is a leading investment company headquartered in Kuwait City, Kuwait. Founded in 1973, KIIC is owned by leading business houses of Kuwait including Government of Kuwait owned Kuwait Investment Authority (KIA) which owns 31.90% share of KIIC.', 'text_ur' => ' یہ ایک معروف سرمایہ کاری کمپنی ہے جس کا ہیڈ کوارٹر کویت سٹی، کویت میں واقع ہے۔ اس کمپنی کا قیام 1973 میں عمل میں آیا۔ کویت کے کئی معروف اور ممتاز کاروباری اداروں کے پاس KIIC کے ملکیتی حصص ہیں، جس میں سے کویت انویسٹمنٹ اتھارٹی (KIA) کمپنی کے 31.90% حصص کے ساتھ سرفہرست ہے۔'],
                        ['strong' => 'Al Bahar Group', 'strong_ur' => 'الفاطر گروپ کویت:', 'text' => ' formerly known as IFA Group, is a Kuwait-based company incorporated in 1974. Al Bahar Group is a multi-billion US dollar consortium of several listed Kuwaiti companies with diverse investments in Takaful, Hospitality, Financial Services, and Real Estate.', 'text_ur' => ' یہ بھی ایک کویتی کمپنی ہے اور 1974 میں اس کا قیام عمل میں آیا۔ البا گروپ کئی لسٹڈ کویتی کمپنیوں کے اشتراک سے وجود میں آئی ہے جن کی تکافل، میزبانی، مالیاتی خدمات اور رئیل اسٹیٹ کے شعبوں میں کئی بلین امریکی ڈالر پر مشتمل سرمایہ کاری ہے۔'],
                        ['strong' => '5th Pillar Holding DIFC Dubai, UAE', 'strong_ur' => 'ففتھ پلر ہولڈنگ DIFC دبئی، یو اے ای:', 'text' => ' is a special purpose company which has been set up by renowned business houses from Kuwait to develop Takaful companies and value chain in major Muslim populations countries.', 'text_ur' => ' یہ کویت کے معروف کاروباری اداروں کی جانب سے خصوصی مقصد کے تحت بنائی گئی کمپنی ہے تاکہ کثیر مسلم آبادی والے ممالک میں تکافل کمپنیز اور حج کی خدمات کے حوالے سے ایک منظم ترتیب تشکیل دی جا سکے۔'],
                        ['strong' => 'Muhammadi Family & Associates', 'strong_ur' => 'محمدی فیملی اینڈ ایسوسی ایٹس:', 'text' => ' include the Muhammadi Family who have been doing business in the Takaful/Insurance sector for over three generations in Pakistan.', 'text_ur' => ' اس میں محمدی فیملی بھی شامل ہے جو تین نسلوں سے پاکستان میں انشورنس سیکٹر کے بزنس میں پیش پیش ہے۔'],
                    ],
                    'closing' => 'This significant financial backing has allowed the company to invest in cutting-edge technology and develop innovative products in order to provide unparalleled customer service to its clients.',
                    'closing_ur' => 'اس اہم مالیاتی تعاون کی بدولت کمپنی اپنے صارفین کو جدید ٹیکنالوجی اور پراڈکٹ کے ذریعے بے مثال خدمات فراہم کرنے کے قابل ہو سکی ہے۔',
                    'bg_image' => '',
                    'bg_image_ur' => 'assets/images/inner-banners-2-64d5da6709c98-e1691742724167.webp',
                ],
                'settings' => ['role' => 'tertiary'],
            ],
            [
                'section_type' => 'image_band',
                'sort_order' => 11,
                'content' => [
                    'heading' => 'The Road Map To Our<br>End-to-End Value Chain',
                    'heading_ur' => 'ففتھ پلر کے مکمل مرتب نظام کی تفصیل',
                    'heading_html' => true,
                    'image' => 'assets/images/about/5th-Pillar-End-to-End-Value-Chain-1.webp',
                    'image_ur' => 'assets/images/Journey-Takaful-Urdu.webp',
                    'bg_image' => '',
                    'bg_image_ur' => 'assets/images/About-Background.webp',
                    'alt' => '5th Pillar End-to-End Value Chain',
                ],
                'settings' => ['role' => 'tertiary'],
            ],
            [
                'section_type' => 'text_band',
                'sort_order' => 12,
                'content' => [
                    'heading' => 'ReTakaful Arrangements',
                    'heading_ur' => 'ری تکافل کے انتظامات',
                    'text' => 'We have made ReTakaful arrangements with Hannover Re (world’s renowned ReTakaful Company) which allows us to enjoy the expertise of one of the most progressive institutions across the globe.',
                    'text_ur' => 'گروپ لائف تکافل پلان کے لیے ہم نے Hannover Re (دنیا کی نامی گرامی ری تکافل کمپنی) کے ساتھ ری تکافل کے انتظامات کیے ہیں۔ اس انتظام کی بدولت ہم دنیا بھر کے ایک ترقی یافتہ ادارے کے تجربے سے فائدہ اٹھاسکیں گے۔',
                    'layout' => 'retakaful',
                ],
                'settings' => ['role' => 'tertiary'],
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function teamSections(): array
    {
        $teamBase = 'assets/images/team';

        return [
            [
                'section_type' => 'team_grid',
                'sort_order' => 1,
                'content' => [
                    'members' => [
                        ['name' => 'Nasar us Samad Qureshi', 'subtitle' => 'Chief Executive Officer', 'subtitle_ur' => 'چیف ایگزیکٹو آفیسر', 'image' => $teamBase.'/nasar-us-samad-qureshi-edited-64d4b85147fc6-370x370.webp', 'linkedin' => 'https://www.linkedin.com/in/nasar-qureshi-090a0117/'],
                        ['name' => 'Muhammad Nasir Ali Syed', 'subtitle' => 'Executive Director - Operations', 'subtitle_ur' => 'ایگزیکٹو ڈائریکٹر - آپریشنز', 'image' => $teamBase.'/Nasir-Ali-Syed-updated-_2_-370x370.webp', 'linkedin' => 'https://www.linkedin.com/in/muhammad-nasir-ali-syed-b936834/'],
                        ['name' => 'Awais Hanif', 'subtitle' => 'Chief Financial Officer & Company Secretary', 'subtitle_ur' => 'چیف فنانشل آفیسر اور کمپنی سیکرٹری', 'image' => $teamBase.'/awais-hanif-edited-64d4b852b7435-370x370.webp', 'linkedin' => 'https://www.linkedin.com/in/awais-hanif-aca-91893572/'],
                        ['name' => 'Imran Irshad', 'subtitle' => 'Head of Group Operations', 'subtitle_ur' => 'ہیڈ آف گروپ آپریشنز', 'image' => $teamBase.'/imran-irshad-edited-flipped-64d4b8538cc37-370x370.webp', 'linkedin' => 'https://www.linkedin.com/in/imran-irshad-acii-flmi-1989a14a/'],
                        ['name' => 'Naseer Ahmed', 'subtitle' => 'Head of IT', 'subtitle_ur' => 'ہیڈ آف آئی ٹی', 'image' => $teamBase.'/naseer-ahmed-edited-1-64d4b853d570b-370x370.webp', 'linkedin' => 'https://www.linkedin.com/in/naseer-ahmed-8596383/'],
                        ['name' => 'Raja Muhammad Adnan Ali', 'subtitle' => 'National Sales Head', 'subtitle_ur' => 'نیشنل سیلز ہیڈ', 'image' => $teamBase.'/RajaAdnan640x640-1-370x370.jpg', 'linkedin' => 'https://www.linkedin.com/in/raja-muhammad-adnan-ali/'],
                        ['name' => 'Samrah Anis', 'subtitle' => 'Group Head Brand Strategy', 'subtitle_ur' => 'گروپ ہیڈ برانڈ اسٹریٹیجی', 'image' => $teamBase.'/WhatsApp-Image-2026-04-07-at-3.48.44-PM-370x370.jpeg', 'linkedin' => 'https://www.linkedin.com/in/samranez'],
                        ['name' => 'Muhammad Adnan', 'subtitle' => 'Manager – Actuarial Services', 'subtitle_ur' => 'منیجر - ایکچوئریل سروسز', 'image' => $teamBase.'/muhammad-adnan-edited-64d4b854f0b56-370x370.webp', 'linkedin' => 'https://www.linkedin.com/in/muhammad-adnan-31720/'],
                        ['name' => 'Kamran Ali Khan', 'subtitle' => 'Head of Underwriting', 'subtitle_ur' => 'ہیڈ آف انڈر رائٹنگ', 'image' => $teamBase.'/kamran-ali-khan-edited-64d4b8562d2e3-370x370.webp', 'linkedin' => 'https://www.linkedin.com/in/kamran-khan-31b819189/'],
                        ['name' => 'Aneel Akhtar', 'subtitle' => 'Head of Compliance', 'subtitle_ur' => 'ہیڈ آف کمپلائنس', 'image' => $teamBase.'/Aneel-Akhtar-370x370.webp', 'linkedin' => 'https://www.linkedin.com/in/aneel-akhtar-47467221/'],
                    ],
                ],
                'settings' => ['role' => 'primary'],
            ],
        ];
    }
}
