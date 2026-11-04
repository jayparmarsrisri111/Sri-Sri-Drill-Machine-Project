<!-- Customer Feedback Submission Modal -->
<div id="feedbackModal" class="quote-modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(4, 7, 13, 0.88); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); z-index: 10001; align-items: center; justify-content: center; padding: 20px;">
    <div class="quote-modal-card" style="background: #090d16; border: 1.5px solid #fbbf24; border-radius: 16px; width: 100%; max-width: 500px; box-shadow: 0 25px 60px rgba(0,0,0,0.9), 0 0 30px rgba(251, 191, 36, 0.2); overflow: hidden;">
        <div style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.98) 100%); padding: 16px 20px; border-bottom: 1px solid rgba(251, 191, 36, 0.3); display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 24px;">⭐</span>
                <div>
                    <h3 style="font-family: 'Space Grotesk', sans-serif; font-size: 16px; font-weight: 800; color: #fff; margin: 0;">Customer Feedback &amp; Review</h3>
                    <div style="font-size: 11px; color: #fbbf24; font-weight: 600;">Shree Varsha Engineering Works (Varsha Brand)</div>
                </div>
            </div>
            <button type="button" id="btnFeedbackModalClose" style="background:none; border:none; color:#94a3b8; font-size:26px; cursor:pointer; line-height:1;">&times;</button>
        </div>

        <form id="feedbackForm" style="padding: 22px;">
            <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">

            <!-- Rating Stars Selection -->
            <div style="text-align: center; margin-bottom: 18px; background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 12px;">
                <label style="display: block; font-size: 12px; color: #94a3b8; margin-bottom: 6px; font-weight: 700; text-transform: uppercase;">Overall Machine &amp; Factory Rating</label>
                <div class="star-rating-select" style="font-size: 28px; cursor: pointer; color: #fbbf24; display: flex; justify-content: center; gap: 6px;">
                    <span class="star-item" data-val="1">★</span>
                    <span class="star-item" data-val="2">★</span>
                    <span class="star-item" data-val="3">★</span>
                    <span class="star-item" data-val="4">★</span>
                    <span class="star-item" data-val="5">★</span>
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="5">
                <div id="ratingText" style="font-size: 12px; color: #34d399; font-weight: 700; margin-top: 4px;">5 / 5 Stars - Excellent Industrial Quality</div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 11.5px;">Your Name *</label>
                    <input type="text" name="customer_name" class="form-control" placeholder="e.g. Ramesh Patel" required style="font-size: 12.5px; padding: 8px 12px;">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 11.5px;">Company / Workshop</label>
                    <input type="text" name="company_name" class="form-control" placeholder="e.g. Patel Engineering" style="font-size: 12.5px; padding: 8px 12px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 11.5px;">City &amp; State</label>
                    <input type="text" name="city" class="form-control" placeholder="e.g. Bhavnagar, Gujarat" style="font-size: 12.5px; padding: 8px 12px;">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 11.5px;">Machine Model Used</label>
                    <input type="text" name="product_name" class="form-control" placeholder="e.g. 40mm Radial (SVEW-RD-40)" style="font-size: 12.5px; padding: 8px 12px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 12px;">
                <label class="form-label" style="font-size: 11.5px;">Review Headline</label>
                <input type="text" name="feedback_title" class="form-control" placeholder="e.g. Outstanding rigidity and accurate drilling" style="font-size: 12.5px; padding: 8px 12px;">
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="font-size: 11.5px;">Your Experience / Feedback Comments *</label>
                <textarea name="comments" class="form-control" rows="3" placeholder="Share your experience regarding machine performance, gear noise, drilling capacity, delivery, and service..." required style="font-size: 12.5px; padding: 8px 12px;"></textarea>
            </div>

            <div id="feedbackResponseMsg" style="display:none; font-size:12.5px; padding:10px; border-radius:6px; margin-bottom:12px; text-align:center;"></div>

            <button type="submit" id="btnSubmitFeedback" class="btn btn-primary" style="width: 100%; padding: 12px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-color: #fbbf24; font-weight: 800; font-size: 14px; box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);">
                ⭐ Submit Review &amp; Feedback
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const feedbackModal = document.getElementById('feedbackModal');
    const btnClose = document.getElementById('btnFeedbackModalClose');
    const feedbackForm = document.getElementById('feedbackForm');
    const btnSubmit = document.getElementById('btnSubmitFeedback');
    const responseMsg = document.getElementById('feedbackResponseMsg');
    const stars = document.querySelectorAll('.star-item');
    const ratingInput = document.getElementById('ratingInput');
    const ratingText = document.getElementById('ratingText');

    const ratingDescriptions = {
        1: '1 / 5 Stars - Needs Improvement',
        2: '2 / 5 Stars - Fair Performance',
        3: '3 / 5 Stars - Good Quality',
        4: '4 / 5 Stars - Very Good & Reliable',
        5: '5 / 5 Stars - Excellent Industrial Quality'
    };

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const val = parseInt(this.getAttribute('data-val'));
            ratingInput.value = val;
            ratingText.textContent = ratingDescriptions[val] || (val + ' / 5 Stars');
            stars.forEach((s, idx) => {
                s.style.color = (idx < val) ? '#fbbf24' : '#475569';
            });
        });
    });

    window.openFeedbackModal = function() {
        if (feedbackModal) {
            feedbackModal.style.display = 'flex';
            if (responseMsg) responseMsg.style.display = 'none';
        }
    };

    if (btnClose) {
        btnClose.addEventListener('click', function() {
            feedbackModal.style.display = 'none';
        });
    }

    if (feedbackModal) {
        feedbackModal.addEventListener('click', function(e) {
            if (e.target === feedbackModal) {
                feedbackModal.style.display = 'none';
            }
        });
    }

    if (feedbackForm) {
        feedbackForm.addEventListener('submit', function(e) {
            e.preventDefault();
            btnSubmit.disabled = true;
            btnSubmit.textContent = 'Submitting Feedback...';

            const formData = new FormData(feedbackForm);
            fetch('<?= BASE_URL ?>/feedback-submit.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                responseMsg.style.display = 'block';
                if (data.success) {
                    responseMsg.style.background = 'rgba(16, 185, 129, 0.2)';
                    responseMsg.style.border = '1px solid #10b981';
                    responseMsg.style.color = '#34d399';
                    responseMsg.textContent = data.message;
                    feedbackForm.reset();
                    setTimeout(function() {
                        feedbackModal.style.display = 'none';
                        btnSubmit.disabled = false;
                        btnSubmit.textContent = '⭐ Submit Review & Feedback';
                    }, 2500);
                } else {
                    responseMsg.style.background = 'rgba(239, 68, 68, 0.2)';
                    responseMsg.style.border = '1px solid #ef4444';
                    responseMsg.style.color = '#f87171';
                    responseMsg.textContent = data.message;
                    btnSubmit.disabled = false;
                    btnSubmit.textContent = '⭐ Submit Review & Feedback';
                }
            })
            .catch(err => {
                responseMsg.style.display = 'block';
                responseMsg.style.background = 'rgba(239, 68, 68, 0.2)';
                responseMsg.style.color = '#f87171';
                responseMsg.textContent = 'Network error. Please try again or WhatsApp us directly.';
                btnSubmit.disabled = false;
                btnSubmit.textContent = '⭐ Submit Review & Feedback';
            });
        });
    }
});
</script>
