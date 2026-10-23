<h3>Weekly Report</h3>
<p>From: <?= date('d M Y', strtotime($startDate)) ?> To: <?= date('d M Y', strtotime($endDate)) ?></p>

<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Category</th>
            <th>Count / Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Connects Given</td>
            <td><?= $r_g_c ?? 0 ?></td>
        </tr>
        <tr>
            <td>Connects Received</td>
            <td><?= $r_r_c ?? 0 ?></td>
        </tr>
        <tr>
            <td>MITB Given</td>
            <td>₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($totalMITBGiven ?? 0) ?></td>
        </tr>
        <tr>
            <td>MITB Received</td>
            <td>₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($totalMITBReceived ?? 0) ?></td>
        </tr>
        <tr>
            <td>F2F Meetings</td>
            <td><?= $meetingsCount ?? 0 ?></td>
        </tr>
        <tr>
            <td>Verified Visitors</td>
            <td><?= $visitors_c ?? 0 ?></td>
        </tr>
        <tr>
            <td>Total MITB Amount</td>
            <td>₹<?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format(($totalMITBGiven + $totalMITBReceived) ?? 0) ?></td>
        </tr>
    </tbody>
</table>