<?php

namespace App\Livewire\Admin\MeetingReservation;

use App\Models\MeetingReservation;
use App\Models\MeetingRoom;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('ایجاد رزرو اتاق جلسه')]
class Create extends Component
{
    public ?int $meeting_room_id = null;
    public ?int $user_id = null;
    public string $user_search = '';
    public $user_results = [];
    public ?int $duration_hours = null; // 1,2,3
    public string $reservation_date = ''; // Jalali from UI (e.g., 1403/07/01)
    public string $reservation_date_gregorian = ''; // YYYY-MM-DD
    public array $available_times = []; // ['08:00','09:00',...]
    public ?string $selected_time = null; // HH:MM

    public $meetingRooms = [];

    public function mount(): void
    {
        $this->meetingRooms = MeetingRoom::with('branch')->orderBy('id','desc')->get();
    }

    protected function rules(): array
    {
        return [
            'meeting_room_id' => ['required','integer','exists:meeting_rooms,id'],
            'user_id' => ['required','integer','exists:users,id'],
            'duration_hours' => ['required','integer','in:1,2,3'],
            'reservation_date_gregorian' => ['required','date'],
            'selected_time' => ['required','string'],
        ];
    }

    public function updatedMeetingRoomId(): void
    {
        // Reset downstream selections when room changes
        $this->user_id = null;
        $this->user_search = '';
        $this->user_results = [];
        $this->duration_hours = null;
        $this->reservation_date = '';
        $this->reservation_date_gregorian = '';
        $this->available_times = [];
        $this->selected_time = null;
        $this->calculateAvailableTimes();
    }

    public function updatedReservationDate($value = null): void
    {
        // Normalize Persian digits and convert Jalali YYYY/MM/DD to Gregorian
        $date = $this->faToEnDigits(trim($value ?? $this->reservation_date));
        if ($date === '') { $this->reservation_date_gregorian = ''; $this->available_times = []; return; }
        // Expect format YYYY/MM/DD or YYYY-MM-DD
        $date = str_replace('-', '/', $date);
        [$y,$m,$d] = array_pad(explode('/', $date), 3, null);
        if ($y && $m && $d) {
            // Convert from Jalali to Gregorian
            [$gy,$gm,$gd] = $this->jalaliToGregorian((int)$y,(int)$m,(int)$d);
            $this->reservation_date_gregorian = sprintf('%04d-%02d-%02d', $gy, $gm, $gd);
        } else {
            $this->reservation_date_gregorian = '';
        }
        $this->calculateAvailableTimes();
    }

    public function updatedDurationHours(): void
    {
        // Reset date/time upon duration change
        $this->reservation_date = '';
        $this->reservation_date_gregorian = '';
        $this->available_times = [];
        $this->selected_time = null;
        $this->calculateAvailableTimes();
    }

    public function updatedUserSearch($value): void
    {
        $term = trim($value ?? '');
        if ($term === '') { $this->user_results = []; return; }
        $like = '%' . $term . '%';
        $this->user_results = User::query()
            ->where(function($q) use ($like) {
                $q->where('name', 'like', $like)
                  ->orWhere('first_name', 'like', $like)
                  ->orWhere('last_name', 'like', $like)
                  ->orWhere('email', 'like', $like)
                  ->orWhere('phone', 'like', $like);
            })
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
    }

    public function chooseUser(int $id): void
    {
        $this->user_id = $id;
        $this->user_search = '';
        $this->user_results = [];
        // Reset downstream when user changes
        $this->duration_hours = null;
        $this->reservation_date = '';
        $this->reservation_date_gregorian = '';
        $this->available_times = [];
        $this->selected_time = null;
    }

    protected function workingHoursForDate(string $date): array
    {
        // Assuming $date is Gregorian YYYY-MM-DD
        $c = Carbon::parse($date);
        // Carbon dayOfWeek: 0=Sunday ... 6=Saturday
        $dow = $c->dayOfWeek;
        // Saturday(6) to Wednesday(3): 08:00-21:00
        // Thursday(4) and Friday(5): 08:00-18:00
        if (in_array($dow, [6,0,1,2,3])) { // Sat, Sun, Mon, Tue, Wed
            return ['start' => '08:00', 'end' => '21:00'];
        }
        // Thu (4), Fri (5)
        return ['start' => '08:00', 'end' => '18:00'];
    }

