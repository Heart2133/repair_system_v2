<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 700px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            text-align: center;
            border: 1px solid #dddddd;
            border-radius: 8px;
        }
        .logo {
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 150px;
        }
        .email-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .email-subheader {
            font-size: 16px;
            color: #333333;
            margin-bottom: 20px;
        }
        .email-button {
            display: inline-block;
            background-color: #d9534f;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
        }
        .email-button:hover {
            background-color: #c9302c;
        }
        .email-footer {
            font-size: 14px;
            color: #888888;
            margin-top: 20px;
        }
        .email-footer a {
            color: #888888;
            text-decoration: none;
        }
        .email-footer a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
    <div class="email-container">
        {{-- <div style="display:flex;justify-content:center;align-items:center;margin-bottom:20px;"> --}}
            <img src="https://static.wixstatic.com/media/544b09_2b096e7b3134472f9a36c53920d5bcce~mv2.png/v1/fill/w_473,h_208,al_c,lg_1,q_85,enc_auto/544b09_2b096e7b3134472f9a36c53920d5bcce~mv2.png" alt="Logo" width="35%" style="margin-bottom:20px;">
        {{-- </div> --}}
        <div class="email-header" style="color:#034ea1; ">กรุณายืนยันอีเมลของท่าน</div>
        <div class="email-header" style="color:#000000FF; ">Verify your email</div>
        <a href="{{ route('verify.email', $user->verification_token) }}" class="email-button" style="background:#034ea1;"><span style="color:#FFFFFFFF;">Verify</span></a>
        <div class="email-footer">
            กรุณายืนยันอีเมลของท่านเพื่อการตรวจสอบว่าท่านยังคงสามารถเข้าถึงข้อมูลบัญชีได้อย่างต่อเนื่อง<br>
            Verifying your email address will ensure continued access to your account.
        </div>
        {{-- <div class="email-footer">
            หากท่านพบปัญหา กรุณาติดต่อที่อีเมล<br>
            <a href="mailto:customerservice@uniqlo.co.th">customerservice@uniqlo.co.th</a>
        </div> --}}
    </div>
</body>
</html>
