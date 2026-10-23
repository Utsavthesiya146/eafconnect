<div class="mitb-row received-row">
    <!-- To Member -->
    <div class="member-info">
        <div class="member-cell">
            <img src="<?= base_url('uploads/profile/' . ($row['to_profile'] ?? 'empty.png')) ?>" style="object-fit: cover; object-position: top center;" class="avatar">
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
            <img src="<?= base_url('uploads/profile/' . ($row['from_profile'] ?? 'empty.png')) ?>" style="object-fit: cover; object-position: top center;" class="avatar">
            <div class="member-text">
                <div class="name" style="font-size: 13px;"><?= esc($row['from_name']) ?></div>
                <small style="font-size: 10px;"><?= esc($row['from_email']) ?></small>
            </div>
        </div>
    </div>
    <!-- Amount -->
    <div class="col-amount">
        <span class="received-amount">
            ₹ <?= (new NumberFormatter('en_IN', NumberFormatter::DECIMAL))->format($row['amount']) ?>
        </span>
    </div>
    <!-- Date -->
    <div class="col-date">
        <p style="font-size: 10px;"> <?= date('d M Y', strtotime($row['created_at'])) ?></p>
    </div>
    <!-- Action -->
    <!-- <div class="col-action action-inline">
        <a class="action-btn edit"
            onclick="edit_mitb('<?= $row['mitb_code'] ?>')">
            <i class="fa fa-edit"></i>
        </a>
        <a class="action-btn delete"
            onclick="delete_mitb('<?= $row['mitb_code'] ?>')">
            <i class="fa fa-trash"></i>
        </a>
    </div> -->
    <style>
        .mitb-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            margin-bottom: 10px;
            background: #fff;
            border: 1px solid #ececec;
            border-radius: 12px;
            transition: .25s;
        }

        .mitb-row:hover {
            box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
        }

        .member-info {
            flex: 1;
            min-width: 130px;
        }

        .member-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            object-position: top center;
            border: 2px solid #f2f2f2;
            flex-shrink: 0;
        }

        .member-text {
            overflow: hidden;
        }

        .member-text .name {
            font-size: 14px;
            font-weight: 600;
            color: #222;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .member-text small {
            display: block;
            color: #777;
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .flow-arrow {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f2ff;
            color: #777edd;
            border-radius: 50%;
            font-size: 15px;
            flex-shrink: 0;
        }

        .col-amount {
            min-width: 120px;
            text-align: center;
            font-size: 15px;
            font-weight: 700;
        }

        .received-amount {
            color: #16a34a;
        }

        .col-date {
            min-width: 95px;
            text-align: center;
            font-size: 13px;
            color: #666;
        }

        .action-inline {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .action-btn {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            text-decoration: none;
            transition: .2s;
        }

        .action-btn.edit {
            background: #eef2ff;
            color: #4f46e5;
        }

        .action-btn.delete {
            background: #fff1f2;
            color: #dc2626;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .mobile-label {
            display: none;
        }

        /* ---------- Mobile ---------- */

        @media(max-width:768px) {

            .mitb-row {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
                padding: 12px;
            }

            .member-info,
            .col-amount,
            .col-date,
            .col-action {
                width: 100%;
            }

            .member-cell {
                width: 100%;
            }

            .mobile-label {
                display: block;
                font-size: 11px;
                font-weight: 600;
                color: #777edd;
                margin-bottom: 5px;
                text-transform: uppercase;
            }

            .flow-arrow {
                align-self: center;
                transform: rotate(90deg);
            }

            .col-amount,
            .col-date {
                text-align: left;
            }

            .action-inline {
                justify-content: flex-start;
            }

        }
    </style>
</div>