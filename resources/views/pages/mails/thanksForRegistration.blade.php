<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1b0c3e;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .success-icon {
            font-size: 48px;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            background: #45a049;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }

        .button:hover {
            background: linear-gradient(135deg, #ff1493 0%, #ff6600 100%);
        }
    </style>
</head>

<body>
    <div class="container">
        <div>
            <img src="{{ $message->embed(public_path('images/icons/indus-ai.jpeg')) }}" width="500px" alt="INDUS AI BANNER" />
        </div>
        <h4 style="text-align: left;">Dear {{$user['name']}}</h4>
        <p>Thank you for registering to attend INDUS AI WEEK! Your registration is now complete. This Visitor pass gives you <br />
            access across the imdustrial expo at Jinnah Sports Complex from February 9-10,2026.</p>
        <p>Please keep e-badge save in your mobile and present at the registration counter at venue entrance.</p>
        <p>For event agenda and details visit : https://indusai.gov.pk/expo</p>
        <br/>
        <p>For Collecting Badge Please visit : <a href="https://vms.badarexpo.com/indusBadge/{{$user['identity']}}">Badge</a></p>
        <br/>
        <h3 style="text-align:center; background-color:#000000; color:#ffffff;">YOUR PASS REGISTRATION SUMMARY</h3>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td style="padding: 5px 0; font-weight: bold;">Badge Category:</td>
                <td style="padding: 5px 0;">
                    VISITOR PASS
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0; font-weight: bold;">Full Name:</td>
                <td style="padding: 5px 0;">
                    {{ $user['name'] ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0; font-weight: bold;">Company Name:</td>
                <td style="padding: 5px 0;">
                    {{ $user['company'] ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0; font-weight: bold;">Registration No.:</td>
                <td style="padding: 5px 0;">
                    <a href="https://vms.badarexpo.com/indusBadge/{{$user['identity']}}">{{ $user['code'] ?? 'N/A' }}</a>
                </td>
            </tr>
        </table>
        <h3 style="text-align:center; background-color:#000000; color:#ffffff;">SHOW INFORMATION</h3>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
            <tr>
                <td style="padding: 5px 0; font-weight: bold;">Show Timing:</td>
                <td style="padding: 5px 0;">
                    Venue
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0; font-weight: bold;">Mon 9 Feb - Tue 10 Feb 2026<br />9:00 AM - 5:00 PM</td>
                <td style="padding: 5px 0;">
                    Jinnah Sports Complex, Islamabad
                </td>
            </tr>
        </table>
        <h3 style="text-align:center; background-color:#000000; color:#ffffff;">GENERAL RULES FOR EVENT VISITORS</h3>
        <p>Welcome to Indus AI Week! To ensure a safe and productive experience for all attendees, please adhere to the following rules:</p>
        <div style="text-align: left;"></div>
        <h4>REGISTRATION AND ENTRY</h4>
        <ul style="text-align: left;">
            <li><b>Non-transferable: </b> Event badges are non-transferable. Each badge is
                unique to the registered attendee and must not be shared.</li>
            <li><b>Restricted Items: </b> Weapons, hazardous materials, Camera/DSLR,
                mobile phone and any items prohibited by venue regulations are not
                allowed.</li>
            <li><b>Bag Check: </b> All bags are subject to security checks upon entry.</li>
            <li><b>Dress Code: </b>Please note that the dress code for the event is National/
                Business Attire.</li>
        </ul>
        <h4>HEALTH AND SAFETY</h4>
        <ul style="text-align: left;">
            <li><b>Symptom Monitoring : </b>Monitor your health and do not
                attend the event if you are experiencing any symptoms of
                illness or have been exposed to someone with COVID-19.
            </li>
            <li><b>Incident Reporting : </b> Report any health and safety concerns
                or incidents to event staff immediately for prompt action.
                Emergency Procedures: Be aware of emergency exits and
                procedures. Follow all emergency instructions from venue
                staff.</li>
            <li><b>Emergency PRocedures:</b>Be aware of emergency exits and procedures. Follow all emergency instructions from venue staff.</li>
        </ul>
        <h4 style="text-transform: uppercase;">ADDITIONAL GUIDELINES
        </h4>
        <ul style="text-align: left;">
            <li><b>Accessibility : </b> If you have any accessibility needs, contact
                the event organizers in advance for facilitation.
            </li>
            <li><b>Event App :</b>Use the event app (if available) for updates,
                schedules, and navigation assistance.</li>
        </ul>
        <p><b>Thank you for your cooperation. We look forward to providing you with a valuable and informative event experience!</b></p>
        <br/>
        <br/>
        <h3>Organizing Team</h3>
        <h4>INDUS AI WEEK</h4>
        <p>February 9-15, 2026</p>
        <p>Contact: 0800 01010</p>
    </div>
</body>

</html>