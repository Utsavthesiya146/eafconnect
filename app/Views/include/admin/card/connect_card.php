<tr class="crm-connect-row">

    <!-- =================================================
                                 S.NO
                            ================================================== -->

    <td
        data-label="S.No"
        class="crm-sno">

        <span class="crm-number">
            <?= $i ?>
        </span>

    </td>


    <!-- =================================================
                                 FROM MEMBER
                            ================================================== -->

    <td
        class="member-info"
        data-label="From Member">

        <div class="crm-member">

            <div class="crm-avatar-wrapper">

                <img
                    src="<?= get_member_avatar($row['from_profile'] ?? '', '', $row['from_user_code'] ?? '') ?>"
                    class="crm-avatar"
                    alt="<?= esc($row['from_name'] ?? '') ?>"
                    onerror="this.src='<?= base_url('uploads/profile/avatars/ai_neutral_avatar.jpg') ?>';">

                <span class="crm-online-dot"></span>

            </div>


            <div class="crm-member-details">

                <div class="crm-member-name">
                    <?= esc($row['from_name'] ?? '') ?>
                </div>

                <div class="crm-member-email">

                    <i class="fa fa-envelope"></i>

                    <?= esc($row['from_email'] ?? '') ?>

                </div>

            </div>

        </div>

    </td>


    <!-- =================================================
                                 CONNECT ARROW
                            ================================================== -->

    <td
        class="crm-connection-arrow"
        data-label="Connect">

        <span class="crm-connect-icon">

            <i class="fa fa-arrow-right"></i>

        </span>

    </td>


    <!-- =================================================
                                 TO MEMBER
                            ================================================== -->

    <td
        class="member-info"
        data-label="To Member">

        <div class="crm-member">

            <div class="crm-avatar-wrapper">

                <img
                    src="<?= get_member_avatar($row['to_profile'] ?? '', '', $row['to_user_code'] ?? '') ?>"
                    class="crm-avatar"
                    alt="<?= esc($row['to_name'] ?? '') ?>"
                    onerror="this.src='<?= base_url('uploads/profile/avatars/ai_neutral_avatar.jpg') ?>';">

            </div>


            <div class="crm-member-details">

                <div class="crm-member-name">
                    <?= esc($row['to_name'] ?? '') ?>
                </div>

                <div class="crm-member-email">

                    <i class="fa fa-envelope"></i>

                    <?= esc($row['to_email'] ?? '') ?>

                </div>

            </div>

        </div>

    </td>


    <!-- =================================================
                                 BUSINESS
                            ================================================== -->

    <td
        class="business"
        data-label="Business">

        <div class="crm-business">

            <span class="crm-business-icon">

                <i class="fa fa-briefcase"></i>

            </span>

            <div>

                <div class="crm-business-name">

                    <?= esc($row['business'] ?? '') ?>

                </div>

                <small>
                    Business Opportunity
                </small>

            </div>

        </div>

    </td>


    <!-- =================================================
                                 CONTACT
                            ================================================== -->

    <td data-label="Contact">

        <div class="crm-contact">

            <span class="crm-contact-icon">

                <i class="fa fa-phone"></i>

            </span>

            <span>
                <?= esc($row['contact_number'] ?? '') ?>
            </span>

        </div>

    </td>


    <!-- =================================================
                                 STATUS
                            ================================================== -->

    <td data-label="Status">

        <?php

        $status = strtolower(
            trim($row['status'] ?? '')
        );

        $statusClass = match ($status) {

            'pending'
            => 'crm-status crm-status-pending',

            'contacted'
            => 'crm-status crm-status-contacted',

            'completed'
            => 'crm-status crm-status-completed',

            'closed business'
            => 'crm-status crm-status-closed',

            'business_done'
            => 'crm-status crm-status-business',

            'not converted'
            => 'crm-status crm-status-not-converted',

            'cancelled'
            => 'crm-status crm-status-cancelled',

            default
            => 'crm-status crm-status-pending'
        };

        ?>

        <span
            class="<?= $statusClass ?>"
            onclick="edit_refferral_status('<?= esc($row['referral_code'] ?? '', 'js') ?>');"
            title="Click to edit status">

            <span class="crm-status-dot"></span>

            <?= esc(
                ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $row['status'] ?? 'Pending'
                    )
                )
            ) ?>

            <i class="fa fa-angle-down crm-status-arrow"></i>

        </span>

    </td>


    <!-- =================================================
                                 CREATED DATE
                            ================================================== -->

    <td data-label="Created">

        <div class="crm-date">

            <span class="crm-date-icon">

                <i class="fa fa-calendar"></i>

            </span>

            <div>

                <?php

                $createdDate = null;

                if (!empty($row['created_at'])) {

                    try {

                        $createdDate =
                            new DateTime(
                                $row['created_at']
                            );
                    } catch (\Exception $e) {

                        $createdDate = null;
                    }
                }

                ?>

                <?php if ($createdDate): ?>

                    <div class="crm-date-main">

                        <?= $createdDate->format('d M Y') ?>

                    </div>

                    <small>

                        <?= $createdDate->format('h:i A') ?>

                    </small>

                <?php else: ?>

                    <div class="crm-date-main">
                        —
                    </div>

                <?php endif; ?>

            </div>

        </div>

    </td>


    <!-- =================================================
                                 ACTIONS
                            ================================================== -->

    <td
        class="actions"
        data-label="Actions">

        <div class="crm-actions">

            <!-- VIEW -->

            <a
                href="<?= site_url('admin/connect/details/' . ($row['referral_code'] ?? '')) ?>"
                class="crm-action-btn crm-view"
                title="View Details">

                <i class="fa fa-eye"></i>

            </a>


            <!-- EDIT -->

            <a
                href="javascript:void(0);"
                onclick="edit_refferral('<?= esc($row['referral_code'] ?? '', 'js') ?>');"
                class="crm-action-btn crm-edit"
                title="Edit Connect">

                <i class="fa fa-edit"></i>

            </a>


            <!-- DELETE -->

            <a
                href="javascript:void(0);"
                onclick="delete_refferral('<?= esc($row['referral_code'] ?? '', 'js') ?>');"
                class="crm-action-btn crm-delete"
                title="Delete Connect">

                <i class="fa fa-trash"></i>

            </a>

        </div>

    </td>

</tr>