<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($urls as $url)
    <url>
        <loc>{{ data_get($url, 'loc') }}</loc>
        @php
            $lastmod = data_get($url, 'lastmod');
            if ($lastmod instanceof \Carbon\CarbonInterface) {
                $lastmod = $lastmod->toAtomString();
            } elseif ($lastmod instanceof \DateTimeInterface) {
                $lastmod = $lastmod->format(DATE_ATOM);
            } elseif (is_string($lastmod) && trim($lastmod) !== '') {
                try {
                    $lastmod = \Carbon\Carbon::parse($lastmod)->toAtomString();
                } catch (\Throwable $exception) {
                    $lastmod = null;
                }
            } else {
                $lastmod = null;
            }
        @endphp
        @if (!empty($lastmod))
        <lastmod>{{ $lastmod }}</lastmod>
        @endif
        @if (!empty(data_get($url, 'changefreq')))
        <changefreq>{{ data_get($url, 'changefreq') }}</changefreq>
        @endif
        @if (!empty(data_get($url, 'priority')))
        <priority>{{ data_get($url, 'priority') }}</priority>
        @endif
    </url>
@endforeach
</urlset>
