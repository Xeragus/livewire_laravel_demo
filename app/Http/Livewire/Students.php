<?php

namespace App\Http\Livewire;

use App\Models\Student;
use Livewire\Component;

class Students extends Component
{
    public $ids;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;

    public function render()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $students = Student::orderBy('id', 'DESC')->get();

        return view('livewire.students', ['students' => $students]);
    }

    public function store()
    {
        $validatedData = $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required'
        ]);

        Student::create($validatedData);

        session()->flash('message', 'Student created successfully');

        $this->resetForm();
        $this->emit('studentAdded');
    }

    public function resetForm()
    {
        $this->first_name = '';
        $this->last_name = '';
        $this->email = '';
        $this->phone = '';
    }

    public function edit($id)
    {
        $student = Student::where('id', $id)->first();

        $this->ids = $student->id;
        $this->first_name = $student->first_name;
        $this->last_name = $student->last_name;
        $this->email = $student->email;
        $this->phone = $student->phone;
    }

    public function update()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required'
        ]);

        if ($this->ids) {
            $student = Student::find($this->ids);

            $student->update([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone
            ]);

            session()->flash('message', 'Student updated successfully');

            $this->resetForm();
            $this->emit('studentUpdated');
        }
    }

    public function delete($id)
    {
        if ($id) {
            Student::where('id', $id)->delete();

            session()->flash('message', 'Student deleted successfully');
        }
    }
}