    protected function calculateAvailableTimes(): void
    {
        $this->available_times = [];
        if (!$this->meeting_room_id || !$this->reservation_date_gregorian || !$this->duration_hours) return;

        $hours = $this->workingHoursForDate($this->reservation_date_gregorian);
        // Build candidate start times on the hour
        [$startH, $startM] = explode(':', $hours['start']);
        [$endH, $endM] = explode(':', $hours['end']);
        $start = Carbon::parse($this->reservation_date_gregorian.' '.$hours['start']);
        $end = Carbon::parse($this->reservation_date_gregorian.' '.$hours['end']);

        // Fetch existing reservations for that room on the selected date
        $existing = MeetingReservation::where('meeting_room_id', $this->meeting_room_id)
            ->whereDate('reservation_date', $this->reservation_date_gregorian)
            ->get();

        // Build occupied intervals
        $occupied = [];
        foreach ($existing as $res) {
            $resStart = Carbon::parse($res->reservation_date);
            $resEnd = (clone $resStart)->addHours($res->duration_hours);
            $occupied[] = [$resStart, $resEnd];
        }

        $cursor = $start->copy();
        while ($cursor->lt($end)) {
            $candidateStart = $cursor->copy();
            $candidateEnd = $cursor->copy()->addHours($this->duration_hours);
            // Must fit within working hours
            if ($candidateEnd->gt($end)) {
                break;
            }

            // Check overlap with occupied
            $overlaps = false;
            foreach ($occupied as [$oStart, $oEnd]) {
                // overlap if start < oEnd and end > oStart
                if ($candidateStart->lt($oEnd) && $candidateEnd->gt($oStart)) {
                    $overlaps = true; break;
                }
            }

            if (!$overlaps) {
                $this->available_times[] = $candidateStart->format('H:00');
            }

            $cursor->addHour();
        }
    }

    public function refreshAvailableTimes(): void
    {
        $this->calculateAvailableTimes();
    }

    public function save(): void
    {
        $this->validate();
        $start = Carbon::parse($this->reservation_date_gregorian.' '.$this->selected_time);
        $end = (clone $start)->addHours($this->duration_hours);

        $conflict = false;
        DB::transaction(function() use (&$conflict, $start, $end) {
            // Lock existing reservations for this room on the same date to prevent races
            $existing = MeetingReservation::where('meeting_room_id', $this->meeting_room_id)
                ->whereIn('status', ['pending','confirmed'])
                ->whereDate('reservation_date', $start->toDateString())
                ->lockForUpdate()
                ->get();

            foreach ($existing as $res) {
                $resStart = Carbon::parse($res->reservation_date);
                $resEnd = (clone $resStart)->addHours($res->duration_hours);
                if ($start->lt($resEnd) && $end->gt($resStart)) {
                    $conflict = true;
                    // Do not proceed with creation inside transaction
                    return;
                }
            }

            // No conflict, create reservation
            MeetingReservation::create([
                'user_id' => $this->user_id,
                'meeting_room_id' => $this->meeting_room_id,
                'reservation_date' => $start,
                'duration_hours' => $this->duration_hours,
                'status' => 'confirmed',
            ]);
        });

        if ($conflict) {
            $this->addError('selected_time', 'این بازه زمانی هم‌پوشان است و همین الان رزرو شده. لطفاً بازه دیگری انتخاب کنید.');
            $this->calculateAvailableTimes();
            return;
        }

        session()->flash('success', 'رزرو اتاق جلسه ثبت شد.');
        // Optionally reset form
        $this->reset(['meeting_room_id','user_id','user_search','user_results','duration_hours','reservation_date','reservation_date_gregorian','available_times','selected_time']);
    }

    public function render()
    {
        return view('livewire.admin.meeting_reservation.create');
    }

    private function faToEnDigits(string $s): string
    {
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $arabic = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
        $english = ['0','1','2','3','4','5','6','7','8','9'];
        $s = str_replace($persian, $english, $s);
        $s = str_replace($arabic, $english, $s);
        return $s;
    }

    private function jalaliToGregorian(int $jy, int $jm, int $jd): array
    {
        $jy -= 979; $jm -= 1; $jd -= 1;
        $j_day_no = 365*$jy + intdiv($jy,33)*8 + intdiv(($jy%33 + 3),4);
        $j_month_days = [31,31,31,31,31,31,30,30,30,30,30,29];
        for ($i=0; $i<$jm; $i++) { $j_day_no += $j_month_days[$i]; }
        $j_day_no += $jd;

        $g_day_no = $j_day_no + 79;

        $gy = 1600 + 400*intdiv($g_day_no,146097);
        $g_day_no %= 146097;

        $leap = true;
        if ($g_day_no >= 36525) {
            $g_day_no--;
            $gy += 100*intdiv($g_day_no,36524);
            $g_day_no %= 36524;
            if ($g_day_no >= 365) { $g_day_no++; }
            else { $leap = false; }
        }

        $gy += 4*intdiv($g_day_no,1461);
        $g_day_no %= 1461;

        if ($g_day_no >= 366) {
            $leap = false;
            $g_day_no--;
            $gy += intdiv($g_day_no,365);
            $g_day_no %= 365;
        }

        $gm_day_counts = [0,31, ($leap?29:28),31,30,31,30,31,31,30,31,30,31];
        $gm = 0;
        for ($i=1; $i<=12; $i++) {
            if ($g_day_no < $gm_day_counts[$i]) { $gm = $i; break; }
            $g_day_no -= $gm_day_counts[$i];
        }
        $gd = $g_day_no + 1;
        return [$gy, $gm, $gd];
    }
}