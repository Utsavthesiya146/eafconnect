<h3>TYFCB Report</h3>

<table border="1" width="100%" cellpadding="5">
    <tr>
        <th>Date</th>
        <th>Amount</th>
        <th>Giver</th>
        <th>Receiver</th>
        <th>Category</th>
        <th>Business Type</th>
    </tr>

    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= $r['created_at'] ?></td>
            <td><?= $r['amount'] ?></td>
            <td><?= $r['from_name'] ?></td>
            <td><?= $r['to_name'] ?></td>
            <td><?= $r['category'] ?></td>
            <td><?= $r['business_type'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>