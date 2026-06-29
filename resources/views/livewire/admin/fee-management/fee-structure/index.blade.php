<div>
    <div class="container-fluid py-4">

    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">Fee Structure Management</h4>

            <button
                class="btn btn-light"
                wire:click="resetForm"
                data-bs-toggle="modal"
                data-bs-target="#feeStructureModal">

                + Add Fee Structure

            </button>

        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-4">

                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search..."
                        wire:model.live="search">

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>
                            <th>Course</th>
                            <th>Fee Type</th>
                            <th>Amount</th>
                            <th>Installments</th>
                            <th>Status</th>
                            <th width="180">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($feeStructures as $fee)

                            <tr>

                                <td>{{ $fee->id }}</td>

                                <td>{{ $fee->course->name ?? '-' }}</td>

                                <td>{{ $fee->feeType->name ?? '-' }}</td>

                                <td>₹ {{ number_format($fee->amount,2) }}</td>

                                <td>{{ $fee->installments }}</td>

                                <td>

                                    @if($fee->status)

                                        <span class="badge bg-success">

                                            Active

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Inactive

                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <button
                                        class="btn btn-warning btn-sm"
                                        wire:click="edit({{ $fee->id }})"
                                        data-bs-toggle="modal"
                                        data-bs-target="#feeStructureModal">

                                        Edit

                                    </button>

                                    <button
                                        class="btn btn-danger btn-sm"
                                        wire:click="delete({{ $fee->id }})">

                                        Delete

                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center">

                                    No Fee Structure Found

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $feeStructures->links() }}

            </div>

        </div>

    </div>

</div>

<!-- Modal -->

<div
    wire:ignore.self
    class="modal fade"
    id="feeStructureModal"
    tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form wire:submit.prevent="save">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">

                        {{ $fee_structure_id ? 'Edit Fee Structure' : 'Add Fee Structure' }}

                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label>Course</label>

                        <select
                            class="form-select"
                            wire:model="course_id">

                            <option value="">Select Course</option>

                            @foreach($courses as $course)

                                <option value="{{ $course->id }}">

                                    {{ $course->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('course_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label>Fee Type</label>

                        <select
                            class="form-select"
                            wire:model="fee_type_id">

                            <option value="">Select Fee Type</option>

                            @foreach($feeTypes as $feeType)

                                <option value="{{ $feeType->id }}">

                                    {{ $feeType->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('fee_type_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label>Amount</label>

                        <input
                            type="number"
                            class="form-control"
                            wire:model="amount">

                        @error('amount')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label>Installments</label>

                        <input
                            type="number"
                            class="form-control"
                            wire:model="installments">

                        @error('installments')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label>Status</label>

                        <select
                            class="form-select"
                            wire:model="status">

                            <option value="1">Active</option>

                            <option value="0">Inactive</option>

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Close

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        {{ $fee_structure_id ? 'Update Fee Structure' : 'Save Fee Structure' }}

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
</div>
