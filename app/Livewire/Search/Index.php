<?php

namespace App\Livewire\Search;

use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    #[Url]
    public string $query = '';

    public $results = [];

    public function updatedQuery()
    {
        if (strlen($this->query) > 2) {
            $this->results = User::search($this->query)->take(10)->get();
        } else {
            $this->results = [];
        }
    }

    public function render()
    {
        return view('livewire.search.index');
    }
}
