@props(['project'])
<div>
    <button class="text-white font-bold tracking-wide uppercase px-8 py-3 rounded-[4px] transition duration-300 ease-in-out
        @if($project->status->value == 'open')
            bg-[#5354FD] hover:bg-[#1f20a6]
        @else
            bg-gray-400 cursor-not-allowed
        @endif" wire:click="$set('modal', true)" @if($project->status->value != 'open') disabled @endif
        >
        Enviar uma proposta
    </button>
    <x-proposals.form />
</div>