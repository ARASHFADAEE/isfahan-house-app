<div class="head-container notification-head-container">
  @forelse($this->items as $item)
    <div class="notification-message head-box">
      <div class="message-images">
        <span class="bg-secondary h-35 w-35 d-flex-center b-r-10 position-relative">
          <img alt="تصویر کاربر" class="img-fluid b-r-10" src="{{ $item['avatar'] }}" />
          <span class="position-absolute end-0 p-1 bg-secondary border border-light rounded-circle notification-avtar"></span>
        </span>
      </div>
      <div class="message-content-box flex-grow-1 ps-2">
        <a class="f-s-15 text-secondary mb-0" href="{{ $item['link'] }}">
          <span class="f-w-600 text-secondary">{{ $item['title'] }}</span>
          — {{ $item['message'] }}
        </a>
        <span class="badge {{ $item['badge_class'] }} mt-2">{{ $item['date_badge'] }}</span>
      </div>
      <div class="align-self-start text-end">
        <i class="iconoir-xmark close-btn"></i>
      </div>
    </div>
  @empty
    <div class="hidden-massage py-4 px-3">
      <img alt="بدون اعلان" class="w-50 h-50 mb-3 mt-2" src="{{ asset('panel/assets/images/icons/bell.png') }}" />
      <div>
        <h6 class="mb-0">اعلانی یافت نشد</h6>
        <p class="text-secondary">وقتی اعلانی داشته باشید، اینجا نمایش داده خواهد شد.</p>
      </div>
    </div>
  @endforelse
</div>