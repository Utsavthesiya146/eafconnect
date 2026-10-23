<table border="1">

    <thead>

        <tr>

            <th>S.No</th>

            <th>Member</th>

            <th>Email</th>

            <th>Phone</th>

            <th>Event</th>

            <th>Chapter</th>

            <th>Date</th>

            <th>Status</th>

            <th>Note</th>

        </tr>

    </thead>


    <tbody>

        <?php $i = 1; ?>

        <?php foreach ($attendance as $a): ?>

            <tr>

                <td>
                    <?= $i++ ?>
                </td>

                <td>
                    <?= esc($a->name ?? '-') ?>
                </td>

                <td>
                    <?= esc($a->email ?? '-') ?>
                </td>

                <td>
                    <?= esc($a->phone ?? '-') ?>
                </td>

                <td>
                    <?= esc($a->event_title ?? '-') ?>
                </td>

                <td>
                    <?= esc($a->chapter_name ?? '-') ?>
                </td>

                <td>

                    <?= !empty($a->event_date)
                        ? date(
                            'd M Y',
                            strtotime($a->event_date)
                        )
                        : '-' ?>

                </td>

                <td>
                    <?= ucfirst(
                        esc($a->status ?? '-')
                    ) ?>
                </td>

                <td>
                    <?= esc($a->note ?? '-') ?>
                </td>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>