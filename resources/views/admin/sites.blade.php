<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Sites</title>
    <style>
        body { font-family: Arial; padding: 40px; background:#f9fafb; }
        .card { background:white; padding:20px; border:1px solid #e5e7eb; border-radius:10px; margin-bottom:20px; }
        table { width: 100%; border-collapse: collapse; background:white; }
        th, td { padding: 12px; border: 1px solid #e5e7eb; text-align:left; vertical-align: top; }
        th { background: #f3f4f6; }
        input { padding:10px; width: 100%; box-sizing:border-box; }
        button { padding:10px 14px; cursor:pointer; }
        .success { background:#d1fae5; padding:10px; margin-bottom:15px; border-radius:6px; }
        code { display:block; background:#111827; color:#f9fafb; padding:12px; border-radius:8px; white-space:pre-wrap; }
        .row { display:grid; grid-template-columns: 1fr 1fr auto; gap:10px; align-items:end; }
        .small { color:#6b7280; font-size: 12px; margin-top:6px; }
    </style>
</head>
<body>

<h2>🌍 Sites (Multi-client)</h2>

@if(session('success'))
    <div class="success">{{ session('success') }}</div>
@endif

<div class="card">
    <h3>Create a Site</h3>
    <form method="POST" action="/admin/sites">
        @csrf
        <div class="row">
            <div>
                <label>Site name</label>
                <input name="name" placeholder="Ex: COPAG Careers" required>
            </div>
            <div>
                <label>Domain (optional)</label>
                <input name="domain" placeholder="Ex: copag.ma">
                <div class="small">Just for info (later we can enforce it).</div>
            </div>
            <div>
                <button type="submit">Create</button>
            </div>
        </div>
    </form>
</div>

<div class="card">
    <h3>Sites List</h3>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Domain</th>
            <th>Site Key</th>
            <th>Embed Code</th>
        </tr>

        @foreach($sites as $site)
        <tr>
            <td>{{ $site->id }}</td>
            <td>{{ $site->name }}</td>
            <td>{{ $site->domain ?? '-' }}</td>
            <td><b>{{ $site->site_key }}</b></td>
            <td>
                <code>&lt;script src="{{ config('app.url') }}/widget.js" data-site-key="{{ $site->site_key }}"&gt;&lt;/script&gt;</code>
                <div class="small">
                    Paste this into ANY website (before &lt;/body&gt;).
                </div>
            </td>
        </tr>
        @endforeach
    </table>
</div>

</body>
</html>
