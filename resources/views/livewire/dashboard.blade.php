<div>
          <main>
            <div class="container-fluid mt-3">
              <div class="row">
                <div class="col-sm-6 col-lg-4 col-xxl-2 order--1-lg">
                  <div class="row">
                    <div class="col-12">
                      <div class="card orders-provided-card">
                        <div class="card-body">
                          <i class="ph-bold ph-circle circle-bg-img"></i>
                          <div>
                            <p class="f-s-18 f-w-600 text-dark txt-ellipsis-1">📈 اشتراک های ثبت شده</p>
                            <h2 class="text-secondary-dark mb-0">{{ $activeSubscriptions }}/{{ number_format($totalSubscriptions) }}</h2>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="card bg-primary-300 product-sold-card">
                        <div class="card-body">
                          <div>
                            <h5 class="text-primary-dark f-w-600">آخرین  تراکنش امروز</h5>
                            <p class="text-dark f-w-600 mb-0 mt-2 txt-ellipsis-1">
                              <i class="iconoir-calendar f-s-16 align-text-top me-2"></i>
                              @if($latestTransactionToday)
                                {{ $latestTransactionToday['at_jalali'] }} · {{ $latestTransactionToday['user'] }} — {{ $latestTransactionToday['branch'] }}
                              @else
                                تراکنشی برای امروز ثبت نشده است
                              @endif
                            </p>
                          </div>
                          <div class="my-4">
                            <h4 class="text-primary-dark">
                              @if($latestTransactionToday)
                                {{ number_format($latestTransactionToday['amount']) }}
                              @else
                                —
                              @endif
                            </h4>
                          </div>
                          <div class="custom-progress-container">
                            <div class="progress-bar productive"></div>
                            <div class="progress-bar middle"></div>
                            <div class="progress-bar idle"></div>
                          </div>
                          <div class="progress-labels">
                            <span>پربازده</span>
                            <span>میانه</span>
                            <span>بیکار</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-6 col-lg-4 col-xxl-2 order--1-lg">
                  <div class="row">
                    <div class="col-12">
                      <div class="card bg-danger-300 product-sold-card">
                        <div class="card-body">
                          <div>
                            <h5 class="text-danger-dark f-w-600">میزان فروش اشتراک</h5>
                            <div id="productSold"></div>
                          </div>
                          <div>
                            <h4>{{ number_format($sales['current_week']) }} تومان</h4>
                            <p class="mb-0 text-dark f-w-500 txt-ellipsis-1">
                              هفته گذشته
                              @php
                                $delta = $sales['delta_percent'];
                                $deltaSign = $delta >= 0 ? '+' : '';
                                $deltaTextClass = $delta >= 0 ? 'text-success-dark' : 'text-danger-dark';
                              @endphp
                              <span class="badge bg-white-300 {{ $deltaTextClass }} ms-2">{{ $deltaSign }}{{ $delta }}%</span>
                            </p>
                          </div>
                          <a
                            class="bg-danger h-35 w-35 d-flex-center b-r-50 product-sold-icon"
                            href="./orders_details.html">
                            <i
                              class="iconoir-arrow-right f-w-600 f-s-18 animate__pulse animate__fadeOutRight animate__infinite animate__slower"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="card product-store-card">
                        <div class="card-body">
                          <i class="ph-bold ph-circle circle-bg-img"></i>
                          <div>
                            <p class="text-success f-s-18 f-w-600 txt-ellipsis-1">
                              تعداد میز های آزاد
                            </p>
                            <h2 class="text-success-dark mb-0 ltr-text">{{ number_format($freeDesks) }}</h2>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="col-md-5 col-lg-4 col-xxl-3 order--1-lg">
                  <div class="card order-detail-card">
                    <div class="pt-3">
                      <h5 class="pa-s-20">اشتراک های اخیر</h5>
                    </div>
                    <div class="card-body">
                      <ul class="order-content-list">
                        @forelse($recentSubscriptions as $item)
                          <li class="bg-success-300">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="text-success-dark f-w-600 mb-0">{{ $item['user'] }} — {{ $item['branch'] }}</h6>
                              <span class="badge text-light-success me-2">
                                @switch($item['status'])
                                  @case('pending') در انتظار تایید ادمین @break
                                  @case('active') فعال @break
                                  @case('expired') منقضی‌شده @break
                                  @default وضعیت نامشخص
                                @endswitch
                              </span>
                            </div>
                            <div>
                              <p class="text-success mb-0 txt-ellipsis-2">
                                {{ $item['created_jalali'] }}
                              </p>
                            </div>
                          </li>
                        @empty
                          <li class="bg-primary-300">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="text-primary-dark f-w-600 mb-0">موردی برای نمایش وجود ندارد</h6>
                            </div>
                          </li>
                        @endforelse
                      </ul>
                    </div>
                  </div>
                </div>

                <!-- System Notifications on Dashboard -->
                <div class="col-md-7 col-lg-4 col-xxl-3 order--1-lg">
                  <div class="card order-detail-card">
                    <div class="pt-3">
                      <h5 class="pa-s-20">اعلان‌های سیستم</h5>
                    </div>
                    <div class="card-body">
                      <ul class="order-content-list">
                        @forelse($dashboardNotifications as $n)
                          <li class="bg-primary-300">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="text-primary-dark f-w-600 mb-0">{{ $n['user'] }}</h6>
                              <span class="badge text-light-primary me-2">
                                @switch($n['event_type'])
                                  @case('subscription_expiring') رو‌به‌اتمام @break
                                  @case('subscription_expired') منقضی‌شده @break
                                  @case('subscription_created') ایجاد اشتراک @break
                                  @case('subscription_approved') تایید اشتراک @break
                                  @default اعلان
                                @endswitch
                              </span>
                            </div>
                            <div>
                              <p class="text-primary mb-1 txt-ellipsis-2">{{ $n['message'] }}</p>
                              <small class="text-secondary">{{ $n['created_jalali'] }}</small>
                            </div>
                          </li>
                        @empty
                          <li class="bg-secondary-300">
                            <div class="d-flex align-items-center justify-content-between">
                              <h6 class="text-secondary-dark f-w-600 mb-0">اعلانی یافت نشد</h6>
                            </div>
                          </li>
                        @endforelse
                      </ul>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </main>
        </div>
