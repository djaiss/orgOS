<title>{{ $title ?? config('app.name') }}</title>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />

<meta name="description" content="{{ config('app.description') }}" />
<link rel="icon" type="image/png" href="{{ asset('images/marketing/logo/30x30@2x.png') }}" sizes="60x60" />
