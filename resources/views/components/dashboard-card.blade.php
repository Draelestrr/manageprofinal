<div class="col-xl-3 col-sm-6 mb-4">
    <div class="card h-100 shadow-sm">
        <div class="card-header p-3 pt-2 position-relative">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <h4 class="mb-0 {{ $textColor }}">{{ $value }}</h4>
                </div>
                <div>
                    <p class="text-sm mb-0 text-capitalize {{ $textColor }}">{{ $title }}</p>
                </div>
            </div>
            <div class="icon icon-shape {{ $color }} shadow text-center border-radius-xl position-absolute top-0 end-0 mt-2 me-2">
                <i class="material-symbols-rounded text-white">{{ $icon }}</i>
            </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
            <p class="mb-0"><span class="text-success text-sm font-weight-bolder">{{ $description }}</span></p>
        </div>
    </div>
</div>
