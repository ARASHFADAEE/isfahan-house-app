<header class="header-main">
            <div class="container-fluid">
              <div class="row">
                <div class="col-6 col-sm-4 d-flex align-items-center header-left p-0">
                  <span class="header-toggle me-3">
                    <i class="iconoir-view-grid"></i>
                  </span>
                </div>

                <div
                  class="col-6 col-sm-8 d-flex align-items-center justify-content-end header-right p-0">
                  <ul class="d-flex align-items-center">



                    <li class="header-searchbar">
                      <a
                        aria-controls="offcanvasRight"
                        class="d-block head-icon"
                        data-bs-target="#offcanvasRight"
                        data-bs-toggle="offcanvas"
                        href="#"
                        role="button">
                        <i class="iconoir-search"></i>
                      </a>

                      <div
                        aria-labelledby="offcanvasRight"
                        class="offcanvas offcanvas-start header-searchbar-canvas"
                        id="offcanvasRight"
                        tabindex="-1">
                        <div class="header-searchbar-header">
                          <div class="d-flex justify-content-between mb-3">
                            <form action="#" class="app-form app-icon-form w-100">
                              <div class="position-relative">
                                <input
                                  aria-label="جستجو"
                                  class="form-control search-filter"
                                  placeholder="جستجو..."
                                  type="search" />
                                <i class="ti ti-search text-dark"></i>
                              </div>
                            </form>

                            <div class="app-dropdown flex-shrink-0 d-flex align-items-center">
                              <a
                                aria-expanded="false"
                                class="h-35 w-35 d-flex-center b-r-15 overflow-hidden bg-light-secondary search-list-avtar ms-2"
                                data-bs-auto-close="outside"
                                data-bs-toggle="dropdown"
                                href="#"
                                role="button">
                                <i class="ph-duotone ph-gear f-s-20"></i>
                              </a>

                              <ul class="dropdown-menu mb-3">
                                <li class="dropdown-item mt-2">
                                  <a href="#">
                                    <h6 class="mb-0">تنظیمات جستجو</h6>
                                  </a>
                                </li>
                                <li
                                  class="dropdown-item d-flex align-items-center justify-content-between">
                                  <a href="#">
                                    <h6 class="mb-0 text-secondary f-s-14">فیلتر جستجوی ایمن</h6>
                                  </a>
                                  <div class="flex-shrink-0">
                                    <div class="form-check form-switch">
                                      <input
                                        checked
                                        class="form-check-input form-check-primary"
                                        id="searchSwitch"
                                        type="checkbox" />
                                    </div>
                                  </div>
                                </li>
                                <li
                                  class="dropdown-item d-flex align-items-center justify-content-between">
                                  <a href="#">
                                    <h6 class="mb-0 text-secondary f-s-14">پیشنهادات جستجو</h6>
                                  </a>
                                  <div class="flex-shrink-0">
                                    <div class="form-check form-switch">
                                      <input
                                        class="form-check-input form-check-primary"
                                        id="searchSwitch1"
                                        type="checkbox" />
                                    </div>
                                  </div>
                                </li>
                                <li
                                  class="dropdown-item d-flex align-items-center justify-content-between">
                                  <h6 class="mb-0 text-secondary f-s-14">تاریخچه جستجو</h6>
                                  <i class="ti ti-message-circle text-success"></i>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li
                                  class="dropdown-item d-flex align-items-center justify-content-between mb-2">
                                  <a href="#">
                                    <h6 class="mb-0 text-dark f-s-14">تنظیمات جستجوی سفارشی</h6>
                                  </a>
                                  <div class="flex-shrink-0">
                                    <div class="form-check form-switch">
                                      <input
                                        class="form-check-input form-check-primary"
                                        id="searchSwitch2"
                                        type="checkbox" />
                                    </div>
                                  </div>
                                </li>
                              </ul>
                            </div>
                          </div>
                          <p class="mb-0 text-secondary f-s-15 mt-2">داده‌های اخیراً جستجو شده:</p>
                        </div>
                        <div class="offcanvas-body app-scroll p-0">
                          <livewire:search-bar />
                          </div>
                      </div>
                    </li>



                    <li class="header-dark">
                      <div class="sun-logo head-icon">
                        <i class="iconoir-sun-light"></i>
                      </div>
                      <div class="moon-logo head-icon">
                        <i class="iconoir-half-moon"></i>
                      </div>
                    </li>

                    <li class="header-notification">
                      <a
                        aria-controls="notificationcanvasRight"
                        class="d-block head-icon position-relative"
                        data-bs-target="#notificationcanvasRight"
                        data-bs-toggle="offcanvas"
                        href="#"
                        role="button">
                        <i class="iconoir-bell"></i>
                        <span
                          class="position-absolute translate-middle p-1 bg-success border border-light rounded-circle animate__animated animate__fadeIn animate__infinite animate__slower"></span>
                      </a>
                      <div
                        aria-labelledby="notificationcanvasRightLabel"
                        class="offcanvas offcanvas-start header-notification-canvas"
                        id="notificationcanvasRight"
                        tabindex="-1">
                        <div class="offcanvas-header">
                          <h5 class="offcanvas-title" id="notificationcanvasRightLabel">
                            اعلان‌ها
                          </h5>
                          <button
                            aria-label="بستن"
                            class="btn-close"
                            data-bs-dismiss="offcanvas"
                            type="button"></button>
                        </div>
                        <div class="offcanvas-body notification-offcanvas-body app-scroll p-0">
                          @livewire('admin.header-notifications')
                        </div>
                      </div>
                    </li>

                    <li class="header-profile">
                      <a
                        aria-controls="profilecanvasRight"
                        class="d-block head-icon"
                        data-bs-target="#profilecanvasRight"
                        data-bs-toggle="offcanvas"
                        href="#"
                        role="button">
                        <img
                          alt="تصویر پروفایل"
                          class="b-r-50 h-35 w-35 bg-dark"
                          src="{{ asset('panel/assets/images/avtar/woman.jpg') }}" />
                      </a>

                      <div
                        aria-labelledby="profilecanvasRight"
                        class="offcanvas offcanvas-start header-profile-canvas"
                        id="profilecanvasRight"
                        tabindex="-1">
                        <div class="offcanvas-body app-scroll">
                          <ul class="">
                            <li class="d-flex gap-3 mb-3">
                              <div class="d-flex-center">
                                <span class="h-45 w-45 d-flex-center b-r-10 position-relative">
                                  <img
                                    alt=""
                                    class="img-fluid b-r-10"
                                    src="{{ asset('panel/assets/images/avtar/woman.jpg') }}" />
                                </span>
                              </div>
                              <div class="mt-2">
                                <h6 class="mb-0">
                                  {{ trim(((auth()->user()->first_name ?? '') . ' ' . (auth()->user()->last_name ?? ''))) ?: (auth()->user()->name ?? 'کاربر') }}
                                </h6>
                                <p class="f-s-12 mb-0 text-secondary">{{ auth()->user()->email ?? '-' }}</p>
                              </div>
                            </li>

                            <li>
                              <a class="f-w-500" href="{{ auth()->check() ? route('admin.users.edit', ['user' => auth()->id()]) : '#' }}">
                                <i class="iconoir-user-love pe-1 f-s-20"></i>
                                جزئیات پروفایل
                              </a>
                            </li>
                            <li>
                              <a class="f-w-500" href="{{ route('admin.settings.index') }}">
                                <i class="iconoir-settings pe-1 f-s-20"></i>
                                تنظیمات
                              </a>
                            </li>
                            <li class="app-divider-v dotted py-1"></li>
                            <li class="mx-0">
                              <form method="POST" action="{{ url('/logout') }}">
                                @csrf
                                <button type="submit" class="mb-0 btn btn-light-danger btn-sm justify-content-center">
                                  <i class="ph-duotone ph-sign-out pe-1 f-s-20"></i>
                                  خروج
                                </button>
                              </form>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </header>
