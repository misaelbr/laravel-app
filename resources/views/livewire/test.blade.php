<div>
    <h1>Busca</h1>
    <input wire:model.live="search" />

    <br />

    <ul>
        @foreach($users as $user)
            <li>{{ $user->name }}</li>
        @endforeach
</div>
