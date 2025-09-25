@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Your Profile</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <tbody>
                {{-- Name --}}
                <tr>
                    <th>Name</th>
                    <td>
                        <span class="editable" data-field="name">{{ auth()->user()->name }}</span>
                    </td>
                </tr>

                {{-- Email --}}
                <tr>
                    <th>Email</th>
                    <td>
                        <div id="email-container">
                            <span>{{ auth()->user()->email }}</span>

                            @if(!auth()->user()->is_email_verified)
                                <form method="POST" action="{{ route('profile.send_otp') }}" class="d-inline ms-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" id="verify-email-btn">Verify
                                        Email</button>
                                </form>
                            @else
                                <span class="badge bg-success ms-2" id="verified-badge">Verified</span>
                            @endif
                        </div>
                    </td>
                </tr>


                {{-- Password --}}
                <tr>
                    <th>Password</th>
                    <td>
                        <span class="editable" data-field="password">********</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger mt-3">Logout</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.editable').forEach(el => {
            el.addEventListener('click', function () {
                let currentValue = this.textContent.trim();
                let field = this.dataset.field;

                let input = document.createElement('input');
                input.type = field === 'password' ? 'password' : 'text';
                input.value = field === 'password' ? '' : currentValue;
                input.classList.add('form-control', 'd-inline');
                input.style.width = 'auto';
                this.replaceWith(input);
                input.focus();

                input.addEventListener('blur', function () {
                    let newValue = this.value;

                    fetch("{{ route('profile.update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ field: field, value: newValue })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                input.replaceWith(el);
                                el.textContent = field === 'password' ? '********' : newValue;
                            } else {
                                alert('Update failed!');
                                input.replaceWith(el);
                            }
                        })
                        .catch(err => {
                            alert('Error updating field');
                            input.replaceWith(el);
                        });
                });
            });
        });
    </script>
@endsection