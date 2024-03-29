<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title') | Barbers.LT</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <meta name="verify-paysera" content="1a3926d5cb45e27e48d69e75a06c7ac0">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Cardo:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @yield('styles')
    <script type="text/javascript" charset="utf-8">
        var wtpQualitySign_projectId  = 235738;
        var wtpQualitySign_language   = "lt";
    </script>
    <script src="https://bank.paysera.com/new/js/project/wtpQualitySigns.js" type="text/javascript" charset="utf-8"></script>
</head>

<body>

@include('components.template.header')

<div class="container main">
    <main id="main" data-aos="fade" data-aos-delay="1500">
        @yield('content')
    </main>
</div>

@include('components.template.footer')

<a href="#" class="scroll-top d-flex align-items-center justify-content-center">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z"/>
    </svg>
</a>

@yield('scripts')
<!-- Accessibility Code for "bakalauras.donatasp.lt" --> <script> window.interdeal = { "sitekey": "df219888286fcc397acea17e375a0838", "Position": "Left", "Menulang": "LT", "domains": { "js": "https://cdn.equalweb.com/", "acc": "https://access.equalweb.com/" }, "btnStyle": { "vPosition": [ "80%", null ], "scale": [ "0.8", "0.8" ], "icon": { "type": 7, "shape": "semicircle", "outline": false } } }; (function(doc, head, body){ var coreCall = doc.createElement('script'); coreCall.src = interdeal.domains.js + 'core/4.5.2/accessibility.js'; coreCall.defer = true; coreCall.integrity = 'sha512-GVvo5c2SV7jwI6rUxQrAjIT6u0WHdJ+pbzRZyzfhOUGMaiKekbDs26ipItwEjD9jCvaV1qWbWurNBQGF5eY9aw=='; coreCall.crossOrigin = 'anonymous'; coreCall.setAttribute('data-cfasync', true ); body? body.appendChild(coreCall) : head.appendChild(coreCall); })(document, document.head, document.body); </script>

</body>

</html>
