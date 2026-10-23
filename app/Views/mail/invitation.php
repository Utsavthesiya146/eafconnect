<table width="100%" bgcolor="#f4f6f9" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif;">
    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0"
                style="background:#ffffff;margin:30px auto;border-radius:10px;overflow:hidden;">

                <!-- Header -->
                <tr>
                    <td align="center" bgcolor="#1b4e8f" style="padding:20px;">
                        <h2 style="color:#fff;margin:0;">You're Invited 🎉</h2>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:25px;">

                        <p style="margin:0 0 15px;">
                            Hi <strong><?= esc($name ?? 'Guest') ?></strong>,
                        </p>

                        <p style="margin:0 0 20px;">
                            You have been invited by
                            <strong><?= esc($member ?? 'Member') ?></strong>.
                        </p>

                        <table width="100%" cellpadding="10"
                            style="border-collapse:collapse;border:1px solid #eee;font-size:14px;">

                            <tr style="border-bottom:1px solid #eee;">
                                <td width="35%"><strong>Invited By</strong></td>
                                <td><?= esc($member ?? '-') ?></td>
                            </tr>


                            <tr style="border-bottom:1px solid #eee;">
                                <td><strong>Your Company</strong></td>
                                <td><?= esc($company ?? '-') ?></td>
                            </tr>

                            <tr style="border-bottom:1px solid #eee;">
                                <td><strong>Your Phone</strong></td>
                                <td><?= esc($phone ?? '-') ?></td>
                            </tr>

                            <tr>
                                <td><strong>Message</strong></td>
                                <td><?= nl2br(esc($message ?? '-')) ?></td>
                            </tr>

                        </table>

                        <!-- Button -->
                        <div style="text-align:center;margin-top:25px;">
                            <a href="<?= base_url(); ?>"
                                style="background:#ff9800;color:#fff;padding:12px 25px;text-decoration:none;border-radius:5px;display:inline-block;">
                                Join Us
                            </a>
                        </div>

                        <p style="margin-top:25px;color:#666;font-size:13px;">
                            We look forward to connecting with you.
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="text-align:center;padding:12px;font-size:12px;color:#777;background:#fafafa;">
                        © <?= date('Y') ?> eafconnect. All Rights Reserved.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>