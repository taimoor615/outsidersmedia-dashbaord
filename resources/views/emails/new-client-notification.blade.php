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
              <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:bold;">New Client Added</h1>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:40px;">
              <p style="margin:0 0 20px;color:#374151;font-size:16px;">
                A new client has been added to Outsidersmedia by <strong>{{ $createdBy }}</strong>.
              </p>

              <!-- Client Details -->
              <table width="100%" style="margin:0 0 28px;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">
                <tr>
                  <td style="padding:16px 20px;background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                    <p style="margin:0;color:#6b7280;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Client Name</p>
                    <p style="margin:4px 0 0;color:#111827;font-size:16px;font-weight:bold;">{{ $clientName }}</p>
                  </td>
                </tr>
                <tr>
                  <td style="padding:16px 20px;background:#ffffff;border-bottom:1px solid #e5e7eb;">
                    <p style="margin:0;color:#6b7280;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Email</p>
                    <p style="margin:4px 0 0;color:#111827;font-size:15px;">{{ $clientEmail }}</p>
                  </td>
                </tr>
                <tr>
                  <td style="padding:16px 20px;background:#f9fafb;">
                    <p style="margin:0;color:#6b7280;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Plan</p>
                    <p style="margin:4px 0 0;color:#111827;font-size:15px;font-weight:600;">{{ $planType }}</p>
                  </td>
                </tr>
              </table>

              <!-- CTA Button -->
              <table width="100%" style="margin:0 0 24px;">
                <tr>
                  <td style="text-align:center;">
                    <a href="{{ $clientUrl }}" style="display:inline-block;padding:14px 36px;background:linear-gradient(135deg,#CD571B,#EC921A);color:#ffffff;text-decoration:none;border-radius:12px;font-weight:bold;font-size:15px;">
                      View Client Profile
                    </a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:24px 40px;background:#f9fafb;border-radius:0 0 16px 16px;text-align:center;">
              <p style="margin:0;color:#9ca3af;font-size:13px;">© 2026 Outsidersmedia. All rights reserved.</p>
              <p style="margin:6px 0 0;color:#9ca3af;font-size:12px;">This is an automated notification. Please do not reply.</p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
