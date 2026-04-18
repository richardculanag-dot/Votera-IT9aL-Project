<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #111;
        }
        h1 {
            font-size: 18px;
            margin: 0 0 6px 0;
        }
        h2 {
            font-size: 14px;
            margin: 14px 0 6px 0;
        }
        .muted { color: #555; }
        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .summary td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            vertical-align: top;
        }
        .summary td.label {
            width: 30%;
            font-weight: 700;
        }
        table.results {
            width: 100%;
            border-collapse: collapse;
        }
        table.results th, table.results td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        table.results th {
            background: #f5f5f5;
        }
        .rank {
            width: 60px;
            text-align: center !important;
            font-weight: 700;
        }
        .winner {
            font-weight: 800;
        }
        .page-break {
            page-break-after: always;
        }
        .generated-at {
            margin-top: 18px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Election Results Report</h1>
    <p class="muted">
        <strong>Election:</strong> {{ $election->title }}<br>
        <strong>Status:</strong> {{ ucfirst($election->status) }}
    </p>

    <table class="summary">
        <tr>
            <td class="label">Total Voters</td>
            <td>{{ $totalVoters }}</td>
            <td class="label">Votes Cast</td>
            <td>{{ $totalVotesCast }}</td>
        </tr>
        <tr>
            <td class="label">Turnout</td>
            <td>{{ $turnout }}%</td>
            <td class="label">Positions</td>
            <td>{{ $positions->count() }}</td>
        </tr>
    </table>

    @foreach($positions as $position)
        @php
            $candidates = $position->candidates->sortByDesc('votes_count')->values();
            $totalVotesForPosition = $candidates->sum('votes_count');
        @endphp

        <h2>{{ $position->name }}</h2>
        <p class="muted">Total votes: {{ $totalVotesForPosition }}</p>

        <table class="results">
            <thead>
                <tr>
                    <th class="rank">Rank</th>
                    <th>Candidate</th>
                    <th style="width:120px;">Votes</th>
                    <th style="width:120px;">Percent</th>
                </tr>
            </thead>
            <tbody>
                @foreach($candidates as $i => $c)
                    @php
                        $pct = $totalVotesForPosition > 0
                            ? round(($c->votes_count / $totalVotesForPosition) * 100, 1)
                            : 0;
                        $isWinner = ($i === 0) && ($totalVotesForPosition > 0);
                    @endphp
                    <tr>
                        <td class="rank">
                            @if($isWinner)
                                <span class="winner">{{ $election->status === 'ended' ? 'Winner' : 'As of now' }}</span>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </td>
                        <td>
                            <div style="font-weight:700;">
                                {{ $c->name }}
                            </div>
                            @if($c->partylist)
                                <div class="muted">{{ $c->partylist }}</div>
                            @endif
                            @if($c->grade_level)
                                <div class="muted">{{ $c->grade_level }}</div>
                            @endif
                        </td>
                        <td>{{ $c->votes_count }}</td>
                        <td>{{ $pct }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div class="generated-at">
        Generated at: {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>
</html>

