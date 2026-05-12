<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background-color:#f3f4f6;">
  <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td style="padding:40px 20px;">
        <table width="100%" style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.1);">
          <tr>
            <td style="padding:32px 40px 24px;text-align:center;background:linear-gradient(135deg,#CD571B,#EC921A);border-radius:16px 16px 0 0;">
              <div style="margin-bottom:14px;">
                <span style="color:#ffffff;font-size:22px;font-weight:800;letter-spacing:0.5px;font-family:'Helvetica Neue',Arial,sans-serif;">Outsidersmedia</span>
              </div>
              <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:bold;">Reset Your Password</h1>
            </td>
          </tr>
          <tr>
            <td style="padding:40px;">
              <p style="margin:0 0 16px;color:#374151;font-size:16px;">Hi <strong>{{ $userName }}</strong>,</p>
              <p style="margin:0 0 24px;color:#374151;font-size:15px;line-height:1.6;">
                We received a request to reset your Outsidersmedia password. Click the button below to set a new password. This link expires in <strong>60 minutes</strong>.
              </p>
              <table width="100%" style="margin:0 0 30px;">
                <tr>
                  <td style="text-align:center;">
                    <a href="{{ $resetUrl }}" style="display:inline-block;padding:16px 40px;background:linear-gradient(135deg,#CD571B,#EC921A);color:#ffffff;text-decoration:none;border-radius:12px;font-weight:bold;font-size:16px;">
                      Reset My Password
                    </a>
                  </td>
                </tr>
              </table>
              <p style="color:#6b7280;font-size:13px;">If the button doesn't work, copy this link:</p>
              <p style="padding:10px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;word-break:break-all;font-size:12px;color:#4b5563;">{{ $resetUrl }}</p>
              <p style="margin-top:24px;color:#6b7280;font-size:13px;">If you didn't request a password reset, please ignore this email. Your password won't change.</p>
            </td>
          </tr>
          <tr>
            <td style="padding:24px 40px;background:#f9fafb;border-radius:0 0 16px 16px;text-align:center;">
              <p style="margin:0;color:#9ca3af;font-size:13px;">© 2026 Outsidersmedia. All rights reserved.</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
