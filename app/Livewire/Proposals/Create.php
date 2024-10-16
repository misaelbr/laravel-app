<?php

namespace App\Livewire\Proposals;

use App\Actions\ArrangePositions;
use App\Models\Project;
use App\Models\Proposal;
use App\Notifications\NewProposal;
use App\Notifications\PerdeuMane;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    public Project $project;
    public bool $modal = false;

    #[Rule(['required', 'email'])]
    public string $email = '';

    #[Rule(['required', 'numeric', 'min:1'])]
    public int $hours = 0;

    public bool $agree = false;

    public function render()
    {
        return view('livewire.proposals.create');
    }

    public function save()
    {


        $this->validate();

        if (!$this->agree) {
            $this->addError('agree', 'Você precisa concordar com os termos de uso.');

            return;
        }

        if ($this->project->status->value != 'open') {
            $this->addError('status', 'Este projeto não está mais aberto para propostas.');
            return;
        }


        DB::transaction(function () {

            $proposal = $this->project->proposals()->updateOrCreate(
                ['email' => $this->email],
                ['hours' => $this->hours],
            );

            $this->arrangePositions($proposal);


            $this->dispatch('proposal::created');

            $this->project->author->notify(new NewProposal($this->project));

            $this->modal = false;
        });
    }

    public function arrangePositions(Proposal $proposal)
    {

        $query = DB::select("
            SELECT 
                *,
                ROW_NUMBER() OVER (ORDER BY hours) AS new_position
            FROM 
                proposals
            WHERE 
                project_id = :project
        ", ['project' => $proposal->project_id]);

        $position = collect($query)->where('id', '=', $proposal->id)->first();
        $otherProposal = collect($query)->where('position', '=', $position->new_position)->first();
        if ($otherProposal) {
            $proposal->update(['position_status' => 'up']);
            $oProposal = Proposal::find($otherProposal->id);

            $oProposal->update(['position_status' => 'down']);
            $oProposal->notify(new PerdeuMane($this->project));
        }



        ArrangePositions::run($proposal->project_id);
    }
}
