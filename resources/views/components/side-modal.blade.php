<div class="offcanvas offcanvas-end  {{ $class }}" tabindex="-1" id="{{ $id }}"
    aria-labelledby="offcanvasEndLabel_{{ $id }}">
    <div class="offcanvas-header">
        <h5 id="offcanvasEndLabel_{{ $id }}" class="offcanvas-title">{{ $title ?? 'Header' }}</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body  mx-0 flex-grow-0">
        {{ $slot ?? '' }}
    </div>
</div>
