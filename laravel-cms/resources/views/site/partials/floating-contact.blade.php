@php
    $siteSettings = $siteSettings ?? [];
    $messengerLink = trim((string) ($siteSettings['social_messenger'] ?? 'https://www.hovi.com.vn/'));
    $zaloLink = trim((string) ($siteSettings['social_zalo'] ?? 'https://zalo.me/0988991635'));
@endphp

<div class="floating-contact" data-contact-widget>
    <div class="floating-contact__panel">
        @if (!empty($messengerLink))
            <a href="{{ $messengerLink }}" target="_blank" rel="noreferrer noopener" aria-label="Messenger">
                <img src="/theme/assets/icons/messenger.svg" alt="Messenger">
            </a>
        @endif

        @if (!empty($zaloLink))
            <a href="{{ $zaloLink }}" target="_blank" rel="noreferrer noopener" aria-label="Zalo">
                <img src="/theme/assets/icons/zalo.svg" alt="Zalo">
            </a>
        @endif
    </div>
    <button class="floating-contact__toggle" type="button" data-contact-toggle aria-label="Mở liên hệ nhanh">
        <img src="/theme/assets/icons/buttonChat.svg" alt="">
    </button>
</div>
