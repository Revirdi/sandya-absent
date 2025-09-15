<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .weekend {
            background-color: rgb(212, 76, 76);
        }
    </style>
</head>

<body>
    <h2>Attendance Report</h2>
    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Month:</strong> {{ \Carbon\Carbon::parse($daysInMonth[0]['date'])->translatedFormat('F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Day</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Status</th>
                <th>Work Time</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($daysInMonth as $day)
                @php
                    $isWeekend = $day['is_weekend'];
                    $isHoliday = $day['is_holiday']['status'];
                    $attendance = $day['attendance'];
                @endphp
                <tr class="{{ $isWeekend || $isHoliday ? 'weekend' : '' }}">
                    <td>{{ $day['date'] }}</td>
                    <td>{{ $day['day_name'] }}</td>
                    <td>{{ $day['attendance']->check_in_time ?? '-' }}</td>
                    <td>{{ $day['attendance']->check_out_time ?? '-' }}</td>
                    <td>
                        @if ($attendance)
                            Present
                        @elseif ($isHoliday)
                            {{ $day['is_holiday']['name'] }}
                        @elseif ($isWeekend)
                            Weekend
                        @else
                            Absent
                        @endif
                    </td>
                    <td>
                        @if (isset($day['working_minutes']))
                            {{ rtrim(rtrim(number_format($day['working_minutes'], 2, '.', ''), '0'), '.') }} minutes
                        @else
                        @endif
                    </td>
                    <td>{{ $day['attendance']->remarks ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
