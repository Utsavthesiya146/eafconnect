<table width="100%" bgcolor="#f4f6f9" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif;">
    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0"
                style="background:#ffffff;margin:30px auto;border-radius:10px;overflow:hidden;">

                <!-- HEADER -->
                <tr>
                    <td align="center" bgcolor="#1b4e8f" style="padding:20px;">
                        <h2 style="color:#fff;margin:0;">New Notification 🔔</h2>
                    </td>
                </tr>

                <!-- BODY -->
                <tr>
                    <td style="padding:25px;">

                        <p>Hi <strong><?= esc($name ?? 'User') ?></strong>,</p>

                        <p>You have received a new notification from <strong>eafconnect</strong>.</p>

                        <table width="100%" cellpadding="10"
                            style="border-collapse:collapse;border:1px solid #eee;">

                            <tr>
                                <td><strong>Title</strong></td>
                                <td><?= esc($title ?? '-') ?></td>
                            </tr>

                            <tr>
                                <td><strong>Message</strong></td>
                                <td><?= nl2br(esc($message ?? '-')) ?></td>
                            </tr>

                            <tr>
                                <td><strong>Date</strong></td>
                                <td><?= date('d M Y H:i A') ?></td>
                            </tr>

                        </table>

                        <div style="text-align:center;margin-top:20px;">
                            <a href="<?= base_url(); ?>"
                                style="background:#1b4e8f;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;">
                                View Dashboard
                            </a>
                        </div>

                    </td>
                </tr>

                <!-- FOOTER -->
                <tr>
                    <td style="text-align:center;padding:10px;font-size:12px;color:#777;">
                        © <?= date('Y') ?> eafconnect
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>