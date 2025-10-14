Table branches {
  id int [pk, increment]
  name varchar(255) [not null, note: 'نام شعبه، مثلاً 22 بهمن یا خوراسگان']
  address varchar(500) [note: 'آدرس شعبه']
  flexible_desk_capacity int [default: 0, note: 'تعداد کل میزهای منعطف در شعبه']
  created_at timestamp [default: `CURRENT_TIMESTAMP`]
  updated_at timestamp [default: `CURRENT_TIMESTAMP`]
  
  indexes {
    (id) [pk]
  }
}

Table users {
  id int [pk, increment]
  first_name varchar(255) [not null]
  last_name varchar(255) [not null]
  email varchar(255) [unique, not null]
  password varchar(255) [not null]
  role enum('user', 'admin', 'ceo') [not null, note: 'user, admin, ceo']
  phone varchar(20) [note: 'برای ارسال پیامک']
  created_at timestamp [default: `CURRENT_TIMESTAMP`]
  updated_at timestamp [default: `CURRENT_TIMESTAMP`]
  
  indexes {
    (id) [pk]
    (email) [unique]
  }
}

Table subscription_types {
  id int [pk, increment]
  name varchar(255) [not null, note: 'نوع اشتراک: میز ثابت، میز منعطف، میز روزانه، کمد']
  price decimal(10,2) [not null]
  duration_days int [note: 'مدت اشتراک، مثلاً 30 برای ماهانه یا 1 برای روزانه']
  requires_admin_approval boolean [default: false, note: 'آیا نیاز به تأیید مدیر دارد؟']
  created_at timestamp [default: `CURRENT_TIMESTAMP`]
  updated_at timestamp [default: `CURRENT_TIMESTAMP`]
  
  indexes {
    (id) [pk]
  }
}

Table subscriptions {
  id int [pk, increment]
  user_id int [ref: > users.id, not null]
  subscription_type_id int [ref: > subscription_types.id, not null]
  branch_id int [ref: > branches.id, not null]
  start_datetime timestamp [not null]
  end_datetime timestamp [not null]
  status enum('pending', 'active', 'expired') [not null, note: 'pending, active, expired']
  total_price decimal(10,2) [not null]
  discount_id int [ref: > discounts.id]
  created_at timestamp [default: `CURRENT_TIMESTAMP`]
  updated_at timestamp [default: `CURRENT_TIMESTAMP`]
  
  indexes {
    (id) [pk]
    (user_id, branch_id) [name: 'subscriptions_user_id_branch_id']
  }
}

Table desks {
  id int [pk, increment]
  branch_id int [ref: > branches.id, not null]
  desk_number varchar(50) [not null, note: 'شماره میز در شعبه']
  status enum('available', 'reserved') [not null, note: 'available, reserved']
  user_id int [ref: > users.id]
  subscription_id int [ref: > subscriptions.id]
  created_at timestamp [default: `CURRENT_TIMESTAMP`]
  updated_at timestamp [default: `CURRENT_TIMESTAMP`]
  
  indexes {
    (branch_id, desk_number) [name: 'desks_branch_id_desk_number', unique]
  }
}

Table lockers {
  id int [pk, increment]
  branch_id int [ref: > branches.id, not null]
  locker_number varchar(50) [not null, note: 'شماره کمد در شعبه']
  status enum('available', 'reserved') [not null, note: 'available, reserved']
  user_id int [ref: > users.id]
  subscription_id int [ref: > subscriptions.id]
  created_at timestamp [default: `CURRENT_TIMESTAMP`]
  updated_at timestamp [default: `CURRENT_TIMESTAMP`]
  
  indexes {
    (branch_id, locker_number) [name: 'lockers_branch_id_locker_number', unique]
  }
}

Table private_rooms {
  id int [pk, increment]
  branch_id int [ref: > branches.id, not null]
  room_number varchar(50) [not null, note: 'شماره اتاق اختصاصی']
  status enum('available', 'reserved') [not null, note: 'available, reserved']
  user_id int [ref: > users.id]
  subscription_id int [ref: > subscriptions.id]
  created_at timestamp [default: `CURRENT_TIMESTAMP`]
  updated_at timestamp [default: `CURRENT_TIMESTAMP`]
  
  indexes {
    (branch_id, room_number) [name: 'private_rooms_branch_id_room_number', unique]
  }
}

Table meeting_rooms {
  id int [pk, increment]
  branch_id int [ref: > branches.id, not null]
  room_number varchar(50) [not null, note: 'شماره اتاق جلسه']
  price_per_hour decimal(10,2) [note: 'هزینه رزرو اتاق به ازای هر ساعت']
  created_at timestamp [default: `CURRENT_TIMESTAMP`]
  updated_at timestamp [default: `CURRENT_TIMESTAMP`]
  
  indexes {
    (branch_id, room_number) [name: 'meeting_rooms_branch_id_room_number', unique]
  }
}

