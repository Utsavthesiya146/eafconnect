<!-- EVENT MODAL -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modern-modal">

            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle">
                    Add Event
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- FORM -->
            <form id="eventForm" method="post" action="<?= base_url('admin/events/save') ?>">

                <div class="modal-body">

                    <input type="hidden" name="event_id" id="event_id">

                    <div class="row g-3">

                        <!-- TITLE -->
                        <div class="col-md-6">
                            <label class="form-label">Event Title</label>
                            <input type="text" name="title" id="title"
                                class="form-control" required>
                        </div>

                        <!-- DATE -->
                        <div class="col-md-6">
                            <label class="form-label">Date & Time</label>
                            <input type="datetime-local" name="date" id="date"
                                class="form-control" required>
                        </div>

                        <!-- CHAPTER -->
                        <div class="col-md-6">
                            <label class="form-label">Chapter</label>
                            <select name="chapter_code" id="chapter_code"
                                class="form-select" required>
                                <option value="">Select Chapter</option>

                                <?php foreach ($chapters as $c): ?>
                                    <option value="<?= $c['chapter_code'] ?>">
                                        <?= esc($c['chapter_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- MODE -->
                        <div class="col-md-6">
                            <label class="form-label">Mode</label>
                            <select name="mode" id="mode" class="form-select">
                                <option value="offline">Offline</option>
                                <option value="online">Online</option>
                                <option value="hybrid">Hybrid</option>
                            </select>
                        </div>

                        <!-- LOCATION -->
                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" id="location"
                                class="form-control">
                        </div>

                        <!-- MEETING LINK -->
                        <div class="col-md-6" id="meetingLinkBox" style="display:none;">
                            <label class="form-label">Meeting Link</label>
                            <input type="url" name="meeting_link"
                                id="meeting_link"
                                class="form-control"
                                placeholder="https://">
                        </div>

                        <!-- DESCRIPTION -->
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="description"
                                class="form-control" rows="3"></textarea>
                        </div>

                        <!-- NOTE -->
                        <div class="col-md-12">
                            <label class="form-label">Note</label>
                            <input type="text" name="note" id="note"
                                class="form-control">
                        </div>

                    </div>
                </div>
                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary px-4">
                        Save Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>