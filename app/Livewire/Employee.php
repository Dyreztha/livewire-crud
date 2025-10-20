<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employee as EmployeeModel;

class Employee extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $nama;
    public $email;
    public $alamat;
    public $search = '';
    public $employee_id;
    public $updateMode = false;
    public $employeeSelectedId = [];
    public $sortColumn = 'id';
    public $sortDirection = 'DESC';

    protected $listeners = ['delete'];

    public function render()
    {
        $employees = EmployeeModel::where('nama', 'like', '%'.$this->search.'%')
            ->orWhere('email', 'like', '%'.$this->search.'%')
            ->orWhere('alamat', 'like', '%'.$this->search.'%')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(5);

        return view('livewire.employee', [
            'employees' => $employees
        ]);
    }

    public function sortBy($column)
    {
        if ($this->sortColumn == $column) {
            $this->sortDirection = $this->sortDirection == 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'ASC';
        }
    }

    public function store()
    {
        $rules = [
            'nama' => 'required',
            'email' => 'required|email',
            'alamat' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak sesuai',
            'alamat.required' => 'Alamat wajib diisi',
        ];

        $this->validate($rules, $pesan);

        EmployeeModel::create([
            'nama' => $this->nama,
            'email' => $this->email,
            'alamat' => $this->alamat,
        ]);

        session()->flash('message', 'Data berhasil dimasukkan');

        $this->reset(['nama', 'email', 'alamat']);
    }

    public function edit($id)
    {
        $employee = EmployeeModel::findOrFail($id);
        $this->employee_id = $id;
        $this->nama = $employee->nama;
        $this->email = $employee->email;
        $this->alamat = $employee->alamat;
        $this->updateMode = true;
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->reset(['nama', 'email', 'alamat', 'employee_id']);
    }

    public function update()
    {
        $rules = [
            'nama' => 'required',
            'email' => 'required|email',
            'alamat' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak sesuai',
            'alamat.required' => 'Alamat wajib diisi',
        ];

        $this->validate($rules, $pesan);

        $employee = EmployeeModel::find($this->employee_id);
        $employee->update([
            'nama' => $this->nama,
            'email' => $this->email,
            'alamat' => $this->alamat,
        ]);

        session()->flash('message', 'Data berhasil diupdate');

        $this->cancel();
    }

    public function deleteConfirmation($id = '')
    {
        if ($id != '') {
            $this->employee_id = $id;
        }
        $this->dispatch('show-delete-confirmation');
    }

    public function delete()
    {
        if ($this->employeeSelectedId) {
            EmployeeModel::whereIn('id', $this->employeeSelectedId)->delete();
            $this->employeeSelectedId = [];
            session()->flash('message', 'Data terpilih berhasil dihapus');
        } else {
            $employee = EmployeeModel::find($this->employee_id);
            $employee->delete();
            session()->flash('message', 'Data berhasil dihapus');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function clear()
{
    $this->reset(['nama', 'email', 'alamat', 'employee_id']);
    $this->resetValidation();
}

}
