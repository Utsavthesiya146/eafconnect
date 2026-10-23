<table width="100%" bgcolor="#f4f6f9" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif;">
    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0"
                style="background:#ffffff;margin:30px auto;border-radius:10px;overflow:hidden;">

                <tr>
                    <td align="center" bgcolor="#1b4e8f" style="padding:20px;">
                        <h2 style="color:#fff;margin:0;">New One-to-One Meeting</h2>
                    </td>
                </tr>

                <tr>
                    <td style="padding:25px;">

                        <p>Hi <strong><?= esc($name ?? 'User') ?></strong>,</p>

                        <p>You have a new <strong>One-to-One (F2F)</strong> meeting scheduled.</p>

                        <table width="100%" cellpadding="10" style="border-collapse:collapse;border:1px solid #eee;">

                            <tr>
                                <td><strong>From</strong></td>
                                <td><?= esc($from_name ?? '-') ?></td>
                            </tr>

                            <tr>
                                <td><strong>Contact</strong></td>
                                <td><?= esc($contact ?? '-') ?></td>
                            </tr>

                            <tr>
                                <td><strong>Date</strong></td>
                                <td><?= !empty($date) ? date('d M Y', strtotime($date)) : '-' ?></td>
                            </tr>

                            <tr>
                                <td><strong>Location</strong></td>
                                <td><?= esc($location ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Summary</strong></td>
                                <td><?= nl2br(esc($summary ?? '-')) ?></td>
                            </tr>
                        </table>
                        <div style="text-align:center;margin-top:20px;">
                            <a href="<?= base_url('member/f2f'); ?>"
                                style="background:#1b4e8f;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;">
                                View Details
                            </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;padding:10px;font-size:12px;color:#777;">
                        © <?= date('Y') ?> eafconnect
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>