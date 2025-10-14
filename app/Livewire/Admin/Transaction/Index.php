<?php

namespace App\Livewire\Admin\Transaction;

use App\Models\Transaction;
use App\Models\Branch;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('لیست تراکنش‌ها')]
class Index extends Component
{
    use WithPagination;

    public ?int $branch_id = null;
    public ?string $status = null; // pending/completed/failed
    public ?string $payment_method = null; // online/card/cash
    public string $search = '';
    public $branches = [];

    public function mount(): void
    {
        $this->branches = Branch::orderBy('name')->get();
    }

    public function updatedBranchId(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }
    public function updatedPaymentMethod(): void { $this->resetPage(); }
    public function updatedSearch(): void { $this->resetPage(); }

    public function render()
    {
        $query = Transaction::query()->with(['user','branch']);
        if ($this->branch_id) {
            $query->where('branch_id', $this->branch_id);
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }
        if ($this->payment_method) {
            $query->where('payment_method', $this->payment_method);
        }
        if (trim($this->search) !== '') {
            $like = '%' . trim($this->search) . '%';
            $query->where(function($q) use ($like) {
                $q->where('transaction_code','like',$like)
                  ->orWhereHas('user', function($uq) use ($like){
                      $uq->where('name','like',$like)
                         ->orWhere('email','like',$like)
                         ->orWhere('phone','like',$like)
                         ->orWhere('first_name','like',$like)
                         ->orWhere('last_name','like',$like);
                  });
            });
        }

        $transactions = $query->orderBy('id','desc')->paginate(10);

        return view('livewire.admin.transaction.index', [
            'transactions' => $transactions,
        ]);
    }

    public function delete(int $id): void
    {
        $trx = Transaction::find($id);
        if (!$trx) {
            return;
        }
        $trx->delete();
        session()->flash('success', 'تراکنش با موفقیت حذف شد.');
        $this->resetPage();
    }
}