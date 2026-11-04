<!-- Quick Quote Modal -->
<div class="modal-overlay" id="quoteModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Request Machine Quotation</h3>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Direct Factory Price • Fast Response • Genuine Specifications</p>
            </div>
            <button type="button" class="modal-close" aria-label="Close modal">&times;</button>
        </div>
        <div class="modal-body">
            <form action="<?= BASE_URL ?>/get-quote.php" method="POST" class="inquiry-form">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <input type="hidden" name="machine_id" id="modalMachineId" value="">
                
                <div class="form-group">
                    <label class="form-label">Selected Machine Model</label>
                    <input type="text" name="machine_name" id="modalMachineName" class="form-control" placeholder="e.g. 40mm All-Geared Radial Drill Machine" style="background: rgba(0, 229, 255, 0.05); border-color: var(--border-cyan); color: var(--accent-cyan); font-weight: 600;">
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Your Name *</label>
                        <input type="text" name="customer_name" class="form-control" placeholder="e.g. Rajesh Patel" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Company / Workshop Name</label>
                        <input type="text" name="company_name" class="form-control" placeholder="e.g. Patel Engineering">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Mobile Number *</label>
                        <input type="tel" name="phone" class="form-control" placeholder="e.g. +91 9898123456" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control" placeholder="e.g. yourname@gmail.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Your Requirement / Specifications / Delivery City</label>
                    <textarea name="message" class="form-control" rows="3" placeholder="Please mention drilling capacity required, material (Steel/C.I.), number of units, and your city for shipping calculation..."></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #cbd5e1; cursor: pointer;">
                        <input type="checkbox" name="open_whatsapp" value="1" checked style="accent-color: var(--accent-cyan);">
                        <span>Also connect me immediately on WhatsApp for instant quote</span>
                    </label>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        ⚡ Send Factory Inquiry
                    </button>
                </div>

                <div style="margin-top: 15px; text-align: center; font-size: 12.5px; color: var(--text-muted);">
                    Direct Assistance: <a href="tel:<?= COMPANY_PHONE_1_CLEAN ?>" style="color: var(--accent-cyan); font-weight: 600;"><?= COMPANY_PHONE_1 ?></a> / <a href="tel:<?= COMPANY_PHONE_2_CLEAN ?>" style="color: var(--accent-cyan); font-weight: 600;"><?= COMPANY_PHONE_2 ?></a>
                </div>
            </form>
        </div>
    </div>
</div>
