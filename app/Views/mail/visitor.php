<table width="100%" bgcolor="#f4f6f9" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif;">
    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0"
                style="background:#ffffff;margin:30px auto;border-radius:10px;overflow:hidden;">

                <tr>
                    <td align="center" bgcolor="#1b4e8f" style="padding:20px;">
                        <h2 style="color:#fff;margin:0;">Visitor Invitation</h2>
                    </td>
                </tr>
                <tr>
                    <td style="padding:25px;">
                        <p>Hi <strong><?= esc($name ?? 'Guest') ?></strong>,</p>
                        <p>You have been added as a <strong>Visitor</strong>. Here are the details:</p>
                        <table width="100%" cellpadding="10" style="border-collapse:collapse;border:1px solid #eee;">
                            <tr>
                                <td><strong>Invited By</strong></td>
                                <td><?= esc($member ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Contact</strong></td>
                                <td><?= esc($contact ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Company</strong></td>
                                <td><?= esc($company ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Your Phone</strong></td>
                                <td><?= esc($phone ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Visit Date</strong></td>
                                <td><?= !empty($date) ? date('d M Y', strtotime($date)) : '-' ?></td>
                            </tr>
                        </table>
                        <div style="text-align:center;margin-top:20px;">
                            <a href="<?= base_url(); ?>member/visitor"
                                style="background:#1b4e8f;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;">
                                Visit Website
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