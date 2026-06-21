<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $companyName ?? config('app.name') }} - OTP Code</title>

    <!--[if mso]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->

    <style>
        /* Base resets */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; display: block; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #F5F7FA; }

        /* Typography */
        body, table, td, p, a, li { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }

        /* Custom Styles */
        .wrapper { width: 100%; table-layout: fixed; background-color: #F5F7FA; padding-bottom: 40px; }
        .webkit { max-width: 600px; background-color: #ffffff; margin: 0 auto; }
        .outer { margin: 0 auto; width: 100%; max-width: 600px; border-spacing: 0; color: #0A1F44; }

        /* Hero Banner (Doctor photo, full width, no text overlay) */
        .hero-banner { background-color: #0A1F44; line-height: 0; font-size: 0; }

        /* Title Bar */
        .title-bar { background-color: #143A75; color: #ffffff; padding: 12px 40px; font-size: 16px; font-weight: 600; letter-spacing: 0.5px; }

        /* Content Section */
        .content-padding { padding: 40px; background-color: #ffffff; }
        .salutation { font-size: 20px; color: #0A1F44; margin-bottom: 8px; }
        .intro-text { font-size: 16px; color: #4A5568; line-height: 1.6; margin-bottom: 30px; }

        /* Card Style */
        .info-card { background-color: #ffffff; border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(10, 31, 68, 0.05); }
        .card-header { background-color: #F8FAFC; border-bottom: 1px solid #E2E8F0; padding: 15px 20px; font-weight: 700; color: #0A1F44; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }
        .card-body { padding: 28px 20px; }
        .label { font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; text-align: center; }

        /* OTP Display */
        .otp-code { display: inline-block; font-size: 32px; font-weight: 800; letter-spacing: 10px; color: #0A1F44; background-color: #FFF3E8; padding: 16px 24px; border-radius: 10px; border: 1px solid #F8D9B8; font-family: Arial, Helvetica, sans-serif; }
        .otp-hint { font-size: 13px; color: #94A3B8; margin-top: 14px; text-align: center; }

        .note-box { background-color: #F1F5F9; border-radius: 8px; padding: 18px 20px; margin-top: 24px; border-left: 4px solid #F58220; }
        .warn-box { background-color: #FEF2F2; border-radius: 8px; padding: 16px 20px; margin-top: 14px; border-left: 4px solid #E53E3E; }

        /* Button (Vodafone Style) */
        .button-td { border-radius: 4px; background: #F58220; transition: all 0.3s ease; }
        .button-a { border: 1px solid #F58220; border-radius: 4px; color: #ffffff !important; display: inline-block; font-size: 16px; font-weight: 700; line-height: 1; padding: 18px 40px; text-align: center; text-decoration: none; }
        .button-td:hover { background: #E06D00 !important; }

        /* Footer */
        .footer-table { background-color: #F5F7FA; color: #64748B; font-size: 13px; line-height: 1.6; }
        .footer-padding { padding: 28px 40px; }

        /* Responsive */
        @media screen and (max-width: 600px) {
            .hero-padding { padding: 35px 20px !important; }
            .title-bar { padding: 10px 20px !important; }
            .content-padding { padding: 30px 20px !important; }
            .footer-padding { padding: 22px 20px !important; }
            .button-a { width: 100% !important; box-sizing: border-box !important; }
            .otp-code { font-size: 26px !important; letter-spacing: 6px !important; padding: 14px 18px !important; }
        }
    </style>
</head>

<body>
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">
        Your OTP code is {{ $otp }}. Expires in {{ $minutes ?? 10 }} minutes.
    </div>

    <div role="article" aria-roledescription="email" lang="en" style="background-color: #F5F7FA;">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" class="wrapper">
            <tr>
                <td align="center">
                    <div class="webkit">
                        <!--[if (gte mso 9)|(IE)]>
                        <table width="600" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation">
                        <tr>
                        <td>
                        <![endif]-->
                        <table class="outer" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation">

                            <!-- Hero Banner Section -->
                            <tr>
                                <td class="hero-banner">
                                    <img src="{{ $doctorImageUrl ?? asset('images/doctor-mohamed-sobhy.png') }}" width="600" alt="{{ $doctorName ?? 'Dr. Mohamed Sobhy' }}" style="width: 100%; max-width: 600px; display: block;">
                                </td>
                            </tr>

                            <!-- Title Bar -->
                            <tr>
                                <td class="title-bar">
                                    One-Time Verification Code
                                </td>
                            </tr>

                            <!-- Main Content Section -->
                            <tr>
                                <td class="content-padding">
                                    <p class="salutation">Hello,</p>
                                    <p class="intro-text">We need to verify your email address. Please enter the following code to complete the verification process.</p>

                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" class="info-card">
                                        <tr class="card-header">
                                            <td style="padding: 15px 20px;">One-Time Password (OTP)</td>
                                        </tr>
                                        <tr>
                                            <td class="card-body" align="center">
                                                <div class="label">Your Verification Code</div>
                                                <span class="otp-code">{{ $otp }}</span>
                                                <p class="otp-hint">Do not share this code with anyone.</p>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="note-box">
                                        <p style="margin:0; font-size:14px; color:#334155; line-height:1.6;">
                                            Please note this code will expire after <strong>{{ $minutes ?? 10 }} minutes</strong>.
                                        </p>
                                    </div>

                                    <div class="warn-box">
                                        <p style="margin:0; font-size:13px; color:#9B2C2C; line-height:1.6;">
                                            Never share your OTP or password with anyone, even support staff. If you didn't request this code, you can safely ignore this email.
                                        </p>
                                    </div>

                                    @if (!empty($actionUrl))
                                        <!-- CTA Button -->
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="margin-top: 40px;">
                                            <tr>
                                                <td align="left">
                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" class="button-td">
                                                        <tr>
                                                            <td>
                                                                <a href="{{ $actionUrl }}" class="button-a">Continue</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    @endif

                                    <p style="margin-top: 30px; font-size: 14px; color: #718096;">
                                        This message was sent to: <strong>{{ $email ?? 'your email' }}</strong><br>
                                        Kind regards,<br>
                                        <strong>{{ $companyName ?? config('app.name') }} Team</strong>
                                    </p>
                                </td>
                            </tr>

                            <!-- Footer Section -->
                            <tr>
                                <td class="footer-padding">
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" class="footer-table">
                                        <tr>
                                            <td align="center">
                                                <p style="margin: 0 0 6px 0; font-weight: 700; color: #0A1F44; font-size: 14px;">{{ $companyName ?? config('app.name') }}</p>
                                                <p style="margin: 0 0 14px 0;">
                                                    <a href="{{ config('app.url') }}" style="color: #143A75; text-decoration: none; font-weight: 600; font-size: 13px;">{{ config('app.url') }}</a>
                                                </p>
                                                <p style="margin: 0; font-size: 12px; color: #A0AEC0;">
                                                    &copy; {{ date('Y') }} {{ $companyName ?? config('app.name') }}. All rights reserved.
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        </table>
                        <!--[if (gte mso 9)|(IE)]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
