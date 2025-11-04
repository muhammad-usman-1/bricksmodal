@extends('layouts.admin')
@section('content')

<div class="talent-profile-view">
    <!-- Header -->
    <div class="page-title">
        <h1>View Profile</h1>
    </div>

    <!-- Profile Card -->
    <div class="profile-card">
        <div class="profile-photo">
            @php
                // Get the best available photo for profile display
                $photoUrl = null;
                $photoFields = [
                    $talentProfile->headshot_center_path,
                    $talentProfile->headshot_left_path,
                    $talentProfile->headshot_right_path,
                    $talentProfile->full_body_front_path,
                    $talentProfile->full_body_right_path,
                    $talentProfile->full_body_back_path,
                ];

                foreach ($photoFields as $field) {
                    if ($field) {
                        $photoUrl = $field;
                        break;
                    }
                }

                // Ensure proper photo URL
                if ($photoUrl) {
                    if (!str_starts_with($photoUrl, 'http') && !str_starts_with($photoUrl, '/storage/')) {
                        $photoUrl = \Storage::url($photoUrl);
                    }
                } else {
                    // Fallback to generated avatar
                    $name = $talentProfile->display_name ?: $talentProfile->legal_name ?: 'Unknown';
                    $photoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=f0f0f0&color=666&size=80';
                }
            @endphp
            <img src="{{ $photoUrl }}" alt="{{ $talentProfile->display_name ?: $talentProfile->legal_name }}">
        </div>
        <div class="profile-info">
            <h2>{{ $talentProfile->display_name ?: $talentProfile->legal_name }}</h2>
            <p class="profile-details">
                {{ ucfirst($talentProfile->gender ?? 'Not specified') }},
                age: {{ $talentProfile->age ?? 'N/A' }},
                height: {{ $talentProfile->height ?? 'N/A' }},
                weight: {{ $talentProfile->weight ?? 'N/A' }}
            </p>
            @php
                // Status display
                $status = $talentProfile->verification_status ?? 'pending';
                $statusLabels = [
                    'pending' => 'Pending',
                    'approved' => 'Verified',
                    'verified' => 'Verified',
                    'rejected' => 'Rejected',
                ];
                $statusDisplay = $statusLabels[$status] ?? ucfirst($status);
            @endphp
            <div class="profile-status">
                <span class="status-label">Status: </span>
                <span class="status-value status-{{ $status }}">
                    {{ $statusDisplay }}
                </span>
            </div>
        </div>
    </div>

    <!-- ID Images Section -->
    <div class="photo-section">
        <h4>ID Images</h4>
        <div class="photo-grid">
            @if($talentProfile->id_front_path)
                <div class="photo-card">
                    <img src="{{ $talentProfile->id_front_path }}" alt="ID Front">
                </div>
            @else
                <div class="photo-card placeholder">
                    <i class="fas fa-id-card"></i>
                    <span>ID Front</span>
                </div>
            @endif

            @if($talentProfile->id_back_path)
                <div class="photo-card">
                    <img src="{{ $talentProfile->id_back_path }}" alt="ID Back">
                </div>
            @else
                <div class="photo-card placeholder">
                    <i class="fas fa-id-card"></i>
                    <span>ID Back</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Head Shot Section -->
    <div class="photo-section">
        <h4>Head Shot</h4>
        <div class="photo-grid">
            @if($talentProfile->headshot_center_path)
                <div class="photo-card">
                    <img src="{{ $talentProfile->headshot_center_path }}" alt="Center Headshot">
                </div>
            @else
                <div class="photo-card placeholder">
                    <i class="fas fa-user"></i>
                    <span>Center</span>
                </div>
            @endif

            @if($talentProfile->headshot_left_path)
                <div class="photo-card">
                    <img src="{{ $talentProfile->headshot_left_path }}" alt="Left Headshot">
                </div>
            @else
                <div class="photo-card placeholder">
                    <i class="fas fa-user"></i>
                    <span>Left</span>
                </div>
            @endif

            @if($talentProfile->headshot_right_path)
                <div class="photo-card">
                    <img src="{{ $talentProfile->headshot_right_path }}" alt="Right Headshot">
                </div>
            @else
                <div class="photo-card placeholder">
                    <i class="fas fa-user"></i>
                    <span>Right</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Portfolio Section -->
    <div class="photo-section">
        <h4>Portfolio</h4>
        <div class="photo-grid">
            @if($talentProfile->full_body_front_path)
                <div class="photo-card">
                    <img src="{{ $talentProfile->full_body_front_path }}" alt="Full Body Front">
                </div>
            @else
                <div class="photo-card placeholder">
                    <i class="fas fa-camera"></i>
                    <span>Front</span>
                </div>
            @endif

            @if($talentProfile->full_body_right_path)
                <div class="photo-card">
                    <img src="{{ $talentProfile->full_body_right_path }}" alt="Full Body Side">
                </div>
            @else
                <div class="photo-card placeholder">
                    <i class="fas fa-camera"></i>
                    <span>Side</span>
                </div>
            @endif

            @if($talentProfile->full_body_back_path)
                <div class="photo-card">
                    <img src="{{ $talentProfile->full_body_back_path }}" alt="Full Body Back">
                </div>
            @else
                <div class="photo-card placeholder">
                    <i class="fas fa-camera"></i>
                    <span>Back</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Notes Section -->
    <div class="notes-section">
        <h4>Notes</h4>
        <textarea
            class="notes-textarea"
            placeholder="Add notes about this talent profile..."
            id="adminNotes">{{ $talentProfile->verification_notes }}</textarea>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        @if($talentProfile->verification_status === 'pending')
            <button class="action-btn approve-btn" data-talent-id="{{ $talentProfile->id }}" data-talent-name="{{ $talentProfile->display_name ?: $talentProfile->legal_name }}">
                Approve
            </button>
            <button class="action-btn reject-btn" data-talent-id="{{ $talentProfile->id }}" data-talent-name="{{ $talentProfile->display_name ?: $talentProfile->legal_name }}">
                Reject
            </button>
            <button class="action-btn request-changes-btn" data-talent-id="{{ $talentProfile->id }}" data-talent-name="{{ $talentProfile->display_name ?: $talentProfile->legal_name }}">
                Request Changes
            </button>
        @else
            <div class="status-display">
                <span class="status-badge status-{{ $talentProfile->verification_status }}">
                    {{ ucfirst($talentProfile->verification_status) }}
                </span>
                @if($talentProfile->verification_notes)
                    <p class="status-notes">{{ $talentProfile->verification_notes }}</p>
                @endif
            </div>
        @endif
    </div>
