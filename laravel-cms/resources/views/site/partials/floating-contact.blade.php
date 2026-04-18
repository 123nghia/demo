@php
    $siteSettings = $siteSettings ?? [];
    $messengerLink = trim((string) ($siteSettings['social_messenger'] ?? 'https://hovi.com.vn/'));
    $zaloLink = trim((string) ($siteSettings['social_zalo'] ?? 'https://zalo.me/0988991635'));
@endphp

<div class="floating-contact" data-contact-widget>
    <div class="floating-contact__panel">
        @if (!empty($messengerLink))
            <a href="{{ $messengerLink }}" target="_blank" rel="noreferrer noopener" aria-label="Messenger">
                <img src="/theme/assets/icons/messenger.svg" alt="Messenger" loading="lazy" decoding="async">
            </a>
        @endif

        @if (!empty($zaloLink))
            <a href="{{ $zaloLink }}" target="_blank" rel="noreferrer noopener" aria-label="Zalo">
                <img src="/theme/assets/icons/zalo.svg" alt="Zalo" loading="lazy" decoding="async">
            </a>
        @endif
    </div>
    <button class="floating-contact__toggle" type="button" data-contact-toggle aria-label="Mở liên hệ nhanh">
        <svg viewBox="0 0 63 63" xmlns="http://www.w3.org/2000/svg" role="presentation" aria-hidden="true" focusable="false">
            <circle cx="31.5" cy="31.5" r="24" fill="#fff" />
            <path
                d="M38.64 20.77H23.36c-.7 0-1.27.57-1.27 1.27v14.75l2.55-2.55a2.55 2.55 0 0 1 1.8-.75h12.2c.7 0 1.27-.57 1.27-1.27V22.04c0-.7-.57-1.27-1.27-1.27Zm-15.27-1.27a2.55 2.55 0 0 0-2.55 2.54v16.29c0 .57.7.85 1.09.45l3.63-3.63c.24-.24.56-.37.9-.37h12.2a2.55 2.55 0 0 0 2.54-2.55V22.04a2.55 2.55 0 0 0-2.54-2.54Z"
                fill="#444" fill-rule="evenodd" />
            <circle cx="29.15" cy="27.64" r="1.27" fill="#444" />
            <circle cx="34.24" cy="27.64" r="1.27" fill="#444" />
            <circle cx="39.33" cy="27.64" r="1.27" fill="#444" />
        </svg>
    </button>
</div>
