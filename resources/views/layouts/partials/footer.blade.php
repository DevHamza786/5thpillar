<footer class="footer_wrap footer_custom footer_custom_17 footer_custom_footer-informed scheme_dark">
    <div class="laravel-footer-main">
        <div class="content_wrap">
            <div class="laravel-footer-brand">
                <img src="{{ $logoUrl }}" alt="5th Pillar Takaful Logo" width="230" height="81">
            </div>

            <div class="laravel-footer-grid">
                <div class="footer-widget widget widget_nav_menu">
                    <h2 class="widgettitle">{{ __('Quick Links') }}</h2>
                    <ul class="menu">
                        <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                        <li><a href="{{ route('pages.show', ['slug' => 'about-us']) }}">{{ __('About Us') }}</a></li>
                        <li><a href="{{ route('pages.show', ['slug' => 'knowledge-centre']) }}">{{ __('Knowledge Centre') }}</a></li>
                        <li><a href="{{ route('pages.show', ['slug' => 'online-complaint-form']) }}">{{ __('Complaint Form') }}</a></li>
                        <li><a href="{{ route('pages.show', ['slug' => 'careers']) }}">{{ __('Careers') }}</a></li>
                        <li><a href="{{ asset('uploads/2026/2026/04/Active-agents.pdf') }}" target="_blank" rel="noopener noreferrer">{{ __('List of Authorized Agents') }}</a></li>
                        <li><a href="{{ asset('uploads/2024/2024/01/How-to-Launch-Complaints-and-Grievances-amended-as-per-18-1-24-2.pdf') }}" target="_blank" rel="noopener noreferrer">{{ __('Grievance Handling Mechanism') }}</a></li>
                        <li><a href="{{ asset('uploads/2024/2024/12/Compliance-Certificate-24-For-website.pdf') }}" target="_blank" rel="noopener noreferrer">{{ __('Compliance Certificate') }}</a></li>
                    </ul>
                </div>

                <div class="footer-widget widget widget_text">
                    <h2 class="widgettitle">{{ __('Get in Touch with Us') }}</h2>
                    <div class="textwidget">
                        <div class="laravel-footer-contact-item">
                            <img src="{{ asset('uploads/2023/2023/09/Person.png') }}" alt="Grievance contact icon" width="30" height="30">
                            <div>
                                <strong>{{ __('For grievance handling') }}: {{ __('Mr. Iftikhar Ahmed') }}</strong><br>
                                <strong>{{ __('Email') }}:</strong> <a href="mailto:grievance@5thpillartakaful.com">grievance@5thpillartakaful.com</a><br>
                                <strong>{{ __('Contact') }}:</strong> 021-36492847
                            </div>
                        </div>
                        <div class="laravel-footer-contact-item">
                            <img src="{{ asset('uploads/2023/2023/08/email-64d5d36f518ed.webp') }}" alt="Email icon" width="30" height="30">
                            <div><strong>{{ __('Email') }}:</strong> <a href="mailto:info@5thpillartakaful.com">info@5thpillartakaful.com</a></div>
                        </div>
                        <div class="laravel-footer-contact-item">
                            <img src="{{ asset('uploads/2023/2023/08/phone-64d5d36eb8358.webp') }}" alt="Phone icon" width="30" height="30">
                            <div><strong>{{ __('UAN') }}:</strong> 021-111-786-573</div>
                        </div>
                        <div class="laravel-footer-contact-item">
                            <img src="{{ asset('uploads/2023/2023/08/location-64d5d36ec0e98.webp') }}" alt="Karachi office icon" width="30" height="30">
                            <div>{{ __('Office No. SF-01 to SF-06, 2nd Floor, Emerald Mall, Near Do-Talwar, Clifton, Block 5, Karachi, Pakistan.') }}</div>
                        </div>
                        <div class="laravel-footer-contact-item">
                            <img src="{{ asset('uploads/2023/2023/08/location-64d5d36ec0e98.webp') }}" alt="Lahore office icon" width="30" height="30">
                            <div>{{ __('Lahore Branch: Office No. 618 - 620, 6th Floor, Al-Hafeez Executive, 30-C-3, Gulberg III, Lahore, Pakistan.') }}</div>
                        </div>
                        <div class="laravel-footer-hours">
                            <h5 class="laravel-footer-hours__title">{{ __('Business Working Hours') }}:</h5>
                            <p class="laravel-footer-hours__text">
                                @if(app()->getLocale() === 'ur')
                                    {{ __('Monday to Friday') }}
                                @else
                                    {{ __('Monday to Friday') }}: 9:00 AM to 5:00 PM
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="footer-widget widget widget_text">
                    <h2 class="widgettitle">{{ __('Follow us on') }}</h2>
                    <div class="textwidget laravel-footer-socials">
                        <p>
                            <img src="{{ asset('uploads/2023/2023/08/facebook-64d5d4a809c09.webp') }}" alt="Facebook" width="30" height="30">
                            <a class="footer-facebook" href="https://www.facebook.com/5thPillarTakaful/" target="_blank" rel="noopener noreferrer">{{ __('Facebook') }}</a>
                        </p>
                        <p>
                            <img src="{{ asset('uploads/2023/2023/08/linkedin-64d5d4a80599e.webp') }}" alt="Linkedin" width="30" height="30">
                            <a class="footer-linkedin" href="https://www.linkedin.com/company/5th-pillar-family-takaful-limited/" target="_blank" rel="noopener noreferrer">{{ __('Linkedin') }}</a>
                        </p>
                        <p>
                            <img src="{{ asset('uploads/2023/2023/08/instagram-64d5d4a764e3b.webp') }}" alt="Instagram" width="30" height="30">
                            <a class="footer-instagram" href="https://www.instagram.com/5thpillartakaful/" target="_blank" rel="noopener noreferrer">{{ __('Instagram') }}</a>
                        </p>
                        <p>
                            <img src="{{ asset('uploads/2023/2023/08/youtube-64d5d4a780967.webp') }}" alt="YouTube" width="30" height="30">
                            <a class="footer-youtube" href="https://www.youtube.com/@5thPillarFamilyTakafulLimited" target="_blank" rel="noopener noreferrer">{{ __('YouTube') }}</a>
                        </p>
                    </div>
                </div>

                <div class="footer-widget widget widget_text">
                    <div class="laravel-footer-badges">
                        <div class="laravel-footer-fio">
                            <img src="{{ asset('uploads/2017/2017/07/Logo-2-1-370x103.png') }}" alt="Federal Insurance Ombudsman Pakistan" width="370" height="103">
                            <p class="laravel-footer-fio-link">
                                <a href="https://fio.gov.pk/" target="_blank" rel="nofollow noopener noreferrer">https://fio.gov.pk/</a>
                            </p>
                        </div>
                        <a href="http://jamapunji.pk/" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('uploads/2017/2017/07/jamapunji_parkash_parmar-1.png') }}" alt="Jamapunji" width="461" height="109">
                        </a>
                        <a href="https://sdms.secp.gov.pk/" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('uploads/2017/2017/07/SECP-Logo-Footer.webp') }}" alt="SECP SDMS" width="419" height="85">
                        </a>
                        <p class="laravel-footer-disclaimer">
                            {{ __('DISCLAIMER: In case your complaint has not been properly redressed by us, you may lodge your complaint with Securities and Exchange Commission of Pakistan (the “SECP”). SECP will entertain only those complaints which were first directly requested to be redressed by the company and the company failed to redress the same.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="laravel-footer-bottom">
                <p>Copyright © {{ now()->year }} by <a href="{{ route('home') }}">5th Pillar Family Takaful</a>. All Rights Reserved. Powered by <a href="https://synitedigital.com/" target="_blank" rel="noopener noreferrer">Synite Digital.</a></p>
                <p>Last Update: {{ now()->format('F j, Y') }}</p>
            </div>
        </div>
    </div>
</footer>
