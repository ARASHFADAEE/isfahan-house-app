<div class="p-3">
  <div class="mb-3">
    <label class="form-label">جستجو</label>
    <div class="position-relative">
      <input type="search" class="form-control" placeholder="نام کاربر، ایمیل، تلفن یا نام شعبه" wire:model.live.debounce.300ms="query" />
      <i class="ti ti-search text-dark"></i>
    </div>
  </div>

  @if (trim($query) === '')
    <p class="mb-2 text-secondary">میان‌برها</p>
    <ul class="list-group mb-3">
      @foreach ($shortcuts as $s)
        <a href="{{ $s['url'] }}" class="list-group-item list-group-item-action d-flex align-items-center">
          <i class="{{ $s['icon'] }} me-2 f-s-20"></i>
          <span>{{ $s['label'] }}</span>
        </a>
      @endforeach
    </ul>
  @endif

  @if (!empty($users) || !empty($branches))
    @if (!empty($users))
      <p class="mb-2 text-secondary">کاربران</p>
      <ul class="list-group mb-3">
        @foreach ($users as $u)
          <a href="{{ $u['url'] }}" class="list-group-item list-group-item-action">
            {{ $u['label'] }}
          </a>
        @endforeach
      </ul>
    @endif

    @if (!empty($branches))
      <p class="mb-2 text-secondary">شعبه‌ها</p>
      <ul class="list-group mb-3">
        @foreach ($branches as $b)
          <a href="{{ $b['url'] }}" class="list-group-item list-group-item-action">
            {{ $b['label'] }}
          </a>
        @endforeach
      </ul>
    @endif
  @elseif (trim($query) !== '')
    <div class="text-secondary">نتیجه‌ای یافت نشد.</div>
  @endif
</div>