</div>



@endsection

@section('styles')
@parent
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .talent-profile-view {

        min-height: 100vh;
    }

    .page-title {
        margin-bottom: 20px;
    }

    .page-title h1 {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .profile-card {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }

    .profile-photo {
        flex-shrink: 0;
    }

    .profile-photo img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #f0f0f0;
    }

    .profile-info {
        flex: 1;
    }

    .profile-info h2 {
        font-size: 20px;
        font-weight: 700;
        color: #333;
        margin: 0 0 8px 0;
    }

    .profile-details {
        font-size: 14px;
        color: #666;
        margin: 0 0 8px 0;
        line-height: 1.4;
    }

    .profile-status {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .status-label {
        font-size: 14px;
        color: #333;
        font-weight: 500;
    }

    .status-value {
        font-size: 14px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-value.status-approved,
    .status-value.status-verified {
        color: #28a745;
    }

    .status-value.status-pending {
        color: #ffc107;
    }

    .status-value.status-rejected {
        color: #dc3545;
    }

    .photo-section {
        margin-bottom: 32px;
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .photo-section h4 {
        margin: 0 0 16px 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .photo-card {
        aspect-ratio: 3/4;
        border-radius: 12px;
        overflow: hidden;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid #e9ecef;
    }

    .photo-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .photo-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-card.placeholder {
        background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
        color: white;
        font-weight: 500;
    }

    .photo-card.placeholder i {
        font-size: 32px;
        margin-bottom: 8px;
        opacity: 0.8;
    }

    .photo-card.placeholder span {
        font-size: 14px;
        opacity: 0.9;
    }

    .notes-section {
        margin-bottom: 32px;
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .notes-section h4 {
        margin: 0 0 16px 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .notes-textarea {
        width: 100%;
        min-height: 120px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 16px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-size: 14px;
        line-height: 1.5;
        resize: vertical;
        outline: none;
        transition: border-color 0.2s;
    }

    .notes-textarea:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
        padding: 20px 0;
    }

    .action-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        min-width: 120px;
    }

    .action-btn.approve-btn {
        background: #28a745;
        color: white;
    }

    .action-btn.approve-btn:hover {
        background: #218838;
        transform: translateY(-1px);
    }

    .action-btn.reject-btn {
        background: #dc3545;
        color: white;
    }

    .action-btn.reject-btn:hover {
        background: #c82333;
        transform: translateY(-1px);
    }

    .action-btn.request-changes-btn {
        background: #6c757d;
        color: white;
    }

    .action-btn.request-changes-btn:hover {
        background: #5a6268;
        transform: translateY(-1px);
    }

    .status-display {
        text-align: center;
        padding: 20px;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.status-approved {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.status-rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .status-badge.status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-notes {
        margin: 12px 0 0 0;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
        font-style: italic;
        color: #666;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .talent-profile-view {
            padding: 16px;
        }

        .profile-card {
            flex-direction: column;
            text-align: center;
            gap: 16px;
        }

        .profile-photo img {
            width: 100px;
            height: 100px;
        }

        .photo-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .action-buttons {
            flex-direction: column;
            align-items: center;
        }

        .action-btn {
            width: 100%;
            max-width: 200px;
        }
    }

    /* Image Modal Styles */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.9);
    }

    .image-modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        max-height: 80%;
        margin-top: 50px;
    }

    .image-modal-close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
    }

    .image-modal-close:hover {
        color: #bbb;
    }
</style>
@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image modal functionality
    const photoCards = document.querySelectorAll('.photo-card img');

    // Create modal
    const modal = document.createElement('div');
    modal.className = 'image-modal';
    modal.innerHTML = `
        <span class="image-modal-close">&times;</span>
        <img class="image-modal-content">
    `;
    document.body.appendChild(modal);

    const modalImg = modal.querySelector('.image-modal-content');
    const closeBtn = modal.querySelector('.image-modal-close');

    // Open modal on image click
    photoCards.forEach(img => {
        img.addEventListener('click', function() {
            modal.style.display = 'block';
            modalImg.src = this.src;
        });
    });

    // Close modal
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Action button functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('approve-btn')) {
            handleTalentAction(e.target, 'approve', 'Approve Talent');
        }

        if (e.target.classList.contains('reject-btn')) {
            handleTalentAction(e.target, 'reject', 'Reject Talent');
        }

        if (e.target.classList.contains('request-changes-btn')) {
            handleTalentAction(e.target, 'request-changes', 'Request Changes');
        }
    });

    function handleTalentAction(btn, action, actionTitle) {
        const talentId = btn.dataset.talentId;
        const talentName = btn.dataset.talentName;
        const notes = document.getElementById('adminNotes').value;
        const isApprove = action === 'approve';
        const isReject = action === 'reject';

        Swal.fire({
            title: actionTitle,
            html: `
                <p>Are you sure you want to ${action.replace('-', ' ')} <strong>${talentName}</strong>?</p>
                <div class="mt-3">
                    <label class="form-label fw-bold">Notes ${isReject ? '(required)' : '(optional)'}:</label>
                    <textarea id="modalNotes" class="form-control" rows="3" placeholder="Add any notes or feedback...">${notes}</textarea>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: isApprove ? '#28a745' : (isReject ? '#dc3545' : '#6c757d'),
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${actionTitle}!`,
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const modalNotes = document.getElementById('modalNotes').value;
                if (isReject && !modalNotes.trim()) {
                    Swal.showValidationMessage('Notes are required for rejection');
                    return false;
                }
                return modalNotes;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/talent-profiles/${talentId}/${action}`;

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = '{{ csrf_token() }}';

                const notesInput = document.createElement('input');
                notesInput.type = 'hidden';
                notesInput.name = 'notes';
                notesInput.value = result.value || '';

                form.appendChild(tokenInput);
                form.appendChild(notesInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
});
</script>
@endsection
