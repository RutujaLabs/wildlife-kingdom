@extends('admin.layout')

@section('title', 'Animals')
@section('page-title', 'Animals')

@push('styles')
<style>
    .page-head { display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 22px; }
    .page-head h2 { margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--forest); }
    .page-head p { margin: 4px 0 0; color: var(--muted); font-size: .92rem; }
    .btn-add { gap: 8px; padding: 12px 22px; }
    .btn-add svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round; }

    .list-panel { padding: 0; overflow: hidden; }
    .toolbar { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; padding: 18px 24px; border-bottom: 1px solid var(--line); }
    .toolbar .count { color: var(--muted); font-size: .92rem; }
    .toolbar .count strong { color: var(--forest); }
    .search-box { position: relative; width: 100%; max-width: 280px; }
    .search-box svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; fill: none; stroke: #9a948a; stroke-width: 2; stroke-linecap: round; }
    .search-box input { padding-left: 40px; border-radius: 999px; background: #faf7f2; }

    .animals-table { width: 100%; border-collapse: collapse; }
    .animals-table th { background: #faf7f2; color: #6b655c; font-size: .82rem; font-weight: 600; padding: 14px 24px; border-bottom: 1px solid var(--line); white-space: nowrap; }
    .animals-table td { padding: 16px 24px; border-bottom: 1px solid var(--line); vertical-align: middle; }
    .animals-table tbody tr { transition: background .15s; }
    .animals-table tbody tr:hover { background: #fdfaf5; }
    .animals-table tbody tr:last-child td { border-bottom: 0; }
    .animals-table th.col-actions, .animals-table td.col-actions { text-align: right; }

    .animal-cell { display: flex; align-items: center; gap: 14px; }
    .animal-avatar { width: 44px; height: 44px; border-radius: 12px; background: var(--forest); color: var(--gold); display: grid; place-items: center; font-weight: 700; font-size: 1.05rem; flex-shrink: 0; }
    .animal-name { font-weight: 600; color: var(--ink); }
    .sci { font-style: italic; color: #5d5a55; }
    .muted-dash { color: #b3ada3; }

    .badge { display: inline-block; font-size: .8rem; font-weight: 600; padding: 5px 14px; border-radius: 999px; white-space: nowrap; }
    .badge-habitat { background: #eef0d9; color: #55601a; }
    .badge-published { background: #e3f1e9; color: #14633f; }
    .badge-draft { background: #f0ece4; color: #6b655c; }

    .actions { display: inline-flex; align-items: center; gap: 8px; }
    .actions form { margin: 0; }
    .btn-sm { padding: 8px 16px; font-size: .86rem; gap: 6px; white-space: nowrap; }
    .btn-sm svg { width: 14px; height: 14px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .btn-edit { background: #fcefdc; color: #8a5413; }
    .btn-edit:hover { background: var(--gold); color: #000; }
    .btn-delete { background: #fbe9e9; color: #a12626; }
    .btn-delete:hover { background: #a12626; color: #fff; }

    .empty-row td { text-align: center; padding: 48px 24px; color: var(--muted); }
    .no-match { display: none; }

    @media (max-width: 760px) {
        .toolbar, .animals-table th, .animals-table td { padding-left: 16px; padding-right: 16px; }
        .search-box { max-width: 100%; }
        .hide-sm { display: none; }
    }
</style>
@endpush

@section('content')
<div class="page-head">
    <div>
        <h2>Animal management</h2>
        <p>Add, update and remove the animals shown on your website.</p>
    </div>
    <a href="{{ route('admin.animals.create') }}" class="btn btn-primary btn-add">
        <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Add animal
    </a>
</div>

<div class="panel list-panel">
    <div class="toolbar">
        <div class="count"><strong id="visibleCount">{{ count($animals) }}</strong> {{ Str::plural('animal', count($animals)) }}</div>
        <div class="search-box">
            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" id="animalSearch" placeholder="Search animals" autocomplete="off">
        </div>
    </div>

    <div class="table-wrap">
        <table class="animals-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th class="hide-sm">Scientific name</th>
                    <th>Habitat</th>
                    <th>Status</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody id="animalRows">
                @forelse($animals as $animal)
                    <tr class="animal-row">
                        <td>
                            <div class="animal-cell">
                                <div class="animal-avatar">{{ strtoupper(substr($animal->name, 0, 1)) }}</div>
                                <span class="animal-name">{{ $animal->name }}</span>
                            </div>
                        </td>
                        <td class="hide-sm">
                            @if($animal->scientific_name)
                                <span class="sci">{{ $animal->scientific_name }}</span>
                            @else
                                <span class="muted-dash">Not added</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $habitatValue = $animal->habitat;
                                $habitatName = is_object($habitatValue) ? ($habitatValue->name ?? null) : $habitatValue;
                            @endphp
                            @if(!empty($habitatName))
                                <span class="badge badge-habitat">{{ $habitatName }}</span>
                            @else
                                <span class="muted-dash">Not assigned</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ strtolower($animal->status) === 'published' ? 'badge-published' : 'badge-draft' }}">{{ ucfirst($animal->status) }}</span>
                        </td>
                        <td class="col-actions">
                            <div class="actions">
                                <a href="{{ route('admin.animals.edit', $animal) }}" class="btn btn-sm btn-edit">
                                    <svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.animals.destroy', $animal) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Delete this animal?')">
                                        <svg viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="m6 6 1 14h10l1-14"/></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="5">No animals yet. Click "Add animal" to create the first one.</td>
                    </tr>
                @endforelse
                <tr class="empty-row no-match" id="noMatch">
                    <td colspan="5">No animals match your search.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    (function () {
        var input = document.getElementById('animalSearch');
        var rows = document.querySelectorAll('.animal-row');
        var counter = document.getElementById('visibleCount');
        var noMatch = document.getElementById('noMatch');
        if (!input || !rows.length) return;
        input.addEventListener('input', function () {
            var q = input.value.trim().toLowerCase();
            var shown = 0;
            rows.forEach(function (row) {
                var match = row.textContent.toLowerCase().indexOf(q) !== -1;
                row.style.display = match ? '' : 'none';
                if (match) shown++;
            });
            counter.textContent = shown;
            noMatch.style.display = shown === 0 ? 'table-row' : 'none';
        });
    })();
</script>
@endsection