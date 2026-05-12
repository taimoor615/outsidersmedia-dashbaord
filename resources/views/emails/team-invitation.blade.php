<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Outsidersmedia</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f3f4f6;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 40px 20px;">
                <table role="presentation" style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">

                    <!-- Header -->
                    <tr>
                        <td style="padding: 32px 40px 24px; text-align: center; background: linear-gradient(135deg, #CD571B 0%, #EC921A 100%); border-radius: 16px 16px 0 0;">
                            <div style="margin-bottom: 14px;">
                                <span style="color:#ffffff;font-size:22px;font-weight:800;letter-spacing:0.5px;font-family:'Helvetica Neue',Arial,sans-serif;">Outsidersmedia</span>
                            </div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 26px; font-weight: bold;">Welcome to Outsidersmedia!</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <p style="margin: 0 0 20px; color: #374151; font-size: 16px; line-height: 1.6;">
                                Hi <strong>{{ $userName }}</strong>,
                            </p>

                            <p style="margin: 0 0 20px; color: #374151; font-size: 16px; line-height: 1.6;">
                                You've been added as a team member to the Outsidersmedia dashboard. To get started, you need to activate your account by clicking the button below.
                            </p>

                            <div style="background-color: #f3f4f6; border-radius: 12px; padding: 20px; margin: 30px 0;">
                                <p style="margin: 0 0 8px; color: #6b7280; font-size: 14px; font-weight: 600;">Your Login Email:</p>
                                <p style="margin: 0 0 16px; color: #111827; font-size: 16px; font-weight: bold;">{{ $userEmail }}</p>
                                <p style="margin: 0 0 8px; color: #6b7280; font-size: 14px; font-weight: 600;">Your Temporary Password:</p>
                                <p style="margin: 0; color: #111827; font-size: 16px; font-weight: bold; letter-spacing: 1px; font-family: monospace;">{{ $temporaryPassword }}</p>
                                <p style="margin: 8px 0 0; color: #9ca3af; font-size: 12px;">Please change your password after your first login.</p>
                            </div>

                            <!-- CTA Button -->
                            <table role="presentation" style="width: 100%; margin: 30px 0;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ $verificationUrl }}" style="display: inline-block; padding: 16px 40px; background: linear-gradient(135deg, #CD571B 0%, #EC921A 100%); color: #ffffff; text-decoration: none; border-radius: 12px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 6px rgba(205,87,27,0.3);">
                                            Activate My Account
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 30px 0 20px; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                If the button doesn't work, copy and paste this link into your browser:
                            </p>

                            <p style="margin: 0 0 20px; padding: 12px; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; word-break: break-all; font-size: 13px; color: #4b5563;">
                                {{ $verificationUrl }}
                            </p>

                            <p style="margin: 30px 0 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                After activation, you'll be able to log in and start managing social media content for your clients.
                            </p>

                            <p style="margin: 20px 0 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                If you didn't expect this email, please ignore it or contact your administrator.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px 40px; background-color: #f9fafb; border-radius: 0 0 16px 16px; text-align: center;">
                            <p style="margin: 0 0 10px; color: #9ca3af; font-size: 14px;">
                                © 2026 Outsidersmedia. All rights reserved.
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                This is an automated email. Please do not reply.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
