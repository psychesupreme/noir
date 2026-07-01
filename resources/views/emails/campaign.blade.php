<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign->subject }}</title>
    <style>
        body {
            background-color: #09090B;
            color: #E4E4E7;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }
        .wrapper {
            width: 100%;
            background-color: #09090B;
            padding: 40px 0;
        }
        .container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #121214;
            border: 1px solid #1F1F23;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }
        .header {
            padding: 40px 40px 20px 40px;
            text-align: center;
            border-bottom: 1px solid #1F1F23;
        }
        .logo-subtitle {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.4em;
            color: #D4AF37;
            text-transform: uppercase;
            margin-bottom: 4px;
            display: block;
        }
        .logo-title {
            font-family: Georgia, serif;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 0.3em;
            color: #FFFFFF;
            text-transform: uppercase;
            margin: 0;
        }
        .content {
            padding: 40px;
            font-size: 14px;
            line-height: 1.8;
            color: #D4D4D8;
            font-weight: 300;
        }
        .content h1, .content h2, .content h3 {
            font-family: Georgia, serif;
            color: #FFFFFF;
            font-weight: normal;
            margin-top: 0;
        }
        .content h1 {
            font-size: 24px;
            font-style: italic;
            border-left: 2px solid #D4AF37;
            padding-left: 16px;
            margin-bottom: 24px;
        }
        .divider {
            height: 1px;
            background-color: #1F1F23;
            margin: 30px 0;
        }
        .btn-container {
            text-align: center;
            margin: 30px 0 10px 0;
        }
        .btn {
            display: inline-block;
            background-color: #FFFFFF;
            color: #000000;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            padding: 14px 32px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .footer {
            padding: 30px 40px;
            background-color: #0C0C0E;
            text-align: center;
            border-top: 1px solid #1F1F23;
            font-size: 10px;
            color: #52525B;
            letter-spacing: 0.05em;
        }
        .footer a {
            color: #A1A1AA;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <span class="logo-subtitle">Atelier</span>
                <h1 class="logo-title">Noir &amp; Bloom</h1>
            </div>
            <div class="content">
                <h1>{{ $campaign->title }}</h1>
                
                {!! nl2br(e($campaign->content)) !!}
                
                <div class="divider"></div>
                <div class="btn-container">
                    <a href="{{ url('/') }}" class="btn">Explore Showroom</a>
                </div>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} Noir &amp; Bloom. All rights reserved.<br>
                You are receiving this because you signed up at our Atelier curation workspace.<br>
                <a href="{{ url('/profile-portal') }}">Manage notifications</a> &bull; <a href="#">Unsubscribe</a>
            </div>
        </div>
    </div>
</body>
</html>
