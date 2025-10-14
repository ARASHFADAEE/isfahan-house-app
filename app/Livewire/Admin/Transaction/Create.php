<?php

namespace App\Livewire\Admin\Transaction;

use App\Models\Transaction;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('ایجاد تراکنش')]
class Create extends Component
{
    public ?int $user_id = null;
    public string $user_search = '';
    public array $user_results = [];

    public ?int $branch_id = null;
    public $amount = '';
    public string $payment_method = 'online';
    public string $status = 'completed';

    public ?int $subscription_id = null;
    public ?int $meeting_reservation_id = null;

    public $branches = [];

    public function mount(): void
    {
        $this->branches = Branch::orderBy('name')->get();
    }

    protected function rules(): array
    {
        return [
            'user_id' => ['required','integer', Rule::exists('users','id')],
            'branch_id' => ['required','integer', Rule::exists('branches','id')],
            'amount' => ['required','numeric','min:0'],
            'payment_method' => ['required', Rule::in(['online','card','cash'])],
            'status' => ['required', Rule::in(['pending','completed','failed'])],
            'subscription_id' => ['nullable','integer', Rule::exists('subscriptions','id')],
            'meeting_reservation_id' => ['nullable','integer', Rule::exists('meeting_reservations','id')],
        ];
    }

    public function updatedUserSearch(): void
    {
        $term = trim($this->user_search);
        if ($term === '') { $this->user_results = []; return; }
        $like = "%$term%";
        $this->user_results = User::query()
            ->where(function($q) use ($like){
                $q->where('name','like',$like)
                  ->orWhere('email','like',$like)
                  ->orWhere('phone','like',$like)
                  ->orWhere('first_name','like',$like)
                  ->orWhere('last_name','like',$like);
            })
            ->orderBy('id','desc')
            ->limit(10)
            ->get()
            ->map(function($u){
                return [
                    'id' => $u->id,
                    'label' => trim(($u->first_name.' '.$u->last_name)) ?: ($u->name ?? '-') ,
                    'email' => $u->email,
                    'phone' => $u->phone,
                ];
            })
            ->toArray();
    }

    public function selectUser(int $id): void
    {
        $this->user_id = $id;
        $this->user_search = '';
        $this->user_results = [];
    }

    public function save(): void
    {
        $this->validate();

        $code = 'TRX-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));

        Transaction::create([
            'user_id' => $this->user_id,
            'subscription_id' => $this->subscription_id,
            'meeting_reservation_id' => $this->meeting_reservation_id,
            'branch_id' => $this->branch_id,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'transaction_code' => $code,
            'status' => $this->status,
        ]);

        session()->flash('success', 'تراکنش با موفقیت ثبت شد.');
        redirect()->route('admin.transactions.index');
    }

    public function render()
    {
        return view('livewire.admin.transaction.create');
    }
}