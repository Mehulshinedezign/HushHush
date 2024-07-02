<!DOCTYPE html>
<html>

<head>
    <meta content="en-us" http-equiv="content-language">
    <meta content="text/html; charset=utf-8" http-equiv="content-type">
    <title>nudora</title>

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');

        body {
            margin: 0;
            padding: 0;
            background-color: #cccccc;
            background: #cccccc;
            font-family: "Inter", sans-serif !important;
        }

        .ExternalClass,
        .ExternalClass div,
        .ExternalClass font,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass td,
        h1,
        img {
            line-height: 100%;
        }

        h1,
        h2 {
            display: block;
            font-style: normal;
            font-weight: 700;
        }

        #outlook a {
            padding: 0;
        }

        .ExternalClass,
        .ReadMsgBody {
            width: 100%;
        }

        a,
        blockquote,
        body,
        li,
        p,
        table,
        td {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0;
            mso-table-rspace: 0;
            width: auto;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            outline: 0;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        #bodyCell,
        #bodyTable,
        body {
            height: 100% !important;
            margin: 0;
            padding: 0;
            width: 100% !important;
        }

        #bodyCell {
            padding: 20px;
        }

        #templateContainer {
            border: 1px solid #ddd;
            background-color: #fff;
        }

        #bodyTable,
        body {
            background-color: #f7f7f7;
        }

        h1 {
            color: #202020;
            font-size: 26px;
            letter-spacing: normal;
            text-align: left;
            margin: 0 0 10px;
        }

        h2 {
            color: #151515;
            font-size: 40px;
            line-height: 100%;
            letter-spacing: normal;
            text-align: center;
            margin: 0 0 20px;
        }

        h3,
        h4 {
            display: block;
            font-style: italic;
            font-weight: 400;
            letter-spacing: normal;
            text-align: left;
            margin: 0 0 10px;
            line-height: 100%;
        }

        h3 {
            color: #606060;
            font-size: 16px;
        }

        h4 {
            color: #606060;
            font-size: 14px;
        }

        .headerContent {
            background-color: #f8f8f8;
            border-bottom: 1px solid #ddd;
            color: #505050;
            font-size: 20px;
            font-weight: 700;
            line-height: 100%;
            text-align: left;
            vertical-align: middle;
            padding: 0;
        }

        .bodyContent,
        .footerContent {
            line-height: 150%;
            text-align: left;
        }

        .footerContent {
            text-align: center;
        }

        .bodyContent pre {
            padding: 15px;
            background-color: #444;
            color: #f8f8f8;
            border: 0;
        }

        .bodyContent pre code {
            white-space: pre;
            word-break: normal;
            word-wrap: normal;
        }

        .bodyContent table {
            margin: 10px 0;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        .bodyContent table th {
            padding: 4px 10px;
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            font-weight: 700;
            text-align: center;
        }

        .bodyContent table td {
            padding: 3px 8px;
            border: 1px solid #ddd;
        }

        .table-responsive {
            border: 0;
        }

        .bodyContent a {
            word-break: break-all;
        }

        .headerContent a .yshortcuts,
        .headerContent a:link,
        .headerContent a:visited {
            color: #1f5d8c;
            font-weight: 400;
            text-decoration: underline;
        }

        #headerImage {
            height: auto;
            max-width: 600px;
            padding: 20px;
        }

        #templateBody {
            background-color: #fff;
        }

        .bodyContent {
            color: #505050;
            font-size: 14px;
            padding: 20px;
        }

        .bodyContent a .yshortcuts,
        .bodyContent a:link,
        .bodyContent a:visited {
            color: #1f5d8c;
            font-weight: 400;
            text-decoration: underline;
        }

        .bodyContent a:hover {
            text-decoration: none;
        }

        .bodyContent img {
            display: inline;
            height: auto;
            max-width: 560px;
        }

        .footerContent {
            color: grey;
            font-size: 12px;
            padding: 20px;
        }

        .footerContent a .yshortcuts,
        .footerContent a span,
        .footerContent a:link,
        .footerContent a:visited {
            color: #606060;
            font-weight: 400;
            text-decoration: underline;
        }

        ul li::marker {
            font-weight: 700;
            color: #000;
        }

        @media only screen and (max-width: 640px) {

            h1,
            h2,
            h3,
            h4 {
                line-height: 100% !important;
            }
        }

        @media only screen and (max-width: 640px) {
            #templateContainer {
                max-width: 600px !important;
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 640px) {

            #templateContainer,
            body {
                width: 100% !important;
            }

        }

        @media only screen and (max-width: 640px) {

            a,
            blockquote,
            body,
            li,
            p,
            table,
            td {
                -webkit-text-size-adjust: none !important;
            }

        }

        @media only screen and (max-width: 640px) {
            body {
                min-width: 100% !important;
            }

        }

        @media only screen and (max-width: 640px) {
            #bodyCell {
                padding: 10px !important;
            }

            p,
            a,
            strong,
            span,
            td {
                font-size: 14px !important;
            }

        }

        @media only screen and (max-width: 640px) {
            h1 {
                font-size: 24px !important;
            }

        }

        @media only screen and (max-width: 640px) {
            h2 {
                font-size: 20px !important;
            }

        }

        @media only screen and (max-width: 640px) {
            h3 {
                font-size: 18px !important;
            }

        }

        @media only screen and (max-width: 640px) {

            h4,
            h5 {
                font-size: 16px !important;
            }

        }

        @media only screen and (max-width: 640px) {
            #templatePreheader {
                display: none !important;
            }

        }

        @media only screen and (max-width: 640px) {
            .headerContent {
                font-size: 20px !important;
                line-height: 125% !important;
            }

        }

        @media only screen and (max-width: 640px) {
            .footerContent {
                font-size: 14px !important;
                line-height: 115% !important;
            }

        }

        @media only screen and (max-width: 640px) {
            .footerContent a {
                display: block !important;
            }

        }

        @media only screen and (max-width: 640px) {
            .hide-mobile {
                display: none;
            }

        }

        @media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
            table[class=body] .ctr-img {
                width: 100%;
            }

        }

        @media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
            .body {
                max-width: 100%;
                width: 100%;
            }

        }

        @media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
            .m-width {
                max-width: 100%;
                width: 100%;
            }

        }
    </style>
