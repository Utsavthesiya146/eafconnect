<div class="mitb-row">
    <!-- To Member -->
    <div class="member-info">
        <div class="member-cell">
            <img src="<?= base_url('uploads/profile/' . ($row['to_profile'] ?? 'empty.png')) ?>" style="object-fit: cover; object-position: top center;"
                class="avatar">
            <div class="member-text">
                <div class="name" style="font-size: 13px;"><?= esc($row['to_name']) ?></div>
                <small style="font-size: 10px;"><?= esc($row['to_email']) ?></small>
            </div>
        </div>
    </div>
    <!-- Arrow -->
    <div class="flow-arrow">
        <i class="fa fa-arrow-right"></i>
    </div>
    <!-- From Member -->
    <div class="member-info">
        <div class="member-cell">
            <img src="<?= base_url('uploads/profile/' . ($row['from_profile'] ?? 'empty.png')) ?>" style="object-fit: cover; object-position: top center;"
                class="avatar">
            <div class="member-text">
                <div class="name" style="font-size: 13px;"><?= esc($row['from_name']) ?></div>
                <small style="font-size: 10px;"><?= esc($row['from_email']) ?></small>
            </div>
        </div>
    </div>
    <!-- Amount -->
    <div class="col-amount">
        <span class="amount">
            ₹ <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($row['amount']) ?>
        </span>
    </div>
    <!-- Date -->
    <div class="col-date">
        <p style="font-size: 10px;"> <?= date('d M Y', strtotime($row['created_at'])) ?></p>
    </div>
    <div class="col-action action-inline">
        <a class="action-btn edit"
            onclick="edit_mitb('<?= $row['mitb_code'] ?>')">
            <i class="fa fa-edit"></i>
        </a>
        <a class="action-btn delete"
            onclick="delete_mitb('<?= $row['mitb_code'] ?>')">
            <i class="fa fa-trash"></i>
        </a>
    </div>
    <style>
        .amount {
            color: #16a34a;
            font-weight: 700;
            font-size: 15px;
        }

        .flow-arrow {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef2ff;
            color: #777edd;
            border-radius: 50%;
            flex-shrink: 0;
        }

        @media (max-width:768px) {

            .mitb-row {
                flex-direction: column;
                align-items: stretch;
            }

            .flow-arrow {
                margin: auto;
                transform: rotate(90deg);
            }

            .col-amount,
            .col-date {
                text-align: left;
            }
        }
    </style>
</div>