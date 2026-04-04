<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background-color:#f3f4f6;">
  <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td style="padding:40px 20px;">
        <table width="100%" style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.1);">

          <!-- Header -->
          <tr>
            <td style="padding:32px 40px 24px;text-align:center;background:linear-gradient(135deg,#CD571B,#EC921A);border-radius:16px 16px 0 0;">
              <div style="margin-bottom:14px;">
                <img src="{{ config('app.url') }}/images/logo-img.png" alt="Outsidersmedia" style="max-height:60px;max-width:220px;object-fit:contain;">
              </div>
              <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:bold;">Welcome to Outsidersmedia!</h1>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:40px;">
              <p style="margin:0 0 16px;color:#374151;font-size:16px;">Hi <strong>{{ $clientName }}</strong>,</p>
              <p style="margin:0 0 24px;color:#374151;font-size:15px;line-height:1.6;">
                We're excited to have you on board! Your Outsidersmedia account has been set up and your content portal is ready to go.
              </p>

              <!-- Plan badge -->
              <table width="100%" style="margin:0 0 28px;">
                <tr>
                  <td style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;padding:20px;">
                    <p style="margin:0 0 6px;color:#6b7280;font-size:13px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Your Plan</p>
                    <p style="margin:0;color:#111827;font-size:20px;font-weight:bold;">{{ $planType }}</p>
                  </td>
                </tr>
              </table>

              <p style="margin:0 0 24px;color:#374151;font-size:15px;line-height:1.6;">
                Through your client portal, you can review your scheduled content, provide feedback, and approve posts before they go live.
              </p>

              <!-- CTA Button -->
              <table width="100%" style="margin:0 0 30px;">
                <tr>
                  <td style="text-align:center;">
                    <a href="{{ $portalUrl }}" style="display:inline-block;padding:16px 40px;background:linear-gradient(135deg,#CD571B,#EC921A);color:#ffffff;text-decoration:none;border-radius:12px;font-weight:bold;font-size:16px;">
                      View Your Content Portal
                    </a>
                  </td>
                </tr>
              </table>

              <p style="color:#6b7280;font-size:13px;">If the button doesn't work, copy this link:</p>
              <p style="padding:10px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;word-break:break-all;font-size:12px;color:#4b5563;">{{ $portalUrl }}</p>

              <p style="margin-top:24px;color:#6b7280;font-size:13px;">If you have any questions, feel free to reply to this email and our team will be happy to help.</p>
            </td>
          </tr>

          <!-- Footer -->
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
