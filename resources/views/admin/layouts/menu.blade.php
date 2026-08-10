<div class="menu-container flex-grow-1">
    <ul id="menu" class="menu">
        <li>
            <a href="/dashboard" data-href="/dashboard">
                <i data-acorn-icon="home-garage" class="icon" data-acorn-size="18"></i>
                <span class="label fw-bold">Dashboards</span>
            </a>
        </li>
        @role('dosen|operator|finance|admin|guest')
        <li>
            <a href="{{ route('payments.index') }}" data-href="#">
                <i data-acorn-icon="wallet" class="icon" data-acorn-size="18"></i>
                <span class="label fw-bold">@lang('dashboard.payment.titles')</span>
            </a>
        </li>
        @endrole
        @role('asesor')
            <li>
                <a href="{{ route('finances.index') }}" data-href="#">
                    <i data-acorn-icon="database" class="icon" data-acorn-size="18"></i>
                    <span class="label fw-bold">Mater Data</span>
                </a>
                <ul id="pages">
                    <li>
                        <a href="{{ route('data-dosen-assessor') }}">
                            <span class="label">Penilaian Dosen Asesor</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('asset-keuangan') }}">
                            <span class="label">Asset Keuangan</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                @php
                    $assignment_letter_count = \App\Models\AssignmentLetter::where('assessor_id', auth()->user()->id)->count();
                @endphp
                <a href="{{ route('list-assignment-letter') }}" data-href="{{ route('list-assignment-letter') }}">
                    <i data-acorn-icon="file-text" class="icon" data-acorn-size="18"></i>
                    <span class="label fw-bold">Surat Tugas @if($assignment_letter_count > 0)<span class="badge bg-danger fw-bold">{{ $assignment_letter_count }}</span>@endif</span>
                </a>
            </li>
        @endrole
        @role('admin')
            <li>
                <a href="#pages" data-href="#">
                    <i data-acorn-icon="database" class="icon" data-acorn-size="18"></i>
                    <span class="label fw-bold">Keuangan</span>
                </a>
                <ul id="pages">
                    <li>
                        <a href="{{ route('finances.index', ['category' => 'dosen']) }}">
                            <span class="label">Laporan Pembayaran</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('finances.index', ['category' => 'assessor']) }}">
                            <span class="label">Keuangan Assesor</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('assessor-fee.index') }}">
                            <span class="label">Bea Assesor</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('rekening.index') }}">
                            <span class="label">Rekening</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#masterdata" data-href="#">
                    <i data-acorn-icon="database" class="icon" data-acorn-size="18"></i>
                    <span class="label fw-bold">Mater Data</span>
                </a>
                <ul id="masterdata">
                    <li>
                        <a href="{{ route('data-user', ['category' => 'dosen']) }}">
                            <span class="label">Data Dosen</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('data-user', ['category' => 'asesor']) }}">
                            <span class="label">Data Assesor</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('assignment-letters.index') }}">
                            <span class="label">Surat Tugas</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#admin" data-href="#">
                    <i data-acorn-icon="settings-2" class="icon" data-acorn-size="18"></i>
                    <span class="label fw-bold">Admin</span>
                </a>
                <ul id="admin">
                    <li>
                        <a href="{{ route('users.index') }}">
                            <span class="label">Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.index') }}">
                            <span class="label">Settings</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endrole
        @role('operator')
            <li>
                <a href="#" data-href="#">
                    <i data-acorn-icon="database" class="icon" data-acorn-size="18"></i>
                    <span class="label fw-bold">Data</span>
                </a>
                <ul id="pages">
                    <li>
                        <a href="{{ route('assignment-letters.index') }}">
                            <span class="label">Surat Tugas</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endrole
        <li>
            <a href="/manual_book" data-href="/manual_book">
                <i data-acorn-icon="file-text" class="icon" data-acorn-size="18"></i>
                <span class="label fw-bold">Buku Panduan</span>
            </a>
        </li>
    </ul>
</div>