</head>

<body>
    <!-- Start of main container -->
    <table width="750px" class="body" bgcolor="#f7f7f7" cellpadding="0" cellspacing="0"
        style="width:100%;margin:0;padding:0 20px;">
        <tr>
            <td style="padding: 0px 0 0;">
                <table bgcolor="#ffffff" class="m-width" width="740px" cellpadding="0" cellspacing="0"
                    style="max-width:750px; background-color: #fff; border-collapse:collapse;  font-weight:normal;font-size:14px;line-height:17pt;color:#444444;margin:0 auto;">
                    <tr>
                        <td bgcolor="#E8E5DE" valign="top" style="padding-top: 20px; padding-bottom:20px">
                            {{-- <a target="_blank" href="javascript:;"
                                style="margin-left:auto;margin-right:auto;text-align:center;">
                                <img alt="image" src="{{ asset('img/logo.png') }}" width="100"
                                    style="max-width:100px;padding: 0; border: 0;display:block;margin:0 auto;"
                                    border="0">
                            </a> --}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table cellpadding="0" cellspacing="0"
                                style="width:100%;max-width:100%; background:#ffffff;" align="center">
                                <tr>
                                    <td colspan="3">
                                        <table cellpadding="0" cellspacing="0"
                                            style="width:100%;max-width:100%;text-align:left;">
                                            <tbody>
                                                <tr>
                                                    <td style="height: 50px;" colspan="3"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <table cellpadding="0" cellspacing="0"
                                                            style="width:100%;max-width: 100%;text-align: center;">
                                                            <tr>
                                                                <td style="width: 10%;"></td>
                                                                <td valign="top"
                                                                    style="width: 80%;font-size: 16px; line-height: 26px;color: #606060;text-align:center;">
                                                                    <h2 style="  text-align: center;">
                                                                        Welcome to nudora </h2>
                                                                    <h4
                                                                        style="font-size: 27px; color: #323232;text-align: center;font-style: normal;font-weight: bold;letter-spacing: -1px;padding-top: 5px;   ">
                                                                        Hello, {{ $user->name }}</h4>
                                                                    <p
                                                                        style="box-sizing: border-box; position: relative; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; margin-top: 0; font-size: 16px;color: #606060; line-height: 24px;text-align:center;">
                                                                        We're on a mission to make fashion more
                                                                        communal, sustainable and accessible. </p>
                                                                    {{-- <strong
                                                                        style="box-sizing: border-box;position: relative;   font-weight: 400;color: #151515;">
                                                                        We're on a mission to make fashion more
                                                                        communal, sustainable and accessible.
                                                                    </strong> --}}
                                                                </td>
                                                                <td style="width: 10%;"></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <!--
                                                <tr>
                                                    <td colspan="3" align="center" style="padding-top: 15px;">
                                                        {{-- <a href="{{ route('verify-email', [$user->id, $user->email_verification_token]) }}" style="
                                                        text-transform: capitalize;background-color: #1B1B1B;font-size: 16px;color: #fff;display: inline-flex;
                                                        text-align: center;padding: 8px 20px;border-radius: 0;min-height: 40px;align-items: center;transition: all 0.5s;
                                                        min-width: 130px;justify-content: center;border: 1px solid #1B1B1B;text-decoration: none;   
                                                        ">Start Chering Now
                                                        </a> --}}
                                                    </td>
                                                </tr>
-->
                                                <tr>
                                                    <td colspan="3" style="height: 20px;"></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 15%;"></td>
                                                    <td valign="top"
                                                        style="width: 70%;font-size: 15px;font-style: italic; line-height: 21px;color: #606060;text-align: center;">
                                                        <div class="content-holder" style="text-align: left;">
                                                            {{-- <h4
                                                                style="font-size: 18px;color: #000;font-style: normal;font-weight: 700;   ">
                                                                Here's what you can look forward to:</h4> --}}
                                                            {{-- <ul
                                                                style="list-style: number;color: #000;padding-left: 20px;   font-style: normal;margin-bottom: 30px">
                                                                <li>New listings, every day: As more users discover the
                                                                    platform,
                                                                    more items will become available to borrow.</li>
                                                                <li style="margin-bottom: 5px;">Flexible,
                                                                    subscription-free shopping: No commitments
                                                                    necessary. Borrow items you love on your own
                                                                    schedule, without
                                                                    the requirement of a subscription.</li>
                                                                <li>Satisfaction guaranteed: We are committed to making
                                                                    both
                                                                    renters and lenders happy. If an item is received or
                                                                    returned
                                                                    not to your liking, please reach out and we will do
                                                                    our best to
                                                                    help.</li>
                                                            </ul> --}}


                                                            {{-- <h4
                                                                style="font-size: 18px;color: #000;font-style: normal;font-weight: 700;   ">
                                                                And here are a few tips to help get you started:</h4>
                                                            <ul
                                                                style="list-style: number;color: #000;padding-left: 20px;   font-style: normal;margin-bottom: 0px">
                                                                <li style="margin-bottom: 5px;">Read our <a
                                                                        href="{{ route('view', ['slug' => 'faq']) }}">FAQ</a>
                                                                    : This will help you better
                                                                    understand how the platform works.</li>
                                                                <li style="margin-bottom: 5px;">Complete your profile:
                                                                    Add a profile picture and short bio,
                                                                    if you haven't already. This helps users get to know
                                                                    you and
                                                                    build trust within our community.</li>
                                                                <li style="margin-bottom: 5px;">Explore listings: Browse
                                                                    our marketplace and discover items
                                                                    of interest. Keep track of items you love by adding
                                                                    them to your
                                                                    favorites.</li>
                                                                <li>nudora your items: If you have fashion pieces you're
                                                                    open to
                                                                    lending, consider listing them. You will earn cash
                                                                    straight to
                                                                    your bank account while helping others find the
                                                                    perfect outfit.
                                                                </li>
                                                            </ul> --}}
                                                            {{-- <p
                                                                style="font-style: normal;color: #000;font-size: 15px;   ">
                                                                Should you have any questions, comments or concerns, our
                                                                support team is here to help. You can reach us at <a
                                                                    href="javascript: void(0);"
                                                                    style="color: #767676;">team@thenudora.com</a>.</p> --}}
                                                            <p style="text-align: center;margin: 20px auto 50px;"><a
                                                                    href="{{ route('index') }}"
                                                                    style="font-style: normal;
                                                        text-transform: capitalize;background-color: #1B1B1B;font-size: 16px;color: #fff;display: inline-flex;
                                                        text-align: center;padding: 8px 20px;border-radius: 0;min-height: 40px;align-items: center;transition: all 0.5s;
                                                        min-width: 130px;justify-content: center;border: 1px solid #1B1B1B;text-decoration: none;   
                                                        ">Start
                                                                    Chering Now
                                                                </a>
                                                            </p>
                                                            {{-- <p
                                                                style="font-style: normal;color: #000;font-size: 15px;   margin: 0 0 3px">
                                                                Stay stylish,</p>
                                                            <p
                                                                style="font-style: normal;color: #525252;font-size: 15px;   margin: 0 0 3px;font-weight: 600;">
                                                                Annika</p>
                                                            <p
                                                                style="font-style: normal;color: #525252;font-size: 15px;   margin: 0 0 3px;font-weight: 600;">
                                                                Founder & CEO, nudora</p> --}}
                                                        </div>
                                                    </td>
                                                    <td style="width: 15%;"></td>
                                                </tr>
                                                <tr>
                                                    <td style="height: 30px;" colspan="3"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <td style="height: 30px;" colspan="3"></td>
                                </tr> --}}
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="padding: 0  80px; ">
                            <table cellpadding="0" cellspacing="0"
                                style="width:100%;max-width: 100%; background:#ffffff;">
                                <tr>
                                    <td style="width: 100%">
                                        <table cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td
                                                    style="font-style: normal;font-weight: 600;color: #1B1B1B;;font-size: 16px; margin: 0 0 3px">
                                                    With love,</td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="font-style: normal;font-weight: 600;color: #1B1B1B;;font-size: 16px; margin: 0 0 3px">
                                                    Team nudora</td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="font-style: normal;font-weight: 600;color: #1B1B1B;;font-size: 16px; margin: 0 0 3px">
                                                    team@thenudora.com</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 40px;" colspan="3"></td>
                    </tr>
                    <tr>
                        <td>
                            <table cellpadding="0" cellspacing="0" style="width:100%;background: #E8E5DE;"
                                align="center">
                                <tbody>
                                    <tr>
                                        <td style="height: 32px;" colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <td width="7%"></td>
                                        <td width="84%" align="center" valign="top"
                                            style="font-size: 14px; line-height: 21px;color: #606060;text-align: center;">

                                            {{-- <ul class="social-icons-list text-demo"
                                                style="list-style:none;justify-content:center;margin-bottom:20px;padding:0;text-align: center;">
                                                <li class="social-icon" style="display:inline-block;margin-left:0;">
                                                    <a style="min-width: 35px; min-height: 35px; display: flex; justify-content: center; align-items: center; background: #FFFFFF; border-radius: 3px; color: #606060; font-size: 15px; margin-right: 10px; margin-top: 5px;text-decoration: none;"
                                                        href="https://www.facebook.com">
                                                        <img src="{{ asset('img/facebook.png') }}"
                                                            alt="social icons" />
                                                    </a>
                                                </li>
                                                <li class="social-icon" style="display:inline-block;margin-left:0;">
                                                    <a style="min-width: 35px; min-height: 35px; display: flex; justify-content: center; align-items: center; background: #FFFFFF; border-radius: 3px; color: #606060; font-size: 15px; margin-right: 10px; margin-top: 5px;text-decoration: none;"
                                                        href="https://twitter.com">
                                                        <img src="{{ asset('img/twitter.png') }}"
                                                            alt="social icons" />
                                                    </a>
                                                </li>
                                                <li class="social-icon" style="display:inline-block;margin-left:0;">
                                                    <a style="min-width: 35px; min-height: 35px; display: flex; justify-content: center; align-items: center; background: #FFFFFF; border-radius: 3px; color: #606060; font-size: 15px; margin-right: 10px; margin-top: 5px;text-decoration: none;"
                                                        href="https://instagram.com">
                                                        <img src="{{ asset('img/instagram.png') }}"
                                                            alt="social icons" />
                                                    </a>
                                                </li>
                                                <li class="social-icon" style="display:inline-block;margin-left:0;">
                                                    <a style="min-width: 35px; min-height: 35px; display: flex; justify-content: center; align-items: center; background: #FFFFFF; border-radius: 3px; color: #606060; font-size: 15px; margin-right: 10px; margin-top: 5px;text-decoration: none;"
                                                        href="https://linkedin.com">
                                                        <img src="{{ asset('img/linkedin.png') }}"
                                                            alt="social icons" />
                                                    </a>
                                                </li>
                                            </ul> --}}
                                            <span class="separate"
                                                style="display: block;border-top: 1px #c7c7c7;margin: 15px auto;"></span>

                                            <div style="padding: 5px 0;">
                                                <a href="{{ route('view', ['slug' => 'faq']) }}"
                                                    style="font-size: 12px;line-height: 24px;color: #606060;display: inline-block;text-decoration: none;margin-right: 10px;   ">FAQ
                                                </a>|
                                                <a href="{{ route('view', ['slug' => 'terms-conditions']) }}"
                                                    style="font-size: 12px;line-height: 24px;color: #606060;display: inline-block;text-decoration: none;margin-right: 10px;   ">Terms
                                                    of Service</a>|
                                                <a href="{{ route('view', ['slug' => 'privacy-policy']) }}"
                                                    style="font-size: 12px;line-height: 24px;color: #606060;display: inline-block;text-decoration: none;   ">Privacy
                                                    Policy</a>
                                            </div>

                                        </td>
                                        <td width="7%"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" align="center"
                                            style="color:#606060;   font-size:12px;text-align:center;line-height:24px;padding-top: 5px;">
                                            © {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved</td>
                                    </tr>
                                    <tr>
                                        <td style="height: 30px;" colspan="3"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!-- End of main container -->
</body>

</html>
