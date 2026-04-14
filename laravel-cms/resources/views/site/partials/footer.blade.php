@php
    $siteSettings = $siteSettings ?? [];

    $brandName = trim((string) ($siteSettings['site_name'] ?? 'HOVI VIỆT NAM'));
    $footerLogo = trim((string) ($siteSettings['footer_logo'] ?? '/theme/logofooter.png'));

    $companyName = trim((string) ($siteSettings['footer_company_name'] ?? 'CÔNG TY TNHH HOVI VIỆT NAM'));
    $taxCode = trim((string) ($siteSettings['footer_tax_code'] ?? ''));
    $address = trim((string) ($siteSettings['footer_address'] ?? ''));
    $website = trim((string) ($siteSettings['footer_website'] ?? ''));
    $email = trim((string) ($siteSettings['footer_email'] ?? ''));
    $phone = trim((string) ($siteSettings['footer_phone'] ?? ''));
    $copyright = trim((string) ($siteSettings['footer_copyright'] ?? ''));

    $websiteLabel = preg_replace('#^https?://#i', '', $website);
    $websiteLabel = rtrim((string) $websiteLabel, '/');

    $socialLinks = [
        ['label' => 'Facebook', 'url' => $siteSettings['social_facebook'] ?? null],
        ['label' => 'TikTok', 'url' => $siteSettings['social_tiktok'] ?? null],
        ['label' => 'YouTube', 'url' => $siteSettings['social_youtube'] ?? null],
    ];
@endphp

<footer class="site-footer">
    <div class="site-footer__brand">
        <div class="site-footer__brand-top">
            <img src="{{ $footerLogo }}" alt="{{ $brandName }}" class="site-footer__logo">
            <div class="site-footer__brand-text">
                <p class="site-footer__brand-name">{{ mb_strtoupper($brandName) }}</p>
                <p class="site-footer__brand-since">EST 2021</p>
            </div>
        </div>
        <div class="social-badges" aria-label="Mạng xã hội">
            @foreach ($socialLinks as $social)
                @continue(empty($social['url']))

                <a href="{{ $social['url'] }}" target="_blank" rel="noreferrer noopener" aria-label="{{ $social['label'] }}">
                    @if ($social['label'] === 'Facebook')
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M13.5 22v-8h2.7l.4-3.2h-3.1V8.8c0-.9.2-1.5 1.5-1.5H16.7V4.4c-.3 0-.9-.1-1.9-.1-3 0-4.8 1.8-4.8 5.1v1.4H7.3V14H10v8h3.5Z" fill="currentColor" />
                        </svg>
                    @elseif ($social['label'] === 'TikTok')
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M14.8 3c.3 2.1 1.5 3.8 3.6 4.2v2.7c-1.5 0-2.9-.5-4-1.5v5.3c0 3-2.2 5.3-5.4 5.3-2.9 0-5-2.1-5-4.9 0-3.1 2.4-5.2 5.6-4.9v2.8c-1.4-.2-2.8.5-2.8 2 0 1.2 1 2 2.1 2 1.5 0 2.1-1.2 2.1-2.6V3h3.8Z" fill="currentColor" />
                        </svg>
                    @else
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M21.2 7.4c-.2-1-.9-1.8-1.9-2-1.7-.4-7.3-.4-7.3-.4s-5.6 0-7.3.4c-1 .2-1.7 1-1.9 2C2.4 9.1 2.4 12 2.4 12s0 2.9.4 4.6c.2 1 .9 1.8 1.9 2 1.7.4 7.3.4 7.3.4s5.6 0 7.3-.4c1-.2 1.7-1 1.9-2 .4-1.7.4-4.6.4-4.6s0-2.9-.4-4.6ZM10.2 15.5v-7l6 3.5-6 3.5Z" fill="currentColor" />
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    <div class="footer-divider"></div>
    <p class="site-footer__title">{{ $companyName }}</p>
    @if (!empty($taxCode))
        <p class="site-footer__tax">MST: {{ $taxCode }}</p>
    @endif

    <div class="footer-grid">
        @if (!empty($address))
            <article class="footer-item">
                <img src="/theme/assets/icons/icon-footer-1.webp" alt="">
                <p>{{ $address }}</p>
            </article>
        @endif

        @if (!empty($website))
            <article class="footer-item">
                <img src="/theme/assets/icons/icon-footer-2.webp" alt="">
                <p>
                    <a href="{{ $website }}" target="_blank" rel="noreferrer noopener">
                        {{ $websiteLabel ?: $website }}
                    </a>
                </p>
            </article>
        @endif

        @if (!empty($email))
            <article class="footer-item">
                <img src="/theme/assets/icons/icon-footer-3.webp" alt="">
                <p><a href="mailto:{{ $email }}">{{ $email }}</a></p>
            </article>
        @endif

        @if (!empty($phone))
            <article class="footer-item">
                <img src="/theme/assets/icons/icon-footer-4.webp" alt="">
                <p><a href="tel:{{ preg_replace('/\D+/', '', $phone) }}">{{ $phone }}</a></p>
            </article>
        @endif
    </div>

    @if (!empty($copyright))
        <p class="site-footer__tax mt-3">{{ $copyright }}</p>
    @endif
</footer>
