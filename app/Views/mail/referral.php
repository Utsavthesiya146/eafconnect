<table width="100%" bgcolor="#f4f6f9" cellpadding="0" cellspacing="0" style="font-family:'Segoe UI', Arial, sans-serif;">
    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0"
                style="background:#ffffff;margin:30px auto;border-radius:12px;overflow:hidden;
        box-shadow:0 6px 18px rgba(0,0,0,0.08);">

                <!-- HEADER -->
                <tr>
                    <td align="center" bgcolor="#1b4e8f" style="padding:25px;">
                        <h2 style="margin:0;color:#ffffff;">New Connects  Received</h2>
                    </td>
                </tr>

                <!-- BODY -->
                <tr>
                    <td style="padding:25px 30px;">

                        <p>Hi <strong><?= esc($name) ?></strong>,</p>

                        <p style="color:#555;">
                            You have received a new Connects . Please check the details below:
                        </p>

                        <table width="100%" cellpadding="8" style="border:1px solid #eee;">
                            <tr>
                                <td><strong>MITB</strong></td>
                                <td><?= esc($business) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Contact</strong></td>
                                <td>XXXXX XXXXX</td>
                            </tr>
                            <tr>
                                <td><strong>Temperature</strong></td>
                                <td><?= esc($temperature) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Description</strong></td>
                                <td><?= nl2br(esc($description)) ?></td>
                            </tr>
                        </table>

                        <!-- BUTTON -->
                        <div style="text-align:center;margin-top:20px;">
                            <a href="<?= base_url('member/connect'); ?>"
                                style="background:#1b4e8f;color:#fff;padding:10px 25px;
                text-decoration:none;border-radius:5px;">
                                View Connects 
                            </a>
                        </div>

                    </td>
                </tr>

                <!-- FOOTER -->
                <tr>
                    <td style="text-align:center;padding:15px;font-size:12px;color:#777;">
                        © <?= date('Y') ?> eafconnect
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>