Table meeting_reservations {
  id int [pk, increment]
  user_id int [ref: > users.id, not null]
  meeting_room_id int [ref: > meeting_rooms.id, not null]
  reservation_date timestamp [not null]
  duration_hours int [not null, note: 'مدت جلسه: 1، 2 یا 3 ساعت']
  status enum('pending', 'confirmed', 'cancelled') [not null, note: 'pending, confirmed, cancelled']
  created_at timestamp [default: `CURRENT_TIMESTAMP`]
  updated_at timestamp [default: `CURRENT_TIMESTAMP`]
  
  indexes {
    (reservation_date) [name: 'meeting_reservations_reservation_date']
  }
}

Table flexible_desk_reservations {
  id int [pk, increment]
  user_id int [ref: > users.id, not null]
  branch_id int [ref: > branches.id, not null]
  reservation_date timestamp [not null]
  status enum('confirmed', 'cancelled') [not null, note: 'confirmed, cancelled']
  created_at timestamp [default: `CURRENT_TIMESTAMP`]
  updated_at timestamp [default: `CURRENT_TIMESTAMP`]
  
  indexes {
    (reservation_date) [name: 'flexible_desk_reservations_reservation_date']
  }
}

Table subscription_addons {
  id int [pk, increment]
  subscription_id int [ref: > subscriptions.id, not null]
  addon_type varchar(255) [not null, note: 'نوع افزودنی، مثلاً locker']
  addon_price decimal(10,2) [not null]
  created_at timestamp [default: `CURRENT_TIMESTAMP`]
  updated_at timestamp [default: `CURRENT_TIMESTAMP`]
  
  indexes {
    (id) [pk]
  }
}

Table discounts {
  id int [pk, increment]
  user_id int [ref: > users.id]
  subscription_type_id int [ref: > subscription_types.id, not null]
  discount_percentage decimal(5,2) [not null, note: 'درصد تخفیف']
  valid_until timestamp [note: 'تاریخ انقضای تخفیف']
  created_at timestamp [default: `CURRENT_TIMESTAMP`]
  updated_at timestamp [default: `CURRENT_TIMESTAMP`]
  
  indexes {
    (id) [pk]
  }
}

Table notifications {
  id int [pk, increment]
  user_id int [ref: > users.id, not null]
  message varchar(500) [not null]
  type enum('sms', 'email', 'system') [not null, note: 'sms, email, system']
  event_type enum('subscription_created', 'subscription_approved', 'subscription_expiring', 'subscription_expired') [not null, note: 'subscription_created, subscription_approved, subscription_expiring, subscription_expired']
  status enum('sent', 'pending', 'failed') [not null, note: 'sent, pending, failed']
  external_id varchar(100) [note: 'شناسه پیامک در سرویس خارجی']
  created_at timestamp [default: `CURRENT_TIMESTAMP`]
  updated_at timestamp [default: `CURRENT_TIMESTAMP`]
  
  indexes {
    (user_id) [name: 'notifications_user_id']
  }
}

Table transactions {
  id int [pk, increment]
  user_id int [ref: > users.id, not null]
  subscription_id int [ref: > subscriptions.id]
  meeting_reservation_id int [ref: > meeting_reservations.id]
  branch_id int [ref: > branches.id, not null]
  amount decimal(10,2) [not null, note: 'مبلغ تراکنش']
  payment_method enum('online', 'card', 'cash') [not null, note: 'online, card, cash']
  transaction_code varchar(100) [unique, note: 'کد رهگیری پرداخت از درگاه']
  status enum('pending', 'completed', 'failed') [not null, note: 'pending, completed, failed']
  created_at timestamp [default: `CURRENT_TIMESTAMP`]
  updated_at timestamp [default: `CURRENT_TIMESTAMP`]
  
  indexes {
    (user_id) [name: 'transactions_user_id']
    (transaction_code) [unique]
  }
}

Table permissions {
  id int [pk, increment]
  role enum('user', 'admin', 'ceo') [not null, note: 'user, admin, ceo']
  permission varchar(255) [not null, note: 'مثلاً add_user, add_admin']
  created_at timestamp [default: `CURRENT_TIMESTAMP`]
  updated_at timestamp [default: `CURRENT_TIMESTAMP`]
  
  indexes {
    (id) [pk]
  }
}

// روابط اضافی (اگر نیاز به many-to-many باشد، می‌توانید Table واسط اضافه کنید، اما در این طراحی one-to-many کافی است)
