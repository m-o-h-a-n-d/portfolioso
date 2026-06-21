<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $companyName }} - Contact Submission</title>

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

        /* Title Bar (Vodafone Style) */
        .title-bar { background-color: #143A75; color: #ffffff; padding: 12px 40px; font-size: 16px; font-weight: 600; letter-spacing: 0.5px; }

        /* Content Section */
        .content-padding { padding: 40px; background-color: #ffffff; }
        .salutation { font-size: 20px; color: #0A1F44; margin-bottom: 20px; }
        .intro-text { font-size: 16px; color: #4A5568; line-height: 1.6; margin-bottom: 30px; }

        /* Card Style */
        .info-card { background-color: #ffffff; border: 1px solid #E2E8F0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(10, 31, 68, 0.05); }
        .card-header { background-color: #F8FAFC; border-bottom: 1px solid #E2E8F0; padding: 15px 20px; font-weight: 700; color: #0A1F44; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }
        .card-body { padding: 20px; }
        .card-row { margin-bottom: 20px; }
        .card-row-last { margin-bottom: 0; }
        .label { font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px; }
        .value { font-size: 16px; color: #0A1F44; line-height: 1.5; margin: 0; }
        .message-box { background-color: #F1F5F9; border-radius: 8px; padding: 20px; margin-top: 10px; border-left: 4px solid #F58220; }

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
        }
    </style>
</head>

<body>
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
                                    New Contact Form Submission
                                </td>
                            </tr>

                            <!-- Main Content Section -->
                            <tr>
                                <td class="content-padding">
                                    <p class="salutation">Hello,</p>
                                    <p class="intro-text">You have received a new message through your portfolio contact form. Below are the details of the submission:</p>

                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" class="info-card">
                                        <tr class="card-header">
                                            <td style="padding: 15px 20px;">Submission Details</td>
                                        </tr>
                                        <tr>
                                            <td class="card-body">
                                                <div class="card-row">
                                                    <div class="label">Sender Name</div>
                                                    <p class="value"><strong>{{ $contactUs->name }}</strong></p>
                                                </div>
                                                <div class="card-row">
                                                    <div class="label">Sender Email</div>
                                                    <p class="value"><a href="mailto:{{ $contactUs->email }}" style="color: #143A75; text-decoration: none; font-weight: 600;">{{ $contactUs->email }}</a></p>
                                                </div>
                                                <div class="card-row">
                                                    <div class="label">Subject</div>
                                                    <p class="value">{{ $contactUs->subject }}</p>
                                                </div>
                                                <div class="card-row-last">
                                                    <div class="label">Message</div>
                                                    <div class="message-box">
                                                        <p class="value" style="color: #334155; line-height: 1.6;">{!! nl2br(e($contactUs->message)) !!}</p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- CTA Button -->
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="margin-top: 40px;">
                                        <tr>
                                            <td align="left">
                                                @php
                                                    $replySubject = rawurlencode('Re: ' . ($contactUs->subject ?? 'Your message'));
                                                    $replyTo = $contactUs->email;
                                                @endphp
                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" class="button-td">
                                                    <tr>
                                                        <td>
                                                            <a href="mailto:{{ $replyTo }}?subject={{ $replySubject }}" class="button-a">Reply to Sender</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>

                                    <p style="margin-top: 30px; font-size: 14px; color: #718096;">
                                        Kind regards,<br>
                                        <strong>{{ $companyName }} System</strong>
                                    </p>
                                </td>
                            </tr>

                            <!-- Footer Section -->
                            <tr>
                                <td class="footer-padding">
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" class="footer-table">
                                        <tr>
                                            <td align="center">
                                                <p style="margin: 0 0 6px 0; font-weight: 700; color: #0A1F44; font-size: 14px;">{{ $companyShortName ?? 'IAET' }}</p>
                                                <p style="margin: 0 0 14px 0;">
                                                    <a href="{{ config('app.url') }}" style="color: #143A75; text-decoration: none; font-weight: 600; font-size: 13px;">{{ config('app.url') }}</a>
                                                </p>
                                                <p style="margin: 0; font-size: 12px; color: #A0AEC0;">
                                                    &copy; {{ date('Y') }} {{ $companyShortName ?? 'IAET' }}. All rights reserved.
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
