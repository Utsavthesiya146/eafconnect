<table width="100%" bgcolor="#f4f6f9" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif;">
    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0"
                style="background:#ffffff;margin:30px auto;border-radius:10px;overflow:hidden;
                box-shadow:0 4px 12px rgba(0,0,0,0.08);">

                <!-- HEADER -->
                <tr>
                    <td align="center" bgcolor="#1b4e8f" style="padding:20px;">
                        <h2 style="margin:0;color:#ffffff;">New MITB Request</h2>
                        <p style="margin:5px 0 0;color:#ffdede;font-size:13px;">
                            Member to Member MITB Notification
                        </p>
                    </td>
                </tr>

                <!-- BODY -->
                <tr>
                    <td style="padding:25px;">

                        <p style="font-size:15px;">
                            Hi <strong><?= esc($name ?? 'User') ?></strong>,
                        </p>

                        <p style="color:#555;font-size:14px;">
                            You have received a new <strong>MITB (Member to Member MITB)</strong> request.
                            Please find the details below:
                        </p>

                        <!-- DETAILS TABLE -->
                        <table width="100%" cellpadding="10"
                            style="border-collapse:collapse;border:1px solid #eee;font-size:14px;">

                            <tr style="background:#fafafa;">
                                <td width="40%" style="border:1px solid #eee;"><strong>From</strong></td>
                                <td style="border:1px solid #eee;"><?= esc($from_name ?? '-') ?></td>
                            </tr>

                            <tr>
                                <td style="border:1px solid #eee;"><strong>Contact</strong></td>
                                <td style="border:1px solid #eee;"><?= esc($contact ?? '-') ?></td>
                            </tr>

                            <tr style="background:#fafafa;">
                                <td style="border:1px solid #eee;"><strong>MITB Type</strong></td>
                                <td style="border:1px solid #eee;"><?= esc($business ?? '-') ?></td>
                            </tr>

                            <tr>
                                <td style="border:1px solid #eee;"><strong>Amount</strong></td>
                                <td style="border:1px solid #eee;">
                                    ₹ <?= esc($amount ?? '0') ?>
                                </td>
                            </tr>

                            <tr style="background:#fafafa;">
                                <td style="border:1px solid #eee;"><strong>Temperature</strong></td>
                                <td style="border:1px solid #eee;"><?= esc($temperature ?? '-') ?></td>
                            </tr>

                            <tr>
                                <td style="border:1px solid #eee;"><strong>Description</strong></td>
                                <td style="border:1px solid #eee;">
                                    <?= nl2br(esc($description ?? '-')) ?>
                                </td>
                            </tr>

                        </table>

                        <!-- CTA BUTTON -->
                        <div style="text-align:center;margin-top:25px;">
                            <a href="<?= base_url('member/mitb'); ?>"
                                style="background:#1b4e8f;color:#ffffff;
                                padding:12px 25px;text-decoration:none;
                                border-radius:6px;font-size:14px;">
                                View MITB Details
                            </a>
                        </div>
                        <!-- NOTE -->
                        <p style="font-size:12px;color:#999;margin-top:20px;text-align:center;">
                            Please login to your dashboard for more details.
                        </p>

                    </td>
                </tr>

                <!-- FOOTER -->
                <tr>
                    <td align="center" style="padding:15px;background:#fafafa;font-size:12px;color:#777;">
                        © <?= date('Y') ?> eafconnect. All rights reserved.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>