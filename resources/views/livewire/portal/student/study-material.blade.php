<div>
    <div class="container-fluid py-4">

        <h3 class="fw-bold mb-4">📚 Study Material</h3>

        @if(! $student)

            <div class="alert alert-warning">
                Your login is not linked to a student registration yet. Please contact the administrator.
            </div>

        @elseif($materials->isEmpty())

            <div class="alert alert-info">
                Abhi tak koi study material upload nahi hui hai.
            </div>

        @else

            <div class="card shadow">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead class="table-dark">
                                <tr>
                                    <th>Title</th>
                                    <th>Subject</th>
                                    <th>Description</th>
                                    <th>Uploaded By</th>
                                    <th>File</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($materials as $material)
                                    <tr>
                                        <td>{{ $material->title }}</td>
                                        <td>{{ $material->subject?->name }}</td>
                                        <td>{{ $material->description }}</td>
                                        <td>{{ $material->teacher?->name ?? 'Admin' }}</td>
                                        <td>
                                            @if($material->file_path)
                                                <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                    ⬇ Download
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    <div class="mt-3">
                        {{ $materials->links() }}
                    </div>

                </div>

            </div>

        @endif

    </div>
</div>
