<table width="100%" bgcolor="#f4f6f9" cellpadding="0" cellspacing="0" style="font-family: 'Segoe UI', Arial, sans-serif;">
  <tr>
    <td align="center">

      <table width="600" cellpadding="0" cellspacing="0"
        style="background:#ffffff;margin:30px auto;border-radius:10px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.08);">

        <!-- HEADER -->
        <tr>
          <td align="center" bgcolor="#1b4e8f" style="padding:25px;">
            <img src="https://eafconnect.com/images/logo.png" width="180">
          </td>
        </tr>

        <!-- SUBJECT -->
        <tr>
          <td style="padding:20px 30px 10px;">
            <h2 style="margin:0;color:#1b4e8f;font-size:22px;">
              <?= esc($subject ?? 'Meeting Invitation'); ?>
            </h2>
          </td>
        </tr>

        <!-- GREETING -->
        <tr>
          <td style="padding:0 30px 15px;">
            <p style="margin:0;font-size:15px;color:#444;">
              Hi <strong><?= esc($name); ?></strong>,
            </p>

            <p style="margin:10px 0 0;color:#555;font-size:14px;line-height:1.7;">
              <?= nl2br(esc($message ?? 'You are invited to attend the meeting.')); ?>
            </p>
          </td>
        </tr>

        <!-- TITLE + DESCRIPTION (PARAGRAPH STYLE) -->
        <tr>
          <td style="padding:10px 30px 20px;">

            <h3 style="margin:0 0 10px;color:#222;font-size:18px;font-weight:600;">
              <?= esc($event->title); ?>
            </h3>

            <p style="margin:0;color:#555;font-size:14px;line-height:1.8;text-align:justify;">
              <?= nl2br(esc($event->description ?? 'No description available.')); ?>
            </p>

            <hr style="border:none;border-top:1px solid #eee;margin:20px 0;">

          </td>
        </tr>

        <!-- EVENT DETAILS TABLE -->
        <tr>
          <td style="padding:0 30px 20px;">
            <table width="100%" cellpadding="0" cellspacing="0"
              style="border:1px solid #eee;border-radius:8px;overflow:hidden;">

              <tr style="background:#fafafa;">
                <td style="padding:12px 15px;font-weight:600;">Chapter</td>
                <td style="padding:12px 15px;"><?= esc($event->chapter_name); ?></td>
              </tr>

              <tr>
                <td style="padding:12px 15px;font-weight:600;">Date</td>
                <td style="padding:12px 15px;">
                  <?= date('d M Y', strtotime($event->date)); ?>
                </td>
              </tr>

              <tr style="background:#fafafa;">
                <td style="padding:12px 15px;font-weight:600;">Time</td>
                <td style="padding:12px 15px;">
                  <?= date('h:i A', strtotime($event->time)); ?>
                </td>
              </tr>

              <tr>
                <td style="padding:12px 15px;font-weight:600;">Location</td>
                <td style="padding:12px 15px;">
                  <?= esc($event->location); ?>
                </td>
              </tr>

            </table>
          </td>
        </tr>

        <!-- GOOGLE MAP -->
        <tr>
          <td align="center" style="padding:10px 20px;">
            <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($event->location); ?>"
              style="background:#555;color:#fff;padding:10px 20px;border-radius:5px;text-decoration:none;font-size:13px;">
              View Location Map
            </a>
          </td>
        </tr>

        <!-- CTA BUTTON -->
        <tr>
          <td align="center" style="padding:25px;">
            <a href="<?= base_url('event/view/' . $event->event_code); ?>"
              style="background:#1b4e8f;color:#ffffff;padding:12px 28px;
              text-decoration:none;border-radius:6px;font-size:14px;
              display:inline-block;font-weight:600;">
              VIEW EVENT DETAILS
            </a>
          </td>
        </tr>

        <!-- FOOTER -->
        <tr>
          <td align="center" style="padding:20px;background:#fafafa;">
            <p style="margin:0;font-size:12px;color:#888;">
              © <?= date('Y'); ?> eafconnect. All rights reserved.
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>