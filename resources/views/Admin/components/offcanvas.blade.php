<div class="offcanvas offcanvas-end shadow-lg" tabindex="-1" id="overviewOffcanvas" aria-labelledby="overviewOffcanvasLabel">
    <!-- Header -->
    <div class="offcanvas-header bg-primary-subtle">
        <h5 class="offcanvas-title d-flex align-items-center" id="overviewOffcanvasLabel">
            <i class="bi bi-folder2-open me-2"></i>
            Category Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <!-- Body -->
    <div class="offcanvas-body p-0">
        <!-- Cover Image Section -->
        <div class="position-relative mb-3">
            <div class="cover-image-container">
                <img src="{{ asset('assets/images/placeholder.png') }}" alt="Cover" class="img-fluid overview-cover-img w-100" style="height: 180px; object-fit: cover;">
                <div class="cover-image-overlay d-none">
                    <i class="bi bi-image text-light display-1"></i>
                </div>
            </div>
            <div class="category-title-badge   shadow-sm position-absolute bottom-0 start-0 m-3 p-2 rounded">
                <h5 class="overview-title fw-bold mb-0 fs-5">Category Title</h5>
            </div>
        </div>

        <!-- Content Container -->
        <div class="px-4">
            <!-- Thumbnail and Info -->
            <div class="row g-3 mb-4 align-items-center">
                <div class="col-5">
                    <div class="thumbnail-container position-relative rounded shadow-sm overflow-hidden">
                        <img src="" alt="Thumbnail" class="img-fluid overview-thumb-img w-100" style="height: 120px; object-fit: cover;">
                        <div class="thumbnail-overlay d-none">
                            <i class="bi bi-card-image text-light fs-1"></i>
                        </div>
                    </div>
                    <p class="text-center mt-2 text-muted small fw-medium">Thumbnail</p>
                </div>
                <div class="col-7">
                    <div class="category-meta p-3 bg-light rounded">
                        <div class="category-badge mb-2">
                            <span class="badge bg-primary-subtle text-primary"><i class="bi bi-bookmark-fill me-1"></i>Main Category</span>
                        </div>
                        <div class="category-stats d-flex justify-content-between text-muted small">
                            <span><i class="bi bi-grid me-1"></i>Subcategories: <span class="subcategory-count fw-medium">0</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subcategories Section -->
            <div class="subcategories-section mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-diagram-3 me-2"></i>Subcategories</h6>
                    <span class="badge bg-secondary-subtle text-secondary subcategory-count-badge">0</span>
                </div>
                <div class="subcategory-list p-3 bg-light rounded">
                    <ul class="vstack gap-2 mb-0 subCategory ps-3" style="list-style-type: none;">
                        <li class="text-muted d-flex align-items-center"><i class="bi bi-info-circle me-2"></i>No subcategories available</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Footer -->
    <div class="p-3 border-top">
        <div class="row">
            <!-- Delete -->
            <div class="col-sm-6">
                <div data-bs-dismiss="offcanvas">
                    <button type="button" class="btn btn-danger w-100 remove-list" data-bs-toggle="modal"
                        data-id="" data-bs-target="">
                        <i class="ri-delete-bin-line me-1 align-bottom"></i> Delete
                    </button>
                </div>
            </div>

            <!-- Edit -->
            <div class="col-sm-6">
                <button type="button" class="btn btn-secondary w-100 edit-list" data-id=""
                    data-bs-dismiss="offcanvas">
                    <i class="ri-pencil-line me-1 align-bottom"></i> Edit
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.offcanvas-backdrop.show {
    z-index: 1040;
}

.offcanvas {
    z-index: 1060 !important;
    width: 400px !important;
}

/* Image containers */
.cover-image-container, 
.thumbnail-container {
    position: relative;
    background-color: #f0f2f5;
    border-radius: 4px;
    overflow: hidden;
}

/* Overlay styling for missing images */
.cover-image-overlay,
.thumbnail-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(108, 117, 125, 0.5);
}

/* Subcategory list styling */
.subcategory-list {
    max-height: 300px;
    overflow-y: auto;
}

.subCategory li {
    padding: 8px 10px;
    border-radius: 4px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
}

.subCategory li:not(:last-child) {
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.subCategory li:hover {
    background-color: rgba(13, 110, 253, 0.05);
}

.subCategory li a {
    text-decoration: none;
    display: block;
    width: 100%;
}

/* Make the design more modern */
.category-meta {
    border-left: 3px solid var(--bs-primary);
}

/* Improve button styling */
.btn {
    border-radius: 4px;
    font-weight: 500;
}
</style>