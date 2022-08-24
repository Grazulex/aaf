<?php

namespace App\View\Components\Members;

use App\Models\Party;
use Illuminate\View\Component;

class Card extends Component
{
    public Party $party;
    public string $showDetails;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Party $party, string $showDetails)
    {
        $this->party = $party;
        $this->showDetails = $showDetails;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.members.card');
    }
}
